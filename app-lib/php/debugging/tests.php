<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



// UNIT TESTS


// ONLY RUN THESE UNIT TESTS IF RUNTIME IS UI (web page loading)
if ( $runtime_mode == 'ui' ) {




	// Check configured charts and price alerts
	if ( $debug_mode == 'all' || $debug_mode == 'charts' ) {
		
		foreach ( $asset_charts_and_alerts as $key => $value ) {
				
		// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
		$check_asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, strpos($key, "-") ) );
		$check_asset = strtoupper($check_asset);
		
		$check_asset_params = explode("||", $value);
		
		$check_pairing_name = $coins_list[$check_asset]['market_pairing'][$check_asset_params[1]][$check_asset_params[0]];
		
		$test_result = get_coin_value($check_asset, $check_asset_params[0], $check_pairing_name)['last_trade'];
		
			if ( $test_result == NULL ) {
			app_error( 'other_error', 'No chart / price alert market data available', 'chart_key: ' . $key . '; market: ' . $check_asset . ' / ' . strtoupper($check_asset_params[1]) . ' @ ' . ucfirst($check_asset_params[0]) );
		}
				
		
		}
	
	}
	
	
	
	
	// Check configured email to mobile text gateways
	if ( $debug_mode == 'all' || $debug_mode == 'texts' ) {
	
		foreach ( $mobile_networks as $key => $value ) {
			
			$test_result = validate_email( 'test@' . trim($value) );
		
			if ( $test_result != 'valid' ) {
			app_error( 'other_error', 'email-to-mobile-text gateway '.trim($value).' does not appear valid', 'key: ' . $key . '; gateway: ' . trim($value) . '; result: ' . $test_result );
			}
		
		}
	
	}
	
	
	
	
	// Check configured coin markets
	if ( $debug_mode == 'all' || $debug_mode == 'markets' ) {
		
		foreach ( $coins_list as $coin_key => $coin_value ) {
		
		
			foreach ( $coin_value['market_pairing'] as $pairing_key => $pairing_value ) {
			
			
				foreach ( $pairing_value as $key => $value ) {
				
				$test_result = get_coin_value( strtoupper($coin_key) , $key , $value )['last_trade'];
				
					if ( $test_result == NULL ) {
					app_error( 'other_error', 'No coin market data available', strtoupper($coin_key) . ' / ' . strtoupper($pairing_key) . ' @ ' . ucfirst($key) );
					}
				
				}
				
			
			}
			
		
		}
	
	}
		



}

?>