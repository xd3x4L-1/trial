<?php
   
/*Ett objekt av klassen har skapats av konstruktorn i CCGuestbook
och som en f�lj av detta har filen laddats och konstruktorn anropat konstruktorn i 
klass CObject.
*/ 

	class CMGuestbook extends CObject implements IHasSQL {

      	public function __construct() {
       parent::__construct();
      	}


/*ett formul�r �r synligt i g�stboken pga, kod i filen CCGuestbook/index.tpl.php 
och n�r n�gon av knapparna trycks f�r formul�ret s� anropar meyoden Handler i CCGuestbook h�r - 
Add($entry) lagrar ett meddelande till databasen, DeleteAll()raderar alla meddelanden ur databasen, 
och Init()skapar en tabell guestbook i databasen om den inte redan finnns.
*/

/*metod SQL anv�nds f�r att p� uppdrag av de andra metoderna i klassen
ge f�rdig SQL-kod f�r uppdrag mot databasen.
*/

	public static function SQL($key=null) {
       $queries = array(
       'create table guestbook'  => "CREATE TABLE IF NOT EXISTS Guestbook (id INTEGER PRIMARY KEY, entry TEXT, created DATETIME default (datetime('now')));",
       'insert into guestbook'   => 'INSERT INTO Guestbook (entry) VALUES (?);',
       'select * from guestbook' => 'SELECT * FROM Guestbook ORDER BY id DESC;',
       'delete from guestbook'   => 'DELETE FROM Guestbook;',
       );
       if(!isset($queries[$key])) {
       throw new Exception("No such SQL query, key '$key' was not found.");
       }
       return $queries[$key];
      	}


/*funktionen Init() anropar metoden ExecuteQuery i CMDatabase f�r att skapa en tabell ig�stboken och till denna
�vers�nds kod ifr�n metod SQL i denna fil.
D�refter lagras ett meddelande till data['flash']['message'] via metoden AddMessage i CSession.
data['flash']['message'] inneh�ller en array i vilken de tv� meds�nda argumenten varav ett �r meddelandet lagras.
*/
    
      public function Init() {
      try {
      $this->db->ExecuteQuery(self::SQL('create table guestbook'));
      $this->session->AddMessage('notice', 'Successfully created the database tables (or left them untouched if they already existed).');
      } 
      catch(Exception$e) {
      die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
      }
      }
     
/*funktionen Add() anropar metoden ExecuteQuery i CMDatabase f�r skriva ett nytt meddelande till g�stboken 
och till denna
�vers�nds kod ifr�n metod SQL i denna fil.
D�refter lagras ett meddelande till data['flash']['message'] via metoden AddMessage i CSession.
data['flash']['message'] inneh�ller en array i vilken de tv� meds�nda argumenten varav ett �r meddelandet lagras.
*/
    
      public function Add($entry) {
      $this->db->ExecuteQuery(self::SQL('insert into guestbook'), array($entry));
      $this->session->AddMessage('success', 'Successfully inserted new message.');
      if($this->db->rowCount() != 1) {
      die('Failed to insert new guestbook item into database.');
      }
      }
     
/*funktionen DeleteAll() anropar metoden ExecuteQuery i CMDatabase f�r att radera alla medelanden i g�stboken. 
D�refter lagras ett meddelande till data['flash']['message'] via metoden AddMessage i CSession.
data['flash']['message'] inneh�ller en array i vilken de tv� meds�nda argumenten varav ett �r meddelandet lagras.
*/
     
      public function DeleteAll() {
      $this->db->ExecuteQuery(self::SQL('delete from guestbook'));
      $this->session->AddMessage('info', 'Removed all messages from the database table.');
      }
     
/*funktionen ReadAll() anropar metoden ExecuteSelectQueryAndFetchAll i CMDatabase f�r att lagra alla 
medelanden i g�stboken till en retur. Denna funktionkallas alltid per automatik ifr�n
Index() i CCGuestbook och inget meddelande om detta l�mnas till anv�ndaren.
*/     
      
      public function ReadAll() {
      try {
      return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * from guestbook'));
      } catch(Exception $e) {
      return array();   
      }
      }

     
    } 
