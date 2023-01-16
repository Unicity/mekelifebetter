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
	
	$alert = "등록이 완료 되었습니다.";
	$alert=iconv("utf-8", "euc-kr", $alert);

	$queryInsert = "insert into StepRecord (ProgramID,ProductID,Amount,CreateDate,member_no, Step) values ('".$programID."', '".$productID."', '".$amonut."', '".$crdateDate."', '".$userNo."', '".$step."')";
	mysql_query($queryInsert) or die("Query Error");
	echo "<script>alert('".$alert."');history.back();</script>";

	?>