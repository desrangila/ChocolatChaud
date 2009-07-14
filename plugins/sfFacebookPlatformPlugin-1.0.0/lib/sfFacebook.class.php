<?php

class sfFacebook
{
  private $facebook = null;
  
  private static $instance = null;
  private static $is_valid = false;
  
  const NAMESPACE = 'fb_sig';

  public static function getInstance()
  {
    $class = __CLASS__;
    if (!(self::$instance instanceof $class))
    {
      self::$instance = new $class();
      self::$instance->initialize();
    }

    return self::$instance;
  }

  public static function isFacebookRequest()
  {
    if (isset($_POST['fb_sig'])) 
    {
      $fb_sig = $_POST['fb_sig'];
      $params = $_POST;
    } 
    elseif (isset($_GET['fb_sig']))
    {
      $fb_sig = $_GET['fb_sig'];
      $params = $_GET;
    } 
    else
    {
      return false;
    }
    
    if (empty($fb_sig)) {
      return false;
    } else if (self::$is_valid === true) {
      return true;
    }
    
    $facebook = self::getInstance();
    $params = $facebook->get_valid_fb_params($params, null, self::NAMESPACE);

    return self::$is_valid = $facebook->verify_signature($params, $fb_sig);
  }
  
  public static function generateQueryString()
  {
    $params = (isset($_POST['fb_sig']))?$_POST:$_GET;
    if (isset($params[self::NAMESPACE.'_in_canvas']) && isset($params[self::NAMESPACE.'_in_iframe'])) {
      unset($params[self::NAMESPACE.'_in_iframe']);
    }
    
    $facebook = self::getInstance();
    $params = $facebook->get_valid_fb_params($params, null, self::NAMESPACE);
    unset($params['friends'], $params['in_canvas']);
    $params['in_iframe'] = 1;
    $fb_sig = $facebook->generate_sig($params, $facebook->secret);

    foreach ($params as $key => $value) 
    {
      unset($params[$key]);
      $params[sfFacebook::NAMESPACE.'_'.$key] = $value;
    }
    $params[self::NAMESPACE] = $fb_sig;
    
    return http_build_query($params, '', '&');
  }
  
  protected function initialize()
  {
    $GLOBALS['facebook_config'] = array(
      'debug' => sfConfig::get('app_facebook_debug', false)
    );
    
    $this->logger = sfLogger::getInstance();
    if (sfConfig::get('sf_logging_enabled'))
    {
      $this->logger->info('{sfFacebook} initialization');
    }

    require_once dirname(__FILE__).'/facebook-platform/client/facebook.php';
  
    $appapikey = sfConfig::get('app_facebook_api_key');
    $appsecret = sfConfig::get('app_facebook_api_secret');

    if (!$appapikey)
    {
      throw new sfException("app_facebook_api_key not defined in app.yml");
    }
    else if (!$appsecret)
    {
      throw new sfException("app_facebook_api_secret not defined in app.yml");
    }

    $this->facebook = new Facebook($appapikey, $appsecret);
  }
  
  public function __call($m, $a)
  {
    return call_user_func_array(array($this->facebook, $m), $a);
  }
  
  public function __get($m)
  {
    return $this->facebook->$m;
  }
}