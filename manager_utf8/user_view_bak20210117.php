<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";
	include "./inc/common_function.php";

	function right($value, $count){
		$value = substr($value, (strlen($value) - $count), strlen($value));
		return $value;
	}

	function left($string, $count){
		return substr($string, 0, $count);
	}

	function str_cut($str,$len){
		$slen = strlen($str);
		if (!$str || $slen <= $len) $tmp = $str;
		else	$tmp = preg_replace("/(([\x80-\xff].)*)[\x80-\xff]?$/", "\\1", substr($str,0,$len))."...";
		return $tmp;
	}


	function makeCode ($parent)  { 

		$sqlstr = "SELECT * FROM tb_code where parent='".$parent."' order by code"; 

		$result = mysql_query($sqlstr);
		$total 	= mysql_affected_rows();
			
		for($i=0 ; $i< $total ; $i++)	{  	//  start 에서 scale 까지 만

			if($i< $total )	{ 								// 전체 자료 개수까지만 출력

				mysql_data_seek($result,$i);
				$row = mysql_fetch_array($result);		
				print("<option value='$row[code]' style='color:352000'>$row[name]</option>\n");

			}
		}
	}

	function makeCodeAsName ($parent)  { 

		$sqlstr = "SELECT * FROM tb_code where parent='".$parent."' order by code"; 

		$result = mysql_query($sqlstr);
		$total 	= mysql_affected_rows();
			
		for($i=0 ; $i< $total ; $i++)	{  	//  start 에서 scale 까지 만

			if($i< $total )	{ 								// 전체 자료 개수까지만 출력

				mysql_data_seek($result,$i);
				$row = mysql_fetch_array($result);		
				print("<option value='$row[name]' style='color:352000'>$row[name] ($row[code])</option>\n");

			}
		}
	}

	

	$mode						= str_quote_smart(trim($mode));
	$member_no			= str_quote_smart(trim($member_no));
	$member_kind		= str_quote_smart(trim($member_kind));
	$idxfield				= str_quote_smart(trim($idxfield));
	$idx						= str_quote_smart(trim($idx));
	$qry_str				= str_quote_smart(trim($qry_str));
	$con_sort				= str_quote_smart(trim($con_sort));
	$con_order			= str_quote_smart(trim($con_order));
	$r_status				= str_quote_smart(trim($r_status));
	$r_memberkind		= str_quote_smart(trim($r_memberkind));
	$r_join_kind		= str_quote_smart(trim($r_join_kind));
	$r_active_kind	= str_quote_smart(trim($r_active_kind));
	$r_couple				= str_quote_smart(trim($r_couple));
	$from_date			= str_quote_smart(trim($from_date));
	$to_date				= str_quote_smart(trim($to_date));

	logging($s_adm_id,'view member '.$member_no.' (user_view.php)');
	
	$query = "select * from tb_userinfo where member_no = '".$member_no."'";
	
