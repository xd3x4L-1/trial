<?php

/*
Konfigureringsfilen för appache .htaccess har följande uppgifter-
Kontrollerar om filen mod_rewrite.c är tillgänglig i www.student.bth.se
Gör möjligt att dirigera om inkommande länkar till den lokala adress som ges av RewriteBase och övrig adress enligt RewriteRule.

ger villkor att inkommande länkar till resurs REQUEST_FILENAME ej skall omdirigeras om resursen är en ordinär fil.
ger villkor att inkommande länkar till resurs REQUEST_FILENAME ej skall omdirigeras om resursen är en befintlig katalog.

RewriteBase ger lokal sökväg till baskatalog.
Inkommande länkar vilka ej är undantagna skall omdirigeras till lokal adress /~boer13/phpmvc/kmom02/trial/index.php.


i <files> ges att åtkomst via länk ej skall ges till filer vilka börjar på .rdt
pga av att deby kommer efter allow förhindras åtkomst till dessa filer per default även om det ej 
finns något ordervillkor som går lika mot förfrågan.
*/


//
// PHASE: BOOTSTRAP

/*ger konstanten LYDIA_INSTALL_PATH som värde den lokala adressen till katalogen som index.php är installerad i.
För nuvarande version på studentservern gäller /home/saxon/students/20131/boer13/www/phpmvc/kmom02/trial
*/

/*ger konstanten LYDIA_SITE_PATH som värde den lokala adressen för katalogen site.
För nuvarande version på studentservern gäller /home/saxon/students/20131/boer13/www/phpmvc/kmom02/trial/site
*/

/* inkluderar koden för fil bootstrap.php vilken registrerar att det finns en funktion som laddar klassfiler då
de anropas för att ett objekt av klassen skall skapas.
i denna fil ges även en funktion för felhnatering.
*/

/*Här byggs ett objekt Origo av klassen Origin och under denna process när konstruktorn i Origin anropas
byggs även objekt av klasserna Csession, CMDatabase, CViewContainer enligt

I klass Origin skrivs dessa objekt som $this->session, $this->db, $this-views

*/



	define('LYDIA_INSTALL_PATH', dirname(__FILE__));

	define('LYDIA_SITE_PATH', LYDIA_INSTALL_PATH . '/site');

	require(LYDIA_INSTALL_PATH.'/src/Origin/bootstrap.php');

	$Origo = Origin::Instance();



//
// PHASE: FRONTCONTROLLER ROUTE


/*Här anropas funktionen FrontControllerRoute() i Origin för att analysera den länk som 
brukats och omdirigerats till index.php

Då metoden FrontcontrollerRoute() anropas initeras ett objekt av klassen 
CReguest för att metoden Init($baseUrl) skall gå att använda för att analysera
länk enligt ovan.

*/

	$Origo->FrontControllerRoute();


//
// PHASE: THEME ENGINE RENDER

/*Här anropas funktionen ThemeEnginerender för att skriva ut () de reultat som lagrats under 
 PHASE: FRONTCONTROLLER ROUTE där rätt kontrollerer och metod anropades med PHP Reflection.
*/

	$Origo->ThemeEngineRender();