--[[
    FiveM Admin Panel - Client Script
    Handles notifications and UI elements
]]

-- Show warning notification
RegisterNetEvent("adminpanel:showWarning")
AddEventHandler("adminpanel:showWarning", function(reason, staffName)
    -- Play warning sound
    PlaySoundFrontend(-1, "Hang_Up", "Phone_SoundSet_Michael", true)
    
    -- Big notification
    SetNotificationTextEntry("STRING")
    AddTextComponentString("~r~WARNING~s~\n" .. reason .. "\n~c~By: " .. (staffName or "Staff"))
    DrawNotification(true, true)
    
    -- Also show as subtitle
    BeginTextCommandPrint("STRING")
    AddTextComponentString("~r~You have been warned: ~s~" .. reason)
    EndTextCommandPrint(8000, true)
end)

-- Show notification
RegisterNetEvent("adminpanel:notify")
AddEventHandler("adminpanel:notify", function(message, type)
    SetNotificationTextEntry("STRING")
    
    if type == "error" then
        AddTextComponentString("~r~" .. message)
    elseif type == "success" then
        AddTextComponentString("~g~" .. message)
    else
        AddTextComponentString(message)
    end
    
    DrawNotification(false, true)
end)

-- Show server announcement
RegisterNetEvent("adminpanel:announcement")
AddEventHandler("adminpanel:announcement", function(message, from)
    -- Play sound
    PlaySoundFrontend(-1, "GTAO_Exec_SecuroServ_Arrive_Notif_Beep", nil, true)
    
    -- Big message
    SetNotificationTextEntry("STRING")
    AddTextComponentString("~b~ANNOUNCEMENT~s~\n" .. message)
    DrawNotification(true, true)
    
    -- Show on screen
    BeginTextCommandPrint("STRING")
    AddTextComponentString("~b~Announcement: ~s~" .. message)
    EndTextCommandPrint(10000, true)
end)
