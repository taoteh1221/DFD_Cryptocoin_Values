<?php
/*
 * Copyright 2014-2021 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


	// Have this script not load any code if system stats are not turned on, or key GET request corrupt
	if ( !isset($_SESSION['admin_logged_in']) || !is_numeric($_GET['key']) ) {
	exit;
	}

$key = $_GET['key'];

$x_coord = 70; // Start position (absolute) for lite chart links
			
		
// Have this script send the UI alert messages, and not load any chart code (to not leave the page endlessly loading) if cache data is not present
if ( !file_exists('cache/charts/system/lite/' . $_GET['days'] . '_days/system_stats.dat') ) {
?>
			
{

gui: {
    contextMenu: {
      customItems: [
        {
          text: 'PRIVACY ALERT!',
          function: 'zingAlert()',
          id: 'showAlert'
        }
      ],
      alpha: 0.9,
      button: {
        visible: true
      },
      docked: true,
      item: {
        textAlpha: 1
      },
      position: 'left'
    },
    behaviors: [
      {
        id: 'showAlert',
        enabled: 'all'
      }
    ]
},
   type: "area",
   noData: {
     text: "No data for the <?=$_GET['days']?> day chart yet, please check back in awhile.",
  	  fontColor: "black",
     backgroundColor: "#808080",
     fontSize: 20,
     textAlpha: .9,
     alpha: .6,
     bold: true
   },
  	backgroundColor: "#f2f2f2",
  	height: 420,
  	x: 0, 
  	y: 0,
  	title: {
        text: 'System Chart #<?=$key?>',
        adjustLayout: true,
    	  align: 'right',
    	  offsetX: -20,
    	  offsetY: 9
  	},
   series: [{
     values: []
   }],
	labels: [
	<?php
	foreach ($app_config['power_user']['lite_chart_day_intervals'] as $lite_chart_days) {
		
		if ( $lite_chart_days == 'all' ) {
		$lite_chart_text = strtoupper($lite_chart_days);
		}
		elseif ( $lite_chart_days == 7 ) {
		$lite_chart_text = '1W';
		}
		elseif ( $lite_chart_days == 14 ) {
		$lite_chart_text = '2W';
		}
		elseif ( $lite_chart_days == 30 ) {
		$lite_chart_text = '1M';
		}
		elseif ( $lite_chart_days == 60 ) {
		$lite_chart_text = '2M';
		}
		elseif ( $lite_chart_days == 90 ) {
		$lite_chart_text = '3M';
		}
		elseif ( $lite_chart_days == 180 ) {
		$lite_chart_text = '6M';
		}
		elseif ( $lite_chart_days == 365 ) {
		$lite_chart_text = '1Y';
		}
		elseif ( $lite_chart_days == 730 ) {
		$lite_chart_text = '2Y';
		}
		elseif ( $lite_chart_days == 1095 ) {
		$lite_chart_text = '3Y';
		}
		elseif ( $lite_chart_days == 1460 ) {
		$lite_chart_text = '4Y';
		}
		else {
		$lite_chart_text = $lite_chart_days . 'D';
		}
	?>
		{
	    x: <?=$x_coord?>,
	    y: 12,
	    id: '<?=$lite_chart_days?>',
	    fontColor: "<?=($_GET['days'] == $lite_chart_days ? 'black' : '#9b9b9b' )?>",
	    fontSize: "22",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "<?=$lite_chart_text?>"
	  	},
	<?php
	
		// Account for more / less digits with absolute positioning
		// Take into account INCREASE OR DECREASE of characters in $lite_chart_text
		if ( strlen($last_lite_chart_text) > 0 && strlen($last_lite_chart_text) != strlen($lite_chart_text) ) {
		$difference = $difference + ( strlen($lite_chart_text) - strlen($last_lite_chart_text) ); 
		$x_coord = $x_coord + ( $difference * $font_width ); 
		}
		elseif ( isset($difference) ) {
		$x_coord = $x_coord + ( $difference * $font_width ); 
		}
	
	$x_coord = $x_coord + $link_spacer;
	$last_lite_chart_text = $lite_chart_text;
	}
	?>
	]
        
}
			
<?php
exit;
}
			
		
$chart_data = chart_data('cache/charts/system/lite/' . $_GET['days'] . '_days/system_stats.dat', 'system');

// Colors for different data in charts
$color_array = array(
							'blank',
							'#29A2CC',
							'#209910',
							'#1d4ba5',
							'#48ad9e',
							'#D31E1E',
							'#a73aad',
							'#bc5210',
							);



// Determine how many data sensors to include in first chart
$num_in_first_chart = 0;
foreach ( $chart_data as $chart_key => $chart_value ) {

// Average for first / last value
//$check_chart_value = number_to_string( delimited_string_sample($chart_value, ',', 'first') + delimited_string_sample($chart_value, ',', 'last') / 2 );
// Just last value
$check_chart_value = number_to_string( delimited_string_sample($chart_value, ',', 'last') );
	
	// Include load average no matter what (it can be zero on a low-load setup, and should be supported by nearly every linux system?)
	// Also always include free disk space (WE WANT TO KNOW IF IT'S ZERO)
	if ( $chart_key != 'time' && $check_chart_value != 'NO_DATA' && $check_chart_value > 0.000000 || $chart_key == 'load_average_15_minutes' || $chart_key == 'free_disk_space_terabtyes' ) {
		
	$check_chart_value_key = $check_chart_value * 100000000; // To RELIABLY sort integers AND decimals, via ksort()
		
	$sorted_by_last_chart_data[number_to_string($check_chart_value_key)] = array($chart_key => $chart_value);
	
		if ( number_to_string($check_chart_value) <= number_to_string($app_config['power_user']['system_stats_first_chart_highest_value']) ) {
		$num_in_first_chart = $num_in_first_chart + 1;
		//echo $check_chart_value . ' --- '; // DEBUGGING ONLY
		}
	
	}
	
}


// Sort array keys by lowest numeric value to highest 
// (newest/last chart sensors data sorts lowest value to highest, for populating the 2 shared charts)
ksort($sorted_by_last_chart_data);

//var_dump($sorted_by_last_chart_data); // DEBUGGING ONLY

// Render chart data
if ( $key == 1 ) {
	
	$loop = 1;
	$counted = 0;
	foreach ( $sorted_by_last_chart_data as $chart_array ) {
		
		foreach ( $chart_array as $chart_key => $chart_value ) {
		
			if ( $counted < $num_in_first_chart && $chart_key != 'time' ) {
			$counted = $counted + 1;
			
				// If there are no data retrieval errors
				// WE STILL COUNT THIS, SO LET COUNT RUN ABOVE
				if ( !preg_match("/NO_DATA/i", $chart_value, $matches) ) {
					
				$chart_config = "{
			  text: '".snake_case_to_name($chart_key)."',
			  values: [".$chart_value."],
			  lineColor: '".$color_array[$counted]."',
				 marker: {
			 backgroundColor: '".$color_array[$counted]."',
			 borderColor: '".$color_array[$counted]."'
				 },
			  legendItem: {
				  fontColor: 'white',
			   fontSize: 20,
			   fontFamily: 'Open Sans',
				backgroundColor: '".$color_array[$counted]."',
				borderRadius: '2px'
			  }
			},
			" . $chart_config;
			
				}
			
			}
	
		}
		
   $loop = $loop + 1;
	}

}
elseif ( $key == 2 ) {
	
	$loop = 1;
	$counted = 0;
	foreach ( $sorted_by_last_chart_data as $chart_array ) {
		
		foreach ( $chart_array as $chart_key => $chart_value ) {
		
			if ( $counted >= $num_in_first_chart && $chart_key != 'time' ) {
			$counted = $counted + 1;
			
				// If there are no data retrieval errors
				// WE STILL COUNT THIS, SO LET COUNT RUN ABOVE
				if ( !preg_match("/NO_DATA/i", $chart_value, $matches) ) {
					
			$chart_config = "{
			  text: '".snake_case_to_name($chart_key)."',
			  values: [".$chart_value."],
			  lineColor: '".$color_array[$counted]."',
				 marker: {
			 backgroundColor: '".$color_array[$counted]."',
			 borderColor: '".$color_array[$counted]."'
				 },
			  legendItem: {
				  fontColor: 'white',
			   fontSize: 20,
			   fontFamily: 'Open Sans',
				backgroundColor: '".$color_array[$counted]."',
				borderRadius: '2px'
			  }
			},
			" . $chart_config;
				
				}
		  
			}
			elseif ( $chart_key != 'time' ) {
			$counted = $counted + 1;
			}
	
		}
		
   $loop = $loop + 1;
	}

}

$chart_config = trim($chart_config);
$chart_config = rtrim($chart_config,',');

header('Content-type: text/html; charset=' . $app_config['developer']['charset_default']);

?>

{ 

gui: {
    contextMenu: {
      customItems: [
        {
          text: 'PRIVACY ALERT!',
          function: 'zingAlert()',
          id: 'showAlert'
        }
      ],
      alpha: 0.9,
      button: {
        visible: true
      },
      docked: true,
      item: {
        textAlpha: 1
      },
      position: 'left'
    },
    behaviors: [
      {
        id: 'showAlert',
        enabled: 'all'
      }
    ]
},
   
   graphset: [
    {
      type: 'line',
      borderColor: '#cccccc',
      borderRadius: '2px',
      borderWidth: '1px',
      title: {
        text: 'System Chart #<?=$key?>',
        adjustLayout: true,
    	  align: 'right',
    	  offsetX: -20,
    	  offsetY: 9
      },  
  		source: {
  		   text: "Select area to zoom in chart, or use zoom grab bars in preview area (X and Y axis zooming supported).",
    		fontColor:"black",
	      fontSize: "13",
    		fontFamily: "Open Sans",
    		offsetX: 60,
    		offsetY: -2,
    		align: 'left'
  		},
      legend: {
        backgroundColor: 'transparent',
        borderWidth: '0px',
    	  offsetX: -40,
    	  offsetY: -20,
        draggable: false,
        header: {
          text: 'Hide / Show',
    		 offsetX: -8,
    	    offsetY: -20,
      	 fontColor: "blue",
	 		 fontSize: "20",
      	 fontFamily: "Open Sans",
        },
        item: {
          margin: '5 17 2 0',
          padding: '3 3 3 3',
          cursor: 'hand',
          fontColor: '#fff'
        },
        marker: {
          visible: false
        },
        verticalAlign: 'middle'
      },
      plot: {
    		marker:{
      		visible: false
    		},
    		tooltip: {
    			fontSize: 20
    		}
      },
      plotarea: {
        margin: 'dynamic'
      },
      scaleX: {
        guide: {
      	visible: true,
     		lineStyle: 'solid',
      	lineColor: "#444444"
        },
        values: [<?=$chart_data['time']?>],
        transform: {
 	     type: 'date',
 	     all: '%Y/%m/%d<br />%g:%i%a'
        },
        zooming: true
      },
      scaleY: {
        guide: {
      	visible: true,
     		lineStyle: 'solid',
      	lineColor: "#444444"
        },
        label: {
          text: 'Telemetry'
        },
    	zooming: true
      },
      crosshairX: {
    	  exact: true,
        lineColor: '#555',
        marker: {
          borderColor: '#fff',
          borderWidth: '1px',
          size: '5px'
        },
        plotLabel: {
      	 backgroundColor: "white",
      	 fontColor: "black",
	 		 fontSize: "20",
      	 fontFamily: "Open Sans",
          borderRadius: '2px',
          borderWidth: '2px',
          multiple: true
        },
    	  scaleLabel:{
   	  	 alpha: 1.0,
    	    fontColor: "black",
      	 fontSize: 20,
      	 fontFamily: "Open Sans",
      	 backgroundColor: "white",
   	  }
      },
      crosshairY: {
    	  exact: true
      },
      tooltip: {
        visible: false
      },
  		"preview":{
  				label: {
   		   color: 'black',
  		    	fontSize: '10px',
  		    	lineWidth: '1px',
   		   lineColor: '#444444',
  		   	},
 			  live: true,
 			  "adjust-layout": true,
 			  "alpha-area": 0.5
 		},
  		backgroundColor: "#f2f2f2",
      series: [
        <?php echo $chart_config . "\n" ?>
      ],
		labels: [
	<?php
	foreach ($app_config['power_user']['lite_chart_day_intervals'] as $lite_chart_days) {
		
		if ( $lite_chart_days == 'all' ) {
		$lite_chart_text = strtoupper($lite_chart_days);
		}
		elseif ( $lite_chart_days == 7 ) {
		$lite_chart_text = '1W';
		}
		elseif ( $lite_chart_days == 14 ) {
		$lite_chart_text = '2W';
		}
		elseif ( $lite_chart_days == 30 ) {
		$lite_chart_text = '1M';
		}
		elseif ( $lite_chart_days == 60 ) {
		$lite_chart_text = '2M';
		}
		elseif ( $lite_chart_days == 90 ) {
		$lite_chart_text = '3M';
		}
		elseif ( $lite_chart_days == 180 ) {
		$lite_chart_text = '6M';
		}
		elseif ( $lite_chart_days == 365 ) {
		$lite_chart_text = '1Y';
		}
		elseif ( $lite_chart_days == 730 ) {
		$lite_chart_text = '2Y';
		}
		elseif ( $lite_chart_days == 1095 ) {
		$lite_chart_text = '3Y';
		}
		elseif ( $lite_chart_days == 1460 ) {
		$lite_chart_text = '4Y';
		}
		else {
		$lite_chart_text = $lite_chart_days . 'D';
		}
	?>
		{
	    x: <?=$x_coord?>,
	    y: 12,
	    id: '<?=$lite_chart_days?>',
	    fontColor: "<?=($_GET['days'] == $lite_chart_days ? 'black' : '#9b9b9b' )?>",
	    fontSize: "22",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "<?=$lite_chart_text?>"
	  	},
	<?php
	
		// Account for more / less digits with absolute positioning
		// Take into account INCREASE OR DECREASE of characters in $lite_chart_text
		if ( strlen($last_lite_chart_text) > 0 && strlen($last_lite_chart_text) != strlen($lite_chart_text) ) {
		$difference = $difference + ( strlen($lite_chart_text) - strlen($last_lite_chart_text) ); 
		$x_coord = $x_coord + ( $difference * $font_width ); 
		}
		elseif ( isset($difference) ) {
		$x_coord = $x_coord + ( $difference * $font_width ); 
		}
	
	$x_coord = $x_coord + $link_spacer;
	$last_lite_chart_text = $lite_chart_text;
	}
	?>
	]
    }
  ]
  
}

<?php
?>