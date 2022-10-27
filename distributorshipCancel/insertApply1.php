<?php 
	include "includes/config/config.php";
	$applyId = $_POST['id'];
	$UserName = $_POST['UserName'];
	$Phone = $_POST['Phone'];
	$birthDay = $_POST['birthDay'];
	$year = $_POST['year'];
	$distributorshipCard = $_POST['distributorshipCard'];
	$distributorshipNote = $_POST['distributorshipNote'];
	$autoshipYn = $_POST['autoshipYn'];
	$cancelDate = $_POST['cancelDate'];
	$cancelReason = $_POST['reason'];
	$reg_status = $_POST['reg_status'];
	$address = $_POST['address'];
	$addressDetail = $_POST['addressDetail'];
	$cardetc = $_POST['etcText'];
	$noteetc = $_POST['etcText1'];
	$flag = $_POST['flag'];
	
	
	$purposeSelect = $_POST['purposeSelect'];
	$purpose = $_POST['purpose'];
	$selectText = $_POST['selectText'];
	$faxNum = $_POST['faxNum'];
	$dscChk = $_POST['dscChk'];
	$faxORdsc = $_POST['faxORdsc']; 
	$memberReg = $_POST['memberReg'];
	
	
	
	echo "autoshipYn::".$autoshipYn."<br/>";
	echo "purpose::".$purpose."<br/>";
	echo "purposeSelect::".$purposeSelect."<br/>";
	echo "faxNum::".$faxNum."<br/>";
	echo "DSC::".$dscChk."<br/>";
	echo "faxDSC::".$faxORdsc."<br/>";
    echo "selectText".$selectText."<br/>";
;
    $alert = '신청이 완료 되었습니다.';
    $alert = iconv("utf-8", "euc-kr",$alert);
    
    $already = '이미 신청 하셨습니다.';
    $already = iconv("utf-8", "euc-kr",$already);
    
    
    $query = "SELECT COUNT(*) CNT FROM distributorshipCancel where id='".$applyId."' and year = '".$year."' and reg_status != '9'";
    $result = mysql_query($query);
    
    if(mysql_result($result,0) >0){
    	echo "<script>alert('$already');history.back();</script>";
    	echo "<script>parent.close();</script>";
    }
    
    if($applyId != 0) {
    		
    	$querylog = "insert into distributorshipCancel (id, 
    													UserName,
    													Phone,
    													birthDay,
    													distributorshipCard,
    													distributorshipNote,
    													cancelDate,
    													cancelReason,
    													year,
    													reg_status,
    													address,
    													addressDetail,
    													cardetc,
    													noteetc,
    													autoshipYn,
    													mainsubchk,
    													memberReg,
    													purposeSelect,
    													purpose,
    													selectText,
    													faxNum,
    													dscChk,
    													faxORdsc) values (
    													'".$applyId."', 
    													'".$UserName."', 
    													'".$Phone."', 
    													'".$birthDay."', 
    													'".$distributorshipCard."', 
    													'".$distributorshipNote."', 
    													'".$cancelDate."', 
    													'".$cancelReason."', 
    													'".$year."', 
    													'".$reg_status."', 
    													'".$address."', 
    													'".$addressDetail."', 
    													'".$cardetc."', 
    													'".$noteetc."', 
    													'".$autoshipYn."', 
    													'".$flag."',
    													'".$memberReg."',		
    													'".$purposeSelect."',
    													'".$purpose."',
    													'".$selectText."',
    													'".$faxNum."',
    													'".$dscChk."',
    													'".$faxORdsc."')";
    
    
    	mysql_query($querylog) or die("Query Error");
    
    	echo "<script>alert('$alert');history.back();</script>";
    	echo "<script>parent.close();</script>";
    }
	
	
?>