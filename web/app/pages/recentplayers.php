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
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">		
                                <h4 class="title">Recently Disconnected Players List</h4>
                                <p class="category">Last 10 Minutes</p>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table id="recentplayers" class="table table-hover table-striped table-bordered" style="width:100%;cursor:pointer;">
                                    <thead>
                                        <th>Name</th>
                                    	<th>Playtime</th>
                                    	<th>Trust Score</th>
                                    	<th>Last Played</th>
                                    </thead>
                                    <tbody>
										<?php
                                            $anyplayers = false;
                                            $minlimit = time() - 60;
                                            $maxlimit = time() - siteConfig('recent_time') * 60;
                                            $players = dbquery('SELECT * FROM players WHERE lastplayed < ' . $minlimit  . ' AND lastplayed > ' . $maxlimit . ' AND community="' . userCommunity($_SESSION['steamid']) . '"');
                                            foreach($players as $player) {
                                                $anyplayers = true;
                                                echo '
                                                    <tr onclick="window.location.href=\'./user/'.$player['license'].'\';" class="clickable">
                                                        <td>' . $player['name'] . '</td>
                                                        <td>' . secsToStr($player['playtime'] * 60) . '</td>
                                                        <td>' . trustScore($player['license']) . '%</td>
                                                        <td>' . date("m/d/Y h:i A", $player['lastplayed']) . '</td>
                                                    </tr>
                                                ';
                                            }
										?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>Playtime</th>
                                            <th>Trust Score</th>
                                            <th>Last Played</th>
                                        </tr>
                                    </tfoot>
                                </table>
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
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#recentplayers').DataTable({
                "paging": false,
                "order": [
                    [0, "asc"]
                ],
                "bInfo": false,
                "language": {
                   "emptyTable": "No Recent Players"
                }
            });
        });
    </script>
<?php $this->partial('app/partial/footer.php');?>
