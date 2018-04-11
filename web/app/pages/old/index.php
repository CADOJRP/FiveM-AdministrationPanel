<?php $this->partial('app/partial/header.php',array('title'=>$this->language->get('HOME')));?>
<div class="pv-herobox">
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-sm-12">
				<h4><?php echo $this->language->get('PHRASE_SLOGAN');?></h4>
			</div>
		</div>
	</div>
</div>
<div class="pv-frontpage">
	<div class="container text-center">
		<div class="col-md-4">
			<h4>Developer Friendly</h4>
		</div>
		<div class="col-md-4">
			<h4>Clean</h4>
		</div>
		<div class="col-md-4">
			<h4>Easy</h4>
		</div>
	</div>
</div>
<?php $this->partial('app/partial/footer.php');?>
