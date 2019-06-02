<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

?>


<p><?=start_page_html('charts')?></p>			
			
<button class="show_chart_settings force_button_style">Show / Hide Charts</button>


<div id="show_chart_settings" style="display:none;">

	<h3>Show / Hide Charts</h3>
	
<?php
foreach ( $exchange_price_alerts as $key => $value ) {
?>
	<p><input type='checkbox' value='<?=$key?>' onchange='

	var show_charts = document.getElementById("show_charts").value;
	
		if ( this.checked == true ) {
		document.getElementById("show_charts").value = show_charts + this.value + ",";
		}
		else {
		document.getElementById("show_charts").value = show_charts.replace("<?=$key?>,", "");
		}
	
' <?=( in_array($key, $show_charts) ? 'checked' : '' )?> /> Show "<?=$key?>" chart</p>
<?php
}
?>

	<p><button class='force_button_style' onclick='javascript:document.coin_amounts.submit();'>Update Shown Charts</button></p>
	
	<p style='color: red;'>*Charts are hidden by default to increase page loading speed. You can persist showing charts between sessions by enabling "Use cookie data to save values between sessions" on the Settings page. <i>If you enable cookie data before showing your charts, your charts will stay visible all the time</i>.</p>

</div>


<script>
$('.show_chart_settings').modaal({
	content_source: '#show_chart_settings'
});
</script>


<br />
  
  
<p><a style='color: red; font-weight: bold;' class='show' id='chartsnotice' href='#show_chartsnotice' title='Click to show charts notice.' onclick='return false;'><b>Charts Notice</b></a></p>
    
<div style='display: none;' class='show_chartsnotice' align='left'>
            	
     
	<p style='font-weight: bold; color: red;'>Charts are only available to show for each price alert properly configured in config.php. Price alerts must be <a href='README.txt' target='_blank'>setup as a cron job on your web server</a>, or <i>the charts here will not work</i> (they will remain blank). The chart's tab, page, caching, and javascript can be disabled in config.php.
	
<br /><br />
	Charts are hidden by default to increase page loading speed. You can persist showing charts between sessions by enabling "Use cookie data to save values between sessions" on the Settings page. <i>If you enable cookie data before showing your charts, your charts will stay visible all the time</i>.</p>

            
</div>


<?php

// Render the charts
foreach ( $exchange_price_alerts as $key => $value ) {
	
	if ( in_array($key, $show_charts) ) {
?>

<div style='background-color: #515050; border: 1px solid #808080; border-radius: 5px;' id='<?=$key?>_chart'></div>
<script src='app-lib/js/chart.js.php?type=asset&asset_data=<?=urlencode($key)?>' async></script>
<br/><br/><br/>

<?php
	}
	
}
?>


