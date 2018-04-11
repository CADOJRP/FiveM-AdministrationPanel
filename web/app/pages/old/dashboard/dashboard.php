<?php $this->partial('app/partial/header.php',array('title'=>$this->language->get('DASHBOARD')));?>
<div class="pv-herobox-small">
	<div class="container">
		<div class="col-xs-12 text-center">
			<h1><?php echo $this->language->get('DASHBOARD');?></h1>
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
						<li class="list-group-item" onclick="redirect('/<?php echo $this->language->lang;?>/statistics');">Account Statistics</li>
						<li class="list-group-item" onclick="redirect('/<?php echo $this->language->lang;?>/account');"><?php echo $this->language->get('ACCOUNT_DETAILS'); ?></li>
					</ul>
				</div>
			</div>
			<div class="col-md-9">
				<div class="panel panel-payvault">
					<div class="panel-heading">
						<h4><?php echo $this->language->get('TRANSACTION_HISTORY'); ?></h4>
					</div>
				</div>
				<div class="panel panel-payvault">
					<table class="table table-borderless table-hover table-payvault">
						<thead>
							<th><?php echo $this->language->get('TIME'); ?></th>
							<th><?php echo $this->language->get('TO_FROM'); ?></th>
							<th><?php echo $this->language->get('AMOUNT'); ?></th>
							<th><?php echo $this->language->get('BALANCE'); ?></th>
						</thead>
						<tbody>
						    
							<?php foreach($this->transactions as $transaction){?>
							
							<tr onclick="redirect('/<?php echo $this->language->lang;?>/transaction/<?php echo $transaction['TID'];?>');">
								<?php if($transaction['email_from'] == $_SESSION['email']){?>
								<td><?php echo date('H:i d/m/Y',$transaction['date']);?></td>
								<?php
								$email = $transaction['email_to'];
								$name = dbquery("SELECT firstname,lastname FROM users WHERE email='$email'");
								?>
								<td><?php echo $this->language->get('PAYMENT_TO') . ' '.$name[0]['firstname'].' '.$name[0]['lastname'];?></td>
								
								<td><?php echo '&dollar;-'.$transaction['amount'].' USD';?></td>
								<td><?php echo '&dollar;'.$transaction['balance_sender'].' USD';?></td>
								<?php } else {?>
								<td><?php echo date('H:i d/m/Y',$transaction['date']);?></td>
								<?php
								$email = $transaction['email_from'];
								$name = dbquery("SELECT firstname,lastname FROM users WHERE email='$email'");
								?>
								<td><?php echo $this->language->get('PAYMENT_FROM') . ' '.$name[0]['firstname'].' '.$name[0]['lastname'];?></td>
								<td><?php echo '&dollar;'.$transaction['amount'].' USD';?></td>
								<td><?php echo '&dollar;'.$transaction['balance_receiver'].' USD';?></td>
								<?php }?>
							</tr>
							
							<?php }?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->partial('app/partial/footer.php');?>
