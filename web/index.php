<?php

require('config.php');

require('vendor/autoload.php');
$klein = new \Klein\Klein;

$klein->respond('*',function($request,$response,$service){
	session_start();
	require(getcwd() . '/steamauth/steamauth.php');
	require(getcwd() . '/q3query.class.php');
	function escapestring($value){
		$conn = new mysqli($GLOBALS['mysql_host'], $GLOBALS['mysql_user'], $GLOBALS['mysql_pass'], $GLOBALS['mysql_db']);
		if ($conn->connect_errno) {
			die('Could not connect: ' . $conn->connect_error);
		}
		return strip_tags(mysqli_real_escape_string($conn,$value));
	}
	
	function dbquery($sql,$returnresult = true){
		$conn = new mysqli($GLOBALS['mysql_host'], $GLOBALS['mysql_user'], $GLOBALS['mysql_pass'], $GLOBALS['mysql_db']);
		if ($conn->connect_errno) {
			die('Could not connect: ' . $conn->connect_error);
		}
		
		$return = array();
		
		$result = mysqli_query($conn,$sql);
		if($returnresult){
			if(mysqli_num_rows($result) != 0){
				while($r = $result->fetch_assoc()){
					array_push($return,$r);
				}
			} else {
				$return = array();
			}
			return $return;
		}
	}
	
	function checkOnline($site) {
		$curlInit = curl_init(strtok($site, ':'));
		curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,5);
		curl_setopt($curlInit,CURLOPT_PORT,str_replace(':', '', substr($site, strpos($site, ':'))));
		curl_setopt($curlInit,CURLOPT_HEADER,true);
		curl_setopt($curlInit,CURLOPT_NOBODY,true);
		curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);

		$response = curl_exec($curlInit);

		curl_close($curlInit);
		if ($response) return true;
		return false;
	}
	
	function getStats() {
		$warns = 0;
		$kicks = 0;
		$bans = 0;
		
		foreach (dbquery('SELECT * FROM warnings') as $warn) { $warns++; }
		foreach (dbquery('SELECT * FROM kicks') as $kick) { $kicks++; }
		foreach (dbquery('SELECT * FROM bans') as $ban) { $bans++; }
		
		$stats = array(
			'warns' => $warns,
			'kicks' => $kicks,
			'bans' => $bans
		);
		
		return $stats;
	}
	
	function serverInfo($conn) {
		$json = file_get_contents('http://' . $conn . '/players.json');
		$data = json_decode($json);
		
		$players = 0;
		foreach($data as $player) {
			$players++;
		}
		
		sort($data);
		
		$info = array(
			'playercount' => $players,
			'players' => $data
		);
		
		return $info;
	}
	
	function getRank($input) {
		return dbquery('SELECT * FROM users WHERE steamid="'.escapestring($input).'"')[0]['rank'];
	}
	
	function isCron(){
		return true;
	}
	
	function removeFromSession($license, $reason) {
		foreach (dbquery('SELECT * FROM servers') as $server) {
			if(checkOnline($server['connection']) == true) {
				$con = new q3query(strtok($server['connection'], ':'), str_replace(':', '', substr($server['connection'], strpos($server['connection'], ':'))), $success);
				foreach(json_decode(file_get_contents('http://' . $server['connection'] . '/players.json')) as $player) {
					if($player->identifiers[1] == $license) {
						$userid = $player->id;
						$con->setRconpassword($server['rcon']);
						$con->rcon("clientkick $userid $reason");
					}
				}
			}
		}
	}
	
	function sendMessage($message) {
		foreach (dbquery('SELECT * FROM servers') as $server) {
			if(checkOnline($server['connection']) == true) {
				$con = new q3query(strtok($server['connection'], ':'), str_replace(':', '', substr($server['connection'], strpos($server['connection'], ':'))), $success);
				$con->setRconpassword($server['rcon']);
				$con->rcon("say " . $message);
			}
		}	
	}
	
	function trustScore($license) {
		$license = escapestring($license);
		$ts = $GLOBALS['trustscore'];
		
		$info = dbquery('SELECT * FROM players WHERE license="'.$license.'"');
		
		$ts = $ts + floor($info[0]['playtime'] / 60);
		
		if($ts > 100) {
			$ts = 100;
		}
		
		foreach (dbquery('SELECT * FROM warnings WHERE license="'.$license.'"') as $warn) { $ts = $ts - $GLOBALS['tswarn']; }
		foreach (dbquery('SELECT * FROM kicks WHERE license="'.$license.'"') as $kick) { $ts = $ts - $GLOBALS['tskick']; }
		foreach (dbquery('SELECT * FROM bans WHERE identifier="'.$license.'"') as $ban) { $ts = $ts - $GLOBALS['tsban']; }
		
		return $ts;
	}
	
	function secsToStr($duration) {
		$periods = array(
			'Day' => 86400,
			'Hour' => 3600,
			'Minute' => 60,
			'Second' => 1
		);
	 
		$parts = array();
	 
		foreach ($periods as $name => $dur) {
			$div = floor($duration / $dur);
	 
			if ($div == 0)
				continue;
			else
				if ($div == 1)
					$parts[] = $div . " " . $name;
				else
					$parts[] = $div . " " . $name . "s";
			$duration %= $dur;
		}
	 
		$last = array_pop($parts);
	 
		if (empty($parts))
			return $last;
		else
			return join(', ', $parts) . " and " . $last;
	}
	
	function dec2hex($number)
	{
		$hexvalues = array('0','1','2','3','4','5','6','7',
						   '8','9','A','B','C','D','E','F');
		$hexval = '';
		while($number != '0')
		{
			$hexval = $hexvalues[bcmod($number,'16')].$hexval;
			$number = bcdiv($number,'16',0);
		}
		return $hexval;
	}

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
		for($i = 0; $i < strlen($number); $i++)
		{
			$decval = bcadd(bcmul(bcpow('16',$i,0),$decvalues[$number{$i}]), $decval);
		}
		return $decval;
	}
	
	if(!isset($_SESSION['steamid'])){	
		if (strpos($_SERVER['REQUEST_URI'], '/api/') === false) {
			steamLogin();
			exit();	
		}
	} else {
		include(getcwd() . '/steamauth/userInfo.php');
		$user = dbquery('SELECT * FROM users WHERE steamid="' . $_SESSION['steamid'] . '"');
		
		if (strpos($_SERVER['REQUEST_URI'], '/api/') === false) {
			if($user[0]['rank'] == "user") { 
				echo "<center><h2>".$GLOBALS['community_name']." Staff Panel</h2><p>You currently do not have access to the staff panel. Please contact the administration team.</p></center>";
				exit;
			}
		}
	}
});

