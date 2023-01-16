<?php session_start();?>
<?php 
	include "../inc/dbconn.php";
	
	
	$userNo=$_SESSION["username"];
	$crdateDate = $_POST['crdateDate'];
	$amonut = $_POST['amonut'];
	$Kcal = $_POST['Kcal'];
	$programID = $_POST['programID'];
	$productID = $_POST['productID'];
	$step = $_POST['step'];
	$type1 = $_POST['color1'];
	$type2 = $_POST['color2'];
	$type3 = $_POST['color3'];

	$alert = "등록이 완료 되었습니다.";
	$alert=iconv("utf-8", "euc-kr", $alert);

	if($step == 3){		
		if($type1 != 0){
			if($type1 == 1){
				$amonut = 1;
			}else if($type1 == 2){
				$amonut = 2;
			}
		
			$queryInsert1 = "insert into StepRecord (ProgramID,ProductID,Amount,CreateDate,member_no,Step) values ('".$programID."', '26022', '".$amonut."','".$crdateDate."','".$userNo."','".$step."')";
			mysql_query($queryInsert1) or die("Query Error");
		}
		if($type2 != 0){
			if($type2 == 1){
				$amonut = 1;
			}else if($type1 == 2){
				$amonut = 2;
			}
			
			$queryInsert2 = "insert into StepRecord (ProgramID,ProductID,Amount,CreateDate,member_no,Step) values ('".$programID."', '15744', '".$amonut."','".$crdateDate."','".$userNo."','".$step."')";
			mysql_query($queryInsert2) or die("Query Error");
		}
		if($type3 != 0){
			if($type3 == 1){
				$amonut = 1;
			}else if($type3 == 2){
				$amonut = 2;
			}
		
			$queryInsert3 = "insert into StepRecord (ProgramID,ProductID,Amount,CreateDate,member_no,Step) values ('".$programID."', '24723', '".$amonut."', '".$crdateDate."','".$userNo."','".$step."')";
			mysql_query($queryInsert3) or die("Query Error");
		}
		
		echo "<script>alert('".$alert."');history.back();</script>";
	
	}

	//$queryInsert1 = "insert into StepRecord (ProgramID,ProductID,Amount,CreateDate) values ('".$programID."', '".$productID."', '".$amonut."', '".$crdateDate."')";
	//mysql_query($queryInsert1) or die("Query Error");
	//echo "<script>alert('".$alert."');history.back();</script>";

	?>