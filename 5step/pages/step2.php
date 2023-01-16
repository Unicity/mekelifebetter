<?php session_start();?>
<? include "./header.inc"; ?>
<?php  include_once("../inc/function.php");?>
<?php 
	include "../inc/dbconn.php";
	checkSessionValue();
	$userNO=$_SESSION["username"];
	$gender=$_SESSION["gender"];
	
	$queryProgramID = "select * from ProgramMaster where userID = '$userNO' and delFlag = 'N';";
	$query_result1 = mysql_query($queryProgramID);
	$query_row1 = mysql_fetch_array($query_result1);
	$userID = $query_row1["userID"];
	$programID = $query_row1["programID"];
	 
	$query = "select sum(Amount) as calorie from StepRecord where DATE_FORMAT(createdate,'%Y%m%d') =  DATE_FORMAT(now(),'%Y%m%d') and ProductID ='P02' and member_no = '".$userNO."'";
	$query_result = mysql_query($query);

	$query_row = mysql_fetch_array($query_result);
	$sumCalorie= $query_row["calorie"];
	
	if($sumCalorie == ""||$sumCalorie == null ){
		$sumCalorie = 0;
	}

?>

 
  	<script type="text/javascript">
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
		var frm = "";
	
		function maxLengthCheck(object){
		    if (object.value.length > object.maxLength){
		        object.value = object.value.slice(0, object.maxLength);
		    }    
		}
		

		function clicksubmit(){
			var kcal = "";
			frm = document.step2ApplyForm;	
			var selectBox = frm.selectValue.value;
			var exerciseMin = frm.exerciseMin.value;

			if(selectBox == "선택"){
				alert("운동을 선택 하세요.");
				return false;
			}

			if(exerciseMin == null || exerciseMin == ""){
				alert("운동량을 입력 하세요.");
				return false;
			}	

			if(selectBox == 1){
				kcal=exerciseMin*6.3
			}else if(selectBox == 2){
				kcal=exerciseMin*2.1
			}else if(selectBox == 3){
				kcal=exerciseMin*4
			}else if(selectBox == 4){
				kcal=exerciseMin*7
			}else if(selectBox == 5){
				kcal=exerciseMin*2.8
			}else if(selectBox == 6){
				kcal=exerciseMin*6
			}else if(selectBox == 7){
				kcal=exerciseMin*8
			}else if(selectBox == 8){
				kcal=exerciseMin*8
			}else if(selectBox == 9){
				kcal=exerciseMin*5
			}else if(selectBox == 10){
				kcal=exerciseMin*14
			}else if(selectBox == 11){
				kcal=exerciseMin*4
			}else if(selectBox == 12){
				kcal=exerciseMin*2.5
			}else if(selectBox == 13){
				kcal=exerciseMin*5
			}else if(selectBox == 14){
				kcal=exerciseMin*20.1
			}else if(selectBox == 15){
				kcal=exerciseMin*4
			}else if(selectBox == 16){
				kcal=exerciseMin*4
			}else if(selectBox == 17){
				kcal=exerciseMin*6
			}else if(selectBox == 18){
				kcal=exerciseMin*3
			}else if(selectBox == 19){
				kcal=exerciseMin*5
			}else if(selectBox == 20){
				kcal=exerciseMin*3
			}else if(selectBox == 21){
				kcal=exerciseMin*4
			}else if(selectBox == 22){
				kcal=exerciseMin*12
			}else if(selectBox == 23){
				kcal=exerciseMin*7
			}else if(selectBox == 24){
				kcal=exerciseMin*7
			}else if(selectBox == 25){
				kcal=exerciseMin*5
			}else if(selectBox == 26){
				kcal=exerciseMin*6
			}else if(selectBox == 27){
				kcal=exerciseMin*12
			}else if(selectBox == 28){
				kcal=exerciseMin*10
			}else if(selectBox == 29){
				kcal=exerciseMin*10.5
			}else if(selectBox == 30){
				kcal=exerciseMin*9
			}else if(selectBox == 31){
				kcal=exerciseMin*9
			}else if(selectBox == 32){
				kcal=exerciseMin*6
			}
															
											
			if(confirm("등록하시겠습니까?")){   
				kcal = kcal.toFixed(2);		
				frm.amonut.value = kcal;
				frm.crdateDate.value = getTimeStamp();
				frm.action = "./stepAction.php"
				frm.submit();	
			}
	
		} 
			
  	</script>
    <div class="StepImg">
		<img alt="" src="../images/s2button.png" style="width: 95%;">  
	</div>
	<form name="step2ApplyForm" method="post">
		<input type="hidden" name="crdateDate" value="">
		<input type="hidden" name="step" value="2">
		<input type="hidden" name="programID" value="<?php echo $programID?>">
		<input type="hidden" name="productID" value="P02">
		<input type="hidden" name="amonut" value="">
		<div class="base">
			<div style="height: 20px;"></div>
			<div class="step" style="margin-left: 50px;">
				<img alt="" src="../images/s2icon.png" style="width: 30px;vertical-align: middle; margin-bottom: 5px;"><font size="4px"><b> 오늘의 운동량:&nbsp;<?php echo $sumCalorie?>&nbsp;kcal</b></font> 
			</div>
			<div style="height: 5px;"></div>
			<div>
				<div style="margin-left: 68px;">
					■ <font size="4px"><b>운동선택 :</b></font> 
					<select name="selectValue" title="운동" onchange="select1Change()" style="width:130px;text-align: center;color: black;border-color: black;">
						<option selected="selected">선택</option>
						<option value="1">계단오르기</option>
						<option value="2">보통걷기</option>
						<option value="3">빠르게 걷기</option>
						<option value="4">조깅 달리기</option>
						<option value="5">런닝머신 걷기(3.5km/h)</option>
						<option value="6">런닝머신 달리기(5.0km/h)</option>
						<option value="7">등산</option>
						<option value="8">자전거타기</option>
						<option value="9">싸이클 실내자전거</option>
						<option value="10">스피닝</option>
						<option value="11">스트레칭</option>
						<option value="12">요가</option>
						<option value="13">필라테스</option>
						<option value="14">플랭크</option>
						<option value="15">훌라후프</option>
						<option value="16">골프</option>
						<option value="17">농구</option>
						<option value="18">당구</option>
						<option value="19">배드민턴</option>
						<option value="20">볼링</option>
						<option value="21">탁구</option>
						<option value="22">스쿼시</option>
						<option value="23">테니스</option>
						<option value="24">방송 댄스</option>
						<option value="25">스포츠 댄스</option>
						<option value="26">에어로빅</option>
						<option value="27">복싱</option>
						<option value="28">킥복싱</option>
						<option value="29">줄넘기</option>
						<option value="30">수영</option>
						<option value="31">축구</option>
						<option value="32">야구</option>
					</select>
					<br/>
					
				</div>
				<div style="height: 5px;"></div>
				<div class="step" style="margin-left: 68px;">
					■ <font size="4px"><b>운동량 :</b> </font>
					<input type="number" id="exerciseMin" name="exerciseMin" maxlength="3" oninput="maxLengthCheck(this)" style="width: 130px;margin-left: 17px;text-align: right"/>&nbsp;분 
				</div>
			</div>
			<div style="height: 100px;"></div>
			<div align="center">
				<img alt="" src="../images/submitButton2.png" onclick="clicksubmit()"> 
			</div>
		</div>
	</form>
