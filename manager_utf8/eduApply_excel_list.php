<?
ini_set('memory_limit',-1);
ini_set('max_execution_time', 60);
?>
<?
	include "./admin_session_check.inc";
	//include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";

	$str_title = iconv("UTF-8","EUC-KR","건식보수교육 신청자 명단");

	$file_name=$str_title."-".date("Ymd").".xls";
		header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
		header( "Content-Disposition: attachment; filename=$file_name" );
		header( "Content-Description: orion70kr@gmail.com" );

	function right($value, $count){
		$value = substr($value, (strlen($value) - $count), strlen($value));
		return $value;
	}

	function left($string, $count){
		return substr($string, 0, $count);
	}

	$mode					= str_quote_smart(trim($mode));

	$qry_str			= str_quote_smart(trim($qry_str));
	$idxfield			= str_quote_smart(trim($idxfield));
	$page					= str_quote_smart(trim($page));
	$con_sort			= str_quote_smart(trim($con_sort));
	$con_order		= str_quote_smart(trim($con_order));

	if ($con_order == "con_a") {
		$order = "asc";
	} else {
		$order = "desc";
		$con_order = "con_d";
	}

	if (empty($idxfield)) {
		$idxfield = "0";
	} 
	
	if (empty($con_sort)) {
		$con_sort = "applyDate";
	}

	if (empty($con_order)) {
		$order = "desc";
	}

	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = " and dist_id like '%$qry_str%' ";
		}
		
		$query2 = "select * from education_apply where 1 = 1 ".$que." order by ".$con_sort." ".$order ;
	} else {
		$query2 = "select * from education_apply where 1 = 1  order by ".$con_sort." ".$order ;
	}

	$result2 = mysql_query($query2);
?>
<font size=3><b>건식 보수교육 신청자 명단 </b></font> <br>
<br>
출력 일자 : [<?=date("Y년 m월 d일")?> ]
<br>
<br>
<table border="1">
	<tr>
		<td align='center' bgcolor='#F4F1EF'>회원번호</td>
		<td align='center' bgcolor='#F4F1EF'>성명</td>
		<td align='center' bgcolor='#F4F1EF'>생년월일</td>
		<td align='center' bgcolor='#F4F1EF'>휴대폰</td>
		<td align='center' bgcolor='#F4F1EF'>이메일</td>
		<td align='center' bgcolor='#F4F1EF'>인허가번호</td>
		<td align='center' bgcolor='#F4F1EF'>대표자성명</td>
		<td align='center' bgcolor='#F4F1EF'>대표자생년월일</td>
		<td align='center' bgcolor='#F4F1EF'>대리여부</td>
		<td align='center' bgcolor='#F4F1EF'>대리사유</td>
		<td align='center' bgcolor='#F4F1EF'>신청일자</td>
		<td align='center' bgcolor='#F4F1EF'>취소여부</td>
		<td align='center' bgcolor='#F4F1EF'>수정일자</td>
	</tr>
<?
	while($obj = mysql_fetch_object($result2)) {
			
		$date_s = date("Y-m-d", strtotime($obj->applyDate));
		$date_m = date("Y-m-d", strtotime($obj->modifyDate));
		if($date_m =='1970-01-01'){
			$date_m = "";
		}
		$id	= $obj->id;
?>
	<tr style='border-collapse:collapse;table-layout:fixed;'>
		<td><?php echo $id ?></td>
		<td><?echo $obj->UserName ?></td>
		<td><?echo $obj->birthDay ?></td>
		<td><?echo $obj->Phone?></td>
		<td><?echo $obj->email?></td>
		<td><?echo $obj->licenseNum?></td>
		<td><?echo $obj->representativeName?></td>
		<td><?echo $obj->representativeBirth?></td>
		<td><?echo $obj->deputyEduYn?></td>
		<td><?echo $obj->deputyReason?></td>
		<td><?echo $date_s?></td>
		<td><?echo $obj->cancelYn?></td>
		<td><?echo $date_m?></td>
	</tr>
<?
	}
?>

</table>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
