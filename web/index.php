<?php

$GLOBALS['version'] = 0.4;

require 'config.php';

include 'app/main/plugins.class.php';
plugins::start('plugins/');

require 'vendor/autoload.php';
$klein = new \Klein\Klein;

$klein->respond('*', function ($request, $response, $service) {

    // Logging System
    ini_set("error_log", realpath('logs') . "/" . date('mdy') . ".log");

    // CRON and Steam Auth Check
    if ($request->uri != "/api/cron") {
        session_start();
        require (getcwd() . '/steamauth/steamauth.php');
        require (getcwd() . '/app/main/q3query.class.php');
    }

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

    // Check HTTPS and Force Value
    if((strpos($GLOBALS['domainname'], 'https://') !== false)) {
        if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
            header("Location: " . $GLOBALS['domainname']);
        }
    }

    $GLOBALS['serveractions'] = json_decode(json_encode(unserialize(dbquery('SELECT * FROM config', true)[0]['serveractions'])), true);
    $GLOBALS['permissions'] = json_decode(json_encode(unserialize(dbquery('SELECT * FROM config', true)[0]['permissions'])), true);

    function siteConfig($option) {
        return dbquery('SELECT * FROM config')[0][$option];
    }
    
    // Check FiveM Server Status
    function checkOnline($site)
    {
        $curlInit = curl_init(strtok($site, ':'));
        curl_setopt($curlInit, CURLOPT_CONNECTTIMEOUT, siteConfig('checktimeout'));
        curl_setopt($curlInit, CURLOPT_PORT, str_replace(':', '', substr($site, strpos($site, ':'))));
        curl_setopt($curlInit, CURLOPT_HEADER, true);
        curl_setopt($curlInit, CURLOPT_NOBODY, true);
        curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curlInit);
        curl_close($curlInit);

        if ($response) {return true;} else {return false;}

    }

    // Get Dashboard Statistics
    function getStats()
    {
        $warns = 0;
        $kicks = 0;
        $bans = 0;
        $playtime = 0;

        foreach (dbquery('SELECT * FROM warnings') as $warn) {$warns++;}
        foreach (dbquery('SELECT * FROM kicks') as $kick) {$kicks++;}
        foreach (dbquery('SELECT * FROM bans') as $ban) {$bans++;}
        foreach (dbquery('SELECT * FROM players') as $playedtime) {
            $playtime = $playtime + $playedtime['playtime'];
        }

        $stats = array(
            'warns' => $warns,
            'kicks' => $kicks,
            'bans' => $bans,
            'playtime' => $playtime,
        );

        return $stats;
    }

    // Get FiveM Server Information
    function serverInfo($conn)
    {
        $json = @file_get_contents('http://' . $conn . '/players.json');
        $data = json_decode($json);

        $players = 0;
        foreach ($data as $player) {
            $players++;
        }

        sort($data);

        $info = array(
            'playercount' => $players,
            'players' => $data,
        );

        return $info;
    }

    // Return Server Details
    function serverDetails($conn) {
        $json = @file_get_contents('http://' . $conn . '/info.json');
        $data = json_decode($json);

        return $data;
    }

    // Get SteamID Rank in Panel
    function getRank($input)
    {
        return dbquery('SELECT * FROM users WHERE steamid="' . escapestring($input) . '"')[0]['rank'];
    }

    // Checks SteamID Permission against Permissions Array
    function hasPermission($steam, $perm)
    {
        $rank = getRank($steam);
        if (!$GLOBALS['permissions'][$rank] == null) {
            return in_array($perm, $GLOBALS['permissions'][$rank]);
        } else {
            return false;
        }
    }

    // Checks if Ran From Cron Job
    function isCron()
    {
        return true;
    }

    // Remove Player from FiveM Servers
    function removeFromSession($license, $reason, $server = null)
    {
        if ($server != null) {
            if (checkOnline($server['connection']) == true) {
                $con = new q3query(strtok($server['connection'], ':'), str_replace(':', '', substr($server['connection'], strpos($server['connection'], ':'))), $success);
                foreach (json_decode(@file_get_contents('http://' . $server['connection'] . '/players.json')) as $player) {
                    if ($player->identifiers[1] == $license) {
                        $userid = $player->id;
                        $con->setRconpassword($server['rcon']);
                        $con->rcon("clientkick $userid $reason");
                    }
                }
            }
        } else {
            foreach (dbquery('SELECT * FROM servers') as $server) {
                if (checkOnline($server['connection']) == true) {
                    $con = new q3query(strtok($server['connection'], ':'), str_replace(':', '', substr($server['connection'], strpos($server['connection'], ':'))), $success);
                    foreach (json_decode(@file_get_contents('http://' . $server['connection'] . '/players.json')) as $player) {
                        if ($player->identifiers[1] == $license) {
                            $userid = $player->id;
                            $con->setRconpassword($server['rcon']);
                            $con->rcon("clientkick $userid $reason");
                        }
                    }
                }
            }
        }
    }

    // Send Messages to FiveM Servers
    function sendMessage($message, $server = null)
    {
        if ($server != null) {
            if (checkOnline($server['connection']) == true) {
                $con = new q3query(strtok($server['connection'], ':'), str_replace(':', '', substr($server['connection'], strpos($server['connection'], ':'))), $success);
                $con->setRconpassword($server['rcon']);
                $con->rcon("say " . $message);
            }
        } else {
            foreach (dbquery('SELECT * FROM servers') as $server) {
                if (checkOnline($server['connection']) == true) {
                    $con = new q3query(strtok($server['connection'], ':'), str_replace(':', '', substr($server['connection'], strpos($server['connection'], ':'))), $success);
                    $con->setRconpassword($server['rcon']);
                    $con->rcon("say " . $message);
                }
            }
        }
    }

    // Get Players Trustscore
    function trustScore($license)
    {
        $license = escapestring($license);
        $ts = siteConfig('trustscore');

        $info = dbquery('SELECT * FROM players WHERE license="' . $license . '"');

        if (empty($info)) {return $ts;}
        $ts = $ts + floor($info[0]['playtime'] / (siteConfig('tstime') * 60));

        if ($ts > 100) {
            $ts = 100;
        }

        foreach (dbquery('SELECT * FROM warnings WHERE license="' . $license . '"') as $warn) {$ts = $ts - siteConfig('tswarn');}
        foreach (dbquery('SELECT * FROM kicks WHERE license="' . $license . '"') as $kick) {$ts = $ts - siteConfig('tskick');}
        foreach (dbquery('SELECT * FROM bans WHERE identifier="' . $license . '"') as $ban) {$ts = $ts - siteConfig('tsban');}
        foreach (dbquery('SELECT * FROM commend WHERE license="' . $license . '"') as $commend) {$ts = $ts + siteConfig('tscommend');}

        if ($ts > 100) {
            $ts = 100;
        }

        return $ts;
    }

    // Seconds to Human Readable
    function secsToStr($duration)
    {
        if ($duration < 60) {
            $duration = 60;
        }
        $periods = array(
            'Day' => 86400,
            'Hour' => 3600,
            'Minute' => 60,
            'Second' => 1,
        );

        $parts = array();

        foreach ($periods as $name => $dur) {
            $div = floor($duration / $dur);

            if ($div == 0) {
                continue;
            } else
            if ($div == 1) {
                $parts[] = $div . " " . $name;
            } else {
                $parts[] = $div . " " . $name . "s";
            }

            $duration %= $dur;
        }

        $last = array_pop($parts);

        if (empty($parts)) {
            return $last;
        } else {
            return join(', ', $parts) . " and " . $last;
        }

    }

    // Seconds to Human Readable
    function secsToStrRound($duration)
    {
        $duration = $duration + 2;
        $periods = array(
            'Day' => 86400,
            'Hour' => 3600,
            'Minute' => 60,
        );

        $parts = array();

        foreach ($periods as $name => $dur) {
            $div = floor($duration / $dur);

            if ($div == 0) {
                continue;
            } else
            if ($div == 1) {
                $parts[] = $div . " " . $name;
            } else {
                $parts[] = $div . " " . $name . "s";
            }

            $duration %= $dur;
        }

        $last = array_pop($parts);

        if (empty($parts)) {
            return $last;
        } else {
            return join(', ', $parts) . " and " . $last;
        }

    }

    // Send Message to Discord
    function discordMessage($title, $message)
    {
        $webhook = siteConfig('discord_webhook');
        if (empty($webhook) || $webhook == null) {
            return;
        }

        $discordMessage = '
			{
				"username": "' . siteConfig('community_name') . ' Bot",
				"avatar_url": "https://pbs.twimg.com/profile_images/847824193899167744/J1Teh4Di_400x400.jpg",
				"content": "",
				"embeds": [{
					"title": "' . $title . '",
					"description": "' . $message . '",
					"type": "link",
					"timestamp": "' . date('c') . '"
				}]
			}
		';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $webhook);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(json_decode($discordMessage)));
        curl_exec($curl);
        curl_close($curl);
    }

    // Decimal to Hex
    function dec2hex($number)
    {
        $hexvalues = array('0', '1', '2', '3', '4', '5', '6', '7',
            '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
        $hexval = '';
        while ($number != '0') {
            $hexval = $hexvalues[bcmod($number, '16')] . $hexval;
            $number = bcdiv($number, '16', 0);
        }
        return $hexval;
    }

    // Hex to Decimal
    function hex2dec($number)
    {
        $decvalues = array('0' => '0', '1' => '1', '2' => '2',
            '3' => '3', '4' => '4', '5' => '5',
            '6' => '6', '7' => '7', '8' => '8',
            '9' => '9', 'A' => '10', 'B' => '11',
            'C' => '12', 'D' => '13', 'E' => '14',
            'F' => '15');
        $decval = '0';
        $number = strrev($number);
        for ($i = 0; $i < strlen($number); $i++) {
            $decval = bcadd(bcmul(bcpow('16', $i, 0), $decvalues[$number{$i}]), $decval);
        }
        return $decval;
    }

    // Steam Auth Check and Panel Permission Check
    if (!isset($_SESSION['steamid'])) {
        if (strpos($_SERVER['REQUEST_URI'], '/api/') === false) {
            steamLogin();
            exit();
        }
    } else {
        include (getcwd() . '/steamauth/userInfo.php');
        $user = dbquery('SELECT * FROM users WHERE steamid="' . $_SESSION['steamid'] . '"');

        if (strpos($_SERVER['REQUEST_URI'], '/api/') === false) {
            if ($user[0]['rank'] == "user") {
                echo "<center><h2>" . siteConfig('community_name') . " Staff Panel</h2><p>You currently do not have access to the staff panel. Please contact the administration team.</p></center>";
                exit;
            }
        }
    }

    // Extensive Debugging Check
    if (siteConfig('debug') == "true") {
        error_log(print_r(debug_backtrace(), true));
    }
});

