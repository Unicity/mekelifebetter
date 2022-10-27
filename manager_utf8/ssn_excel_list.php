<?
ini_set('memory_limit',-1);
ini_set('max_execution_time', 60);
?>
<?
	include "./admin_session_check.inc";
	//include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";
	include "./inc/common_function.php";

	logging($s_adm_id,'download ssn list (ssn_excel_list.php)');

	$str_title = iconv("UTF-8","EUC-KR","세금신고용 주민번호 리스트");

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
		$con_sort = "create_date";
	}

	if (empty($con_order)) {
		$order = "desc";
	}

	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = " and dist_id like '%$qry_str%' ";
		}
		
		$query2 = "select * from tb_distSSN where 1 = 1 ".$que." order by ".$con_sort." ".$order ;
	} else {
		$query2 = "select * from tb_distSSN where 1 = 1  order by ".$con_sort." ".$order ;
	}

	$result2 = mysql_query($query2);
?>
<font size=3><b>세금신고용 주민번호 리스트 </b></font> <br>
<br>
출력 일자 : [<?=date("Y년 m월 d일")?> ]
<br>
<br>
<table border="1">
	<tr>
		<td align='center' bgcolor='#F4F1EF'>회원번호</td>
		<td align='center' bgcolor='#F4F1EF'>주민증록번호</td>
		<td align='center' bgcolor='#F4F1EF'>등록일</td>
	</tr>
<?
	while($obj = mysql_fetch_object($result2)) {
			
		$create_date	= date("Y-m-d", strtotime($obj->create_date));
		$jumin_number = decrypt($key, $iv, $obj->government_id);
		$dist_id			= $obj->dist_id;
?>
	<tr style='border-collapse:collapse;table-layout:fixed;'>
		<td><?=$dist_id?></td>
		<td><?=left($jumin_number,6)?>-<?=right($jumin_number,7)?></td>
		<td><?=$create_date?></td>
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
