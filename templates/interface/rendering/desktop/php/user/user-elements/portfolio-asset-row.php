<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


// REMEMBER WE HAVE #GLOBALS# TO WORRY ABOUT ADDING IN $pt_assets->ui_coin_row(), AS THAT'S WHERE THIS CODE IS RAN!

 ?>
 

<!-- Coin data row START -->

<tr id='<?=strtolower($asset_symbol)?>_row'>
  


<td class='data border_lb'>

<span class='app_sort_filter'><?php echo $sort_order; ?></span>

</td>



<td class='data border_lb' align='right' style='position: relative; white-space: nowrap;'>
 
 
 <?php
 
 $mkcap_render_data = trim($app_config['portfolio_assets'][$asset_symbol]['marketcap_website_slug']);
 
// Consolidate function calls for runtime speed improvement
 $marketcap_data = marketcap_data($asset_symbol);
 
 $info_icon = ( !$marketcap_data['rank'] && $asset_symbol != 'MISCASSETS' ? 'info-red.png' : 'info.png' );
 
 
	if ( $mkcap_render_data != '' ) {
 	
 
 		if ( $app_config['general']['primary_marketcap_site'] == 'coinmarketcap' ) {
 		$asset_pagebase = 'coinmarketcap.com/currencies/';
 		}
 		elseif ( $app_config['general']['primary_marketcap_site'] == 'coingecko' ) {
 		$asset_pagebase = 'coingecko.com/en/coins/';
 		}
 	
 	
 		?>
 		
 <a href='https://<?=$asset_pagebase?><?=$mkcap_render_data?>/' target='_blank' class='blue app_sort_filter' title='View <?=ucfirst($app_config['general']['primary_marketcap_site'])?> Information Page For <?=$asset_symbol?>'><?=$asset_name?></a> <img id='<?=$mkcap_render_data?>' src='templates/interface/media/images/<?=$info_icon?>' alt='' style='position: relative; vertical-align:middle; height: 30px; width: 30px;' /> 
 <script>

		<?php
		if ( !$marketcap_data['rank'] ) {
			
			if ( $app_config['general']['primary_marketcap_site'] == 'coinmarketcap' && trim($app_config['general']['coinmarketcapcom_api_key']) == null ) {
			?>

			var cmc_content = '<p class="coin_info"><span class="red"><?=ucfirst($app_config['general']['primary_marketcap_site'])?> API key is required. <br />Configuration adjustments can be made in the Admin Config GENERAL section.</span></p>';
	
			<?php
			}
			else {
			?>

			var cmc_content = '<p class="coin_info"><span class="red"><?=ucfirst($app_config['general']['primary_marketcap_site'])?> API may be offline / under heavy load, <br />marketcap range not set high enough (current range is top <?=$app_config['power_user']['marketcap_ranks_max']?> marketcaps), <br />or API timeout set too low (current timeout is <?=$app_config['power_user']['remote_api_timeout']?> seconds). <br /><br />Configuration adjustments can be made in the Admin Config POWER USER section.</span></p>';
	
			<?php
			}

			if ( sizeof($alert_percent) > 4 ) { // Backwards compatibility (reset if user data is not this many array values)
			?>
			
			setTimeout(function() {
    		row_alert("<?=strtolower($asset_symbol)?>_row", "visual", "no_cmc", "<?=$theme_selected?>"); // Assets with marketcap data not set or functioning properly
			}, 1000);
			
			<?php
			}
		
        }
        else {
        	
        		if ( isset($cap_data_force_usd) ) {
        		$cmc_primary_currency_symbol = '$';
        		$cmc_primary_currency_ticker = 'USD';
        		}
        		else {
        		$cmc_primary_currency_symbol = $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']];
        		$cmc_primary_currency_ticker = strtoupper($app_config['general']['btc_primary_currency_pairing']);
        		}
        		
        ?> 
    
        var cmc_content = '<h5 class="yellow tooltip_title"><?=ucfirst($app_config['general']['primary_marketcap_site'])?>.com Summary For <?=$asset_name?> (<?=$asset_symbol?>)</h5>'
        
        		<?php
            if ( $marketcap_data['app_notice'] != '' ) {
        		?>
        +'<p class="coin_info red">Notice: <?=$marketcap_data['app_notice']?></p>'
        		<?php
            }
        		?>
        
        +'<p class="coin_info"><span class="yellow">Ranking:</span> #<?=$marketcap_data['rank']?></p>'
        +'<p class="coin_info"><span class="yellow">Marketcap (circulating):</span> <?=$cmc_primary_currency_symbol?><?=number_format($marketcap_data['market_cap'],0,".",",")?></p>'
        
        <?php
            if ( $marketcap_data['market_cap_total'] > 0 ) {
            ?>
        +'<p class="coin_info"><span class="yellow">Marketcap (total):</span> <?=$cmc_primary_currency_symbol?><?=number_format($marketcap_data['market_cap_total'],0,".",",")?></p>'
        <?php
            }
            if ( $marketcap_data['circulating_supply'] > 0 ) {
            ?>
        +'<p class="coin_info"><span class="yellow">Circulating Supply:</span> <?=number_format($marketcap_data['circulating_supply'], 0, '.', ',')?></p>'
        <?php
            }
            if ( $marketcap_data['total_supply'] > 0 ) {
            ?>
        +'<p class="coin_info"><span class="yellow">Total Supply:</span> <?=number_format($marketcap_data['total_supply'], 0, '.', ',')?></p>'
        <?php
            }
            if ( $marketcap_data['max_supply'] > 0 ) {
            ?>
        +'<p class="coin_info"><span class="yellow">Maximum Supply:</span> <?=number_format($marketcap_data['max_supply'], 0, '.', ',')?></p>'
        <?php
            }
            ?>
        +'<p class="coin_info"><span class="yellow">Unit Value (global average):</span> <?=$cmc_primary_currency_symbol?><?=$marketcap_data['price']?></p>'
        +'<p class="coin_info"><span class="yellow">24 Hour Volume (global):</span> <?=$cmc_primary_currency_symbol?><?=number_format($marketcap_data['volume_24h'],0,".",",")?></p>'
        <?php
            if ( $marketcap_data['percent_change_1h'] != null ) {
            ?>
        +'<p class="coin_info"><span class="yellow">1 Hour Change:</span> <?=( stristr($marketcap_data['percent_change_1h'], '-') != false ? '<span class="red">'.$marketcap_data['percent_change_1h'].'%</span>' : '<span class="green">+'.$marketcap_data['percent_change_1h'].'%</span>' )?></p>'
        <?php
            }
            ?>
        +'<p class="coin_info"><span class="yellow">24 Hour Change:</span> <?=( stristr($marketcap_data['percent_change_24h'], '-') != false ? '<span class="red">'.$marketcap_data['percent_change_24h'].'%</span>' : '<span class="green">+'.$marketcap_data['percent_change_24h'].'%</span>' )?></p>'
        <?php
            if ( $marketcap_data['percent_change_7d'] != null ) {
            ?>
        +'<p class="coin_info"><span class="yellow">7 Day Change:</span> <?=( stristr($marketcap_data['percent_change_7d'], '-') != false ? '<span class="red">'.$marketcap_data['percent_change_7d'].'%</span>' : '<span class="green">+'.$marketcap_data['percent_change_7d'].'%</span>' )?></p>'
        <?php
            }
            if ( $marketcap_data['percent_change_14d'] != null ) {
            ?>
        +'<p class="coin_info"><span class="yellow">14 Day Change:</span> <?=( stristr($marketcap_data['percent_change_14d'], '-') != false ? '<span class="red">'.$marketcap_data['percent_change_14d'].'%</span>' : '<span class="green">+'.$marketcap_data['percent_change_14d'].'%</span>' )?></p>'
        <?php
            }
            if ( $marketcap_data['percent_change_30d'] != null ) {
            ?>
        +'<p class="coin_info"><span class="yellow">30 Day Change:</span> <?=( stristr($marketcap_data['percent_change_30d'], '-') != false ? '<span class="red">'.$marketcap_data['percent_change_30d'].'%</span>' : '<span class="green">+'.$marketcap_data['percent_change_30d'].'%</span>' )?></p>'
        <?php
            }
            if ( $marketcap_data['percent_change_90d'] != null ) {
            ?>
        +'<p class="coin_info"><span class="yellow">90 Day Change:</span> <?=( stristr($marketcap_data['percent_change_90d'], '-') != false ? '<span class="red">'.$marketcap_data['percent_change_90d'].'%</span>' : '<span class="green">+'.$marketcap_data['percent_change_90d'].'%</span>' )?></p>'
        <?php
            }
            if ( $marketcap_data['percent_change_200d'] != null ) {
            ?>
        +'<p class="coin_info"><span class="yellow">200 Day Change:</span> <?=( stristr($marketcap_data['percent_change_200d'], '-') != false ? '<span class="red">'.$marketcap_data['percent_change_200d'].'%</span>' : '<span class="green">+'.$marketcap_data['percent_change_200d'].'%</span>' )?></p>'
        <?php
            }
            if ( $marketcap_data['percent_change_1y'] != null ) {
            ?>
        +'<p class="coin_info"><span class="yellow">1 Year Change:</span> <?=( stristr($marketcap_data['percent_change_1y'], '-') != false ? '<span class="red">'.$marketcap_data['percent_change_1y'].'%</span>' : '<span class="green">+'.$marketcap_data['percent_change_1y'].'%</span>' )?></p>'
        <?php
            }
            if ( $marketcap_data['last_updated'] != '' ) {
            ?>
        +'<p class="coin_info"><span class="yellow">Data Timestamp (UTC):</span> <?=gmdate("Y-M-d\ \\a\\t g:ia", $marketcap_data['last_updated'])?></p>'
        +'<p class="coin_info"><span class="yellow">App Cache Time:</span> <?=$app_config['power_user']['marketcap_cache_time']?> minute(s)</p>'
        <?php
            }
            ?>
    
        +'<p class="coin_info balloon_notation yellow">*Current config setting retrieves the top <?=$app_config['power_user']['marketcap_ranks_max']?> rankings.</p>';
    
        <?php
        
        }
        ?>
    
        $('#<?=$mkcap_render_data?>').balloon({
        html: true,
        position: "right",
  		  classname: 'balloon-tooltips',
        contents: cmc_content,
        css: {
                fontSize: ".8rem",
                minWidth: "450px",
                padding: ".3rem .7rem",
                border: "2px solid rgba(212, 212, 212, .4)",
                borderRadius: "6px",
                boxShadow: "3px 3px 6px #555",
                color: "#eee",
                backgroundColor: "#111",
                opacity: "0.99",
                zIndex: "32767",
                textAlign: "left"
                }
        });
    
    
    <?php
    
    
        if ( sizeof($alert_percent) > 4 ) { // Backwards compatibility (reset if user data is not this many array values)
        	
        $percent_alert_filter = $alert_percent[2]; // gain / loss / both
    
        $percent_change_alert = $alert_percent[1];
    
        $percent_alert_type = $alert_percent[4];
    
    
            if ( $alert_percent[3] == '1hour' ) {
            $percent_change = $marketcap_data['percent_change_1h'];
            }
            elseif ( $alert_percent[3] == '24hour' ) {
            $percent_change = $marketcap_data['percent_change_24h'];
            }
            elseif ( $alert_percent[3] == '7day' ) {
            $percent_change = $marketcap_data['percent_change_7d'];
            }
          
         
            if ( $percent_alert_filter != 'gain' && stristr($percent_change, '-') != false && abs($percent_change) >= abs($percent_change_alert) && is_numeric($percent_change) ) {
            ?>
         
            setTimeout(function() {
               row_alert("<?=strtolower($asset_symbol)?>_row", "<?=$percent_alert_type?>", "yellow", "<?=$theme_selected?>");
            }, 1000);
            
            <?php
            }
            elseif ( $percent_alert_filter != 'loss' && stristr($percent_change, '-') == false && abs($percent_change) >= abs($percent_change_alert) && is_numeric($percent_change) ) {
            ?>
            
            setTimeout(function() {
               row_alert("<?=strtolower($asset_symbol)?>_row", "<?=$percent_alert_type?>", "green", "<?=$theme_selected?>");
            }, 1000);
            
            <?php
            }
        
        
        }
        ?>
     </script>
     
 <?php
	}
	else {
		
  ?>
  
  <span class='blue app_sort_filter'><?=$asset_name?></span> <img id='<?=$rand_id?>' src='templates/interface/media/images/<?=$info_icon?>' alt='' style='position: relative; vertical-align:middle; height: 30px; width: 30px;' /> 
 <script>
 
 			<?php
			if ( $asset_symbol == 'MISCASSETS' ) {
			?>

			var cmc_content = '<h5 class="yellow align_center tooltip_title"><?=$asset_name?> (<?=$asset_symbol?>)</h5>'
    
        +'<p class="coin_info" style="white-space: normal; max-width: 600px;"><span class="yellow">Miscellaneous <?=strtoupper($app_config['general']['btc_primary_currency_pairing'])?> value can be included in you portfolio stats, by entering it under the "MISCASSETS" asset on the "Update" page.</span></p>'
        
        +'<p class="coin_info" style="white-space: normal; max-width: 600px;"><span class="yellow">Additionally, you can see it\'s potential market value in another asset by changing the "Market" value on the "Portfolio" page to an asset other than <?=strtoupper($app_config['general']['btc_primary_currency_pairing'])?>.</span></p>';
	
			<?php
			}
			else {
			?>
			
			var cmc_content = '<p class="coin_info"><span class="red">No <?=ucfirst($app_config['general']['primary_marketcap_site'])?>.com data for <?=$asset_name?> (<?=$asset_symbol?>) has been configured yet.</span></p>';
	
			<?php
			}
			?>
 
 $('#<?=$rand_id?>').balloon({
  html: true,
  position: "right",
  classname: 'balloon-tooltips',
  contents: cmc_content,
			css: {
					fontSize: ".8rem",
					minWidth: "450px",
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: "32767",
					textAlign: "left"
					}
});

		<?php
		if ( sizeof($alert_percent) > 4 ) { // Backwards compatibility (reset if user data is not this many array values)
		?>
		
		setTimeout(function() {
    	row_alert("<?=strtolower($asset_symbol)?>_row", "visual", "no_cmc", "<?=$theme_selected?>"); // Assets with marketcap data not set or functioning properly
		}, 1000);
		
		<?php
		}
		?>
		
 </script>
 
	<?php
	}
 
 ?>
 
 
