<?session_start();?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<style type="text/css" title="">
body {font-size:12px; line-height:150%;  max-width:480px; max-width:480px; word-break:break-all;}
</style>
<?
#include "admin_session_check.inc";
include "./inc/global_init.inc";
include "../dbconn_utf8.inc";
include "../AES.php";
include "./inc/common_function.php";
include "./new_api_function.php";
?>
<script>
function js_close(){
	parent.js_close_modal();
	//parent.frames[3].test('done');
}
</script>
<!-- <button onclick="js_close()">닫기</button> -->
<?php 
$mode = str_quote_smart(trim($mode));

if ($mode != "mod") {
	//echo '유효하지 않은 요청입니다.';
	sleep(1);
	echo "<script>js_close();</script>";

	exit;
}

$member_no				= str_quote_smart(trim($member_no));
$ename						= str_quote_smart(trim($ename));
$number						= str_quote_smart(trim($number));
$password					= str_quote_smart(trim($password));
$active_kind			= str_quote_smart(trim($active_kind));
$couple						= str_quote_smart(trim($couple));
$couple_name			= str_quote_smart(trim($couple_name));
$couple_ename			= str_quote_smart(trim($couple_ename));
$couple_reg_jumin1 = str_quote_smart(trim($couple_reg_jumin1));
$couple_reg_jumin2 = str_quote_smart(trim($couple_reg_jumin2));
$pho1							= str_quote_smart(trim($pho1));
$pho2							= str_quote_smart(trim($pho2));
$pho3							= str_quote_smart(trim($pho3));
$hpho1						= str_quote_smart(trim($hpho1));
$hpho2						= str_quote_smart(trim($hpho2));
$hpho3						= str_quote_smart(trim($hpho3));
$zip							= str_quote_smart(trim($zip));
$addr							= str_quote_smart(trim($addr));
$addr_detail			= str_quote_smart(trim($addr02));
$del_zip					= str_quote_smart(trim($del_zip));
$del_addr					= str_quote_smart(trim($del_addr));
$del_addr_detail	= str_quote_smart(trim($del_addr02));
$email						= str_quote_smart(trim($email));
$email_flag				= str_quote_smart(trim($email_flag));
$hphone_flag			= str_quote_smart(trim($hphone_flag));
$account					= str_quote_smart(trim($account));
$account_bank			= str_quote_smart(trim($account_bank));
$co_number				= str_quote_smart(trim($co_number));
$co_name					= str_quote_smart(trim($co_name));
$member_kind			= str_quote_smart(trim($member_kind));
$interest					= str_quote_smart(trim($interest));
$birth_y					= str_quote_smart(trim($birth_y));
$birth_m					= str_quote_smart(trim($birth_m));
$birth_d					= str_quote_smart(trim($birth_d));
$sex							= str_quote_smart(trim($sex));
$agree_01					= str_quote_smart(trim($agree_01));
$agree_02					= str_quote_smart(trim($agree_02));
$agree_03					= str_quote_smart(trim($agree_03));
$agree_04					= str_quote_smart(trim($agree_04));

$sel_agree01			= str_quote_smart(trim($sel_agree01));
$sel_agree02			= str_quote_smart(trim($sel_agree02));
$sel_agree03			= str_quote_smart(trim($sel_agree03));
$sel_agree04			= str_quote_smart(trim($sel_agree04));
$sel_agree05 			= str_quote_smart(trim($sel_agree05));
$sel_agree06			= str_quote_smart(trim($sel_agree06));

$reg_status				= str_quote_smart(trim($reg_status));

$couple_reg_jumin1 = encrypt($key, $iv, $couple_reg_jumin1);
$couple_reg_jumin2 = encrypt($key, $iv, $couple_reg_jumin2);
$en_account		   = encrypt($key, $iv, $account);
$enc_password	   = encrypt($key, $iv, $password);


$changeHistory = "";

$result = mysql_query("select * from tb_userinfo_dup where member_no = '".$member_no."'") or die(mysql_error());	
$row = mysql_fetch_array($result);
$account_name = $row[name];


