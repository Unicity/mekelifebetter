<!--
/********************************************************************************************************************************************
		NICE평가정보 Copyright(c) KOREA INFOMATION SERVICE INC. ALL RIGHTS RESERVED
		
		서비스명 : 간편실명 서비스 
		페이지명 : 간편실명 서비스 입력 페이지
*********************************************************************************************************************************************/
-->

<html>
<head>
	<meta charset="utf-8">
	<title>NICE평가정보 간편실명 서비스</title>
	
	<style>
		input{width:68;font-size:9pt;height:18}
	</style>

	<script>
		function fnSubmit()
		{
			if (document.form_sname.jumin.value.length =='' || document.form_sname.jumin.value.length < 6)
			{
				alert('생년월일을 입력해주세요.');
				document.form_sname.jumin.focus();
				return;
			}
			
			if (document.form_sname.name.value == '' )
			{
				alert('성명을 입력해주세요.');
				document.form_sname.name.focus();
				return;
			}
			
			document.form_sname.submit();
		}
	</script>
</head>	
<body>
	<!-- 간편실명 서비스 인증요청 form -->
	<form name="form_sname" method="post" action="./nc/nc_p.php">
			<!-- 생년월일 (yymmdd OR yyyymmdd) -->
			생년월일 (6자리 or 8자리) : 
			<input type="text" name="jumin" maxlength="8"><br>
			<!-- 성별코드 -->
			성별 : 
			<input type="radio" name="gender" value="0" checked>여성   
			<input type="radio" name="gender" value="1" >남성<br>
			<!-- 성명 -->
			성명 : 
			<input type="text" name="name" value="" maxlength="50"><br><br>
			<!-- 확인 버튼 -->	
			<input type="button" name="ok" value=" 확  인 " onclick="JavaScript:fnSubmit();">
	</form>
</body>
</html>

