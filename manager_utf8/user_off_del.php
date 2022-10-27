<?
include "admin_session_check.inc";
include "./inc/global_init.inc";
include "../dbconn_utf8.inc";
include "./inc/common_function.php";


if($_POST['mode'] == 'delete'){

	if($_POST['chk'] == ""){		
		echo "삭제대상이 없습니다";
	}else{
		for($i=0; $i<count($_POST['chk']); $i++) {
			$sql = "update tb_useroff set del_tf = 'Y' where mno = '".$_POST['chk'][$i]."'";;
			mysql_query($sql) or die(mysql_error());	
		}
		echo "OK";
	}
}else{
	echo "처리가 되지 않았습니다";
}
@mysql_close();
?>
