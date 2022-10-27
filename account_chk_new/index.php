<?php 
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
include "../dbconn_utf8.inc";

	function makeCode ($parent)  { 

		$sqlstr = "SELECT * FROM tb_code where parent='".$parent."' order by name"; 

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

	//$str_bank = "국민은행";
?>
<HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="/register/js/jquery-1.8.0.min.js"></script>
<script  language="javascript">
	function f_change() {
		document.frm.search_bank_code.value = document.frm.sel_account_bank.value.split("^")[0];
		document.frm.bank_account.value = document.frm.sel_account_bank.value.split("^")[1];
		
		//alert(flag);

	}

	function showInput(tp){
		if(tp == 'line') $('.line').show();
		else  $('.line').hide();
	}
	
</script>
</head>
<body>
<h2>계좌조회</h2>
<form name="frm" action="user_act.php" method="post">
<input type="radio" name="type" value="line" checked onclick="showInput('line');">전용선방식 &nbsp;
<input type="radio" name="type" value="api" onclick="showInput('api');">웹(API)방식<br>
<div class="line">이름 <input type="text" name="user_name" value=""></div>
<div class="line">
	주민번호 앞자리 
	<input type="text" name="reg_jumin1" value="">
	<input type="hidden" name="reg_jumin2" value="">
</div>
<div>
	계좌번호<input type="text" name="account" value="">
	<select name="sel_account_bank" onChange="f_change();">
		<option value="^">거래은행 선택</option>
		<? makeCode ("bank3"); ?>
	</select>
	<input type="hidden" name="bank_account" value="">
	<input type="hidden" name="search_bank_code" value="">
	<input type="button" name="btn" value="전송" onClick="document.frm.submit();">
</div>
</form>
<script language="javascript">
//	alert(sel_account_bank.length="")
</script>
</body>
</HTML>
<?
	mysql_close($connect);
?>
<?
	$now=time(); 
	$now_date_time = date("YmdHi",$now); 

	if ($now_date_time >= 201110060900) {
		//echo "0";
	} else {
		//echo "1";
	}
?>
<br>
