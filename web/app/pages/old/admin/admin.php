<?php $this->partial('app/partial/header.php',array('title'=>'Admin ' . $this->language->get('DASHBOARD')));?>
<div class="pv-herobox-small">
	<div class="container">
		<div class="col-xs-12 text-center">
			<h1>Admin <?php echo $this->language->get('DASHBOARD'); ?> <h1>
		</div>
	</div>
</div>
<div class="pv-dashboard">
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<div class="panel panel-payvault">
					<div class="panel-heading text-center">
						<h5>Support Tickets:<br>3</h5>
					</div>
				</div>
				<div class="panel panel-payvault">
					<ul class="list-group">
						<li class="list-group-item" onclick="redirect('/<?php echo $this->language->lang;?>/admin/userlookup');">Lookup User</li>
					</ul>
				</div>
			</div>
			</div>
		</div>
	</div>
</div>
<?php $this->partial('app/partial/footer.php');?>
