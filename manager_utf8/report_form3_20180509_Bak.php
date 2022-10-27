<?session_start();
	ini_set("display_errors", 0);

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
.year { position: absolute; top: 24px; left: 39px; width: 43px; height: 18px; text-align: center; padding-top: 2px; }
.in01 { position: absolute; top: 152px; left: 165px; width: 169px; height: 29px; text-align: center; padding-top: 5px; }
.in02 { position: absolute; top: 152px; left: 429px; width: 209px; height: 29px; text-align: center; padding-top: 5px; letter-spacing: 2px; }
.in03 { position: absolute; top: 181px; left: 165px; width: 473px; height: 29px; text-align: center; padding-top: 5px; letter-spacing: 1px; }
.in04 { position: absolute; top: 210px; left: 165px; width: 169px; height: 29px; text-align: center; padding-top: 5px; letter-spacing: 10px; }
.in05 { position: absolute; top: 210px; left: 429px; width: 209px; height: 29px; text-align: center; padding-top: 5px; letter-spacing: 2px; }
.in06 { position: absolute; top: 240px; left: 165px; width: 475px; height: 29px; text-align: center; padding-top: 5px; letter-spacing: 1px; }
.table { position: absolute; top: 362px; left: 0px; width: 640px; }
.table tr td { height: 26px; }
.txt { font-size:12px; }
.ac { text-align:center; }
.ar { text-align:right; padding-right: 8px; }
.date { position: absolute; top: 711px; left: 400px; width: 150px; height: 18px; text-align: right; letter-spacing: 2px; }
</style>
</head>
<?
	include "../dbconn_utf8.inc";
	include "../AES.php";

	$y=date("y"); 
	$m=date("m"); 
	$d=date("d");

	$id			= str_quote_smart(trim($id));
	$JU_NO	= str_quote_smart(trim($JU_NO));
	$s_date = str_quote_smart(trim($s_date));
	$e_date = str_quote_smart(trim($e_date));

	$e_year = explode("-",$e_date);
	$yy=$e_year[0];

	//$JU_NO="";

	if($JU_NO==""){
		$sql_jumin="select JU_NO FROM tb_Activityreport  where ID='$id' order by regdate desc limit 0,1";

		$result_jumin = mysql_query($sql_jumin,$connect);
		$row_jumin = mysql_fetch_array($result_jumin);
		$JU_NO = $row_jumin[0];
	}
	
	
	$query = "SELECT DistributorName,JU_NO,companynum,address,companyaddr,sangho FROM tb_Activityreport  where ID='$id' and JU_NO='$JU_NO' and regdate > '$s_date' and regdate <= '$e_date' order by regdate desc limit 0,1";

	//echo $query;
	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$DistributorName = $row[0];
//	$JU_NO = $row[1];
	$companynum = $row[2];
	$address = $row[3];
	$companyaddr = $row[4];
	$sangho = $row[5];
	
	if($DistributorName==""){
		$query2 = "SELECT DistributorName,JU_NO,companynum,address,companyaddr,sangho FROM tb_Activityreport  where ID='$id'  and VolumePeriod='$VolumePeriod' ";
		//echo $query2;
		$result2 = mysql_query($query2,$connect);
		$row2 = mysql_fetch_array($result2);
		$DistributorName = $row2[0];
		$JU_NO = $row2[1];
		$companynum = $row2[2];
		$address = $row2[3];
		$companyaddr = $row2[4];
		$sangho = $row2[5];
	}

	$jumin = decrypt($key, $iv, $JU_NO);
?>
<body onload="print();">
<div id="wrap">
<table width="640" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="640" height="1001" background="images/form_1.gif">
		<div class="year"><?=$yy?></div>
		<div class="in01"><?=$sangho?></div>
		<div class="in02">
		<?if($companynum){echo $companynum;}?>
		</div>
		<div class="in03"><?=$companyaddr?></div>
		<div class="in04"><?=$DistributorName?></div>
		<div class="in05"><?=substr($jumin,0,6)?>-<?=substr($jumin,6,7)?></div>
		<div class="in06"><?=$address?></div>
		<div class="table">
			<table width="640" border="0" cellspacing="0" cellpadding="0">
				<colgroup>
					<col width="30" />
					<col width="32" />
					<col width="32" />
					<col width="35" />
					<col width="30" />
					<col width="120" />
					<col width="50" />
					<col width="96" />
					<col width="117" />
					<col width="107" />
				</colgroup>
				<?
					$query = "SELECT * FROM tb_Activityreport where ID='$id' and regdate > '$s_date' and regdate <= '$e_date' order by regdate asc";
					$result = mysql_query($query,$connect);
					$i=1;
					$tot=0;//지금총액 계
					$tot_c=0;//소득세 계
					$tot_d=0;//주민세 계
					$tot_e=0;// 계
					while($obj = mysql_fetch_object($result)) {
						$regdate=$obj->regdate;
						$a_date = explode("-",$regdate);
						$yyyy=$a_date[0];
						$mmmm=$a_date[1];
						$dddd=$a_date[2];
				?>
				<tr>
					<td class="ac txt"><?=$yyyy?></td>
					<td class="ac"><?=$mmmm?></td>
					<td class="ac"><?=$dddd?></td>
					<td class="ac txt"><?=$yyyy?></td>
					<td class="ac"><?=$mmmm?></td>
					<td class="ar"><?=Number_Format($obj->MonthNetEarning)?></td>
					<td class="ac">3%</td>
					<td class="ar"><?=Number_Format(abs($obj->IncomeTax))?></td>
					<td class="ar"><?=Number_Format(abs($obj->ResidentTax))?></td>
					<td class="ar"><?=Number_Format(abs($obj->IncomeTax + $obj->ResidentTax))?></td>
				</tr>
				<?
					$tot=$tot+$obj->MonthNetEarning;
					$tot_c=$tot_c + $obj->IncomeTax;
					$tot_d=$tot_d + $obj->ResidentTax;
					$tot_e=$tot_e + ($obj->IncomeTax + $obj->ResidentTax);
					$i++;
					}
					$k=19- $i;
					for($j=1;$j < $k;$j++){
					?>
				<tr>
					<td class="ac txt">&nbsp;</td>
					<td class="ac">&nbsp;</td>
					<td class="ac">&nbsp;</td>
					<td class="ac txt">&nbsp;</td>
					<td class="ac">&nbsp;</td>
					<td class="ar">&nbsp;</td>
					<td class="ac">&nbsp;</td>
					<td class="ar">&nbsp;</td>
					<td class="ar">&nbsp;</td>
					<td class="ar">&nbsp;</td>
				</tr>
				<?}?>
			</table>
		</div>
		<div class="date">20<?=$y?>년 <?=$m?>월 <?=$d?>일</div>
		</td>
	</tr>
</table>
</div>
</body>
</html>
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
?>