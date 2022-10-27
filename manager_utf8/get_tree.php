<? 
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/7/21
	// 	Last Update : 2003/7/21
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.7/21 by Park ChanHo 
	// 	File Name 	: get tree.php
	// 	Description : the member information Insert and Update as file
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	set_time_limit(0); 

	include "../dbconn_utf8.inc";

	function makeTreeCategory ($sParentid)  { 
	
		$sqlstr = "SELECT * FROM tb_distrib_perfs WHERE distributor_sponsor_id='$sParentid' order by distributor_id"; 
	
		$result = mysql_query($sqlstr);
		$total 	= mysql_affected_rows();
		
		print("<ul type='disc'>");
	
		for($i=0 ; $i< $total ; $i++)	{  	//  start 에서 scale 까지 만
	
			if($i< $total )	{ 								// 전체 자료 개수까지만 출력
				mysql_data_seek($result,$i);
				$row = mysql_fetch_array($result);		
				
				//$sql = "SELECT * FROM tb_member WHERE number = '$row[distributor_number]' "; 
				
				//$result2 = mysql_query($sql);
				//$list2 = mysql_fetch_array($result2);
				//$local_name = $list2[name];
				
				//if ($local_name == "") {
				//	$local_name = "비활동";
				//}		
				
				print("$row[distributor_id] $local_name [$row[base_personal_volume], [$row[base_team_volume]]]");

				makeTreeCategory($row[distributor_id]);
			}
		}
		print("</ul>");
	}

	$Distributor_number = "566909";
	
	$query = "select * from tb_distrib_perfs where distributor_number = '$Distributor_number' ";

	$result = mysql_query($query);
	
	while($row = mysql_fetch_array($result)) {

		$distributor_id = $row[distributor_id];

	}

?>
<html>
<head>
<title>:::::Unicity:::::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<link rel="stylesheet" href="inc/admin.css" type="text/css">
<script language="javascript">
<!--
//-->
</script>
</head>
<BODY bgcolor="#FFFFFF">
<FORM name="frmCategory" method="GET">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>회원 Manager</B></TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
		&nbsp;
	</TD>
</TR>
</TABLE>
<input type='hidden' name='mod' value=''>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN">
<tr>
	<td>
<?
	makeTreeCategory($distributor_id);
?>
	</td>
</tr>
</TABLE>
</FORM>
</BODY>
</HTML>
<?
	mysql_close($connect);
?>                                   