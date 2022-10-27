<?
	ini_set('memory_limit',-1);
	ini_set('max_execution_time', 60);

	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/3/7
	// 	Last Update : 2003/3/7
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.3.7 by Park ChanHo 
	// 	File Name 	: new_member_file_db.php
	// 	Description : the member information Insert and Update as file
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////


	// 2020. 6. 22 
	// register_globals = On 되어 있으므로 $cfile[name] 을 $cfile_name 로 표현 할 수 있다.
	//

	include "./admin_session_check.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";

	$sql="select A.VolumePeriod, sum(A.cnt) from (select VolumePeriod, ID, count(id) as cnt from tb_Activityreport group by VolumePeriod, ID) A where A.cnt > 1 group by VolumePeriod;";
	$result=mysql_query($sql);

	while($row = mysql_fetch_row($result)) {
		echo "VolumePeriod : ".$row[0]." | cnt : ".$row[1];
		echo "<br>";
	}
?>