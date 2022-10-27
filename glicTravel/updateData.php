<?php
session_start();
header('Content-Type:text/html;charset=UTF-8');
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Mon,26 Jul 1997 05:00:00 GMT");
?>

<?php

include "includes/config/config.php";
include "./includes/AES.php";
include "./includes/admin_session_check.inc";

$distID = $_POST['member_no'];
$distName = $_POST['member_name'];
$phone = $_POST['phone'];
$attendP = $_POST['attendP'];
$selectDate = $_POST['selectDate'];
$selectCard = $_POST['selectCard'];
$cardNumber = $_POST['card_number'];
$expireDate = $_POST['expire_date'];
$birthDay = $_POST['birthday'];
$passWord = $_POST['pword'];
$custHref = $_POST['custHref'];
$updateDate = $_POST['updateDate'];
$installment=$_POST['installment'];
$noFlag=$_POST['noFlag'];
$flagUD=$_POST['flagUD'];

echo $flagUD.'<br/>';




if($flagUD=='U'){



	$done3 = '수정이 완료 되었습니다.';
	$now_date = date("Y-m-d H:i:s");

	/**카드번호 암호화 */
	$cardNumber  = encrypt($key, $iv, $cardNumber );
	/** 카드 비밀번호 암호화 */
	$passWord  = encrypt($key, $iv, $passWord );


	$updateQuery="update tb_glicTravel 
		set phone = '$phone',
			select_date='$selectDate',
			member='$attendP',
			payment_card='$selectCard',
			card_number='$cardNumber',
			expire_date='$expireDate',
			birthday='$birthDay',
			password='$passWord',
			update_date='$now_date',
			installment='$installment',
			update_p='$s_adm_id'
		where member_no='$distID'
		and No='$noFlag'";


		mysql_query($updateQuery) or die("updateQuery Error");
		echo "<script>alert('$done3');history.go(-2);</script>";    	

}else if ($flagUD=='D'){
	$done4 = '삭제가 완료 되었습니다.';
	$deleteQuery="update tb_glicTravel 
		set delete_p='$s_adm_id',
			flagUD='D'
		where member_no='$distID'
		and No='$noFlag'";

		mysql_query($deleteQuery) or die("deleteQuery Error");
		
		echo "<script>alert('$done4');history.go(-2);</script>";    	
}
?>

