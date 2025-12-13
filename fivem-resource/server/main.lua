--[[
    FiveM Admin Panel - Server Script
    Handles player connections, ban checking, and panel communication
]]

local isAuthenticated = false
local serverPlayersData = {}

-- Debug print helper
local function DebugPrint(...)
    if Config.Debug then
        print("[Admin Panel]", ...)
    end
end

-- Collect player identifiers
local function GetPlayerIdentifiers(source)
    local identifiers = {}
    local numIds = GetNumPlayerIdentifiers(source)
    
    for i = 0, numIds - 1 do
        local id = GetPlayerIdentifier(source, i)
        if id then
            -- Parse identifier type
            local idType = id:match("^([^:]+):")
            
            if idType and Config.Identifiers[idType] then
                identifiers[idType] = id
            end
        end
    end
    
    return identifiers
end

-- Make HTTP request to panel
local function PanelRequest(endpoint, method, data, callback)
    local url = Config.PanelURL .. "/api/fivem" .. endpoint
    local headers = {
        ["Content-Type"] = "application/json",
        ["Authorization"] = "Bearer " .. Config.ServerToken
    }
    
    DebugPrint("Request:", method, url)
    
    PerformHttpRequest(url, function(statusCode, response, responseHeaders)
        DebugPrint("Response:", statusCode)
        
        if callback then
            if statusCode == 200 and response then
                local success, decoded = pcall(json.decode, response)
                if success then
                    callback(true, decoded)
                else
                    callback(false, { error = "Failed to decode JSON" })
                end
            else
                callback(false, { error = "HTTP " .. tostring(statusCode) })
            end
        end
    end, method, data and json.encode(data) or "", headers)
end

-- Authenticate with panel on resource start
CreateThread(function()
    Wait(5000) -- Wait for server to fully start
    
    PanelRequest("/auth", "POST", {}, function(success, data)
        if success and data.success then
            isAuthenticated = true
            print("[Admin Panel] Connected to panel: " .. Config.ServerName)
        else
            print("[Admin Panel] Failed to authenticate with panel!")
            print("[Admin Panel] Check your Config.ServerToken and Config.PanelURL")
        end
    end)
end)

-- Heartbeat loop
CreateThread(function()
    while true do
        Wait(Config.HeartbeatInterval * 1000)
        
        if isAuthenticated then
            local playerCount = #GetPlayers()
            
            PanelRequest("/heartbeat", "POST", {
                player_count = playerCount
            }, function(success, data)
                if not success then
                    DebugPrint("Heartbeat failed")
                end
            end)
        end
    end
end)

-- Player connecting event
AddEventHandler("playerConnecting", function(name, setKickReason, deferrals)
    local source = source
    deferrals.defer()
    
    Wait(0)
    deferrals.update("Checking player status...")
    
    -- Get identifiers
    local identifiers = GetPlayerIdentifiers(source)
    
    if not next(identifiers) then
        deferrals.done("Failed to retrieve player identifiers. Please restart FiveM.")
        return
    end
    
    DebugPrint("Player connecting:", name, json.encode(identifiers))
    
    -- Check ban status
    PanelRequest("/check-ban", "POST", {
        identifiers = identifiers
    }, function(success, data)
        if not success then
            -- Panel unreachable - allow player (fail-open)
            DebugPrint("Ban check failed, allowing player")
            deferrals.done()
            return
        end
        
        if data.banned then
            local banMessage = string.format(
                "You are banned from this server.\n\n" ..
                "Reason: %s\n" ..
                "Banned by: %s\n" ..
                "Expires: %s",
                data.reason or "No reason provided",
                data.banned_by or "Staff",
                data.permanent and "Permanent" or (data.expires_at or "Unknown")
            )
            deferrals.done(banMessage)
            return
        end
        
        -- Not banned, allow connection and register player
        PanelRequest("/player/connect", "POST", {
            name = name,
            identifiers = identifiers
        }, function(success, playerData)
            if success and playerData.success then
                -- Store player data
                serverPlayersData[source] = {
                    identifiers = identifiers,
                    trust_score = playerData.trust_score or 75
                }
                
                -- Show join message
                if Config.ShowJoinMessages then
                    local msg = Config.JoinMessageFormat
                        :gsub("{name}", name)
                        :gsub("{score}", tostring(playerData.trust_score or 75))
                    TriggerClientEvent("chat:addMessage", -1, { args = { msg } })
                end
            end
            
            deferrals.done()
        end)
    end)
end)

-- Player dropped event
AddEventHandler("playerDropped", function(reason)
    local source = source
    local playerData = serverPlayersData[source]
    
    if playerData then
        PanelRequest("/player/disconnect", "POST", {
            identifiers = playerData.identifiers
        }, function() end)
        
        serverPlayersData[source] = nil
    end
end)

-- Handle kick from panel
RegisterNetEvent("adminpanel:kick")
AddEventHandler("adminpanel:kick", function(targetSource, reason)
    -- This event should only be triggered by server-side code (source is 0)
    if source and source > 0 then return end
    
    DropPlayer(targetSource, reason or "Kicked by staff")
end)

-- Handle warn notification from panel
RegisterNetEvent("adminpanel:warn")
AddEventHandler("adminpanel:warn", function(targetSource, reason, staffName)
    -- This event should only be triggered by server-side code (source is 0)
    if source and source > 0 then return end
    
    TriggerClientEvent("adminpanel:showWarning", targetSource, reason, staffName)
    
    -- Also show in chat
    local playerName = GetPlayerName(targetSource) or "Unknown"
    TriggerClientEvent("chat:addMessage", -1, {
        args = { "^1[WARNING]^0 " .. playerName .. " was warned: " .. reason }
    })
end)

-- Export functions for other resources
exports("GetPlayerTrustScore", function(source)
    local playerData = serverPlayersData[source]
    return playerData and playerData.trust_score or 75
end)

exports("GetPlayerIdentifiers", function(source)
    local playerData = serverPlayersData[source]
    return playerData and playerData.identifiers or {}
end)

-- Print startup message
print("^2[Admin Panel]^0 Resource loaded - " .. Config.ServerName)
print("^2[Admin Panel]^0 Panel URL: " .. Config.PanelURL)
