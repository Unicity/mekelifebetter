<?php
	header("Content-Type: text/html; charset=UTF-8");
	//include_once("../includes/function.php");
	include "../../dbconn_utf8.inc";

	$distID = $_POST["distID"];
	$distName = $_POST["distName"];
	$birthDay = $_POST["birthDay"];
	$phone = $_POST["phone"];
	$fName = $_POST["fName"];
	$fPhone = $_POST["fPhone"];
	$relationShip = $_POST["relationShip"];
	$fBirthDay = $_POST["fBirthDay"];
	$addr = $_POST["addr"];
	$sTime = $_POST["sTime"];
	
	echo "distID::".$distID;
	
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="../css/reset.css"/>
		<link rel="stylesheet" type="text/css" href="../css/common.css"/>
		<link rel="stylesheet" type="text/css" href="../css/selectordie.css"/>
		<script type="text/javascript" src="../js/jquery-1.8.0.min.js"></script>
		<script type="text/javascript" src='../js/common.js'></script>
		<script type="text/javascript" src="../js/selectordie.min.js"></script>
		<script type="text/javascript">
			function getCheckBoxValue(boxname){
				var result = '';
				var thecheckbox = document.getElementsByName(boxname);
				for(var i=0; i< thecheckbox.length; i++) {
					if (thecheckbox[i].checked) {
						result = thecheckbox[i].value;
					}
				}
				return result;
	
			}
			function getCheckedBoxCounter(){
				
				var total =  $('input[type="checkbox"]:checked').length;
				 
				if(document.getElementById("allterm").checked == true) {
					total -= 1;
				}
				return total;
			}

			function getRadioCounter(){
				
				var total =  $('input[type="radio"]:checked').length;
				 
				return total;
			}
			
			function agreementButton(){
				var enrollFormVal = document.enrollForm;
				if (getCheckedBoxCounter() <7) // 강제필드 check 여부 확인
				{
					alert("모든 필수 항목에 동의해야 회원가입을 할 수 있습니다.");
					return;
				}

		 
				if (getRadioCounter() <6) // 선택필드 선택 여부 확인
				{
					alert("선택 항목에 동의여부를 선택해야 회원가입을 할 수 있습니다.");
					return;
				}

				document.enrollForm.selchk1.value = getCheckBoxValue("selchk1");
				document.enrollForm.selchk2.value = getCheckBoxValue("selchk2");
				document.enrollForm.selchk3.value = getCheckBoxValue("selchk3");
				document.enrollForm.selchk4.value = getCheckBoxValue("selchk4");
				document.enrollForm.selchk5.value = getCheckBoxValue("selchk5");
				document.enrollForm.selchk6.value = getCheckBoxValue("selchk6");	
				enrollFormVal.action = "../mainSubChg.php";
				enrollFormVal.submit();
			}	

				
			function selectall(checkbox){
				if(checkbox.checked == true){
			       	 updateFlags(true);
			    }else{
			         updateFlags(false);
			  	}

			}
			function updateFlags(flag){
				document.getElementById("term01").checked = flag;
		       	document.getElementById("term02").checked = flag;
		       	document.getElementById("term03").checked = flag;
		       	document.getElementById("term04").checked = flag;
		       	document.getElementById("term05").checked = flag;
		       	document.getElementById("term06").checked = flag;
		       	document.getElementById("term11").checked = flag;

		       	document.getElementById("selchk1_Y").checked = flag;
		       	document.getElementById("selchk1_N").checked = !flag;
		       	document.getElementById("selchk2_Y").checked = flag;
		       	document.getElementById("selchk2_N").checked = !flag;
		       	document.getElementById("selchk3_Y").checked = flag;
		       	document.getElementById("selchk3_N").checked = !flag;
		       	document.getElementById("selchk4_Y").checked = flag;
		       	document.getElementById("selchk4_N").checked = !flag;
		       	document.getElementById("selchk5_Y").checked = flag;
		       	document.getElementById("selchk5_N").checked = !flag;
		       	document.getElementById("selchk6_Y").checked = flag;
		       	document.getElementById("selchk6_N").checked = !flag;
		   } 
		</script>
		<title>이용약관 및 유의사항</title>
		</head>
	<body>
		<form name="enrollForm" method="post">
			<input type="hidden" name="ChkYN" value="Y">
			<input type="text" name="sid" value="<?php echo $distID ?>">
			<input type="text" name="fname" value="<?php echo $distName ?>">
			<input type="text" name="birthDay" value="<?php echo $birthDay ?>">
			<input type="text" name="phone" value="<?php echo $phone ?>">
			<input type="text" name="address" value="<?php echo $addr ?>">
			<input type="text" name="sTime" value="<?php echo $sTime ?>">
			<div class="cont_wrap">
				<dl class="conttit_wrap">
					<dt>약관 및 유의사항</dt>
					<dd>아래의 유의사항을 반드시 숙지하고 이를 준수하여야 합니다.</dd>
				</dl>	
				
				<?php include "../includes/terms/term01.php";?>
				<p class="terms_txt">
					<input type="checkbox" id="term01" name="term01" value="Y"/><label for="term01" style="font-size: 25px;"><span class="fcolor01">[필수]</span> 본인은 회사의 이용약관에 동의합니다.</label>
				</p>
				
				 <?php include "../includes/terms/term02.php";?>
				<p class="terms_txt">
					<input type="checkbox" id="term02" name="term02" value="Y"/><label for="term02" style="font-size: 25px;" ><span class="fcolor01">[필수]</span> 본인은 회사의 방침 및 절차에 동의합니다.</label>
				</p>
				<?php include "../includes/terms/term03.php";?>
				<p class="terms_txt">
					<input type="checkbox" id="term03" name="term03" value="Y"/><label for="term03" style="font-size: 23px;"><span class="fcolor01">[필수]</span> 본인은 회사의 필수 개인정보 수집 및 이용에 관한 설명을 모두 이해하였고, 이에 동의합니다.</label>
				</p>
	
				 <?php include "../includes/terms/term04.php";?>
				 <p class="terms_txt">
					<input type="checkbox" id="term04" name="term04" value="Y"/><label for="term04" style="font-size: 23px;"><span class="fcolor01">[필수]</span> 본인은 회사의 고유식별정보 수집 및 이용에 관한 설명을 모두 이해하였고, 이에 동의합니다.</label>
				</p>
	
				<?php include "../includes/terms/term05.php";?>
				 <p class="terms_txt">
					<input type="checkbox" id="term05" name="term05" value="Y"/><label for="term05" style="font-size: 23px;"><span class="fcolor01">[필수]</span> 본인은 회사의 개인정보 제3자 제공 및 공유에 관한 설명을 모두 이해하였고, 이에 동의합니다.</label>
				</p>
				<?php include "../includes/terms/term06.php";?>
				 <p class="terms_txt">
					<input type="checkbox" id="term06" name="term06" value="Y"/><label for="term06" style="font-size: 23px;"><span class="fcolor01">[필수]</span> 본인은 회사의 개인정보 취급위탁 및 공유에 관한 설명을 모두 이해하였고, 이에 동의합니다.</label>
				</p>
	
				<?php include "../includes/terms/term07.php";?>
				 <p class="terms_txt">
				 	<span style="vertical-align:top;margin:10px;"> 본인은 회사의 고유식별정보 수집 및 이용에 관한 설명을 모두 이해하였고, 이에 동의합니다.</span>
				 	<input type="radio" name="selchk1" id="selchk1_Y" value="Y"/><label for="selchk1_Y" style="font-size: 21px;">동의</label>
					<input type="radio" name="selchk1" id="selchk1_N" value="N"/><label for="selchk1_N" style="font-size: 21px;">동의안함</label>
				</p>
	
				<?php include "../includes/terms/term08.php";?>
				 <p class="terms_txt">
				 	<span style="vertical-align:top;margin:10px;"> 본인은 회사의 고유식별정보 수집 및 이용에 관한 설명을 모두 이해하였고, 이에 동의합니다.</span>
				 	<input type="radio" name="selchk2" id="selchk2_Y" value="Y"/><label for="selchk2_Y" style="font-size: 21px;">동의</label>
					<input type="radio" name="selchk2" id="selchk2_N" value="N"/><label for="selchk2_N" style="font-size: 21px;">동의안함</label>
				</p>
	
				<?php include "../includes/terms/term09.php";?>
				 <p class="terms_txt">
				 	<span style="vertical-align:top;margin:10px;"> 본인은 회사의 고유식별정보 수집 및 이용에 관한 설명을 모두 이해하였고, 이에 동의합니다.</span>
				 	<input type="radio" name="selchk3" id="selchk3_Y" value="Y"/><label for="selchk3_Y" style="font-size: 21px;">동의</label>
					<input type="radio" name="selchk3" id="selchk3_N" value="N"/><label for="selchk3_N" style="font-size: 21px;">동의안함</label>
				</p>
	
				<?php include "../includes/terms/term10.php";?>
				 <p class="terms_txt">
				 	<span style="vertical-align:top;margin:10px;"> 본인은 회사의 고유식별정보 수집 및 이용에 관한 설명을 모두 이해하였고, 이에 동의합니다.</span>
				 	<input type="radio" name="selchk4" id="selchk4_Y" value="Y"/><label for="selchk4_Y" style="font-size: 21px;">동의</label>
					<input type="radio" name="selchk4" id="selchk4_N" value="N"/><label for="selchk4_N" style="font-size: 21px;">동의안함</label>
				</p>
	
				<?php include "../includes/terms/term11.php";?>
				 <p class="terms_txt">
					<input type="checkbox" id="term11" name="term11" value="Y"/><label for="term11" style="font-size: 23px;"><span class="fcolor01">[필수]</span> 개인정보 국외 이전에 대해 동의합니다.</label>
				</p>
	
				<?php include "../includes/terms/term12.php";?>
				 <p class="terms_txt">
				 	<span style="vertical-align:top;margin:10px;"> 본인은 회사의 본인 외 주문 및 개인정보 제3자 제공과 공유에 관한 설명을 모두 이해하였고, 본인 외의 제3자가 본인의 회원번호를 사용하여 유니시티에서 제품을 주문하는 것에 동의합니다.</span><br>
				 	<input type="radio" name="selchk5" id="selchk5_Y" value="Y"/><label for="selchk5_Y" style="font-size: 21px;">동의</label>
					<input type="radio" name="selchk5" id="selchk5_N" value="N"/><label for="selchk5_N" style="font-size: 21px;">동의안함</label>
				</p>
	
				<?php include "../includes/terms/term13.php";?>
				 <p class="terms_txt">
				 	<span style="vertical-align:top;margin:10px;"> 본인은 회사의 마케팅 목적 개인정보 이용 및 광고성 정보 전송에 관한 설명을 이해하고, 이에 동의합니다.</span>
				 	<input type="radio" name="selchk6" id="selchk6_Y" value="Y"/><label for="selchk6_Y" style="font-size: 21px;">동의</label>
					<input type="radio" name="selchk6" id="selchk6_N" value="N"/><label for="selchk6_N" style="font-size: 21px;">동의안함</label>
				</p>
	 
				<div class="terms_txt" style="text-align:center;">
					<input type="checkbox" id="allterm" onchange="selectall(this);"/><label for="allterm" style="font-size: 23px;"><span class="fcolor01">전체 동의</span></label>
				</div>
				<div style="height: 20px;"></div>
				<div align="center" style="background-color: #B2CCFF;width: 100px;height:50px;font-size:30px; margin-left: 46%; border-radius : 5px; text-shadow: 0px -1px 1px rgba(0,0,0,.3); border: 1px solid #4081AF;box-shadow : inset 0 1px 0 rgba(255,255,255,.3), inset 0 0 2px rgba(255,255,255,.3) 0 1px 2px rgba(0,0,0,.29); cursor: pointer; ">
					<a href="javascript:agreementButton()"><b>저장</b></a>
				</div>
			</div>
		</form>
	</body>

</html>