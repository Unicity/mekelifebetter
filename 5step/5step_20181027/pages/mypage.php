<?php session_start();?>
<?php
  header('Content-Type: text/html; charset=utf-8');
?>
<? include "./header.inc"; ?>
<?php  include_once("../inc/function.php");?>
<?php 
	include "../inc/dbconn.php";
	checkSessionValue();
	$userNo=$_SESSION["username"];
	$name = $_SESSION["realname"];
	$Prodcut4Name = array();
	$Prodcut5Name = array();
	
	$history4Data = "select group_concat(B.ProductName) as ProductName, startDate, endDate,Step,name,step3,commentTxt,programType
	 			  from ProgramDetail A,
		   			   Product B,
		   			   ProgramMaster C
				 where A.productID = B.productID
	  			   and A.programID = C.programID
	 		 	   and C.UserID = '".$userNo."'
	  			   and C.delFlag = 'Y'
	  			   and B.Step = 4
       			 group by C.startDate
				 order by C.startDate desc 
       			 limit 3";
	
	$history4_result = mysql_query($history4Data);
	while($row = mysql_fetch_row($history4_result)) {
		$history4Product[] = $row[0];
		$histroy4StartDate[] = $row[1];
		$histroy4EndDate[] = $row[2];
		$histroy4Step[] = $row[3];
		$histroy4Name[] = $row[4];
		$histroy4Step3[] = $row[5];
		$histroy4commnetTxt[] = $row[6];
		$histroy4programType[] = $row[7];
		
	}
	
	$history5Data = "select group_concat(B.ProductName) as ProductName, startDate, endDate,Step
	 			  from ProgramDetail A,
		   			   Product B,
		   			   ProgramMaster C
				 where A.productID = B.productID
	  			   and A.programID = C.programID
	 		 	   and C.UserID = '".$userNo."'
	  			   and C.delFlag = 'Y'
	  			   and B.Step = 5
       			 group by C.startDate
				 order by C.startDate desc
       			 limit 3";
	
	$history5_result = mysql_query($history5Data);
	while($row = mysql_fetch_row($history5_result)) {
		$history5Product[] = $row[0];
	}
	$loopCnt = count($history4Product);
	
	$product4Select = "select B.ProductName
                        from ProgramDetail A,
	                         Product B,
                             ProgramMaster C
					   where A.productID = B.productID
  						 and A.programID = C.programID
  						 and C.UserID = '".$userNo."'
					   	 and C.delFlag = 'N'
 	 					 and B.Step = '4'";
	$product_result = mysql_query($product4Select);
	//$product_row = mysql_fetch_assoc($product_result);
	while($row = mysql_fetch_row($product_result)) {
		$Prodcut4Name[]= $row[0];		 
	}
	
	$product5Select = "select B.ProductName
                        from ProgramDetail A,
	                         Product B,
                             ProgramMaster C
					   where A.productID = B.productID
  						 and A.programID = C.programID
  						 and C.UserID = '".$userNo."'
					   	 and C.delFlag = 'N'
 	 					 and B.Step = '5'";
	$product5_result = mysql_query($product5Select);
	while($row = mysql_fetch_row($product5_result)) {
		$Prodcut5Name[]= $row[0];
	
	}
	

	$query = "select * 
			    from ProgramMaster 
			   where userID = '".$userNo."'
				 and delFlag = 'N'";	

	$query_result = mysql_query($query);
	$query_row = mysql_fetch_array($query_result);
	
	$gender= $query_row["gender"];
	if($gender == 'M'){
		$gender ='남자';
	}else{
		$gender ='여자';
	}
	$programType= $query_row["programType"];

	$age= $query_row["age"];
	$height= $query_row["height"];
	$weight= $query_row["weight"];
	$startDate= $query_row["startDate"];
	$duration= $query_row["duration"];
	$programName= $query_row["programName"];
	$step3= $query_row["step3"];
	$comment= $query_row["commentTxt"];

	$strDate = strtotime($startDate);
	$strDate= date('Y-m-d',$strDate);
	
    $endDate = strtotime($startDate.'+'.$duration. 'days');
    $endDate= date('Y-m-d',$endDate);    
    
