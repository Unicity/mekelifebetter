<?php session_start();?>
<?php 
	include "../inc/dbconn.php";
	$userNo=$_SESSION["username"];
	
	$commentID = $_POST['commentID'];
	//$commentID=iconv("utf-8", "euc-kr", $commentID);

	
	$alert = "등록이 완료 되었습니다.";
	$alert=iconv("utf-8", "euc-kr", $alert);


	$queryUpdate = "update ProgramMaster set commentTxt = '".$commentID."'
					  where userID = '".$userNo."'
						and delFlag = 'N';";

	mysql_query($queryUpdate) or die("Query Error");

	echo "<script>alert('".$alert."');history.back();</script>";

	?>