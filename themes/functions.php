<?php

/*funktionen get_debug som i html-dokumentet default.tp1.php anropas efter $footer ger
med hjälp av metoden GetFlash är till för att förbereda en utskrift om antalet frågor till databasen, frågorna i SQL
och tidsåtgången för uppdraget.
*/


	function get_debug() {
 
  	$Origo = Origin::Instance();
  	if(empty($Origo->config['debug'])) {
    	return;
  	}
  

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


/*?get_messages_from_session anropas från html-dokumentet default.tp1.php och 
i denna funktion definieras variabel $message att innehålla det medelande till användaren 
om vad som utförts mot databasen som lagrats av funktion AddMessage($type, $message).
funktionen get_messages_from_session i sig returnerar en variabel $html
med html-kod vilket gör att meddelandet till användaren nu sedan kan skrivas ut.
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
* Login menu. Creates a menu which reflects if user is logged in or not.
*/
function login_menu() {
  $Origo = Origin::Instance();
  if($Origo->user['isAuthenticated']) {
    $items = "<a href='" . create_url('user/profile') . "'><img class='gravatar' src='" . get_gravatar(20) . "' alt=''> " . $Origo->user['acronym'] . "</a> ";
    if($Origo->user['hasRoleAdministrator']) {
      $items .= "<a href='" . create_url('acp') . "'>acp</a> ";
    }
    $items .= "<a href='" . create_url('user/logout') . "'>logout</a> ";
  } else {
    $items = "<a href='" . create_url('user/login') . "'>login</a> ";
  }
  return "<nav id='login-menu'>$items</nav>";
}


/**
* Get a gravatar based on the user's email.
*/
function get_gravatar($size=null) {
  return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim(Origin::Instance()->user['email']))) . '.jpg?r=pg&amp;d=wavatar&amp;' . ($size ? "s=$size" : null);
}


/**
* Escape data to make it safe to write in the browser.
*/
function esc($str) {
  return htmlEnt($str);
}


/**
* Display diff of time between now and a datetime.
*
* @param $start datetime|string
* @returns string
*/
function time_diff($start) {
  return formatDateTimeDiff($start);
}



/**
* Filter data according to a filter. Uses CMContent::Filter()
*
* @param $data string the data-string to filter.
* @param $filter string the filter to use.
* @returns string the filtered string.
*/
function filter_data($data, $filter) {
  return CMContent::Filter($data, $filter);
}















	
/*i denna fil finns flera funktioner till hjälp för att beskriva 
adresser till olika punkter av ramverket.
*/


	function base_url($url=null) {
  	return Origin::Instance()->request->base_url . trim($url, '/');
	}

	function create_url($urlOrController=null, $method=null, $arguments=null) {
  return Origin::Instance()->request->CreateUrl($urlOrController, $method, $arguments);
}

	/**
* Prepend the theme_url, which is the url to the current theme directory.
*/
function theme_url($url) {
  $Origo = Origin::Instance();
  return "{$Origo->request->base_url}themes/{$Origo->config['theme']['name']}/{$url}";
}



	function current_url() {
  	return Origin::Instance()->request->current_url;
	}

/*render_views() anropas från html-dokumentet default.tp1.php och ger det
innehåll som kommer ifrån functionen Render i CVIewContainer via extract och include.
Det innehåll som ges är filen src/CCGuestbook/index.tp1.php och en
array med de meddelanden som tidigare lagrats i databasen och gjorts tillgänglig via 
AddInclude($file, $variables=array() i CviewContainer.
*/

	
/**
* Render all views.
*
* @param $region string the region to draw the content in.
*/
function render_views($region='default') {
  return Origin::Instance()->views->Render($region);
}





/**
* Check if region has views. Accepts variable amount of arguments as regions.
*
* @param $region string the region to draw the content in.
*/
function region_has_content($region='default' /*...*/) {
  return Origin::Instance()->views->RegionHasView(func_get_args());
}