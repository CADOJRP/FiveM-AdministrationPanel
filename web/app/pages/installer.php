<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>FiveM Administration System Installer</title>
		<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
		<meta name="viewport" content="width=device-width" />
		<link href="<?php echo $GLOBALS['domainname']; ?>app/css/bootstrap.min.css" rel="stylesheet" />
		<link href="<?php echo $GLOBALS['domainname']; ?>app/css/animate.min.css" rel="stylesheet"/>
		<link href="<?php echo $GLOBALS['domainname']; ?>app/css/light-bootstrap-dashboard.css" rel="stylesheet"/>
		<link href="<?php echo $GLOBALS['domainname']; ?>app/css/error.css" rel="stylesheet"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<link rel="stylesheet" href="<?php echo $GLOBALS['domainname']; ?>app/css/bootstrap-duration-picker.css">
		<script src="<?php echo $GLOBALS['domainname']; ?>app/js/bootstrap-duration-picker.js"></script>
		<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script>
		<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css"/>
		<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/themes/bootstrap.min.css"/>
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
		<link href="<?php echo $GLOBALS['domainname']; ?>app/css/pe-icon-7-stroke.css" rel="stylesheet" />
	</head>
	<body>
		<center>
			<div class="cn">
				<div class="inner">
					<div class="row text-center">
						<div class="">
							<div class="panel panel-error panel-underline">
								<div class="panel-heading panel-error-heading">
									<h4>Simple Installer</h4>
								</div>
								<div class="panel-body">
									<?php 
									
										$folder = str_replace('/installer', '', $_SERVER['REQUEST_URI']);
										
										if(!empty($_SERVER['HTTPS'])){
											$url = "https://" . $_SERVER['HTTP_HOST'] . $folder;
										} else {
											$url = "http://" . $_SERVER['HTTP_HOST'] . $folder;
										}
										
										if($folder == "/") { $folder = ""; }
										
										$domainname = $url . "/";
									
										$disabled = "";
									?>
									<form action="<?php echo $domainname ?>api/install" method="post" onsubmit="return submitForm($(this));" style="width: 500px;">
										<div class="form-group">
											<label>System Requirements</label>
												<br>
											<b>BCMath Ext:</b> <?php if (!extension_loaded('bcmath')) { echo "<font color='red'>Missing!</font>"; $disabled = "disabled"; } else { echo "<font color='green'>Installed</font>"; } ?>
												<br>
											<b>CURL Ext:</b> <?php if (!extension_loaded('curl')) { echo "<font color='red'>Missing!</font>"; $disabled = "disabled"; } else { echo "<font color='green'>Installed</font>"; } ?>
												<br>
											<b>URL FOPEN: </b> 
											<?php
											if(ini_get('allow_url_fopen')) { echo "<font color='green'>Enabled</font>"; } else { echo "<font color='red'>Outdated!</font>"; $disabled = "disabled"; }
											?>
												<br>
											<b>PHP 7:</b>
												<?php
												if (version_compare(phpversion(), '7.0.0', '>=')) {
														echo "<font color='green'>Installed</font>";
												} else {
													echo "<font color='red'>Outdated!</font>";
													$disabled = "disabled"; 
												}
												?>
												<br>
										</div>
										<hr>
										<div class="form-group">
											<label>MySQL Settings</label>
											<input type="text" class="form-control" name="mysql_host" placeholder="MySQL Host" required />
										</div>
										<div class="form-group">
											<input type="text" class="form-control" name="mysql_user" placeholder="MySQL User" required />
										</div>
										<div class="form-group">
											<input type="text" class="form-control" name="mysql_pass" placeholder="MySQL Password" required />
										</div>
										<div class="form-group">
											<input type="text" class="form-control" name="mysql_db" placeholder="MySQL Database" required />
										</div>
										<hr>
										<div class="form-group">
											<label>Steam Settings</label>
											<input type="text" class="form-control" name="steam_apikey" placeholder="Steam API Key" required />
										</div>
										<hr>
										<div class="form-group">
											<label>Community Settings</label>
											<input type="text" class="form-control" name="community_name" placeholder="Community/Server Name" required />
										</div>
										<input type="hidden" name="domain" value="<?php echo $domainname; ?>" required />
										<input type="hidden" name="subfolder" value="<?php echo $folder; ?>" required />
										<div id="message"></div>
										<button type="submit" class="btn btn-success btn-fill" style="width: 100%;" <?php echo $disabled; ?>>Complete Installation</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</center>
<?php $this->partial('app/partial/footer.php');?>
