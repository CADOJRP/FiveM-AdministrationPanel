<?php $this->partial('app/partial/header.php',array('community'=>$this->community,'title'=>$this->title));?>
    <div class="main-panel">
        <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button> <a class="navbar-brand" href="#">Panel Settings</a> </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="?logout">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="content">
            <div class="container-fluid">
                <?php
                    $email = dbquery('SELECT * FROM communities WHERE uniqueid="' . escapestring(userCommunity($_SESSION['steamid'])) . '"')[0]['email'];
                    if(empty($email) || $email == null) {
                ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                <span><center><b>Required Action!</b> A contact email is required. In the event of abuse or an inactive server/community we will attempt to contact you via the email provided.</center></span>
                            </div>
                        </div>
                    </div>
                <?php
                    }
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Settings</h4> </div>
                            <div class="content">
                                <form action="<?php echo $GLOBALS['domainname']; ?>api/updatepanel" method="post" onsubmit="return submitForm($(this));">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Community Name</label>
                                                <input type="text" class="form-control" placeholder="Community Name" name="communityname" value="<?php echo siteConfig('community_name'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Community ID (Keep Secret)</label>
                                                <input type="text" class="form-control" placeholder="Community ID" value="<?php echo siteConfig('community'); ?>" readonly="true" style="cursor: default; user-select: all;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Contact Email</label>
                                                <input type="email" class="form-control" name="contactemail" placeholder="Contact Email Address" value="<?php echo $email; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Discord Webhook (Blank To Disable)</label>
                                                <input type="text" class="form-control" placeholder="Discord Webhook Link" name="discordwebhook" value="<?php echo siteConfig('discord_webhook'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Join Messages (true/false)</label>
                                                <input type="text" class="form-control" placeholder="Join Messages" name="joinmessages" value="<?php echo siteConfig('joinmessages'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Chat Commands (true/false)</label>
                                                <input type="text" class="form-control" placeholder="Chat Commands" name="chatcommands" value="<?php echo siteConfig('chatcommands'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Starting Trust Score</label>
                                                <input type="number" class="form-control" name="trustscore" value="<?php echo siteConfig('trustscore'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Warn Points (Value Taken)</label>
                                                <input type="number" class="form-control" name="warnpoints" value="<?php echo siteConfig('tswarn'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Kick Points (Value Taken)</label>
                                                <input type="number" class="form-control" name="kickpoints" value="<?php echo siteConfig('tskick'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Ban Points (Value Taken)</label>
                                                <input type="number" class="form-control" name="banpoints" value="<?php echo siteConfig('tsban'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Commend Points (Value Given)</label>
                                                <input type="number" class="form-control" name="commendpoints" value="<?php echo siteConfig('tscommend'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Time Increase Points (Points Per Hour)</label>
                                                <input type="number" class="form-control" name="timepoints" value="<?php echo siteConfig('tstime'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Recent Players Time Limit (Minutes)</label>
                                                <input type="number" class="form-control" name="recentplayers" value="<?php echo siteConfig('recent_time'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Server Query Timeout (Seconds)</label>
                                                <input type="number" class="form-control" name="checktimeout" value="<?php echo siteConfig('checktimeout'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Permissions (JSON)</label>
                                            <?php 
                                                $permissions = trim(preg_replace('/\\\\\"/',"\"", json_encode(json_decode(json_encode(unserialize(siteConfig('permissions'))), true))),'"'); 
                                            ?>
                                            <textarea rows="20" cols="80" class="form-control" id="permissions"><?php print_r($permissions); ?></textarea>
                                            <input type="hidden" name="permissions" id="permissionshidden" value='<?php print_r($permissions); ?>'/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Server Buttons (JSON)</label>
                                            <?php
                                                $buttons = trim(preg_replace('/\\\\\"/',"\"", json_encode(json_decode(json_encode(unserialize(siteConfig('serveractions'))), true))),'"');
                                            ?>
                                            <textarea rows="20" cols="80" class="form-control" id="serveractions"><?php print_r($buttons); ?></textarea>
                                            <input type="hidden" name="serveractions" id="serveractionshidden" value='<?php print_r($buttons) ?>'/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Panel Theme (CSS)</label>
                                            <?php
                                                $theme = siteConfig('themecss');
                                                if($theme == "false") {
                                                    $theme = "";
                                                }
                                            ?>
                                            <textarea rows="20" cols="80" class="form-control" id="paneltheme"><?php print_r($theme); ?></textarea>
                                            <input type="hidden" name="paneltheme" id="panelthemehidden" value='<?php print_r($theme) ?>'/>
                                        </div>
                                    </div>
                                    <div id="message"></div>
                                    <button type="submit" class="btn btn-info btn-fill" style="width: 100%;">Update Settings</button>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    if(dbquery('SELECT * FROM communities WHERE owner="' . $_SESSION['steamid'] . '" AND uniqueid="' . userCommunity($_SESSION['steamid']) . '"')[0]) {
                ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title">Delete Community</h4> </div>
                                <div class="content">
                                    <form action="<?php echo $GLOBALS['domainname']; ?>api/delcommunity" method="post" onsubmit="return submitForm($(this));">
                                        <label>Confirmation (Please Type: "I wish to delete my community")</label>
                                        <input type="text" class="form-control" placeholder="Please Type 'I wish to delete my community'" name="securitycheck">
                                        <div id="message"></div>
                                        <button type="submit" class="btn btn-danger btn-fill" style="width: 100%;">Delete Community</button>
                                        <div class="clearfix"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    }
                ?>
                <!--<div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Plugins</h4> </div>
                            <div class="content">
                                <form action="<?php echo $GLOBALS['domainname']; ?>api/editplugins" method="post" onsubmit="return submitForm($(this));">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>More User Information</label>
                                                <select class="form-control" name="null">
                                                    <option value="true">Enabled</option>
                                                    <option value="false">Disabled</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Global User Records</label>
                                                <select class="form-control" name="null">
                                                    <option value="true">Enabled</option>
                                                    <option value="false">Disabled</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>More Coming Soon!</label>
                                                <select class="form-control" name="null" disabled="true">
                                                    <option value="Coming Soon!">Coming Soon!</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="message"></div>
                                    <button type="submit" class="btn btn-info btn-fill" style="width: 100%;">Delete Community</button>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>-->
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
    <script type="text/javascript">
        $(document).ready(function(){
            if ($('#permissions').length > 0) {
                var editor = CodeMirror.fromTextArea(document.getElementById("permissions"), {
                    lineNumbers: true,
                    mode: { name: "javascript", json: true },
                    indentUnit: 4,
                    indentWithTabs: true,
                    enterMode: "keep",
                    tabMode: "shift"
                });

                var totalLines = editor.lineCount();
                var totalChars = editor.getTextArea().value.length;
                editor.autoFormatRange({line:0, ch:0}, {line:totalLines, ch:totalChars});

                editor.on('change',function(content){
                    $("#permissionshidden").val(content.getValue());
                });
            } else {
                console.log('Error E1005. Contact FiveM Admin Panel Staff. (Details: #Permissions)');
            }


            if ($('#serveractions').length > 0) {
                var editor2 = CodeMirror.fromTextArea(document.getElementById("serveractions"), {
                    lineNumbers: true,
                    mode: { name: "javascript", json: true },
                    indentUnit: 4,
                    indentWithTabs: true,
                    enterMode: "keep",
                    tabMode: "shift"
                });

                var totalLines = editor2.lineCount();
                var totalChars = editor2.getTextArea().value.length;
                editor2.autoFormatRange({line:0, ch:0}, {line:totalLines, ch:totalChars});

                editor2.on('change',function(content){
                    $("#serveractionshidden").val(content.getValue());
                });
            } else {
                console.log('Error E1005. Contact FiveM Admin Panel Staff. (Details: #serveractions)');
            }


            if ($('#paneltheme').length > 0) {
                var editor3 = CodeMirror.fromTextArea(document.getElementById("paneltheme"), {
                    lineNumbers: true,
                    mode: "css",
                    indentUnit: 4,
                    indentWithTabs: true,
                    enterMode: "keep",
                    tabMode: "shift"
                });

                var totalLines = editor3.lineCount();
                var totalChars = editor3.getTextArea().value.length;
                editor3.autoFormatRange({line:0, ch:0}, {line:totalLines, ch:totalChars});

                editor3.on('change',function(content){
                    $("#panelthemehidden").val(content.getValue());
                });
            } else {
                console.log('Error E1005. Contact FiveM Admin Panel Staff. (Details: #paneltheme)');
            }


        });
    </script>
<?php $this->partial('app/partial/footer.php');?>