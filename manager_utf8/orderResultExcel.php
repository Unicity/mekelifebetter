<?
	//include "admin_session_check.inc";
	//include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";

	$qry_str = $_POST['qry_str'];
	$qry_str1 = $_POST['qry_str1'];

	
	$str_title = iconv("UTF-8","EUC-KR"," 효성 결과 확인");
	
	$file_name=$str_title."-".$qry_str."~".$qry_str1.".xls";
	header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
	header( "Content-Disposition: attachment; filename=$file_name" );
	header( "Content-Description: orion70kr@gmail.com" );


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

					
					list($memberNo,$orderNo)=split("[_:]",	$transactionId);
					
					

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
