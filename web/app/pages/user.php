<?php $this->partial('app/partial/header.php',array('community'=>$this->community,'title'=>'User: ' . $this->userinfo['name']));?>
	<div class="main-panel">
        <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">User: <?php echo $this->userinfo['name']; ?></a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="?logout">
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="content">
            <div class="container-fluid">
				<div class="overlay-user-container" style="z-index:20">
					<div class="row">
						<div class="col-md-3">
							<center>
								<div class="card card-user" style="margin-top: 70px;">	
									<div class="card-body">
										<div class="author">
											<?php
												$data = json_decode(@file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . $GLOBALS['apikey'] . '&steamids=' . hex2dec(strtoupper(str_replace('steam:', '', $this->userinfo['steam'])))));
												if($this->userinfo['lastplayed'] == null || $this->userinfo['firstjoined'] == null) {
													$this->userinfo['firstjoined'] = time(); 
													$this->userinfo['lastplayed'] = time();
												}
											?>
											<img class="avatar border-gray" id="profile-avatar" src="<?php echo $data->response->players[0]->avatarfull; ?>" alt="Profile Image" onerror="this.src='https://steamuserimages-a.akamaihd.net/ugc/885384897182110030/F095539864AC9E94AE5236E04C8CA7C2725BCEFF/'" draggable="false">
											<h4 class="title" id="profile-username"><?php echo $this->userinfo['name']; ?></h4>
												<hr>
											<span class="description" style="font-weight: normal;">
												Trust Score: <?php echo trustScore($this->userinfo['license']); ?>%
											</span>
												<hr>
											<span class="description" style="font-weight: normal;">
												Playtime: <?php if($this->userinfo['playtime'] != null) { echo secsToStr($this->userinfo['playtime'] * 60); } else { echo "1 Minute"; } ?>
											</span>
												<hr>
											<span class="description" style="font-weight: normal;">
												First Joined: <?php echo date("m/d/Y h:i A", $this->userinfo['firstjoined']); ?>
											</span>
												<hr>
											<span class="description" style="font-weight: normal;">
												Last Played: <?php echo date("m/d/Y h:i A", $this->userinfo['lastplayed']); ?>
											</span>
											<?php
												plugins::call('userInfoTable', array($this->userinfo));
											?>
										</div>
									</div>
									<hr>
									<div class="button-container mr-auto ml-auto">
										<a href="https://steamcommunity.com/profiles/<?php echo hex2dec(strtoupper(str_replace('steam:', '', $this->userinfo['steam']))); ?>" target="_BLANK" class="btn btn-simple btn-link btn-icon">
											<i class="fa fa-steam"></i>
										</a>
									</div>
								</div>
							</center>
						</div>
						<div class="col-md-9">
							<div class="tabbable" id="tabs-125045">
								<ul class="nav nav-tabs">
								    <li class="active">
										<a href="#panel-warn" data-toggle="tab">Warn</a>
									</li>
									<li>
										<a href="#panel-kick" data-toggle="tab">Kick</a>
									</li>
									<li>
										<a href="#panel-ban" data-toggle="tab">Ban</a>
									</li>
									<li>
										<a href="#panel-commend" data-toggle="tab">Commend</a>
									</li>
									<li>
										<a href="#panel-other" data-toggle="tab">Other Actions</a>
									</li>
								</ul>
								<div class="tab-content">
								    <div class="tab-pane active" id="panel-warn">
										<form action="<?php echo $GLOBALS['domainname']; ?>api/warn" method="post" onsubmit="return submitForm($(this));">
											<div class="form-group">
												<label>Warn Reason</label>
												<input type="text" class="form-control" name="reason" />
												<input type="hidden" class="form-control" name="name" value="<?php echo $this->userinfo['name']; ?>" />
												<input type="hidden" class="form-control" name="license" value="<?php echo $this->userinfo['license']; ?>" />
											</div>
											<div id="message"></div>
											<button type="submit" class="btn btn-success btn-fill" style="width: 100%;"><i class="fa fa-paper-plane"></i> &nbsp; Warn Player</button>
										</form>
									</div>
									<div class="tab-pane" id="panel-kick">
										<form action="<?php echo $GLOBALS['domainname']; ?>api/kick" method="post" onsubmit="return submitForm($(this));">
											<div class="form-group">
												<label>Kick Reason</label>
												<input type="text" class="form-control" name="reason" />
												<input type="hidden" class="form-control" name="name" value="<?php echo $this->userinfo['name']; ?>" />
												<input type="hidden" class="form-control" name="license" value="<?php echo $this->userinfo['license']; ?>" />
											</div>
											<div id="message"></div>
											<button type="submit" class="btn btn-success btn-fill" style="width: 100%;"><i class="fa fa-paper-plane"></i> &nbsp; Kick Player</button>
										</form>
									</div>
									<div class="tab-pane" id="panel-ban">
										<form action="<?php echo $GLOBALS['domainname']; ?>api/ban" method="post" onsubmit="return submitForm($(this));">
											<div class="form-group">
												<label>Ban Reason</label>
												<input type="text" class="form-control" name="reason" />
											</div>
											<div class="form-group">
												<label>
													Ban Length (Permanent is all zeros)
												</label>
												<input type="text" class="form-control" id="duration" name="banlength" value="86400">
											</div>
											<input type="hidden" class="form-control" name="name" value="<?php echo $this->userinfo['name']; ?>" />
											<input type="hidden" class="form-control" name="license" value="<?php echo $this->userinfo['license']; ?>" />
											<div id="message"></div>
											<button type="submit" class="btn btn-success btn-fill" style="width: 100%;"><i class="fa fa-paper-plane"></i> &nbsp; Ban Player</button>
										</form>
									</div>
								    <div class="tab-pane" id="panel-commend">
										<form action="<?php echo $GLOBALS['domainname']; ?>api/commend" method="post" onsubmit="return submitForm($(this));">
											<div class="form-group">
												<label>Commendation Reason</label>
												<input type="text" class="form-control" name="reason" />
												<input type="hidden" class="form-control" name="name" value="<?php echo $this->userinfo['name']; ?>" />
												<input type="hidden" class="form-control" name="license" value="<?php echo $this->userinfo['license']; ?>" />
											</div>
											<div id="message"></div>
											<button type="submit" class="btn btn-success btn-fill" style="width: 100%;"><i class="fa fa-paper-plane"></i> &nbsp; Commend Player</button>
										</form>
									</div>
									<div class="tab-pane" id="panel-other">
										<?php
											plugins::call('playerPageOther', array($this->userinfo));
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h3>
								Warnings
							</h3>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>
											Staff
										</th>
										<th>
											Reason
										</th>
										<th>
											Date
										</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$warns = dbquery('SELECT * FROM warnings WHERE license="' . $this->userinfo['license'] . '"');
										if(empty($warns)) {
											echo '
												<tr>
													<td colspan="4">
														<center>
															No Warnings on Record
														</center>
													</td>
												</tr>
											';
										} else {
											foreach($warns as $warn) {
												echo '
													<tr>
														<td>
															' . $warn['staff_name'] . '
														</td>
														<td>
															' . $warn['reason'] . '
														</td>
														<td>
															' . date("m/d/Y h:i A", $warn['time']) . '
														</td>
												';
													if(hasPermission($_SESSION['steamid'], 'delrecord')) {
														echo '
														<form action="'.$GLOBALS['domainname'].'api/delwarn" method="post" onsubmit="return submitForm($(this));">
															<input type="hidden" name="warnid" value="'.$warn['ID'].'" />
															<input type="submit" id="remove-warn-'.$warn['ID'].'" style="display: none;" />
															<td class="table-remove-button"><span class="label label-danger" onclick=\'$("#remove-warn-'.$warn['ID'].'").click();\' style="cursor: pointer;">Remove</span></td>
														</form>
														';
													} else {
														echo '<td></td>';
													}
												echo '
													</tr>
												';
											}
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h3>
								Kicks
							</h3>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>
											Staff
										</th>
										<th>
											Reason
										</th>
										<th>
											Date
										</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$kicks = dbquery('SELECT * FROM kicks WHERE license="' . $this->userinfo['license'] . '"');
										if(empty($kicks)) {
											echo '
												<tr>
													<td colspan="4">
														<center>
															No Kicks on Record
														</center>
													</td>
												</tr>
											';
										} else {
											foreach($kicks as $kick) {
												echo '
													<tr>
														<td>
															' . $kick['staff_name'] . '
														</td>
														<td>
															' . $kick['reason'] . '
														</td>
														<td>
															' . date("m/d/Y h:i A", $kick['time']) . '
														</td>
												';
													if(hasPermission($_SESSION['steamid'], 'delrecord')) {
														echo '
														<form action="'.$GLOBALS['domainname'].'api/delkick" method="post" onsubmit="return submitForm($(this));">
															<input type="hidden" name="kickid" value="'.$kick['ID'].'" />
															<input type="submit" id="remove-kick-'.$kick['ID'].'" style="display: none;" />
															<td class="table-remove-button"><span class="label label-danger" onclick=\'$("#remove-kick-'.$kick['ID'].'").click();\' style="cursor: pointer;">Remove</span></td>
														</form>
														';
													} else {
														echo '<td></td>';
													}
												echo '
													</tr>
												';
											}
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h3>
								Bans
							</h3>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>
											Staff
										</th>
										<th>
											Reason
										</th>
										<th>
											Issued
										</th>
										<th>
											Expires
										</th>
										<th>
											
										</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$bans = dbquery('SELECT * FROM bans WHERE identifier="' . $this->userinfo['license'] . '"');
										if(empty($bans)) {
											echo '
												<tr>
													<td colspan="5">
														<center>
															No Bans on Record
														</center>
													</td>
												</tr>
											';
										} else {
											foreach($bans as $ban) {
												if($ban['banned_until'] == 0) {
													$banned_until = "Permanent";
												} else {
													$banned_until = date("m/d/Y h:i A", $ban['banned_until']);
												}
												echo '
													<tr>
														<td>
															' . $ban['staff_name'] . '
														</td>
														<td>
															' . $ban['reason'] . '
														</td>
														<td>
															' . date("m/d/Y h:i A", $ban['ban_issued']) . '
														</td>
														<td>
															' . $banned_until . '
														</td>
												';
													if(hasPermission($_SESSION['steamid'], 'delrecord')) {
														echo '
														<form action="'.$GLOBALS['domainname'].'api/delban" method="post" onsubmit="return submitForm($(this));">
															<input type="hidden" name="banid" value="'.$ban['ID'].'" />
															<input type="submit" id="remove-ban-'.$ban['ID'].'" style="display: none;" />
															<td class="table-remove-button"><span class="label label-danger" onclick=\'$("#remove-ban-'.$ban['ID'].'").click();\' style="cursor: pointer;">Remove</span></td>
														</form>
														';
													} else {
														echo '<td></td>';
													}
												echo '
													</tr>
												';
											}
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h3>
								Commendations
							</h3>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>
											Staff
										</th>
										<th>
											Reason
										</th>
										<th>
											Date
										</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$commendations = dbquery('SELECT * FROM commend WHERE license="' . $this->userinfo['license'] . '"');
										if(empty($commendations)) {
											echo '
												<tr>
													<td colspan="4">
														<center>
															No Commendations on Record
														</center>
													</td>
												</tr>
											';
										} else {
											foreach($commendations as $commend) {
												echo '
													<tr>
														<td>
															' . $commend['staff_name'] . '
														</td>
														<td>
															' . $commend['reason'] . '
														</td>
														<td>
															' . date("m/d/Y h:i A", $commend['time']) . '
														</td>
												';
													if(hasPermission($_SESSION['steamid'], 'delrecord')) {
														echo '
														<form action="'.$GLOBALS['domainname'].'api/delcommend" method="post" onsubmit="return submitForm($(this));">
															<input type="hidden" name="commendid" value="'.$commend['ID'].'" />
															<input type="submit" id="remove-commend-'.$commend['ID'].'" style="display: none;" />
															<td class="table-remove-button"><span class="label label-danger" onclick=\'$("#remove-commend-'.$commend['ID'].'").click();\' style="cursor: pointer;">Remove</span></td>
														</form>
														';
													} else {
														echo '<td></td>';
													}
												echo '
													</tr>
												';
											}
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
					<?php
            			plugins::call('addPlayerPageContentEnd', array($this->userinfo));
 				   	?>
				</div>
            </div>
        </div>
        <footer class="footer">
            <div class="container-fluid">
                <p class="copyright pull-right">
                    &copy; <?php echo date('Y') . ' ' . $this->community; ?>
                </p>
            </div>
        </footer>
    </div>
	<script type="text/javascript">
		$('#duration').durationPicker();
	</script>
<?php $this->partial('app/partial/footer.php');?>
