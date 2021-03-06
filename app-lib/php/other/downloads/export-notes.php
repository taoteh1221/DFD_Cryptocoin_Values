<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


require_once($base_dir . '/app-lib/php/other/sub-init/minimized-sub-init.php');


// CSRF attack protection (REQUIRED #POST# VAR 'submit_check')
if ( $_POST['submit_check'] != 1 ) {
$pt_gen->log('security_error', 'Missing "submit_check" POST data (-possible- CSRF attack) for request: ' . $_SERVER['REQUEST_URI']);
$pt_cache->error_logs();
exit;
}


$file = tempnam(sys_get_temp_dir(), 'temp');
$fp = fopen($file, 'w');

fwrite($fp, $_COOKIE['notes']);
fclose($fp);

$pt_gen->file_download($file, 'Trading-Notes.txt'); // Download file (by default deletes after download, then exits)
exit;


?>