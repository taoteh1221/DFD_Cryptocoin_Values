<?php
/*
 * Copyright 2014-2018 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
	

?>			
			<?php
				
				$minutes = ( $time / 60 );
				
				$hours = ( $minutes / 60 );
				
				$days = ( $hours / 24 );
				
				$months = ( $days / 30 );
				
				$years = ( $days / 365 );
				
				?>
				
				
		<!-- Green colored START -->
		<p style='color: green;'>
			
				<?php
				    if ( $minutes < 60 ) {
				    ?>
				    Minutes until block found: 
				    <?php
				    echo round($minutes, 2);
				    }
				
				    elseif ( $hours < 24 ) {
				    ?>
				    Hours until block found: 
				    <?php
				    echo round($hours, 2);
				    }
				
				    elseif ( $days < 30 ) {
				    ?>
				    Days until block found: 
				    <?php
				    echo round($days, 2);
				    }
				
				    elseif ( $days < 365 ) {
				    ?>
				    Months until block found: 
				    <?php
				    echo round($months, 2);
				    }
				    
				    else {
				    ?>
				    Years until block found: 
				    <?php
				    echo round($years, 2);
				    }
				
				$calculate_daily = ( 24 / $hours );
				
				$daily_average = ( $calculate_daily * ( get_trade_price($calculation_form_data[6], $calculation_form_data[7]) * trim($_POST['block_reward']) ) );
				
				?>
				
				