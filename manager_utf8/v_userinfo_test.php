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


	$query = "delete from v_userinfo";
	mysql_query($query) or die("Query Error".$query);

	$query = "select a.number, a.name, a.achived_level, a.sex, a.area,
        		a.ages, a.member_kind, a.appdate, a.temp1, a.temp2, b.interest, 
				b.email, b.email_flag, b.hpho1, b.hpho2, b.hpho3, b.visit_count, b.ldate 
				from tb_member a, tb_userinfo b 
				where a.number = b.number
				and b.email is not null ";

	$result = mysql_query($query);
		
	while($row = mysql_fetch_array($result)) {

		$number = $row[number];
		$name = $row[name];
		$achived_level = $row[achived_level];
		$sex = $row[sex];
		$area = $row[area];
		$ages = $row[ages];
		$member_kind = $row[member_kind];
		$appdate = $row[appdate];
		$temp1 = $row[temp1];
		$temp2 = $row[temp2];
		$interest = $row[interest];
		$email = $row[email];
		$email_flag = $row[email_flag];
		$visit_count = $row[visit_count];
		$ldate = $row[ldate];
		$hpho1 = $row[hpho1];
		$hpho2 = $row[hpho2];
		$hpho3 = $row[hpho3];


	   	$query = "insert into v_userinfo (number, name, achived_level, sex, area, ages, member_kind, appdate, temp1, temp2,
	   				interest, email, email_flag, visit_count, ldate, hpho1, hpho2, hpho3) values 
	   				('".$number."', '".$name."', '".$achived_level."', '".$sex."', '".$area."', '".$ages."', '".$member_kind."', '".$appdate."', 
	   				 '".$temp1."', '".$temp2."', '".$interest."', '".$email."', '".$email_flag."', '".$visit_count."', '".$ldate."',
	   				 '".$hpho1."', '".$hpho2."', '".$hpho3."')";
		mysql_query($query) or die("Query Error".$query);
		
	}

	mysql_close($connect);

?>