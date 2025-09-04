<?php
if (isset($_SESSION['steamid'])) {
    $steamid = $_SESSION['steamid'];
    $apikey = Config::get('apikey'); // Use Config class

    if (!empty($apikey)) {
        $url = "https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$apikey&steamids=$steamid";
        $json = @file_get_contents($url);
        if ($json) {
            $data = json_decode($json, true);
            if (isset($data['response']['players'][0])) {
                $steamprofile = $data['response']['players'][0];
                // Sanitize and store
                $_SESSION['steam_personaname'] = htmlspecialchars($steamprofile['personaname'], ENT_QUOTES, 'UTF-8');
                $_SESSION['steam_profileurl'] = htmlspecialchars($steamprofile['profileurl'], ENT_QUOTES, 'UTF-8');
                $_SESSION['steam_avatar'] = htmlspecialchars($steamprofile['avatar'], ENT_QUOTES, 'UTF-8');
            } else {
                error_log('Steam API failed for user ' . $steamid);
            }
        } else {
            error_log('Failed to fetch Steam data');
        }
    } else {
        error_log('Steam API key not set');
    }
}
?>
