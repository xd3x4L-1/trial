<?php

/*om en förfrågan inkommer utan angiven kontroller och utan angiven metod används 
denna kontroller och metod index.

Ett objekt av klassen skapas vid anrop med PHP reflection i klass Origin metod FrontControllerRoute()
och som en följ av detta laddas filen och konstruktorn anropar konstruktorn i 
klass CObjec.
*/
	class CCIndex extends CObject implements IController {

  	public function __construct() {
    	parent::__construct();
 	}


/* Index() är enda metod och den anropar functionen menu som ger variablerna $menu, $main värden.
Då variable $main byggs anropas metod CreateUrl($val) för att ta fram fullständiga länkar till varje val
som är tillgängligt i ramverket. Dessa läggs ivariabel $html  som sedan inryms i main.
resultatet blir $this->data['title'] och $this->data['main'].
*/


       public function Index() {        
    	$this->Menu();
       }

 /**
         * Create a method that shows the menu, same for all methods
         */
        private function Menu() {        
                $menu = array(
                 'index', 'developer', 'developer/links',
                 'developer/display-object', 'guestbook', 'user', 'user/login', 'user/logout',
                 'user/profile', 'acp',
                );
                
    $this->views->SetTitle('Index Controller');
    $this->views->AddInclude(__DIR__ . '/index.tpl.php', array('menu'=>$menu));
  }
        
}  