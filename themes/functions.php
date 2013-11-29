<?php

/*funktionen get_debug som i html-dokumentet default.tp1.php anropas efter $footer ger
med hj�lp av metoden GetFlash �r till f�r att f�rbereda en utskrift om antalet fr�gor till databasen, fr�gorna i SQL
och tids�tg�ngen f�r uppdraget.
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


/*?get_messages_from_session anropas fr�n html-dokumentet default.tp1.php och 
i denna funktion definieras variabel $message att inneh�lla det medelande till anv�ndaren 
om vad som utf�rts mot databasen som lagrats av funktion AddMessage($type, $message).
funktionen get_messages_from_session i sig returnerar en variabel $html
med html-kod vilket g�r att meddelandet till anv�ndaren nu sedan kan skrivas ut.
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
  if($Origo->user->IsAuthenticated()) {
    $items = "<a href='" . create_url('user/profile') . "'>" . $Origo->user->GetAcronym() . "</a> ";
    if($Origo->user->IsAdministrator()) {
      $items .= "<a href='" . create_url('acp') . "'>acp</a> ";
    }
    $items .= "<a href='" . create_url('user/logout') . "'>logout</a> ";
  } else {
    $items = "<a href='" . create_url('user/login') . "'>login</a> ";
  }
  return "<nav>$items</nav>";
}	
	
	
/*i denna fil finns flera funktioner till hj�lp f�r att beskriva 
adresser till olika punkter av ramverket.
*/


	function base_url($url=null) {
  	return Origin::Instance()->request->base_url . trim($url, '/');
	}

	function create_url($urlOrController=null, $method=null, $arguments=null) {
  return Origin::Instance()->request->CreateUrl($urlOrController, $method, $arguments);
}

	function theme_url($url) {
  	$Origo = Origin::Instance();
  	return "{$Origo->request->base_url}themes/{$Origo->config['theme']['name']}/{$url}";
	}

	function current_url() {
  	return Origin::Instance()->request->current_url;
	}

/*render_views() anropas fr�n html-dokumentet default.tp1.php och ger det
inneh�ll som kommer ifr�n functionen Render i CVIewContainer via extract och include.
Det inneh�ll som ges �r filen src/CCGuestbook/index.tp1.php och en
array med de meddelanden som tidigare lagrats i databasen och gjorts tillg�nglig via 
AddInclude($file, $variables=array() i CviewContainer.
*/

	function render_views() {
  	return Origin::Instance()->views->Render();
	}