#	echo $query;	

	$result = mysql_query($query);
	$list = mysql_fetch_array($result);

	$number = $list[number];
	$member_no = $list[member_no];
	$password = $list[password];
	$name = $list[name];
	$ename = $list[ename];
	$reg_jumin1 = $list[JU_NO01];
	$reg_jumin2 = $list[JU_NO02];
	$tax_number = $list[tax_number];
	$active_kind = $list[active_kind];
	$couple = $list[couple];
	$couple_name = $list[couple_name];
	$couple_ename = $list[couple_ename];
	$couple_reg_jumin1 = $list[couple_reg_jumin1];
	$couple_reg_jumin2 = $list[couple_reg_jumin2];
	$zip = $list[zip];
	$addr = $list[addr];
	$addr_detail = $list[addr_detail];
	$del_zip = $list[del_zip];
	$del_addr = $list[del_addr];
	$del_addr_detail = $list[del_addr_detail];
	$hpho1 = $list[hpho1];
	$hpho2 = $list[hpho2];
	$hpho3 = $list[hpho3];
	$pho1 = $list[pho1];
	$pho2 = $list[pho2];
	$pho3 = $list[pho3];
	$account = $list[account];
	$account_bank = $list[account_bank];
	$email = $list[email];
	$email_flag = $list[email_flag];
	$interest = $list[interest];
	$co_number = $list[co_number];
	$co_name = $list[co_name];
	$join_kind = $list[join_kind];
	$hphone_flag = $list[hphone_flag];

	$member_kind = $list[member_kind];
	$birth_y = $list[birth_y];
	$birth_m = $list[birth_m];
	$birth_d = $list[birth_d];
	$sex = $list[sex];

	$regdate = $list[regdate];
	$moddate = $list[moddate];

	$reg_status = $list[reg_status];
	$print_date = $list[print_date];
	$print_ma = $list[print_ma];
	$confirm_person_date = $list[confirm_person_date];
	$confirm_person_ma = $list[confirm_person_ma];
	$confirm_date = $list[confirm_date];
	$confirm_ma = $list[confirm_ma];
	$wait_date = $list[wait_date];
	$wait_ma = $list[wait_ma];
	$reject_date = $list[reject_date];
	$reject_ma = $list[reject_ma];
	$sms_date = $list[sms_date];
	$sms_ma = $list[sms_ma];
	$email_date = $list[email_date];
	$email_ma = $list[email_ma];
	$live = $list[live];
	$ldate = $list[ldate];
	$visit_count = $list[visit_count];
	$memo = $list[memo];

	$agree_01 = $list[agree_01];
	$agree_02 = $list[agree_02];
	$agree_03 = $list[agree_03];
	$agree_04 = $list[agree_04];

	$sel_agree01 = $list[sel_agree01];
	$sel_agree02 = $list[sel_agree02];
	$sel_agree03 = $list[sel_agree03];
	$sel_agree04 = $list[sel_agree04];
	$sel_agree05 = $list[sel_agree05];

	if($agree_01 == "")  $agree_01 = "N";
	if($agree_02 == "")  $agree_02 = "N";
	if($agree_03 == "")  $agree_03 = "N";
	if($agree_04 == "")  $agree_04 = "N";
	if($sel_agree01 == "") $sel_agree01 = "N";
	if($sel_agree02 == "") $sel_agree02 = "N";
	if($sel_agree03 == "") $sel_agree03 = "N";
	if($sel_agree04 == "") $sel_agree04 = "N";
	if($sel_agree05 == "") $sel_agree05 = "N";
	
	$DI = $list[DI];

	$JU_NO01 = decrypt($key, $iv, $reg_jumin1);
	$JU_NO02 = decrypt($key, $iv, $reg_jumin2);

	$account = decrypt($key, $iv, $account);

	$couple_reg_jumin1 = decrypt($key, $iv, $couple_reg_jumin1);
	$couple_reg_jumin2 = decrypt($key, $iv, $couple_reg_jumin2);


	$zip_ep = explode("-", $zip);
	$del_zip_ep = explode("-", $del_zip);
	$email_ep = explode("@", $email);

	if (strlen($interest) == 6) {
		$interest_1 = substr($interest, 0, 1);
		$interest_2 = substr($interest, 1, 1);
		$interest_3 = substr($interest, 2, 1);
		$interest_4 = substr($interest, 3, 1);
		$interest_5 = substr($interest, 4, 1);
		$interest_6 = substr($interest, 5, 1);
	}

	if ($regdate != null) {
		$date_s1 = date("Y-m-d [H:i]", strtotime($regdate));
	} else {
		$date_s1 = "";
	}

	if ($ldate != null) {
		$date_s2 = date("Y-m-d [H:i]", strtotime($ldate));
	} else {
		$date_s2 = "";
	}

	if ($print_date != null) {
		$date_sp = date("Y-m-d [H:i]", strtotime($print_date));
	} else {
		$date_sp = "";
	}

	if ($confirm_date != null) {
		$date_sc = date("Y-m-d [H:i]", strtotime($confirm_date));
	} else {
		$date_sc = "";
	}

	if ($confirm_person_date != null) {
		$date_scp = date("Y-m-d [H:i]", strtotime($confirm_person_date));
	} else {
		$date_scp = "";
	}

	if ($reject_date != null) {
		$date_sr = date("Y-m-d [H:i]", strtotime($reject_date));
	} else {
		$date_sr = "";
	}

	if ($wait_date != null) {
		$date_sw = date("Y-m-d [H:i]", strtotime($wait_date));
	} else {
		$date_sw = "";
	}

	if ($email_date != null) {
		$date_se = date("Y-m-d [H:i]", strtotime($email_date));
	} else {
		$date_se = "";
	}

	if ($sms_date != null) {
		$date_ss = date("Y-m-d [H:i]", strtotime($sms_date));
	} else {
		$date_ss = "";
	}	

	
	if ($member_no > 160617) {

		$query = "select check_kind, name, jumin1, jumin2, chkdate from tb_check_log
								where jumin2 = '".$DI."'
									and flag = 'Y' and check_kind in ('C','M','X','I') order by check_no desc ";

					//echo $query;

	} else {

		$query = "select check_kind, name, jumin1, jumin2, chkdate from tb_check_log
							where jumin1 = '".$reg_jumin1."'
								and jumin2 = '".$reg_jumin2."'
								and flag = 'Y' and check_kind in ('C','M','X') order by check_no desc ";

		}



	$result_chk = mysql_query($query);
	
	$str_chk_type = "";

	while($row_chk = mysql_fetch_array($result_chk)) {
		$check_kind = $row_chk[check_kind];
		$chkdate = $row_chk[chkdate];
		$chkdate = date("[H:i]", strtotime($chkdate));
		if ($check_kind == "C") $str_check_kind = "신용카드";
		if ($check_kind == "M") $str_check_kind = "모바일";
		if ($check_kind == "X") $str_check_kind = "공인인증서";
		if ($check_kind == "I") $str_check_kind = "아이핀";

		$str_chk_type = $str_chk_type.$str_check_kind." ".$chkdate."<br>";

	}


?>		
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta http-equiv="X-Frame-Options" content="deny" />
<script type="text/javascript" src="inc/jquery.js"></script>
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>

