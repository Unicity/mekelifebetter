<? 
set_time_limit(0); 
session_start();

include "admin_session_check.inc";
include "./inc/global_init.inc";
include "../dbconn_utf8.inc";
include "../AES.php";
include "./inc/common_function.php";
include "./new_api_function.php";

$result = mysql_query("select * from tb_batch_account where grp = '".$_GET['grp']."'") or die(mysql_error());	
$num = mysql_num_rows($result);

if($num < 1){
	echo "<script>
	alert('발송대상이 없습니다');
	location.replace('batch_account.php');
	</script>";
	exit;
}
while($row=mysql_fetch_array($result)) {

	$rs['tbl'] = $row['tbl'];
	$rs['name'] = $row['name'];
	$rs['number'] = $row['number'];
	$rs['bank'] = $row['account_bank'];
	$rs['bank_code'] = $row['bank_code'];
	$rs['account'] = 	decrypt($key, $iv, $row['account']);
	$rs['regDate'] = $row['regDate'];
	
	$data[] = $rs;
}
?>
<html>
<head>
<title><?echo $g_site_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- <meta http-equiv="X-Frame-Options" content="deny" /> -->
<link rel="stylesheet" href="inc/admin.css" type="text/css">
<script type="text/javascript" src="inc/jquery.js"></script>
<script language="javascript">
function js_excel() {
	var grp = '<?=$_GET['grp']?>';
	if(grp == "" || grp == "batch_account_upload.php"){
		alert("처리할 데이터가 없습니다.");
		location.replace('');
	}else{
		if(confirm("API일괄발송 처리를 하시겠습니까?")){
			$('#frm').submit();
		}
	}
}

var dt = '<?=json_encode($data)?>';
var data = JSON.parse(dt);
var total = data.length;

//console.log(data);
$(document).ready(function() {

	$('#total').html(total);

	for(i = 0; i < total; i++){

		if(data[i].number == "" || data[i].bank_code == "" || data[i].account == ""){

			$('#monitor').append("<div>"+data[i].number+" : 자료조회 실패로 미전송</div>");
			$('#monitor').scrollTop($('#monitor')[0].scrollHeight);

		}else{
			$.ajax({
				type: 'post',
				url: 'batch_account_run.php',
				data: {'name': data[i].name, 'number': data[i].number, 'bank': data[i].bank, 'bank_code': data[i].bank_code, 'account' : data[i].account, 'tbl': data[i].tbl},
				//async: false,
				success: function(msg){				
					var n = ($('#ncnt').html() *1) + 1;
					$('#ncnt').html(n);

					$('#monitor').append("<div>"+msg+"</div>");
					$('#monitor').scrollTop($('#monitor')[0].scrollHeight);
					//console.log(msg);
					
					if(n == total){
						$('#loading').hide();
						$('#btnGroup').show();
						alert("완료되었습니다");
					}
				},
				error: function( jqXHR, textStatus, errorThrown ) { 
					//alert( textStatus + ", " + errorThrown ); 				
				} 
			});		   
		}
	}

});
</script>
<STYLE type='text/css'>
.btn { padding:10px 20px; background:#000; color:#fff !important; }
</STYLE>
</head>
<BODY bgcolor="#FFFFFF">

<?php include "common_load.php" ?>

<div style="padding:20px;  position:relative;">

	<img src="images/viewLoading.gif" id="loading" style="position:absolute; top:50%; left:45%" />

	<TABLE cellspacing="0" cellpadding="10" class="TITLE" style="width:100%;">
	<TR>
		<TD align="left">
			<B>동의사항 일괄 전송</B>
			&nbsp;&nbsp;
			<B><span id="ncnt">0</span> / <span id="total"><?=$row['total']?></span></B>
			
		</TD>
		<TD align="right" width="600" align="center" bgcolor=silver>
			
		</TD>
	</TR>
	</TABLE>
	<br>
	<div name="monitor" id="monitor" style="width:94%; height:500px; border:1px solid #cdcdcd; padding:10px; overflow-y:scroll;">
		
		<h3>동의사항 전송 시작 - 시간이 소요됩니다. 기다려 주세요</h3>
	</div>

</div>

<div id="btnGroup" style="width:94%;; text-align:center; padding:10px 0px 30px; display:none"><a href="batch_account.php" class="btn">확인</a></div>

</body>
</html>
<?
mysql_close($connect);
?>