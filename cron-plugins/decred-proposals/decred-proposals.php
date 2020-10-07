<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/CRON-PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################

// Get current unvoted proposals
$unvoted_proposals = json_decode( trim( file_get_contents($base_dir . '/cache/vars/decred-unvoted-proposals.dat') ) , TRUE);

if ( !is_array($unvoted_proposals) ) {
$unvoted_proposals = array();
}


///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////


// VOTING STARTED DETECTION

$voting_proposals = array();

foreach ($unvoted_proposals as $key => $value) {

$json_string = 'https://explorer.dcrdata.org/api/proposal/' . $value;

$jsondata = @external_api_data('url', $json_string, 360); // Re-cache every 6 hours (360 minutes)
     
$data = json_decode($jsondata, true);

	if ( sizeof($data['time']) > 0 ) {
	$voting_proposals[] = $value;
	unset($unvoted_proposals[$key]);
	}

}


// Updating / alerting

if ( sizeof($voting_proposals) > 0 ) {

$voting_proposals_message = sizeof($voting_proposals) . " Decred proposal(s) are now being voted on...";

	foreach ( $voting_proposals as $proposal ) {
	$voting_proposal_urls .= 'https://proposals.decred.org/proposals/' . substr($proposal,0,7) . "\n\n";
	}


  				// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
  				
  				// Minimize function calls
  				$encoded_text_message = content_data_encoding($voting_proposals_message . "\n\n" . $voting_proposal_urls); // Unicode support included for text messages (emojis / asian characters / etc )
  				
          	$send_params = array(
          								'notifyme' => $voting_proposals_message,
          								'telegram' => $voting_proposals_message . "\n\n" . $voting_proposal_urls,
          								'text' => array(
          														'message' => $encoded_text_message['content_output'],
          														'charset' => $encoded_text_message['charset']
          														),
          								'email' => array(
          														'subject' => $voting_proposals_message,
          														'message' => $voting_proposals_message . "\n\n" . $voting_proposal_urls
          														)
          								);
          	
          	
          	
// Send notifications
@queue_notifications($send_params);

// Update unvoted proposals data with now-voting proposals removed
store_file_contents($base_dir . '/cache/vars/decred-unvoted-proposals.dat', json_encode($unvoted_proposals, JSON_PRETTY_PRINT) );

}


///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////


// NEW PROPOSAL DETECTION

$new_proposals = array();

$json_string = 'https://api.github.com/repos/decred-proposals/mainnet/commits';

$jsondata = @external_api_data('url', $json_string, 60); // Re-cache every 60 minutes
     
$data = json_decode($jsondata, true);


if (is_array($data) || is_object($data)) {
  	
	foreach ($data as $key => $value) {
	
		if ( preg_match("/^add record /i", $value['commit']['message']) ) {
			
		$parse_token = $value['commit']['message'];
		$parse_token = preg_replace("/add record /i", "", $parse_token);
		
			if ( !in_array($parse_token, $unvoted_proposals) ) {
			$new_proposals[] = $parse_token;
			}
		
		}
	
	}

}


// Updating / alerting

if ( sizeof($new_proposals) > 0 ) {

$unvoted_proposals = array_merge($unvoted_proposals, $new_proposals);

$new_proposals_message = sizeof($new_proposals) . " new Decred proposal(s) have been detected...";

	foreach ( $new_proposals as $proposal ) {
	$new_proposal_urls .= 'https://proposals.decred.org/proposals/' . substr($proposal,0,7) . "\n\n";
	}


  				// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
  				
  				// Minimize function calls
  				$encoded_text_message = content_data_encoding($new_proposals_message . "\n\n" . $new_proposal_urls); // Unicode support included for text messages (emojis / asian characters / etc )
  				
          	$send_params = array(
          								'notifyme' => $new_proposals_message,
          								'telegram' => $new_proposals_message . "\n\n" . $new_proposal_urls,
          								'text' => array(
          														'message' => $encoded_text_message['content_output'],
          														'charset' => $encoded_text_message['charset']
          														),
          								'email' => array(
          														'subject' => $new_proposals_message,
          														'message' => $new_proposals_message . "\n\n" . $new_proposal_urls
          														)
          								);
          	
          	
          	
// Send notifications
@queue_notifications($send_params);

// Store new proposals
store_file_contents($base_dir . '/cache/vars/decred-unvoted-proposals.dat', json_encode($unvoted_proposals, JSON_PRETTY_PRINT) );

}

?>


