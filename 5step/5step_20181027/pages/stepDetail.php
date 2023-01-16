<?php
    include_once("../inc/function.php");
    checkSessionValue();

    $page = $_GET['page'];

    $title= "";
    $imageUrl = "";

    if ($page == '3'){
        $title = "Step 3";
        $imageUrl = "../images/step3.png";
    } else if ($page == '4') {
        $title = "Step 4";
        $imageUrl = "../images/step4.png";
    } else if ($page == '5') {
        $title = "Step 5";
        $imageUrl = "../images/step5.png";
    } else {
        DisplayAlert('잘못된 접근입니다.');
        moveTo('http://www.makelifebetter.com/5step4July');
    }


?>
<!DOCTYPE html>
<html lang="kr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="ScreenOrientation" content="autoRotate:disabled">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>5 Step - Home</title>
    
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/modal.css" rel="stylesheet"> 
     
     <!--<link href="../css/calendar/datapickk.min.css" rel="stylesheet">-->
    <script type="text/javascript" src="../js/spinner/spin.js"></script>
    <!--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
 <!--   <script type="text/javascript" src="../js/calendar/calendar.js"></script>-->
  
    <script>
       
    </script>
</head>
<body>
    <nav class="navbar">
        <ul class="" style="width:100%">
          <li class="home-nav"><a class="active" href="./mainpage.php">HOME</a></li>
          <li class="ranking-nav"><a href="./record.php">RECORD</a></li>
          <li class="mypage-nav"><a href="./mypage.php">MY PAGE</a></li>
        </ul>
    </nav>
    <div class="container" style="margin-left: 1px;">
        <img src='<?php echo $imageUrl;?>' style='position: absolute; margin-left:-5px;' width='360'>
    </div>
</body>
</html>