<SCRIPT language="javascript">
<!--
	function init() {
		document.frm_m.hpho1.value = "<?echo $hpho1?>";
		document.frm_m.pho1.value = "<?echo $pho1?>";
<? 	if ($member_kind == "D") {?>
		document.frm_m.account_bank.value = "<?echo $account_bank?>";
<?	}?>
	}

	function lengthCheck3(form, str) {
		if (str.length >= 6) {
			frm_m.couple_reg_jumin1.blur();
		//	frm_m.couple_reg_jumin2.focus();
		}
	}

	function goBack() {
		document.frm_m.target = "frmain";
		document.frm_m.action="user_list.php";
		document.frm_m.submit();
	}

	function reload_user() {
		document.frm_m.target = "frmain";
		document.frm_m.action="user_view.php";
		document.frm_m.submit();
	}

	function goMemo(kind) {
		var url = "user_memo.php?memo_kind="+kind+"&member_no="+document.frm_m.member_no.value;
		NewWindow(url, "memo_page", '600', '250', "no");
	}

	function updateBankInfo() {
		$('#bankMsg').html('API 업데이트 중입니다...');
		$.ajax({
			type: 'post',
			url: 'user_bank_update.php',
			data: {member_no: document.frm_m.member_no.value, 'account' :  document.frm_m.account.value},
			success: function(msg){
				//console.log(msg);
				if(msg == "OK"){
					$('#bankMsg').html('업데이트 되었습니다');
				}else{
					$('#bankMsg').html(msg);
				}
			},
			error: function( jqXHR, textStatus, errorThrown ) { 
				alert( textStatus + ", " + errorThrown ); 
			} 
		});

	}

	function goSMS() {
			
		var url = "user_sms_ba.php?member_no="+document.frm_m.member_no.value;
	
		NewWindow(url, "sms_page", '700', '260', "no");
		
	}
	
	function goEmail() {
			
		var url = "user_email_ba.php?member_no="+document.frm_m.member_no.value;
	
		NewWindow(url, "email_page", '700', '280', "no");
		
	}

	function goCEmail() {
			
		var url = "user_cemail_ba.php?member_no="+document.frm_m.member_no.value;
	
		NewWindow(url, "email_page", '700', '280', "no");
		
	}


	function goDel() {
	
		bDelOK = confirm("정말 삭제 하시겠습니까?\n\n한번 삭제 하시면 복구 하실 수 없습니다.");
		
		if ( bDelOK ==true ) {
			document.frm_m.mode.value = "del";
			document.frm_m.action = "user_db.php";
			document.frm_m.submit();
		} else {
			return;
		}

	}
	function goInfo() {
		alert('준비중입니다');
		return;
		/*
		if (document.frm_m.number.value == "") {
			alert("회원번호를 입력하세요.");
			document.frm_m.number.focus();
			return;
		}
		fieldValidation();	

		document.frm_m.target = "frhidden";
		document.frm_m.action = "./updateUserInfo/updateInfo.php?type=new";
		document.frm_m.submit();
		*/
	}

	function goIn() {
		fieldValidation();		
		
//		alert(document.frm_m.interest.value);
			
		document.frm_m.target = "frhidden";
		document.frm_m.action = "user_db.php";
		document.frm_m.submit();
		
	}
	function fieldValidation() {
		if (document.frm_m.name.value == "") {
			alert("한글성명을 입력하세요.");
			document.frm_m.name.focus();
			return;
		}

		if (document.frm_m.ename.value == "") {
			alert("영문성명을 입력하세요.");
			document.frm_m.ename.focus();
			return;
		}

	/*	if (document.frm_m.password.value == "") {
			alert("희망 하시는 비밀번호를 입력하세요.");
			document.frm_m.password.focus();
			return;
		}
*/
		if (document.frm_m.birth_y.value == "") {
			alert("생년월일 입력하세요.");
			document.frm_m.birth_y.focus();
			return;
		}

		if(!isNumber(document.frm_m.birth_y)) {
			alert("숫자만 입력해 주세요.");
			document.frm_m.birth_y.focus();
			return;
		}

		if (document.frm_m.birth_m.value == "") {
			alert("생년월일 입력하세요.");
			document.frm_m.birth_m.focus();
			return;
		}

		if(!isNumber(document.frm_m.birth_m)) {
			alert("숫자만 입력해 주세요.");
			document.frm_m.birth_m.focus();
			return;
		}

		if (document.frm_m.birth_d.value == "") {
			alert("생년월일 입력하세요.");
			document.frm_m.birth_d.focus();
			return;
		}

		if(!isNumber(document.frm_m.birth_d)) {
			alert("숫자만 입력해 주세요.");
			document.frm_m.birth_d.focus();
			return;
		}

		if (document.frm_m.couple[0].checked==1) {

			if (document.frm_m.couple_name.value == "") {
				alert("배우자 한글성명을 입력하세요.");
				document.frm_m.couple_name.focus();
				return;
			}

			if (document.frm_m.couple_ename.value == "") {
				alert("배우자 영문성명을 입력하세요.");
				document.frm_m.couple_ename.focus();
				return;
			}
		

			if (document.frm_m.couple_reg_jumin1.value == "") {
				alert("배우자 주민등록번호를 입력하세요.");
				document.frm_m.couple_reg_jumin1.focus();
				return;
			}

			if(!isNumber(document.frm_m.couple_reg_jumin1)) {
				alert("숫자만 입력해 주세요.");
				document.frm_m.couple_reg_jumin1.focus();
				return;
			}

			if (document.frm_m.couple_reg_jumin2.value == "") {
				alert("배우자 주민등록번호를 입력하세요.");
				document.frm_m.couple_reg_jumin2.focus();
				return;
			}

			if(!isNumber(document.frm_m.couple_reg_jumin2)) {
				alert("숫자만 입력해 주세요.");
				document.frm_m.couple_reg_jumin2.focus();
				return;
			}

		}

		if (document.frm_m.zip1.value == "") {
			alert("우편번호를 입력하세요.");
			document.frm_m.zip1.focus();
			return;
		}
		
		/*
		if (document.frm_m.zip2.value == "") {
			alert("우편번호를 입력하세요.");
			document.frm_m.zip2.focus();
			return;
		}
		*/

		document.frm_m.zip.value = document.frm_m.zip1.value;

		if (document.frm_m.addr.value == "") {
			alert("주소를 입력하세요.");
			document.frm_m.addr.focus();
			return;
		}

		if (document.frm_m.del_zip1.value != "") {
			document.frm_m.del_zip.value = document.frm_m.del_zip1.value;
		}

		if (document.frm_m.hpho2.value == "") {
			alert("휴대전화번호를 입력하세요.");
			document.frm_m.hpho2.focus();
			return;
		}

		if(!isNumber(document.frm_m.hpho2)) {
			alert("숫자만 입력해 주세요.");
			document.frm_m.hpho2.focus();
			return;
		}

		if (document.frm_m.hpho3.value == "") {
			alert("휴대전화번호를 입력하세요.");
			document.frm_m.hpho3.focus();
			return;
		}

		if(!isNumber(document.frm_m.hpho3)) {
			alert("숫자만 입력해 주세요.");
			document.frm_m.hpho3.focus();
			return;
		}

		/*
		if (document.frm_m.pho2.value == "") {
			alert("전화번호를 입력하세요.");
			document.frm_m.pho2.focus();
			return;
		}

		if(!isNumber(document.frm_m.pho2)) {
			alert("숫자만 입력해 주세요.");
			document.frm_m.pho2.focus();
			return;
		}

		if (document.frm_m.pho3.value == "") {
			alert("전화번호를 입력하세요.");
			document.frm_m.pho3.focus();
			return;
		}

		if(!isNumber(document.frm_m.pho3)) {
			alert("숫자만 입력해 주세요.");
			document.frm_m.pho3.focus();
			return;
		}
		*/

<? if ($member_kind == "D") {?>

		if (document.frm_m.account_bank.value == "" ) {
			alert("거래은행을 선택해 주십시오.");
			document.frm_m.account_bank.focus();
			return;
		}

		if (document.frm_m.account.value == "" ) {
			alert("계좌번호를 입력해 주십시오.");
			document.frm_m.account.focus();
			return;
		}
<?	} ?>
		if (document.frm_m.email1.value == "" ) {
			alert("이메일을 입력해 주세요.");
			document.frm_m.email1.focus();
			return;
		}

		if (document.frm_m.email2.value == "" ) {
			alert("이메일을 입력해 주세요.");
			document.frm_m.email2.focus();
			return;
		}

		document.frm_m.email.value = document.frm_m.email1.value+"@"+document.frm_m.email2.value;

		if (!isValidEmail(document.frm_m.email)) {
			alert("이메일을 정확히 입력해 주세요.");
			document.frm_m.email1.focus();
			return;
		}

		var check_count = 0;
		var check_result = "";
		var total = document.frm_m.chk_interest.length;
						 
		for(var i=0; i< total; i++) {
			if(document.frm_m.chk_interest[i].checked == true) {				
				check_result = check_result+"Y";
	    		check_count++;
	   	 	} else {
				check_result = check_result+"N";
			}
	   	 	
		}
		/*
		if(check_count == 0) {
			alert("하나 이상의 관심 항목을 선택해 주십시오 .");
	    	return;
		}
		*/
		document.frm_m.interest.value = check_result;
	}

	function isValidEmail(input) {
//    var format = /^(\S+)@(\S+)\.([A-Za-z]+)$/;
    	var format = /^((\w|[\-\.])+)@((\w|[\-\.])+)\.([A-Za-z]+)$/;
    	return isValidFormat(input,format);
	}
	
	function isValidFormat(input,format) {
    	if (input.value.search(format) != -1) {
        	return true; //올바른 포맷 형식
    	}
    	return false;
	}

	function containsCharsOnly(input,chars) {
    	for (var inx = 0; inx < input.value.length; inx++) {
       		if (chars.indexOf(input.value.charAt(inx)) == -1)
           		return false;
    	}
    	return true;	
	}

	function isNumber(input) {
    	var chars = "0123456789";
    	return containsCharsOnly(input,chars);
	}

	function NewWindow(mypage, myname, w, h, scroll) {
		var winl = (screen.width - w) / 2;
		var wint = (screen.height - h) / 2;
		winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
		win = window.open(mypage, myname, winprops)
		if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
	}

	function Addr(zipcode1,zipcode2, address, add) {

		if (add == "addr") {
			document.frm_m.zip1.value = zipcode1;
			document.frm_m.zip2.value = zipcode2;
			document.frm_m.addr.value = address;
		} else {
			//alert(address);
			document.frm_m.del_zip1.value = zipcode1;
			document.frm_m.del_zip2.value = zipcode2;
			document.frm_m.del_addr.value = address;
		}
	}

	function test(str){
		//alert(str);
	}
