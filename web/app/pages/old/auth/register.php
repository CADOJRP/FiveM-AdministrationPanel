<?php $this->partial('app/partial/header.php',array('title'=>$this->language->get('REGISTER')));?>
<div class="pv-herobox-small">
	<div class="container">
		<div class="col-xs-12 text-center">
			<h1><?php echo $this->language->get('REGISTER');?></h1>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<form action="/<?php echo $this->language->lang;?>/auth/register" method="post" onsubmit="return submitForm($(this));">
			<div class="col-sm-12 col-md-10 col-md-offset-1">
				<div id="message"></div>
				<div class="panel panel-payvault panel-payvault-underline">
					<div class="panel-heading">
						<h4><?php echo $this->language->get('REGISTER');?></h4>
					</div>
					<div class="panel-body">
						<div class="col-md-6">
							<div class="form-group">
								<label><?php echo $this->language->get('FIRST_NAME');?></label>
								<input type="text" class="form-control login-field" name="firstname" placeholder="<?php echo $this->language->get('FIRST_NAME');?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label><?php echo $this->language->get('LAST_NAME');?></label>
								<input type="text" class="form-control login-field" name="lastname" placeholder="<?php echo $this->language->get('LAST_NAME');?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label><?php echo $this->language->get('NEW_PASSWORD');?></label>
								<input type="password" class="form-control login-field password-strength" name="newpassword" placeholder="<?php echo $this->language->get('NEW_PASSWORD');?>">
								<div class="input-strength"><div class="input-strength-inner"></div></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label><?php echo $this->language->get('CONFIRM_PASSWORD');?></label>
								<input type="password" class="form-control login-field" name="confirmpassword" placeholder="<?php echo $this->language->get('CONFIRM_PASSWORD');?>">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label><?php echo $this->language->get('EMAIL');?></label>
								<input type="email" class="form-control login-field" name="email" placeholder="<?php echo $this->language->get('EMAIL');?>">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label><?php echo $this->language->get('DOB');?></label>
								<input type="date" class="form-control login-field" name="dob" placeholder="<?php echo $this->language->get('DOB');?>">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group form-button">
								<a href="/<?php echo $this->language->lang;?>/auth/login" class="btn btn-info" style="width:49%;"><i class="fa fa-rocket"></i> &nbsp; <?php echo $this->language->get('YES_ACCOUNT');?></a>
								<button type="submit" class="btn btn-success" style="width:49%;float:right"><i class="fa fa-sign-in"></i> &nbsp; <?php echo $this->language->get('REGISTER');?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
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
  } else if (result.score == 1) {
      $('.input-strength-inner').attr('style','width:'+(result.score/4)*100+'%;background:#f0ad4e!important');
  } else if (result.score == 2){
      $('.input-strength-inner').attr('style','width:'+(result.score/4)*100+'%;background:#f1c40f!important');
  } else if (result.score == 3){
      $('.input-strength-inner').attr('style','width:'+(result.score/4)*100+'%;background:#5cb85c!important');
  } else if (result.score == 4){
      $('.input-strength-inner').attr('style','width:'+(result.score/4)*100+'%;background:#2ecc71!important');
  }

});
</script>