<?

	include "admin_session_check.inc";
	include "../dbconn_utf8.inc";
	include "./inc/common_function.php";

	function trimSpace ($array)
	{
		$temp = Array();
		for ($i = 0; $i < count($array); $i++)
				$temp[$i] = trim($array[$i]);

		return $temp;
		
	}

	$member_id		= str_quote_smart(trim($member_id));
	$qry_str			= str_quote_smart(trim($qry_str));
	$idxfield			= str_quote_smart(trim($idxfield));
	$page					= str_quote_smart(trim($page));
	$sort					= str_quote_smart(trim($sort));
	$order				= str_quote_smart(trim($order));
	$member_kind	= str_quote_smart(trim($member_kind));

	if ($mode == "add") {

		$big_menu		= str_quote_smart(trim($big_menu));
		$small_menu = str_quote_smart(trim($small_menu));
		$menu_url		= str_quote_smart(trim($menu_url));
		$menu_info	= str_quote_smart(trim($menu_info));
		$menu_id		= str_quote_smart(trim($menu_id));

		$query = "insert into tb_admin_menu (big_menu, small_menu, menu_url, menu_info) values 
				('$big_menu', '$small_menu', '$menu_url', '$menu_info' )";

		mysql_query($query) or die("Query Error");

		logging_admin($s_adm_id, "add admin_menu ".$small_menu);


		mysql_close($connect);

		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
		echo "<script language=\"javascript\">\n
			alert('등록 되었습니다.');
			parent.frames[3].location = 'menu_list.php';
			</script>";
		exit;

	} else if ($mode == "mod") {

		$big_menu		= str_quote_smart(trim($big_menu));
		$small_menu = str_quote_smart(trim($small_menu));
		$menu_url		= str_quote_smart(trim($menu_url));
		$menu_info	= str_quote_smart(trim($menu_info));
		$menu_id		= str_quote_smart(trim($menu_id));

		$query = "update tb_admin_menu set 
					big_menu = '$big_menu',
					small_menu = '$small_menu',
					menu_url = '$menu_url',
					menu_info = '$menu_info'
				    where menu_id = '$menu_id'";
					 
		mysql_query($query) or die("Query Error");


		$change_str = "";

		if($old_big_menu != $big_menu) $change_str .= " 메뉴그룹 : ".$old_big_menu."->".$big_menu;
		if($old_small_menu != $small_menu) $change_str .= " 메뉴명 : ".$old_small_menu."->".$small_menu;
		if($old_menu_url != $menu_url)  $change_str .= " 메뉴경로 : ".$old_menu_url."->".$menu_url;
		if($old_menu_info != $menu_info)  $change_str .= " 메뉴설명 : ".$old_menu_info."->".$menu_info;

		if($change_str != "") logging_admin($s_adm_id, "modify admin_menu ".$menu_id." ".$change_str);


		mysql_close($connect);

		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
		echo "<script language=\"javascript\">\n
			alert('수정 되었습니다.');
			parent.frames[3].location = 'menu_list.php';
			</script>";
		exit;

	} else if ($mode == "del") {

		$menu_id		= str_quote_smart(trim($menu_id));
		$menu_id = str_replace("^", "'",$menu_id);

		$query = "delete from tb_admin_menu 
				    where menu_id in $menu_id";

		#echo $query; 					 
		mysql_query($query) or die("Query Error");

		logging_admin($s_adm_id, "delete admin_menu ".$menu_id);

		mysql_close($connect);

		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
		echo "<script language=\"javascript\">\n
			alert('삭제 되었습니다.');
			parent.frames[3].location = 'admin_list.php';
			</script>";
		exit;

	}
?>	