</td>




<td class='data border_b'>


<?php
  
  $coin_primary_currency_value = ( $selected_btc_primary_currency_value * $btc_trade_eqiv_raw );

  // UX on FIAT EQUIV number values
  $coin_primary_currency_value = ( $pt_vars->num_to_str($coin_primary_currency_value) >= $app_config['general']['primary_currency_decimals_max_threshold'] ? $pt_vars->num_pretty($coin_primary_currency_value, 2) : $pt_vars->num_pretty($coin_primary_currency_value, $app_config['general']['primary_currency_decimals_max']) );
	
  echo "<span class='white'>" . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] . "</span>" . "<span class='app_sort_filter'>" . $coin_primary_currency_value . "</span>";

?>

</td>




<td class='data border_lb'>

	<?php
	if ( $asset_market_data['defi_pool_name'] ) {
	$defi_exchange_dropdown_title = "\n\n" . 'Current DeFi Liquidity Pool: ' . $asset_market_data['defi_pool_name'] . ' (' . $asset_market_data['defi_platform'] . ')';
	}
	?>
 
    <select class='browser-default custom-select' name='change_<?=strtolower($asset_symbol)?>_market' title='Choose which exchange or defi pool you want.<?=htmlentities($defi_exchange_dropdown_title, ENT_QUOTES)?>' onchange='
    $("#<?=strtolower($asset_symbol)?>_market").val(this.value);
    $("#coin_amounts").submit();
    '>
        <?php
        foreach ( $all_pairing_markets as $market_key => $market_name ) {
         $loop = $loop + 1;
         	if ( $original_market == ($loop -1) ) {
         	$ui_selected_market = snake_case_to_name($market_key);
         	}
        ?>
        <option value='<?=($loop)?>' <?=( $original_market == ($loop -1) ? ' selected ' : '' )?>> <?=snake_case_to_name($market_key)?> </option>
        <?php
        }
        $loop = null;
        ?>
    </select>
    
    <div class='app_sort_filter' style='display: none;'><?=$ui_selected_market?></div>

