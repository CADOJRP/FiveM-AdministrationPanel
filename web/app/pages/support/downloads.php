<?php $this->partial('app/partial/header.php', array('community' => $this->community, 'title' => $this->title));?>
    <div class="main-panel">
        <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button> <a class="navbar-brand" href="#">View Ticket</a> </div>
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
                        <div class="header">
                            <h4 class="title">Your Tickets</h4>
                        </div>
                        <div class="content table-full-width">
                            <table class="table table-striped">
                                <thead>
                                    <th>Title</th>
                                    <th>GitHub</th>
                                    <th>Last Updated</th>
                                    <th>Download</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>FiveM Admin Panel Resource (New)</td>
                                        <td>N/A</td>
                                        <td>2/13/2019</td>
                                        <td><a href="https://fivemadminpanel.com/resource.zip"><span class="label label-success">Download</span></a></td>
                                    </tr>
                                    <tr>
                                        <td>FiveM Admin Panel (Old)</td>
                                        <td><a href="https://github.com/CADOJRP/FiveM-AdministrationPanel">https://github.com/CADOJRP/FiveM-AdministrationPanel</a></td>
                                        <td>11/26/2018</td>
                                        <td><a href="https://github.com/CADOJRP/FiveM-AdministrationPanel/archive/v0.4.1-hotfix.zip"><span class="label label-success">Download</span></a></td>
                                    </tr>
                                    <tr>
                                        <td>FiveM Admin Panel Resource (Old)</td>
                                        <td><a href="https://github.com/CADOJRP/FiveM-AdministrationPanel">https://github.com/CADOJRP/FiveM-AdministrationPanel</a></td>
                                        <td>11/26/2018</td>
                                        <td><a href="https://github.com/CADOJRP/FiveM-AdministrationPanel/releases/download/v0.4.1-hotfix/fivem-resource.zip"><span class="label label-success">Download</span></a></td>
                                    </tr>
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