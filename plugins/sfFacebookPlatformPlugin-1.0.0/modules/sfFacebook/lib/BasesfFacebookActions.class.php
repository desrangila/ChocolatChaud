<?php

#doc
# classname:  BasesfFacebookActions
# scope:    PUBLIC
#
#/doc

class BasesfFacebookActions extends sfActions
{
  public function preExecute()
  {
    require_once dirname(__FILE__).'/../../../lib/facebook-platform/client/facebook.php';

    $appapikey = sfConfig::get('app_facebook_api_key');
    $appsecret = sfConfig::get('app_facebook_api_secret');
    
    if (!$appapikey)
    {
      throw new sfException("app_facebook_api_key not defined in app.yml");
    }
    
    if (!$appsecret)
    {
      throw new sfException("app_facebook_api_secret not defined in app.yml");
    }
    
    $this->facebook = new Facebook($appapikey, $appsecret);
    $this->user = $this->facebook->require_login();

    //[todo: change the following url to your callback url]
    $appcallbackurl = sfConfig::get('app_facebook_callback_url');  

    //catch the exception that gets thrown if the cookie has an invalid session_key in it
    try {
      if (!$this->facebook->api_client->users_isAppAdded()) {
        $this->facebook->redirect($this->facebook->get_add_url());
      }
    } catch (Exception $ex) {
      //this will clear cookies for your application and redirect them to a login prompt
      $this->facebook->set_user(null, null);
      $this->facebook->redirect($appcallbackurl);
    }
    
  }
  
  public function executeIndex()
  {
    
  }
}
###