</td>




<td class='data border_b' align='right'>

<span class='app_sort_filter'>

<?php 

$coin_value_raw = $pt_vars->num_to_str($coin_value_raw);

	// UX on FIAT EQUIV number values
	if ( $fiat_eqiv == 1 ) {
	$coin_value_decimals = ( $coin_value_raw >= $app_config['general']['primary_currency_decimals_max_threshold'] ? 2 : $app_config['general']['primary_currency_decimals_max'] );
	}
	else {
	
		if ( $selected_pairing == 'btc' ) {
		$coin_value_decimals = ( $coin_value_raw >= 0.01 ? 6 : 8 );
		$coin_value_decimals = ( $coin_value_raw >= 1 ? 4 : $coin_value_decimals );
		}
		else {
		$coin_value_decimals = ( $coin_value_raw >= 0.01 ? 4 : 8 );
		$coin_value_decimals = ( $coin_value_raw >= 1 ? 2 : $coin_value_decimals );
		}
		
	}
  
  
echo $pt_vars->num_pretty($coin_value_raw, $coin_value_decimals); 


?>

</span>

<?php

  if ( $show_secondary_trade_value != null && $selected_pairing != $show_secondary_trade_value && strtolower($asset_symbol) != $show_secondary_trade_value ) {
  
		if ( $show_secondary_trade_value == 'btc' ) {
		$secondary_trade_value_result = $pt_vars->num_to_str($btc_trade_eqiv_raw);
		$secondary_trade_value_decimals = ( $secondary_trade_value_result >= 0.01 ? 6 : 8 );
		$secondary_trade_value_decimals = ( $secondary_trade_value_result >= 1 ? 4 : $secondary_trade_value_decimals );
		}
		else {
		$secondary_trade_value_result = $pt_vars->num_to_str( $btc_trade_eqiv_raw / pairing_btc_value($show_secondary_trade_value) );
		$secondary_trade_value_decimals = ( $secondary_trade_value_result >= 0.01 ? 4 : 8 );
		$secondary_trade_value_decimals = ( $secondary_trade_value_result >= 1 ? 2 : $secondary_trade_value_decimals );
		}
		
		if ( $secondary_trade_value_result >= 0.00000001 ) {
  		echo '<div class="crypto_worth">(' . $pt_vars->num_pretty($secondary_trade_value_result, $secondary_trade_value_decimals) . ' '.strtoupper($show_secondary_trade_value).')</div>';
		}
  
  }
  
