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


/* Funktionen använder som default länktyp=0 som ställt in i config via $Origo->config['url_in'] = 0;
*/

  public function __construct($urlType) {
  
    $this->cleanUrl       = $urlType= 1 ? true : false;
    $this->querystringUrl = $urlType= 2 ? true : false;


  }

  /**
   * Create a url in the way it should be created.
   *
   */

/* här byggs utgående länkar för anrop av kontroller och metod upp.
dvs inkluderat den del av länken som kommer med $url och den före $query som tagits fram i CRequest::init().
returneras en fullständig adress.
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


/*vid drift på www.bth.se motsvarar $_SERVER['SCRIPT_NAME'] /~boer13/phpmvc/kmom02/trial/index.php
då alla länkar om de har rätt konstruktion omdiregera av .htacces.
*/

// Take current url and divide it in controller, method and arguments

$requestUri = $_SERVER['REQUEST_URI'];

$scriptPart = $scriptName = $_SERVER['SCRIPT_NAME'];    

/*($requestUri, $scriptName, 0, strlen($scriptName) )   genererar ett positivt tal om echo $_SERVER['REQUEST_URI'] är längre än echo $_SERVER['SCRIPT_NAME'];
och ett negativt om längden är kortare och med nuvarande namn på filerna för controllers kan längden av länk på form controller/method/arg1/arg2/arg3
 ej bli lika längden på /~boer13/phpmvc/kmom02/trial/index.php så om länken har form controller/method/arg1/arg2/arg3 sker exekvering i if-delen.

*/

/*För falla att länken har form index.php/controller/method/arg1/arg2/arg3 eller index.php?q=controller/method/arg1/arg2/arg3
blir värdet av ($requestUri, $scriptName, 0, strlen($scriptName) )=0 och exekvering av del i if-del sker ej.
*/

/*$a=substr_compare($requestUri, $scriptName, 0, strlen($scriptName));
att använda för kontroll av funktion.
*/



 // Check if url is in format controller/method/arg1/arg2/arg3
    if(substr_compare($requestUri, $scriptName, 0, strlen($scriptName))) {
      $scriptPart = dirname($scriptName);
    }








 

/*här tas den del av inkommande länk som ligger bakom   /~boer13/phpmvc/kmom02/trial/ fram
*/
    $query = trim(substr($requestUri, strlen(rtrim($scriptPart, '/'))), '/');  

/*om istället  första tecknet i $query är ett frågetecken ställs $query om till $_GET['q'].
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

/*om controller och metod ej ges av inkommande länk används kontroller index och metod index som default.
*/

    $controller =  !empty($splits[0]) ? $splits[0] : 'index';
    $method     =  !empty($splits[1]) ? $splits[1] : 'index';
    $arguments = $splits;
    unset($arguments[0], $arguments[1]); // remove controller & method part from argument list
    
    // Prepare to create current_url and base_url
    $currentUrl = $this->GetCurrentUrl();


/* här delas nuvarande url upp i sina bestående delar.
*/
    $parts       = parse_url($currentUrl);

/* en url till installationskatalogen byggs av delar. För studentservern gäller http, www.student.bth.se ,/~boer13/phpmvc/kmom02/trial/
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