<?php 
	session_start();
	
?>

<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="format-detection" content="telephone=no" />
	<link rel="stylesheet" type="text/css" href="./css/joo1.css" />
	<title>GLIC Travel 예약</title>
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
								<span>예약이 완료 되었습니다.</span>
								<span>예약번호는 <?php echo $_GET['rnum'] ?>입니다.</span>
							</h1>
						</div>	
					</div>
				</div>
			</div>
		</div>	

	</body>	

</html>
