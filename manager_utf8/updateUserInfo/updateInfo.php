<?php
	header("Content-Type: text/html; charset=UTF-8");
	
	include "../../AES.php";
	include "../../dbconn_utf8.inc";
	include "./function.php";

	$type 				= str_quote_smart(trim($type));

	$krname					= trim($krname);
	$number			= str_quote_smart(trim($number));
	$password			= str_quote_smart(trim($password));
	$pho1					= str_quote_smart(trim($pho1));
	$pho2					= str_quote_smart(trim($pho2));
	$pho3					= str_quote_smart(trim($pho3));
	$hpho1				= str_quote_smart(trim($hpho1));
	$hpho2				= str_quote_smart(trim($hpho2));
	$hpho3				= str_quote_smart(trim($hpho3));
	$zip					= str_quote_smart(trim($zip));
	$addr					= str_quote_smart(trim($addr));
	$addr_detail	= str_quote_smart(trim($addr02));
	$email				= str_quote_smart(trim($email));
	
	$account			= str_quote_smart(trim($account));
	$account_bank = str_quote_smart(trim($account_bank));
	
	$decryptPassword = decrypt($key, $iv, $password);

	$addressArray = explode(' ',$addr,3);
	$state = $addressArray[0];
	$city = $addressArray[1];
	$address1 = $addressArray[2];
	
	$mobilePhone = $hpho1.$hpho2.$hpho3;
	$homePhone = $pho1.$pho2.$pho3;

	$bankCode = getBankcode($account_bank);
//	$token = getToken($number, $decryptPassword);
 	
 	// $passwordToken = getPasswordResetToken($number, $mobilePhone) ;
 	// Update password
	//updateUserPassword($number, $decryptPassword);

 	//Update Address, contact numbers and emails 

 	//updateUserInfoAPI($city, $state, $zip, $address1, $addr_detail, $email, $mobilePhone, $homePhone, $token);


 	// Update bank account information
 	// updateAccountInfo($number, $account_bank, $bankCode, $krname, $account)
 	 updateUserInfo($number,$city, $state, $zip, $address1, $addr_detail, $email, $mobilePhone, $homePhone, $account_bank, $bankCode, $krname, $account);
  	
?>
 <html>
	<head>
		<SCRIPT language="javascript">
			function goBack() {
				var type = '<?php echo $type;?>';
				location.target = "frmain";
				if (type=='new') location.href = "../user_list.php";
				else location.href = "../dup_user_list.php";
			}
		</SCRIPT>
	</head>
	<body onload="goBack()">
	</body>
</html>