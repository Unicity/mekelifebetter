<?
	include "../../dbconn_utf8.inc";
	
	$query = "select reg_jumin1, reg_jumin2, number, co_number, co_name from tb_userinfo ";
	$result = mysql_query($query);
	
	$i = 1;

  while($row = mysql_fetch_array($result)) {

		$reg_jumin1 = $row[reg_jumin1];
		$reg_jumin2 = $row[reg_jumin2];
		$number = $row[number];
		$co_number = $row[co_number];
		$co_name = $row[co_name];
		 
		$reg_no = $reg_jumin1."-".$reg_jumin2;

		$sub_query = "select reg_no, number,  sponsor_number, sponsor_name from tb_member_new where reg_no = '$reg_no ' ";
		$sub_result = mysql_query($sub_query);
		$total  = mysql_affected_rows();
		
	  if ($total > 0) {
			$list = mysql_fetch_array($sub_result);
			$reg_no = $list[reg_no];
			$mm = $list[number];
			$sm = $list[sponsor_number];
			$sn = $list[sponsor_name];

			
			echo $i++.":".$reg_no."&nbsp;&nbsp;".$number.":".$mm."&nbsp;".$co_number.":".$sm."&nbsp;".$co_name.":".$sn."<br>";

			$sub_update = "update tb_userinfo set number = '$mm',
																						co_number = '$sm',
																						co_name = '$sn'	
																			where reg_jumin1 = '$reg_jumin1' and reg_jumin2 = '$reg_jumin2' ";
			echo $sub_update. "<br>";

			mysql_query($sub_update) or die("Query Error".$query);
		
		}
	}		
	mysql_close($connect);
?>