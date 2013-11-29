<?php
   


    class CMDatabase {

     
      private $db = null;
      private $stmt = null;
      private static $numQueries = 0;
      private static $queries = array();

/*Aktivering av konstruktor sker i origins konstruktor där ett abjekt av klaseen skapas.
När detta objekt skapas så skapas en koppling till
en den databas som ges av config['database'][0]['dsn'] med hjälp av PDO.

*/

      public function __construct($dsn, $username = null, $password = null, $driver_options = null) {
        $this->db = new PDO($dsn, $username, $password, $driver_options);
        $this->db->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }
     
/* function SetAttribute anropas ifrån konstruktorn.
*/   
     
      public function SetAttribute($attribute, $value) {
        return $this->db->setAttribute($attribute, $value);
      }

      public function GetNumQueries() { return self::$numQueries; }
      public function GetQueries() { return self::$queries; }


/*funktionen ReadAll() i CMGuestbook anropar metoden ExecuteSelectQueryAndFetchAll i för att lagra alla 
medelanden i gästboken till en retur. Denna funktionkallas alltid per automatik ifrån
Index() i CCGuestbook och inget meddelande om detta lämnas till användaren.
*/ 
   
      public function ExecuteSelectQueryAndFetchAll($query, $params=array()){
        $this->stmt = $this->db->prepare($query);
        self::$queries[] = $query;
        self::$numQueries++;
        $this->stmt->execute($params);
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
      }

/*funktionen DeleteAll() i CMGuestbook anropar metoden ExecuteQuery för att radera alla medelanden i gästboken. 
Därefter lagras ett meddelande till data['flash']['message'] via metoden AddMessage i CSession.
data['flash']['message'] innehåller en array i vilken de två medsända argumenten varav ett är meddelandet lagras.

funktionen Add() i CMGuestbook anropar metoden ExecuteQuery i CMDatabase för skriva ett nytt meddelande till gästboken 
och till funktionen
översänds kod ifrån metod SQL.
Därefter lagras ett meddelande till data['flash']['message'] via metoden AddMessage i CSession.
data['flash']['message'] innehåller en array i vilken de två medsända argumenten varav ett är meddelandet lagras.
*/
    
      public function ExecuteQuery($query, $params = array()) {
        $this->stmt = $this->db->prepare($query);
        self::$queries[] = $query;
        self::$numQueries++;
        return $this->stmt->execute($params);
      }


/*funktionen anropas ifrån CMGuestbook metod function Add($entry) för att kontrollera 
det är en rad i databastabellen som påverkats av uppdraget att lagra ettt meddelande till databasen.
*/

      public function RowCount() {
        return is_null($this->stmt) ? $this->stmt : $this->stmt->rowCount();
      }

 	public function LastInsertId() {
        return $this->db->lastInsertid();
      }


    }