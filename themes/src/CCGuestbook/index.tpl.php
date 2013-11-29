<?php
/*
kommandot?=render_views() i default.tp1.php anropar render_wiews i /themes/functions som der ett 
innehåll som kommer ifrån functionen Render i CVIewContainer via extract och include.
Det innehåll som ges är filen src/CCGuestbook/index.tp1.php och en
array med de meddelanden som tidigare lagrats i databasen och gjorts tillgänglig via 
AddInclude($file, $variables=array() i CviewContainer.

pga av att filen  src/CCGuestbook/index.tp1.php inkluderas till html-dokumentet
blir alla html kod i den nu synlig via detta html-dokument och 
i det nu inkluderade innehållet finns också databasens meddelanden pga av
php-koden i den inkluderade filen.
*/
?>


	<h1>Guestbook Example</h1>
	<p>Showing off how to implement a guestbook in trial. Now saving to database.</p>

	<form action="<?=$formAction?>" method='post'>
  	<p>
    	<label>Message: <br/>
    	<textarea name='newEntry'></textarea></label>
  	</p>
  	<p>
    	<input type='submit' name='doAdd' value='Add message' />
    	<input type='submit' name='doClear' value='Clear all messages' />
    	<input type='submit' name='doCreate' value='Create database table' />
  	</p>
	</form>

	<h2>Current messages</h2>

	<?php foreach($entries as $val):?>
	<div style='background-color:#f6f6f6;border:1px solid #ccc;margin-bottom:1em;padding:1em;'>
  	<p>At: <?=$val['created']?></p>
	<p><?=htmlent($val['entry'])?></p>
	</div>
	<?php endforeach;?>