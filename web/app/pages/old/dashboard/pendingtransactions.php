<?php $this->partial('app/partial/header.php',array('title'=>$this->language->get('PENDING_TRANSACTIONS')));?>
<div class="pv-herobox-small">
	<div class="container">
		<div class="col-xs-12 text-center">
			<h1><?php echo $this->language->get('PENDING_TRANSACTIONS');?></h1>
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
						<h4><?php echo $this->language->get('PENDING_TRANSACTIONS'); ?></h4>
					</div>
				</div>
				<?php if(empty($this->pending)){?>
			    <div class="panel panel-payvault">
			        <div class="panel-heading text-center">
			            <h4>There are currently no pending transactions!</h4>
			        </div>
			    </div>
			    <?php } else {?>
				<div class="panel panel-payvault">
					<table class="table table-borderless table-hover table-payvault">
						<thead>
							<th><?php echo $this->language->get('TIME'); ?></th>
							<th><?php echo $this->language->get('TO_FROM'); ?></th>
							<th><?php echo $this->language->get('AMOUNT'); ?></th>
						</thead>
						<tbody>
						    <?php foreach($this->pending as $transaction){?>
						    <?php 
						    $payto = $transaction['payto'];
						    $paymentto = dbquery("SELECT * FROM users WHERE email='$payto'")[0];
						    ?>
						    <tr onclick="redirect('/<?php echo $this->language->lang;?>/dashboard/confirm/<?php echo $transaction['tid'];?>');">
						        <td><?php echo date('H:i d/m/Y',$transaction['date']);?></td>
						        <td>To <?php echo $paymentto['firstname'].' '.$paymentto['lastname'];?></td>
						        <td>&dollar;<?php echo $transaction['amount'];?> USD</td>
						    </tr>
						    <?php }?>
						</tbody>
					</table>
				</div>
				<?php }?>
			</div>
		</div>
	</div>
</div>
<?php $this->partial('app/partial/footer.php');?>