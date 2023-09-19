<?
header("Content-Transfer-Encoding: UTF-8");
include "admin_session_check.inc";
include "../dbconn_utf8.inc";

//logging($s_adm_id,'open new member list (user_list.php)');
/*
function download_csv_results($results, $name = NULL)
{
    if(!$name){
        $name = md5(uniqid() . microtime(TRUE) . mt_rand()). '.csv';
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename='. $name);
    header('Pragma: no-cache');
    header("Expires: 0");

    $outstream = fopen("php://output", "wb");

    foreach($results as $result)
    {
        fputcsv($outstream, $result);
    }

    fclose($outstream);
}

*/
$query_cnt = "select max(cnt) cnt FROM (SELECT COUNT(LOWER(email)) cnt from tb_useroff where email != '' and email_send_grp = 79 and ifnull(del_tf,'N') = 'N' and memo = 'excel ok' GROUP BY LOWER(email) ) AS result";
//echo $query_cnt;
$resultt = mysql_query($query_cnt,$connect);
$row = mysql_fetch_array($resultt);
$totcnt = $row[0];
//print_r($row);
//exit;
//AND write_date >= '2021-05-03 00:00:00' AND write_Date <= '2021-05-03 23:59:59'

for($j=1; $j<=$totcnt; $j++) {
	$query = "select * from tb_useroff where email != '' and email_send_grp = 79 and ifnull(del_tf,'N') = 'N'  and memo = 'excel ok' GROUP BY LOWER(email) order by LOWER(email) ";
	$result = mysql_query($query)  or die(mysql_error());
	$num = mysql_num_rows($result);

	$fname = "mail_".date("Ymd")."_".$j.".csv";
	//$fname = "mail_".date("Ymd")."_1.csv";

	//header('Content-Type: text/csv');
	//header('Content-Disposition: attachment; filename='. $fname);
	//header('Pragma: no-cache');
	//header("Expires: 0");


	//echo "성명,이메일,핸드폰,팩스,고객정보1,고객정보2,고객정보3,고객정보4,고객정보5\n";
	$contents = iconv("utf-8","euc-kr","고객명,이메일,고객번호\n");

	$snum = "";
	$enum = "";
	$cnt =0;
	//$pre_email = "";
	echo $fname."<br>";
	$fp = fopen('./upload_usermailing_data/'.$fname, 'w+');

	for($i=0; $i<mysql_num_rows($result); $i++) {
		$row = mysql_fetch_array($result);
		if($row['email'] != ""){
			//if($pre_email != strtolower($row['email'])){
				if($snum == "") $snum = $row['mno'];
				$enum = $row['mno'];
				$cnt++;
				$fixNum = sprintf("%010d",$row['mno']);
				$cardNum = $row['reg_num'].$fixNum;
				$enNum = base64_encode($cardNum);
				//echo "$row[name],$row[email],,,$row[mno],$row[reg_num],$enNum,,\n";
				$contents .= iconv("utf-8","euc-kr",$row[name]).",$row[email],$enNum\n";

				mysql_query("update tb_useroff set memo ='excel ok1' where mno = '$enum' ") or die(mysql_error());
			//}

			//$pre_email = strtolower($row['email']);
		}
	}
	//echo $contents."<br>";

	fwrite($fp, $contents);
	fclose($fp);

}





//mysql_query("insert into tb_useroff_mail_list (fname, snum, enum, cnt, cdate) values ('$fname', '$snum', '$enum', '$cnt', now())") or die(mysql_error());
//$newGrp = mysql_insert_id();
//if($newGrp != ""){
//	mysql_query("update tb_useroff set email_send_grp='".$newGrp."' where email != '' and  email_send_yn = 'N' and ifnull(del_tf,'N') = 'N'") or die(mysql_error());
//}

mysql_close();
?>
	