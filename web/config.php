<?php
	
	#region Database Settings
	// DATABASE SETTINGS
	$GLOBALS['mysql_host'] = "HOST";								// MySQL Host
	$GLOBALS['mysql_user'] = "USER";								// MySQL User
	$GLOBALS['mysql_pass'] = "PASS";								// MySQL Password
	$GLOBALS['mysql_db'] = "DBASE";									// MySQL Database
	#endregion
	
	// CRON SETTINGS
	$GLOBALS['phpbin'] = "/opt/cpanel/ea-php70/root/usr/bin/lsphp"; // Used for Multithreading (Set to EXE for Windows) (Find via cPanel Info)
	
	// SITE SETTINGS
	$GLOBALS['domainname'] = "https://arthurmitchell.xyz/beta/";	// URL (and folder if used) with protocol and trailing slash. Example: https://arthurmitchell.xyz/beta/
	$GLOBALS['subfolder'] = "/beta"; 								// If accessing via a sub folder type the sub folder name out like the following: /foldername Example: /staff otherwise leave blank
	$GLOBALS['apikey'] = "";						 				// SteamCommunity API Key https://steamcommunity.com/dev/apikey
	date_default_timezone_set('America/New_York');					// Timezone (http://php.net/manual/en/timezones.php)
	
?>