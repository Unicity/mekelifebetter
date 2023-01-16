<?
ini_set('memory_limit',-1);
ini_set('max_execution_time', 60);
?>
<?
	include "admin_session_check.inc";
	//include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";

	
	$str_title = iconv("UTF-8","EUC-KR","현금영수증발행_센터");
	
	$file_name=$str_title."-".date("Ymd").".xls";
	header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
	header( "Content-Disposition: attachment; filename=$file_name" );
	header( "Content-Description: orion70kr@gmail.com" );
	
	$s_status = str_quote_smart(trim($r_status));
	$qry_str = str_quote_smart(trim($qry_str));
	$con_order = str_quote_smart(trim($con_order));
	$idxfield = str_quote_smart(trim($idxfield));
	$con_sort = str_quote_smart(trim($con_sort));
	
	$que = $_POST['que'];
	
	$query2 = "select * from  tb_cashReceipts where 1 = 1 ".$que;
	$result2 = mysql_query($query2);
	
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
				<th class="backGround" width="5%" style="text-align: center;">매출일시</th>
				<th class="backGround" width="5%" style="text-align: center;">회원번호</th>
				<th class="backGround" width="5%" style="text-align: center;">회원명</th>
				<th class="backGround" width="5%" style="text-align: center;">주문번호</th>
				<th class="backGround" width="5%" style="text-align: center;">반품번호</th>
				<th class="backGround" width="5%" style="text-align: center;">금액</th>
				<th class="backGround" width="5%" style="text-align: center;">신분확인방법</th>
				<th class="backGround" width="5%" style="text-align: center;">신분확인번호</th>
				<th class="backGround" width="5%" style="text-align: center;">승인번호</th>
				<th class="backGround" width="5%" style="text-align: center;">취소승인번호</th>
				<th class="backGround" width="5%" style="text-align: center;">발행상태</th>
				<th class="backGround" width="5%" style="text-align: center;">취소사유</th>
				<th class="backGround" width="5%" style="text-align: center;">센터명</th>
			</tr>
			<?php 
			while($obj = mysql_fetch_object($result2)) { ?>
			<tr>
				<td style="width: 5%" align="center"><?echo $obj-> s_date?></td>
				<td style="width: 5%" align="center"><?echo $obj-> member_no?></td>
				<td style="width: 5%" align="center"><?echo $obj-> member_name?></td>
				<td style="width: 5%" align="center"><?echo $obj-> order_no?></td>
				<td style="width: 5%" align="center"><?echo $obj-> back_no?></td>
				<td style="width: 5%" align="center"><?echo $obj-> amount?></td>
				<td style="width: 5%" align="center"><?echo $obj-> check_text?></td>
				<td style="width: 5%" align="center"><?echo $obj-> check_num?></td>
				<td style="width: 5%" align="center"><?echo $obj-> approval_num?></td>
				<td style="width: 5%" align="center"><?echo $obj-> cancel_no?></td>
				<td style="width: 5%" align="center"><?echo $obj-> check_result?></td>
				<td style="width: 5%" align="center"><?echo $obj-> cancel_reason?></td>
				<td style="width: 5%" align="center"><?echo $obj-> center?></td>
				
				
			</tr>
			<?php 
                }
			?>
		</table>
		
	</body>
</html>

<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>