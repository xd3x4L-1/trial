<?php

/*Detta gränssnitt används i ramverket för att en metod Index() alltid
krävs tillgänglig om det inte anges någon annan metod.
Detta är alltså dels ur default syfte då anropa av någon av klasserna 
CCINdex, CCDeveloper, CCGuestbook anropas.
*/

interface IController {
  public function Index();
}