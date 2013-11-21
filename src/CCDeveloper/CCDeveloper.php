 <?php

/*Ett objekt av klassen skapas vid anrop med PHP reflection i klass Origin metod FrontControllerRoute()
och som en följ av detta laddas filen och konstruktorn anropar konstruktorn i 
klass CObject.
*/

	class CCDeveloper extends CObject implements IController {

  	public function __construct() {
    	parent::__construct();
  	}
  
/* Index() anropar functionen menu som ger variablerna $menu, $main värden.
Då variable $main byggs anropas metod CreateUrl($val) för att ta fram fullständiga länkar till varje val
som är tillgängligt i ramverket. Dessa läggs ivariabel $html  som sedan inryms i main.
resultatet blir $this->data['title'] och $this->data['main'].
*/

  	public function Index() {  
    	$this->Menu();
  	}


/*Funktion anropar först meny och därför ges $this->data['main']till allt innehåll som ges av 
då anropet kommer ifrån index men sedan skappas ytterliggare innehåll i denna funktion
så att ytterliggare innehåll tillkommer längre ned på sidan när $this->data['main']
skrivs ut av templatefilen.

till att börja med är den url typ som sedan skapas styrd av config[url_type] 
och därför blir den länk som ges $current av typen clean url.

då ändras $this->request->cleanUrl = false; och länk default blir därför på typen
index.php/developer/links

I nästa länk $clean har cleanurl åter givits värdet true och ytterliggare en länk av typen clean url skapas.

till sist ändras värdet för $this->request->querystringUrl till true och den
sista länk blir då på formen index.php?q=developer/links.

till sist läggs skapade länkar och en del text till $this->data['main'] enligt inledande stycke.

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



/*Funktion anropar först meny och därför ges $this->data['main']till allt innehåll som ges av 
då anropet kommer ifrån index men sedan skappas ytterliggare innehåll i denna funktion
så att ytterliggare innehåll tillkommer längre ned på sidan när $this->data['main']
skrivs ut av templatefilen.

print_r($this, true) behandlar objektet som en array med objektets variabler som nycklar 
och då CCDEveloper ärver variabler ifrån CObject som häller en instans av 
Origin så kommer även variabler ifrån objekt som skapats i Origin till.

till sist läggs skapade länkar och en del text till $this->data['main'] enligt inledande stycke.

*/

	public function DisplayObject() {   
     
       $this->Menu();
                
      	$this->data['main'] .= <<<EOD
	<h2>Dumping content of CDeveloper</h2>
	<p>Here is the content of the controller, including properties from CObject which holds access to common resources in Origin.</p>
EOD;
        $this->data['main'] .= '<pre>' . htmlent(print_r($this, true)) . '</pre>';
        }


/* Index(), Links() och DisplayObject() anropar functionen menu som ger variablerna $menu, $main värden.
Då variable $main byggs anropas metod CreateUrl($val) för att ta fram fullständiga länkar till varje val
som är tillgängligt i ramverket. Dessa läggs i variabel $html som sedan inryms i main.
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