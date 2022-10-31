<?php
header("Content-Type: text/html; charset=UTF-8");
include_once("../includes/function.php");
include "TranseId.php";
if (!include_once("../includes/dbconn.php")){
	echo "The config file could not be loaded";
}

	function getMaxCode()  {
	
		$iNewid = 0;
		$sqlstr = "SELECT MAX(id) as CNT FROM mainSubChg";
	
		$result = mysql_query($sqlstr);
		$row = mysql_fetch_array($result);
	
		$iNewid = $row["CNT"] + 1;
	
		if (strlen($iNewid) == 1) {
			$iNewid = "0".$iNewid;
		}
	
		return $iNewid;
	
	}
	// 회원
	$distID = $_POST["distID"];      //회원번호
	$distName = $_POST["distName"];  //회원 이름
	$birthDay = $_POST["birthDay"];  //생년월일
	$phone = $_POST["phone"]; // 폰 번호
	$applyDate = $_POST["applyDate"];
	$addr = $_POST["addr"];
	$applyflag = $_POST["flag"];
	$reg_status = $_POST['reg_status'];
	$sTime = $_POST['sTime'];
	

	//공동등록 
	$fName = $_POST["fName"]; // 공동등록 이름
	$fPhone = $_POST["fPhone"]; //공동등록 전화번호
	$relationShip = $_POST["relationShip"]; //공동등록 관계
	$fBirthDay = $_POST["fBirthDay"]; // 공동등록 생년월일
	$gender = $_POST["gender"]; // 공동등록 성별
	
	// 주부사업자 변경 
	$bankcode = $_POST["bankcode"]; // 주부사업자 은행코드
	$accountNum = $_POST["accountNum"]; // 주부사업자 계좌번호
	
	$encAccountNo = encrypt($key, $iv, $accountNum);
	
	$mName = $_POST["mName"]; // 주부사업자 계좌번호
	$mBirth = $_POST["mBirth"]; // 주부사업자 계좌번호
	$sName = $_POST["sName"]; // 주부사업자 계좌번호
	$sBirth = $_POST["sBirth"]; // 주부사업자 계좌번호

	$myfile = $_FILES['myfile'];

	$myfile1 = $_FILES['myfile11'];

	
	if($applyflag != '1'){
		$accountChecker = bankValidation($mBirth, $accountNum, $bankcode) ;
	
		if (substr($accountChecker[msg],51,4)!="0000") {
		
			logger('B', $mName, $mBirth, $bankcode, $encAccountNo,'N');
			DisplayAlert("계좌정보가 일치하지 않습니다.");
			echo "<script>
					var infoValue = document.infoForm;
					var memberId = infoValue.memberId.value 
					
					</script>";
			exit();
		}
	}



	$ext = "";
	$kxt = "";
	$txt = "";

	$max_file_size = 1024*1024*20; //20mb
	$valid_exts = array('jpeg', 'jpg', 'png', 'gif', 'pdf');

	
	if((isset($myfile) && is_uploaded_file($myfile['tmp_name']))&&(isset($myfile1) && is_uploaded_file($myfile1['tmp_name']))) {
		if(($myfile['size'] < $max_file_size)&&($myfile1['size'] < $max_file_size)) {
			$ext = pathinfo($myfile['name'], PATHINFO_EXTENSION);
			$kxt = pathinfo($myfile1['name'], PATHINFO_EXTENSION);
			//$txt = pathinfo($myfile2['name'], PATHINFO_EXTENSION);

			if(!in_array($ext, $valid_exts)){		
				echo "<script>alert('지원되지 않는 파일입니다..');history.back();</script>";
				exit();
			}else if(!in_array($kxt, $valid_exts)){
				echo "<script>alert('지원되지 않는 파일입니다...');history.back();</script>";
				exit();
			}
		} else {
			echo "<script>alert('파일 사이즈가 큽니다.');history.back();</script>";
			exit();
			
		}
	}
	
	if($applyflag=='1'){
		$myfile1 = "";
	}else if($applyflag=='2'){
		$myfile = "";
	}


	
	$alert = '등록이 완료 되었습니다.';
	
	if($myfile1 !=="" && $myfile == ""){	
		$kMax = getMaxCode();
		$kxt ="K".$kMax.".".$kxt;

	}else if($myfile !=="" && $myfile1 =="" ){
		
		$sMax = getMaxCode();
		$ext ="W".$sMax.".".$ext;
	}else{
		$sMax = getMaxCode();
		$ext ="P".$sMax.".".$ext;
		$kxt ="K".$sMax.".".$kxt;
	}


	if($distID != null){
		$querylog = "insert into mainSubChg (FO_ID, FO_NAME,FO_BIRTHDAY,FO_PHONE,TOGETHER_NAME,TOGETHER_BIRTHDAY,TOGETHER_RELATION,TOGETHER_PHONE,TOGETHER_FILE,CHG_MAIN_NAME,CHG_MAIN_BIRTHDAY,CHG_SUB_NAME,CHG_SUB_BIRTHDAY,CHG_BANK,CHG_ACCOUNT,CHG_FILE,applydate,state,applyflag,reg_status,gender,mtime) values ('".$distID."', '".$distName."', '".$birthDay."', '".$phone."', '".$fName."','".$fPhone."','".$relationShip."','".$fBirthDay."','".$ext."','".$mName."','".$mBirth."','".$sName."','".$sBirth."','".$bankcode."','".$accountNum."','".$kxt."','".$applyDate."','".$addr."','".$applyflag."','".$reg_status."','".$gender."','".$sTime."')";
		mysql_query($querylog) or die("Query Error");
	
		$newId = mysql_insert_id();
		$newId = $distID."_".$newId;
		
		if($ext !== "") {
			resizeImage($myfile,$ext);
		}
		
		if($kxt !== "") {
			resizeImage1($myfile1,$kxt);
		}
		
		
		echo "<script>alert('$alert');</script>";
	}
	
	function resizeImage($file,$ext) {

		$size = getimagesize($file['tmp_name']);
	
		$ratio = $size[0]/$size[1]; // width / height
			
		if($ratio>1) {
			$width = 500;
			$height = 500/$ratio;
		} else {
			$width = 500*$ratio;
			$height = 500;
		}
	
		$path = "../uploads/".$ext;
	
		$imgString = file_get_contents($file['tmp_name']);
	
		$image = imagecreatefromstring($imgString);
	
		$image_p = imagecreatetruecolor($width, $height);
	
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
	
		echo "image_p".$image_p;
		switch ($file['type']) {
			case 'image/jpeg':
				imagejpeg($image_p, $path, 100);
				break;
			case 'image/png':
				imagepng($image_p, $path, 9);
				break;
			case 'image/gif':
				imagegif($image_p, $path);
				break;
			default:
				exit();
				break;
		}
		return $path;
	
		
		imagedestroy($image);
		imagedestroy($image_p);
	}
	
	function resizeImage1($file1,$kxt) {
		$size1 = getimagesize($file1['tmp_name']);
	
		$ratio1 = $size1[0]/$size1[1]; // width / height
	
		if($ratio>1) {
			$width1 = 500;
			$height1 = 500/$ratio1;
		} else {
			$width1 = 500*$ratio1;
			$height1 = 500;
		}
	
		$path1 = "../uploads/".$kxt;
	
		$imgString1 = file_get_contents($file1['tmp_name']);
	
		$image1 = imagecreatefromstring($imgString1);
	
		$image_p1 = imagecreatetruecolor($width1, $height1);
	
		imagecopyresampled($image_p1, $image1, 0, 0, 0, 0, $width1, $height1, $size1[0], $size1[1]);
	
		echo "image_p1".$image_p1;
		switch ($file1['type']) {
			case 'image/jpeg':
				imagejpeg($image_p1, $path1, 100);
				break;
			case 'image/png':
				imagepng($image_p1, $path1, 9);
				break;
			case 'image/gif':
				imagegif($image_p1, $path1);
				break;
			default:
				exit();
				break;
		}
		return $path1;
	
	}
	
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="../css/common.css"/>
	</head>
	<body>
		<form name="infoForm" method="post">
			<input type="hidden" name="memberId" value="<?php echo $distID?>">
			<input type="hidden" name="memberName" value="<?php echo $distName?>">
			
			<div class="cont_wrap">
				<div class="completion_box">
					<dl class="conttit_wrap">
						<dt>등록이 완료 되었습니다. </dt>
					</dl>
					<div class="stepbox_wrap">
						<img src="../images/unicityLogo.jpg" width="50%"/>
					</div>
		 		</div>
			</div>
		</form>
	</body>
</html>