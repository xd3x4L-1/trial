 <?php
/**
 * Controller for development and testing purpose, helpful methods for the developer.
 * 
 * @package TrialCore
 */

/* i denna klass byggs variabler upp fˆr att vara tillg‰nglig
fˆr ramverkets temamotor.
*/


class CCDeveloper implements IController {

  /**
    * Implementing interface IController. All controllers must have an index action.
   */
  public function Index() {  
    $this->Menu();
  }


  /**
    * Create a list of links in the supported ways.
   */
  public function Links() {  
    $this->Menu();
    
    $Origo = Origin::Instance();
    
    $url = 'developer/links';
    $current      = $Origo->request->CreateUrl($url);

/*fˆr att skapa olika typer av l‰nkar ‰r i tur och ordning default, clenurl och querystring
givna till true.
*/

    $Origo->request->cleanUrl = false;
    $Origo->request->querystringUrl = false;    
    $default      = $Origo->request->CreateUrl($url);
    
    $Origo->request->cleanUrl = true;
    $clean        = $Origo->request->CreateUrl($url);    
    
    $Origo->request->cleanUrl = false;
    $Origo->request->querystringUrl = true;    
    $querystring  = $Origo->request->CreateUrl($url);
    
    $Origo->data['main'] .= <<<EOD
<h2>CRequest::CreateUrl()</h2>
<p>Here is a list of urls created using above method with various settings. All links should lead to
this same page.</p>
<ul>
<li><a href='$current'>This is the current setting</a>
<li><a href='$default'>This would be the default url</a>
<li><a href='$clean'>This should be a clean url</a>
<li><a href='$querystring'>This should be a querystring like url</a>
</ul>
<p>Enables various and flexible url-strategy.</p>
EOD;
  }


  /**
    * Create a method that shows the menu, same for all methods
   */
  private function Menu() {  

/*H√§r h√§mtas befintligt objekt av klassen Origin.
*/  
  
    $Origo = Origin::Instance();
	
/*h√§r byggs en lista med denna kontrollers metoder upp.
*/		
	
    $menu = array('developer', 'developer/index', 'developer/links');  
    $html = null;
	
    foreach($menu as $val) {
      $html .= "<li><a href='" . $Origo->request->CreateUrl($val) . "'>$val</a>";  
    }
    
    $Origo->data['title'] = "Developer";
    $Origo->data['main'] = <<<EOD
<h1>The Developer Controller</h1>
<p>This is what you can do for now:</p>
<ul>
$html
</ul>
EOD;
  }
  
}  