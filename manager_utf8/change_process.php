<?php session_start();?>
<?php 

 	include "./admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "./inc/common_function.php";
	
	$processType = isset($_POST['processType']) ? $_POST['processType'] : '';
	$commissionData = isset($_POST['commissionData']) ? $_POST['commissionData'] : '';
	$s_adm_id = str_quote_smart_session($s_adm_id);
	
	$status	= str_quote_smart(trim($reg_status));
	echo $reg_status;

	
	$delFlag = $_POST['flag'];
	$delNo = $_POST['delNo'];
	$agreeDate = $_POST['agreeDate'];
	$spNo = $_POST['spNo'];

	


	$alert = '삭제가 완료 됐습니다.';
	$alertUpdate = '수정이 완료 됐습니다.';
	$alertAgree = '동의 수정이 완료 됐습니다.';
	if($delFlag == 'delete'){
	    $delQuery="delete from tb_change_sponsor where no =".$delNo;
	    mysql_query($delQuery) or die("Query Error".mysql_error());
	    
	    echo "<script>alert('$alert');
		  history.go(-2);</script>";
	}else if($delFlag == 'update'){
	    $queryUpdate = "UPDATE tb_change_sponsor SET reg_status = '".$status."', update_date = now(), update_ma='".$s_adm_name."' where no ='".$delNo."' ";
	
	    mysql_query($queryUpdate) or die("Query Error".mysql_error());
	    
	    echo "<script>alert('$alertUpdate');
		  history.go(-2);</script>";
	}else if($delFlag == 'forAgree'){
	    $agreeUpdate = "UPDATE tb_change_sponsor set sponsor_agree_yn = 'Y', agree_date = '".$agreeDate."'where no='".$delNo."' and sponsor_no='$spNo' ";
	   
	    mysql_query($agreeUpdate) or die("Query Error".mysql_error());
	    
	    echo "<script>alert('$alertAgree');
		  history.go(-1);</script>";
	}else{
    	if ($processType == '' || $commissionData == ''){
    		 echo "<script type='text/javascript'> alert('Wrong Access');self.close(); </script>";
       		 exit();
    	}
    
    	$status = $processType=='a' ? '30' : '40';
    	$records = explode(',', $commissionData );
    	//$query = "UPDATE unclaimedCommission SET `status` = '".$status."' ";
    	
    	foreach($records as $record) {
    	    $query = "UPDATE tb_change_sponsor SET `reg_status` = '".$status."', update_date = now(), update_ma='".$s_adm_name."' ";
    		$cols = explode("_", $record);
    		$where = " where `no` = '$cols[0]'  ; ";
    		$query .= $where;
    
    		 
      		mysql_query($query) or die("Query Error".mysql_error());
    
     		//logging($s_adm_id,'unclaimedCommission '.$status.' '.$where);
    
    	}
    
    	
     //	mysql_query($query) or die("Query Error".mysql_error());
    
    // 	logging($s_adm_id,'unclaimedCommission '.$status.' '.$where);
    
     	echo "<script>window.opener.location.reload();self.close();</script>";
	}


?>