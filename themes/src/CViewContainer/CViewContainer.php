<?php

	class CViewContainer {

        
       private $data = array();
       private $views = array();
        
	public function __construct() { ; }


/*funktion GetData() anropas av ThemeEngineRender i Origin
f�r att $data=array() skall bli tillg�nglig f�r templatefilen i form av variabler.
*/

  	public function GetData() { return $this->data; }



/*Index() i CCGuestbook ropar p� metoden SetTitle i CViewcontainer med medskickad str�ng 'Lydia Guestbook Example'
och metoden SetTitle skickar vidare till metoden SetVariable i CViewcontaoner och p� s� vis
ges att $this->data['title']='Lydia Guestbook Example';
*/
  
       public function SetTitle($value) {
       return $this->SetVariable('title', $value);
  	}

       public function SetVariable($key, $value) {
       $this->data[$key] = $value;
	   return $this;
  	}


/*metoden ReadAll i CMguestbook anropas f�r objektet guestbookModel och den
anropar metoden ExecuteSelectQueryandFetchAll som finns i CMDatabase och resultatet l�ggs till arrayen 
under nyckel 'entries'.

Vad som sen sker �r att denna array med variabler g�rs tillg�nglig f�r filen __DIR__ . '/index.tpl.php'
via metoden Addinclude.
*/
  /**
         * Add a view as file to be included and optional variables.
         *
         * @param $file string path to the file to be included.
         * @param vars array containing the variables that should be avilable for the included file.
         */
        public function AddInclude($file, $variables=array()) {
         $this->views[] = array('type' => 'include', 'file' => $file, 'variables' => $variables);
         return $this;
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