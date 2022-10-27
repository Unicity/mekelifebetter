<?php

    /**autoship DB연결 */
    $db_host = '54.180.152.178';
    $db_user = 'autoship';
    $db_passwd = 'inxide1!!';
    $db_name = 'autoship';

    $conn = mysql_connect($db_host,$db_user,$db_passwd) or die ("데이터베이스 연결에 실패!"); 
    mysql_select_db($db_name, $conn); // DB 선택 


?>