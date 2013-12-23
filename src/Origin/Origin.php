<?php



	class Origin implements ISingleton {

/*variable $instance s�tts till null f�r att i funktionen Instance() p�verka s� att
endast ett objekt kan skapas av klassen.
*/


  	private static $instance = null;
  	public $config = array();
  	public $request;
  	public $data;
  	public $db;	
 	public $views;
  	public $session;
	public $user;
  	public $timer = array();


/* Funktion som vid anv�ndning garanterar att endast ett objekt g�r att skapa av klassen Origin.
self anv�nds f�r att relatera till infomration som h�r till klassen, i detta fall en medlemsvariabel.
*/

  	public static function Instance() {
    	if(self::$instance == null) {
      self::$instance = new Origin();
    	}
    	return self::$instance;
  	}

/* Konstruktorn anropas varje g�ng ett nytt objekt av klassen skall skapas
och i detta fall d� ramverket initialiseras genom att skapa en instance av denna klass 
anv�nds konstruktorn f�r att l�sa in konfigurationsinst�llningar f�r 
ramverkets fortsatta exekvering.

F�rst l�ses unixtiden i sekunder in till  $this->timer['first'].

D�refter inkluderas koden i config.php och renderas och f�r att konfigurationsinst�llningar
skall g� att anropa med Origo, objektets namn, ist�llet f�r $this anv�nds koden
$Origo = &$this; 

D�refter startas en session med det namn som konfigureratsa i config.

D�refter skapas ett objekt av klassen CSession och objektet ges den sessionsnyckel som 
givits i config. Funktionen PoulateFromSession 
vilken �r till f�r att hantera meddelanden till anv�ndaren anropas.

D�refter ges vilken tids zon till vilken tid relateras och d� en adress f�r en databas finns konfigurerad i config 
s� skapas ett objekt av databasklassen CMDatabase. N�r detta objekt skapas s� skapas en koppling till
en den databas som ges av config['database'][0]['dsn'] med hj�lp av PDO.

D�refter skapas ett objekt av klassen CViewContainer f�r att de metoder som finns i klassen skall g� att 
anv�nda f�r att hantera vyer.

*/

   /**
         * Constructor
         */
        protected function __construct() {
                // time page generation
                $this->timer['first'] = microtime(true);

                // include the site specific config.php and create a ref to $ly to be used by config.php
                $Origo = &$this;
    require(LYDIA_SITE_PATH.'/config.php');

                // Start a named session
                session_name($this->config['session_name']);
                session_start();
                $this->session = new CSession($this->config['session_key']);
                $this->session->PopulateFromSession();
                
                // Set default date/time-zone
               date_default_timezone_set('UTC');
                
                // Create a database object.
                if(isset($this->config['database'][0]['dsn'])) {
                  $this->db = new CMDatabase($this->config['database'][0]['dsn']);
          }
          
          // Create a container for all views and theme data
          $this->views = new CViewContainer();

          // Create a object for the user
          $this->user = new CMUser($this);
  }
  
  
/*I FrontControllerRoute() analyseras den l�nk som 
brukats och omdirigerats till index.php

D� metoden FrontcontrollerRoute() anropas initeras ett objekt av klassen 
CReguest f�r att metoden Init($baseUrl) skall g� att anv�nda f�r att analysera
l�nk enligt ovan.

L�nken delas i Init($baseUrl) och de resultat som d� ges �verf�rs till variablerna
$controller, $method, $arguments.

Sedan kontrollerar metoden att efterfr�gad kontroller och metod finns och �r 
tillg�nglig och sedan sker anrop med PHP reflection.

*/

  	public function FrontControllerRoute() {


    	$this->request = new CRequest($this->config['url_type']);
    	$this->request->Init($this->config['base_url']);

    	$controller = $this->request->controller;
    	$method = $this->request->method;
    	$arguments = $this->request->arguments;


    	$controllerExists         = isset($this->config['controllers'][$controller]);
    	$controllerEnabled        = false;
    	$className                = false;
    	$classExists              = false;

    	if($controllerExists) {
      	$controllerEnabled      = ($this->config['controllers'][$controller]['enabled'] == true);
      	$className              = $this->config['controllers'][$controller]['class'];
      	$classExists            = class_exists($className);
    	}
    
   
    	if($controllerExists && $controllerEnabled && $classExists) {

      $rc = new ReflectionClass($className);
      if($rc->implementsInterface('IController')) {
         $formattedMethod = str_replace(array('_', '-'), '', $method);
        if($rc->hasMethod($formattedMethod)) {
          $controllerObj = $rc->newInstance();
          $methodObj = $rc->getMethod($formattedMethod);
          if($methodObj->isPublic()) {
            $methodObj->invokeArgs($controllerObj, $arguments);
          } else {
            die("404. " . get_class() . ' error: Controller method not public.');
          }
        } else {
          die("404. " . get_class() . ' error: Controller does not contain method.');
        }
      } else {
        die('404. ' . get_class() . ' error: Controller does not implement interface IController.');
      }
    }
    else {
      die('404. Page is not found.');
    }
  }


/*I Themeenginerender brukas data vilka tagits fram under tidigare fas FrontControllerRoute 
tillsammans med ett tema (html +css )f�r att best�mma efterfr�gad sidas inneh�ll och
d�refter visa denna sida f�r anv�ndaren.

I steg 1 sparas data ifr�n $data i Csession till $SESSION s� att de meddelanden som visas f�r
anv�ndaren och tilkommer f�r att knappar trycks i formul�ret i index.tpl.php 
�r aktuelt uppdaterade.

D�refter kontrolleras att ett tema har givits i fil config och om det inte �r s� 
avbryts exekveringen i denna funktion, annars fors�tter exekvering och 
en adress till efterfr�ga sida $themeUrl g�rs i ordning.

D�refter inkluderas tv� st filer med v�rden p� varialber och funktioner f�r att det
html-dokument som beskrivs av /themes/core/default.tp1.php skall vara m�jlig att
f�rdigst�llas.

d�refter importeras variabler data-arrayen i Origin med extract s� 
de nycklar som d�r finns kan till�mpas som variabler h�r och med hj�lp av metoden 
getData i CViewContainer sker samma sak med data-arrayen d�r.

Till sist inkluderas html-dokumentet /themes/core/default.tp1.php och d� tar denna fil �ver 
och styr vad som skall visas d� denna direkt inneh�ller html-kod.

*/

  	public function ThemeEngineRender() {
 
    	$this->session->StoreInSession();
  
    	if(!isset($this->config['theme'])) {
      	return;
    	}
    
    	$themeName         = $this->config['theme']['name'];
    	$themePath         = LYDIA_INSTALL_PATH . "/themes/{$themeName}";
    	$themeUrl          = $this->request->base_url . "themes/{$themeName}";

    	   $this->data['stylesheet'] = "{$themeUrl}/".$this->config['theme']['stylesheet'];
 	$this->data['favicon'] = "{$themeUrl}/img/favicon.ico";

    	$Origo = &$this;

    	include(LYDIA_INSTALL_PATH . '/themes/functions.php');
    	$functionsPath = "{$themePath}/functions.php";
    	if(is_file($functionsPath)) {
      	include $functionsPath;
    	}

    	extract($this->data);
    	extract($this->views->GetData());
    	include("{$themePath}/default.tp1.php");
  	}
 
  
       

} 