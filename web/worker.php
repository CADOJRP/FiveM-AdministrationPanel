<?php

require('config.php');

ini_set("error_log", realpath('logs') . "/cron.log");

// MySQL Injection Prevention
function escapestring($value)
{
    $conn = new mysqli($GLOBALS['mysql_host'], $GLOBALS['mysql_user'], $GLOBALS['mysql_pass'], $GLOBALS['mysql_db']);
    if ($conn->connect_errno) {
        die('Could not connect: ' . $conn->connect_error);
    }
    return strip_tags(mysqli_real_escape_string($conn, $value));
}

// Insert into Database
function dbquery($sql, $returnresult = true)
{
    $conn = new mysqli($GLOBALS['mysql_host'], $GLOBALS['mysql_user'], $GLOBALS['mysql_pass'], $GLOBALS['mysql_db']);
    if ($conn->connect_errno) {
        error_log('MySQL could not connect: ' . $conn->connect_error);
        return $conn->connect_error;
    }

    $return = array();

    $result = mysqli_query($conn, $sql);
    if ($returnresult) {
        if (mysqli_num_rows($result) != 0) {
            while ($r = $result->fetch_assoc()) {
                array_push($return, $r);
            }
        } else {
            $return = array();
        }
    } else {
        $return = array();
    }

    return $return;
}


$players = json_decode(@file_get_contents('http://' . $argv[1] . '/players.json'), true);
$playercount = 0;
if (!empty($players)) {
    foreach ($players as $player) {
        $playercount++;
        $discord = 'NULL';
        foreach ($player['identifiers'] as $identifier) {
            if (strpos($identifier, 'discord:') !== false) {
                $discord = '"' . $identifier . '"';
            }

            if (strpos($identifier, 'license:') !== false) {
                $license = $identifier;
            }

            if (strpos($identifier, 'steam:') !== false) {
                $steam = $identifier;
            }

            if (strpos($identifier, 'xbl:') !== false) {
                $xbl = $identifier;
            }

            if (strpos($identifier, 'ip:') !== false) {
                // Only Works on Unsecure Endpoint
                $ip = $identifier;
            }

            if (strpos($identifier, 'live:') !== false) {
                $live = $identifier;
            }
        }

        if (!isset($steam)) {
            error_log('No Steam Found!');
            break;
        }
        if (!isset($license)) {
            error_log('No License Found!');
            break;
        }
        dbquery('INSERT INTO players (name, license, steam, discord, xbl, ip, live, firstjoined, lastplayed, community) VALUES ("' . escapestring($player['name']) . '", "' . escapestring($license) . '", "' . escapestring($steam) . '", ' . $discord . ',  ' . $xbl . ',  ' . $ip . ',  ' . $live . ', "' . time() . '", "' . time() . '", "' . $argv[2] . '") ON DUPLICATE KEY UPDATE name="' . escapestring($player['name']) . '", playtime=playtime+5, steam="' . escapestring($steam) . '", discord=' . $discord . ', xbl=' . $xbl . ', ip=' . $ip . ', live=' . $live . ', lastplayed="' . time() . '"', false);
        dbquery('UPDATE servers SET players=' . $playercount . ' WHERE connection="' . escapestring($argv[1]) . '"', false);
    }
} else {
    dbquery('UPDATE servers SET players=' . $playercount . ' WHERE connection="' . escapestring($argv[1]) . '"', false);    
}

exit();

