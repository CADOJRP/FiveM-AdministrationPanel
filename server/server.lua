-- Secure admin commands with ACL checks
RegisterCommand('kick', function(source, args, raw)
    if IsPlayerAceAllowed(source, 'admin.kick') then
        local target = tonumber(args[1])
        if target then
            DropPlayer(target, 'Kicked by admin')
            TriggerClientEvent('chat:addMessage', -1, {args = {'^1Admin', 'Player ' .. target .. ' was kicked'}})
        end
    else
        TriggerClientEvent('chat:addMessage', source, {args = {'^1Error', 'Insufficient permissions'}})
    end
end, false)

RegisterCommand('ban', function(source, args, raw)
    if IsPlayerAceAllowed(source, 'admin.ban') then
        local target = tonumber(args[1])
        if target then
            local identifiers = GetPlayerIdentifiers(target)
            -- Store ban in database (integrate with web panel)
            TriggerEvent('es:addBan', identifiers[1], 'Banned by admin')
            DropPlayer(target, 'Banned')
        end
    else
        TriggerClientEvent('chat:addMessage', source, {args = {'^1Error', 'Insufficient permissions'}})
    end
end, false)

-- Add more commands as needed, e.g., teleport, give items
