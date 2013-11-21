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

       public $config;
       public $data;
	public $request;
	public $views;
	public $db;
	public $session;

/* I konstruktorn som anropas ifr�n de olika kontrolleklasserna och ifr�n modellen CMDatabase 
ges medlemsvariablerna till�telse att vara skrivs�tt f�r v�rden som tagit fram i Origin.
*/

      	protected function __construct() {

      	$Origo = Origin::Instance();
    	$this->config = &$Origo->config;
    	$this->request = &$Origo->request;
    	$this->data = &$Origo->data;
	$this->db       = &$Origo->db;
	$this->views    = &$Origo->views;
	$this->session  = &$Origo->session;
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

      	protected function RedirectTo($url) {

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
    	header('Location: ' . $this->request->CreateUrl($url));
  	}
  
  
  
  
  
  
  
  

}