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
                <a class="navbar-brand" href="#"><?php echo $this->title; ?></a>
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
                            <h4 class="title">Find User</h4>
                        </div>
                        <div class="content">
                            <form action="<?php echo $GLOBALS['domainname']; ?>api/finduser" method="post" onsubmit="return submitForm($(this));">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Player Name</label>
                                            <input type="text" class="form-control auto" placeholder="Player Name" name="playername">
                                        </div>
                                    </div>
                                </div>
                                <div id="message"></div>
                                <button type="submit" class="btn btn-info btn-fill" style="width: 100%;">View User</button>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
		$(function() {
			$(".auto").autocomplete({
				source: "<?php echo $GLOBALS['domainname']; ?>api/auto/finduser",
				minLength: 1,
                select: function (event, ui) {
                    event.preventDefault();
                    $(".auto").val(ui.item.label);
                    window.location = "<?php echo $GLOBALS['domainname']; ?>user/" + ui.item.value;
                },
                focus: function (event, ui) {
                    event.preventDefault();
                    $(".auto").val(ui.item.label);
                }
			});				
		});
    </script>
<?php $this->partial('app/partial/footer.php');?>
