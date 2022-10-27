<?
ini_set('memory_limit',-1);
ini_set('max_execution_time', 60);
?>
<?
	include "../admin_session_check.inc";
	//include "./inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../../AES.php";


	$start_year = str_quote_smart(trim($start_year));
	$start_month = str_quote_smart(trim($start_month));
	$start_day = '01';
	$end_year = str_quote_smart(trim($end_year));
	$end_month = str_quote_smart(trim($end_month));
	$end_day = date("t", mktime(0, 0, 0, $end_month, 1, $end_year));

	$que = "and createdate between '".$start_year."-".$start_month."-".$start_day." 00:00:00' and '".$end_year."-".$end_month."-".$end_day." 23:59:59' ";
	$str_title = "ConsultationList";

	$file_name=$str_title."-".date("Ymd").".xls";
	header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
	header( "Content-Disposition: attachment; filename=$file_name" );
	//header( "Content-Description: orion70kr@gmail.com" );
	function getCategory($code){
		$categoryCode = array(
			"01" =>"본인동의",
			"02" =>"수당/실적",
			"03" =>"주문/반품",
			"04" =>"가입/해지",
			"05" =>"제품문의(기타)",
			"06" =>"제품문의(건식)",
			"07" =>"제품문의(화장품)",
			"08" =>"1800",
			"09" =>"세무/증빙",
			"10" =>"웹사이트/모바일앱",
			"11" =>"군포물류",
			"12" =>"우체국",
			"13" =>"국제후원",
			"14" =>"오토쉽",
			"15" =>"정수기",
			"16" =>"기타"
		);
		return $categoryCode[$code];
	}

	function getCounselType($code){
		$typeCodes = array(
			"01" => "일반", 
			"02" => "클레임", 
			"03" => "건의"
		);
		return $typeCodes[$code];
	}

		function getDeptName($code){
		$DeptCodes = array(
			"SDSC" =>"서울DSC",
			"ADSC" =>"안산DSC",
			"TDSC" =>"대전DSC",
			"WDSC" =>"원주DSC",
			"BDSC" =>"부산DSC",
			"DDSC" =>"대구DSC",
			"KDSC" =>"광주DSC",
			"IDSC" =>"인천DSC",
			"PRD1" =>"건식",
			"PRD2" =>"화장품",
			"FA"   =>"Finance",
			"OP"   =>"Operation",
			"LA"   =>"Legal",
			"LG"   =>"Logistics",
			"WT"   =>"Water",
			"DES"  =>"Design",
			"EV"   =>"Event",
			"IT"   =>"IT",
			"HR"   =>"HR",
			"PR"   =>"PR"
		);
		return $DeptCodes[$code];
	}
	
	$query2 = "select * from tb_counsel where 1 = 1  ".$que." order by createdate desc" ;
    

	$result2 = mysql_query($query2);
?>
 
<br>

<br>
<br>
	<table cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
		<tr align="center">
			<th width="2%" style="text-align: center;">#</th>
			<th width="5%" style="text-align: center;">상담구분</th>
			<th width="5%" style="text-align: center;">상담유형</th>
			<th width="5%" style="text-align: center;">지역</th>
			<th width="8%" style="text-align: center;">인입번호</th>
			<th width="5%" style="text-align: center;">회원번호</th>
			<th width="5%" style="text-align: center;">회원명</th>
			<th width="8%" style="text-align: center;">상담시간</th>
			<th width="5%" style="text-align: center;">상담자</th>
			<th width="5%" style="text-align: center;">이관부서</th>
			<th width="5%" style="text-align: center;">이관받은사람</th>
			<th width="14%" style="text-align: center;">기타(건의)</th>
			<th width="14%" style="text-align: center;">상담내역</th>
		</tr>     
<?php
		$result2 = mysql_query($query2);
	 
	
			while($obj = mysql_fetch_object($result2)) {
				$createddate_s = date("Y-m-d H:i", strtotime($obj->createdate));
				$status = $obj->status;
	?>
		<tr>
			<td align="center"><?echo $obj->id?></td>
			<td align="center"><?echo getCounselType($obj->counsel_type2).' ('.($status==0?'진행중':'완료').')' ?></td>
			<td align="center"><?echo getCategory($obj->counsel_type1)?></td>
			<td align="center"><?echo $obj->location?></td>
			<td align="center"><?echo $obj->contact_no?></a></td>
			<td align="center"><?echo $obj->member_no?></td>
			<td align="center"><?echo $obj->name?></td>
			<td align="center"><?echo $createddate_s?></td>
			<td align="center"><?echo $obj->updator?></td>
			<td align="center"><?echo getDeptName($obj->transferred_dept)?></td>
			<td align="center"><?echo $obj->transferred_staff?></td>
			<td align="center"><?echo $obj->short_comment?></td>
	 		<td align="left"><?echo $obj->description?></td>
		</tr>
	<?php
	
			 
		}
	?>
	</table>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
