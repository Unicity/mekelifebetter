<? 
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/3/7
	// 	Last Update : 2003/3/7
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.3.7 by Park ChanHo 
	// 	File Name 	: member_file_db.php
	// 	Description : the member information Insert and Update as file
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	include "admin_session_check.inc";
	include "../dbconn_utf8.inc";
	$number_id = 0;		
	$path = "../member/member_file";

	$cfile		= str_quote_smart(trim($cfile));
	$dfile		= str_quote_smart(trim($dfile));

	if ($cfile != "") {
		$cfile_ext = substr(strrchr($cfile_name, "."), 1);
	
		if ($cfile_ext != "txt" && $cfile_ext != "TXT")
		{
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			echo "<script>
				window.alert('확장자가 txt, TXT외에는 업로드할수가 없습니다.');
				history.go(-1);
				</script>";
				mysql_close($connect);
			exit;
		}
        	
		$cfile_strtmp = $path."/c_member.".$cfile_ext;	
		$new_cfile = "c_member.".$cfile_ext;	
        	
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
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
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
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			echo "<script>
				window.alert('확장자가 txt, TXT외에는 업로드할수가 없습니다.');
				history.go(-1);
				</script>";
				mysql_close($connect);
			exit;
		}
        	
		$dfile_strtmp = $path."/d_member.".$dfile_ext;	
		$new_dfile = "d_member.".$dfile_ext;	
        	
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
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			echo "<script>
				window.alert('$dfile_name 를 업로드할 수 없습니다.');
				history.go(-1);
				</script>";
				mysql_close($connect);
			exit;
		}
	}


	$query = "delete from tb_member where member_kind = 'C' ";
	//echo $query;
	mysql_query($query) or die("Query Error");

	$fo = fopen($path."/".$new_cfile, "r");

	while($str = fgets($fo, 3000)) {
		$number_id++;
		$a_str = explode("\",\"",$str);
		
		$reg_no = $a_str[0]; 
		$number = $a_str[1]; 
		$name = $a_str[2]; 
		$phone = $a_str[3]; 
		$temp1 = $a_str[4]; 
		$temp2 = $a_str[5]; 
		$addr = $a_str[6]; 
		$zip = $a_str[7]; 

	
		$reg_no = trim($reg_no);
		$number = trim($number);
		$name = trim($name);
		$phone = trim($phone);
		$temp1 = trim($temp1);
		$temp2 = trim($temp2);
		$addr = trim($addr);
		
		$reg_no = substr($reg_no, 1, strlen($reg_no)-1);
		
		$phone = eregi_replace("\"", "''", $phone);
		$addr = eregi_replace("\"", "''", $addr);
		$zip = eregi_replace("\"", "", $zip);

		$phone = eregi_replace("'", "''", $phone);
		$addr = eregi_replace("'", "''", $addr);

		
	   	$query = "insert into tb_member (member_id, reg_no, number, name, phone, temp1, temp2, email ,addr, zip, regdate, member_kind) values 
	   				('".$number_id."', '".$reg_no."', '".$number."', '".$name."', '".$phone."', '".$temp1."', '".$temp2."', '".$temp2."', '".$addr."', '".$zip."',now(), 'C')";
		mysql_query($query) or die("Query Error".$query);

	}
	
	fclose($fo);

	$query = "delete from tb_member where member_kind = 'D' ";
	//echo $query;
	mysql_query($query) or die("Query Error");

	$fo_d = fopen($path."/".$new_dfile, "r");

	while($str = fgets($fo_d, 3000)) {
		$number_id++;

		$a_str = explode("\",\"",$str);
		
		$reg_no = $a_str[0]; 
		$number = $a_str[1]; 
		$name = $a_str[2]; 
		$phone = $a_str[3]; 
		$temp1 = $a_str[4]; 
		$temp2 = $a_str[5]; 
		$addr = $a_str[6]; 
		$zip = $a_str[7]; 

	
		$reg_no = trim($reg_no);
		$number = trim($number);
		$name = trim($name);
		$phone = trim($phone);
		$temp1 = trim($temp1);
		$temp2 = trim($temp2);
		$addr = trim($addr);
		
		$reg_no = substr($reg_no, 1, strlen($reg_no)-1);
		
		$phone = eregi_replace("\"", "''", $phone);
		$addr = eregi_replace("\"", "''", $addr);
		$zip = eregi_replace("\"", "", $zip);

		$phone = eregi_replace("'", "''", $phone);
		$addr = eregi_replace("'", "''", $addr);

		
	   	$query = "insert into tb_member (member_id, reg_no, number, name, phone, temp1, temp2, email ,addr, zip, regdate, member_kind) values 
	   				('".$number_id."', '".$reg_no."', '".$number."', '".$name."', '".$phone."', '".$temp1."', '".$temp2."', '".$temp2."', '".$addr."', '".$zip."',now(), 'D')";
		mysql_query($query) or die("Query Error".$query);

	}
	
	fclose($fo_d);
	
	mysql_close($connect);

	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	echo "<script language=\"javascript\">\n
		alert('정보가 갱신 되었습니다.');
		parent.frames[3].location = 'member_input_result.php';
		</script>";
	exit;
	
?> 
