<?php $this->partial('app/partial/header.php',array('community'=>$this->community,'title'=>$this->title));?>
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
                    <a class="navbar-brand" href="#"><?php echo $this->server['name']; ?></a>
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
                <?php
                    $serverversion = serverDetails($this->server['connection'])->vars->AdminPanelVersion;
                    if(!isset($serverversion) || empty($serverversion)) {
                        echo '
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger">
                                        <span><center><strong>Update Required!</strong> FiveM resource update is needed. Detected Version: 0.9 (Newest Version: ' . $GLOBALS['resourceversion'] . ') </center></span>
                                    </div>
                                </div>
                            </div>
                        ';
                    } else {
                        if($serverversion < $GLOBALS['resourceversion']) {
                            echo '
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger">
                                            <span><center><strong>Update Required!</strong> FiveM resource update is needed. Detected Version: ' . $serverversion . ' (Newest Version: ' . $GLOBALS['resourceversion'] . ') </center></span>
                                        </div>
                                    </div>
                                </div>
                            ';
                        }
                    }
                ?>
                <?php
                    if(isset($GLOBALS['serveractions'][$this->server['connection']])) {
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">	
                                <h4 class="title">Server Actions</h4>
							</div>
							<div class="content">
								<?php
									if($GLOBALS['serveractions'][$this->server['connection']] != null) {
										foreach($GLOBALS['serveractions'][$this->server['connection']] as $button) {
											echo '
												<form action="'.$GLOBALS['domainname'].'api/button/'.$button['action'].'" method="post" onsubmit="return submitForm($(this));" style="display: inline-block;">
													<input type="hidden" name="server" value="'.$this->server['connection'].'"/>
													<input type="hidden" name="input" value="'.$button['input'].'"/>
													<button type="submit" class="btn btn-success btn-fill '.$button['buttonstyle'].'">'.$button['buttonname'].'</button>
												</form>
											';
										}
                                    }
                                    plugins::call('createServerButton')
								?>
							</div>
						</div>
					</div>
				</div>
                <?php
                    }
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">		
                                <h4 class="title">Player List<span style="float: right;"><?php echo $this->info['playercount'] . '/' . serverDetails($this->server['connection'])->vars->sv_maxClients; ?></span></h4>
                                <p class="category"><?php echo $this->server['connection']; ?></p>
                            </div>
                            <div class="content table-responsive">
                                <table id="players" class="table table-hover table-striped table-bordered" style="width:100%;cursor:pointer;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Ping</th>
                                            <th>Playtime</th>
                                            <th>Trust Score</th>
                                            <th>Flags</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php
											foreach($this->info['players'] as $player) {
												$playerinfo = dbquery('SELECT * FROM players WHERE license="'.$player->identifiers[1].'" AND community="' . userCommunity($_SESSION['steamid']) . '"');
												$playtime = $playerinfo[0]['playtime'];
												if(!is_null($playerinfo[0]['playtime'])) {
													$playtime = secsToStr($playerinfo[0]['playtime'] * 60);
												} else {
													$playtime = secsToStr(60);
                                                }
                                                
                                                $flags = '';

                                                if(dbquery('SELECT * FROM notes WHERE license="'.$player->identifiers[1].'" AND community="' . userCommunity($_SESSION['steamid']) . '"')) {
                                                    $flags .= 'N ';
                                                }

												echo '
													<tr onclick="window.location.href=\'../user/'.$player->identifiers[1].'\';" class="clickable">
														<td>'.$player->id.'</td>
														<td>'.$player->name.'</td>
														<td>'.$player->ping.'</td>
														<td>'.$playtime.'</td>
														<td>'.trustScore($player->identifiers[1]).'%</td>
														<td>'. $flags . plugins::call('addUserFlags', array($playerinfo)) . '</td>
													</tr> 
												';
											}
											if($this->info['playercount'] == 0) {
												echo '<tr><td colspan="6"><center>No Players Online</center></td></tr>';
											}
										?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Ping</th>
                                            <th>Playtime</th>
                                            <th>Trust Score</th>
                                            <th>Flags</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            plugins::call('addServerPageContentEnd', array($server));
        ?>
        <footer class="footer">
            <div class="container-fluid">
                <p class="copyright pull-left"><b style="padding-right: 4px;">Theme By:</b> <span class="themeauthor">FiveMAdminPanel</span></p>
                <p class="copyright pull-right">
                    &copy; <?php echo date('Y') . ' ' . $this->community; ?>
                </p>
            </div>
        </footer>
    </div>
  <script type="text/javascript">
        $(document).ready(function() {
            $('#players').DataTable({
                "paging": false,
                "order": [[ 0, "asc" ]],
                "bInfo" : false
            });
        } );
  </script>
<?php $this->partial('app/partial/footer.php');?>
