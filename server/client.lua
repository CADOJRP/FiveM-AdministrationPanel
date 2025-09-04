-- Client-side notifications
RegisterNetEvent('admin:notify')
AddEventHandler('admin:notify', function(msg)
    SetNotificationTextEntry('STRING')
    AddTextComponentString(msg)
    DrawNotification(false, false)
end)

-- Example: Request admin menu (integrate with NUI if needed)
Citizen.CreateThread(function()
    while true do
        Citizen.Wait(0)
        if IsControlJustPressed(0, 166) then -- F5 key
            TriggerServerEvent('admin:openMenu')
        end
    end
end)
