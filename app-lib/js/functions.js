
// Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com

/////////////////////////////////////////////////////////////

function delete_cookie( name ) {
  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

/////////////////////////////////////////////////////////////

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

/////////////////////////////////////////////////////////////

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}


/////////////////////////////////////////////////////////////

function auto_reload(time) {
	
	if ( window.reload_function ) {
	clearInterval(window.reload_function);
	}
	
	if ( time >= 1 ) {
		
	count_down(time, 1);
		
		if ( document.getElementById("set_use_cookies").checked == false ) {
		alert('To use auto-refresh effectively, enable "Save coin values as cookie data" in "Program Settings". Otherwise you likely will be prompted to manually re-submit form data every auto-refresh.');
		}

	
	
	setCookie("coin_reload", time, 365);
	
	
	window.reload_function = setInterval(function() {
				location.reload(true);
				}, (time * 1000));
		
	
	
	}
	else {
	count_down(time, 0);
	setCookie("coin_reload", '', 365);
	}

}

/////////////////////////////////////////////////////////////

function count_down(i, toggle) {
	
	if ( window.reload_countdown ) {
	clearInterval(window.reload_countdown);
	}

	if ( toggle == 1 ) {
		
			
	    window.reload_countdown = setInterval(function () {
	    	
				    	
	    	
	    	if ( i >= 60 ) {
	    	
	    	var round_min = Math.floor(i/60);
	    	
	    	var sec = ( i - (round_min*60) );
	    	
	    	document.getElementById("reload_countdown").innerHTML = "(" + round_min + " minutes " + sec + " seconds remaining)";
	    	
	    	}
	    	else {
	    	document.getElementById("reload_countdown").innerHTML = "(" + i + " seconds remaining)";
	    	}

		
		
		i-- || clearInterval(int);  //if i is 0, then stop the interval
	    }, 1000);
	    
	  
	    
	}
	else {
	document.getElementById("reload_countdown").innerHTML = "";
	}
    
}

/////////////////////////////////////////////////////////////

function play_alert(tr_id, alert_type, color) {

    
    $( document ).ready(function() {
      
	if ( color == 'blue' ) {
	window.alert_color = '#e5f1ff'; // Assets with CMC data not set or functioning properly
	}
	else if ( color == 'yellow' && !window.alert_color ) {
	window.alert_color = '#efd362';
	}
	else if ( color == 'green' && !window.alert_color ) {
	window.alert_color = '#81c185';
	}
      
      
      $('.tablesorter tr#' + tr_id).css("background", window.alert_color);
      $('.tablesorter tr#' + tr_id + ' td').css("background", window.alert_color);
      $('.tablesorter tr#' + tr_id).css("background-color", window.alert_color);
      $('.tablesorter tr#' + tr_id + ' td').css("background-color", window.alert_color);
      
      // Zebra stripes
	
	if ( color == 'blue' ) {
	window.alert_color = ''; // Assets with CMC data not set or functioning properly
	}
	else if ( window.alert_color == '#efd362' ) {
	window.alert_color = '#d3bb5b';
	}
	else if ( window.alert_color == '#d3bb5b' ) {
	window.alert_color = '#efd362';
	}
	else if ( window.alert_color == '#81c185' ) {
	window.alert_color = '#abfcaf';
	}
	else if ( window.alert_color == '#abfcaf' ) {
	window.alert_color = '#81c185';
	}
      
	
	
	if ( !window.is_alerted && alert_type == 'visual_audio' ) {
	  
	  document.getElementById('audio_alert').play();
	  
	window.is_alerted = 1;
	}


    
    });
    

}

/////////////////////////////////////////////////////////////