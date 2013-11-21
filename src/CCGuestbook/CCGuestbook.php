<?php

/*Ett objekt av klassen skapas vid anrop med PHP reflection i klass Origin metod FrontControllerRoute()
och som en f�lj av detta laddas filen och konstruktorn anropar konstruktorn i 
klass CObject.
medlemsvariabeln $guestbookModel blir i konstruktorn till ett objekt av CMGuestbook och detta
objekt h�ller metoder f�r att initiera databasen, skriva till databasen, radera ur databasen, och
l�sa alla meddelanden i databasen.
*/ 
  
	class CCGuestbook extends CObject implements IController {

  	private $guestbookModel;
  
  	public function __construct() {
    	parent::__construct();
    	$this->guestbookModel = new CMGuestbook();
  	}


/*den metod som alltid kallas om ingen metod anges �r Index() och den �terkallas ocks� d�
de den andra metoden handler () brukats eftersom den g�r en redirect d�r bara kontroller ges och
Index() �r default.

I origin har ett objekt views av CViewContainer skapats och detta �r i metoden Index() tillg�ngligt 
d� klassen �rver av CObject som h�ller en instans av Origin.

Index() ropar f�rst p� metoden SetTitle i CViewcontainer med medskickad str�ng 'Lydia Guestbook Example'
och metoden SetTitle skickar vidare till metoden SetVariable i CViewcontaoner och p� s� vis
ges att $this->data['title']='Lydia Guestbook Example';

vad som sker d�refter �r att metoden ReadAll i CMguestbook anropas f�r objektet guestbookModel och den
anropar metoden ExecuteSelectQueryandFetchAll som finns i CMDatabase och resultatet l�ggs till arrayen 
under nyckel 'entries'.

Vad som sen sker �r att denna array med variabler g�rs tillg�nglig f�r filen __DIR__ . '/index.tpl.php'
via metoden Addinclude i CViewContainer.

I slutet av filen __DIR__ . '/index.tpl.php' finns kod f�r att skriva in inneh�llet i 'entries till,
olika <div>element.

d�refter ges variabel $formAction v�rdet http://www.student.bth.se/~boer13/phpmvc/kmom03/trial/guestbook/handler
f�ra att metoden handler skall anropas om n�gon av knapparna i formul�ret som ges av
__DIR__ . '/index.tpl.php' trycks in.


*/

  	public function Index() {

    	$this->views->SetTitle('Lydia Guestbook Example');
    
	$this->views->AddInclude(__DIR__ . '/index.tpl.php', array(
      	'entries'=>$this->guestbookModel->ReadAll(),
      	'formAction'=>$this->request->CreateUrl('guestbook/handler')
	  
    	));
  	}
  

/*funktionen Handler vidtar g�rom�l om n�gon av knapparna i formul�ret trycks in och
f�r olika knappar ropas p� metoder i CMGuestbook.
Add($entry) lagrar ett meddelande till databasen, DeleteAll()raderar alla meddelanden ur databasen, 
och Init()skapar en tabell guestbook i databasen om den inte redan finnns.

funktionen omdirigerar sedan till http://www.student.bth.se/~boer13/phpmvc/kmom03/trial/guestbook
med hj�lp av metoden RedirectTo($url) som finns i CObject.

*/

  public function Handler() {
    if(isset($_POST['doAdd'])) {
      $this->guestbookModel->Add(strip_tags($_POST['newEntry']));
    }
    elseif(isset($_POST['doClear'])) {
      $this->guestbookModel->DeleteAll();
    }
    elseif(isset($_POST['doCreate'])) {
      $this->guestbookModel->Init();
    }
    $this->RedirectTo($this->request->CreateUrl($this->request->controller));
  }
  

} 