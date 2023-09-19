<?
	include "admin_session_check.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";
	include "./inc/common_function.php";

	function trimSpace ($array)
	{
		$temp = Array();
		for ($i = 0; $i < count($array); $i++)
				$temp[$i] = trim($array[$i]);

		return $temp;
		
	}


	$mode					= str_quote_smart(trim($mode));

	if ($mode == "add") {

		$title		= str_quote_smart(trim($title));
		$contents = str_quote_smart(trim($contents));
		$sms_type = str_quote_smart(trim($sms_type));
		$send_state = "0";

		$query = "insert into tb_sms_master (title, contents, callback, sms_type, send_state, reg_adm, reg_date) values 
					  ('$title', '$contents', '$callback', '$sms_type', '$send_state', '".$s_adm_id."', now())";

		mysql_query($query) or die("Query Error");

		logging($s_adm_id,'add sms '.$title);

		mysql_close($connect);

		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
		echo "<script language=\"javascript\">\n
			alert('등록 되었습니다.');
			parent.frames[3].location = 'sms_list.php';
			</script>";
		exit;

	} else if ($mode == "mod") {

		$seq_no		= str_quote_smart(trim($seq_no));
		$title		= str_quote_smart(trim($title));
		$contents = str_quote_smart(trim($contents));
		$sms_type = str_quote_smart(trim($sms_type));



		$query = "update tb_sms_master set 
					title = '$title',
					contents = '$contents',
					callback = '$callback',
					sms_type = '$sms_type',
					up_adm = '$s_adm_id',
					up_date = now() 
				where seq_no = '$seq_no'";
		
		//echo $query;
		mysql_query($query) or die("Query Error");

		logging($s_adm_id,'modify sms '.$seq_no);

		mysql_close($connect);

		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
		echo "<script language=\"javascript\">\n
			alert('수정 되었습니다.');
			</script>";
		exit;

	} else if ($mode == "del") {

		$seq_no		= str_quote_smart(trim($seq_no));
		//$seq_no = str_replace("^", "'",$seq_no);

		$seq_no = str_replace("_", ",",$seq_no);


		if($seq_no == ""){
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			echo "<script language=\"javascript\">\n
				alert('삭제 대상이 없습니다.');
				history.back();
				</script>";
			exit;
		}

		//$query = "delete from tb_sms_master where seq_no in $seq_no";
		$query = "update tb_sms_master set del_tf = 'Y', del_adm = '".$s_adm_id."', del_date = now()  where seq_no in (".$seq_no.")";

		mysql_query($query) or die("Query Error");

		logging($s_adm_id,'delete sms '.$seq_no);

		mysql_close($connect);

		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
		echo "<script language=\"javascript\">\n
			alert('삭제 되었습니다.');
			parent.frames[3].location = 'sms_list.php';
			</script>";
		exit;

	}
?>			parent.frames[3].location = 'sms_list.php';
			</script>";
		exit;

	}
?>