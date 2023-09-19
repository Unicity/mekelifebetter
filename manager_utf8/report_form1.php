<?session_start();
	ini_set("display_errors", 0);
	include "../dbconn_utf8.inc";
	include "../AES.php";

	include_once($_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/str_check.php"); 
	$s_adm_id = str_quote_smart_session($s_adm_id);
	$s_number = str_quote_smart_session($s_number);

	if(isset($_SESSION["s_adm_id"])){
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
<title>거주자의 기타소득 원천징수영수증</title>
<style type="text/css">
body, table, td { margin : 0; padding : 0; }
body { font-size:11px; color:#000000; font-family: 바탕; }
#wrap { width: 640px; margin: 0 auto; position: relative; }
.year { position: absolute; top: 40px; left: 36px; width: 44px; height: 21px; text-align: center; padding-top: 3px; letter-spacing: 2px; }
.name { position: absolute; top: 149px; left: 169px; width: 156px; height: 19px; text-align: center; padding-top: 3px; letter-spacing: 10px; }
.num { position: absolute; top: 149px; left: 444px; width: 195px; height: 19px; text-align: center; padding-top: 3px; letter-spacing: 2px; }
.add { position: absolute; top: 169px; left: 169px; width: 468px; height: 19px; text-align: center; padding-top: 3px; letter-spacing: 1px; }
.table { position: absolute; top: 288px; left: 0px; width: 640px; }
.table tr td { height: 25px; }
.ac { text-align:center; }
.ar { text-align:right; padding-right: 8px; }
.date { position: absolute; top: 627px; left: 395px; width: 130px; height: 18px; text-align: right; letter-spacing: 2px; }
</style>
</head>
<?


	$id						= str_quote_smart(trim($id));
	$VolumePeriod = str_quote_smart(trim($VolumePeriod));
	$e_date				= str_quote_smart(trim($e_date));
	$print_vel		= str_quote_smart(trim($print_vel));


	$y=date("y"); 
	$m=date("m"); 
	$d=date("d"); 
	

	if($JU_NO==""){
		$sql_jumin="select JU_NO FROM tb_Activityreport  where ID='$id' order by regdate desc limit 0,1";
	
		$result_jumin = mysql_query($sql_jumin,$connect);
		$row_jumin = mysql_fetch_array($result_jumin);
		$JU_NO = $row_jumin[0];
	}

	$query = "SELECT DistributorName,JU_NO,companynum,address,companyaddr FROM tb_Activityreport  where ID='$id' and JU_NO='$JU_NO' order by regdate desc limit 0,1";

	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$DistributorName = $row[0];
	$jumin = $row[1];
	$companynum = $row[2];
	$address = $row[3];
	$companyaddr = $row[4];

	$jumin = decrypt($key, $iv, $jumin);
	
	$e_year = explode("-",$e_date);
	$yy=$e_year[0];
?>
<body onload="print();">

<?php include "common_load.php" ?>

<div id="wrap">
<table width="640" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="640" height="831" background="images/form.gif">
		<div class="year"><?=$yy?></div>
		<div class="name"><?=$DistributorName?></div>
		<div class="num"><?if($jumin){echo substr($jumin,0,6)."-".substr($jumin,6,7);}else{echo $companynum;}?></div>
		<div class="add"><?if($jumin){echo $address;}else{echo $companyaddr;}?></div>
		<div class="table">
			<table width="640" border="0" cellspacing="0" cellpadding="0">
				<colgroup>
					<col width="30" />
					<col width="20" />
					<col width="20" />
					<col width="20" />
					<col width="20" />
					<col width="60" />
					<col width="60" />
					<col width="60" />
					<col width="20" />
				 	<col width="70" />
					<col width="64" />
					<col width="60" />
					<col width="70" />
					<col width="70" />
				</colgroup>
				<?
					$query = "SELECT * FROM tb_Activityreport_MBL where ID='$id' and regdate > '$s_date' and regdate <= '$e_date' order by regdate asc";
					$result = mysql_query($query,$connect);
					$i=1;
					$tot=0;//지금총액 계
					$tot_a=0;//필요경비 계
					$tot_b=0;//소득금액 계
					$tot_c=0;//소득세 계
					$tot_d=0;//주민세 계
					$tot_e=0;// 계
					while($obj = mysql_fetch_object($result)) {
						$regdate=$obj->regdate;
						$a_date = explode("-",$regdate);
						$yyyy=$a_date[0];
						$mmmm=$a_date[1];
						$dddd=$a_date[2];
						$yy2=substr($a_date[0],2,2);
				?>
				<tr>
					<td class="ac"><?=$yyyy?></td>
					<td class="ac"><?=$mmmm?></td>
					<td class="ac"><?=$dddd?></td>
					<td class="ac"><?=$yy2?></td>
					<td class="ac"><?=$mmmm?></td>
					<td class="ar"><?=Number_Format($obj->MonthNetEarning)?></td>
					<td class="ar"><?=Number_Format($obj->MonthNetEarning * 0.8)?></td>
					<td class="ar"><?=Number_Format(($obj->MonthNetEarning-($obj->MonthNetEarning * 0.8)))?></td>
					<td class="ac">4%</td> 
					<td class="ar"><?=Number_Format(str_replace("-","",$obj->IncomeTax))?></td>
					<td class="ar">&nbsp;</td>
					<td class="ar"><?=Number_Format(str_replace("-","",$obj->ResidentTax))?></td>
					<td class="ar">&nbsp;</td>
					<td class="ar"><?=Number_Format(str_replace("-","",($obj->IncomeTax + $obj->ResidentTax)))?></td>
				</tr>
				<?
					$tot=$tot+$obj->MonthNetEarning;
					$tot_a=$tot_a + ($obj->MonthNetEarning * 0.8);
					$tot_b=$tot_b + ($obj->MonthNetEarning-($obj->MonthNetEarning * 0.8));
					$tot_c=$tot_c + $obj->IncomeTax;
					$tot_d=$tot_d + $obj->ResidentTax;
					$tot_e=$tot_e + ($obj->IncomeTax + $obj->ResidentTax);
					$i++;
					}
					$k=12- $i;
					for($j=1;$j < $k;$j++){
					?>
				<tr>
					<td class="ac">&nbsp;</td>
					<td class="ac">&nbsp;</td>
					<td class="ac">&nbsp;</td>
					<td class="ac">&nbsp;</td>
					<td class="ac">&nbsp;</td>
					<td class="ar">&nbsp;</td>
					<td class="ar">&nbsp;</td>
					<td class="ar">&nbsp;</td>
					<td class="ac">&nbsp;</td>
					<td class="ar">&nbsp;</td>
					<td class="ar">&nbsp;</td>
					<td class="ar">&nbsp;</td>
					<td class="ar">&nbsp;</td>
					<td class="ar">&nbsp;</td>
				</tr>
				<?}
					?>
				<!--tr>
					<td class="ac" colspan="5">합 계</td>
					<td class="ar"><?=Number_Format($tot)?></td>
					<td class="ar"><?=Number_Format($tot_a)?></td>
					<td class="ar"><?=Number_Format($tot_b)?></td>
					<td class="ar"></td>
					<td class="ar"><?=Number_Format($tot_c)?></td>
					<td class="ar">-</td>
					<td class="ar"><?=Number_Format($tot_d)?></td>
					<td class="ar">-</td>
					<td class="ar"><?=Number_Format($tot_e)?></td>
				</tr-->
			</table>
		</div>
		<div class="date">20<?=$y?>년 <?=$m?>월 <?=$d?>일</div>
		</td>
	</tr>
</table>
</div>
</body>
</html>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

<?
if($print_vel=="Y"){
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	print();
//-->
</SCRIPT>
<?}?>

<?
mysql_close($connect);
?>;
?>