<?php

/*Ett objekt av klassen skapas vid anrop med PHP reflection i klass Origin metod FrontControllerRoute()
och som en följ av detta laddas filen och konstruktorn anropar konstruktorn i 
klass CObject.
medlemsvariabeln $guestbookModel blir i konstruktorn till ett objekt av CMGuestbook och detta
objekt håller metoder för att initiera databasen, skriva till databasen, radera ur databasen, och
läsa alla meddelanden i databasen.
*/ 
  
	class CCGuestbook extends CObject implements IController {

  	private $guestbookModel;
  
  	public function __construct() {
    	parent::__construct();
    	$this->guestbookModel = new CMGuestbook();
  	}


/*den metod som alltid kallas om ingen metod anges är Index() och den återkallas också då
de den andra metoden handler () brukats eftersom den gör en redirect där bara kontroller ges och
Index() är default.

I origin har ett objekt views av CViewContainer skapats och detta är i metoden Index() tillgängligt 
då klassen ärver av CObject som häller en instans av Origin.

Index() ropar först på metoden SetTitle i CViewcontainer med medskickad sträng 'Lydia Guestbook Example'
och metoden SetTitle skickar vidare till metoden SetVariable i CViewcontaoner och på så vis
ges att $this->data['title']='Lydia Guestbook Example';

vad som sker därefter är att metoden ReadAll i CMguestbook anropas för objektet guestbookModel och den
anropar metoden ExecuteSelectQueryandFetchAll som finns i CMDatabase och resultatet läggs till arrayen 
under nyckel 'entries'.

Vad som sen sker är att denna array med variabler görs tillgänglig för filen __DIR__ . '/index.tpl.php'
via metoden Addinclude i CViewContainer.

I slutet av filen __DIR__ . '/index.tpl.php' finns kod för att skriva in innehållet i 'entries till,
olika <div>element.

därefter ges variabel $formAction värdet http://www.student.bth.se/~boer13/phpmvc/kmom03/trial/guestbook/handler
föra att metoden handler skall anropas om någon av knapparna i formuläret som ges av
__DIR__ . '/index.tpl.php' trycks in.


*/

  	public function Index() {

    	$this->views->SetTitle('Lydia Guestbook Example');
    
	$this->views->AddInclude(__DIR__ . '/index.tpl.php', array(
      	'entries'=>$this->guestbookModel->ReadAll(),
      	'formAction'=>$this->request->CreateUrl('guestbook/handler')
	  
    	));
  	}
  

/*funktionen Handler vidtar göromål om någon av knapparna i formuläret trycks in och
för olika knappar ropas på metoder i CMGuestbook.
Add($entry) lagrar ett meddelande till databasen, DeleteAll()raderar alla meddelanden ur databasen, 
och Init()skapar en tabell guestbook i databasen om den inte redan finnns.

funktionen omdirigerar sedan till http://www.student.bth.se/~boer13/phpmvc/kmom03/trial/guestbook
med hjälp av metoden RedirectTo($url) som finns i CObject.

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