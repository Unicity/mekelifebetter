<?php 
session_start();
ini_set('memory_limit',-1);
ini_set('max_execution_time', 60);

	//include "admin_session_check.inc";
	//include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";
	include "./inc/common_function.php";

	$qry_str = $_POST['qry_str'];
	$qry_str1 = $_POST['qry_str1'];


	//ob_start();

	/*	
	$str_title = iconv("UTF-8","EUC-KR"," 효성 결과 확인");
	
	$file_name=$str_title."-".$qry_str."~".$qry_str1.".xls";
	header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
	header( "Content-Disposition: attachment; filename=$file_name" );
	header( "Content-Description: orion70kr@gmail.com" );
	*/


	//$sendUrl = 'https://api.hyosungcms.co.kr/v1/custs/unicity0/cash-receipts?fromReceiptDate='.$qry_str.'&toReceiptDate='.$qry_str1;
	$sendUrl = 'https://api.hyosungcms.co.kr/v1/payments/cms?fromPaymentDate='.$qry_str.'&toPaymentDate='.$qry_str1;

	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $sendUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:VAN hK1fmDEKCSrj55zn:xHJjExZxIYUDZSSO'));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $response = curl_exec($ch);
    $json_result = json_decode($response, true);

	$cnt = $json_result['payments'];

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
            
            	text-align:Left;
            	vertical-align:bottom;
            	mso-background-source:auto;
            	mso-pattern:auto;
            	white-space:nowrap;
            }
            .backGround{
                background-color: #D5D5D5;
            }

			.xlGeneralText {
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

		


</style>
	</head>
	<body>
		<table border="1">
			<tr align="center">
				<th class="backGround" width="5%" style="text-align: center;">출금결과</th>
				<th class="backGround" width="5%" style="text-align: center;">회원번호</th>
				<th class="backGround" width="5%" style="text-align: center;">회원이름</th>
				<th class="backGround" width="5%" style="text-align: center;">주문번호</th>
				<th class="backGround" width="5%" style="text-align: center;">결제일</th>
				<th class="backGround" width="5%" style="text-align: center;">금액</th>

			</tr>

			<?
				for($i = 0; $i < count($cnt); $i++) {
					$status=$cnt[$i]['status'];
					$memberId=$cnt[$i]['memberId'];
					$memberName=$cnt[$i]['memberName'];
					$paymentDate=$cnt[$i]['paymentDate'];
					$actualAmount=$cnt[$i]['actualAmount'];
					$transactionId=$cnt[$i]['transactionId'];
					
					list($memberNo,$orderNo) = explode("[_:]",	$transactionId);

			?>
			<tr>
	
				<td class="xlGeneral" style="width: 5%" align="center"><?echo $status ?></td>
				<td class="xlGeneral" style="width: 5%" align="center"><?echo $memberId ?></td>
				<td class="xlGeneral" style="width: 5%" align="center"><?echo $memberName?></td>
				<td class="xlGeneral" style="width: 5%" align="center"><?echo $orderNo?></td>
				<td class="xlGeneral" style="width: 5%" align="center"><?echo $paymentDate?></td>
				<td class="xlGeneral" style="width: 5%" align="center"><?echo $actualAmount?></td>
			</tr>
		<?}?>
		</table>
		
	</body>
</html>
<?php
$xls_contents = ob_get_contents();
ob_end_clean();

$xls_contents = iconv("UTF-8","EUC-KR",$xls_contents);

$str_title = iconv("UTF-8","EUC-KR"," 효성 결과 확인");
$file_name=$str_title."-".$qry_str."~".$qry_str1.".zip";


$save_file_name = time()."_".rand(11111, 99999);
$save_xls_name = $save_file_name.".xls";
$save_zip_name = $save_file_name.".zip";

// 압축할 디렉토리 
$upload_dir = $_SERVER['DOCUMENT_ROOT']."/manager_utf8/upload_data";

$password = $_SESSION['s_adm_id'];
if($password == "") $password = "unicity";

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
?>