<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function string_to_array($string) {

$string = explode("||",$string);

return $string;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function strip_formatting($price) {

$price = preg_replace("/ /", "", $price); // Space
$price = preg_replace("/,/", "", $price); // Comma
$price = preg_replace("/  /", "", $price); // Tab

return $price;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function trim_array($data) {

   foreach ( $data as $key => $value ) {
   $data[$key] = trim(strip_formatting($value));
   }
        
return $data;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function remove_number_format($text) {

$text = str_replace("    ", '', $text);
$text = str_replace(" ", '', $text);
$text = str_replace(",", "", $text);
$text = trim($text);

return float_to_string($text);

}

////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function random_array_var($array) {

$rand = array_rand($array);

return $array[$rand];

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function text_number($string) {

$string = explode("||",$string);

$number = trim($string[0]);

return $number;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function regex_compat_url($url) {
	
$regex_url = trim($url);

$regex_url = preg_replace("/(http|https|ftp|tcp|ssl):\/\//i", "", $regex_url);

$regex_url = preg_replace("/\//i", "\/", $regex_url);

return $regex_url;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function cleanup_string($string, $mode) {

	// Upper or lower case
	if ( $mode == 'lower' ) {
	$string = strtolower($string);
	}
	elseif ( $mode == 'upper' ) {
	$string = strtoupper($string);
	}

// Remove all whitespace
$string = preg_replace('/\s/', '', $string);

return $string;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function delimited_string_sample($string, $delimiter, $position, $charset='utf-8') {
	
	if ( $position == 'first' ) {
	$result = substr($string, 0, mb_strpos($string, $delimiter, 0, $charset) );
	}
	elseif ( $position == 'last' ) {
	$result = array_pop( explode(',', $string) );
	}

return $result;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function snake_case_to_name($string) {


// Uppercase every word, and remove underscore between them
$string = ucwords(preg_replace("/_/i", " ", $string));


// Pretty up the individual words as needed
$words = explode(" ",$string);

	foreach($words as $key => $value) {
	
		if ( $value == 'Us' ) {
		$words[$key] = strtoupper($value); // All uppercase US
		}
	
	$pretty_string .= $words[$key] . ' ';
	}

$pretty_string = preg_replace("/btc/i", 'BTC', $pretty_string);
$pretty_string = preg_replace("/coin/i", 'Coin', $pretty_string);
$pretty_string = preg_replace("/bitcoin/i", 'Bitcoin', $pretty_string);
$pretty_string = preg_replace("/exchange/i", 'Exchange', $pretty_string);
$pretty_string = preg_replace("/market/i", 'Market', $pretty_string);
$pretty_string = preg_replace("/forex/i", 'Forex', $pretty_string);
$pretty_string = preg_replace("/finex/i", 'Finex', $pretty_string);
$pretty_string = preg_replace("/stamp/i", 'Stamp', $pretty_string);
$pretty_string = preg_replace("/flyer/i", 'Flyer', $pretty_string);
$pretty_string = preg_replace("/panda/i", 'Panda', $pretty_string);

return trim($pretty_string);


}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


// Always display very large / small numbers in non-scientific format
// Also removes any leading and trailing zeros for efficient storage / UX / etc
function float_to_string($val) {

global $app_config;

// Trim any whitespace off the ends
$val = trim($val);


	// Covert scientific notation to a normal value / string
    
	// MUST ALLOW MAXIMUM OF 9 DECIMALS, TO COUNT WATCH-ONLY ASSETS
	// (ANYTHING OVER 9 DECIMALS SHOULD BE AVOIDED FOR UX)
   $detect_decimals = (string)$val;
   if ( preg_match('~\.(\d+)E([+-])?(\d+)~', $detect_decimals, $matches) ) {
   $decimals = $matches[2] === '-' ? strlen($matches[1]) + $matches[3] : 0;
   }
   else {
   $decimals = mb_strpos( strrev($detect_decimals) , '.', 0, 'utf-8');
   }
    
	if ( $decimals > 9 ) {
	$decimals = 9;
	}
   
   $val = number_format($val, $decimals, '.', '');


	// Remove TRAILING zeros ie. 140.00000 becomes 140.
	// (ONLY IF DECIMAL PLACE EXISTS)
	if ( preg_match("/\./", $val) ) {
	$val = rtrim($val, '0');
	}


	// Remove any extra LEADING zeros 
	// IF less than 1.00
	if ( $val < 1 ) {
	$val = preg_replace("/(.*)00\./", "0.", $val);
	}
	// IF greater than or equal to 1.00
	elseif ( $val >= 1 ) {
	$val = ltrim($val, '0');
	}
	

// Remove decimal point if an integer ie. 140. becomes 140
$val = rtrim($val, '.');
	
	
	// Always at least return zero
	if ( $val >= 0.000000001 ) {
	return $val;
	}
	else {
	return 0;
	}


}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function pretty_numbers($value_to_pretty, $num_decimals, $small_unlimited=false) {

global $app_config;

// Pretty number formatting, while maintaining decimals


// Strip formatting, convert from scientific format, and remove leading / trailing zeros
$raw_value_to_pretty = remove_number_format($value_to_pretty);

// Do any rounding that may be needed now (skip WATCH-ONLY 9 decimal values)
if ( float_to_string($raw_value_to_pretty) > 0.00000000 && $small_unlimited != TRUE ) { 
$raw_value_to_pretty = number_format($raw_value_to_pretty, $num_decimals, '.', '');
}

// AFTER ROUNDING, RE-PROCESS removing leading / trailing zeros
$raw_value_to_pretty = float_to_string($raw_value_to_pretty);
	    
	    
	    // Pretty things up...
	    
	    
	    	if ( preg_match("/\./", $raw_value_to_pretty) ) {
	    	$value_no_decimal = preg_replace("/\.(.*)/", "", $raw_value_to_pretty);
	    	$decimal_amount = preg_replace("/(.*)\./", "", $raw_value_to_pretty);
	    	$check_decimal_amount = '0.' . $decimal_amount;
	    	}
	    	else {
	    	$value_no_decimal = $raw_value_to_pretty;
	    	$decimal_amount = null;
	    	$check_decimal_amount = null;
	    	}
	    	
	    	
	    // Limit $decimal_amount to $num_decimals (unless it's a watch-only asset)
	    if ( $raw_value_to_pretty != 0.000000001 ) {
	    $decimal_amount = ( iconv_strlen($decimal_amount, 'utf-8') > $num_decimals ? substr($decimal_amount, 0, $num_decimals) : $decimal_amount );
	    }
	    
	    	
	    	// Show EVEN IF LOW VALUE IS OFF THE MAP, just for UX purposes (tracking token price only, etc)
	    	if ( float_to_string($raw_value_to_pretty) > 0.00000000 && $small_unlimited == true ) {  
	    		
	    		if ( $num_decimals == 2 ) {
	    		$value_to_pretty = number_format($raw_value_to_pretty, 2, '.', ',');
	    		}
	    		else {
				// $value_no_decimal stops rounding, while number_format gives us pretty numbers left of decimal
	    		$value_to_pretty = number_format($value_no_decimal, 0, '.', ',') . ( float_to_string($check_decimal_amount) > 0.00000000 ? '.' . $decimal_amount : '' );
	    		}
	    	
	    	}
	    	// Show low value only with $decimal_amount minimum
	    	elseif ( float_to_string($raw_value_to_pretty) >= 0.00000001 && $small_unlimited == false ) {  
	    		
	    		if ( $num_decimals == 2 ) {
	    		$value_to_pretty = number_format($raw_value_to_pretty, 2, '.', ',');
	    		}
	    		else {
				// $value_no_decimal stops rounding, while number_format gives us pretty numbers left of decimal
	    		$value_to_pretty = number_format($value_no_decimal, 0, '.', ',') . ( float_to_string($check_decimal_amount) > 0.00000000 ? '.' . $decimal_amount : '' );
	    		}
	    	
	    	}
	    	else {
	    	$value_to_pretty = 0;
	    	}
	    	
	    	
	    
return $value_to_pretty;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////



?>