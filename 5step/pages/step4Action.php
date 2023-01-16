<?php session_start();?>
<?php 
	include "../inc/dbconn.php";
	

	$userNo=$_SESSION["username"];
	$crdateDate = $_POST['crdateDate'];
	$amonut = $_POST['amonut'];
	$programID = $_POST['programID'];
	$productID = $_POST['productID'];
	$step = $_POST['step'];
	$productVal = $_POST['productVal']; 
	$productKey = $_POST['productKey'];

	$pattern = ",";
	$arrProductVal = split($pattern,$productVal);
	for($i=0;$i< sizeof($arrProductVal);$i++){
		$arrProductVal[$i];
	}
	
	$arrProductKey = split($pattern,$productKey);
	for($i=0;$i< sizeof($arrProductKey);$i++){
		$arrProductKey[$i];
	}
/*
	echo "<pre>\n";
	  	print_r($arrProductVal);
	echo "</pre>\n";
	
	echo "<pre>\n";
		print_r($arrProductKey);
	echo "</pre>\n";
*/	
	$arrCount = count($arrProductKey);
	
	$alert = "등록이 완료 되었습니다.";
	$alert=iconv("utf-8", "euc-kr", $alert);

	for($i=0; $i<$arrCount;$i++){
		if($arrProductVal[$i] !=0 ){
			$queryInsert1 = "insert into StepRecord (ProgramID,ProductID,Amount,CreateDate,member_no,Step) values ('".$programID."', '".$arrProductKey[$i]."', '".$arrProductVal[$i]."','".$crdateDate."','".$userNo."','".$step."')";
			mysql_query($queryInsert1) or die("Query Error");
		}
	}
	echo "<script>alert('".$alert."');history.back();</script>";

	?>