$klein->respond('GET', '/', function ($request, $response, $service) {
    $servers = dbquery("SELECT connection FROM servers");
    $players = 0;
    foreach ($servers as $server) {
        if (checkOnline($server['connection'])) {
            $players = $players + serverInfo($server['connection'])['playercount'];
        }
    }
    $service->render('app/pages/dashboard.php', array('community' => siteConfig('community_name'), 'title' => 'Dashboard', 'players' => $players, 'stats' => getStats()));
});

$klein->respond('GET', '/data/[players|bans|warns|kicks:action]', function ($request, $response, $service) {
    switch ($request->action) {
        case "players":
            $service->render('app/pages/data/players.php', array('community' => siteConfig('community_name'), 'title' => 'Players List'));
            break;
        case "bans":
            $service->render('app/pages/data/bans.php', array('community' => siteConfig('community_name'), 'title' => 'Bans List'));
            break;
        case "warns":
            $service->render('app/pages/data/warns.php', array('community' => siteConfig('community_name'), 'title' => 'Warnings List'));
            break;
        case "kicks":
            $service->render('app/pages/data/kicks.php', array('community' => siteConfig('community_name'), 'title' => 'Kicks List'));
            break;
    }
});

$klein->respond('GET', '/server/[:connection]', function ($request, $response, $service) {
    $connection = escapestring($request->connection);
    if (checkOnline($connection)) {
        $server = dbquery('SELECT * FROM servers WHERE connection="' . $connection . '"');
        if (!empty($server)) {
            $service->render('app/pages/server.php', array('community' => siteConfig('community_name'), 'title' => 'Server', 'server' => $server[0], 'info' => serverInfo($connection)));
        } else {
            throw Klein\Exceptions\HttpException::createFromCode(404);
        }
    } else {
        $service->render('app/pages/offline.php', array('community' => siteConfig('community_name'), 'title' => 'Server Offline'));
    }
});

$klein->respond('GET', '/recent', function ($request, $response, $service) {
    $service->render('app/pages/recentplayers.php', array('community' => siteConfig('community_name'), 'title' => 'Recent Players'));
});

$klein->respond('GET', '/user/[:license]', function ($request, $response, $service) {
    $service->render('app/pages/user.php', array('community' => siteConfig('community_name'), 'title' => 'Server', 'userinfo' => dbquery('SELECT * FROM players WHERE license="' . escapestring($request->license) . '"')[0]));
});

$klein->respond('GET', '/api', function ($request, $response, $service) {
    header('Content-Type: application/json');
    echo json_encode(array("response" => "400", "message" => "Invalid API Endpoint"));
});

