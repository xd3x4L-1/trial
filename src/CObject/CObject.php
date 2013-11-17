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
	
  }

}