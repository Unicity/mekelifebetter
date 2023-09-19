<?php 
session_start();
ini_set("display_errors", 0);

	include "../dbconn_utf8.inc";

	include_once($_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/str_check.php"); 

	$s_adm_id = str_quote_smart_session($s_adm_id);
	$s_number = str_quote_smart_session($s_number);

	if($_SESSION['s_adm_id']!=''){
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
.table tr td { height: 23px; }
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


	$id						= str_quote_smart(trim($id));
	$VolumePeriod = str_quote_smart(trim($VolumePeriod));

	$query = "SELECT * FROM tb_Activityreport where VolumePeriod='$VolumePeriod' and id='$id'";

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
	$MB= $row[11];
	$PGO= $row[12];
	$Adjustment= $row[13];
	$MonthNetEarning= $row[14];
	$IncomeTax= $row[15];
	$ResidentTax= $row[16];
	$soa= $row[17];
	$AutoshipAdj= $row[18];
	$MonthNetPayment = $row[19];
	$address = $row[21];

	$query2 = "SELECT MLB,IncomeTax,ResidentTax  FROM tb_Activityreport_MBL where VolumePeriod='$VolumePeriod' and id='$id'";
	$result2 = mysql_query($query2,$connect);
	$row2 = mysql_fetch_array($result2);
	$MLB =$row2[0];
	$IncomeTax_mlb=$row2[1];
	$ResidentTax_mlb=$row2[2];

	$totl_IncomeTax=$IncomeTax+$IncomeTax_mlb;
	$totl_ResidentTax_mlb=$ResidentTax_mlb+$ResidentTax;
?>
<body onload="print();">

<?php include "common_load.php" ?>

<div id="wrap">
	<div class="table">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<colgroup>
				<col width="70%" />
				<col width="30%" />
			</colgroup>
			<tr>
				<td class="al bold">Franchise Owner Infomation</td>
				<td></td>
			</tr>
			<tr>
				<td class="al">Volume Period</td>
				<td class="ar">
				<?
					$Volume = explode ("-", $VolumePeriod);
					if($Volume[0]=="Dec"){
						$Vol="12";
					}elseif($Volume[0]=="Jan"){
						$Vol="1";
					}elseif($Volume[0]=="Feb"){
						$Vol="2";
					}elseif($Volume[0]=="Mar"){
						$Vol="3";
					}elseif($Volume[0]=="Apr"){
						$Vol="4";
					}elseif($Volume[0]=="May"){
						$Vol="5";
					}elseif($Volume[0]=="Jun"){
						$Vol="6";
					}elseif($Volume[0]=="Jul"){
						$Vol="7";
					}elseif($Volume[0]=="Aug"){
						$Vol="8";
					}elseif($Volume[0]=="Sep"){
						$Vol="9";
					}elseif($Volume[0]=="Oct"){
						$Vol="10";
					}elseif($Volume[0]=="Nov"){
						$Vol="11";
					}

					$V_year="20".$Volume[1];

					$lastChildCharityMonth = date("Y-m",strtotime("2018-11"));
					$currentMonth = date("Y-m", strtotime($V_year."-".$Vol));
					 
				?>
				<?=$V_year?>-<?=$Vol?></td>
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
				<td class="al">Paid Rank (지급 랭크)</td>
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
				<td class="al bold" style="height: 26px;">Commission Description</td>
				<td class="ar bold" style="height: 26px;">Amount</td>
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
				<td class="al" style="height: 20px;">Team Development Commission (팀개발수당)</td>
				<td class="ar txt02" style="height: 20px;"><?if($TD){echo Number_Format($TD);}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al" style="height: 20px;">Organization Development Commission (조직개발수당)</td>
				<td class="ar txt02" style="height: 20px;"><?if($OD){echo Number_Format($OD);}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al" style="height: 20px;">Car Bonus (승용차유지보너스)</td>
				<td class="ar" style="height: 20px;"><?if($CarBonus){echo Number_Format($CarBonus);}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al" style="height: 22px;">President's Club Achiever's Bonus (프레지던트클럽 성취보너스)</td>
				<td class="ar txt02" style="height: 22px;"><?if($PCAB){echo Number_Format($PCAB);}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al" style="height: 20px;">MLB fee (MLB 수당)</td>
				<td class="ar txt02" style="height: 20px;">
				<?
				if($MB > 0 ){
					echo Number_Format($MB);
				}else{
					if($MLB){
						echo Number_Format($MLB);
					}else{
						echo "0";
					}
				}
				?>
				</td>
			</tr>
			<tr>
				<td class="al">Management Group (국제후원수당)</td>
				<td class="ar txt02"><?if($PGO){echo Number_Format($PGO);}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al" style="height: 21px;">Deduction Adjustment (조정분)</td>
				<td class="ar txt02" style="height: 21px;"><?if($Adjustment){echo Number_Format($Adjustment);}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al bold txt03">Net Earning</td>
				<td class="ar bold txt03"><?if($MonthNetEarning){echo Number_Format($MonthNetEarning + $MLB);}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al bold txt04" style="height: 20px;">Taxes</td>
				<td class="ar" style="height: 20px;"></td>
			</tr>
			<tr>
				<td class="al txt04">Income (소득세)</td>
				<td class="ar txt02"><?if($totl_IncomeTax){echo Number_Format(str_replace("-","",$totl_IncomeTax));}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al txt04" style="height: 20px;">Resident (주민세)</td>
				<td class="ar txt02" style="height: 20px;"><?if($totl_ResidentTax_mlb){echo Number_Format(str_replace("-","",$totl_ResidentTax_mlb));}else{echo "0";}?></td>
			</tr>
			
			<tr>
				<?php if($lastChildCharityMonth > $currentMonth) { ?>
				<td class="al bold txt04">* 한국소아암협회후원금</td>
				<td class="ar bold txt02"><?if($soa){echo Number_Format(str_replace("-","",$soa));}else{echo "0";}?></td>
				<?php } else {?>
				<td class="al txt04"> </td>
				<td class="ar txt02"> </td>	
				<?php } ?>

			</tr>
			
			<tr>
				<td class="al bold txt04" style="height: 20px;">* Autoship Adjustment</td>
				<td class="ar bold txt02" style="height: 20px;"><?if($AutoshipAdj){echo Number_Format(str_replace("-","",$AutoshipAdj));}else{echo "0";}?></td>
			</tr>
			<tr>
				<td class="al bold txt03">Net Earning after Taxes and Aid</td>
				<td class="ar bold txt03"><?=Number_Format($MonthNetEarning + $MLB + $AutoshipAdj + $soa + $totl_ResidentTax_mlb + $totl_IncomeTax)?></td>
			</tr>
		</table>
	</div>
	<!--
	<div id="personal">
		<div class="name"><?=$DistributorName?> 귀하 (<?=$id?>)</div>
		<div class="add"><?=$address?></div>
		<div class="post"></div>
	</div>
	-->
</div>
<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>
<?
mysql_close($connect);
?>
</body>
</html>



