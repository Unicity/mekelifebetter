<?php //개인토큰발행->공용토큰을 이용한 개인URL방식 전송으로 변경 ?>
<?session_start();?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<style type="text/css" title="">
body {font-size:12px; line-height:150%;  max-width:480px; max-width:480px; word-break:break-all;}
</style>
<?
//include "admin_session_check.inc";
include "./inc/global_init.inc";
include "../dbconn_utf8.inc";
include "../AES.php";
include "./inc/common_function.php";
include "./new_api_function.php";

$mode = str_quote_smart(trim($mode));
?>
<script type="text/javascript" src="./inc/jquery.js"></script>
<script>
function js_close(){
	parent.js_close_modal();
	//parent.frames[3].test('done');
}

$(function(){
	$('#allcheck').click(function(){ 
		if($("#allcheck").is(":checked")){ 
			$(".check").prop("checked", true); 
		}else{ 
			$(".check").prop("checked", false);
		} 
	});
	$('.check').click(function(){ 
		var cnt = $("input:checkbox[name='chk[]']:checked").length;
		if(cnt == ($('#grpChk').find('li').length - 1)) $("#allcheck").prop("checked", true); 
		else $("#allcheck").prop("checked", false); 
	});
});

function js_send(){
	var cnt = $("input:checkbox[name='chk[]']:checked").length;
	if(cnt < 1){
		alert('선택항목이 없습니다');
		return;
	}
	document.frm.submit();
}
</script>

<!-- <button onclick="js_close()">닫기</button> -->

<?php 
/*
if($member_kind != 'D'){
	echo "
	<script>
	alert('FO회원이 아닙니다');
	parent.js_close_modal();
	</script>";
	exit;
}
*/

if($member_no == '' || $number == ''){
	echo "
	<script>
	alert('회원번호가 없습니다.');
	parent.js_close_modal();
	</script>";
	exit;
}
?>

<?php if($_POST['chk'] == ""){ ?>

<div>
	<form name='frm' method="POST" action="<?=$_SERVER['PHP_SELF']?>">
	<input type="hidden" name="number" id="number" value="<?=$number?>">
	<input type="hidden" name="member_kind" id="member_kind" value="<?=$member_kind?>">
	<input type="hidden" name="member_no" id="member_no" value="<?=$member_no?>">
	<h3 style="width:100%;text-align:center">*** 변경된 정보는 먼저 <u>저장</u>후 전송하여 주세요 ***</h3>
	<h3 style="margin-top:20px">전송하실 항목을 선택하여 주세요</h3>
	<ul id="grpChk" style="margin:0; padding:0;">
		<li style="list-style:none"><input type="checkbox" name="allcheck" id="allcheck" value="ALL"> 전체</li>
		<!-- <li style="list-style:none"><input type="checkbox" name="chk[]" id="chk" class="check" value="pass"> 비밀번호</li> -->
		<!-- <li style="list-style:none"><input type="checkbox" name="chk[]" id="chk" class="check" value="ename"> 영문성명</li> -->
		<li style="list-style:none"><input type="checkbox" name="chk[]" id="chk" class="check" value="address"> 주소</li>
		<li style="list-style:none"><input type="checkbox" name="chk[]" id="chk" class="check" value="email"> 이메일</li>
		<li style="list-style:none"><input type="checkbox" name="chk[]" id="chk" class="check" value="phone"> 휴대전화번호</li>
		<li style="list-style:none"><input type="checkbox" name="chk[]" id="chk" class="check" value="tel"> 자택전화번호</li>
		<?php if($member_kind == 'D'){ ?>
			<li style="list-style:none"><input type="checkbox" name="chk[]" id="chk" class="check" value="bank"> 계좌정보</li>
		<?php } ?>
		<li style="list-style:none"><input type="checkbox" name="chk[]" id="chk" class="check" value="agree"> 주요동의항목(8개 항목)</li>
	</ul>
	</form>
	<div style="width:100%; text-align:center; padding-top:10px;"><button onclick="js_send()">선택항목 재전송</button></div>		
</div>

<?php exit; } ?>

<?php 
//전송처리 시작
$result = mysql_query("select * from tb_userinfo_dup where member_no = '".$member_no."'") or die(mysql_error());	
$row = mysql_fetch_array($result);

$account_name = $row[name];

$number = $row[number];
$password = decrypt($key, $iv, $row[password]);

