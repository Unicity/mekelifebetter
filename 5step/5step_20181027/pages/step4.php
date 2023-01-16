<?php session_start();?>
<? include "./header.inc"; ?>
<?php  include_once("../inc/function.php");?>
<?php 
	include "../inc/dbconn.php";
	checkSessionValue();
	$userName=$_SESSION["username"];
	$IsCleanser = $_SESSION["cleanser"];
	$productInfo = array();
	$query = "select B.productID,A.programID
				from ProgramMaster A,
					 ProgramDetail B,
					 Product C
			   where A.programID = B.programID
				 and B.productID = C.ProductID
	 			 and A.userID = '".$userName."'
			   	 and A.delFlag = 'N'
				 and C.step = '4'";
	$query_result = mysql_query($query);
	while($row = mysql_fetch_row($query_result)) {
		$productInfo[]= $row[0];
		$programID = $row[1];
		$productCnt = $row[0];

		$querySelect= "select sum(Amount) as cnt, ProductID 
			             from StepRecord 
			            where Step = 4 
			              and member_no = '".$userName."' 
			              and ProductID = '".$productCnt."' 
			              and DATE_FORMAT(createdate,'%Y%m%d') =  DATE_FORMAT(now(),'%Y%m%d')";
		$query_result1 = mysql_query($querySelect);
		$query_row1 = mysql_fetch_assoc($query_result1);
		$ProductID[]= $query_row1["ProductID"];
		$sumCnt[]= $query_row1["cnt"];
	}

	$loopCnt = count($ProductID);
	?>

  	<script type="text/javascript">
  		var IsCleanser  = '<?php echo $IsCleanser?>';
  		//var obj = document.getElementById(id);
  		var productInfo="";
  		var ProductID="";
  		var sumCnt = "";
  		var loopCnt = "";	

  		$(document).ready(function(){
  			
			productInfo = <?php echo json_encode($productInfo)?>;
			ProductID = <?php echo json_encode($ProductID)?>;
			sumCnt = <?php echo json_encode($sumCnt)?>;
			loopCnt = <?php echo $loopCnt?>;
			

			for(var i=0; i<loopCnt;i++){

				if(ProductID[i] == '29276'){

					if(sumCnt[i]==1){
						$("#moningBlack1").css("display","none");
						$("#moningColor1").css("display","none");
						$("#searchColor1").css("display","block");
					}else if(sumCnt[i]==3){
						
						$("#nightBlack2").css("display","none");
						$("#nightColor2").css("display","none");
						$("#searchColor2").css("display","block");
					}else if(sumCnt[i] >=4){
						$("#moningBlack1").css("display","none");
						$("#moningColor1").css("display","none");
						$("#nightBlack2").css("display","none");
						$("#nightColor2").css("display","none");
						$("#searchColor1").css("display","block");
						$("#searchColor2").css("display","block");
					}			
				}	
						
				if(ProductID[i] == '31022'){
					if(sumCnt[i]==1){
						$("#woman2Black1").css("display","none");
						$("#woman2color1").css("display","none");
						$("#searchWoman2Color1").css("display","block");
					}else if(sumCnt[i]==2){
						$("#woman2Black1").css("display","none");
						$("#woman2Black2").css("display","none");
						$("#woman2color1").css("display","none");
						$("#woman2color2").css("display","none");
						$("#searchWoman2Color1").css("display","block");
						$("#searchWoman2Color2").css("display","block");
					}else if(sumCnt[i]>=3){
						$("#woman2Black1").css("display","none");
						$("#woman2Black2").css("display","none");
						$("#woman2Black3").css("display","none");
						$("#woman2color1").css("display","none");
						$("#woman2color2").css("display","none");
						$("#woman2color3").css("display","none");
						$("#searchWoman2Color1").css("display","block");
						$("#searchWoman2Color2").css("display","block");
						$("#searchWoman2Color3").css("display","block");
					}			
				}

				if(ProductID[i] == '31021'){
					if(sumCnt[i]==1){
						$("#woman1Black1").css("display","none");
						$("#woman1color1").css("display","none");
						$("#searchWoman1Color1").css("display","block");
					}else if(sumCnt[i]==2){
						$("#woman1Black1").css("display","none");
						$("#woman1Black2").css("display","none");
						$("#woman1color1").css("display","none");
						$("#woman1color2").css("display","none");
						$("#searchWoman1Color1").css("display","block");
						$("#searchWoman1Color2").css("display","block");
					}else if(sumCnt[i]>=3){
						$("#woman1Black1").css("display","none");
						$("#woman1Black2").css("display","none");
						$("#woman1Black3").css("display","none");
						$("#woman1color1").css("display","none");
						$("#woman1color2").css("display","none");
						$("#woman1color3").css("display","none");
						$("#searchWoman1Color1").css("display","block");
						$("#searchWoman1Color2").css("display","block");
						$("#searchWoman1Color3").css("display","block");
					}			
				}	

				if(ProductID[i] == '31020'){
					if(sumCnt[i]>=1){
						$("#mansBlack").css("display","none");
						$("#mansColor").css("display","none");
						$("#searchMansColor").css("display","block");
					}	
				}

				if(ProductID[i] == '29200'){
					if(sumCnt[i]>=1){
						$("#provicnicBlack1").css("display","none");
						$("#provicnicColor1").css("display","none");
						$("#searchProvicnicColor1").css("display","block");
					}	
				}

				if(ProductID[i] == '28582'){	
					if(sumCnt[i]==1){
						$("#childrenBlack1").css("display","none");
						$("#childrenColor1").css("display","none");
						$("#searchChildrenColor1").css("display","block");
					}else if(sumCnt[i]==2){
		
						$("#childrenBlack1").css("display","none");
						$("#childrenBlack2").css("display","none");
						$("#childrenColor1").css("display","none");
						$("#childrenColor2").css("display","none");
						$("#searchChildrenColor1").css("display","block");
						$("#searchChildrenColor2").css("display","block");
					}else if(sumCnt[i]>=3){
						$("#childrenBlack1").css("display","none");
						$("#childrenBlack2").css("display","none");
						$("#childrenBlack3").css("display","none");
						$("#childrenColor1").css("display","none");
						$("#childrenColor2").css("display","none");
						$("#childrenColor3").css("display","none");
						$("#searchChildrenColor1").css("display","block");
						$("#searchChildrenColor2").css("display","block");
						$("#searchChildrenColor3").css("display","block");
					}				
				}

				if(ProductID[i] == '29125'){
		
					if(sumCnt[i]==1){
						$("#childrenCBlack1").css("display","none");
						$("#childrenCColor1").css("display","none");
						$("#searchChildrenCColor1").css("display","block");
					}else if(sumCnt[i]==2){
						$("#childrenCBlack1").css("display","none");
						$("#childrenCBlack2").css("display","none");
						$("#childrenCColor1").css("display","none");
						$("#childrenCColor2").css("display","none");
						$("#searchChildrenCColor1").css("display","block");
						$("#searchChildrenCColor2").css("display","block");
					}else if(sumCnt[i]>=3){
						$("#childrenCBlack1").css("display","none");
						$("#childrenCBlack2").css("display","none");
						$("#childrenCBlack3").css("display","none");
						$("#childrenCColor1").css("display","none");
						$("#childrenCColor2").css("display","none");
						$("#childrenCColor3").css("display","none");
						$("#searchChildrenCColor1").css("display","block");
						$("#searchChildrenCColor2").css("display","block");
						$("#searchChildrenCColor3").css("display","block");
					}				
				}

				if(ProductID[i] == '28826'){
					if(sumCnt[i]==1){
						$("#dailyBlack1").css("display","none");
						$("#dailyColor1").css("display","none");
						$("#searchDailyColor1").css("display","block");
					}else if(sumCnt[i]>=2){
						$("#dailyBlack1").css("display","none");
						$("#dailyBlack2").css("display","none");
						$("#dailyColor1").css("display","none");
						$("#dailyColor2").css("display","none");
						$("#searchDailyColor1").css("display","block");
						$("#searchDailyColor2").css("display","block");
					}	
				}	

				if(ProductID[i] == '30821'){
					if(sumCnt[i]==1){
						$("#chloroBlack1").css("display","none");
						$("#chloroColor1").css("display","none");
						$("#searchChloroColor1").css("display","block");
					}else if(sumCnt[i]==2){
						$("#chloroBlack1").css("display","none");
						$("#chloroBlack2").css("display","none");
						$("#chloroColor1").css("display","none");
						$("#chloroColor2").css("display","none");
						$("#searchChloroColor1").css("display","block");
						$("#searchChloroColor2").css("display","block");
					}else if(sumCnt[i]>=3){
						$("#chloroBlack1").css("display","none");
						$("#chloroBlack2").css("display","none");
						$("#chloroBlack3").css("display","none");
						$("#chloroColor1").css("display","none");
						$("#chloroColor2").css("display","none");
						$("#chloroColor3").css("display","none");
						$("#searchChloroColor1").css("display","block");
						$("#searchChloroColor2").css("display","block");
						$("#searchChloroColor3").css("display","block");
					}			
				}

				if(ProductID[i] == '26206'){
					if(sumCnt[i]==1){
						$("#enzygenBlock1").css("display","none");
						$("#enzygenColor1").css("display","none");
						$("#searchEnzygenColor1").css("display","block");
					}else if(sumCnt[i]==2){
						$("#enzygenBlock1").css("display","none");
						$("#enzygenBlock2").css("display","none");
						$("#enzygenColor1").css("display","none");
						$("#enzygenColor2").css("display","none");
						$("#searchEnzygenColor1").css("display","block");
						$("#searchEnzygenColor2").css("display","block");
					}else if(sumCnt[i]>=3){
						$("#enzygenBlock1").css("display","none");
						$("#enzygenBlock2").css("display","none");
						$("#enzygenBlock3").css("display","none");
						$("#enzygenColor1").css("display","none");
						$("#enzygenColor2").css("display","none");
						$("#enzygenColor3").css("display","none");
						$("#searchEnzygenColor1").css("display","block");
						$("#searchEnzygenColor2").css("display","block");
						$("#searchEnzygenColor3").css("display","block");
					}
				}		
				
							
			}	

			if (productInfo.indexOf("29276") != -1) {
				$("#29276").css("display","block");
			}else{
				$("#29276").css("display","none");	
			}

			if (productInfo.indexOf("31021") != -1) {
				$("#31021").css("display","block");
			}else{
				$("#31021").css("display","none");	
			}

			if (productInfo.indexOf("31022") != -1) {
				$("#31022").css("display","block");
			}else{
				$("#31022").css("display","none");	
			}

			if (productInfo.indexOf("31020") != -1) {
				$("#31020").css("display","block");
			}else{
				$("#31020").css("display","none");	
			}

			if (productInfo.indexOf("28582") != -1) {
				$("#28582").css("display","block");
			}else{
				$("#28582").css("display","none");	
			}

			if (productInfo.indexOf("29125") != -1) {
				$("#29125").css("display","block");
			}else{
				$("#29125").css("display","none");	
			}	

			if (productInfo.indexOf("28826") != -1) {
				$("#28826").css("display","block");
			}else{
				$("#28826").css("display","none");	
			}	

			if (productInfo.indexOf("30821") != -1) {
				$("#30821").css("display","block");
			}else{
				$("#30821").css("display","none");	
			}	

			if (productInfo.indexOf("26206") != -1) {
				$("#26206").css("display","block");
			}else{
				$("#26206").css("display","none");	
			}	

			if (productInfo.indexOf("29200") != -1) {
				$("#29200").css("display","block");
			}else{
				$("#29200").css("display","none");	
			}	

  	  	});	
  		var frm = "";
  		var color1 = 0;
  		var color2 = 0;
  		var color3 = 0;
  		var color4 = 0;
  		var color5 = 0;
		var color6 = 0;
		var color7 = 0;
		var color8 = 0;
		var color9 = 0;
		var color10 = 0;

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
				$("#moningBlack1").css("display","none");
				$("#moningColor1").css("display","block");
				color1 += 1;
			}else if(num ==2){
				$("#moningBlack1").css("display","block");
				$("#moningColor1").css("display","none");
				color1 -= 1;
			}else if(num ==3){
				$("#nightBlack2").css("display","none");
				$("#nightColor2").css("display","block");
				color1 += 3;			
			}else if(num ==4){
				 $("#nightColor2").css("display","none");
				 $("#nightBlack2").css("display","block");
				 color1 -= 3;
			}

			if(num ==5){
				$("#woman1Black1").css("display","none");
				$("#woman1Color1").css("display","block");
				color2 += 1;
			}else if(num ==6){
				$("#woman1Color1").css("display","none");
				$("#woman1Black1").css("display","block");
				color2 -= 1;
			}else if(num==7){
				$("#woman1Black2").css("display","none");
				$("#woman1Color2").css("display","block");
				color2 += 1;
			}else if(num==8){
				$("#woman1Color2").css("display","none");
				$("#woman1Black2").css("display","block");
				color2 -= 1;
			}else if(num==9){
				$("#woman1Black3").css("display","none");
				$("#woman1Color3").css("display","block");
				color2 += 1;
			}else if(num==10){
				 $("#woman1Color3").css("display","none");
				 $("#woman1Black3").css("display","block");
				 color2 -= 1;
			}	

			if(num ==11){
				$("#woman2Black1").css("display","none");
				$("#woman2Color1").css("display","block");
				color3 += 1;
			}else if(num ==12){
				$("#woman2Color1").css("display","none");
				$("#woman2Black1").css("display","block");
				color3 -= 1;
			}else if(num==13){
				$("#woman2Black2").css("display","none");
				$("#woman2Color2").css("display","block");
				color3 += 1;
			}else if(num==14){
				$("#woman2Color2").css("display","none");
				$("#woman2Black2").css("display","block");
				color3 -= 1;
			}else if(num==15){
				$("#woman2Black3").css("display","none");
				$("#woman2Color3").css("display","block");
				color3 += 1;
			}else if(num==16){
				 $("#woman2Color3").css("display","none");
				 $("#woman2Black3").css("display","block");
				 color3 -= 1;
			}

			if(num==17){
				$("#mansBlack").css("display","none");
				$("#mansColor").css("display","block");
				color4 += 1;
			}else if(num==18){
				$("#mansColor").css("display","none");
				$("#mansBlack").css("display","block");
				color4 -= 1;
			}

			if(num ==19){
				$("#childrenBlack1").css("display","none");
				$("#childrenColor1").css("display","block");
				color5 += 1;
			}else if(num ==20){
				$("#childrenColor1").css("display","none");
				$("#childrenBlack1").css("display","block");
				color5 -= 1;
			}else if(num==21){
				$("#childrenBlack2").css("display","none");
				$("#childrenColor2").css("display","block");
				color5 += 1;
			}else if(num==22){
				$("#childrenColor2").css("display","none");
				$("#childrenBlack2").css("display","block");
				color5 -= 1;
			}else if(num==23){
				$("#childrenBlack3").css("display","none");
				$("#childrenColor3").css("display","block");
				color5 += 1;
			}else if(num==24){
				 $("#childrenColor3").css("display","none");
				 $("#childrenBlack3").css("display","block");
				 color5 -= 1;
			}	

			if(num ==25){
				$("#childrenCBlack1").css("display","none");
				$("#childrenCColor1").css("display","block");
				color6 += 1;
			}else if(num ==26){
				$("#childrenCColor1").css("display","none");
				$("#childrenCBlack1").css("display","block");
				color6 -= 1;
			}else if(num==27){
				$("#childrenCBlack2").css("display","none");
				$("#childrenCColor2").css("display","block");
				color6 += 1;
			}else if(num==28){
				$("#childrenCColor2").css("display","none");
				$("#childrenCBlack2").css("display","block");
				color6 -= 1;
			}else if(num==29){
				$("#childrenCBlack3").css("display","none");
				$("#childrenCColor3").css("display","block");
				color6 += 1;
			}else if(num==30){
				 $("#childrenCColor3").css("display","none");
				 $("#childrenCBlack3").css("display","block");
				 color6 -= 1;
			}

			if(num ==31){
				$("#dailyBlack1").css("display","none");
				$("#dailyColor1").css("display","block");
				color7 += 1;
			}else if(num ==32){
				$("#dailyColor1").css("display","none");
				$("#dailyBlack1").css("display","block");
				color7 -= 1;
			}else if(num==33){
				$("#dailyBlack2").css("display","none");
				$("#dailyColor2").css("display","block");
				color7 += 1;
			}else if(num==34){
				$("#dailyColor2").css("display","none");
				$("#dailyBlack2").css("display","block");
				color7 -= 1;
			}	

			if(num ==35){
				$("#chloroBlack1").css("display","none");
				$("#chloroColor1").css("display","block");
				color8 += 1;
			}else if(num ==36){
				$("#chloroColor1").css("display","none");
				$("#chloroBlack1").css("display","block");
				color8 -= 1;
			}else if(num==37){
				$("#chloroBlack2").css("display","none");
				$("#chloroColor2").css("display","block");
				color8 += 1;
			}else if(num==38){
				$("#chloroColor2").css("display","none");
				$("#chloroBlack2").css("display","block");
				color8 -= 1;
			}else if(num==39){
				$("#chloroBlack3").css("display","none");
				$("#chloroColor3").css("display","block");
				color8 += 1;
			}else if(num==40){
				$("#chloroColor3").css("display","none");
				$("#chloroBlack3").css("display","block");
				color8 -= 1;
			}

			if(num ==41){
				$("#enzygenBlock1").css("display","none");
				$("#enzygenColor1").css("display","block");
				color9 += 1;
			}else if(num ==42){
				$("#enzygenColor1").css("display","none");
				$("#enzygenBlock1").css("display","block");
				color9 -= 1;
			}else if(num==43){
				$("#enzygenBlock2").css("display","none");
				$("#enzygenColor2").css("display","block");
				color9 += 1;
			}else if(num==44){
				$("#enzygenColor2").css("display","none");
				$("#enzygenBlock2").css("display","block");
				color9 -= 1;
			}else if(num==45){
				$("#enzygenBlock3").css("display","none");
				$("#enzygenColor3").css("display","block");
				color9 += 1;
			}else if(num==46){
				$("#enzygenColor3").css("display","none");
				$("#enzygenBlock3").css("display","block");
				color9 -= 1;
			}

			if(num ==47){
				$("#provicnicBlack1").css("display","none");	
				$("#provicnicColor1").css("display","block");
				color10 += 1;
			}else if(num ==48){
				$("#provicnicColor1").css("display","none");
				$("#provicnicBlack1").css("display","block");	
				color10 -= 1;
			}
		}
		
		
		function clicksubmit(){
			frm = document.step4ApplyForm;	
			var qty = "";
			var step4ProductVal  = []; 
			var productKey =Array();
			var productVal =Array();
			var proCnt = 0;
			var step4ProductVal  = {
				    '29276': color1,
				    '31021': color2,
				    '31022': color3,
				    '31020': color4,
				    '28582': color5,
				    '29125': color6,
				    '28826': color7,
				    '30821': color8,
				    '26206': color9,
				    '29200': color10
		};
			
			for(var key in step4ProductVal){
			  productKey[proCnt] = key;
			  productVal[proCnt] = step4ProductVal[key];
			  proCnt++			   
			}
			if(confirm("등록하시겠습니까?")){ 
				frm.productKey.value = productKey;
				frm.productVal.value = productVal;
				frm.crdateDate.value = getTimeStamp();
		
				frm.action = "step4Action.php";
				frm.submit();
			}
		} 
			
  	</script>
    <div class="StepImg">
		<img alt="" src="../images/step4button.png" style="width: 95%;">  
	</div>
	<form name="step4ApplyForm" method="post">
		<input type="hidden" name="crdateDate" value="">
		<input type="hidden" name="step" value="4">	
		<input type="hidden" name="qty" value="">
		<input type="hidden" name="productKey" value="">
		<input type="hidden" name="productVal" value="">
		<input type="hidden" name="programID" value="<?php echo $programID?>">
		<div class="step4base" align="center">

