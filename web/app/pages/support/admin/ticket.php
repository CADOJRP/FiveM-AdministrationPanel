<?php $this->partial('app/partial/header.php', array('community' => $this->community, 'title' => $this->title));?>
    <div class="main-panel">
        <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button> <a class="navbar-brand" href="#">Admin View Ticket</a> </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li> <a href="?logout">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header" style="padding-bottom: 15px;">
                            <h4 class="title"><?php echo $this->ticketinfo['title']; ?></h4>
                            <p class="category">Ticket ID: <?php echo $this->ticketinfo['ticketid']; ?></p>
                            <p class="category">Date: <?php echo date("m/d/Y h:i A", $this->ticketinfo['time']); ?> EST</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">Ticket - <?php echo getName($this->ticketinfo['steamid']); ?></h4>
                            <p class="category">Date: <?php echo date("m/d/Y h:i A", $this->ticketinfo['time']); ?> EST</p>
                        </div>
                        <div class="content">
                            <div class="comment" style="font-size: 14px; min-height: 110px;"><?php echo $this->ticketinfo['message']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">Ticket Information</h4>
                        </div>
                        <div class="content" style="font-size: 14px;">
                            <p><b>Username: </b><?php echo getName($this->ticketinfo['steamid']); ?></p>
                            <?php
                                switch($this->ticketinfo['status']) {
                                    case "open":
                                        $status = "Open";
                                        break;
                                    case "in-progress":
                                        $status = "In-Progress";
                                        break;
                                    case "pending":
                                        $status = "Pending";
                                        break;
                                    case "closed":
                                        $status = "Closed";
                                        break;
                                }
                            ?>
                            <p><b>Status: </b><?php echo $status; ?></p>
                            <p><b>Submitted: </b><?php echo date("m/d/Y h:i A", $this->ticketinfo['time']); ?> EST</p>
                            <?php
                                $comments = dbquery('SELECT * FROM support_comments WHERE ticketid="' . escapestring($this->ticketinfo['ticketid']) . '" ORDER BY time DESC');
                                if(empty($comments)) {
                                    $lastupdated = $this->ticketinfo['time'];
                                } else {
                                    $lastupdated = $comments[0]['time'];
                                }
                            ?>
                            <p><b>Last Updated: </b><?php echo date("m/d/Y h:i A", $lastupdated); ?> EST</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                $listcomments = dbquery('SELECT * FROM support_comments WHERE ticketid="' . escapestring($this->ticketinfo['ticketid']) . '" ORDER BY time ASC');
                foreach($listcomments as $comment) {
                    echo '
                        <div class="row">
                            <div class="col-md-9">
                                <div class="card">
                                    <div class="header">
                                    <h4 class="title">Comment - ' . getName($comment['steamid']) . '</h4>
                                        <p class="category">Date: ' . date("m/d/Y h:i A", $comment['time']) . ' EST</p>
                                    </div>
                                    <div class="content">
                                        <div class="comment">' . $comment['message'] . '</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ';
                }
            ?>
            <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">Add Comment</h4>
                        </div>
                        <div class="content">
                            <form action="<?php echo $GLOBALS['domainname']; ?>api/support/addcomment" method="post" onsubmit="return submitForm($(this));">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group"><label>Comment</label><textarea class="form-control" name="message"></textarea></div>
                                    </div>
                                </div>
                                <input type="hidden" name="ticketid" value="<?php echo $this->ticketinfo['ticketid']; ?>"/>
                                <div id="message"></div> <button type="submit" class="btn btn-success btn-fill" style="width: 100%;">Add Comment</button>
                                <div class="clearfix"></div>
                            </form>
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