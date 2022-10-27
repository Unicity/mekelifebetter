<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: admin_group_list.php
	// 	Description : 관리자 그룹 리스트 화면
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////


	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "./inc/common_function.php";

	if ($mode == "add") {

		$group_name		= str_quote_smart(trim($group_name));
		$group_id			= str_quote_smart(trim($group_id));
		$id						= str_quote_smart(trim($id));
		$page					= str_quote_smart(trim($page));

		$query = "insert into tb_admin_group (group_name) values ('$group_name')";

		mysql_query($query) or die("Query Error");


		$in_id = mysql_insert_id();
		logging_admin($s_adm_id, "add group ".$group_name."(".$in_id.")");

		mysql_close($connect);

		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
		echo "<script language=\"javascript\">\n
			alert('등록 되었습니다.');
			parent.frames[3].location = 'admin_group_list.php';
			</script>";
		exit;

	} else if ($mode == "mod") {

		$group_name = str_quote_smart(trim($group_name));
		$group_id		= str_quote_smart(trim($group_id));
		$id					= str_quote_smart(trim($id));
		$page				= str_quote_smart(trim($page));

		
	
		$query = "update tb_admin_group set 
					group_name = '$group_name'
				    where group_id = '$group_id'";
					 
		mysql_query($query) or die("Query Error");

		

		mysql_close($connect);


		exit;

		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
		echo "<script language=\"javascript\">\n
			alert('수정 되었습니다.');
			</script>";
		exit;


	} else if ($mode == "del") {
		$id = str_quote_smart(trim($id));
		$id = str_replace("^", "'",$id);

		$query = "delete from tb_admin_group 
				    where group_id in $id";

		#echo $query; 					 
		mysql_query($query) or die("Query Error");

		logging_admin($s_adm_id, "delete group ".$id);


		mysql_close($connect);

		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
		echo "<script language=\"javascript\">\n
			alert('삭제 되었습니다.');
			parent.frames[3].location = 'admin_group_list.php';
			</script>";
		exit;

	}
?>