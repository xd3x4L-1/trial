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
  if(isset($Origo->config['debug']['db-num-queries']) && $Origo->config['debug']['db-num-queries'] && isset($Origo->db)) {
    $html .= "<p>Database made " . $Origo->db->GetNumQueries() . " queries.</p>";
  }
  if(isset($Origo->config['debug']['db-queries']) && $Origo->config['debug']['db-queries'] && isset($Origo->db)) {
    $html .= "<p>Database made the following queries.</p><pre>" . implode('<br/><br/>', $Origo->db->GetQueries()) . "</pre>";
  }
  if(isset($Origo->config['debug']['trial']) && $Origo->config['debug']['trial']) {
    $html .= "<hr><h3>Debuginformation</h3><p>The content of Origin:</p><pre>" . htmlent(print_r($Origo, true)) . "</pre>";
  }
  return $html;
}



/**
 * Prepend the base_url.
 */
function base_url($url=null) {
  return Origin::Instance()->request->base_url . trim($url, '/');
}

/**
* Create a url to an internal resource.
*/
function create_url($url=null) {
  return Origin::Instance()->request->CreateUrl($url);
}









/**
* Prepend the theme_url, which is the url to the current theme directory.
*/
function theme_url($url) {
  $Origo = Origin::Instance();
  return "{$Origo->request->base_url}themes/{$Origo->config['theme']['name']}/{$url}";
}

/**
 * Return the current url.
 */
function current_url() {
  return Origin::Instance()->request->current_url;
}


/**
* Render all views.
*/
function render_views() {
  return Origin::Instance()->views->Render();
}