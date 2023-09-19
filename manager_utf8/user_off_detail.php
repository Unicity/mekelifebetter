<?
include "admin_session_check.inc";
include "./inc/global_init.inc";
include "../dbconn_utf8.inc";
include "../AES.php";
include "./inc/common_function.php";

?>
<html>
<head>
<title><?echo $g_site_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
<script type="text/javascript">
function cardDownload(n){
	location.href = 'user_off_card_download.php?mno='+n;
}
</script>
</head>
<BODY bgcolor="#FFFFFF" style="padding:10px">

<?php include "common_load.php" ?>

<? if($_GET['mno'] == "") { ?>
	<script type="text/javascript">
	alert("조회대상이 없습니다");
	self.close();
	</script>
	<? exit; ?>
<? } ?>

<?
$result = mysql_query("select * from tb_useroff where mno = '".$_GET['mno']."'") or die(mysql_error());	
$row = mysql_fetch_array($result);

if($row[0] == ""){
	echo'
	<script type="text/javascript">
	alert("조회대상이 없습니다");
	self.close();
	</script>';
	exit;
}

$fixNum = sprintf("%010d",$row[mno]);
$cardNum = $row[reg_num].$fixNum;

$enNum = base64_encode($cardNum);

$birth = substr($row[birth],0,4)."-".substr($row[birth],4,2)."-".substr($row[birth],6,2);
$reg_date = substr($row[reg_date],0,4)."-".substr($row[reg_date],4,2)."-".substr($row[reg_date],6,2);

//회원카드생성
$baseImg = $_SERVER['DOCUMENT_ROOT']."/formmail/images/unct_card_txt.png";
//$saveImg = $_SERVER['DOCUMENT_ROOT']."/formmail/card/test.png";
$saveImg = $_SERVER['DOCUMENT_ROOT']."/formmail/card/".$cardNum.".png";

//회원카드이미지가 없는 경우 카드이미지 생성
if(!file_exists($saveImg)){

	$font = $_SERVER['DOCUMENT_ROOT']."/formmail/font/Noto-KR-Bold.ttf";

	$fontSize = 11;
	if(strlen($row[addr]) > 60) $fontSize = 10;
	else if(strlen($row[addr]) > 80) $fontSize = 9;

	$im  = imagecreatefrompng($baseImg); // 배경이미지
	$tc  = imagecolorallocatealpha($im, 0, 0, 0, 0); // 텍스트컬러

	//투명처리
	imagesavealpha($im, true); 


	// imagettftext(이미지, 텍스트 크기, 텍스트 각도, x축, y축, 텍스트 컬러, 텍스트 폰트, 텍스트 내용);
	imagettftext($im, 11, 0, 104, 82, $tc, $font, $row[name]);
	imagettftext($im, 11, 0, 104, 109, $tc, $font, $birth);
	imagettftext($im, 11, 0, 104, 135, $tc, $font, $row[reg_num]);
	imagettftext($im, 11, 0, 298, 135, $tc, $font, $reg_date);
	imagettftext($im, $fontSize, 0, 104, 161, $tc, $font, $row[addr]);
	imagettftext($im, $fontSize, 0, 104, 185, $tc, $font, $row[addr2]);

	imagepng($im, $saveImg);
	imagedestroy($im);

	@system("chmod 0777 $saveImg");
}
?>

<TABLE cellspacing="0" cellpadding="10" class="TITLE" style="width:95%">
<TR>
	<TD align="left"><B>회원조회</B></TD>
</TR>
</TABLE>

<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
<TR>
	<TH width="80px">이름</TH>	
	<TD align="left"><B><?=$row['name']?></B></TD>
</TR>
<TR>
	<TH width="80px">생년월일</TH>	
	<TD align="left"><B><?=$birth?></B></TD>
</TR>
<TR>
	<TH width="80px">등록번호</TH>	
	<TD align="left"><B><?=$row['reg_num']?></B></TD>
</TR>
<TR>
	<TH width="80px">등록일자</TH>	
	<TD align="left"><B><?=$reg_date?></B></TD>
</TR>
<TR>
	<TH width="80px">발송일자</TH>	
	<TD align="left">
		<? //if($row['email_send_yn'] == "Y"){ ?>
			<B><?=substr($row['email_send_date'],0,10)?></B>
		<? //}else{ ?>
			<!-- - -->
		<? //} ?>
	</TD>
</TR>
<TR>
	<TH width="80px">발송여부</TH>	
	<TD align="left">
		<B><?=$row['email_send_yn']?></B>
		<? if($row['email_error'] != ""){ ?>
			(<?=$row['email_error']?>)
		<? } ?>
	</TD>
</TR>
<TR>
	<TH width="80px">수신확인</TH>	
	<TD align="left">
		<B>
		<?
		if($row['email_read_date'] != "") echo $row['email_read_date'];
		else echo "-";
		?>
		</B>
	</TD>
</TR>

<TR>
	<TH width="80px">카드번호</TH>	
	<TD align="left"><B><?=$cardNum?> <!-- <?=$enNum?> --></B></TD>
</TR>
<TR>
	<TH width="80px">회원카드</TH>	
	<TD align="left" style="line-height:230%">
		<img src="/formmail/card/<?=$cardNum?>.png"><br>
		<span class="input" onclick="cardDownload('<?=$row[mno]?>')" style="padding:3px 10px; background-color:#bbb;color:#fff;font-size:11px;">다운로드</span>

	</TD>
</TR>
</TABLE>
<br>
<TABLE cellspacing="0" cellpadding="10" class="TITLE" style="width:95%">
<TR>
	<td align="center"><span class="input" onclick="self.close()">닫기</span></td>
</TR>
</TABLE>

<br><br>
<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>
</body>
</html>
<?
mysql_close($connect);
?>