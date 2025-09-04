--------------
--  CONFIG  --
--------------

local website = "https://orbithosting.uk"  -- Set to your staff panel URL with protocol (and sub folder if applicable) and without trailing slash. Example: https://orbithosting.uk
local apiTimeout = 5000  -- Timeout for API requests in ms

------- DO NOT EDIT BELOW THIS LINE -------

-- Utility function for URL encoding
function urlencode(str)
    if str then
        str = string.gsub(str, "\n", "\r\n")
        str = string.gsub(str, "([^%w ])", function(c) return string.format("%%%02X", string.byte(c)) end)
        str = string.gsub(str, " ", "+")
    end
    return str
end

-- Secure API request with error handling
function performSecureHttpRequest(url, callback)
    PerformHttpRequest(url, function(statusCode, response, headers)
        if statusCode == 200 and response then
            local success, data = pcall(json.decode, response)
            if success and data then
                callback(data)
            else
                print("[FiveM Admin] Failed to decode JSON response from " .. url)
                callback(nil)
            end
        else
            print("[FiveM Admin] HTTP request failed: " .. tostring(statusCode) .. " for " .. url)
            callback(nil)
        end
    end, 'GET', '', { ['Content-Type'] = 'application/json' }, apiTimeout)
end

-- Player connecting event with modern identifier handling
AddEventHandler("playerConnecting", function(name, setReason, deferrals)
    local identifiers = GetPlayerIdentifiers(source)
    local steamId = nil
    local license = nil

    for _, id in ipairs(identifiers) do
        if string.find(id, "steam:") then
            steamId = id
        elseif string.find(id, "license:") then
            license = id
        end
    end

    if steamId then
        deferrals.defer()
        deferrals.update("Checking Player Information. Please Wait.")

        -- Add user to database
        performSecureHttpRequest(website .. '/api/adduser?name=' .. urlencode(GetPlayerName(source)) .. '&license=' .. urlencode(license), function(response)
            if not response then
                deferrals.done("Failed to verify player. Please try again.")
                return
            end

            -- Check ban status
            performSecureHttpRequest(website .. '/api/checkban?license=' .. urlencode(license), function(banData)
                if banData and banData['banned'] == "true" then
                    deferrals.done("Ban Reason: " .. banData['reason'] .. " | Banned Until: " .. banData['banned_until'] .. " | Banned By: " .. banData['staff'] .. " | Ban Issued: " .. banData['ban_issued'])
                else
                    deferrals.done()
                end
            end)
        end)
    else
        setReason("Error! Steam is required to play on this FiveM server.")
        CancelEvent()
    end
end)

-- Chat message event with validation
AddEventHandler('chatMessage', function(source, name, msg)
    local identifiers = GetPlayerIdentifiers(source)
    local license = nil

    for _, id in ipairs(identifiers) do
        if string.find(id, "license:") then
            license = id
            break
        end
    end

    if license then
        performSecureHttpRequest(website .. '/api/message?id=' .. urlencode(license) .. '&message=' .. urlencode(msg), function(response)
            -- Handle response if needed
        end)
    end
end)

-- Additional modern features: Log events
Citizen.CreateThread(function()
    while true do
        Citizen.Wait(60000)  -- Log every minute
        print("[FiveM Admin] Server running with " .. #GetPlayers() .. " players.")
    end
end)