if($row[name] == ""){
	echo '회원정보 조회 실패';
	sleep(1);
	echo "<script>js_close();</script>";

	exit;
}
echo "'<strong>".$row[name]."'</strong>님 정보조회 -> OK<br>";
flush();
ob_flush();

$passSql = "";
if(strlen($password) > 3 && $row[password] != $enc_password){
	$enc_password = encrypt($key, $iv, $password);
	$passSql = "password = '$enc_password',";
}
		
$query = "update tb_userinfo_dup set 
		number = '$number',
		ename = '$ename',
		$passSql
		active_kind = '$active_kind',
		couple = '$couple',
		couple_name = '$couple_name',
		couple_ename = '$couple_ename',
		couple_reg_jumin1 = '$couple_reg_jumin1',
		couple_reg_jumin2 = '$couple_reg_jumin2',
		hpho1 = '$hpho1',
		hpho2 = '$hpho2',
		hpho3 = '$hpho3',
		pho1 = '$pho1',
		pho2 = '$pho2',
		pho3 = '$pho3',
		zip = '$zip',
		addr = '$addr',
		addr_detail = '$addr_detail',
		del_zip = '$del_zip',
		del_addr = '$del_addr',
		del_addr_detail = '$del_addr_detail',
		email = '$email',
		email_flag = '$email_flag',
		hphone_flag = '$hphone_flag',
		account = '$en_account',
		account_bank = '$account_bank',
		co_name = '$co_name',
		co_number = '$co_number',
		birth_y = '$birth_y',
		birth_m = '$birth_m',
		birth_d = '$birth_d',
		sex = '$sex',
		member_kind = '$member_kind',
		interest = '$interest',
		moddate = now(),
		reg_status = '$reg_status',
		agree_01 = '$agree_01',
		agree_02 = '$agree_02',
		agree_03 = '$agree_03',
		agree_04 = '$agree_04',
		sel_agree01 = '$sel_agree01',
		sel_agree02 = '$sel_agree02',
		sel_agree03 = '$sel_agree03',
		sel_agree04 = '$sel_agree04',
		sel_agree05 = '$sel_agree05',
		sel_agree06 = '$sel_agree06',
		en_account = '$en_account'
		where member_no = '$member_no'";
		
$result = mysql_query($query) or die("Query Error");

//20210310
//agree_04 = '$agree_04',	[필수]개인정보 국외 이전에 대한 동의 삭제	
//sel_agree01 = '$sel_agree01', 개인정보(하나투어,레드캡,SMTT)제공동의 삭제


if($result){
	echo '정보수정->OK<br>';
}else{
	echo '정보수정에 실패하였습니다.<br>';
	flush();
	ob_flush();

	sleep(1);
	echo "<script>js_close();</script>";
	exit;
}
flush();
ob_flush();

//====== 정보변경 내역 확인 =======

$passChange = "N";
$enameChange = "N";
$addressChange = "N";
$bankChange = "N";
$hpChange = "N";
$telChange = "N";
$emailChange = "N";

if(strlen($password) > 3 && $row[password] != $enc_password){
	$old_password = decrypt($key, $iv, $row[password]);
	$changeHistory .= "비밀번호변경<br>";

}
		
if($row[ename] != $ename){  //영문성명 변경
	$enameChange = "Y";
	$changeHistory .=  "영문성명 변경 : ".$row[ename]."->".$ename."<br>";
}


if($row[zip] != $zip1 || $row[addr] != $addr || $row[addr_detail] != $addr_detail){ //우편번호 변경
	$addressChange = "Y";
	$changeHistory .=  "주소 변경 : ".$row[zip]." ". $row[addr]." ".$row[addr_detail]."->".$zip1." ".$addr." ".$addr_detail."<br>";

	if($building != "") $api_addr = $addr_detail." ".$building;
	else $api_addr = $addr_detail;

	$changeAddress = array(
			'address1' => $dong,
			'address2' => $api_addr,
			'city' => $city,
			'state' => $state,
			'zip' => $zip1,
			'country' => 'KR'
	);
	//updateMembersAddress($mem_account, $changeAddress);
}

