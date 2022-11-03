<?php
session_start();


$flag = $_POST['flag'];

$sponserId = $_GET['sponsorId'];
$memberType = $_GET['memberType'];

$_SESSION["S_K_SPONSOR_ID"] = $sponserId;
$_SESSION["S_K_MEMBERTYPE"] = $memberType ;


if(!isset($_SERVER["HTTPS"])) {
	header('Location: https://'.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']);
	exit;
}
include "./includes/common_functions.php";
include "./includes/nc_config.php";

//if($_GET['memberType'] != 'D' && $_GET['memberType'] != 'C'){
//	Redirect("./guide.php");
//}

$user_device = mobile_check();  // return P or M

//세션아이디
if($_SESSION['ssid'] == "") $_SESSION['ssid'] = session_id().time();

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
$returnurl = "https://".$_SERVER['HTTP_HOST']."/register/includes/cert_success.php";	// 성공시 이동될 URL - Success
$errorurl =  "https://".$_SERVER['HTTP_HOST']."/register/includes/cert_fail.php";		// 실패시 이동될 URL - Fail

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

if( $enc_data == -1 ){
	$returnMsg = "encryption error.";
	$enc_data = "";
}else if( $enc_data== -2 ){
	$returnMsg = "encryption process error.";
	$enc_data = "";
}else if( $enc_data== -3 ){
	$returnMsg = "encryption data error.";
	$enc_data = "";
}else if( $enc_data== -9 ){
	$returnMsg = "input value error.";
	$enc_data = "";
}

// IPIN server  part
$sSiteCode					= $Ip_SiteID;			// IPIN service site code
$sSitePw					= $Ip_SitePW;			// IPIN service site password
$sModulePath				= $Ip_encode_path;			// Module Path
$sReturnURL					= "https://".$_SERVER['HTTP_HOST']."/register/includes/ipin_process.php";			// Return utl
$sCPRequest					= "";			// when result == success, we need to store sCPRequest value and verify from the success page

$sCPRequest = `$sModulePath SEQ $sSiteCode`;

$_SESSION['CPREQUEST'] = $sCPRequest;

$sEncData					= "";			// encrypted data
$sRtnMsg					= "";			// return result msg

// Encryption
$sEncData	= `$sModulePath REQ $sSiteCode $sSitePw $sCPRequest $sReturnURL`;

// result
if ($sEncData == -9){
	$sRtnMsg = "Input value error : one of the parameters are wrong. fail to encrypt";
} else {
	$sRtnMsg = "$sEncData has to be encrypted now. if now please contact admin.";
}



include "./includes/top.php";
?>

<script type="text/javascript">
	// 인증
	window.name ="Parent_window";

	function fnPopupCb(type){
		<?
		if($_GET['direct']!='y'){
		?>
		window.open('', 'popupChk', 'width=408, height=655, top=100, left=100, fullscreen=no, resizable=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
		<?}?>

		if (type == "M") {
			document.form_chk.EncodeData.value = document.form_chk.MEncodeData.value;
		} else if (type == "C") {
			document.form_chk.EncodeData.value = document.form_chk.CEncodeData.value;
		} else if (type == "X") {
			document.form_chk.EncodeData.value = document.form_chk.XEncodeData.value;
		}

		//console.log('value 1 : ' + document.form_chk.MEncodeData.value);
		//console.log('value ' + document.form_chk.EncodeData.value);

		document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
		<?
		if($_GET['direct']!='y'){
		?>
		document.form_chk.target = "popupChk";
		<?}?>
		document.form_chk.submit();

	}

	function fnPopupIp(){
		window.open('', 'popupIPIN', 'width=450, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
		document.form_ipin.target = "popupIPIN";
		document.form_ipin.action = "https://cert.vno.co.kr/ipin.cb";
		document.form_ipin.submit();
 	}

 	function go_next_sign() {
 		document.location.href = '/register/guide.php';
 	}

	function sign_fail(){
		//nothing
	}


	function go() { 
		var app_key = "abda45f8a0495e41791983180aa4ccfc"; 
		var redirect_uri = "https://www.makelifebetter.co.kr/register/includes/kakaoLogin.php"; 
		location.href = "https://kauth.kakao.com/oauth/authorize?client_id=" + app_key + "&redirect_uri=" + redirect_uri + "&response_type=code"; 
	

	}


</script>

<div class="cont_wrap">
    <dl class="conttit_wrap mo_none">
        <dt>본인인증</dt>
        <dd>외국인 회원가입 또는 국제후원가입은 지역센터로 문의 하시기 바랍니다. <a href="https://korea.unicity.com/contact-us/" target="_blank">지역센터보기</a></dd>
    </dl>

</div>

<!-- need this form for the certificate verification popup. -->
<form name="form_chk" method="post" rel="opener">
	<input type="hidden" name="m" value="checkplusSerivce">						<!-- Mandatory data. -->
	<input type="hidden" name="EncodeData" value="<?=$enc_data?>">		<!-- encrypted data for unicity korea -->
	<input type="hidden" name="MEncodeData" value="<?=$enc_dataM?>">
	<input type="hidden" name="CEncodeData" value="<?=$enc_dataC?>">
	<input type="hidden" name="XEncodeData" value="<?=$enc_dataX?>">
	<!-- extra fields that we can request from the vendor. leave them blank for now -->
	<input type="hidden" name="param_r1" value="">
	<input type="hidden" name="param_r2" value="<?=$_GET['direct']?>">
	<input type="hidden" name="param_r3" value="<?=$_GET['memberType']?>">
</form>

<!-- Ipin verification -->
<form name="form_ipin" method="post" rel="opener">
	<input type="hidden" name="m" value="pubmain">						<!-- 필수 데이타로, 누락하시면 안됩니다. -->
	<input type="hidden" name="enc_data" value="<?= $sEncData ?>">		<!-- 위에서 업체정보를 암호화 한 데이타입니다. -->
	<input type="hidden" name="param_r1" value="">
	<input type="hidden" name="param_r2" value="<?=$_GET['direct']?>">
	<input type="hidden" name="param_r3" value="<?=$_GET['memberType']?>">
</form>

<!-- 결과값 담아 ipin_result.php로 넘기는 form -->
<form name="vnoform" method="post">
<input type="hidden" name="enc_data">
<input type="hidden" name="param_r1" value="">
<input type="hidden" name="param_r2" value="">
<input type="hidden" name="param_r3" value="<?=$_GET['memberType']?>">
</form>

<form name="kakaoLoginForm" method ="POST" rel="opener">
	<input type="hidden" name="k_name" value ="<?echo $k_name ?>">
	<input type="hidden" name="k_email" value ="<?echo $k_email ?>">
	<input type="hidden" name="k_phone_number" value ="<?echo $k_phone_number ?>">
	<input type="hidden" name="k_birthday" value ="<?echo $k_birthday ?>">
	<input type="hidden" name="sponsor_id" value ="">
	<input type="hidden" name="flag" value ="kakao">
</form>



<div class="cont_wrap">
	<h2 class="certification_title">본인인증</h2>
	<div class="certification_box">
		<ul class="certification_list">
			<li class="ty01"><div><a href="javascript:fnPopupCb('M');" rel="opener">휴대폰 인증</a></div></li>
			<li class="ty02"><div><a href="javascript:fnPopupCb('X');" rel="opener">공동인증서 인증</a></div></li>
			<li class="ty03"><div><a href="javascript:fnPopupIp();" rel="opener">I - Pin 인증</a></div></li>
		</ul>
	</div>
</div>

<div class="btn_box">
   	<?php  if ($_SESSION['S_BIRTH'] != '' && $_SESSION['S_GENDER'] != '' && $_SESSION['S_NM'] != '')  { ?>
		<a class="btn btn_color_2" href="registerForm.php?memberType=<?=$_GET['memberType']?>" class="gray_btn" id="nextbutton">다음</a>
	<?php } ?>
	<a class="btn" href="guide.php" class="gray_btn" id="nextbutton">뒤로가기</a>

</div>
<?php include "./includes/footer.php";?>


<script>


function device_check() {
	// 디바이스 종류 설정
	var pc_device = "win16|win32|win64|mac|macintel";

	// 접속한 디바이스 환경
	var this_device = navigator.platform;

	if ( this_device ) {

		if ( pc_device.indexOf(navigator.platform.toLowerCase()) < 0 ) {
			return 'mo'
		}
	};
}
function pcMoType (){
  var pcMo = document.getElementById('pc_mo_bool');
  var pcMo_bool = ( window.getComputedStyle(pcMo).getPropertyValue('display') == 'block' ) ? 'pc' : 'mo';
  return pcMo_bool;
}

var timer;
var delta = 300;
$(window).on('resize' , function(){
	wheelMove = false;

	clearTimeout( timer );
	timer = setTimeout( resizeDone, delta );

});

function resizeDone(){
	if( device_check()  == 'mo' ){
		$('.ty02').hide();
	}else{
		$('.ty02').show();
	}
}
resizeDone();

</script>
</body>
</html>