$klein->respond('GET', '/',function($request,$response,$service){
	$servers = dbquery("SELECT connection FROM servers");
	$players = 0;
	foreach($servers as $server) {
		if(checkOnline($server['connection'])) {
			$players = $players + serverInfo($server['connection'])['playercount'];
		}
	}
	$service->render('app/pages/dashboard.php',array('community'=>$GLOBALS['community_name'],'title'=>'Dashboard','players'=>$players,'stats'=>getStats()));
});

$klein->respond('GET', '/server/[:connection]',function($request,$response,$service){ 
	$connection = escapestring($request->connection);
	if(checkOnline($connection)) {
		$server = dbquery('SELECT * FROM servers WHERE connection="'.$connection.'"');
		if(!empty($server)){
			$service->render('app/pages/server.php',array('community'=>$GLOBALS['community_name'],'title'=>'Server','server'=>$server[0],'info'=>serverInfo($connection)));
		} else {
			throw Klein\Exceptions\HttpException::createFromCode(404);
		}
	} else {
		$service->render('app/pages/offline.php',array('community'=>$GLOBALS['community_name'],'title'=>'Server Offline'));
	}
});

$klein->respond('GET', '/user/[:license]',function($request,$response,$service){ 
	$service->render('app/pages/user.php',array('community'=>$GLOBALS['community_name'],'title'=>'Server','userinfo'=>dbquery('SELECT * FROM players WHERE license="'.escapestring($request->license).'"')[0]));
});

