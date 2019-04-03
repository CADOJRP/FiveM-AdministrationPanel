<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title><?php echo $this->community . " &bullet; " . $this->title; ?></title>
		<link rel="icon" type="image/x-icon" href="<?php echo $GLOBALS['subfolder']; ?>/app/img/favicon.ico"/>
		<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
		<meta name="viewport" content="width=device-width" />
		<link rel="stylesheet" href="<?php echo $GLOBALS['subfolder']; ?>/app/css/font-awesome.min.css">
		<link rel="stylesheet" href="<?php echo $GLOBALS['subfolder']; ?>/app/css/jquery-ui.css">
		<script src="<?php echo $GLOBALS['subfolder']; ?>/app/js/jquery.min.js"></script>
		<script src="<?php echo $GLOBALS['subfolder']; ?>/app/js/jquery-1.10.2.js"></script>
		<script src="<?php echo $GLOBALS['subfolder']; ?>/app/js/alertify.min.js"></script>
		<link rel="stylesheet" href="<?php echo $GLOBALS['subfolder']; ?>/app/css/alertify.min.css"/>
		<link rel="stylesheet" href="<?php echo $GLOBALS['subfolder']; ?>/app/css/alertify.bootstrap.min.css"/>
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
		<link href='//fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="<?php echo $GLOBALS['subfolder']; ?>/app/css/bootstrap-duration-picker.css">
		<script src="<?php echo $GLOBALS['subfolder']; ?>/app/js/bootstrap-duration-picker.js"></script>
		<link href="<?php echo $GLOBALS['subfolder']; ?>/app/css/pe-icon-7-stroke.css" rel="stylesheet" />
		<link href="<?php echo $GLOBALS['subfolder']; ?>/app/css/bootstrap.min.css" rel="stylesheet" />
		<link href="<?php echo $GLOBALS['subfolder']; ?>/app/css/animate.min.css" rel="stylesheet"/>
		<link href="<?php echo $GLOBALS['subfolder']; ?>/app/css/light-bootstrap-dashboard.css" rel="stylesheet"/>
		<script src="<?php echo $GLOBALS['subfolder']; ?>/app/js/chart.bundle.min.js"></script>
		<link href="<?php echo $GLOBALS['subfolder']; ?>/app/css/dataTables.bootstrap4.min.css" rel="stylesheet">
		<script src="<?php echo $GLOBALS['subfolder']; ?>/app/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo $GLOBALS['subfolder']; ?>/app/js/dataTables.bootstrap4.min.js"></script>
		<link rel="stylesheet" href="<?php echo $GLOBALS['subfolder']; ?>/app/css/codemirror.min.css" />
		<script src="<?php echo $GLOBALS['subfolder']; ?>/app/js/codemirror.min.js"></script>
		<script src="<?php echo $GLOBALS['subfolder']; ?>/app/js/codemirror.formatting.min.js"></script>
		<script src="<?php echo $GLOBALS['subfolder']; ?>/app/js/codemirror.javascript.style.js"></script>
		<script src="<?php echo $GLOBALS['subfolder']; ?>/app/js/codemirror.css.style.js"></script>
		<link href="<?php echo $GLOBALS['subfolder']; ?>/app/css/select2.min.css" rel="stylesheet" />
		<script src="<?php echo $GLOBALS['subfolder']; ?>/app/js/select2.min.js"></script>
		<?php
			if(isset($_SESSION['steamid'])) {
				$theme = siteConfig('themecss');
				if($theme != "false") {
					echo '
						<style>
							' . $theme . '
						</style>
					';
				}
			}
		?>
	</head>
	<body>
		<div class="wrapper">
			<div class="sidebar" data-color="blue" data-image="<?php echo $GLOBALS['subfolder']; ?>/app/img/sidebar-5.jpg">
				<div class="sidebar-wrapper">
					<div class="logo">
						<div class="simple-text">
							<?php echo $this->community; ?>
						</div>
					</div>
					<ul class="nav">
						<li>
							<a href="<?php echo $GLOBALS['domainname']; ?>">
								<i class="pe-7s-graph"></i>
								<p>Dashboard</p>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="collapse" href="#serverData">
								<i class="pe-7s-server"></i>
								<p>
									Servers
									<b class="caret"></b>
								</p>
							</a>
						</li>
						<div class="collapse" id="serverData">
							<ul class="nav">
								<?php
									$servers = dbquery('SELECT * FROM servers WHERE community="' . userCommunity($_SESSION['steamid']) . '"');
									foreach($servers as $server) {
										echo '
											<li class="nav-item">
												<a class="nav-link" href="' . $GLOBALS['domainname'] . 'server/' . $server['connection'] . '">
													<span class="sidebar-normal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $server['name'] . '</span>
												</a>
											</li>
										';
									}
								?>
							</ul>
						</div>
						<li class="nav-item">
							<a class="nav-link" data-toggle="collapse" href="#playerData">
								<i class="pe-7s-display1"></i>
								<p>
									Player Data
									<b class="caret"></b>
								</p>
							</a>
						</li>
						<div class="collapse" id="playerData">
							<ul class="nav">
								<li class="nav-item">
									<a class="nav-link" href="<?php echo $GLOBALS['domainname']; ?>recent">
										<span class="sidebar-normal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Recent Players List</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="<?php echo $GLOBALS['domainname']; ?>data/players">
										<span class="sidebar-normal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Player List</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="<?php echo $GLOBALS['domainname']; ?>data/commends">
										<span class="sidebar-normal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Commends List</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="<?php echo $GLOBALS['domainname']; ?>data/warns">
										<span class="sidebar-normal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Warning List</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="<?php echo $GLOBALS['domainname']; ?>data/kicks">
										<span class="sidebar-normal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kicks List</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="<?php echo $GLOBALS['domainname']; ?>data/bans">
										<span class="sidebar-normal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bans List</span>
									</a>
								</li>
							</ul>
						</div>
						<?php
							if(hasPermission($_SESSION['steamid'], 'editstaff') || hasPermission($_SESSION['steamid'], 'editservers') || hasPermission($_SESSION['steamid'], 'editpanel')) {
						?>
								<li class="nav-item">
									<a class="nav-link" data-toggle="collapse" href="#settingsData">
										<i class="pe-7s-settings"></i>
										<p>
											Settings
											<b class="caret"></b>
										</p>
									</a>
								</li>
								<div class="collapse" id="settingsData">
									<ul class="nav">
										<?php
											if(hasPermission($_SESSION['steamid'], 'editstaff')) {
												echo '
													<li class="nav-item">
														<a class="nav-link" href="' . $GLOBALS['domainname'] . 'admin/staff">
															<span class="sidebar-normal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit Staff</span>
														</a>
													</li>											
												';
											}

											if(hasPermission($_SESSION['steamid'], 'editservers')) {
												echo '
													<li class="nav-item">
														<a class="nav-link" href="' . $GLOBALS['domainname'] . 'admin/servers">
															<span class="sidebar-normal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit Servers</span>
														</a>
													</li>											
												';
											}

											if(hasPermission($_SESSION['steamid'], 'editpanel')) {
												echo '
													<li class="nav-item">
														<a class="nav-link" href="' . $GLOBALS['domainname'] . 'admin/panel">
															<span class="sidebar-normal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit Panel</span>
														</a>
													</li>											
												';
											}
										?>
									</ul>
								</div>
						<?php
							}
						?>
						<li class="nav-item">
							<a class="nav-link" data-toggle="collapse" data-target="#support" href="#support">
								<i class="pe-7s-help1"></i>
								<p>
									Support
									<b class="caret"></b>
								</p>
							</a>
						</li>
						<div class="collapse" id="support">
							<ul class="nav">
								<li class="nav-item">
									<a class="nav-link" href="<?php echo $GLOBALS['domainname']; ?>support/downloads">
										<span class="sidebar-normal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Downloads</span>
									</a>
								</li>
								<!--<li class="nav-item">
									<a class="nav-link" href="<?php echo $GLOBALS['domainname']; ?>support/tickets">
										<span class="sidebar-normal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Support Tickets</span>
									</a>
								</li>-->
								<li class="nav-item">
									<a class="nav-link" href="https://discord.gg/vFXqGXg" target="_BLANK">
										<span class="sidebar-normal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Discord Server</span>
									</a>
								</li>
							</ul>
						</div>
						<li class="nav-item">
							<a class="nav-link" data-toggle="collapse" href="#accountData">
								<i class="pe-7s-user"></i>
								<p>
									Account
									<b class="caret"></b>
								</p>
							</a>
						</li>
						<div class="collapse" id="accountData">
							<ul class="nav">
								<li class="nav-item">
									<a class="nav-link" href="<?php echo $GLOBALS['domainname']; ?>admin/profile/<?php echo $_SESSION['steamid']; ?>">
										<span class="sidebar-normal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Statistics</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="<?php echo $GLOBALS['domainname']; ?>leave">
										<span class="sidebar-normal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Leave Community</span>
									</a>
								</li>
								<?php
									if(isStaff($_SESSION['steamid'])) {
										echo '
										<li class="nav-item">
											<a class="nav-link" href="' . $GLOBALS['domainname'] . 'support/admin">
												<span class="sidebar-normal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Admin Panel</span>
											</a>
										</li>';
									}
								?>
								<li class="nav-item">
									<a class="nav-link" href="<?php echo $GLOBALS['domainname']; ?>?logout">
										<span class="sidebar-normal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Logout</span>
									</a>
								</li>
							</ul>
						</div>
					</ul>
				</div>
			</div>
