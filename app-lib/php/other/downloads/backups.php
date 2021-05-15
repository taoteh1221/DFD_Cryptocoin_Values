<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


$filename_sections = explode("_", $_GET['backup']);


foreach ( $filename_sections as $section ) {
	
$section = preg_replace("/\.zip/", "", $section);

	// Detect whether or not the filename requested is privately secured 
	// with a hexidecimal hash suffix of 32 characters or higher.
	// If not then cancel allowing the download
	if ( ctype_xdigit($section) && strlen($section) >= 32 ) {
	$private_filename = 1;
	}

}

if ( $private_filename != 1 ) {
die('Non-private filename request detected, download aborted.');
}


$path = "cache/secured/backups/";

$fullPath = $path.$_GET['backup'];


if ($fd = fopen ($fullPath, "r")) {
    $fsize = filesize($fullPath);
    $path_parts = pathinfo($fullPath);
    $ext = strtolower($path_parts["extension"]);
    
    switch ($ext) {
        case "pdf":
        header("Content-type: application/pdf"); // add here more headers for diff. extensions
        header("Content-disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
        break;
        default;
        header("Content-type: application/octet-stream");
        header("Content-disposition: filename=\"".$path_parts["basename"]."\"");
    }
    
    header("Content-length: $fsize");
    header("Cache-control: private"); //use this to open files directly
    
    // Clear output buffer, or it may corrupt download
    ob_clean();
    flush();
      
    while(!feof($fd)) {
        $buffer = fread($fd, 2048);
        echo $buffer;
    }
    
}


// Log errors / debugging, send notifications
$pt_cache->error_logs();
$pt_cache->debug_logs();
$pt_cache->send_notifications();

fclose ($fd);
gc_collect_cycles(); // Clean memory cache
exit;


?>