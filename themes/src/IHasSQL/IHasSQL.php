<?php

/*gränsnittt vilket implementeras av databasmodell CMGuestbook 
och detta gränssnitt kräver att denna modell skall inneha en metod SQL som utför
frågor med SQL mot ramverkets databas.
*/


interface IHasSQL {
  public static function SQL($key=null);
}