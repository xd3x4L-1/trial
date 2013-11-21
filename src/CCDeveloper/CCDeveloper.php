 <?php

/*Ett objekt av klassen skapas vid anrop med PHP reflection i klass Origin metod FrontControllerRoute()
och som en f�lj av detta laddas filen och konstruktorn anropar konstruktorn i 
klass CObject.
*/

	class CCDeveloper extends CObject implements IController {

  	public function __construct() {
    	parent::__construct();
  	}
  
/* Index() anropar functionen menu som ger variablerna $menu, $main v�rden.
D� variable $main byggs anropas metod CreateUrl($val) f�r att ta fram fullst�ndiga l�nkar till varje val
som �r tillg�ngligt i ramverket. Dessa l�ggs ivariabel $html  som sedan inryms i main.
resultatet blir $this->data['title'] och $this->data['main'].
*/

  	public function Index() {  
    	$this->Menu();
  	}


/*Funktion anropar f�rst meny och d�rf�r ges $this->data['main']till allt inneh�ll som ges av 
d� anropet kommer ifr�n index men sedan skappas ytterliggare inneh�ll i denna funktion
s� att ytterliggare inneh�ll tillkommer l�ngre ned p� sidan n�r $this->data['main']
skrivs ut av templatefilen.

till att b�rja med �r den url typ som sedan skapas styrd av config[url_type] 
och d�rf�r blir den l�nk som ges $current av typen clean url.

d� �ndras $this->request->cleanUrl = false; och l�nk default blir d�rf�r p� typen
index.php/developer/links

I n�sta l�nk $clean har cleanurl �ter givits v�rdet true och ytterliggare en l�nk av typen clean url skapas.

till sist �ndras v�rdet f�r $this->request->querystringUrl till true och den
sista l�nk blir d� p� formen index.php?q=developer/links.

till sist l�ggs skapade l�nkar och en del text till $this->data['main'] enligt inledande stycke.

*/

  	public function Links() {  
    
	$this->Menu();
    	$url = 'developer/links';
    	$current      = $this->request->CreateUrl($url);

    	$this->request->cleanUrl = false;
    	$this->request->querystringUrl = false; 
    	$default      = $this->request->CreateUrl($url);
    
   	$this->request->cleanUrl = true;
    	$clean        = $this->request->CreateUrl($url);    
    
    	$this->request->cleanUrl = false;
    	$this->request->querystringUrl = true;
	$querystring  = $this->request->CreateUrl($url);
 
    	$this->data['main'] .= <<<EOD
	<h2>CRequest::CreateUrl()</h2>
	<p>Here is a list of urls created using above method with various settings. All links should lead to this same page.</p>
	<ul>
	<li><a href='$current'>This is the current setting</a>
	<li><a href='$default'>This would be the default url</a>
	<li><a href='$clean'>This should be a clean url</a>
	<li><a href='$querystring'>This should be a querystring like url</a>
	</ul>
	<p>Enables various and flexible url-strategy.</p>
EOD;
  	}



/*Funktion anropar f�rst meny och d�rf�r ges $this->data['main']till allt inneh�ll som ges av 
d� anropet kommer ifr�n index men sedan skappas ytterliggare inneh�ll i denna funktion
s� att ytterliggare inneh�ll tillkommer l�ngre ned p� sidan n�r $this->data['main']
skrivs ut av templatefilen.

print_r($this, true) behandlar objektet som en array med objektets variabler som nycklar 
och d� CCDEveloper �rver variabler ifr�n CObject som h�ller en instans av 
Origin s� kommer �ven variabler ifr�n objekt som skapats i Origin till.

till sist l�ggs skapade l�nkar och en del text till $this->data['main'] enligt inledande stycke.

*/

	public function DisplayObject() {   
     
       $this->Menu();
                
      	$this->data['main'] .= <<<EOD
	<h2>Dumping content of CDeveloper</h2>
	<p>Here is the content of the controller, including properties from CObject which holds access to common resources in Origin.</p>
EOD;
        $this->data['main'] .= '<pre>' . htmlent(print_r($this, true)) . '</pre>';
        }


/* Index(), Links() och DisplayObject() anropar functionen menu som ger variablerna $menu, $main v�rden.
D� variable $main byggs anropas metod CreateUrl($val) f�r att ta fram fullst�ndiga l�nkar till varje val
som �r tillg�ngligt i ramverket. Dessa l�ggs i variabel $html som sedan inryms i main.
resultatet blir $this->data['title'] och $this->data['main'].
*/

  	private function Menu() {  

    	$Origo = Origin::Instance();
		
	$menu = array('index', 'index/index', 'developer', 'developer/index', 'developer/links', 'developer/display-object', 'guestbook');
    
    	$html = null;
	
    	foreach($menu as $val) {
      	$html .= "<li><a href='" . $this->request->CreateUrl($val) . "'>$val</a>";  
    	}
    
    	$this->data['title'] = "Developer";
    	$this->data['main'] = <<<EOD
	<h1>The Developer Controller</h1>
	<p>This is what you can do for now:</p>
	<ul>
	$html
	</ul>
EOD;
  }
  
}  