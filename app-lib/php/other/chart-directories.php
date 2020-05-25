<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



///////////////////////////////////////////////////////////////////////////
// Chart data cache sub-directory creation
///////////////////////////////////////////////////////////////////////////
// Structure of lite charts sub-directories
$lite_charts_structure = array(
									3,
									7,
									30,
									90,
									180,
									365,
									730,
									1460,
									'all',
									);

// ALL CHARTS FOR SPOT PRICE / 24 HOUR VOLUME
foreach ( $app_config['charts_alerts']['tracked_markets'] as $key => $value ) {

	// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
	$asset_dir = ( stristr($key, "-") == false ? $key : substr( $key, 0, mb_strpos($key, "-", 0, 'utf-8') ) );
	$asset_dir = strtoupper($asset_dir);
		
	$asset_cache_params = explode("||", $value);
	
	if ( $asset_cache_params[2] == 'chart' || $asset_cache_params[2] == 'both' ) {
		
		// Archival charts
		if ( dir_structure($base_dir . '/cache/charts/spot_price_24hr_volume/archival/'.$asset_dir.'/') != TRUE ) { // Attempt to create directory if it doesn't exist
		$disabled_caching = 1;
		}
		
		// Lite charts
		foreach( $lite_charts_structure as $lite_chart_days ) {
			
			if ( dir_structure($base_dir . '/cache/charts/spot_price_24hr_volume/lite/'.$lite_chart_days.'_day/'.$asset_dir.'/') != TRUE ) { // Attempt to create directory if it doesn't exist
			$disabled_caching = 1;
			}
			
		}
	
	}
	
}

// LITE CHARTS FOR SYSTEM STATS
foreach( $lite_charts_structure as $lite_chart_days ) {
			
	if ( dir_structure($base_dir . '/cache/charts/system/lite/'.$lite_chart_days.'_day/') != TRUE ) { // Attempt to create directory if it doesn't exist
	$disabled_caching = 1;
	}
			
}

if ( $disabled_caching == 1 ) {
echo "Improper directory permissions on the '/cache/charts/' sub-directories, cannot create new sub-directories. Make sure the folder '/cache/charts/' AND ANY SUB-DIRECTORIES IN IT have read / write permissions (and further sub-directories WITHIN THESE should be created automatically)";
exit;
}
///////////////////////////////////////////////////////////////////////////
// END chart sub-directory creation
///////////////////////////////////////////////////////////////////////////

  
 
 ?>