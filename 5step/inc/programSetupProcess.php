<?php 
	header("Content-Type: text/html; charset=UTF-8");
	if (!include_once("./dbconn.php")){
		echo "The config file could not be loaded";
	}
	include "./function.php";
	checkSessionValue();
    
	$type = isset($_POST['type']) ? $_POST['type'] : 'new'; 
	$username = $_SESSION['username'];
   
    $masterquery='UPDATE ProgramMaster SET ';
    $detailquery='INSERT INTO ProgramDetail VALUES';
   
    if(isset($_POST['programid']) && $_POST['programid'] != '') {
        $programID = $_POST['programid']; 
        $whereclause = ' WHERE programID = '.$programID;
    }
    if ($type == 'new') {
    		
    	$masterquery = 'INSERT INTO ProgramMaster SET ';
    	 
    	
    }
    $oagree = $_POST['oagree'];
    $cagree = $_POST['cagree'];

    $programname = $_POST['programname'];
    $programtype = $_POST['programtype'];
    $startdate = $_POST['startdate'];
    $period = $_POST['period'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $step3  = $_POST['step3'];
    $step4items  = $_POST['step4items'];
    $step5items  = $_POST['step5items'];
    $height = '0';
    $weight = '0';
    $bloodsugar = '0';
    if($programtype=='1'){
      $height = $_POST['height'];
      $weight = $_POST['weight'];
    }
    if($step3 == 0 || $step3 == '0'){
        $bloodsugar = '1';
    }
    
    $enddate = date('Y-m-d', strtotime(strtotime('$startdate') . "+ $period days"));

    $mastersetquery = "`consent` = '$oagree'"
    			.", `userID` = '$username'"
    			.", `name` = '$name'"
    			.", `gender` = '$gender'"
    			.", `age` = $age"
    			.", `height` = $height"
    			.", `weight` = $weight"
    			.", `programname` = '$programname'"
    			.", `startDate` = '$startdate'"
    			.", `duration` = $period"
    			.", `endDate` = '$enddate'"
    			.", `delFlag` = 'N'"
    			.", `updateDate` = now()"
    			.", `step3` = $step3"
    			.", `programType` = '$programtype'" ;

    $masterquery .= $mastersetquery;
    $detailsetquery ='';
    
    if ($type == 'edit') {
    	$masterquery .= $whereclause;	
    }
    
    $result = mysql_query($masterquery);

    if(!$result){
    	die(mysql_error());
    }
	    
//	$programID = 1;
    if( $type == 'edit') {
    	$deleteQuery = "DELETE FROM ProgramDetail ".$whereclause; 
    	
    	mysql_query($deleteQuery) ;

    } else {
    	$programID = mysql_insert_id();
	}
	
    if (is_array($step4items) || is_object($step4items)) {
        foreach($step4items as $value) {
      		$detailsetquery .= "($programID, '$value') ,";
    	}
    }

    if (is_array($step5items) || is_object($step5items)) {
        foreach($step5items as $value) {
          	$detailsetquery .= "($programID, '$value') ,";
            if($value == '28586' || $value == '26022'){
                $bloodsugar = '1';
            }
        }
    }

    $detailsetquery = substr($detailsetquery, 0, -1);

    $detailquery .= $detailsetquery;

    if (mysql_query($detailquery))
    {
        $_SESSION["programType"] = $programtype;
        $_SESSION["bloodsugar"] = $bloodsugar;
    	moveTo("../pages/mainpage.php");
    } else {
    	die(mysql_error());		
    }


  


?>