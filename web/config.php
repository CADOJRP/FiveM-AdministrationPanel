<?php
	
	// DATABASE SETTINGS
	$GLOBALS['mysql_host'] = "localhost";								// MySQL Host
	$GLOBALS['mysql_user'] = "";										// MySQL User
	$GLOBALS['mysql_pass'] = "";										// MySQL Password
	$GLOBALS['mysql_db'] = "";											// MySQL Database
	
	// SITE ACCESS SETTINGS
	$GLOBALS['domainname'] = "";										// URL (and folder if used) with protocol and trailing slash. Example: https://arthurmitchell.xyz/beta/
	$GLOBALS['logoutpage'] = "";										// (If running from sub folder name this like the following: /foldername/ with the prefix slash and trailing slash) Example: /staff/
	$GLOBALS['loginpage'] = "";											// Same as above. Example: /staff/
	$GLOBALS['subfolder'] = ""; 										// If accessing via a sub folder type the sub folder name out like the following: /foldername Example: /staff
	$GLOBALS['apikey'] = ""; 											// SteamCommunity API Key https://steamcommunity.com/dev/apikey
	
	// COMMUNITY SETTINGS
	$GLOBALS['community_name'] = "Community Name";						// Community/Server Name
	$GLOBALS['discord_webhook'] = "";									// Discord Webhook (Blank to Disable) NOT CURRENTLY USED 
	
	
	// TRUST SCORE SETTINGS
	$GLOBALS['trustscore'] = 75;										// Trust Score Starting Value
	$GLOBALS['tswarn'] = 1;												// Trust Score Warning Impact
	$GLOBALS['tskick'] = 3;												// Trust Score Kick Impact
	$GLOBALS['tsban'] = 6;												// Trust Score Ban Impact
	
	
	// OTHER SETTINGS
	date_default_timezone_set('America/New_York');						// Timezone (http://php.net/manual/en/timezones.php)
	
	//////////////////////
	//    PERMISSIONS   //
	//  FOR FUTURE USE  //
	//////////////////////
	$GLOBALS['permissions'] = [
		"owner"=> [
			"warn",
			"kick",
			"ban",
			"unban",
			"editstaff",
			"editservers"
		],
		"senioradmin"=> [
			"warn",
			"kick",
			"ban",
			"unban"
		],
		"admin"=> [
			"warn",
			"kick",
			"ban"
		],
		"moderator"=> [
			"warn",
			"kick"
		],
		"trusted"=> [
			"warn"
		]
	];
	
	
	// SERVER ACTIONS BUTTONS
	// Example Provided Below
	$GLOBALS['serveractions'] = [
		"206.221.190.163:30120"=> [
			"els-fivem"=> [
				"action"=> "restart",
				"resource"=> "els-fivem",
				"buttonname"=> "Restart ELS",
				"buttonstyle"=> "btn-warning"
			]
		]
	];
	
	// BREAK SOMETHING OR NEED HELP? JOIN OUR DISCORD https://discord.gg/EgWrfBy
	// BREAK SOMETHING OR NEED HELP? JOIN OUR DISCORD https://discord.gg/EgWrfBy
	// BREAK SOMETHING OR NEED HELP? JOIN OUR DISCORD https://discord.gg/EgWrfBy
	
?>