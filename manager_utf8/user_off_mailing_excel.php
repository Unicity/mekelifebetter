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
$query = "select * from tb_useroff where email != '' and  email_send_yn = 'N' and ifnull(del_tf,'N') = 'N' order by mno desc ";
$result = mysql_query($query)  or die(mysql_error());
$num = mysql_num_rows($result);

$fname = "mail_".date("YmdHis").".csv";

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename='. $fname);
header('Pragma: no-cache');
header("Expires: 0");


//echo "성명,이메일,핸드폰,팩스,고객정보1,고객정보2,고객정보3,고객정보4,고객정보5\n";
echo iconv("utf-8","euc-kr","고객명,이메일,고객번호\n");

$snum = "";
$enum = "";
$cnt =0;

for($i=0; $i<mysql_num_rows($result); $i++) {
	$row = mysql_fetch_array($result);
	if($row['email'] != ""){
		if($snum == "") $snum = $row['mno'];
		$enum = $row['mno'];
		$cnt++;
		$fixNum = sprintf("%010d",$row['mno']);
		$cardNum = $row['reg_num'].$fixNum;
		$enNum = base64_encode($cardNum);
		//echo "$row[name],$row[email],,,$row[mno],$row[reg_num],$enNum,,\n";
		echo iconv("utf-8","euc-kr",$row[name]).",$row[email],$enNum\n";
	}
}

mysql_query("insert into tb_useroff_mail_list (fname, snum, enum, cnt, cdate) values ('$fname', '$snum', '$enum', '$cnt', now())") or die(mysql_error());
$newGrp = mysql_insert_id();
if($newGrp != ""){
	mysql_query("update tb_useroff set email_send_grp='".$newGrp."' where email != '' and  email_send_yn = 'N' and ifnull(del_tf,'N') = 'N'") or die(mysql_error());
}

mysql_close();
?>
	