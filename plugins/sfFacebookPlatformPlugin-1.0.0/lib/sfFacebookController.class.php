<?php

class sfFacebookController extends sfFrontWebController
{
  /** Dispatches a request.
   *
   * This will determine which module and action to use by request parameters specified by the user.
   */
  public function dispatch()
  {
    // The code bellow verifies that the request comes from Facebook
    // and quits if otherwise.
    if (sfFacebook::isFacebookRequest())
    {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') 
      {
        $_POST[sfFacebook::NAMESPACE.'_in_canvas'] = 1;
        $_POST[sfFacebook::NAMESPACE.'_in_iframe'] = 1;
        foreach ($_POST as $key => $value) 
        {
          if (strpos($key, sfFacebook::NAMESPACE) === 0) 
          {
            $_GET[$key] = $value;
            unset($_POST[$key]);
          }
        }
        if (empty($_POST) && empty($_FILES)) 
        {
          $_SERVER['REQUEST_METHOD'] = 'GET';
          sfContext::getInstance()->getRequest()->setMethod(sfRequest::GET);
        }
      }
    } else {
      exit();
    }

    sfMixer::register('sfController:forward:error404', array('sfFacebookController', '_forwardError404'));
    sfMixer::register('sfException:printStackTrace:printStackTrace', array('sfFacebookException', '_printStackTrace'));

    parent::dispatch();
  }
  
  /**
   * Generates an URL from an array of parameters.
   *
   * @param mixed   An associative array of URL parameters or an internal URI as a string.
   * @param boolean Whether to generate an absolute URL
   *
   * @return string A URL to a symfony resource
   */
  public function genUrl($parameters = array(), $absolute = false)
  {
    if (!is_array($parameters) && preg_match('#^[a-z]+\://#', $parameters)) {
      return $parameters;
    } else if (!is_array($parameters) && $parameters == '#') {
      return $parameters;
    }
    else 
    {
      $with_fb_params = false;
      $parameters = str_replace(array('&fb_params=true', '?fb_params=true'), '', $parameters, $with_fb_params);
      
      $host = ($absolute)?sfConfig::get('app_facebook_callback_url'):sfConfig::get('app_facebook_canvas_url');
      $sf_no_script_name = sfConfig::get('sf_no_script_name');
      sfConfig::set('sf_no_script_name', true);
      $path = parent::genUrl($parameters, false);
      sfConfig::set('sf_no_script_name', $sf_no_script_name);
  
      $url = rtrim($host, '/') . $path;
      
      if ($with_fb_params === 1) 
      {
        $url .= (parse_url($url, PHP_URL_QUERY) == '')?'?':'&';
        $url .= sfFacebook::generateQueryString();
      }
      
      return $url;
    }
  }
  
  /**
   * Redirects the request to another URL.
   *
   * @param string An existing URL
   */
  public function redirect($url, $delay = 0, $statusCode = 302)
  {
    // We want redirect() to always redirect to the facebook canvas page
    $url = str_replace(
      sfConfig::get('app_facebook_callback_url'), 
      sfConfig::get('app_facebook_canvas_url'), 
      $url
    );

    return sfFacebook::getInstance()->redirect($url);
  }
  
  protected static function _forwardError404($controller)
  {
    $controller->getContext()->getResponse()->clearHttpHeaders();
    $controller->getContext()->getResponse()->setStatusCode(200);
  }
}
