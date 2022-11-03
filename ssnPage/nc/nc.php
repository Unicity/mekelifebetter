<?php

	header("Content-type: text/html; charset=euc-kr");
	session_start();

	/********************************************************************************************************************************************
	NICE평가정보 Copyright(c) KOREA INFOMATION SERVICE INC. ALL RIGHTS RESERVED
	
	서비스명 : 주민번호 실명확인 서비스 
	페이지명 : 주민번호 실명확인 서비스 입력 페이지
	*********************************************************************************************************************************************/
	
	// 데이터 위변조 검사용 변수 (생략 가능)
	$sReqKey = "Q662";
	$sReqNo = "14622139";

	// 데이터 위변조 검사용 변수를 세션에 저장 (생략 가능)
	$_SESSION[$sReqKey] = $sReqNo;
?>

<html>
<head>
	<meta charset="EUC-KR">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>NICE평가정보 실명확인 서비스</title>
	<style type="text/css">
		table, th, td {
			border: 0px solid black;
			border-spacing: 0px;
			padding: 0px;
			
		}
	</style>
	<script>
		function fnSubmit() {
            if (document.form_nc.jumin1.value.length != 6) {
                alert('주민번호 앞자리 6자리를 입력해주세요.');
                jumin1.focus();
                return;
            }

            if (document.form_nc.jumin2.value.length != 7) {
                alert('주민번호 뒷자리 7자리를 입력해주세요.');
                jumin2.focus();
                return;
            }

            if (document.form_nc.name.value == '') {
                alert('이름을 입력해주세요.');
                name.focus();
                return;
            }
			document.form_nc.submit();
		}
	</script>
</head>
<body>
	<!-- NICE 평가정보 실명확인 form -->
	<form name="form_nc" method="post" action="./nc_p.php">
	<table style="width:650;height:470;">
		<tr>
			<td style="height:20;"></td>
		</tr>
		<tr>
			<td style="text-align:center; vertical-align:top;">
				<table>
					<tr>
						<td colspan="5">
							<img src="https://www.niceid.co.kr/images/mycredit00/static/img/pop04_001_01.gif" width="583" height="156" alt="">
						</td>
					</tr>
					<tr>
						<td>
							<img src="https://www.niceid.co.kr/images/mycredit00/static/img/pop04_001_02.gif" width="38" height="62" alt="">
						</td>
						<td>
							<img src="https://www.niceid.co.kr/images/mycredit00/static/img/pop04_001_03.gif" width="150" height="62" alt="">
						</td>
						<td style="width:206;">
							<table>
								<tr>
									<td style="height:28;">
                                        <input name="jumin1" id="jumin1" type="text" size="6" maxlength="6"> 
                                        - <input name="jumin2" id="jumin2" type="password" size="7" maxlength="7">
                                    </td>
								</tr>
								<tr>
									<td style="height:28;">
										<!-- 영문 50자 한글 25자까지 입력 가능 -->
										<input name="name" id="name" type="text" size="20" maxlength="50"> 
									</td>
								</tr>
							</table>
						</td>
						<td>
							<!-- NICE 아이디 실명확인 버튼 -->
							<a href="javascript:fnSubmit();">
								<img src="https://www.niceid.co.kr/images/mycredit00/static/img/pop04_001_05.gif" alt="NICE아이디 실명확인 버튼">
							</a>
						</td>
						<td>
							<img src="https://www.niceid.co.kr/images/mycredit00/static/img/pop04_001_06.gif" width="39" height="62" alt="">
						</td>
					</tr>
					<tr>
						<td colspan="5">
							<!-- NICE 아이디 실명확인 서비스 안내 링크 -->
	                        <a href="https://www.niceid.co.kr/prod_name.nc" target="_blank">
	                            <img src="https://www.niceid.co.kr/images/mycredit00/static/img/pop04_001_07.gif" alt="NICE아이디 실명확인 서비스" >
	                        </a>
                         </td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="height:20;"></td>
		</tr>
	</table>
	</form>
</body>
</html>