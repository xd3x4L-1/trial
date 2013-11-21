<?php

	class CViewContainer {

        
       private $data = array();
       private $views = array();
        
	public function __construct() { ; }


/*funktion GetData() anropas av ThemeEngineRender i Origin
för att $data=array() skall bli tillgänglig för templatefilen i form av variabler.
*/

  	public function GetData() { return $this->data; }



/*Index() i CCGuestbook ropar på metoden SetTitle i CViewcontainer med medskickad sträng 'Lydia Guestbook Example'
och metoden SetTitle skickar vidare till metoden SetVariable i CViewcontaoner och på så vis
ges att $this->data['title']='Lydia Guestbook Example';
*/
  
       public function SetTitle($value) {
       $this->SetVariable('title', $value);
  	}

       public function SetVariable($key, $value) {
       $this->data[$key] = $value;
  	}


/*metoden ReadAll i CMguestbook anropas för objektet guestbookModel och den
anropar metoden ExecuteSelectQueryandFetchAll som finns i CMDatabase och resultatet läggs till arrayen 
under nyckel 'entries'.

Vad som sen sker är att denna array med variabler görs tillgänglig för filen __DIR__ . '/index.tpl.php'
via metoden Addinclude.
*/

       public function AddInclude($file, $variables=array()) {
       $this->views[] = array('type' => 'include', 'file' => $file, 'variables' => $variables);
  	}

       public function Render() {
       foreach($this->views as $view) {
      	switch($view['type']) {
       case 'include':
       	extract($view['variables']);
          	include($view['file']);
          	break;
      }
         }
  }


}