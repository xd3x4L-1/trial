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

		private function Menu() {     
   
       $menu = array(
        'index', 'index/index', 'developer', 'developer/index', 'developer/links',
        'developer/display-object', 'guestbook',
       );
   
    	$html = null;
    	foreach($menu as $val) {
    	$html .= "<li><a href='" . $this->request->CreateUrl($val) . "'>$val</a>";
       }
                
    	$this->data['title'] = "The Index Controller";

	$this->data['main'] = <<<EOD
	<h1>The Index Controller</h1>
	<p>This is what you can do for now:</p>
	<ul>
	$html
	</ul>
EOD;
 } 	
        
}  