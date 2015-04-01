




<?php
require_once "../php-includes/connect.inc.php";
require_once "../functions/allowed_params.php";
require_once "../functions/csrf_request_type_functions.php";
require_once "../functions/validation_functions.php";
require_once "../functions/xss_sanitize_functions.php";
//require_once "Mail.php";


#header('Content-type: application/json');

if(request_is_post()){
    //echo ($_POST);
    echo "request is post";
    $params = allowed_post_params(['e','subject', 'message']);
    
    $email = $params["e"];
    
    $is_email = (isValidEmail($email)) ? true : false;
    
    $subject = strip_tags($params["subject"]);
    $msg = $params["message"];
     echo $email . " " . $subject . " " . $msg;
   $sub_length = (has_length($subject, ["min" => 5 , "max" => 40])) ? true : false;
    
    $msg_length = (has_length($msg, ["min" => 20 , "max" => 1000])) ? true : false;
     echo "request is post";
   if($is_email && $sub_length && $msg_length){
         echo "request is post          \r\n\t";
        //return "met conditions";
        //bool = mail(rcvr, subject line, message, additional headers, i.e. Subject, reply to, from)
        $mail_del = mail("joshwilson608@gmail.com", $subject, $msg, "FROM:".$email);
        echo boolval($mail_del);
        
   } else {
        echo "ajax failed";
    }
    
    
    
}





?>