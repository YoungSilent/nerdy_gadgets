<?php
require_once 'config.php';

if (!in_array($_SERVER['REMOTE_ADDR'], unserialize(UW_IP_ADRES))) {
	die('Toegang geweigerd!');
}

if (!empty($_GET['opnieuw'])) {
	if (!$db = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME)) {
		die($db->connect_errno.' - '.$db->connect_error);
	} else {
		$session = $db->real_escape_string(session_id());
		$db->query(sprintf("DELETE FROM tbl_ideal_payments WHERE ID = '%s' AND status = 'open'", $session));
		session_regenerate_id();
	}
}
$msg = file_get_contents(DOCROOT.'/incl/emailtemplate.tpl');
?>
<!DOCTYPE html>
<html lang="nl">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
	  <form id="postform">
		  <div class="jumbotron">
			<h1>iDEAL Betaling via E-mail</h1>
			<p>Gebruik het onderstaande formulier voor het aanmaken en versturen van een iDEAL betaling.</p>
		  </div>
		  <div class="row marketing">
			<div class="col-lg-6">
			  <h2>Betaalgegevens</h2>
			  <div class="form-group">
				<label for="InputBedrag">Te betalen bedrag</label>
				<div class="input-group">
				  <span class="input-group-addon">€</span>
				  <input type="number" name="euros" class="form-control" id="InputBedrag" min="0" step="1" data-bind="value:replyNumber" placeholder="0">
				  <span class="input-group-addon">,</span>
				  <input type="number" name="centen" class="form-control" id="InputBedrag2" min="0" step="1" data-bind="value:replyNumber" placeholder="00">
				</div>
			  </div>
			  <div class="form-group">
				<label for="InputDescr">Omschrijving voor de iDEAL betaling</label>
				<input type="text" name="paydecr" class="form-control" id="InputDescr" placeholder="max. 32 karakters!">
			  </div>
			  <h2>Bijlage toevoegen</h2>
			  <p>Upload hier een bijlage voor uw e-mailbericht. De volgende bestandstypen zijn toegestaan: PDF, Word document of ZIP archief.</p>
			  <div class="form-group">
				<p id="uplmsg"></p>
				<span class="btn btn-default btn-file">
					<i class="glyphicon glyphicon-plus"></i>
					<span>Kies bestand...</span>
					<input id="fileupload" type="file" name="files">
				</span>
			  </div>
			  <div id="uploadscontainer"></div>
			</div>
			<div class="col-lg-6">
			  <h2>Contactgegevens</h2>
			  <div class="form-group">
				<label for="InputEmail1">E-mailadres verzender</label>
				<input type="email" name="emailfrom" class="form-control" id="InputEmail1" value="<?php echo EMAILFROM; ?>" readonly>
			  </div>
			  <div class="form-group">
				<label for="InputName1">Naam verzender</label>
				<input type="text" name="namefrom" class="form-control" id="InputName1" value="<?php echo SITENAME; ?>" readonly>
			  </div>
			  <div class="form-group">
				<label for="InputEmail2">E-mailadres ontvanger</label>
				<input type="email" name="emailto" class="form-control" id="InputEmail2" placeholder="">
			  </div>
			  <div class="form-group">
				<label for="InputName2">Naam ontvanger</label>
				<input type="text" name="nameto" class="form-control" id="InputName2" placeholder="">
			  </div>
			</div>
		  </div>
		  <div class="row marketing">
			<div class="col-lg-12">
			  <div class="form-group">
				<label for="InputSubject">Onderwerp voor e-mailbericht</label>
				<input type="text" name="subject" class="form-control" id="InputSubject" placeholder="Kies een duidelijk onderwerp...">
			  </div>
			  <div class="form-group">
				<label for="InputMailtext">Tekst voor e-mailbericht</label>
				<textarea class="form-control" id="InputMailtext" name="mailtext" rows="5" aria-describedby="helpBlock"><?php echo $msg; ?></textarea>
				<span id="helpBlock" class="help-block">Wijzig hier uw e-mailbericht. Let op, verwijder niet de teksten zoals {IDEALLINK}.</span>
			  </div>
			  <a class="btn btn-link pull-right" href="?opnieuw=1">Opnieuw beginnen</a>
			  <input type="hidden" name="attachment" id="InputAttachment" value="">
			  <input type="hidden" name="Submit" value="1">
			  <button type="button" class="btn btn-primary" id="submit-btn">Verzenden</button>
			</div>
		  </div>
		  <div id="message"></div>
	  </form>
      <footer class="footer">
        <p>&copy; <?php echo date('Y').' '.SITENAME; ?> - iDEAL betaalformulier door <a href="https://www.finalwebsites.nl/betalen-via-ideal-zonder-webshop/">finalwebsites</a></p>
      </footer>
    </div> <!-- /container -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="incl/ie10-viewport-bug-workaround.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="incl/vendor/jquery.ui.widget.js"></script>
	<script src="incl/jquery.iframe-transport.js"></script>
	<script src="incl/jquery.fileupload.js"></script>
    <script>
	function isValidEmailAddress(emailAddress) {
		var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
		return pattern.test(emailAddress);
	}
	function keepLB(str) {
		var reg = new RegExp("(%0A)", "g");
		return str.replace(reg,"%0D$1");
	}
	$(document).ready(function() {

		$("#InputDescr").attr('maxlength','32');

		$('#fileupload').fileupload({
			dataType: 'json',
			url: 'incl/upload/',
			add: function (e, data) {
				$('#uplmsg').html('');
				data.context = $('#uplmsg').html('Uploading...');
				data.submit();
			},
			done: function (e, data) {
				 $.each(data.result.files, function (index, file) {
					if (file.error) {
						$('#uplmsg').addClass('text-warning').html(file.error);
					} else {
						$('#uplmsg').addClass('text-success').html('Upload voltooid!');
						$('#uploadscontainer').html(file.name + ' <a href="javascript:void(0);" class="glyphicon glyphicon-trash" aria-hidden="true" data-type="' + file.deleteType + '" data-url="' + file.deleteUrl + '"></a>');
						$('#InputAttachment').val(file.name);
					}
				});

			}
		});

		$('#uploadscontainer').on('click', 'a', function (e) {
			e.preventDefault();
			$('#uplmsg').html('');
			var delfile = $(this);
			$.ajax({
				url: delfile.attr('data-url'),
				type: delfile.attr('data-type'),
				success: function(result) {
					$('#uplmsg').addClass('text-info').html('Bestand verwijderd!');
					$('#InputAttachment').val('');
					$('#uploadscontainer').html('');
				}
			});
		});

		$('#submit-btn').click(function() {

			$('#message').removeClass('alert alert-success alert-warning');
			$('#submit-btn').prop('disabled', true);
			$('#message').html('<img src="img/loading.gif" alt="">');
			var formdata
			$.ajax({
				type: 'POST',
				url: 'incl/sendemail.php',
				data: decodeURIComponent($('#postform').serialize()),
				dataType: 'json',
				beforeSend: function() {
					var subject = $('#InputSubject').val();
					var bericht = $('#InputMailtext').val();
					var bedrag = $('#InputBedrag').val();
					var decbedrag = $('#InputBedrag2').val();
					var descr = $('#InputDescr').val();
					var naam = $('#InputName2').val();
					var email = $('#InputEmail2').val();
					if (!bericht || !naam || !email || !descr || !bedrag || !subject) {
						$('#message').addClass('alert alert-warning').html('Alle velden moeten worden ingevuld.');
						$('#submit-btn').prop('disabled', false);
						return false;
					}
					if (parseInt(bedrag) <= 0 || parseInt(decbedrag) < 0) {
						$('#message').addClass('alert alert-warning').html('Het ingevoerde bedrag is niet geldig!');
						$('#submit-btn').prop('disabled', false);
						return false;
					}
					if (!isValidEmailAddress(email)) {
						$('#message').addClass('alert alert-warning').html('Het ingevoerde e-mailadres heeft niet het juiste formaat.');
						$('#submit-btn').prop('disabled', false);
						return false;
					}

				},
				success: function(response) {
					if (response.status == 'success') {
						$('#postform')[0].reset();
						$('#uploadscontainer').html('');
						$('#uplmsg').html('');
						$('#message').addClass('alert alert-success');
					} else {
						$('#message').addClass('alert alert-warning');
					}
					$('#submit-btn').prop('disabled', false);
					$('#message').html(response.error);
				}
			});
		});
	});
	</script>
  </body>
</html>
