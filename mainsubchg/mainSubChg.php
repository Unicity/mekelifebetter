<?php 	include "includes/config/common_functions.php";
		
?>
<?php 
	include "./includes/config/nc_config.php";
	include "../dbconn_utf8.inc";
	cert_validation();
	
	session_start(); 
	
	$distID = $_POST['sid'];
	$distName = $_POST['fname'];
	$Phone = $_POST['phone'];
	
echo "distID::".$distID."<br/>";
echo "distName::".$distName."<br/>";
echo "phone::".$Phone."<br/>";

	$birthDay = $_POST['birthDay'];
	$YnChk = $_POST['ChkYN'];
	$name = isset($_SESSION["S_NM"])? $_SESSION["S_NM"] : "";
	$sMobileNo = isset($_SESSION["S_MOBILE_NO"]) ? $_SESSION["S_MOBILE_NO"] : "";
	$addr = $_POST['address'];
	
	$jumin1 = $_SESSION["S_BIRTH"];
	$jumin2 = $_SESSION["S_DI"];
	$realName= $_SESSION["S_NM"];
	$realMoNum = $_SESSION["S_MOBILE_NO"];
	
	$sTime = $_POST["sTime"];
	

	// 안심본인인증 서비스
	$sitecode = $Cb_SiteID;				// Site Code for Unicity Korea
	$sitepasswd = $Cb_SitePW;			// Site Password for Unicity Korea
	
	 
	$cb_encode_path = $Cb_encode_path;		// module path (absolute path+module name)
	$authtype = "";
	
	$popgubun 	= "N";		//Y : 취소버튼 있음 / N : 취소버튼 없음
	$customize 	= "";			//없으면 기본 웹페이지 / Mobile : 모바일페이지
	
	$reqseq = "REQ_0123456789";     // 요청 번호, 이는 성공/실패후에 같은 값으로 되돌려주게 되므로
	
	// 업체에서 적절하게 변경하여 쓰거나, 아래와 같이 생성한다.
	$reqseq  = `$Cb_encode_path SEQ $Cb_SiteID`;
	
	
	// CheckPlus(본인인증) 처리 후, 결과 데이타를 리턴 받기위해 다음예제와 같이 http부터 입력합니다.
	
	$returnurl = (isset($_SERVER['HTTPS']) ? "https" : "https")."://".$_SERVER['HTTP_HOST']."/mainsubchg/signup/cert_success.php";	// 성공시 이동될 URL - Success
	$errorurl = (isset($_SERVER['HTTPS']) ? "https" : "https")."://".$_SERVER['HTTP_HOST']. "/mainsubchg/signup/cert_fail.php";		// 실패시 이동될 URL - Fail
	
	// reqseq값은 성공페이지로 갈 경우 검증을 위하여 세션에 담아둔다.
	
	$_SESSION["REQ_SEQ"] = $reqseq;
	
	$defaultdata = "7:REQ_SEQ" . strlen($reqseq) . ":" . $reqseq .
	"8:SITECODE" . strlen($sitecode) . ":" . $sitecode .
	"7:RTN_URL" . strlen($returnurl) . ":" . $returnurl .
	"7:ERR_URL" . strlen($errorurl) . ":" . $errorurl .
	"11:POPUP_GUBUN" . strlen($popgubun) . ":" . $popgubun .
	"9:CUSTOMIZE" . strlen($customize) . ":" . $customize ;
	// plain data for enc_data.
	$plaindata = $defaultdata."9:AUTH_TYPE" . strlen($authtype) . ":". $authtype ;
	
	$authtype = "X";
	$plaindataX = $defaultdata."9:AUTH_TYPE" . strlen($authtype) . ":". $authtype ;
	
	$authtype = "M";
	$plaindataM = $defaultdata."9:AUTH_TYPE" . strlen($authtype) . ":". $authtype ;
	
	$authtype = "C";
	$plaindataC = $defaultdata."9:AUTH_TYPE" . strlen($authtype) . ":". $authtype ;
	
	$enc_data = `$cb_encode_path ENC $sitecode $sitepasswd $plaindata`;
	 
	$enc_dataM = `$cb_encode_path ENC $sitecode $sitepasswd $plaindataM`;
	$enc_dataX = `$cb_encode_path ENC $sitecode $sitepasswd $plaindataX`;
	$enc_dataC = `$cb_encode_path ENC $sitecode $sitepasswd $plaindataC`;
	
	
	if( $enc_data == -1 )
	{
		$returnMsg = "encryption error.";
		$enc_data = "";
	}
	else if( $enc_data== -2 )
	{
		$returnMsg = "encryption process error.";
		$enc_data = "";
	}
	else if( $enc_data== -3 )
	{
		$returnMsg = "encryption data error.";
		$enc_data = "";
	}
	else if( $enc_data== -9 )
	{
		$returnMsg = "input value error.";
		$enc_data = "";
	}
	
	
	// IPIN server  part
	$sSiteCode					= $Ip_SiteID;			// IPIN service site code
	$sSitePw					= $Ip_SitePW;			// IPIN service site password
	$sModulePath				= $Ip_encode_path;			// Module Path
	$sReturnURL					= (isset($_SERVER['HTTPS']) ? "https" : "https")."://".$_SERVER['HTTP_HOST']."/korea/signup/pc/includes/ipin_process.php";			// Return utl
	$sCPRequest					= "";			// when result == success, we need to store sCPRequest value and verify from the success page
		
	$sCPRequest = `$sModulePath SEQ $sSiteCode`;
	
	$_SESSION['CPREQUEST'] = $sCPRequest;
	
	$sEncData					= "";			// encrypted data
	$sRtnMsg					= "";			// return result msg
	
	// Encryption
	$sEncData	= `$sModulePath REQ $sSiteCode $sSitePw $sCPRequest $sReturnURL`;
	
	// result
	if ($sEncData == -9)
	{
		$sRtnMsg = "Input value error : one of the parameters are wrong. fail to encrypt";
	} else {
		$sRtnMsg = "$sEncData has to be encrypted now. if now please contact admin.";
	}
	