$mobilePhone = '';
if($row['hpho1'] != '' && $row['hpho2'] != '' && $row['hpho3'] != '') $mobilePhone = $row['hpho1'].$row['hpho2'].$row['hpho3'];

$homePhone = '';  
if($row['pho1'] != '' && $row['pho2'] != '' && $row['pho3'] != '') $homePhone = $row['pho1'].$row['pho2'].$row['pho3'];

//주소 특수문자제거
$replace_char = array('샾', '숖', 'ㆍ', '・', '·', '잌', '믑', '틍');
$replace_char2 = array('샵', '숍', '', '', '', '익', '읍', '층');

for($i=0; $i<count($replace_char); $i++) {
	$row['addr'] = str_replace($replace_char[$i], $replace_char2[$i], $row['addr']);
	$row['addr_detail'] = str_replace($replace_char[$i], $replace_char2[$i], $row['addr_detail']);	
}

if($number == ''){
	echo "
	<script>
	alert('회원정보를 조회할 수 없습니다.');
	parent.js_close_modal();
	</script>";
	exit;
}

$chk = explode(",",implode(",", $_POST['chk']));

$infodata = array();
$infotitle = "";

if(in_array('address', $chk)){
	//주소변환
	$extra_gubun = array("고양시 덕양구","고양시 일산동구","고양시 일산서구","성남시 분당구","성남시 수정구","성남시 중원구","수원시 권선구", "수원시 영통구","수원시 장안구","수원시 팔달구","안산시 단원구","안산시 상록구","안양시 동안구","안양시 만안구","용인시 기흥구","용인시 수지구","용인시 처인구","전주시 덕진구","전주시 완산구","창원시 마산합포구","창원시 마산회원구","창원시 성산구","창원시 의창구","창원시 진해구","천안시 동남구","천안시 서북구","청주시 상당구","청주시 서원구","청주시 청원구","청주시 흥덕구","포항시 남구","포항시 북구");

	$arr = explode("(", $row['addr']);
	$extra = ($arr[1] != "") ? " (".$arr[1] : "";

	$addr = explode(" ", $arr[0]);
	$state = $addr[0];
	$city = $addr[1];
	$address1 = str_replace($state." ".$city, "",$arr[0]);
	$address2 = $row['addr_detail'].$extra;

	for($i=0; $i<count($extra_gubun); $i++) {
		if(str_replace($extra_gubun[$i], '', $arr[0]) != $arr[0]){
			$city = $extra_gubun[$i];
			$address1 = str_replace($state." ".$city, "",$arr[0]);
			break;	
		}		
	}

	$infodata[mainAddress] = array(
				'address1' =>$address1,
				'address2' => $address2,
				'city' => $city,
				'state' => $state,
				'country' => "KR",
				'zip' => $row['zip']
			);
	if($infotitle == "") $infotitle = "주소";
	else $infotitle .= ", 주소";
}
if(in_array('email', $chk)){
	$infodata[email] = $row['email'];
	if($infotitle == "") $infotitle = "이메일";
	else $infotitle .= ", 이메일";
}
if(in_array('phone', $chk)){
	$infodata[mobilePhone] = $mobilePhone;
	if($infotitle == "") $infotitle = "휴대전화";
	else $infotitle .= ", 휴대전화";
}
if(in_array('tel', $chk)){
	$infodata[homePhone] = $homePhone;
	if($infotitle == "") $infotitle = "자택전화";
	else $infotitle .= ", 자택전화";
}

if(in_array('bank', $chk)){
	$account_bank = $row[account_bank];
	$account = decrypt($key, $iv, $row[account]);
	$account_name = $row[name];

	$result2 = mysql_query("SELECT code FROM tb_code where parent='bank3' and name = '".$row[account_bank]."'") or die(mysql_error()); 
	$row2 = mysql_fetch_array($result2);
	$search_bank_code = $row2[0];

	if($search_bank_code == ""){
		echo "
		<script>
		alert('은행코드를 조회할 수 없습니다.');
		parent.js_close_modal();
		</script>";
		exit;
	}
}

if(in_array('pass', $chk)){
//	echo '비밀번호 업데이트';
}


$mem_account = $number;

echo "*** ".$row[name]."님 API 재전송 Start ***<br>";
flush();
ob_flush();


echo "로그인 토근발행 발행 : ";
flush();
ob_flush();

