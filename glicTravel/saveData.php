<?php

header('Content-Type:text/html;charset=UTF-8');
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Mon,26 Jul 1997 05:00:00 GMT");
?>

<?php

include "includes/config/config.php";
include "./includes/AES.php";

$distID = $_POST['distID'];
$distName = $_POST['distName'];
$phone = $_POST['phone'];
$attendP = $_POST['attendP'];
$selectDate = $_POST['selectDate'];
$selectCard = $_POST['selectCard'];
$cardNumber = $_POST['cardNumber'];
$expireDate = $_POST['expireDate'];
$birthDay = $_POST['birthDay'];
$passWord = $_POST['passWord'];
$custHref = $_POST['custHref'];
$updateDate = $_POST['updateDate'];
$installment=$_POST['installment'];


function getMaxCode()  {
	
	$iNewid = 0;
	$sqlstr = "SELECT MAX(No) as CNT FROM tb_glicTravel";

	$result = mysql_query($sqlstr);
	$row = mysql_fetch_array($result);

	$iNewid = $row["CNT"] + 1;

	if (strlen($iNewid) == 1) {
		$iNewid = "0".$iNewid;
	}

	return $iNewid;

}




$sMax = getMaxCode();
$rNum = $distID."_".$sMax;


function sendSMS($phone,$rNum){

	$callbackNum			= '15778269';
	$contents			= "[유니시티 코리아] GLIC Travel 예약이 완료 되었습니다.\n\n예약 번호는".$rNum."입니다.\n\n예약 확정 후 6월 중 직원이 상세 안내 및 진행 전화를 드릴 예정입니다.";
	
	$mode = 'SEND';
	if ($mode == "SEND") {
	    $query = "insert into NEO_MSG (phone, callback, reqdate, msg, subject, type) values ('$phone', '$callbackNum', sysdate(), '$contents','유티시티코리아', 2)";
	    mysql_query($query);
		//logging($s_adm_id,'send sms ('.substr($phone,-4).')');
	}	
	echo "<script>
		location.replace('glicTravelComplete.php?rnum=$rNum');
	</script>";

}



$already = '이미 신청하신 예약 내역이 있습니다. ';

$alreadyCheckQuery = "select count(*) cnt from tb_glicTravel where flagUD is null and member_no=".$distID;
$result3 = mysql_query($alreadyCheckQuery);
$list3 = mysql_fetch_array($result3);

$cnt = $list3[cnt];

if($cnt >0){
	echo "<script>alert('$already');history.back();</script>";
	exit;
}

/*
$sumQuery = "select sum(member) tot from tb_glicTravel where select_date=0804 ";

$result = mysql_query($sumQuery);
$list = mysql_fetch_array($result);

$tot = $list[tot];

echo $tot."<br/>";


$done = '0804 해당일자 예약이 마감 마감되었습니다.';
$done1 = '0805 해당일자 예약이 마감 마감되었습니다.';

if($selectDate =='0804'){
	if($tot >=600){
		echo "<script>alert('$done');history.back();</script>";
		exit;
	}
}
$sumQuery2 = "select sum(member) tot from tb_glicTravel where select_date=0805 ";

$result2 = mysql_query($sumQuery2);
$list2 = mysql_fetch_array($result2);

$tot2 = $list2[tot];
if($selectDate =='0805'){
	if($tot2 >=400){
		echo "<script>alert('$done1');history.back();</script>";    	
		exit;
	}
	
}

*/

$done3 = '예약이 완료 되었습니다.';
$now_date = date("Y-m-d H:i:s");

/**카드번호 암호화 */
$cardNumber  = encrypt($key, $iv, $cardNumber );
/** 카드 비밀번호 암호화 */
$passWord  = encrypt($key, $iv, $passWord );

$insertQuery="insert into tb_glicTravel (
	member_no, 
	member_name, 
	member, 
	phone,
	select_date,
	payment_card,
	card_number,
	expire_date,
	birthday,
	password,
	create_date, 
	update_date,
	customers_href,
	reservationNo,
	installment

	) 
	value(
	'".$distID."',
	'".$distName."',
	'".$attendP."',
	'".$phone."',
	'".$selectDate."',
	'".$selectCard."',
	'".$cardNumber."',
	'".$expireDate."',
	'".$birthDay."',
	'".$passWord."',
	'".$now_date."',
	'".$updateDate."',
	'".$custHref."',
	'".$rNum."',
	'".$installment."'
	)";
echo $insertQuery;
	mysql_query($insertQuery) or die("insertQuery Error");
	echo "<script>alert('$done3');</script>";    	

	$insertQueryBack="insert into tb_glicTravel_back (
		member_no, 
		member_name, 
		member, 
		phone,
		select_date,
		payment_card,
		card_number,
		expire_date,
		birthday,
		password,
		create_date, 
		update_date,
		customers_href,
		reservationNo,
		installment
		) 
		value(
		'".$distID."',
		'".$distName."',
		'".$attendP."',
		'".$phone."',
		'".$selectDate."',
		'".$selectCard."',
		'".$cardNumber."',
		'".$expireDate."',
		'".$birthDay."',
		'".$passWord."',
		'".$now_date."',
		'".$updateDate."',
		'".$custHref."',
		'".$rNum."',
		'".$installment."'
		)";
	
		mysql_query($insertQueryBack) or die("insertQueryBack Error");

		sendSMS($phone,$rNum);

?>

