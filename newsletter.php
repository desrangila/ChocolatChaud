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

echo render_header($user, "newsletter");

echo render_feed_form($user, "newsletter");

echo render_footer();

?>