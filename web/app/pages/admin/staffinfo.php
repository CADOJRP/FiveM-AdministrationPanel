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
                <a class="navbar-brand" href="#"><?php echo $this->userinfo['name']; ?>'s Profile</a>
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
            <div class="row">
                <div class="col-md-3">
                    <a class="info-tiles tiles-inverse has-footer info-tiles-warns" href="#">
                        <div class="tiles-heading">
                            <div class="pull-left">Warnings</div>
                            <div class="pull-right">
                                <div id="tileorders" class="sparkline-block">
                                    <canvas width="39" height="13" style="display: inline-block; width: 39px; height: 13px; vertical-align: top;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="tiles-body">
                            <div class="text-center"><?php echo dbquery('SELECT COUNT(*) FROM warnings WHERE staff_steamid="' . $this->userinfo['steamid'] . '" AND community="' . userCommunity($_SESSION['steamid']) . '"')[0]['COUNT(*)']; ?></div>
                        </div>
                        <div class="tiles-footer"></div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a class="info-tiles tiles-green has-footer info-tiles-kicks" href="#">
                        <div class="tiles-heading">
                            <div class="pull-left">Kicks</div>
                            <div class="pull-right">
                                <div id="tilerevenues" class="sparkline-block">
                                    <canvas width="40" height="13" style="display: inline-block; width: 40px; height: 13px; vertical-align: top;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="tiles-body">
                            <div class="text-center"><?php echo dbquery('SELECT COUNT(*) FROM kicks WHERE staff_steamid="' . $this->userinfo['steamid'] . '" AND community="' . userCommunity($_SESSION['steamid']) . '"')[0]['COUNT(*)']; ?></div>
                        </div>
                        <div class="tiles-footer"></div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a class="info-tiles tiles-blue has-footer info-tiles-bans" href="#">
                        <div class="tiles-heading">
                            <div class="pull-left">TEMP-BANS</div>
                            <div class="pull-right">
                                <div id="tiletickets" class="sparkline-block">
                                    <canvas width="13" height="13" style="display: inline-block; width: 13px; height: 13px; vertical-align: top;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="tiles-body">
                            <div class="text-center"><?php echo dbquery('SELECT COUNT(*) FROM bans WHERE staff_steamid="' . $this->userinfo['steamid'] . '" AND banned_until!=0 AND community="' . userCommunity($_SESSION['steamid']) . '"')[0]['COUNT(*)']; ?></div>
                        </div>
                        <div class="tiles-footer"></div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a class="info-tiles tiles-midnightblue has-footer info-tiles-online" href="#">
                        <div class="tiles-heading">
                            <div class="pull-left">PERM-BANS</div>
                            <div class="pull-right">
                                <div id="tilemembers" class="sparkline-block">
                                    <canvas width="39" height="13" style="display: inline-block; width: 39px; height: 13px; vertical-align: top;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="tiles-body">
                            <div class="text-center"><?php echo dbquery('SELECT COUNT(*) FROM bans WHERE staff_steamid="' . $this->userinfo['steamid'] . '" AND banned_until=0 AND community="' . userCommunity($_SESSION['steamid']) . '"')[0]['COUNT(*)']; ?></div>
                        </div>
                        <div class="tiles-footer"></div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <a class="info-tiles tiles-inverse has-footer info-tiles-warns" href="#">
                        <div class="tiles-heading">
                            <div class="pull-left">Past Week Warnings</div>
                            <div class="pull-right">
                                <div id="tileorders" class="sparkline-block">
                                    <canvas width="39" height="13" style="display: inline-block; width: 39px; height: 13px; vertical-align: top;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="tiles-body">
                            <div class="text-center"><?php echo dbquery('SELECT COUNT(*) FROM warnings WHERE staff_steamid="' . $this->userinfo['steamid'] . '" AND time >= ' . (time() - 604800) . ' AND community="' . userCommunity($_SESSION['steamid']) . '"')[0]['COUNT(*)']; ?></div>
                        </div>
                        <div class="tiles-footer"></div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a class="info-tiles tiles-green has-footer info-tiles-kicks" href="#">
                        <div class="tiles-heading">
                            <div class="pull-left">Past Week Kicks</div>
                            <div class="pull-right">
                                <div id="tilerevenues" class="sparkline-block">
                                    <canvas width="40" height="13" style="display: inline-block; width: 40px; height: 13px; vertical-align: top;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="tiles-body">
                            <div class="text-center"><?php echo dbquery('SELECT COUNT(*) FROM kicks WHERE staff_steamid="' . $this->userinfo['steamid'] . '" AND time >= ' . (time() - 604800) . ' AND community="' . userCommunity($_SESSION['steamid']) . '"')[0]['COUNT(*)']; ?></div>
                        </div>
                        <div class="tiles-footer"></div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a class="info-tiles tiles-blue has-footer info-tiles-bans" href="#">
                        <div class="tiles-heading">
                            <div class="pull-left">Past Week TEMP-BANS</div>
                            <div class="pull-right">
                                <div id="tiletickets" class="sparkline-block">
                                    <canvas width="13" height="13" style="display: inline-block; width: 13px; height: 13px; vertical-align: top;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="tiles-body">
                            <div class="text-center"><?php echo dbquery('SELECT COUNT(*) FROM bans WHERE staff_steamid="' . $this->userinfo['steamid'] . '" AND banned_until!=0 AND ban_issued >= ' . (time() - 604800) . ' AND community="' . userCommunity($_SESSION['steamid']) . '"')[0]['COUNT(*)']; ?></div>
                        </div>
                        <div class="tiles-footer"></div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a class="info-tiles tiles-midnightblue has-footer info-tiles-online" href="#">
                        <div class="tiles-heading">
                            <div class="pull-left">Past Week PERM-BANS</div>
                            <div class="pull-right">
                                <div id="tilemembers" class="sparkline-block">
                                    <canvas width="39" height="13" style="display: inline-block; width: 39px; height: 13px; vertical-align: top;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="tiles-body">
                            <div class="text-center"><?php echo dbquery('SELECT COUNT(*) FROM bans WHERE staff_steamid="' . $this->userinfo['steamid'] . '" AND banned_until=0 AND ban_issued >= ' . (time() - 604800) . ' AND community="' . userCommunity($_SESSION['steamid']) . '"')[0]['COUNT(*)']; ?></div>
                        </div>
                        <div class="tiles-footer"></div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">Action Log</h4>
                        </div>
                        <div class="content">
                            <div class="row">
                            <table class="table">
                                <thead>
                                    <th>Name</th>
                                    <th>Action</th>
                                    <th>Date Issued</th>
                                    <th>Reason</th>
                                </thead>
                                <tbody>
                                    <?php
                                        $actionlog = array();
                                        foreach(dbquery('SELECT * FROM bans WHERE staff_steamid="' . $this->userinfo['steamid'] . '" AND community="' . userCommunity($_SESSION['steamid']) . '"') as $ban) {
                                            if($ban['banned_until'] == 0) {
                                                $actionlog[] = array(
                                                    "action"=>"Permanent Ban",
                                                    "date"=>date("m/d/Y h:i A T", $ban['ban_issued']),
                                                    "timestamp"=>$ban['ban_issued'],
                                                    "reason"=>$ban['reason'],
                                                    "license"=>$ban['identifier'],
                                                );
                                            } else {
                                                $actionlog[] = array(
                                                    "action"=>"Temporary Ban (" . secsToStrRound($ban['banned_until'] - $ban['ban_issued']) . ")",
                                                    "date"=>date("m/d/Y h:i A T", $ban['ban_issued']),
                                                    "timestamp"=>$ban['ban_issued'],
                                                    "reason"=>$ban['reason'],
                                                    "license"=>$ban['identifier']
                                                );
                                            }
                                        }
                                        foreach(dbquery('SELECT * FROM kicks WHERE staff_steamid="' . $this->userinfo['steamid'] . '" AND community="' . userCommunity($_SESSION['steamid']) . '"') as $kick) {
                                            $actionlog[] = array(
                                                "action"=>"Kick",
                                                "date"=>date("m/d/Y h:i A T", $kick['time']),
                                                "timestamp"=>$kick['time'],
                                                "reason"=>$kick['reason'],
                                                "license"=>$kick['license']
                                            );
                                        }
                                        foreach(dbquery('SELECT * FROM warnings WHERE staff_steamid="' . $this->userinfo['steamid'] . '" AND community="' . userCommunity($_SESSION['steamid']) . '"') as $warn) {
                                            $actionlog[] = array(
                                                "action"=>"Warning",
                                                "date"=>date("m/d/Y h:i A T", $warn['time']),
                                                "timestamp"=>$warn['time'],
                                                "reason"=>$warn['reason'],
                                                "license"=>$warn['license']
                                            );
                                        }
                                        foreach(dbquery('SELECT * FROM commend WHERE staff_steamid="' . $this->userinfo['steamid'] . '" AND community="' . userCommunity($_SESSION['steamid']) . '"') as $commend) {
                                            $actionlog[] = array(
                                                "action"=>"Commend",
                                                "date"=>date("m/d/Y h:i A T", $commend['time']),
                                                "timestamp"=>$commend['time'],
                                                "reason"=>$commend['reason'],
                                                "license"=>$commend['license']
                                            );
                                        }
                                        function cmp($a, $b) {
                                            if ($a['timestamp'] == $b['timestamp']) {
                                                return 0;
                                            }
                                            return ($a['timestamp'] < $b['timestamp']) ? -1 : 1;
                                       }
                                        usort($actionlog, "cmp");
                                        foreach(array_reverse($actionlog) as $action) {
                                            echo '
                                                <tr onclick=\'window.location.href="' .$GLOBALS['domainname'] . 'user/' . $action['license'].'"\' style="cursor: pointer">
                                                    <td>' . dbquery('SELECT * FROM players WHERE license="' . $action['license'] . '" AND community="' . userCommunity($_SESSION['steamid']) . '"')[0]['name'] . '</td>
                                                    <td>' . $action['action'] . '</td>
                                                    <td>' . $action['date'] . '</td>
                                                    <td>' . $action['reason'] . '</td>
                                                </tr>
                                            ';
                                        }
                                    ?>
                                </tbody>
                            </table>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="container-fluid">
            <p class="copyright pull-left"><b style="padding-right: 4px;">Theme By:</b> <span class="themeauthor">FiveMAdminPanel</span></p>
            <p class="copyright pull-right">
                &copy; <?php echo date('Y') . ' ' . $this->community; ?>
            </p>
        </div>
    </footer>
<?php $this->partial('app/partial/footer.php');?>