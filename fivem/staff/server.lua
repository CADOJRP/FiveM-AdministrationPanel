--------------
--  CONFIG  --
--------------

local website = "http://banmanager.bluebear.network/bans" 
-- Set to your staff panel URL with protocol (and with sub folder if applicable) and without trailing slash. Example: https://arthurmitchell.xyz/beta





------- DO NOT EDIT BELOW THIS LINE -------
function urlencode(str)
   if (str) then
      str = string.gsub (str, "\n", "\r\n")
      str = string.gsub (str, "([^%w ])",
         function (c) return string.format ("%%%02X", string.byte(c)) end)
      str = string.gsub (str, " ", "+")
   end
   return str    
end

AddEventHandler( "playerConnecting", function(name, setReason, deferrals)
local source = source
		deferrals.defer()
		Wait(0)
	if string.find(GetPlayerIdentifiers(source)[1], "steam:") then

		deferrals.update("Checking Player Information. Please Wait.")
		PerformHttpRequest(website .. '/api/adduser?name=' .. urlencode(GetPlayerName(source)) .. '&license=' .. GetPlayerIdentifiers(source)[2], function(statusCode, response, headers) end)
		PerformHttpRequest(website .. '/api/checkban?license=' .. GetPlayerIdentifiers(source)[2], function(statusCode, response, headers)
			if response then
				local userinfo = json.decode(response)
				if userinfo['banned'] == "true" then
					deferrals.done("Ban Reason: " .. userinfo['reason'] .. " ⚫ Banned Until: " .. userinfo['banned_until'] .. " ⚫ Banned By: " .. userinfo['staff'] .. " ⚫ Ban Issued: " .. userinfo['ban_issued'] ..   " Want to appeal? Visit https://ubanned.me")
				else
					deferrals.done()
				end
			end
		end)
	else 
		--
		setReason("Error! Steam is required to play on this FiveM server.")

		deferrals.done("you need steam")
	end
end)

AddEventHandler('chatMessage', function(source, name, msg)
	PerformHttpRequest(website .. '/api/message?id=' .. GetPlayerIdentifiers(source)[2] .. '&message=' .. urlencode(msg), function(statusCode, text, headers) end, 'GET')
end)






-- RCON Kick Command
RegisterCommand("clientkick", function(source, args, rawCommand)
	if source == 0 or source == "console" then
		DropPlayer(table.remove(args, 1), table.concat(args, ' '))
			--TriggerEvent('InteractSound_SV:PlayOnAll', 'kidclapped', 1.0)
			Citizen.Wait(500)
			--TriggerEvent('InteractSound_SV:PlayOnAll', 'kidclapped', 1.0)

		CancelEvent()
	end
end)

-- RCON Tell All Command
RegisterCommand("staff_sayall", function(source, args, rawCommand)
	if source == 0 or source == "console" then
		local message = rawCommand:gsub('staff_sayall ', '')
		TriggerClientEvent("chatMessage", -1, '', {255, 255, 255}, config.prefix .. '^0 ' .. message)
	end
end)

-- RCON Tell Player Command
RegisterCommand("staff_tell", function(source, args, rawCommand)
	if source == 0 or source == "console" then
		local message = rawCommand:gsub('staff_tell ' .. args[1], '')
		TriggerClientEvent("chatMessage", args[1], '', {255, 255, 255}, config.prefix .. '^0 ' .. message)
	end
end)
