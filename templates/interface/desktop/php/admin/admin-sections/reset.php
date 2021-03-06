<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


?>

<div class='max_1200px_wrapper'>
	


	<!-- RESET API key START -->

	<div style='margin: 25px;'>
	
	<form name='reset_api' id='reset_api' action='' method='post'>
	
	<input type='hidden' name='admin_hashed_nonce' value='<?=$pt_gen->admin_hashed_nonce('reset_api_key')?>' />
	
	<input type='hidden' name='reset_api_key' value='1' />
	
	</form>
	
	<!-- Submit button must be OUTSIDE form tags here, or it runs improperly -->
	<button id='reset_api_button' class='force_button_style' onclick='
	
	var int_api_key_reset = confirm("Resetting the API key will stop any external \napps from accessing the Internal API with the current key. \n\nPress OK to reset the API key, or CANCEL to keep the current API key. ");
	
		if ( int_api_key_reset ) {
		document.getElementById("reset_api_button").innerHTML = ajax_placeholder(15, "center", "Submitting...");
		document.getElementById("reset_api_button").disable = true;
		$("#reset_api").submit(); // Triggers "app reloading" sequence
		}
	
	'>Reset API Key</button>
	
	</div>
				
	<!-- RESET API key END -->


	<!-- RESET webhook key START -->

	<div style='margin: 25px;'>
	
	<form name='reset_webhook' id='reset_webhook' action='' method='post'>
	
	<input type='hidden' name='admin_hashed_nonce' value='<?=$pt_gen->admin_hashed_nonce('reset_webhook_key')?>' />
	
	<input type='hidden' name='reset_webhook_key' value='1' />
	
	</form>
	
	<!-- Submit button must be OUTSIDE form tags here, or it runs improperly -->
	<button id='reset_webhook_button' class='force_button_style' onclick='
	
	var webhook_key_reset = confirm("Resetting the webhook secret key will stop \nany external apps from accessing webhooks \nwith their webhook app key. \n\nPress OK to reset the webhook secret key, or CANCEL to keep the current webhook secret key. ");
	
		if ( webhook_key_reset ) {
		document.getElementById("reset_webhook_button").innerHTML = ajax_placeholder(15, "center", "Submitting...");
		document.getElementById("reset_webhook_button").disable = true;
		$("#reset_webhook").submit(); // Triggers "app reloading" sequence
		}
	
	'>Reset Webhook Keys</button>
	
	</div>
				
	<!-- RESET webhook key END -->
	
	
			    
</div> <!-- max_1200px_wrapper END -->




		    