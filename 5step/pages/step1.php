<?php session_start();?>
<? include "./header.inc"; ?>
<?php  include_once("../inc/function.php");?>
<?php 
	include "../inc/dbconn.php";
	checkSessionValue();
	$userNO=$_SESSION["username"];
	$IsCleanser = $_SESSION["cleanser"];
	
	$queryProgramID = "select * from ProgramMaster where userID = '$userNO' and delFlag = 'N';";
	$query_result1 = mysql_query($queryProgramID);
	$query_row1 = mysql_fetch_array($query_result1);
	$userID = $query_row1["userID"];
	$programID = $query_row1["programID"];

 
	$query = "select sum(Amount) as sumAmount from StepRecord where DATE_FORMAT(createdate,'%Y%m%d') =  DATE_FORMAT(now(),'%Y%m%d') and ProductID ='P01' and member_no = '".$userNO."'";
	$query_result = mysql_query($query);

	$query_row = mysql_fetch_array($query_result);
	$sumAmount = $query_row["sumAmount"];
	if($sumAmount==""||$sumAmount==null){
		$sumAmount=0;
	}

?>

    <style type="text/css">
    .waterImg {
    	width : 100%;
    	margin: 10px;
    }
    .baseStep1 {
    	   margin-left : 0px;
    	   width: 100%;
    	   height: 100%;
    	  
    }
    
    .baseStep1 .water{
    		
    	    margin-left: 30px;
    	    margin-right: 5px;
    	    
    }
    .baseStep1 .waterInput{
    		margin:5px;
    }
    
    .baseStep1 .dayWaterTotal{
    	
    	margin-left: 10px;
    	
    }
    
    .baseStep1 .txt{
    	margin-top: 10px;
    	margin-left: 30px;  
    	margin-bottom : 10px;	
    	border: 1px;
    	font-size: 3px;
    	
    	
    }
    
    .buttonSubmit{
		margin-left: 1 0px;
    }    
    </style>
    <!-- Bootstrap core CSS -->
  	<script type="text/javascript">
  		var amount = '<?php echo $sumAmount?>'
	
		var color1=0;
		var color2=0;
		var color3=0;
		var color4=0;
		var color5=0;
		var color6=0;
		var color7=0;
		var color8=0;
		var frm="";
		$(document).ready(function(){

			if(amount == 0){
				$("#bWater1").css("display","block");
				$("#bWater2").css("display","block");
				$("#bWater3").css("display","block");
				$("#bWater4").css("display","block");
				$("#bWater5").css("display","block");
				$("#bWater6").css("display","block");
				$("#bWater7").css("display","block");
				$("#bWater8").css("display","block");
				$("#cWater1").css("display","none");
				$("#cWater2").css("display","none");
				$("#cWater3").css("display","none");
				$("#cWater4").css("display","none");
				$("#cWater5").css("display","none");
				$("#cWater6").css("display","none");
				$("#cWater7").css("display","none");
				$("#cWater8").css("display","none");	
			}else if(amount == 250){
				$("#bWater1").css("display","none");
				$("#cWaterColor1").css("display","block");
				$("#bWater2").css("display","block");
				$("#cWater2").css("display","none");
				$("#bWater3").css("display","block");
				$("#cWater3").css("display","none");
				$("#bWater4").css("display","block");
				$("#cWater4").css("display","none");
				$("#bWater5").css("display","block");
				$("#cWater5").css("display","none");
				$("#bWater6").css("display","block");
				$("#cWater6").css("display","none");
				$("#bWater7").css("display","block");
				$("#cWater7").css("display","none");
				$("#bWater8").css("display","block");
				$("#cWater8").css("display","none");

			}else if(amount == 500){
				$("#bWater1").css("display","none");
				$("#cWaterColor1").css("display","block");
				$("#bWater2").css("display","none");
				$("#cWaterColor2").css("display","block");
				$("#bWater3").css("display","block");
				$("#cWater3").css("display","none");
				$("#bWater4").css("display","block");
				$("#cWater4").css("display","none");
				$("#bWater5").css("display","block");
				$("#cWater5").css("display","none");
				$("#bWater6").css("display","block");
				$("#cWater6").css("display","none");
				$("#bWater7").css("display","block");
				$("#cWater7").css("display","none");
				$("#bWater8").css("display","block");
				$("#cWater8").css("display","none");

			}else if(amount == 750){
				$("#bWater1").css("display","none");
				$("#cWaterColor1").css("display","block");
				$("#bWater2").css("display","none");
				$("#cWaterColor2").css("display","block");
				$("#bWater3").css("display","none");
				$("#cWaterColor3").css("display","block");
				$("#bWater4").css("display","block");
				$("#cWater4").css("display","none");
				$("#bWater5").css("display","block");
				$("#cWater5").css("display","none");
				$("#bWater6").css("display","block");
				$("#cWater6").css("display","none");
				$("#bWater7").css("display","block");
				$("#cWater7").css("display","none");
				$("#bWater8").css("display","block");
				$("#cWater8").css("display","none");

			}else if(amount == 1000){
				$("#bWater1").css("display","none");
				$("#cWaterColor1").css("display","block");
				$("#bWater2").css("display","none");
				$("#cWaterColor2").css("display","block");
				$("#bWater3").css("display","none");
				$("#cWaterColor3").css("display","block");
				$("#bWater4").css("display","none");
				$("#cWaterColor4").css("display","block");
				$("#bWater5").css("display","block");
				$("#cWater5").css("display","none");
				$("#bWater6").css("display","block");
				$("#cWater6").css("display","none");
				$("#bWater7").css("display","block");
				$("#cWater7").css("display","none");
				$("#bWater8").css("display","block");
				$("#cWater8").css("display","none");

			}else if(amount == 1250){
				$("#bWater1").css("display","none");
				$("#cWaterColor1").css("display","block");
				$("#bWater2").css("display","none");
				$("#cWaterColor2").css("display","block");
				$("#bWater3").css("display","none");
				$("#cWaterColor3").css("display","block");
				$("#bWater4").css("display","none");
				$("#cWaterColor4").css("display","block");
				$("#bWater5").css("display","none");
				$("#cWaterColor5").css("display","block");
				$("#bWater6").css("display","block");
				$("#cWater6").css("display","none");
				$("#bWater7").css("display","block");
				$("#cWater7").css("display","none");
				$("#bWater8").css("display","block");
				$("#cWater8").css("display","none");

			}else if(amount == 1500){
				$("#bWater1").css("display","none");
				$("#cWaterColor1").css("display","block");
				$("#bWater2").css("display","none");
				$("#cWaterColor2").css("display","block");
				$("#bWater3").css("display","none");
				$("#cWaterColor3").css("display","block");
				$("#bWater4").css("display","none");
				$("#cWaterColor4").css("display","block");
				$("#bWater5").css("display","none");
				$("#cWaterColor5").css("display","block");
				$("#bWater6").css("display","none");
				$("#cWaterColor6").css("display","block");
				$("#bWater7").css("display","block");
				$("#cWater7").css("display","none");
				$("#bWater8").css("display","block");
				$("#cWater8").css("display","none");

			}else if(amount == 1750){
				$("#bWater1").css("display","none");
				$("#cWaterColor1").css("display","block");
				$("#bWater2").css("display","none");
				$("#cWaterColor2").css("display","block");
				$("#bWater3").css("display","none");
				$("#cWaterColor3").css("display","block");
				$("#bWater4").css("display","none");
				$("#cWaterColor4").css("display","block");
				$("#bWater5").css("display","none");
				$("#cWaterColor5").css("display","block");
				$("#bWater6").css("display","none");
				$("#cWaterColor6").css("display","block");
				$("#bWater7").css("display","none");
				$("#cWaterColor7").css("display","block");
				$("#bWater8").css("display","block");
				$("#cWater8").css("display","none");

			}else if(amount == 2000){
				$("#bWater1").css("display","none");
				$("#cWaterColor1").css("display","block");
				$("#bWater2").css("display","none");
				$("#cWaterColor2").css("display","block");
				$("#bWater3").css("display","none");
				$("#cWaterColor3").css("display","block");
				$("#bWater4").css("display","none");
				$("#cWaterColor4").css("display","block");
				$("#bWater5").css("display","none");
				$("#cWaterColor5").css("display","block");
				$("#bWater6").css("display","none");
				$("#cWaterColor6").css("display","block");
				$("#bWater7").css("display","none");
				$("#cWaterColor7").css("display","block");
				$("#bWater8").css("display","none");
				$("#cWaterColor8").css("display","block");

			}			
		});	
		
		
		function ChangeWater1(){
			if(color1 == 0){
				$("#bWater1").css("display","none");
				$("#cWater1").css("display","block");
				color1 = 1;
			}else if(color1 == 1){
				$("#bWater1").css("display","block");
				$("#cWater1").css("display","none");
				color1 = 0;
			}		
		}
		function ChangeWater2(){
			if(color2 == 0){
				$("#bWater2").css("display","none");
				$("#cWater2").css("display","block");
				color2 = 1;
			}else if(color2 == 1){
				$("#bWater2").css("display","block");
				$("#cWater2").css("display","none");
				color2 = 0;
			}		
		}	
		function ChangeWater3(){
			if(color3 == 0){
				$("#bWater3").css("display","none");
				$("#cWater3").css("display","block");
				color3 = 1;
			}else if(color3 == 1){
				$("#bWater3").css("display","block");
				$("#cWater3").css("display","none");
				color3 = 0;
			}		
		}	
		function ChangeWater4(){
			if(color4 == 0){
				$("#bWater4").css("display","none");
				$("#cWater4").css("display","block");
				color4 = 1;
			}else if(color4 == 1){
				$("#bWater4").css("display","block");
				$("#cWater4").css("display","none");
				color4 = 0;
			}		
		}
		function ChangeWater5(){
			if(color5 == 0){
				$("#bWater5").css("display","none");
				$("#cWater5").css("display","block");
				color5 = 1;
			}else if(color5 == 1){
				$("#bWater5").css("display","block");
				$("#cWater5").css("display","none");
				color5 = 0;
			}		
		}
		function ChangeWater6(){
			if(color6 == 0){
				$("#bWater6").css("display","none");
				$("#cWater6").css("display","block");
				color6 = 1;
			}else if(color6 == 1){
				$("#bWater6").css("display","block");
				$("#cWater6").css("display","none");
				color6 = 0;
			}		
		}
		function ChangeWater7(){
			if(color7 == 0){
				$("#bWater7").css("display","none");
				$("#cWater7").css("display","block");
				color7 = 1;
			}else if(color7 == 1){
				$("#bWater7").css("display","block");
				$("#cWater7").css("display","none");
				color7 = 0;
			}		
		}
		function ChangeWater8(){
			if(color8 == 0){
				$("#bWater8").css("display","none");
				$("#cWater8").css("display","block");
				color8 = 1;
			}else if(color8 == 1){
				$("#bWater8").css("display","block");
				$("#cWater8").css("display","none");
				color8 = 0;
			}		
		}
			
	  	var date = new Date();
		function getTimeStamp() {
		  var s =
			leadingZeros(date.getFullYear(), 4) + '-' +
			leadingZeros(date.getMonth() + 1, 2) + '-' +
			leadingZeros(date.getDate(), 2) + ' ' +
	
			leadingZeros(date.getHours(), 2) + ':' +
			leadingZeros(date.getMinutes(), 2) + ':' +
			leadingZeros(date.getSeconds(), 2);
	
		  return s;
		}

		function leadingZeros(n, digits) {
		  var zero = '';
		  n = n.toString();
	
		  if (n.length < digits) {
			for (i = 0; i < digits - n.length; i++)
			  zero += '0';
		  }
		  return zero + n;
		}

		 var reg1 = /[^0-9,.]/gi;
		 var reg2 = /[^0-9]/gi;
		 function sosuCheck(obj) {
			bb = obj.value.indexOf("." , 1);
			if(obj.value.substr(0,bb)){
		    	var head = obj.value.substr(0,bb);
		        var tail = obj.value.substr(bb).replace(reg2,"");
		        var dot = ".";
		  
		        if(tail.length > 1){
		             tail = tail.substr(0,1);
		       	}
		       	obj.value = head + dot + tail;
			}else if(obj.value.length>3) {
		    	obj.value = obj.value.replace(reg2,"");
		    	var temp1 = obj.value.split("");
		        var temp2 = new Array();
		        var len = obj.value.length;
		          temp1 = temp1.reverse();
		    }
		}

				
		function clicksubmit(){
			frm = document.step1ApplyForm;
			if(amount == 2000){
				alert("금일 섭취량을 모두 섭취 했습니다.")
				return false;
			}	
			if(color1==0 &&color2==0 &&color3==0 &&color4==0 &&color5==0 &&color6==0 &&color7==0 &&color8==0){
				alert("섭취량을 등록하세요.");
				return false;
			}	
			var totalColor = color1+color2+color3+color4+color5+color6+color7+color8
			var dayml = 0;
			for(var i = 0; i < totalColor; i++) {
				dayml += 250
			}	
			if(confirm("등록하시겠습니까?")){
				frm.crdateDate.value = getTimeStamp();
				frm.amonut.value = dayml;
				frm.action = "./stepAction.php";
				frm.submit();	
			}	
			
			
		}	
  	</script>
  
   
    <div class="waterImg">
		<img alt="" src="../images/s1button.png" style="width: 95%;">  
	</div>
	<form name="step1ApplyForm" method="post">
		<input type="hidden" name="crdateDate" value="">
		<input type="hidden" name="amonut" value="">
		<input type="hidden" name="programID" value="<?php echo $programID?>">
		<input type="hidden" name="productID" value="P01">
		<input type="hidden" name="step" value="1">
		<div class="baseStep1">
			<div style="height: 20px;"></div>
			<div class="water" style="text-align: center">
				<img alt="" src="../images/s1icon.png" style="width: 20px;vertical-align: middle; margin-bottom: 5px;"><font size="4px"><b> 오늘의 수분 섭취량 :<?php echo $sumAmount ?>&nbsp;ml</b></font> 
			</div><br/>				
			<div style="height: 20px;"></div>
			<div align="center">
				<table style="padding: 0px;margin: 0px;">
					<tr>
						<td>
							<img name="bWater1" id="bWater1" onclick="ChangeWater1();" alt="" src="../images/cupblack.png" style="width: 50px; margin-right: 10px;">
							<img name="cWater1" id="cWater1" onclick="ChangeWater1();"  alt="" src="../images/cupColor.png" style="width: 50px;display: none;margin-right: 10px;">
							<img id="cWaterColor1" name="cWaterColor1" alt="" src="../images/cupColor.png" style="width: 50px;display: none; margin-right: 10px;">
						</td>
						<td>
							<img name="bWater2" id="bWater2" onclick="ChangeWater2();" alt="" src="../images/cupblack.png" style="width:50px;margin-right: 10px;">
							<img name="cWater2" id="cWater2" onclick="ChangeWater2();" alt="" src="../images/cupColor.png" style="width:50px; display: none;margin-right: 10px;">
							<img id="cWaterColor2" name="cWaterColor2" alt="" src="../images/cupColor.png" style="width: 50px;display: none;margin-right: 10px;">
						</td>
						<td>
							<img name="bWater3" id="bWater3" onclick="ChangeWater3();" alt="" src="../images/cupblack.png" style="width: 50px;margin-right: 10px;">
							<img name="cWater3" id="cWater3" onclick="ChangeWater3();" alt="" src="../images/cupColor.png" style="width: 50px;display: none;margin-right: 10px;">
							<img id="cWaterColor3" name="cWaterColor3" alt="" src="../images/cupColor.png" style="width: 50px;display: none;margin-right: 10px;">	
						</td>
						<td>
							<img name="bWater4" id="bWater4" onclick="ChangeWater4();" alt="" src="../images/cupblack.png" style="width: 50px;">
							<img name="cWater4" id="cWater4" onclick="ChangeWater4();" alt="" src="../images/cupColor.png" style="width: 50px;display: none;">
							<img id="cWaterColor4" name="cWaterColor4" alt="" src="../images/cupColor.png" style="width: 50px;display: none;">  						
						</td>
					</tr>
					<tr height="20px;"></tr>
					<tr>
						<td>
							<img name="bWater5" id="bWater5" onclick="ChangeWater5();" alt="" src="../images/cupblack.png" style="width: 50px;margin-right: 10px;">
							<img name="cWater5" id="cWater5" onclick="ChangeWater5();" alt="" src="../images/cupColor.png" style="width: 50px;display: none;margin-right: 10px;">
							<img id="cWaterColor5" name="cWaterColor5" alt="" src="../images/cupColor.png" style="width: 50px;display: none;margin-right: 10px;">		
						</td>
						<td>
							<img name="bWater6" id="bWater6" onclick="ChangeWater6();" alt="" src="../images/cupblack.png" style="width: 50px;margin-right: 10px;">
							<img name="cWater6" id="cWater6" onclick="ChangeWater6();" alt="" src="../images/cupColor.png" style="width: 50px;display: none;margin-right: 10px;">
							<img id="cWaterColor6" name="cWaterColor6" alt="" src="../images/cupColor.png" style="width: 50px;display: none;margin-right: 10px;">	
						</td>
						<td>
							<img name="bWater7" id="bWater7" onclick="ChangeWater7();" alt="" src="../images/cupblack.png" style="width: 50px;margin-right: 10px;">
							<img name="cWater7" id="cWater7" onclick="ChangeWater7();" alt="" src="../images/cupColor.png" style="width: 50px;display: none;margin-right: 10px;">
							<img id="cWaterColor7" name="cWaterColor6" alt="" src="../images/cupColor.png" style="width: 50px;display: none;margin-right: 10px;">		
						</td>
						<td>
							<img name="bWater8" id="bWater8" onclick="ChangeWater8();" alt="" src="../images/cupblack.png" style="width: 50px;">
							<img name="cWater8" id="cWater8" onclick="ChangeWater8();" alt="" src="../images/cupColor.png" style="width: 50px;display: none;"> 
							<img id="cWaterColor8" name="cWaterColor8" alt="" src="../images/cupColor.png" style="width: 50px;display: none;">	
						</td>
					</tr>
				</table>
			</div>
			<div style="height: 40px;"></div>
			<div>
				<p style="font-size: 15px;display:font-weight: bold; text-align: center;padding: 10px 10px;margin: 0;">
					<b>하루 물 섭취 권장량 8잔</b><br/>
					<b>*1잔 = 250 ml</b>
				</p>
			</div>	
			<div style="height: 50px;"></div>
			<div align="center">
				<img alt="" src="../images/submitButton1.png" onclick="clicksubmit()" > 
			</div>
			<div style="height: 50px;"></div>
				
		</div>
	</form>
</body>
</html>
