<?php

/*om en f�rfr�gan inkommer utan angiven kontroller och utan angiven metod anv�nds 
denna kontroller och metod index.

Ett objekt av klassen skapas vid anrop med PHP reflection i klass Origin metod FrontControllerRoute()
och som en f�lj av detta laddas filen och konstruktorn anropar konstruktorn i 
klass CObjec.
*/
	class CCIndex extends CObject implements IController {

  	public function __construct() {
    	parent::__construct();
 	}


/* Index() �r enda metod och den anropar functionen menu som ger variablerna $menu, $main v�rden.
D� variable $main byggs anropas metod CreateUrl($val) f�r att ta fram fullst�ndiga l�nkar till varje val
som �r tillg�ngligt i ramverket. Dessa l�ggs ivariabel $html  som sedan inryms i main.
resultatet blir $this->data['title'] och $this->data['main'].
*/

 /**
* Implementing interface IController. All controllers must have an index action.
*/
  public function Index() {                        
    $this->views->SetTitle('Index Controller');
    $this->views->AddInclude(__DIR__ . '/index.tpl.php', array('menu'=>$this->Menu()));
  }
     

   /**
* A menu that shows all available controllers/methods
*/
  private function Menu() {        
    $items = array();
    foreach($this->config['controllers'] as $key => $val) {
      if($val['enabled']) {
        $rc = new ReflectionClass($val['class']);
        $items[] = $key;
        $methods = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
        foreach($methods as $method) {
          if($method->name != '__construct' && $method->name != '__destruct' && $method->name != 'Index') {
            $items[] = "$key/" . mb_strtolower($method->name);
          }
        }
      }
    }
    return $items;
  }
        
}  