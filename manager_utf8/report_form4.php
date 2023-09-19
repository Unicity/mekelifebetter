<?php 
session_start();
include "../dbconn_utf8.inc";

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
<title>Bonus Summary</title>
<style type="text/css">
body, table, td { margin : 0; padding : 0; }
body { font-size:12px; color:#3e6682; font-family: verdana, 돋움; }
#wrap { background: transparent url(images/bonus-summary.jpg) no-repeat; width: 640px; height: 860px; margin: 0 auto; }
.table { float: left; margin-top: 115px; margin-left: 58px; width: 527px; display: inline;}
.table tr td { height: 22px; }
#personal { float: left; width: 640px; text-align: center; padding-top: 20px; }
.ar { text-align:right; padding-right: 10px; }
.al { text-align:left; padding-left: 10px; }
.bold { font-size: 14px; font-weight: bold; }
.sp15 { height: 15px; width:1px; font-size: 0; line-height: 0; clear:both; }
.txt01 { color:#3e6682; }
.txt02 { color:#666666; }
.txt03 { color:#ffffff; }
.txt04 { color:#765912; }
</style>
</head>
<?

	
	$query = "SELECT * FROM tb_Activityreport where substring(regdate,1,7)='$regdate' and id='$id'";

	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	
	$DistributorName = $row[1];
	$PaidRank= $row[2];
	$PV08= $row[3];
	$TV08= $row[4];
	$BD= $row[5];
	$PR= $row[6];
	$TD= $row[7];
	$OD= $row[8];
	$CarBonus= $row[9];
	$PCAB= $row[10];
	$PGO= $row[11];
	$Adjustment= $row[12];
	$MonthNetEarning= $row[13];
	$IncomeTax= $row[14];
	$ResidentTax= $row[15];
	$soa= $row[16];
	$AutoshipAdj= $row[17];
	$MonthNetPayment = $row[18];
	$address = $row[20];
	$VolumePeriod = $row[24];

	$query2 = "SELECT MLB FROM tb_Activityreport_MBL where substring(regdate,1,7)='$regdate' and id='$id'";
	$result2 = mysql_query($query2,$connect);
	$row2 = mysql_fetch_array($result2);
	$MLB =$row2[0];
?>
<body onload="print();">

<?php include "common_load.php" ?>

<div id="wrap">
	<div class="table">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<colgroup>
				<col width="80%" />
				<col width="20%" />
			</colgroup>
			<tr>
				<td class="al bold">Franchise Owner Infomation</td>
				<td></td>
			</tr>
			<tr>
				<td class="al">Volume Period</td>
				<td class="ar"><?=$VolumePeriod?></td>
			</tr>
			<tr>
				<td class="al">ID</td>
				<td class="ar txt02"><?=$id?></td>
			</tr>
			<tr>
				<td class="al">Name</td>
				<td class="ar txt02"><?=$DistributorName?></td>
			</tr>
			<tr>
				<td class="al">Pald Rank (지급 랭크)</td>
				<td class="ar txt02"><?=$PaidRank?></td>
			</tr>
			<tr>
				<td class="al">Personal Volume (개인 매출)</td>
				<td class="ar txt02"><?if($PV08){echo Number_Format($PV08);}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al">Team Volume (팀 매출)</td>
				<td class="ar txt02"><?if($TV08){echo Number_Format($TV08);}else{echo "0";}?></td>
			</tr>
		</table>
		<div class="sp15"></div>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<colgroup>
				<col width="80%" />
				<col width="20%" />
			</colgroup>
			<tr>
				<td class="al bold">Commission Description</td>
				<td class="ar bold">Amount</td>
			</tr>
			<tr>
				<td class="al">Business Development Commission (창업수당)</td>
				<td class="ar txt02"><?if($BD){echo Number_Format($BD);}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al">Personal Rebates (개인판매수당)</td>
				<td class="ar txt02"><?if($PR){echo Number_Format($PR);}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al">Team Development Commission (팀개발수당)</td>
				<td class="ar txt02"><?if($TD){echo Number_Format($TD);}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al">Organization Development Commission (조직개발수당)</td>
				<td class="ar txt02"><?if($OD){echo Number_Format($OD);}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al">Car Bonus (승용차유지보너스)</td>
				<td class="ar"><?if($CarBonus){echo Number_Format($CarBonus);}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al">President's Club Achiever's Bonus (프레지던트클럽 성취보너스)</td>
				<td class="ar txt02"><?if($PCAB){echo Number_Format($PCAB);}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al">MLB Lecture fee (MLB 감사료)</td>
				<td class="ar txt02"><?if($MLB){echo Number_Format($MLB);}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al">Management Group (국제후원수당)</td>
				<td class="ar txt02"><?if($PGO){echo Number_Format($PGO);}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al">Deduction Adjustment (조정분)</td>
				<td class="ar txt02"><?if($Adjustment){echo Number_Format($Adjustment);}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al bold txt03">Net Eaming</td>
				<td class="ar bold txt03"><?if($MonthNetEarning){echo Number_Format($MonthNetEarning);}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al bold txt04">Taxes</td>
				<td class="ar"></td>
			</tr>
			<tr>
				<td class="al txt04">Income (소득세)</td>
				<td class="ar txt02"><?if($IncomeTax){echo Number_Format($IncomeTax);}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al txt04">Resident (주민세)</td>
				<td class="ar txt02"><?if($ResidentTax){echo Number_Format($ResidentTax);}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al bold txt04">* 한국소아암협회후원비</td>
				<td class="ar bold txt02"><?if($soa){echo Number_Format($soa);}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al bold txt04">* Autoship Adjustment</td>
				<td class="ar bold txt02"><?if($AutoshipAdj){echo Number_Format($AutoshipAdj);}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al bold txt03">Net Earning after Taxes and Aid</td>
				<td class="ar bold txt03"><?=Number_Format($MonthNetPayment)?></td>
			</tr>
		</table>
	</div>
	<div id="personal">
		<div class="name"><?=$DistributorName?> 귀하 (<?=$id?>)</div>
		<div class="add"><?=$address?></div>
		<div class="post"></div>
	</div>
</div>

<?
mysql_close($connect);
?>
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