//-->
</SCRIPT>
</HEAD>
<BODY  onLoad="init();">

<?php include "common_load.php" ?>

<? if($_SERVER['HTTPS'] == "on"){ ?>
    <script src="https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js"></script>
<? }else{ ?>
    <script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<? } ?>
<script>
//다음주소검색
function execDaumPostcode(zip, addr1, addr2) {
	new daum.Postcode({
		oncomplete: function(data) {
			var fullRoadAddr = data.roadAddress; // 도로명 주소 변수
			var extraRoadAddr = ''; // 도로명 조합형 주소 변수
			if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
				extraRoadAddr += data.bname;
			}
			//if(data.buildingName !== '' && data.apartment === 'Y'){
			if(data.buildingName !== ''){
				extraRoadAddr += (extraRoadAddr !== '' ? ', ' + data.buildingName : data.buildingName);
			}
			if(extraRoadAddr !== ''){
				extraRoadAddr = '(' + extraRoadAddr + ')';
			}
			//if(fullRoadAddr !== ''){
			//	fullRoadAddr += extraRoadAddr;
			//}

			if(zip == 'zip1'){  //주소인 경우 세분화 주소 업데이트
				var streetNo = data.address.substr(data.address.indexOf(data.roadname)+data.roadname.length);

				var new_addr = fullRoadAddr.substr(fullRoadAddr.indexOf(data.roadname)+data.roadname.length);
				//new_addr = new_addr.replace(',','').replace('(','').replace(')','');
				//new_addr =  data.roadname+new_addr;				

				document.getElementById('state').value = data.sido;
				document.getElementById('city').value = data.sigungu;
				//상세주소 포함해서 전송하도록 요청 2021-01-05 이정윤대리 -> address2 +  (동,건물명) 형태로 Api전송요청 2021-01-06 이성수부장님 
				//document.getElementById('dong').value = data.roadname+streetNo;
				document.getElementById('dong').value = data.roadname + new_addr;
				document.getElementById('building').value = 	extraRoadAddr

			}
			document.getElementById(zip).value = data.zonecode; //5자리 새우편번호 사용
			document.getElementById(addr1).value = fullRoadAddr;
			//document.getElementById('sample4_jibunAddress').value = data.jibunAddress;
			document.getElementById(addr2).focus();
		}
	}).open();
}
</script>

