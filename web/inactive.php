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

echo '<pre>';
$time = time() - 1209600;
$servers = dbquery('SELECT * FROM servers WHERE active=1');
foreach($servers as $server) {
    $active = 'false';
    $actions = array();
    array_push($actions, dbquery('SELECT * FROM bans WHERE community="' . escapestring($server['community']) . '" AND ban_issued >= ' . $time));
    array_push($actions, dbquery('SELECT * FROM commend WHERE community="' . escapestring($server['community']) . '" AND time >= ' . $time));
    array_push($actions, dbquery('SELECT * FROM warnings WHERE community="' . escapestring($server['community']) . '" AND time >= ' . $time));
    array_push($actions, dbquery('SELECT * FROM kicks WHERE community="' . escapestring($server['community']) . '" AND time >= ' . $time));
    array_push($actions, dbquery('SELECT * FROM notes WHERE community="' . escapestring($server['community']) . '" AND time >= ' . $time));
    foreach($actions as $server2) {
        if(count($server2) > 0) {
            $active = 'true';
        } else {
            $active = 'false';
        }
    }

    //echo '<br><br><br><br><br>';
    /*if(!isempty($actions)) {
        $active = true;
    }*/
    echo $server['connection'] . ' • ' . $active . ' <br>';
}

exit();