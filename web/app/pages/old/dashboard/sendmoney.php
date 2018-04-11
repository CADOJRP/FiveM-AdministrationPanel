<?php $this->partial('app/partial/header.php',array('title'=>$this->language->get('SEND_MONEY')));?>
<div class="pv-herobox-small">
	<div class="container">
		<div class="col-xs-12 text-center">
			<h1><?php echo $this->language->get('SEND_MONEY');?></h1>
		</div>
	</div>
</div>
<div class="pv-sendmoney">
    <div class="container"> 
        <div class="col-sm-12 col-md-6 col-md-offset-3">
            <form action="/<?php echo $this->language->lang;?>/dashboard/sendmoney" method="post" onsubmit="return submitForm($(this));">
                <div id="message"></div>
                <div class="panel panel-payvault login-form-no2fa">
                    <div class="panel-heading pv-gradient text-center text-white">
                        <a href="/<?php echo $this->language->lang;?>/dashboard"><h4><i class="fa fa-angle-left pull-left text-white"></i></h4></a>
                        <h4>Send Money</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>To</label>
                            <input type="text" class="form-control login-field" name="to" placeholder="Email Address of Recipient">
                        </div>
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="text" class="form-control login-field" name="amount" placeholder="Amount">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success btn-block attached-top">Send</button>
                </div>
				<div class="panel panel-payvault panel-margin-top panel-payvault-underline login-form-2fa" style="display:none">
					<div class="panel-heading">
						<h4><?php echo $this->language->get('TWOFACTOR');?></h4>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<label>2FA Code</label>
							<input type="text" pattern="[0-9]*" class="form-control login-field" name="2facode" placeholder="<?php echo $this->language->get('TWOFACTOR_CODE');?>: XXXXXX">
						</div>
						<div class="form-group form-button">
						    <a class="btn btn-info button-margin button-back"><i class="fa fa-arrow-left"></i> &nbsp; <?php echo $this->language->get('GO_BACK');?></a>
							<button type="submit" class="btn btn-success button-margin"><i class="fa fa-sign-in"></i> &nbsp; <?php echo $this->language->get('LOGIN');?></button>
						</div>
					</div>
				</div>
            </form>
        </div>
    </div>
</div>
<?php $this->partial('app/partial/footer.php');?>