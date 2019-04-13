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
$time = time() - 604800;
$servers = dbquery('SELECT * FROM servers WHERE active=1');
foreach($servers as $server) {
    $actions = array();
    array_push($actions, dbquery('SELECT * FROM bans WHERE community="' . escapestring($server['community']) . '" AND ban_issued >= ' . $time));
    print_r($actions);
    echo '<br><br>';
}

exit();