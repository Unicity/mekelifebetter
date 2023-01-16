<?php include "common_functions.php";?>
<?php
	  include "nc_config.php";
	  include "config.php";
	  
	  
	  header('Content-Type: application/javascript;charset=UTF-8');
	  
	  
	  $ref1 = $_REQUEST['ref1'];
	  //$ref1 = print_r($ref1);
	  
	  $dtime = date("Y-m-d H:i:s");
	  
	  $callback = $_REQUEST['callback']; //request 쪽에서 명시한 myCallback에 해당한다. 명시적일때 굳이 변수로 받지 않고 그냥 myCallback이라고 적어줘도 된다. 단, 크로스 도메인 통신 2 경우처럼 임의 생성될때는 이름을 모르니 이렇게 받아서 아래쪽에서 함수명을 완성하여 콜백해준다.
	  
	  $query = "select * from internet_sales_warning where no = '18'";
	
	#	echo $query;
	
	$result = mysql_query($query);
	$list = mysql_fetch_array($result);
	$url = $list[url];
	 
	  
	  $arr = array("message" => "You got an AJAX response via JSONP from another site! ", "time" =>$url, "gate_name" => $ref1);
	  
	  
	  
	  $json_val =  json_encode($arr);
	  
	  //echo "${param.callback}(".$json_val.");";
	  
	  echo $callback."(".$json_val.")";
	  
	  

	//$name = $_POST[''];
	  
	// 1. 자바스크립트 객체 또는 serialize() 로 전달 
	//$name = $_POST['name']; 
	//$email = $_POST['email']; 
	//echo(json_encode(array("mode" => $_REQUEST['mode'], "name" => $name, "email" => $email))); 
	
	// 2. JSON.stirngify() 함수로 전달 
	//$data = json_decode($_POST['data']); 
	//echo(json_encode(array("mode" => $_REQUEST['mode'], 
	//"name" => $data->name, "email" => $data->email)));


	 


?>