?>
<meta charset="utf-8" /> 
	<script src="https://d3js.org/d3.v4.min.js"></script>
    <link href="../css/billboard.css" rel="stylesheet">
	<!--  <script type="text/javascript" src="../js/jquery.canvasjs.min.js"></script>-->
	<script type="text/javascript" src="../js/billboard.js"></script>
	<style>
		.myPage .myStyle .pinfo .tdHstyle{
			font-weight: bold;
			vertical-align: middle;
		}   
		.myPage .myStyle .pinfo .tdstyle{
			color: #939393;
		} 
		
		.myPage .myStyle .pinfo .dypTdH{
			width: 40%;
			height: 5%;
			margin: 0px;
			padding: 0px;
			font-weight: bold;
		} 
		
		.myPage .myStyle .pinfo .dypTd{
			color: #939393;
			width: 60%;
			height: 5%;
			margin: 0px;
			padding: 0px;
		} 
		
		.myPage .myStyle .pinfo .fatCheck{
			color: #ffffff;
			height: 5%;
			margin: 0px;
			padding: 0px;
			border: 0px;
		}
		
		.myPage .myStyle .pinfo .fatCheck1{
			height: 5%;
			margin: 0px;
			padding: 0px;
			border: 0px;
		}
		
		.myPage .myStyle .pinfo .fatCheck2{
			height: 5%;
			margin: 0px;
			padding: 0px;
			border: 0px;
			background-color: #FFFFFF;
		}
		
		.myPage .myStyle .pinfo .fatCheck3{
			height: 5%;
			margin: 1px;
			padding: 1px;
			border: 0px;
			background-color: #000000;
		}
		
	
		
		.myPage .myStyle .fat{
			color: #939393;
			margin-left: 20px;
		}
		
		.myPage .myStyle .gradient{
			margin: 0px; 
    		padding: 0px;
    		width: 100%;
    		margin-left: 40px;
		} 
		
		.myPage .myStyle .gradient .cholesterol1{
			background: linear-gradient(to left,
                  #d1ff00,#aaff00,#92ff00, #5aff00,#00ff00);
        	width: 20%;
        	height: 10%;
        	margin: 0px;
        	padding: 0px;  
        	float: left;
        	text-align: center;   
        	border-top-left-radius: 20px;
        	border-bottom-left-radius: 20px;
		}
		.myPage .myStyle .gradient .cholesterol2{
			 background: linear-gradient(to left,
	                #ffde00,#ffe800,#ffff00, #e6ff00,#e4ff00);
	        width: 20%;
	        height: 10%;
	        margin: 0px;
	        padding: 0px;  
	        float: left;
	        text-align: center;
		}
		
		.myPage .myStyle .gradient .cholesterol3{
			background: linear-gradient(to left,
	              #ff9600,#ffa300,#ffbb00,#ffd100,#ffdb00);
	
	        width: 20%;
	        margin: 0px;
	        padding: 0px;  
	        float: left;
	        text-align: center;        
		}
		.myPage .myStyle .gradient .cholesterol4{
			background: linear-gradient(to left,
                #ff1900,#ff3700,#ff5100,#ff5e00,#ff9000);
	        width: 20%;
	        height: 10%;
	        margin: 0px;
	        padding: 0px;  
	        float: left;
	        text-align: center;     
	        border-top-right-radius: 20px;
	        border-bottom-right-radius: 20px;
		}
		
		.myPage .myStyle .gradient .box{
			width: 40px; 
  			height: 20px; 
  			margin: 0px; 
  			position: relative;
		}
	</style> 
  	<script type="text/javascript">
	var gender = '<?php echo $BloodSugar?>';
	var programType = '<?php echo $programType?>';
	
	$(document).ready(function(){
		if(programType == 1){
			$("#dyp").show();
			$("#5step").hide();
			$("#dypBlack").css("display","none");
			$("#dypColor").css("display","block");
			$("#historyBlack").css("display","block");
			$("#historyColor").css("display","none");
			$("#historyGroup").css("display","none");
			$("#dypGroup").css("display","block");
			var totC = 50;
			$("#totalC1").animate({left: +totC+'%'},'slow');
		}else if(programType == 0){
			$("#dyp").hide();
			$("#5step").show();
			$("#5stepGroup").css("display","block");
	  		$("#historyGroup").css("display","none");
	  		$("#5stepColor").css("display","block");
	  		$("#historyBlack").css("display","block");
	  		$("#5stepBlack").css("display","none");
	  		$("#historyColor").css("display","none");
	  		}		
	<?php if($comment == null){?>
		$("#saveComment").css("display","block");
		$("#commentText").css("display","block");
	<?php }else{?>
 		$("#commentView").css("display","block");
 		$("#updateComment").css("display","block");
	<?php }?>		

	});

  	function stepClick(){	
  	  	$("#5stepGroup").css("display","block");
  		$("#historyGroup").css("display","none");
  		$("#5stepColor").css("display","block");
  		$("#historyBlack").css("display","block");
  		$("#5stepBlack").css("display","none");
  		$("#historyColor").css("display","none");

	}

	function dypClick(){
		$("#dypBlack").css("display","none");
		$("#dypColor").css("display","block");
		$("#historyBlack").css("display","block");
		$("#historyColor").css("display","none");

		$("#historyGroup").css("display","none");
		$("#dypGroup").css("display","block");
		var totC = 50;
		$("#totalC1").animate({left: +totC+'%'},'slow');
		 
	}	
				
	function historyClick(){
		$("#historyGroup").css("display","block");
		$("#5stepGroup").css("display","none");
		$("#dypGroup").css("display","none");	
		$("#historyBlack").css("display","none");	
		$("#historyColor").css("display","block");
		$("#5stepBlack").css("display","block");
		$("#5stepColor").css("display","none");
		$("#dypBlack").css("display","block");
		$("#dypColor").css("display","none");
		     
	}

	function maxLengthCheck(object){
		if(object.value.length>object.maxLength){
			object.value = object.value.slice(0, object.maxLength);
		}	
	}	

	
	function commentUpdate(){
		$("#commentText").css("display","block");
		$("#saveComment").css("display","block");
		$("#updateComment").css("display","none");
	}	

	function commentSave() {
		var frm = document.mypageComment;
		frm.action = "myPageAction.php";
		frm.submit();
	}
	
  	</script>
	<div class="myPage">  	

	  	<table style="padding: 0px;margin: 0px;width: 100%">
			<tr>
				<td width="50%" align="center" id="5step" style="display: none;">
					<img id="5stepColor" alt="" src="../images/5step2.png" onclick="stepClick()" width="100%"> 
					<img id="5stepBlack" alt="" src="../images/5step0.png" onclick="stepClick()" style="display: none;" width="100%">
				</td>
				<td width="50%" id="dyp" style="display: none;">
					<img id="dypBlack" alt="" src="../images/DYP0.png" onclick="dypClick()"  width="100%">
					<img id="dypColor" alt="" src="../images/DYP2.png" onclick="dypClick()" style="display: none;" width="100%">
				</td>
				<td width="50%" id="history">
					<img id="historyBlack" alt="" src="../images/history0.png" onclick="historyClick()"  width="100%">
					<img id="historyColor" alt="" src="../images/history2.png" style="display: none;" onclick="historyClick()"  width="100%">
				</td>
			</tr>
		</table>
		
		<div id="dypGroup" class="myStyle" style="display: none;">
			<div style="height: 5px;"></div>
			<div style="margin-left: 20px;">
				안녕하세요 <b><?php echo $name?> 님</b>
			</div>
			<div style="height: 15px;"></div>
			<div style="margin-left: 20px; font-weight: bold">				
				DYP 캠페인  : <?php echo $programName?>
			</div>
			<div style="margin-left: 20px;">
				■ 기간 : <?php echo $strDate?> ~ <?php echo $endDate?>	
			</div>
			<table  class="pinfo" style="margin:0 1%; text-align: center; background-color: #FFFFFF;width: 90%;margin-left: 20px;">
				<tr>
					<td class="dypTdH">
						 성별
					</td>
					<td class="dypTd">
						<?php echo $gender?>
					</td>
				</tr>
				<tr>
					<td class="dypTdH">
						 연령
					</td>
					<td class="dypTd">
						<?php echo $age?>
					</td>
				</tr>
				<tr>
					<td class="dypTdH">
						 키
					</td>
					<td class="dypTd">
						<?php echo $height?> cm
					</td>
				</tr>
				<tr>
					<td class="dypTdH">
						 몸무게
					</td>
					<td class="dypTd">
						<?php echo $weight?> kg
					</td>
				</tr>
			</table>
			<!--  
			<div style="height:10px;"></div>
			<div style="margin-left: 20px;font-weight: bold;">
			 	체지방률(%) 검사결과: 
			</div>
			<div class="gradient">
				<div style="height: 5px;"></div>
				<div class="cholesterol1"><b>저체중</b></div>
				<div class="cholesterol2"><b>정상</b></div>                                                                                                                                                                                    
				<div class="cholesterol3"><b>비만</b></div>
				<div class="cholesterol4"><b>고도비만</b></div><br/>
				<div class="box" id="totalC1"><img id="" alt="" src="../images/locationPoint.png" style="margin-top: 0px;"></div>
			</div>
					-->
			<div style="height: 15px;"></div>
			<div class="fat">
				※ 체지방률에 따른 비만도 평가(%)
			</div>
	
			<div style="height: 5px;"></div>
			<table class="pinfo" style="text-align: center; width: 90%; margin-left: 20px;">
				<tr style="background-color: #000000;">
					<td class="fatCheck">
						분류
					</td>
					<td class="fatCheck">
						남성
					</td>
					<td class="fatCheck">
						여성
					</td>
				</tr>
				<tr>
					<td class="fatCheck1">
						정상
					</td>
					<td class="fatCheck1">
						18% 이하
					</td>
					<td class="fatCheck1">
						24% 이하
					</td>
				</tr>
				<tr>
					<td class="fatCheck2">
						경계
					</td>
					<td class="fatCheck2">
						18.1 ~ 24.9%
					</td>
					<td class="fatCheck2">
						24.1 ~ 29.9%	
					</td>
				</tr>
				<tr>
					<td class="fatCheck1">
						비만
					</td>
					<td class="fatCheck1">
						25% 이상 	
					</td>
					<td class="fatCheck1">
						30% 이상	
					</td>
				</tr>
				<tr><td class="fatCheck3" colspan="3"></td></tr>
			</table>
		</div>
	
		
		<div id="historyGroup" class="myStyle" style="display: none;"> 
			<div style="height: 5px;"></div>
			<div style="margin-left: 20px;">
				안녕하세요 <b><?php echo $name?> 님</b>
			</div>
			<div style="height: 15px;"></div>
			<?php for($i=0; $i<$loopCnt;$i++){

				$histroy4Start = date("Y-m-d", strtotime($histroy4StartDate[$i]));
				$histroy4End= date("Y-m-d", strtotime($histroy4EndDate[$i]));
				$history4replace = str_replace(",", "<br>", $history4Product[$i]);
				$history5replace = str_replace(",", "<br>", $history5Product[$i]);
				
			?>
			
			<div style="margin-left: 20px; font-weight: bold">				
				<?php if($histroy4programType[$i] == 0){?>
				나의 프로그램 : 
				<?php }else{?>
				DYP 프로그램 :
				<?php }?>
				<?php echo $histroy4Name[$i]?>
			</div>
			<div style="margin-left: 20px;">				
					■ 각오 한마디 <br/>-&nbsp;&nbsp;&nbsp;<?php echo $histroy4commnetTxt[$i]?>
				</div>
			<div style="margin-left: 20px;">
				■ 기간 : <?php echo $histroy4Start?> ~ <?php echo $histroy4End?>
			</div>
			
			<div style="margin-left: 20px;">
				■ 섭취제품
			</div>
			<div align="center">
				<table id="historyHtml" class="pinfo" style="margin:0 1%; text-align: center; background-color: #FFFFFF; width: 93%;">
					<?php if($histroy4Step3[$i] == 0){?>
					<tr>
						<td class="tdHstyle" width="40%">
							STEP 3<br/>클린즈
						</td>
						
						<td class="tdstyle" width="60%">
							라이화이버<br/>
							알로에 아보레센스<br/>
							패러웨이 플러스
						</td>
					</tr>
					<?php }?>
					<tr>
						<td class="tdHstyle" width="40%">
							STEP 4<br/>기초영양
						</td>
						<td class="tdstyle" width="60%">
							<?php echo $history4replace?>
						</td>
					</tr>
					<tr>
						<td class="tdHstyle" width="40%">
							STEP 5<br/>타겟영양
						</td>
						<td class="tdstyle" width="60%">
							<?php echo $history5replace?>
						</td>
					</tr>
				</table>
				
			</div><br/>
		<?php }?>
		</div>
		
		<div id="5stepGroup" class="myStyle" style="display: none;">
			<div style="height: 5px;"></div>
			<div style="margin-left: 20px;">
				안녕하세요 <b><?php echo $name?> 님</b>
			</div>
			<div style="height: 15px;"></div>
			<div style="margin-left: 20px; font-weight: bold">				
				나의 프로그램 : <?php echo $programName?>
			</div>
			<div style="margin-left: 20px;">
				■ 기간 : <?php echo $strDate?> ~ <?php echo $endDate?>		
			</div>
			<div style="margin-left: 20px;">
				■ 섭취제품
			</div>
			<div class="5stepC" align="center">
				<table class="pinfo" style="margin:0 1%; text-align: center; background-color: #FFFFFF; width: 90%;">
					<?php if($step3 == 0){?>
					<tr>
						<td class="tdHstyle" width="40%">
							STEP 3<br/>클린즈
						</td>
						<td class="tdstyle" width="60%">
							라이화이버<br/>
							알로에 아보레센스<br/>
							패러웨이 플러스
						</td>
					</tr>
					<?php }?>
					<tr>
						<td class="tdHstyle" width="40%">
							STEP 4<br/>기초영양
						</td>
						<td class="tdstyle" width="60%">
							<?php for ($i=0; $i<count($Prodcut4Name); $i++){
								$product4 = $Prodcut4Name[$i]."<div style = 'height:5px;'></div>";
								echo $product4;
							}?>
						</td>
					</tr>
					<tr>
						<td class="tdHstyle" width="40%">
							STEP 5<br/>타겟영양
						</td>
						<td class="tdstyle" width="60%">
							<?php for ($i=0; $i<count($Prodcut5Name); $i++){
								$product5 = $Prodcut5Name[$i]."<div style = 'height:5px;'></div>";
								echo $product5;
							}?>
						</td>
					</tr>
				</table>
				<div style="height: 10px;"></div>
				<form name="mypageComment" method="post">
					<table class="pinfo" style="margin:0 1%; text-align: center; background-color: #FFFFFF; width: 90%;">
						<tr>
							<td style="height: 10px;"><b>MY PROGRAM 각오 한마디</b></td>
						</tr>
					
						<tr>
							<td id="commentView" style="display: none;height: 50px;"><?php echo $comment?></td>
						</tr>
					
						<tr>
							<td id="commentText" style="display: none;height: 55px;">
								<input type="text" id="commentID" name="commentID" maxlength="50" oninput="maxLengthCheck(this)" style="width: 80%;height:40px;text-align: left;"/>
							</td>
						</tr>
				
						<tr>							
							<td align="center">
								<input type="button" id="updateComment" name="updateComment" value="수정" onclick="commentUpdate()" style="display:none;" >
								<input type="button" id="saveComment" name="saveComment" value="저장" onclick="commentSave()" style="display:none;" >
							
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
