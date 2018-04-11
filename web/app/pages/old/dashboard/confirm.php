<?php $this->partial('app/partial/header.php',array('title'=>$this->language->get('CONFIRM_PAYMENT')));?>
<div class="pv-herobox-small">
	<div class="container">
		<div class="col-xs-12 text-center">
			<h1><?php echo $this->language->get('CONFIRM_PAYMENT');?></h1>
		</div>
	</div>
</div>
<div class="pv-dashboard">
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<div class="panel panel-payvault">
					<div class="panel-heading text-center">
						<h5><?php echo $this->language->get('BALANCE'); ?>:<br>&dollar;<?php echo $_SESSION['balance'];?> USD</h5>
					</div>
				</div>
				<div class="panel panel-payvault">
					<ul class="list-group">
					    <li class="list-group-item" onclick="redirect('/<?php echo $this->language->lang;?>/dashboard');"><?php echo $this->language->get('TRANSACTION_HISTORY'); ?></li>
						<li class="list-group-item" onclick="redirect('/<?php echo $this->language->lang;?>/dashboard/sendmoney');"><?php echo $this->language->get('SEND_MONEY'); ?></li>
						<li class="list-group-item" onclick="redirect('/<?php echo $this->language->lang;?>/dashboard/pendingtransactions');"><?php echo $this->language->get('PENDING_TRANSACTIONS');?></li>
					</ul>
			    </div>
			    <div class="panel panel-payvault">
			        <ul class="list-group">
						<li class="list-group-item" onclick="redirect('/<?php echo $this->language->lang;?>/account');"><?php echo $this->language->get('ACCOUNT_DETAILS'); ?></li>
					</ul>
				</div>
			</div>
			<div class="col-md-9">
			    <div class="panel panel-payvault">
					<div class="panel-heading">
						<h4><?php echo $this->language->get('CONFIRM_PAYMENT'); ?> <span class="pull-right"><form action="/<?php echo $this->language->lang;?>/dashboard/canceltransaction/<?php echo $this->transaction['tid'];?>" method="post" onsubmit="return submitForm($(this));"><div class="form-group"><button type="submit" class="btn btn-danger">Cancel Transaction</button></div></form></span></h4>
					</div>
				</div>
				<form action="" method="post" onsubmit="return submitForm($(this));">
				    <div id="message" class="message-nomarginabs"></div>
        			<div class="panel panel-payvault panel-payvault-underline login-form-no2fa">
        				<div class="panel-heading">
        				    <h6>Payment to <?php echo $this->recipientinfo['email'];?> <span class="pull-right"><?php echo date('H:i d/m/Y',$this->transaction['date']);?></span></h6>
        				</div>
        				<div class="panel-body text-center">
        				    <h2>&dollar;<?php echo $this->transaction['amount'];?> USD</h2>
        				    <h4>Your balance after this transaction will be: &dollar;<?php echo $_SESSION['balance'] - $this->transaction['amount'];?> USD</h4>
        			    </div>
        			    <div class="panel-foot">
        			        <div class="form-group form-button">
        			            <button type="submit" class="btn btn-success btn-block">Confirm Payment</button>
        			        </div>
        				</div>
        			</div>
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
				</form>
			</div>
		</div>
	</div>
</div>
<?php $this->partial('app/partial/footer.php');?>