<?php
ob_start();
session_start();

function logoutbutton() {
	echo "<form action='' method='get'><button name='logout' type='submit'>Logout</button></form>"; //logout button
}

function steamLogin() {
	require 'openid.php';
	try {
		$openid = new LightOpenID($GLOBALS['domainname']);
		
		if(!$openid->mode) {
			$openid->identity = 'https://steamcommunity.com/openid';
			header('Location: ' . $openid->authUrl());
		} elseif ($openid->mode == 'cancel') {
			echo 'User has canceled authentication!';
		} else {
			if($openid->validate()) { 
				$id = $openid->identity;
				$ptn = "/^https?:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
				preg_match($ptn, $id, $matches);
				
				$_SESSION['steamid'] = $matches[1];
				if (!headers_sent()) {
					include('userInfo.php');
					$ProfileName = $steamprofile['personaname'];
					$ProfileID = $steamprofile['steamid'];
					$ProfileImage = $steamprofile['avatarfull'];
					$mysqli = new mysqli($GLOBALS['mysql_host'], $GLOBALS['mysql_user'], $GLOBALS['mysql_pass'], $GLOBALS['mysql_db']);
					$query = $mysqli->prepare("INSERT INTO users (name, steamid) VALUES (?, ?) ON DUPLICATE KEY UPDATE name = ?");
					$query->bind_param('sss', $ProfileName, $ProfileID, $ProfileName);
					$query->execute();
					$query->close();
					header('Location: '.$GLOBALS['loginpage']);
					exit;
				} else {
					include('userInfo.php');
					$ProfileName = $steamprofile['personaname'];
					$ProfileID = $steamprofile['steamid'];
					$ProfileImage = $steamprofile['avatarfull'];
					$mysqli = new mysqli($GLOBALS['mysql_host'], $GLOBALS['mysql_user'], $GLOBALS['mysql_pass'], $GLOBALS['mysql_db']);
					$query = $mysqli->prepare("INSERT INTO users (name, steamid) VALUES (?, ?) ON DUPLICATE KEY UPDATE name = ?");
					$query->bind_param('sss', $ProfileName, $ProfileID, $ProfileName);
					$query->execute();
					$query->close();
					exit;
				}
			} else {
				echo "User is not logged in.\n";
			}
		}
	} catch(ErrorException $e) {
		echo $e->getMessage();
	}
}

if (isset($_GET['logout'])){
	session_unset();
	session_destroy();
	require(getcwd() . '/config.php');
	header('Location: '.$GLOBALS['logoutpage']);
	exit;
}

if (isset($_GET['update'])){
	unset($_SESSION['steam_uptodate']);
	require 'userInfo.php';
	header('Location: '.$_SERVER['PHP_SELF']);
	exit;
}

// Version 4.0

?>
