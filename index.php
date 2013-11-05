<?php

/*I ramverket omdirigeras inkommande länkar till denna sida om länkarna ej pekar till en ordinär fil eller till en befintlig katalog
*/

/*
// Konfigureringsfilen för appache .htaccess har följande uppgifter-
//Kontrollerar om filen mod_rewrite.c är tillgänglig i www.student.bth.se
//Gör möjligt att dirigera om inkommande länkar till den lokala adress som ges av RewriteBase och RewriteRule.
//lokal sökväg till baskatalog.
//ger villkor att inkommande länkar till resurs REQUEST_FILENAME ej skall omdirigeras om resursen är en ordinär fil.
//ger villkor att inkommande länkar till resurs REQUEST_FILENAME ej skall omdirigeras om resursen är en befintlig katalog.
// Inkommande länkar vilka ej är undantagna skall omdirigeras till lokal adress /~boer13/phpmvc/kmom02/trial/index.php.
*/


//
// PHASE: BOOTSTRAP

/*ger konstanten LYDIA_INSTALL_PATH som värde den lokala adressen till katalogen som index.php är installerad i.
För nuvarande version på studentservern gäller /home/saxon/students/20131/boer13/www/phpmvc/kmom02/trial
*/

define('LYDIA_INSTALL_PATH', dirname(__FILE__));


/*ger konstanten LYDIA_SITE_PATH som värde den lokala adressen för katalogen site.
För nuvarande version på studentservern gäller /home/saxon/students/20131/boer13/www/phpmvc/kmom02/trial/site
*/

define('LYDIA_SITE_PATH', LYDIA_INSTALL_PATH . '/site');


/* inkluderar koden för fil bootstrap.php vilken registrerar att det finns en funktion som laddar klassfiler då
de anropas för att ett objekt av klassen skall skapas.
*/


require(LYDIA_INSTALL_PATH.'/src/Origin/bootstrap.php');

/*Här byggs ett objekt Origo av klassen Origin
*/


$Origo = Origin::Instance();

//
// PHASE: FRONTCONTROLLER ROUTE

/*Här anropas funktionen FrontControllerRoute() i Origin för att analysera den
länk som inkommer.
*/

$Origo->FrontControllerRoute();

//
// PHASE: THEME ENGINE RENDER

/*Här anropas funktionen ThemeEnginerender för att skriva ut () de reultat som lagrats under 
 PHASE: FRONTCONTROLLER ROUTE där rätt kontrollerer och metod anropades med PHP Reflection.
*/

$Origo->ThemeEngineRender();