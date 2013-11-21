<?php
   
/*Ett objekt av klassen har skapats av konstruktorn i Origin
och som en följ av detta har filen laddats och konstruktorn anropat konstruktorn i 
klass CObject.
*/ 

	class CMGuestbook extends CObject implements IHasSQL {

      	public function __construct() {
       parent::__construct();
      	}


/*ett formulär är synligt i gästboken pga, kod i filen CCGuestbook/index.tpl.php 
och när någon av knapparna trycks för formuläret så anropar meyoden Handler i CCGuestbook här - 
Add($entry) lagrar ett meddelande till databasen, DeleteAll()raderar alla meddelanden ur databasen, 
och Init()skapar en tabell guestbook i databasen om den inte redan finnns.
*/

/*metod SQL används för att på uppdrag av de andra metoderna i klassen
ge färdig SQL-kod för uppdrag mot databasen.
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


/*funktionen Init() anropar metoden ExecuteQuery i CMDatabase för att skapa en tabell igästboken och till denna
översänds kod ifrån metod SQL i denna fil.
Därefter lagras ett meddelande till data['flash']['message'] via metoden AddMessage i CSession.
data['flash']['message'] innehåller en array i vilken de två medsända argumenten varav ett är meddelandet lagras.
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
     
/*funktionen Add() anropar metoden ExecuteQuery i CMDatabase för skriva ett nytt meddelande till gästboken 
och till denna
översänds kod ifrån metod SQL i denna fil.
Därefter lagras ett meddelande till data['flash']['message'] via metoden AddMessage i CSession.
data['flash']['message'] innehåller en array i vilken de två medsända argumenten varav ett är meddelandet lagras.
*/
    
      public function Add($entry) {
      $this->db->ExecuteQuery(self::SQL('insert into guestbook'), array($entry));
      $this->session->AddMessage('success', 'Successfully inserted new message.');
      if($this->db->rowCount() != 1) {
      die('Failed to insert new guestbook item into database.');
      }
      }
     
/*funktionen DeleteAll() anropar metoden ExecuteQuery i CMDatabase för att radera alla medelanden i gästboken. 
Därefter lagras ett meddelande till data['flash']['message'] via metoden AddMessage i CSession.
data['flash']['message'] innehåller en array i vilken de två medsända argumenten varav ett är meddelandet lagras.
*/
     
      public function DeleteAll() {
      $this->db->ExecuteQuery(self::SQL('delete from guestbook'));
      $this->session->AddMessage('info', 'Removed all messages from the database table.');
      }
     
/*funktionen ReadAll() anropar metoden ExecuteSelectQueryAndFetchAll i CMDatabase för att lagra alla 
medelanden i gästboken till en retur. Denna funktionkallas alltid per automatik ifrån
Index() i CCGuestbook och inget meddelande om detta lämnas till användaren.
*/     
      
      public function ReadAll() {
      try {
      return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * from guestbook'));
      } catch(Exception $e) {
      return array();   
      }
      }

     
    } 