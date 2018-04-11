<?php $this->partial('app/partial/header.php',array('title'=>$this->language->get('LOGIN')));?>
<div class="pv-herobox-small">
	<div class="container">
		<div class="col-xs-12 text-center">
			<h1><?php echo $this->language->get('LOGIN');?></h1>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<form action="/<?php echo $this->language->lang;?>/auth/login" method="post" onsubmit="return submitForm($(this));">
		    <div id="message" class="col-sm-12 col-md-10 col-md-offset-1"></div>
			<div class="col-sm-12 col-md-10 col-md-offset-1">
				<div class="panel panel-payvault panel-payvault-underline login-form-no2fa">
					<div class="panel-heading">
						<h4><?php echo $this->language->get('LOGIN');?></h4>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<label><?php echo $this->language->get('EMAIL');?></label>
							<input type="email" class="form-control login-field" name="email" placeholder="<?php echo $this->language->get('EMAIL');?>">
						</div>
						<div class="form-group">
							<label><?php echo $this->language->get('PASSWORD');?></label>
							<input type="password" class="form-control login-field" name="password" placeholder="<?php echo $this->language->get('PASSWORD');?>">
						</div>
						<div class="form-group form-button">
							<a href="/<?php echo $this->language->lang;?>/auth/register" class="btn btn-info button-margin"><i class="fa fa-rocket"></i> &nbsp; <?php echo $this->language->get('NO_ACCOUNT');?></a>
							<button type="submit" class="btn btn-success button-margin" style="float:right"><i class="fa fa-sign-in"></i> &nbsp; <?php echo $this->language->get('LOGIN');?></button>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-6 col-md-offset-3">
				<div class="panel panel-payvault panel-payvault-underline login-form-2fa" style="display:none">
					<div class="panel-heading">
						<h4><?php echo $this->language->get('TWOFACTOR');?></h4>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<label>2FA Code</label>
							<input type="text" pattern="[0-9]*" class="form-control login-field" name="2facode" placeholder="<?php echo $this->language->get('TWOFACTOR_CODE');?>: XXXXXX">
						</div>
						<div class="form-group form-button">
						    <a class="btn btn-info button-margin button-back-login"><i class="fa fa-arrow-left"></i> &nbsp; <?php echo $this->language->get('GO_BACK');?></a>
							<button type="submit" class="btn btn-success button-margin"><i class="fa fa-sign-in"></i> &nbsp; <?php echo $this->language->get('LOGIN');?></button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<?php $this->partial('app/partial/footer.php');?>
