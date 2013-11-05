<?php
/**
 * Bootstrapping, setting up and loading the core.
 *
 * @package TrialCore
 */

/**
 * Enable auto-load of class declarations.
 */

/*Funktionen autoload har byggt så att den i första hand väljer en fil med namnet
$aClassname placerad i katalog site och i andra hand en fil med namn $aclassname ur installationskatalogen
för index.php
*/

/* i funktonen byggs de lokala adresserna till frågad fil och om efterfrågad fil inte finns 
avbryts programmets exekvering - Funktionen träder in automatiskt när nya objekt skall skapas enligt 
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

/*För att detta skall fungera behöver funktionen registreras.
*/

spl_autoload_register('autoload');

