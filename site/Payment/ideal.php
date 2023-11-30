<?php
require_once 'config.php';
include_once LIBS.'/sisow.cls5.php';
include_once LIBS.'/Mollie/API/Autoloader.php';
include_once DOCROOT.'/incl/func.php';



$cssclass = '';
$showinfo = true;

if (!empty($_GET['pid']) && preg_match('/^([a-z0-9]{20,})$/', $_GET['pid'], $matches)) {
	if (!$db = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME)) {
		die($db->connect_errno.' - '.$db->connect_error);
	} else {
		$sess_id = $matches[0];
		$session = $db->real_escape_string($sess_id);
		$sql = sprintf("SELECT * FROM tbl_ideal_payments WHERE ID = '%s'", $session);
		$result = $db->query($sql) or die($mysqli->error);

		if ($result->num_rows == 1) {
			$obj = $result->fetch_object();
			$bedrag = number_format($obj->bedrag, 2, ',', '');
			if (in_array($obj->status, array('send', 'cancel'))) {
				if (isset($_POST['issuerid'])) {
					if ($_POST['issuerid'] == '') {
						$msg = 'Kies een bank uit de lijst om verder te gaan.';
						$cssclass = 'alert alert-warning';
					} else {
						$payment = create_ideal_payment($obj);
						if ($payment['url'] != '' && $payment['transid'] != '') {
							$trans_id = $db->real_escape_string($payment['transid']);
							$db->query(sprintf("UPDATE tbl_ideal_payments SET transaction_id = '%s' WHERE ID = %d", $trans_id, $obj->ID));
							header("Location: " . $payment['url']);
							exit;
						} else {
							$msg = $payment['msg'];
							$cssclass = $payment['css'];
						}
					}
				}
			} else {
				$msg = 'De gegevens voor dit betaalverzoek zijn niet meer geldig.';
				$cssclass = 'alert alert-warning';
				$showinfo = false;
			}
		} else {
			$msg = 'Kan het betaalverzoek niet vinden.';
			$cssclass = 'alert alert-warning';
			$showinfo = false;
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
	  <form id="postform" action="/sip/<?php echo $sess_id; ?>" method="post" class="form-inline">
		  <div class="jumbotron">
			<h1>iDEAL Betaling</h1>
			<?php
			if ($showinfo) {
				echo '
			<p>'.sprintf('Beste %s,<br><br>via deze pagina wil ik u vragen om het bedrag via iDEAL te betalen. ', $obj->naamto).'</p>
			<p>'.sprintf('Omschrijving: %s<br>Bedrag: €%s', $obj->descr, $bedrag).'</p>
			<p>Met vriendelijke groet,<br>'.$obj->naamfrom.'</p>';
			}
			?>
		  </div>

		  <div class="row marketing">
			<div class="col-lg-12">

				<?php
				if ($showinfo) {
					echo '
			  <div class="form-group">
				'.create_bank_select('Selecteer bank').'
			  </div>
			  <button type="submit" class="btn btn-primary" id="submit-btn">Verzenden</button>';
				} else {
					if (!$selectdata) {
						$msg = 'Kan de bankenlijst niet ophalen.';
					}
					$cssclass = 'alert alert-warning';
				}
				?>
			</div>
		  </div>
		  <div id="message" class="<?php echo $cssclass; ?>" role="alert"><?php echo $msg; ?></div>
	  </form>
      <footer class="footer">
        <p>&copy; <?php echo date('Y').' '.SITENAME; ?> - iDEAL betaalformulier door <a href="https://www.finalwebsites.nl/betalen-via-ideal-zonder-webshop/">finalwebsites</a></p>
      </footer>
    </div> <!-- /container -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/<?php echo APP_DIR; ?>/incl/ie10-viewport-bug-workaround.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  </body>
</html>
