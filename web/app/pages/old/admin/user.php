<?php $this->partial('app/partial/header.php',array('title'=>'Admin User Lookup'));?>
<?php
  $userdetails = dbquery("SELECT * FROM users WHERE id='$this->userid'");
  foreach($userdetails as $userdetail) {
    
  }
?>
<?php $this->partial('app/partial/footer.php');?>