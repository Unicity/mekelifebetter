<?php 
session_start();
ini_set("display_errors", 0);

include "../dbconn_utf8.inc";

include_once($_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/str_check.php"); 

$s_adm_id = str_quote_smart_session($s_adm_id);
$s_number = str_quote_smart_session($s_number);

if($s_adm_id){
	$report_flag="Y";
}else{
	if ($s_number) {
		if($id==$s_number){
			$report_flag="Y";
		}else{
			$report_flag="N";
		}
	}else{
		$report_flag="N";
	}
}

if($report_flag=="N"){
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-Frame-Options" content="deny" />
	<script language="javascript">
		alert("세션이 종료 되었거나 보안검사에 실패하였습니다.\n\n 다시 로그인 후 시도해주세요. \n\n 같은 현상이 반복될 시 고객센타로 문의 주시기 바랍니다.");
		self.close();
	</script>
	<?
	die;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-Frame-Options" content="deny" />
<meta http-equiv="X-UA-Compatible" content="IE=7" /> 
<title>거주자의 사업소득 원천징수영수증</title>
<style type="text/css">
body, table, td { margin : 0; padding : 0; }
body { font-size:14px; color:#000000; font-family: 바탕; }
#wrap { width: 640px; margin: 0 auto; position: relative; }
.table { position: absolute; top: 362px; left: 0px; width: 640px; }
.table tr td { height: 26px; }
.btn { padding:7px 10px; background:#000; color:#fff !important; }
</style>
</head>
<script type="text/javascript" src="inc/jquery.js"></script>
<script type="text/javascript">
function js_go(){
	if(!$('input:radio[name=name]').is(':checked')){
		alert("발행 대상자를 선택헤 주세요");
		return;
	}	
	location.href = "/manager_utf8/report_form3_period.php?id=<?=$id?>&sy=<?=$sy?>&sm=<?=$sm?>&ey=<?=$ey?>&em=<?=$em?>&dName=" +  encodeURIComponent($("input:radio[name=name]:checked").val());
}
</script>
<?php
	include "../AES.php";

	$id	= str_quote_smart(trim($id));
	$sy	= str_quote_smart(trim($sy));
	$sm = str_quote_smart(trim($sm));
	$ey = str_quote_smart(trim($ey));
	$em = str_quote_smart(trim($em));

	$sdate = $sy."-".sprintf('%02d', $sm);
	$edate = $ey."-".sprintf('%02d', $em);

	if($id == "" || $sy	== "" || $sm == "" || $ey == "" || $em == ""){
		echo "
		<script>
		alert('잘못된 요청입니다.');
		self.close();
		</script>";
		exit;
	}

	if(strtotime($sdate."-01") > strtotime($edate."-01")){
		echo "
		<script>
		alert('기간 설정이 잘못되었습니다.');
		self.close();
		</script>";
		exit;
	}

	//DistributorName이 다수인지 조회
	$sql_dict = "SELECT * FROM tb_Activityreport where ID='".$id."'  and regdate between '".$sdate."-01' and '".$edate."-31' group by DistributorName order by regdate asc";
	$result_dict = mysql_query($sql_dict) or die(mysql_error());
	$num_dict = mysql_num_rows($result_dict);
	if($num_dict <= 1){
		$dict_row = mysql_fetch_array($result_dict);
		?>
		<script>
		location.href = "/manager_utf8/report_form3_period.php?id=<?=$id?>&sy=<?=$sy?>&sm=<?=$sm?>&ey=<?=$ey?>&em=<?=$em?>&namechk=n&&dName=<?=urlencode($dict_row['DistributorName']);?>";
		</script>
		<?php
		exit;
	}
?>
<!-- <body onload="print();"> -->

<?php include "common_load.php" ?>

<div id="wrap">
	<table style="padding:10px; margin-top:20px">
		<tr>
			<td style="border-bottom:1px solid #777"><h3>원천징수 대상자가 1인 이상입니다. 발행하실 대상자를 선택하여 주세요</h3></td>
		</tr>
		<?php while($obj = mysql_fetch_object($result_dict)) { ?>
			<tr>					
				<td style="list-style:none; line-height:35px">
						<input type="radio" name="name" id="name" value="<?=$obj->DistributorName?>">
						<?=$obj->DistributorName?> : 
						<?=decrypt($key, $iv, $obj->JU_NO)?>
				</td>
			</tr>
		<?php } ?>
		<tr>
			<td style="text-align:center; border-top:1px solid #777">
				<br><a href="javascript:;" onclick="js_go()" class="btn">확인</a>
			</td>
		</tr>
	</table>

</div>
</body>
</html>
<?php mysql_close($connect); ?>