?>

</td>




<td class='data border_b'> 

 
    <select class='browser-default custom-select' name='change_<?=strtolower($asset_symbol)?>_pairing' title='Choose which market you want.' onchange='
    $("#<?=strtolower($asset_symbol)?>_pairing").val(this.value); 
    $("#<?=strtolower($asset_symbol)?>_market").val(1); // Just reset to first listed market for this pairing
    $("#coin_amounts").submit();
    '>
    
    
        <?php
		  
        $loop = 0;

	        foreach ( $all_pairings as $pairing_key => $pairing_name ) {
	         $loop = $loop + 1;
	         	if ( $selected_pairing == $pairing_key ) {
	         	$ui_selected_pairing = $pairing_key;
	         	}
	        ?>
	        <option value='<?=$pairing_key?>' <?=( $selected_pairing == $pairing_key ? ' selected ' : '' )?>> <?=strtoupper($pairing_key)?> </option>
	        <?php
	        }
        
        $loop = null;
        
        ?>
        
        
    </select>
    
    <div class='app_sort_filter' style='display: none;'><?=$ui_selected_pairing?></div>

</td>




<td class='data border_b'>

<span class='white'><?=$app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']]?></span><span class='app_sort_filter'><?php 

  // NULL if not setup to get volume, negative number returned if no data received from API
  if ( $trade_volume == NULL || $trade_volume == -1 ) {
  echo '0';
  }
  elseif ( $trade_volume >= 0 ) {
  echo number_format($trade_volume, 0, '.', ',');
  }

