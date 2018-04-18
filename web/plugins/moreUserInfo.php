<?php

class moreUserInfo
{
    public static function userInfoTable($userinfo)
    {
        // CONFIG
        $config['steamhex'] = true;
        $config['license'] = false;

        if ($config['steamhex']) {
            echo '
                <hr>
                <span class="description" style="font-weight: normal; word-wrap: break-word;">
                    Steam Hex: ' . $userinfo['steam'] . '
                </span>
            ';
        }

        if ($config['license']) {
            echo '
                <hr>
                <span class="description" style="font-weight: normal; word-wrap: break-word;">
                    License: ' . $userinfo['license'] . '
                </span>
            ';
        }
    }
}
