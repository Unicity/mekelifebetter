<?php 
header("Content-Type:text/html;charset=euc-kr"); 
@extract($_GET);
@extract($_POST);
@extract($_SESSION);

require_once "mysql-wrapper.php";

//$connect = mysql_pconnect ("localhost","unicity_db_user","unicity!2011");
$connect = mysql_pconnect ("192.168.20.39","unicity_db_user","unicity!2011");
mysql_select_db("unicity",$connect);

mysql_query("SET NAMES euckr");
mysql_query("SET character_set_server = euckr");

//mysql_set_charset('euckr',LINK); 

function rtnSpecialCharCheck($str) {
	if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $str,  $match)) {
		return false;
	}
	return true;
}

function protect_xss_v2($s){
	$ret="";
	if(is_array($s)){
		$ret = $s;
	}else{
		$ret = trim($s);
		if($ret!=""){
			$ret		= str_replace("<","&lt;",$ret);
			$ret		= str_replace(">","&gt;",$ret);
			$ret		= str_replace('"','&#34;',$ret);
			$ret		= str_replace("'","&#39;",$ret);
			$ret		= str_replace("(","&#40;",$ret);
			$ret		= str_replace(")","&#41;",$ret);
			$ret		= str_replace("-","&#45;",$ret);
		}
	}
	return $ret;
}


/**
* @brief iframe, script코드 제거
**/
function removeJSEvent($matches) 
{
	$tag = strtolower($matches[1]);
	if(preg_match('/(src|href)=("|\'?)javascript:/i',$matches[2])) $matches[0] = preg_replace('/(src|href)=("|\'?)javascript:/i','$1=$2_javascript:', $matches[0]);
	return preg_replace('/ on([a-z]+)=/i',' _on$1=',$matches[0]);
}

function removeSrcHack($matches) 
{
	$tag = strtolower(trim($matches[1]));

	$buff = trim(preg_replace('/(\/>|>)/','/>',$matches[0]));
	$buff = preg_replace_callback('/([^=^"^ ]*)=([^ ^>]*)/i', fixQuotation, $buff);

	$oXmlParser = new XMLParser();
	$xml_doc = $oXmlParser->parse($buff);

	// src값에 module=admin이라는 값이 입력되어 있으면 이 값을 무효화 시킴
	$src = $xml_doc->{$tag}->attrs->src;
	$dynsrc = $xml_doc->{$tag}->attrs->dynsrc;
	if(_isHackedSrc($src) || _isHackedSrc($dynsrc) ) return sprintf("<%s>",$tag);

	return $matches[0];
}

function _isHackedSrc($src) {
	if(!$src) return false;
	if($src && preg_match('/javascript:/i',$src)) return true;
	if($src) 
	{
		$url_info = parse_url($src);
		$query = $url_info['query'];
		$queries = explode('&', $query);
		$cnt = count($queries);
		for($i=0;$i<$cnt;$i++) 
		{
			$pos = strpos($queries[$i],'=');
			if($pos === false) continue;
			$key = strtolower(trim(substr($queries[$i], 0, $pos)));
			$val = strtolower(trim(substr($queries[$i] ,$pos+1)));
			if(($key == 'module' && $val == 'admin') || $key == 'act' && preg_match('/admin/i',$val)) return true;
		}
	}
	return false;
}


// sql injection 처리
function quote_smart($value) {

	/* sql injection 처리 */
	$banlist = array(
			"insert", "select", "update", "delete", "distinct", "having", "truncate", "replace",
			"handler", "like", " as ", "or ", "procedure", "limit", "order by", "group by", "asc", "desc"
	);

	$value = trim(str_replace($banlist, '', strtolower($value)));
	/* //sql injection 처리 */

	//$value = addslashes($value);

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
	$value = preg_replace_callback("!<([a-z]+)(.*?)>!is", removeJSEvent, $value);

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

//	if(!is_numeric($value)) {
//		$value = "'" . mysql_real_escape_string($value) . "'";
//	}

	$value = htmlentities($value, ENT_QUOTES, 'UTF-8');

	$value = str_replace("\"","&quot;", $value);
	$value = str_replace("'","&#039;", $value);
	//$value = str_replace("%","&#037;", $value);
	$value = str_replace(">","&gt;", $value);
	$value = str_replace("<","&lt;", $value);

	return $value;
}

// sql injection 처리
function upper_quote_smart($value) {

	/* sql injection 처리 */
	$banlist = array(
			"INSERT", "SELECT", "UPDATE", "DELETE", "DISTINCT", "HAVING", "TRUNCATE", "REPLACE",
			"HANDLER", "LIKE", " AS ", "OR ", "PROCEDURE", "LIMIT", "ORDER BY", "GROUP BY", "ASC", "DESC"
	);

	$value = trim(str_replace($banlist, '', strtoupper($value)));
	/* //sql injection 처리 */

	//$value = addslashes($value);

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
	$value = preg_replace_callback("!<([a-z]+)(.*?)>!is", removeJSEvent, $value);

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

//	if(!is_numeric($value)) {
//		$value = "'" . mysql_real_escape_string($value) . "'";
//	}

	$value = htmlentities($value, ENT_QUOTES, 'UTF-8');

	$value = str_replace("\"","&quot;", $value);
	$value = str_replace("'","&#039;", $value);
	//$value = str_replace("%","&#037;", $value);
	$value = str_replace(">","&gt;", $value);
	$value = str_replace("<","&lt;", $value);

	return $value;
}

function str_quote_smart($value) {

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
	$value = preg_replace_callback("!<([a-z]+)(.*?)>!is", removeJSEvent, $value);

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

function html_quote_smart($value) {

	/* sql injection 처리 */
	$banlist = array(
			"insert", "select", "update", "delete", "distinct", "having", "truncate", "replace",
			"handler", "like", " as ", "or ", "procedure", "limit", "order by", "group by", "asc", "desc", "iframe", "script", "meta", "style",
			"INSERT", "SELECT", "UPDATE", "DELETE", "DISTINCT", "HAVING", "TRUNCATE", "REPLACE",
			"HANDLER", "LIKE", " AS ", "OR ", "PROCEDURE", "LIMIT", "ORDER BY", "GROUP BY", "ASC", "DESC", "IFRAME", "SCRIPT", "META", "STYLE"
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
	$value = preg_replace_callback("!<([a-z]+)(.*?)>!is", removeJSEvent, $value);

	/**
	* 이미지나 동영상등의 태그에서 src에 관리자 세션을 악용하는 코드를 제거
	* - 취약점 제보 : 김상원님
	**/
	//$value = preg_replace_callback("!<([a-z]+)(.*?)>!is", removeSrcHack, $value);
	/* //XSS 처리 */

	// stripslashes

	//if (get_magic_quotes_gpc()) {
	//	$value = htmlspecialchars(stripslashes($value));
	//}else{
	//	$value = htmlspecialchars($value);
	//}

	//$value = htmlentities($value, ENT_QUOTES, 'UTF-8');

		$value = str_replace("\"","&quot;", $value);
		$value = str_replace("'","&#039;", $value);
		$value = str_replace("%","&#037;", $value);
//	$value = str_replace(">","&gt;", $value);
//	$value = str_replace("<","&lt;", $value);

//	if(!is_numeric($value)) {
//		$value = "'" . mysql_real_escape_string($value) . "'";
//	}


	return $value;
}

?>