<?
	include "../dbconn.inc";

	function makeCode ($parent)  { 

		$sqlstr = "SELECT * FROM tb_code where parent='".$parent."' order by code"; 

		$result = mysql_query($sqlstr);
		$total 	= mysql_affected_rows();
			
		for($i=0 ; $i< $total ; $i++)	{  	//  start 에서 scale 까지 만

			if($i< $total )	{ 								// 전체 자료 개수까지만 출력

				mysql_data_seek($result,$i);
				$row = mysql_fetch_array($result);		
				print("<option value='$row[code]' style='color:352000'>$row[name]</option>\n");

			}
		}
	}

	$str_bank = "국민은행";
?>
<HTML>
<head>
<script  language="javascript">
	function f_change() {
		document.frm.search_bank_code.value = document.frm.sel_account_bank.value.split("^")[0];
		document.frm.bank_account.value = document.frm.sel_account_bank.value.split("^")[1];
		
		alert(flag);

	}
	
</script>
</head>
<body>
<form name="frm" action="user_act.php" method="post">
이름 <input type="test" name="name" value="홍민성"><br>
주민번호 <input type="test" name="reg_jumin1" value="740605">
<input type="test" name="reg_jumin2" value="1068216"><br>
계좌번호<input type="test" name="account" value="61410201162081"><br>
<select name="sel_account_bank" onChange="f_change();">
	<option value="^">거래은행 선택</option>
	<? makeCode ("bank"); ?>
</select>
<input type="hidden" name="bank_account" value="">
<input type="hidden" name="search_bank_code" value="">
<input type="button" name="btn" value="전송" onClick="document.frm.submit();">
</form>
<script language="javascript">
//	alert(sel_account_bank.length="")
</script>
</body>
</HTML>
<?
	mysql_close($connect);
?>