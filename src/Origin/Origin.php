<?php
/**
* Main class for Lydia, holds everything.
*
* @package LydiaCore
*/


	class Origin implements ISingleton {

 /**
         * Members
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


 /**
         * Singleton pattern. Get the instance of the latest created object or create a new one.
         * @return CLydia The instance of this class.
         */
  	public static function Instance() {
    	if(self::$instance == null) {
      self::$instance = new Origin();
    	}
    	return self::$instance;
  	}



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
  
  
/**
         * Frontcontroller, check url and route to controllers.
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


 /**
* ThemeEngineRender, renders the reply of the request to HTML or whatever.
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
		 if(isset($this->config['theme']['data'])) {
      extract($this->config['theme']['data']);
    }
		
		
		
    	$templateFile = (isset($this->config['theme']['template_file'])) ? $this->config['theme']['template_file'] : 'default.tpl.php';
    include("{$themePath}/{$templateFile}");
  	}
 
  
       

} 