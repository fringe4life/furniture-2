<?php

//error_reporting(0); // Disable error reporting for production version
                    // Remove this line for development 
class FurnitureDB {

    private static $db = null;
    
    public function __construct(){
        if (self::$db===NULL){
            try {
                $dbConnect = array(
                    'server' => 'localhost',
                    'user' => 'root',
                    'pass' => 'w79Ha924iF',
                    'name' => 'furniture'
                );
                self::$db = new PDO( 'mysql:dbname='.$dbConnect["name"].';host=127.0.0.1;charset=utf8', $dbConnect['user'], $dbConnect['pass'] );
            } catch(PDOException $e){
                $message = $e->getMessage();
                die($message);
            }
            if(self::$db){
                //self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } else {
                return "Database Could not be connected to" . $message;
            }
        }
    }
    public static function getConnection() {
    
        //Guarantees single instance, if no connection object exists then create one.
        if (!self::$db) {
        //new connection object.
            new MovieDB();
        }
        //return connection.
        return self::$db;
    }

}

?>