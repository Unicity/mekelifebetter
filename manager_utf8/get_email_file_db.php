<? 
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/9/10
	// 	Last Update : 2003/9/10
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.9.10 by Park ChanHo 
	// 	File Name 	: get_email_file_db.php
	// 	Description : 이메일 추출 리스트
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	include "admin_session_check.inc";
	include "../dbconn_utf8.inc";
	$number_id = 0;		
	$path = "../member/member_file";

	$cfile = str_quote_smart(trim($cfile));

	if ($cfile != "") {
		$cfile_ext = substr(strrchr($cfile_name, "."), 1);
	
		if ($cfile_ext != "txt" && $cfile_ext != "TXT")
		{
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
			<script>
				window.alert('확장자가 txt, TXT외에는 업로드할수가 없습니다.');
				history.go(-1);
				</script>";
				mysql_close($connect);
			exit;
		}
        	
		$cfile_strtmp = $path."/email_member.".$cfile_ext;	
		$new_cfile = "email_member.".$cfile_ext;	
        	
		if (!copy($cfile, $cfile_strtmp))
		{
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
			<script>
				window.alert('$cfile_name 를 업로드할 수 없습니다.');
				history.go(-1);
				</script>";
				mysql_close($connect);
			exit;
		}
	}

	$fo = fopen($path."/".$new_cfile, "r");

	while($str = fgets($fo, 3000)) {
		$number_id++;
		$a_str = explode("	",$str);
		
		$reg_no = $a_str[0]; 
		$number = $a_str[1]; 

	
		$reg_no = trim($reg_no);
		$number = trim($number);
		
		$reg_no = substr($reg_no, 1, strlen($reg_no)-1);
		
		$query = "select number, name, email from tb_member where number = '$number' ";

		$result = mysql_query($query);
		$list = mysql_fetch_array($result);
		$number = $list[number];
		$name = $list[name];
		$email = $list[email];
		$email = trim($email);


		if ($email == "") {
			$query = "select number, name, email from tb_member where number = '$number' ";
			$result = mysql_query($query);
			$list = mysql_fetch_array($result);
			$email = $list[email];
		}

		echo $number_id.", ".$number.", ".$name.", ".$email."<br>";
	}
	
	fclose($fo);
		
	mysql_close($connect);

	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	echo "<script language=\"javascript\">\n
		alert('추출된 이메일 입니다.');
		</script>";
	exit;
	
?> 
