<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



// Trim whitespace
if ( $_POST['hive_submitted'] ) {
$_POST = trim_array($_POST);
}

// Get HIVE value
$hive_market = asset_market_data('HIVE', 'bittrex', 'BTC-HIVE')['last_trade'];

?>
    

<div>
    <form action='<?=start_page('mining_calculators')?>' method='post'>
        
        <p><b>Power Down Period:</b> <?=$app_config['hive_powerdown_time']?> weeks</p>
	
        <p><b>HIVE Power Interest Rate:</b> <?=($app_config['hivepower_yearly_interest'])?> percent annually (see config file for yearly adjustments)</p>
	
        <p><b>HIVE Power Purchased:</b> <input type='text' name='hp_purchased' value='<?=$_POST['hp_purchased']?>' placeholder="(from Bittrex trading etc)" size='45' /></p>
        
        <p><b>HIVE Power Earned:</b> <input type='text' name='hp_earned' value='<?=$_POST['hp_earned']?>' placeholder="(voting, posting, mining)" size='45' /></p>
        
        <p><b>All HIVE Power:</b> <input type='text' name='hp_total' value='<?=$_POST['hp_total']?>' placeholder="(including interest)" size='45' /></p>
        
        <p><input type='submit' value='Calculate HIVE Interest / Power Down Weekly Payout Amounts Over Time' /></p>
        
				    <input type='hidden' value='1' name='hive_submitted' />
        
    </form>
</div>

<?php
if ( $_POST['hive_submitted'] ) {
?>

<p class='red' style='font-weight: bold;'>Your <i>current</i> HIVE Power interest rate results (<i><u><?=strtoupper($app_config['btc_primary_currency_pairing'])?> values may change significantly over long periods of time</u></i>):</p>

<p class='green' style='font-weight: bold;'>1 HIVE = <?=number_to_string($hive_market)?> BTC (<?=$app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']]?><?php echo number_format( number_to_string( $hive_market * $btc_primary_currency_value ), 8, '.', ','); ?>)</p>


<?php

hivepower_time('day');
hivepower_time('week');
hivepower_time('month');
hivepower_time('2month');
hivepower_time('3month');
hivepower_time('6month');
hivepower_time('9month');
hivepower_time('12month');


}
?>