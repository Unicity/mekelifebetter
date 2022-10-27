<?
	include "admin_session_check.inc";
	//include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "./inc/common_function.php";

	logging($s_adm_id,'download admin log list '.$qry_str.'(admin_log_v2_excel.php)');

	$qry_str			= str_quote_smart(trim($qry_str));
	$idxfield			= str_quote_smart(trim($idxfield));
	$from_date			= str_quote_smart(trim($from_date));
	$to_date			= str_quote_smart(trim($to_date));

	if(strlen($from_date) != 8 || strlen($to_date) != 8) msg_err("시작일 또는 종료일을 확인하여 주세요");

	$date_gap = strtotime($to_date) - strtotime($from_date);
	if($date_gap < 0) msg_err("시작일이 종료일 보다 늦습니다."); 

	$que = "";

	if($from_date != "") $que = " and  createdDate >= '".substr($from_date, 0, 4)."-".substr($from_date, 4, 2)."-".substr($from_date, 6, 2)." 00:00:00'";
	if($to_date != "") $que .= " and createdDate <= '".substr($to_date, 0, 4)."-".substr($to_date, 4, 2)."-".substr($to_date, 6, 2)." 23:59:59'";


	if($idxfield != "" && $qry_str != ""){
		if($idxfield == 'log') $que = $que." and actionType like '%".$qry_str."%' ";
		else $que = $que." and ".$idxfield." = '".$qry_str."' ";
	}

	$query2 = "select * from tb_admin_log where 1 ".$que." order by id asc";
	$result2 = mysql_query($query2);

$str_title = "관리자권한로그";
if($from_date != "") $str_title .= "_".$from_date;
if($to_date != "") $str_title .= "_".$to_date;
if($idxfield != "" && $qry_str != ""){
	$str_title .= "_".$idxfield."_".$qry_str;
}
$file_name = $str_title.".xls";
$file_name = iconv("UTF-8", "EUC-KR", $file_name);

header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-Disposition: attachment; filename=$file_name" );
?>
<TABLE border="1">
<TR style="background:#f6f6f6">
	<TH style="text-align:center">No</TH>
	<TH style="text-align:center">아이디</TH>
	<TH style="text-align:center">LOG</TH>
	<TH style="text-align:center">IP</TH>
	<TH style="text-align:center">로그일시</TH>

</TR>     
<?
$i = 1;
while($obj = mysql_fetch_object($result2)) {
	?>
	<TR align="center">
		<TD style="mso-number-format:'\@'"><?=$i?></TD>
		<TD style="mso-number-format:'\@'"><?=$obj->adminId?></TD>
		<TD style="mso-number-format:'\@'"><?=$obj->actionType?></TD>
		<TD style="mso-number-format:'\@'"><?=$obj->ip?></TD>
		<TD style="mso-number-format:'\@'"><?=$obj->createdDate?></TD>
	</TR>
	<?
	$i++;
}
?>
</TABLE>
<?
mysql_close($connect);
?>