<?php
   


    class CMDatabase {

     
      private $db = null;
      private $stmt = null;
      private static $numQueries = 0;
      private static $queries = array();

/*Aktivering av konstruktor sker i origins konstruktor d�r ett abjekt av klaseen skapas.
N�r detta objekt skapas s� skapas en koppling till
en den databas som ges av config['database'][0]['dsn'] med hj�lp av PDO.

*/

      public function __construct($dsn, $username = null, $password = null, $driver_options = null) {
        $this->db = new PDO($dsn, $username, $password, $driver_options);
        $this->db->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }
     
/* function SetAttribute anropas ifr�n konstruktorn.
*/   
     
      public function SetAttribute($attribute, $value) {
        return $this->db->setAttribute($attribute, $value);
      }

      public function GetNumQueries() { return self::$numQueries; }
      public function GetQueries() { return self::$queries; }


/*funktionen ReadAll() i CMGuestbook anropar metoden ExecuteSelectQueryAndFetchAll i f�r att lagra alla 
medelanden i g�stboken till en retur. Denna funktionkallas alltid per automatik ifr�n
Index() i CCGuestbook och inget meddelande om detta l�mnas till anv�ndaren.
*/ 
   
      public function ExecuteSelectQueryAndFetchAll($query, $params=array()){
        $this->stmt = $this->db->prepare($query);
        self::$queries[] = $query;
        self::$numQueries++;
        $this->stmt->execute($params);
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
      }

/*funktionen DeleteAll() i CMGuestbook anropar metoden ExecuteQuery f�r att radera alla medelanden i g�stboken. 
D�refter lagras ett meddelande till data['flash']['message'] via metoden AddMessage i CSession.
data['flash']['message'] inneh�ller en array i vilken de tv� meds�nda argumenten varav ett �r meddelandet lagras.

funktionen Add() i CMGuestbook anropar metoden ExecuteQuery i CMDatabase f�r skriva ett nytt meddelande till g�stboken 
och till funktionen
�vers�nds kod ifr�n metod SQL.
D�refter lagras ett meddelande till data['flash']['message'] via metoden AddMessage i CSession.
data['flash']['message'] inneh�ller en array i vilken de tv� meds�nda argumenten varav ett �r meddelandet lagras.
*/
    
      public function ExecuteQuery($query, $params = array()) {
        $this->stmt = $this->db->prepare($query);
        self::$queries[] = $query;
        self::$numQueries++;
        return $this->stmt->execute($params);
      }


/*funktionen anropas ifr�n CMGuestbook metod function Add($entry) f�r att kontrollera 
det �r en rad i databastabellen som p�verkats av uppdraget att lagra ettt meddelande till databasen.
*/

      public function RowCount() {
        return is_null($this->stmt) ? $this->stmt : $this->stmt->rowCount();
      }

 	public function LastInsertId() {
        return $this->db->lastInsertid();
      }


    }