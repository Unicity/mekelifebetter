<?php session_start();?>
<?php
    include "./admin_session_check.inc";
    include "./inc/global_init.inc";
    include "../dbconn_utf8.inc";
    include "../AES.php";
	include "./inc/common_function.php";
    include "excel_modal.php";

    $r_status = str_quote_smart(trim($r_status));
    $idxfield = str_quote_smart(trim($idxfield));
    $qry_str = str_quote_smart(trim($qry_str));
            
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-Frame-Options" content="deny" />
		<title>현금 결과 확인</title>
		<link rel="stylesheet" href="./inc/admin.css" type="text/css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	</head>
	<body bgcolor="#FFFFFF">
	
<?php include "common_load.php" ?>

		<form name="frmSearch" method="post">
			<table cellspacing="0" cellpadding="0 " class="title" border="0" width="100%">
				<tr>
					<td align="left"><b>효성 결과 확인</b></td>
					<td>
							
				

					</td>
					<td align="right">
						<input type="text" name="qry_str" id="qry_str" maxlength="8" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
						<input type="text" name="qry_str1"id="qry_str1" maxlength="8" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
						<input type="button" value="조회" onclick="searchData();" >
						<input type="button" value="엑셀 다운로드" onclick="hyExcel();" >
				
					</td>
				</tr>
			</table>
			<table cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
				<tr>
					<!--<th width="2%" style="text-align: center;"><input type="checkbox" id="chckHead" onchange="toggleCheckbox(this);" /></td>-->
					<th width="6%" style="text-align: center;">출금결과</th>
					<th width="6%" style="text-align: center;">회원번호_주문번호</th>
					<th width="6%" style="text-align: center;">이름</th>
					<th width="6%" style="text-align: center;">결제일</th>
					<th width="6%" style="text-align: center;">결제금액</th>
				</tr>
				<tr>
					<tbody name="additional"></tbody>
				</tr>
			</table>
		
			
		</form>
	</body>
	<script>
		var staDate ="";
		var endDate = "";

		function searchData(){
			staDate = $('#qry_str').val();
			endDate = $('#qry_str1').val();
	
				if(staDate =="" ||endDate==""){
				alert("조회 기간을 입력 하세요");
				return false;
				}
				$.ajax({
					'type':'GET',
					'headers':{'Authorization':'VAN hK1fmDEKCSrj55zn:xHJjExZxIYUDZSSO'},
					//'url':  'https://api.hyosungcms.co.kr/v1/custs/unicity0/cash-receipts?fromReceiptDate='+staDate+'&toReceiptDate='+endDate,
					'url':  'https://api.hyosungcms.co.kr/v1/payments/cms?fromPaymentDate='+staDate+'&toPaymentDate='+endDate,
					'success':function (result) {
						console.log(">>"+JSON.stringify(result));

						for(i=0; i<result.payments.length;i++){
							
							

							var html =  "<tr>"
									//	+"<td align='center'><input type='checkbox' name='chkNum' value="+result.cashReceipts[i].cashReceiptId+"></td>"
										+"<td align='center'>"+result.payments[i].status+"</td>"
										+"<td align='center'>"+result.payments[i].transactionId+"</td>"
										+"<td align='center'>"+result.payments[i].memberName+"</td>"
										+"<td align='center'>"+result.payments[i].paymentDate+"</td>"
										+"<td align='center'>"+result.payments[i].actualAmount+"</td>"
										+"</tr>"
							var trHtml = $( "tbody[name=additional]:last" );   
								console.log(trHtml.after(html)); 			


						}	

					}, 'error':function () {
						alert("내역이 없습니다.");
					}
				});
			
		
		}
		
		function priceToString(price) {
    		return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
		}

		function phoneToString(price) {
    		return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '-');
		}

		function hyExcel(){
			staDate = $('#qry_str').val();
			endDate = $('#qry_str1').val();
			if(staDate =="" ||endDate==""){
				alert("조회 기간을 입력 하세요");
				return false;
				}
			var frm = document.frmSearch;
				frm.target = "";
				frm.action = "orderResultExcel.php";
				frm.submit();
		}

		function toggleCheckbox(element){
		var chkboxes = document.getElementsByName("chkNum");
 	
 		for(var i=0; i<chkboxes.length; i++){
 			var obj = chkboxes[i];
 			obj.checked = element.checked;
 		}
 	}
	</script>

	<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</html>
