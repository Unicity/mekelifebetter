<?php 
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );
$from = "master@makelifebetter.co.kr";
$to = "intergl@nate.com";
$subject = "Mail Test";
$message = "Mail Test Message";
$headers = "From:" . $from;

//$res  = mail($to,$subject,$message, $headers);
$res  = mail($to,$subject,$message, $headers, " -f".$from);

var_dump($res);
?>