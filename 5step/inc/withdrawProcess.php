<?php 
	header("Content-Type: text/html; charset=UTF-8");
	if (!include_once("./dbconn.php")){
		echo "The config file could not be loaded";
	}
	include "./function.php";
	
	$username = isset($_POST['username']) ? $_POST['username'] : '';

	if($username == '' || isempty($username)) {
		DisplayAlert("잘못된 접근입니다.");
		moveTo("../pages/mainpage.php");
	}
	
	logging($username, 'resign');
	$tempID = date("YmdHis");
	echo $tempID;
	 
	$deleteUserQuery = "DELETE FROM User WHERE id ='$username'; ";
	$deleteUserInfoQuery = "DELETE FROM UserInfo WHERE id ='$username'; ";
	
	$updatehealthCheckQuery = "UPDATE healthCheck set id='$tempID' WHERE id ='$username'; ";
	$updateProgramMasterQuery = "UPDATE ProgramMaster set userid='$tempID' WHERE userid ='$username'; ";
	$updateStepRecordQuery = "UPDATE StepRecord set member_no='$tempID' WHERE member_no ='$username'; ";

	 
	 
   //User Table Record 삭제
    mysql_query($deleteUserQuery) or die("deleteUserQuery Error");
    mysql_query($deleteUserInfoQuery) or die("deleteUserInfoQuery Error");
   
   //레코드 user id update
    mysql_query($updatehealthCheckQuery) or die("deleteUserInfoQuery Error");
    mysql_query($updateProgramMasterQuery) or die("deleteUserInfoQuery Error");
    mysql_query($updateStepRecordQuery) or die("deleteUserInfoQuery Error");

    logout();

	moveTo("../index.html");
?>