//로그인 토근발행
$headers = array(
	'Content-Type: application/json'	
);

$url = "https://hydra.unicity.net/v5a/loginTokens?expand=whoami";
$key = base64_encode($number.":".$password);
$data = json_encode(
	array(
		'type' => 'base64',
		'value' => $key,
		'namespace' => "https://hydra.unicity.net/v5a/customers"
	)
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$response = curl_exec($ch);
$result = json_decode($response, true);
$token = $result[token];
curl_close($ch);
if (isset($result["error"]["code"])) {
	 echo $result["error"]["code"]."<br>";
	 flush();
	 ob_flush();
	 return;
}
if($token  == ""){
	echo "token error<br>";
	flush();
	ob_flush();
	exit;
}

$me_url = $result[customer][href];

echo "OK<br>";

//echo "token => ".$token."<Br>";
//echo "href => ".$me_url."<Br>";
flush();
ob_flush();

usleep(300000); //0.3초

$headers = array(
	'Authorization: Bearer '.$token
);

//이메일, 주소, 휴대전화번호, 자택전화번호 재전송
//if(in_array('info', $chk)){
if( count($infodata) > 0){

	echo $infotitle." 재전송";
	//echo "이메일, 주소, 휴대전화번호, 자택전화번호 재전송";
	flush();
	ob_flush();

	/*$data = json_encode(array(
		'mainAddress' => array(
				'address1' =>$address1,
				'address2' => $address2,
				'city' => $city,
				'state' => $state,
				'country' => "KR",
				'zip' => $row['zip']
			),		
		'email' => $row['email'],
		'mobilePhone' => $row['hpho1'].$row['hpho2'].$row['hpho3'],
		'homePhone' => $row['pho1'].$row['pho2'].$row['pho3']
	));
	*/
	$data = json_encode($infodata);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://hydra.unicity.net/v5a/customers/me');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer '.$token));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$result = curl_exec($ch); 
	$data = json_decode($result, true);
	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	if ($response !== false && ($status == 200 ||$status == 201)) {		
		echo "->OK<br>";
	}else{
		echo "->Fail<br>";
	}
	flush();
	ob_flush();
}


//계좌정보 재전송
if(in_array('bank', $chk)){
	
	echo "계좌정보 재전송";
	flush();
	ob_flush();

	$data = json_encode(array(
			'depositBankAccount' => array(
					'bankName' => $account_bank,
					'bin' => $search_bank_code,
					'accountHolder' => $account_name,
					'accountNumber' => $account,
					'accountType' => 'SavingsPersonal',
					'routingNumber' => 1,
				)
			));

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://hydra.unicity.net/v5a/customers/me');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer '.$token));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$result = curl_exec($ch); 
	$data = json_decode($result, true);
	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	if ($response !== false && ($status == 200 ||$status == 201)) {		
		if($data[depositBankAccount][bin] == $search_bank_code && $data[depositBankAccount][accountNumber] == $account) echo "->OK<br>";
		else echo "->Fail<br>";
	}else{
		echo "->Fail<br>";
	}
	flush();
	ob_flush();
}

//주요동의사항 업데이트
if(in_array('agree', $chk)){	
	echo '주요동의항목 재전송<br>';
	flush();
	ob_flush();
	
	if(time() >= strtotime('2021-03-10 19:00:00')){
		$rightsArray = array(
			"selchk5"  => array('본인외 주문에 대한 동의', 'Upline', 'Order', $row['sel_agree05']),
			"selchk6"  => array('수첩 및 등록증 이메일 수령 동의', 'Unicity', 'SendMembershipBookEmail', $row['sel_agree06']),
			"selchk2"  => array('마케팅 목적의 이메일 수신동의', 'Unicity', 'SendMarketingEmail', $row['sel_agree02']),
			"selchk3"  => array('마케팅 목적의 SMS 수신 동의', 'Unicity', 'SendMarketingSms', $row['sel_agree03']),
			"selchk4"  => array('마케팅 목적의 우편물 수신 동의', 'Unicity', 'SendMarketingMail', $row['sel_agree04']),
			"agree_01" => array('방침 및 절차의 변경 통지(이메일)', 'Unicity', 'SendNoticeEmail', $row['agree_01']),
			"agree_02" => array('방침 및 절차의 변경 통지(SMS)', 'Unicity', 'SendNoticeSms', $row['agree_02']),
			"agree_03" => array('방침 및 절차의 변경 통지(우편)', 'Unicity', 'SendMail', $row['agree_03']),			
			);
	}else{
		$rightsArray = array(
			"agree_01" => array('이메일 통보', 'Unicity', 'SendNoticeEmail', $row['agree_01']),
			"agree_02" => array('주요안내사항 통보', 'Unicity', 'SendNoticeSms', $row['agree_02']),
			"agree_03" => array('SMS 통보', 'Unicity', 'SendMail', $row['agree_03']),
			"selchk1"  => array('개인정보 (하나투어, 레드캡, SMTT) 제공 동의', 'Unicity', 'ShareOrdersDataWithTravelAgency', $row['sel_agree01']),
			"selchk2"  => array('마케팅 목적의 이메일 수신 동의', 'Unicity', 'SendMarketingEmail', $row['sel_agree02']),
			"selchk3"  => array('마케팅 목적의 SMS 수신 동의', 'Unicity', 'SendMarketingSms', $row['sel_agree03']),
			"selchk4"  => array('마케팅 목적의 우편물 수신 동의', 'Unicity', 'SendMarketingMail', $row['sel_agree04']),
			"selchk5"  => array('본인외 주문에 대한 동의', 'Upline', 'Order', $row['sel_agree05'])
			);
	}

	foreach($rightsArray as $key=>$val) {

		$title = $val[0];
		$holder = $val[1];
		$type = $val[2];
		$agree = $val[3];
		
		$ch = curl_init();		

		if(strtoupper($agree) == 'Y') {
			$data = json_encode(array(
				'holder' => $holder,
				'type' => $type
			));			
			$url = 'https://hydra.unicity.net/v5a/customers/me/rights';
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer '.$token));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		} else {				
			$url = 'https://hydra.unicity.net/v5a/customers/me/rights?type='.$type.'&holder='.$holder;
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 0);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:Bearer '.$token));
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		}

		$response = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		$result = json_decode($response);

		//echo $title.$holder.$type.$agree.":";
		echo "-".$title."(".$agree."):";

		if($status == "201" || $status == "204"){  //delete인경우 204
			echo "->OK<br>";			
		}else{			
			echo "->Fail<br>";		
		}
		flush();
		ob_flush();
		curl_close($ch);
	}
	usleep(300000); //0.3초
	
} 
echo "*** API전송이 완료되었습니다 ***";

