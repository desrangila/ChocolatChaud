<?php

sfLoader::loadHelpers(array('Javascript'));


function fb_include_title()
{
  $title = sfContext::getInstance()->getResponse()->getTitle();

  echo content_tag('fb:title', $title)."\n";
}


function fb_include_javascripts()
{
  $response = sfContext::getInstance()->getResponse();
  $response->setParameter('javascripts_included', true, 'symfony/view/asset');

  $already_seen = array();
  $js = '';

  foreach (array('first', '', 'last') as $position)
  {
    foreach ($response->getJavascripts($position) as $files)
    {
      if (!is_array($files))
      {
        $files = array($files);
      }

      foreach ($files as $file)
      {
        $file = javascript_path($file);

        if (isset($already_seen[$file])) continue;

        $already_seen[$file] = 1;
        
        // TODO: the following logic needs to be fixed the tested. For now it works OK.
        global $sf_symfony_data_dir;

        $webdir = SF_ROOT_DIR.DIRECTORY_SEPARATOR.'web';
        $dir    = $webdir.DIRECTORY_SEPARATOR.'js';

        if (substr($file, 0, 4) == '/sf/') {
          $file = $sf_symfony_data_dir.DIRECTORY_SEPARATOR.'web'.$file;
        } else if (substr($file, 0, 3) == 'sf/') {
          $file = $sf_symfony_data_dir.DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.$file;
        } else if (0 === strpos($file, '/')) {
          $file = realpath($webdir.$file);
        } else if (eregi('^/?sf(.*)Plugin', $file)) {
          $file = realpath($webdir.DIRECTORY_SEPARATOR.$file);
        } else {
          $file = realpath($dir.DIRECTORY_SEPARATOR.$file);
        }
        if (is_readable($file)) {
          $js .= file_get_contents($file);
        }
      }
    }
  }
  
  echo content_tag('script', $js);
}

/**
 * Return one <style> tag for all stylesheets configured in view.yml or added to the response object. *
 * You can use this helper to decide the location of stylesheets in pages.
 * By default, if you don't call this helper, symfony will automatically include stylesheets before </head>.
 * Calling this helper disables this behavior.
 *
 * @return string <style> tag
 */
function fb_include_stylesheets()
{
  $response = sfContext::getInstance()->getResponse();
  $response->setParameter('stylesheets_included', true, 'symfony/view/asset');

  $css = '';
  $already_seen = array();
  foreach (array('first', '', 'last') as $position)
  {
    foreach ($response->getStylesheets($position) as $files => $options)
    {
      if (!is_array($files)) {
        $files = array($files);
      }

      foreach ($files as $file)
      {
        if (isset($already_seen[$file])) {
          continue;
        } else {
          $already_seen[$file] = 1;
        }

        $absolute = isset($options['absolute']);
        unset($options['absolute']);

        if(!isset($options['raw_name']))
        {
          $file = stylesheet_path($file, $absolute);
        }
        else
        {
          unset($options['raw_name']);
        }
        
        // TODO: the following logic needs to be fixed the tested. For now it works OK.
        global $sf_symfony_data_dir;

        $webdir = SF_ROOT_DIR.DIRECTORY_SEPARATOR.'web';
        $dir    = $webdir.DIRECTORY_SEPARATOR.'css';
        
        if (substr($file, 0, 4) == '/sf/') {
          $file = $sf_symfony_data_dir.DIRECTORY_SEPARATOR.'web'.$file;
        } else if (substr($file, 0, 3) == 'sf/') {
          $file = $sf_symfony_data_dir.DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.$file;
        } else if (0 === strpos($file, '/')) {
          $file = realpath($webdir.$file);
        } else if (eregi('^sf(.*)Plugin', $file)) {
          $file = realpath($webdir.DIRECTORY_SEPARATOR.$file);
        } else {
          $file = realpath($dir.DIRECTORY_SEPARATOR.$file);
        }
        if (is_readable($file)) {
          $css .= file_get_contents($file);
        }
      }
    }
  }

  echo "\n".tag('style', array('type' => 'text/css'), true)."\n".$css."\n</style>\n";
}

function fb_link_to_remote($name, $options)
{
  if (isset($options['update'])) {
    $update = $options['update'];
    unset($options['update']);
  } else {
    return false;
  }
  
  if (isset($options['url'])) {
    $url = url_for($options['url'], true);
    unset($options['url']);
  } else {
    return false;
  }
  
  if (isset($options['loading'])) {
    $options['onLoading'] = "function() { ".$options['loading']."; }";
    unset($options['loading']);
  }
  if (isset($options['complete'])) {
    $options['onComplete'] = "function() { ".$options['complete']."; }";
    unset($options['complete']);
  }
  if (!isset($options['type'])) {
    $options['type'] = 'Ajax.RAW';
  }
  $options = _options_for_javascript($options);
  
  return link_to_function($name, sprintf("fb_ajax_updater('%s', '%s', %s)", $update, $url,  $options), $options);
}
