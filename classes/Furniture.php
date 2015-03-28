<?php

require_once "../php-includes/connect.inc.php";
require_once "../functions/allowed_params.php";
require_once "../functions/csrf_request_type_functions.php";
require_once "../functions/validation_functions.php";
require_once "../functions/xss_sanitize_functions.php";

class Furniture {
    private static $x_default = 0;
    private static $number_default = 10;
    private static $max_query = "Select Max(id) AS id From furniture";
    private static $get_furniture = "Select * FROM furniture WHERE id <= ? AND id >= ?";
    
    function addFurniture() {
        
    }
    
    function __construct(){
        
    }
    /**
        checks for presence of vital values and sets to defaults if not present
        Takes a list of known safe get variables, sanitize first with the get_allowed_params
    */
    private function check_presence($params){//
        if(has_presence($params["x"])){
                    
        }else {
            $params["x"]= $this::$x_default;
        }
        if(has_presence($params["number"])){
            
        } else {
            $params["number"] = $this::$number_default;
        }
        if(has_presence($params["min"]) && has_presence($params["max"])){
            $min = is_numeric($params["min"])=== true ?true : false;
            $max = is_numeric($params["max"])=== true ?true : false;
            if(!$min){
                $params["min"] = 500;
            }
            if(!$max){
                    $params["max"] = 1400;
            }
            
            if($min && $max){
                $right_order = ($max > $min) ? true : false;
                if(!$right_order){
                    $params["min"] = 500;
                    $params["max"] = 1400;
                } else {
                    
                }
            }
        } else {
            $params["min"] = 500;
            $params["max"] = 1400;
        }
    }
    
    private function check_query_string($params){
        if(has_presence($params["query"])){
            if(has_length($params["query"], ['min' => 5, 'max' => 100])){
                $query =& $params["query"];
                $query = strip_tags($query);
                $query = PDO::quote($query);
                return true;
            }
        }
        return false;
    }
    
    
    private function getFurnitureFailed($query, $db){
        $query->closeCursor();
        $db = null;
        exit;
    }
    
    /**
    First checks if variables are present by calling check_presence
    Second Checks if all allowed params are numbers
    
    Returns a boolean if the received values are numbers or not
    */
    private function performChecks($params){
        
        $isNumber = true;
        //method that checks if variables have values if not set to defualts
        
        self::check_presence($params);
         
        $paramsInt = array($params["x"], $params["number"] );
        
        $has_query = self::check_query_string($params);
            
        if($has_query){
            // perform search?? probably not here
        } else {
            // do something maybe??
        }
        
        foreach ($paramsInt as $value){//test if any value is not a number 
            if (!is_numeric($value)){
                return false;
            } 
        }
        return $isNumber;
    }
    
    
    
    /**
        Tests for presence of errors and whether the query has returned false
    
    */
    private function checkQuerySuccess($stmt, $db){
        
        $errorInfo = $stmt->errorInfo();
        if(isset($errorInfo[2]) || !$stmt){
            self::getFurnitureFailed($stmt, $db);
            return $false;
        }
        return true;
    }
    
    private function checkNumberSize($x, $number, $total, $number_of_furniture){
        
        if ($x>$number_of_furniture ){
            
            return false;
        }
        return true;
    }
    
    private function bindValues($x, $number, $gallery, $stmt, $total){
        
        $half = $number/2;
        
        if($half+$x>$total){
            $half = $total/2;
        }
        
        $get = $x + $half;
        if ($gallery==="left"){
            $get = $x + $half;
            $stmt->bindParam(1, $get, PDO::PARAM_INT);
            $stmt->bindParam(2, $x, PDO::PARAM_INT);
        } elseif($gallery==="right"){
            $stmt->bindParam(1, $total, PDO::PARAM_INT);
            $get = $get + 1;
            $stmt->bindParam(2, $get, PDO::PARAM_INT);
        }
        //echo $half . " " . $get;
    }
    
    function getFurniture($gallery) {
        
        $half = self::checkHalf(trim($gallery));
        
        if($half){
        
            $message = "";
            //ignore other paramters by getting the allowed ones
            $params = allowed_get_params(["x", "number", "min", "max", "query"]);
            //$params["min"] = 500;
            //$params["max"] = 1400;
            $isNumber = self::performChecks($params);
            
            //$message = $message . "\rgot here";
            if ($isNumber){// if not null, not empty and all are numbers...
                //get the values into variables, more readable
                $x = $params["x"];
                $number = $params["number"];
                
                // create the class that access the database
                    //$message = $message . "\rgot here";
                $db = new FurnitureDB();
                //get a connection
                $db = $db::getConnection();
                
                $query = $db->query(Furniture::$max_query);
                
                $success = self::checkQuerySuccess($query, $db);
                //$message = $message . "\rgot here";
                if ($success){// if query succedded
                    $result = $query->fetchAll(PDO::FETCH_ASSOC);
                    $number_of_furniture = $result[0];
                    $number_of_furniture = $number_of_furniture['id'];
                    $total = $number + $x;
                    
                    $query->closeCursor();
                    
                    $success = self::checkNumberSize($x, $number, $total, $number_of_furniture);
                    if($success) {
                        $stmt = $db->prepare(Furniture::$get_furniture);
                        
                        self::bindValues($x, $number, $gallery, $stmt, $number_of_furniture);
                        //$message = $message . " " . $x . " number: " . $number . " " . $number_of_furniture;
                        //$message = $message . "\rgot here";
                        $stmt->execute();
                        
                        $success = self::checkQuerySuccess($stmt, $db);
                        
                        if ($success){ //if found items, loop through all of them and create the html elements
                            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
                                $message = $message . "\n" . self::getFurnitureByName($row); 
                                //$message = $message . var_dump($row);
                            }
                            $stmt->closeCursor();
                        }else {
                          //failed to get any results from database, something very strange happened
                            self::getFurnitureFailed($stmt, $db);
                            $message = "<p class='error'>There was a problem with the database, we will try to fix this as soon as possible</p>";
                            return $message;
                        }
                    } else {
                        $message = "<p class='error'>There was a problem with your input, please check the numbers</p>";
                        return $message;
                    }
                    
                }else {
                  //likely no images in the database
                    self::getFurnitureFailed($query, $db);
                    $message = "<p class='error'>There was a problem with the database, we will try to fix this as soon as possible</p>";
                    return $message;
                }
            }
            else {
                // was either null, not present or not a number... tell user to try again
                $message = "<p class='error'>There was a problem with your input, please check that they are numbers</p>";
                return $message ."\r\n" . var_dump($params) ."\r\n" . $_SERVER['QUERY_STRING'];
            }
            $db = null;
            return $message;
        } else {
            return "invalid half: " . $gallery;
        }
    }
    
    
    public function checkHalf($half){
        
        $is_left = strcmp($half, "left") ===0 ? true : false;
        $is_right = strcmp($half, "right") ===0 ? true : false;
        
        if($is_left || $is_right ){
            return true;
        }
        return false;
    }
    
    /**
        Makes a figure html element out of the database information
    */
    function getFurnitureByName($row) {
        $filename = $row["filename"];
        $folder = $row["folder"];
        $title = $row["title"];
        $description = $row["description"];
        $price = $row["price"];
        $id = $row["id"];
        $image = "<li ><figure class='center'>
        <img src='". $folder . "/" . $filename ."' alt='picture' class='center'>
        <figcaption><p><span class='title'>" . $title ."</span> <span class='price'>" . $price  . "<span></p><p><span class='description'>" . $description ." </span></p>" .
        "</figcaption></figure></li>";
        return $image;
    }
}
?>