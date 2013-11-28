<?php

/* Denna klass innehar funktioner för att hantera meddelanden till användaren dels AddMessage och GetMessage som
ger användaren i klartext uppgift om vilka frågor som utförts mot databasen och utskrift sker långt upp i
trial/Guestbook. 
dels metoderna SetFlash och GetFlash som används för att ge användren information om
tidsåtgång för frågor mot databasen, antalet frågor för varje begärd åtgärd av användaren och
den direkta SQL-kod som används vid frågorna
*/

	class CSession {

       private $key;
       private $data = array();
       private $flash = null;
        
/* då konstruktorn anropas pga av att ett nytt objekt av klassen skapas i Origins konstruktor
så ges medlemsvariablen det värde som finns förinställdt i config.php. För 
närvarande är detta värde = 'trial'.
*/

       public function __construct($key) {
    	$this->key = $key;
  	}



       public function __set($key, $value) {
    	$this->data[$key] = $value;
  	}

       public function __get($key) {
    	return isset($this->data[$key]) ? $this->data[$key] : null;
  	}
	
	/**
* Get, Set or Unset the authenticated user
*/
  public function SetAuthenticatedUser($profile) { $this->data['authenticated_user'] = $profile; }
  public function UnsetAuthenticatedUser() { unset($this->data['authenticated_user']); }
  public function GetAuthenticatedUser() { return $this->authenticated_user; }
	
	
	
	
	
	
	
	
	



/*denna funktion SetFlash($key, $value) anropas av funktionen RedirectTo($url) i CObjekt vilken i sig 
obligatoriskt anropas efter 
varje utförd fråga mot datavasen av metod Handler i CCGuestbook.
alla nycklar - 'database_numQueries', 'database_queries', 'timer' används varje gång och 
bå ersätts värdet för denna nyckel med ett nytt.

Funktionen lagrar de värden om antalet frågor, frågorna i sig och tiden för att
utskrift senare skall kunna ske under sidfoten i trial/guestbook.
*/
  	public function SetFlash($key, $value) {
    	$this->data['flash'][$key] = $value;
  	}


/*denna funktion GetFlash($key, $value) anropas av html-dokumentet i filem themes/core/default.tp1.php
via metoden get_debug() i filen themes/functions.php för att det innehåll som lagrats i 
funktion SetFlash($key, $value) lagrat till ['flash'][$key] via get_debug skall
kunna skrivas ut nedanför fotern i /trial/guestbook.
*/

  	public function GetFlash($key) {
    	return isset($this->flash[$key]) ? $this->flash[$key] : null;
  	}





/*AddMessage anropas ifrån metoderna Add($entry), DeleteAll(), Init() i CMGuestbook
för att metoden skall lagra ett meddelande till användaren om vad som utföts i frågor till
databasen
*/
  	public function AddMessage($type, $message) {
    	$this->data['flash']['messages'][] = array('type' => $type, 'message' => $message);
  	}


        public function GetMessages() {
    	return isset($this->flash['messages']) ? $this->flash['messages'] : null;
  	}




/*Denna metod StoreInSession() anropas ifrån ThemeEngineRender i Origin och gör 
arrayen $this-> data under $_SESSION med nyckel ifrån config.
*/
 
  	public function StoreInSession() {
    	$_SESSION[$this->key] = $this->data;
  	}


/*I denna funktion PopulateFromSession skrivs innehållet i $this->data['flash'] över till $this->flash
och innehållet i $this->data['flash'] raderas sedan.
detta sker vid varje förfrågan eftersom anrop kommer ifrån konstruktorn i Origin.
-detta har att göra med att med att meddelanden skall överleva två sidanrop
men inte mer. *se att det är skillnad så tilllvida att funktion AddMessages lagrar till 
$this->data['flash']['messages'][] och på att Get Messages returnerar ifrån 
$this->flash['messages']
*/



  	public function PopulateFromSession() {
    	if(isset($_SESSION[$this->key])) {
      	$this->data = $_SESSION[$this->key];
      	if(isset($this->data['flash'])) {
       $this->flash = $this->data['flash'];
       unset($this->data['flash']);
      }
    }
  }


}