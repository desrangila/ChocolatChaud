<?php

/*
 *   SAMPLE PRESENTATION LAYER FUNCTIONS TO WORK WITH FB CONNECT
 */

/*   FUNCTION OVERVIEW
 *   prevent_cache_headers()						PREVENTS BROWSER FROM CACHING
 *   render_header()								RENDERS HEADER AND BODY TAGS
 *   onloadRegister($js)							JAVASCRIPT TO INCLUDE WHEN PAGE HAS LOADED
 *   render_footer()								RENDERS FOOTER INCLUDING FB CONNECT JS INCLUDE
 *   render_logged_out_index()						RENDERS PAGE WHEN USER HAS LOGGED OUT
 *	 render_status($user, $status_result)			RENDERS USERS PROFILE STATUS
 *   render_feed_form($user, $publish_result='')	RENDERS FORM FOR SUBMITTING FEED STORIES
 *   showing_results($row_start, $num_rows, $max_rows, $label)
 *   render_friends_table_html($friend, $row_start, $num_rows, $class_namespace='', $title='')		RENDERS TABLE OF FRIENDS USING HTML APPROACHs
 *   render_friends_table_xfbml($friend, $row_start, $num_rows, $class_namespace='', $title='')		RENDERS TABLE OF FRIENDS USING XFBML
 *   render_connect_invite_link($has_existing_friends = false)	RENDERS CONNECT INVITE LINK FOR FRIENDS
 *   render_error($msg)								RENDERS ERROR MESSAGE
 */

/*
 * Prevent caching of pages. When the Javascript needs to refresh a page,
 * it wants to actually refresh it, so we want to prevent the browser from
 * caching them.
 */

