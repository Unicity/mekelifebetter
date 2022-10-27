<?
	$proceed         = $_POST["proceed"];
	$isptype         = $_POST["isptype"];
	$kvp_cardcode    = $_POST["kvp_cardcode"];
	$kvp_quota       = $_POST["kvp_quota"];
	$kvp_noint       = $_POST["kvp_noint"];
	$kvp_card_prefix = $_POST["kvp_card_prefix"] ;
	$kb_app_otp      = $_POST["kb_app_otp"];
?>
<html>
<body>
<script language="javascript">
<!--
	function return_proceed()
	{             
		parent.kbparamSet('<?echo($proceed)?>','<?echo($isptype)?>','<?echo($kvp_cardcode)?>','<?echo($kvp_quota)?>','<?echo($kvp_noint)?>','<?echo($kvp_card_prefix)?>','<?echo($kb_app_otp)?>');
		location.href="./blank.html";
	}
	return_proceed();

-->
</script>
</body>
</html>