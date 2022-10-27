<?
if(strstr($HTTP_USER_AGENT, "MSIE")) { 
	header("Content-type:application/vnd.ms-excel"); 
	header("Content-Disposition:attachment;filename=new_member_log.xls"); 
	header("Content-Transfer-Encoding: binary"); 
	Header("Cache-Control: cache, must-revalidate"); 
	header("Pragma: no-cache"); 
	header("Expires: 0"); 
	
}else { 
	Header("Content-type:application/vnd.ms-excel"); 
	Header("Content-Disposition:attachment; filename=new_member_log.xls"); 
	Header("Content-Description: PHP3 Generated Data"); 
	Header("Content-Length: $fileinfo[filesize]"); 
	Header("Cache-Control: cache, must-revalidate"); 
	header("Pragma: no-cache"); 
	header("Expires: 0"); 
} 
?>

<?
	
	include "./admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";

	$mode					= str_quote_smart(trim($mode));

	$qry_str			= str_quote_smart(trim($qry_str));
	$idxfield			= str_quote_smart(trim($idxfield));
	$page					= str_quote_smart(trim($page));
	$sort					= str_quote_smart(trim($sort));
	$order				= str_quote_smart(trim($order));
	$member_kind	= str_quote_smart(trim($member_kind));

	$query = "select count(*) from tb_member_lkk  ";
	$query2 = "select * from tb_member_lkk  order by idx desc" ;


	//echo $query."<BR>"; 
	//echo $query2;
		
	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<STYLE type='text/css'>
TD {FONT-SIZE: 9pt}
.h {FONT-SIZE: 9pt; LINE-HEIGHT: 120%}
.h2 {FONT-SIZE: 9pt; LINE-HEIGHT: 180%}
.s {FONT-SIZE: 8pt}
.l {FONT-SIZE: 11pt}
.text {  line-height: 125%}
</STYLE>
</head>
<BODY bgcolor="#FFFFFF">
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="1" bgcolor="silver">
<TR>
	<TH width="30">번호</TH> 
	<TH width="50">회원명</TH>
	<TH width="100">주민번호 (TAX No)</TH>
	<TH width="100">ip</TH>
	<TH width="100">날짜</TH>
</TR>     

<TR align="center"><td colspan="5" height="2" bgcolor="000000"></td></tr>       
<?
	$result2 = mysql_query($query2);

	if ($TotalArticle) {

		for ($i = 0; $i < $TotalArticle; ++$i) {
	
			mysql_data_seek($result2,$i);

			$obj = mysql_fetch_object($result2);
			
			//if ($i >= $First) {
				
				$date_s1 = date("Y-m-d", strtotime($obj->wdate));
					
?>					
<TR align="center">                    
	<TD><?echo $i+1;?> (게시번호:<?echo $obj->idx?>)</TD>
	<TD align="left"><?echo $obj->uname?></TD>
	<TD><?echo $obj->jumin1?>-<?echo $obj->jumin2?></TD>
	<TD><?echo $obj->ip?></TD>
	<TD><?echo $obj->wdate?></TD>
</TR>
<?
			//}
		}
	}
?>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0">
<TR>
	<TD align="left">
	    [<b><?echo $date_s1?></b>] 까지 등록된 회원 수 : <?echo $TotalArticle?> 명 
	</TD>
	<TD align="right"></TD>
</TR>
</TABLE>
</body>
</html>
<?
mysql_close($connect);
?>