<? 
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/3/7
	// 	Last Update : 2003/3/7
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.3.7 by Park ChanHo 
	// 	File Name 	: new_member_file_db.php
	// 	Description : the member information Insert and Update as file
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	include "../dbconn_utf8.inc";
	$number_id = 0;		
	$path = "../member/member_file";

	$query = "delete from tb_distrib_activity_header ";
	//echo $query;
	mysql_query($query) or die("Query Error".$query);

	$new_cfile = "Activity_Header_200405.txt";

	$fo = fopen($path."/".$new_cfile, "r");

	while($str = fgets($fo, 3000)) {
		$number_id++;

		echo $str; 

		$a_str = explode(",",$str);

		$Commission_period_date = $a_str[0]; 
		$Distributor_number = $a_str[1]; 
		$commission_total_amount = $a_str[2]; 
		$override_total_amount = $a_str[3]; 
		$bonus_total_amount = $a_str[4]; 
		$adjustment_amount = $a_str[5]; 
		$local_name = $a_str[6]; 
		$paid_as_level_desc = $a_str[7]; 
		$personal_volume = $a_str[8]; 
		$base_team_volume = $a_str[9]; 
		$base_cum_personal_volume = $a_str[10]; 
		$total_organizational_volume = $a_str[11]; 
		$check_issued_amount = $a_str[12]; 
		$check_issued_date = $a_str[13]; 
		$tax_amount = $a_str[14]; 
		$application_received_date = $a_str[15]; 
		$hawaii_points = $a_str[16]; 
		$distrib_activity_id = $a_str[17]; 


	
		$Commission_period_date = trim($Commission_period_date);
		$Distributor_number = trim($Distributor_number);
		$commission_total_amount = trim($commission_total_amount);
		$override_total_amount = trim($override_total_amount);
		$bonus_total_amount = trim($bonus_total_amount); 
		$adjustment_amount = trim($adjustment_amount);
		$local_name = trim($local_name);
		$paid_as_level_desc = trim($paid_as_level_desc);
		$personal_volume = trim($personal_volume);
		$base_team_volume = trim($base_team_volume);
		$base_cum_personal_volume = trim($base_cum_personal_volume);
		$total_organizational_volume = trim($total_organizational_volume);
		$check_issued_amount = trim($check_issued_amount);
		$check_issued_date = trim($check_issued_date); 
		$tax_amount = trim($tax_amount); 
		$application_received_date = trim($application_received_date);
		$hawaii_points = trim($hawaii_points);
		$distrib_activity_id = trim($distrib_activity_id);
				
		$Commission_period_date = eregi_replace("\"", "''", $Commission_period_date);
		$Distributor_number = eregi_replace("\"", "''", $Distributor_number);
		$local_name = eregi_replace("'", "''", $local_name);
		$local_name = eregi_replace("\"", "''", $local_name);
		$paid_as_level_desc = eregi_replace("\"", "", $paid_as_level_desc);
		$total_organizational_volume = eregi_replace("\"", "", $total_organizational_volume);
		$check_issued_date = eregi_replace("\"", "", $check_issued_date);
		$application_received_date = eregi_replace("\"", "", $application_received_date);
		$hawaii_points = eregi_replace("\"", "", $hawaii_points);
		
	   	$query = "insert into tb_distrib_activity_header (Commission_period_date, Distributor_number, commission_total_amount, 
	   				override_total_amount, bonus_total_amount, adjustment_amount, local_name, 
	   				paid_as_level_desc ,personal_volume, base_team_volume, base_cum_personal_volume,
	   				total_organizational_volume, check_issued_amount, check_issued_date, tax_amount, 
	   				application_received_date, hawaii_points, distrib_activity_id) values 
	   				('".$Commission_period_date."', '".$Distributor_number."', '".$commission_total_amount."', 
	   				 '".$override_total_amount."', '".$bonus_total_amount."', '".$adjustment_amount."', '".$local_name."', 
	   				 '".$paid_as_level_desc."', '".$personal_volume."', '".$base_team_volume."', '".$base_cum_personal_volume."', 
	   				 '".$total_organizational_volume."', '".$check_issued_amount."', '".$check_issued_date."', '".$tax_amount."', 
	   				 '".$application_received_date."', '".$hawaii_points."', '".$distrib_activity_id."')";

		mysql_query($query) or die("Query Error".$query);

	}
	
	fclose($fo);

	mysql_close($connect);

	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";

	echo "<script language=\"javascript\">\n
		alert('정보가 갱신 되었습니다.');
		</script>";
	exit;
	
?> 