if($row[account_bank] != $account_bank || decrypt($key, $iv, $row[account]) != $account){
	$bankChange = "Y";
	$result1 = mysql_query("select code from tb_code where parent='bank3' and name='".$account_bank."'") or die(mysql_error());	
	$row1 = mysql_fetch_array($result1);	
	$changedBankCode = $row1['code'];
	$changeHistory .=  "계좌정보 변경 : ".$row[account_bank]." ".decrypt($key, $iv, $row[account])."->".$account_bank."(".$changedBankCode.") ".$account."<br>";
}



if($row[hpho1].$row[hpho2].$row[hpho3] != $hpho1.$hpho2.$hpho3){
	$hpChange = 'Y';
	$changeHistory .= "핸드폰번호 변경 : ".$row[hpho1].$row[hpho2].$row[hpho3]."->".$hpho1.$hpho2.$hpho3."<br>";
}

if($pho1.$pho2.$pho3 != '' && $pho1.$pho2.$pho3  != $row[pho1].$row[pho2].$row[pho3]){
	$telChange = 'Y';
	$changeHistory .= "전화번호 변경 : ".$row[pho1].$row[pho2].$row[pho3]."->".$pho1.$pho2.$pho3."<br>";
}

if($row[email] != $email){ 
	$emailChange = 'Y';
	$changeHistory .=  "email변경 : ".$row[email]."->".$email."<br>";
}


$rightsArray = array();
if($row[agree_01] != $agree_01){
	$changeHistory .=  "방침 및 절차의 변경 통지(이메일) : ".$row[agree_01]."->".$agree_01."<br>";
	$rightsArray[agree_01] = array('방침 및 절차의 변경 통지(이메일)', 'Unicity', 'SendNoticeEmail', $agree_01);
}
if($row[agree_02] != $agree_02){
	$changeHistory .=  "방침 및 절차의 변경 통지(SMS) : ".$row[agree_02]."->".$agree_02."<br>";
	$rightsArray[agree_02] =  array('방침 및 절차의 변경 통지(SMS)', 'Unicity', 'SendNoticeSms', $agree_02);
}
if($row[agree_03] != $agree_03){
	$changeHistory .=  "방침 및 절차의 변경 통지(우편) : ".$row[agree_03]."->".$agree_03."<br>";
	$rightsArray[agree_03] =  array('방침 및 절차의 변경 통지(우편)', 'Unicity', 'SendMail', $agree_03);
}
/*
20210310삭제
if($row[sel_agree01] != $sel_agree01){ 
	$changeHistory .=  "개인정보 (하나투어, 레드캡, SMTT) 제공 동의 변경 : ".$row[sel_agree01]."->".$sel_agree01."<br>";
	$rightsArray[sel_agree01] =  array('개인정보 (하나투어, 레드캡, SMTT) 제공 동의', 'Unicity', 'ShareOrdersDataWithTravelAgency', $sel_agree01);
}
*/
if($row[sel_agree02] != $sel_agree02){
	$changeHistory .=  "마케팅 홍보 목적의 이메일 수신동의 변경 : ".$row[sel_agree02]."->".$sel_agree02."<br>";
	$rightsArray[sel_agree02] =  array('마케팅 홍보 목적의 이메일 수신동의', 'Unicity', 'SendMarketingEmail', $sel_agree02);
}
if($row[sel_agree03] != $sel_agree03){ 
	$changeHistory .=  "마케팅 홍보 목적의 SMS 수신동의 변경 : ".$row[sel_agree03]."->".$sel_agree03."<br>";
	$rightsArray[sel_agree03] =  array('마케팅 홍보 목적의 SMS 수신동의', 'Unicity', 'SendMarketingSms', $sel_agree03);
}
if($row[sel_agree04] != $sel_agree04){ 
	$changeHistory .=  "마케팅 목적의 우편물 수신 동의 변경 : ".$row[sel_agree04]."->".$sel_agree04."<br>";
	$rightsArray[sel_agree04] =  array('마케팅 목적의 우편물 수신 동의', 'Unicity', 'SendMarketingMail', $sel_agree04);
}
if($row[sel_agree05] != $sel_agree05){
	$changeHistory .=  "본인외 주문에 대한 동의 변경 : ".$row[sel_agree05]."->".$sel_agree05."<br>";
	$rightsArray[sel_agree05] =  array('본인외 주문에 대한 동의', 'Upline', 'Order', $sel_agree05);
}
if($row[sel_agree06] != $sel_agree06){
	$changeHistory .=  "다단계판매원 수첩과 등록증의 이메일 수령에 대한 동의 : ".$row[sel_agree06]."->".$sel_agree06."<br>";
	$rightsArray[sel_agree06] =  array('수첩 및 등록증 이메일 수령 동의', 'Unicity', 'SendMembershipBookEmail', $sel_agree06);
}

