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

	include "../admin_session_check.inc";
	include "../../dbconn_utf8.inc";
	$number_id = 0;		
	$path = "../../member/member_file";

	if ($cfile != "") {
		$cfile_ext = substr(strrchr($cfile_name, "."), 1);
	
		if ($cfile_ext != "txt" && $cfile_ext != "TXT")
		{
			echo "<script>
				window.alert('확장자가 txt, TXT외에는 업로드할수가 없습니다.');
				history.go(-1);
				</script>";
				mysql_close($connect);
			exit;
		}
        	
		$cfile_strtmp = $path."/c_member002.".$cfile_ext;	
		$new_cfile = "c_member002.".$cfile_ext;	
        	
/*
		if (file_exists($image_zoom_strtmp)) {
			echo "<script>
       		window.alert('$image_zoom_name 이 같은 디렉토리에 존재합니다..');
				history.go(-1);
				</script>";
			exit;
		}
*/        	
		if (!copy($cfile, $cfile_strtmp))
		{
			echo "<script>
				window.alert('$cfile_name 를 업로드할 수 없습니다.');
				history.go(-1);
				</script>";
				mysql_close($connect);
			exit;
		}
	}

	if ($dfile != "") {
		$dfile_ext = substr(strrchr($dfile_name, "."), 1);
	
		if ($dfile_ext != "txt" && $dfile_ext != "TXT")
		{
			echo "<script>
				window.alert('확장자가 txt, TXT외에는 업로드할수가 없습니다.');
				history.go(-1);
				</script>";
				mysql_close($connect);
			exit;
		}
        	
		$dfile_strtmp = $path."/d_member002.".$dfile_ext;	
		$new_dfile = "d_member002.".$dfile_ext;	
        	
/*
		if (file_exists($image_zoom_strtmp)) {
			echo "<script>
       		window.alert('$image_zoom_name 이 같은 디렉토리에 존재합니다..');
				history.go(-1);
				</script>";
			exit;
		}
*/        	
		if (!copy($dfile, $dfile_strtmp))
		{
			echo "<script>
				window.alert('$dfile_name 를 업로드할 수 없습니다.');
				history.go(-1);
				</script>";
				mysql_close($connect);
			exit;
		}
	}


	$query = "delete from tb_member_new where member_kind = 'C' ";
	//echo $query;
	mysql_query($query) or die("Query Error".$query);

	$fo = fopen($path."/".$new_cfile, "r");

	while($str = fgets($fo, 3000)) {
		$number_id++;
		$a_str = explode("	",$str);

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
		$passwd = $a_str[21]; 
	
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
		$passwd = trim($passwd);
		
		#$reg_no = substr($reg_no, 1, strlen($reg_no)-1);
		
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
		$passwd = eregi_replace("\"", "", $passwd);

		$phone = eregi_replace("'", "''", $phone);
		$hphone = eregi_replace("'", "''", $hphone);
		$addr = eregi_replace("'", "''", $addr);
		$temp1 = eregi_replace("'", "''", $temp1);
		$temp2 = eregi_replace("'", "''", $temp2);
		$passwd = eregi_replace("'", "''", $passwd);

		if (strlen($appdate) == 10) {
			$appdate = substr($appdate, 6, 4)."-".substr($appdate, 3, 2)."-".substr($appdate, 0, 2);
		}
		
	   	$query = "insert into tb_member_new (member_id, reg_no, number, name, phone, hphone, addr, zip, email ,appdate, 
	   				sex, area, ages, sponsor_number, sponsor_name, regdate, member_kind, temp1, temp2, passwd) values 
	   				('".$number_id."', '".$reg_no."', '".$number."', '".$name."', '".$phone."', '".$hphone."', '".$addr."', '".$zip."', 
	   				 '".$email."', '".$appdate."', '".$sex."', '".$area."', '".$ages."', '".$sponsor_number."', '".$sponsor_name."', now(), 
	   				 'C', '".$temp1."', '".$temp2."', '".$passwd."' )";
		mysql_query($query) or die("Query Error".$query);

	}
	
	fclose($fo);

	$query = "delete from tb_member_new where member_kind = 'D' ";
	//echo $query;
	mysql_query($query) or die("Query Error");

	$fo_d = fopen($path."/".$new_dfile, "r");

	while($str = fgets($fo_d, 3000)) {
		$number_id++;

		$a_str = explode("	",$str);

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
		$passwd = $a_str[20]; 
	
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
		$passwd = trim($passwd);
		
		#$reg_no = substr($reg_no, 1, strlen($reg_no)-1);
		
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
		$passwd = eregi_replace("\"", "", $passwd);

		$phone = eregi_replace("'", "''", $phone);
		$hphone = eregi_replace("'", "''", $hphone);
		$addr = eregi_replace("'", "''", $addr);
		$temp1 = eregi_replace("'", "''", $temp1);
		$temp2 = eregi_replace("'", "''", $temp2);
		$passwd = eregi_replace("'", "''", $passwd);

		if (strlen($appdate) == 10) {
			$appdate = substr($appdate, 6, 4)."-".substr($appdate, 3, 2)."-".substr($appdate, 0, 2);
		}
		
	   	$query = "insert into tb_member_new (member_id, reg_no, number, name, phone, hphone, addr, zip, achived_level, email ,appdate, 
	   				sex, area, ages, sponsor_number, sponsor_name, regdate, member_kind, temp1, temp2, passwd) values 
	   				('".$number_id."', '".$reg_no."', '".$number."', '".$name."', '".$phone."', '".$hphone."', '".$addr."', '".$zip."', 
	   				 '".$achived_level."', '".$email."', '".$appdate."', '".$sex."', '".$area."', '".$ages."', '".$sponsor_number."', 
	   				 '".$sponsor_name."', now(), 'D', '".$temp1."', '".$temp2."', '".$passwd."')";
		mysql_query($query) or die("Query Error".$query);

	}
	
	fclose($fo_d);
