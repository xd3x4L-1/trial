<?php

/*Detta gr�nssnitt anv�nds i ramverket f�r att en metod Index() alltid
kr�vs tillg�nglig om det inte anges n�gon annan metod.
Detta �r allts� dels ur default syfte d� anropa av n�gon av klasserna 
CCINdex, CCDeveloper, CCGuestbook anropas.
*/

interface IController {
  public function Index();
}