<?php $this->partial('app/partial/header.php', array('community' => $this->community, 'title' => $this->title));?>
<div class="main-panel">
  <nav class="navbar navbar-default navbar-fixed">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">
          <?php echo $this->title; ?>
        </a>
      </div>
      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-right">
          <li>
            <a href="?logout">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="content">
      <?php $this->render('https://docs.google.com/forms/d/e/1FAIpQLSca-CnECuvUuTNM9UNmGxC5lTeUDEAt796FEqhjkUclTy1AhA/viewform?usp=sf_link'); ?>
  </div>
  <?php $this->partial('app/partial/footer.php');?>