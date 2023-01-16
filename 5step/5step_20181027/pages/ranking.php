<?php
    include_once("../inc/function.php");
    checkSessionValue();

  //  $username = $_SESSION['username'];
    
    $IsCleanser = $_SESSION["cleanser"];
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
</head>
<body>
    <nav class="navbar">
        <ul class="" style="width:100%">
          <li class="home-nav"><a class="active" href="./mainpage.php">HOME</a></li>
          <li class="ranking-nav"><a href="./ranking.php">RECORD</a></li>
          <li class="mypage-nav"><a href="./mypage.php">MY PAGE</a></li>
        </ul>
    </nav>
    <div class="container" style="padding:0px; margin:0px; overflow-x:auto;">
        <table class="rankTable">
            <thead>
                <tr>
                    <td width="10%">Rank</td>
                    <td width="15%">팀</td>
                    <td width="20%">이름</td>
                    <td width="10%">S1</td>
                    <td width="10%">S2</td>
                    <td width="10%">S3</td>
                    <td width="10%">S5</td>
                    <td width="15%">Total</td>
                </tr>
            </thead>
            <tbody id="rankbody">
            </tbody>
             <script for=window event=onload>
         var htmlString = "";
                 
         var ajax = new XMLHttpRequest();
            if (window.XMLHttpRequest) {
                // code for modern browsers
                ajax = new XMLHttpRequest();
             } else {
                // code for old IE browsers
                ajax = new ActiveXObject("Microsoft.XMLHTTP");
            }

            ajax.open('post', '../inc/getRankData.php', false);
            ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajax.onreadystatechange = function(){
           
            if (ajax.readyState === 4 && ajax.status === 200) {
                    
                    myObj = JSON.parse(ajax.responseText);
                    
                    myObj.sort(function(a,b){
                        return a.rank - b.rank;
                        }
                    );
                    
                    for(var i = 0; i< myObj.length; i++){
                        htmlString += "<tr>";
                        if (myObj[i]['rank'] == 1) {
                            htmlString += "<td class='goldrank' width='36'>  "+myObj[i]['rank']+"</td>";
                        } else if (myObj[i]['rank'] == 2) {
                            htmlString += "<td class='silverrank' width='36'>  "+myObj[i]['rank']+"</td>";
                        } else if (myObj[i]['rank'] == 3) {
                            htmlString += "<td class='bronzerank' width='36'>  "+myObj[i]['rank']+"</td>";
                        } else {
                            htmlString += "<td width='36'>"+myObj[i]['rank']+"</td>";
                        }
                        var arrow = "";
          
                        htmlString += "<td width='45'>"+myObj[i]['department']+"</td>";
                        htmlString += "<td width='78'>"+myObj[i]['name']+"</td>";
                        htmlString += "<td width='36'>"+myObj[i]['step1Total']+"</td>";
                        htmlString += "<td width='36'>"+myObj[i]['step2Total']+"</td>";
                        htmlString += "<td width='36'>"+myObj[i]['step3Total']+"</td>";
                        htmlString += "<td width='36'>"+myObj[i]['step5Total']+"</td>";
                        
                        if (parseInt(myObj[i]['total']) != 0){
                            if (myObj[i]['rank'] < myObj[i]['yesterdayRank'] ) {
                                arrow = "<font color='red'>&uarr;</font>"; 
                            } else if(myObj[i]['rank'] > myObj[i]['yesterdayRank'] ) {
                                arrow = "<font color='green'>&darr;</font>";
                            } else {
                                arrow = "";
                            } 
                        }
                        htmlString += "<td width='40'>"+myObj[i]['total']+arrow+"</td>";
                        htmlString += "</tr>";
                    }
                    
                   
                }
             };
            ajax.send();
            
            document.getElementById('rankbody').innerHTML = htmlString;

           
            
    </script>
        </table>
        <div class="buttons">
            <a href="./step1.php"><img src="../images/s1button.png" width="345"></a>
            <a href="./step2.php"><img src="../images/s2button.png" width="345"></a>
        <?php 
          //  $dt = date("Y/m/d");
            $link = "./stepDetail.php?page=3";
           /* if ($IsCleanser == 1) {
                $link = "./step3.php";
            }
            if ($dt >= date('2017/11/16 00:00:00')) {
                $link = "./stepDetail.php?page=3";
            }*/

        ?>
            <a href="<?php echo $link; ?>"><img src="../images/s3button.png" width="345"></a>
         
            <a href="./stepDetail.php?page=4"><img src="../images/s4button.png" width="345"></a>
        <?php  
            $link2 = "./step5.php";
         /*   $dt = date("Y/m/d");

            if ($IsCleanser == 1) {
                $link2 = "./stepDetail.php?page=5";
            } else {
                $link2 = "./step5.php";
            }

            if ($dt >= date('2017/11/16 00:00:00')) {
                $link2 = "./step5.php";
            }*/
        ?>
            <a href="<?php echo $link2; ?>"><img src="../images/s5button.png" width="345"></a> 
        
        </div>
    </div>
</body>
</html>
