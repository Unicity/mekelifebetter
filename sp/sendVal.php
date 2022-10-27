<?php
include "includes/config/config.php";
$num1 = $_GET['num1'];

$query = "select * from tb_change_sponsor where no = '".$num1."'";
$result = mysql_query($query);
$list = mysql_fetch_array($result);

$memberNo = $list[member_no];
$memberName = $list[member_name];
$sponsorNo = $list[sponsor_no];
$sponsorName = $list[sponsor_name];
$chSponsorNo = $list[ch_sponsor_no];
$chSponName = $list[ch_sponsor_name];
$num = $list[no];
echo json_encode(array("memberNo" => $memberNo, "memberName" => $memberName,"sponsorNo" => $sponsorNo,"sponsorName" => $sponsorName,"chSponsorNo" => $chSponsorNo,"chSponsorName" => $chSponName,"num"=>$num ));




?>