<?
include "admin_session_check.inc";
include "./inc/global_init.inc";
include "../dbconn_utf8.inc";
include "../AES.php";
include "./inc/common_function.php";


function goToPage($msg, $url){
	echo "<script>";
	if($msg != "") echo "alert('$msg');";
	echo "document.location.replace('$url');";
	echo "</script>";
	exit;
}

function convertData($str){
	$str = iconv("euc-kr","utf-8",$str);
	$str = str_replace('"',"",$str);
	$str = str_replace("'","",$str);
	//$str = str_replace("-","",$str);
	return $str;
}


//업로드DIR 정의
$uploads_dir = $_SERVER["DOCUMENT_ROOT"].'/manager_utf8/excel';


//파일업로드
if($_POST['mode'] == "upload"){ 
	
	$allowed_ext = array('csv','CSV');

	$error = $_FILES['upfile']['error'];
	$name = $_FILES['upfile']['name'];
	$ext = array_pop(explode('.', $name));
	//파일형식: $_FILES['upfile']['type']
	//파일크기: $_FILES['upfile']['size']

	$upname = time().rand(111,999).".".strtolower($ext);

	if( $error != "UPLOAD_ERR_OK" ) {
		echo "파일이 제대로 업로드되지 않았습니다. ($error)";
		goToPage("파일이 제대로 업로드되지 않았습니다", "/manager_utf8/user_off_input.php");
	}

	if( !in_array($ext, $allowed_ext) ) {
		goToPage("csv파일만 등록 가능합니다", "/manager_utf8/user_off_input.php");
	}

	// 파일 이동
	if(move_uploaded_file($_FILES['upfile']['tmp_name'], $uploads_dir."/".$upname)){
		$rurl = "/manager_utf8/user_off_input.php?mode=confirm&f=".$upname;
		goToPage("", $rurl);
	}else{
		goToPage("파일 업로드가 되지 않았습니다", "/manager_utf8/user_off_input.php");
	}

	exit;	
}
?>
<html>
<head>
<title><?echo $g_site_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<link rel="stylesheet" href="inc/admin.css" type="text/css">
<STYLE type='text/css'>
TD {FONT-SIZE: 9pt}
.h {FONT-SIZE: 9pt; LINE-HEIGHT: 120%}
.h2 {FONT-SIZE: 9pt; LINE-HEIGHT: 180%}
.s {FONT-SIZE: 8pt}
.l {FONT-SIZE: 11pt}
.text {  line-height: 125%}

