<?PHP
$ext_arr = array ('PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST',
                  'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS',
                  'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS');
$ext_cnt = count($ext_arr);
for ($i=0; $i<$ext_cnt; $i++) {
    // POST, GET 으로 선언된 전역변수가 있다면 unset() 시킴
    if (isset($_GET[$ext_arr[$i]]))  unset($_GET[$ext_arr[$i]]);
    if (isset($_POST[$ext_arr[$i]])) unset($_POST[$ext_arr[$i]]);
}
$DB_host = '54.180.152.178';
$DB_id = 'autoship';
$DB_pass = 'inxide1!!';
$DB_name = 'autoship';

define('G5_MYSQL_HOST', $DB_host);
define('G5_MYSQL_USER', $DB_id);
define('G5_MYSQL_PASSWORD', $DB_pass);
define('G5_MYSQL_DB', $DB_name);
define('G5_MYSQL_SET_MODE', false);

define('MB_MYSQL_HOST', '10.107.133.149');
define('MB_MYSQL_USER', 'unicity_db_user');
define('MB_MYSQL_PASSWORD', 'unicity!2011');
define('MB_MYSQL_DB', 'makelifebetter');

// MySQLi 사용여부를 설정합니다.
define('G5_MYSQLI_USE', true);

// 에러를 표시하려면 TRUE 로 변경
define('G5_DISPLAY_SQL_ERROR', FALSE);

// Browscap 사용여부를 설정합니다.
define('G5_BROWSCAP_USE', true);

// 접속자 기록 때 Browscap 사용여부를 설정합니다.
define('G5_VISIT_BROWSCAP_USE', false);

// escape string 처리 함수 지정
// addslashes 로 변경 가능
define('G5_ESCAPE_FUNCTION', 'sql_escape_string');


class Security{
/*
 * XSS filter
 *
 * This was built from numerous sources
 * (thanks all, sorry I didn't track to credit you)
 *
 * It was tested against *most* exploits here: http://ha.ckers.org/xss.html
 * WARNING: Some weren't tested!!!
 * Those include the Actionscript and SSI samples, or any newer than Jan 2011
 *
 *
 * TO-DO: compare to SymphonyCMS filter:
 * https://github.com/symphonycms/xssfilter/blob/master/extension.driver.php
 * (Symphony's is probably faster than my hack)
 */
  public function xss_clean($data)
  {

      $data = preg_replace('/(<)(|\/)(\!|\?|html|head|title|meta|body|script|style|base|noscript|form|input|select|option|optgroup|textarea|button|label|fieldset|legend|iframe|embed|object|param|frameset|frame|noframes|basefont|applet| isindex|xmp|plaintext|listing|bgsound|marquee|blink|noembed|comment|xml)/i', '&lt;$3', $data);
    // Fix &entity\n;

    $data = str_replace(array('&amp;','&lt;','&gt;','document.cookie'), array('&amp;amp;','&amp;lt;','&amp;gt;',''), $data);
    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
    $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
    $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

    // Remove any attribute starting with "on" or xmlns
    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

    // Remove javascript: and vbscript: protocols
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

    // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

    // Remove namespaced elements (we do not need them)
    $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

    do
    {
      // Remove really unwanted tags
      $old_data = $data;
      $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
    }
    while ($old_data !== $data);

    // we are done...
    return $data;

  }

}
$v3= new Security();
foreach($_POST as $k=>$v){

    if(gettype($v)=='string'){

        $_POST[$k] = $v3->xss_clean($v);
    }
}
foreach($_GET as $k=>$v){

    if(gettype($v)=='string'){

        $_GET[$k] = $v3->xss_clean($v);
    }
}
@extract($_GET);
@extract($_POST);
@extract($_SERVER);
?>
