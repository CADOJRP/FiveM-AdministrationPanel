<?php

/*
 * Plugin Name: autoAnnounce
 * Version: 0.0.1
 * Details: Auto announces messages every set amount of minutes.
 * Created Date: Monday, April 17th 2018, 9:26:00 pm
 * Author: Avery Johnson
 *
 * Copyright (c) 2018 Avery Johnson
 */

class autoAnnounce
{
    public static function cronCalled()
    {
        // AUTO ANNOUNCE CONFIG
        // TO ADD BREAKS EACH MINUTE ADD BLANK LINES. I'M TOO LAZY TO ADD A WAIT SYSTEM. IF SOMEONE WANTS TO PR IT ON GITHUB GO FOR IT
        $config['messages'] = [
            'This server is ^2powered^0 by ^2FiveM Administration System^0. To learn more visit https://GitHub.com/CADOJRP',
            'Don\'t forget to register on our forums at ^2forum.FiveM.net',
            '^1E^2X^3A^4M^5P^6L^7E ^8M^9E^0S^1S^2A^3G^4E',
        ];

        if (!isset($_SESSION['runcount'])) {
            $_SESSION['runcount'] = 0;
        } else {
            if(!empty($config['messages'][$_SESSION['runcount']])) {
                sendMessage($config['messages'][$_SESSION['runcount']]);
            }
            if ($_SESSION['runcount'] >= count($config['messages']) - 1) {
                $_SESSION['runcount'] = 0;
            } else {
                $_SESSION['runcount']++;
            }
        }
    }
}
