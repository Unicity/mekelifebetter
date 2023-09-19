<?php 
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );
$from = "root@makelifebetter.co.kr";
//$to = "intergl@nate.com";
$to = "starksj1@naver.com";
$subject = "Mail Test";
$message = "Mail Test Message";
$headers = "From:" . $from;

//$res  = mail('example@example.com', 'subj', 'bodddd');

$res  = mail($to,$subject,$message, $headers, " -f".$from);

var_dump($res);
?>
