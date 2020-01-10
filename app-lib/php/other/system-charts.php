<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


	
// Have this script not load any code if system stats are not turned on, or key GET request corrupt
if ( $app_config['system_stats'] == 'off' || !is_numeric($_GET['key']) ) {
exit;
}

$key = $_GET['key'];
		
			// Have this script send the UI alert messages, and not load any chart code (to not leave the page endlessly loading) if cache data is not present
			if ( file_exists('cache/charts/system/archival/system_stats.dat') != 1 ) {
			?>
			
			$("#system_stats_chart_<?=$key?> span.loading").html(' &nbsp; No chart data activated for: System Statistics Chart #<?=$key?>');
			
			$("#system_charts_error").show();
			
			$("#system_charts_error").html('One or more charts could not be loaded. Please make sure you have a cron job running (see <a href="README.txt" target="_blank">README.txt</a> for how-to setup a cron job), or charts cannot be activated. Check app error logs too, for write errors (which would indicate improper cache directory permissions).');
			
			
			<?php
			exit;
			}
			
		
$chart_data = chart_data('cache/charts/system/archival/system_stats.dat', 'system');


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


// Sort array keys by lowest numeric value to highest
asort($chart_data);

//var_dump($chart_data); // DEBUGGING ONLY

		
if ( $key == 1 ) {
	
	$loop = 1;
	$counted = 0;
	foreach ( $chart_data as $chart_key => $chart_value ) {
		
		if ( $counted < 3 && $chart_key != 'time' ) {
		$counted = $counted + 1;
		
			// If there are no data retrieval errors
			// WE STILL COUNT THIS, SO LET COUNT RUN ABOVE
			if ( !preg_match("/NO_DATA/i", $chart_value, $matches) ) {
				
			?>
var <?=$chart_key?> = [<?=$chart_value?>];
			<?php
		
			$chart_config = "{
          text: '".name_rendering($chart_key)."',
          values: ".$chart_key.",
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
	
   $loop = $loop + 1;
	}

}
elseif ( $key == 2 ) {
	
	$loop = 1;
	$counted = 0;
	foreach ( $chart_data as $chart_key => $chart_value ) {
		
		if ( $counted >= 3 && $chart_key != 'time' ) {
		$counted = $counted + 1;
		
			// If there are no data retrieval errors
			// WE STILL COUNT THIS, SO LET COUNT RUN ABOVE
			if ( !preg_match("/NO_DATA/i", $chart_value, $matches) ) {
				
		?>
var <?=$chart_key?> = [<?=$chart_value?>];
		<?php
		
		$chart_config = "{
          text: '".name_rendering($chart_key)."',
          values: ".$chart_key.",
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
	
   $loop = $loop + 1;
	}

}

$chart_config = trim($chart_config);
$chart_config = rtrim($chart_config,',');
?>



var dates = [<?=$chart_data['time']?>];

let chartConfig_<?=$key?> = {
	
  graphset: [
    {
      type: 'line',
      borderColor: '#cccccc',
      borderRadius: '2px',
      borderWidth: '1px',
      title: {
        text: 'System Statistics Chart #<?=$key?>',
        adjustLayout: true,
        marginTop: '20px'
      },  
  		source: {
  		   text: "Select an area to zoom inside the chart itself, or use the zoom grab bars in the preview area (X and Y axis zooming are both supported).",
    		fontColor:"black",
	      fontSize: "13",
    		fontFamily: "Open Sans",
    		offsetX: 60,
    		offsetY: -1,
    		align: 'left'
  		},
      legend: {
        backgroundColor: 'transparent',
        borderWidth: '0px',
        draggable: true,
        header: {
          text: 'System Data (click to hide)',
      	 fontColor: "black",
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
        values: dates,
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
          text: 'System Data'
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
  		backgroundColor: "white",
      series: [
        <?php echo $chart_config . "\n" ?>
      ]
    }
  ],
  gui: {
    contextMenu: {
      alpha: 0.9,
      button: {
        visible: true
      },
      docked: true,
      item: {
        textAlpha: 1
      },
      position: 'right'
    }
  }
};
 
zingchart.TOUCHZOOM = 'pinch'; /* mobile compatibility */

zingchart.render({
  id: 'system_stats_chart_<?=$key?>',
  data: chartConfig_<?=$key?>
});




$("#system_stats_chart_<?=$key?> span").hide(); // Hide "Loading chart X..." after it loads
			