.input { background-color:#000; color:#fff; font-size:14px; cursor:pointer; padding:10px 20px; }
</STYLE>
<script type="text/javascript" src="/manager_utf8/inc/jquery.js"></script>
</head>
<BODY bgcolor="#FFFFFF">


<?php include "common_load.php" ?>


<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>회원카드 온라인 발급 관리 - 엑셀등록</B></TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
		&nbsp; 
	</TD>
</TR>
</TABLE>


<? if($mode == ""){ ?>
<br>
<script type="text/javascript">
function uploadCsv(){
	if($("#upfile").val() ==""){
		alert("업로드할 csv 파일을 선택하여 주세요");
	}else if(/csv$/.test($("#upfile").val()) == false && /CSV$/.test($("#upfile").val()) == false){
		alert("csv파일만 등록하실 수 있습니다.");
	}else{
		$("#uploadForm").submit();
	}
}
</script>
<form name="uploadForm" id="uploadForm" method="post" enctype="multipart/form-data" action="user_off_input.php">
<input type="hidden" name="mode" id="mode" value="upload">
<strong>* csv파일만 등록가능합니다</strong> (파일 -> 다른이름으로저장 -> CSV(쉼표로분리))
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
		<table bgcolor="#EEEEEE" width="100%" cellpadding='0' cellspacing='0' border='1' bordercolorlight='#FFFFFF' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
			<tr>
				<td align="right" width="100" style="padding:7px 0px">
					<b>엑셀파일 : &nbsp;</b>
				</td>
				<td  style="padding:7px">
					<input type="file" name="upfile" id="upfile" style="width:70%">
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr><td height="20"></td></tr>
<tr>
	<td align="center"><span class="input" onclick="uploadCsv()">CSV파일올리기</span></td>
</tr>
</table>
</form>

<? } ?>


<?

if($mode == "confirm" || $mode == "insert"){
	setlocale(LC_CTYPE, 'ko_KR.eucKR'); 

	if($_GET['f'] == "") goToPage("등록된 파일이 없습니다.", "/manager_utf8/user_off_input.php");	
	
	//그룹구하기
	$result2 = mysql_query("select max(grp) from tb_useroff") or die(mysql_error());	
	$row2 = mysql_fetch_array($result2);
	$grp = $row2[0] + 1;

	$filename = $uploads_dir."/".$_GET['f'];
	//$data = file_get_contents($filename);

	if(!is_file($filename)) goToPage("파일검색이 되지 않습니다.", "/manager_utf8/user_off_input.php");

	$wdate = date("Y-m-d H:i:s");
	$tot = 0; 
	$indata = 0;
	$errdata = "";
	$handle = fopen($filename, "r"); 
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) { 
		
		if($mode == "insert"){  //데이터등록		
			$num = count($data);
			$val = array();
			if($tot  > 0){ //제목행제외
				for ($c=0; $c < $num; $c++) { 
					$val[$c] = convertData($data[$c]);

					if($c == 9){ //Birth_Check
						if(strtolower($val[9]) == "yes") $val[9] = "Y";
						else $val[9] = "N";
					}
				} 

				//이메일유효성검사
				$check_email=preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $val[7]);  

				//등록양식 변경 - 210310 이성수 부장님 요청
				//등록일자0,성명1,회원번호2,생년월일3,기본주소4,상세주소5,우편번호6,메일주소7, Flag19,Consents,BIRTH CHECK, Entry_init
				$val[0]	= str_replace("-","",trim($val[0]));
				$val[3]	= str_replace("-","",trim($val[3]));


				if($check_email==true && $val[0] > 0 && $val[3] > 0) {  //생년월일, 등록일자 숫자 검증 추가
				//if($check_email==true && $val[1] > 0 && $val[3] > 0) {  //생년월일, 등록일자 숫자 검증 추가 - 변경

					//이메일중복체크  - 190611 이성수부장님 요청에 의해 이메일중복 제거
					//$resultc = mysql_query("select count(mno) from tb_useroff where email='$val[7]' and del_tf='N'") or die(mysql_error());
					//$rowc = mysql_fetch_array($resultc);
					//if($rowc[0] > 0){ //이메일중복

					//	$errdata .= "(중복이메일) ".$val[0].",".$val[1].",".$val[2].",".$val[2].",".$val[7]."<br>";

					//}else{
					
						$indata++;
						
						$sql = "insert into tb_useroff (grp, name, birth, reg_num, reg_date, addr, addr2, zip, email, email_yn, birth_chk, write_date, write_adm) ";
						$sql .=  " values ";
						$sql .=  "('$grp', '$val[1]', '$val[3]', '$val[2]', '$val[0]', '$val[4]', '$val[5]', '$val[6]', '$val[7]', 'Y', 'Y', '$wdate', '$_SESSION[s_adm_id]')";
						//$sql .=  "('$grp', '$val[0]', '$val[1]', '$val[2]', '$val[3]', '$val[4]', '$val[5]', '$val[6]', '$val[7]', '$val[8]', '$val[9]', '$wdate', '$_SESSION[s_adm_id]')";
						//echo $sql."<br>";
						mysql_query($sql) or die(mysql_error());
					//}


				}else{

				   $errdata .= $val[0].",".$val[1].",".$val[2].",".$val[2].",".$val[7]."<br>";
				}
			}
		}
		$tot++; 
	} 
	fclose($handle); 

	if($indata > 0){
		//담당자메일발송
		$who = 'sjkim@giringrim.com,adonis@giringrim.com,sua@giringrim.com';
		$title = iconv('utf-8','euc-kr','[유니시티] 회원카드 온라인등록 신규데이터 등록 ('.$indata.'건)');
		$content = iconv('utf-8','euc-kr','[유니시티] 회원카드 온라인등록 신규데이터 등록 ('.$indata.'건)');

		$optionValue = 'From: '.iconv('utf-8','euc-kr','유니씨티코리아온라인').'<master@makelifebetter.co.kr>';
		$header.= "MIME-Version: 1.0";
		$header.= "Content-Type: text/html; charset=euc-kr";
		$header.= "X-Mailer: PHP";

		@mail($who, $title, $content, $optionValue);
	}

	if($mode == "insert"){
		
		if($errdata != ""){
			?>			
			<br><br>
			<font color="red">* 이메일 또는 생년월일, 등록일자가 잘못되어 등록되지 않은 회원입니다.(드래그하여 복사후 별도 저장하여 주세요).</font><br><br>
			<?=$errdata?><br><br>
			<span onclick="location.href='/manager_utf8/user_off_list.php'" class="input">확인</span>
			<?
		}else{
			goToPage("등록되었습니다.", "/manager_utf8/user_off_list.php");
		}
	}

}
?>


<? if($mode == "confirm"){ ?>

	<table>
		<tr>
			<td style="padding:50px; line-height:200%; font-size:14px;">
				- 업로드 파일명 :  <?=$_GET['f']?> <br><br>
				
				<? if($tot - 1 > 0){ ?>
					- 총 <font color="red"><strong><?=number_format($tot - 1)?></strong></font> 개의 회원자료가 조회되었습니다.<br><br>
					등록하시겠습니까? <br><br>
					<span onclick="location.href='user_off_input.php?mode=insert&f=<?=$_GET['f']?>'" class="input">등록하기</span>

				<? }else{ ?>
					<font color="red">- 등록하실 회원자료가 없습니다.</font><br><br>
					
					- 첫번째행은 제목을 입력해 주시고, 회원자료는 2번째 행부터 입력해 주세요.<br><br>
					<span onclick="location.href='user_off_input.php'" class="input">뒤로</span>
				<? } ?>

			</td>
		</tr>
	</table>

<? } ?>
<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>
</body>
</html>
<?
mysql_close($connect);
?>