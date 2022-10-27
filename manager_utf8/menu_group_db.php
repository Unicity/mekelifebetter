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

	$old = explode(",",$old_item);
	$new = explode(",",$menu_item);

	//기존권한 기준으로 신규권한 비교 - 삭제 권한 체크
	$del_right = "";		
	for($i=0; $i<count($old); $i++) {
		if(!in_array($old[$i], $new)){
			if($del_right == "") $del_right = $old[$i];
			else $del_right = $del_right.",".$old[$i];
		}
	}
	if($del_right != "") $del_right = "Delete Rights(".$del_right.")";


	//신규권한 기준의로 기존권한 비교 - 추가 권한 체크
	$new_right = "";
	for($i=0; $i<count($new); $i++) {
		if(!in_array($new[$i], $old)){
			if($new_right == "") $new_right = $new[$i];
			else $new_right = $new_right.",".$new[$i];
		}
	}

	if($new_right != "") $new_right = "Add Rights(".$new_right.")";

	
	//권한 변동이 있는 경우만 로그 저장
	$change_right = "";
	if($del_right != "" || $new_right != ""){ 
		if($del_right != "" && $new_right != "") $change_right = $new_right.",".$del_right;
		else $change_right = $new_right.$del_right;

		$change_right = "Group : ".$change_right;
		
		//mysql_query("insert into tb_user_log (adminId, actionType) values ('".$s_adm_id."', '".$change_right."')") or die("Query Error".mysql_error());
		logging_admin($s_adm_id, "modify group ".$group_id." rights ".$change_right);
	}
	

	$menu_item	= str_quote_smart(trim($menu_item));
	$group_id		= str_quote_smart(trim($group_id));

	$query = "update tb_admin_group set 
				group_item = '$menu_item'
			    where group_id = '$group_id'";
					 
	mysql_query($query) or die("Query Error");



	mysql_close($connect);

	//echo $query;
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
?>	
<script language="javascript">
	alert('수정 되었습니다.');
	parent.frames[3].location = "admin_group_check.php?group_id=<?echo $group_id?>";
</script>
