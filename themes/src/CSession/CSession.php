<?php

/* Denna klass innehar funktioner f�r att hantera meddelanden till anv�ndaren dels AddMessage och GetMessage som
ger anv�ndaren i klartext uppgift om vilka fr�gor som utf�rts mot databasen och utskrift sker l�ngt upp i
trial/Guestbook. 
dels metoderna SetFlash och GetFlash som anv�nds f�r att ge anv�ndren information om
tids�tg�ng f�r fr�gor mot databasen, antalet fr�gor f�r varje beg�rd �tg�rd av anv�ndaren och
den direkta SQL-kod som anv�nds vid fr�gorna
*/

	class CSession {

       private $key;
       private $data = array();
       private $flash = null;
        
/* d� konstruktorn anropas pga av att ett nytt objekt av klassen skapas i Origins konstruktor
s� ges medlemsvariablen det v�rde som finns f�rinst�lldt i config.php. F�r 
n�rvarande �r detta v�rde = 'trial'.
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
varje utf�rd fr�ga mot datavasen av metod Handler i CCGuestbook.
alla nycklar - 'database_numQueries', 'database_queries', 'timer' anv�nds varje g�ng och 
b� ers�tts v�rdet f�r denna nyckel med ett nytt.

Funktionen lagrar de v�rden om antalet fr�gor, fr�gorna i sig och tiden f�r att
utskrift senare skall kunna ske under sidfoten i trial/guestbook.
*/
  	public function SetFlash($key, $value) {
    	$this->data['flash'][$key] = $value;
  	}


/*denna funktion GetFlash($key, $value) anropas av html-dokumentet i filem themes/core/default.tp1.php
via metoden get_debug() i filen themes/functions.php f�r att det inneh�ll som lagrats i 
funktion SetFlash($key, $value) lagrat till ['flash'][$key] via get_debug skall
kunna skrivas ut nedanf�r fotern i /trial/guestbook.
*/

  	public function GetFlash($key) {
    	return isset($this->flash[$key]) ? $this->flash[$key] : null;
  	}





/*AddMessage anropas ifr�n metoderna Add($entry), DeleteAll(), Init() i CMGuestbook
f�r att metoden skall lagra ett meddelande till anv�ndaren om vad som utf�ts i fr�gor till
databasen
*/
  	public function AddMessage($type, $message) {
    	$this->data['flash']['messages'][] = array('type' => $type, 'message' => $message);
  	}


        public function GetMessages() {
    	return isset($this->flash['messages']) ? $this->flash['messages'] : null;
  	}




/*Denna metod StoreInSession() anropas ifr�n ThemeEngineRender i Origin och g�r 
arrayen $this-> data under $_SESSION med nyckel ifr�n config.
*/
 
  	public function StoreInSession() {
    	$_SESSION[$this->key] = $this->data;
  	}


/*I denna funktion PopulateFromSession skrivs inneh�llet i $this->data['flash'] �ver till $this->flash
och inneh�llet i $this->data['flash'] raderas sedan.
detta sker vid varje f�rfr�gan eftersom anrop kommer ifr�n konstruktorn i Origin.
-detta har att g�ra med att med att meddelanden skall �verleva tv� sidanrop
men inte mer. *se att det �r skillnad s� tilllvida att funktion AddMessages lagrar till 
$this->data['flash']['messages'][] och p� att Get Messages returnerar ifr�n 
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