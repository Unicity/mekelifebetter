<?php
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";
	
	$confirmDate = $_REQUEST['confirmDate'];
	$member_no	= str_quote_smart(trim($member_no));
	$mode	    = str_quote_smart(trim($mode));
	$reg_status	= str_quote_smart(trim($reg_status));
	$member_no = trim($member_no);
	$member_no = str_replace("^", "'",$member_no);
	
	$cancelQuery = "select cancelDate from distributorshipCancel where no = '".$member_no."'";
	
	$query_result = mysql_query($cancelQuery);
	$query_row = mysql_fetch_array($query_result);
	
	$cancelDate = $query_row["cancelDate"];
	$flag = $_POST['flag'];
	$forUpdateNo = $_POST['no'];
	$delFlag = $_POST['flag'];

	if($reg_status == '4'){
		$Date = "confirm_date = now()";
		$admName = "confirm_ma = '$s_adm_name'";
	}else if($reg_status == '3'){
		$Date = "print_date = now()";
		$admName = "print_ma = '$s_adm_name'";
	}else if($reg_status == '8'){
		$Date = "wait_date = now()";
		$admName = "wait_ma = '$s_adm_name'";
	}else if($reg_status == '9'){
		$Date = "reject_date = now()";
		$admName = "reject_ma = '$s_adm_name'";
	}else if($reg_status == '2'){
		$Date = "cancelDate = now()";
	}

	//완료인 경우 회원테이블, 중복회원테이블에서 탈퇴회원테이블로 이관 후 회원정보 삭제
	if($reg_status == '4' && $member_no != ''){
	
		$tables = array('tb_userinfo', 'tb_userinfo_dup');
		
		for($j=0; $j<count($tables); $j++) {
			
			$result = mysql_query("select * from ".$tables[$j]." where number = '".$member_no."'") or die(mysql_error());	
			
			for($i=0; $i<mysql_num_rows($result); $i++) {
				$row = mysql_fetch_object($result);

				//echo $row->member_no."<br>";

				if($row->member_no != ''){

					$sql = "";
					$fields = array();
					$values = array();
					foreach($row as $key => $val){
						if($key == "api_fail") $key = "sms_send_flag"; //tb_userinfo와 tb_userinfo_dup 필드명 다름 주의!!!

						$fields[] = $key;
						
						if(strtolower($val) == "null") $values[] = "NULL";
						else $values[] = "'".$val."'";
					}

					$fields[] = "tables"; 
					$values[] = "'".$tables[$j]."'";

					$fields = ' (' . implode(', ', $fields) . ')';
					$values = '('. implode(', ', $values) .')';

					$sql = "INSERT INTO tb_userinfo_out ".$fields .' VALUES '. $values;
					//echo $sql."<br>";

					$resultin = mysql_query($sql) or die(mysql_error());	

					if($resultin){
						mysql_query("delete from ".$tables[$j]." where number = '".$row->member_no."'") or die(mysql_error());	
					}
				}
			}
		}
	}
	//회원탈퇴 처리 끝


	if($flag=='update'){
		$queryUpdate = "update distributorshipCancel SET 
						reg_status = $reg_status
		 				where no = $forUpdateNo";
		echo $queryUpdate;
		mysql_query($queryUpdate) or die("Query Error");
	
		echo "<script>alert('수정이 완료 되었습니다.');
			parent.frames[3].location = 'distributorshipCancel_admin.php';	
			</script>";
	
	}
	
	if($reg_status != '2'){
	$query = "update distributorshipCancel set
				     reg_status = $reg_status,
					 $Date,	     
					$admName
			where no = $member_no";

	}else if($reg_status == '2'){
		
		$query = "update distributorshipCancel set
		reg_status = $reg_status
		where no = $member_no";
		 
	}
	mysql_query($query) or die("Query Error");
	
	echo "<script>alert('완료 되었습니다.');
			parent.frames[3].location = 'distributorshipCancel_admin.php';	
		 </script>";
	
	
?>