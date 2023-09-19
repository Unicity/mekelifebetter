<?php


header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Mon,26 Jul 1997 05:00:00 GMT");
?>

<?php
include "../dbconn_utf8.inc";


$custHref = $_POST['href'];
$uniId = $_POST['unicityId'];




$update = "update reset_password 
					set member_href= '$href',
					new_member_no='$uniId', 
					entryDate=now(),
					flag = 'Y'
				   where member_no ='$uniId'
				    and new_member_no=0";
	
		mysql_query($update) or die("Query Error");


		$countChekc  = "select count(*) as cnt from reset_password where flag = 'N' and new_member_no = 0";
	$resultCount = mysql_query($countChekc);
	$rowCk = mysql_fetch_array($resultCount);
	$cnt = $rowCk[cnt];
	

$okVal="OK";
	echo(json_encode(array("count"=>$cnt,"okVal"=>$okVal)));




 




/*
    echo(json_encode(array("authyn"=> $r_authyn,
                           "trno"=> $r_trno,
                           "trddt"=> $r_trddt,
                           "trdtm"=> $r_trdtm,
                           "amt"=> $r_amt,
                           "authno"=> $r_authno,
                           "msg1"=> $r_msg1,
                           "msg2"=> $r_msg2 ,
                           "ordno"=> $r_ordno , 
                           "isscd"=>$r_isscd ,
                           "aqucd"=> $r_aqucd ,
                           "href"=>$custHref,
                           "name"=>$shipToName,
                           "token"=>$token

                        )));
*/
      
?>

