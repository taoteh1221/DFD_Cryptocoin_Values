<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */



class pt_assets {
	
// Class variables / arrays
var $pt_var1;
var $pt_var2;
var $pt_var3;
var $pt_array1 = array();

    
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function powerdown_primary_currency($data) {
   
   global $hive_market, $app_config, $selected_btc_primary_currency_value;
   
   return ( $data * $hive_market * $selected_btc_primary_currency_value );
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function get_sub_token_price($chosen_market, $market_pairing) {
   
   global $app_config;
   
     if ( strtolower($chosen_market) == 'eth_subtokens_ico' ) {
     return $app_config['power_user']['ethereum_subtoken_ico_values'][$market_pairing];
     }
    
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function bitcoin_total() {
     
   global $btc_worth_array;
   
       foreach ( $btc_worth_array as $key => $value ) {
       $total_value = ($value + $total_value);
       }
     
   return $total_value;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function coin_stats_data($request) {
   
   global $coin_stats_array;
   
       foreach ( $coin_stats_array as $key => $value ) {
       $results = ($results + $value[$request]);
     }
       
   return $results;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function asset_list_internal_api() {
   
   global $app_config;
   
   $result = array();
   
     foreach ( $app_config['portfolio_assets'] as $key => $unused ) {
       
       if ( strtolower($key) != 'miscassets' ) {
       $result[] = strtolower($key);
       }
       
     }
     
   sort($result);
   return array('asset_list' => $result);
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function conversion_list_internal_api() {
   
   global $app_config;
   
   $result = array();
   
     foreach ( $app_config['power_user']['bitcoin_currency_markets'] as $key => $unused ) {
     $result[] = $key;
     }
     
   sort($result);
   return array('conversion_list' => $result);
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function btc_market($input) {
   
   global $app_config;
   
     $pairing_loop = 0;
     foreach ( $app_config['portfolio_assets']['BTC']['market_pairing'][$app_config['general']['btc_primary_currency_pairing']] as $market_key => $market_id ) {
       
       // If a numeric id, return the exchange name
       if ( is_int($input) && $pairing_loop == $input ) {
       return $market_key;
       }
       // If an exchange name (alphnumeric with possible underscores), return the numeric id (used in UI html forms)
       elseif ( preg_match("/^[A-Za-z0-9_]+$/", $input) && $market_key == $input ) {
       return $pairing_loop + 1;
       }
     $pairing_loop = $pairing_loop + 1;
     }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function exchange_list_internal_api() {
   
   global $app_config;
   
   $result = array();
   
     foreach ( $app_config['portfolio_assets'] as $asset_key => $unused ) {
   
       foreach ( $app_config['portfolio_assets'][$asset_key]['market_pairing'] as $pairing_key => $unused ) {
             
         foreach ( $app_config['portfolio_assets'][$asset_key]['market_pairing'][$pairing_key] as $exchange_key => $unused ) {
             
           if( !in_array(strtolower($exchange_key), $result) && !preg_match("/misc_assets/i", $exchange_key) ) {
           //$all_exchange_count = $all_exchange_count + 1;
           $result[] = strtolower($exchange_key);
           }
         
         }
           
       }
     
     }
   
   sort($result);
   return array('exchange_list' => $result);
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function market_list_internal_api($exchange) {
   
   global $app_config, $remote_ip;
   
   $exchange = strtolower($exchange);
   
   $result = array();
   
     foreach( $app_config['portfolio_assets'] as $asset_key => $asset_value ) {
     
       foreach( $asset_value['market_pairing'] as $market_pairing_key => $market_pairing_value ) {
         
         foreach( $market_pairing_value as $exchange_key => $unused ) {
           
           if ( $exchange_key == $exchange ) {
           $result[] = $exchange_key . '-' . strtolower($asset_key) . '-' . $market_pairing_key;
           }
           
         }
         
       }
     
     }
     
     sort($result);
     
     
     if ( !$exchange ) {
     app_logging('int_api_error', 'From ' . $remote_ip . ' (Missing parameter: exchange)', 'uri: ' . $_SERVER['REQUEST_URI'] . ';');
     return array('error' => 'Missing parameter: [exchange]; ');
     }
     if ( sizeof($result) < 1 ) {
     app_logging('int_api_error', 'From ' . $remote_ip . ' (No markets found for exchange: ' . $exchange . ')', 'uri: ' . $_SERVER['REQUEST_URI'] . ';');
     return array('error' => 'No markets found for exchange: ' . $exchange);
     }
     else {
     
     return array(
             'market_list' => array($exchange => $result)
             );
     
     }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function defi_pools_info($pairing_array, $pool_address=null) {
   
   global $app_config, $pt_cache;
   
   
     if ( $app_config['power_user']['defi_liquidity_pools_sort_by'] == 'volume' ) {
     $sort_by = 'usdVolume';
     }
     elseif ( $app_config['power_user']['defi_liquidity_pools_sort_by'] == 'liquidity' ) {
     $sort_by = 'usdLiquidity';
     }
   
      
      if ( $pool_address ) {
      $json_string = 'https://data-api.defipulse.com/api/v1/blocklytics/pools/v1/exchange/'.$pool_address.'?api-key=' . $app_config['general']['defipulsecom_api_key'];
      }
      else {
      $json_string = 'https://data-api.defipulse.com/api/v1/blocklytics/pools/v1/exchanges?limit=' . $app_config['power_user']['defi_liquidity_pools_max'] . '&orderBy='.$sort_by.'&direction=desc&api-key=' . $app_config['general']['defipulsecom_api_key'];
      }
   
   
   $jsondata = @$pt_cache->ext_apis('url', $json_string, $app_config['power_user']['defi_pools_info_cache_time']); // Re-cache exchanges => addresses data, etc
        
   $data = json_decode($jsondata, true);
   
   
     if ( $pool_address ) {
     $new_data = array($data);
     $data = $new_data;
     }
     else {
     $data = $data['results'];
     }
   
     
         if ( is_array($data) ) {
           
           foreach ($data as $key => $value) {
               
               foreach ( $value['assets'] as $asset ) {
                 
                 // Check for main asset
                 if ( $asset['symbol'] == $pairing_array[0] || preg_match("/([a-z]{1})".$pairing_array[0]."/", $asset['symbol']) ) {
                 $debug_asset = $asset['symbol'];
                 $is_asset = true;
                 }
                 // Check for pairing asset
                 elseif ( $asset['symbol'] == $pairing_array[1] || preg_match("/([a-z]{1})".$pairing_array[1]."/", $asset['symbol']) ) {
                 $debug_pairing = $asset['symbol'];
                 $is_pairing = true;
                 }
                 
                 
                 if ( !$done && $is_asset && $is_pairing ) {
                 
                 $done = true;
                 $result['platform'] = $value['platform'];
                 $result['pool_name'] = $value['poolName'];
                 $result['pool_address'] = $value['exchange'];
                 $result['pool_assets'] = $value['assets'];
                 $result['pool_usd_volume'] = $value['usdVolume'];
                 
                   if ( $result['pool_usd_volume'] < 1 ) {
                   app_logging('market_error', 'No 24 hour trade volume for DeFi liquidity pool at address ' . $result['pool_address'] . ' (' . $pairing_array[0] . '/' . $pairing_array[1] . ')');
                   }
               
                 }
                 
               }
           
            $is_asset = false;
            $is_pairing = false;
           }
         
         }
    
   
    
   return $result;
     
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function marketcap_data($symbol, $force_currency=null) {
     
   global $app_config, $pt_vars, $pt_apis, $alert_percent, $coinmarketcap_currencies, $cap_data_force_usd, $cmc_notes, $coingecko_api, $coinmarketcap_api;
   
   $symbol = strtolower($symbol);
   
   $data = array();
   
   
     if ( $app_config['general']['primary_marketcap_site'] == 'coingecko' ) {
     
       
       // Check for currency support, fallback to USD if needed
       if ( $force_currency != null ) {
         
       $app_notice = 'Forcing '.strtoupper($force_currency).' stats.';
       
       $coingecko_api_no_overwrite = $pt_apis->coingecko($force_currency);
         
         // Overwrite previous app notice and unset force usd flag, if this appears to be a data error rather than an unsupported language
         if ( !isset($coingecko_api_no_overwrite['btc']['market_cap_rank']) ) {
         $app_notice = 'Coingecko.com API data error, check the error logs for more information.';
         }
       
       }
       elseif ( !isset($coingecko_api['btc']['market_cap_rank']) && strtoupper($app_config['general']['btc_primary_currency_pairing']) != 'USD' ) {
         
       $app_notice = 'Coingecko.com does not seem to support '.strtoupper($app_config['general']['btc_primary_currency_pairing']).' stats,<br />showing USD stats instead.';
       
       $cap_data_force_usd = 1;
       
       $coingecko_api = $pt_apis->coingecko('usd');
         
         // Overwrite previous app notice and unset force usd flag, if this appears to be a data error rather than an unsupported language
         if ( !isset($coingecko_api['btc']['market_cap_rank']) ) {
         $cap_data_force_usd = null;
         $app_notice = 'Coingecko.com API data error, check the error logs for more information.';
         }
       
       }
       elseif ( $cap_data_force_usd == 1 ) {
       $app_notice = 'Coingecko.com does not seem to support '.strtoupper($app_config['general']['btc_primary_currency_pairing']).' stats,<br />showing USD stats instead.';
       }
     
     
     // Marketcap data
     $marketcap_data = ( $coingecko_api_no_overwrite ? $coingecko_api_no_overwrite : $coingecko_api );
     
       
     $data['rank'] = $marketcap_data[$symbol]['market_cap_rank'];
     $data['price'] = $marketcap_data[$symbol]['current_price'];
     $data['market_cap'] = round( $pt_vars->rem_num_format($marketcap_data[$symbol]['market_cap']) );
     
       if ( $pt_vars->rem_num_format($marketcap_data[$symbol]['total_supply']) > $pt_vars->rem_num_format($marketcap_data[$symbol]['circulating_supply']) ) {
       $data['market_cap_total'] = round( $pt_vars->rem_num_format($marketcap_data[$symbol]['current_price']) * $pt_vars->rem_num_format($marketcap_data[$symbol]['total_supply']) );
       }
       
     $data['volume_24h'] = $marketcap_data[$symbol]['total_volume'];
     
     $data['percent_change_1h'] = number_format( $marketcap_data[$symbol]['price_change_percentage_1h_in_currency'] , 2, ".", ",");
     $data['percent_change_24h'] = number_format( $marketcap_data[$symbol]['price_change_percentage_24h_in_currency'] , 2, ".", ",");
     $data['percent_change_7d'] = number_format( $marketcap_data[$symbol]['price_change_percentage_7d_in_currency'] , 2, ".", ",");
     
     $data['circulating_supply'] = $marketcap_data[$symbol]['circulating_supply'];
     $data['total_supply'] = $marketcap_data[$symbol]['total_supply'];
     $data['max_supply'] = null;
     
     $data['last_updated'] = strtotime( $marketcap_data[$symbol]['last_updated'] );
     
     $data['app_notice'] = $app_notice;
     
     // Coingecko-only
     $data['percent_change_14d'] = number_format( $marketcap_data[$symbol]['price_change_percentage_14d_in_currency'] , 2, ".", ",");
     $data['percent_change_30d'] = number_format( $marketcap_data[$symbol]['price_change_percentage_30d_in_currency'] , 2, ".", ",");
     $data['percent_change_60d'] = number_format( $marketcap_data[$symbol]['price_change_percentage_60d_in_currency'] , 2, ".", ",");
     $data['percent_change_200d'] = number_format( $marketcap_data[$symbol]['price_change_percentage_200d_in_currency'] , 2, ".", ",");
     $data['percent_change_1y'] = number_format( $marketcap_data[$symbol]['price_change_percentage_1y_in_currency'] , 2, ".", ",");
     
     }
     elseif ( $app_config['general']['primary_marketcap_site'] == 'coinmarketcap' ) {
   
     // Don't overwrite global
     $coinmarketcap_primary_currency = strtoupper($app_config['general']['btc_primary_currency_pairing']);
     
     
       // Default to USD, if selected primary currency is not supported
       if ( $force_currency != null ) {
       $app_notice .= ' Forcing '.strtoupper($force_currency).' stats. ';
       $coinmarketcap_api_no_overwrite = $pt_apis->coinmarketcap($force_currency);
       }
       elseif ( isset($cap_data_force_usd) ) {
       $coinmarketcap_primary_currency = 'USD';
       }
       
       
       if ( isset($cmc_notes) ) {
       $app_notice .= $cmc_notes;
       }
       
     
     // Marketcap data
     $marketcap_data = ( $coinmarketcap_api_no_overwrite ? $coinmarketcap_api_no_overwrite : $coinmarketcap_api );
       
       
     $data['rank'] = $marketcap_data[$symbol]['cmc_rank'];
     $data['price'] = $marketcap_data[$symbol]['quote'][$coinmarketcap_primary_currency]['price'];
     $data['market_cap'] = round( $pt_vars->rem_num_format($marketcap_data[$symbol]['quote'][$coinmarketcap_primary_currency]['market_cap']) );
     
       if ( $pt_vars->rem_num_format($marketcap_data[$symbol]['total_supply']) > $pt_vars->rem_num_format($marketcap_data[$symbol]['circulating_supply']) ) {
       $data['market_cap_total'] = round( $pt_vars->rem_num_format($marketcap_data[$symbol]['quote'][$coinmarketcap_primary_currency]['price']) * $pt_vars->rem_num_format($marketcap_data[$symbol]['total_supply']) );
       }
       
     $data['volume_24h'] = $marketcap_data[$symbol]['quote'][$coinmarketcap_primary_currency]['volume_24h'];
     
     $data['percent_change_1h'] = number_format( $marketcap_data[$symbol]['quote'][$coinmarketcap_primary_currency]['percent_change_1h'] , 2, ".", ",");
     $data['percent_change_24h'] = number_format( $marketcap_data[$symbol]['quote'][$coinmarketcap_primary_currency]['percent_change_24h'] , 2, ".", ",");
     $data['percent_change_7d'] = number_format( $marketcap_data[$symbol]['quote'][$coinmarketcap_primary_currency]['percent_change_7d'] , 2, ".", ",");
     
     $data['circulating_supply'] = $marketcap_data[$symbol]['circulating_supply'];
     $data['total_supply'] = $marketcap_data[$symbol]['total_supply'];
     $data['max_supply'] = $marketcap_data[$symbol]['max_supply'];
     
     $data['last_updated'] = strtotime( $marketcap_data[$symbol]['last_updated'] );
     
     $data['app_notice'] = $app_notice;
     
     }
     
     
     // UX on number values
     $data['price'] = ( $pt_vars->num_to_str($data['price']) >= $app_config['general']['primary_currency_decimals_max_threshold'] ? $pt_vars->num_pretty($data['price'], 2) : $pt_vars->num_pretty($data['price'], $app_config['general']['primary_currency_decimals_max']) );
     
   
   // Return null if we don't even detect a rank
   return ( $data['rank'] != NULL ? $data : NULL );
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function primary_currency_trade_volume($asset_symbol, $pairing, $last_trade, $vol_in_pairing) {
   
   global $app_config, $selected_btc_primary_currency_value;
     
     
     // Return negative number, if no volume data detected (so we know when data errors happen)
     if ( is_numeric($vol_in_pairing) != true ) {
     return -1;
     }
     // If no pairing data, skip calculating trade volume to save on uneeded overhead
     elseif ( !$asset_symbol || !$pairing || !isset($last_trade) || $last_trade == 0 ) {
     return false;
     }
   
   
     // WE NEED TO SET THIS (ONLY IF NOT SET ALREADY) for $pt_apis->market() calls, 
     // because it is not set as a global THE FIRST RUNTIME CALL TO $pt_apis->market()
     if ( strtoupper($asset_symbol) == 'BTC' && !$selected_btc_primary_currency_value ) {
     $temp_btc_primary_currency_value = $last_trade; // Don't overwrite global
     }
     else {
     $temp_btc_primary_currency_value = $selected_btc_primary_currency_value; // Don't overwrite global
     }
   
   
     // Get primary currency volume value	
     // Currency volume from Bitcoin's DEFAULT PAIRING volume
     if ( $pairing == $app_config['general']['btc_primary_currency_pairing'] ) {
     $volume_primary_currency_raw = number_format( $vol_in_pairing , 0, '.', '');
     }
     // Currency volume from btc PAIRING volume
     elseif ( $pairing == 'btc' ) {
     $volume_primary_currency_raw = number_format( $temp_btc_primary_currency_value * $vol_in_pairing , 0, '.', '');
     }
     // Currency volume from other PAIRING volume
     else { 
     
     $pairing_btc_value = pairing_btc_value($pairing);
   
       if ( $pairing_btc_value == null ) {
       app_logging('market_error', 'pairing_btc_value() returned null in primary_currency_trade_volume()', 'pairing: ' . $pairing);
       }
     
     $volume_primary_currency_raw = number_format( $temp_btc_primary_currency_value * ( $vol_in_pairing * $pairing_btc_value ) , 0, '.', '');
     
     }
     
     
   return $volume_primary_currency_raw;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function market_conversion_internal_api($market_conversion, $all_markets_data_array) {
   
   global $app_config, $pt_vars, $pt_apis, $remote_ip, $selected_btc_primary_currency_value;
   
   $result = array();
   
   // Cleanup
   $market_conversion = strtolower($market_conversion);
   $all_markets_data_array = array_map('trim', $all_markets_data_array);
   $all_markets_data_array = array_map('strtolower', $all_markets_data_array);
       
   $possible_dos_attack = 0;
   
   
      // Return error message if there are missing parameters
      if ( $market_conversion != 'market_only' && !$app_config['power_user']['bitcoin_currency_markets'][$market_conversion] || $all_markets_data_array[0] == '' ) {
         
         if ( $market_conversion == '' ) {
         $result['error'] .= 'Missing parameter: [currency_symbol|market_only]; ';
         app_logging('int_api_error', 'From ' . $remote_ip . ' (Missing parameter: currency_symbol|market_only)', 'uri: ' . $_SERVER['REQUEST_URI'] . ';');
         }
         elseif ( $market_conversion != 'market_only' && !$app_config['power_user']['bitcoin_currency_markets'][$market_conversion] ) {
         $result['error'] .= 'Conversion market does not exist: '.$market_conversion.'; ';
         app_logging('int_api_error', 'From ' . $remote_ip . ' (Conversion market does not exist: '.$market_conversion.')', 'uri: ' . $_SERVER['REQUEST_URI'] . ';');
         }
         
         if ( $all_markets_data_array[0] == '' ) {
         $result['error'] .= 'Missing parameter: [exchange-asset-pairing]; ';
         app_logging('int_api_error', 'From ' . $remote_ip . ' (Missing parameter: exchange-asset-pairing)', 'uri: ' . $_SERVER['REQUEST_URI'] . ';');
         }
       
      return $result;
      
      }
      
      
      // Return error message if the markets lists is more markets than allowed by $app_config['developer']['local_api_market_limit']
      if ( sizeof($all_markets_data_array) > $app_config['developer']['local_api_market_limit'] ) {
      $result['error'] = 'Exceeded maximum of ' . $app_config['developer']['local_api_market_limit'] . ' markets allowed per request (' . sizeof($all_markets_data_array) . ').';
      app_logging('int_api_error', 'From ' . $remote_ip . ' (Exceeded maximum markets allowed per request)', 'markets_requested: ' . sizeof($all_markets_data_array) . '; uri: ' . $_SERVER['REQUEST_URI'] . ';');
      return $result;
      }
   
   
       // Loop through each set of market data
       foreach( $all_markets_data_array as $market_data ) {
   
         
           // Stop processing output and return an error message, if this is a possible dos attack
           if ( $possible_dos_attack > 5 ) {
           $result = array(); // reset for no output other than error notice
           $result['error'] = 'Too many non-existent markets requested.';
         app_logging('int_api_error', 'From ' . $remote_ip . ' (Too many non-existent markets requested)', 'uri: ' . $_SERVER['REQUEST_URI'] . ';');
           return $result;
           }
           
       
       
       $market_data_array = explode("-", $market_data); // Market data array
                   
       $exchange = $market_data_array[0];
           
       $asset = $market_data_array[1];
           
       $market_pairing = $market_data_array[2];
           
       $pairing_id = $app_config['portfolio_assets'][strtoupper($asset)]['market_pairing'][$market_pairing][$exchange];
           
       
       
           // If market exists, get latest data
           if ( $pairing_id != '' ) {
           
                 
                 
                 // GET BTC MARKET CONVERSION VALUE #BEFORE ANYTHING ELSE#, OR WE WON'T GET PROPER VOLUME IN CURRENCY ETC
                 // IF NOT SET YET, get bitcoin market data (if we are getting converted fiat currency values)
                 if ( $market_conversion != 'market_only' && !isset($btc_exchange) && !isset($market_conversion_btc_value) ) {
                 
                   
                       // If a preferred bitcoin market is set in app config, use it...otherwise use first array key
                       if ( isset($app_config['power_user']['bitcoin_preferred_currency_markets'][$market_conversion]) ) {
                       $btc_exchange = $app_config['power_user']['bitcoin_preferred_currency_markets'][$market_conversion];
                 }
                 else {
                 $btc_exchange = key($app_config['portfolio_assets']['BTC']['market_pairing'][$market_conversion]);
                 }
                   
                   
                 $btc_pairing_id = $app_config['portfolio_assets']['BTC']['market_pairing'][$market_conversion][$btc_exchange];
                 
                 $market_conversion_btc_value = $pt_apis->market('BTC', $btc_exchange, $btc_pairing_id)['last_trade'];
                 
                       
                       // FAILSAFE: If the exchange market is DOES NOT RETURN a value, 
                       // move the internal array pointer one forward, until we've tried all exchanges for this btc pairing
                       $switch_exchange = true;
                       while ( !isset($market_conversion_btc_value) && $switch_exchange != false || $pt_vars->num_to_str($market_conversion_btc_value) < 0.00000001 && $switch_exchange != false ) {
                         
                       $switch_exchange = next($app_config['portfolio_assets']['BTC']['market_pairing'][$market_conversion]);
                       
                           if ( $switch_exchange != false ) {
                             
                           $btc_exchange = key($app_config['portfolio_assets']['BTC']['market_pairing'][$market_conversion]);
                           
                           $btc_pairing_id = $app_config['portfolio_assets']['BTC']['market_pairing'][$market_conversion][$btc_exchange];
                 
                           $market_conversion_btc_value = $pt_apis->market('BTC', $btc_exchange, $btc_pairing_id)['last_trade'];
                       
                           }
                 
                       }
           
                 
                 // OVERWRITE SELECTED BITCOIN CURRENCY MARKET GLOBALS
                 $app_config['general']['btc_primary_currency_pairing'] = $market_conversion;
               $app_config['general']['btc_primary_exchange'] = $btc_exchange;
                 
                 // OVERWRITE #GLOBAL# BTC PRIMARY CURRENCY VALUE (so we get correct values for volume in currency etc)
                 $selected_btc_primary_currency_value = $market_conversion_btc_value;
                 
                 }
                 
                   
                   
           $asset_market_data = $pt_apis->market(strtoupper($asset), $exchange, $pairing_id, $market_pairing);
           
           $coin_value_raw = $asset_market_data['last_trade'];
           
           // Pretty numbers
           $coin_value_raw = $pt_vars->num_to_str($coin_value_raw);
           
           // If no pair volume is available for this market, emulate it within reason with: asset value * asset volume
           $volume_pairing_raw = $pt_vars->num_to_str($asset_market_data['24hr_pairing_volume']);
           
           
           
                 // More pretty numbers formatting
                 if ( array_key_exists($market_pairing, $app_config['power_user']['bitcoin_currency_markets']) ) {
                 $coin_value_raw = ( $pt_vars->num_to_str($coin_value_raw) >= $app_config['general']['primary_currency_decimals_max_threshold'] ? round($coin_value_raw, 2) : round($coin_value_raw, $app_config['general']['primary_currency_decimals_max']) );
                 $volume_pairing_rounded = round($volume_pairing_raw);
                 }
                 else {
                 $volume_pairing_rounded = round($volume_pairing_raw, 3);
                 }
                 
                 
                 
                 // Get converted fiat currency values if requested
                 if ( $market_conversion != 'market_only' ) {
                 
                     // Value in fiat currency
                       if ( $market_pairing == 'btc' ) {
                       $coin_primary_market_worth_raw = $coin_value_raw * $market_conversion_btc_value;
                       }
                       else {
                       $pairing_btc_value = pairing_btc_value($market_pairing);
                           if ( $pairing_btc_value == null ) {
                           app_logging('market_error', 'pairing_btc_value() returned null in market_conversion_internal_api()', 'pairing: ' . $market_pairing);
                           }
                       $coin_primary_market_worth_raw = ($coin_value_raw * $pairing_btc_value) * $market_conversion_btc_value;
                       }
                 
                 // Pretty numbers for fiat currency
                 $coin_primary_market_worth_raw = ( $pt_vars->num_to_str($coin_primary_market_worth_raw) >= $app_config['general']['primary_currency_decimals_max_threshold'] ? round($coin_primary_market_worth_raw, 2) : round($coin_primary_market_worth_raw, $app_config['general']['primary_currency_decimals_max']) );
                 
                 }
           
           
           
                 // Results
                 if ( $market_conversion != $market_pairing && $market_conversion != 'market_only' ) {
                 
                 // Flag we are doing a price conversion
                 $price_conversion = 1;
                   
                 $result['market_conversion'][$market_data] = array(
                                                                       'market' => array( $market_pairing => array('spot_price' => $coin_value_raw, '24hr_volume' => $volume_pairing_rounded) ),
                                                                       'conversion' => array( $market_conversion => array('spot_price' => $coin_primary_market_worth_raw, '24hr_volume' => round($asset_market_data['24hr_primary_currency_volume']) ) )
                                                                     );
                                                                               
                 }
                 else {
                   
                 $result['market_conversion'][$market_data] = array(
                                                                       'market' => array( $market_pairing => array('spot_price' => $coin_value_raw, '24hr_volume' => $volume_pairing_rounded) )
                                                                     );
                                                       
                 }
           
           
           
           }
           elseif ( sizeof($market_data_array) < 3 ) {
           $result['market_conversion'][$market_data] = array('error' => "Missing all 3 REQUIRED sub-parameters: [exchange-asset-pairing]");
         app_logging('int_api_error', 'From ' . $remote_ip . ' (Missing all 3 REQUIRED sub-parameters: exchange-asset-pairing)', 'uri: ' . $_SERVER['REQUEST_URI'] . ';');
           $possible_dos_attack = $possible_dos_attack + 1;
           }
           elseif ( $pairing_id == '' ) {
           $result['market_conversion'][$market_data] = array('error' => "Market does not exist: [" . $exchange . "-" . $asset . "-" . $market_pairing . "]");
         app_logging('int_api_error', 'From ' . $remote_ip . ' (Market does not exist: ' . $exchange . "-" . $asset . "-" . $market_pairing . ')', 'uri: ' . $_SERVER['REQUEST_URI'] . ';');
           $possible_dos_attack = $possible_dos_attack + 1;
           }
       
       
       }
   
   
   
      // If we did a price conversion, show market used
      if ( $market_conversion != 'market_only' && $price_conversion == 1 ) {
      
      // Reset internal array pointer
       reset($app_config['portfolio_assets']['BTC']['market_pairing'][$market_conversion]);
       
      $result['market_conversion_source'] = $btc_exchange . '-btc-' . $market_conversion;
      
      }
   
   
   
   return $result;
   
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function pairing_btc_value($pairing) {
   
   global $app_config, $pt_vars, $pt_apis, $btc_pairing_markets, $btc_pairing_markets_excluded;
   
   $pairing = strtolower($pairing);
   
   
     // Safeguard / cut down on runtime
     if ( $pairing == null ) {
     return null;
     }
     // If BTC
     elseif ( $pairing == 'btc' ) {
     return 1;
     }
     // If session value exists
     elseif ( isset($btc_pairing_markets[$pairing.'_btc']) ) {
     return $btc_pairing_markets[$pairing.'_btc'];
     }
     // If we need an ALTCOIN/BTC market value (RUN BEFORE CURRENCIES FOR BEST MARKET DATA, AS SOME CRYPTOS ARE INCLUDED IN BOTH)
     elseif ( array_key_exists($pairing, $app_config['power_user']['crypto_pairing']) ) {
       
       
       // Include a basic array check, since we want valid data to avoid an endless loop in our fallback support
       if ( !is_array($app_config['portfolio_assets'][strtoupper($pairing)]['market_pairing']['btc']) ) {
       app_logging('market_error', 'pairing_btc_value() - market failure (unknown pairing) for ' . $pairing);
       return null;
       }
       // Preferred BITCOIN market(s) for getting a certain currency's value, if in config and more than one market exists
       elseif ( sizeof($app_config['portfolio_assets'][strtoupper($pairing)]['market_pairing']['btc']) > 1 && array_key_exists($pairing, $app_config['power_user']['crypto_pairing_preferred_markets']) ) {
       $market_override = $app_config['power_user']['crypto_pairing_preferred_markets'][$pairing];
       }
     
     
       // Loop until we find a market override / non-excluded pairing market
       foreach ( $app_config['portfolio_assets'][strtoupper($pairing)]['market_pairing']['btc'] as $market_key => $market_value ) {
             
             
         if ( isset($market_override) && $market_override == $market_key && !in_array($market_key, $btc_pairing_markets_excluded[$pairing])
         || isset($market_override) && $market_override != $market_key && in_array($market_override, $btc_pairing_markets_excluded[$pairing]) && !in_array($market_key, $btc_pairing_markets_excluded[$pairing])
         || !isset($market_override) && !in_array($market_key, $btc_pairing_markets_excluded[$pairing]) ) {
           
         $btc_pairing_markets[$pairing.'_btc'] = $pt_apis->market(strtoupper($pairing), $market_key, $market_value)['last_trade'];
         
           // Fallback support IF THIS IS A FUTURES MARKET (we want a normal / current value), OR no data returned
           if ( stristr($market_key, 'bitmex_') == false && $pt_vars->num_to_str($btc_pairing_markets[$pairing.'_btc']) >= 0.00000001 ) {
             
             // Data debugging telemetry
             if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'all_telemetry' ) {
             app_logging('market_debugging', 'pairing_btc_value() market request succeeded for ' . $pairing, 'exchange: ' . $market_key);
             }		
               
           return $pt_vars->num_to_str($btc_pairing_markets[$pairing.'_btc']);
           
           }
           // ONLY LOG AN ERROR IF ALL AVAILABLE MARKETS FAIL (AND RETURN NULL)
           // We only want to loop a fallback for the amount of available markets
           elseif ( sizeof($btc_pairing_markets_excluded[$pairing]) == sizeof($app_config['portfolio_assets'][strtoupper($pairing)]['market_pairing']['btc']) ) {
           app_logging('market_error', 'pairing_btc_value() - market request failure (all '.sizeof($btc_pairing_markets_excluded[$pairing]).' markets failed) for ' . $pairing . ' / btc (' . $market_key . ')', $pairing . '_markets_excluded_count: ' . sizeof($btc_pairing_markets_excluded[$pairing]) );
           return null;
           }
           else {
           $btc_pairing_markets[$pairing.'_btc'] = null; // Reset
           $btc_pairing_markets_excluded[$pairing][] = $market_key; // Market exclusion list, getting pairing data from this exchange IN ANY PAIRING, for this runtime only
           return pairing_btc_value($pairing);
           }
         
         }
         
         
       }
       return null; // If we made it this deep in the logic, no data was found	
     
     }
     // If we need a BITCOIN/CURRENCY market value 
     // RUN AFTER CRYPTO MARKETS...WE HAVE A COUPLE CRYPTOS SUPPORTED HERE, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE
     elseif ( array_key_exists($pairing, $app_config['power_user']['bitcoin_currency_markets']) ) {
     
     
       // Include a basic array check, since we want valid data to avoid an endless loop in our fallback support
       if ( !is_array($app_config['portfolio_assets']['BTC']['market_pairing'][$pairing]) ) {
       app_logging('market_error', 'pairing_btc_value() - market failure (unknown pairing) for ' . $pairing);
       return null;
       }
       // Preferred BITCOIN market(s) for getting a certain currency's value, if in config and more than one market exists
       elseif ( sizeof($app_config['portfolio_assets']['BTC']['market_pairing'][$pairing]) > 1 && array_key_exists($pairing, $app_config['power_user']['bitcoin_preferred_currency_markets']) ) {
       $market_override = $app_config['power_user']['bitcoin_preferred_currency_markets'][$pairing];
       }
           
           
       // Loop until we find a market override / non-excluded pairing market
       foreach ( $app_config['portfolio_assets']['BTC']['market_pairing'][$pairing] as $market_key => $market_value ) {
             
             
         if ( isset($market_override) && $market_override == $market_key && !in_array($market_key, $btc_pairing_markets_excluded[$pairing])
         || isset($market_override) && $market_override != $market_key && in_array($market_override, $btc_pairing_markets_excluded[$pairing]) && !in_array($market_key, $btc_pairing_markets_excluded[$pairing])
         || !isset($market_override) && !in_array($market_key, $btc_pairing_markets_excluded[$pairing]) ) {
               
         $btc_pairing_markets[$pairing.'_btc'] = ( 1 / $pt_apis->market(strtoupper($pairing), $market_key, $market_value)['last_trade'] );
               
           // Fallback support IF THIS IS A FUTURES MARKET (we want a normal / current value), OR no data returned
           if ( stristr($market_key, 'bitmex_') == false && $pt_vars->num_to_str($btc_pairing_markets[$pairing.'_btc']) >= 0.0000000000000000000000001 ) { // FUTURE-PROOF FIAT ROUNDING WITH 25 DECIMALS, IN CASE BITCOIN MOONS HARD
                 
             // Data debugging telemetry
             if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'all_telemetry' ) {
             app_logging('market_debugging', 'pairing_btc_value() market request succeeded for ' . $pairing, 'exchange: ' . $market_key);
             }
                 
           return $pt_vars->num_to_str($btc_pairing_markets[$pairing.'_btc']);
               
           }
           // ONLY LOG AN ERROR IF ALL AVAILABLE MARKETS FAIL (AND RETURN NULL)
           // We only want to loop a fallback for the amount of available markets
           elseif ( sizeof($btc_pairing_markets_excluded[$pairing]) >= sizeof($app_config['portfolio_assets']['BTC']['market_pairing'][$pairing]) ) {
           app_logging('market_error', 'pairing_btc_value() - market request failure (all '.sizeof($btc_pairing_markets_excluded[$pairing]).' markets failed) for btc / ' . $pairing . ' (' . $market_key . ')', $pairing . '_markets_excluded_count: ' . sizeof($btc_pairing_markets_excluded[$pairing]) );
           return null;
           }
           else {
           $btc_pairing_markets[$pairing.'_btc'] = null; // Reset	
           $btc_pairing_markets_excluded[$pairing][] = $market_key; // Market exclusion list, getting pairing data from this exchange IN ANY PAIRING, for this runtime only
           return pairing_btc_value($pairing);
           }
         
             
         }
             
               
       }
       return null; // If we made it this deep in the logic, no data was found	
         
       
     }
      else {
      return null; // If we made it this deep in the logic, no data was found
      }
      
      
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function hivepower_time($time) {
       
   global $_POST, $hive_market, $app_config, $selected_btc_primary_currency_value;
   
   $powertime = null;
   $powertime = null;
   $hive_total = null;
   $primary_currency_total = null;
   
   $decimal_yearly_interest = $app_config['power_user']['hivepower_yearly_interest'] / 100;  // Convert APR in config to decimal representation
   
   $speed = ($_POST['hp_total'] * $decimal_yearly_interest) / 525600;  // Interest per minute
   
       if ( $time == 'day' ) {
       $powertime = ($speed * 60 * 24);
       }
       elseif ( $time == 'week' ) {
       $powertime = ($speed * 60 * 24 * 7);
       }
       elseif ( $time == 'month' ) {
       $powertime = ($speed * 60 * 24 * 30);
       }
       elseif ( $time == '2month' ) {
       $powertime = ($speed * 60 * 24 * 60);
       }
       elseif ( $time == '3month' ) {
       $powertime = ($speed * 60 * 24 * 90);
       }
       elseif ( $time == '6month' ) {
       $powertime = ($speed * 60 * 24 * 180);
       }
       elseif ( $time == '9month' ) {
       $powertime = ($speed * 60 * 24 * 270);
       }
       elseif ( $time == '12month' ) {
       $powertime = ($speed * 60 * 24 * 365);
       }
       
       $powertime_primary_currency = ( $powertime * $hive_market * $selected_btc_primary_currency_value );
       
       $hive_total = ( $powertime + $_POST['hp_total'] );
       $primary_currency_total = ( $hive_total * $hive_market * $selected_btc_primary_currency_value );
       
       $power_purchased = ( $_POST['hp_purchased'] / $hive_total );
       $power_earned = ( $_POST['hp_earned'] / $hive_total );
       $power_interest = 1 - ( $power_purchased + $power_earned );
       
       $powerdown_total = ( $hive_total / $app_config['power_user']['hive_powerdown_time'] );
       $powerdown_purchased = ( $powerdown_total * $power_purchased );
       $powerdown_earned = ( $powerdown_total * $power_earned );
       $powerdown_interest = ( $powerdown_total * $power_interest );
       
       ?>
       
   <div class='result'>
       <h2> Interest Per <?=ucfirst($time)?> </h2>
       <ul>
           
           <li><b><?=number_format( $powertime, 3, '.', ',')?> HIVE</b> <i>in interest</i> (after a <?=$time?> time period) = <b><?=$app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']]?><?=number_format( $powertime_primary_currency, 2, '.', ',')?></b></li>
           
           <li><b><?=number_format( $hive_total, 3, '.', ',')?> HIVE</b> <i>in total</i> (including original vested amount) = <b><?=$app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']]?><?=number_format( $primary_currency_total, 2, '.', ',')?></b></li>
       
       </ul>
   
     <p><b>A Power Down Weekly Payout <i>Started At This Time</i> Would Be (rounded to nearest cent):</b></p>
           <table border='1' cellpadding='10' cellspacing='0'>
               <tr>
           <th class='normal'> Purchased </th>
           <th class='normal'> Earned </th>
           <th class='normal'> Interest </th>
           <th> Total </th>
               </tr>
                   <tr>
   
                   <td> <?=number_format( $powerdown_purchased, 3, '.', ',')?> HIVE = <?=$app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']]?><?=number_format( powerdown_primary_currency($powerdown_purchased), 2, '.', ',')?> </td>
                   <td> <?=number_format( $powerdown_earned, 3, '.', ',')?> HIVE = <?=$app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']]?><?=number_format( powerdown_primary_currency($powerdown_earned), 2, '.', ',')?> </td>
                   <td> <?=number_format( $powerdown_interest, 3, '.', ',')?> HIVE = <?=$app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']]?><?=number_format( powerdown_primary_currency($powerdown_interest), 2, '.', ',')?> </td>
                   <td> <b><?=number_format( $powerdown_total, 3, '.', ',')?> HIVE</b> = <b><?=$app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']]?><?=number_format( powerdown_primary_currency($powerdown_total), 2, '.', ',')?></b> </td>
   
                   </tr>
              
           </table>     
           
   </div>
   
       <?php
       
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function mining_calc_form($calculation_form_data, $network_measure, $hash_unit='hash') {
   
   global $_POST, $app_config;
   
   ?>
   
           <form name='<?=$calculation_form_data['symbol']?>' action='<?=start_page('mining')?>' method='post'>
           
           
           <p><b><?=ucfirst($network_measure)?>:</b> 
           <?php
           if ( $hash_unit == 'hash' ) {
           ?>
           
           <input type='text' value='<?=( $_POST['network_measure'] && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? number_format($_POST['network_measure']) : number_format($calculation_form_data['difficulty']) )?>' name='network_measure' /> 
           
           <?php
           }
           ?>
           </p>
           
           
           <p><b>Your Hashrate:</b>  
           <input type='text' value='<?=( $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? $_POST['your_hashrate'] : '' )?>' name='your_hashrate' /> 
           
           
           
           <?php
           if ( $hash_unit == 'hash' ) {
           ?>
           <select class='browser-default custom-select' name='hash_level'>
           <option value='1' <?=( $_POST['hash_level'] == '1' && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? 'selected' : '' )?>> Hs (hashes per second) </option>
           <option value='1000' <?=( $_POST['hash_level'] == '1000' && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? 'selected' : '' )?>> Khs (thousand hashes per second) </option>
           <option value='1000000' <?=( $_POST['hash_level'] == '1000000' && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? 'selected' : '' )?>> Mhs (million hashes per second) </option>
           <option value='1000000000' <?=( $_POST['hash_level'] == '1000000000' && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? 'selected' : '' )?>> Ghs (billion hashes per second) </option>
           <option value='1000000000000' <?=( $_POST['hash_level'] == '1000000000000' && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? 'selected' : '' )?>> Ths (trillion hashes per second) </option>
           <option value='1000000000000000' <?=( $_POST['hash_level'] == '1000000000000000' && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? 'selected' : '' )?>> Phs (quadrillion hashes per second) </option>
           <option value='1000000000000000000' <?=( $_POST['hash_level'] == '1000000000000000000' && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? 'selected' : '' )?>> Ehs (quintillion hashes per second) </option>
           </select>
           
           <?php
           }
           ?>
           
           
           </p>
           
           
           <p><b>Block Reward:</b> <input type='text' value='<?=( $_POST['block_reward'] && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? $_POST['block_reward'] : $calculation_form_data['block_reward'] )?>' name='block_reward' /> (MAY be static from Power User Config, verify manually)</p>
           
           
           <p><b>Watts Used:</b> <input type='text' value='<?=( isset($_POST['watts_used']) && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? $_POST['watts_used'] : '300' )?>' name='watts_used' /></p>
           
           
           <p><b>kWh Rate (<?=$app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']]?>/kWh):</b> <input type='text' value='<?=( isset($_POST['watts_rate']) && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? $_POST['watts_rate'] : '0.1000' )?>' name='watts_rate' /></p>
           
           
           <p><b>Pool Fee:</b> <input type='text' value='<?=( isset($_POST['pool_fee']) && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? $_POST['pool_fee'] : '1' )?>' size='4' name='pool_fee' />%</p>
               
               
            <input type='hidden' value='1' name='<?=$calculation_form_data['symbol']?>_submitted' />
               
            <input type='hidden' value='<?=$calculation_form_data['symbol']?>' name='pow_calc' />
           
           <input type='submit' value='Calculate <?=strtoupper($calculation_form_data['symbol'])?> Mining Profit' />
     
           </form>
           
   
   <?php
     
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function ui_coin_row($asset_name, $asset_symbol, $asset_amount, $all_pairing_markets, $selected_pairing, $selected_exchange, $purchase_price=NULL, $leverage_level, $selected_margintype) {
   
   
   // Globals
   global $_POST, $base_dir, $pt_vars, $pt_apis, $btc_worth_array, $coin_stats_array, $td_color_zebra, $cap_data_force_usd, $theme_selected, $primary_currency_market_standalone, $app_config, $selected_btc_primary_currency_value, $alert_percent, $show_secondary_trade_value, $coingecko_api, $coinmarketcap_api;
   
       
   $original_market = $selected_exchange;
   
     
     // If asset is no longer configured in app config, return false for UX / runtime speed
     if ( !isset($app_config['portfolio_assets'][$asset_symbol]) ) {
     return false;
     }
   
   
     //  For faster runtimes, minimize runtime usage here to held / watched amount is > 0, OR we are setting end-user (interface) preferred Bitcoin market settings
     if ( $pt_vars->num_to_str($asset_amount) > 0.00000000 || strtolower($asset_name) == 'bitcoin' ) {
       
       
         // Update, get the selected market name
         
       $loop = 0;
       foreach ( $all_pairing_markets as $key => $value ) {
          
           if ( $loop == $selected_exchange || $key == "eth_subtokens_ico" ) {
           $selected_exchange = $key;
            
            if ( sizeof($primary_currency_market_standalone) != 2 && strtolower($asset_name) == 'bitcoin' ) {
            $app_config['general']['btc_primary_exchange'] = $key;
            $app_config['general']['btc_primary_currency_pairing'] = $selected_pairing;
            
                   // Dynamically modify MISCASSETS in $app_config['portfolio_assets']
                   // ONLY IF USER HASN'T MESSED UP $app_config['portfolio_assets'], AS WE DON'T WANT TO CANCEL OUT ANY
                   // CONFIG CHECKS CREATING ERROR LOG ENTRIES / UI ALERTS INFORMING THEM OF THAT
                   if ( is_array($app_config['portfolio_assets']) ) {
                   $app_config['portfolio_assets']['MISCASSETS']['asset_name'] = 'Misc. '.strtoupper($selected_pairing).' Value';
                   }
       
            ?>
            
            <script>
            window.btc_primary_currency_value = '<?=$pt_apis->market('BTC', $key, $app_config['portfolio_assets']['BTC']['market_pairing'][$selected_pairing][$key])['last_trade']?>';
            
            window.btc_primary_currency_pairing = '<?=strtoupper($selected_pairing)?>';
            </script>
            
            <?php
            }
            
           }
          
       $loop = $loop + 1;
       }
       $loop = null; 
       
       
     $market_id = $all_pairing_markets[$selected_exchange];
       
       
     // Overwrite PRIMARY CURRENCY CONFIG / BTC market value, in case user changed preferred market IN THE UI
     $selected_btc_pairing_id = $app_config['portfolio_assets']['BTC']['market_pairing'][$app_config['general']['btc_primary_currency_pairing']][$app_config['general']['btc_primary_exchange']];
     $selected_btc_primary_currency_value = $pt_apis->market('BTC', $app_config['general']['btc_primary_exchange'], $selected_btc_pairing_id)['last_trade'];
       
       
       // Log any Bitcoin market errors
       if ( !isset($selected_btc_primary_currency_value) || $selected_btc_primary_currency_value == 0 ) {
       app_logging('market_error', 'pt_assets->ui_coin_row() Bitcoin primary currency value not properly set', 'exchange: ' . $app_config['general']['btc_primary_exchange'] . '; pairing_id: ' . $selected_btc_pairing_id . '; value: ' . $selected_btc_primary_currency_value );
       }
       
   
     }
     
     
   
     // Start rendering table row in the interface, if value set
     if ( $pt_vars->num_to_str($asset_amount) > 0.00000000 ) { // Show even if decimal is off the map, just for UX purposes tracking token price only
   
         
        // For watch-only, we always want only zero to show here in the UI (with no decimals)
        if ( $pt_vars->num_to_str($asset_amount) == 0.000000001 ) {
        $asset_amount = 0;
        }
         
   
     $rand_id = rand(10000000,100000000);
         
     $sort_order = ( array_search($asset_symbol, array_keys($app_config['portfolio_assets'])) + 1);
       
     $all_pairings = $app_config['portfolio_assets'][$asset_symbol]['market_pairing'];
       
   
      // Consolidate function calls for runtime speed improvement
      // (called here so first runtime with NO SELECTED ASSETS RUNS SIGNIFICANTLY QUICKER)
      if ( $app_config['general']['primary_marketcap_site'] == 'coingecko' && sizeof($coingecko_api) < 1 ) {
      $coingecko_api = $pt_apis->coingecko();
      }
      elseif ( $app_config['general']['primary_marketcap_site'] == 'coinmarketcap' && sizeof($coinmarketcap_api) < 1 ) {
      $coinmarketcap_api = $pt_apis->coinmarketcap();
      }
     
       
       // UI table coloring
       if ( !$td_color_zebra || $td_color_zebra == '#d6d4d4' ) {
       $td_color_zebra = 'white';
       }
       else {
       $td_color_zebra = '#d6d4d4';
       }
     
     
     // Get coin values, including non-BTC pairings
       
     // Consolidate function calls for runtime speed improvement
     $asset_market_data = $pt_apis->market($asset_symbol, $selected_exchange, $market_id, $selected_pairing);
   
   
       // ETH ICOS (OVERWRITE W/ DIFF LOGIC)
       if ( $selected_exchange == 'eth_subtokens_ico' ) {
       $coin_value_raw = get_sub_token_price($selected_exchange, $market_id);
       }
       else {
        $coin_value_raw = $asset_market_data['last_trade'];
       }
   
   
     $coin_value_total_raw = $pt_vars->num_to_str($asset_amount * $coin_value_raw);
     
     // SUPPORTED even for BTC ( pairing_btc_value('btc') ALWAYS = 1 ), 
     // since we use this var for secondary trade / holdings values logic further down
     $pairing_btc_value = pairing_btc_value($selected_pairing); 
        
        
      if ( $pairing_btc_value == null ) {
      app_logging('market_error', 'pairing_btc_value(\''.$selected_pairing.'\') returned null in pt_assets->ui_coin_row(), likely from exchange API request failure');
      }
     
     
     $coin_primary_currency_worth_raw = ($coin_value_total_raw * $pairing_btc_value) * $selected_btc_primary_currency_value;
   
   
       // BITCOIN (OVERWRITE W/ DIFF LOGIC)
       if ( strtolower($asset_name) == 'bitcoin' ) {
       $btc_trade_eqiv_raw = 1;
       $btc_worth_array[$asset_symbol] = $asset_amount;
        }
        else {
        $btc_trade_eqiv_raw = number_format( ($coin_value_raw * $pairing_btc_value) , 8, '.', '');
        $btc_worth_array[$asset_symbol] = $pt_vars->num_to_str($coin_value_total_raw * $pairing_btc_value);
        }
        
        
       // FLAG SELECTED PAIRING IF FIAT EQUIVALENT formatting should be used, AS SUCH
       // #FOR CLEAN CODE#, RUN CHECK TO MAKE SURE IT'S NOT A CRYPTO AS WELL...WE HAVE A COUPLE SUPPORTED, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE
       if ( array_key_exists($selected_pairing, $app_config['power_user']['bitcoin_currency_markets']) && !array_key_exists($selected_pairing, $app_config['power_user']['crypto_pairing']) ) {
      $fiat_eqiv = 1;
       }
      
      
        // Calculate gain / loss if purchase price was populated, AND asset held is at least 1 satoshi
      if ( $pt_vars->num_to_str($purchase_price) >= 0.00000001 && $pt_vars->num_to_str($asset_amount) >= 0.00000001 ) {
       
      $coin_paid_total_raw = ($asset_amount * $purchase_price);
      
      $gain_loss = $coin_primary_currency_worth_raw - $coin_paid_total_raw;
        
        
       // Convert $gain_loss for shorts with leverage
       if ( $leverage_level >= 2 && $selected_margintype == 'short' ) {
         
       $prev_gain_loss_val = $gain_loss;
         
         if ( $prev_gain_loss_val >= 0 ) {
         $gain_loss = $prev_gain_loss_val - ( $prev_gain_loss_val * 2 );
         $coin_primary_currency_worth_raw = $coin_primary_currency_worth_raw - ( $prev_gain_loss_val * 2 );
         }
         else {
         $gain_loss = $prev_gain_loss_val + ( abs($prev_gain_loss_val) * 2 );
         $coin_primary_currency_worth_raw = $coin_primary_currency_worth_raw + ( abs($prev_gain_loss_val) * 2 );
         }
   
       }
      
      
      // Gain / loss percent (!MUST NOT BE! absolute value)
      $gain_loss_percent = ($coin_primary_currency_worth_raw - $coin_paid_total_raw) / abs($coin_paid_total_raw) * 100;
      
      // Check for any leverage gain / loss
      $only_leverage_gain_loss = ( $leverage_level >= 2 ? ($gain_loss * ($leverage_level - 1) ) : 0 );
      
      $inc_leverage_gain_loss = ( $leverage_level >= 2 ? ($gain_loss * $leverage_level) : $gain_loss );
      
      $inc_leverage_gain_loss_percent =  ( $leverage_level >= 2 ? ($gain_loss_percent * $leverage_level) : $gain_loss_percent );
      
       
      }
      else {
      $no_purchase_price = 1;
      $purchase_price = null;
      $coin_paid_total_raw = null;
      }
       
      
      
       $coin_stats_array[] = array(
                                 'coin_symbol' => $asset_symbol, 
                                 'coin_leverage' => $leverage_level,
                                 'selected_margintype' => $selected_margintype,
                                 'coin_worth_total' => $coin_primary_currency_worth_raw,
                                 'coin_total_worth_if_purchase_price' => ($no_purchase_price == 1 ? null : $coin_primary_currency_worth_raw),
                                 'coin_paid' => $purchase_price,
                                 'coin_paid_total' => $coin_paid_total_raw,
                                 'gain_loss_only_leverage' => $only_leverage_gain_loss,
                                 'gain_loss_total' => $inc_leverage_gain_loss,
                                 'gain_loss_percent_total' => $inc_leverage_gain_loss_percent,
                                 );
                           
   
   
   
     // Get trade volume
     $trade_volume = $asset_market_data['24hr_primary_currency_volume'];
     
     
     // Rendering webpage UI output
     // DON'T USE require_once(), as we are looping here!
     require($base_dir . '/templates/interface/rendering/desktop/php/user/user-elements/portfolio-asset-row.php');
     
     
     }
   
   
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function charts_price_alerts($asset_data, $exchange, $pairing, $mode) {
   
   // Globals
   global $base_dir, $pt_vars, $pt_apis, $app_config, $default_btc_primary_exchange, $default_btc_primary_currency_value, $default_btc_primary_currency_pairing, $price_alerts_fixed_reset_array;
   
     
     // Return true (no errors) if alert-only, and alerts are disabled
     if ( $mode == 'alert' && $app_config['comms']['price_alerts_threshold'] == 0 ) {
     return true;
     }
   
   $pairing = strtolower($pairing);
   
   /////////////////////////////////////////////////////////////////
   // Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
   $asset = ( stristr($asset_data, "-") == false ? $asset_data : substr( $asset_data, 0, mb_strpos($asset_data, "-", 0, 'utf-8') ) );
   $asset = strtoupper($asset);
   
   
     // Fiat or equivalent pairing?
     // #FOR CLEAN CODE#, RUN CHECK TO MAKE SURE IT'S NOT A CRYPTO AS WELL...WE HAVE A COUPLE SUPPORTED, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE
     if ( array_key_exists($pairing, $app_config['power_user']['bitcoin_currency_markets']) && !array_key_exists($pairing, $app_config['power_user']['crypto_pairing']) ) {
     $fiat_eqiv = 1;
     }
   /////////////////////////////////////////////////////////////////
   
   
   
   // Get any necessary variables for calculating asset's PRIMARY CURRENCY CONFIG value
   
   // Consolidate function calls for runtime speed improvement
   $asset_market_data = $pt_apis->market($asset, $exchange, $app_config['portfolio_assets'][$asset]['market_pairing'][$pairing][$exchange], $pairing);
      
      
     // Get asset PRIMARY CURRENCY CONFIG value
     /////////////////////////////////////////////////////////////////
     // PRIMARY CURRENCY CONFIG CHARTS
     if ( $pairing == $default_btc_primary_currency_pairing ) {
     $asset_primary_currency_value_raw = $asset_market_data['last_trade']; 
     }
     // BTC PAIRINGS CONVERTED TO PRIMARY CURRENCY CONFIG (EQUIV) CHARTS
     elseif ( $pairing == 'btc' ) {
     $asset_primary_currency_value_raw = number_format( $default_btc_primary_currency_value * $asset_market_data['last_trade'] , 8, '.', '');
     }
     // OTHER PAIRINGS CONVERTED TO PRIMARY CURRENCY CONFIG (EQUIV) CHARTS
     else {
       
     $pairing_btc_value = pairing_btc_value($pairing); 
     
       if ( $pairing_btc_value == null ) {
       app_logging('market_error', 'pairing_btc_value() returned null in pt_assets->charts_price_alerts()', 'pairing: ' . $pairing);
       }
     
     $asset_primary_currency_value_raw = number_format( $default_btc_primary_currency_value * ( $asset_market_data['last_trade'] * $pairing_btc_value ) , 8, '.', '');
     
     }
     /////////////////////////////////////////////////////////////////
     
     
       
   /////////////////////////////////////////////////////////////////
   $volume_pairing_raw = $pt_vars->num_to_str($asset_market_data['24hr_pairing_volume']); // If available, we'll use this for chart volume UX
   $volume_primary_currency_raw = $asset_market_data['24hr_primary_currency_volume'];
       
   $asset_pairing_value_raw = number_format( $asset_market_data['last_trade'] , 8, '.', '');
   /////////////////////////////////////////////////////////////////
     
     
     
     /////////////////////////////////////////////////////////////////
     // Make sure we have basic values, otherwise log errors / return false
     // Return false if we have no $default_btc_primary_currency_value
     if ( !isset($default_btc_primary_currency_value) || $default_btc_primary_currency_value == 0 ) {
     app_logging('market_error', 'pt_assets->charts_price_alerts() - No Bitcoin '.strtoupper($default_btc_primary_currency_pairing).' value ('.strtoupper($pairing).' pairing) for "' . $asset_data . '"', $asset_data . ': ' . $asset . ' / ' . strtoupper($pairing) . ' @ ' . $exchange . ';' );
     $set_return = 1;
     }
     
     
     // Return false if we have no asset value
     if ( $pt_vars->num_to_str( trim($asset_primary_currency_value_raw) ) >= 0.00000001 ) {
     // Continue
     }
     else {
     app_logging('market_error', 'pt_assets->charts_price_alerts() - No '.strtoupper($default_btc_primary_currency_pairing).' conversion value ('.strtoupper($pairing).' pairing) for "' . $asset_data . '"', $asset_data . ': ' . $asset . ' / ' . strtoupper($pairing) . ' @ ' . $exchange . '; pairing_id: ' . $app_config['portfolio_assets'][$asset]['market_pairing'][$pairing][$exchange] . ';' );
     $set_return = 1;
     }
     
     
     if ( $set_return == 1 ) {
     return false;
     }
     /////////////////////////////////////////////////////////////////
     
     
     
   // Optimizing storage size needed for charts data
   /////////////////////////////////////////////////////////////////
   // Round PRIMARY CURRENCY CONFIG volume to nullify insignificant decimal amounts / for prettier numbers UX, and to save on data set / storage size
   $volume_primary_currency_raw = ( isset($volume_primary_currency_raw) ? round($volume_primary_currency_raw) : null );		
     
   // Round PAIRING volume to only keep $app_config['power_user']['charts_crypto_volume_decimals'] decimals max (for crypto volume etc), to save on data set / storage size
   $volume_pairing_raw = ( isset($volume_pairing_raw) ? round($volume_pairing_raw, ( $fiat_eqiv == 1 ? 0 : $app_config['power_user']['charts_crypto_volume_decimals'] ) ) : null );	
     
     
   // Round PRIMARY CURRENCY CONFIG asset price to only keep $app_config['general']['primary_currency_decimals_max'] decimals maximum 
   // (or only 2 decimals if worth $app_config['general']['primary_currency_decimals_max_threshold'] or more), to save on data set / storage size
   $asset_primary_currency_value_raw = ( $pt_vars->num_to_str($asset_primary_currency_value_raw) >= $app_config['general']['primary_currency_decimals_max_threshold'] ? round($asset_primary_currency_value_raw, 2) : round($asset_primary_currency_value_raw, $app_config['general']['primary_currency_decimals_max']) );
     
     
     // If fiat equivalent format, round asset price 
     // to only keep $app_config['general']['primary_currency_decimals_max'] decimals maximum 
     // (or only 2 decimals if worth $app_config['general']['primary_currency_decimals_max_threshold'] or more), to save on data set / storage size
      if ( $fiat_eqiv == 1 ) {
      $asset_pairing_value_raw = ( $pt_vars->num_to_str($asset_pairing_value_raw) >= $app_config['general']['primary_currency_decimals_max_threshold'] ? round($asset_pairing_value_raw, 2) : round($asset_pairing_value_raw, $app_config['general']['primary_currency_decimals_max']) );
      }
   
   
   // Remove any leading / trailing zeros from CRYPTO asset price, to save on data set / storage size
   $asset_pairing_value_raw = $pt_vars->num_to_str($asset_pairing_value_raw);
   
   // Remove any leading / trailing zeros from PAIRING VOLUME, to save on data set / storage size
   $volume_pairing_raw = $pt_vars->num_to_str($volume_pairing_raw);
   /////////////////////////////////////////////////////////////////
   
     
   
     // Charts (WE DON'T WANT TO STORE DATA WITH A CORRUPT TIMESTAMP)
     /////////////////////////////////////////////////////////////////
     // If the charts page is enabled in Admin Config, save latest chart data for assets with price alerts configured on them
     if ( $mode == 'both' && $pt_vars->num_to_str($asset_primary_currency_value_raw) >= 0.00000001 && $app_config['general']['asset_charts_toggle'] == 'on'
     || $mode == 'chart' && $pt_vars->num_to_str($asset_primary_currency_value_raw) >= 0.00000001 && $app_config['general']['asset_charts_toggle'] == 'on' ) {
     
     // In case a rare error occured from power outage / corrupt memory / etc, we'll check the timestamp (in a non-resource-intensive way)
     // (#SEEMED# TO BE A REAL ISSUE ON A RASPI ZERO AFTER MULTIPLE POWER OUTAGES [ONE TIMESTAMP HAD PREPENDED CORRUPT DATA])
     $now = time();
     
       if ( $now > 0 ) {
       // Continue
       }
       else {
       // Return
       app_logging('system_error', 'time() returned a corrupt value (from power outage / corrupt memory / etc), chart updating canceled', 'chart_type: asset market');
       return false;
       }
       
     // PRIMARY CURRENCY CONFIG ARCHIVAL charts (CRYPTO/PRIMARY CURRENCY CONFIG markets, 
     // AND ALSO crypto-to-crypto pairings converted to PRIMARY CURRENCY CONFIG equiv value for PRIMARY CURRENCY CONFIG equiv charts)
     
     $primary_currency_chart_path = $base_dir . '/cache/charts/spot_price_24hr_volume/archival/'.$asset.'/'.$asset_data.'_chart_'.strtolower($default_btc_primary_currency_pairing).'.dat';
     $primary_currency_chart_data = $now . '||' . $asset_primary_currency_value_raw . '||' . $volume_primary_currency_raw;
     store_file_contents($primary_currency_chart_path, $primary_currency_chart_data . "\n", "append", false);  // WITH newline (UNLOCKED file write)
       
       
       // Crypto / secondary currency pairing ARCHIVAL charts, volume as pairing (for UX)
       if ( $pairing != strtolower($default_btc_primary_currency_pairing) ) {
       $crypto_secondary_currency_chart_path = $base_dir . '/cache/charts/spot_price_24hr_volume/archival/'.$asset.'/'.$asset_data.'_chart_'.$pairing.'.dat';
       $crypto_secondary_currency_chart_data = $now . '||' . $asset_pairing_value_raw . '||' . $volume_pairing_raw;
       store_file_contents($crypto_secondary_currency_chart_path, $crypto_secondary_currency_chart_data . "\n", "append", false); // WITH newline (UNLOCKED file write)
       }
       
       
       // Lite charts (update time dynamically determined in update_lite_chart() logic)
       // Wait 0.05 seconds before updating lite charts (which reads archival data)
       usleep(50000); // Wait 0.05 seconds
       
       foreach ( $app_config['power_user']['lite_chart_day_intervals'] as $light_chart_days ) {
         
       // Primary currency lite charts
       update_lite_chart($primary_currency_chart_path, $primary_currency_chart_data, $light_chart_days); // WITHOUT newline (var passing)
           
         // Crypto / secondary currency pairing lite charts
         if ( $pairing != strtolower($default_btc_primary_currency_pairing) ) {
         update_lite_chart($crypto_secondary_currency_chart_path, $crypto_secondary_currency_chart_data, $light_chart_days); // WITHOUT newline (var passing)
         }
       
       }
       
       
     }
     /////////////////////////////////////////////////////////////////
     
     
     
     
     // Alert checking START
     /////////////////////////////////////////////////////////////////
     if ( $mode == 'alert' && $app_config['comms']['price_alerts_threshold'] > 0 || $mode == 'both' && $app_config['comms']['price_alerts_threshold'] > 0 ) {
   
           
      // WE USE PAIRING VOLUME FOR VOLUME PERCENTAGE CHANGES, FOR BETTER PERCENT CHANGE ACCURACY THAN FIAT EQUIV
      $alert_cache_contents = $asset_primary_currency_value_raw . '||' . $volume_primary_currency_raw . '||' . $volume_pairing_raw;
       
     // Grab any cached price alert data
      $data_file = trim( file_get_contents('cache/alerts/'.$asset_data.'.dat') );
       
      $cached_array = explode("||", $data_file);
      
       
         // Make sure numbers are cleanly pulled from cache file
         foreach ( $cached_array as $key => $value ) {
         $cached_array[$key] = $pt_vars->rem_num_format($value);
         }
       
       
         // Backwards compatibility
         if ( $cached_array[0] == null ) {
         $cached_asset_primary_currency_value = $data_file;
         $cached_primary_currency_volume = -1;
         $cached_pairing_volume = -1;
         }
         else {
         $cached_asset_primary_currency_value = $cached_array[0];  // PRIMARY CURRENCY CONFIG token value
         $cached_primary_currency_volume = round($cached_array[1]); // PRIMARY CURRENCY CONFIG volume value (round PRIMARY CURRENCY CONFIG volume to nullify insignificant decimal amounts skewing checks)
         $cached_pairing_volume = $cached_array[2]; // Crypto volume value (more accurate percent increase / decrease stats than PRIMARY CURRENCY CONFIG value fluctuations)
         }
       
       
       
         // Price checks (done early for including with price alert reset logic)
         // If cached and current price exist
         if ( $pt_vars->num_to_str( trim($cached_asset_primary_currency_value) ) >= 0.00000001 && $pt_vars->num_to_str( trim($asset_primary_currency_value_raw) ) >= 0.00000001 ) {
         
         
         // PRIMARY CURRENCY CONFIG price percent change (!MUST BE! absolute value)
         $percent_change = abs( ($asset_primary_currency_value_raw - $cached_asset_primary_currency_value) / abs($cached_asset_primary_currency_value) * 100 );
         $percent_change = $pt_vars->num_to_str($percent_change); // Better decimal support
                 
                       
       // Pretty exchange name / percent change for UI / UX (defined early for any price alert reset logic)
         $percent_change_text = number_format($percent_change, 2, '.', ',');
       $exchange_text = snake_case_to_name($exchange);
       
                 
           // UX / UI variables
           if ( $pt_vars->num_to_str($asset_primary_currency_value_raw) < $pt_vars->num_to_str($cached_asset_primary_currency_value) ) {
           $change_symbol = '-';
           $increase_decrease = 'decreased';
           }
           elseif ( $pt_vars->num_to_str($asset_primary_currency_value_raw) >= $pt_vars->num_to_str($cached_asset_primary_currency_value) ) {
           $change_symbol = '+';
           $increase_decrease = 'increased';
           }
                 
         
           // INITIAL check whether we should send an alert (we ALSO check for a few different conditions further down, and UPDATE THIS VAR AS NEEDED THEN)
           if ( $percent_change >= $app_config['comms']['price_alerts_threshold'] ) {
           $send_alert = 1;
           }
                 
                 
         }
                 
       
       
         ////// If flagged to run alerts //////////// 
         if ( $send_alert == 1 ) {
           
       
         // Check for a file modified time !!!BEFORE ANY!!! file creation / updating happens (to calculate time elapsed between updates)
           
         $last_cached_days = ( time() - filemtime('cache/alerts/'.$asset_data.'.dat') ) / 86400;
         $last_cached_days = $pt_vars->num_to_str($last_cached_days); // Better decimal support for whale alerts etc
          
          
                 if ( $last_cached_days >= 365 ) {
                 $last_cached_time = number_format( ($last_cached_days / 365) , 2, '.', ',') . ' years';
                 }
                 elseif ( $last_cached_days >= 30 ) {
                 $last_cached_time = number_format( ($last_cached_days / 30) , 2, '.', ',') . ' months';
                 }
                 elseif ( $last_cached_days >= 7 ) {
                 $last_cached_time = number_format( ($last_cached_days / 7) , 2, '.', ',') . ' weeks';
                 }
                 else {
                 $last_cached_time = number_format($last_cached_days, 2, '.', ',') . ' days';
                 }
          
           
                  
         // Crypto volume checks
                 
         // Crypto volume percent change (!MUST BE! absolute value)
         $volume_percent_change = abs( ($volume_pairing_raw - $cached_pairing_volume) / abs($cached_pairing_volume) * 100 );        
         $volume_percent_change = $pt_vars->num_to_str($volume_percent_change); // Better decimal support
         
                 
                 
                 // UX adjustments, and UI / UX variables
                 if ( $cached_primary_currency_volume <= 0 && $volume_primary_currency_raw <= 0 ) { // ONLY PRIMARY CURRENCY CONFIG VOLUME CALCULATION RETURNS -1 ON EXCHANGE VOLUME ERROR
                 $volume_percent_change = 0; // Skip calculating percent change if cached / live PRIMARY CURRENCY CONFIG volume are both zero or -1 (exchange API error)
                 $volume_change_symbol = '+';
                 }
                 elseif ( $cached_primary_currency_volume <= 0 && $volume_pairing_raw >= $cached_pairing_volume ) { // ONLY PRIMARY CURRENCY CONFIG VOLUME CALCULATION RETURNS -1 ON EXCHANGE VOLUME ERROR
                 $volume_percent_change = $volume_primary_currency_raw; // Use PRIMARY CURRENCY CONFIG volume value for percent up, for UX sake, if volume is up from zero or -1 (exchange API error)
                 $volume_change_symbol = '+';
                 }
                 elseif ( $cached_primary_currency_volume > 0 && $volume_pairing_raw < $cached_pairing_volume ) {
                 $volume_change_symbol = '-';
                 }
                 elseif ( $cached_primary_currency_volume > 0 && $volume_pairing_raw > $cached_pairing_volume ) {
                 $volume_change_symbol = '+';
                 }
                 
                 
                 
                 // Whale alert (price change average of X or greater over X day(s) or less, with X percent pair volume increase average that is at least a X primary currency volume increase average)
                 $whale_alert_threshold = explode("||", $app_config['charts_alerts']['price_alerts_whale_alert_threshold']);
       
                 if ( trim($whale_alert_threshold[0]) != '' && trim($whale_alert_threshold[1]) != '' && trim($whale_alert_threshold[2]) != '' && trim($whale_alert_threshold[3]) != '' ) {
                 
                 $whale_max_days_to_24hr_average_over = $pt_vars->num_to_str( trim($whale_alert_threshold[0]) );
                 
                 $whale_min_price_percent_change_24hr_average = $pt_vars->num_to_str( trim($whale_alert_threshold[1]) );
                 
                 $whale_min_volume_percent_increase_24hr_average = $pt_vars->num_to_str( trim($whale_alert_threshold[2]) );
                 
                 $whale_min_volume_currency_increase_24hr_average = $pt_vars->num_to_str( trim($whale_alert_threshold[3]) );
                 
                 
                   // WE ONLY WANT PRICE CHANGE PERCENT AS AN ABSOLUTE VALUE HERE, ALL OTHER VALUES SHOULD BE ALLOWED TO BE NEGATIVE IF THEY ARE NEGATIVE
                   if ( $last_cached_days <= $whale_max_days_to_24hr_average_over 
                   && $pt_vars->num_to_str($percent_change / $last_cached_days) >= $whale_min_price_percent_change_24hr_average 
                   && $pt_vars->num_to_str($volume_change_symbol . $volume_percent_change / $last_cached_days) >= $whale_min_volume_percent_increase_24hr_average 
                   && $pt_vars->num_to_str( ($volume_primary_currency_raw - $cached_primary_currency_volume) / $last_cached_days ) >= $whale_min_volume_currency_increase_24hr_average ) {
                   $whale_alert = 1;
                   }
                   
                
                 }
                
                
                 
                 // We disallow alerts where minimum 24 hour trade PRIMARY CURRENCY CONFIG volume has NOT been met, ONLY if an API request doesn't fail to retrieve volume data
                 if ( $volume_primary_currency_raw >= 0 && $volume_primary_currency_raw < $app_config['comms']['price_alerts_min_volume'] ) {
                 $send_alert = null;
                 }
         
         
         
         
                 // We disallow alerts if they are not activated
                 if ( $mode != 'both' && $mode != 'alert' ) {
                 $send_alert = null;
                 }
         
         
                 // We disallow alerts if $app_config['comms']['price_alerts_block_volume_error'] is on, and there is a volume retrieval error
                 // ONLY PRIMARY CURRENCY CONFIG VOLUME CALCULATION RETURNS -1 ON EXCHANGE VOLUME ERROR
                 if ( $volume_primary_currency_raw == -1 && $app_config['comms']['price_alerts_block_volume_error'] == 'on' ) {
                 $send_alert = null;
                 }
                 
                 
                 
                 
                 
                 // Sending the alerts
                 if ( update_cache_file('cache/alerts/'.$asset_data.'.dat', ( $app_config['comms']['price_alerts_freq_max'] * 60 ) ) == true && $send_alert == 1 ) {
                 
                               
                 // Message formatting for display to end user
                   
                 $desc_alert_type = ( $app_config['charts_alerts']['price_alerts_fixed_reset'] > 0 ? 'reset' : 'alert' );
                 
                   
                   // IF PRIMARY CURRENCY CONFIG volume was between 0 and 1 last alert / reset, for UX sake 
                   // we use current PRIMARY CURRENCY CONFIG volume instead of pair volume (for percent up, so it's not up 70,000% for altcoins lol)
                   if ( $cached_primary_currency_volume >= 0 && $cached_primary_currency_volume <= 1 ) {
                   $volume_describe = strtoupper($default_btc_primary_currency_pairing) . ' volume was ' . $app_config['power_user']['bitcoin_currency_markets'][$default_btc_primary_currency_pairing] . $cached_primary_currency_volume . ' last price ' . $desc_alert_type . ', and ';
                   $volume_describe_mobile = strtoupper($default_btc_primary_currency_pairing) . ' volume up from ' . $app_config['power_user']['bitcoin_currency_markets'][$default_btc_primary_currency_pairing] . $cached_primary_currency_volume . ' last ' . $desc_alert_type;
                   }
                   // Best we can do feasibly for UX on volume reporting errors
                   elseif ( $cached_primary_currency_volume == -1 ) { // ONLY PRIMARY CURRENCY CONFIG VOLUME CALCULATION RETURNS -1 ON EXCHANGE VOLUME ERROR
                   $volume_describe = strtoupper($default_btc_primary_currency_pairing) . ' volume was NULL last price ' . $desc_alert_type . ', and ';
                   $volume_describe_mobile = strtoupper($default_btc_primary_currency_pairing) . ' volume up from NULL last ' . $desc_alert_type;
                   }
                   else {
                   $volume_describe = 'pair volume ';
                   $volume_describe_mobile = 'pair volume'; // no space
                   }
                 
                 
                 
                 
                 // Pretty up textual output to end-user (convert raw numbers to have separators, remove underscores in names, etc)
                   
                       
                 // Pretty numbers UX on PRIMARY CURRENCY CONFIG asset value
                 $asset_primary_currency_text = ( $pt_vars->num_to_str($asset_primary_currency_value_raw) >= $app_config['general']['primary_currency_decimals_max_threshold'] ? $pt_vars->num_pretty($asset_primary_currency_value_raw, 2) : $pt_vars->num_pretty($asset_primary_currency_value_raw, $app_config['general']['primary_currency_decimals_max']) );
                       
                 $volume_primary_currency_text = $app_config['power_user']['bitcoin_currency_markets'][$default_btc_primary_currency_pairing] . number_format($volume_primary_currency_raw, 0, '.', ',');
                       
                 $volume_change_text = 'has ' . ( $volume_change_symbol == '+' ? 'increased ' : 'decreased ' ) . $volume_change_symbol . number_format($volume_percent_change, 2, '.', ',') . '% to a ' . strtoupper($default_btc_primary_currency_pairing) . ' value of';
                       
                 $volume_change_text_mobile = '(' . $volume_change_symbol . number_format($volume_percent_change, 2, '.', ',') . '% ' . $volume_describe_mobile . ')';
                       
                       
                       
                       
                   // If -1 from exchange API error not reporting any volume data (not even zero)
                   // ONLY PRIMARY CURRENCY CONFIG VOLUME CALCULATION RETURNS -1 ON EXCHANGE VOLUME ERROR
                   if ( $cached_primary_currency_volume == -1 || $volume_primary_currency_raw == -1 ) {
                   $volume_change_text = null;
                   $volume_change_text_mobile = null;
                   }
                   
                   
                   
                   // Format trade volume data
                   
                   // Volume filter skipped message, only if filter is on and error getting trade volume data (otherwise is NULL)
                   if ( $volume_primary_currency_raw == null && $app_config['comms']['price_alerts_min_volume'] > 0 || $volume_primary_currency_raw < 1 && $app_config['comms']['price_alerts_min_volume'] > 0 ) {
                   $volume_filter_skipped_text = ', so volume filter was skipped';
                   }
                   else {
                   $volume_filter_skipped_text = null;
                   }
                   
                   
                   
                   // Successfully received > 0 volume data, at or above an enabled volume filter
                       if ( $volume_primary_currency_raw > 0 && $app_config['comms']['price_alerts_min_volume'] > 0 && $volume_primary_currency_raw >= $app_config['comms']['price_alerts_min_volume'] ) {
                   $email_volume_summary = '24 hour ' . $volume_describe . $volume_change_text . ' ' . $volume_primary_currency_text . ' (volume filter on).';
                   }
                   // NULL if not setup to get volume, negative number returned if no data received from API, therefore skipping any enabled volume filter
                   // ONLY PRIMARY CURRENCY CONFIG VOLUME CALCULATION RETURNS -1 ON EXCHANGE VOLUME ERROR
                       elseif ( $volume_primary_currency_raw == -1 ) { 
                   $email_volume_summary = 'No data received for 24 hour volume' . $volume_filter_skipped_text . '.';
                   $volume_primary_currency_text = 'No data';
                   }
                   // If volume is zero or greater in successfully received volume data, without an enabled volume filter (or filter skipped)
                   // IF exchange PRIMARY CURRENCY CONFIG value price goes up/down and triggers alert, 
                   // BUT current reported volume is zero (temporary error on exchange side etc, NOT on our app's side),
                   // inform end-user of this probable volume discrepancy being detected.
                   elseif ( $volume_primary_currency_raw >= 0 ) {
                   $email_volume_summary = '24 hour ' . $volume_describe . $volume_change_text . ' ' . $volume_primary_currency_text . ( $volume_primary_currency_raw == 0 ? ' (probable volume discrepancy detected' . $volume_filter_skipped_text . ')' : '' ) . '.'; 
                   }
                       
                       
                       
                       
                 // Build the different messages, configure comm methods, and send messages
                       
                 $email_message = ( $whale_alert == 1 ? 'WHALE ALERT: ' : '' ) . 'The ' . $asset . ' trade value in the ' . strtoupper($pairing) . ' market at the ' . $exchange_text . ' exchange has ' . $increase_decrease . ' ' . $change_symbol . $percent_change_text . '% in ' . strtoupper($default_btc_primary_currency_pairing) . ' value to ' . $app_config['power_user']['bitcoin_currency_markets'][$default_btc_primary_currency_pairing] . $asset_primary_currency_text . ' over the past ' . $last_cached_time . ' since the last price ' . $desc_alert_type . '. ' . $email_volume_summary;
                       
                 // Were're just adding a human-readable timestamp to smart home (audio) alerts
                 $notifyme_message = $email_message . ' Timestamp: ' . time_date_format($app_config['general']['local_time_offset'], 'pretty_time') . '.';
                       
                 $text_message = ( $whale_alert == 1 ? '🐳 ' : '' ) . $asset . ' / ' . strtoupper($pairing) . ' @ ' . $exchange_text . ' ' . $increase_decrease . ' ' . $change_symbol . $percent_change_text . '% in ' . strtoupper($default_btc_primary_currency_pairing) . ' value to ' . $app_config['power_user']['bitcoin_currency_markets'][$default_btc_primary_currency_pairing] . $asset_primary_currency_text . ' over ' . $last_cached_time . '. 24 Hour ' . strtoupper($default_btc_primary_currency_pairing) . ' Volume: ' . $volume_primary_currency_text . ' ' . $volume_change_text_mobile;
                       
                       
                       
                       
                 // Cache the new lower / higher value + volume data
                 store_file_contents($base_dir . '/cache/alerts/'.$asset_data.'.dat', $alert_cache_contents); 
                   
                   
                   
                 // Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
                       
                 // Minimize function calls
                 $encoded_text_message = content_data_encoding($text_message); // Unicode support included for text messages (emojis / asian characters / etc )
                       
                 $send_params = array(
                 
                                      'notifyme' => $notifyme_message,
                                      'telegram' => ( $whale_alert == 1 ? '🐳 ' : '' ) . $email_message, // Add emoji here, so it's not sent with alexa / google home alerts
                                      'text' => array(
                                                      'message' => $encoded_text_message['content_output'],
                                                      'charset' => $encoded_text_message['charset']
                                                      ),
                                      'email' => array(
                                                      'subject' => $asset . ' Asset Value '.ucfirst($increase_decrease).' Alert' . ( $whale_alert == 1 ? ' (🐳 WHALE ALERT)' : '' ),
                                                      'message' => ( $whale_alert == 1 ? '🐳 ' : '' ) . $email_message // Add emoji here, so it's not sent with alexa / google home alerts
                                                      )
                                                      
                                       );
                   
                   
                   
                 // Send notifications
                 @queue_notifications($send_params);
         
                    
                 }
                 
                 
         
         }
           
      
      
       // Cache a price alert value / volumes if not already done, OR if config setting set to reset every X days
       if ( $pt_vars->num_to_str($asset_primary_currency_value_raw) >= 0.00000001 && !file_exists('cache/alerts/'.$asset_data.'.dat') ) {
       store_file_contents($base_dir . '/cache/alerts/'.$asset_data.'.dat', $alert_cache_contents); 
       }
       elseif ( $send_alert != 1 && $app_config['charts_alerts']['price_alerts_fixed_reset'] >= 1 && $pt_vars->num_to_str($asset_primary_currency_value_raw) >= 0.00000001 
       && update_cache_file('cache/alerts/'.$asset_data.'.dat', ( $app_config['charts_alerts']['price_alerts_fixed_reset'] * 1440 ) ) == true ) {
         
       store_file_contents($base_dir . '/cache/alerts/'.$asset_data.'.dat', $alert_cache_contents); 
       
       // Comms data (for one alert message, including data on all resets per runtime)
       $price_alerts_fixed_reset_array[strtolower($asset)][$asset_data] = $asset . ' / ' . strtoupper($pairing) . ' @ ' . $exchange_text . ' (' . $change_symbol . $percent_change_text . '%)';
       
       }
   
   
      
     ////// Alert checking END //////////////
     }
     /////////////////////////////////////////////////////////////////
     
     
   
   // If we haven't returned false yet because of any issues being detected, return true to indicate all seems ok
   return true;
   
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////

      
   
}




?>