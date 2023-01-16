<?php 
	header("Content-Type: text/html; charset=UTF-8");
	if (!include_once("./dbconn.php")){
		echo "The config file could not be loaded";
	}
	include "./function.php";
	checkSessionValue();

    $type = isset($_POST['type']) ? $_POST['type'] : '0'; 

    $weight = isset($_POST['weight']) ? $_POST['weight'] : 0;
    $bodyfat = isset($_POST['bodyfat']) ? $_POST['bodyfat'] : 0; 
	
    $hdl = isset($_POST['hdl']) ? $_POST['hdl'] : 0;
    $ldl = isset($_POST['ldl']) ? $_POST['ldl'] : 0; 
    $cholesterol = isset($_POST['cholesterol']) ? $_POST['cholesterol'] : 0;
    $triglycerides = isset($_POST['triglycerides']) ? $_POST['triglycerides'] : 0; 
    
    $arrayData = array();
    $username = $_SESSION['username'];
    
    $query = 'INSERT INTO healthCheck (`id`, `checkValue`, `type`, `createDate`) VALUES';

    if ($type == '1'){
        $arrayData = array("E"=>$weight
                        , "F"=>$bodyfat); 
    } else if ($type == '2') {
        $arrayData = array("A"=>$cholesterol
                        , "B"=>$hdl
                        , "C"=>$ldl
                        , "D"=>$triglycerides);
    }

    foreach($arrayData as $key => $value) {
        $queryValues .= "('$username', $value, '$key', Date_format(now(), '%Y-%m-%d') ) ,";
    }
    $queryValues =rtrim($queryValues,",");
   $query .= $queryValues;
    if (mysql_query($query))
    {
        moveTo("../pages/mainpage.php");
    } else {
    	die(mysql_error());		
    }


  


?>