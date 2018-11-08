<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title><?php echo $this->community . " " . $this->title; ?></title>
		<link rel="icon" type="image/x-icon" href="<?php echo $GLOBALS['domainname']; ?>app/img/favicon.ico"/>
		<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
		<meta name="viewport" content="width=device-width" />
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script>
		<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css"/>
		<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/themes/bootstrap.min.css"/>
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
		<link href='//fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="<?php echo $GLOBALS['domainname']; ?>app/css/bootstrap-duration-picker.css">
		<script src="<?php echo $GLOBALS['domainname']; ?>app/js/bootstrap-duration-picker.js"></script>
		<link href="<?php echo $GLOBALS['domainname']; ?>app/css/pe-icon-7-stroke.css" rel="stylesheet" />
		<link href="<?php echo $GLOBALS['domainname']; ?>app/css/bootstrap.min.css" rel="stylesheet" />
		<link href="<?php echo $GLOBALS['domainname']; ?>app/css/animate.min.css" rel="stylesheet"/>
		<link href="<?php echo $GLOBALS['domainname']; ?>app/css/light-bootstrap-dashboard.css" rel="stylesheet"/>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"/>
		<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
		<link href="//cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css" rel="stylesheet">
		<script src="//cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
		<script src="//cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.41.0/codemirror.min.css" />
		<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.41.0/codemirror.min.js"></script>
		<script src="//cdn.jsdelivr.net/npm/codemirror-formatting@1.0.0/formatting.min.js"></script>
		<script src="//esironal.github.io/cmtouch/mode/javascript/javascript.js"></script>
		<?php
            plugins::call('header');
        ?>
	</head>
	<body>
		<div class="wrapper">
			<div class="sidebar" data-color="blue" data-image="<?php echo $GLOBALS['domainname'] ?>app/img/sidebar-5.jpg">
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
							<div class="collapse" id="serverData">
								<ul class="nav">
									<?php
										$servers = dbquery('SELECT * FROM servers');
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
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="collapse" href="#playerData">
								<i class="pe-7s-display1"></i>
								<p>
									Player Data
									<b class="caret"></b>
								</p>
							</a>
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
						</li>
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
								</li>
						<?php
							}
						?>
						<li class="nav-item">
							<a class="nav-link" data-toggle="collapse" href="#accountData">
								<i class="pe-7s-user"></i>
								<p>
									Account
									<b class="caret"></b>
								</p>
							</a>
							<div class="collapse" id="accountData">
								<ul class="nav">
									<li class="nav-item">
										<a class="nav-link" href="<?php echo $GLOBALS['domainname']; ?>admin/profile/<?php echo $_SESSION['steamid']; ?>">
											<span class="sidebar-normal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Statistics</span>
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" href="<?php echo $GLOBALS['domainname']; ?>?logout">
											<span class="sidebar-normal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Logout</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<?php
							plugins::call('navbarButtons');
						?>
					</ul>
				</div>
			</div>