<!-- 코어헬스 -->
			<div id="29276" style="display: none;">		
				<div style="margin-right: 230px;">
					<img alt="" src="../images/coreicon.png" style="width: 25px; vertical-align: middle;margin-bottom: 5px;"><font color="orange" size="4px;" style="vertical-align: middle;">&nbsp;<b>코어 헬스 팩</b></font>
				</div>
				<table style="font-size:14px; padding-left:10px;">
					<tr>
						<td class="tdFont">
							<b>종합비타민 & 미네랄<br/>옥타코사놀 함유 유지:</b>	
						</td>
						<td rowspan="2">
							<img id="moningBlack1" name="moningBlack1" alt="" src="../images/moningBlack.png" onclick="changeColor(1);" style="margin-left: 20px; margin-top: 20px; width: 50px;">
							<img id="moningColor1" name="moningColor1" alt="" src="../images/moningColor.png" onclick="changeColor(2);" style="display: none;margin-left: 20px;margin-top: 20px;width: 50px;">
							<img id="searchColor1" name="searchColor1" alt="" src="../images/moningColor.png" style="display: none;margin-left: 20px;margin-top: 20px;width: 50px;">
						</td>
						<td rowspan="2">
							<img id="nightBlack2" name="nightBlack2" alt="" src="../images/nightBlack.png"  onclick="changeColor(3);" style="margin-left: 5px;margin-top: 20px;width: 50px;">
							<img id="nightColor2" name="nightColor2" alt="" src="../images/nightColor.png"  onclick="changeColor(4)" style="display: none;margin-left: 5px;margin-top: 20px;width: 50px;">
							<img id="searchColor2" name="searchColor2" alt="" src="../images/nightColor.png" style="display: none;margin-left: 5px;margin-top: 20px;width: 50px;">
						</td>
					</tr>
					<tr>
						<td class="tdFont">
							지구력 증진에 도움을<br/>줄 수 있음
						</td>
					</tr>
					
					<tr>
						<td class="tdFont">
							<b>L-테아닌:</b><br/>스트레스로 인한<br/>긴장완화에 <br/>도움을 줄 수 있음
						</td>
						<td colspan="2" rowspan="2">
							<font style="margin-left: 40px; margin-top: 10px;">1일 2회 / 1회 1포<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(아침, 저녁 각 1포씩)</font>
						</td>
					</tr>
				
				</table>
			</div>	
			<div style="height: 20px;"></div>
