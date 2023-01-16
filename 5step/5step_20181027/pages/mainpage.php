<?php
    include_once("../inc/function.php");
    checkSessionValue();

    $username = $_SESSION['username'];
    $name = $_SESSION['realname'];
    $programID = $_SESSION["programID"];

    
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
       // <!-- Original:  Nick Korosi (nfk2000@hotmail.com) -->

       // <!-- This script and many more are available free online at -->
       // <!-- The JavaScript Source!! http://javascript.internet.com -->

       // <!-- Begin
        var dDate = new Date();
        var dCurMonth = dDate.getMonth();
        var dCurDayOfMonth = dDate.getDate();
        var dCurYear = dDate.getFullYear();
        var objPrevElement = new Object();

         function getRegisterDates(myMonth, iYear, iMonth) {

           
        }
        function fToggleColor(myElement) {
            var toggleColor = "#ff0000";
            if (myElement.id == "calDateText") {
                if (myElement.color == toggleColor) {
                    myElement.color = "";
                } else {
                    myElement.color = toggleColor;
                }
            } else if (myElement.id == "calCell") {
                for (var i in myElement.children) {
                    if (myElement.children[i].id == "calDateText") {
                        if (myElement.children[i].color == toggleColor) {
                            myElement.children[i].color = "";
                        } else {
                            myElement.children[i].color = toggleColor;
                        }
                    }
                }
            }
        }


        function fSetSelectedDay(myElement){
             
            if (myElement.id == "calCell") {
                if (!isNaN(parseInt(myElement.children["calDateText"].innerText))) {
                    myElement.style.BackgroundColor = "#c0c0c0";
                    objPrevElement.bgColor = "";
                    document.all.calSelectedDate.value = parseInt(myElement.children["calDateText"].innerText);
                    objPrevElement = myElement;
                }
            }
        }
        function fGetDaysInMonth(iMonth, iYear) {
            var dPrevDate = new Date(iYear, iMonth, 0);
            return dPrevDate.getDate();
        }
        function fBuildCal(iYear, iMonth) {
         
            var aMonth = new Array();
            aMonth[0] = new Array(7);
            aMonth[1] = new Array(7);
            aMonth[2] = new Array(7);
            aMonth[3] = new Array(7);
            aMonth[4] = new Array(7);
            aMonth[5] = new Array(7);
            aMonth[6] = new Array(7);
            var dCalDate = new Date(iYear, iMonth-1, 1);
            var iDayOfFirst = dCalDate.getDay();
            var iDaysInMonth = fGetDaysInMonth(iMonth, iYear);
            var iVarDate = 1;
            var  d, w;
            
            aMonth[0][0] = "일";
            aMonth[0][1] = "월";
            aMonth[0][2] = "화";
            aMonth[0][3] = "수";
            aMonth[0][4] = "목";
            aMonth[0][5] = "금";
            aMonth[0][6] = "토";
            
            for (d = iDayOfFirst; d < 7; d++) {
                aMonth[1][d] = iVarDate;
                iVarDate++;
            }
            for (w = 2; w < 7; w++) {
                for (d = 0; d < 7; d++) {
                    if (iVarDate <= iDaysInMonth) {
                        aMonth[w][d] = iVarDate;
                        iVarDate++;
                    }
                }
            }
            return aMonth;
        }

        function fDrawCal(iYear, iMonth, iCellWidth, iCellHeight) {
            var myMonth;
            myMonth = fBuildCal(iYear, iMonth);
            htmlString = "";
            htmlString +="<table id='calTable'>";
            htmlString +="<tr align='center'>";
            htmlString +="<td style='color:red;'><b>" + myMonth[0][0] + "</b></td>";
            htmlString +="<td><b>" + myMonth[0][1] + "</b></td>";
            htmlString +="<td><b>" + myMonth[0][2] + "</b></td>";
            htmlString +="<td><b>" + myMonth[0][3] + "</b></td>";
            htmlString +="<td><b>" + myMonth[0][4] + "</b></td>";
            htmlString +="<td><b>" + myMonth[0][5] + "</b></td>";
            htmlString +="<td style='color:#000080;'><b>" + myMonth[0][6] + "</b></td>";
            htmlString +="</tr>";    
            
            var today = new Date();
            var theDate = today.getDate(); 
            var theMonth = today.getMonth()  + 1;
            var theYear = today.getFullYear();

            console.log(theMonth);
            var recordDate = getRecordDates(iYear, iMonth);

            for (w = 1; w < 7; w++) {
                for (d = 0; d < 7; d++) {
                    htmlString +="<td id='calCell'>";
                    var fontColor ="";
                    var underline ="";
                    var bold = "";
                    var recordExist = 0;  
                    
                    if(d == 0){
                        fontColor = "color: red;"
                    } else if (d==6) {
                        fontColor = "color:#000080;";
                    } else {
                        fontColor ="";
                    }
                    for(j=0; j<recordDate.length; j++){
                        if(parseInt(myMonth[w][d]) == parseInt(recordDate[j]['RecordDate'])){
                            recordExist = 1;
                            break;
                        }  
                    }
                    
                    if(recordExist == 1){
                        underline = "text-decoration: underline;";
                    }  
                        
                    if (!isNaN(myMonth[w][d])) {
                        if(theYear == iYear && theMonth == iMonth && theDate == myMonth[w][d]) {
                            bold = "font-weight: bolder; color: #FFA500;"; 
                        }
                        
                        
                        htmlString +="<font id='calDateText' style='z-index:2;position:relative; "+fontColor+' '+underline+' '+bold+"' onclick='selectedDate("+iYear+","+iMonth+","+ myMonth[w][d]+" )'>" + myMonth[w][d]+ "</font>";
                        htmlString +="<div id='id"+myMonth[w][d]+"' class='w3-modal'> ";
                    } else {
                        htmlString +="<font id=calDateText> </font>";
                    }
                    htmlString +="</td>";
                }
                htmlString +="</tr>";
            }
            htmlString +="</table>";
             
            document.getElementById("calTable").innerHTML = htmlString;
        }

        function selectedDate(year, month, date){
            var elementId = 'id'+date;
            var element =  document.getElementById(elementId);

            var username = '<?php echo $username; ?>';
            var programID ='<?php echo $programID; ?>'; 
            var selectDate = year+'-'+month+"-"+date;

            var data = "username="+ username+"&selectedDate="+selectDate+"&programID="+programID;

            var myObj ;
            var ajax = new XMLHttpRequest();
            if (window.XMLHttpRequest) {
                // code for modern browsers
                ajax = new XMLHttpRequest();
             } else {
                // code for old IE browsers
                ajax = new ActiveXObject("Microsoft.XMLHTTP");
            }
            newhtmlString="";
            ajax.open('post', '../inc/getCalendarData.php', false);
            ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajax.onreadystatechange = function(){
            
                if (ajax.readyState === 4 && ajax.status === 200) {
                      
                    myObj = JSON.parse(ajax.responseText);
                    
                    if(myObj.length > 0) {
                        newhtmlString ="  <div class='w3-modal-content'>";
                        newhtmlString +="      <div class='w3-container'>";
                        newhtmlString +="        <div class='dailyDetail'>";
                        newhtmlString +="          <div class='heading'>"; 
                        newhtmlString +="           <div style='color:#fff; padding-top:5px'><수행 일지></div>";
                        newhtmlString +="            <span onclick='javascript:closeModal("+date+")' class='w3-button w3-display-topright' style='color: #fff;'>&times;</span></div>";
                        newhtmlString +="          <div class='contentbody'>";
                        step1htmlString ="";
                        step2htmlString ="";
                        step3htmlString ="<p><img src='../images/s3icon.png' width='30'> 클린즈 섭취량</p>";
                        step4htmlString ="<p><img src='../images/tablet.png' width='30'> 기초영양 섭취량</p>";
                        step5htmlString ="<p><img src='../images/squared_tablet.png' width='30'> 타겟영양 섭취량</p>";
                        step3 = 0;
                        step4 = 0;
                        step5 = 0; 
                        for(i=0; i<myObj.length; i++){
                             
                            if(myObj[i]['Step'] == '1'){
                                step1htmlString = "<p><img src='../images/waterdroplet.png' width='20' style='margin-left: 5pmargin-right: 5px'> 수분 섭취량 : <u>"+myObj[i]['amount']+"</u> ml</p>";
                            } else if(myObj[i]['Step'] == '2') {
                                step2htmlString = "<p><img src='../images/runningman.png' width='30'> 칼로리 소모량 :  <u>"+myObj[i]['amount'] +"</u> kcal</p>"; 
                            } else if(myObj[i]['Step'] == '3') {
                                step3htmlString += "<p> <font style='margin-left:35px;'>- "+myObj[i]['ProductName']+" : <u>"+myObj[i]['amount']+"</u> </font>회<br>";
                                step3 = 1;
                            }
                            else if(myObj[i]['Step'] == '4') {
                                if(myObj[i]['ProductID'] == '29276'){
                                   
                                    if(myObj[i]['amount'] < 4 ){
                                        step4htmlString += "<p> <font style='margin-left:35px;'>- "+myObj[i]['ProductName']+" : <u>1</u> </font>회<br>";
                                    } else {
                                        step4htmlString += "<p> <font style='margin-left:35px;'>- "+myObj[i]['ProductName']+" : <u>2</u> </font>회<br>";
                                    }
                                } else {
                                    step4htmlString += "<p> <font style='margin-left:35px;'>- "+myObj[i]['ProductName']+" : <u>"+myObj[i]['amount']+"</u> </font>회<br>";
                                }
                                step4 = 1;
                            } else if(myObj[i]['Step'] == '5') {
                               
                                    step5htmlString += "<p> <font style='margin-left:35px;'>- "+myObj[i]['ProductName']+" : <u>"+myObj[i]['amount']+"</u> </font>회<br>";

                                step5 = 1;
                            } else {

                            }
                        }
                        
                        newhtmlString = newhtmlString + step1htmlString + step2htmlString;
                        if (step3 == 1){
                            newhtmlString += step3htmlString;
                        }
                        if(step4 == 1){
                            newhtmlString += step4htmlString;
                        }
                        if(step5 == 1){
                             newhtmlString += step5htmlString ;
                        }
                        newhtmlString +="        </div>";
                        newhtmlString +="        </div>";
                        newhtmlString +="      </div>";
                        newhtmlString +="  </div>";
                        newhtmlString +="</div> ";
                    }
                }
            };
            ajax.send(data);

            if (newhtmlString != "" && typeof(element) != 'undefined' && element != null)
            {
                element.innerHTML = newhtmlString; 
                element.style.display='block';
            } else {
                element.style.display='none';
            }
        }

        function closeModal(date){
            var elementId = 'id'+date;
            var element =  document.getElementById(elementId);
            if (typeof(element) != 'undefined' && element != null)
            {
              element.style.display='none';
               
            }
        }
        
        function fUpdateCal(iYear, iMonth) {

         //   myMonth = fBuildCal(iYear, iMonth);
            objPrevElement.bgColor = "";
            document.getElementById("calTable").innerHTML="";
            document.all.calSelectedDate.value = "";
            fDrawCal(iYear, iMonth, 30, 30);
         /*   for (w = 1; w < 7; w++) {
                for (d = 0; d < 7; d++) {
                    if (!isNaN(myMonth[w][d])) {
                        calDateText[((7*w)+d)-7].innerText = myMonth[w][d];
                    } else {
                        calDateText[((7*w)+d)-7].innerText = " ";
                    }
                }
            }*/
        }
        function getRecordDates(iYear, iMonth){
            var myObj ;
            var ajax = new XMLHttpRequest();
            var username = '<?php echo $username; ?>';
            var selectedMonth = iYear+'-'+iMonth;
            var data = "username="+username+"&selectedMonth="+selectedMonth;
            if (window.XMLHttpRequest) {
                // code for modern browsers
                ajax = new XMLHttpRequest();
             } else {
                // code for old IE browsers
                ajax = new ActiveXObject("Microsoft.XMLHTTP");
            }
             
            ajax.open('post', '../inc/getRecordDate.php', false);
            ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajax.onreadystatechange = function(){
            
                if (ajax.readyState === 4 && ajax.status === 200) {
                      
                    myObj = JSON.parse(ajax.responseText);


                }
            }
            ajax.send(data);

            return myObj;

            
        }

       
        // End -->
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
    <div class="container" style="padding:0px; margin:0px; text-align: center;">
      <h1> My Diary </h1>
      <span style="margin-left:10px;">안녕하세요. <?php echo $name;?>님</span><br>
      <div class="calendar" id="calendar">
        <form name="frmCalendar" method="post" action="">
          <input type="hidden" name="calSelectedDate" value="">
            <table style="border-bottom-right-radius: 15px; border-bottom-left-radius: 15px;">
              <thead class="monthHeader">
                <tr>
                  <td>
                    <select name="tbSelYear" onchange='fUpdateCal(frmCalendar.tbSelYear.value, frmCalendar.tbSelMonth.value)'>
                      <option value="2018">2018년</option>
                      <option value="2019">2019년</option>
                      <option value="2020">2020년</option>
                    </select>
                    <select name="tbSelMonth" onchange='fUpdateCal(frmCalendar.tbSelYear.value, frmCalendar.tbSelMonth.value)'>
                      <option value="1">1월</option>
                      <option value="2">2월</option>
                      <option value="3">3월</option>
                      <option value="4">4월</option>
                      <option value="5">5월</option>
                      <option value="6">6월</option>
                      <option value="7">7월</option>
                      <option value="8">8월</option>
                      <option value="9">9월</option>
                      <option value="10">10월</option>
                      <option value="11">11월</option>
                      <option value="12">12월</option>
                    </select>
                  </td>
              </tr>
            </thead>
            <tr>
              <td id="calTable">
                <script language="JavaScript">
                    var dCurDate = new Date();
                    fDrawCal(dCurDate.getFullYear(), dCurDate.getMonth()+1, 30, 30);
                </script>
              </td>
            </tr>
          </table>
        </form>
        <script language="JavaScript" for="window" event="onload">
                //<!-- Begin
          var dCurDate = new Date();
          frmCalendar.tbSelMonth.options[dCurDate.getMonth()].selected = true;
          for (i = 0; i < frmCalendar.tbSelYear.length; i++)
            if (frmCalendar.tbSelYear.options[i].value == dCurDate.getFullYear())
              frmCalendar.tbSelYear.options[i].selected = true;
           //  End -->
        </script>
      </div>
      
      <div>
             <a href="./programSetup02.php?type=edit"><img src="../images/programmodifybtn.png" style="margin:4%; max-width:92%; height: auto;"  width="352" height="56"></a>  
      </div>
      <div>
             <a href="../inc/logout.php"><font color="black">LOGOUT</font></a>  
      </div>
  </div>
</body>
</html>