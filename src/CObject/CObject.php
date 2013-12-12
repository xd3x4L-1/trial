<?php

/*Denna klass �r f�r�ldraklass till de tre controller 
klasserna 
CCIndex, CCDeveloper, CCGuestbook och till modellklassen CMGuestbbok.
*/

/*De medlemsvariabler vilka �r givna anv�nds f�r de olika objekt som tidigare skapats i Origin
och f�r den config() array som finns i filen config.
medlemsvariablerna har en motsvarande variabel med lika namn i klass Origin.

$config anv�nds f�r konfigurationsarrayen i fil config.(variablel inf�rd i config.php).
$data anv�nds f�r den data variabel  som skall skrivas ut till sk�rmen. (variabel inf�rd i Origin).

$reguest anv�nds f�r objektet av CRequst.
$views anv�nds f�r objektet av CViewContainer.
$db anv�nds f�r det databasobjekt som skapats ur CMDatabas och som inneh�ller kopplingen mot databasen.
$session �r f�r objektet av CSession som inneh�ller metoder f�r meddelanden till anv�ndaren.
*/

	class CObject {

   /**
         * Members
         */
        protected $config;
        protected $request;
        protected $data;
        protected $db;
        protected $views;
        protected $session;
        protected $user;

/* I konstruktorn som anropas ifr�n de olika kontrolleklasserna och ifr�n modellen CMDatabase 
ges medlemsvariablerna till�telse att vara skrivs�tt f�r v�rden som tagit fram i Origin.
*/

      /**
         * Constructor, can be instantiated by sending in the $ly reference.
         */
        protected function __construct($Origo=null) {
         if(!$Origo) {
         $Origo = Origin::Instance();
         }
    $this->config = &$Origo->config;
    $this->request = &$Origo->request;
    $this->data = &$Origo->data;
    $this->db = &$Origo->db;
    $this->views = &$Origo->views;
    $this->session = &$Origo->session;
    $this->user = &$Origo->user;
        }


  
/* Funktionen RedirectTo($url) anropas ifr�n CCGuestbook i metod handler efter varje fr�ga mot databasen.
f�r att g�stbokens huvudsida p� nytt och med aktuellt inneh�ll skall visas p� sk�rmen.

F�r att if-delar i funktionen skall utf�ras s� erfordras dela att inst�llningar i filen config �r inst�llda till true och
dels att det finns ett existerade databasobjekt.

Funktion SetFlash($key, $value) i CSession anropas med nycklar database_numQueries, database_queries, timer
och med v�rden som kommer ifr�n CMDatabase(och �ndras varje g�ng som en fr�ga mot databasen st�lls.
F�r Origo->timer g�ller aktuell unix-tid.

denna funktion SetFlash($key, $value) anropas av funktionen RedirectTo($url) i CObjekt vilken i sig 
obligatoriskt anropas efter 
varje utf�rd fr�ga mot datavasen av metod Handler i CCGuestbook.
alla nycklar - 'database_numQueries', 'database_queries', 'timer' anv�nds varje g�ng och 
b� ers�tts v�rdet f�r denna nyckel med ett nytt.

Funktionen lagrar de v�rden om antalet fr�gor, fr�gorna i sig och tiden f�r att
utskrift senare skall kunna ske under sidfoten i trial/guestbook.

*/

/**
         * Redirect to another url and store the session
         */
        protected function RedirectTo($urlOrController=null, $method=null) {
    $Origo = Origin::Instance();
    if(isset($Origo->config['debug']['db-num-queries']) && $Origo->config['debug']['db-num-queries'] && isset($Origo->db)) {
      $this->session->SetFlash('database_numQueries', $this->db->GetNumQueries());
    }
    if(isset($Origo->config['debug']['db-queries']) && $Origo->config['debug']['db-queries'] && isset($Origo->db)) {
      $this->session->SetFlash('database_queries', $this->db->GetQueries());
    }
    if(isset($Origo->config['debug']['timer']) && $Origo->config['debug']['timer']) {
         $this->session->SetFlash('timer', $Origo->timer);
    }
    $this->session->StoreInSession();
    header('Location: ' . $this->request->CreateUrl($urlOrController, $method));
  }
  
  
     /**
         * Redirect to a method within the current controller. Defaults to index-method. Uses RedirectTo().
         *
         * @param string method name the method, default is index method.
         * @param $arguments string the extra arguments to send to the method
         */
        protected function RedirectToController($method=null, $arguments=null) {
    $this->RedirectTo($this->request->controller, $method, $arguments);
  }
  
  
  
  
  

 /**
         * Redirect to a controller and method. Uses RedirectTo().
         *
         * @param string controller name the controller or null for current controller.
         * @param string method name the method, default is current method.
         * @param $arguments string the extra arguments to send to the method
         */
        protected function RedirectToControllerMethod($controller=null, $method=null, $arguments=null) {
         $controller = is_null($controller) ? $this->request->controller : null;
         $method = is_null($method) ? $this->request->method : null;        
    $this->RedirectTo($this->request->CreateUrl($controller, $method, $arguments));
  }
  
  
  
  
  
  
  
  

 
  /**
         * Save a message in the session. Uses $this->session->AddMessage()
         *
* @param $type string the type of message, for example: notice, info, success, warning, error.
* @param $message string the message.
*/
 /**
         * Save a message in the session. Uses $this->session->AddMessage()
         *
* @param $type string the type of message, for example: notice, info, success, warning, error.
* @param $message string the message.
* @param $alternative string the message if the $type is set to false, defaults to null.
*/
  protected function AddMessage($type, $message, $alternative=null) {
    if($type === false) {
      $type = 'error';
      $message = $alternative;
    } else if($type === true) {
      $type = 'success';
    }
    $this->session->AddMessage($type, $message);
  }


        /**
         * Create an url. Uses $this->request->CreateUrl()
         *
         * @param $urlOrController string the relative url or the controller
         * @param $method string the method to use, $url is then the controller or empty for current
         * @param $arguments string the extra arguments to send to the method
         */
        protected function CreateUrl($urlOrController=null, $method=null, $arguments=null) {
    $this->request->CreateUrl($urlOrController, $method, $arguments);
  }



  
  
  
  

}