<?php
include "admin_session_check.inc";
include "./inc/global_init.inc";
include "../dbconn_utf8.inc";
include "../AES.php";

$member_no	= str_quote_smart(trim($member_no));
		
$query = "select * from tb_userinfo_dup where member_no = $member_no";
$result = mysql_query($query);
$list = mysql_fetch_array($result);

$member_no = $list[member_no];
$number = $list[number];
$password = $list[password];
$name = $list[name];
$email = $list[email];
$reg_jumin1 = $list[reg_jumin1];
$reg_jumin2 = $list[reg_jumin2];
$tax_number = $list[tax_number];
$hpho1 = $list[hpho1];
$hpho2 = $list[hpho2];
$hpho3 = $list[hpho3];
$reg_status = $list[reg_status];
$email_date = $list[email_date];
$email_ma = $list[email_ma];
$member_kind = $list[member_kind];

$co_number = $list[co_number];
$ename = $list[ename];
$addr = $list[addr];
$addr02 = $list[addr_detail];
$zip = $list[zip];
$pho1 = $list[pho1];
$pho2 = $list[pho2];
$pho3 = $list[pho3];
$account = $list[account];
$account_bank = $list[account_bank];
$search_bank_code = $list[bank_code];

$birth_y	= $list[birth_y];
$birth_m	= $list[birth_m];
$birth_d	= $list[birth_d];

$sex			= $list[sex];

$this_yyyy = date("Y",strtotime("-10 year"));

$arr_addr = explode(" ",$addr);

$state	= $arr_addr[0];
$city		= $arr_addr[1];

if ($birth_y > substr($this_yyyy,2)) {
	$dob = "19".$birth_y."-".$birth_m."-".$birth_d;
} else {
	$dob = "20".$birth_y."-".$birth_m."-".$birth_d;
}

$JU_NO01 = decrypt($key, $iv, $reg_jumin1);
$JU_NO02 = decrypt($key, $iv, $reg_jumin2);

$password = decrypt($key, $iv, $password);
$account = decrypt($key, $iv, $account);

$mobilePhone						= $hpho1.$hpho2.$hpho3;
$homePhone							= $pho1.$pho2.$pho3;


include "../register/includes/signup_function.php";



if($number == ""){
	//신규회원가입을 진행합니다.




echo "$name, $birth_y, $birth_m, $birth_d";


$result1 = isExistAPI($name, $year, $month, $date);		
if($result1 == 'F'){
	$b = 'F';
	
}else if($result1 > 0){
	$b = 1;
	
}

echo $result1;
?>

