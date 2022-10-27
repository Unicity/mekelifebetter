<?

function right($value, $count){
	$value = substr($value, (strlen($value) - $count), strlen($value));
	return $value;
}

function left($string, $count){
	return substr($string, 0, $count);
}

	include "../dbconn_utf8.inc";

	$query = "select * from tb_member where member_kind = 'D'  limit 0,3" ;

	$result = mysql_query($query);

	$TotalArticle = 3;
	$Last	= 3;

	if ($TotalArticle) {

		for ($i = 0; $i < $Last; ++$i) {
	
			mysql_data_seek($result,$i);

			$obj = mysql_fetch_object($result);
			
			if ($i >= $First) {
			
				echo $obj->reg_no."<br>";
				$reg_temp = str_replace("-","",$obj->reg_no);
				
				echo left($reg_temp,6)."<br>";
				echo right($reg_temp,7)."<br>";

				$phone_tmp = explode("-",$obj->phone);

				echo $phone_tmp[0]."<br>";
				echo $phone_tmp[1]."<br>";
				echo $phone_tmp[2]."<br>";

				echo $obj->phone."<br>";
				echo $obj->zip."<br>";
				echo $obj->addr."<br>";
			}
		}
	}



	
$query = "update tb_userinfo set pho1 = '".$phone_tmp[0]."', pho2 = '".$phone_tmp[1]."', pho3 = '".$phone_tmp[2]."', 
																 hpho1 = '".$hphone_tmp[0]."', hpho2 = '".$hphone_tmp[1]."', hpho3 = '".$hphone_tmp[2]."', 
																 zip = '".$zip."', addr = '".$addr."', email = '".$email."' 
					 where reg_jumin1 = '".trim(left($reg_temp,6))."' and reg_jumin2 = '".trim(right($reg_temp,7))."' ";

mysql_close($connect);
?>