<!-- 우먼스포뮬라1 -->	
			<div id="31021" style="display: none;">		
				<div style="margin-right: 210px;">
					<img alt="" src="../images/coreicon.png" style="width: 25px; vertical-align: middle; margin-bottom: 5px;"><font color="orange" size="4px;" style="margin-bottom: middle;">&nbsp;<b>우먼스포뮬라1</b></font>
				</div>
				<table class="tableStyle">
					<tr>
						<td style="font-size:14px; padding-left:35px;">
							<b>멀티비타민 & 미네랄</b>	
						</td>
						<td rowspan="2" class="verticalM">
							<img id="woman1Black1" name="woman1Black1" alt="" src="../images/woman1Black.png" onclick="changeColor(5);" align="right" style="margin-left: 25px;">
							<img id="woman1Color1" name="woman1Color1" alt="" src="../images/woman1Color.png" onclick="changeColor(6);" style="display: none;margin-left: 25px;">
							<img id="searchWoman1Color1" name="searchWoman1Color1" alt="" src="../images/woman1Color.png" style="display: none;margin-left: 25px;">
						</td>
						<td rowspan="2" class="verticalM">
							<img id="woman1Black2" name="nightBlack2" alt="" src="../images/woman1Black.png"  onclick="changeColor(7);" style="margin-left: 5px;">
							<img id="woman1Color2" name="nightColor2" alt="" src="../images/woman1Color.png"  onclick="changeColor(8)" style="display: none;margin-left: 5px;">
							<img id="searchWoman1Color2" name="searchWoman1Color2" alt="" src="../images/woman1Color.png" style="display: none;margin-left: 5px;">
						</td>
						<td rowspan="2" class="verticalM">
							<img id="woman1Black3" name="woman1Black3" alt="" src="../images/woman1Black.png"  onclick="changeColor(9);" style="margin-left: 5px;">
							<img id="woman1Color3" name="woman1Color3" alt="" src="../images/woman1Color.png"  onclick="changeColor(10)" style="display: none;margin-left: 5px;">
							<img id="searchWoman1Color3" name="searchWoman1Color3" alt="" src="../images/woman1Color.png" style="display: none;margin-left: 5px;">
						</td>
					</tr>
					<tr>
						<td style="font-size:14px; padding-left:35px;">
							가임기 여성을 위한<br/>영양보충용 제품
						</td> 
						
					</tr>
				</table>
				<div class="font">1일 3회 / 1회 2캡슐</div>
			</div>	
