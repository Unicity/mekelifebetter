<?php
    include_once("../inc/function.php");
    checkSessionValue();

    $username = $_SESSION['username'];

   
    $type = isset($_GET['type']) ? $_GET['type'] : 'add';
    
    
    if ($type != "add"){
        $id = isset($_GET['id']) ? $_GET['id'] : '0';
        $title = isset($_GET['title']) ? $_GET['title'] : '-';
        $description = isset($_GET['description']) ? $_GET['description'] : '';    
        $filename = isset($_GET['filename']) ? $_GET['filename'] : ''; 
    }

    $isReadonly = "";

    if ($type == "view") {
        $isReadonly = "readonly";
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
    <title>5 Step - Community</title>
    
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="../css/community.css" rel="stylesheet">
    
     
     <!--<link href="../css/calendar/datapickk.min.css" rel="stylesheet">-->
    <script type="text/javascript" src="../js/spinner/spin.js"></script>
    <!--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
 <!--   <script type="text/javascript" src="../js/calendar/calendar.js"></script>-->
  
    <script>
  
    function validation()
    {
        var title = document.contentsForm.title.value;
        var description = document.contentsForm.description.value;
         
        if (title == "" ){
            classHandler('add', 'title', 'wrong') ;
            return false;
        }
        if (description == "" ){
            classHandler('add', 'description', 'wrong') ;
            return false;
        }

        return true;
         
    }
        
    function classHandler(type, id, classname){
        var element = document.getElementById(id).classList;
        if (type =='add'){
            element.add(classname);
        }else {
            element.remove("wrong");
        }

    }

    function getReplies(id) {
        var htmlString = "";
        var username = '<?php echo $username ?>';
        var ajax = new XMLHttpRequest();
        
        if (window.XMLHttpRequest) {
            // code for modern browsers
            ajax = new XMLHttpRequest();
            } else {
            // code for old IE browsers
                ajax = new ActiveXObject("Microsoft.XMLHTTP");
            } 
            var data =  "boardId="+id;
            ajax.open('post', '../inc/getReplyData.php', false);
            ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajax.onreadystatechange = function(){
               
            if (ajax.readyState === 4 && ajax.status === 200) {
                     
                myObj = JSON.parse(ajax.responseText);
                 
                myObj.sort(function(a,b){
                    return a.id - b.id;
                });
                        
                for(var i = 0; i< myObj.length; i++){
                    
                    htmlString += "<div class='feed'>";
                    htmlString += "<div class='title'>";
                    htmlString += myObj[i]['description'];
                    htmlString += "</div>";
                    htmlString += "<div class='writer'>";
                    htmlString += myObj[i]['name'] +"("+myObj[i]['department']+") | " +dateFormat(myObj[i]['lastModifyDate']);
                    htmlString += "</div>";
                    htmlString += "</div> ";
                }
            }
        };
        ajax.send(data);

        document.getElementById('replylist').innerHTML = htmlString;  
    }
    // End -->

    function addReply(){
        var txtReply = document.getElementById('reply');
        if (txtReply.value == ""){
            alert("댓글을 써주세요.");
            return ;
        } else {
            var htmlString = "";
            var username = '<?php echo $username ?>';
            var boardId = '<?php echo $id ?>';
            var ajax = new XMLHttpRequest();
            
            if (window.XMLHttpRequest) {
                // code for modern browsers
                ajax = new XMLHttpRequest();
                } else {
                // code for old IE browsers
                    ajax = new ActiveXObject("Microsoft.XMLHTTP");
                } 
                var data =  "boardId="+boardId+"&username="+username+"&description="+txtReply.value;
                ajax.open('post', '../inc/addReply.php', false);
                ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                ajax.onreadystatechange = function(){
                   
                if (ajax.readyState === 4 && ajax.status === 200) {
                    txtReply.value="";
                    getReplies(boardId);
                }
            };
            ajax.send(data);
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


    </script>
         
</head>
<body>
    <nav class="navbar">
        <ul class="" style="width:100%">
          <li class="home-nav"><a class="active" href="./mainpage.php">HOME</a></li>
          <li class="ranking-nav"><a href="./ranking.php">RANKING</a></li>
          <li class="mypage-nav"><a href="./mypage.php">MY PAGE</a></li>
        </ul>
    </nav>
    <div class="container" style="padding:0px; margin:0px;">
        <h2>Community - <?php echo ucfirst($type);?></h2>
        <form name="contentsForm" action="../inc/communityHandler.php" enctype="multipart/form-data" method="post" onsubmit="return validation()">
            <div class="contents">
                <div class="contentsTitle">
                    <label for="title">제목</label>
                    <input type="text" class="inputfield" id="title" name="title" value="<?php echo $title?>" maxlength="23" onfocus="classHandler('remove', this.id, 'wrong')"  <?php echo $isReadonly;?> />
                </div>
                <div class="contentsDescription">
                    <label for="description">내용</label>
                    <textarea id="description" class="inputfield" name="description" style="padding: 1px;" rows="4"   maxlength="80" onfocus="classHandler('remove', this.id, 'wrong')" <?php echo $isReadonly;?>><?php echo $description;?></textarea>
                </div>
                <?php 
                if ($filename != ""){ ?>
                <div class="contentsTitle" align="center">
                    <a href="../uploads/<?php echo $id?>.<?php echo $filename?>" target="_blank"><img src="../uploads/<?php echo $id?>.<?php echo $filename?>" width="300px"> </a>
                </div>

                <?php } ?>
                 <?php 
                    if($type != "view") { 
                ?>
                <div class="contentsTitle">
                    <label for="myfile">첨부파일</label>
                    <input type="file" class="inputfield" id="myfile" name="myfile" accept="image/*"> 
                    <span style="font-size: small; margin-left:10px; color:red; font-weight:bold">File 최대 크기 : 20MB / 타입 : jpg, gif, png</span>
                </div>
                <?php }?>
                    <input type="hidden" name="id" value="<?php echo $id?>">
                    <input type="hidden" name="type" value="<?php echo $type?>">
                    <input type="hidden" name="username" value="<?php echo $username;?>">
                <div class="contentsButton">
                    <?php 
                    if($type != "view") { 
                    ?>
                    
                    <input type="submit" class="button2" value="저장" >
                    
                    <?php 
                        $buttonClass= "button2";
                    } 
                    else { 
                        $buttonClass= "button1"; 
                    } 
                    ?>
                    <input type="button" class="<?php echo $buttonClass; ?>" value="돌아가기" onclick="history.back();">
                </div>
            </div>
        </form>
          <?php 
            if ($type != "add"){ ?>
        <div class="replies">
            <div class="addNewReply">
                <input type="text" name="reply" id="reply" maxlength="20"> <input type="button" name="btnreply" id="btnreply" value="댓글" onclick="addReply()">
            </div>
            <div id="replylist"></div>
               <script>
                 window.onload = getReplies(<?php echo $id;?>);
                </script>
             <?php } ?>
        </div>
    </div>
</body>
</html>