//관리자 로그
logging($s_adm_id,'modify dup user '.$number);

//수정내역저장
if($changeHistory != ''){
	$sql = "insert into tb_userinfo_history set
			gubun = 'userinfo',
			member_no = '".$member_no."', 
			number = '".$number."',
			name = '".$krname."',
			changed = '".$changeHistory."',
			redAdm = '".$s_adm_id."',
			regDate = now();";

	@mysql_query($sql);
}


if($passChange == "Y"){	
	echo "비밀번호 API 전송 : ";
	flush();
	ob_flush();
	$updateMembersPasswordResult = updateMembersPassword($number, $old_password, $password);	
	echo "<br>";
}
echo "수정이 완료되었습니다";
flush();
ob_flush();

sleep(1);
echo "<script>js_close();</script>";
exit;

//================================================================================================================================
//API재전송은 비밀번호만 이후는 API재전송 기능으로 변경
//================================================================================================================================

if($row[reg_status] != $reg_status && $reg_status == '4'){ //처리사항 완료로 변경시만 API전송

	//if($number != '') updateMembersRights($number, $rightsArray);

	if($changeHistory == ""){
		echo "수정이 완료되었습니다.<br>";
		flush();
		ob_flush();

		sleep(1);
		echo "<script>js_close();</script>";

		exit;
	}else{
		if($number == ""){
			echo "수정이 완료되었습니다.<br>";
			flush();
			ob_flush();

			sleep(1);
			echo "<script>js_close();</script>";

			exit;

		}else{
			echo '주요 회원정보 변경내역이 있어 API전송을 진행합니다.<br><br>';
			echo $changeHistory."<br>";
		}
	}
	flush();
	ob_flush();

	if($passChange == "Y"){	
		echo "비밀번호 API 전송 : ";
		$updateMembersPasswordResult = updateMembersPassword($number, $old_password, $password);	
		echo "<br>";
	}

	if($addressChange == "Y"){	
		echo "주소정보  API 전송 : ";
		updateMembersAddress($number, $changeAddress);
		echo "<br>";
	}

	if($bankChange == "Y"){
		echo "계좌정보 API 전송 : ";
		updateMembersBank($mem_account, $account_bank, $changedBankCode, $account_name, $account);	
		echo "<br>";
	}

	if($hpChange == 'Y' || $emailChange == 'Y' || $telChange == "Y" ){
		echo "회원기본정보 API 전송 : ";	
		//updateMembersEmailAndHp($number, $email, $hpho1.$hpho2.$hpho3);
		updateMembersInformation($number, $email, $hpho1.$hpho2.$hpho3, $pho1.$pho2.$pho3);
		echo "<br>";
	}

	if(count($rightsArray) > 0){
		echo "주요동의사항 API 전송 : <br>";
		flush();
		ob_flush();
		updateMembersRightsNew($number, $rightsArray);
		echo "<br>";
	}

	echo "수정 및 API전송이 완료되었습니다.<br>";

}else{
	echo "수정이 완료되었습니다.<br>";
	flush();
	ob_flush();

	sleep(1);
	echo "<script>js_close();</script>";
}



mysql_close($connect);

exit;

/*
echo "
	<html>\n
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
	<script language=\"javascript\">\n
	alert('수정 되었습니다.');
	</script>\n
	</html>";
exit;
*/
?>