<!-- 우먼스포뮬라2 -->	
			<div id="31022" style="display: none;">		
				<div style="margin-right: 210px;">
					<img alt="" src="../images/coreicon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="orange" size="4px;" style="margin-bottom: middle;">&nbsp;<b>우먼스포뮬라2</b></font>
				</div>
					<table class="tableStyle">
					<tr>
						<td style="font-size:14px; padding-left:35px;">
							<b>멀티비타민 & 미네랄</b>	
						</td>
						<td rowspan="2">
							<img id="woman2Black1" name="woman2Black1" alt="" src="../images/woman1Black.png" onclick="changeColor(11);" align="right" style="margin-left: 25px;">
							<img id="woman2Color1" name="woman2Color1" alt="" src="../images/woman1Color.png" onclick="changeColor(12);" style="display: none;margin-left: 25px;">
							<img id="searchWoman2Color1" name="searchWoman2Color1" alt="" src="../images/woman1Color.png" style="display: none;margin-left: 25px;">
						</td>
						<td rowspan="2">
							<img id="woman2Black2" name="woman2Black2" alt="" src="../images/woman1Black.png"  onclick="changeColor(13);" style="margin-left: 5px;">
							<img id="woman2Color2" name="woman2Color2" alt="" src="../images/woman1Color.png"  onclick="changeColor(14)" style="display: none;margin-left: 5px;">
							<img id="searchWoman2Color2" name="searchWoman2Color2" alt="" src="../images/woman1Color.png" style="display: none;margin-left: 5px;">
						</td>
						<td rowspan="2">
							<img id="woman2Black3" name="woman2Black3" alt="" src="../images/woman1Black.png"  onclick="changeColor(15);" style="margin-left: 5px;">
							<img id="woman2Color3" name="woman2Color3" alt="" src="../images/woman1Color.png"  onclick="changeColor(16)" style="display: none;margin-left: 5px;">
							<img id="searchWoman2Color3" name="searchWoman2Color3" alt="" src="../images/woman1Color.png" style="display: none;margin-left: 5px;">
						</td>
					</tr>
					<tr>
						<td style="font-size:14px; padding-left:35px;">
							중년 여성을 위한<br/>영양보충용 제품
						</td> 
					</tr>
				</table>
				<div class="font">1일 3회 / 1회 2캡슐</div>
			</div>	
