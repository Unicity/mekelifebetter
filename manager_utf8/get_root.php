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

	function makeQuery ($distributor_id)  { 
			
		$sqlstr = "SELECT distributor_sponsor_id FROM tb_distrib_perfs WHERE distributor_id = '$distributor_id'"; 
					
		$result = mysql_query($sqlstr);
		$total 	= mysql_affected_rows();
								
		if ($total > 0) {		

			for($i=0 ; $i< $total ; $i++)	{  	//  start 에서 scale 까지 만
	
				if($i < $total )	{ 								// 전체 자료 개수까지만 출력

					mysql_data_seek($result,$i);
					$row = mysql_fetch_array($result);		
	
					$distributor_sponsor_id .= ",".$row[distributor_sponsor_id];	

					$distributor_sponsor_id .= ",".makeQuery($row[distributor_sponsor_id], $row[distributor_sponsor_id]);
				} 		
			}
		}
		return $distributor_sponsor_id;
	}


	function makeTreeCategory ($sParentid)  { 
	
		$sqlstr = "SELECT * FROM tb_distrib_perfs WHERE distributor_sponsor_id = '$sParentid' order by distributor_id"; 
	
		$result = mysql_query($sqlstr);
		$total 	= mysql_affected_rows();
		
		print("<ul type='disc'>");
	
		for($i=0 ; $i< $total ; $i++)	{  	//  start 에서 scale 까지 만
	
			if($i< $total )	{ 								// 전체 자료 개수까지만 출력
				mysql_data_seek($result,$i);
				$row = mysql_fetch_array($result);		
				
				$sql = "SELECT * FROM tb_member WHERE number = '$row[distributor_number]' "; 
				
				$result2 = mysql_query($sql);
				$list2 = mysql_fetch_array($result2);
				$local_name = $list2[name];
				
				if ($local_name == "") {
					$local_name = "비활동";
				}		
				
				print("$row[distributor_id] $local_name [$row[base_personal_volume], [$row[base_team_volume]]]");

				makeTreeCategory($row[distributor_id]);
			}
		}
		print("</ul>");
	}
	
?>
<html>
<head>
<title>:::::Unicity:::::</title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
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

	$query = "select * from tb_distrib_perfs limit 10000 , 10000";

	$result = mysql_query($query);
	
	while($row = mysql_fetch_array($result)) {

		$distributor_id = $row[distributor_id];
		$sTempCatid = makeQuery($distributor_id, $distributor_id);
		echo $sTempCatid."<BR>"; 

	}
	
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