<?php
	header("Content-Type: text/html; charset=UTF-8");
	
	include "./includes/inc/common_functions.php";

	if (session_id() == '') {
		// php >= 5.4.0
		// if (session_status() == PHP_SESSION_NONE) {
 		@session_start();
	}
	$token = isset($_SESSION['token']) ? $_SESSION['token'] : $_COOKIE['token'];
	$certName = isset($_SESSION["S_NM"]) ? $_SESSION["S_NM"]  : $_COOKIE['S_NM'];
	$username = isset($_SESSION["username"]) ? $_SESSION["username"] : $_COOKIE['username'];

	if (!$token  || !$certName || !$username ) {
		echo "<script> alert('정상적인 프로세스가 아닙니다.'); window.location.href = 'https://www.makelifebetter.co.kr/withholdingtax/login.php';</script>";
	} else {
		$authorizationCode = "Bearer ".$token;
		$url ="https://hydra.unicity.net/v5a/customers/me";
		$header = array(
			"Authorization: ".$authorizationCode
		);
		$response = getAPIwithHead($url, $header);

		if (is_array($response)){	
			
			$loginNM = $response['humanName']['fullName@ko'];
			 
			if ($certName == $loginNM) {
			
				$html = "<div id='withhold-receipt'>";
				$html .= 	"<div class='content-box' style='margin-top: 20px; '>"; 
          		$html .= 		"<form name=withholdingForm >";
          		$html .=         "<input type='hidden' name='certName' value='".$certName."'>";
              	$html .= 			"<span style='border: 0;font-size: 25px;height: 40px;margin-left: 5px;width: 190px;'>기간 : ";
              	$html .= 			"<select name='start' id='select-period-withhold-start' style='border: 0;font-size: 25px;height: 39px;margin-left: 5px;width: 190px;'></select>";
             	$html .=   "<span style='border: 0;font-size: 25px;height: 39px;margin-left: 5px;width: 190px;'> ~ </span><select name='end' id='select-period-withhold-end' style='border: 0;font-size: 25px; margin-left: 5px;width: 190px;'></select>";
             	$html .=  "<input type='hidden' name='id' value='".$username."'>";
              	$html .=  "<button id='btn-print-receipt' style='font-size:20px; margin-left:10px; margin-top:12px; border-radius: 5px;padding-top: 5px;padding-left: 10px;padding-right: 10px;border: 0px;' onclick='printOut()'><img height='23' width='23' style='padding-bottom: 0px;width: inherit;' src='./includes/images/printer.png'> 출력</button></form>";

              		  	 
			} else {
				if($certName=='홍순국'){
					//print_r($response);
					//echo "<script> alert('로그인 회원과 본인인증 회원이 상이합니다.이름:{$certName}.휴먼내임:{$loginNM} ".json_encode($response).".'); window.location.href = 'https://www.makelifebetter.co.kr/withholdingtax/certification.php';</script>";
				}
				echo "<script> alert('로그인 회원과 본인인증 회원이 상이합니다..'); window.location.href = 'https://www.makelifebetter.co.kr/withholdingtax/certification.php';</script>";
			}
		}else {
			echo "<script> alert('로그인 회원과 본인인증 회원이 상이합니다...'); window.location.href = 'https://www.makelifebetter.co.kr/withholdingtax/certification.php';</script>";
		}

	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 
	<link rel="stylesheet" type="text/css" href="./includes/css/common.css"/>
 
	  
 
	<title>원천징수영수증 출력</title>

</head>
	<body>
		<div class="cont_wrap">
			<dl class="conttit_wrap">
				<dt>원천징수 영수증 출력</dt>
				<dd>출력하고자 하는 기간을 선택해 주세요.</dd>
				<dd>원천징수 영수증은 현재 주사업자 기준으로만 출력 가능 합니다. 그 외 출력건은 1577-8269로 연락 주시기 바랍니다</dd>
			</dl>
			<div class="conttit_wrap">
				<?php echo $html; ?>	
			</div>
		</div>
	</body>
		<script>
		 
			var month = ['01','02','03','04','05','06','07','08','09','10','11','12'];
			var currentYear = new Date().getFullYear();
			var currentMonth = month[new Date().getMonth()]; 
			var endPeriod = currentYear + '-' + currentMonth;
			var currentPeriod = new Date(currentYear, new Date().getMonth(),1);
			var i=20;
			period_date = [];
			period_date.push(endPeriod);
			 
			for(i=23;i>=0;i--){
				currentPeriod.setMonth(currentPeriod.getMonth()-1);
				period_date.push(currentPeriod.getFullYear()+'-'+month[currentPeriod.getMonth()]);
			}
			var prev = document.getElementById("select-period-withhold-start");
			var prev2 = document.getElementById("select-period-withhold-end");
			var start, end = ""
			for(var i in period_date){
					
					start += '<option class="period-sum_'+i+'" value="'+period_date[i]+'">'+period_date[i]+'</option>';
					end +='<option class="period-sum_'+i+'" value="'+period_date[i]+'">'+period_date[i]+'</option>';
				} 
			prev.innerHTML = start;
			prev2.innerHTML = end;

			function printOut(){
				var start = document.withholdingForm.start.value;
				var end = document.withholdingForm.end.value;

				var startSub = start.substring( 0, 4 );
				var endSub = end.substring( 0, 4 ) 
				
				if(startSub != endSub){
					alert("같은 년도의 데이터만 출력이 가능 합니다.");
					return;
				}	
				// 원천징수 영수증은 현재 주사업자 기준으로만 출력 가능 합니다. 그 외 출력건은 1588-8269로 연락 주시기 바랍니다.
				
				if (new Date(start) > new Date(end)){
					alert('기간을 다시 확인해 주세요.');
					return ;
				}
				maxEndPeriod = new Date(new Date(start).setMonth(new Date(start).getMonth()+12));
				if (maxEndPeriod < new Date(end)) {
					alert('최대 1년치만 한번에 인쇄가가능합니다.');
					return;
				}
				document.withholdingForm.method = "POST";
				document.withholdingForm.target = "_blank";
				document.withholdingForm.action = "https://www.makelifebetter.co.kr/manager_utf8/report_form_new.php";
				document.withholdingForm.submit();

			}
		 
	</script>
</html>
