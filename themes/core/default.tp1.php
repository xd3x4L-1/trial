<?php

/*html-dokumentet i denna sida styr vad användaren ser då adressen 
http://www.student.bth.se/~boer13/phpmvc/kmom03/trial/guestbook efterfrågas och
denna adress ges igen till användaren efter varje tryck på knapparna i formuläret då 
metoden Handler i 
CCGuestbook alltid ger adressen åter. 
adressen ges även per default av metoden Index() som är default för gästboken.

i Origin ThemeEngineRender har hjälpfilerna themes/core/functions.php och 
themes/functions.php extraherats och variabler läggs in i html-dokumentet.

I den första filen finns uppgift om $header, $slogan, $footer, $logo.

I den andra finns funktionen debug som i html-dokumentet anropas efter $footer och
med hjälp av metoden GetFlash ger utskrift om antalet frågor till databasen, frågorna i SQL
och tidsåtgången för uppdraget.

?=render_views() anropar render_wiews i /themes/functions som der ett 
innehåll som kommer ifrån functionen Render i CVIewContainer via extract och include.
Det innehåll som ges är filen src/CCGuestbook/index.tp1.php och en
array med de meddelanden som tidigare lagrats i databasen och gjorts tillgänglig via 
AddInclude($file, $variables=array() i CviewContainer.

pga av att filen  src/CCGuestbook/index.tp1.php inkluderas till html-dokumentet
blir alla html kod i den nu synlig via detta html-dokument och 
i det nu inkluderade innehållet finns också databasens meddelanden pga av
php-koden i den inkluderade filen.

?get_messages_from_session i themes/functions.php anropas från html-dokumentet och 
i denna funktion definieras variabel $message att innehålla det medelande till användaren 
om vad som utförts mot databasen som lagrats av funktion AddMessage($type, $message)
funktionen get_messages_from_session i sig returnerar en variabel $html
med html-kod vilket gör att meddelandet till användaren nu syns.


<?=@$main?> är till för visning då det är någon av kontrollerna CCINdex eller CCDeveloper
som styrt variablerna för utskrift då det då är data['title'] och data['main']
som skrivs ut.

vad gäller titel har i CCGuestbook $this->views->SetTitle('Lydia Guestbook Example');
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