?>


<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>공동등록 및 주ㆍ부사업자 변경 신청</title>
		<meta name="description" content="" />
		<meta http-equiv="Content-Script-Type" content="text/javascript">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
		<link rel="stylesheet" type="text/css" href="./css/mainsubchg.css?aa" />
		<script type="text/javascript" src="./js/jquery-1.8.0.min.js"></script>
		<script type="text/javascript" src='./js/common.js'></script>
		<script type="text/javascript" src="./js/selectordie.min.js"></script>
		<script type="text/javascript" src="./js/multifile/jQuery.MultiFile.min.js" ></script>
		<script type="text/javascript" src="./js/multifile/jquery.form.min.js"></script>
<!-- 2022.02.11 첨부파일 업로드 방식 변경으로 인한 주석 -->
<!--		
	<style>
.insert {
    padding: 20px 30px;
    display: block;
    width: 100%;
    margin: 5vh auto;
    height: 20%;
    border: 1px solid #dbdbdb;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
.insert .file-list {
    height: 60%;
    width: 100%;
    overflow: auto;
    border: 1px solid #989898;
    padding: 10px;
}

.insert .file-list.filebox p {
    font-size: 12px;
    margin-top: 9px;
    display: inline-block;
}
.insert .file-list .filebox .delete i{
    color: #ff5353;
    margin-left: 5px;
}

	</style>
-->	
		<script type="text/javascript">
			
			var firstValue ="";
		    var phoneChk ="" ;
		    var agreeChk ="";
			var mainSelect="";
			var subCheck="";
			var secondValue = "";
			var sNm="";
			var rBirth="";
			var dNm ="";
			var rBir="";
			var sTime ="<?php echo $sTime?>";
			function selectMenu(){		
				
				firstValue = document.memberInfo;
				 dNm = $("#distName").val();
				 rBir = $("#birthDay").val();
/*				
				if(rBir == "" || dNm == ""){
					alert("회원성명 또는 생년월일을 입력 하세요");
					$("#agreeCheck1").attr("checked",false);
					return false;		
				}	

				if(sNm != dNm){
					alert("본인 인증의 성명 과 회원명이 동일하지 않습니다.");
					$("#agreeCheck1").attr("checked",false);
					return false;
				}

				if(rBir != rBirth){
					alert("본인 인증의 성명 과 회원명이 동일하지 않습니다.");
					$("#agreeCheck1").attr("checked",false);
					return false;
				}		
				
				if(document.getElementById("phoneAgree").checked !=true){
					alert("휴대폰 인증을 먼저 진행 하여 주세요");
					$("#agreeCheck1").attr("checked",false);
					return false;
				}
			
				 agreeChk = "<?php echo $YnChk?>";
				 */
				if(agreeChk !='Y'){
					firstValue = document.memberInfo;
					firstValue.action = "./page/terms.php";
					firstValue.submit();
				}

				
			}
			function applyMenu(){
				agreeChk = "<?php echo $YnChk?>";
				if(agreeChk != 'Y'){
					alert("동의 체크를 먼저 진행 하셔야 합니다.");
					$("#mainCheck").attr("checked",false);
					$("#subCheck").attr("checked",false);
					return false;
				}else if (agreeChk == 'Y'){	
					var fname = "<?php echo $name?>";
					var mphone = "<?php echo $Phone?>";
					mainSelect = document.getElementById("mainCheck").checked;
					subCheck = document.getElementById("subCheck").checked;
					if(mainSelect== true){
						$("#together").css("display","block");
						$("#fName").val(fname);
						$("#fPhone").val(mphone);
						$("#nextButton2").css("display","block");
					}else{
						$("#together").css("display","none");
						$("#fName").val("");
						$("#fPhone").val("");
						$("#nextButton2").css("display","none");
					}		
	
					if(subCheck == true){
						$("#subMainChg").css("display","block");
						$("#agree").css("display","block");
						$("#nextButton1").css("display","block");
						$("#agreement1").css("display","block");
					}else{
						$("#subMainChg").css("display","none");
						$("#agree").css("display","none");
						$("#nextButton1").css("display","none");
						$("#agreementBut").css("display","none");
						$("#agreementChk").css("display","none");
					}
	
					if(subCheck == true && mainSelect== true){
						$("#nextButton1").css("display","block");
						$("#nextButton2").css("display","none");
					}
				}
						
			}	
			
			function nextButton(){
				
				var agreement = document.getElementById("chk5").checked;
				var notAgreement = document.getElementById("chk6").checked;
				mainSelect = document.getElementById("mainCheck").checked;
				
				firstValue = document.memberInfo;

		
				if(firstValue.birthDay.value == ""){
					alert("회원의 생년월일을 입력 하세요");
					return false
				}
				
				if(mainSelect == false){					
					if(firstValue.mName.value == ''){
						alert("새로운 주사업자 성명을 입력 하세요");
						return false
					}
					if(firstValue.mBirth.value == ''){
						alert("새로운 주사업자 생년월일 입력 하세요");
						return false
					}
					if(firstValue.sName.value == ''){
						alert("새로운 부사업자 성명을 입력 하세요");
						return false
					}
					if(firstValue.sBirth.value == ''){
						alert("새로운 부사업자 생년월일 입력 하세요");
						return false
					}
					if(firstValue.bankcode.value == ''){
						alert("새로운 주사업자 은행을 입력 하세요");
						return false
					}
					if(firstValue.accountNum.value == ''){
						alert("새로운 부사업자 계좌번호를 입력 하세요");
						return false
					}
					/*
					if(firstValue.myfile1.value == ""){
						alert("새로운 주사업자 첨부파일을 등록 하세요");
						return false
					}
					*/
					if(firstValue.mBirth.value.length != '8' || firstValue.sBirth.value.length != '8'){
					 	alert("생년월일을 바르게 입력 해 주세요.\n 예)19000101");
					 	return false;
					}
				}else{

					
					if(firstValue.fName.value == ""){
						alert("공동등록자 회원의 성명을 입력 하세요");
						return false
					}
					if(firstValue.fPhone.value == ""){
						alert("공동등록자 회원의 전화번호를 입력 하세요");
						return false
					}
					if(firstValue.relationShip.value == ""){
						alert("공동등록자 회원의 관계를 입력 하세요");
						return false
					}
					if(firstValue.fBirthDay.value == ""){
						alert("공동등록자 회원의 생년월일 입력 하세요");
						return false
					}
						
					if(firstValue.mName.value == ''){
						alert("새로운 주사업자 성명을 입력 하세요");
						return false
					}
					if(firstValue.mBirth.value == ''){
						alert("새로운 주사업자 생년월일 입력 하세요");
						return false
					}
					if(firstValue.sName.value == ''){
						alert("새로운 부사업자 성명을 입력 하세요");
						return false
					}
					if(firstValue.sBirth.value == ''){
						alert("새로운 부사업자 생년월일 입력 하세요");
						return false
					}
					if(firstValue.bankcode.value == ''){
						alert("새로운 주사업자 은행을 입력 하세요");
						return false
					}
					if(firstValue.accountNum.value == ''){
						alert("새로운 부사업자 계좌번호를 입력 하세요");
						return false
					}
					/*
					if(firstValue.myfile1.value == ""){
						alert("새로운 주사업자 첨부파일을 등록 하세요");
						return false
					}
					*/
					if(firstValue.mBirth.value.length != '8' || firstValue.sBirth.value.length != '8' || firstValue.fBirthDay.value.length != '8'){
					 	alert("생년월일을 바르게 입력 해 주세요.\n 예)19000101");
					 	return false;
					}

				}	
				if(agreement == false || notAgreement == true){
					alert("동의 하셔야 합니다.");
					return false;
				}	
				
				var mNameVal=firstValue.mName.value;
				var mBirthVal=firstValue.mBirth.value;
				var sNameVal=firstValue.sName.value;
				var sBirthVal=firstValue.sBirth.value;
				
				
				$("#applyBox").css("display","none");
				$("#agreementBut").css("display","block");
				$("#agreementChk").css("display","block");
				$("#mNameVal").val(mNameVal);
				$("#mBirthVal").val(mBirthVal);
				$("#sNameVal").val(sNameVal);
				$("#sBirthVal").val(sBirthVal);

				$('[name="mNameVal"]').val(mNameVal);
				$('[name="sNameVal"]').val(sNameVal);

				
				/*
				firstValue = document.memberInfo;
				var agreement = document.getElementById("chk5").checked;
				var notAgreement = document.getElementById("chk6").checked;

				mainSelect = document.getElementById("mainCheck").checked;
				if(agreement == false || notAgreement == true){
					alert("동의 하셔야 합니다.");
					return false;
				}	
				
					firstValue.applyDate.value = getTimeStamp();
					if(mainSelect == false){
						firstValue.flag.value = '2';
					}else{
						firstValue.flag.value = '3';
					}	
					firstValue.action = "./page/agreementPgae.php";
					firstValue.submit();
				*/
			}	

			function nextButton1(){

				firstValue = document.memberInfo;
				var agreement = document.getElementById("chk5").checked;
				var notAgreement = document.getElementById("chk6").checked;
				var applyD= getTimeStamp();

				var urlVal = $("#myfile").val();
				var urlVal1 = $("#myfile1").val();
				var urlVal2 = $("#myfile2").val();

				
/*
				if(firstValue.birthDay.value == ""){
					alert("회원의 생년월일을 입력 하세요");
					return false
				}
				if(firstValue.fName.value == ""){
					alert("공동등록자 회원의 성명을 입력 하세요");
					return false
				}
				if(firstValue.fPhone.value == ""){
					alert("공동등록자 회원의 전화번호를 입력 하세요");
					return false
				}
				if(firstValue.relationShip.value == ""){
					alert("공동등록자 회원의 관계를 입력 하세요");
					return false
				}
				if(firstValue.fBirthDay.value == ""){
					alert("공동등록자 회원의 생년월일 입력 하세요");
					return false
				}

				if(firstValue.gender.value == ""){
					alert("성별을 선택 하세요");
					return false
				}
			*/	
			/*		
				if(firstValue.myfile.value == ""){
					alert("공동등록자 회원의 첨부파일을 등록 하세요");
					return false
				}
			*/			
				if(agreement == false || notAgreement == true){
					alert("동의 하셔야 합니다.");
					return false;
				}

				if(firstValue.fBirthDay.value.length != '8'){
				 	alert("생년월일을 바르게 입력 해 주세요.\n 예)19000101");
				}	
					
				firstValue.applyDate.value = getTimeStamp();
				firstValue.sTime.value = sTime;
				firstValue.myfile11.value =urlVal1;
				firstValue.myfile12.value =urlVal2;
alert(urlVal);				
alert(firstValue.myfile11.value);
alert(firstValue.myfile12.value);
				firstValue.flag.value = '1';
				firstValue.action = "./page/infoSave.php";
				firstValue.submit();

				
			}	

			function agreementButton(){

				firstValue = document.memberInfo;
				mainSelect = document.getElementById("mainCheck").checked;

				var agreement = document.getElementById("chk7").checked;
				var notAgreement = document.getElementById("chk8").checked;
				
				
				if(agreement == false || notAgreement == true){
					alert("동의 하셔야 합니다.");
					return false;
				}
				if(mainSelect == true){
					firstValue.flag.value = '3';
				}else{
					firstValue.flag.value = '2';
				}	
				firstValue.applyDate.value = getTimeStamp();
				firstValue.sTime.value = sTime;
				firstValue.action = "./page/infoSave.php";
				firstValue.submit()	
			}	
			
			// 인증
			window.name ="Parent_window";
			function fnPopupCb(type){
				firstValue = document.memberInfo;
				if(firstValue.birthDay.value.length != '8' || firstValue.birthDay.value.length != '8' || firstValue.birthDay.value.length != '8'){
				 	alert("생년월일을 바르게 입력 해 주세요.\n 예)19000101");
				 	return false;
				}					
							
				window.open('', 'popupChk', 'width=408, height=655, top=100, left=100, fullscreen=no, resizable=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
				if (type == "M") {
					document.form_chk.EncodeData.value = document.form_chk.MEncodeData.value;
				} else if (type == "C") {
					document.form_chk.EncodeData.value = document.form_chk.CEncodeData.value;
				} else if (type == "X") {
					document.form_chk.EncodeData.value = document.form_chk.XEncodeData.value;
				}				
				document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
				document.form_chk.target = "popupChk";
				document.form_chk.submit();
			}	

			function go_next_sign(birthdate, s_nm, mChkDate){
				firstValue = document.memberInfo;
				sNm = s_nm;
				rBirth=birthdate;
				firstValue.sTime.value = mChkDate;
			
				$("#phoneAgree").attr("checked",true);
		 		var phoneAgreeMent = document.getElementById("phoneAgree").checked;
		 		if(phoneAgreeMent ==true){
		 			
		 			$("#agreement1").css("display","block");
		 			$("#nextButton1").css("display","block");	
			 	}
		 	}

			function maxLengthCheck(object){
				if(object.value.length>object.maxLength){
					object.value = object.value.slice(0, object.maxLength);
				}	
			}

			var date = new Date();
			function getTimeStamp() {
			  var s =
				leadingZeros(date.getFullYear(), 4) + '-' +
				leadingZeros(date.getMonth() + 1, 2) + '-' +
				leadingZeros(date.getDate(), 2) + ' ' +

				leadingZeros(date.getHours(), 2) + ':' +
				leadingZeros(date.getMinutes(), 2) + ':' +
				leadingZeros(date.getSeconds(), 2);

			  return s;
			}

			function leadingZeros(n, digits) {
			  var zero = '';
			  n = n.toString();

			  if (n.length < digits) {
				for (i = 0; i < digits - n.length; i++)
				  zero += '0';
			  }
			  return zero + n;
			}

			function add(){


				
				var idx = jQuery('tr td input').size();
			
				if(idx <= 2){ 			        
			        var addStaffText = '<tr name="trStaff">'+
			        	//'    <td>'+
			        	//'    <h2 style="margin-top: 9px;">URL 입력</h2>'	+
			        	//'    </td>'+
			            '    <td>'+
			            //'        <input type="text" class="form-control" name="url" id="url'+idx+'"+ placeholder="url" value="https://" style="width: 80%">'+
						'			<input type="file" name="myfile" id="myfile'+idx+'" size="60">' + 
			            //'        <button class="btn btn-default" name="delStaff" style="margin-top: 9px;" >삭제</button>'+
			            '    </td>'+
			            '</tr>';
			            
			        var trHtml = $( "tr[name=trStaff]:last" ); 
			
			        trHtml.after(addStaffText); 
		        
				    //삭제 버튼
				    $(document).on("click","button[name=delStaff]",function(){
				        var trHtml = $(this).parent().parent();
				        trHtml.remove(); //tr 테그 삭제
				    });
				}else{
					alert("URL추가 입력은 3개까지만 가능 합니다")
					return false;
				}
				
			}



			function init(){
				/*
				var dist_id='<?php echo $distID ?>';
				var dist_name='<?php echo $distName ?>';
					if(dist_id == "" && dist_name==""){
						alert('올바른 경로가 아닙니다. 마이비즈 사이트를 이용해주세요');
						window.location.href = 'https://ushop-kr.unicity.com/login';
		
					}
					*/		
			}	
	

/** 2022.02.11 첨부파일 업로드 방식 변경으로 인한 주석 */
/*
	var fileNo = 0;
	var filesArr = new Array();

	
	function addFile(obj){
    	var maxFileCnt = 5;   // 첨부파일 최대 개수
    	var attFileCnt = document.querySelectorAll('.filebox').length;    // 기존 추가된 첨부파일 개수
    	var remainFileCnt = maxFileCnt - attFileCnt;    // 추가로 첨부가능한 개수
    	var curFileCnt = obj.files.length;  // 현재 선택된 첨부파일 개수


		if (curFileCnt > remainFileCnt) {
			alert("첨부파일은 최대 " + maxFileCnt + "개 까지 첨부 가능합니다.");
		} else {
			for (const file of obj.files) {
	
				if (validation(file)) {
		
					var reader = new FileReader();
					reader.onload = function () {
						filesArr.push(file);
					};
					reader.readAsDataURL(file);

			
					let htmlData = '';
					htmlData += '<div id="file' + fileNo + '" class="filebox">';
					htmlData += '   <p class="name" style="font-size: 12px;margin-top: 9px;display: inline-block;">' + file.name + '</p>';
					htmlData += '   <a class="delete"style="color: #ff5353;margin-left: 5px;" onclick="deleteFile(' + fileNo + ');" ><i class="far fa-minus-square"></i></a>';
					htmlData += '</div>';
					console.log(htmlData);
					$('.file-list').append(htmlData);
					fileNo++;
				} else {
					continue;
				}
			}
		}
		
	}


	function validation(obj){
		alert(obj);
		const fileTypes = ['application/pdf', 'image/gif', 'image/jpeg', 'image/png', 'image/bmp', 'image/tif', 'application/haansofthwp', 'application/x-hwp'];
		if (obj.name.length > 100) {
			alert("파일명이 100자 이상인 파일은 제외되었습니다.");
			return false;
		} else if (obj.size > (100 * 1024 * 1024)) {
			alert("최대 파일 용량인 100MB를 초과한 파일은 제외되었습니다.");
			return false;
		} else if (obj.name.lastIndexOf('.') == -1) {
			alert("확장자가 없는 파일은 제외되었습니다.");
			return false;
		} else if (!fileTypes.includes(obj.type)) {
			alert("첨부가 불가능한 파일은 제외되었습니다.");
			return false;
		} else {
			return true;
		}
	}


	function deleteFile(num) {
    	document.querySelector("#file" + num).remove();
    	filesArr[num].is_delete = true;
	}


function submitForm() {
   
    var form = document.querySelector("form");
    var formData = new FormData(form);
    for (var i = 0; i < filesArr.length; i++) {
        
        if (!filesArr[i].is_delete) {
            formData.append("attach_file", filesArr[i]);
        }
    }

    $.ajax({
        method: 'POST',
        url: '/register',
        dataType: 'json',
        data: formData,
        async: true,
        timeout: 30000,
        cache: false,
        headers: {'cache-control': 'no-cache', 'pragma': 'no-cache'},
        success: function () {
            alert("파일업로드 성공");
        },
        error: function (xhr, desc, err) {
            alert('에러가 발생 하였습니다.');
            return;
        }
    })
}
*/


		</script>
	</head>
	<body onload="init();">
		<div class="wrapper" >
			<!-- container start {-->
			<div class="main_wrapper">
				<div class="figure">
					<img src="./images/mainlogo.png" alt="유니시티 로고" />
				</div>
				<div class="main_box">
					<div class="main_inner_box">
						<div class="main_top">
							<h1>
								<span>공동 등록 및 주ㆍ부사업자 변경 신청</span>
							</h1>
						</div>
						<form name="form_chk" method="post" >
							<input type="hidden" name="m" value="checkplusSerivce">						<!-- Mandatory data. -->
							<input type="hidden" name="EncodeData" value="<?=$enc_data?>">		<!-- encrypted data for unicity korea -->
						    <input type="hidden" name="MEncodeData" value="<?=$enc_dataM?>">
							<input type="hidden" name="CEncodeData" value="<?=$enc_dataC?>">
							<input type="hidden" name="XEncodeData" value="<?=$enc_dataX?>">
						    <!-- extra fields that we can request from the vendor. leave them blank for now -->
							<input type="hidden" name="param_r1" value="">
							<input type="hidden" name="param_r2" value="">
							<input type="hidden" name="param_r3" value="">
							<input type="hidden" name="applyDate" value="">
							
						</form>
						<form name="memberInfo" method="post" onsubmit="return false;" enctype="multipart/form-data">
					<!--
							<input type="hidden" name="myfile11" value="">
							<input type="hidden" name="myfile12" value="">
					-->
							<input type="hidden" name="addr" value="<?=$addr?>">
							<input type="hidden" name="flag" value="">
							<input type="hidden" name="applyDate" value="">
							<input type="hidden" name="reg_status" value="2"/>
							<input type="hidden" name="sTime" value="">
						
							<div class="wrap_input">
								<div id="applyBox">
									<div class="member">
										<h2 style="float: left; margin-top: 9px;">회원번호&nbsp;</h2>
										<div class="wrap">
											<input type="text" placeholder="회원번호" name="distID" id="distID" value="209415382" readonly="readonly" />
										</div>
										<h2 style="float: left; margin-top: 9px;">회원성명&nbsp;</h2>
										<div class="wrap">
											<input type="text" placeholder="회원성명" name="distName" id="distName" readonly="readonly" value="김민구" />
										</div>
										<!-- 
										<h2 style="float: left; margin-top: 9px;">전화번호&nbsp;</h2>
										<div class="wrap">
											<input type="text" placeholder="전화번호" name="phone" id="phone" value="<?php echo $Phone?>" />
										</div>
										 -->
										<h2 style="float: left; margin-top: 9px;">생년월일&nbsp;</h2>
								
										<div class="wrap">
											<input type="number" style="height: 40px;" placeholder="생년월일(8자리로 입력)" name="birthDay" id="birthDay" maxlength="8" oninput="maxLengthCheck(this)" value="<?php echo $birthDay?>" />
										</div>	
									</div>
								
									<div align="center" style="background-color: #B2CCFF;width: 100px;margin-left: 40%; border-radius : 5px; text-shadow: 0px -1px 1px rgba(0,0,0,.3); border: 1px solid #4081AF;box-shadow : inset 0 1px 0 rgba(255,255,255,.3), inset 0 0 2px rgba(255,255,255,.3) 0 1px 2px rgba(0,0,0,.29); cursor: pointer; ">
										<a href="javascript:fnPopupCb('M')"><b>휴대폰 인증</b></a>
									</div>
			
							
									<?php if($YnChk=='Y'){?>
										<div><input type="checkbox" size="30px" id="phoneAgree1" name="phoneAgree" value="Yes" checked="checked" disabled="disabled"/> 인증확인</div>
									<?php }else{?>
										<div><input type="checkbox" size="30px" id="phoneAgree" name="phoneAgree" value="Yes" disabled="disabled" checked="checked"/> 인증확인</div>
									<?php }?>
									<div id="check0">
										<?php if($YnChk=='Y'){?>
										<input type="checkbox" size="30px" id="agreeCheck" name="agreeCheck" checked="checked" disabled="disabled" onclick="selectMenu()" style=""/> 동의 체크
										<?php }else{?>
										<input type="checkbox" size="30px" id="agreeCheck1" name="agreeCheck" onclick="selectMenu()"/> 동의 체크
										<?php }?>
									</div>
									<div id="check1">
										<input type="checkbox" size="30px" id="mainCheck" name="selectChk" value="Yes" onclick="applyMenu()" /> 공동등록 신청
									</div>
									<div id="check2">
										<input type="checkbox" size="30px" id="subCheck" name="selectChk" value="Yes" onclick="applyMenu()" /> 주ㆍ부 사업자 변경 신청
									</div>
									<div id="together" class="member" style="display: none;">
										<h2>◆ 공 동 등 록 자 (새로운 공동등록자 정보)</h2>
										<div class="wrap">
											<h2 style="float: left; margin-top: 9px;">성&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;명 &nbsp;</h2>
											<div class="wrap">
												<input type="text" placeholder="성명" name="fName" id="fName" value="김스"/>
											</div>
											<h2 style="float: left; margin-top: 9px;">전화번호&nbsp;</h2>
											<div class="wrap">
												<input type="text" placeholder="전화번호" name="fPhone" id="fPhone" value="01022223333" />
											</div>
											<h2 style="float: left; margin-top: 9px;">관&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;계 &nbsp;</h2>
											<div class="wrap">
												<!--<input type="text" placeholder="관계" name="relationShip" id="relationShip" value="" />-->
												<select name="relationShip" id="relationShip" title="관계" style="width:70px; height: 40px;">
													<option value="">선택</option>
													<option value="p" selected>배우자</option>
													<option value="c">자녀</option>
												</select>
											</div>
											<h2 style="float: left; margin-top: 9px;">생년월일&nbsp;</h2>
											<div class="wrap">
												<input type="number" style="height: 40px;" placeholder="생년월일(8자리로 입력)" name="fBirthDay" id="fBbirthDay" value="19000101" maxlength="8" oninput="maxLengthCheck(this)" />
											</div>
											
											<h2 style="float: left; margin-top: 9px;">성&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;별 &nbsp;</h2>
											<div class="wrap">
												<select name="gender" id="gender" title="성별" style="width:50px; height: 40px;">
													<option value="">선택</option>
													<option value="m" selected>남</option>
													<option value="w">여</option>
												</select>
											</div>

											<div class="subtitle">
												
												<table border="0" style="width: 100%">
													<tr>
														<td width="80%">
															<h2 style="margin-top: 9px;"><b style="background-color: #7CF1F6;">첨부 서류 업로드</b><br/>
														필요서류 : 현 주업자와 공동 등록자(부사업자)가 가족(부부)임을 증명 할 수 있는 증명서류 1부(주민등록번호 뒷7자리 삭제)</h2>
														</td>
													</tr>	
													<tr name="trStaff">
														<td id="box">
															<input type="file" name="myfile" id="myfile" size="60">		
														</td>
													</tr> 
													<tr name="trStaff">
														<td id="box">
															<input type="file" name="myfile11" id="myfile11" size="60">		
														</td>
													</tr> 
													<tr name="trStaff">
														<td id="box">
															<input type="file" name="myfile12" id="myfile12" size="60">		
														</td>
													</tr> 
													
												</table>
											</div>
									
											<button name="addStaff" onclick="add()">첨부파일 추가</button>

											<!--
											<div class="insert">
												<input type="file" name="upload[]" id="myfile" onchange="addFile(this);" multiple />
													<input type="file" class="multi" name="myfile" id="myfile" multiple/>
												<div class="file-list"></div>
											</div>
										-->
										</div>
									</div>
									<div id="subMainChg" class="member" style="display: none;">
										<div style="font-size: 10px;font-weight: bold;background: yellow">※ 매월 1일부터 20일사이에는 주ㆍ부사업자 변경 신청 건은 21일부터 말일 사이에 처리 됩니다.<br/>
											※ 신청한 달 20일에 지급되는 전원 분 후원수당은 전월 기준 주사업자 명의 은행계좌로 송금되며,신청한 달의 후원수당 분은 익월 20일에 새로운 주사업자 명의 은행계좌로 송금 됩니다.
										</div>
										<div style="height: 3px;"></div>
										<h2>◆ 새로운 주사업자</h2>
										<h2 style="float: left; margin-top: 9px;">성 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;명 &nbsp;</h2>
										<div class="wrap">
											<input type="text" placeholder="성명" name="mName" id="mName" value="김"/>
										</div>
										<h2 style="float: left; margin-top: 9px;">생년월일 &nbsp;</h2>
										<div class="wrap">
												<input type="number" style="height: 40px;" placeholder="생년월일(8자리로 입력)" name="mBirth" id="mBirth" maxlength="8" oninput="maxLengthCheck(this)" value="19000101"/>
										</div>
										<h2>◆ 계좌정보</h2>
										<h2 style="float: left; margin-top: 12px;">은행선택 &nbsp;</h2>
										<div class="wrap">
											<select id="bankcode" name="bankcode" title="거래은행 선택" style="width:185px; height: 50px; overflow: auto;">	
												<option value="">거래은행 선택</option>
												<option value='060'>BOA은행</option>
												<option value='263'>HMC투자증권</option>
												<option value='054'>HSBC은행</option>
												<option value='292'>LIG투자증권</option>
												<option value='289'>NH투자증권</option>
												<option value='023'>SC제일은행</option>
												<option value='266'>SK증권</option>
												<option value='039'>경남은행</option>
												<option value='034'>광주은행</option>
												<option value='261'>교보증권</option>
												<option value='004'>국민은행</option>
												<option value='003'>기업은행</option>
												<option value='011'>농협중앙회</option>
												<option value='012'>농협회원조합</option>
												<option value='031'>대구은행</option>
												<option value='267'>대신증권</option>
												<option value='238'>대우증권</option>
												<option value='055'>도이치은행</option>
												<option value='279'>동부증권</option>
												<option value='209'>유안타증권</option>
												<option value='287'>메리츠종합금융증권</option>
												<option value='052'>모건스탠리은행</option>
												<option value='230'>미래에셋증권</option>
												<option value='059'>미쓰비시도쿄UFJ은행</option>
												<option value='058'>미즈호코퍼레이트은행</option>
												<option value='290'>부국증권</option>
												<option value='032'>부산은행</option>
												<option value='002'>산업은행</option>
												<option value='240'>삼성증권</option>
												<option value='050'>상호저축은행</option>
												<option value='045'>새마을금고연합회</option>	
												<option value='268'>솔로몬투자증권</option>
												<option value='008'>수출입은행</option>
												<option value='007'>수협중앙회</option>
												<option value='291'>신영증권</option>		
												<option value='278'>신한금융투자</option>
												<option selected value='088'>신한은행</option>
												<option value='048'>신협중앙회</option>
												<option value='056'>알비에스은행</option>
												<option value='005'>외환은행</option>
												<option value='020'>우리은행</option>
												<option value='247'>우리투자증권</option>
												<option value='071'>우체국</option>
												<option value='280'>유진투자증권</option>
												<option value='265'>이트레이드증권</option>
												<option value='037'>전북은행</option>
												<option value='057'>제이피모간체이스은행</option>
												<option value='035'>제주은행</option>
												<option value='090'>카카오뱅크</option>
												<option value='264'>키움증권</option>
												<option value='270'>하나대투증권</option>
												<option value='081'>하나은행</option>
												<option value='262'>하이투자증권</option>
												<option value='027'>한국씨티은행</option>
												<option value='243'>한국투자증권</option>
												<option value='269'>한화증권</option>
												<option value='218'>현대증권</option>
											</select>
										</div>	
										<h2 style="float: left; margin-top: 8px;">계좌번호 &nbsp;</h2>
										<div class="wrap">
											<input type="number" style="height: 40px;" placeholder="계좌번호" name="accountNum" id="accountNum" maxlength="17" oninput="maxLengthCheck(this)" value="110454931633" />
										</div>
										<div style="height: 5px;"></div>
										<h2>◆ 새로운 부사업자</h2>
										<h2 style="float: left; margin-top: 9px;">성 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;명 &nbsp;</h2>
										<div class="wrap">
											<input type="text" placeholder="성명" name="sName" id="sName" value="김테스트"/>
										</div>
										<h2 style="float: left; margin-top: 9px;">생년월일 &nbsp;</h2>
		
										<div class="wrap">
											<input type="number" style="height: 40px;" placeholder="생년월일(8자리로 입력)" name="sBirth" id="sBirth" maxlength="8" oninput="maxLengthCheck(this)" value="19880101"/>
										</div>
										<!--
										<div class="wrap">
											<b>첨부 서류 업로드</b>
										</div>
										  
										<div class="wrap">
											<input type="file" name="myfile1" id="myfile1" size="60" accept="image/*">
										</div>
										-->
									</div><br/>
									<div id="agree" style="display: none;">
										<p style="font-size: 9px;font-weight: bold">■ 해당 회계연도 중 주ㆍ부사업자 변경 이전에 발생한 후원수당이 있습니까?</p> 
										<div style="font-size: 10px;text-align: center">
											<input type="radio" id="chk1" name="checkYN1" value="Yes" />&nbsp;예 &nbsp;
											<input type="radio" id="chk2" name="checkYN1" value="No"/>&nbsp;아니오 
										</div>
												
										<p style="font-size: 9px;font-weight: bold">■ 후원수당이 발생한 경우, 관련 법령에 따라 주사업자의 주민등록번호를 제공하셨습니까?</p>
										<div style="font-size: 10px;text-align: center">
											<input type="radio" id="chk3" name="checkYN2" value="Yes" />&nbsp;예 &nbsp;
											<input type="radio" id="chk4" name="checkYN2" value="No"/>&nbsp;아니오 
										</div>
										<p style="font-size: 9px;font-weight: bold">
											※ 해당 회계연도 중 발생한 후원수당이 있는 경우, 주민 등록 번호를 회사에 제공한 경우에만 주ㆍ부사업자 변경 접수 및 그 처리가 가능합니다.
										</p>
									</div>
									<div style="height:5px;"></div>
									<div id="agreement1" style="display: block;">
										<p style="font-size: 10px;font-weight: bold">본인과 공동등록자(이하'신청인'이라 함) 는 위 내용을 모두 이해하고 동의하며, 사실만을 기재하여 유니시티코리아(유)의 디스트리뷰터로 '공동등록'/'주ㆍ부 사업자 변경'을 신청 합니다.또한 신청인은
										방문판매등에 관한 법률 등 관련 법률 상 다단계판매원 결격사유에 해당하지 않으며, 관련법률과 유니시티의 모든 규정 및 방침을 준수하여 디스트리뷰터 자격을 수행할 것임을 확약 하십니까?
										</p>
										<div style="height:4px;"></div>
										<div style="font-size: 10px;text-align: center">
											<input type="radio" id="chk5" name="checkYN3" value="Yes" />&nbsp;예 &nbsp;
											<input type="radio" id="chk6" name="checkYN3" value="No"/>&nbsp;아니오 
										</div>
									</div>
									<div style="height: 5px;"></div>
									<div id="nextButton1" align="center" style="background-color: #B2CCFF;width: 50px;margin-left: 40%; border-radius : 5px; text-shadow: 0px -1px 1px rgba(0,0,0,.3); border: 1px solid #4081AF;box-shadow : inset 0 1px 0 rgba(255,255,255,.3), inset 0 0 2px rgba(255,255,255,.3) 0 1px 2px rgba(0,0,0,.29); cursor: pointer; display: none; ">
										<a href="javascript:nextButton()"><b>다음</b></a>
									</div>
									<div id="nextButton2" align="center" style="background-color: #B2CCFF;width: 50px;margin-left: 40%; border-radius : 5px; text-shadow: 0px -1px 1px rgba(0,0,0,.3); border: 1px solid #4081AF;box-shadow : inset 0 1px 0 rgba(255,255,255,.3), inset 0 0 2px rgba(255,255,255,.3) 0 1px 2px rgba(0,0,0,.29); cursor: pointer; display: none; ">
										<a href="javascript:nextButton1()"><b>다음</b></a>
									</div>
								</div>
								<div class="main_inner_box" id="agreementChk" style="display:none;">
									<div class="main_top">
										<h2>
											<span style="font-size: 20px;">주ㆍ부 사업자 변경 동의서</span>
										</h2>
									</div>
									<div style="height: 20px;"></div>
									<div class="wrap">
										<b>변경 전 주사업자</b>
									</div>
									<div class="wrap">
										성&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;명 : <input type="text" id="mNameVal" name="mNameVal" style="border:0px;background-color:transparent" readonly="readonly" />
									</div>
									<div class="wrap">
										생년월일 : <input type="text" id="mBirthVal" style="border:0px;background-color:transparent" readonly="readonly" />
									</div>
									<div style="height: 20px;"></div>
									<div class="wrap">
										<b>변경 후 주사업자</b>
									</div>
									<div class="wrap">
										성&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;명 : <input type="text" id="sNameVal" name="sNameVal" style="border:0px;background-color:transparent" readonly="readonly" />
									</div>
									<div class="wrap">
										생년월일 : <input type="text" id="sBirthVal" style="border:0px;background-color:transparent" readonly="readonly" />
									</div>
								
									<div style="height: 20px;"></div>
									<div style="margin-left: 10%;margin-right: 10%">
										<P style="font-size: 12px;font-weight: bold">회원번호 <b><?php echo $distID ?></b>의 주사업자인 본인 <input name="mNameVal" type="text" style="border:0px; background-color:transparent; width: 73px; height: 15px; font-weight: bold;" readonly="readonly"/>
										 은(는) 공동등록자 <input type="text" name="sNameVal" style="border:0px;background-color:transparent; width: 73px; height: 15px; font-weight: bold;" readonly="readonly" />에게로 주사업자의 지위와 권한을 변경하는 것에 동의하며,
										이를 신청 합니다. 아울러, 변경 후 6개월 이내에는 주ㆍ부사업자의 재변경이 불가함을 인지 하였고, 확인 후 이에 동의합니다.</P>
									</div>
									<div style="height: 5px;"></div>
									<div style="font-size: 10px;text-align: center">
										<input type="radio" id="chk7" name="checkYN4" value="Yes" />&nbsp;예 &nbsp;
										<input type="radio" id="chk8" name="checkYN4" value="No"/>&nbsp;아니오 
									</div>
									<div style="height: 10px;"></div>
								</div>
								<div align="center" id="agreementBut" style="background-color: #B2CCFF;width: 100px;margin-left: 40%; border-radius : 5px; text-shadow: 0px -1px 1px rgba(0,0,0,.3); border: 1px solid #4081AF;box-shadow : inset 0 1px 0 rgba(255,255,255,.3), inset 0 0 2px rgba(255,255,255,.3) 0 1px 2px rgba(0,0,0,.29); cursor: pointer; display: none; ">
									<a href="javascript:agreementButton()"><b>동의 및 저장</b></a>
								</div>
							</div>
						</form>
					</div>	
				</div>
			</div>		
		</div>		
	</body>
</html>
