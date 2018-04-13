--------------
--  CONFIG  --
--------------

local website = "https://arthurmitchell.xyz/beta" 
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
	if string.find(GetPlayerIdentifiers(source)[1], "steam:") then
		deferrals.defer()
		deferrals.update("Checking Player Information. Please Wait.")
		PerformHttpRequest(website .. '/api/adduser?name=' .. urlencode(GetPlayerName(source)) .. '&license=' .. GetPlayerIdentifiers(source)[2], function(statusCode, response, headers) end)
		PerformHttpRequest(website .. '/api/checkban?license=' .. GetPlayerIdentifiers(source)[2], function(statusCode, response, headers)
			if response then
				local userinfo = json.decode(response)
				if userinfo['banned'] == "true" then
					deferrals.done("Ban Reason: " .. userinfo['reason'] .. " ⚫ Banned Until: " .. userinfo['banned_until'] .. " ⚫ Banned By: " .. userinfo['staff'] .. " ⚫ Ban Issued: " .. userinfo['ban_issued'])
				else
					deferrals.done()
				end
			end
		end)
		PerformHttpRequest(website .. '/api/adduser?name=' .. GetPlayerName(source) .. '&license=' .. GetPlayerIdentifiers(source)[2], function(statusCode, response, headers) end)
	else 
		setReason("Error! Steam is required to play on this FiveM server.")
		CancelEvent()
	end
end)

AddEventHandler('chatMessage', function(source, name, msg)
	PerformHttpRequest(website .. '/api/message?id=' .. GetPlayerIdentifiers(source)[2] .. '&message=' .. urlencode(msg), function(statusCode, text, headers) end, 'GET')
end)