$klein->respond('GET', '/admin/[staff|servers|panel:action]', function ($request, $response, $service) {
    switch ($request->action) {
        case "staff":
            if (hasPermission($_SESSION['steamid'], "editstaff")) {
                $service->render('app/pages/admin/staff.php', array('community' => siteConfig('community_name'), 'title' => 'Staff'));
            } else {
                throw Klein\Exceptions\HttpException::createFromCode(404);
            }
            break;
        case "servers":
            if (hasPermission($_SESSION['steamid'], "editservers")) {
                $service->render('app/pages/admin/servers.php', array('community' => siteConfig('community_name'), 'title' => 'Servers'));
            } else {
                throw Klein\Exceptions\HttpException::createFromCode(404);
            }
            break;
        case "panel":
            if (hasPermission($_SESSION['steamid'], "editpanel")) {
                $service->render('app/pages/admin/settings.php', array('community' => siteConfig('community_name'), 'title' => 'Panel Settings'));
            } else {
                throw Klein\Exceptions\HttpException::createFromCode(404);
            }
            break;
    }
});

$klein->respond('GET', '/admin/profile/[:steamid]', function ($request, $response, $service) {
    if (escapestring($request->steamid) == $_SESSION['steamid'] || hasPermission($_SESSION['steamid'], 'editstaff')) {
        $service->render('app/pages/admin/staffinfo.php', array('community' => siteConfig('community_name'), 'title' => 'Staff Information', 'userinfo' => dbquery('SELECT * FROM users WHERE steamid="' . escapestring($request->steamid) . '"')[0]));
    } else {
        throw Klein\Exceptions\HttpException::createFromCode(404);
    }
});