<!-- 멘스포뮬라 -->	
			<div id="31020" style="display: none;">
				<div style="margin-right: 230px;">
					<img alt="" src="../images/coreicon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="orange" size="4px;" style="margin-bottom: middle;">&nbsp;<b>멘스포뮬라</b></font>
				</div>
				<table class="tableStyle">
					<tr>
						<td style="font-size:14px; padding-left:18px;">
							<b>멀티비타민 & 미네랄</b>
						</td>
						<td rowspan="2">
							<img id="mansBlack" name="mansBlack" alt="" src="../images/mansBlack.png" onclick="changeColor(17);" style="margin-left: 40px;">
							<img id="mansColor" name="mansColor" alt="" src="../images/mansColor.png" onclick="changeColor(18);" style="display: none;margin-left: 40px;">
							<img id="searchMansColor" name="searchMansColor" alt="" src="../images/mansColor.png" style="display: none;margin-left: 40px;">	
						</td>
					</tr>
					<tr>
						<td style="font-size:14px; padding-left:18px;">
							남성을 위한<br/>영향보충용 제품
						</td>
					
					</tr>
				</table>
				<div class="font">1일 1회 / 1회 4캡슐</div>
			</div>	
<!-- 칠드런스 포뮬라 -->	
			<div id="28582" style="display: none;">
				<div style="margin-right: 191px;">
					<img alt="" src="../images/coreicon.png" style="width: 25px; vertical-align: middle; margin-bottom: 5px;"><font color="orange" size="4px;" style="margin-bottom: middle;">&nbsp;<b>칠드런스 포뮬라</b></font>
				</div>
				<table class="tableStyle">
					<tr>
						<td style="font-size:14px; padding-left:35px;vertical-align: top;">
							<b>멀티비타민 & 미네랄</b>	
						</td>
						<td rowspan="2">
							<img id="childrenBlack1" name="childrenBlack1" alt="" src="../images/childrenBlack.png" onclick="changeColor(19);" align="right" style="margin-left: 25px;">
							<img id="childrenColor1" name="childrenColor1" alt="" src="../images/childrenColor.png" onclick="changeColor(20);" style="display: none;margin-left: 25px;">
							<img id="searchChildrenColor1" name="searchChildrenColor1" alt="" src="../images/childrenColor.png" style="display: none;margin-left: 25px;">
						</td>
						<td rowspan="2">
							<img id="childrenBlack2" name="childrenBlack2" alt="" src="../images/childrenBlack.png"  onclick="changeColor(21);" style="margin-left: 5px;">
							<img id="childrenColor2" name="childrenColor2" alt="" src="../images/childrenColor.png"  onclick="changeColor(22)" style="display: none;margin-left: 5px;">
							<img id="searchChildrenColor2" name="searchChildrenColor2" alt="" src="../images/../images/childrenColor.png" style="display: none;margin-left: 5px;">
						</td>
						<td rowspan="2">
							<img id="childrenBlack3" name="childrenBlack3" alt="" src="../images/childrenBlack.png"  onclick="changeColor(23);" style="margin-left: 5px;">
							<img id="childrenColor3" name="childrenColor3" alt="" src="../images/childrenColor.png"  onclick="changeColor(24)" style="display: none;margin-left: 5px;">
							<img id="searchChildrenColor3" name="searchChildrenColor3" alt="" src="../images/../images/childrenColor.png" style="display: none;margin-left: 5px;">
						</td>
					</tr>				
				</table>
				<div class="font2">1일 3회 / 1회 1정</div>
			</div>	