<form name="frm_m" method="post" action="user_db.php">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>온라인 등록 회원 관리 (수정)</B></TD>
	<TD align="right" width="300" align="center" bgcolor=silver>
		<input type="button" onClick="goIn();" value="수정" name="btn3">
		<input type="button" onClick="goBack();" value="목록" name="btn4">
<?	#if ($s_flag == "1") {?>
		<input type="button" onClick="goDel();" value="삭제" name="btn5">
<?	#}?>
		<input type="button" onClick="goInfo();" value="인포전송" name="btn6">
		<INPUT type="hidden" name="page" value="<?echo $page?>">
	</TD>
</TR>
</TABLE>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN">
<tr>
	<th>
		성명 :
	</th>
	<td><?echo $name?></td>
</tr>
<tr>
	<th>
		영문성명 :
	</th>
	<td>
		<input type="text" name="ename" size="20" value="<?echo $ename?>">
		<input type="hidden" name="krname" value="<?=$name?>">
	</td>
</tr>
<tr>
	<th>
		FO number :
	</th>
	<td>
<?	#if ($s_flag == "1") {?>
		<input type="text" name="number" size="15" value="<?echo $number?>"> <!--최고 관리자만 수정 가능 합니다.-->
<?	#} else {?>
<!00		<?echo $number?> 최고 관리자만 수정 가능 합니다.-->
<!--		<input type="hidden" name="number" value="<?echo $number?>">-->
<?	#}	?>
	</td>
</tr>
<tr>
	<th>
		희망 비밀번호 :
	</th>
	<td>
		<input type="text" name="password" size="15" value=""> <font color="red">* 수정시만입력(4자이상)</font>
		<input type="hidden" size="15" value="<?echo $password?>">
	</td>
</tr>
<!--
<tr>
	<th>
		생년월 :
	</th>
	<td>
		일 
		
	</td>
</tr>
<tr>-->
	<th>
		생년월일 :
	</th>
	<td>
		<input type="text" name="birth_y" size="4" maxlength="4" value="<?echo $birth_y?>"> 년
		<input type="text" name="birth_m" size="2" maxlength="2" value="<?echo $birth_m?>"> 월
		<input type="text" name="birth_d" size="2" maxlength="2" value="<?echo $birth_d?>"> 일
	</td>
</tr>
<tr>
	<th>
		성별 :
	</th>
	<td><? if (trim($sex) == "1" ) { echo "남"; } else {echo "여";} ?></td>
</tr>
<? if ($member_kind == "D") {?>
<tr>
	<th>
		활동 유형 :
	</th>
	<td>
        <input type="radio" name="active_kind" value="0" <?if ($active_kind == "0") echo "checked"; ?>>소매이익사업자(부가가치 신고대상자) 
        <input type="radio" name="active_kind" value="1" <?if ($active_kind == "1") echo "checked"; ?>>후원사업자 (자가소비)
	</td>
</tr>
<?	}?>
<tr>
	<th>
		배우자 등록 :
	</th>
	<td>
    	<input type="radio" name="couple" value="Y" <?if ($couple == "Y") echo "checked"; ?>>등록
        <input type="radio" name="couple" value="N" <?if ($couple == "N") echo "checked"; ?>>등록 안함
	</td>
</tr>
<tr>
	<th>
		배우자 성명 :
	</th>
	<td>		
		<input type="text" name="couple_name" size="15" value="<?echo $couple_name?>">
	</td>
</tr>
<tr>
	<th>
		배우자 영문성명 :
	</th>
	<td>
		<input type="text" name="couple_ename" size="20" value="<?echo $couple_ename?>">
	</td>
</tr>
<tr>
	<th>
		배우자 생년월일 :
	</th>
	<td>
        <input name="couple_reg_jumin1" type="text" size="8" maxlength="6" onkeyup='lengthCheck3(this.form,this.value);' value="<?echo $couple_reg_jumin1?>">
                  <!-- - 
        <input name="couple_reg_jumin2" type="text" size="10" maxlength="7" value="<?echo $couple_reg_jumin2?>"> --></td>
	</td>
