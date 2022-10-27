<?
	$proceed = $_POST["proceed"];
	$xid	 = $_POST["xid"];
	$eci	 = $_POST["eci"];
	$cavv	 = $_POST["cavv"];
	$cardno	 = $_POST["cardno"];
	$errCode = $_POST["errCode"];
	$isp_yn  = $_POST["isp_yn"];
	$tx_key	 = $_GET["tx_key"];
?>
<html>
<body>
<script language="javascript">
<!--
		function return_proceed()
		{             
			if (typeof(top.opener) == "undefined" || typeof(top.opener.paramSet) == "undefined" )
 			{       
 				if ("undefined" != typeof(window.localStorage))
				{
					var tnm = '<?echo($tx_key)?>';
					if (tnm != null && tnm.length>5)
					{
						var tval = new Array('<?echo($proceed)?>','<?echo($xid)?>','<?echo($eci)?>','<?echo($cavv)?>','<?echo($cardno)?>','<?echo($errCode)?>','<?echo($isp_yn)?>');
						localStorage.setItem("ksmpi_" + tnm ,tval.join('|'));
						setTimeout("self.close()",2000);
					}
				}else{
					alert("도구->인터넷옵션->보안->신뢰할 수 있는 사이트->보호모드 사용체크 후 다시 결제 진행해주세요");
				}
 			}else{
 				opener.paramSet('<?echo($proceed)?>|<?echo($xid)?>|<?echo($eci)?>|<?echo($cavv)?>|<?echo($cardno)?>|<?echo($errCode)?>|<?echo($isp_yn)?>');
				setTimeout("self.close()",2000);
 			}
		}
		return_proceed();
-->
</script>
</body>
</html>