$klein->respond('GET', '/api',function($request,$response,$service){ 
	header('Content-Type: application/json');
	echo json_encode(array("response"=>"400","message"=>"Invalid API Endpoint"));
});

$klein->respond('GET', '/api/[staff|players|servers|bans|warns|kicks|cron|checkban:action]',function($request,$response,$service){ 
	header('Content-Type: application/json');
	switch($request->action){
		case "staff":
			echo json_encode(dbquery('SELECT name, steamid, rank FROM users WHERE rank != "user"'));
		break;
		case "players":
			echo json_encode(dbquery('SELECT * FROM players'));
		break;
		case "servers":
			echo json_encode(dbquery('SELECT ID, name, connection FROM servers'));
		break;
		case "cron":
			if(!isCron()) { 
				throw Klein\Exceptions\HttpException::createFromCode(404);
				exit();
			}
			$servers = dbquery('SELECT ID, name, connection FROM servers');
			foreach($servers as $server) {
				if(checkOnline($server['connection'])) {
					$players = json_decode(file_get_contents('http://' . $server['connection'] . '/players.json'), true);
					foreach($players as $player) {
						dbquery('INSERT INTO players (name, license, steam, firstjoined, lastplayed) VALUES ("' . escapestring($player['name']) . '", "' . escapestring($player['identifiers'][1]) . '", "' . escapestring($player['identifiers'][0]) . '", "' . time() . '", "' . time() . '") ON DUPLICATE KEY UPDATE name="' . escapestring($player['name']) . '", playtime=playtime+1, steam="' . escapestring($player['identifiers'][0]) . '", lastplayed="' . time() . '"' , false);
					}
				}
			}
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
			if($request->param('license') == null) {
				echo json_encode(array("response"=>"400","message"=>"Missing Player Identifier"));
			} else {
				$bans = dbquery('SELECT reason, ban_issued, banned_until, staff_name FROM bans WHERE identifier="'.escapestring($request->param('license')).'" AND (banned_until >= '.time().' OR banned_until = 0)');
				if(!empty($bans)) {
					if($bans[0]['banned_until'] == 0) {
						$banned_until = "Permanent";
					} else {
						$banned_until = date("m/d/Y h:i A T", $bans[0]['banned_until']);
					}
					echo json_encode(array(
						"banned"=>"true",
						"reason"=>$bans[0]['reason'],
						"staff"=>$bans[0]['staff_name'],
						"ban_issued"=>date("m/d/Y h:i A T", $bans[0]['ban_issued']),
						"banned_until"=>$banned_until,
					));
				} else {
					echo json_encode(array(
						"banned"=>"false"
					));
				}
			}
		break;
	}
});

$klein->respond('POST', '/api/button/[restart:action]',function($request,$response,$service){ 
	header('Content-Type: application/json');
	if(isset($_SESSION['steamid'])) {
		if(getRank($_SESSION['steamid']) != "user") {
			switch($request->action){
				case "restart":
					if($request->param('input') == null || $request->param('server') == null) {
						echo json_encode(array("response"=>"400","message"=>"Invalid API Endpoint"));
					} else {
						$server = dbquery('SELECT * FROM servers WHERE connection="'.escapestring($request->param('server')).'"');
						if(!empty($server)) {
							if(checkOnline($server[0]['connection']) == true) {
								$con = new q3query(strtok($server[0]['connection'], ':'), str_replace(':', '', substr($server[0]['connection'], strpos($server[0]['connection'], ':'))), $success);
								$con->setRconpassword($server[0]['rcon']);
								$con->rcon("restart " . $request->param('input'));
								echo json_encode(array('success'=>true,'reload'=>true));
							}
						}
					}
				break;
			}
		} else {
			echo json_encode(array("response"=>"403","message"=>"User rank does not have access to POST API."));
		}
	} else {
		echo json_encode(array("response"=>"401","message"=>"Unauthenticated API request."));
	}
});