/*
	$query = "select a.number  from tb_member a, tb_userinfo b where a.number = b.number and  a.temp1 like '비활동%' ";

	$result = mysql_query($query);
		
	while($row = mysql_fetch_array($result)) {

		$number = $row[number];

	   	$query = "delete from tb_userinfo where number = '$number'";
		mysql_query($query) or die("Query Error".$query);
		
	}
*/
	#$query = "delete from v_userinfo";
	#mysql_query($query) or die("Query Error".$query);
	

	#$query = "select a.number, a.name, a.achived_level, a.sex, a.area,
  #      		a.ages, a.member_kind, a.appdate, a.temp1, a.temp2, b.interest, 
	#			b.email, b.email_flag, b.hpho1, b.hpho2, b.hpho3, b.visit_count, b.ldate, b.hphone_flag 
	#			from tb_member a, tb_userinfo b 
	#			where a.number = b.number
	#			and b.email is not null ";

	#$result = mysql_query($query);
		
	#while($row = mysql_fetch_array($result)) {

	#	$number = $row[number];
	#	$name = $row[name];
	#	$achived_level = $row[achived_level];
	#	$sex = $row[sex];
	#	$area = $row[area];
	#	$ages = $row[ages];
	#	$member_kind = $row[member_kind];
	#	$appdate = $row[appdate];
	#	$temp1 = $row[temp1];
	#	$temp2 = $row[temp2];
	#	$interest = $row[interest];
	#	$email = $row[email];
	#	$email_flag = $row[email_flag];
	#	$visit_count = $row[visit_count];
	#	$ldate = $row[ldate];
	#	$hpho1 = $row[hpho1];
	#	$hpho2 = $row[hpho2];
	#	$hpho3 = $row[hpho3];
	#	$hphone_flag = $row[hphone_flag];

	#   	$query = "insert into v_userinfo (number, name, achived_level, sex, area, ages, member_kind, appdate, temp1, temp2,
	#   				interest, email, email_flag, visit_count, ldate, hpho1, hpho2, hpho3, hphone_flag) values 
	#   				('".$number."', '".$name."', '".$achived_level."', '".$sex."', '".$area."', '".$ages."', '".$member_kind."', '".$appdate."', 
	#   				 '".$temp1."', '".$temp2."', '".$interest."', '".$email."', '".$email_flag."', '".$visit_count."', '".$ldate."',
	#   				 '".$hpho1."', '".$hpho2."', '".$hpho3."', '".$hphone_flag."')";
	#	mysql_query($query) or die("Query Error".$query);
		
	#}

	#$query = "select * from tb_userinfo where email_flag = 'N' ";

	#$result = mysql_query($query);
		
	#while($row = mysql_fetch_array($result)) {

	#	$number = $row[number];
	#	$email_flag = $row[email_flag];

	#   	$query = "update tb_member set email_flag = 'N' where number = '$number'";
	#	mysql_query($query) or die("Query Error".$query);
		
	#}

	mysql_close($connect);

	echo "<script language=\"javascript\">\n
		alert('정보가 갱신 되었습니다.');
		parent.frames[3].location = 'new_member_input_result.php';
		</script>";
	exit;
	
?> 
