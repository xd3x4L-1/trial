 <?php
/**
 * Parse the request and identify controller, method and arguments.
 *
 * @package TrialCore
 */
class CRequest {

  /**
   * Member variables
   */
  public $cleanUrl;
  public $querystringUrl;


  /**
   * Constructor
   *
   * Decide which type of url should be generated as outgoing links.
   * default      = 0      => index.php/controller/method/arg1/arg2/arg3
   * clean        = 1      => controller/method/arg1/arg2/arg3
   * querystring  = 2      => index.php?q=controller/method/arg1/arg2/arg3
   *
   * @param boolean $urlType integer 

   */


/* Funktionen anv�nder som default l�nktyp=0 som st�llt in i config via $Origo->config['url_in'] = 0;
*/

  public function __construct($urlType) {
  
    $this->cleanUrl       = $urlType= 1 ? true : false;
    $this->querystringUrl = $urlType= 2 ? true : false;


  }

  /**
   * Create a url in the way it should be created.
   *
   */

/* h�r byggs utg�ende l�nkar f�r anrop av kontroller och metod upp.
dvs inkluderat den del av l�nken som kommer med $url och den f�re $query som tagits fram i CRequest::init().
returneras en fullst�ndig adress.
*/

  public function CreateUrl($url=null) {
    $prepend = $this->base_url;
    if($this->cleanUrl) {
      ;
    } elseif ($this->querystringUrl) {
      $prepend .= 'index.php?q=';
    } else {
      $prepend .= 'index.php/';
    }
    return $prepend . rtrim($url, '/');
  }

  /**
   * Parse the current url request and divide it in controller, method and arguments.
   *
   * Calculates the base_url of the installation. Stores all useful details in $this.
   *
   * @param $baseUrl string use this as a hardcoded baseurl.
   */
  public function Init($baseUrl = null) {


/*vid drift p� www.bth.se motsvarar $_SERVER['SCRIPT_NAME'] /~boer13/phpmvc/kmom02/trial/index.php
d� alla l�nkar om de har r�tt konstruktion omdiregera av .htacces.
*/

// Take current url and divide it in controller, method and arguments

$requestUri = $_SERVER['REQUEST_URI'];

$scriptPart = $scriptName = $_SERVER['SCRIPT_NAME'];    

/*($requestUri, $scriptName, 0, strlen($scriptName) )   genererar ett positivt tal om echo $_SERVER['REQUEST_URI'] �r l�ngre �n echo $_SERVER['SCRIPT_NAME'];
och ett negativt om l�ngden �r kortare och med nuvarande namn p� filerna f�r controllers kan l�ngden av l�nk p� form controller/method/arg1/arg2/arg3
 ej bli lika l�ngden p� /~boer13/phpmvc/kmom02/trial/index.php s� om l�nken har form controller/method/arg1/arg2/arg3 sker exekvering i if-delen.

*/

/*F�r falla att l�nken har form index.php/controller/method/arg1/arg2/arg3 eller index.php?q=controller/method/arg1/arg2/arg3
blir v�rdet av ($requestUri, $scriptName, 0, strlen($scriptName) )=0 och exekvering av del i if-del sker ej.
*/

/*$a=substr_compare($requestUri, $scriptName, 0, strlen($scriptName));
att anv�nda f�r kontroll av funktion.
*/



 // Check if url is in format controller/method/arg1/arg2/arg3
    if(substr_compare($requestUri, $scriptName, 0, strlen($scriptName))) {
      $scriptPart = dirname($scriptName);
    }








 

/*h�r tas den del av inkommande l�nk som ligger bakom   /~boer13/phpmvc/kmom02/trial/ fram
*/
    $query = trim(substr($requestUri, strlen(rtrim($scriptPart, '/'))), '/');  

/*om ist�llet  f�rsta tecknet i $query �r ett fr�getecken st�lls $query om till $_GET['q'].
*/

 $pos = strcspn($query, '?');
    if($pos) {
      $query = substr($query, 0, $pos);
    }









    // Check if this looks like a querystring approach link
    if(substr($query, 0, 1) === '?' && isset($_GET['q'])) {
      $query = trim($_GET['q']);
    }

    $splits = explode('/', $query);
    
    // Set controller, method and arguments

/*om controller och metod ej ges av inkommande l�nk anv�nds kontroller index och metod index som default.
*/

    $controller =  !empty($splits[0]) ? $splits[0] : 'index';
    $method     =  !empty($splits[1]) ? $splits[1] : 'index';
    $arguments = $splits;
    unset($arguments[0], $arguments[1]); // remove controller & method part from argument list
    
    // Prepare to create current_url and base_url
    $currentUrl = $this->GetCurrentUrl();


/* h�r delas nuvarande url upp i sina best�ende delar.
*/
    $parts       = parse_url($currentUrl);

/* en url till installationskatalogen byggs av delar. F�r studentservern g�ller http, www.student.bth.se ,/~boer13/phpmvc/kmom02/trial/
*/
    $baseUrl     = !empty($baseUrl) ? $baseUrl : "{$parts['scheme']}://{$parts['host']}" . (isset($parts['port']) ? ":{$parts['port']}" : '') . rtrim(dirname($scriptName), '/');


    
    // Store it
    $this->base_url     = rtrim($baseUrl, '/') . '/';
    $this->current_url  = $currentUrl;
    $this->request_uri  = $requestUri;
    $this->script_name  = $scriptName;
    $this->query        = $query;
    $this->splits        = $splits;
    $this->controller    = $controller;
    $this->method        = $method;
    $this->arguments    = $arguments;
  }


  /**
   * Get the url to the current page. 
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

} 