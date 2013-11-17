<?php
/**
 * Bootstrapping, setting up and loading the core.
 *
 * @package TrialCore
 */

/**
 * Enable auto-load of class declarations.
 */

/*Funktionen autoload har byggt s� att den i f�rsta hand v�ljer en fil med namnet
$aClassname placerad i katalog site och i andra hand en fil med namn $aclassname ur installationskatalogen
f�r index.php
*/

/* i funktonen byggs de lokala adresserna till fr�gad fil och om efterfr�gad fil inte finns 
avbryts programmets exekvering - Funktionen tr�der in automatiskt n�r nya objekt skall skapas enligt 
new $aClassname;
*/

function autoload($aClassName) {
  $classFile = "/src/{$aClassName}/{$aClassName}.php";
  $file1 = LYDIA_SITE_PATH . $classFile;
  $file2 = LYDIA_INSTALL_PATH . $classFile;
  if(is_file($file1)) {
    require_once($file1);
  } elseif(is_file($file2)) {
    require_once($file2);
  }
}

/*F�r att detta skall fungera beh�ver funktionen registreras.
*/

spl_autoload_register('autoload');

/**
* Set a default exception handler and enable logging in it.
*/
function exceptionHandler($e) {
  echo "Trial: Uncaught exception: <p>" . $e->getMessage() . "</p><pre>" . $e->getTraceAsString(), "</pre>";
}
set_exception_handler('exceptionHandler');


/**
* Helper, include a file and store it in a string. Make $vars available to the included file.
*/
function getIncludeContents($filename, $vars=array()) {
  if (is_file($filename)) {
    ob_start();
    extract($vars);
    include $filename;
    return ob_get_clean();
  }
  return false;
}



/**
* Helper, wrap html_entites with correct character encoding
*/
function htmlent($str, $flags = ENT_COMPAT) {
  return htmlentities($str, $flags, Origin::Instance()->config['character_encoding']);
}
