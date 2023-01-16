<?php
	include "../inc/dbconn.php";
    include_once("../inc/function.php");
    checkSessionValue();

   $userNO = $_SESSION['username'];
    $IsCleanser = $_SESSION["cleanser"];
    
    $programIDResult="select count(*) AS cnt, step3 
    		           from ProgramMaster 
					  where DATE_FORMAT(startDate,'%Y%m%d') <=DATE_FORMAT(now(),'%Y%m%d')
					    and DATE_FORMAT(endDate,'%Y%m%d') >=DATE_FORMAT(now(),'%Y%m%d')
					    and userID = '". $userNO."'
						and delFlag = 'N'";
	
	$programID_result = mysql_query($programIDResult);
    $programID_row = mysql_fetch_assoc($programID_result);
    $step3= $programID_row["step3"];
    $cnt= $programID_row["cnt"];
                         
    $programID_result = mysql_query($programIDResult);
    $programID_row = mysql_fetch_assoc($programID_result);
    $step3= $programID_row["step3"];
    $cnt= $programID_row["cnt"];

?>
<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="ScreenOrientation" content="autoRotate:disabled">
    <meta name="description" content="">
    <meta name="author" content="">
	<title>5 Step - Ranking</title>
    
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="../css/ranking.css" rel="stylesheet">
    <script type="text/javascript" src="../js/spinner/spin.js"></script>
  
    <script type="text/javascript">
         function predicateBy(prop){
               return function(a,b){
                  if( a[prop] > b[prop]){
                      return 1;
                  }else if( a[prop] < b[prop] ){
                      return -1;
                  }
                  return 0;
               }
            }
    </script>
    <style>
    .videoWrapper {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 */
    padding-top: 25px;
    height: 0;
    }
    .videoWrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    </style>
</head>
<body>
    <nav class="navbar">
        <ul class="" style="width:100%">
          <li class="home-nav"><a class="active" href="./mainpage.php">HOME</a></li>
          <li class="ranking-nav"><a href="./record.php">RECORD</a></li>
          <li class="mypage-nav"><a href="./mypage.php">MY PAGE</a></li>
        </ul>
    </nav>
    <div class="container" style="padding:0px; margin:0px; overflow-x:auto;">
        <div style=" background-color: white; margin:1% 1% 2% 1%;" class="videoWrapper">
           
           <iframe width="100%" height="100%" frameborder=”0″ style="display: block; margin-left: auto; margin-right: auto; " src="https://www.youtube.com/embed/Sgw_yIuqHCM?controls=1"> </iframe>
            <!-- <video autoplay>
                <source src="http://www.makelifebetter.co.kr/movie/20180702_microbiome.mp4" type="video/mp4">
            </video>-->
          
        </div>
        <div class="buttons">
            <a href="./step1.php"><img src="../images/s1button.png" width="345"></a>
            <a href="./step2.php"><img src="../images/s2button.png" width="345"></a>
        <?php 
            $dt = date("Y/m/d");
         //   echo $dt;
            //$link = "./stepDetail.php?page=3";
            //$link = "./step3.php";
            //if (($IsCleanser == '1' || $IsCleanser == '2') and ($dt >= date('2018/03/15 00:00:00') && $dt <= date('2018/04/15 00:00:00'))) {
            if ($step3 == '1' || $step3 == null ||$step3 == undefined ) {
                $link = "./stepDetail.php?page=3";
            }else{
            	$link = "./step3.php";
            }

        ?>
            <a href="<?php echo $link; ?>"><img src="../images/s3button.png" width="345"></a>
         	
         <?php 
        	$link2 = "./step4.php";
        	$link3 = "./step5.php";
        	if($cnt == '1') {
		
        		$link2 = "./step4.php";
        		$link3 = "./step5.php";
        	}else{

        		$link2 = "./stepDetail.php?page=4";
        		$link3 = "./stepDetail.php?page=5";
        	}
        ?>   
         	<a href="<?php echo $link2; ?>"><img src="../images/s4button.png" width="345"></a>
         	<a href="<?php echo $link3; ?>"><img src="../images/s5button.png" width="345"></a> 
        
        </div>
    </div>
</body>
</html>
