<?
header("Content-Type: text/html; charset=UTF-8");

include "../../dbconn_utf8.inc";

function goToPage($msg, $url){
	echo "<script>";
	if($msg != "") echo "alert('$msg');";
	echo "document.location.replace('$url');";
	echo "</script>";
	exit;
}
function errorMsg($msg){
	echo "<script>";
	echo "alert('$msg');";
	echo "history.back();";
	echo "</script>";
	exit;
}

$auchCode = str_quote_smart(trim($_POST['auchCode']));
$birth	  = str_quote_smart(trim($_POST['bth']));

if(strpos($_SERVER['HTTP_REFERER'], "makelifebetter.co.kr") < 0){
	errorMsg("올바른 경로가 아닙니다");
}

if($auchCode == "" || $birth == ""){
	errorMsg("주요정보가 누락되었습니다.");
}

$data = base64_decode($auchCode);
//echo $data."<br>";

$reg_num = substr($data, 0, strlen($data)-10);
$mno = substr($data,-10,10) * 1;

$result = mysql_query("select * from tb_useroff where mno = '$mno' and birth='$birth' and reg_num = '$reg_num' order by mno desc limit 1") or die(mysql_error());	
$row = mysql_fetch_array($result);

if($row[0] == "") errorMsg("회원을 조회할 수 없습니다");

$birth = substr($row[birth],0,4)."-".substr($row[birth],4,2)."-".substr($row[birth],6,2);
$reg_date = substr($row[reg_date],0,4)."-".substr($row[reg_date],4,2)."-".substr($row[reg_date],6,2);

//정보업데이트
mysql_query("update tb_useroff set email_send_yn='Y', email_read_date=now() where mno = '$mno'") or die(mysql_error());	

//회원카드조회
$saveImg = $_SERVER['DOCUMENT_ROOT']."/formmail/card/".$data.".png";

//회원카드이미지가 없는 경우 카드이미지 생성
if(!file_exists($saveImg)){
	$baseImg = $_SERVER['DOCUMENT_ROOT']."/formmail/images/unct_card_txt.png";
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

//echo $auchCode."<br>";
//echo $birth."<br>";
?>

<!DOCTYPE html>
<html lang="ko-KR" prefix="og: http://ogp.me/ns#" class=" footer-sticky-0">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="http://korea.unicity.com/xmlrpc.php">
<title>기업 | 유니시티코리아</title>

<link rel="canonical" href="http://korea.unicity.com/our-company/" />
<meta property="og:locale" content="ko_KR" />
<meta property="og:type" content="article" />
<meta property="og:title" content="기업 | 유니시티코리아" />
<meta property="og:url" content="http://korea.unicity.com/our-company/" />
<meta property="og:site_name" content="유니시티코리아" />
<meta property="article:publisher" content="https://www.facebook.com/unicitykorea.official" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="기업 | 유니시티코리아" />

<link rel="stylesheet" type="text/css" href="css/style.css"/>
</head>

<body>

<div class="all_unct_wrap">
	<div class="unct_card">
		<a href="#" title="logo" class="unct_logo02"><img src="images/logo02.png"></a>
		<div class="unct_card_contents">
			<div class="unct_card_txt tac"><img src="images/card_txt.png" alt=""></div>
			<div class="unct_card_pic unct_card_pic02">
				<a href="download.php?cnum=<?=$data?>&name=<?=urlencode($row['name'])?>"><img src="/formmail/card/<?=$data?>.png" /></a>	
				<div>
					<a href="download.php?cnum=<?=$data?>&name=<?=urlencode($row['name'])?>"><span style="color: #fff;font-size: 16px;">회원카드다운로드</span></a>
				</div>
			</div>
			
			<div class="unct_card_info">등록증을 카드 형태로 재단하여 사용하세요.</div>
		</div>
	
	</div><!-- //unct_form -->
</div> <!-- //all_unct_wrap -->

</body>
</html>
