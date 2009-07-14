<?php

class sfFacebookFilter extends sfFilter
{
  public function execute($filterChain)
  {
    // We want to store as much data as we can about the facebook user
    // at least get the FB id, and then we can take care of the rest.  
    if ($this->isFirstCall())
    {
      $user = $this->getContext()->getUser();
    
      $facebook = sfFacebook::getInstance();

      if (sfConfig::get('app_facebook_require') == 'add') {
        $facebook_id = $facebook->require_add();
      } else {
        $facebook_id = $facebook->require_login();
      }
      $user->setAttribute('id', $facebook_id, 'facebook');

      // catch the exception that gets thrown if the cookie has an invalid session_key in it
      try 
      {
        if (sfConfig::get('app_facebook_require') == 'add') 
        {
          if (!$facebook->api_client->users_isAppAdded()) {
            $facebook->redirect($facebook->get_add_url());
          }
        }
      } 
      catch (Exception $e) 
      {
        //this will clear cookies for your application and redirect them to a login prompt
        $facebook->set_user(null, null);
        if ($callback_url = sfConfig::get('app_facebook_callback_url')) {
          $facebook->redirect($callback_url);
        }
      }
    }

    $filterChain->execute();
  }
}
