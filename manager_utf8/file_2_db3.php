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
	
	set_time_limit(0); 

	include "../dbconn_utf8.inc";
	$number_id = 0;		
	$path = "../member/member_file";

//	$query = "delete from tb_distrib_perfs ";
	//echo $query;
//	mysql_query($query) or die("Query Error".$query);

	$new_cfile = "distrib_perfs_200406_2.txt";

	$fo = fopen($path."/".$new_cfile, "r");

	while($str = fgets($fo, 3000)) {
		$number_id++;

//		echo $str; 

		$a_str = explode(",",$str);

		$distributor_number = $a_str[0]; 
		$distributor_id = $a_str[1]; 
		$distributor_sponsor_id = $a_str[2]; 
		$application_received_date = $a_str[3]; 
		$commission_period_date = $a_str[4]; 
		$tax_id_number = $a_str[5]; 
		$national_id_number = $a_str[6]; 
		$distributor_level_id = $a_str[7]; 
		$highest_level_id = $a_str[8]; 
		$highest_level_date = $a_str[9]; 
		$base_personal_volume = $a_str[10]; 
		$base_group_volume = $a_str[11]; 
		$base_organizational_volume = $a_str[12]; 
		$base_team_volume = $a_str[13]; 
		$base_cum_personal_volume = $a_str[14]; 
		$base_1_organizational_volume = $a_str[15]; 
		$base_2_organizational_volume = $a_str[16]; 
		$base_3_organizational_volume = $a_str[17]; 
		$base_4_organizational_volume = $a_str[18]; 
		$base_5_organizational_volume = $a_str[19]; 
		$base_6_organizational_volume = $a_str[20]; 
		$base_7_organizational_volume = $a_str[21]; 
		$base_8_organizational_volume = $a_str[22]; 
		$base_9_organizational_volume = $a_str[23]; 
		$base_10_organizational_volume = $a_str[24]; 
		$base_11_organizational_volume = $a_str[25]; 
		$base_promotion_TV = $a_str[26]; 

	
		$distributor_number = trim($distributor_number);
		$distributor_id = trim($distributor_id);
		$distributor_sponsor_id = trim($distributor_sponsor_id);
		$application_received_date = trim($application_received_date);
		$commission_period_date = trim($commission_period_date); 
		$tax_id_number = trim($tax_id_number);
		$national_id_number = trim($national_id_number);
		$distributor_level_id = trim($distributor_level_id);
		$highest_level_id = trim($highest_level_id);
		$highest_level_date = trim($highest_level_date);
		$base_personal_volume = trim($base_personal_volume);
		$base_group_volume = trim($base_group_volume);
		$base_organizational_volume = trim($base_organizational_volume);
		$base_team_volume = trim($base_team_volume); 
		$base_cum_personal_volume = trim($base_cum_personal_volume); 
		$base_1_organizational_volume = trim($base_1_organizational_volume);
		$base_2_organizational_volume = trim($base_2_organizational_volume);
		$base_3_organizational_volume = trim($base_3_organizational_volume);
		$base_4_organizational_volume = trim($base_4_organizational_volume);
		$base_5_organizational_volume = trim($base_5_organizational_volume);
		$base_6_organizational_volume = trim($base_6_organizational_volume);
		$base_7_organizational_volume = trim($base_7_organizational_volume);
		$base_8_organizational_volume = trim($base_8_organizational_volume);
		$base_9_organizational_volume = trim($base_9_organizational_volume);
		$base_10_organizational_volume = trim($base_10_organizational_volume);
		$base_11_organizational_volume = trim($base_11_organizational_volume);
		$base_promotion_TV = trim($base_promotion_TV);
				
		$distributor_number = eregi_replace("\"", "''", $distributor_number);
		$application_received_date = eregi_replace("\"", "''", $application_received_date);
		$commission_period_date = eregi_replace("\"", "''", $commission_period_date);
		$tax_id_number = eregi_replace("'", "''", $tax_id_number);
		$tax_id_number = eregi_replace("\"", "''", $tax_id_number);
		$national_id_number = eregi_replace("\"", "''", $national_id_number);
		$highest_level_date = eregi_replace("\"", "''", $highest_level_date);
		
	   	$query = "insert into tb_distrib_perfs (distributor_number, distributor_id, distributor_sponsor_id, 
	   				application_received_date, commission_period_date, tax_id_number, national_id_number, 
	   				distributor_level_id, highest_level_id, highest_level_date, base_personal_volume,
	   				base_group_volume, base_organizational_volume, base_team_volume, base_cum_personal_volume, 
	   				base_1_organizational_volume, base_2_organizational_volume, base_3_organizational_volume,
	   				base_4_organizational_volume, base_5_organizational_volume, base_6_organizational_volume,
	   				base_7_organizational_volume, base_8_organizational_volume, base_9_organizational_volume,
	   				base_10_organizational_volume, base_11_organizational_volume, base_promotion_TV) values 
	   				('".$distributor_number."', '".$distributor_id."', '".$distributor_sponsor_id."', 
	   				 '".$application_received_date."', '".$commission_period_date."', '".$tax_id_number."', '".$national_id_number."', 
	   				 '".$distributor_level_id."', '".$highest_level_id."', '".$highest_level_date."', '".$base_personal_volume."', 
	   				 '".$base_group_volume."', '".$base_organizational_volume."', '".$base_team_volume."', '".$base_cum_personal_volume."', 
	   				 '".$base_1_organizational_volume."', '".$base_2_organizational_volume."', '".$base_3_organizational_volume."',
	   				 '".$base_4_organizational_volume."', '".$base_5_organizational_volume."', '".$base_6_organizational_volume."',
	   				 '".$base_7_organizational_volume."', '".$base_8_organizational_volume."', '".$base_9_organizational_volume."',
	   				 '".$base_10_organizational_volume."', '".$base_11_organizational_volume."', '".$base_promotion_TV."' )";

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