</tr>
<tr>
	<th>
		우편번호 :
	</th>
	<td>
		<input type="text" name="zip1" id="zip1" size="6" value="<?echo $zip?>" readonly=1>
		<input type="hidden" name="zip2" value="">
		<!--
		<input type="text" name="zip2" size="3" value="" readonly=1>
		-->
		<!-- <input type="button" name="btn1" value="주소찾기" onClick="NewWindow('/korea/popup/post/doro_zipcode_search4json2.php?add=addr','popup_post', '600','495','NO');"> -->
		<input type="button" name="btn1" value="주소찾기" onclick="execDaumPostcode('zip1', 'addr', 'addr02')" >

		<input type="hidden" id="state" name="state" value="">
		<input type="hidden" id="city" name="city" value="">
		<input type="hidden" id="dong" name="dong" value="">
		<input type="hidden" id="building" name="building" value="">

	</td>
</tr>
<tr>
	<th>
		주 소 :
	</th>
	<td>
		<input type="text" name="addr" id="addr" size="60" value="<?echo $addr?>"> <input type="text" name="addr02" id="addr02" size="60" value="<?echo $addr_detail?>">
	</td>
</tr>
<tr>
	<th>
		제품수령우편번호 :
	</th>
	<td>
		<input type="text" name="del_zip1" id="del_zip1" size="6" value="<?echo $del_zip?>" readonly=1>
		<input type="hidden" name="del_zip2" value="">
		<!-- <input type="button" name="btn1" value="주소찾기" onClick="NewWindow('/korea/popup/post/doro_zipcode_search4json2.php?add=del','popup_post', '600','495','NO');"> -->
		<input type="button" name="btn1" value="주소찾기" onclick="execDaumPostcode('del_zip1', 'del_addr', 'del_addr02')">
	</td>
</tr>
<tr>
	<th>
		제품수령주소 :
	</th>
	<td>
		<input type="text" name="del_addr" id="del_addr" size="60" value="<?echo $del_addr?>"> <input type="text" name="del_addr02" id="del_addr02" size="60" value="<?echo $del_addr_detail?>">
	</td>
</tr>
<tr>
	<th>
		휴대전화번호 :
	</th>
	<td>
		<select name="hpho1" size=1 style="width:60">
			<option value="010">010</option>
			<option value="011">011</option>
			<option value="016">016</option>
			<option value="017">017</option>
			<option value="018">018</option>
			<option value="019">019</option>
		</select> -		
		<input type="text" name="hpho2" size="4" desc="휴대전화번호" maxlength="4" value="<?echo $hpho2?>"> -
		<input type="text" name="hpho3" size="4" desc="휴대전화번호" maxlength="4" value="<?echo $hpho3?>">
	</td>
</tr>
<tr>
	<th>
		문자수신여부 :
	</th>
	<td>
		<input type="radio" name="hphone_flag" value="Y" <?if ($hphone_flag == "Y") echo "checked";?> > 수신 
		<input type="radio" name="hphone_flag" value="N" <?if ($hphone_flag == "N") echo "checked";?>> 수신거부
	</td>
</tr>
<tr>
	<th>
		자택전화번호 :
	</th>
	<td>
		<select name="pho1" size=1 style="width:60">
			<option value="02"> 02</option>
			<option value="031">031</option>
			<option value="032">032</option>
			<option value="033">033</option>
			<option value="041">041</option>
			<option value="042">042</option>
			<option value="043">043</option>
			<option value="051">051</option>
			<option value="052">052</option>
			<option value="053">053</option>
			<option value="054">054</option>
			<option value="055">055</option>
			<option value="061">061</option>
			<option value="062">062</option>
			<option value="063">063</option>
			<option value="064">064</option>
			<option value="010">010</option>
			<option value="011">011</option>
			<option value="016">016</option>
			<option value="017">017</option>
			<option value="018">018</option>
			<option value="019">019</option>
		</select> -
		<input type="text" name="pho2" size="4" desc="전화번호" maxlength="4" value="<?echo $pho2?>"> -
		<input type="text" name="pho3" size="4" desc="전화번호" maxlength="4" value="<?echo $pho3?>">
	</td>
</tr>
<? if ($member_kind == "D") {?>
<tr>
	<th>
		거래 은행 :
	</th>
	<td>
		<select name="account_bank">
			<? makeCodeAsName ("bank3"); ?>
		</select>
	</td>
</tr>
<tr>
	<th>
		계좌번호 :
	</th>
	<td>
		<input type="text" name="account" size="30" value="<?echo $account?>">

		<? if($account != "" && $number != ""){ ?>
			<input type="button" value="계좌재전송" onClick="updateBankInfo();"> 
			<span id="bankMsg"></span>
		<? } ?>
	</td>
</tr>
<?	}?>
<tr>
	<th>
		E-Mail :
	</th>
	<td>
		<input type="text" name="email1" size="10" value="<?echo $email_ep[0]?>">@
		<input type="text" name="email2" size="20" value="<?echo $email_ep[1]?>">
	</td>
</tr>
<!--
<tr>
	<th>
		메일수신여부 :
	</th>
	<td>
		<input type="radio" name="email_flag" value="Y" <?if ($agree_01 == "Y") echo "checked";?> > 수신 
		<input type="radio" name="email_flag" value="N" <?if ($agree_01 == "N") echo "checked";?>> 수신거부
	</td>
</tr>
-->
<tr>
	<th>
		후원자 FO :
	</th>
	<td>
		<input type="text" name="co_number" size="15" value="<?echo $co_number?>">
	</td>
</tr>
<tr>
	<th>
		후원자 :
	</th>
	<td>
		<input type="text" name="co_name" size="15" value="<?echo $co_name?>">
	</td>
</tr>
<!--
<tr>
	<th>
		회원종류 :
	</th>
	<td>
		<input type="radio" name="member_kind" value="D" <?if ($member_kind == "D") echo "checked";?>> FO 회원 
		<input type="radio" name="member_kind" value="C" <?if ($member_kind == "C") echo "checked";?>> 소비자 회원
	</td>
