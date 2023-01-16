<?php session_start();?>
<? include "./header.inc"; ?>
<?php  include_once("../inc/function.php");?>
<?php 
	include "../inc/dbconn.php";
	checkSessionValue();
	$userName=$_SESSION["username"];
	$IsCleanser = $_SESSION["cleanser"];

	$productInfo = array();
	$query = "select B.productID,A.ProgramID
				from ProgramMaster A,
					 ProgramDetail B,
					 Product C
			   where A.programID = B.programID
				 and B.productID = C.ProductID
	 			 and A.userID = '".$userName."'
			   	 and A.delFlag = 'N'
				 and C.step = '5'";
	$query_result = mysql_query($query);
	while($row = mysql_fetch_row($query_result)) {
		$productInfo[]= $row[0];
		$programID = $row[1];
		$productCnt = $row[0];
		$querySelect= "select sum(Amount) as cnt, ProductID
			             from StepRecord
			            where Step = 5
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
  		var productInfo="";
  		var ProdcutID = "";
  		var sumCnt="";
  		var loopCnt="";
  		$(document).ready(function(){
  			productInfo = <?php echo json_encode($productInfo)?>;
  			ProductID = <?php echo json_encode($ProductID)?>;
			sumCnt = <?php echo json_encode($sumCnt)?>;
			loopCnt = <?php echo $loopCnt?>;

			for(var i=0; i<loopCnt;i++){
				if(ProductID[i] == '28974'){
					if(sumCnt[i]==1){
						$("#prosBlack1").css("display","none");
						$("#prosColor1").css("display","none");
						$("#searchProsColor1").css("display","block");
					}else if(sumCnt[i]>=2){
						$("#prosBlack1").css("display","none");
						$("#prosBlack2").css("display","none");
						$("#prosColor1").css("display","none");
						$("#prosColor2").css("display","none");
						$("#searchProsColor1").css("display","block");
						$("#searchProsColor2").css("display","block");
					}			
				}

				if(ProductID[i] == '28463'){
					if(sumCnt[i]>=1){
						$("#liverBlack1").css("display","none");
						$("#liverColor1").css("display","none");
						$("#searchLiverColor1").css("display","block");
					}		
				}

				if(ProductID[i] == '28584'){
					if(sumCnt[i]>=1){
						$("#noniBlack1").css("display","none");
						$("#noniColor1").css("display","none");
						$("#searchNoniColor1").css("display","block");
					}	
				}

				if(ProductID[i] == '25141'){
					if(sumCnt[i]==1){
						$("#omegaBlack1").css("display","none");
						$("#omegaColor1").css("display","none");
						$("#searchOmegaColor1").css("display","block");
					}else if(sumCnt[i]>=2){
						$("#omegaBlack1").css("display","none");
						$("#omegaColor1").css("display","none");
						$("#omegaBlack2").css("display","none");
						$("#omegaColor2").css("display","none");
						$("#searchOmegaColor1").css("display","block");
						$("#searchOmegaColor2").css("display","block");
					}		
				}

				if(ProductID[i] == '25155'){
					if(sumCnt[i]>=1){
						$("#visionBlack1").css("display","none");
						$("#visionColor1").css("display","none");
						$("#searchVisionColor1").css("display","block");
					}	
				}

				if(ProductID[i] == '18739'){
					if(sumCnt[i]>=1){
						$("#clearBlack1").css("display","none");
						$("#clearColor1").css("display","none");
						$("#searchClearColor1").css("display","block");
					}	
				}

				if(ProductID[i] == '17284'){
					if(sumCnt[i]==1){
						$("#phytoBlack1").css("display","none");
						$("#phytoColor1").css("display","none");
						$("#searchPhytoColor1").css("display","block");
					}else if(sumCnt[i]==2){
						$("#phytoBlack1").css("display","none");
						$("#phytoBlack2").css("display","none");
						$("#phytoColor1").css("display","none");
						$("#phytoColor2").css("display","none");
						$("#searchPhytoColor1").css("display","block");
						$("#searchPhytoColor2").css("display","block");
					}else if(sumCnt[i]>=3){
						$("#phytoBlack1").css("display","none");
						$("#phytoBlack2").css("display","none");
						$("#phytoBlack3").css("display","none");
						$("#phytoColor1").css("display","none");
						$("#phytoColor2").css("display","none");
						$("#phytoColor3").css("display","none");
						$("#searchPhytoColor1").css("display","block");
						$("#searchPhytoColor2").css("display","block");
						$("#searchPhytoColor3").css("display","block");
					}			
				}

				if(ProductID[i] == '28824'){
					if(sumCnt[i]==1){
						$("#bioBlack1").css("display","none");
						$("#bioColor1").css("display","none");
						$("#searchBioColor1").css("display","block");
					}else if(sumCnt[i]>=2){
						$("#bioBlack1").css("display","none");
						$("#bioColor1").css("display","none");
						$("#bioBlack2").css("display","none");
						$("#bioColor2").css("display","none");
						$("#searchBioColor1").css("display","block");
						$("#searchBioColor2").css("display","block");
					}		
				}

				if(ProductID[i] == '28830'){
					if(sumCnt[i]>=1){
						$("#cellularBlack1").css("display","none");
						$("#cellularColor1").css("display","none");
						$("#searchCellularColor1").css("display","block");
					}	
				}

				if(ProductID[i] == '24724'){
					if(sumCnt[i]>=1){
						$("#coenzymeBlack1").css("display","none");
						$("#coenzymeColor1").css("display","none");
						$("#searchCoenzymeColor1").css("display","block");
					}	
				}	

				if(ProductID[i] == '19281'){
					if(sumCnt[i]==1){
						$("#chitoBlack1").css("display","none");
						$("#chitoColor1").css("display","none");
						$("#searchChitoColor1").css("display","block");
					}else if(sumCnt[i]>=2){
						$("#chitoBlack1").css("display","none");
						$("#chitoColor1").css("display","none");
						$("#chitoBlack2").css("display","none");
						$("#chitoColor2").css("display","none");
						$("#searchChitoColor1").css("display","block");
						$("#searchChitoColor2").css("display","block");
					}		
				}	

				if(ProductID[i] == '30904'){
					if(sumCnt[i]==1){
						$("#boneBlack1").css("display","none");
						$("#boneColor1").css("display","none");
						$("#searchBoneColor1").css("display","block");
					}else if(sumCnt[i]==2){
						$("#boneBlack1").css("display","none");
						$("#boneBlack2").css("display","none");
						$("#boneColor1").css("display","none");
						$("#boneColor2").css("display","none");
						$("#searchBoneColor1").css("display","block");
						$("#searchBoneColor2").css("display","block");
					}else if(sumCnt[i]>=3){
						$("#boneBlack1").css("display","none");
						$("#boneBlack2").css("display","none");
						$("#boneBlack3").css("display","none");
						$("#boneColor1").css("display","none");
						$("#boneColor2").css("display","none");
						$("#boneColor3").css("display","none");
						$("#searchBoneColor1").css("display","block");
						$("#searchBoneColor2").css("display","block");
						$("#searchBoneColor3").css("display","block");
					}			
				}

				if(ProductID[i] == '30823'){
					if(sumCnt[i]>=1){
						$("#jointBlack1").css("display","none");
						$("#jointColor1").css("display","none");
						$("#searchJointColor1").css("display","block");
					}	
				}

				if(ProductID[i] == '27267'){
					if(sumCnt[i]>=1){
						$("#immunizenBlack1").css("display","none");
						$("#immunizenColor1").css("display","none");
						$("#searchImmunizenColor1").css("display","block");
					}	
				}

				if(ProductID[i] == '26189'){
					if(sumCnt[i]>=1){
						$("#leanBlack1").css("display","none");
						$("#leanColor1").css("display","none");
						$("#searchLeanColor1").css("display","block");
					}	
				}

				if(ProductID[i] == '23818'){
					if(sumCnt[i]==1){
						$("#soyBlack1").css("display","none");
						$("#soyColor1").css("display","none");
						$("#searchSoyColor1").css("display","block");
					}else if(sumCnt[i]>=2){
						$("#soyBlack1").css("display","none");
						$("#soyColor1").css("display","none");
						$("#soyBlack2").css("display","none");
						$("#soyColor2").css("display","none");
						$("#searchSoyColor1").css("display","block");
						$("#searchSoyColor2").css("display","block");
					}		
				}

				if(ProductID[i] == '22370'){
					if(sumCnt[i]==1){
						$("#enjuveBlack1").css("display","none");
						$("#enjuveColor1").css("display","none");
						$("#searchEnjuveColor1").css("display","block");
					}else if(sumCnt[i]>=2){
						$("#enjuveBlack1").css("display","none");
						$("#enjuveColor1").css("display","none");
						$("#enjuveBlack2").css("display","none");
						$("#enjuveColor2").css("display","none");
						$("#searchEnjuveColor1").css("display","block");
						$("#searchEnjuveColor2").css("display","block");
					}		
				}

				if(ProductID[i] == '29843'){
					if(sumCnt[i]==1){
						$("#unimateBlack1").css("display","none");
						$("#unimateColor1").css("display","none");
						$("#searchUnimateColor1").css("display","block");
					}else if(sumCnt[i]>=2){
						$("#unimateBlack1").css("display","none");
						$("#unimateColor1").css("display","none");
						$("#unimateBlack2").css("display","none");
						$("#unimateColor2").css("display","none");
						$("#searchUnimateColor1").css("display","block");
						$("#searchUnimateColor2").css("display","block");
					}		
				}

				if(ProductID[i] == '28586'){
					if(sumCnt[i]==1){
						$("#bioscplusBlack1").css("display","none");
						$("#bioscplusColor1").css("display","none");
						$("#searchBioscplusColor1").css("display","block");
					}else if(sumCnt[i]>=2){
						$("#bioscplusBlack1").css("display","none");
						$("#bioscplusColor1").css("display","none");
						$("#bioscplusBlack2").css("display","none");
						$("#bioscplusColor2").css("display","none");
						$("#searchBioscplusColor1").css("display","block");
						$("#searchBioscplusColor2").css("display","block");
					}		
				}

				if(ProductID[i] == '24991'){
					if(sumCnt[i]==1){
						$("#biosBlack1").css("display","none");
						$("#biosColor1").css("display","none");
						$("#searchBiosColor1").css("display","block");
					}else if(sumCnt[i]>=2){
						$("#biosBlack1").css("display","none");
						$("#biosColor1").css("display","none");
						$("#biosBlack2").css("display","none");
						$("#biosColor2").css("display","none");
						$("#searchBiosColor1").css("display","block");
						$("#searchBiosColor2").css("display","block");
					}		
				}

				if(ProductID[i] == '28911'){
					if(sumCnt[i]==1){
						$("#biossplusBlack1").css("display","none");
						$("#biossplusColor1").css("display","none");
						$("#searchBiossplusColor1").css("display","block");
					}else if(sumCnt[i]>=2){
						$("#biossplusBlack1").css("display","none");
						$("#biossplusColor1").css("display","none");
						$("#biossplusBlack2").css("display","none");
						$("#biossplusColor2").css("display","none");
						$("#searchBiossplusColor1").css("display","block");
						$("#searchBiossplusColor2").css("display","block");
					}		
				}

				if(ProductID[i] == '31215'){
					if(sumCnt[i]==1){
						$("#bios7Black1").css("display","none");
						$("#bios7Color1").css("display","none");
						$("#searchBios7Color1").css("display","block");
					}else if(sumCnt[i]>=2){
						$("#bios7Black1").css("display","none");
						$("#bios7Color1").css("display","none");
						$("#bios7Black2").css("display","none");
						$("#bios7Color2").css("display","none");
						$("#searchBios7Color1").css("display","block");
						$("#searchBios7Color2").css("display","block");
					}		
				}	

				if(ProductID[i] == '24927'){
					if(sumCnt[i]>=1){
						$("#eBlack1").css("display","none");
						$("#eColor1").css("display","none");
						$("#searchBiosEColor1").css("display","block");
					}	
				}

				if(ProductID[i] == '26022'){
					if(sumCnt[i]==1){
						$("#lifiberBlock1").css("display","none");
						$("#lifiberColor1").css("display","none");
						$("#lifibersearchColor1").css("display","block");
					}else if(sumCnt[i]>=2){
						$("#lifiberBlock1").css("display","none");
						$("#lifiberColor1").css("display","none");
						$("#lifiberBlock2").css("display","none");
						$("#lifiberColor2").css("display","none");
						$("#lifibersearchColor1").css("display","block");
						$("#lifibersearchColor2").css("display","block");
					}		
				}

				if(ProductID[i] == '24723'){
					if(sumCnt[i]==1){
						$("#aloeBlock1").css("display","none");
						$("#aloeColor1").css("display","none");
						$("#aloesearchColor1").css("display","block");
					}else if(sumCnt[i]>=2){
						$("#aloeBlock1").css("display","none");
						$("#aloeColor1").css("display","none");
						$("#aloeBlock2").css("display","none");
						$("#aloeColor2").css("display","none");
						$("#aloesearchColor1").css("display","block");
						$("#aloesearchColor2").css("display","block");
					}		
				}

				if(ProductID[i] == '15744'){
					if(sumCnt[i]==1){
						$("#parawayPlusBlack1").css("display","none");
						$("#parawayPlusColor1").css("display","none");
						$("#parawaySearchPlusColor1").css("display","block");
					}else if(sumCnt[i]>=2){
						$("#parawayPlusBlack1").css("display","none");
						$("#parawayPlusColor1").css("display","none");
						$("#parawayPlusBlack2").css("display","none");
						$("#parawayPlusColor2").css("display","none");
						$("#parawaySearchPlusColor1").css("display","block");
						$("#parawaySearchPlusColor2").css("display","block");
					}		
				}						
					
			}

  			if (productInfo.indexOf("28974") != -1) {
  				$("#28974").css("display","block");
  	  		}else{
  	  			$("#28974").css("display","none");	
  	  	  	}

			if (productInfo.indexOf("28463") != -1) {
				$("#28463").css("display","block");
			}else{
				$("#28463").css("display","none");
			}

			if (productInfo.indexOf("28584") != -1) {
				$("#28584").css("display","block");
			}else{
				$("#28584").css("display","none");
			}

			if (productInfo.indexOf("25141") != -1) {
				$("#25141").css("display","block");
			}else{
				$("#25141").css("display","none");
			}

			if (productInfo.indexOf("25155") != -1) {
				$("#25155").css("display","block");
			}else{
				$("#25155").css("display","none");
			}

			if (productInfo.indexOf("18739") != -1) {
				$("#18739").css("display","block");
			}else{
				$("#18739").css("display","none");
			}

			if (productInfo.indexOf("17284") != -1) {
				$("#17284").css("display","block");
			}else{
				$("#17284").css("display","none");
			}

			if (productInfo.indexOf("28824") != -1) {
				$("#28824").css("display","block");
			}else{
				$("#28824").css("display","none");
			}

			if (productInfo.indexOf("28830") != -1) {
				$("#28830").css("display","block");
			}else{
				$("#28830").css("display","none");
			}

			if (productInfo.indexOf("24724") != -1) {
				$("#24724").css("display","block");
			}else{
				$("#24724").css("display","none");
			}		

			if (productInfo.indexOf("19281") != -1) {
				$("#19281").css("display","block");
			}else{
				$("#19281").css("display","none");
			}

			if (productInfo.indexOf("30904") != -1) {
				$("#30904").css("display","block");
			}else{
				$("#30904").css("display","none");
			}

			if (productInfo.indexOf("30823") != -1) {
				$("#30823").css("display","block");
			}else{
				$("#30823").css("display","none");
			}

			if (productInfo.indexOf("27267") != -1) {
				$("#27267").css("display","block");
			}else{
				$("#27267").css("display","none");
			}

			if (productInfo.indexOf("26189") != -1) {
				$("#26189").css("display","block");
			}else{
				$("#26189").css("display","none");
			}

			if (productInfo.indexOf("23818") != -1) {
				$("#23818").css("display","block");
			}else{
				$("#23818").css("display","none");
			}

			if (productInfo.indexOf("22370") != -1) {
				$("#22370").css("display","block");
			}else{
				$("#22370").css("display","none");
			}

			if (productInfo.indexOf("29843") != -1) {
				$("#29843").css("display","block");
			}else{
				$("#29843").css("display","none");
			}

			if (productInfo.indexOf("28586") != -1) {
				$("#28586").css("display","block");
			}else{
				$("#28586").css("display","none");
			}

			if (productInfo.indexOf("24991") != -1) {
				$("#24991").css("display","block");
			}else{
				$("#24991").css("display","none");
			}	

			if (productInfo.indexOf("28911") != -1) {
				$("#28911").css("display","block");
			}else{
				$("#28911").css("display","none");
			}

			if (productInfo.indexOf("24927") != -1) {
				$("#24927").css("display","block");
			}else{
				$("#24927").css("display","none");
			}

			if (productInfo.indexOf("31215") != -1) {
				$("#31215").css("display","block");
			}else{
				$("#31215").css("display","none");
			}

			if (productInfo.indexOf("26022") != -1) {
				$("#26022").css("display","block");
			}else{
				$("#26022").css("display","none");
			}

			if (productInfo.indexOf("24723") != -1) {
				$("#24723").css("display","block");
			}else{
				$("#24723").css("display","none");
			}

			if (productInfo.indexOf("15744") != -1) {
				$("#15744").css("display","block");
			}else{
				$("#15744").css("display","none");
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
 		var color11 = 0;
 		var color12 = 0;
 		var color13 = 0;
 		var color14 = 0;
 		var color15 = 0;
 		var color16 = 0;
 		var color17 = 0;
 		var color18 = 0;
 		var color19 = 0;
 		var color20 = 0;
 		var color21 = 0;
 		var color22 = 0;
 		var color23 = 0;
 		var color24 = 0;
 		var color25 = 0;
 		var color26 = 0;
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
				$("#prosBlack1").css("display","none");
				$("#prosColor1").css("display","block");
				color1 += 1;
			}else if(num ==2){
				$("#prosColor1").css("display","none");
				$("#prosBlack1").css("display","block");
				color1 -= 1;
			}else if(num ==3){
				$("#prosBlack2").css("display","none");
				$("#prosColor2").css("display","block");
				color1 += 1;
			}else if(num ==4){
				$("#prosBlack2").css("display","block");
				$("#prosColor2").css("display","none");
				color1 -= 1;
			}

			if(num ==5){
				$("#liverBlack1").css("display","none");
				$("#liverColor1").css("display","block");
				color2 += 1;
			}else if(num ==6){
				$("#liverColor1").css("display","none");
				$("#liverBlack1").css("display","block");
				color2 -= 1;
			}
						
			if(num ==9){
				$("#noniBlack1").css("display","none");
				$("#noniColor1").css("display","block");
				color3 += 1;
			}else if(num ==10){
				$("#noniColor1").css("display","none");
				$("#noniBlack1").css("display","block");
				color3 -= 1;
			}
		
			if(num ==11){
				$("#omegaBlack1").css("display","none");
				$("#omegaColor1").css("display","block");
				color4 += 1;
			}else if(num ==12){
				$("#omegaColor1").css("display","none");
				$("#omegaBlack1").css("display","block");
				color4 -= 1;
			}else if(num ==13){
				$("#omegaBlack2").css("display","none");
				$("#omegaColor2").css("display","block");
				color4 += 1;
			}else if(num==14){
				$("#omegaBlack2").css("display","block");
				$("#omegaColor2").css("display","none");
				color4 -= 1;
			}
					
			if(num ==15){
				$("#visionBlack1").css("display","none");
				$("#visionColor1").css("display","block");
				color5 += 1;
			}else if(num==16){
				$("#visionColor1").css("display","none");
				$("#visionBlack1").css("display","block");
				color5 -= 1;
			}

			if(num ==17){
				$("#clearBlack1").css("display","none");
				$("#clearColor1").css("display","block");
				color6 += 1;
			}else if(num ==18){
				$("#clearColor1").css("display","none");
				$("#clearBlack1").css("display","block");
				color6 -= 1;
			}	

			if(num ==19){
				$("#cellularBlack1").css("display","none");
				$("#cellularColor1").css("display","block");
				color7 += 1;
			}else if(num ==20){
				$("#cellularColor1").css("display","none");
				$("#cellularBlack1").css("display","block");
				color7 -= 1;
			}	

			if(num ==21){
				$("#coenzymeBlack1").css("display","none");
				$("#coenzymeColor1").css("display","block");
				color8 += 1;
			}else if(num ==22){
				$("#coenzymeColor1").css("display","none");
				$("#coenzymeBlack1").css("display","block");
				color8 -= 1;
			}

			if(num ==23){
				$("#phytoBlack1").css("display","none");
				$("#phytoColor1").css("display","block");
				color9 += 1;
			}else if(num ==24){
				$("#phytoColor1").css("display","none");
				$("#phytoBlack1").css("display","block");
				color9 -= 1;
			}else if(num ==25){
				$("#phytoBlack2").css("display","none");
				$("#phytoColor2").css("display","block");
				color9 += 1;
			}else if(num ==26){
				$("#phytoColor2").css("display","none");
				$("#phytoBlack2").css("display","block");
				color9 -= 1;
			}else if(num ==27){
				$("#phytoBlack3").css("display","none");
				$("#phytoColor3").css("display","block");
				color9 += 1;
			}else if(num ==28){
				$("#phytoColor3").css("display","none");
				$("#phytoBlack3").css("display","block");
				color9 -= 1;
			}

			if(num ==29){
				$("#bioBlack1").css("display","none");
				$("#bioColor1").css("display","block");
				color10 += 1;
			}else if(num ==30){
				$("#bioColor1").css("display","none");
				$("#bioBlack1").css("display","block");
				color10 -= 1;
			}else if(num ==31){
				$("#bioBlack2").css("display","none");
				$("#bioColor2").css("display","block");
				color10 += 1;
			}else if(num ==32){
				$("#bioColor2").css("display","none");
				$("#bioBlack2").css("display","block");
				color10 -= 1;
			}

			if(num ==33){
				$("#chitoBlack1").css("display","none");
				$("#chitoColor1").css("display","block");
				color11 += 1;
			}else if(num ==34){
				$("#chitoColor1").css("display","none");
				$("#chitoBlack1").css("display","block");
				color11 -= 1;
			}else if(num ==35){
				$("#chitoBlack2").css("display","none");
				$("#chitoColor2").css("display","block");
				color11 += 1;
			}else if(num ==36){
				$("#chitoColor2").css("display","none");
				$("#chitoBlack2").css("display","block");
				color11 -= 1;
			}

			if(num ==37){
				$("#boneBlack1").css("display","none");
				$("#boneColor1").css("display","block");
				color12 += 1;
			}else if(num ==38){
				$("#boneColor1").css("display","none");
				$("#boneBlack1").css("display","block");
				color12 -= 1;
			}else if(num ==39){
				$("#boneBlack2").css("display","none");
				$("#boneColor2").css("display","block");
				color12 += 1;
			}else if(num ==40){
				$("#boneColor2").css("display","none");
				$("#boneBlack2").css("display","block");
				color12 -= 1;
			}else if(num ==41){
				$("#boneBlack3").css("display","none");
				$("#boneColor3").css("display","block");
				color12 += 1;
			}else if(num ==42){
				$("#boneColor3").css("display","none");
				$("#boneBlack3").css("display","block");
				color12 -= 1;
			}

			if(num == 43){
				$("#jointBlack1").css("display","none");
				$("#jointColor1").css("display","block");
				color13 += 1;
			}else if(num ==44){
				$("#jointColor1").css("display","none");
				$("#jointBlack1").css("display","block");
				color13 -= 1;
			}

			if(num == 45){
				$("#immunizenBlack1").css("display","none");
				$("#immunizenColor1").css("display","block");
				color14 += 1;
			}else if(num ==46){
				$("#immunizenColor1").css("display","none");
				$("#immunizenBlack1").css("display","block");
				color14 -= 1;
			}	

			if(num == 47){
				$("#leanBlack1").css("display","none");
				$("#leanColor1").css("display","block");
				color15 += 1;
			}else if(num == 48){
				$("#leanColor1").css("display","none");
				$("#leanBlack1").css("display","block");
				color15 -= 1;		
			}

			if(num == 49){
				$("#soyBlack1").css("display","none");
				$("#soyColor1").css("display","block");
				color16 += 1;
			}else if(num == 50){
				$("#soyColor1").css("display","none");
				$("#soyBlack1").css("display","block");
				color16 -= 1;		
			}else if(num == 51){
				$("#soyBlack2").css("display","none");
				$("#soyColor2").css("display","block");
				color16 += 1;		
			}else if(num == 52){
				$("#soyColor2").css("display","none");
				$("#soyBlack2").css("display","block");
				color16 -= 1;		
			}

			if(num == 53){
				$("#enjuveBlack1").css("display","none");
				$("#enjuveColor1").css("display","block");
				color17 += 1;
			}else if(num == 54){
				$("#enjuveColor1").css("display","none");
				$("#enjuveBlack1").css("display","block");
				color17 -= 1;		
			}else if(num == 55){
				$("#enjuveBlack2").css("display","none");
				$("#enjuveColor2").css("display","block");
				color17 += 1;		
			}else if(num == 56){
				$("#enjuveColor2").css("display","none");
				$("#enjuveBlack2").css("display","block");
				color17 -= 1;		
			}

			if(num == 57){
				$("#unimateBlack1").css("display","none");
				$("#unimateColor1").css("display","block");
				color18 += 1;
			}else if(num == 58){
				$("#unimateColor1").css("display","none");
				$("#unimateBlack1").css("display","block");
				color18 -= 1;		
			}else if(num == 59){
				$("#unimateBlack2").css("display","none");
				$("#unimateColor2").css("display","block");
				color18 += 1;		
			}else if(num == 60){
				$("#unimateColor2").css("display","none");
				$("#unimateBlack2").css("display","block");
				color18 -= 1;		
			}

			if(num == 61){
				$("#bioscplusBlack1").css("display","none");
				$("#bioscplusColor1").css("display","block");
				color19 += 1;
			}else if(num == 62){
				$("#bioscplusColor1").css("display","none");
				$("#bioscplusBlack1").css("display","block");
				color19 -= 1;		
			}else if(num == 63){
				$("#bioscplusBlack2").css("display","none");
				$("#bioscplusColor2").css("display","block");
				color19 += 1;		
			}else if(num == 64){
				$("#bioscplusColor2").css("display","none");
				$("#bioscplusBlack2").css("display","block");
				color19 -= 1;		
			}										

			if(num == 65){
				$("#biosBlack1").css("display","none");
				$("#biosColor1").css("display","block");
				color20 += 1;
			}else if(num == 66){
				$("#biosColor1").css("display","none");
				$("#biosBlack1").css("display","block");
				color20 -= 1;		
			}else if(num == 67){
				$("#biosBlack2").css("display","none");
				$("#biosColor2").css("display","block");
				color20 += 1;		
			}else if(num == 68){
				$("#biosColor2").css("display","none");
				$("#biosBlack2").css("display","block");
				color20 -= 1;		
			}	


			if(num == 69){
				$("#biossplusBlack1").css("display","none");
				$("#biossplusColor1").css("display","block");
				color21 += 1;
			}else if(num == 70){
				$("#biossplusColor1").css("display","none");
				$("#biossplusBlack1").css("display","block");
				color21 -= 1;		
			}else if(num == 71){
				$("#biossplusBlack2").css("display","none");
				$("#biossplusColor2").css("display","block");
				color21 += 1;		
			}else if(num == 72){
				$("#biossplusColor2").css("display","none");
				$("#biossplusBlack2").css("display","block");
				color21 -= 1;		
			}

			if(num == 73){
				$("#eBlack1").css("display","none");
				$("#eColor1").css("display","block");
				color22 += 1;
			}else if(num == 74){
				$("#eColor1").css("display","none");
				$("#eBlack1").css("display","block");
				color22 -= 1;		
			}

			if(num == 75){
				$("#bios7Black1").css("display","none");
				$("#bios7Color1").css("display","block");
				color23 += 1;
			}else if(num == 76){
				$("#bios7Color1").css("display","none");
				$("#bios7Black1").css("display","block");
				color23 -= 1;		
			}else if(num == 77){
				$("#bios7Black2").css("display","none");
				$("#bios7Color2").css("display","block");
				color23 += 1;		
			}else if(num == 78){
				$("#bios7Color2").css("display","none");
				$("#bios7Black2").css("display","block");
				color23 -= 1;		
			}

			if(num == 79){
				$("#lifiberBlock1").css("display","none");
				$("#lifiberColor1").css("display","block");
				color24 += 1;
			}else if(num == 80){
				$("#lifiberColor1").css("display","none");
				$("#lifiberBlock1").css("display","block");
				color24 -= 1;		
			}else if(num == 81){
				$("#lifiberBlock2").css("display","none");
				$("#lifiberColor2").css("display","block");
				color24 += 1;		
			}else if(num == 82){
				$("#lifiberColor2").css("display","none");
				$("#lifiberBlock2").css("display","block");
				color24 -= 1;		
			}

			if(num == 83){
				$("#aloeBlock1").css("display","none");
				$("#aloeColor1").css("display","block");
				color25 += 1;
			}else if(num == 84){
				$("#aloeColor1").css("display","none");
				$("#aloeBlock1").css("display","block");
				color25 -= 1;		
			}else if(num == 85){
				$("#aloeBlock2").css("display","none");
				$("#aloeColor2").css("display","block");
				color25 += 1;		
			}else if(num == 86){
				$("#aloeColor2").css("display","none");
				$("#aloeBlock2").css("display","block");
				color25 -= 1;		
			}

			if(num == 87){
				$("#parawayPlusBlack1").css("display","none");
				$("#parawayPlusColor1").css("display","block");
				color26 += 1;
			}else if(num == 88){
				$("#parawayPlusColor1").css("display","none");
				$("#parawayPlusBlack1").css("display","block");
				color26 -= 1;		
			}else if(num == 89){
				$("#parawayPlusBlack2").css("display","none");
				$("#parawayPlusColor2").css("display","block");
				color26 += 1;		
			}else if(num == 90){
				$("#parawayPlusColor2").css("display","none");
				$("#parawayPlusBlack2").css("display","block");
				color26 -= 1;		
			}
								
		}	


		
		function clicksubmit(){
			frm = document.step5ApplyForm;	
			var step5ProductVal  = []; 
			var productKey =Array();
			var productVal =Array();
			var proCnt = 0;
			var step5ProductVal  = {
						'28974': color1,//프로스테이트 티엘씨
					    '28463': color2,//리버에센셜
					    '28584': color3,//하와이안노니
					    '25141': color4,//오메가3
					    '25155': color5,//비전
					    '18739': color6,//클리어소트
					    '28830': color7,//셀룰라베이직
					    '24724': color8,//코엔자임 큐텐
					    '17284': color9,//피토파스
					    '28824': color10,//바이오씨
					    '19281': color11,//키토리치
					    '30904': color12,//본메이트 칼슐
					    '30823': color13,//조인트 모빌리티
					    '27267': color14,//이뮤니젠
					    '26189': color15,//린컴플리트
					    '23818': color16,//소이프로틴
					    '22370': color17,//엔쥬비네이트
					    '29843': color18,//유니마테
					    '28586': color19,//바이오스라이프 C 플러스
					    '24991': color20,//바이오스라이프 S
					    '28911': color21,//바이오스라이프 S 플러스
					    '24927': color22,//바이오스라이프 이 에너지
					    '31215': color23, //바이오스 라이프7
					    '26022': color24, //라이화이버
					    '24723': color25, //알로에 아보센스
					    '15744': color26  // 패러웨이플러스
					    				}		

			for(var key in step5ProductVal){
				  productKey[proCnt] = key;
				  productVal[proCnt] = step5ProductVal[key];
				  proCnt++			   
				}
			if(confirm("등록하시겠습니까?")){   			
				frm.crdateDate.value = getTimeStamp();
				frm.productKey.value = productKey;
				frm.productVal.value = productVal;
				frm.action = "step4Action.php";
				frm.submit();
			}
		} 
			
  	</script>
    <div class="StepImg">
		<img alt="" src="../images/s5button.png" style="width: 95%;">  
	</div>
	<form name="step5ApplyForm" method="post">
		<input type="hidden" name="crdateDate" value="">
		<input type="hidden" name="step" value="5">
		<input type="hidden" name="productKey" value="">
		<input type="hidden" name="productVal" value="">
		<input type="hidden" name="programID" value="<?php echo $programID?>">
		<div class="step5base" align="center">
<!-- 프로스테이트 티엘씨 -->
			<div id="28974" style="display: none ;">
				<div style="margin-right: 150px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px; vertical-align: middle; margin-bottom: 5px;"><font color="red" size="4px;" style="margin-bottom: middle;">&nbsp;<b>프로스테이트 티엘씨</b></font>
				</div>
				<table class="tableStyle">
					<tr >
						<td style="font-size:14px; padding-left:35px;vertical-align: top;">
							<b>쏘팔메토 열매 추출물 :</b>	
						</td>
						<td rowspan="2">
							<img id="prosBlack1" name="prosBlack1" alt="" src="../images/prosBlack.png" onclick="changeColor(1);" align="left" style="margin-left: 30px;">
							<img id="prosColor1" name="prosColor1" alt="" src="../images/prosColor.png" onclick="changeColor(2);" style="display: none;margin-left: 30px;">
							<img id="searchProsColor1" name="searchProsColor1" alt="" src="../images/prosColor.png" style="display: none;margin-left: 30px;">
						</td> 
						<td rowspan="2">
							<img id="prosBlack2" name="prosBlack2" alt="" src="../images/prosBlack.png"  onclick="changeColor(3);" style="margin-left: 5px;">
							<img id="prosColor2" name="prosColor2" alt="" src="../images/prosColor.png"  onclick="changeColor(4)" style="display: none;margin-left: 5px;">
							<img id="searchProsColor2" name="searchProsColor2" alt="" src="../images/prosColor.png" style="display: none;margin-left: 5px;">
						</td>
					</tr>
					<tr>
						<td style="font-size:14px; padding-left:35px;vertical-align: top;">
							전립선 건강의 유지에<br/>도움을 줄 수 있음
						</td>
					</tr>			
				</table>
				<div class="font">1일 2회 / 1회 2캡슐</div>
			</div>	
<!-- 리버 에센셜 -->
			<div id="28463" style="display: none;">	
				<div style="margin-right: 230px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red " size="4px;" style="margin-bottom: middle;">&nbsp;<b>리버 에센셜</b></font>
				</div>
				
				<table class="tableStyle">
					<tr>
						<td style="padding-left: 30px; font-size: 14px;">
							<b>밀크씨슬추출물:</b>	
						</td>
					</tr>
					<tr>
						<td style="padding-left: 30px; font-size: 14px;">
							간 건강에 도움을<br/>
							줄 수 있음		
						</td>
						<td>
							<img id="liverBlack1" name="liverBlack1" alt="" src="../images/LiverBlack.png" onclick="changeColor(5);" style="margin-left: 80px; margin-bottom: 5px;">
							<img id="liverColor1" name="liverColor1" alt="" src="../images/LiverColor.png" onclick="changeColor(6);" style="display: none;margin-left: 80px;margin-bottom: 5px;">
							<img id="searchLiverColor1" name="searchLiverColor1" alt="" src="../images/LiverColor.png" style="display: none;margin-left:80px;margin-bottom: 5px;">
						</td> 
					</tr>
					<tr>
						<td style="padding-left: 30px; font-size: 14px;">
							<b>홍경천추출몰:</b>	
						</td>
						<td colspan="2" style="padding-left: 50px; font-size: 14px;">
							1일 1회 / 1회 3캡슐
						</td>
					</tr>
					<tr>
						<td colspan="3" style="padding-left: 30px; font-size: 14px;">
							스트레스로 인한 피로개선에<br/>
							도움을 줄 수 있음
						</td>
					</tr>		
				</table>
				
				<div style="height: 10px;"></div>
			</div>	
<!-- 하와이안 노니 -->
			<div id="28584" style="display: none;">		
				<div style="margin-right: 215px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red " size="4px;" style="margin-bottom: middle;">&nbsp;<b>하와이안 노니</b></font>
				</div>
				<table class="tableStyle">
					<tr>
						<td style="font-size:14px; vertical-align: top;">
							<b>일반식품(음료베이스)</b>	
						</td>
						<td>
							<img id="noniBlack1" name="noniBlack1" alt="" src="../images/noniBlack.png" onclick="changeColor(9);" style="margin-left: 80px;">
							<img id="noniColor1" name="noniColor1" alt="" src="../images/noniColor.png" onclick="changeColor(10);"style="display: none;margin-left:80px;">
							<img id="searchNoniColor1" name="searchNoniColor1" alt="" src="../images/noniColor.png" style="display: none;margin-left: 80px;">
						</td>
					</tr>
				</table>
				<div class="font2" >1일 1회 / 1회 1포</div>
			</div>	
<!-- 오메가라이프-3 -->	
			<div id="25141" style="display: none;">		
				<div style="margin-right: 200px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red " size="4px;" style="margin-bottom: middle;">&nbsp;<b>오메가라이프-3</b></font>
				</div>
				<table class="tableStyle">
					<tr>
						<td style="padding-left: 20px; font-size: 14px;">
							<b>EPA 및 DHA 함유 유지:</b>
						</td>
					</tr>
					<tr>
						<td style="padding-left: 20px; font-size: 14px;">
							혈중 중성지질 개선,<br/>혈행개선, 기억력 개선에<br/>도움을 줄 수 있음	
						</td>
						<td rowspan="2">
							<img id="omegaBlack1" name="omegaBlack1" alt="" src="../images/omegaBlack.png" onclick="changeColor(11);" align="right" style="margin-left: 25px;">
							<img id="omegaColor1" name="omegaColor1" alt="" src="../images/omegaColor.png" onclick="changeColor(12);" align="right" style="display: none;margin-left: 25px;">
							<img id="searchOmegaColor1" name="searchOmegaColor1" alt="" src="../images/omegaColor.png" style="display: none;margin-left:25px;">
						</td> 
						<td rowspan="2">
							<img id="omegaBlack2" name="omegaBlack2" alt="" src="../images/omegaBlack.png"  onclick="changeColor(13);" style="margin-left: 5px;">
							<img id="omegaColor2" name="omegaColor2" alt="" src="../images/omegaColor.png"  onclick="changeColor(14)" style="display: none;margin-left: 5px;">
							<img id="searchOmegaColor2" name="searchOmegaColor2" alt="" src="../images/omegaColor.png" style="display: none;margin-left: 5px;">
						</td>
					</tr>
				</table>
				<div class="font" style="margin-top: 0px;">1일 2회 / 1회 2캡슐</div>
			</div>	
<!-- 비전 에센셜 -->
			<div id="25155" style="display: none;">				
				<div style="margin-right: 215px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red " size="4px;"style="margin-bottom: middle;">&nbsp;<b>비전 에센셜</b></font>
				</div>
				<table class="tableStyle">
					<tr>
						<td style="padding-left: 25px; font-size: 14px;">
							<b>베타카로틴:</b>
						</td>
					</tr>
					<tr>
						<td style="padding-left: 25px; font-size: 14px;">
							어두운 곳에서 <br/>시각 적응을 <br/>위해 필요		
						</td>
						<td>
							<img id="visionBlack1" name="visionBlack1" alt="" src="../images/visionBlack.png" onclick="changeColor(15);" style="margin-left: 80px;">
							<img id="visionColor1" name="visionColor1" alt="" src="../images/visionColor.png" onclick="changeColor(16);"style="display: none;margin-left:80px;">
							<img id="searchVisionColor1" name="searchVisionColor1" alt="" src="../images/visionColor.png" style="display: none;margin-left: 80px;">
						</td>
					</tr>
					<tr>
						<td style="padding-left: 25px; font-size: 14px;">
							피부와 점막을 <br/>형성하고 기능을 
							<br/>유지 하는데 필요		
						</td>
						<td colspan="2" style="padding-left: 30px; font-size: 14px; vertical-align: top;" >
							1일 1회 / 1회 2캡슐
						</td>
					</tr>
					<tr>
						<td colspan="3" style="padding-left: 25px; font-size: 14px;">
							 상피세포의 성장과 발달에 필요
						</td>
					</tr>
				</table>
				<div style="height: 10px;"></div>
			</div>	
<!-- 클리어소트 -->
			<div id="18739" style="display: none;">
				<div style="margin-right: 225px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red " size="4px;" style="margin-bottom: middle;">&nbsp;<b>클리어 소트</b></font>
				</div>	
				<table class="tableStyle">
					<tr>
						<td style="font-size:14px; padding-left:0px; vertical-align: top;">
							<b>복합 비타민 & <br/>아연 보충 제품</b>
						</td>
						<td>
							<img id="clearBlack1" name="clearBlack1" alt="" src="../images/clearBlack.png" onclick="changeColor(17);" style="margin-left: 105px;">
							<img id="clearColor1" name="clearColor1" alt="" src="../images/clearColor.png" onclick="changeColor(18);"style="display: none;margin-left:105px;">
							<img id="searchClearColor1" name="searchVisionColor1" alt="" src="../images/visionColor.png" style="display: none;margin-left: 105px;">
						</td>
					</tr>
				</table>		
				<div class="font2">1일 1회 / 1회 2캡슐</div>
			</div>	
<!-- 피토파스 -->
			<div id="17284" style="display: none;">
				<div style="margin-right: 250px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red " size="4px;"style="margin-bottom: middle;">&nbsp;<b>피토파스</b></font>
				</div>			
				<table class="tableStyle">
					<tr>
						<td style="font-size:14px; vertical-align: top; padding-left: 17px;">
							<b>비타민C & E 보충 제품</b>
						</td>
						<td>
							<img id="phytoBlack1" name="phytoBlack1" alt="" src="../images/phytoBlack.png" onclick="changeColor(23);" style="margin-left: 50px;">
							<img id="phytoColor1" name="phytoColor1" alt="" src="../images/phytoColor.png" onclick="changeColor(24);"style="display: none;margin-left:50px;">
							<img id="searchPhytoColor1" name="searchPhytoColor1" alt="" src="../images/phytoColor.png" style="display: none;margin-left: 50px;">
						</td>
						<td>
							<img id="phytoBlack2" name="phytoBlack2" alt="" src="../images/phytoBlack.png" onclick="changeColor(25);" style="margin-left: 5px;">
							<img id="phytoColor2" name="phytoColor2" alt="" src="../images/phytoColor.png" onclick="changeColor(26);"style="display: none;margin-left:5px;">
							<img id="searchPhytoColor2" name="searchPhytoColor2" alt="" src="../images/phytoColor.png" style="display: none;margin-left: 5px;">
						</td>
						<td>
							<img id="phytoBlack3" name="phytoBlack3" alt="" src="../images/phytoBlack.png" onclick="changeColor(27);" style="margin-left: 5px;">
							<img id="phytoColor3" name="phytoColor3" alt="" src="../images/phytoColor.png" onclick="changeColor(28);"style="display: none;margin-left:5px;">
							<img id="searchPhytoColor3" name="searchPhytoColor3" alt="" src="../images/phytoColor.png" style="display: none;margin-left: 5px;">
						</td>
					</tr>
				</table>
				<div class="font">1일 3회 / 1회 1캡슐</div>
			</div>	
<!-- 바이오씨- -->	
			<div id="28824" style="display: none;">
				<div style="margin-right: 250px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red " size="4px;" style="margin-bottom: middle;">&nbsp;<b>바이오 씨</b></font>
				</div>		
				<table  class="tableStyle">
					<tr>
						<td style="font-size:14px;padding-left: 8px; vertical-align: top;">
							<b>비타민C 보충 제품</b>
						</td>
						<td rowspan="2">
							<img id="bioBlack1" name="bioBlack1" alt="" src="../images/bioBlack.png" onclick="changeColor(29);"  style="margin-left: 71px;width: 32px;">
							<img id="bioColor1" name="bioColor1" alt="" src="../images/bioColor.png" onclick="changeColor(30);"style="display: none;margin-left:71px;width: 32px;">
							<img id="searchBioColor1" name="searchBioColor1" alt="" src="../images/bioColor.png" style="display: none;margin-left: 71px;width: 32px;">
						</td>
						<td rowspan="2">
							<img id="bioBlack2" name="bioBlack2" alt="" src="../images/bioBlack.png" onclick="changeColor(31);"style="margin-left: 5px;width: 32px;">
							<img id="bioColor2" name="bioColor2" alt="" src="../images/bioColor.png" onclick="changeColor(32);"style="display: none;margin-left:5px;width: 32px;">
							<img id="searchBioColor2" name="searchBioColor2" alt="" src="../images/bioColor.png" style="display: none;margin-left: 5px;width: 32px;">
						</td>
					</tr>
				</table>
				<div class="font2">1일 2회 / 1회 1정</div>
			</div>	
<!-- 셀룰라베이직 -->
			<div id="28830" style="display: none;">
				<div style="margin-right: 215px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red " size="4px;" style="margin-bottom: middle;">&nbsp;<b>셀룰라 베이직</b></font>
				</div>
				<table  class="tableStyle">
					<tr>
						<td style="font-size:14px;padding-left: 0px;vertical-align: top;">
							<b>비타민 B군 보충 제품</b>
						</td>
						<td>
							<img id="cellularBlack1" name="cellularBlack1" alt="" src="../images/cellularBlack.png" onclick="changeColor(19);" style="margin-left: 70px;">
							<img id="cellularColor1" name="cellularColor1" alt="" src="../images/cellularColor.png" onclick="changeColor(20);"style="display: none;margin-left:70px;">
							<img id="searchCellularColor1" name="searchCellularColor1" alt="" src="../images/visionColor.png" style="display: none;margin-left: 70px;">
						</td>
					</tr>
				</table>	
				<div class="font2">1일 1회 / 1회 2정</div>
			</div>	
<!-- 코엔자임 큐텐 -->
			<div id="24724" style="display: none;">	
				<div style="margin-right: 215px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px; vertical-align: middle; margin-bottom: 5px;"><font color="red " size="4px;" style="margin-bottom: middle;">&nbsp;<b>코엔자임 큐텐</b></font>
				</div>
				<table class="tableStyle">
					<tr>
						<td style="font-size:14px;padding-left: 5px;">
							<b>코엔자임Q10:</b>
						</td>
					</tr>
					<tr>
						<td style="font-size:14px;padding-left: 5px;">
							항산화ㆍ높은 혈압 감소에<br/>도움을 줄 수 있음	
						</td>
						<td rowspan="2" style="vertical-align: top; padding-top: 0px;margin-top: 0px;">
							<img id="coenzymeBlack1" name="coenzymeBlack1" alt="" src="../images/coenzymeBlack.png" onclick="changeColor(21);"style="margin-left:55px;">
							<img id="coenzymeColor1" name="coenzymeColor1" alt="" src="../images/coenzymeColor.png" onclick="changeColor(22);"style="display: none;margin-left:55px;">
							<img id="searchCoenzymeColor1" name="searchCoenzymeColor1" alt="" src="../images/coenzymeColor.png" style="display: none;margin-left: 55px;">
						</td>
					</tr>
				</table>		
				<div class="font" style="margin-top: 0px;">1일 1회 / 1회 2캡슐</div>
			</div>			
<!-- 키토리치-->
			<div id="19281" style="display: none;">			
				<div style="margin-right: 250px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red " size="4px;" style="margin-bottom: middle;">&nbsp;<b>키토리치</b></font>
				</div>
				<table  class="tableStyle">
					<tr>
						<td style="font-size:14px;padding-left: 28px;">
							<b>키토산:</b>
						</td>
						<td rowspan="3">
							<img id="chitoBlack1" name="chitoBlack1" alt="" src="../images/chitorichBlack.png" onclick="changeColor(33);" align="right" style="margin-left: 15px;">
							<img id="chitoColor1" name="chitoColor1" alt="" src="../images/chitorichColor.png" onclick="changeColor(34);"style="display: none;margin-left:15px;">
							<img id="searchChitoColor1" name="searchChitoColor1" alt="" src="../images/chitorichColor.png" style="display: none;margin-left: 15px;">
						</td>
						<td rowspan="3">
							<img id="chitoBlack2" name="chitoBlack2" alt="" src="../images/chitorichBlack.png" onclick="changeColor(35);"style="margin-left: 5px;">
							<img id="chitoColor2" name="chitoColor2" alt="" src="../images/chitorichColor.png" onclick="changeColor(36);"style="display: none;margin-left:5px;">
							<img id="searchChitoColor2" name="searchChitoColor2" alt="" src="../images/chitorichColor.png" style="display: none;margin-left: 5px;">
						</td>
					</tr>
					<tr>
						<td style="font-size:14px;padding-left: 28px;">
							혈중 콜레스테롤 개선에<br/>도움을 줄 수 있음	
						</td>
						
					</tr>
				</table>
				<div class="font" style="margin-top: 0px;">1일 2회 / 1회 3캡슐</div>
			</div>	
<!-- 본메이드 칼슘 -->
			<div id="30904" style="display: none;">
				<div style="margin-right: 215px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red " size="4px;" style="margin-bottom: middle;">&nbsp;<b>본메이트 칼슘</b></font>
				</div>			
				<table  class="tableStyle">
					<tr>
						<td style="font-size:14px;padding-left: 20px;">
							<b>칼슘 & <br/>비타민D 보충 제품</b>	
						</td>
						<td rowspan="3">
							<img id="boneBlack1" name="boneBlack1" alt="" src="../images/boneBlack.png" onclick="changeColor(37);"  style="margin-left: 70px;">
							<img id="boneColor1" name="boneColor1" alt="" src="../images/boneColor.png" onclick="changeColor(38);"style="display: none;margin-left:70px;">
							<img id="searchBoneColor1" name="searchChitoColor1" alt="" src="../images/boneColor.png" style="display: none;margin-left: 70px;">
						</td>
						<td rowspan="3">
							<img id="boneBlack2" name="boneBlack2" alt="" src="../images/boneBlack.png" onclick="changeColor(39);"style="margin-left: 5px;">
							<img id="boneColor2" name="boneColor2" alt="" src="../images/boneColor.png" onclick="changeColor(40);"style="display: none;margin-left:5px;">
							<img id="searchBoneColor2" name="searchBioColor2" alt="" src="../images/boneColor.png" style="display: none;margin-left: 5px;">
						</td>
						<td rowspan="3">
							<img id="boneBlack3" name="boneBlack3" alt="" src="../images/boneBlack.png" onclick="changeColor(41);"style="margin-left: 5px;">
							<img id="boneColor3" name="boneColor3" alt="" src="../images/boneColor.png" onclick="changeColor(42);"style="display: none;margin-left:5px;">
							<img id="searchBoneColor3" name="searchBoneColor3" alt="" src="../images/boneColor.png" style="display: none;margin-left: 5px;">
						</td>
					</tr>
				</table>
				<div class="font2">1일 3회 / 1회 1정</div>
			</div>	
<!-- 조인트 모빌리티 -->
			<div id="30823" style="display: none;">
				<div style="margin-right: 195px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red " size="4px;" style="margin-bottom: middle;">&nbsp;<b>조인트 모빌리티</b></font>
				</div>	
				<table  class="tableStyle">
					<tr>
						<td style="font-size:14px;padding-left: 0px;">
							<b>엠에스엠(MSM):</b>
						</td>
						<td rowspan="2">
							<img id="jointBlack1" name="jointBlack1" alt="" src="../images/jointBlack.png" onclick="changeColor(43);" style="margin-left: 75px;">
							<img id="jointColor1" name="jointColor1" alt="" src="../images/jointColor.png" onclick="changeColor(44);"style="display: none;margin-left:75px;">
							<img id="searchJointColor1" name="searchJointColor1" alt="" src="../images/jointColor.png" style="display: none;margin-left: 75px;">
						</td>
					</tr>
					<tr>
						<td style="font-size:14px;padding-left: 0px;">
							관절 및 연골건강에<br/>도움을 줄 수 있음
						</td>
						
					</tr>
				</table>		
				<div class="font">1일 1회 / 1회 3캡슐</div>
			</div>	
<!-- 이뮤니젠 -->
			<div id="27267" style="display: none;">
				<div style="margin-right: 255px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red " size="4px;" style="margin-bottom: middle;">&nbsp;<b>이뮤니젠</b></font>
				</div>
				<table  class="tableStyle">
					<tr>
						<td style="font-size:14px;padding-left: 0px; vertical-align: top;">
							<b>아연 함유 제품</b>
						</td>
						<td>
							<img id="immunizenBlack1" name="immunizenBlack1" alt="" src="../images/immunizenBlack.png" onclick="changeColor(45);" style="margin-left: 105px;">
							<img id="immunizenColor1" name="immunizenColor1" alt="" src="../images/immunizenColor.png" onclick="changeColor(46);"style="display: none;margin-left:105px;">
							<img id="searchImmunizenColor1" name="searchImmunizenColor1" alt="" src="../images/immunizenColor.png" style="display: none;margin-left: 105px;">
						</td>
					</tr>
				</table>	
				<div class="font2">1일 1회 / 1회 3캡슐</div>
			</div>		
<!-- 린 컴플리트 -->
			<div id="26189" style="display: none;">
				<div style="margin-right: 230px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red " size="4px;"style="margin-bottom: middle;">&nbsp;<b>린 컴플리트</b></font>
				</div>
				<table  class="tableStyle">
					<tr>
						<td  style="font-size:14px;padding-left: 15px; vertical-align: top;">
							<b>체중조절용 조제식품</b>
						</td>
						<td>
							<img id="leanBlack1" name="leanBlack1" alt="" src="../images/leanBlack.png" onclick="changeColor(47);" style="margin-left: 68px;">
							<img id="leanColor1" name="leanColor1" alt="" src="../images/leanColor.png" onclick="changeColor(48);"style="display: none;margin-left:68px;">
							<img id="searchLeanColor1" name="searchLeanColor1" alt="" src="../images/leanColor.png" style="display: none;margin-left: 68px;">
						</td>
					</tr>
				</table>
				<div class="font2">1일 1회 / 1회 1포</div>
			</div>		
<!-- 소이프로틴 -->
			<div id="23818" style="display: none;">
				<div style="margin-right: 235px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red " size="4px;" style="margin-bottom: middle;">&nbsp;<b>소이프로틴</b></font>
				</div>		
				<table  class="tableStyle">
					<tr>
						<td  style="font-size:14px;padding-left: 30px;vertical-align: top;">
							<b>단백질 <br/>보충 제품</b>
						</td>
						<td>
							<img id="soyBlack1" name="soyBlack1" alt="" src="../images/soyBlack.png" onclick="changeColor(49);" style="margin-left: 110px; width: 45px;">
							<img id="soyColor1" name="soyColor1" alt="" src="../images/soyColor.png" onclick="changeColor(50);"style="display: none;margin-left:110px;width: 45px;">
							<img id="searchSoyColor1" name="searchSoyColor1" alt="" src="../images/soyColor.png" style="display: none;margin-left: 110px;width: 45px;">
						</td>
						<td>
							<img id="soyBlack2" name="soyBlack2" alt="" src="../images/soyBlack.png" onclick="changeColor(51);" style="margin-left: 5px;width: 45px;">
							<img id="soyColor2" name="soyColor2" alt="" src="../images/soyColor.png" onclick="changeColor(52);"style="display: none;margin-left:5px;width: 45px;">
							<img id="searchSoyColor2" name="searchSoyColor2" alt="" src="../images/soyColor.png" style="display: none;margin-left: 5px;width: 45px;">
						</td>
					</tr>
				</table>
				<div class="font">1일 2회 / 1회 1포</div>
			</div>		
<!-- 엔쥬비네이트 -->
			<div id="22370" style="display: none;">
				<div style="margin-right: 220px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red " size="4px;"style="margin-bottom: middle;">&nbsp;<b>엔쥬비네이트</b></font>
				</div>	
				<table class="tableStyle">
					<tr>
						<td class="tdFont" style="vertical-align: top;">
							<b>단백질 보충 제품</b>
						</td>
						<td>
							<img id="enjuveBlack1" name="enjuveBlack1" alt="" src="../images/enjuveBlack.png" onclick="changeColor(53);" style="margin-left: 75px;">
							<img id="enjuveColor1" name="enjuveColor1" alt="" src="../images/enjuveColor.png" onclick="changeColor(54);"style="display: none;margin-left:75px;">
							<img id="searchEnjuveColor1" name="searchEnjuveColor1" alt="" src="../images/enjuveColor.png" style="display: none;margin-left: 75px;">
						</td>
						<td>
							<img id="enjuveBlack2" name="enjuveBlack2" alt="" src="../images/enjuveBlack.png" onclick="changeColor(55);" style="margin-left: 5px;">
							<img id="enjuveColor2" name="enjuveColor2" alt="" src="../images/enjuveColor.png" onclick="changeColor(56);"style="display: none;margin-left:5px;">
							<img id="searchEnjuveColor2" name="searchEnjuveColor2" alt="" src="../images/enjuveColor.png" style="display: none;margin-left: 5px;">
						</td>
					</tr>
				</table>	
				<div class="font" style="margin-top: 0px;padding-top: 0px;">2스푼(취침 1시간 전)</div>
			</div>	
<!-- 유니마테 -->
			<div id="29843" style="display: none;">
				<div style="margin-right: 250px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red " size="4px;" style="margin-bottom: middle;">&nbsp;<b>유니마테</b></font>
				</div>
				<table class="tableStyle">
					<tr>
						<td style="font-size:14px;padding-left: 9px;vertical-align: top;">
							<b>일반식품(고형차)</b>
						</td>
						<td>
							<img id="unimateBlack1" name="unimateBlack1" alt="" src="../images/unimateBlack.png" onclick="changeColor(57);" style="margin-left: 95px;">
							<img id="unimateColor1" name="unimateColor1" alt="" src="../images/unimateColor.png" onclick="changeColor(58);"style="display: none;margin-left:95px;">
							<img id="searchUnimateColor1" name="searchUnimateColor1" alt="" src="../images/unimateColor.png" style="display: none;margin-left: 95px;">
						</td>
						<!--  
						<td>
							<img id="unimateBlack2" name="unimateBlack2" alt="" src="../images/unimateBlack.png" onclick="changeColor(59);" style="margin-left: 5px;">
							<img id="unimateColor2" name="unimateColor2" alt="" src="../images/unimateColor.png" onclick="changeColor(60);"style="display: none;margin-left:5px;">
							<img id="searchUnimateColor2" name="searchUnimateColor2" alt="" src="../images/unimateColor.png" style="display: none;margin-left: 5px;">
						</td>
						-->
					</tr>
				</table>
				<div class="font2">1일 1회 / 1회 1포</div>
			</div>	
<!-- 바이오스 라이프 C플러스 -->
			<div id="28586" style="display: none;">
				<div style="margin-right: 120px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red " size="4px;"style="margin-bottom: middle;">&nbsp;<b>바이오스 라이프 C 플러스</b></font>
				</div>
				<table class="tableStyle">
					<tr>
						<td style="font-size:14px;padding-left: 27px;vertical-align: top;">
							<b>귀리식이섬유:</b>
						</td>
						<td rowspan="2" style="vertical-align: top;">
							<img id="bioscplusBlack1" name="bioscplusBlack1" alt="" src="../images/bioscplusBlack.png" onclick="changeColor(61);" style="margin-left: 35px;">
							<img id="bioscplusColor1" name="bioscplusColor1" alt="" src="../images/bioscplusColor.png" onclick="changeColor(62);"style="display: none;margin-left:35px;">
							<img id="searchBioscplusColor1" name="searchBioscplusColor1" alt="" src="../images/bioscplusColor.png" style="display: none;margin-left: 35px;">
						</td>
						<td rowspan="2" style="vertical-align: top;">
							<img id="bioscplusBlack2" name="bioscplusBlack2" alt="" src="../images/bioscplusBlack.png" onclick="changeColor(63);" style="margin-left: 5px;">
							<img id="bioscplusColor2" name="bioscplusColor2" alt="" src="../images/bioscplusColor.png" onclick="changeColor(64);"style="display: none;margin-left:5px;">
							<img id="searchBioscplusColor2" name="searchBioscplusColor2" alt="" src="../images/bioscplusColor.png" style="display: none;margin-left: 5px;">
						</td>
					</tr>				
					<tr>
						<td style="font-size:14px;padding-left: 27px;vertical-align: top;">
							혈중 콜레스테롤 개선,<br/>식후 혈당상승 억제에<br/>도움을 줄 수 있음
						</td>	
					</tr>
				</table>
				<div class="font" style="margin-top: 0px;padding-top: 0px;">1일 2회 / 1회 1포</div>
			</div>		
<!-- 바이오스 라이프S -->
			<div id="24991" style="display: none;">			
				<div style="margin-right: 180px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red " size="4px;"style="margin-bottom: middle;">&nbsp;<b>바이오스 라이프 S</b></font>
				</div>
				<table class="tableStyle">
					<tr>
						<td style="font-size:14px;padding-left: 24px;vertical-align: top;">
							<b>식이섬유:</b><br/>식이섬유 보충
						</td>
						<td rowspan="2">
							<img id="biosBlack1" name="biosBlack1" alt="" src="../images/biosBlack.png" onclick="changeColor(65);" style="margin-left: 80px;">
							<img id="biosColor1" name="biosColor1" alt="" src="../images/biosColor.png" onclick="changeColor(66);"style="display: none;margin-left:80px;">
							<img id="searchBiosColor1" name="searchBiosColor1" alt="" src="../images/biosColor.png" style="display: none;margin-left: 80px;">
						</td>
						<td rowspan="2">
							<img id="biosBlack2" name="biosBlack2" alt="" src="../images/biosBlack.png" onclick="changeColor(67);" style="margin-left: 5px;">
							<img id="biosColor2" name="biosColor2" alt="" src="../images/biosColor.png" onclick="changeColor(68);"style="display: none;margin-left:5px;">
							<img id="searchBiosColor2" name="searchBiosColor2" alt="" src="../images/biosColor.png" style="display: none;margin-left: 5px;">
						</td>
					</tr>
				</table>			
				<div class="font2">1일 2회 / 1회 1포</div>
			</div>		
<!-- 바이오스 라이프 S 플러스 -->
			<div id="28911" style="display: none;">
				<div style="margin-right: 120px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red " size="4px;" style="margin-bottom: middle;">&nbsp;<b>바이오스 라이프 S 플러스</b></font>
				</div>
				<table class="tableStyle">
					<tr>
						<td style="font-size:14px;padding-left: 30px;vertical-align: top;">
							<b>귀리식이섬유:</b>
						</td>
						<td rowspan="2">
							<img id="biossplusBlack1" name="biossplusBlack1" alt="" src="../images/biossplusBlack.png" onclick="changeColor(69);" style="margin-left: 35px;width: 45px;">
							<img id="biossplusColor1" name="biossplusColor1" alt="" src="../images/biossplusColor.png" onclick="changeColor(70);"style="display: none;margin-left:35px;width: 45px;">
							<img id="searchBiossplusColor1" name="searchBiossplusColor1" alt="" src="../images/biossplusColor.png" style="display: none;margin-left: 35px;width: 45px;">
						</td>
						<td rowspan="2">
							<img id="biossplusBlack2" name="biossplusBlack2" alt="" src="../images/biossplusBlack.png" onclick="changeColor(71);" style="margin-left: 5px;width: 45px;">
							<img id="biossplusColor2" name="biossplusColor2" alt="" src="../images/biossplusColor.png" onclick="changeColor(72);"style="display: none;margin-left:5px;width: 45px;">
							<img id="searchBiossplusColor2" name="searchBiossplusColor2" alt="" src="../images/biossplusColor.png" style="display: none;margin-left: 5px;width: 45px;">
						</td>
					</tr>
					<tr>	
						<td style="font-size:14px;padding-left: 30px;vertical-align: top;">
							식후 혈당상승 억제에<br/>도움을 줄 수 있음
						</td>
						
					</tr>
				</table>
				<div class="font">1일 2회 / 1회 1포</div>
			</div>		
<!-- 바이오스 라이프 E -->
			<div id="24927" style="display: none;">
				<div  style="margin-right: 115px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red " size="4px;" style="margin-bottom: middle;">&nbsp;<b>바이오스 라이프 이 에너지</b></font>
				</div>
				<table class="tableStyle">
					<tr>
						<td style="font-size:14px;padding-left: 9px;vertical-align: top;">
							<b>멀티비타민</b>
						</td>
						<td rowspan="2">
							<img id="eBlack1" name="eBlack1" alt="" src="../images/eBlack.png" onclick="changeColor(73);" style="margin-left: 125px;">
							<img id="eColor1" name="eColor1" alt="" src="../images/eColor.png" onclick="changeColor(74);"style="display: none;margin-left:125px;">
							<img id="searchBiosEColor1" name="searchBiosEColor1" alt="" src="../images/eColor.png" style="display: none;margin-left: 125px;">
						</td>
					</tr>
				</table>			
				<div class="font">1일 1회 / 1회 1포</div>
			</div>		
<!-- 바이오스라이프 7 -->
			<div id="31215" style="display: none;">
				<div style="margin-right: 180px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red " size="4px;"style="margin-bottom: middle;">&nbsp;<b>바이오스 라이프 7</b></font>
				</div>
				<table class="tableStyle">
					<tr>
						<td style="font-size:14px;padding-left: 23px;vertical-align: top;">
							<b>프락토올리고당:</b>
					</tr>
					<tr>
						<td style="font-size:14px;padding-left: 23px;">
							유익균 증식 및 <br/>유해균 억제ㆍ<br/>칼슘흡수ㆍ배변활동 <br/>원할에 도움을<br/>줄 수 있음
						</td>	
						<td>
							<img id="bios7Black1" name="bios7Black1" alt="" src="../images/bios7Black.png" onclick="changeColor(75);" style="margin-left: 37px;">
							<img id="bios7Color1" name="bios7Color1" alt="" src="../images/bios7Color.png" onclick="changeColor(76);"style="display: none;margin-left:37px;">
							<img id="searchBios7Color1" name="searchBios7Color1" alt="" src="../images/bios7Color.png" style="display: none;margin-left: 37px;">
						</td>
						<td>
							<img id="bios7Black2" name="bios7Black2" alt="" src="../images/bios7Black.png" onclick="changeColor(77);" style="margin-left: 5px;">
							<img id="bios7Color2" name="bios7Color2" alt="" src="../images/bios7Color.png" onclick="changeColor(78);"style="display: none;margin-left:5px;">
							<img id="searchBios7Color2" name="searchBios7Color2" alt="" src="../images/bios7Color.png" style="display: none;margin-left: 5px;">
						</td>
					</tr>
					<tr>
						<td style="font-size:14px;padding-left: 23px;vertical-align: top;">
							<b>식이섬유:</b>
						</td>
						<td colspan="2" style="padding-left: 30px; font-size: 14px; vertical-align: top;">
							1일 2회 / 1회 1포
						</td>
					</tr>
					<tr>
						<td style="font-size:14px;padding-left: 23px;vertical-align: top;">
							식이섬유 보충
						</td>
					</tr>
				</table>												
				<div style="height: 20px;"></div>
			</div>
<!-- 라이화이버 -->			
			<div id="26022" style="display: none;">
				<div style="margin-right: 230px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red" size="4px;"style="margin-bottom: middle;">&nbsp;<b>라이화이버</b></font>
				</div>
				<table class="tableStyle">
					<tr>
						<td style="font-size:14px;padding-left: 5px;vertical-align: top;">
							<b>차전자피 식이섬유:</b>
						</td>
						<td rowspan="2">
							<img id="lifiberBlock1" name="lifiberBlock1" alt="" src="../images/lifiberBlock.png" onclick="changeColor(79);" style="margin-left:50px;">
							<img id="lifiberColor1" name="lifiberColor1" alt="" src="../images/lifiberColor.png" onclick="changeColor(80);" style="display: none;margin-left: 50px;">
							<img id="lifibersearchColor1" name="lifibersearchColor1" alt="" src="../images/lifiberColor.png" style="display: none;margin-left: 50px;">
						</td>
						<td rowspan="2">
							<img id="lifiberBlock2" name="lifiberBlock2" alt="" src="../images/lifiberBlock.png"  onclick="changeColor(81);" style="margin-left: 5px;">
							<img id="lifiberColor2" name="lifiberColor2" alt="" src="../images/lifiberColor.png"  onclick="changeColor(82);" style="display: none;margin-left: 5px;">
							<img id="lifibersearchColor2" name="lifibersearchColor2" alt="" src="../images/lifiberColor.png" style="display: none;margin-left: 5px;">	
						</td>
					</tr>
					<tr>
						<td style="font-size:14px;padding-left: 5px;vertical-align: top;"> 
							혈중 콜레스테롤 개선ㆍ<br/>배변활동
							원활에<br/>도움을 줄 수 있음
						</td>
					</tr>
				</table>
				<div class="font">1일 2회 / 1회 1포</div>
			</div>	
<!-- 알로에 아보레센스 -->			
			<div id="24723" style="display: none;">
				<div style="margin-right: 180px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red" size="4px;"style="margin-bottom: middle;">&nbsp;<b>알로에 아보레센스</b></font>
				</div>
				<table style="margin: 0px;padding: 0px;">
					<tr>
						<td style="font-size:14px;padding-left: 0px;vertical-align: top;">
							<b>알로에 전잎:</b>
						</td>
						<td rowspan="2">
							<img id="aloeBlock1" name="aloeBlock1" alt="" src="../images/aloeBlack.png" onclick="changeColor(83);" style="margin-left:60px;">
							<img id="aloeColor1" name="aloeColor1" alt="" src="../images/aloeColor.png" onclick="changeColor(84);" style="display: none;margin-left: 60px;">
							<img id="aloesearchColor1" name="aloesearchColor1" alt="" src="../images/aloeColor.png" style="display: none;margin-left: 60px;">		
						</td>
						<td rowspan="2">
							<img id="aloeBlock2" name="aloeBlock2" alt="" src="../images/aloeBlack.png"  onclick="changeColor(85);" style="margin-left: 5px;">
							<img id="aloeColor2" name="aloeColor2" alt="" src="../images/aloeColor.png"  onclick="changeColor(86)" style="display: none;margin-left: 5px;">
							<img id="aloesearchColor2" name="aloesearchColor2" alt="" src="../images/aloeColor.png" style="display: none;margin-left: 5px;">	
						</td>	
					</tr>
					<tr>
						<td style="font-size:14px;padding-left: 0px;vertical-align: top;">
							배변활동 원활에<br/>
							도움을 줄 수 있음
						</td>	
					</tr>
				</table>
				<div style="margin-top: 0;padding-top: 0px; padding-left:175px; font-size: 14px;">1일 2회 / 1회 3캡슐</div>	
			</div>
<!-- 패러웨이 플러스 -->			
			<div id="15744" style="display: none;">
				<div style="margin-right: 195px;">
					<img alt="" src="../images/s4icon.png" style="width: 25px;vertical-align: middle; margin-bottom: 5px;"><font color="red" size="4px;"style="margin-bottom: middle;">&nbsp;<b>패러웨이 플러스</b></font>
				</div>
				<table class="tableStyle">
					<tr>
						<td class="tdFont" style="vertical-align: top;">
							<b>비타민B₁:</b>
						</td>
						<td rowspan="2">
							<img id="parawayPlusBlack1" name="parawayPlusBlack1" alt="" src="../images/parawayPlusBlack.png" onclick="changeColor(87);" style="margin-left: 68px;" >
							<img id="parawayPlusColor1" name="parawayPlusColor1" alt="" src="../images/parawayPlusColor.png" onclick="changeColor(88);" style="display: none;margin-left: 68px;">
							<img id="parawaySearchPlusColor1" name="parawaySearchPlusColor1" alt="" src="../images/parawayPlusColor.png" style="display: none;margin-left: 68px;">
						</td>
						<td rowspan="2">
							<img id="parawayPlusBlack2" name="parawayPlusBlack2" alt="" src="../images/parawayPlusBlack.png"  onclick="changeColor(89);" style="margin-left: 5px;">
							<img id="parawayPlusColor2" name="parawayPlusColor2" alt="" src="../images/parawayPlusColor.png"  onclick="changeColor(90)" style="display: none;margin-left: 5px;">
							<img id="parawaySearchPlusColor2" name="parawaySearchPlusColor2" alt="" src="../images/parawayPlusColor.png" style="display: none;margin-left: 5px;">
						</td>
					</tr>
					<tr>
						<td class="tdFont" style="vertical-align: top;">
							탄수화물과 에너지<br/>대사에 필요
						</td>	
					</tr>
				</table>
				<div style="margin-top: 0;padding-top: 0px; padding-left:175px; font-size: 14px;">1일 2회 / 1회 2캡슐</div>
			</div>
			<div align="center">
				<img alt="" src="../images/submitButton5.png" onclick="clicksubmit()"> 
			</div>	
		</div>		
	</form>

