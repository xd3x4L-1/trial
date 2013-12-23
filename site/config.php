<?php

/*Array config ger initiella v�rden till Trial.

//config['url_type'] talar om p� vilken form utg�ende l�nkar skall byggas.

//config['session_name'] vid installation p� studentservern p� BTH har $_SERVER["SERVER_NAME"]v�rdet www.student.bth.se.
preg_replace ers�tter tecknen /[:\.\/-_]/ till i detta fall ingenting.

//config['controllers'] Inneh�ller en lista �ver filer som anv�nds f�r att lagra v�rden till
variabler f�r utskrift. Val av fil, kontroller, styrs av inkommande l�nk.

//config['theme'] namnet som ges motsvarar namnet p� en underkatalog till katalog themes.
Denna underkatalog inneh�ller sedan f�r temat relevanta filer som template, stilmall och funktioner.
*/

	error_reporting(-1);
	ini_set('display_errors', 1);

    	$Origo->config['debug']['trial'] = false;
    	$Origo->config['debug']['db-num-queries'] = true;
    	$Origo->config['debug']['db-queries'] = true;
    	$Origo->config['debug']['session'] = false;
    	$Origo->config['debug']['timer'] = true;

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
* How to hash password of new users, choose from: plain, md5salt, md5, sha1salt, sha1.
*/
$Origo->config['hashing_algorithm'] = 'sha1salt';


/**
* Allow or disallow creation of new user accounts.
*/
$Origo->config['create_new_users'] = true;


/**
* Define session name
*/
$Origo->config['session_name'] = preg_replace('/[:\.\/-_]/', '', __DIR__);
	$Origo->config['session_key']  = 'trial';

	$Origo->config['timezone'] = 'Europe/Stockholm';
	$Origo->config['character_encoding'] = 'UTF-8';
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
	'theme'     => array('enabled' => true,'class' => 'CCTheme'),
 	'guestbook' => array('enabled' => true,'class' => 'CCGuestbook'),
	'content' => array('enabled' => true,'class' => 'CCContent'),
	'blog' => array('enabled' => true,'class' => 'CCBlog'),
	'page' => array('enabled' => true,'class' => 'CCPage'),
	'user'      => array('enabled' => true,'class' => 'CCUser'),
	'acp'       => array('enabled' => true,'class' => 'CCAdminControlPanel'),
	);

  

    	$Origo->config['theme'] = array(
      	'name'    => 'grid',
		'stylesheet' => 'style.php',
    	);

	
    $Origo->config['base_url'] = null;
	$Origo->config['database'][0]['dsn'] = 'sqlite:' . LYDIA_SITE_PATH . '/data/.rdt.sqlite';