</tr>
-->
<!--
<tr>
	<th>
		직 업 :
	</th>
	<td>
		<select name="job">
			<? makeCodeAsName ("job"); ?>
		</select>
	</td>
</tr>
-->
<tr>
	<th>
		관심 분야 :
	</th>
	<td>
		<input type="checkbox" name="chk_interest" value="Y" <? if ($interest_1 == "Y") echo "checked"?> > 건강보조 식품
		<input type="checkbox" name="chk_interest" value="Y" <? if ($interest_2 == "Y") echo "checked"?> > 웨이트매니지먼트 제품
		<input type="checkbox" name="chk_interest" value="Y" <? if ($interest_3 == "Y") echo "checked"?> > 퍼스날케어 제품
		<input type="checkbox" name="chk_interest" value="Y" <? if ($interest_4 == "Y") echo "checked"?> > Be_Brand 제품
		<input type="checkbox" name="chk_interest" value="Y" <? if ($interest_5 == "Y") echo "checked"?> > 클리어소스 제품
		<input type="checkbox" name="chk_interest" value="Y" <? if ($interest_6 == "Y") echo "checked"?> > 홈 베이직 제품
	</td>
</tr>

<tr>
	<th>
		인증 방법 :
	</th>
	<td>
		<?echo $str_chk_type?>
	</td>
</tr>

<tr>
	<th>
		이메일 통보 :
	</th>
	<td>
		<input type="radio" name="agree_01" value="Y" <?if ($agree_01 == "Y") echo "checked";?> > 동의 
		<input type="radio" name="agree_01" value="N" <?if ($agree_01 == "N") echo "checked";?>> 동의안함
	</td>
</tr>


<tr>
	<th>
		SMS 통보 :
	</th>
	<td>
		<input type="radio" name="agree_02" value="Y" <?if ($agree_02 == "Y") echo "checked";?> > 동의 
		<input type="radio" name="agree_02" value="N" <?if ($agree_02 == "N") echo "checked";?>> 동의안함
	</td>
</tr>

<tr>
	<th>
		주요안내사항 통보 :
	</th>
	<td>
		<input type="radio" name="agree_03" value="Y" <?if ($agree_03 == "Y") echo "checked";?> > 동의 
		<input type="radio" name="agree_03" value="N" <?if ($agree_03 == "N") echo "checked";?>> 동의안함
	</td>
</tr>

<tr>
	<th>
		후원수당 변경동의 :
	</th>
	<td>
		<input type="radio" name="agree_04" value="Y" <?if ($agree_04 == "Y") echo "checked";?> > 동의 
		<input type="radio" name="agree_04" value="N" <?if ($agree_04 == "N") echo "checked";?>> 동의안함
	</td>
</tr>

<tr>
	<th>
		<!-- 효성FMS㈜ 정보동의 : -->
		개인정보(하나투어,레드캡,SMTT)제공동의 : 
	</th>
	<td>
		<input type="radio" name="sel_agree01" value="Y" <?if ($sel_agree01 == "Y") echo "checked";?> > 동의 
		<input type="radio" name="sel_agree01" value="N" <?if ($sel_agree01 == "N") echo "checked";?>> 동의안함
	</td>
</tr>

<tr>
	<th>
		<!-- (주)GMCOM 정보동의 : -->
		마케팅 목적의 이메일 수신 동의 : 
	</th>
	<td>
		<input type="radio" name="sel_agree02" value="Y" <?if ($sel_agree02 == "Y") echo "checked";?> > 동의 
		<input type="radio" name="sel_agree02" value="N" <?if ($sel_agree02 == "N") echo "checked";?>> 동의안함
	</td>
</tr>

<tr>
	<th>
		<!-- ㈜하나투어 / ㈜SM C&C BT&I 정보동의 : -->
		마케팅 목적의 SMS 수신 동의 : 
	</th>
	<td>
		<input type="radio" name="sel_agree03" value="Y" <?if ($sel_agree03 == "Y") echo "checked";?> > 동의 
		<input type="radio" name="sel_agree03" value="N" <?if ($sel_agree03 == "N") echo "checked";?>> 동의안함
	</td>
</tr>

<tr>
	<th>
		<!-- 깊은인상 정보동의 : -->
		마케팅 목적의 우편물 수신 동의 : 
	</th>
	<td>
		<input type="radio" name="sel_agree04" value="Y" <?if ($sel_agree04 == "Y") echo "checked";?> > 동의 
		<input type="radio" name="sel_agree04" value="N" <?if ($sel_agree04 == "N") echo "checked";?>> 동의안함
	</td>
</tr>

<tr>
	<th>
		본인외 주문 정보동의 :
	</th>
	<td>
		<input type="radio" name="sel_agree05" value="Y" <?if ($sel_agree05 == "Y") echo "checked";?> > 동의 
		<input type="radio" name="sel_agree05" value="N" <?if ($sel_agree05 == "N") echo "checked";?>> 동의안함
	</td>
</tr>

<tr>
	<th>
		가입, 방문 정보:
	</th>
	<td>
		<b>가입일</b> : <?echo $date_s1?>&nbsp;&nbsp;&nbsp;<b>마지막 방문일</b> : <?echo $date_s2?>&nbsp;&nbsp;&nbsp;<b>방문수</b> : <?echo $visit_count?> 회
	</td>
</tr>



</TABLE>
	</td>
