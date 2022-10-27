<?php session_start();?>

<?php
	
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../../AES.php";
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>기간설정</title>	
	<script type="text/javascript" src="/js/jquery-1.8.2.js"></script>
	<script>

 	function generateExcel() {
 		var startyear = document.myform.start_year.value;
 		var startmonth = document.myform.start_month.value;
 		var endyear = document.myform.end_year.value;
 		var endmonth = document.myform.end_month.value;

 		if (startyear != "" && startmonth != "" && endyear !="" && endmonth !=""){
 			
 			if (parseInt(endyear+endmonth) >= parseInt(startyear+startmonth)) {
 				//document.myform.submit();

				goExcelHistory('상담관리','상담이력관리',startyear + startmonth + "~" + endyear + endmonth);


 			} else {
 				alert('기간설정을 바르게 해주세요.');	
 			}
 		
 		} else{
 				alert('기간설정을 바르게 해주세요!');	
 		}

 	}

	function excelDown(){
		document.myform.submit();
	}
	</script>
</head>
<body>
	<form name="myform" method="post" action="counselMgt_excel.php">
		<div>
			기간설정 
			<select name="start_year">
				<option value="">년도</option>
				<option value="2018">2018</option>
				<option value="2019">2019</option>
				<option value="2020">2020</option>
				<option value="2021">2021</option>
				<option value="2022">2022</option>
			</select>
			<select name="start_month">
				<option value="">월</option>
				<option value="01">1월</option>
				<option value="02">2월</option>
				<option value="03">3월</option>
				<option value="04">4월</option>
				<option value="05">5월</option>
				<option value="06">6월</option>
				<option value="07">7월</option>
				<option value="08">8월</option>
				<option value="09">9월</option>
				<option value="10">10월</option>
				<option value="11">11월</option>
				<option value="12">12월</option>
			</select> 
			~
				<select name="end_year">
				<option value="">년도</option>
				<option value="2018">2018</option>
				<option value="2019">2019</option>
				<option value="2020">2020</option>
				<option value="2021">2021</option>
				<option value="2022">2022</option>
			</select>
			<select name="end_month">
				<option value="">월</option>
				<option value="01">1월</option>
				<option value="02">2월</option>
				<option value="03">3월</option>
				<option value="04">4월</option>
				<option value="05">5월</option>
				<option value="06">6월</option>
				<option value="07">7월</option>
				<option value="08">8월</option>
				<option value="09">9월</option>
				<option value="10">10월</option>
				<option value="11">11월</option>
				<option value="12">12월</option>
			</select>
			<input type="button" name="generate" value="Excel" onclick="generateExcel()">
		</div>
	</form>

	<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

	<? include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/excel_modal.php"; ?>
</body>
</html>