?></span>

</td>




<td class='data border_lb blue' align='right'>

<?php

	if ( strtoupper($asset_symbol) == 'MISCASSETS' ) {
	$asset_amount_decimals = 2;
	}
	else {
	$asset_amount_decimals = 8;
	}
	
$pretty_coin_amount = $pt_vars->num_pretty($asset_amount, $asset_amount_decimals);

echo "<span class='app_sort_filter blue'>" . ( $pretty_coin_amount != null ? $pretty_coin_amount : 0 ) . "</span>";

?>

</td>




<td class='data border_b'><span class='app_sort_filter'>

<?php echo $asset_symbol; ?></span>

</td>




<td class='data border_b blue'>

<?php

$coin_value_total_raw = $pt_vars->num_to_str($coin_value_total_raw);

	// UX on FIAT EQUIV number values
	if ( $fiat_eqiv == 1 ) {
	$coin_value_total_decimals = ( $coin_value_total_raw >= $app_config['general']['primary_currency_decimals_max_threshold'] ? 2 : $app_config['general']['primary_currency_decimals_max'] );
	}
	else {
	
		if ( $selected_pairing == 'btc' ) {
		$coin_value_total_decimals = ( $coin_value_total_raw >= 0.01 ? 6 : 8 );
		$coin_value_total_decimals = ( $coin_value_total_raw >= 1 ? 4 : $coin_value_total_decimals );
		}
		else {
		$coin_value_total_decimals = ( $coin_value_total_raw >= 0.01 ? 4 : 8 );
		$coin_value_total_decimals = ( $coin_value_total_raw >= 1 ? 2 : $coin_value_total_decimals );
		}
		
	}
  
  
