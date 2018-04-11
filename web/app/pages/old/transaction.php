<?php $this->partial('app/partial/header.php',array('title'=>$this->language->get('TRANSACTION')));?>
<div class="pv-herobox-small">
	<div class="container">
		<div class="col-xs-12 text-center">
			<h3><?php echo $this->language->get('TRANSACTION');?></h3>
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
			            <h4><?php echo $this->language->get('TRANSACTION').' '.$this->tid;?></h4>
			        </div>
			    </div>
			    <div class="panel panel-payvault panel-payvault-underline">
			        <div class="panel-heading">
			            <h4>Transaction Details</h4>
			        </div>
			        <div class="panel-body">
			            <p>Date: <strong><?php echo date('H:i d/m/Y T',$this->transaction[0]['date']);?></strong></p>
			            <p>Transaction ID: <strong><?php echo $this->tid;?></strong></p>
			        </div>
			        <div class="panel-body">
			            <p>Paid by: </p>
			        </div>
			    </div>
			</div>
		</div>
	</div>
</div>
<?php $this->partial('app/partial/footer.php');?>