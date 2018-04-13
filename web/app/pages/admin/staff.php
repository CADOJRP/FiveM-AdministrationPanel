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
                <a class="navbar-brand" href="#">Staff</a>
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
                <div class="col-md-8 col-md-offset-2">
                    <div class="card">
                        <div class="header">		
                            <h4 class="title">Staff List</h4>
                        </div>
                        <div id="message"></div>
                        <div class="content table-responsive table-full-width">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <th>Name</th>
                                    <th>Rank</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach (dbquery('SELECT * FROM users WHERE rank!="user"') as $staff) {
                                            echo '
                                                <tr>
                                                    <td>
                                                        '.$staff['name'].'
                                                    </td>
                                                    <td>
                                                        '.$staff['rank'].'
                                                    </td>
                                                    <form action="'.$GLOBALS['domainname'].'api/delstaff" method="post" onsubmit="return submitForm($(this));">
                                                        <input type="hidden" name="steamid" value="'.$staff['steamid'].'" />
                                                        <input type="submit" id="remove-staff-'.$staff['steamid'].'" style="display: none;" />
                                                        <td>
                                                            '.(($_SESSION['steamid'] != $staff['steamid'])?'<span class="label label-danger" onclick=\'$("#remove-staff-'.$staff['steamid'].'").click();\' style="cursor: pointer;">Remove</span>':"").'
                                                        </td>
                                                    </form>

                                                </tr>
                                            ';
                                        }
                                    ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">Add Staff</h4>
                        </div>
                        <div class="content">
                            <form action="<?php echo $GLOBALS['domainname']; ?>api/addstaff" method="post" onsubmit="return submitForm($(this));">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>User</label>
                                            <select class="form-control" name="steamid">
                                                <?php
                                                    $users = 0;
                                                    foreach (dbquery('SELECT * FROM users WHERE rank="user"') as $user) {
                                                        $users++;
                                                        echo '
                                                            <option value="'.$user['steamid'].'">'.$user['name'].'</option>
                                                        ';
                                                    }
                                                    if ($users == 0) {
                                                        echo '<option value="" selected disabled>No Users Found!</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Rank</label>
                                            <select class="form-control" name="rank">
                                                <?php
                                                    foreach ($GLOBALS['permissions'] as $role=>$rank) {
                                                        echo '<option value="'.$role.'">'.$role.'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="message"></div>
                                <button type="submit" class="btn btn-info btn-fill" style="width: 100%;">Add Staff</button>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $this->partial('app/partial/footer.php');?>
