<?php

class Player {
	private $mysql;
	private $license;
	private $panel;

    public function __construct($license){
		$this->mysql = new MySQL();
		$this->panel = new Panel();
		$this->license = $this->mysql->escape($license);
	}

	function kick($reason, $staffid) {
		// Get Staff From Database
		$staff = $this->mysql->query('SELECT * FROM users WHERE steamid="' . $this->mysql->escape($staffid) . '"')[0];

		// Get Player From Database
		$player = $this->mysql->query('SELECT * FROM players WHERE license="' . $this->license . '" AND community="' . $staff['community'] . '"')[0];

		// Insert Kick Into Database
		$this->mysql->query('INSERT INTO kicks 
			(
				license,
				reason,
				staff_name,
				staff_steamid,
				time,
				community
			) VALUES (
				"' . $player['license'] . '",
				"' . $this->mysql->escape($reason) . '",
				"' . $staff['name'] . '",
				"' . $staff['steamid'] . '",
				"' . time() . '",
				"' . $staff['community'] . '"
			)', false);

		// Remove From Game and Send Message
			foreach ($this->mysql->query('SELECT * FROM servers WHERE community="' . $staff['community'] . '"') as $server) {
				$fivem = new q3query(strtok($server['connection'], ':'), str_replace(':', '', substr($server['connection'], strpos($server['connection'], ':'))), $success);
				foreach (json_decode(@file_get_contents('http://' . $server['connection'] . '/players.json'), true) as $playerinfo) {
					if ($playerinfo['identifiers'][1] == $player['license']) {
						$fivem->setRconpassword($server['rcon']);
						$fivem->rcon("staff_kick " . $playerinfo['id'] . " " . $this->mysql->escape($reason));
						$fivem->rcon("staff_sayall ^3" . $player['name'] . '^0 has been kicked by ^2' . $staff['name'] . '^0 for ^3' . $this->mysql->escape($reason));
					}
				}
			}

		// Send Discord Message
			$this->panel->discord('Player Kicked', '**Player: **' . $player['name'] . '\r\n**Reason: **' . $reason . '\r\n**Kicked By: **' . $staff['name'], $staff['community']);
	}

	function warn($reason, $staffid) {
		// Get Staff From Database
		$staff = $this->mysql->query('SELECT * FROM users WHERE steamid="' . $this->mysql->escape($staffid) . '"')[0];

		// Get Player From Database
		$player = $this->mysql->query('SELECT * FROM players WHERE license="' . $this->license . '" AND community="' . $staff['community'] . '"')[0];

		// Insert Warning Into Database
		$this->mysql->query('INSERT INTO warnings 
			(
				license,
				reason,
				staff_name,
				staff_steamid,
				time,
				community
			) VALUES (
				"' . $player['license'] . '",
				"' . $this->mysql->escape($reason) . '",
				"' . $staff['name'] . '",
				"' . $staff['steamid'] . '",
				"' . time() . '",
				"' . $staff['community'] . '"
			)', false);

		// Send Message
			foreach ($this->mysql->query('SELECT * FROM servers WHERE community="' . $staff['community'] . '"') as $server) {
				$fivem = new q3query(strtok($server['connection'], ':'), str_replace(':', '', substr($server['connection'], strpos($server['connection'], ':'))), $success);
				foreach (json_decode(@file_get_contents('http://' . $server['connection'] . '/players.json'), true) as $playerinfo) {
					if ($playerinfo['identifiers'][1] == $player['license']) {
						$fivem->setRconpassword($server['rcon']);
						$fivem->rcon("staff_sayall ^3" . $player['name'] . '^0 has been warned by ^2' . $staff['name'] . '^0 for ^3' . $this->mysql->escape($reason));
					}
				}
			}

		// Send Discord Message
			$this->panel->discord('Player Warned', '**Player: **' . $player['name'] . '\r\n**Reason: **' . $reason . '\r\n**Warned By: **' . $staff['name'], $staff['community']);
	}

	function ban($reason, $staffid, $time) {
		// Get Staff From Database
		$staff = $this->mysql->query('SELECT * FROM users WHERE steamid="' . $this->mysql->escape($staffid) . '"')[0];

		// Get Player From Database
		$player = $this->mysql->query('SELECT * FROM players WHERE license="' . $this->license . '" AND community="' . $staff['community'] . '"')[0];
		

		if($this->mysql->escape($time) == 0) {
			$banexpire = 0;
		} else {
			$banexpire = time() + $this->mysql->escape($time);
		}

		// Insert Ban Into Database
		$this->mysql->query('INSERT INTO bans 
			(
				name,
				identifier,
				reason,
				ban_issued,
				banned_until,
				staff_name,
				staff_steamid,
				community
			) VALUES (
				"' . $player['name'] . '",
				"' . $player['license'] . '",
				"' . $this->mysql->escape($reason) . '",
				"' . time() . '",
				"' . $banexpire . '",
				"' . $staff['name'] . '",
				"' . $staff['steamid'] . '",
				"' . $staff['community'] . '"
			)', false);

		// Remove From Game and Send Message
			foreach ($this->mysql->query('SELECT * FROM servers WHERE community="' . $staff['community'] . '"') as $server) {
				$fivem = new q3query(strtok($server['connection'], ':'), str_replace(':', '', substr($server['connection'], strpos($server['connection'], ':'))), $success);
				foreach (json_decode(@file_get_contents('http://' . $server['connection'] . '/players.json'), true) as $playerinfo) {
					if ($playerinfo['identifiers'][1] == $player['license']) {
						$fivem->setRconpassword($server['rcon']);
						if ($banexpire == 0) {
							$banlength = "Permanent";
							$fivem->rcon("staff_sayall ^3" . $player['name'] . '^0 has been ^3permanently banned by ^2' . $staff['name'] . '^0 for ^3' . $this->mysql->escape($reason));						
                        } else {
							$banlength = $this->panel->timeDuration($time);
							$fivem->rcon("staff_sayall ^3" . $player['name'] . '^0 has been banned for ^3' . $banlength . ' by ^2' . $staff['name'] . '^0 for ^3' . $this->mysql->escape($reason));													
						}
						$fivem->rcon("staff_kick " . $playerinfo['id'] . " You've been banned! Reason: " . $this->mysql->escape($reason) . " (Reconnect For Details)");
						$this->panel->discord('Player Banned', '**Player: **' . $player['name'] . '\r\n**Reason: **' . $reason . '\r\n**Ban Length: **' . $banlength . '\r\n**Banned By: **' . $staff['name'], $staff['community']);
					}
				}
			}
	}
	

	function commend($reason, $staffid) {
		// Get Staff From Database
		$staff = $this->mysql->query('SELECT * FROM users WHERE steamid="' . $this->mysql->escape($staffid) . '"')[0];

		// Get Player From Database
		$player = $this->mysql->query('SELECT * FROM players WHERE license="' . $this->license . '" AND community="' . $staff['community'] . '"')[0];

		// Insert Commend Into Database
		$this->mysql->query('INSERT INTO commend 
			(
				license,
				reason,
				staff_name,
				staff_steamid,
				time,
				community
			) VALUES (
				"' . $player['license'] . '",
				"' . $this->mysql->escape($reason) . '",
				"' . $staff['name'] . '",
				"' . $staff['steamid'] . '",
				"' . time() . '",
				"' . $staff['community'] . '"
			)', false);

		// Send Message
			foreach ($this->mysql->query('SELECT * FROM servers WHERE community="' . $staff['community'] . '"') as $server) {
				$fivem = new q3query(strtok($server['connection'], ':'), str_replace(':', '', substr($server['connection'], strpos($server['connection'], ':'))), $success);
				foreach (json_decode(@file_get_contents('http://' . $server['connection'] . '/players.json'), true) as $playerinfo) {
					if ($playerinfo['identifiers'][1] == $player['license']) {
						$fivem->setRconpassword($server['rcon']);
						$fivem->rcon("staff_sayall ^3" . $player['name'] . '^0 has been commended by ^2' . $staff['name'] . '^0 for ^3' . $this->mysql->escape($reason));
					}
				}
			}

		// Send Discord Message
			$this->panel->discord('Player Commended', '**Player: **' . $player['name'] . '\r\n**Reason: **' . $reason . '\r\n**Commended By: **' . $staff['name'], $staff['community']);
	}
	

	function note($reason, $staffid) {
		// Get Staff From Database
		$staff = $this->mysql->query('SELECT * FROM users WHERE steamid="' . $this->mysql->escape($staffid) . '"')[0];

		// Get Player From Database
		$player = $this->mysql->query('SELECT * FROM players WHERE license="' . $this->license . '" AND community="' . $staff['community'] . '"')[0];

		// Insert Warning Into Database
		$this->mysql->query('INSERT INTO notes 
			(
				license,
				reason,
				staff_name,
				staff_steamid,
				time,
				community
			) VALUES (
				"' . $player['license'] . '",
				"' . $this->mysql->escape($reason) . '",
				"' . $staff['name'] . '",
				"' . $staff['steamid'] . '",
				"' . time() . '",
				"' . $staff['community'] . '"
			)', false);

		// Remove From Game and Send Message
			foreach ($this->mysql->query('SELECT * FROM servers WHERE community="' . $staff['community'] . '"') as $server) {
				$fivem = new q3query(strtok($server['connection'], ':'), str_replace(':', '', substr($server['connection'], strpos($server['connection'], ':'))), $success);
				foreach (json_decode(@file_get_contents('http://' . $server['connection'] . '/players.json'), true) as $playerinfo) {
					if ($playerinfo['identifiers'][1] == $player['license']) {
						$fivem->setRconpassword($server['rcon']);
						$fivem->rcon("staff_sayall Note Added to Player");
					}
				}
			}

		// Send Discord Message
			$this->panel->discord('Player Note', '**Player: **' . $player['name'] . '\r\n**Reason: **' . $reason . '\r\n**Posted By: **' . $staff['name'], $staff['community']);
	}
}

class Server {
	//private $steamid;
	private $mysql;

    public function __construct(){
		$this->mysql = new MySQL();
	}

	public function message($message, $server = null) {
		//return $this->mysql->query('SELECT * FROM users WHERE steamid="' . $this->mysql->escape($steamid) . '"')[0]['community'];
	}
}

class User {
	//private $steamid;
	private $mysql;

    public function __construct(){
		$this->mysql = new MySQL();
	}

	public function community($steamid) {
		return $this->mysql->query('SELECT * FROM users WHERE steamid="' . $this->mysql->escape($steamid) . '"')[0]['community'];
	}
}

class MySQL {
	private $mysql;

    public function __construct(){
        $this->mysql = new mysqli($GLOBALS['mysql_host'], $GLOBALS['mysql_user'], $GLOBALS['mysql_pass'], $GLOBALS['mysql_db']);
	}
	
	function escape($value) {
        if ($this->mysql->connect_errno) {
			error_log('MySQL Could Not Connect: ' . $this->mysql->connect_error);
			return;
		}
        return strip_tags(mysqli_real_escape_string($this->mysql, $value));
	}
	
	function query($value, $returnresult = true) {
        if ($this->mysql->connect_errno) {
			error_log('MySQL Could Not Connect: ' . $this->mysql->connect_error);
			return;
		}

        $return = array();

        $result = mysqli_query($this->mysql, $value);
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
}

class Panel {
	private $mysql;
	private $user;

    public function __construct(){
        $this->mysql = new MySQL();
        $this->user = new User();
	}

	public function discord($title, $message, $community = null)
    {
        if ($community == null) {
            $webhook = $this->mysql->query('SELECT * FROM config WHERE community="' . $this->user->community($_SESSION['steamid']) . '"')[0]['discord_webhook'];
            if (empty($webhook) || $webhook == null) {
                return;
            }
        } else {
            $webhook = $this->mysql->query('SELECT * FROM config WHERE community="' . $this->mysql->escape($community) . '"')[0]['discord_webhook'];
        }

        if ($webhook == "" || $webhook == null) {
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

    public function timeDuration($duration) {
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
	
	public function error($val) {
		error_log($val);
		return true;
	}
}
?>