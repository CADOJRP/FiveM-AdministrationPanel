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
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="header">
              <h4 class="title">Player List</h4>
            </div>
            <div class="content table-responsive">
                <table id="players" class="table table-hover table-striped table-bordered" style="width:100%;cursor:pointer;">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Playtime</th>
                            <th>Trust Score</th>
                            <th>First Played</th>
                            <th>Last Played</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Playtime</th>
                            <th>Trust Score</th>
                            <th>First Played</th>
                            <th>Last Played</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
        $(document).ready(function() {
            $('#players').DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax": "<?php echo $GLOBALS['domainname']; ?>api/playerslist",
            } );
            
            var table = $('#players').DataTable();
            
            $('#players tbody').on('click', 'tr', function () {
                var data = table.row( this ).data();
                window.location = "<?php echo $GLOBALS['domainname']; ?>user/" + data[-1]
            } );
        } );
  </script>
  <?php $this->partial('app/partial/footer.php');?>