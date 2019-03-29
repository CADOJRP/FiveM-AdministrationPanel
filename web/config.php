<?php
	// PLEASE READ ALL COMMENTS AFTER PARAMETERS
	// PLEASE READ ALL COMMENTS AFTER PARAMETERS
	// PLEASE READ ALL COMMENTS AFTER PARAMETERS
	

	// DATABASE SETTINGS
	$GLOBALS['mysql_host'] = "";										// MySQL Host
	$GLOBALS['mysql_user'] = "";										// MySQL User
	$GLOBALS['mysql_pass'] = "";										// MySQL Password
	$GLOBALS['mysql_db'] = "";											// MySQL Database
	
	// CRON SETTINGS
	$GLOBALS['phpbin'] = "/opt/cpanel/ea-php70/root/usr/bin/lsphp"; 	// Used for Multithreading (Set to EXE Location for Windows) (Find Linux Bin via cPanel Info) (Defaulted to ELHostingServices)

	// SITE SETTINGS
	$GLOBALS['domainname'] = "http://example.com/example/";				// URL (and folder if used) with protocol and trailing slash. Example: https://arthurmitchell.xyz/beta/
	$GLOBALS['subfolder'] = "/example"; 								// If accessing via a sub folder type the sub folder name out like the following: /foldername Example: /staff otherwise leave blank
	$GLOBALS['apikey'] = "";								 			// SteamCommunity API Key https://steamcommunity.com/dev/apikey
	date_default_timezone_set('America/New_York');						// Timezone (http://php.net/manual/en/timezones.php)
	
?>