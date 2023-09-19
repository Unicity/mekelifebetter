
<?php
    include "../dbconn_utf8.inc";     
	//include "./lodingBar.html";     
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>마감관리</title>
		<link rel="stylesheet" href="./inc/admin.css" type="text/css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src =" https://cdnjs.cloudflare.com/ajax/libs/fetch-jsonp/1.0.6/fetch-jsonp.min.js " > </script>
	</head>
	<body bgcolor="#FFFFFF">
		<form name="frmSearch" id="trans" method="post" action="sales_close_search.php">	
			<table cellspacing="0" cellpadding="10" class="title">
				<tr>
					<td align="left" style='width:80px;'><b>마감관리</b></td>
					<td align="left" bgcolor=silver>
						날짜
						<input type="text" name="sDate" id="sDate" value="">
						<input type="submit" value="submit" onclick="goSubmit(this)">
						<input type="button" value="엑셀 다운로드" onClick="excelDown();">
					</td>
				</tr>
			</table>   
		</form>
	</body>
	<script>
		$(document).ready(function() {

			$('#loading').hide();
			$('#trans').submit(function(){
				$('#loading').show();
				return true;
				});
		});

		function goSubmit(val){
			document.frmSearch.target = "";
			document.frmSearch.action = "./sales_close_search.php";
			document.frmSearch.submit();
		}

		function excelDown(){
			if (confirm("데이터 조회 시 약 1분 정도 시간이 소요 됩니다.저장 하시겠습니까?")) {
				document.frmSearch.target = "";
				document.frmSearch.action = "./sales_close_excel.php";
				document.frmSearch.submit();
			}
		}
	

	</script>

</html>
