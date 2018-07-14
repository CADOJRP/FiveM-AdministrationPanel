<?php 

	// THE NEWEST VERSION RELEASED ON 7/14/2018 AT 1 AM EST HAS SLIGHTLY CHANGED THE WAY BUTTONS WORK. PLEASE TAKE A LOOK AT THE NEW EXAMPLES BELOW
	// THE NEWEST VERSION RELEASED ON 7/14/2018 AT 1 AM EST HAS SLIGHTLY CHANGED THE WAY BUTTONS WORK. PLEASE TAKE A LOOK AT THE NEW EXAMPLES BELOW
	// THE NEWEST VERSION RELEASED ON 7/14/2018 AT 1 AM EST HAS SLIGHTLY CHANGED THE WAY BUTTONS WORK. PLEASE TAKE A LOOK AT THE NEW EXAMPLES BELOW
	
	// DATABASE SETTINGS
	$GLOBALS['mysql_host'] = "";										// MySQL Host
	$GLOBALS['mysql_user'] = "";										// MySQL User
	$GLOBALS['mysql_pass'] = "";										// MySQL Password
	$GLOBALS['mysql_db'] = "";											// MySQL Database
	
	// SITE SETTINGS
	$GLOBALS['domainname'] = "";										// URL (and folder if used) with protocol and trailing slash. Example: https://arthurmitchell.xyz/beta/
	$GLOBALS['subfolder'] = ""; 										// If accessing via a sub folder type the sub folder name out like the following: /foldername Example: /staff otherwise leave blank
	$GLOBALS['apikey'] = ""; 											// SteamCommunity API Key https://steamcommunity.com/dev/apikey
	$GLOBALS['checktimeout'] = 5;										// How long in seconds until the CRON times out and says the server is offline. Default: 5. If your server takes a long time to respond raise this. (If you have a ton of offline servers lower this)
	$GLOBALS['trustscore'] = 75;										// Trust Score Starting Value
	$GLOBALS['tswarn'] = 3;												// Trust Score Warning Impact
	$GLOBALS['tskick'] = 6;												// Trust Score Kick Impact
	$GLOBALS['tsban'] = 10;												// Trust Score Ban Impact
	$GLOBALS['tstime'] = 1;												// How often (in hours) a user goes up in trust score.
	$GLOBALS['recent_time'] = 10;										// How long players count as recently connected in minutes.
	date_default_timezone_set('America/New_York');						// Timezone (http://php.net/manual/en/timezones.php)


	// COMMUNITY SETTINGS
	$GLOBALS['community_name'] = "";									// Community/Server Name
	$GLOBALS['discord_webhook'] = "";									// Discord Webhook (Blank to Disable)
	

	// SERVER SETTINGS
	$GLOBALS['joinmessages'] = false;									// The chat resource has the default join messages so without remove that this will cause two join messages.
	$GLOBALS['chatcommands'] = true;									// Add default chat commands in game.

	$GLOBALS['permissions'] = [
		"owner"=> [
			"warn",
			"kick",
			"ban",
			"unban",
			"editstaff",
			"editservers",
			"delrecord"
		],
		"communitymanager"=> [
			"warn",
			"kick",
			"ban",
			"unban",
			"editstaff",
			"delrecord"
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
			"kick",
			"ban"
		],
		"trialmod"=> [
			"warn",
			"kick"
		]
	];
	
	
	// SERVER ACTIONS BUTTONS
	$GLOBALS['serveractions'] = [
		"108.61.69.48:30120"=> [
			"kickforstaff"=> [
				"action"=> "kickforstaff",
				"input"=> "",
				"buttonname"=> "Kick For Staff",
				"buttonstyle"=> "btn-warning"
			],
			"aop-blaine"=> [
				"action"=> "command",
				"input"=> "aop Blaine County",
				"buttonname"=> "AOP Blaine",
				"buttonstyle"=> "btn-success"
			],
			"aop-city"=> [
				"action"=> "command",
				"input"=> "aop Los Santos",
				"buttonname"=> "AOP City",
				"buttonstyle"=> "btn-success"
			],
			"aop-paleto"=> [
				"action"=> "command",
				"input"=> "aop Paleto Bay",
				"buttonname"=> "AOP Paleto",
				"buttonstyle"=> "btn-success"
			]
		]
	];
	

	$GLOBALS['debug'] = false;
?>