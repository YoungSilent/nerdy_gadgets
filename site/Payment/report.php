<?php
require_once 'config.php';

if (!in_array($_SERVER['REMOTE_ADDR'], unserialize(UW_IP_ADRES))) {
	die('Toegang geweigerd!');
}


if (!$db = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME)) {
	die($db->connect_errno.' - '.$db->connect_error);
} else {
	$result = $db->query("SELECT datumtijd, naamto, emailto, bedrag, descr, status FROM tbl_ideal_payments WHERE status != 'open' ORDER BY datumtijd DESC LIMIT 0, 25") or die($mysqli->error);
}

?>
<!DOCTYPE html>
<html lang="nl">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/favicon.ico">
	<meta name="robots" content="noindex,follow"/>
    <title>iDEAL betaling via e-mail</title>

    <!-- Bootstrap core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
      <div class="header clearfix">
        <nav>
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			  <a class="navbar-brand logo" href="/<?php echo APP_DIR; ?>/"><img src="/<?php echo APP_DIR; ?>/img/logo.jpg" alt="Logo"></a>
			</div>
			<div id="navbar" class="collapse navbar-collapse">
			  <ul class="nav nav-pills pull-right">
				<li role="presentation"><a href="/<?php echo APP_DIR; ?>/?opnieuw=1">Start</a></li>
				<li role="presentation"><a href="/<?php echo APP_DIR; ?>/report.php">Overzicht</a></li>
			  </ul>
			</div>
		</nav>
      </div>

	  <div class="jumbotron">
		<h1>iDEAL Betaling via E-mail</h1>
		<p>
		<?php
		if ($result->num_rows > 0 ) {
			echo 'Vind hieronder vindt u de '.$numrows.' meest recente betaalverzoeken. ';
		} else {
			echo 'Op dit moment zijn er nog geen betaalverzoeken ingevoerd.';
		}
		echo ' <a href="/'.APP_DIR.'/?opnieuw=1">Klik hier</a> om een nieuw betaalverzoek in te voeren.';
		?>
		</p>
	  </div>
	  <?php
	  if ($result->num_rows > 0 ) {
		  echo '
	  <div class="lastitems">
	    <table class="table table-condensed">
	      <thead>
	        <tr>
			  <th>Ontvanger</th>
			  <th>Datum verzonden</th>
			  <th>Omschrijving</th>
			  <th>Status</th>
			  <th>Bedrag</th>
	        </tr>
	      </thead>
	      <tbody>';
	      $status = array('send'=>'verzonden', 'cancel'=>'geannuleerd', 'paid'=>'betaald');
	      while ($obj = $result->fetch_object()) {
			  echo '
		    <tr>
			  <td><a href="mailto:'.$obj->emailto.'">'.$obj->naamto.'</a></td>
			  <td>'.date('d-m-Y H:i', strtotime($obj->datumtijd)).'</td>
			  <td>'.$obj->descr.'</td>
			  <td>'.$status[$obj->status].'</td>
			  <td class="text-right">€'.number_format($obj->bedrag, 2, ',', '').'</td>
		    </tr>';
		  }
	      echo '
	      </tbody>
	    </table>
	  </div>';
	  }
	  ?>
      <footer class="footer">
        <p>&copy; <?php echo date('Y').' '.SITENAME; ?> - iDEAL betaalformulier door <a href="https://www.finalwebsites.nl/betalen-via-ideal-zonder-webshop/">finalwebsites</a></p>
      </footer>
    </div> <!-- /container -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="incl/ie10-viewport-bug-workaround.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

  </body>
</html>
