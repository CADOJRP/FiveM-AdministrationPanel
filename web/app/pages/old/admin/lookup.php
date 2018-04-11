<?php $this->partial('app/partial/header.php',array('title'=>'Admin User Lookup'));?>
<div class="pv-herobox-small">
	<div class="container">
		<div class="col-xs-12 text-center">
			<h1>User Lookup</h1>
		</div>
	</div>
</div>
<div class="pv-sendmoney">
    <div class="container"> 
        <div class="col-sm-12 col-md-6 col-md-offset-3">
            <form action="/<?php echo $this->language->lang;?>/admin/userlookup" method="post" onsubmit="return submitForm($(this));">
                <div id="message"></div>
                <div class="panel panel-payvault login-form-no2fa">
                    <div class="panel-heading pv-gradient text-center text-white">
                        <a href="/<?php echo $this->language->lang;?>/dashboard"><h4><i class="fa fa-angle-left pull-left text-white"></i></h4></a>
                        <h4>User Lookup</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>User Email</label>
                            <input type="text" class="form-control login-field" name="email" placeholder="Email Address of User">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success btn-block attached-top">Search</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->partial('app/partial/footer.php');?>