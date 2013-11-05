<?php

/*I ramverket omdirigeras inkommande l�nkar till denna sida om l�nkarna ej pekar till en ordin�r fil eller till en befintlig katalog
*/

/*
// Konfigureringsfilen f�r appache .htaccess har f�ljande uppgifter-
//Kontrollerar om filen mod_rewrite.c �r tillg�nglig i www.student.bth.se
//G�r m�jligt att dirigera om inkommande l�nkar till den lokala adress som ges av RewriteBase och RewriteRule.
//lokal s�kv�g till baskatalog.
//ger villkor att inkommande l�nkar till resurs REQUEST_FILENAME ej skall omdirigeras om resursen �r en ordin�r fil.
//ger villkor att inkommande l�nkar till resurs REQUEST_FILENAME ej skall omdirigeras om resursen �r en befintlig katalog.
// Inkommande l�nkar vilka ej �r undantagna skall omdirigeras till lokal adress /~boer13/phpmvc/kmom02/trial/index.php.
*/


//
// PHASE: BOOTSTRAP

/*ger konstanten LYDIA_INSTALL_PATH som v�rde den lokala adressen till katalogen som index.php �r installerad i.
F�r nuvarande version p� studentservern g�ller /home/saxon/students/20131/boer13/www/phpmvc/kmom02/trial
*/

define('LYDIA_INSTALL_PATH', dirname(__FILE__));


/*ger konstanten LYDIA_SITE_PATH som v�rde den lokala adressen f�r katalogen site.
F�r nuvarande version p� studentservern g�ller /home/saxon/students/20131/boer13/www/phpmvc/kmom02/trial/site
*/

define('LYDIA_SITE_PATH', LYDIA_INSTALL_PATH . '/site');


/* inkluderar koden f�r fil bootstrap.php vilken registrerar att det finns en funktion som laddar klassfiler d�
de anropas f�r att ett objekt av klassen skall skapas.
*/


require(LYDIA_INSTALL_PATH.'/src/Origin/bootstrap.php');

/*H�r byggs ett objekt Origo av klassen Origin
*/


$Origo = Origin::Instance();

//
// PHASE: FRONTCONTROLLER ROUTE

/*H�r anropas funktionen FrontControllerRoute() i Origin f�r att analysera den
l�nk som inkommer.
*/

$Origo->FrontControllerRoute();

//
// PHASE: THEME ENGINE RENDER

/*H�r anropas funktionen ThemeEnginerender f�r att skriva ut () de reultat som lagrats under 
 PHASE: FRONTCONTROLLER ROUTE d�r r�tt kontrollerer och metod anropades med PHP Reflection.
*/

$Origo->ThemeEngineRender();