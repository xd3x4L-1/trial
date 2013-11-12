<?php

/*Array config ger initiella värden till Trial.

////config['url_in'] talar om på vilken form inkommande länkar föutsätts vara.

//config['url_type'] talar om på vilken form utgående länkar skall byggas.

//config['session_name'] vid installation på studentservern på BTH har $_SERVER["SERVER_NAME"]värdet www.student.bth.se.
preg_replace ersätter tecknen /[:\.\/-_]/ till i detta fall ingenting.

//config['timezone'] gör det möjligt att ställa in aktuell tidszon.

//config['character_encoding'] gör det möjligt attställa in teckenkod.

//config['language'] gör det möjligt att ställa in språk.

//config['controllers'] Innehåller en lista över filer som används för att lagra värden till
variabler för utskrift. Val av fil, kontroller, styrs av inkommande länk.

//config['theme'] namnet som ges motsvarar namnet på en underkatalog till katalog themes.
Denna underkatalog innehåller sedan för temat relevanta filer som template, stilmall och funktioner.

//config['base_url'] det förinställda värdet null utgör startpunkt för att beräkna värdet för base_url i ramverket.
Denna utgångspunkt gör att $base_url inte är tom.

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
