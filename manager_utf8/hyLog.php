<?php session_start();?>
<?php
    include "./admin_session_check.inc";
    include "./inc/global_init.inc";
    include "../dbconn_utf8.inc";
    include "../AES.php";
    include "excel_modal.php";
	//include "./hycms/config_database.php";
    $r_status = str_quote_smart(trim($r_status));
    $idxfield = str_quote_smart(trim($idxfield));
    $qry_str = str_quote_smart(trim($qry_str));
	$qry_str1 = str_quote_smart(trim($qry_str1));


/**autoship DB연결 */
	$db_host = '54.180.152.178';
	$db_user = 'autoship';
	$db_passwd = 'inxide1!!';
	$db_name = 'autoship';

	$conn = mysql_connect($db_host,$db_user,$db_passwd) or die ("데이터베이스 연결에 실패!"); 
	mysql_select_db($db_name, $conn); // DB 선택 

	//$query = "select count(*) from smart_fms_log where reg_date between 20220510 and 20220530 and send_url in ('https://api.hyosungcms.co.kr/v1/payments/cms','https://api.hyosungcms.co.kr/v1/custs/unicity0/cash-receipts')";
	$query = "select count(*) from smart_fms_log where reg_date between ".$qry_str." and ".$qry_str1." and send_url in ('https://api.hyosungcms.co.kr/v1/payments/cms','https://api.hyosungcms.co.kr/v1/custs/unicity0/cash-receipts')";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    $TotalArticle = $row[0];
	
	//$query2 = "select * from smart_fms_log where reg_date between 20220510 and 20220530 and send_url in ('https://api.hyosungcms.co.kr/v1/payments/cms','https://api.hyosungcms.co.kr/v1/custs/unicity0/cash-receipts')";
	$query2 = "select * from smart_fms_log where reg_date between ".$qry_str." and ".$qry_str1." and send_url in ('https://api.hyosungcms.co.kr/v1/payments/cms','https://api.hyosungcms.co.kr/v1/custs/unicity0/cash-receipts')";

	$result2 = mysql_query($query2);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-Frame-Options" content="deny" />
		<title>효성 로그 확인</title>
		<link rel="stylesheet" href="./inc/admin.css" type="text/css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	</head>
	<body bgcolor="#FFFFFF">
		<form name="frmSearch" method="post">
			<table cellspacing="0" cellpadding="0 " class="title" border="0" width="100%">
				<tr>
					<td align="left"><b>효성 오토쉽 로그 확인</b></td>
					<td align="right">
						<input type="text" name="qry_str" id="qry_str" maxlength="8" value="<?php echo $qry_str?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">~
						<input type="text" name="qry_str1"id="qry_str1" maxlength="8" value="<?php echo $qry_str1?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
						<input type="button" value="조회" onclick="onSearch();" >
						<!--<input type="button" value="엑셀 다운로드" onclick="hyExcel();" >-->
					</td>
				</tr>
			</table>
			<table cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
				<tr>
					<th width="6%" style="text-align: center;">회원번호</th>
					<th width="6%" style="text-align: center;">회원번호_주문번호</th>
					<th width="6%" style="text-align: center;">날짜</th>
					<th width="6%" style="text-align: center;">구분</th>
				</tr>
				<?php
					if ($TotalArticle) {
						while($obj = mysql_fetch_object($result2)) {
							$arr = json_decode($obj->return_json,true);
							if($obj->send_url=='https://api.hyosungcms.co.kr/v1/custs/unicity0/cash-receipts'){
								$method = '실시간';
								$orderNum = $arr["cashReceipt"]["cashReceiptId"];
								list($memberNo,$orderNo)=split("[_:]",	$orderNum);
							}else{
								$method = 'CMS';
								$orderNum = $arr["payment"]["transactionId"];
								list($memberNo,$orderNo)=split("[_:]",	$orderNum);
							}
				?>
				<tr>
					<td style="width: 5%" align="center"><?echo $memberNo?></td>
					<td style="width: 5%" align="center"><?echo $orderNo?></td>
					<td style="width: 5%" align="center"><?echo $obj->reg_date?></td>
					<td style="width: 5%" align="center"><?echo $method?></td>

				</tr>
				<?php }
					}
				?>
			</table>
		
			
		</form>
	</body>
	<script>
		var staDate ="";
		var endDate = "";

		function onSearch(){
				
				
				//document.frmSearch.page.value="1";
				document.frmSearch.action="hyLog.php";
				document.frmSearch.submit();
			}
		
		function priceToString(price) {
    		return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
		}

		function phoneToString(price) {
    		return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '-');
		}

	

	</script>
</html>
