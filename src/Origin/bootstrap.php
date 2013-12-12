<?php


/*Funktionen autoload har byggt så att den i första hand väljer en fil med namnet
$aClassname placerad i katalog site/src/{$aClassName} och i andra hand en fil med namn $aclassname ur trial/src/{$aClassName}
för index.php

i funktonen byggs de lokala adresserna till frågad fil och om efterfrågad fil inte finns 
avbryts programmets exekvering - Funktionen träder in automatiskt när nya objekt skall skapas enligt 
new $aClassname;

För att detta skall fungera behöver funktionen registreras.
*/

function autoload($aClassName) {
  $classFile = "/src/{$aClassName}/{$aClassName}.php";
  $file1 = LYDIA_SITE_PATH . $classFile;
  $file2 = LYDIA_INSTALL_PATH . $classFile;
  if(is_file($file1)) {
    require_once($file1);
  } elseif(is_file($file2)) {
    require_once($file2);
  }
}

spl_autoload_register('autoload');




/*Funktionen exeptionhandler är till för att hantera ofångade fel och för att den skall vara 
tillgänglig brukas set_exception _handler
*/

function exceptionHandler($e) {
  echo "Trial: Uncaught exception: <p>" . $e->getMessage() . "</p><pre>" . $e->getTraceAsString(), "</pre>";
}
set_exception_handler('exceptionHandler');




/**
* Helper, include a file and store it in a string. Make $vars available to the included file.
*/

function getIncludeContents($filename, $vars=array()) {
  if (is_file($filename)) {
    ob_start();
    extract($vars);
    include $filename;
    return ob_get_clean();
  }
  return false;
}


/**
* Helper, wrap html_entites with correct character encoding
*/
function htmlent($str, $flags = ENT_COMPAT) {
  return htmlentities($str, $flags, Origin::Instance()->config['character_encoding']);
}

/**
* Helper, interval formatting of times. Needs PHP5.3.
*
* All times in database is UTC so this function assumes the starttime to be in UTC, if not otherwise
* stated.
*
* Copied from http://php.net/manual/en/dateinterval.format.php#96768
* Modified (mos) to use timezones.
* A sweet interval formatting, will use the two biggest interval parts.
* On small intervals, you get minutes and seconds.
* On big intervals, you get months and days.
* Only the two biggest parts are used.
*
* @param DateTime|string $start
* @param DateTimeZone|string|null $startTimeZone
* @param DateTime|string|null $end
* @param DateTimeZone|string|null $endTimeZone
* @return string
*/
function formatDateTimeDiff($start, $startTimeZone=null, $end=null, $endTimeZone=null) {
  if(!($start instanceof DateTime)) {
    if($startTimeZone instanceof DateTimeZone) {
      $start = new DateTime($start, $startTimeZone);
    } else if(is_null($startTimeZone)) {
      $start = new DateTime($start);
    } else {
      $start = new DateTime($start, new DateTimeZone($startTimeZone));
    }
  }
  
  if($end === null) {
    $end = new DateTime();
  }
  
  if(!($end instanceof DateTime)) {
    if($endTimeZone instanceof DateTimeZone) {
      $end = new DateTime($end, $endTimeZone);
    } else if(is_null($endTimeZone)) {
      $end = new DateTime($end);
    } else {
      $end = new DateTime($end, new DateTimeZone($endTimeZone));
    }
  }
  
  $interval = $end->diff($start);
  $doPlural = function($nb,$str){return $nb>1?$str.'s':$str;}; // adds plurals
  //$doPlural = create_function('$nb,$str', 'return $nb>1?$str."s":$str;'); // adds plurals
  
  $format = array();
  if($interval->y !== 0) {
    $format[] = "%y ".$doPlural($interval->y, "year");
  }
  if($interval->m !== 0) {
    $format[] = "%m ".$doPlural($interval->m, "month");
  }
  if($interval->d !== 0) {
    $format[] = "%d ".$doPlural($interval->d, "day");
  }
  if($interval->h !== 0) {
    $format[] = "%h ".$doPlural($interval->h, "hour");
  }
  if($interval->i !== 0) {
    $format[] = "%i ".$doPlural($interval->i, "minute");
  }
  if(!count($format)) {
      return "less than a minute";
  }
  if($interval->s !== 0) {
    $format[] = "%s ".$doPlural($interval->s, "second");
  }
  
  if($interval->s !== 0) {
      if(!count($format)) {
          return "less than a minute";
      } else {
          $format[] = "%s ".$doPlural($interval->s, "second");
      }
  }
  
  // We use the two biggest parts
  if(count($format) > 1) {
      $format = array_shift($format)." and ".array_shift($format);
  } else {
      $format = array_pop($format);
  }
  
  // Prepend 'since ' or whatever you like
  return $interval->format($format);
}
