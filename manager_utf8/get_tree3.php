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

	function makeTreeCategory2 ($sParentid, $depth, $parentid, $iDepth, $end_Depth, $open_node)  { 	

		$sqlstr = "SELECT * FROM tb_distrib_perfs WHERE distributor_sponsor_id='$sParentid' order by distributor_id"; 
	
		$result = mysql_query($sqlstr);
		$total 	= mysql_affected_rows();

		$iDepth++;
					
		for($i=0 ; $i < $total ; $i++)	{  	

			if($i < $total )	{ 
								
				mysql_data_seek($result,$i);
				$row = mysql_fetch_array($result);		

				$sql = "SELECT count(*) cnt FROM tb_distrib_perfs WHERE distributor_sponsor_id ='$row[distributor_id]' "; 
				$result2 = mysql_query($sql);
				$list2 = mysql_fetch_array($result2);
				$ii_cnt = $list2[cnt];

				$sql = "SELECT * FROM tb_member WHERE number = '$row[distributor_number]' "; 
				
				$result2 = mysql_query($sql);
				$list2 = mysql_fetch_array($result2);
				$local_name = $list2[name];
				
				if ($local_name == "") {
					$local_name = "비활동";
				}		

				if ($parentid != $row[distributor_sponsor_id]) {

					if ($depth != $row[distributor_sponsor_id]) {

						$open_node_ep = explode("^", $open_node);
													
						for ($t=0 ;  $t < sizeof($open_node_ep); $t++) {;
						
							if ($open_node_ep[$t] == $row[distributor_sponsor_id]."d") {
								$is_open = "1";							
							}
						
						}
						
						if ($is_open == "1") {
							print("<div id=$row[distributor_sponsor_id]d style='margin-top:-1px;display:;'>\n");
						} else {
							print("<div id=$row[distributor_sponsor_id]d style='margin-top:-1px;display:none;'>\n");
						}
						$is_open = "0";
						
					}
					$parentid = $row[distributor_sponsor_id];

				}

				print("<table cellspacing=0 cellpadding=0>\n<tr>\n<td><img src='../manager/images/ftv2blank.gif'></td>\n");


				for ($k = 0 ; $k < $iDepth ; $k++ ) {

					if ($k != 0) {
						
						if ($k != $end_Depth) {

//							if ($k == $end_Depth) {
								print("<td><img src='../manager/images/mid_blank.gif'></td>\n");
//							} else {
//								print("<td><img src='../manager/images/ftv2blank.gif'></td>\n");
//							}
						
						} else {

							print("<td><img src='../manager/images/ftv2blank.gif'></td>\n");

						}
						
					} else {
							print("<td><img src='../manager/images/ftv2blank.gif'></td>\n");
					}

				}

				if ($ii_cnt == 0) {

//					sum_pv($row[distributor_id], $row[ base_personal_volume], 0);

					if ($i == ($total-1)) {
						
						print("<td><img src='../manager/images/last_normal.gif' id=$row[distributor_id] class=LastOutline style='cursor:hand'></td>\n");
						print("<td><a href='#'><img src='../manager/images/close.gif' id=$row[distributor_id]f border=0 ></a></td>\n<td>$local_name  [$row[base_personal_volume]] [$row[base_team_volume],$row[test_tv]]</td>\n");

					} else {

						print("<td><img src='../manager/images/mid_normal.gif' id=$row[distributor_id] class=Outline style='cursor:hand'></td>\n");
						print("<td><a href='#'><img src='../manager/images/close.gif' id=$row[distributor_id]f border=0 ></a></td>\n<td>$local_name  [$row[base_personal_volume]] [$row[base_team_volume],$row[test_tv]]</td>\n");

					}
					
				} else {

					$open_node_ep = explode("^", $open_node);
													
					for ($t=0 ;  $t < sizeof($open_node_ep); $t++) {;
						
						if ($open_node_ep[$t] == $row[distributor_id]."d") {
							$is_open = "1";							
						}
						
					}
									
					if ($i == ($total-1)) {

						$end_Depth = $iDepth;
						
						if ($is_open == "1") {

							print("<td><img src='images/last_minus.gif' id=$row[distributor_id] class=LastOutline style='cursor:hand'></td>\n");
							print("<td><a href='#'><img src='images/open.gif' id=$row[distributor_id]f border=0 ></a></td>\n<td>$local_name [$row[base_personal_volume]] [$row[base_team_volume],$row[test_tv]]</td>\n");

						} else {
							print("<td><img src='images/last_plus.gif' id=$row[distributor_id] class=LastOutline style='cursor:hand'></td>\n");
							print("<td><a href='#'><img src='images/close.gif' id=$row[distributor_id]f border=0 ></a></td>\n<td>$local_name [$row[base_personal_volume]] [$row[base_team_volume],$row[test_tv]]</td>\n");
						}

					} else {

						if ($is_open == "1") {
							print("<td><img src='images/mid_minus.gif' id=$row[distributor_id] class=Outline style='cursor:hand'></td>\n");
							print("<td><a href='#'><img src='images/open.gif' id=$row[distributor_id]f border=0 ></a></td>\n<td>$local_name [$row[base_personal_volume]] [$row[base_team_volume],$row[test_tv]]</td>\n");
						} else {
							print("<td><img src='images/mid_plus.gif' id=$row[distributor_id] class=Outline style='cursor:hand'></td>\n");
							print("<td><a href='#'><img src='images/close.gif' id=$row[distributor_id]f border=0 ></a></td>\n<td>$local_name [$row[base_personal_volume]] [$row[base_team_volume],$row[test_tv]]</td>\n");
						}

					}
					
					$is_open = "0";
				}

				print("</tr>\n</Table>\n");
																				
				makeTreeCategory2($row[distributor_id], $depth, $parentid, $iDepth, $end_Depth, $open_node);

				if ($ii_cnt != 0) {
					print("</div>\n");
				}
			}
		}
	}

	function sum_pv ($distributor_id, $pv, $tv)  { 	

		echo "sum_pv";		
		
		$sqlstr = "SELECT distributor_sponsor_id, base_personal_volume FROM tb_distrib_perfs WHERE distributor_id = '$distributor_id'"; 
					
		$result = mysql_query($sqlstr);
		$total 	= mysql_affected_rows();
		
		echo $total;
		
		if ($total > 0) {

			$list = mysql_fetch_array($result);
			$next_distributor_id = $list[distributor_sponsor_id];
			$next_tv = $list[base_team_volume];
			$next_pv = $list[base_personal_volume];

			$sum_pv = $pv + $next_pv;
			
			$sqlstr = "update tb_distrib_perfs set test_tv = '$next_tv + $sum_pv' where distributor_id = '$next_distributor_id'";
			mysql_query($sqlstr) or die("Query Error");

			$sqlstr = "update tb_distrib_perfs set test_tv = '$tv + $sum_pv' where distributor_id = '$distributor_id'";
			mysql_query($sqlstr) or die("Query Error");


			sum_pv ($next_distributor_id, $next_pv, $next_tv);				
		}
	}


	if (!empty($open_node)) {
		$open_node = trim($open_node);
	} else {
		$open_node = "";
	}

	$Distributor_number = "395465";
	
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
<script src="inc/mapfunction_include.js"></script>
<link rel="stylesheet" type="text/css" href="inc/treeStyle.css">
<link rel="stylesheet" href="inc/admin.css" type="text/css">
<script language="javascript">
<!--
//-->
</script>
</head>
<BODY bgcolor="#FFFFFF">

<?php include "common_load.php" ?>

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
<TABLE border="0" cellspacing="1" cellpadding="2">
<tr>
	<td>
<div id='treeViewer'>
<?
	makeTreeCategory2($distributor_id, "0", $distributor_id, 0, 0, $open_node);
?>
</div>
	</td>
</tr>
</TABLE>
</FORM>
</BODY>
</HTML>
<?
	mysql_close($connect);
?>                                   