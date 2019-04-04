<?php $this->partial('app/partial/header.php', array('community' => $this->community, 'title' => $this->title));?>
    <div class="main-panel">
        <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button> <a class="navbar-brand" href="#">Admin Panel</a> </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li> <a href="?logout">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="content">
            <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">All Tickets</h4>
                        </div>
                        <div class="content table-responsive">
                            <table id="servers" class="table table-hover table-striped table-bordered" style="width:100%;cursor:pointer;">
                                <thead>
                                    <tr>
                                        <th>Server</th>
                                        <th>IP:PORT</th>
                                        <th>Email</th>
                                        <th>Players</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Server</th>
                                        <th>IP:PORT</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">Ticket Statistics</h4>
                        </div>
                        <div class="content" style="font-size: 14px;">
                            <p><b>Total Tickets: </b><?php echo count(dbquery('SELECT * FROM support_tickets')); ?></p>
                            <p><b>Total Comments: </b><?php echo count(dbquery('SELECT * FROM support_comments')); ?></p>
                            <p><b>Your Tickets: </b><?php echo count(dbquery('SELECT * FROM support_tickets WHERE steamid="' . $_SESSION['steamid'] . '"')); ?></p>
                            <p><b>Your Comments: </b><?php echo count(dbquery('SELECT * FROM support_comments WHERE steamid="' . $_SESSION['steamid'] . '"')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container-fluid">
                <p class="copyright pull-right"> &copy;
                    <?php echo date('Y') . " " . $this->community; ?>
                </p>
            </div>
        </footer>
    </div>
    <script type="text/javascript">
            $(document).ready(function() {
                $('#servers').DataTable( {
                    "processing": true,
                    "serverSide": true,
                    "ajax": "<?php echo $GLOBALS['domainname']; ?>api/serverslist?community=NA",
                    "order": [[ 0, "desc" ]]
                } );
            } );
    </script>
    <?php $this->partial('app/partial/footer.php');?>