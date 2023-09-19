<?php 
session_start();
ini_set("display_errors", 0);
header("X-Frame-Options: SAMEORIGIN");
	
/**
* @brief iframe, script�ڵ� ����
**/
function removeJSEvent_session($matches) 
{
	$tag = strtolower($matches[1]);
	if(preg_match('/(src|href)=("|\'?)javascript:/i',$matches[2])) $matches[0] = preg_replace('/(src|href)=("|\'?)javascript:/i','$1=$2_javascript:', $matches[0]);
	return preg_replace('/ on([a-z]+)=/i',' _on$1=',$matches[0]);
}

function str_quote_smart_session($value) {

	/* sql injection ó�� */
	$banlist = array(
			"insert", "select", "update", "delete", "distinct", "having", "truncate", "replace",
			"handler", "like", " as ", "or ", "procedure", "limit", "order by", "group by", "asc", "desc", "iframe", "script", "meta", "style"
	);

	//$value = addslashes($value);
	$value = trim(str_ireplace($banlist, '', $value));
	/* //sql injection ó�� */

	/* XSS ó�� */
	// iframe ����

	$value = preg_replace("!<iframe(.*?)<\/iframe>!is", '', $value);

	// script code ����
	$value = preg_replace("!<script(.*?)<\/script>!is", '', $value);

	// meta �±� ����
	$value = preg_replace("!<meta(.*?)>!is", '', $value);

	// style �±� ����
	$value = preg_replace("!<style(.*?)<\/style>!is", '', $value);

	// XSS ����� ���� �̺�Ʈ ����
	$value = preg_replace_callback("!<([a-z]+)(.*?)>!is", removeJSEvent_session, $value);

	/**
	* �̹����� ��������� �±׿��� src�� ������ ������ �ǿ��ϴ� �ڵ带 ����
	* - ����� ���� : ������
	**/
	//$value = preg_replace_callback("!<([a-z]+)(.*?)>!is", removeSrcHack, $value);
	/* //XSS ó�� */

	// stripslashes
	if (get_magic_quotes_gpc()) {
		$value = htmlspecialchars(stripslashes($value));
	}else{
		$value = htmlspecialchars($value);
	}

	//$value = htmlentities($value, ENT_QUOTES, 'UTF-8');

	$value = str_replace("\"","&quot;", $value);
	$value = str_replace("'","&#039;", $value);
	$value = str_replace("%","&#037;", $value);
	$value = str_replace(">","&gt;", $value);
	$value = str_replace("<","&lt;", $value);

//	if(!is_numeric($value)) {
//		$value = "'" . mysql_real_escape_string($value) . "'";
//	}


	return $value;
}

	$s_adm_id = str_quote_smart_session($s_adm_id);

	if(!isset($_SESSION["s_adm_id"])){
?>
	<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-Frame-Options" content="SAMEORIGIN" />
	<script language="javascript">
		alert("������ ���� �Ǿ� �ٽ� �α��� �ϼž� �մϴ�.");
		parent.location="/manager_utf8/admin.php";
	</script>
	</html>
<?
		exit;
	} else {
		$s_adm_id = $s_adm_id;
	}
?>