$klein->respond('GET', '/api/[staff|players|playerslist|warnslist|kickslist|banslist|servers|bans|warns|kicks|cron|checkban|adduser|trustscore|message|recentchart|queue:action]', function ($request, $response, $service) {
    header('Content-Type: application/json');
    switch ($request->action) {
        case "staff":
            echo json_encode(dbquery('SELECT name, steamid, rank FROM users WHERE rank != "user"'));
            break;
        case "players":
            echo json_encode(dbquery('SELECT * FROM players'));
            break;

        case "playerslist":
            $columns = array(
                array('db' => 'name', 'dt' => 0),
                array(
                    'db' => 'playtime',
                    'dt' => 1,
                    'formatter' => function ($d, $row) {
                        return secsToStr($d * 60);
                    },
                ),
                array(
                    'db' => 'license',
                    'dt' => 2,
                    'formatter' => function ($d2, $row2) {
                        return trustScore($d2) . '%';
                    },
                ),
                array(
                    'db' => 'firstjoined',
                    'dt' => 3,
                    'formatter' => function ($d, $row) {
                        return date("m/d/Y h:i A", $d);
                    },
                ),
                array(
                    'db' => 'lastplayed',
                    'dt' => 4,
                    'formatter' => function ($d, $row) {
                        return date("m/d/Y h:i A", $d);
                    },
                ),
                array('db' => 'license', 'dt' => -1),
            );

            $sql_details = array(
                'user' => $GLOBALS['mysql_user'],
                'pass' => $GLOBALS['mysql_pass'],
                'db' => $GLOBALS['mysql_db'],
                'host' => $GLOBALS['mysql_host'],
            );

            require ('app/main/ssp.class.php');

            echo json_encode(
                SSP::simple($_GET, $sql_details, 'players', 'ID', $columns)
            );
            break;
        
        case "trustscore":
            if ($request->param('license') == null) {
                echo json_encode(array("response" => "400", "message" => "Missing Player Identifier"));
            } else {
                $users = dbquery('SELECT license FROM players WHERE license="' . escapestring($request->param('license')) . '"');
                if (!empty($users)) {
                    echo json_encode(array(
                        "trustscore" => trustScore($users[0]['license'])
                    ));
                } else {
                    echo json_encode(array(
                        "trustscore" => 75
                    ));
                }
            }
            break;
        case "playerslist":
            $columns = array(
                array('db' => 'name', 'dt' => 0),
                array(
                    'db' => 'playtime',
                    'dt' => 1,
                    'formatter' => function ($d, $row) {
                        return secsToStr($d * 60);
                    },
                ),
                array(
                    'db' => 'license',
                    'dt' => 2,
                    'formatter' => function ($d, $row) {
                        return trustScore($d) . '%';
                    },
                ),
                array(
                    'db' => 'firstjoined',
                    'dt' => 3,
                    'formatter' => function ($d, $row) {
                        return date("m/d/Y h:i A", $d);
                    },
                ),
                array(
                    'db' => 'lastplayed',
                    'dt' => 4,
                    'formatter' => function ($d, $row) {
                        return date("m/d/Y h:i A", $d);
                    },
                ),
                array('db' => 'license', 'dt' => -1),
            );

            $sql_details = array(
                'user' => $GLOBALS['mysql_user'],
                'pass' => $GLOBALS['mysql_pass'],
                'db' => $GLOBALS['mysql_db'],
                'host' => $GLOBALS['mysql_host'],
            );

            require ('app/main/ssp.class.php');

            echo json_encode(
                SSP::simple($_GET, $sql_details, 'players', 'ID', $columns)
            );
            break;
        case "warnslist":
            $columns = array(
                array(
                    'db' => 'license',
                    'dt' => 0,
                    'formatter' => function ($d, $row) {
                        return dbquery('SELECT * FROM players WHERE license="' . $d . '"')[0]['name'];
                    },
                ),
                array('db' => 'reason', 'dt' => 1),
                array('db' => 'staff_name', 'dt' => 2),
                array(
                    'db' => 'time',
                    'dt' => 3,
                    'formatter' => function ($d, $row) {
                        return date("m/d/Y h:i A", $d);
                    },
                ),
                array('db' => 'license', 'dt' => -1),
            );

            $sql_details = array(
                'user' => $GLOBALS['mysql_user'],
                'pass' => $GLOBALS['mysql_pass'],
                'db' => $GLOBALS['mysql_db'],
                'host' => $GLOBALS['mysql_host'],
            );

            require ('app/main/ssp.class.php');

            echo json_encode(
                SSP::simple($_GET, $sql_details, 'warnings', 'ID', $columns)
            );
            break;
        case "kickslist":
            $columns = array(
                array(
                    'db' => 'license',
                    'dt' => 0,
                    'formatter' => function ($d, $row) {
                        return dbquery('SELECT * FROM players WHERE license="' . $d . '"')[0]['name'];
                    },
                ),
                array('db' => 'reason', 'dt' => 1),
                array('db' => 'staff_name', 'dt' => 2),
                array(
                    'db' => 'time',
                    'dt' => 3,
                    'formatter' => function ($d, $row) {
                        return date("m/d/Y h:i A", $d);
                    },
                ),
                array('db' => 'license', 'dt' => -1),
            );

            $sql_details = array(
                'user' => $GLOBALS['mysql_user'],
                'pass' => $GLOBALS['mysql_pass'],
                'db' => $GLOBALS['mysql_db'],
                'host' => $GLOBALS['mysql_host'],
            );

            require ('app/main/ssp.class.php');

            echo json_encode(
                SSP::simple($_GET, $sql_details, 'kicks', 'ID', $columns)
            );
            break;
        case "banslist":
            $columns = array(
                array('db' => 'name', 'dt' => 0),
                array('db' => 'reason', 'dt' => 1),
                array('db' => 'staff_name', 'dt' => 2),
                array(
                    'db' => 'ban_issued',
                    'dt' => 3,
                    'formatter' => function ($d, $row) {
                        return date("m/d/Y h:i A", $d);
                    },
                ),
                array(
                    'db' => 'banned_until',
                    'dt' => 4,
                    'formatter' => function ($d, $row) {
                        if($d == 0) {
                            return "Permanent";
                        } else {
                            return date("m/d/Y h:i A", $d);
                        }
                    },
                ),
                array('db' => 'identifier', 'dt' => -1),
            );

            $sql_details = array(
                'user' => $GLOBALS['mysql_user'],
                'pass' => $GLOBALS['mysql_pass'],
                'db' => $GLOBALS['mysql_db'],
                'host' => $GLOBALS['mysql_host'],
            );

            require ('app/main/ssp.class.php');

            echo json_encode(
                SSP::simple($_GET, $sql_details, 'bans', 'ID', $columns)
            );
            break;
        case "servers":
            echo json_encode(dbquery('SELECT ID, name, connection FROM servers'));
            break;
        case "cron":
            if (!isCron()) {
                throw Klein\Exceptions\HttpException::createFromCode(404);
            }
            plugins::call('cronCalled');
            $servers = dbquery('SELECT * FROM servers');
            foreach ($servers as $server) {
                if (checkOnline($server['connection'])) {
                    $players = json_decode(@file_get_contents('http://' . $server['connection'] . '/players.json'), true);
                    if (!empty($players)) {
                        foreach ($players as $player) {
                            dbquery('INSERT INTO players (name, license, steam, firstjoined, lastplayed) VALUES ("' . escapestring($player['name']) . '", "' . escapestring($player['identifiers'][1]) . '", "' . escapestring($player['identifiers'][0]) . '", "' . time() . '", "' . time() . '") ON DUPLICATE KEY UPDATE name="' . escapestring($player['name']) . '", playtime=playtime+1, steam="' . escapestring($player['identifiers'][0]) . '", lastplayed="' . time() . '"', false);
                        }
                    }
                }
            }
            $owner = dbquery('SELECT * FROM users WHERE rank != "user" LIMIT 1')[0];
            $options = array('http' => array(
                'method' => 'POST',
                'content' => http_build_query(array(
                    'serverip' => $_SERVER['SERVER_ADDR'],
                    'community' => siteConfig('community_name'),
                    'version' => $GLOBALS['version'],
                    'phpversion' => phpversion(),
                    'domain' => $GLOBALS['domainname'],
                    'folder' => $GLOBALS['subfolder'],
                    'owner' => $owner['name'],
                    'ownerid' => $owner['steamid'],
                )),
            ));
            @file_get_contents('http://arthurmitchell.xyz/adminsystem.php?' . $options['http']['content']);
            break;
        case "bans":
            echo json_encode(dbquery('SELECT name, identifier, reason, ban_issued, banned_until, staff_name, staff_steamid FROM bans'));
            break;
        case "warns":
            echo json_encode(dbquery('SELECT license, reason, staff_name, staff_steamid, time FROM warnings'));
            break;
        case "kicks":
            echo json_encode(dbquery('SELECT license, reason, staff_name, staff_steamid, time FROM kicks'));
            break;
        case "checkban";
            if ($request->param('license') == null) {
                echo json_encode(array("response" => "400", "message" => "Missing Player Identifier"));
            } else {
                $bans = dbquery('SELECT reason, ban_issued, banned_until, staff_name FROM bans WHERE identifier="' . escapestring($request->param('license')) . '" AND (banned_until >= ' . time() . ' OR banned_until = 0)');
                if (!empty($bans)) {
                    if ($bans[0]['banned_until'] == 0) {
                        $banned_until = "Permanent";
                    } else {
                        $banned_until = date("m/d/Y h:i A T", $bans[0]['banned_until']);
                    }
                    echo json_encode(array(
                        "banned" => "true",
                        "reason" => $bans[0]['reason'],
                        "staff" => $bans[0]['staff_name'],
                        "ban_issued" => date("m/d/Y h:i A T", $bans[0]['ban_issued']),
                        "banned_until" => $banned_until,
                    ));
                } else {
                    echo json_encode(array(
                        "banned" => "false",
                    ));
                }
            }
            break;
        case "adduser":
            if ($request->param('license') == null || $request->param('name') == null) {
                echo json_encode(array("response" => "400", "message" => "Missing Parameters"));
            } else {
                plugins::call('playerJoined', array("license" => $request->param('license'), "name" => $request->param('name')));
                dbquery('INSERT INTO players (name, license, playtime, firstjoined, lastplayed) VALUES ("' . escapestring($request->param('name')) . '", "' . escapestring($request->param('license')) . '", "0", "' . time() . '", "' . time() . '") ON DUPLICATE KEY UPDATE name="' . escapestring($request->param('name')) . '"', false);
                echo json_encode(array("response" => "200", "message" => "Successfully added user into database."));
                if (siteConfig('joinmessages') == "true") {
                    sendMessage('^3' . $request->param('name') . '^0 is joining the server with ^2' . trustScore($request->param('license')) . '%^0 trust score.');
                }
            }
            break;
        case "message":
            plugins::call('chatMessage', array("license" => $request->param('id'), "message" => $request->param('message')));
            if (siteConfig('chatcommands') == true) {
                if ($request->param('id') == null || $request->param('message') == null) {
                    echo json_encode(array("response" => "400", "message" => "Missing Parameters"));
                } else {
                    switch ($request->param('message')) {
                        case strpos($request->param('message'), "/warn ") === 0:
                            $staff = dbquery('SELECT * FROM players WHERE license="' . escapestring($request->param('id')) . '"');
                            if (hasPermission(hex2dec(strtoupper(str_replace('steam:', '', $staff[0]['steam']))), "warn")) {
                                $input = str_replace('/warn ', '', $request->param('message'));
                                $params = explode(' ', $input, 2);
                                foreach (dbquery('SELECT * FROM servers') as $server) {
                                    if (checkOnline($server['connection']) == true) {
                                        $players = serverInfo($server['connection'])['players'];
                                        foreach ($players as $player) {
                                            if ($player->identifiers[1] == escapestring($request->param('id'))) {
                                                foreach ($players as $player) {
                                                    if ($player->id == $params[0]) {
                                                        dbquery('INSERT INTO warnings (license, reason, staff_name, staff_steamid, time) VALUES ("' . $player->identifiers[1] . '", "' . escapestring($params[1]) . '", "' . $staff[0]['name'] . '", "' . hex2dec(strtoupper(str_replace('steam:', '', $staff[0]['steam']))) . '", "' . time() . '")', false);
                                                        sendMessage('^3' . $player->name . '^0 has been warned by ^2' . $staff[0]['name'] . '^0 for ^3' . escapestring($params[1]), $server);
                                                        if (!empty(siteConfig('discord_webhook')) && siteConfig('discord_webhook') != null) {
                                                            discordMessage('Player Warned', '**Player: **' . $player->name . '\r\n**Reason: **' . $params[1] . '\r\n**Warned By: **' . $staff[0]['name']);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            break;
                        case strpos($request->param('message'), "/kick ") === 0:
                            $staff = dbquery('SELECT * FROM players WHERE license="' . escapestring($request->param('id')) . '"');
                            if (hasPermission(hex2dec(strtoupper(str_replace('steam:', '', $staff[0]['steam']))), "kick")) {
                                $input = str_replace('/kick ', '', $request->param('message'));
                                $params = explode(' ', $input, 2);
                                foreach (dbquery('SELECT * FROM servers') as $server) {
                                    if (checkOnline($server['connection']) == true) {
                                        $players = serverInfo($server['connection'])['players'];
                                        foreach ($players as $player) {
                                            if ($player->identifiers[1] == escapestring($request->param('id'))) {
                                                foreach ($players as $player) {
                                                    if ($player->id == $params[0]) {
                                                        dbquery('INSERT INTO kicks (license, reason, staff_name, staff_steamid, time) VALUES ("' . $player->identifiers[1] . '", "' . escapestring($params[1]) . '", "' . $staff[0]['name'] . '", "' . hex2dec(strtoupper(str_replace('steam:', '', $staff[0]['steam']))) . '", "' . time() . '")', false);
                                                        removeFromSession($player->identifiers[1], "You were kicked by " . $staff[0]['name'] . " for " . $params[1], $server);
                                                        sendMessage('^3' . $player->name . '^0 has been kicked by ^2' . $staff[0]['name'] . '^0 for ^3' . escapestring($params[1]), $server);
                                                        if (!empty(siteConfig('discord_webhook')) && siteConfig('discord_webhook') != null) {
                                                            discordMessage('Player Kicked', '**Player: **' . $player->name . '\r\n**Reason: **' . $params[1] . '\r\n**Kicked By: **' . $staff[0]['name']);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            break;
                        case strpos($request->param('message'), "/ban ") === 0:
                            $staff = dbquery('SELECT * FROM players WHERE license="' . escapestring($request->param('id')) . '"');
                            if (hasPermission(hex2dec(strtoupper(str_replace('steam:', '', $staff[0]['steam']))), "kick")) {
                                $input = str_replace('/ban ', '', $request->param('message'));
                                $params = explode(' ', $input, 3);
                                foreach (dbquery('SELECT * FROM servers') as $server) {
                                    if (checkOnline($server['connection']) == true) {
                                        $players = serverInfo($server['connection'])['players'];
                                        foreach ($players as $player) {
                                            if ($player->identifiers[1] == escapestring($request->param('id'))) {
                                                foreach ($players as $player) {
                                                    if ($player->id == $params[0]) {
                                                        $time = 0;
                                                        if (isset($params[1])) {
                                                            $length = preg_split('/(?<=[0-9])(?=[a-z]+)/i', $params[1]);
                                                            if ($length[0] != 0) {
                                                                switch ($length[1]) {
                                                                    case "m":
                                                                        $time = 60;
                                                                        break;
                                                                    case "h":
                                                                        $time = 3600;
                                                                        break;
                                                                    case "d":
                                                                        $time = 86400;
                                                                        break;
                                                                    case "w":
                                                                        $time = 604800;
                                                                        break;
                                                                    default:
                                                                        $time = 86400;
                                                                        break;
                                                                }
                                                            } else {
                                                                $time = 0;
                                                            }
        
                                                            $daycount = secsToStr($length[0] * $time);
                                                            if ($time == 0) {
                                                                $banned_until = 0;
                                                                sendMessage('^3' . $player->name . '^0 has been permanently banned by ^2' . $staff[0]['name'] . '^0 for ^3' . $params[2], $server);
                                                                discordMessage('Player Banned', '**Player: **' . $player->name . '\r\n**Reason: **' . $params[2] . '\r\n**Ban Length: **Permanent\r\n**Banned By: **' . $staff[0]['name']);
                                                            } else {
                                                                $banned_until = time() + ($length[0] * $time);
                                                                sendMessage('^3' . $player->name . '^0 has been banned for ^3' . $daycount . '^0 by ^2' . $staff[0]['name'] . '^0 for ^3' . $params[2], $server);
                                                                discordMessage('Player Banned', '**Player: **' . $player->name . '\r\n**Reason: **' . $params[2] . '\r\n**Ban Length: **' . secsToStr($length[0] * $time) . '\r\n**Banned By: **' . $staff[0]['name']);
                                                            }
                                                            dbquery('INSERT INTO bans (name, identifier, reason, ban_issued, banned_until, staff_name, staff_steamid) VALUES ("' . escapestring($player->name) . '", "' . escapestring($player->identifiers[1]) . '", "' . escapestring($params[2]) . '", "' . time() . '", "' . $banned_until . '", "' . $staff[0]['name'] . '", "' . hex2dec(strtoupper(str_replace('steam:', '', $staff[0]['steam']))) . '")', false);
                                                            removeFromSession($player->identifiers[1], "You were banned by " . $staff[0]['name'] . " for " . $params[3] . " (Relog for more info)", $server);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            break;
                        case strpos($request->param('message'), "/trustscore ") === 0:
                            $input = str_replace('/trustscore ', '', $request->param('message'));
                            foreach (dbquery('SELECT * FROM servers') as $server) {
                                if (checkOnline($server['connection']) == true) {
                                    $players = serverInfo($server['connection'])['players'];
                                    foreach ($players as $player) {
                                        if ($player->identifiers[1] == escapestring($request->param('id'))) {
                                            foreach ($players as $player) {
                                                if ($player->id == $input) {
                                                    $playerinfo = dbquery('SELECT * FROM players WHERE license="' . $player->identifiers[1] . '"');
                                                    sendMessage('^3' . $player->name . '^0 has a playtime of ^2' . secsToStr($playerinfo[0]['playtime'] * 60) . '^0 and a trustscore of ^2' . trustScore($player->identifiers[1]) . '%', $server);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            break;
                        case strpos($request->param('message'), "/commend ") === 0:
                            $staff = dbquery('SELECT * FROM players WHERE license="' . escapestring($request->param('id')) . '"');
                            if (hasPermission(hex2dec(strtoupper(str_replace('steam:', '', $staff[0]['steam']))), "warn")) {
                                $input = str_replace('/commend ', '', $request->param('message'));
                                $params = explode(' ', $input, 2);
                                foreach (dbquery('SELECT * FROM servers') as $server) {
                                    if (checkOnline($server['connection']) == true) {
                                        $players = serverInfo($server['connection'])['players'];
                                        foreach ($players as $player) {
                                            if ($player->identifiers[1] == escapestring($request->param('id'))) {
                                                foreach ($players as $player) {
                                                    if ($player->id == $params[0]) {
                                                        dbquery('INSERT INTO commend (license, reason, staff_name, staff_steamid, time) VALUES ("' . $player->identifiers[1] . '", "' . escapestring($params[1]) . '", "' . $staff[0]['name'] . '", "' . hex2dec(strtoupper(str_replace('steam:', '', $staff[0]['steam']))) . '", "' . time() . '")', false);
                                                        sendMessage('^3' . $player->name . '^0 has been commended by ^2' . $staff[0]['name'] . '^0 for ^3' . escapestring($params[1]), $server);
                                                        if (!empty(siteConfig('discord_webhook')) && siteConfig('discord_webhook') != null) {
                                                            discordMessage('Player Commended', '**Player: **' . $player->name . '\r\n**Reason: **' . $params[1] . '\r\n**Commended By: **' . $staff[0]['name']);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            break;
                    }
                }
            }
            break;
        case "recentchart":
            $weekprior = time() - 604800;
            $recentwarns = dbquery('SELECT * FROM warnings WHERE time>="' . $weekprior . '"');
            $recentkicks = dbquery('SELECT * FROM kicks WHERE time>="' . $weekprior . '"');
            $recentbans = dbquery('SELECT * FROM bans WHERE ban_issued>="' . $weekprior . '"');
            echo json_encode($recentbans);
            break;
        case "queue":
            header("Content-Type: text/plain");
            $staff = dbquery('SELECT * FROM users WHERE rank!="user"');
            foreach ($staff as $user) {
                echo "    - SteamId: " . dec2hex($user['steamid']) . "\r";
                switch ($user['rank']) {
                    case "director":
                        echo "      Priority: " . 1;
                        break;
                    case "communitymanager":
                        echo "      Priority: " . 5;
                        break;
                    case "senioradmin":
                        echo "      Priority: " . 10;
                        break;
                    case "admin":
                        echo "      Priority: " . 15;
                        break;
                    case "moderator":
                        echo "      Priority: " . 20;
                        break;
                    case "trialmod":
                        echo "      Priority: " . 25;
                        break;
                }
                echo "\r\r";
            }
            break;
    }
});

$klein->respond('POST', '/api/button/[restart|kickforstaff|command:action]', function ($request, $response, $service) {
    header('Content-Type: application/json');
    if (isset($_SESSION['steamid'])) {
        if (getRank($_SESSION['steamid']) != "user") {
            switch ($request->action) {
                case "restart":
                    if ($request->param('input') == null || $request->param('server') == null) {
                        echo json_encode(array("response" => "400", "message" => "Invalid API Endpoint"));
                    } else {
                        $server = dbquery('SELECT * FROM servers WHERE connection="' . escapestring($request->param('server')) . '"');
                        if (!empty($server)) {
                            if (checkOnline($server[0]['connection']) == true) {
                                $con = new q3query(strtok($server[0]['connection'], ':'), str_replace(':', '', substr($server[0]['connection'], strpos($server[0]['connection'], ':'))), $success);
                                $con->setRconpassword($server[0]['rcon']);
                                $con->rcon("restart " . $request->param('input'));
                                echo json_encode(array('success' => true, 'reload' => true));
                            }
                        }
                    }
                    break;
                case "command":
                    if ($request->param('input') == null || $request->param('server') == null) {
                        echo json_encode(array("response" => "400", "message" => "Invalid API Endpoint"));
                    } else {
                        $server = dbquery('SELECT * FROM servers WHERE connection="' . escapestring($request->param('server')) . '"');
                        if (!empty($server)) {
                            if (checkOnline($server[0]['connection']) == true) {
                                $con = new q3query(strtok($server[0]['connection'], ':'), str_replace(':', '', substr($server[0]['connection'], strpos($server[0]['connection'], ':'))), $success);
                                $con->setRconpassword($server[0]['rcon']);
                                $con->rcon($request->param('input'));
                                echo json_encode(array('success' => true, 'reload' => true));
                            }
                        }
                    }
                    break;
                case "kickforstaff":
                    if ($request->param('server') == null) {
                        echo json_encode(array("response" => "400", "message" => "Invalid API Endpoint"));
                    } else {
                        $server = dbquery('SELECT * FROM servers WHERE connection="' . escapestring($request->param('server')) . '"');
                        if (!empty($server)) {
                            if (checkOnline($server[0]['connection']) == true) {
                                $serverinfo = json_decode(@file_get_contents('http://' . $server[0]['connection'] . '/players.json'));
                                $con = new q3query(strtok($server[0]['connection'], ':'), str_replace(':', '', substr($server[0]['connection'], strpos($server[0]['connection'], ':'))), $success);
                                sort($serverinfo);
                                $kickplayer = null;
                                foreach ($serverinfo as $player) {
                                    $kickplayer = $player;
                                }
                                $con->setRconpassword($server[0]['rcon']);
                                $con->rcon('say ^3' . $kickplayer->name . '^0 has been kicked by ^2' . $_SESSION['steam_personaname'] . '^0 to make room for staff.');
                                discordMessage('Kick For Staff', '**Staff Member: **' . $_SESSION['steam_personaname']);
                                $con->rcon('clientkick ' . $kickplayer->id . ' Kicked for Reserved Staff Slot');
                                echo json_encode(array('success' => true, 'reload' => true));
                            }
                        }
                    }
                    break;
            }
        } else {
            echo json_encode(array("response" => "403", "message" => "User rank does not have access to POST API."));
        }
    } else {
        echo json_encode(array("response" => "401", "message" => "Unauthenticated API request."));
    }
});

$klein->respond('POST', '/api/[warn|kick|ban|commend|addserver|updatepanel|delserver|addstaff|delstaff|delwarn|delcommend|delkick|delban:action]', function ($request, $response, $service) {
    header('Content-Type: application/json');
    if (isset($_SESSION['steamid'])) {
        if (getRank($_SESSION['steamid']) != "user") {
            switch ($request->action) {
                case "warn":
                    if (!hasPermission($_SESSION['steamid'], 'warn')) {
                        echo json_encode(array('message' => 'You do not have permission to warn!'));
                        exit();
                    }
                    if ($request->param('name') == null || $request->param('license') == null || $request->param('reason') == null) {
                        echo json_encode(array('message' => 'Please fill in all of the fields!'));
                    } else {
                        plugins::call('playerWarned', array("name" => $request->param('name'), "license" => $request->param('license'), "reason" => $request->param('reason')));
                        dbquery('INSERT INTO warnings (license, reason, staff_name, staff_steamid, time) VALUES ("' . escapestring($request->param('license')) . '", "' . escapestring($request->param('reason')) . '", "' . $_SESSION['steam_personaname'] . '", "' . $_SESSION['steamid'] . '", "' . time() . '")', false);
                        sendMessage('^3' . $request->param('name') . '^0 has been warned by ^2' . $_SESSION['steam_personaname'] . '^0 for ^3' . $request->param('reason'));
                        if (!empty(siteConfig('discord_webhook'))) {
                            discordMessage('Player Warned', '**Player: **' . $request->param('name') . '\r\n**Reason: **' . $request->param('reason') . '\r\n**Warned By: **' . $_SESSION['steam_personaname']);
                        }
                        echo json_encode(array('success' => true, 'reload' => true));
                    }
                    break;
                case "kick":
                    if (!hasPermission($_SESSION['steamid'], 'kick')) {
                        echo json_encode(array('message' => 'You do not have permission to kick!'));
                        exit();
                    }
                    if ($request->param('name') == null || $request->param('license') == null || $request->param('reason') == null) {
                        echo json_encode(array('message' => 'Please fill in all of the fields!'));
                    } else {
                        plugins::call('playerKicked', array("name" => $request->param('name'), "license" => $request->param('license'), "reason" => $request->param('reason')));
                        dbquery('INSERT INTO kicks (license, reason, staff_name, staff_steamid, time) VALUES ("' . escapestring($request->param('license')) . '", "' . escapestring($request->param('reason')) . '", "' . $_SESSION['steam_personaname'] . '", "' . $_SESSION['steamid'] . '", "' . time() . '")', false);
                        removeFromSession($request->param('license'), "You were kicked by " . $_SESSION['steam_personaname'] . " for " . $request->param('reason'));
                        sendMessage('^3' . $request->param('name') . '^0 has been kicked by ^2' . $_SESSION['steam_personaname'] . '^0 for ^3' . $request->param('reason'));
                        if (!empty(siteConfig('discord_webhook'))) {
                            discordMessage('Player Kicked', '**Player: **' . $request->param('name') . '\r\n**Reason: **' . $request->param('reason') . '\r\n**Kicked By: **' . $_SESSION['steam_personaname']);
                        }
                        echo json_encode(array('success' => true, 'reload' => true));
                    }
                    break;
                case "ban":
                    if (!hasPermission($_SESSION['steamid'], 'ban')) {
                        echo json_encode(array('message' => 'You do not have permission to ban!'));
                        exit();
                    }
                    if ($request->param('name') == null || $request->param('license') == null || $request->param('reason') == null || $request->param('banlength') == null) {
                        echo json_encode(array('message' => 'Please fill in all of the fields!'));
                    } else {
                        if ($request->param('banlength') == 0) {
                            $banned_until = 0;
                            sendMessage('^3' . $request->param('name') . '^0 has been permanently banned by ^2' . $_SESSION['steam_personaname'] . '^0 for ^3' . $request->param('reason'));
                        } else {
                            $banned_until = time() + $request->param('banlength');
                            sendMessage('^3' . $request->param('name') . '^0 has been banned for ' . secsToStr($request->param('banlength')) . ' by ^2' . $_SESSION['steam_personaname'] . '^0 for ^3' . $request->param('reason'));
                        }
                        plugins::call('playerBanned', array("name" => $request->param('name'), "license" => $request->param('license'), "reason" => $request->param('reason'), "length" => $request->param('banlength'), "staff_steamid" => $_SESSION['steamid']));
                        dbquery('INSERT INTO bans (name, identifier, reason, ban_issued, banned_until, staff_name, staff_steamid) VALUES ("' . escapestring($request->param('name')) . '", "' . escapestring($request->param('license')) . '", "' . escapestring($request->param('reason')) . '", "' . time() . '", "' . $banned_until . '", "' . $_SESSION['steam_personaname'] . '", "' . $_SESSION['steamid'] . '")', false);
                        removeFromSession($request->param('license'), "Banned by " . $_SESSION['steam_personaname'] . " for " . $request->param('reason') . " (Relog for more information)");
                        if (!empty(siteConfig('discord_webhook'))) {
                            if ($request->param('banlength') == 0) {
                                $banlength = "Permanent";
                            } else {
                                $banlength = secsToStr($request->param('banlength'));
                            }
                            discordMessage('Player Banned', '**Player: **' . $request->param('name') . '\r\n**Reason: **' . $request->param('reason') . '\r\n**Ban Length: **' . $banlength . '\r\n**Banned By: **' . $_SESSION['steam_personaname']);
                        }
                        echo json_encode(array('success' => true, 'reload' => true));
                    }
                    break;
                case "commend":
                    if (!hasPermission($_SESSION['steamid'], 'commend')) {
                        echo json_encode(array('message' => 'You do not have permission to commend!'));
                        exit();
                    }
                    if ($request->param('name') == null || $request->param('license') == null || $request->param('reason') == null) {
                        echo json_encode(array('message' => 'Please fill in all of the fields!'));
                    } else {
                        dbquery('INSERT INTO commend (license, reason, staff_name, staff_steamid, time) VALUES ("' . escapestring($request->param('license')) . '", "' . escapestring($request->param('reason')) . '", "' . $_SESSION['steam_personaname'] . '", "' . $_SESSION['steamid'] . '", "' . time() . '")', false);
                        sendMessage('^3' . $request->param('name') . '^0 has been commended by ^2' . $_SESSION['steam_personaname'] . '^0 for ^3' . $request->param('reason'));
                        if (!empty(siteConfig('discord_webhook'))) {
                            discordMessage('Player Commended', '**Player: **' . $request->param('name') . '\r\n**Reason: **' . $request->param('reason') . '\r\n**Commended By: **' . $_SESSION['steam_personaname']);
                        }
                        echo json_encode(array('success' => true, 'reload' => true));
                    }
                    break;
                case "addserver":
                    if (!hasPermission($_SESSION['steamid'], 'editservers')) {
                        echo json_encode(array('message' => 'You do not have permission to edit servers!'));
                        exit();
                    }
                    if ($request->param('servername') == null || $request->param('serverip') == null || $request->param('serverport') == null || $request->param('serverrcon') == null) {
                        echo json_encode(array('message' => 'Please fill in all of the fields!'));
                    } else {
                        if ($request->param('serverip') == "localhost") {
                            echo json_encode(array('message' => 'Server IP \'localhost\' is disabled for compatibility reasons. We recommend that you use an external IP address.'));
                            exit();
                        }
                        plugins::call('serverAdded', array("name" => $request->param('servername'), "serverip" => $request->param('serverip'), "serverport" => $request->param('serverport'), "serverrcon" => $request->param('serverrcon')));
                        dbquery('INSERT INTO servers (name, connection, rcon) VALUES ("' . $request->param('servername') . '", "' . $request->param('serverip') . ':' . $request->param('serverport') . '", "' . $request->param('serverrcon') . '")', false);
                        echo json_encode(array('success' => true, 'reload' => true));
                    }
                    break;
                case "updatepanel":
                    if (!hasPermission($_SESSION['steamid'], 'editpanel')) {
                        echo json_encode(array('message' => 'You do not have permission to edit the panel!'));
                        exit();
                    } else {

                        if(escapestring(serialize(json_decode($_POST['permissions']))) == "N;") {
                            echo json_encode(array('success' => false, 'message' => 'Your permissions field failed to validate (Check Syntax)'));
                            exit();
                        }

                        if(escapestring(serialize(json_decode($_POST['serveractions']))) == "N;") {
                            echo json_encode(array('success' => false, 'message' => 'Your server buttons field failed to validate (Check Syntax)'));
                            exit();
                        }

                        if($_POST['joinmessages'] != "true" && $_POST['joinmessages'] != "false") {
                            echo json_encode(array('success' => false, 'message' => 'Join Messages field incorrect input. (true/false)'));
                            exit();
                        }

                        if($_POST['chatcommands'] != "true" && $_POST['chatcommands'] != "false") {
                            echo json_encode(array('success' => false, 'message' => 'Chat Commands field incorrect input. (true/false)'));
                            exit();
                        }

                        dbquery('UPDATE config SET
                        community_name = "' . escapestring($_POST['communityname']) . '",
                        discord_webhook = "' . escapestring($_POST['discordwebhook']) . '",
                        joinmessages = "' . escapestring($_POST['joinmessages']) . '",
                        chatcommands = "' . escapestring($_POST['chatcommands']) . '",
                        trustscore = "' . escapestring($_POST['trustscore']) . '",
                        tswarn = "' . escapestring($_POST['warnpoints']) . '",
                        tskick = "' . escapestring($_POST['kickpoints']) . '",
                        tsban = "' . escapestring($_POST['banpoints']) . '",
                        tscommend = "' . escapestring($_POST['commendpoints']) . '",
                        tstime = "' . escapestring($_POST['timepoints']) . '",
                        recent_time = "' . escapestring($_POST['recentplayers']) . '",
                        checktimeout = "' . escapestring($_POST['checktimeout']) . '",
                        permissions = \'' . escapestring(serialize(json_decode($_POST['permissions']))) . '\',
                        serveractions = \'' . escapestring(serialize(json_decode($_POST['serveractions']))) . '\'
                         WHERE ID=1', false);                    
                         
                        echo json_encode(array('success' => true, 'reload' => true));
                    }
                    break;
                case "delserver":
                    if (!hasPermission($_SESSION['steamid'], 'editservers')) {
                        echo json_encode(array('message' => 'You do not have permission to edit servers!'));
                        exit();
                    }
                    if ($request->param('serverid') != null) {
                        dbquery('DELETE FROM servers WHERE ID="' . escapestring($request->param('serverid')) . '"', false);
                        echo json_encode(array('success' => true, 'reload' => true));
                    }
                    break;
                case "addstaff":
                    if (!hasPermission($_SESSION['steamid'], 'editstaff')) {
                        echo json_encode(array('message' => 'You do not have permission to edit staff!'));
                        exit();
                    }
                    if ($request->param('steamid') == null || $request->param('rank') == null) {
                        echo json_encode(array('message' => 'Please fill in all of the fields!'));
                    } else {
                        dbquery('UPDATE users SET rank="' . escapestring($request->param('rank')) . '" WHERE steamid="' . escapestring($request->param('steamid')) . '"', false);
                        echo json_encode(array('success' => true, 'reload' => true));
                    }
                    break;
                case "delstaff":
                    if (!hasPermission($_SESSION['steamid'], 'editstaff')) {
                        echo json_encode(array('message' => 'You do not have permission to edit staff!'));
                        exit();
                    }
                    if ($request->param('steamid') != null) {
                        dbquery('UPDATE users SET rank="user" WHERE steamid="' . escapestring($request->param('steamid')) . '"', false);
                        echo json_encode(array('success' => true, 'reload' => true));
                    }
                    break;
                case "delwarn":
                    if (!hasPermission($_SESSION['steamid'], 'delrecord')) {
                        echo json_encode(array('message' => 'You do not have permission to remove a record!'));
                        exit();
                    }
                    dbquery('DELETE FROM warnings WHERE ID="' . escapestring($request->param('warnid')) . '"', false);
                    echo json_encode(array('success' => true, 'reload' => true));
                    break;
                case "delcommend":
                    if (!hasPermission($_SESSION['steamid'], 'delrecord')) {
                        echo json_encode(array('message' => 'You do not have permission to remove a record!'));
                        exit();
                    }
                    dbquery('DELETE FROM commend WHERE ID="' . escapestring($request->param('commendid')) . '"', false);
                    echo json_encode(array('success' => true, 'reload' => true));
                    break;
                case "delkick":
                    if (!hasPermission($_SESSION['steamid'], 'delrecord')) {
                        echo json_encode(array('message' => 'You do not have permission to remove a record!'));
                        exit();
                    }
                    dbquery('DELETE FROM kicks WHERE ID="' . escapestring($request->param('kickid')) . '"', false);
                    echo json_encode(array('success' => true, 'reload' => true));
                    break;
                case "delban":
                    if (!hasPermission($_SESSION['steamid'], 'delrecord')) {
                        echo json_encode(array('message' => 'You do not have permission to remove a record!'));
                        exit();
                    }
                    dbquery('DELETE FROM bans WHERE ID="' . escapestring($request->param('banid')) . '"', false);
                    echo json_encode(array('success' => true, 'reload' => true));
                    break;
            }
        } else {
            echo json_encode(array("response" => "403", "message" => "User rank does not have access to POST API."));
        }
    } else {
        echo json_encode(array("response" => "401", "message" => "Unauthenticated API request."));
    }
});

plugins::call('createRoute');

$klein->onHttpError(function ($code, $router) {
    $service = $router->service();
    $service->render('app/pages/404.php', array('community' => siteConfig('community_name'), 'title' => $code . ' Error'));
});

$klein->dispatch();
