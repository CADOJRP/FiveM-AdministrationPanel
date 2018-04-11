<?php $this->partial('app/partial/header.php',array('title'=>$this->language->get('ACCOUNT')));?>
<div class="pv-herobox-small">
	<div class="container">
		<div class="col-xs-12 text-center">
			<h1><?php echo $this->language->get('ACCOUNT');?></h1>
		</div>
	</div>
</div>
<div class="pv-account">
	<div class="container">
		<div class="col-md-3">
			<div class="panel panel-payvault panel-margin-top">
				<div class="panel-heading text-center">
					<h5><?php echo $this->language->get('ACCOUNT');?></h5>
				</div>
			</div>
			<div class="panel panel-payvault">
				<ul class="list-group">
					<li class="list-group-item" onclick="redirect('/<?php echo $this->language->lang;?>/dashboard');"><i class="fa fa-angle-left"></i>&nbsp;&nbsp;<?php echo $this->language->get('DASHBOARD');?></li>
				</ul>
			</div>
			<div class="panel panel-payvault">
				<ul class="list-group">
					<li class="list-group-item" data-toggle="tab" href="#logs">Account Logs</li>
					<li class="list-group-item" data-toggle="tab" href="#2fa"><?php echo $this->language->get('TWOFACTOR_SHORT');?></li>
					<li class="list-group-item" data-toggle="tab" href="#changepassword"><?php echo $this->language->get('PASSWORD_CHANGE');?></li>
				</ul>
			</div>
		</div>
		<div class="col-md-9 tab-content">
			<div id="2fa" class="tab-pane fade">
				<div class="panel panel-payvault panel-margin-top panel-payvault-underline">
					<div class="panel-heading">
						<h3><?php echo $this->language->get('TWOFACTOR');?></h3>
					</div>
					<div class="panel-body">
						<div class="col-md-12">
							<?php if(!$_SESSION['2fa']){?>
							<h4>Enable 2FA</h4>
							<div class="2fa-enable-code" style="display:none">
								<center>
									<img class="2fa-enable-code-img" src=""/>
										<br>
									<code class="2fa-enable-code-code"></code>
								</center>
							</div>
							<form action="/<?php echo $this->language->lang;?>/account/2faenable" method="POST" onsubmit="return submitForm($(this));" class="2fa-enable-button">
								<div id='message'></div>
								<div class="form-group">
									<button type="submit" class="btn btn-success btn-block">Enable</button>
								</div>
							</form>
							<?php } else {?>
							<h4 class="message message-success text-center"><?php echo $this->language->get('TWOFACTOR_ENABLED');?></h4>
						</div>
					</div>
				</div>
				<div class="panel panel-payvault panel-payvault-underline">
				    <div class="panel-heading">
				        <h4>Disable Two Factor Authentication</h4>
				    </div>
					<div class="panel-body">
					    <div class="col-md-12">
							<form action="/<?php echo $this->language->lang;?>/account/2fadisable" method="POST" onsubmit="return submitForm($(this));" class="2fa-disable-button">
							    <div id='message' class='message-nomargin'></div>
							    <div class="form-group">
							        <label><?php echo $this->language->get('TWOFACTOR_CODE');?></label>
							        <input type="text" pattern="[0-9]*" class="form-control login-field" placeholder="<?php echo $this->language->get('TWOFACTOR_CODE');?>: XXXXXX" name="2facode">
							    </div>
							    <div class="form-group">
							        <button type="submit" class="btn btn-success btn-block"><?php echo $this->language->get('TWOFACTOR_DISABLE');?></button>
							    </div>
							</form>
							<?php }?>
						</div>
					</div>
				</div>
			</div>
			<div id="changepassword" class="tab-pane fade">
			    <form action="/<?php echo $this->language->lang;?>/auth/changepassword" method="POST" onsubmit="return submitForm($(this));">
			        <div id="message"></div>
    				<div class="panel panel-payvault panel-margin-top panel-payvault-underline">
    					<div class="panel-heading">
    						<h3><?php echo $this->language->get('PASSWORD_CHANGE');?></h3>
    					</div>
    					<div class="panel-body">
    					    
    					    <div class="form-group">
    					        <label><?php echo $this->language->get('PASSWORD_CURRENT');?></label>
    					        <input type="password" class="form-control login-field" name="current" placeholder="<?php echo $this->language->get('PASSWORD_CURRENT');?>">
    					    </div>
    					    <div class="form-group">
    					        <label><?php echo $this->language->get('PASSWORD_NEW');?></label>
    					        <input type="password" class="form-control login-field password-strength" name="new" placeholder="<?php echo $this->language->get('PASSWORD_NEW');?>">
    					        <div class="input-strength"><div class="input-strength-inner"></div></div>
    					    </div>
    					    <div class="form-group">
    					        <label><?php echo $this->language->get('PASSWORD_REPEAT');?></label>
    					        <input type="password" class="form-control login-field" name="repeat" placeholder="<?php echo $this->language->get('PASSWORD_REPEAT');?>">
    					    </div>
    					    <?php if($_SESSION['2fa']){?>
    					    <div class="form-group">
    					        <label><?php echo $this->language->get('TWOFACTOR_CODE');?></label>
    					        <input type="text" pattern="[0-9]*" class="form-control login-field" name="2fa" placeholder="<?php echo $this->language->get('TWOFACTOR_CODE');?>: XXXXXX">
    					    </div>
    					    <?php }?>
    					    <div class="form-group form-button">
    					        <button type="submit" class="btn btn-success btn-block"><?php echo $this->language->get('PASSWORD_CHANGE');?></button>
    					    </div>
    					</div>
    				</div>
				</form>
			</div>
			<div id="logs" class="tab-pane fade">
				<div class="panel panel-payvault panel-margin-top panel-payvault-underline">
					<div class="panel-heading">
						<h3>Account Logs</h3>
					</div>
				</div>
				<div class="panel panel-payvault panel-payvault-underline">
					<div class="panel-body">
    					<table class="table table-borderless table-hover table-payvault">
    						<thead>
    							<th>Action</th>
    							<th>Time</th>
    							<th>IP</th>
    						</thead>
    						<tbody>
    							<?php
    								foreach($this->logs as $log) {
    									echo "<tr><td>".ucwords($log['action'])."</td><td>".date('H:i d/m/Y',$log['time'])."</td><td>".$log['ip']."</td></tr>";
    								}
    							?>
    						</tbody>
    					</table>
					</div>
				</div>
				<div class="text-center">
                	<ul class="pagination">
                		<?php $totalpages = ceil(count($this->totallogs) / $this->pagesize);?>
                		<?php if($_GET['page'] > $totalpages && isset($_GET['page'])){echo '<script>window.location.href="/'.$this->language->lang;'/account?tab=logs";</script>';}{?>
                			<?php if($this->page > 1){?>
                			<li class="previous"><a href="/<?php echo $this->language->lang;?>/account?page=<?php echo $this->page - 1;?>&tab=logs" class="fui-arrow-left"></a></li>
                			<?php }?>
                			<?php 
                			$x = 1;
                			while($x<=$totalpages){?>
                			<li <?php if($this->page == $x){?>class="active" disabled<?php }?>><a href="/<?php echo $this->language->lang;?>/account?page=<?php echo $x;?>&tab=logs"><?php echo $x;?></a></li>
                			<?php $x++;}?>
                			<?php if($this->page < $totalpages){?>
                			<li class="next"><a href="/<?php echo $this->language->lang;?>/account?page=<?php echo $this->page + 1;?>&tab=logs" class="fui-arrow-right"></a></li>
                			<?php }?>
                		<?php }?>
                	</ul>
                </div>
			</div>
		</div>
	</div>
</div>
<?php $this->partial('app/partial/footer.php');?>
<script src="/app/js/zxcvbn.js"></script>
<script>
$('.password-strength').on('input', function() {
  var val = $('.password-strength').val();
  var result = zxcvbn(val);
  if(result.score == 0){
      $('.input-strength-inner').attr('style','width:'+5+'%;background:#e74c3c!important');
      //#e74c3c
  } else if (result.score == 1) {
      $('.input-strength-inner').attr('style','width:'+(result.score/4)*100+'%;background:#f0ad4e!important');
      //#f0ad4e
  } else if (result.score == 2){
      $('.input-strength-inner').attr('style','width:'+(result.score/4)*100+'%;background:#f1c40f!important');
      //#f1c40f
  } else if (result.score == 3){
      $('.input-strength-inner').attr('style','width:'+(result.score/4)*100+'%;background:#5cb85c!important');
      //#5cb85c
  } else if (result.score == 4){
      $('.input-strength-inner').attr('style','width:'+(result.score/4)*100+'%;background:#2ecc71!important');
      //#2ecc71
  }

});
</script>