$pretty_coin_value_total_raw = $pt_vars->num_pretty($coin_value_total_raw, $coin_value_total_decimals); 


echo ' <span class="blue"><span class="data app_sort_filter blue">' . $pretty_coin_value_total_raw . '</span> ' . strtoupper($selected_pairing) . '</span>';

  
  if ( $show_secondary_trade_value != null && $selected_pairing != $show_secondary_trade_value && strtolower($asset_symbol) != $show_secondary_trade_value ) {
  
		if ( $show_secondary_trade_value == 'btc' ) {
		$secondary_holdings_value_result = $pt_vars->num_to_str($coin_value_total_raw * $pairing_btc_value);
		$secondary_holdings_value_decimals = ( $secondary_holdings_value_result >= 0.01 ? 6 : 8 );
		$secondary_holdings_value_decimals = ( $secondary_holdings_value_result >= 1 ? 4 : $secondary_holdings_value_decimals );
		}
		else {
		$secondary_holdings_value_result = $pt_vars->num_to_str( ($coin_value_total_raw * $pairing_btc_value) / pairing_btc_value($show_secondary_trade_value) );
		$secondary_holdings_value_decimals = ( $secondary_holdings_value_result >= 0.01 ? 4 : 8 );
		$secondary_holdings_value_decimals = ( $secondary_holdings_value_result >= 1 ? 2 : $secondary_holdings_value_decimals );
		}
		
		if ( $secondary_holdings_value_result >= 0.00000001 ) {
  		echo '<div class="crypto_worth"><span>(' . $pt_vars->num_pretty($secondary_holdings_value_result, $secondary_holdings_value_decimals) . ' '.strtoupper($show_secondary_trade_value).')</span></div>';
  		}
  		
  }
  

?>

</td>




<td class='data border_rb blue' style='white-space: nowrap;'>



<?php