function prevent_cache_headers() {
	header('Cache-Control: private, no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
	header('Pragma: no-cache');
}

/*
 * Show the header that goes at the top of each page.
 */

function render_header($user=null, $publish_result='') {
	// Might want to serve this out of a canvas sometimes to test
	// out fbml, so if so then don't serve the JS stuff
	if (isset($_POST['fb_sig_in_canvas'])) {
		return;
	}

	prevent_cache_headers();
	
	if ($publish_result == "ok") $title = "Mise à jour du profil Facebook";
	else if ($publish_result == "error") $title = "Erreur";
	else if ($user) $title = "Bienvenue";
	else $title = "Bienvenue";

	$html = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

		<title>Chocolat Chaud &#183; ' . $title . '</title>
		<link rel="stylesheet" href="' . CONNECT_CSS_PATH . 'screen.css" type="text/css" media="screen" title="no title" charset="utf-8">
		</head>
		<body class=';
		if ($user) $html.= '"logged"';
		else $html .= '"home"';
	$html .= '		
		>
			<div id="container">
				<div id="header">
					<h1><a href="/" class="h1">Chocolat Chaud</a></h1>
					<h3>Dites “Bonjour” et “Bonne nuit” à vos amis !</h3>
				</div>
	';

	if (is_fbconnect_enabled()) {
		ensure_loaded_on_correct_url();
	}
	
	// Virer la barre facebook
	$html .= '
		<script type="text/javascript">			if (top.location != location) {				top.location.href = document.location.href;			}		</script>
	';
	
	
	$html .= '
		<div id="content">
			<div id="content-inner">
		';
	// Feed sent
	if ($publish_result == "ok") {
		$html .= 
			'<h2>Votre profil Facebook a été mis à jour avec succès !</h2>				
			<p>3 électrons se sont malheureusement perdus en route mais des avis de recherche ont déjà été émis.</p><p>(Nan mais on rigole hein).</p>
			<p><a href="/">‹ Retourner sur Chocolat Chaud</a></p>';
	// Error
	} else if ($publish_result == "error") {
		$html .= 
			'<h2>Saperlipopette !</h2>
				<p style="text-align:center;"><object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/a1Y73sPHKxw&hl=fr&fs=1&rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/a1Y73sPHKxw&hl=fr&fs=1&rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object></p>
				<p>Les marmottes se sont faite enlevées par les tamias ! Il n\'y a plus de chocolat. Revenez plus tard, ou</p>
				<p><a href="http://getsatisfaction.com/desrangila/topics/new">Ouvrez un ticket pour prévenir les forces intergalactiques ›</a></p>';	
	} else if ($publish_result == "newsletter") {
		$html .= 
			'<h2>Bonjour '.$user->fbc_getName().' !</h2>
			<p>Gérez votre abonnement à la lettre d\'information.</p>
		';
	// Form
	} else if ($user) {
		// USER LOGGED IN
		$html .= ' 
			<h2>Bonjour '.$user->fbc_getName().' !</h2>
			<p>Maintenant que vous êtes connecté, vous pouvez dire à vos amis que vous venez de vous lever ou que vous allez vous coucher. Pour ce faire, cliquez sur “Bonjour” ou “Bonne nuit”.</p>
			<p>À ce moment-là, Chocolat Chaud publiera une ligne de statut sur votre profil Facebook.</p>
		';
		/*if ($user->fbc_is_facebook_user()) {
			$html .= '<div id="header-profilepic">';
			$html .= $user->fbc_getProfilePic_xfbml(true);
		}*/
		//$html .= '<div id="header-account">';
		//$html .= '<div class="account_links">';
	} else {
		// NOT LOGGED IN
		$html .= '
			<h2>Bienvenue ! Identifiez-vous pour commencer !</h2>
				<p>Pour indiquer à vos amis sur Facebook lorsque vous vous levez et vous allez vous coucher, vous devez d\'abord vous identifier. Cette opération est totalement sécurisée.</p>
				<p>Cliquez sur le bouton Connect with Facebook pour continuer.</p>
		';
		// Loggin Button
		$html .= render_logged_out_index();
		// Newsletter
		$html .= '
			<div id="newsletter">
				<img src="img/newsletter-logo.png" style="float:left;padding-right:20px;"/><h4>Pour ne rater aucune saveur,<br/>inscrivez-vous à la newsletter :</h4>
				<form action="http://desrangila.createsend.com/t/r/s/yhiyb/" method="post">
					<div>
						<label for="name">Nom </label><input type="text" name="cm-name" id="name" /><br />
						<label for="yhiyb-yhiyb">Email </label><input type="text" name="cm-yhiyb-yhiyb" id="yhiyb-yhiyb" /><br />

						<input type="submit" value="Oh oui, tartinez-moi de Chocolat !" />
					</div>
				</form>
					<p><u>Note :</u> Comme vous, nous n\'aimons pas le spam. Nous n\'envoyons pas de courriers non sollicités ni ne revendons vos adresses. De plus, vous pouvez vous désabonner à tout moment.</p>
			</div>
		';
		$html .= '</div>'; // content-inner
		
	}

	
	// Catch misconfiguration errors.
	if (!is_config_setup()) {
		$html .= render_error('Your FB Connect configuration is not complete.');
		$html .= '</div>'; // content-inner
		$html .= '</div>'; // content
		$html .= '</body>';
		echo $html;
		exit;
	}

	return $html;
}

/*
 * Register a bit of javascript to be executed on page load.
 *
 * This is printed in render_footer(), so make sure to include that on all pages.
 */

function onloadRegister($js) {
	global $onload_js;
	$onload_js .= $js;
}

/*
 * Print the unified footer for all pages. Includes all onloadRegister'ed Javascript for the page.
 *
 */

function render_footer() {
	global $onload_js;
	$html = '</div>'; // content-inner
	$html .= '</div>'; // content
	$html .= '
		<div id="footer">
			<p>TM &amp; &copy; 2009 <a href="http://www.desrangila.com/">Des Rangila</a>. Tous droits réservés. &#183; <a href="http://getsatisfaction.com/desrangila/products/desrangila_chocolat_chaud">Remarques et suggestions</a> 
			</p>
			<p style="opacity:0.5">Créé par @<a href="http://twitter.com/stephane">stephane</a> et @<a href="http://twitter.com/littlefox">littlefox</a>
			</p>
		</div>
	';
	$html .= "</div>"; // container
	// the init js needs to be at the bottom of the document, within the </body> tag
	// this is so that any xfbml elements are already rendered by the time the xfbml
	// rendering takes over. otherwise, it might miss some elements in the doc.

	if (is_fbconnect_enabled()) {
		$html .= render_fbconnect_init_js();
	}

	// Print out all onload function calls
	if ($onload_js) {
		$html .= '<script type="text/javascript">'
			.'window.onload = function() { ' . $onload_js . ' };'
			.'</script>';
	}

	$html .= '
		</body>
		</html>
	';

	return $html;
}

/*
 * Default index for logged out or new users.
 *
 */

function render_logged_out_index() {
	if (is_fbconnect_enabled()) {
		$html .= '<div class="fbconnect_login">';
		$html .= render_fbconnect_button('medium');
		$html .= '</div>'; // fbconnect_login
	}

	return $html;
}

function render_feed_form($user, $page='') {
		$html = '<div id="action_area">';	
		
		if ($page == "newsletter") {
			$html .= '
					<div id="left">
						<p>Abonnement</p>
						<form action="http://desrangila.createsend.com/t/r/s/yhiyb/" method="post">
							<div>
								<label for="name">Nom </label><input type="text" name="cm-name" id="name" /><br />
								<label for="yhiyb-yhiyb">Email </label><input type="text" name="cm-yhiyb-yhiyb" id="yhiyb-yhiyb" /><br />
								
								<input type="submit" value="Oh oui, tartinez-moi de Chocolat !" />
								</div>
								</form>
						<p></p>
					</div>
					<div id="right">
						<p>Désabonnement</p>
						<p>
							<form action="http://desrangila.createsend.com/t/r/u/yhiyb/" method="post">
								<div>
									<label for="email">Email </label>
									<input type="Text" id="email" name="cm-yhiyb-yhiyb" /><br />
									<input type="submit" value="Pas de bras, pas de chocolat !" />
								</div>
							</form>
						</p>
					</div>
			';		
		} else {
			$html .= "<form name='feed_form' method='POST'>";
			//$html .= "<textarea id='fbconnect_textarea' name='comment' rows='5' cols='30'></textarea>";
			//$html .= "<input type='button' name='fbconnect_js_submit' class='fbconnect_button' onclick='publish_js_comment(\"" . idx($GLOBALS, 'feed_bundle_id') . "\", \"" . idx($GLOBALS, 'sample_post_title') . "\",  \"" . idx($GLOBALS, 'sample_post_url') . "\");' value='Post via JS'>";
			//$html .= "<div class='fbconnect_text'>In order to post comments directly from PHP, you must have the user authorize offline access to your application.";
			if ($user->fbc_getExtendedPermission('offline_access') < 1) {
				$html .= '  Cliquez sur le bouton ci-dessous pour autoriser l\'accès à Facebook :<br/><br/>';
				$html .= "<input type='button' name='fbconnect_oad_submit' class='fbconnect_button' onclick='fbc_show_offline_access_permission_dialog();' value='Autoriser'>";
			} else {
				//$html .= '<br/><br/>Offline Access has been granted.<br/><br/>';
				$html .= '		
							<script type="text/javascript">
								function submitDate(action) {
								
									var rightNow = new Date();									var jan1 = new Date(rightNow.getFullYear(), 0, 1, 0, 0, 0, 0);  // jan 1st									var june1 = new Date(rightNow.getFullYear(), 6, 1, 0, 0, 0, 0); // june 1st									var temp = jan1.toGMTString();									var jan2 = new Date(temp.substring(0, temp.lastIndexOf(" ")-1));									temp = june1.toGMTString();									var june2 = new Date(temp.substring(0, temp.lastIndexOf(" ")-1));									var std_time_offset = (jan1 - jan2) / (1000 * 60 * 60);									var daylight_time_offset = (june1 - june2) / (1000 * 60 * 60);									var dst;									if (std_time_offset == daylight_time_offset) {										dst = "0"; // daylight savings time is NOT observed									} else {										// positive is southern, negative is northern hemisphere										var hemisphere = std_time_offset - daylight_time_offset;										if (hemisphere >= 0)											std_time_offset = daylight_time_offset;										dst = "1"; // daylight savings time is observed									}
									
									document.feed_form.timeZone.value = std_time_offset;
									document.feed_form.action.value = action;
									
									
										document.feed_form.submit();				
								}
								
								function precisionSwitch(id){
									reveilSelectedIndex = document.feed_form.reveil.selectedIndex;
									coucherSelectedIndex = document.feed_form.coucher.selectedIndex;
									
									if(reveilSelectedIndex == 5){
										document.getElementById(id).style.display = \'block\';
										document.getElementById(\'ReveilStandardSwitch\').style.display = \'none\';
										document.getElementById(\'preveil\').name = \'reveil\';
									}
									
									if(coucherSelectedIndex == 5){
										document.getElementById(id).style.display = \'block\';
										document.getElementById(\'CoucherStandardSwitch\').style.display = \'none\';
										document.getElementById(\'pcoucher\').name = \'coucher\';
									}
								}
								
							</script>
				
				
							<div id="left">
								<p>Je me suis levé…</p>
								<p id="ReveilStandardSwitch">
									<select name="reveil" onChange="precisionSwitch(\'reveilPrecisionSwitch\');" id="sreveil">
										<option value="0">à l\'instant</option>
										<option value="5">il y a 5 minutes</option>
										<option value="10">il y a 10 minutes</option>
										<option value="15">il y a un quart d\'heure</option>
										<option value="30">il y a une demi-heure</option>
										<option value="-1"><b>Spécifier l\'heure moi-même</b></option>
									</select></p>
									<p id="reveilPrecisionSwitch" style="display:none; "><label for="preveil">il y a </label><input name="preveil" value="45" style="width:35px;" id="preveil"/> <label for="preveil">minutes.</label></p>
								<p>
								
									<a class="bonjour" href="javascript:submitDate(\'lever\');"><span>Bonjour !</span></a>
								</p>
							</div>
							<div id="right">
								<p>Je vais me coucher…</p>
								<p id="CoucherStandardSwitch">
									<select name="coucher" onChange="precisionSwitch(\'coucherPrecisionSwitch\');" id="scoucher">
										<option value="0">dans quelques instants</option>
										<option value="5">dans 5 minutes</option>
										<option value="10">dans 10 minutes</option>
										<option value="15">dans un quart d\'heure</option>
										<option value="30">dans une demi-heure</option>
										<option value="-1"><b>Spécifier l\'heure moi-même</b></option>
									</select></p>
									<p id="coucherPrecisionSwitch" style="display:none; "><label for="pcoucher">dans </label><input name="pcoucher" value="45" style="width:35px;" id="pcoucher"/> <label for="pcoucher">minutes.</label></p>
								<p>
									<a class="bonne-nuit" href="javascript:submitDate(\'coucher\');"><span>Bonne nuit !</span></a>
								</p>
							</div>
				';
				
				$html .= "<input type='hidden' name='timeZone' value=''>";
				$html .= "<input type='hidden' name='action' value=''>";
			}
			$html .= "</form>";
		}
		$html .= "</div>"; // action_area
		
		if ($user) {
			if ($user->fbc_is_facebook_user()) {
				$html .= '
					<p>&#160;</p>
					<div id="content-footer">
						<p><a href="newsletter.php">Lettre d\'information</a> &#183;
				';
				$html .= sprintf('<a href="/" onClick="FB.Connect.logout(function() { refresh_page(); })">'
												 .'Déconnexion'
												 //.'<img src="images/fbconnect_logout.png">'
												 .'</a>',
												 $_SERVER['REQUEST_URI']);
				$html .= "</p></div>"; // content-footer
			} else {
				// PUT YOUR SITES LOGOUT URL HERE
			}
		}
	

	return $html;
}

function showing_results($row_start, $num_rows, $max_rows, $label) {
	// SHOWS HOW MANY AND WHICH RECORDS ARE BEING DISPLAYED
	if (($row_start != "") && ($row_start > 0)) { $limit_low = $row_start+1;} else { $limit_low = 1;}
	$limit_high = $num_rows + $limit_low - 1;					
	if ($limit_high > $max_rows) { $limit_high = $max_rows; }
	if ($num_rows > 0) {						
		$html = "Showing " . $label;
		$html .= " " . $limit_low . " through " . $limit_high . " of " . $max_rows;
	}
	return $html;
}

function render_error($msg) {
	return '<div class="error">'.$msg.'</div>';
}

?>