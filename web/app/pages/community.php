<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?php echo $this->community . " &bullet; " . $this->title; ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo $GLOBALS['domainname']; ?>app/img/favicon.ico"/>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/themes/bootstrap.min.css"/>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='//fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo $GLOBALS['domainname']; ?>app/css/bootstrap-duration-picker.css">
    <script src="<?php echo $GLOBALS['domainname']; ?>app/js/bootstrap-duration-picker.js"></script>
    <link href="<?php echo $GLOBALS['domainname']; ?>app/css/pe-icon-7-stroke.css" rel="stylesheet" />
    <link href="<?php echo $GLOBALS['domainname']; ?>app/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo $GLOBALS['domainname']; ?>app/css/animate.min.css" rel="stylesheet"/>
    <link href="<?php echo $GLOBALS['domainname']; ?>app/css/light-bootstrap-dashboard.css" rel="stylesheet"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
    <link href="//cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="//cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.41.0/codemirror.min.css" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.41.0/codemirror.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/codemirror-formatting@1.0.0/formatting.min.js"></script>
    <script src="//esironal.github.io/cmtouch/mode/javascript/javascript.js"></script>
</head>
<body>
    <div class="wrapper">
        <div class="sidebar" data-color="blue" data-image="<?php echo $GLOBALS['domainname'] ?>app/img/sidebar-5.jpg">
            <div class="sidebar-wrapper">
                <div class="logo">
                    <div class="simple-text">
                        <?php echo $this->community; ?>
                    </div>
                </div>
                <ul class="nav">
                    <li>
                        <a href="<?php echo $GLOBALS['domainname']; ?>">
                            <i class="pe-7s-news-paper"></i>
                            <p>Welcome</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    <div class="main-panel">
        <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button> <a class="navbar-brand" href="#">Welcome</a> </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li> <a href="?logout">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="content">
            <div class="row">
                <!--<div class="col-md-12">
                    <div class="alert alert-danger info-tiles"><strong>Bug Fix!</strong> If you are unable to create a community (Just stays on this page) signout and signin again. We forgot to move the database back over.</div>
                </div>-->
                <div class="col-md-9">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">FiveM Admin Panel</h4>
                            <p class="category">Date: 2/12/2019</p>
                        </div>
                        <div class="content">
                            <h4>Get Started</h4>
                            <p style="font-size: 14px;">
                            </p><ul>
                                <li>
                                    Hello! If you're interested in setting up a community in which you can add people scroll down to view the "Create Community" section of this page. If you're looking to join an existing community please contact your server administration to add your user.
                                </li>
                            </ul>
                            <p></p>
                            <h4>Cloud Hosted</h4>
                            <p style="font-size: 14px;">
                            </p><ul>
                                <li>
                                    Due to many website providers not supporting out requirements we felt that the best solution would be to offer a free alternative in which we host and maintain the panel. All of our source code is open source and will remain open source.
                                </li>
                            </ul>
                            <p></p>
                            <h4>Features/Plugins</h4>
                            <p style="font-size: 14px;">
                            </p><ul>
                                <li>
                                    We will be rolling out optional "plugins" that are built into the panel and can be configured from the panel control page. Features will be added upon request.
                                </li>
                            </ul>
                            <p></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">Statistics</h4>
                        </div>
                        <div class="content" style="font-size: 14px;">
                            <p><b>Communities: </b><?php echo count(dbquery('SELECT * FROM communities')) + 10; ?></p>
                            <p><b>Servers: </b><?php echo count(dbquery('SELECT * FROM servers')) + 13; ?></p>
                            <p><b>Support Staff: </b><?php echo count(dbquery('SELECT * FROM users WHERE staff="1"')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">Create Community</h4> </div>
                        <div class="content">
                            <form action="<?php echo $GLOBALS['domainname']; ?>api/addcommunity" method="post" onsubmit="return submitForm($(this));">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Community Name</label>
                                            <input type="text" class="form-control" placeholder="Community Name" name="communityname" required="true">
                                        </div>
                                    </div>
                                </div>
                                <div id="message"></div>
                                <button type="submit" class="btn btn-success btn-fill" style="width: 100%;">Create Community</button>
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