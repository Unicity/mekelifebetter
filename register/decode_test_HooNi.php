<?php
session_start();
if(!isset($_SERVER["HTTPS"])) {
	header('Location: https://'.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']);
	exit;
}
include "./includes/common_functions.php";
include "./includes/nc_config.php";
include "./includes/AES.php";
include "./includes/TranseId.php";
include "./includes/signup_function.php";


//암호화 키 재설정
$enckey = hex2bin("12345678901234567890123456789077");
$enciv = hex2bin("12345678901234567890123456789011");

$gender_A = "9YPA1vcdk3AIuQubBHaouw==";
$gender_B = "dx2s8AQqNnbKxDyNKOAIEQ==";
$gender_C = "gdvN4m5t9HK3Db2+sqKh6Q==";
$gender_D = "V+e/35RAqDE7CgKv5V5evg==";
$gender_E = "Y9EPL9MALhH6smihu7Go9w==";

$gender = "";

$decGender_A = decrypt($enckey, $enciv, $gender_A);
$decGender_B = decrypt($enckey, $enciv, $gender_B);
$decGender_C = decrypt($enckey, $enciv, $gender_C);
$decGender_D = decrypt($enckey, $enciv, $gender_D);
$decGender_E = decrypt($enckey, $enciv, $gender_E);

$encGender = encrypt($enckey, $enciv, $gender);

echo "A : ".$decGender_A;
echo "<br>";

echo "B : ".$decGender_B;
echo "<br>";

echo "C : ".$decGender_C;
echo "<br>";

echo "D : ".$decGender_D;
echo "<br>";

echo "E : ".$decGender_E;
echo "<br>";

echo "encGender : ".$encGender;
echo "<br>";

exit;



?>
