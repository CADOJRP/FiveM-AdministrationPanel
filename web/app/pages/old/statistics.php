<?php $this->partial('app/partial/header.php',array('title'=>'Statistics'));?>
<div class="pv-herobox-small">
	<div class="container">
		<div class="col-xs-12 text-center">
			<h1><?php echo $this->language->get('STATISTICS');?></h1>
		</div>
	</div>
</div>
<div class="pv-dashboard">
	<div class="container">
		<div class="row">
			<div class="col-6 col-sm-3">
				<div class="panel panel-payvault">
					<div class="panel-heading text-center">
						<h4>Last 30 Days</h4>
						$100 USD
					</div>
				</div>
			</div>
			<div class="col-6 col-sm-3">
				<div class="panel panel-payvault">
					<div class="panel-heading text-center">
						Test
					</div>
				</div>
			</div>
			<div class="col-6 col-sm-3">
				<div class="panel panel-payvault">
					<div class="panel-heading text-center">
						Test
					</div>
				</div>
			</div>
			<div class="col-6 col-sm-3">
				<div class="panel panel-payvault">
					<div class="panel-heading text-center">
						Test
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->partial('app/partial/footer.php');?>