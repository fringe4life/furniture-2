




<?php

//require_once '../../../../Users/Coinnich/vendor/swiftmailer/lib/swift_required.php';
require_once "../php-includes/connect.inc.php";
require_once "../functions/allowed_params.php";
require_once "../functions/csrf_request_type_functions.php";
session_start();
require_once "../functions/csrf_token_functions.php";
require_once "../functions/validation_functions.php";
require_once "../functions/xss_sanitize_functions.php";





if(request_is_post()){
    //echo ($_POST);
  //  echo "request is post";
    $params = allowed_post_params(['e','subject', 'message', 'csrf_token']);
    
    
    $token = $params['csrf_token'];
    
    $token_is_valid = csrf_token_is_valid() && csrf_token_is_recent();
    
    $email = $params["e"];
    $is_email = (isValidEmail($email)) ? true : false;
    
    $safe_subject = strip_tags(htmlspecialchars($params["subject"]));
    echo $safe_subject;
    $msg = $params["message"];
   //  echo $email . " " . $subject . " " . $msg;
   $sub_length = (has_length($safe_subject , ["min" => 5 , "max" => 40])) ? true : false;
    
    $msg_length = (has_length($msg, ["min" => 20 , "max" => 1000])) ? true : false;
     //echo "request is post";
     
     $contactMeetsReqs = ($is_email && $sub_length && $msg_length && $token_is_valid) ? true : false;
   if($contactMeetsReqs){
        // echo "request is post          \r\n\t";
        //return "met conditions";
       // bool = mail(rcvr, subject line, message, additional headers, i.e. Subject, reply to, from)
       $mail_del = mail("joshwilson608@gmail.com", $safe_subject, $msg, "FROM:".$email);
        echo boolval($mail_del);
     /*  $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "tls")
            ->setUsername('joshwilson608@gmail.com')
            ->setPassword('ocls4LOrF$p"W{RX');

$mailer = Swift_Mailer::newInstance($transport);

$message = Swift_Message::newInstance('Test Subject')
  ->setFrom(array('joshwilson608@gmail.com' => 'Josh'))
  ->setTo(array('russia2009@windowslive.com'))
  ->setBody('This is a test mail.');

$result = $mailer->send($message);
 echo $result;
*/
       
   } else {
        echo "ajax failed";
    }
    
    
    
}





?>