echo '<span class="' . ( $purchase_price >= 0.00000001 && $leverage_level >= 2 && $selected_margintype == 'short' ? 'short">★ ' : 'blue">' ) . '<span class="blue">' . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] . '</span><span class="app_sort_filter blue">' . number_format($coin_primary_currency_worth_raw, 2, '.', ',') . '</span></span>';

  if ( $purchase_price >= 0.00000001 && $leverage_level >= 2 ) {

  $coin_worth_inc_leverage = $coin_primary_currency_worth_raw + $only_leverage_gain_loss;
  
  echo ' <span class="extra_data">(' . $leverage_level . 'x ' . $selected_margintype . ')</span>';

  // Here we parse out negative symbols
  $parsed_gain_loss = preg_replace("/-/", "-" . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']], number_format( $gain_loss, 2, '.', ',' ) );
  
  $parsed_inc_leverage_gain_loss = preg_replace("/-/", "-" . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']], number_format( $inc_leverage_gain_loss, 2, '.', ',' ) );
  
  $parsed_only_leverage_gain_loss = preg_replace("/-/", "-" . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']], number_format($only_leverage_gain_loss, 2, '.', ',' ) );
  
  // Here we can go negative 'total worth' with the margin leverage (unlike with the margin deposit)
  // We only want a negative sign here in the UI for 'total worth' clarity (if applicable), NEVER a plus sign
  // (plus sign would indicate a gain, NOT 'total worth')
  $parsed_coin_worth_inc_leverage = preg_replace("/-/", "", number_format($coin_worth_inc_leverage, 2, '.', ',' ) );
  
  
  // Pretty format, but no need to parse out anything here
  $pretty_coin_primary_currency_worth_raw = number_format( ($coin_primary_currency_worth_raw) , 2, '.', ',' );
  $pretty_leverage_gain_loss_percent = number_format( $inc_leverage_gain_loss_percent, 2, '.', ',' );
  
  
  		// Formatting
  		$gain_loss_span_color = ( $gain_loss >= 0 ? 'green' : 'red' );
  		$gain_loss_primary_currency = ( $gain_loss >= 0 ? '+' . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] : '' );
  		
		?> 
		<img id='<?=$rand_id?>_leverage' src='templates/interface/media/images/info.png' alt='' width='30' style='position: relative; left: -5px;' />
	 <script>
	
			var leverage_content = '<h5 class="yellow tooltip_title"><?=$leverage_level?>x <?=ucfirst($selected_margintype)?> For <?=$asset_name?> (<?=$asset_symbol?>)</h5>'
			
			+'<p class="coin_info"><span class="yellow">Deposit (1x):</span> <span class="<?=$gain_loss_span_color?>"><?=$gain_loss_primary_currency?><?=$parsed_gain_loss?></span> (<?=$app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']]?><?=$pretty_coin_primary_currency_worth_raw?>)</p>'
			
			+'<p class="coin_info"><span class="yellow">Margin (<?=($leverage_level - 1)?>x):</span> <span class="<?=$gain_loss_span_color?>"><?=$gain_loss_primary_currency?><?=$parsed_only_leverage_gain_loss?></span></p>'
			
			+'<p class="coin_info"><span class="yellow">Total (<?=($leverage_level)?>x):</span> <span class="<?=$gain_loss_span_color?>"><?=$gain_loss_primary_currency?><?=$parsed_inc_leverage_gain_loss?> / <?=( $gain_loss >= 0 ? '+' : '' )?><?=$pretty_leverage_gain_loss_percent?>%</span> (<?=( $coin_worth_inc_leverage >= 0 ? '' : '-' )?><?=$app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']]?><?=$parsed_coin_worth_inc_leverage?>)</p>'
			
				
			+'<p class="coin_info"><span class="yellow"> </span></p>';
		
		
			$('#<?=$rand_id?>_leverage').balloon({
			html: true,
			position: "left",
  			classname: 'balloon-tooltips',
			contents: leverage_content,
			css: {
					fontSize: ".8rem",
					minWidth: "450px",
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: "32767",
					textAlign: "left"
					}
			});
		
		 </script>
		 
		<?php
  		}

?>


</td>



  
</tr>

<!-- Coin data row END -->



