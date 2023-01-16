<?php
    include_once("../inc/function.php");
    checkSessionValue();

    $username = $_SESSION['username'];
    $name = $_SESSION['name'];
    
   // $username = 'joecho';
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
    <link href="../css/community.css" rel="stylesheet">
    <link href="../css/modal.css" rel="stylesheet"> 
     
     <!--<link href="../css/calendar/datapickk.min.css" rel="stylesheet">-->
    <script type="text/javascript" src="../js/spinner/spin.js"></script>
    <!--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
 <!--   <script type="text/javascript" src="../js/calendar/calendar.js"></script>-->
  
    <script>
        var htmlString = "";
        function getFeeds(from, to){
            var htmlLoadString="";
            var htmlNewString="";
            var username = '<?php echo $username ?>';
            var ajax = new XMLHttpRequest();
                if (window.XMLHttpRequest) {
                    // code for modern browsers
                    ajax = new XMLHttpRequest();
                } else {
                    // code for old IE browsers
                    ajax = new ActiveXObject("Microsoft.XMLHTTP");
                }
                var data =  "from="+ from+"&to="+to;
                ajax.open('post', '../inc/getFeedData.php', false);
                ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                ajax.onreadystatechange = function(){
               
                if (ajax.readyState === 4 && ajax.status === 200) {
                        
                        myObj = JSON.parse(ajax.responseText);
                        
                        myObj.sort(function(a,b){
                            return b.lastModifyDate - a.lastModifyDate;
                            }
                        );
                        
                        for(var i = 0; i< myObj.length; i++){
                            type="view";
                            backgroundColor = "";
                            if (myObj[i]['writer'] == username) {
                                type="edit";
                            }
                            if (myObj[i]['newreply'] == 'O') {
                                backgroundColor = "style='background-color: rgba(255, 0, 0, 0.5)'"
                            }
                            htmlNewString += "<div class='replycounter' "+backgroundColor+">";
                            htmlNewString += myObj[i]['replies'];
                            htmlNewString += "<br>댓글";
                            htmlNewString += "</div>";

                            htmlNewString += "<a href='./community_detail.php?type="+type+"&id="+myObj[i]['id']+"&title="+myObj[i]['title']+"&description="+myObj[i]['description']+"&filename="+myObj[i]['filename']+"'> <div class='feed'>";
                            htmlNewString += "<div class='feedno'>"+ myObj[i]['id'] + "</div>" ;
                            htmlNewString += "<div class='title'>";
                             if(myObj[i]['newflag'] == "N"){
                                htmlNewString += "<img src='../images/new.png' class='newicon'>";
                            } 

                            htmlNewString += "<b>"+ (((myObj[i]['title'].length) < 15) ? myObj[i]['title'] : (myObj[i]['title']).substring(0,15)+".." )+"</b>";
                            htmlNewString += "</div>";
                            htmlNewString += "<div class='writer'>";
                            htmlNewString += myObj[i]['name']+"("+myObj[i]['department']+")" + " | " +dateFormat(myObj[i]['lastModifyDate']);
                            htmlNewString += "</div>";
                            htmlNewString += "</div></a>";

                        }
                    }
                 };
                ajax.send(data);
                if(htmlNewString != "") {
                htmlString += htmlNewString;
                htmlLoadString = "<div class='contentsButton' id='"+from+"'><input type='button' class='button3' value='더보기' onclick=\"loadmore("+(from)+");\"></div>";
                document.getElementById('feeds').innerHTML = htmlString+htmlLoadString;  
                } else {
                    alert("더이상 데이터가 없습니다.");
                }
        }

        function dateFormat(date){
            var timestamp = date.split(" ");

            var theDate = new Date(timestamp[0]).getFullYear()+"/"+new Date(timestamp[0]).getMonth()+"/"+new Date(timestamp[0]).getDate();
            var today = new Date().getFullYear()+"/"+new Date().getMonth()+"/"+new Date().getDate();
                        
            if (today == theDate){
                return timestamp[1];
            } else {
                return timestamp[0];
            }

        }
        function loadmore(from){
         //   console.log("hide "+ from);
         //   document.getElementById(from).style.display='none';

            getFeeds(from+10, from+20);
        }
        // End -->
    </script>
</head>
<body onload ="getFeeds(0,10)">
    <nav class="navbar">
        <ul class="" style="width:100%">
          <li class="home-nav"><a class="active" href="./mainpage.php">HOME</a></li>
          <li class="ranking-nav"><a href="./ranking.php">RANKING</a></li>
          <li class="mypage-nav"><a href="./mypage.php">MY PAGE</a></li>
        </ul>
    </nav>

    <div class="container" style="padding:0px; margin:0px;">
       <div class="contents">
            <div id="feeds">
            </div>

            
            <div class="contentsButton">
                <a href="./community_detail.php?type=add"><input type="button" class="button1" value="글쓰기"></a>
            </div>
       </div>
    </div>
  </div>
</body>
</html>