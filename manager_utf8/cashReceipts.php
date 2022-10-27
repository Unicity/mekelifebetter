<?php session_start();?>
<?php
    include "./admin_session_check.inc";
    include "./inc/global_init.inc";
    include "../dbconn_utf8.inc";
    include "../AES.php";
    include "excel_modal.php";
    $r_status = str_quote_smart(trim($r_status));
    $idxfield = str_quote_smart(trim($idxfield));
    $qry_str = str_quote_smart(trim($qry_str));
            
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-Frame-Options" content="deny" />
		<title>현금 영수증 확인</title>
		<link rel="stylesheet" href="./inc/admin.css" type="text/css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	</head>
	<body bgcolor="#FFFFFF">
		<form name="frmSearch" method="post">
			<table cellspacing="0" cellpadding="0 " class="title" border="0" width="100%">
				<tr>
					<td align="left"><b>효성 현금 영수증 확인</b></td>
					<td>
							
				

					</td>
					<td align="right">
						<select name="idxfield" onchange="changeSelect()">
				
							<option value="detail">상세</option>
							<option value="date" selected>날짜</option>
						</select>
						<input type="text" name="memberNo" id="memberNo" placeholder="회원번호_주문번호" style="display:none;"/>
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
					<th width="6%" style="text-align: center;">회원번호_주문번호</th>
					<th width="6%" style="text-align: center;">상태</th>
					<th width="6%" style="text-align: center;">발급번호</th>
					<th width="6%" style="text-align: center;">금액</th>
					<th width="6%" style="text-align: center;">신청일자</th>
					<th width="6%" style="text-align: center;">영수증 승인 번호</th>
					<th width="6%" style="text-align: center;">발급목적</th>
					<th width="6%" style="text-align: center;">현금영수증 취소</th>
					
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
		//var baId = "";

		function changeSelect(){
			selVal = document.frmSearch.idxfield.value;

			if(selVal == 'date'){
				$('[name=qry_str]').show();
				$('[name=qry_str1]').show();
				$('[name=memberNo]').val("");
				$('[name=memberNo]').hide();
			}else{
				$('[name=qry_str]').hide();
				$('[name=qry_str1]').hide();
				$('[name=qry_str]').val("");
				$('[name=qry_str1]').val("");
				$('[name=memberNo]').show();
			}		
		}

		function searchData(){
			staDate = $('#qry_str').val();
			endDate = $('#qry_str1').val();
			var baId = $('#memberNo').val();


			if(baId !=''){
		

				$.ajax({
					'type':'GET',
					'headers':{'Authorization':'VAN hK1fmDEKCSrj55zn:xHJjExZxIYUDZSSO'},
					'url':  'https://api.hyosungcms.co.kr/v1/custs/unicity0/cash-receipts/'+baId,
					'success':function (result) {
						
						alert(JSON.stringify(result));
					}, 'error':function () {
						alert("내역이 없습니다");
					}
				});

			}else{
				
				if(staDate =="" ||endDate==""){
				alert("조회 기간을 입력 하세요");
				return false;
				}
				$.ajax({
					'type':'GET',
					'headers':{'Authorization':'VAN hK1fmDEKCSrj55zn:xHJjExZxIYUDZSSO'},
					'url':  'https://api.hyosungcms.co.kr/v1/custs/unicity0/cash-receipts?fromReceiptDate='+staDate+'&toReceiptDate='+endDate,
					'success':function (result) {
						

						for(i=0; i<result.cashReceipts.length;i++){
							
						

							var html =  "<tr>"
									//	+"<td align='center'><input type='checkbox' name='chkNum' value="+result.cashReceipts[i].cashReceiptId+"></td>"
										+"<td align='center'>"+result.cashReceipts[i].cashReceiptId+"</td>"
										+"<td align='center'>"+result.cashReceipts[i].status+"</td>"
										+"<td align='center'>"+phoneToString(result.cashReceipts[i].receiptNumber)+"</td>"
										+"<td align='right'>"+priceToString(result.cashReceipts[i].totalAmount)+"</td>"
										+"<td align='center'>"+result.cashReceipts[i].receiptDate+"</td>"
										+"<td align='center'>"+result.cashReceipts[i].receiptApprovalNumber+"</td>"
										+"<td align='center'>"+result.cashReceipts[i].receiptPurpose+"</td>"
										
										+"<td align='center'><a href=javascript:hyCencel("+result.cashReceipts[i].cashReceiptId+")>현금 영수증 취소("+result.cashReceipts[i].cashReceiptId+")</a></td>"
										+"</tr>"
							var trHtml = $( "tbody[name=additional]:last" );   
								console.log(trHtml.after(html)); 			


						}	

					}, 'error':function () {
						alert("내역이 없습니다.");
					}
				});
			}
		
		}
		
		function priceToString(price) {
    		return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
		}

		function phoneToString(price) {
    		return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '-');
		}

		function hyExcel(){
			var frm = document.frmSearch;
				frm.target = "";
				frm.action = "cashReceiptsExcel.php";
				frm.submit();
		}

		function hyCencel(val){
			alert(val);
			alert("아직 만들고 있어요");
			
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
