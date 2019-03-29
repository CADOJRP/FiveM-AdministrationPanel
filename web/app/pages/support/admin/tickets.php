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
                        <div class="content table-responsive table-full-width">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <th>Ticket ID</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </thead>
                                    <?php
                                        $tickets = dbquery('SELECT * FROM support_tickets ORDER BY time DESC');
                                        foreach($tickets as $ticket) {
                                            switch($ticket['status']) {
                                                case "open":
                                                    $status = '<span class="label label-success">Open</span>';
                                                    break;
                                                case "in-progress":
                                                    $status = '<span class="label label-info">In-Progress</span>';
                                                    break;
                                                case "pending":
                                                    $status = '<span class="label label-warning">Pending</span>';
                                                    break;
                                                case "closed":
                                                    $status = '<span class="label label-danger">Closed</span>';
                                                    break;
                                            }

                                            echo '
                                                <tr onclick="window.location.href=\'./admin/ticket/'.$ticket['ticketid'].'\';" class="clickable">
                                                    <td>' . $ticket['ticketid'] . '</td>
                                                    <td>' . $ticket['title'] . '</td>
                                                    <td>' . getName($ticket['steamid']) . '</td>
                                                    <td>' . date("m/d/Y h:i A", $ticket['time']) . '</td>
                                                    <td>' . $status . '</td>
                                                </tr>
                                            ';
                                        }
                                        if(empty($tickets)) {
                                            echo '<tr><td colspan="5"><center>No Tickets Found</center></td></tr>';
                                        }
                                    ?>
                                </tbody>
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
            <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">All Servers</h4>
                        </div>
                        <div class="content table-responsive table-full-width">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <th>Name</th>
                                    <th>Connection</th>
                                    <th>Community</th>
                                </thead>
                                    <?php
                                        $servers = dbquery('SELECT * FROM servers');
                                        foreach($servers as $server) {
                                            echo '
                                                <tr>
                                                    <td>' . $server['name'] . '</td>
                                                    <td>' . $server['connection'] . '</td>
                                                    <td>' . dbquery('SELECT * FROM config WHERE community="' . escapestring($server['community']) . '"')[0]['community_name'] . '</td>
                                                </tr>
                                            ';
                                        }
                                        if(empty($servers)) {
                                            echo '<tr><td colspan="3"><center>No Servers Found</center></td></tr>';
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">All Communities</h4>
                        </div>
                        <div class="content table-responsive table-full-width">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <th>Community Name</th>
                                    <th>Owner</th>>
                                    <th>Warnings</th>
                                    <th>Kicks</th>
                                    <th>Bans</th>
                                    <th>Commends</th>
                                </thead>
                                    <?php
                                        $communitys = dbquery('SELECT * FROM communities');
                                        foreach($communitys as $community) {
                                            if (strpos($community['owner'], 'deleted_') === false) {
                                                $stats = getStats($community['uniqueid']);
                                                echo '
                                                    <tr>
                                                        <td>' . $community['name'] . '</td>
                                                        <td>' . getName($community['owner']) . '</td>
                                                        <td>' . $stats['warns'] . '</td>
                                                        <td>' . $stats['kicks'] . '</td>
                                                        <td>' . $stats['bans'] . '</td>
                                                        <td>' . $stats['commends'] . '</td>
                                                    </tr>
                                                ';
                                            }
                                        }
                                        if(empty($communitys)) {
                                            echo '<tr><td colspan="6"><center>No Servers Found</center></td></tr>';
                                        }
                                    ?>
                                </tbody>
                            </table>
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
    <?php $this->partial('app/partial/footer.php');?>