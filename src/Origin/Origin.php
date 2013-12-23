<?php



	class Origin implements ISingleton {

/*variable $instance sätts till null för att i funktionen Instance() påverka så att
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


/* Funktion som vid användning garanterar att endast ett objekt går att skapa av klassen Origin.
self används för att relatera till infomration som hör till klassen, i detta fall en medlemsvariabel.
*/

  	public static function Instance() {
    	if(self::$instance == null) {
      self::$instance = new Origin();
    	}
    	return self::$instance;
  	}

/* Konstruktorn anropas varje gång ett nytt objekt av klassen skall skapas
och i detta fall då ramverket initialiseras genom att skapa en instance av denna klass 
används konstruktorn får att läsa in konfigurationsinställningar för 
ramverkets fortsatta exekvering.

Först läses unixtiden i sekunder in till  $this->timer['first'].

Därefter inkluderas koden i config.php och renderas och för att konfigurationsinställningar
skall gå att anropa med Origo, objektets namn, istället för $this används koden
$Origo = &$this; 

Därefter startas en session med det namn som konfigureratsa i config.

Därefter skapas ett objekt av klassen CSession och objektet ges den sessionsnyckel som 
givits i config. Funktionen PoulateFromSession 
vilken är till för att hantera meddelanden till användaren anropas.

Därefter ges vilken tids zon till vilken tid relateras och då en adress för en databas finns konfigurerad i config 
så skapas ett objekt av databasklassen CMDatabase. När detta objekt skapas så skapas en koppling till
en den databas som ges av config['database'][0]['dsn'] med hjälp av PDO.

Därefter skapas ett objekt av klassen CViewContainer för att de metoder som finns i klassen skall gå att 
använda för att hantera vyer.

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
  
  
/*I FrontControllerRoute() analyseras den länk som 
brukats och omdirigerats till index.php

Då metoden FrontcontrollerRoute() anropas initeras ett objekt av klassen 
CReguest för att metoden Init($baseUrl) skall gå att använda för att analysera
länk enligt ovan.

Länken delas i Init($baseUrl) och de resultat som då ges överförs till variablerna
$controller, $method, $arguments.

Sedan kontrollerar metoden att efterfrågad kontroller och metod finns och är 
tillgänglig och sedan sker anrop med PHP reflection.

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
tillsammans med ett tema (html +css )för att bestämma efterfrågad sidas innehåll och
därefter visa denna sida för användaren.

I steg 1 sparas data ifrån $data i Csession till $SESSION så att de meddelanden som visas för
användaren och tilkommer för att knappar trycks i formuläret i index.tpl.php 
är aktuelt uppdaterade.

Därefter kontrolleras att ett tema har givits i fil config och om det inte är så 
avbryts exekveringen i denna funktion, annars forsätter exekvering och 
en adress till efterfråga sida $themeUrl görs i ordning.

Därefter inkluderas två st filer med värden på varialber och funktioner för att det
html-dokument som beskrivs av /themes/core/default.tp1.php skall vara möjlig att
färdigställas.

därefter importeras variabler data-arrayen i Origin med extract så 
de nycklar som där finns kan tillämpas som variabler här och med hjälp av metoden 
getData i CViewContainer sker samma sak med data-arrayen där.

Till sist inkluderas html-dokumentet /themes/core/default.tp1.php och då tar denna fil över 
och styr vad som skall visas då denna direkt innehåller html-kod.

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