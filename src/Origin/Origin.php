 <?php
/**
 * Main class for Trial, holds everything.
 *
 * @package TrialCore
 */
class Origin implements ISingleton {

/*variable sätts till null för att i funktionen Instance påverka så att
endast ett objekt kan skapas av klassen.
*/

  private static $instance = null;

  /**
   * Constructor
   */
   
/* Konstruktorn anropas varje gång ett nytt objekt av klassen skall skapas
och i detta fall då ramverket initialiseras genom att skapa en instance av denna klass 
används konstruktorn får att läsa in konfigurationsinställningar för 
ramverkets fortsatta exekvering.
*/

  protected function __construct() {

    // include the site specific config.php and create a ref to $ly to be used by config.php

/*$Origo = &$this; gör det möjligt att direkt använda $Origo i config.php.
*/

    $Origo = &$this;
    require(LYDIA_SITE_PATH.'/config.php');
	
	session_name($this->config['session_name']);
    session_start();
    
    //Set default date/time-zone
    date_default_timezone_set($this->config['timezone']);
	
	
	
	
	
	
	
  }
  
  
  /**
   * Singleton pattern. Get the instance of the latest created object or create a new one. 
   * @return Origin The instance of this class.
   */
   
/* Funktion som garanterar att endast ett objekt går att skapa av klassen Origin.
self används för att relatera till infomration som hör till klassen, i detta fall en medlemsvariabel.
*/
  public static function Instance() {
    if(self::$instance == null) {
      self::$instance = new Origin();
    }
    return self::$instance;
  }
  

  /**
   * Frontcontroller, check url and route to controllers.
   */
  public function FrontControllerRoute() {
    // Take current url and divide it in controller, method and parameters

/*Här byggs ett objekt request av klassen Crequest som inom klassen Origin refereras som
 $this->request.
*/
/*Vid skapandet av det nya objektet används konfigurationen för inkommande url i config.php.
*/

    $this->request = new CRequest($this->config['url_in']);

    $this->request->Init($this->config['base_url']);




    $controller = $this->request->controller;
    $method     = $this->request->method;
    $arguments  = $this->request->arguments;
    
    // Is the controller enabled in config.php?
    $controllerExists   = isset($this->config['controllers'][$controller]);
    $controllerEnabled   = false;
    $className          = false;
    $classExists         = false;


/*för att göra anropet med PHP Reflection lite enklare används tre variabler
*/

    if($controllerExists) {
      $controllerEnabled   = ($this->config['controllers'][$controller]['enabled'] == true);
      $className          = $this->config['controllers'][$controller]['class'];
      $classExists         = class_exists($className);
    }
    
    // Check if controller has a callable method in the controller class, if then call it
    if($controllerExists && $controllerEnabled && $classExists) {
      $rc = new ReflectionClass($className);
      if($rc->implementsInterface('IController')) {
 	$formattedMethod = str_replace(array('_', '-'), '', $method);


        if($rc->hasMethod($formattedMethod)) {
          $controllerObj = $rc->newInstance();
          $methodObj = $rc->getMethod($formattedMethod);
	if($methodObj->isPublic()) {
            $methodObj->invokeArgs($controllerObj, $arguments);

/*Ett sätt att ta del av värden ifrån den klass som anropats med reflection.
*/

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
 
  
         /**
        * ThemeEngineRender, renders the reply of the request.
        */
      public function ThemeEngineRender() {
        // Get the paths and settings for the theme

/*Här hämta namnet för temat under den enda nyckel som finns, nämligen 'name'.
*/
        $themeName    = $this->config['theme']['name'];
        $themePath    = LYDIA_INSTALL_PATH . "/themes/{$themeName}";
        $themeUrl      = $this->request->base_url . "themes/{$themeName}";
       
        // Add stylesheet path to the $Origo->data array
        $this->data['stylesheet'] = "{$themeUrl}/style.css";
	 $this->data['favicon'] = "{$themeUrl}/img/favicon.ico";

        // Include the global functions.php and the functions.php that are part of the theme
        $Origo = &$this;

	 include(LYDIA_INSTALL_PATH . '/themes/functions.php');

        $functionsPath = "{$themePath}/functions.php";
        if(is_file($functionsPath)) {
          include $functionsPath;
        }

        // Extract $Origo->data to own variables and handover to the template file
        extract($this->data);     
        include("{$themePath}/default.tp1.php");
      }
       

} 