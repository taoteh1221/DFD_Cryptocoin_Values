
    <!- footer START -->

            	
            	<div id="api_error_alert"><?=( $_SESSION['api_data_error'] ? $_SESSION['api_data_error'] . $_SESSION['cmc_error'] : $_SESSION['cmc_error'] )?></div>
            	
    <p align='center'><a href='https://github.com/taoteh1221/DFD_Cryptocoin_Values/releases' target='_blank' title='Download the latest version here.'>Github Releases (running v<?=$app_version?>)</a>
    

    <p align='center'><a id='donate' href='#show_donation_addresses' title='Click to show donation addresses.' onclick='return false;'>Donations Support Development</a></p>
    
            	<div style='display: none;' id='donate_div' align='center'>
            	
            	<b>Monero (XMR) Donation Address:</b> <br /><span class='long_linebreak'>47mWWjuwPFiPD6t2MaWcMEfejtQpMuz9oj5hJq18f7nvagcmoJwxudKHUppaWnTMPaMWshMWUTPAUX623KyEtukbSMdmpqu</span>
            	<br /><b>Monero Address QR Code (for phones)</b><br /><img src='xmr-donations-qr-code.png' border='0' />
            	<br /><br /><b>PayPal:</b> <br /><a href='https://www.paypal.me/dragonfrugal' target='_blank'>https://www.paypal.me/dragonfrugal</a>
            
            	</div>
     
    <?php
    
    // Calculate page load time
    $time = microtime();
    $time = explode(' ', $time);
    $time = $time[1] + $time[0];
    $finish = $time;
    $total_time = round(($finish - $start), 3);
    echo '<p align="center" style="color: '.( $total_time <= 10 ? 'green' : 'red' ).';"> Page generated in '.$total_time.' seconds. </p>';
    
    ?>
        
            </div>
        </div>
    </div>
     <br /> <br />
</body>
</html>
<!-- /*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */ -->
<?php
//var_dump($_SESSION['debugging_printout']);


if ( $proxy_alerts != 'none' ) {
	
	foreach ( $_SESSION['proxy_checkup'] as $problem_proxy ) {
	test_proxy($problem_proxy);
	sleep(1);
	}

}

error_logs();
session_destroy();
?>