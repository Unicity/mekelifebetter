<?
ini_set('memory_limit',-1);
ini_set('max_execution_time', 60);

?>
<?
	include "../admin_session_check.inc";
	//include "./inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../../AES.php";

	$file_name="unclaimedCommission_List_csv-".date("Ymd").".csv";
	header( "Content-Type: text/csv; charset=UTF-8"); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
	header( "Content-Disposition: attachment; filename=$file_name");
 	
 	$output = fopen('php://output', 'w');
 	$columnArray = array('BankCode', 'AccountNo', 'AccountHolder', 'Amount', 'GovernmentID','MemberID');

 	fputcsv($output, $columnArray);

	$s_status			= str_quote_smart(trim($s_status));
	$qry_str			= str_quote_smart(trim($qry_str));
	$page					= str_quote_smart(trim($page));
	$con_sort			= str_quote_smart(trim($con_sort));
	$con_order		= str_quote_smart(trim($con_order));
	$idxfield				= str_quote_smart(trim($idxfield));

	if ($con_order == "con_a") {
		$order = "asc";
	} else {
		$order = "desc";
		$con_order = "con_d";
	}

	 
	if (empty($con_sort)) {
		$con_sort = "CommissionDate";
	}

	if (empty($con_order)) {
		$order = "desc";
	}

	if (empty($idxfield)) {
		$idxfield = "0";
	} 
	if ((empty($s_status)) || ($s_status == "A")) {
		$s_status = "A";
	} else {
		$que = $que." and status = '$s_status' ";		
	}

	if (!empty($qry_str)) {
		if ($idxfield == "0") {
			$que = $que." and id like '%$qry_str%' ";
		} else if($idxfield == "1"){
			$que = $que." and memberName like '%$qry_str%' ";
		}
	}

	$query = "select uc.newBankCode, uc.newAccountNo, uc.newAccountHolder, uc.Amount, case when ds.government_id is null then 'Not registered' else  ds.government_id end as governmentId, uc.id from unclaimedCommission as uc left outer join tb_distSSN as ds on uc.id = ds.dist_id where 1 = 1  ".$que."order by ".$con_sort." ".$order ;
	
	$results = mysql_query($query);

	while($row = mysql_fetch_assoc($results)) {
		 $row[newAccountNo] = decrypt($key, $iv, $row[newAccountNo]);
		 $row[governmentId] = decrypt($key, $iv, $row[governmentId]);
		
		fputcsv($output, $row); 
 
	}
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
