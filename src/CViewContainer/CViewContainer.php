<?php

	class CViewContainer {

        
       private $data = array();
       private $views = array();
        
	public function __construct() { ; }


/*funktion GetData() anropas av ThemeEngineRender i Origin
för att $data=array() skall bli tillgänglig för templatefilen i form av variabler.
*/

  	public function GetData() { return $this->data; }



/*Index() i CCGuestbook ropar på metoden SetTitle i CViewcontainer med medskickad sträng 'Lydia Guestbook Example'
och metoden SetTitle skickar vidare till metoden SetVariable i CViewcontaoner och på så vis
ges att $this->data['title']='Lydia Guestbook Example';
*/
  
       public function SetTitle($value) {
       return $this->SetVariable('title', $value);
  	}

       public function SetVariable($key, $value) {
       $this->data[$key] = $value;
	   return $this;
  	}
	
	
	
	/**
* Add inline style.
*
* @param $value string to be added as inline style.
* @returns $this.
*/
  public function AddStyle($value) {
    if(isset($this->data['inline_style'])) {
      $this->data['inline_style'] .= $value;
    } else {
      $this->data['inline_style'] = $value;
    }
    return $this;
  }
	
	
	
	
	
	
	
	
	
	
	
	
	
	


/*metoden ReadAll i CMguestbook anropas för objektet guestbookModel och den
anropar metoden ExecuteSelectQueryandFetchAll som finns i CMDatabase och resultatet läggs till arrayen 
under nyckel 'entries'.

Vad som sen sker är att denna array med variabler görs tillgänglig för filen __DIR__ . '/index.tpl.php'
via metoden Addinclude.
*/
 
 
   /**
* Add a view as file to be included and optional variables.
*
* @param $file string path to the file to be included.
* @param $vars array containing the variables that should be avilable for the included file.
* @param $region string the theme region, uses string 'default' as default region.
* @returns $this.
*/
  public function AddInclude($file, $variables=array(), $region='default') {
    $this->views[$region][] = array('type' => 'include', 'file' => $file, 'variables' => $variables);
    return $this;
  }
  
  
  
  
    /**
* Add text and optional variables.
*
* @param $string string content to be displayed.
* @param $vars array containing the variables that should be avilable for the included file.
* @param $region string the theme region, uses string 'default' as default region.
* @returns $this.
*/
  public function AddString($string, $variables=array(), $region='default') {
    $this->views[$region][] = array('type' => 'string', 'string' => $string, 'variables' => $variables);
    return $this;
  }
  
  
  
  
  
  
  
  
  
    /**
* Check if there exists views for a specific region.
*
* @param $region string/array the theme region(s).
* @returns boolean true if region has views, else false.
*/
  public function RegionHasView($region) {
    if(is_array($region)) {
      foreach($region as $val) {
        if(isset($this->views[$val])) {
          return true;
        }
      }
      return false;
    } else {
      return(isset($this->views[$region]));
    }
  }
 
 
 
 
 
 
 
 
 
 
 
      /**
* Render all views according to their type.
*
* @param $region string the region to render views for.
*/
  public function Render($region='default') {
    if(!isset($this->views[$region])) return;
    foreach($this->views[$region] as $view) {
      switch($view['type']) {
        case 'include': extract($view['variables']); include($view['file']); break;
        case 'string': extract($view['variables']); echo $view['string']; break;
      }
    }
  }


}