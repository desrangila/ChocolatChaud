<?php

/*   
 *   FACEBOOK CONNECT LIBRARY FUNCTIONS/CLASSES
 */

/*   
 *   FILE INCLUDE PATHS
 *   MAKE SURE THESE PATHS ALL END WITH A FORWARD SLASH
 */

define(CONNECT_APPLICATION_PATH, "");
define(CONNECT_JAVASCRIPT_PATH, "javascript/");
define(CONNECT_CSS_PATH, "css/");
define(CONNECT_IMG_PATH, "img/");

include_once CONNECT_APPLICATION_PATH . 'facebook-client/facebook.php';
include_once CONNECT_APPLICATION_PATH . 'lib/fbconnect.php';
include_once CONNECT_APPLICATION_PATH . 'lib/core.php';
include_once CONNECT_APPLICATION_PATH . 'lib/user.php';
include_once CONNECT_APPLICATION_PATH . 'lib/display.php';

/*   
 *   FB CONNECT APPLICATION DATA
 */

$callback_url    = 'http://www.chocolatchaud.org';
$api_key         = 'e2b17860b953d72622837c8d31ef007f';
$api_secret      = '65522f282416bf51a83e8337ff4742dc';
$base_fb_url     = 'connect.facebook.com';


$feed_bundle_reveil_id  = '77281138339';
$feed_bundle_coucher_id = '77281883339';
/*   
 *   SAMPLE BUNDLE DATA
 */

$sample_post_title = "Chocolat Chaud";
$sample_post_url = "http://www.chocolatchaud.org";
$sample_one_line_story = '{*actor*} s\'est levé à {*time*}.';
$sample_template_data = '{"time":"7h45"}';

?>