<?php

/*gr�nsnittt vilket implementeras av databasmodell CMGuestbook 
och detta gr�nssnitt kr�ver att denna modell skall inneha en metod SQL som utf�r
fr�gor med SQL mot ramverkets databas.
*/


interface IHasSQL {
  public static function SQL($key=null);
}