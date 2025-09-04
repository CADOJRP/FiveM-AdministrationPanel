<?php
ob_start();

function logoutbutton() {
	echo "<form action='' method='get'><button name='logout' type='submit'>Logout</button></form>"; //logout button
}

function steamLogin() {
	require 'openid.php';
	try {
		$openid = new LightOpenID(Config::get('domainname')); // Use Config::get
		
		if(!$openid->mode) {
			$openid->identity = 'https://steamcommunity.com/openid';
			header('Location: ' . $openid->authUrl());
		} elseif ($openid->mode == 'cancel') {
			error_log('User canceled Steam authentication');
			echo 'Authentication canceled.';
		} else {
			if($openid->validate()) { 
				$id = $openid->identity;
				$ptn = "/^https?:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
				preg_match($ptn, $id, $matches);
				
				$_SESSION['steamid'] = $matches[1];
				include('userInfo.php');
				$ProfileName = htmlspecialchars($steamprofile['personaname'], ENT_QUOTES, 'UTF-8'); // Sanitize
				$ProfileID = $steamprofile['steamid'];
				
				// Use prepared statements to prevent SQL injection
				if(empty(dbquery('SELECT * FROM users', [], true))) {
					dbquery('INSERT INTO users (name, steamid, rank) VALUES (?, ?, ?)', [$ProfileName, $ProfileID, 'owner'], false);
				} else {
					dbquery('INSERT INTO users (name, steamid) VALUES (?, ?) ON DUPLICATE KEY UPDATE name = ?', [$ProfileName, $ProfileID, $ProfileName], false);
				}
				header('Location: ' . Config::get('domainname')); // Use Config::get
				exit;
			} else {
				error_log('Steam validation failed');
				echo "Authentication failed.\n";
			}
		}
	} catch(ErrorException $e) {
		error_log('Steam login error: ' . $e->getMessage());
		echo 'An error occurred during login.';
	}
}

if (isset($_GET['logout'])){
	session_unset();
	session_destroy();
	session_regenerate_id(true); // Regenerate for security
	require(getcwd() . '/config.php');
	header('Location: ' . Config::get('domainname')); // Use Config::get
	exit;
}

if (isset($_GET['update'])){
	unset($_SESSION['steam_uptodate']);
	require 'userInfo.php';
	header('Location: '.$_SERVER['PHP_SELF']);
	exit;
}
?>
?>
