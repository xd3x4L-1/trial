<?php

/**
* Holding a instance of CLydia to enable use of $this in subclasses and provide some helpers.
*
* @package LydiaCore
*/

	class CObject {

   /**
         * Members
         */
		protected $Origo;
        protected $config;
        protected $request;
        protected $data;
        protected $db;
        protected $views;
        protected $session;
        protected $user;
	
	


      /**
         * Constructor, can be instantiated by sending in the $ly reference.
         */
        protected function __construct($Origo=null) {
         if(!$Origo) {
         $Origo = Origin::Instance();
         }
		 $this->Origo       = &$Origo;
    $this->config = &$Origo->config;
    $this->request = &$Origo->request;
    $this->data = &$Origo->data;
    $this->db = &$Origo->db;
    $this->views = &$Origo->views;
    $this->session = &$Origo->session;
    $this->user = &$Origo->user;

        }


  
        /**
         * Wrapper for same method in CLydia. See there for documentation.
         */
        protected function RedirectTo($urlOrController=null, $method=null, $arguments=null) {
    $this->Origo->RedirectTo($urlOrController, $method, $arguments);
  }


        /**
         * Wrapper for same method in CLydia. See there for documentation.
         */
        protected function RedirectToController($method=null, $arguments=null) {
    $this->Origo->RedirectToController($method, $arguments);
  }


        /**
         * Wrapper for same method in CLydia. See there for documentation.
         */
        protected function RedirectToControllerMethod($controller=null, $method=null, $arguments=null) {
    $this->Origo->RedirectToControllerMethod($controller, $method, $arguments);
  }


        /**
         * Wrapper for same method in CLydia. See there for documentation.
         */
  protected function AddMessage($type, $message, $alternative=null) {
    return $this->Origo->AddMessage($type, $message, $alternative);
  }


        /**
         * Wrapper for same method in CLydia. See there for documentation.
         */
        protected function CreateUrl($urlOrController=null, $method=null, $arguments=null) {
    return $this->Origo->CreateUrl($urlOrController, $method, $arguments);
  }


 

}