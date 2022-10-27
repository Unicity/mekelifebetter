<?php 	include "includes/config/common_functions.php";
		
?>
<?php 
	@session_start();
    include "includes/config/config.php";
    include "includes/config/nc_config.php";
    cert_validation();

	$distID = $_GET['loginId'];

	/*
	$distID = $_POST['fId'];
	$distName = $_POST['fName'];
	$rsponsorId = $_POST['sponsorId'];
	$rsponsorName = $_POST['sponsorName'];
	$fAddress = $_POST['fAddress'];
	$entryDate = $_POST['entryDate'];
*/
	
	?>
	
	
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>GLIC Travel pack 예약</title>
		<meta name="description" content="" />
		<meta http-equiv="Content-Script-Type" content="text/javascript">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
		<link rel="stylesheet" type="text/css" href="./css/joo1.css" />
	</head>
	<body>
		<div>
			<div>
				<div class="figure">
					<img src="./images/mainlogo.png" alt="유니시티 로고" />
				</div>
				<div>
					<div class="main_inner_box">
						<div class="main_top">
							<h1>
								<span>2022 GLIC Travel 안내</span>
							</h1>
						</div>
								
					</div>
					<div align="center">
						<div class="">
							<h5 id="DetailShow1" style="margin-top: 9px;cursor: pointer;font-size: 17px; color:#11c2c9;" onclick="DetailShow1()"><u>22년 8월 4일 상세일정 확인 ▼</u></h5><br/>
							<h5 id="DetailNone1" style="margin-top: 9px;display:none;cursor: pointer;font-size: 17px; color:#11c2c9;" onclick="DetailNone1()"><u>22년 8월 4일 상세일정 확인 ▲</u></h5><br/>
							<div id="deteilCheck1" style="display:none;">
								<img src="https://www.makelifebetter.co.kr/glicTravel/GLIC_0804.jpg" alt="GLIC" style="max-width:100%; height:auto;" />
							</div>	
							<h5 id="DetailShow2" style="margin-top: 9px;cursor: pointer;font-size: 17px; color:#11c2c9;" onclick="DetailShow2()"><u>22년 8월 5일 상세일정 확인 ▼</u></h5><br/>
							<h5 id="DetailNone2" style="margin-top: 9px;display:none;cursor: pointer;font-size: 17px; color:#11c2c9;" onclick="DetailNone2()"><u>22년 8월 5일 상세일정 확인 ▲</u></h5><br/>
							<div id="deteilCheck2" style="display:none;">
								<img src="https://www.makelifebetter.co.kr/glicTravel/GLIC_0805.jpg" alt="GLIC" style="max-width:100%; height:auto;" />
							</div>	
						</div>
					</div>
					<div style="height: 10px;"></div>
					<div align="center" id="agreementBut" style="background-color: #B2CCFF;width: 30%; height: 40px; margin-left: 35%; border-radius : 5px; text-shadow: 0px -1px 1px rgba(0,0,0,.3); border: 1px solid #4081AF;box-shadow : inset 0 1px 0 rgba(255,255,255,.3), inset 0 0 2px rgba(255,255,255,.3) 0 1px 2px rgba(0,0,0,.29); cursor: pointer; ">
					<h2 style="margin-top: 5px;"><a href="javascript:nextPage()"><b>예약 하기</b></a></h2>
					</div>
				</div>
			</div>
		</div>			
	</body>
	<script type="text/javascript" src="./js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src='./js/common.js'></script>
	<script type="text/javascript" src="./js/selectordie.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			var now = new Date()
			var year = now.getFullYear();	
			var month = now.getMonth()+1;
			var date = now.getDate();
			var hours = now.getHours();	
			var minutes = now.getMinutes();
			var currentTime = year+'-'+month+'-'+date+' '+hours+':'+minutes
	/*	
console.log(currentTime);
			if(currentTime >='2022-5-16 10:00'){
				alert("예약사이트 오픈 시간은 5월 16일 10시 입니다.");
				location.href='https://ushop-kr.unicity.com/login';
			}
*/
		});

		function DetailShow1(){
		
			$('#deteilCheck1').css("display", "block");
			$('#DetailShow1').css("display", "none");
			$('#DetailNone1').css("display", "block");
			
		}

		function DetailNone1(){
		
		$('#deteilCheck1').css("display", "none");
		$('#DetailShow1').css("display", "block");
		$('#DetailNone1').css("display", "none");
		
		}

		function DetailShow2(){
		
		$('#deteilCheck2').css("display", "block");
		$('#DetailShow2').css("display", "none");
		$('#DetailNone2').css("display", "block");
		
		}

		function DetailNone2(){
		
		$('#deteilCheck2').css("display", "none");
		$('#DetailShow2').css("display", "block");
		$('#DetailNone2').css("display", "none");
		
		}

		function nextPage(){
			alert("여행상품권 구매는 마이비즈 쇼핑에서 구입 가능 합니다.");
			//location.href="glicTravel.php?glicId=<?php echo $distID?>";
		}


	</script>
		
</html>