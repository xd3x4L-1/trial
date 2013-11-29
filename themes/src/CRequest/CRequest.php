 <?php

	class CRequest {

  public $cleanUrl;
  public $querystringUrl;

/* 
I config finns $Origo->config['url_type'] = 1;
och d�rf�r ges i konstruktorn att  $this->cleanUrl=true.

Decide which type of url should be generated as outgoing links.
default      = 0      => index.php/controller/method/arg1/arg2/arg3
clean        = 1      => controller/method/arg1/arg2/arg3
querystring  = 2      => index.php?q=controller/method/arg1/arg2/arg3
@param boolean $urlType integer 
*/

  
	public function __construct($urlType) {
  
    	$this->cleanUrl       = $urlType= 1 ? true : false;
    	$this->querystringUrl = $urlType= 2 ? true : false;
	}


/*vid drift p� www.bth.se motsvarar $_SERVER['SCRIPT_NAME'] /~boer13/phpmvc/kmom02/trial/index.php
d� alla l�nkar om de har r�tt konstruktion omdiregera av .htacces.

F�rst i metoden ges $requestUri, $scriptPart v�rden ifr�n $SERVER och det �r det
som g�r att metoden kan arbeta med den l�nk som fr�gat efter den sida som k�r koden.

($requestUri, $scriptName, 0, strlen($scriptName) ) genererar ett positivt tal om echo $_SERVER['REQUEST_URI'] 
�r l�ngre �n echo $_SERVER['SCRIPT_NAME'];
och ett negativt om l�ngden �r kortare och med nuvarande namn p� filerna f�r controllers kan l�ngden av l�nk p� 
form controller/method/arg1/arg2/arg3
ej bli lika l�ngden p� /~boer13/phpmvc/kmom02/trial/index.php s� om l�nken har form controller/method/arg1/arg2/arg3 
sker exekvering i if-delen.

F�r fall att l�nken har form index.php/controller/method/arg1/arg2/arg3 eller index.php?q=controller/method/arg1/arg2/arg3
blir v�rdet av ($requestUri, $scriptName, 0, strlen($scriptName) )=0 och exekvering av del i if-del sker ej.

$a=substr_compare($requestUri, $scriptName, 0, strlen($scriptName));
g�r att anv�nda f�r kontroll av funktion.
Check if url is in format controller/method/arg1/arg2/arg3

D�refter att kontroll av l�nktyp fullgjorts 
tas den del av inkommande l�nk som ligger bakom /~boer13/phpmvc/kmom02/trial/ fram.

om del enligt ovan inneh�ller ett fr�getecken p� position [0] st�lls $query om till $_GET['q'].


D�refter g�ller att om controller och metod ej ges av inkommande l�nk anv�nds kontroller index och metod index 
som default.

Metod Init($baseUrl = null) tart sedan fram den fullst�ndiga adressen (inte bara den lokala)
till efterfr�gad sida genom anropa metoden GetCurrentUrl().

I Init($baseUrl = null) delas d�refter efrerfr�gad fullst�ndig url till sina delar 
och dessa anv�nds sedan f�r att bygga en fullst�ndig adress till ramverkets installationskatalog.
En url till installationskatalogen byggs av delar. F�r studentservern g�ller http, www.student.bth.se ,/~boer13/phpmvc/kmom02/trial/

Sisti metoden Init($baseUrl = null) sparas alla v�rden som tagits fram i variabler.

*/


 /**
* Parse the current url request and divide it in controller, method and arguments.
*
* Calculates the base_url of the installation. Stores all useful details in $this.
*
* @param $baseUrl string use this as a hardcoded baseurl.
*/
  public function Init($baseUrl = null) {
    $requestUri = $_SERVER['REQUEST_URI'];
    $scriptName = $_SERVER['SCRIPT_NAME'];
    
    // Compare REQUEST_URI and SCRIPT_NAME as long they match, leave the rest as current request.
    $i=0;
    $len = min(strlen($requestUri), strlen($scriptName));
    while($i<$len && $requestUri[$i] == $scriptName[$i]) {
      $i++;
    }
    $request = trim(substr($requestUri, $i), '/');
  
    // Remove the ?-part from the query when analysing controller/metod/arg1/arg2
    $queryPos = strpos($request, '?');
    if($queryPos !== false) {
      $request = substr($request, 0, $queryPos);
    }
    
    // Check if request is empty and querystring link is set
    if(empty($request) && isset($_GET['q'])) {
      $request = trim($_GET['q']);
    }
    $splits = explode('/', $request);
    
    // Set controller, method and arguments
    $controller = !empty($splits[0]) ? $splits[0] : 'index';
    $method                 = !empty($splits[1]) ? $splits[1] : 'index';
    $arguments = $splits;
    unset($arguments[0], $arguments[1]); // remove controller & method part from argument list
    
    // Prepare to create current_url and base_url
    $currentUrl = $this->GetCurrentUrl();
    $parts          = parse_url($currentUrl);
    $baseUrl                 = !empty($baseUrl) ? $baseUrl : "{$parts['scheme']}://{$parts['host']}" . (isset($parts['port']) ? ":{$parts['port']}" : '') . rtrim(dirname($scriptName), '/');
    
    // Store it
    $this->base_url          = rtrim($baseUrl, '/') . '/';
    $this->current_url = $currentUrl;
    $this->request_uri = $requestUri;
    $this->script_name = $scriptName;
    $this->request = $request;
    $this->splits         = $splits;
    $this->controller         = $controller;
    $this->method         = $method;
    $this->arguments = $arguments;
  }

/*Metod function GetCurrentUrl tar sedan fram den fullst�ndiga adressen (inte bara den lokala)
till efterfr�gad sida genom anropa metoden med hj�lp av skrivna data f�r serverns port och variabler av typ
$SERVER[].
*/



  	public function GetCurrentUrl() {
    	$url = "http";
    	$url .= (@$_SERVER["HTTPS"] == "on") ? 's' : '';
    	$url .= "://";
    	$serverPort = ($_SERVER["SERVER_PORT"] == "80") ? '' :
    	(($_SERVER["SERVER_PORT"] == 443 && @$_SERVER["HTTPS"] == "on") ? '' : ":{$_SERVER['SERVER_PORT']}");
    	$url .= $_SERVER["SERVER_NAME"] . $serverPort . htmlspecialchars($_SERVER["REQUEST_URI"]);
    	return $url;
  	}


/*Denna metod public function CreateUrl($url=null, $method=null) 
anv�ns f�r att utifr�n en variabel $url inneh�llande controller och metod taga fram olika
former av l�nkar.
Metoden anv�nds av developer/links f�r att visas f�r anv�ndaren.
*/


	 /**
         * Create a url in the way it should be created.
         *
         * @param $url string the relative url or the controller
         * @param $method string the method to use, $url is then the controller or empty for current
         * @param $arguments string the extra arguments to send to the method
         */
        public function CreateUrl($url=null, $method=null, $arguments=null) {
    // If fully qualified just leave it.
                if(!empty($url) && (strpos($url, '://') || $url[0] == '/')) {
                        return $url;
                }
    
    // Get current controller if empty and method or arguments choosen
    if(empty($url) && (!empty($method) || !empty($arguments))) {
      $url = $this->controller;
    }
    
    // Get current method if empty and arguments choosen
    if(empty($method) && !empty($arguments)) {
      $method = $this->method;
    }
    
    // Create url according to configured style
    $prepend = $this->base_url;
    if($this->cleanUrl) {
      ;
    } elseif ($this->querystringUrl) {
      $prepend .= 'index.php?q=';
    } else {
      $prepend .= 'index.php/';
    }
    $url = trim($url, '/');
    $method = empty($method) ? null : '/' . trim($method, '/');
    $arguments = empty($arguments) ? null : '/' . trim($arguments, '/');
    return $prepend . rtrim("$url$method$arguments", '/');
  }


  

	}