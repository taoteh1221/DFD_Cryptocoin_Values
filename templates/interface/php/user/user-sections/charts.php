<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

?>

<div class='full_width_wrapper'>
	
	<h4 style='display: inline;'>Charts</h4>
				
				<span id='reload_countdown4' class='red countdown_notice'></span>
			
	
	<p style='margin-top: 15px; margin-bottom: 15px;'><?=start_page_html('charts')?></p>			
				
	<p><button class="show_chart_settings force_button_style">Activate Charts</button></p>
	
	
	<div id="show_chart_settings">
	
		
		<h3>Activate Charts</h3>
	
	<p class='red'>*Charts are not activated by default to increase page loading speed / responsiveness. It's recommended to avoid activating too many charts at the same time, to keep your page load times quick. You can enable "Use cookies to save data between browser sessions" on the Settings page <i>before activating your charts</i>, if you want them to stay activated between browser sessions.</p>
	
	<p class='red'>v4.03.0 and higher charts are NOT backwards-compatible, as the 24 hour volume format was completely changed over to always be based off pairing volume data only (24 hour asset volume is no longer supported).</p>
	
	<!-- Submit button must be OUTSIDE form tags here, or it submits the target form improperly and loses data -->
	<p><button class='force_button_style' onclick='
	document.coin_amounts.submit();
	'>Update Activated Charts</button></p>
	
	<p><input type='checkbox' onclick='
	
		selectAll(this, "activate_charts");
		
		if ( this.checked == false ) {
		$("#show_charts").val("");
		}
		
	' /> Select / Unselect All &nbsp;&nbsp; <span class='red'>(if "loading charts" notice freezes, check / uncheck this box, then click "Update Activated Charts")</span></p>
		
		<form id='activate_charts' name='activate_charts'>
		
	<?php
	
	$zebra_stripe = 'long_list_odd';
	foreach ( $app_config['charts_and_price_alerts'] as $key => $value ) {
		
		// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
		$show_asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, mb_strpos($key, "-", 0, 'utf-8') ) );
		$show_asset = strtoupper($show_asset);
		
		$show_asset_params = explode("||", $value);
		
				
			if ( $show_asset_params[2] == 'chart' || $show_asset_params[2] == 'both' ) {
	?>
	
		<div class='<?=$zebra_stripe?> long_list <?=( $last_rendered != $show_asset ? 'activate_chart_sections' : '' )?>'>
			
				<?php
				// Markets that are the same as PRIMARY CURRENCY CONFIG setting
				if ( $show_asset_params[1] == $default_btc_primary_currency_pairing ) {
				?>
	
			   <input type='checkbox' value='<?=$key?>_<?=$show_asset_params[1]?>' onchange='chart_toggle(this);' <?=( in_array("[".$key . '_' . $show_asset_params[1]."]", $show_charts) ? 'checked' : '' )?> /> <span class='blue'><?=$show_asset?></span> / <?=strtoupper($show_asset_params[1])?> @ <?=snake_case_to_name($show_asset_params[0])?>
	
				<?php
				}
				// All other paired markets (WITH PRIMARY CURRENCY CONFIG EQUIV CHARTS INCLUDED)
				else {
				?>
				
				<input type='checkbox' value='<?=$key?>_<?=$show_asset_params[1]?>' onchange='chart_toggle(this);' <?=( in_array("[".$key . '_' . $show_asset_params[1]."]", $show_charts) ? 'checked' : '' )?> /> <span class='blue'><?=$show_asset?></span> / <?=strtoupper($show_asset_params[1])?> @ <?=snake_case_to_name($show_asset_params[0])?> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
					
				<input type='checkbox' value='<?=$key?>' onchange='chart_toggle(this);' <?=( in_array("[".$key."]", $show_charts) ? 'checked' : '' )?> /> <?=strtoupper($default_btc_primary_currency_pairing)?> Value
				
			   
	
				<?php
				}?>
	
			</div>
				
	<?php
	    
		 		if ( $zebra_stripe == 'long_list_odd' ) {
			 	$zebra_stripe = 'long_list_even';
			 	}
			 	else {
			 	$zebra_stripe = 'long_list_odd';
			 	}
		 	
		 	}
	
	$last_rendered = $show_asset;
	}
	    
	?>
	<div class='long_list_end' style='border-top: 2px solid black;'> &nbsp; </div>
	
		</form>
	
		<!-- Submit button must be OUTSIDE form tags here, or it submits the target form improperly and loses data -->
		<p><button class='force_button_style' onclick='
		document.coin_amounts.submit();
		'>Update Activated Charts</button></p>
		
	</div>
	
	
	<script>
	$('.show_chart_settings').modaal({
		content_source: '#show_chart_settings'
	});
	</script>
	
	  
	<p><a style='font-weight: bold;' class='red show' id='chartsnotice' href='#show_chartsnotice' title='Click to show charts notice.' onclick='return false;'><b>Charts Notice / Information</b></a></p>
		
		
	<div style='display: none;' class='align_left show_chartsnotice'>
		
		<?php
		foreach ( $app_config['bitcoin_currency_markets'] as $key => $unused ) {
		$supported_primary_currency_list .= strtoupper($key) . ' / ';
		}
		$supported_primary_currency_list = trim($supported_primary_currency_list);
		$supported_primary_currency_list = rtrim($supported_primary_currency_list,'/');
		$supported_primary_currency_list = trim($supported_primary_currency_list);
		
		foreach ( $app_config['portfolio_assets']['BTC']['market_pairing'][$default_btc_primary_currency_pairing] as $key => $unused ) {
		$supported_exchange_list .= snake_case_to_name($key) . ' / ';
		}
		$supported_exchange_list = trim($supported_exchange_list);
		$supported_exchange_list = rtrim($supported_exchange_list,'/');
		$supported_exchange_list = trim($supported_exchange_list);
		?>
					
		<p class='red' style='font-weight: bold;'>The charts <i>primary currency market</i> (lets you see <i>optional / additional</i> "<?=strtoupper($default_btc_primary_currency_pairing)?> Value" charts, showing the <i><?=strtoupper($default_btc_primary_currency_pairing)?>-equivalent</i> market values) is set to: &nbsp; <span class='bitcoin'><?=strtoupper($default_btc_primary_currency_pairing)?> @ <?=snake_case_to_name($default_btc_primary_exchange)?></span></p>
		
		<p class='red' style='font-weight: bold;'><?=strtoupper($default_btc_primary_currency_pairing)?>-paired exchanges supported in this app are: <?=$supported_exchange_list?>. Currency pairings (that are supported in config.php in the "btc_primary_currency_pairing" setting) are: <?=$supported_primary_currency_list?>. !NOT! ALL EXCHANGES SUPPORT ALL CURRENCY PAIRS, double check any setting changes you make (and check the error log at /cache/logs/errors.log for any reported issues).</p>
		 
		<p class='red' style='font-weight: bold;'>A few crypto exchanges only provide asset volume data (with no pairing volume data included). If 24 hour pair volume is NOT available for a market, it will be emulated via the asset volume multiplied by the <i>current</i> asset market value (which gives us the rough pairing volume for a better chart user experience).</p>
		 
		<p class='red' style='font-weight: bold;'>Charts are only available to show for each asset properly configured in the charts / price alerts configuration section in the file config.php (located in the primary directory of this app). Charts (and price alerts) must be <a href='README.txt' target='_blank'>setup as a cron job on your web server</a>, or <i>they will not work</i>. The chart's tab / page, and chart data caching can be disabled in config.php if you choose to not setup a cron job.</p>
		 
		<p class='red' style='font-weight: bold;'>v4.03.0 and higher charts are NOT backwards-compatible, as the 24 hour volume format was completely changed over to always be based off pairing volume data only (24 hour asset volume is no longer supported).</p>
	
				<br /><br />
	</div>
	
	
	<div class='red' id='charts_error'></div>
	
	
	<?php
	
	// Render the charts
	foreach ( $app_config['charts_and_price_alerts'] as $key => $value ) {
		
		$chart_asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, mb_strpos($key, "-", 0, 'utf-8') ) );
		$chart_asset = strtoupper($chart_asset);
		
		$charts_available = 1;
		$alerts_market_parse = explode("||", $value );	
		
		if ( in_array('['.$key.'_'.$alerts_market_parse[1].']', $show_charts) ) {
		$charts_shown = 1;
	?>
	
	<div class='chart_wrapper' id='<?=$key?>_<?=$alerts_market_parse[1]?>_chart'><span class='loading' style='color: <?=$app_config['charts_text']?>;'> &nbsp; Loading chart for <?=strtoupper($chart_asset)?> / <?=strtoupper($alerts_market_parse[1])?> @ <?=snake_case_to_name($alerts_market_parse[0])?>...</span></div>
	
	<script>
	
	$(document).ready(function() {
    $.getScript("app-lib/js/chart-js.php?type=asset&asset_data=<?=urlencode($key)?>&charted_value=pairing");
	});
	
	</script>
	
	<br/><br/><br/>
	
	<?php
		}
		
		if ( in_array('['.$key.']', $show_charts) && $alerts_market_parse[2] == 'chart' 
		|| in_array('['.$key.']', $show_charts) && $alerts_market_parse[2] == 'both'  ) {
		$charts_shown = 1;
	?>
	
	<div class='chart_wrapper' id='<?=$key?>_<?=strtolower($default_btc_primary_currency_pairing)?>_chart'><span class='loading' style='color: <?=$app_config['charts_text']?>;'> &nbsp; Loading chart for <?=strtoupper($chart_asset)?> / <?=strtoupper($alerts_market_parse[1])?> @ <?=snake_case_to_name($alerts_market_parse[0])?> (<?=strtoupper($default_btc_primary_currency_pairing)?> Value)...</span></div>
	
	<script>
	
	$(document).ready(function() {
    $.getScript("app-lib/js/chart-js.php?type=asset&asset_data=<?=urlencode($key)?>&charted_value=<?=strtolower($default_btc_primary_currency_pairing)?>");
	});
	
	</script>
	
	<br/><br/><br/>
	
	<?php
		}
		
	}
	
	if ( $charts_available == 1 && $charts_shown != 1 ) {
	?>
	<div class='align_center' style='min-height: 100px;'>
	
		<p><img src='templates/interface/media/images/favicon.png' alt='' class='image_border' /></p>
		<p class='red' style='font-weight: bold; position: relative; margin: 15px;'>Click the Activate Charts button (top left) to add charts.</p>
	</div>
	<?php
	}
	?>
	
				
				
				
</div> <!-- charts_page_wrapper END -->