echo "<br><br>";

echo "*** 본사에 등록된 회원정보를 조회합니다 ***<br>";
$ch = curl_init();		

$headers = array(
	'Authorization: Bearer '.$token
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $me_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$result = curl_exec($ch); 
$data = json_decode($result, true);

//echo "========data parse=====<br>";
//echo $data[unicity]."<br>";
echo "address1->".$data[mainAddress][address1]."<br>";
echo "address2->".$data[mainAddress][address2]."<br>";
echo "city->".$data[mainAddress][city]."<br>";
echo "state->".$data[mainAddress][state]."<br>";
echo "zip->".$data[mainAddress][zip]."<br>";
//echo "<br>";
//echo "firstName->".$data[humanName]['firstName']."<br>";
//echo "lastName->".$data[humanName]['lastName']."<br>";
//echo "fullName->".$data[humanName]['fullName']."<br>";
//echo "fullName@ko->".$data[humanName]['fullName@ko']."<br>";
//echo "<br>";
//echo "".$data[id]['unicity']."<br>";
//echo "<br>";
//echo "".$data[sponsoredCustomers]['href']."<br>";
//echo "<br>";
//echo "birthDate->".$data[birthDate]."<br>";
echo "email->".$data[email]."<br>";
//echo "".$data[enroller]['href]']."<br>";
//echo "enroller->".$data[enroller]['id']['unicity']."<br>";
//echo "gender->".$data[gender]."<br>";
echo "homePhone->".$data[homePhone]."<br>";
echo "mobilePhone->".$data[mobilePhone]."<br>";
//echo "taxTerms->".$data[taxTerms]['taxId']."<br>";

//print_r($data[rights])."<br>";

echo "주요동의사항<br>";
if(count($data[rights]) < 1){
	echo "- 동의 내역 없슴<br>";
}else{
	for($i=0; $i<count($data[rights]); $i++) {
		echo "-holder:".$data[rights][$i][holder].", type:".$data[rights][$i][type]."<br>";
	}
}
flush();
ob_flush();
exit;
?>
