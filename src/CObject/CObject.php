<?php
/**
* Holding a instance of CLydia to enable use of $this in subclasses.
*
* @package TrialCore
*/
class CObject {

        public $config;
        public $request;
        public $data;
		public $db;
		public $views;
		public $session;
		
        /**
         * Constructor
         */
        protected function __construct() {
    $Origo = Origin::Instance();
    $this->config = &$Origo->config;
    $this->request = &$Origo->request;
    $this->data = &$Origo->data;
	$this->db       = &$Origo->db;
	$this->views    = &$Origo->views;
	 $this->session  = &$Origo->session;
  }
  
  /**
         * Redirect to another url and store the session
         */
        protected function RedirectTo($url) {
    $Origo = Origin::Instance();
    if(isset($Origo->config['debug']['db-num-queries']) && $Origo->config['debug']['db-num-queries'] && isset($Origo->db)) {
      $this->session->SetFlash('database_numQueries', $this->db->GetNumQueries());
    }
    if(isset($Origo->config['debug']['db-queries']) && $Origo->config['debug']['db-queries'] && isset($Origo->db)) {
      $this->session->SetFlash('database_queries', $this->db->GetQueries());
    }
    if(isset($Origo->config['debug']['timer']) && $Origo->config['debug']['timer']) {
         $this->session->SetFlash('timer', $Origo->timer);
    }
    $this->session->StoreInSession();
    header('Location: ' . $this->request->CreateUrl($url));
  }
  
  
  
  
  
  
  
  

}