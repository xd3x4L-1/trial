<?php

/*F�r att utf�rda �tg�rder i programmet skall relateras till en 
och inte flera instanser av klassen Origin erfordras att denna klass har en 
metod Instance().
Tillg�ng ges av Origin::Instance.
*/


interface ISingleton {
  public static function Instance();
}