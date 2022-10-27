<?
/**
* @brief iframe, script코드 제거
**/
function removeJSEvent_session($matches) 
{
	$tag = strtolower($matches[1]);
	if(preg_match('/(src|href)=("|\'?)javascript:/i',$matches[2])) $matches[0] = preg_replace('/(src|href)=("|\'?)javascript:/i','$1=$2_javascript:', $matches[0]);
	return preg_replace('/ on([a-z]+)=/i',' _on$1=',$matches[0]);
}

function str_quote_smart_session($value) {

	/* sql injection 처리 */
	$banlist = array(
			"insert", "select", "update", "delete", "distinct", "having", "truncate", "replace",
			"handler", "like", " as ", "or ", "procedure", "limit", "order by", "group by", "asc", "desc", "iframe", "script", "meta", "style"
	);

	//$value = addslashes($value);
	$value = trim(str_ireplace($banlist, '', $value));
	/* //sql injection 처리 */

	/* XSS 처리 */
	// iframe 제거

	$value = preg_replace("!<iframe(.*?)<\/iframe>!is", '', $value);

	// script code 제거
	$value = preg_replace("!<script(.*?)<\/script>!is", '', $value);

	// meta 태그 제거
	$value = preg_replace("!<meta(.*?)>!is", '', $value);

	// style 태그 제거
	$value = preg_replace("!<style(.*?)<\/style>!is", '', $value);

	// XSS 사용을 위한 이벤트 제거
	$value = preg_replace_callback("!<([a-z]+)(.*?)>!is", removeJSEvent_session, $value);

	/**
	* 이미지나 동영상등의 태그에서 src에 관리자 세션을 악용하는 코드를 제거
	* - 취약점 제보 : 김상원님
	**/
	//$value = preg_replace_callback("!<([a-z]+)(.*?)>!is", removeSrcHack, $value);
	/* //XSS 처리 */

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

?>