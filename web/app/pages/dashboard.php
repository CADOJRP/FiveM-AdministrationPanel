<?php $this->partial('app/partial/header.php', array('community' => $this->community, 'title' => $this->title));?>
    <div class="main-panel">
        <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button> <a class="navbar-brand" href="#">Dashboard</a> </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li> <a href="?logout">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <a class="info-tiles tiles-inverse has-footer" href="#">
                            <div class="tiles-heading">
                                <div class="pull-left">Warnings</div>
                                <div class="pull-right">
                                    <div id="tileorders" class="sparkline-block">
                                        <canvas width="39" height="13" style="display: inline-block; width: 39px; height: 13px; vertical-align: top;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="tiles-body">
                                <div class="text-center">
                                    <?php echo $this->stats['warns']; ?>
                                </div>
                            </div>
                            <div class="tiles-footer"></div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a class="info-tiles tiles-green has-footer" href="#">
                            <div class="tiles-heading">
                                <div class="pull-left">Kicks</div>
                                <div class="pull-right">
                                    <div id="tilerevenues" class="sparkline-block">
                                        <canvas width="40" height="13" style="display: inline-block; width: 40px; height: 13px; vertical-align: top;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="tiles-body">
                                <div class="text-center">
                                    <?php echo $this->stats['kicks']; ?>
                                </div>
                            </div>
                            <div class="tiles-footer"></div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a class="info-tiles tiles-blue has-footer" href="#">
                            <div class="tiles-heading">
                                <div class="pull-left">Bans</div>
                                <div class="pull-right">
                                    <div id="tiletickets" class="sparkline-block">
                                        <canvas width="13" height="13" style="display: inline-block; width: 13px; height: 13px; vertical-align: top;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="tiles-body">
                                <div class="text-center">
                                    <?php echo $this->stats['bans']; ?>
                                </div>
                            </div>
                            <div class="tiles-footer"></div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a class="info-tiles tiles-midnightblue has-footer" href="#">
                            <div class="tiles-heading">
                                <div class="pull-left">Online</div>
                                <div class="pull-right">
                                    <div id="tilemembers" class="sparkline-block">
                                        <canvas width="39" height="13" style="display: inline-block; width: 39px; height: 13px; vertical-align: top;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="tiles-body">
                                <div class="text-center">
                                    <?php echo $this->players; ?>
                                </div>
                            </div>
                            <div class="tiles-footer"></div>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <a class="info-tiles tiles-inverse has-footer" href="#">
                            <div class="tiles-heading">
                                <div class="pull-left">Accumulative Playtime (Minutes)</div>
                                <div class="pull-right">
                                    <div id="tileorders" class="sparkline-block">
                                        <canvas width="39" height="13" style="display: inline-block; width: 39px; height: 13px; vertical-align: top;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="tiles-body">
                                <div class="text-center">
                                    <?php echo $this->stats['playtime']; ?>
                                </div>
                            </div>
                            <div class="tiles-footer"></div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a class="info-tiles tiles-green has-footer" href="#">
                            <div class="tiles-heading">
                                <div class="pull-left">Accumulative Playtime (Hours)</div>
                                <div class="pull-right">
                                    <div id="tilerevenues" class="sparkline-block">
                                        <canvas width="40" height="13" style="display: inline-block; width: 40px; height: 13px; vertical-align: top;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="tiles-body">
                                <div class="text-center">
                                    <?php echo round($this->stats['playtime'] / 60, 2); ?>
                                </div>
                            </div>
                            <div class="tiles-footer"></div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a class="info-tiles tiles-blue has-footer" href="#">
                            <div class="tiles-heading">
                                <div class="pull-left">Accumulative Playtime (Days)</div>
                                <div class="pull-right">
                                    <div id="tiletickets" class="sparkline-block">
                                        <canvas width="13" height="13" style="display: inline-block; width: 13px; height: 13px; vertical-align: top;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="tiles-body">
                                <div class="text-center">
                                    <?php echo round($this->stats['playtime'] / 1440, 2); ?>
                                </div>
                            </div>
                            <div class="tiles-footer"></div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a class="info-tiles tiles-midnightblue has-footer" href="#">
                            <div class="tiles-heading">
                                <div class="pull-left">Accumulative Playtime (Years)</div>
                                <div class="pull-right">
                                    <div id="tilemembers" class="sparkline-block">
                                        <canvas width="39" height="13" style="display: inline-block; width: 39px; height: 13px; vertical-align: top;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="tiles-body">
                                <div class="text-center">
                                    <?php echo round($this->stats['playtime'] / 525600, 2); ?>
                                </div>
                            </div>
                            <div class="tiles-footer"></div>
                        </a>
                    </div>
                </div>
                <?php	
                plugins::call('addDashboardContent');
                $version = json_decode(file_get_contents('https://raw.githubusercontent.com/CADOJRP/FiveM-AdministrationPanel/master/version.json'));if ($version->webpanel > $GLOBALS['version']) {
                    echo '<div class="row"><div class="col-md-12"><div class="alert alert-danger info-tiles"><strong>Update Needed!</strong> We strongly advise updating as we are currently in our beta stages and will be resolving major flaws. <a href="https://github.com/CADOJRP/FiveM-AdministrationPanel/releases" target="_BLANK" style="color: #FFF;"><b><u>Download Update</u></b></a></div></div></div>						';
                }
                echo file_get_contents('https://raw.githubusercontent.com/CADOJRP/FiveM-AdministrationPanel/master/updates');?> </div>
        </div>
        <footer class="footer">
            <div class="container-fluid">
                <p class="copyright pull-right"> &copy;
                    <?php echo date('Y') . " " . $this->community; ?>
                </p>
            </div>
        </footer>
    </div>
    <?php $this->partial('app/partial/footer.php');?>