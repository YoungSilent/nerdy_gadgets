<?php
require_once '../config.php';
include_once LIBS.'/phpmailer/PHPMailerAutoload.php';



$error = '';
$status = 'error';

if (isset($_POST['Submit'])) {
	if (empty($_POST['euros']) || empty($_POST['emailto']) || empty($_POST['nameto']) || empty($_POST['mailtext']) || empty($_POST['subject']) || empty($_POST['paydecr'])) {
		$error = 'Tenminste een van de verplichte velden is leeg.';
	} else {
		if ((int)$_POST['euros'] < 0 || (isset($_POST['centen']) && (int)$_POST['centen'] < 0)) {
			$error = 'De ingevoerde bedragen mogen niet negatief zijn.';
		} else {
			if (!$db = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME)) {
				die($db->connect_errno.' - '.$db->connect_error);
			} else {
				$session = $db->real_escape_string(session_id());
				$sql = sprintf("SELECT status FROM tbl_ideal_payments WHERE ID = '%s'", $session);
				$result = $db->query($sql) or die($mysqli->error);
				if ($result->num_rows == 1) {
					$error = 'Het bericht met dit betaalverzoek heeft u al eerder verzonden. <a href="?opnieuw=1">Klik hier</a> om een nieuwe e-mail te verzenden.';
				} else {
					$contact = filter_var($_POST['nameto'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
					$emailto = filter_var($_POST['emailto'], FILTER_SANITIZE_EMAIL);
					$mailmsg = filter_var($_POST['mailtext'], FILTER_SANITIZE_STRING, !FILTER_FLAG_STRIP_LOW);
					$subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
					$paydecr = filter_var($_POST['paydecr'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
					$link = PROTO.'://'.HOSTNAME.'/sip/'.urlencode($session);
					$bedrag = (int)$_POST['euros'];
					if ((int)$_POST['centen'] > 0) {
						$bedrag = $bedrag + ((int)$_POST['centen']/100);
					}
					$mailmsg = str_replace('{CONTACT}', $contact, $mailmsg);
					$mailmsg = str_replace('{BEDRAG}', number_format($bedrag, 2, ',', ''), $mailmsg);
					$mailmsg = str_replace('{IDEALLINK}', $link, $mailmsg);
					$mailmsg = str_replace('{BEDRIJFSNAAM}', SITENAME, $mailmsg);
					$mailmsg = str_replace('{DATUMTIJD}', date('d-m-Y H:i'), $mailmsg);

					if (HTML_EMAIL == 'true') {
						$template = file_get_contents(DOCROOT.'/incl/emailtemplate.tpl');
						$mailmsg = str_replace('##MAILTEXT##', $mailmsg, $template);
					}
					
					$stmt = $db->prepare("INSERT INTO tbl_ideal_payments SET ID = ?, datumtijd = NOW(), naamfrom = ?, emailfrom = ?, naamto = ?, emailto = ?, bedrag = ?, descr = ?, mailsubject = ?, mailtekst = ?, ipadres = ?, status = 'open'");
					//var_dump($stmt);
					$stmt->bind_param('sssssdssss', $session, $naamfrom, $emailfrom, $contact, $emailto, $bedrag, $paydecr, $subject, $mailmsg, $_SERVER['REMOTE_ADDR']);

					$naamfrom = SITENAME;
					$emailfrom = EMAILFROM;


					$stmt->execute();
					$stmt->close();

					$mail = new PHPMailer();
					$mail->CharSet = 'utf-8';
					$mail->IsSMTP();
					$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
					$mail->SMTPAuth = true;
					$mail->Host = SMTP_HOST;
					$mail->Port = SMTP_PORT;
					$mail->Username = SMTP_LOGIN;
					$mail->Password = SMTP_PASSWORD;
					$mail->SetFrom(EMAILFROM, SITENAME, 0);
					if (HTML_EMAIL != 'true') {
						$mail->isHTML(false);
						$mail->Body = $mailmsg;
					} else {
						$mail->MsgHTML($mailmsg);
					}
					if (!empty($_POST['attachment'])) {
						$file = filter_var($_POST['attachment'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
						$filepath = DOCROOT.'/tmp/'.$file;
						if (file_exists($filepath)) {
							$finfo = finfo_open(FILEINFO_MIME_TYPE);
							if (!$mimetype = finfo_file($finfo, $filepath)) {
								$mimetype = 'application/octet-stream';
							}
							finfo_close($finfo);
							$mail->AddAttachment($filepath, $file, 'base64', $mimetype);
						}
					}
					$mail->Subject = $subject;
					//$mail->AddCC(EMAILFROM, SITENAME);
					$mail->AddAddress($emailto);
					if (!$mail->Send()) {
						$error = 'Fout bij het versturen van de e-mail.';
					} else {
						$db->query(sprintf("UPDATE tbl_ideal_payments SET status = 'send' WHERE ID = '%s'", $session));
						$error = 'Het bericht (inclusief betaallink) is verzonden.';
						$status = 'success';
						session_regenerate_id();
						if (is_file($filepath)) unlink($filepath);
					}
				}
				$db->close();
			}
		}
	}
}
$resp = array('status'=>$status, 'error'=>$error);
echo json_encode($resp);
exit;
