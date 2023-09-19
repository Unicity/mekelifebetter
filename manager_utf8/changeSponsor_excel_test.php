<?
ini_set('memory_limit',-1);
ini_set('max_execution_time', 60);

include "admin_session_check.inc";
//include "./inc/global_init.inc";
include "../dbconn_utf8.inc";
include "../AES.php";

$idVal = str_quote_smart(trim($idVal));
if($idVal == ""){
	echo "<script>alert('선택내역이 없습니다');history.back();</script>";
	exit;
}

//$str_title = iconv("UTF-8","EUC-KR","후원자 변경 신청");
//$file_name=$str_title."-".date("Ymd").".xls";
//header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
//header( "Content-Disposition: attachment; filename=$file_name" );
//header( "Content-Description: orion70kr@gmail.com" );

$s_status = str_quote_smart(trim($r_status));
$qry_str = str_quote_smart(trim($qry_str));
$con_order = str_quote_smart(trim($con_order));
$idxfield = str_quote_smart(trim($idxfield));
$con_sort = str_quote_smart(trim($con_sort));

if ((empty($s_status)) || ($s_status == "A")) {
	$s_status = "A";
} else {
	$que = $que." and reg_status = '$s_status' ";
}

if (!empty($qry_str)) {
	
	if ($idxfield == "0") {
		$que = $que." and member_no like '%$qry_str%' ";
	} else if($idxfield == "1"){
		$que = $que." and member_name like '%$qry_str%' ";
	}
}


$query2 = "select * from tb_change_sponsor where no in (".$idVal.") ".$que." order by ".$con_sort;
$result2 = mysql_query($query2);

ob_start();
?>

<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<style>
            .xlGeneral {
            	padding-top:1px;
            	padding-right:1px;
            	padding-left:1px;
            	mso-ignore:padding;
            	color:windowtext;
            	font-size:10.0pt;
            	font-weight:400;
            	font-style:normal;
            	text-decoration:none;
            	font-family:Arial;
            	mso-generic-font-family:auto;
            	mso-font-charset:0;
            	mso-number-format:\@;
            	text-align:Left;
            	vertical-align:bottom;
            	mso-background-source:auto;
            	mso-pattern:auto;
            	white-space:nowrap;
            }
            .backGround{
                background-color: #D5D5D5;
            }
</style>
	</head>
	<body>
		<table border="1">
			<tr align="center">
				<th class="backGround" width="5%" style="text-align: center;">신청일자</th>
				<th class="backGround" width="5%" style="text-align: center;">신청인 번호</th>
				<th class="backGround" width="5%" style="text-align: center;">신청인</th>
				<th class="backGround" width="5%" style="text-align: center;">현재 후원자 번호</th>
				<th class="backGround" width="5%" style="text-align: center;">현재 후원인</th>
				<th class="backGround" width="5%" style="text-align: center;">변경 후원자 번호</th>
				<th class="backGround" width="5%" style="text-align: center;">변경 후원인</th>
				<th class="backGround" width="5%" style="text-align: center;">동의여부</th>
				<th class="backGround" width="5%" style="text-align: center;">동의날짜</th>
			</tr>
			<?php 
			while($obj = mysql_fetch_object($result2)) {
			    $apply_s = date("Y-m-d", strtotime($obj->apply_date));
			    if($obj-> sponsor_agree_yn == 'Y'){
			         $agree_s = date("Y-m-d", strtotime($obj->agree_date));
			    }else{
			        $agree_s = '0000-00-00';
			    }
			    ?>
			<tr>
				<td style="width: 5%" align="center"><?echo $apply_s?></td>
				<td style="width: 5%" align="center"><?echo $obj-> member_no?></td>
				<td style="width: 5%" align="center"><?echo $obj-> member_name?></td>
				<td style="width: 5%" align="center"><?echo $obj-> sponsor_no?></td>
				<td style="width: 5%" align="center"><?echo $obj-> sponsor_name?></td>
				<td style="width: 5%" align="center"><?echo $obj-> ch_sponsor_no?></td>
				<td style="width: 5%" align="center"><?echo $obj-> ch_sponsor_name?></td>
				<td style="width: 5%" align="center"><?echo $obj-> sponsor_agree_yn?></td>
				
				<td style="width: 5%" align="center"><?echo $agree_s?></td>
			</tr>
			<?php 
                }
			?>
		</table>
		
	</body>
</html>

<?php 
$xls_contents = ob_get_contents();
ob_end_clean();

$xls_contents = iconv("UTF-8","EUC-KR",$xls_contents);

$str_title = iconv("UTF-8","EUC-KR","후원자 변경 신청");
$file_name = $str_title."-".date("Ymd").".zip";


$save_file_name = time()."_".rand(11111, 99999);
$save_xls_name = $save_file_name.".xls";
$save_zip_name = $save_file_name.".zip";

// 압축할 디렉토리 
$upload_dir = $_SERVER['DOCUMENT_ROOT']."/manager_utf8/upload_data";

$password = "1234";

$txtfile = @fopen($upload_dir."/".$save_xls_name, "w");
@fwrite($txtfile, $xls_contents);
@fclose($txtfile);
@chmod($upload_dir."/".$save_xls_name, 0777);

$zip = new ZipArchive; 
if( $zip->open($upload_dir."/".$save_zip_name, ZipArchive::CREATE)) {
	$zip->setPassword($password);
	$zip->addFile($upload_dir."/".$save_xls_name, $save_xls_name);	
	$zip->setEncryptionName($save_xls_name, ZipArchive::EM_AES_256);
	$zip->close();
	@chmod($upload_dir."/".$save_zip_name, 0777);
}


//파일다운로드
if(file_exists($upload_dir."/".$save_zip_name)){
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$file_name); 
	readfile($upload_dir."/".$save_zip_name);
	@unlink($upload_dir."/".$save_xls_name);
	@unlink($upload_dir."/".$save_zip_name);
}

#====================================================================
# DB Close
#====================================================================


	mysql_close($conn);
?>