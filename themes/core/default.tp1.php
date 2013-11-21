<?php

/*html-dokumentet i denna sida styr vad anv�ndaren ser d� adressen 
http://www.student.bth.se/~boer13/phpmvc/kmom03/trial/guestbook efterfr�gas och
denna adress ges igen till anv�ndaren efter varje tryck p� knapparna i formul�ret d� 
metoden Handler i 
CCGuestbook alltid ger adressen �ter. 
adressen ges �ven per default av metoden Index() som �r default f�r g�stboken.

i Origin ThemeEngineRender har hj�lpfilerna themes/core/functions.php och 
themes/functions.php extraherats och variabler l�ggs in i html-dokumentet.

I den f�rsta filen finns uppgift om $header, $slogan, $footer, $logo.

I den andra finns funktionen debug som i html-dokumentet anropas efter $footer och
med hj�lp av metoden GetFlash ger utskrift om antalet fr�gor till databasen, fr�gorna i SQL
och tids�tg�ngen f�r uppdraget.

?=render_views() anropar render_wiews i /themes/functions som der ett 
inneh�ll som kommer ifr�n functionen Render i CVIewContainer via extract och include.
Det inneh�ll som ges �r filen src/CCGuestbook/index.tp1.php och en
array med de meddelanden som tidigare lagrats i databasen och gjorts tillg�nglig via 
AddInclude($file, $variables=array() i CviewContainer.

pga av att filen  src/CCGuestbook/index.tp1.php inkluderas till html-dokumentet
blir alla html kod i den nu synlig via detta html-dokument och 
i det nu inkluderade inneh�llet finns ocks� databasens meddelanden pga av
php-koden i den inkluderade filen.

?get_messages_from_session i themes/functions.php anropas fr�n html-dokumentet och 
i denna funktion definieras variabel $message att inneh�lla det medelande till anv�ndaren 
om vad som utf�rts mot databasen som lagrats av funktion AddMessage($type, $message)
funktionen get_messages_from_session i sig returnerar en variabel $html
med html-kod vilket g�r att meddelandet till anv�ndaren nu syns.


<?=@$main?> �r till f�r visning d� det �r n�gon av kontrollerna CCINdex eller CCDeveloper
som styrt variablerna f�r utskrift d� det d� �r data['title'] och data['main']
som skrivs ut.

vad g�ller titel har i CCGuestbook $this->views->SetTitle('Lydia Guestbook Example');
definierat titeln.
*/


?>


<!doctype html>
<html lang='en'>
<head>
  <meta charset='utf-8'/>
  <title><?=$title?></title>
        <link rel='shortcut icon' href='<?=$favicon?>'/>
<link rel='stylesheet' href='<?=$stylesheet?>'/>
</head>
<body>
<div id='wrap-header'>
<div id='header'>
<div id='banner'>
<a href='<?=base_url()?>'>
<img class='site-logo' src='<?=$logo?>' alt='logo' width='<?=$logo_width?>' height='<?=$logo_height?>' />
</a>
<p class='site-title'><?=$header?></p>
<p class='site-slogan'><?=$slogan?></p>
</div>
</div>
</div>
<div id='wrap-main'>
<div id='main' role='main'>
      <?=get_messages_from_session()?>
      <?=@$main?>
      <?=render_views()?>
    </div>
  </div>
<div id='wrap-footer'>
<div id='footer'>
<?=$footer?>
<?=get_debug()?>
</div>
</div>
</body>
</html>