<!-- 칠드런스 칼슘 -->
			<div id="29125" style="display: none;">
				<div style="margin-right: 205px;">
					<img alt="" src="../images/coreicon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="orange" size="4px;" style="margin-bottom: middle;">&nbsp;<b>칠드런스 칼슘</b></font>
				</div>	
				<table class="tableStyle">
					<tr>
						<td style="font-size:14px; padding-left:35px;">
							<b>칼슘 & 마그네슘 &<br/>비타민D & 비타민K</b>	
						</td>
						<td rowspan="2">
							<img id="childrenCBlack1" name="childrenCBlack1" alt="" src="../images/childrenCBlack.png" onclick="changeColor(25);" align="right" style="margin-left: 30px;">
							<img id="childrenCColor1" name="childrenCColor1" alt="" src="../images/childrenCColor.png" onclick="changeColor(26);" style="display: none;margin-left: 30px;">
							<img id="searchChildrenCColor1" name="searchChildrenCColor1" alt="" src="../images/childrenCColor.png" style="display: none;margin-left: 30px;">
						</td>
						<td rowspan="2">
							<img id="childrenCBlack2" name="childrenCBlack2" alt="" src="../images/childrenCBlack.png"  onclick="changeColor(27);" style="margin-left: 5px;">
							<img id="childrenCColor2" name="childrenCColor2" alt="" src="../images/childrenCColor.png"  onclick="changeColor(28)" style="display: none;margin-left: 5px;">
							<img id="searchChildrenCColor2" name="searchChildrenCColor2" alt="" src="../images/childrenCColor.png" style="display: none;margin-left: 5px;">
						</td>
						<td rowspan="2">
							<img id="childrenCBlack3" name="childrenCBlack3" alt="" src="../images/childrenCBlack.png"  onclick="changeColor(29);" style="margin-left: 5px;">
							<img id="childrenCColor3" name="childrenCColor3" alt="" src="../images/childrenCColor.png"  onclick="changeColor(30)" style="display: none;margin-left: 5px;">
							<img id="searchChildrenCColor3" name="searchChildrenCColor3" alt="" src="../images/childrenCColor.png" style="display: none;margin-left: 5px;">
						</td>
					</tr>			
				</table>
				<div class="font2">1일 3회 / 1회 1정</div>
			</div>	
