<?php
	include "./includes/config/config.php";
	$distId = $_POST['distID'];
	$distName = $_POST['distName'];
	$urlInfo1 = $_POST['urlInfo1'];
	$urlInfo2 = $_POST['urlInfo2'];
	$urlInfo3 = $_POST['urlInfo3'];
	$urlInfo4 = $_POST['urlInfo4'];
	$urlInfo4 = $_POST['urlInfo4'];
	$dsc = $_POST['dscSelect'];
	$url=$_POST['urlInfo0'];
	$reg_status = $_POST['reg_status'];
	
	$alert = '접수하신 내용에 대하여 면밀히 조사하여 신속하게 처리될 수 있도록 최선의 노력을 다하겠습니다.다만, 진행 및 처리결과에 대해 개별적으로 안내가 어려운 점 양해 부탁 드립니다.감사합니다.';
	$alert = iconv("utf-8", "euc-kr",$alert);
	
	if($distId != 0 || $distId != null) {
	
		$querylog = "insert into internet_sales_warning (member_no,
    													member_name,
    													url,
														url1,
    													url2,
    													url3,
    													url4,
    													dsc_sel,
														applyDate,
														reg_status) values (
    													'".$distId."',
    													'".$distName."',
    													'".$url."',
    													'".$urlInfo1."',
    													'".$urlInfo2."',
    													'".$urlInfo3."',
    													'".$urlInfo4."',
    													'".$dsc."',
    													'".$applyDate."',
    													'".$reg_status."')";
	
	
		mysql_query($querylog) or die("Query Error");
		echo "<script>alert('$alert');
		  self.close();</script>";
	
	}
	
?>