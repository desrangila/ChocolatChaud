<?php

//error_reporting(E_ALL);

include_once 'lib/config.php';

$user = User::fbc_getLoggedIn();
($user) ? $fb_active_session = $user->fbc_is_session_active() : $fb_active_session = FALSE;

if (!$user) {
	// DISPLAY PAGE WHEN USER IS NOT LOGGED IN TO FB CONNECT
	echo render_header($user);
	//echo render_logged_out_index();
	echo render_footer();
	exit;
} 

// USER CONNECTED TO APPLICATION
if ($user->fbc_is_facebook_user()) {
	// wake up
	if ($_POST["action"] == "lever" && $_POST["reveil"] != "") {
		// TODO ASAP: Add a protection to verify if it's a number 
		// PUBLISH STORY TO PROFILE FEED
		
		$timeZone = htmlentities($_POST["timeZone"]);
		// France et Quebec
		if($timeZone == 1 || $timeZone == -5) {
			// Temps du serveur + Zone du serveur en France + Zone de l'utilisateur + heure d'été
			$time = time() + (-2 + $timeZone  + 1) * 3600;
		} else {
			$time = time() + ($timeZone - 2) *3600;
		}
		$time = $time - 60 * $_POST["reveil"];
		
		$template_data = array(
			'time'=>date("H:i", $time));			
		$target_ids = array();
		$body_general = '';
		$publish_success = $user->fbc_publishFeedStory(idx($GLOBALS, 'feed_bundle_reveil_id'), $template_data);
		if ($publish_success) { $publish_result = "ok"; } else { $publish_result = "error"; }
	}
	
	if ($_POST["action"] == "coucher" && $_POST["coucher"] != "") {
		// TODO ASAP: Add a protection to verify if it's a number 
		// PUBLISH STORY TO PROFILE FEED
		$timeZone = htmlentities($_POST["timeZone"]);
		// France et Quebec
		if($timeZone == 1 || $timeZone == -5) {
			// Temps du serveur + Zone du serveur en France + Zone de l'utilisateur + heure d'été
			$time = time() + (-2 + $timeZone  + 1) * 3600;
		} else {
			$time = time() + ($timeZone - 2) *3600;
		}
		$time = $time + 60 * $_POST["coucher"];
		
		$template_data = array(
			'time'=>date("H:i", $time));			
		$target_ids = array();
		$body_general = '';
		$publish_success = $user->fbc_publishFeedStory(idx($GLOBALS, 'feed_bundle_coucher_id'), $template_data);
		if ($publish_success) { $publish_result = "ok"; } else { $publish_result = "error"; }
	}

	echo render_header($user, $publish_result);

	// POST FEED TO PROFILE
	if(!$publish_success) echo render_feed_form($user);
	
	echo render_footer();
} else {
	echo render_header($user);
	echo render_footer();
}

?>