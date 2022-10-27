<? 
$proceed=$HTTP_POST_VARS["proceed"];
$xid=$HTTP_POST_VARS["xid"];
$eci=$HTTP_POST_VARS["eci"];
$cavv=$HTTP_POST_VARS["cavv"];
$errCode=$HTTP_POST_VARS["errCode"];
?>
<html>
	<body onload="return_proceed();">
	<script language="javascript">
	<!--
		function return_proceed()
		{
			parent.paramSet("<? echo $xid; ?>","<? echo $eci; ?>","<? echo $cavv; ?>");
			parent.proceed('<? echo strtoupper($proceed); ?>');
		}
	-->
	</script>
	</body>
</html>

