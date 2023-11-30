<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

require_once '../../config.php';
include_once LIBS.'/UploadHandler.php';


//print_r($_SERVER);


$options = array(
	'upload_dir' => DOCROOT.'/tmp/',
	'upload_url' => PROTO.'://'.HOSTNAME.'/'.APP_DIR.'/tmp/',
	'max_file_size' => 1024*1024*2, // 2MB
	'accept_file_types' => '/\.(zip|pdf|docx?)$/i'
);
$error_messages = array(
	1 => 'De bestandsgrootte is groter dan de waarde voor de instelling upload_max_filesize',
	2 => 'De bestandsgrootte is groter dan de MAX_FILE_SIZE waarde binnen het HTML formulier',
	3 => 'Het bestand is maar gedeeltelijk geüpload',
	4 => 'Geen bestand geüpload',
	6 => 'De folder voor tijdelijk bestanden bestaat niet',
	7 => 'Kan het bestand niet kopiëren',
	8 => 'Het upload is onderbroken door de PHP extensie',
	'post_max_size' => 'De bestandsgrootte is groter dan de waarde voor de instelling post_max_size',
	'max_file_size' => 'Bestand is te groot',
	'min_file_size' => 'Bestand is te klein',
	'accept_file_types' => 'Bestandstype is niet toegestaan',
	'abort' => 'Upload is afgebroken'
);

$upload_handler = new UploadHandler($options, true, $error_messages);


	
	