</tr>
</table>
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>온라인 등록 회원 관리 (수정)</B></TD>
	<TD align="right" width="300" align="center" bgcolor=silver>
		<input type="button" onClick="goIn();" value="수정" name="btn3">
		<input type="button" onClick="goBack();" value="목록" name="btn4">
<?	#if ($s_flag == "1") {?>
		<input type="button" onClick="goDel();" value="삭제" name="btn5">
<?	#}?>
		<INPUT type="hidden" name="page" value="<?echo $page?>">
	</TD>
</TR>
</TABLE>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
<TABLE border="0" width='100%' cellspacing="1" cellpadding="1">
<tr>
	<th bgcolor="#DDDDDD" width="137">
		신청 처리 정보:
	</th>
	<td bgcolor="#EEEEEE">
		<table border=0>
<?	if ($join_kind == "N") {?>
			<tr>
				<td width=200><b>본인확인일</b> : <?echo $date_scp?></td>
				<td width=150><b>확인자</b> : <?echo $confirm_person_ma?></td>
				<td>&nbsp;</td>
			</tr>
<?	}?>
			<tr>
				<td width=200><b>출력일</b> : <?echo $date_sp?></td>
				<td width=150><b>출력자</b> : <?echo $print_ma?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><b>완료일</b> : <?echo $date_sc?></td>
				<td><b>완료자</b> : <?echo $confirm_ma?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><b>보류일</b> : <?echo $date_sw?></td>
				<td><b>보류자</b> : <?echo $wait_ma?></td>
				<td><input type="button" value="보류 내용 입력" onClick="goMemo('w');"></td>
			</tr>
			<tr>
				<td><b>거부일</b> : <?echo $date_sr?></td>
				<td><b>거부자</b> : <?echo $reject_ma?></td>
				<td><input type="button" value="거부 내용 입력" onClick="goMemo('r');"></td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<th bgcolor="#DDDDDD">
		문자, 이메일 정보:
	</th>
	<td bgcolor="#EEEEEE">
		<table border="0">
			<tr>
				<td width=200><b>SMS 송신일</b> : <?echo $date_ss?></td>
				<td width=150><b>송신자</b> : <?echo $sms_ma?></td>
				<td><input type="button" value="다시 보내기" onClick="goSMS();"></td>
			</tr>
			<tr>
				<td><b>메일 송신일</b> : <?echo $date_se?></td>
				<td><b>송신자</b> : <?echo $email_ma?></td>
				<td><input type="button" value="신청(서) 보내기" onClick="goCEmail();"><input type="button" value="완료메일 보내기" onClick="goEmail();"></td>
				</td>
			</tr>
		</table>
	</td>
</tr>
<?	#if ($s_flag == "1") {?>
<tr>
	<th bgcolor="#DDDDDD">
		회원 처리 사항:
	</th>
	<td bgcolor="#EEEEEE">
			<select name="reg_status">
				<option value = "1" <?if ($reg_status == "1") echo "selected";?>>신청 (본인확인)</option>
				<option value = "2" <?if ($reg_status == "2") echo "selected";?>>신청</option>
				<option value = "3" <?if ($reg_status == "3") echo "selected";?>>출력</option>
				<option value = "4" <?if ($reg_status == "4") echo "selected";?>>완료</option>
				<option value = "8" <?if ($reg_status == "8") echo "selected";?>>보류</option>
				<option value = "9" <?if ($reg_status == "9") echo "selected";?>>거부</option>
			</select>&nbsp; <!--최고 관리자로 접속하셨을 경우만 변경 가능 합니다.-->
	</td>
</tr>
<?	#} else { ?>
<!--<input type="hidden" name="reg_status" value="<?echo $reg_status?>">-->
<?	#} ?>
<tr>
	<th bgcolor="#DDDDDD">
		보류 또는 거부사유:
	</th>
	<td bgcolor="#EEEEEE">
		<?echo nl2br($memo)?>
	</td>
</tr>
</TABLE>
	</td>
</tr>
</table>
<table border="0">
<tr>
	<td width="20">
		&nbsp;
	</td>
	<td>
</td>
</tr>
</table>
<input type="hidden" name="mode" value="mod">
<input type="hidden" name="zip" value="">
<input type="hidden" name="del_zip" value="">
<input type="hidden" name="email" value="">
<input type="hidden" name="interest" value="">
<INPUT type="hidden" name="sex" value="<?echo $sex?>">
<INPUT type="hidden" name="member_no" value="<?echo $member_no?>">
<INPUT type="hidden" name="member_kind" value="<?echo $member_kind?>">
<INPUT type="hidden" name="idxfield" value="<?echo $idxfield?>">
<INPUT type="hidden" name="idx" value="<?echo $idx?>">
<INPUT type="hidden" name="qry_str" value="<?echo $qry_str?>">
<input type="hidden" name="con_sort" value="<?echo $con_sort?>">
<input type="hidden" name="con_order" value="<?echo $con_order?>">
<INPUT type="hidden" name="r_status" value="<?echo $r_status?>">
<INPUT type="hidden" name="r_memberkind" value="<?echo $r_memberkind?>">
<INPUT type="hidden" name="r_join_kind" value="<?echo $r_join_kind?>">
<INPUT type="hidden" name="r_active_kind" value="<?echo $r_active_kind?>">
<INPUT type="hidden" name="r_couple" value="<?echo $r_couple?>">
<INPUT type="hidden" name="from_date" value="<?echo $from_date?>">
<INPUT type="hidden" name="to_date" value="<?echo $to_date?>">
</FORM>
</BODY>
</HTML>
<?
mysql_close($connect);
?>