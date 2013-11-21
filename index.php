<?php

/*
Konfigureringsfilen f�r appache .htaccess har f�ljande uppgifter-
Kontrollerar om filen mod_rewrite.c �r tillg�nglig i www.student.bth.se
G�r m�jligt att dirigera om inkommande l�nkar till den lokala adress som ges av RewriteBase och �vrig adress enligt RewriteRule.

ger villkor att inkommande l�nkar till resurs REQUEST_FILENAME ej skall omdirigeras om resursen �r en ordin�r fil.
ger villkor att inkommande l�nkar till resurs REQUEST_FILENAME ej skall omdirigeras om resursen �r en befintlig katalog.

RewriteBase ger lokal s�kv�g till baskatalog.
Inkommande l�nkar vilka ej �r undantagna skall omdirigeras till lokal adress /~boer13/phpmvc/kmom02/trial/index.php.


i <files> ges att �tkomst via l�nk ej skall ges till filer vilka b�rjar p� .rdt
pga av att deby kommer efter allow f�rhindras �tkomst till dessa filer per default �ven om det ej 
finns n�got ordervillkor som g�r lika mot f�rfr�gan.
*/


//
// PHASE: BOOTSTRAP

/*ger konstanten LYDIA_INSTALL_PATH som v�rde den lokala adressen till katalogen som index.php �r installerad i.
F�r nuvarande version p� studentservern g�ller /home/saxon/students/20131/boer13/www/phpmvc/kmom02/trial
*/

/*ger konstanten LYDIA_SITE_PATH som v�rde den lokala adressen f�r katalogen site.
F�r nuvarande version p� studentservern g�ller /home/saxon/students/20131/boer13/www/phpmvc/kmom02/trial/site
*/

/* inkluderar koden f�r fil bootstrap.php vilken registrerar att det finns en funktion som laddar klassfiler d�
de anropas f�r att ett objekt av klassen skall skapas.
i denna fil ges �ven en funktion f�r felhnatering.
*/

/*H�r byggs ett objekt Origo av klassen Origin och under denna process n�r konstruktorn i Origin anropas
byggs �ven objekt av klasserna Csession, CMDatabase, CViewContainer enligt

I klass Origin skrivs dessa objekt som $this->session, $this->db, $this-views

*/



	define('LYDIA_INSTALL_PATH', dirname(__FILE__));

	define('LYDIA_SITE_PATH', LYDIA_INSTALL_PATH . '/site');

	require(LYDIA_INSTALL_PATH.'/src/Origin/bootstrap.php');

	$Origo = Origin::Instance();



//
// PHASE: FRONTCONTROLLER ROUTE


/*H�r anropas funktionen FrontControllerRoute() i Origin f�r att analysera den l�nk som 
brukats och omdirigerats till index.php

D� metoden FrontcontrollerRoute() anropas initeras ett objekt av klassen 
CReguest f�r att metoden Init($baseUrl) skall g� att anv�nda f�r att analysera
l�nk enligt ovan.

*/

	$Origo->FrontControllerRoute();


//
// PHASE: THEME ENGINE RENDER

/*H�r anropas funktionen ThemeEnginerender f�r att skriva ut () de reultat som lagrats under 
 PHASE: FRONTCONTROLLER ROUTE d�r r�tt kontrollerer och metod anropades med PHP Reflection.
*/

	$Origo->ThemeEngineRender();