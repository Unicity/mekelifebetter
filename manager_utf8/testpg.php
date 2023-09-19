<?
header('Content-Type: text/html; charset=euc-kr');

//session_start();

$default_charset = "euckr";
	include "../dbconn.inc";

    $query = "select count(*) from tb_kspay";
    $result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
    $TotalArticle = $row[0];
echo "row==>".$TotalArticle;
echo "����� ��";
?>