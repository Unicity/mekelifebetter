<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
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

	$query = "delete from tb_member where member_kind = 'C' ";
	//echo $query;
	mysql_query($query) or die("Query Error".$query);

	$fo = fopen($path."/c_member.txt", "r");

	while($str = fgets($fo, 3000)) {
		$number_id++;
		$a_str = explode("\",\"",$str);

		$reg_no = $a_str[0]; 
		$number = $a_str[1]; 
		$name = $a_str[2]; 
		$phone = $a_str[3]; 
		$hphone = $a_str[4]; 
		$addr = $a_str[7]; 
		$zip = $a_str[8]; 
		$email = $a_str[11]; 
		$appdate = $a_str[13]; 
		$sex = $a_str[14]; 
		$area = $a_str[15]; 
		$temp2 = $a_str[16]; 
		$ages = $a_str[17]; 
		$sponsor_number = $a_str[18]; 
		$sponsor_name = $a_str[19]; 
		$temp1 = $a_str[20]; 
	
		$reg_no = trim($reg_no);
		$number = trim($number);
		$name = trim($name);
		$phone = trim($phone);
		$hphone = trim($hphone); 
		$addr = trim($addr);
		$zip = trim($zip);
		$email = trim($email);
		$appdate = trim($appdate);
		$sex = trim($sex);
		$area = trim($area);
		$temp2 = trim($temp2);
		$ages = trim($ages);
		$sponsor_number = trim($sponsor_number); 
		$sponsor_name = trim($sponsor_name); 
		$temp1 = trim($temp1);
		
		$reg_no = substr($reg_no, 1, strlen($reg_no)-1);
		
		$phone = eregi_replace("\"", "''", $phone);
		$hphone = eregi_replace("\"", "''", $hphone);
		$addr = eregi_replace("\"", "''", $addr);
		$zip = eregi_replace("\"", "", $zip);
		$email = eregi_replace("\"", "", $email);
		$appdate = eregi_replace("\"", "", $appdate);
		$sex = eregi_replace("\"", "", $sex);
		$sponsor_number = eregi_replace("\"", "", $sponsor_number);
		$sponsor_name = eregi_replace("\"", "", $sponsor_name);
		$temp1 = eregi_replace("\"", "", $temp1);
		$temp2 = eregi_replace("\"", "''", $temp2);

		$phone = eregi_replace("'", "''", $phone);
		$hphone = eregi_replace("'", "''", $hphone);
		$addr = eregi_replace("'", "''", $addr);
		$temp1 = eregi_replace("'", "''", $temp1);
		$temp2 = eregi_replace("'", "''", $temp2);

		if (strlen($appdate) == 10) {
			$appdate = substr($appdate, 6, 4)."-".substr($appdate, 4, 2)."-".substr($appdate, 1, 2);
		}
		
	   	$query = "insert into tb_member (member_id, reg_no, number, name, phone, hphone, addr, zip, email ,appdate, 
	   				sex, area, ages, sponsor_number, sponsor_name, regdate, member_kind, temp1, temp2) values 
	   				('".$number_id."', '".$reg_no."', '".$number."', '".$name."', '".$phone."', '".$hphone."', '".$addr."', '".$zip."', 
	   				 '".$email."', '".$appdate."', '".$sex."', '".$area."', '".$ages."', '".$sponsor_number."', '".$sponsor_name."', now(), 
	   				 'C', '".$temp1."', '".$temp2."')";
		mysql_query($query) or die("Query Error".$query);

	}
	
	fclose($fo);

	$query = "delete from tb_member where member_kind = 'D' ";
	//echo $query;
	mysql_query($query) or die("Query Error");

	$fo_d = fopen($path."/d_member.txt", "r");

	while($str = fgets($fo_d, 3000)) {
		$number_id++;

		$a_str = explode("\",\"",$str);

		$reg_no = $a_str[0]; 
		$number = $a_str[1]; 
		$name = $a_str[2]; 
		$phone = $a_str[3]; 
		$hphone = $a_str[4]; 
		$addr = $a_str[7]; 
		$zip = $a_str[8]; 
		$achived_level = $a_str[9]; 
		$email = $a_str[10]; 
		$appdate = $a_str[12]; 
		$sex = $a_str[13]; 
		$area = $a_str[14]; 
		$temp2 = $a_str[15]; 
		$ages = $a_str[16]; 
		$sponsor_number = $a_str[17]; 
		$sponsor_name = $a_str[18]; 
		$temp1 = $a_str[19]; 
	
		$reg_no = trim($reg_no);
		$number = trim($number);
		$name = trim($name);
		$phone = trim($phone);
		$hphone = trim($hphone); 
		$addr = trim($addr);
		$zip = trim($zip);
		$achived_level = trim($achived_level);
		$email = trim($email);
		$appdate = trim($appdate);
		$sex = trim($sex);
		$area = trim($area);
		$temp2 = trim($temp2);
		$ages = trim($ages);
		$sponsor_number = trim($sponsor_number); 
		$sponsor_name = trim($sponsor_name); 
		$temp1 = trim($temp1);

		
		$reg_no = substr($reg_no, 1, strlen($reg_no)-1);
		
		$phone = eregi_replace("\"", "''", $phone);
		$hphone = eregi_replace("\"", "''", $hphone);
		$addr = eregi_replace("\"", "''", $addr);
		$zip = eregi_replace("\"", "", $zip);
		$email = eregi_replace("\"", "", $email);
		$appdate = eregi_replace("\"", "", $appdate);
		$sex = eregi_replace("\"", "", $sex);
		$sponsor_number = eregi_replace("\"", "", $sponsor_number);
		$sponsor_name = eregi_replace("\"", "", $sponsor_name);
		$temp1 = eregi_replace("\"", "", $temp1);
		$temp2 = eregi_replace("\"", "''", $temp2);

		$phone = eregi_replace("'", "''", $phone);
		$hphone = eregi_replace("'", "''", $hphone);
		$addr = eregi_replace("'", "''", $addr);
		$temp1 = eregi_replace("'", "''", $temp1);
		$temp2 = eregi_replace("'", "''", $temp2);

		if (strlen($appdate) == 10) {
			$appdate = substr($appdate, 6, 4)."-".substr($appdate, 4, 2)."-".substr($appdate, 1, 2);
		}
		
	   	$query = "insert into tb_member (member_id, reg_no, number, name, phone, hphone, addr, zip, achived_level, email ,appdate, 
	   				sex, area, ages, sponsor_number, sponsor_name, regdate, member_kind, temp1, temp2) values 
	   				('".$number_id."', '".$reg_no."', '".$number."', '".$name."', '".$phone."', '".$hphone."', '".$addr."', '".$zip."', 
	   				 '".$achived_level."', '".$email."', '".$appdate."', '".$sex."', '".$area."', '".$ages."', '".$sponsor_number."', 
	   				 '".$sponsor_name."', now(), 'D', '".$temp1."', '".$temp2."')";
		mysql_query($query) or die("Query Error".$query);

	}
	
	fclose($fo_d);

	$query = "select * from tb_userinfo where email_flag = 'N' ";

	$result = mysql_query($query);
		
	while($row = mysql_fetch_array($result)) {

		$number = $row[number];
		$email_flag = $row[email_flag];

	   	$query = "update tb_member set email_flag = 'N' where number = '$number'";
		mysql_query($query) or die("Query Error".$query);
		
	}
				
	mysql_close($connect);

	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
	<script language=\"javascript\">\n
		alert('정보가 갱신 되었습니다.');
		parent.frames[3].location = 'new_member_input_result.php';
		</script>";
	exit;
	
?> 
