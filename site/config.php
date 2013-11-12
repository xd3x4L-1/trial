<?php

/*Array config ger initiella v�rden till Trial.

////config['url_in'] talar om p� vilken form inkommande l�nkar f�uts�tts vara.

//config['url_type'] talar om p� vilken form utg�ende l�nkar skall byggas.

//config['session_name'] vid installation p� studentservern p� BTH har $_SERVER["SERVER_NAME"]v�rdet www.student.bth.se.
preg_replace ers�tter tecknen /[:\.\/-_]/ till i detta fall ingenting.

//config['timezone'] g�r det m�jligt att st�lla in aktuell tidszon.

//config['character_encoding'] g�r det m�jligt attst�lla in teckenkod.

//config['language'] g�r det m�jligt att st�lla in spr�k.

//config['controllers'] Inneh�ller en lista �ver filer som anv�nds f�r att lagra v�rden till
variabler f�r utskrift. Val av fil, kontroller, styrs av inkommande l�nk.

//config['theme'] namnet som ges motsvarar namnet p� en underkatalog till katalog themes.
Denna underkatalog inneh�ller sedan f�r temat relevanta filer som template, stilmall och funktioner.

//config['base_url'] det f�rinst�llda v�rdet null utg�r startpunkt f�r att ber�kna v�rdet f�r base_url i ramverket.
Denna utg�ngspunkt g�r att $base_url inte �r tom.

/**
 * Set level of error reporting
 */
error_reporting(-1);
ini_set('display_errors', 1);

/**
* Set what to show as debug or developer information in the get_debug() theme helper.
*/
$Origo->config['debug']['display-trial'] = true;

    /**
    * What type of urls should be used?
    *
    * default      = 0      => index.php/controller/method/arg1/arg2/arg3
    * clean        = 1      => controller/method/arg1/arg2/arg3
    * querystring  = 2      => index.php?q=controller/method/arg1/arg2/arg3
    */
    
    $Origo->config['url_in'] = 0;

    $Origo->config['url_type'] = 1;

/**
 * Site configuration, this file is changed by user per site.
 *
 */

/**
 * Define session name
 *
*/

$Origo->config['session_name'] = preg_replace('/[:\.\/-_]/', '', $_SERVER["SERVER_NAME"]);

/**
 * Define server timezone
 */
$Origo->config['timezone'] = 'Europe/Stockholm';

/**
 * Define internal character encoding
 */
$Origo->config['character_encoding'] = 'UTF-8';

/**
 * Define language
 */
$Origo->config['language'] = 'en';

/**
 * Define the controllers, their classname and enable/disable them.
 *
 * The array-key is matched against the url, for example: 
 * the url 'developer/dump' would instantiate the controller with the key "developer", that is 
 * CCDeveloper and call the method "dump" in that class. This process is managed in:
 * $Origo->FrontControllerRoute();
 * which is called in the frontcontroller phase from index.php.
 */
$Origo->config['controllers'] = array(
  'index'     => array('enabled' => true,'class' => 'CCIndex'),
 'developer' => array('enabled' => true,'class' => 'CCDeveloper'),
 'guestbook' => array('enabled' => true,'class' => 'CCGuestbook'),
);

    /**
    * Settings for the theme.
    */

    $Origo->config['theme'] = array(
      // The name of the theme in the theme directory

      'name'    => 'core',
    );

	    /**
    * Set a base_url to use another than the default calculated
    */

    $Origo->config['base_url'] = null;
