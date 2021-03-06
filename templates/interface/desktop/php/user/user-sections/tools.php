<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


?>

<div class='max_1200px_wrapper'>

				
				<span class='red countdown_notice'></span>
			

			<p style='margin-top: 15px; margin-bottom: 15px;'><?=$pt_gen->start_page_html('tools')?></p>			

			
			<fieldset class='subsection_fieldset'>
				<legend class='subsection_legend'> <b>QR Code Generator For Addresses</b> </legend>
		    
			<p class='red'>Using this QR code generator <i><u>will set this page as the start page</u>, which you can reset afterwards at top left</i>. If you have portfolio data you don't want to lose, be sure you have enabled "Use cookies to save data" on the Settings page before using this tool.</p>
			
			<p>If you need to safely / quickly copy an address to yours or someone else's phone / air-gapped machine / etc, with a QR Code scanner app. 
			<br /><br />NOTE: Whitespace, carriage returns, HTML, and non-alphanumeric characters are not allowed.</p>

			<form method='post' action='<?=$pt_gen->start_page('tools')?>'>

			<p><input type='text' name='qr-string' placeholder="Enter address to convert to QR code here..." value='<?=trim($_POST['qr-string'])?>' style='width: 100%;' /></p>

			<p><input type='submit' value='Generate QR Code Address' /></p>

			</form>

			<?php
			if ( trim($_POST['qr-string']) != '' ) {
			?>

			<p style='font-weight: bold;' class='bitcoin'>Your Generated QR Code Address:</p>
			<p><img src='templates/interface/media/images/qr-code-image.php?data=<?=urlencode(trim($_POST['qr-string']))?>' /></p>
			<p class='red' style='font-weight: bold;'>--ALWAYS-- VERIFY YOUR ADDRESS COPIED OVER CORRECTLY</p>

			<?php
			}
			?>
				
				
			</fieldset>
			
			
			<fieldset class='subsection_fieldset'>
				<legend class='subsection_legend'> <b>Altcoin Trade Preview / Marketcap Calculator</b> </legend>
    			
    			<p>Preview your altcoin buy / sell order value. Can also be used to calculate the marketcap of a coin supply.</p>
    			
    			<p><b>Token Amount:</b> <input type='text' id='to_trade_amount' name='to_trade_amount' value='0' size='20' /> </p>
    			
    			<p><b>BTC Trade Value:</b> <input type='text' id='sat_target' name='sat_target' value='0.00000001' minlength="10" maxlength="10" size="11" /> </p>
    			
    			<p><button class='force_button_style' onclick='
    				document.getElementById("sat_target").value = (0.00000001).toFixed(8);
    				sats_val("refresh");
    				'>Reset Satoshi Value</button> 
    			
    				<button class='force_button_style' onclick='sats_val(0.00000001);'>+1</button> 
    				
    				<button class='force_button_style' onclick='sats_val(0.00000010);'>+10</button> 
    				
    				<button class='force_button_style' onclick='sats_val(0.00000100);'>+100</button> 
    				
    				<button class='force_button_style' onclick='sats_val(0.00001000);'>+1,000</button> 
    				
    				<button class='force_button_style' onclick='sats_val(0.00010000);'>+10,000</button> 
    				
    				<button class='force_button_style' onclick='sats_val(0.00100000);'>+100,000</button> 
    				
    				<button class='force_button_style' onclick='sats_val(0.01000000);'>+1,000,000</button> 
    				
    				<button class='force_button_style' onclick='sats_val(0.10000000);'>+10,000,000</button> 
    				
    			</p>
    			
    			<p class='green' style='font-weight: bold;'>Per-Token (<?=strtoupper($pt_conf['gen']['btc_prim_currency_pairing'])?>): <?=$pt_conf['power']['btc_currency_markets'][ $pt_conf['gen']['btc_prim_currency_pairing'] ]?><span id='target_prim_currency'>0.00</span> (<span id='target_btc'>0.00</span> BTC) </p>
    			
    			<p class='green' style='font-weight: bold;'>Total: <?=$pt_conf['power']['btc_currency_markets'][ $pt_conf['gen']['btc_prim_currency_pairing'] ]?><span id='target_total_prim_currency'>0.00</span> (<span id='target_total_btc'>0.00</span> BTC) </p>
    			
    			<script>
    			
    			document.getElementById("to_trade_amount").addEventListener("input", function(){
  				sats_val("refresh");
				});
				
    			document.getElementById("sat_target").addEventListener("input", function(){
  				sats_val("refresh");
				});
    			
    			
    			</script>
    			
			</fieldset>
			
			
			<fieldset class='subsection_fieldset'>
				<legend class='subsection_legend'> <b>External Tools</b> </legend>
    			<ul>
        
        			<li class='links_list'><a href='https://calendar.google.com/' target='_blank'>Google Calendar to Send Yourself Reminders For Important Crypto Times</a> ;-)</li>
	        
	        		<li class='links_list'><a href='https://github.com/iancoleman/bip39' target='_blank'>Hardware / Software Wallet 24 Word Recovery Seed Generator</a> (USE OFFLINE WITH NO INTERNET CONNECTION FOR SAFETY!!!)</li>
	        
	        		<li class='links_list'><a href='https://sourceforge.net/projects/dfd-crypto-ticker/' target='_blank'>Raspberry PI Real-Time / Multi-Crypto Slideshow Price Ticker</a> (a side project of mine)</li>
		    
        			<li class='links_list'><a href='https://opentimestamps.org/' target='_blank'>Timestamp Proof-Of-Existence Of Files (FREE) With The Bitcoin Blockchain</a></li>
        			<!-- alternate if any issues occur: https://dgi.io/ots/#ots_stampverify -->
				
				
    			</ul>
			</fieldset>
			
			
		    
		    
</div> <!-- max_1200px_wrapper END -->



			
			