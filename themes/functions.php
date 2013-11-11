<?php
/**
 * Helpers for theming, available for all themes in their template files and functions.php.
 * This file is included right before the themes own functions.php
 */
 

/**
 * Print debuginformation from the framework.
 */
function get_debug() {
  $Origo = Origin::Instance();
  
  $html = null;
  if(isset($Origo->config['debug']['display-trial'])) {
    $html = "<hr><h3>Debuginformation</h3><p>The content of Origin:</p><pre>" . htmlent(print_r($Origo, true)) . "</pre>";
  } 
 
  
  return $html;
}


/**
 * Prepend the base_url.
 */
function base_url($url) {
  return Origin::Instance()->request->base_url . trim($url, '/');
}


/**
 * Return the current url.
 */
function current_url() {
  return Origin::Instance()->request->current_url;
}