$klein->respond('POST', '/api/[warn|kick|ban:action]',function($request,$response,$service){ 
	header('Content-Type: application/json');
	if(isset($_SESSION['steamid'])) {
		if(getRank($_SESSION['steamid']) != "user") {
			switch($request->action){
				case "warn":
					if($request->param('name') == null || $request->param('license') == null || $request->param('reason') == null) {					
						echo json_encode(array('message'=>'Please fill in all of the fields!'));
					} else {
						dbquery('INSERT INTO warnings (license, reason, staff_name, staff_steamid, time) VALUES ("'.escapestring($request->param('license')).'", "'.escapestring($request->param('reason')).'", "'.$_SESSION['steam_personaname'].'", "'.$_SESSION['steamid'].'", "'.time().'")', false);
						sendMessage('^3' . $request->param('name') . '^0 has been warned by ^2' . $_SESSION['steam_personaname'] . '^0 for ^3' . $request->param('reason'));
						echo json_encode(array('success'=>true,'reload'=>true));
					}
				break;
				case "kick":
					if($request->param('name') == null || $request->param('license') == null || $request->param('reason') == null) {					
						echo json_encode(array('message'=>'Please fill in all of the fields!'));	
					} else {
						dbquery('INSERT INTO kicks (license, reason, staff_name, staff_steamid, time) VALUES ("'.escapestring($request->param('license')).'", "'.escapestring($request->param('reason')).'", "'.$_SESSION['steam_personaname'].'", "'.$_SESSION['steamid'].'", "'.time().'")', false);
						removeFromSession($request->param('license'), "You were kicked by " . $_SESSION['steam_personaname'] . " for " . $request->param('reason'));
						sendMessage('^3' . $request->param('name') . '^0 has been kicked by ^2' . $_SESSION['steam_personaname'] . '^0 for ^3' . $request->param('reason'));
						echo json_encode(array('success'=>true,'reload'=>true));
					}
				break;
				case "ban":
					if($request->param('name') == null || $request->param('license') == null || $request->param('reason') == null || $request->param('banlength') == null) {				
						echo json_encode(array('message'=>'Please fill in all of the fields!'));		
					} else {
						if($request->param('banlength') == 0) {
							$banned_until = 0;
							sendMessage('^3' . $request->param('name') . '^0 has been permanently banned by ^2' . $_SESSION['steam_personaname'] . '^0 for ^3' . $request->param('reason'));
						} else {
							$banned_until = time() + $request->param('banlength');
							sendMessage('^3' . $request->param('name') . '^0 has been banned for ' . secsToStr($request->param('banlength')) . ' by ^2' . $_SESSION['steam_personaname'] . '^0 for ^3' . $request->param('reason'));
						}	
						dbquery('INSERT INTO bans (name, identifier, reason, ban_issued, banned_until, staff_name, staff_steamid) VALUES ("'.escapestring($request->param('name')).'", "'.escapestring($request->param('license')).'", "'.escapestring($request->param('reason')).'", "'.time().'", "'.$banned_until.'", "'.$_SESSION['steam_personaname'].'", "'.$_SESSION['steamid'].'")', false);
						removeFromSession($request->param('license'), "Banned by " . $_SESSION['steam_personaname'] . " for " . $request->param('reason') . " (Relog for more information)");
						echo json_encode(array('success'=>true,'reload'=>true));
					}
				break;
			}
		} else {
			echo json_encode(array("response"=>"403","message"=>"User rank does not have access to POST API."));
		}
	} else {
		echo json_encode(array("response"=>"401","message"=>"Unauthenticated API request."));
	}
});

$klein->onHttpError(function ($code, $router) {
	$service = $router->service();
	$service->render('app/pages/404.php',array('community'=>$GLOBALS['community_name'],'title'=>$code.' Error'));
});

$klein->dispatch();