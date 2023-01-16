<?php session_start();?>
<? include "./header.inc"; ?>
<?php  include_once("../inc/function.php");?>
<?php 
	include "../inc/dbconn.php";
	checkSessionValue();
	$userNo=$_SESSION["username"];
	$sumCnt = array();
	$productChk = "select ProductId from Product where Step = '3' and Category = '3'";
	$productChk_result = mysql_query($productChk);
	while($row = mysql_fetch_row($productChk_result)) {
		$ProductCnt= $row[0];
	
		$query1 = "select sum(Amount) as CNT from StepRecord where member_no = '".$userNo."' and ProductID = '".$ProductCnt."'  and DATE_FORMAT(createdate,'%Y%m%d') =  DATE_FORMAT(now(),'%Y%m%d')";
		$query_result1 = mysql_query($query1);
		$query_row1 = mysql_fetch_assoc($query_result1);
		$sumCnt[]= $query_row1;
	
	
		$pCnt = $sumCnt[0];
		$pCnt1 = $sumCnt[1];
		$pCnt2 = $sumCnt[2];

		
	}
	
	$sumCnt = $pCnt["CNT"];
	$sumCnt1 = $pCnt1["CNT"];
	$sumCnt2 = $pCnt2["CNT"];

	$programIDResult="select programID
					    from ProgramMaster
				       where delFlag = 'N'
	                     and userID = '".$userNo."'";
	$programID_result = mysql_query($programIDResult);
	$programID_row = mysql_fetch_assoc($programID_result);
	$programID= $programID_row["programID"];

