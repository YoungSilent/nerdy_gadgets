<?php
// Instellingen voor het script "iDEAL betalingen"

define('HOSTNAME', 'www.uwdomeinnaam.nl');
define('APP_DIR', 'ideal-betalingen');

$whitelist = array('145.44.85.178', 'IP adres 2');  // Beperk de toegang tot het formulier en het overzicht op basis van uw IP adres(sen)
define('UW_IP_ADRES', serialize($whitelist));
define('SESSION_SECRET', '7dPw45dahYr'); // gebruik hier enkele random letters en of cijfers

define('DB_USER', 'database user');
define('DB_PASSWORD', 'database wachtwoord');
define('DB_SERVER', 'localhost'); // in de meeste gevallen is dit localhost
define('DB_NAME', 'database naam');

define('SITENAME', 'NerdyGadgets');
define('EMAILFROM', 'contact@NerdyGadgets.nl');

// Geen SMTP server? Wij adviseren een gratis account bij mailgun.com
define('SMTP_HOST', 'smtp.servernaam.nl');
define('SMTP_PORT', 587); // of 25, afhankelijk van de server
define('SMTP_LOGIN', 'Uw SMTP loginnaam');
define('SMTP_PASSWORD', 'Uw SMTP wachtwoord');
define('HTML_EMAIL', 'false');// zet dit op "true" wanneer uw een HTML email template heeft

define('HP_SECRET', 'kies hier een random string');
define('SISOW_MERCHANT_ID', 'Uw Sisow Merchant ID');
define('SISOW_MERCHANT_KEY', 'Uw Sisow Merchant Key');
define('SISOW_TEST', 'true');

define('MOLLIE_API_KEY', 'Uw Mollie API Key'); // test_xxx of live_xxx

define('IDEALMODE', 'mollie'); // mollie of sisow

$proto = ($_SERVER['HTTP_HTTPS'] == 'on') ? 'https' : 'http'; // niet wijzigen
define('PROTO', $proto); // niet wijzigen
define('BEDANKT_PAGINA', PROTO.'://'.HOSTNAME.'/sip/bedankt/');
define('GEANNULEERD_PAGINA', PROTO.'://'.HOSTNAME.'/sip/geannuleerd/');
define('WEBHOOK_URL', PROTO.'://'.HOSTNAME.'/sip/callback/');

// Geen wijzigingen hieronder uitvoeren!
define('DOCROOT', dirname(__FILE__));
define('LIBS', DOCROOT.'/libs');

if (session_status() == PHP_SESSION_NONE) {
	session_id(md5(SESSION_SECRET));
    session_start();
}
