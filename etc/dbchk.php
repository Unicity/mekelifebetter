<!doctype html>
<html lang="ko">
<html>
<head>
<meta charset="utf-8">
<script language="javascript">
function send(){
	if(document.myform.checkvalue.value==""){
		alert("입력값이 없습니다");
		document.myform.checkvalue.focus();
		return false;
	}
	document.myform2.checkvalue.value = encodeURIComponent(document.myform.checkvalue.value);
	document.myform2.submit();
	return false;
}
</script>
</head>
	<body>
		<form name="myform" method="post" action="./dupcheck.php" onsubmit="return send()">
			<label for="checkvalue">yymmddName</label>
			<input type="text" id="checkvalue" name="checkvalue">
			<input type="submit" value="Submit">
		</form>

		<div style="display:none">
		<form name="myform2" method="post" action="./dupcheck.php">
			<input type="text" id="checkvalue" name="checkvalue">
		</form>
		</div>

	</body>
</html>