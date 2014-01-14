<?php

/**
* All requests routed through here. This is an overview of what actaully happens during
* a request.
*
* @package LydiaCore
*/

// ---------------------------------------------------------------------------------------
//
// PHASE: BOOTSTRAP
//



	define('LYDIA_INSTALL_PATH', dirname(__FILE__));

	define('LYDIA_SITE_PATH', LYDIA_INSTALL_PATH . '/site');

	require(LYDIA_INSTALL_PATH.'/src/Origin/bootstrap.php');

	$Origo = Origin::Instance();
       $Origo->a=1;


// ---------------------------------------------------------------------------------------
//
// PHASE: FRONTCONTROLLER ROUTE
//

	$Origo->FrontControllerRoute();
       

// ---------------------------------------------------------------------------------------
//
// PHASE: THEME ENGINE RENDER
//
	$Origo->ThemeEngineRender();