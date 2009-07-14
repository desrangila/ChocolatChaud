<?php

class sfFacebookException extends sfException
{
  protected static function _printStackTrace($sfException, $exception)
  {
    $message = null !== $exception->getMessage() ? $exception->getMessage() : 'n/a';
    $name    = get_class($exception);
    $format  = 0 == strncasecmp(PHP_SAPI, 'cli', 3) ? 'plain' : 'html';
    $traces  = $sfException->getTraces($exception, $format);

    // extract error reference from message
    $error_reference = '';
    if (preg_match('/\[(err\d+)\]/', $message, $matches))
    {
      $error_reference = $matches[1];
    }

    include(dirname(__FILE__).'/../data/exception.'.($format == 'html' ? 'php' : 'txt'));
    exit;
  }
}