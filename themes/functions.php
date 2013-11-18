<?php
/**
 * Helpers for theming, available for all themes in their template files and functions.php.
 * This file is included right before the themes own functions.php
 */
 
/**
* Print debuginformation from the framework.
*/
function get_debug() {
  // Only if debug is wanted.
  $Origo = Origin::Instance();
  if(empty($Origo->config['debug'])) {
    return;
  }
  
  // Get the debug output
  $html = null;
  if(isset($Origo->config['debug']['db-num-queries']) && $Origo->config['debug']['db-num-queries'] && isset($Origo->db)) {
    $flash = $Origo->session->GetFlash('database_numQueries');
    $flash = $flash ? "$flash + " : null;
    $html .= "<p>Database made $flash" . $Origo->db->GetNumQueries() . " queries.</p>";
  }
  if(isset($Origo->config['debug']['db-queries']) && $Origo->config['debug']['db-queries'] && isset($Origo->db)) {
    $flash = $Origo->session->GetFlash('database_queries');
    $queries = $Origo->db->GetQueries();
    if($flash) {
      $queries = array_merge($flash, $queries);
    }
    $html .= "<p>Database made the following queries.</p><pre>" . implode('<br/><br/>', $queries) . "</pre>";
  }
  if(isset($Origo->config['debug']['timer']) && $Origo->config['debug']['timer']) {
    $html .= "<p>Page was loaded in " . round(microtime(true) - $Origo->timer['first'], 5)*1000 . " msecs.</p>";
  }
  if(isset($Origo->config['debug']['trial']) && $Origo->config['debug']['trial']) {
    $html .= "<hr><h3>Debuginformation</h3><p>The content of trial:</p><pre>" . htmlent(print_r($Origo, true)) . "</pre>";
  }
  if(isset($Origo->config['debug']['session']) && $Origo->config['debug']['session']) {
    $html .= "<hr><h3>SESSION</h3><p>The content of Origin->session:</p><pre>" . htmlent(print_r($Origo->session, true)) . "</pre>";
    $html .= "<p>The content of \$_SESSION:</p><pre>" . htmlent(print_r($_SESSION, true)) . "</pre>";
  }
  return $html;
}



/**
* Get messages stored in flash-session.
*/
function get_messages_from_session() {
  $messages = Origin::Instance()->session->GetMessages();
  $html = null;
  if(!empty($messages)) {
    foreach($messages as $val) {
      $valid = array('info', 'notice', 'success', 'warning', 'error', 'alert');
      $class = (in_array($val['type'], $valid)) ? $val['type'] : 'info';
      $html .= "<div class='$class'>{$val['message']}</div>\n";
    }
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