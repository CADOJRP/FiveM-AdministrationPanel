<?php

/*
 * Plugin Name: antiSwear
 * Version: 0.0.1
 * Details: Warns or kicks a player when they type a blacklisted word.
 * Created Date: Monday, April 16th 2018, 9:16:16 pm
 * Author: Avery Johnson
 *
 * Copyright (c) 2018 Avery Johnson
 */

class antiSwear
{
    public static function chatMessage($license, $message)
    {
        // ANTISWEAR CONFIG
        $config['action'] = "warn";                     // 'warn' or 'kick'
        $config['reason'] = "Vulgar Language";          // Warn/Kick Reason
        $conifg['announce'] = true;                     // Announce Warning/Kick
        $config['blacklist'] = [
            'shit',
            'whore',
            'fuck'
        ];
        
        foreach ($blacklist as $banned) {
            if (strpos($banned, $message) !== false) {
                if ($config['action'] == "kick") {
                    dbquery('INSERT INTO kicks (license, reason, staff_name, staff_steamid, time) VALUES ("' . escapestring($license) . '", "' . $config['reason'] . '", "Console", "Console")', false);
                    if ($config['announce']) {
                        removeFromSession($license, 'You\'ve been kicked by Console for ' . $config['reason']);
                        sendMessage('^3' . dbquery("SELECT * FROM players WHERE license='" . escapestring($license) . "'")[0]['name'] . '^0 has been kicked by ^2Console^0 for ^3' . $config['reason']);
                    }
                } else {
                    dbquery('INSERT INTO warnings (license, reason, staff_name, staff_steamid, time) VALUES ("' . escapestring($license) . '", "' . $config['reason'] . '", "Console", "Console")', false);
                    if ($config['announce']) {
                        sendMessage('^3' . dbquery("SELECT * FROM players WHERE license='" . escapestring($license) . "'")[0]['name'] . '^0 has been warned by ^2Console^0 for ^3' . $config['reason']);
                    }
                }
            }
        }
    }
}
