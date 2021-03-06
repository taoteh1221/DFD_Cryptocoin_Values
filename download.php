<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


error_reporting(0); // Turn off all error reporting (0), or enable (1)

$runtime_mode = 'download';


// Flag as CSV export BEFORE config.php (to run minimized logic from init.php)
if ( $_GET['csv_export'] == 1 ) {
$is_csv_export = true;
}
// Flag as notes download BEFORE config.php (to run minimized logic from init.php)
else if ( $_GET['notes'] == 1 ) {
$is_notes = true;
}


require("config.php");


// Backups download
if ( $_GET['backup'] != null ) {
require_once( $base_dir . "/app-lib/php/other/downloads/backups.php");
}


// NO LOGS / DEBUGGING / MESSAGE SENDING AT RUNTIME END HERE 
// (WE ALWAYS EXIT BEFORE HERE IN EACH REQUIRED FILE, OR WE SKIP IT FOR MINIMIZED RUNTIME LOGIC ETC)


?>