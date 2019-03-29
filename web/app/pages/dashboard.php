<?php $this->partial('app/partial/header.php', array('community' => $this->community, 'title' => $this->title)); ?>
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
                    <a class="info-tiles tiles-success has-footer info-tiles-warns" href="#">
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
                    <a class="info-tiles tiles-warning has-footer info-tiles-kicks" href="#">
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
                    <a class="info-tiles tiles-danger has-footer info-tiles-bans" href="#">
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
                    <a class="info-tiles tiles-primary has-footer info-tiles-online" href="#">
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
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <center>
                            <h4 class="title">Thank You - FiveMAdminPanel Team</h4>
                            <p class="category">Date: 3/18/2019</p>
                            </center>
                        </div>
                        <div class="content">
                            <h4>450 Registered Servers</h4>
                            <p style="font-size: 14px;">
                            </p>
                            <ul>
                                <li>
                                Holy $#*!. 450 registered servers as of 3/18/2019. We would say this is a milestone but we didn't account for these many people using our panel. Over the past few weeks, we've been hard at work optimizing the panel for easier scaling. This should mean your pages should start loading faster. We will continue to work on optimizations while adding and fixing other features.
                                </li>
                            </ul>
                            <p></p>
                            <h4>9,640,000 Monthly Requests</h4>
                            <p style="font-size: 14px;">
                            </p>
                            <ul>
                                <center>
                                    <img src="https://i.imgur.com/juF0OBZ.png"/><br/>
                                    We've recieved nearly 10,000,000 requests within the last 30 days. Thats about 223 requests per minute around the clock.
                                </center>  
                            </ul>
                            <p></p>
                            <h4>Feedback</h4>
                            <p style="font-size: 14px;">
                            </p>
                            <ul>
                                <li>
                                We want to thank everyone for all the feedback that has been provided via the Discord server. We've been able to quickly resolve issues and add features. If you have any more suggestions or bugs feel free to join the Discord from the sidebar on the left.
                                </li>
                            </ul>
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="container-fluid">
            <p class="copyright pull-left"><b style="padding-right: 4px;">Theme By:</b> <span class="themeauthor">FiveMAdminPanel</span></p>
            <p class="copyright pull-right"> &copy;
                <?php echo date('Y') . ' ' . $this->community; ?>
            </p>
        </div>
    </footer>
</div>
<?php $this->partial('app/partial/footer.php'); ?> 