<!-- 데일리포스 -->
			<div id="28826" style="display: none;">
				<div style="margin-right: 220px;">
					<img alt="" src="../images/coreicon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="orange" size="4px;" style="margin-bottom: middle;">&nbsp;<b>데일리 포스</b></font>
				</div>
				<table class="tableStyle">
					<tr>
						<td style="font-size:14px; padding-left:35px;vertical-align: top;">
							<b>멀티비타민</b>	
						</td>
		
						
						<td rowspan="2">
							<img id="dailyBlack1" name="dailyBlack1" alt="" src="../images/dailyBlack.png" onclick="changeColor(31);" style="width: 57px;margin-left: 75px;">
							<img id="dailyColor1" name="dailyColor1" alt="" src="../images/dailyColor.png" onclick="changeColor(32);" style="display: none;width: 57px;margin-left: 75px;">
							<img id="searchDailyColor1" name="searchDailyColor1" alt="" src="../images/dailyColor.png" style="display: none;width: 57px;margin-left: 75px;">
						</td> 
						<td rowspan="2">
							<img id="dailyBlack2" name="dailyBlack2" alt="" src="../images/dailyBlack.png"  onclick="changeColor(33);" style="width: 57px;margin-left: 5px;">
							<img id="dailyColor2" name="dailyColor2" alt="" src="../images/dailyColor.png"  onclick="changeColor(34)" style="display: none;width: 57px;margin-left: 5px;">
							<img id="searchDailyColor2" name="searchDailyColor2" alt="" src="../images/dailyColor.png" style="display: none;width: 57px;margin-left: 5px;">
						</td>
						
						
					</tr>			
				</table>
				<div class="font2">1일 2회 / 1회 2정</div>
			</div>		
