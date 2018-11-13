<?php
	
	#region Database Settings
	// DATABASE SETTINGS
	$GLOBALS['mysql_host'] = "";								// MySQL Host
	$GLOBALS['mysql_user'] = "";								// MySQL User
	$GLOBALS['mysql_pass'] = "";								// MySQL Password
	$GLOBALS['mysql_db'] = "";									// MySQL Database
	#endregion
	
	// SITE SETTINGS
	$GLOBALS['domainname'] = "";										// URL (and folder if used) with protocol and trailing slash. Example: https://arthurmitchell.xyz/beta/
	$GLOBALS['subfolder'] = ""; 										// If accessing via a sub folder type the sub folder name out like the following: /foldername Example: /staff otherwise leave blank
	$GLOBALS['apikey'] = "";								 			// SteamCommunity API Key https://steamcommunity.com/dev/apikey
	date_default_timezone_set('America/New_York');						// Timezone (http://php.net/manual/en/timezones.php)

?>