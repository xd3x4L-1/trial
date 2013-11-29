<?php

/*För att utförda åtgärder i programmet skall relateras till en 
och inte flera instanser av klassen Origin erfordras att denna klass har en 
metod Instance().
Tillgång ges av Origin::Instance.
*/


interface ISingleton {
  public static function Instance();
}