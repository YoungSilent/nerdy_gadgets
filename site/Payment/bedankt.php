<?php
require_once 'config.php';
include_once LIBS.'/Mollie/API/Autoloader.php';

$response = 'Helaas kunnen wij uw gegevens niet verwerken. Een mogelijk probleem is dat het webadres dat u naar deze pagina heeft geleid niet juist is. Probeer het opnieuw of neem contact op met de beheerder van deze applicatie.';


$update_status = '';

if (IDEALMODE == 'mollie') {
	
	if (!$db = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME)) {
		die( $db->connect_errno.' - '.$db->connect_error );
	} else {
		
		if (!empty($_GET['orderstring']) && preg_match('/^[a-f0-9]{32}$/', $_GET['orderstring'], $matches)) {
			$sess_id = $matches[0];
			$session = $db->real_escape_string($sess_id);
			$sql = sprintf( "SELECT ID, naamfrom, emailfrom, naamto, transaction_id FROM tbl_ideal_payments WHERE MD5(CONCAT('%s', ID)) = '%s'", HP_SECRET, $session );
			$result = $db->query($sql) or die($db->error);
			if ($result->num_rows == 1) {
				$obj = $result->fetch_object();
				$mollie = new Mollie_API_Client;
				$mollie->setApiKey(MOLLIE_API_KEY);
				$payment  = $mollie->payments->get($obj->transaction_id);
				
				if ($payment->isPaid() == TRUE)	{
					$update_status = 'paid';
				}
			}
		} else {
            if (!empty($_GET['ec']) && preg_match('/^([a-z0-9]{20,})$/', $_GET['ec'], $matches) && !empty($_GET['trxid'])) {
    
        		$sess_id = $matches[0];
        		$session = $db->real_escape_string($sess_id);
        		$trans_id = filter_var($_GET['trxid'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
        		$trans_id = $db->real_escape_string($trans_id);
        		$sql = sprintf("SELECT ID, naamfrom, emailfrom, naamto FROM tbl_ideal_payments WHERE ID = '%s' AND transaction_id = '%s'", $session, $trans_id);
        		$result = $db->query($sql) or die($mysqli->error);
        		if ($result->num_rows == 1) {
        			$obj = $result->fetch_object();
        			if (isset($_GET['status'])) {
        				if ($_GET['status'] == 'Success') {
        					$update_status = 'paid';
        				}
        			}
        		}
            }
		}
        if ($update_status == 'paid') {
			$response = sprintf('Beste %s,<br><br>bedankt voor uw betaling. Wij zullen zo snel mogelijk contact met u opnemen.<br><br>Met vriendelijke groet,<br>%s', $obj->naamto, $obj->naamfrom);
			$db->query(sprintf("UPDATE tbl_ideal_payments SET status = 'paid', trans_date = NOW() WHERE ID = %d AND status != 'paid'", $obj->ID));
		} else {
			$response = sprintf('Beste %s,<br><br>u heeft de betaling afgebroken of er is een fout ontstaan tijdens de betaling. Controleer uw gegevens en probeer het via de link in de e-mail later nog een keer. Voor vragen of informatie neemt u het beste <a href="mailto:%s">contact</a> met ons op.<br><br>Met vriendelijke groet,<br>%s', $obj->naamto, $obj->emailfrom, $obj->naamfrom);
		}
	}
}
?>
<!DOCTYPE html>
<html lang="nl">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex,follow"/>
    <title>iDEAL betaling via e-mail</title>

    <!-- Bootstrap core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/<?php echo APP_DIR; ?>/style.css" rel="stylesheet">

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
				<li role="presentation"><a href="mailto:<?php echo EMAILFROM; ?>">Contact</a></li>
			  </ul>
			</div>
		</nav>
      </div>

	  <div class="jumbotron">
		<h1>Uw iDEAL Betaling</h1>
		<p><?php echo $response; ?></p>
	  </div>
	  <p>&nbsp;</p>
      <footer class="footer">
        <p>&copy; <?php echo date('Y').' '.SITENAME; ?> - iDEAL betaalformulier door <a href="https://www.finalwebsites.nl/betalen-via-ideal-zonder-webshop/">finalwebsites</a></p>
      </footer>
    </div> <!-- /container -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/<?php echo APP_DIR; ?>/incl/ie10-viewport-bug-workaround.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  </body>
</html>
