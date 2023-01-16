<?php
include "./inc/dbconn.php";

$query = "select count(*) from ProgramMaster where userID not in (15745082, 137240082, 99708282, 191759082, 188022582)";

$result = mysql_query($query);

$a=  mysql_fetch_row($result);
echo mysql_error();
echo 'New Program Count: '.$a[0];
 ?>
