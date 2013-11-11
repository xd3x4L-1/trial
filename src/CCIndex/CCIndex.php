<?php
/**
* Standard controller layout.
*
* @package TrialCore
*/
class CCIndex implements IController {

        /**
         * Implementing interface IController. All controllers must have an index action.
         */
        public function Index() {        
    $this->Menu();
        }


        /**
         * Create a method that shows the menu, same for all methods
         */
        private function Menu() {        
                $Origo = Origin::Instance();
                $menu = array('index', 'index/index', 'developer', 'developer/index', 'developer/links', 'developer/display-object');
                
                $html = null;
                foreach($menu as $val) {
                 $html .= "<li><a href='" . $Origo->request->CreateUrl($val) . "'>$val</a>";
                }
                
                $Origo->data['title'] = "The Index Controller";
                $Origo->data['main'] = <<<EOD
<h1>The Index Controller</h1>
<p>This is what you can do for now:</p>
<ul>
$html
</ul>
EOD;
  }
  
} 