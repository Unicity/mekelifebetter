<?session_start();
	ini_set("display_errors", 0);
	header("X-Frame-Options: SAMEORIGIN");
	
	include_once($_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/str_check.php"); 

	$s_adm_id = str_quote_smart_session($s_adm_id);

	if(!session_is_registered("s_adm_id")){
?>
	<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-Frame-Options" content="SAMEORIGIN" />
	<script language="javascript">
		alert("세션이 종료 되어 다시 로그인 하셔야 합니다.");
		parent.location="/manager_utf8/admin.php";
	</script>
	</html>
<?
		exit;
	} else {
		$s_adm_id = $s_adm_id;
	}
?>
