<?php
	$distID = $_POST['distID'];
	$mName = $_POST['mName'];
	$sName = $_POST['sName'];
	$mBirth = $_POST['mBirth'];
	$sBirth = $_POST['sBirth'];
	$bankcode=$_POST['bankcode'];
	$accountNum=$_POST['accountNum'];

	$fName = $_POST["fName"]; // 공동등록 이름
	$fPhone = $_POST["fPhone"]; //공동등록 전화번호
	$relationShip = $_POST["relationShip"]; //공동등록 관계
	$fBirthDay = $_POST["fBirthDay"]; // 공동등록 생년월일
	$myfile = $_FILES['myfile'];
	
	echo $fName."<br/>";
	echo $fPhone."<br/>";
	echo $relationShip."<br/>";
	echo $fBirthDay."<br/>";
	echo $myfile."<br/>";
	
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>주ㆍ부 사업자 변경 동의서</title>
		<meta name="description" content="" />
		<meta http-equiv="Content-Script-Type" content="text/javascript">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
		<link rel="stylesheet" type="text/css" href="../css/joo.css" />
		<script type="text/javascript">
			var secondValue = "";
			function agreementButton(){
				secondValue = document.agreementForm;
				var agreement = document.getElementById("chk1").checked;
				var notAgreement = document.getElementById("chk2").checked;
				if(agreement == false || notAgreement == true){
					alert("동의 하셔야 합니다.");
					return false;
				}
				console.log(">>>>>>>"+JSON.stringify(secondValue.myfile.value));
				console.log(secondValue.myfile.value);
				//secondValue.action = "infoSave.php";
				//secondValue.submit()	
			}	 
		</script>
	</head>
	<body>
		<form name="agreementForm" method="post" enctype="multipart/form-data">
			<input type="hidden" name="bankcode" value="<?php echo $bankcode?>">
			<input type="hidden" name="accountNum" value="<?php echo $accountNum?>">
			<input type="hidden" name="mName" value="<?php echo $mName?>">
			<input type="hidden" name="mBirth" value="<?php echo $mBirth?>">
			<input type="hidden" name="sName" value="<?php echo $sName?>">
			<input type="hidden" name="sBirth" value="<?php echo $sBirth?>">
			<input type="hidden" name="distID" value="<?php echo $distID?>">
			
			<input type="hidden" name="fName" value="<?php echo $fName?>">
			<input type="hidden" name="fPhone" value="<?php echo $fPhone?>">
			<input type="hidden" name="relationShip" value="<?php echo $relationShip?>">
			<input type="hidden" name="fBirthDay" value="<?php echo $fBirthDay?>">
			<input type="text" name="myfile" id="myfile" value="<?php echo $myfile?>" accept="image/*">
			
			<div class="wrapper" >
				<!-- container start {-->
				<div class="main_wrapper">
					<div class="figure">
						<img src="../images/logo_Image.jpg" alt="유니시티 로고" />
					</div>
					<div class="main_inner_box">
						<div class="main_top">
							<h2>
								<span><font color="white">주ㆍ부 사업자 변경 동의서</font></span>
							</h2>
						</div>
						<div style="height: 20px;"></div>
						<div class="main_text" style="margin-left: 10%;">
							<b>변경 전 주사업자</b>
						</div>
						<div class="main_text" style="margin-left: 10%;">
							성&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;명 : <?php echo $mName ?>
						</div>
						<div class="main_text" style="margin-left: 10%;">
							생년월일 : <?php echo $mBirth?>
						</div>
						<div style="height: 20px;"></div>
						<div class="main_text" style="margin-left: 10%;">
							<b>변경 후 주사업자</b>
						</div>
						<div class="main_text" style="margin-left: 10%;">
							성&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;명 : <?php echo $sName ?>
						</div>
						<div class="main_text" style="margin-left: 10%;">
							생년월일 : <?php echo $sBirth?>
						</div>
						<div class="main_text" style="margin-left: 10%;">
							<b>첨부 서류 업로드</b>
						</div>
						<div class="main_text" style="margin-left: 10%;">
							<input type="file" name="myfile1" id="myfile1" size="60" multiple="multiple" accept="image/*">
						</div>
						<div style="height: 20px;"></div>
						<div style="margin-left: 10%;margin-right: 10%">
							<P><font color="white">회원번호 <b><?php echo $distID ?></b>의 주사업자인 본인 <b><?php echo $mName ?></b> 은(는) 공동등록자 <b><?php echo $sName ?></b> 에게로 주사업자의 지위와 권한을 변경하는 것에 동의하며,
							이를 신청 합니다. 아울러, 변경 후 6개월 이내에는 주ㆍ부사업자의 재변경이 불가함을 인지 하였고, 확인 후 이에 동의합니다. </font></P>
						</div>
						<div style="height: 5px;"></div>
						<div class="main_text" style="font-size: 10px;text-align: center">
							<input type="radio" id="chk1" name="checkYN1" value="Yes" />&nbsp;예 &nbsp;
							<input type="radio" id="chk2" name="checkYN1" value="No"/>&nbsp;아니오 
						</div>
						<div style="height: 10px;"></div>
						<div align="center" style="background-color: #B2CCFF;width: 100px;margin-left: 48%; border-radius : 5px; text-shadow: 0px -1px 1px rgba(0,0,0,.3); border: 1px solid #4081AF;box-shadow : inset 0 1px 0 rgba(255,255,255,.3), inset 0 0 2px rgba(255,255,255,.3) 0 1px 2px rgba(0,0,0,.29); cursor: pointer; ">
							<a href="javascript:agreementButton()"><b>동의 및 저장</b></a>
						</div>
						
						
					</div>
				</div>
			</div>
		</form>	
	</body>
</html>

	