?>

  	<script type="text/javascript">

  	var  sumQty1 = '<?php echo $sumCnt ?>';
  	var  sumQty2 = '<?php echo $sumCnt1 ?>';
  	var  sumQty3 = '<?php echo $sumCnt2 ?>';
	
		$(document).ready(function(){
  	 
			if(sumQty1 == 1){
				$("#lifiberBlock1").css("display","none");
				$("#lifiberColor1").css("display","none");
				$("#lifiberBlock2").css("display","block");
				$("#lifiberColor2").css("display","none");
				$("#lifibersearchColor1").css("display","block");
			}else if(sumQty1 >= 2){
				$("#lifiberBlock1").css("display","none");
				$("#lifiberColor1").css("display","none");
				$("#lifiberBlock2").css("display","none");
				$("#lifiberColor2").css("display","none");
				$("#lifibersearchColor1").css("display","block");
				$("#lifibersearchColor2").css("display","block");
			}else if(sumQty1 == 0 || sumQty1 == null || sumQty1 == ""){
				$("#lifiberBlock1").css("display","block");
				$("#lifiberColor1").css("display","none");
				$("#lifiberBlock2").css("display","block");
				$("#lifiberColor2").css("display","none");
				$("#lifibersearchColor1").css("display","none");
				$("#lifibersearchColor2").css("display","none");
			}			

			if(sumQty2 == 1){
				$("#aloeBlock1").css("display","none");
				$("#aloeColor1").css("display","none");
				$("#aloeBlock2").css("display","block");
				$("#aloeColor2").css("display","none");
				$("#aloesearchColor1").css("display","block");
			}else if(sumQty2 >= 2){
				$("#aloeBlock1").css("display","none");
				$("#aloeColor1").css("display","none");
				$("#aloeBlock2").css("display","none");
				$("#aloeColor2").css("display","none");
				$("#aloesearchColor1").css("display","block");
				$("#aloesearchColor2").css("display","block");
			}else if(sumQty2 == 0 || sumQty2 == null || sumQty2 == ""){
				$("#aloeBlock1").css("display","block");
				$("#aloeColor1").css("display","none");
				$("#aloeBlock2").css("display","block");
				$("#aloeColor2").css("display","none");
				$("#aloesearchColor1").css("display","none");
				$("#aloesearchColor2").css("display","none");
			}	

			if(sumQty3 == 1){
				$("#parawayPlusBlack1").css("display","none");
				$("#parawayPlusColor1").css("display","none");
				$("#parawayPlusBlack2").css("display","block");
				$("#parawayPlusColor2").css("display","none");
				$("#parawaySearchPlusColor1").css("display","block");
			}else if(sumQty3 >= 2){
				$("#parawayPlusBlack1").css("display","none");
				$("#parawayPlusColor1").css("display","none");
				$("#parawayPlusBlack2").css("display","none");
				$("#parawayPlusColor2").css("display","none");
				$("#parawaySearchPlusColor1").css("display","block");
				$("#parawaySearchPlusColor2").css("display","block");
			}else if(sumQty3 == 0 || sumQty3 == null || sumQty3 == ""){
				$("#parawayPlusBlack1").css("display","block");
				$("#parawayPlusColor1").css("display","none");
				$("#parawayPlusBlack2").css("display","block");
				$("#parawayPlusColor2").css("display","none");
				$("#parawaySearchPlusColor1").css("display","none");
				$("#parawaySearchPlusColor2").css("display","none");
			}	
					
  	  	});	
  		var frm = "";
  		var color1 = 0;
  		var color2 = 0;
  		var color3 = 0;
 
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

		function changeColor(num){
			if(num ==1){
				$("#lifiberBlock1").css("display","none");
				$("#lifiberColor1").css("display","block")
				color1 +=1;
			}else if(num == 2){
				$("#lifiberBlock1").css("display","block");
				$("#lifiberColor1").css("display","none");
				color1 -=1;
			}else if(num ==3){
				$("#lifiberBlock2").css("display","none");
				$("#lifiberColor2").css("display","block");
				color1 +=1;
			}else if(num ==4){
				$("#lifiberColor2").css("display","none");
				$("#lifiberBlock2").css("display","block");
				color1 -=1;
			}		

			if(num ==5){
				$("#aloeBlock1").css("display","none");
				$("#aloeColor1").css("display","block")
				color2 +=1;
			}else if(num ==6){
				$("#aloeBlock1").css("display","block");
				$("#aloeColor1").css("display","none")
				color2 -=1;
			}else if(num==7){
				$("#aloeBlock2").css("display","none");
				$("#aloeColor2").css("display","block");
				color2 +=1;
			}else if(num==8){
				$("#aloeColor2").css("display","none");
				$("#aloeBlock2").css("display","block");
				color2 -=1;
			}

			if(num == 9){
				$("#parawayPlusBlack1").css("display","none");
				$("#parawayPlusColor1").css("display","block");
				color3 +=1;
			}else if(num ==10){
				$("#parawayPlusBlack1").css("display","block");
				$("#parawayPlusColor1").css("display","none");;
				color3 -=1;
			}else if(num ==11){
				$("#parawayPlusBlack2").css("display","none");
				$("#parawayPlusColor2").css("display","block")	
				color3 +=1;
			}else if(num ==12){
				$("#parawayPlusColor2").css("display","none");
				$("#parawayPlusBlack2").css("display","block")
				color3 -=1;
			}		
		}	

		function clicksubmit(){
			frm = document.step3ApplyForm;	
			
			if(sumQty1 >=2 && sumQty2 >=2 && sumQty3 >=2){
				alert("금일 섭취량을 모두 섭취했습니다.");
				return false;
			}

			if(color1==0 && color2==0 && color3==0){
				alert("섭취량을 등록하세요.");
				return false;
			}	
			if(confirm("등록하시겠습니까?")){   
				frm.crdateDate.value = getTimeStamp();
				frm.color1.value = color1;
				frm.color2.value = color2;
				frm.color3.value = color3;	
				frm.action = "step3Action.php";
				frm.submit();	
			}	
			
		}	
			
  	</script>

    <div class="StepImg">
		<img alt="" src="../images/s3button.png" style="width: 95%;">  
	</div>
	<form name="step3ApplyForm" method="post">
		<input type="hidden" name="crdateDate" value="">
		<input type="hidden" name="step" value="3">
		<input type="hidden" name="color1" value="">
		<input type="hidden" name="color2" value="">
		<input type="hidden" name="color3" value="">
		<input type="hidden" name="programID" value="<?php echo $programID?>">
		<div class="step3base" align="center">
			<div style="margin-right: 230px;">
				<img alt="" src="../images/s3icon.png" style="width: 25px;vertical-align: middle;margin-bottom: 5px;"><font color="blue" size="4px;" style="vertical-align: bottom;">&nbsp;<b>라이화이버</b></font>
			</div>		
			<table class="tableStyle">
				<tr>
					<td style="font-size:14px; padding-left:13px;">
						<b>차전자피 식이섬유:</b>
					</td>
					<td rowspan="2">
						<img id="lifiberBlock1" name="lifiberBlock1" alt="" src="../images/lifiberBlock.png" onclick="changeColor(1);" style="margin-left: 50px;">
						<img id="lifiberColor1" name="lifiberColor1" alt="" src="../images/lifiberColor.png" onclick="changeColor(2);" style="display: none;margin-left: 50px;">
						<img id="lifibersearchColor1" name="lifibersearchColor1" alt="" src="../images/lifiberColor.png" style="display: none;margin-left: 50px;">
					</td>
					<td rowspan="2">
						<img id="lifiberBlock2" name="lifiberBlock2" alt="" src="../images/lifiberBlock.png"  onclick="changeColor(3);" style="margin-left: 10px;">
						<img id="lifiberColor2" name="lifiberColor2" alt="" src="../images/lifiberColor.png"  onclick="changeColor(4);" style="display: none;margin-left: 10px;">
						<img id="lifibersearchColor2" name="lifibersearchColor2" alt="" src="../images/lifiberColor.png" style="display: none;margin-left: 10px;">	
					</td>
				</tr>
				<tr>
					<td style="font-size:14px; padding-left:13px;"> 
						혈중 콜레스테롤 개선ㆍ<br/>배변활동 원활에<br/> 도움을 줄 수 있음
					</td>
				</tr>
			</table>
			<div class="font">
				1일 2회 / 1회 1포
			</div>
						
			<div style="margin-right: 175px;">
				<img alt="" src="../images/s3icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 2px;"><font color="blue" size="4px;" style="vertical-align: middle;">&nbsp;<b>알로에 아보레센스</b></font>
			</div>
				
			<table class="tableStyle">
				<tr>
					<td style="font-size:14px; padding-left:35px;">
						<b>알로에 전잎:</b>
					</td>
					<td rowspan="2">
						<img id="aloeBlock1" name="aloeBlock1" alt="" src="../images/aloeBlack.png" onclick="changeColor(5);" style="margin-left: 40px;">
						<img id="aloeColor1" name="aloeColor1" alt="" src="../images/aloeColor.png" onclick="changeColor(6);" style="display: none;margin-left: 40px;">
						<img id="aloesearchColor1" name="aloesearchColor1" alt="" src="../images/aloeColor.png" style="display: none;margin-left: 40px;">		
					</td>
					<td rowspan="2">
						<img id="aloeBlock2" name="aloeBlock2" alt="" src="../images/aloeBlack.png"  onclick="changeColor(7);" style="margin-left: 10px;">
						<img id="aloeColor2" name="aloeColor2" alt="" src="../images/aloeColor.png"  onclick="changeColor(8)" style="display: none;margin-left: 10px;">
						<img id="aloesearchColor2" name="aloesearchColor2" alt="" src="../images/aloeColor.png" style="display: none;margin-left: 10px;">	
					</td>	
				</tr>
				<tr>
					<td style="font-size:14px; padding-left:35px;">
						배변활동 원활에<br/>도움을 줄 수 있음
					</td>	
				</tr>
			</table>
			<div class="font" style="margin-top: 0px;padding-top: 0px;">
				1일 2회 / 1회 3캡슐
			</div>
			<div style="margin-right: 190px;">
				<img alt="" src="../images/s3icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 2px;"><font color="blue" size="4px;" style="vertical-align: middle;">&nbsp;<b>패러웨이 플러스</b></font>
			</div>
			<table class="tableStyle">
				<tr>
					<td style="font-size:14px; padding-left:20px;">
						<b>비타민B₁:</b>
					</td>
					<td rowspan="2">
						<img id="parawayPlusBlack1" name="parawayPlusBlack1" alt="" src="../images/parawayPlusBlack.png" onclick="changeColor(9);" style="margin-left: 55px; margin-bottom: 10px;" >
						<img id="parawayPlusColor1" name="parawayPlusColor1" alt="" src="../images/parawayPlusColor.png" onclick="changeColor(10);" style="display: none;margin-left: 55px;margin-bottom: 10px;">
						<img id="parawaySearchPlusColor1" name="parawaySearchPlusColor1" alt="" src="../images/parawayPlusColor.png" style="display: none;margin-left: 55px;margin-bottom: 10px;">
					</td>
					<td rowspan="2">
						<img id="parawayPlusBlack2" name="parawayPlusBlack2" alt="" src="../images/parawayPlusBlack.png"  onclick="changeColor(11);" style="margin-left: 10px;margin-bottom: 10px;">
						<img id="parawayPlusColor2" name="parawayPlusColor2" alt="" src="../images/parawayPlusColor.png"  onclick="changeColor(12)" style="display: none;margin-left: 10px;margin-bottom: 10px;">
						<img id="parawaySearchPlusColor2" name="parawaySearchPlusColor2" alt="" src="../images/parawayPlusColor.png" style="display: none;margin-left: 10px;margin-bottom: 10px;">
					</td>
				</tr>
				<tr>
					<td style="font-size:14px; padding-left:20px;">
						탄수화물과 에너지<br/>대사에 필요
					</td>	
				</tr>
			</table>
			<div class="font" style="margin-top: 0px;padding-top: 0px;">
				1일 2회 / 1회 2캡슐
			</div>
			<div style="height: 10px;"></div>
			<div align="center">
				<img alt="" src="../images/submitButton3.png" onclick="clicksubmit()"> 
			</div>
		</div>
		
	</form>