<!-- 클로로 파워 -->
			<div id="30821" style="display: none;">
				<div style="margin-right: 220px;">
					<img alt="" src="../images/coreicon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="orange" size="4px;" style="margin-bottom: middle;">&nbsp;<b>클로로 파워</b></font>
				</div>
				<table class="tableStyle">
					<tr>
						<td style="font-size:14px; padding-left:35px;vertical-align: top;">
							<b>스피루리나:</b>
						</td>
						<td rowspan="2">
							<img id="chloroBlack1" name="chloroBlack1" alt="" src="../images/chloroBlack.png" onclick="changeColor(35);" style="margin-left: 30px;width: 33px;">
							<img id="chloroColor1" name="chloroColor1" alt="" src="../images/chloroColor.png" onclick="changeColor(36);" style="display: none;margin-left: 30px;width: 33px;">
							<img id="searchChloroColor1" name="searchChloroColor1" alt="" src="../images/chloroColor.png" style="display: none;margin-left: 30px;width:33px;">	
						</td>
						<td rowspan="2">
							<img id="chloroBlack2" name="chloroBlack2" alt="" src="../images/chloroBlack.png" onclick="changeColor(37);" style="margin-left: 5px; width: 33px;">
							<img id="chloroColor2" name="chloroColor2" alt="" src="../images/chloroColor.png" onclick="changeColor(38);" style="display: none;margin-left: 5px;width: 33px;">
							<img id="searchChloroColor2" name="searchChloroColor2" alt="" src="../images/chloroColor.png" style="display: none;margin-left: 5px;width: 33px;">	
						</td>
						<td rowspan="2">
							<img id="chloroBlack3" name="chloroBlack3" alt="" src="../images/chloroBlack.png" onclick="changeColor(39);" style="margin-left: 5px;width: 33px;">
							<img id="chloroColor3" name="chloroColor3" alt="" src="../images/chloroColor.png" onclick="changeColor(40);" style="display: none;margin-left: 5px;width: 33px;">
							<img id="searchChloroColor3" name="searchChloroColor3" alt="" src="../images/chloroColor.png" style="display: none;margin-left: 5px;width: 33px;">	
						</td>
					</tr>
					<tr>
					
						<td style="font-size:14px; padding-left:35px;">
							피부건강ㆍ항산화에<br/>
							도움을 줄 수 있음
						</td>
						
					</tr>
				</table>		
				<div class="font">1일 3회 / 1회 2캡슐</div>
			</div>		
<!-- 엔지겐 B 플러스 -->
			<div id="26206" style="display: none;">
				<div style="margin-right: 190px;">
					<img alt="" src="../images/coreicon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="orange" size="4px;" style="margin-bottom: middle;">&nbsp;<b>엔지겐 B 플러스</b></font>
				</div>
				<table class="tableStyle">
					<tr>
						<td style="font-size:14px; padding-left:10px;vertical-align: top;">
							<b>비타민 B군 보충제품</b>
						</td>
						<td rowspan="2">
							<img id="enzygenBlock1" name="enzygenBlock1" alt="" src="../images/enzygenBlock.png" onclick="changeColor(41);" style="margin-left:50px;">
							<img id="enzygenColor1" name="enzygenColor1" alt="" src="../images/enzygenColor.png" onclick="changeColor(42);" style="display: none;margin-left: 50px;">
							<img id="searchEnzygenColor1" name="searchEnzygenColor1" alt="" src="../images/enzygenColor.png" style="display: none;margin-left: 50px;">	
						</td>
						<td rowspan="2">
							<img id="enzygenBlock2" name="enzygenBlock2" alt="" src="../images/enzygenBlock.png" onclick="changeColor(43);" style="margin-left: 5px;">
							<img id="enzygenColor2" name="enzygenColor2" alt="" src="../images/enzygenColor.png" onclick="changeColor(44);" style="display: none;margin-left: 5px;">
							<img id="searchEnzygenColor2" name="searchEnzygenColor2" alt="" src="../images/enzygenColor.png" style="display: none;margin-left: 5px;">	
						</td>
						<td rowspan="2">
							<img id="enzygenBlock3" name="enzygenBlock3" alt="" src="../images/enzygenBlock.png" onclick="changeColor(45);" style="margin-left: 5px;">
							<img id="enzygenColor3" name="enzygenColor3" alt="" src="../images/enzygenColor.png" onclick="changeColor(46);" style="display: none;margin-left: 5px;">
							<img id="searchEnzygenColor3" name="searchEnzygenColor3" alt="" src="../images/enzygenColor.png" style="display: none;margin-left: 5px;">	
						</td> 
					</tr>
				</table>	
				<div class="font2">1일 3회 / 1회 1캡슐</div>
			</div>										
<!-- 프로바이오닉 플러스 -->
			<div id="29200" style="display: none;">
				<div style="margin-right: 160px;">
					<img alt="" src="../images/coreicon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="orange" size="4px;" style="margin-bottom: middle;">&nbsp;<b>프로바이오닉 플러스</b></font>
				</div>
				<table class="tableStyle">
					<tr>
						<td style="font-size:14px; padding-left:10px;vertical-align: top;">
							<b>프로바이오틱스:</b><br/>
							유산균 증식 및 유해균 억제ㆍ<br/>
							배변활동 원활에 도움을 줄 수 있음
						</td>
						<td rowspan="2">
							<img id="provicnicBlack1" name="provicnicBlack1" alt="" src="../images/provicnicBlack.png" onclick="changeColor(47);" style="margin-left: 10px;">
							<img id="provicnicColor1" name="provicnicColor1" alt="" src="../images/provionicColor.png" onclick="changeColor(48);"style="display: none;margin-left:10px;">
							<img id="searchProvicnicColor1" name="searchProvicnicColor1" alt="" src="../images/provionicColor.png" style="display: none;margin-left: 10px;">
						</td>
					</tr>
				</table>			
				<div class="font2">1일 1회 / 1회 1포</div>
			</div>
			<div align="center">
				<img alt="" src="../images/submitButton4.png" onclick="clicksubmit()"> 
			</div>
		</div>
	</form>