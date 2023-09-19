<?
ini_set('memory_limit',-1);
ini_set('max_execution_time', 60);
?>
<?
	include "admin_session_check.inc";
	//include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";

	
	$str_title = iconv("UTF-8","EUC-KR","GLIC 여행");
	
	$file_name=$str_title."-".date("Ymd").".xls";
	header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
	header( "Content-Disposition: attachment; filename=$file_name" );
	header( "Content-Description: orion70kr@gmail.com" );
	

	
	
	$query2 = " select * from tb_glicTravel where flagUD is null order by No desc;";
	$result2 = mysql_query($query2);
	
	?>

<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<style>
            .xlGeneral {
            	padding-top:1px;
            	padding-right:1px;
            	padding-left:1px;
            	mso-ignore:padding;
            	color:windowtext;
            	font-size:10.0pt;
            	font-weight:400;
            	font-style:normal;
            	text-decoration:none;
            	font-family:Arial;
            	mso-generic-font-family:auto;
            	mso-font-charset:0;
            	mso-number-format:\@;
            	text-align:Left;
            	vertical-align:bottom;
            	mso-background-source:auto;
            	mso-pattern:auto;
            	white-space:nowrap;
            }
            .backGround{
                background-color: #D5D5D5;
            }

		


</style>
	</head>
	<body>
		<table border="1">
			<tr align="center">
				<th class="backGround" width="5%" style="text-align: center;">회원번호</th>
				<th class="backGround" width="5%" style="text-align: center;">회원명</th>
				<th class="backGround" width="5%" style="text-align: center;">참여인원</th>
				<th class="backGround" width="5%" style="text-align: center;">연락처</th>
				<th class="backGround" width="5%" style="text-align: center;">신청일자</th>
				<th class="backGround" width="5%" style="text-align: center;">카드</th>
				<th class="backGround" width="5%" style="text-align: center;">카드번호</th>
				<th class="backGround" width="5%" style="text-align: center;">유효시간</th>
				<th class="backGround" width="5%" style="text-align: center;">할부개월</th>
				<th class="backGround" width="5%" style="text-align: center;">생년월일</th>
				<th class="backGround" width="5%" style="text-align: center;">비번</th>
				<th class="backGround" width="5%" style="text-align: center;">신청일자</th>
				<th class="backGround" width="5%" style="text-align: center;">예약번호</th>
			</tr>
			<?php 
			while($obj = mysql_fetch_object($result2)) {
			    if($obj->payment_card == 'bc'){
					$paymentCard='BC카드';
				}else if($obj->payment_card == 'ss'){
					$paymentCard='삼성카드';
				}else if($obj->payment_card == 'sh'){
					$paymentCard='수협카드';
				}else if($obj->payment_card == 'jb'){
					$paymentCard='전북카드';
				}else if($obj->payment_card == 'kj'){
					$paymentCard='광주카드';
				}else if($obj->payment_card == 'hd'){
					$paymentCard='현대카드';
				}else if($obj->payment_card == 'lt'){
					$paymentCard='롯데카드';
				}else if($obj->payment_card == 'sinhan'){
					$paymentCard='신한카드';
				}else if($obj->payment_card == 'ct'){
					$paymentCard='시티카드';
				}else if($obj->payment_card == 'nh'){
					$paymentCard='농협카드';
				}else if($obj->payment_card == 'kb'){
					$paymentCard='국민카드';
				}else if($obj->payment_card == 'ha'){
					$paymentCard='하나카드';
				}else if($obj->payment_card == 'wo'){
					$paymentCard='우리카드';
				}

				$CardNumber = decrypt($key, $iv, $obj-> card_number);
				//$CardNumber =substr($CardNumber,0,4)." ".substr($CardNumber,4,4)." ".substr($CardNumber,8,4)." ".substr($CardNumber,12,4);
				$password = decrypt($key, $iv, $obj-> password);
			    ?>
			<tr>
	
				<td class="xlGeneral" style="width: 5%" align="center"><?echo $obj-> member_no?></td>
				<td class="xlGeneral" style="width: 5%" align="center"><?echo $obj-> member_name?></td>
				<td class="xlGeneral" style="width: 5%" align="center"><?echo $obj-> member?></td>
				<td class="xlGeneral" style="width: 5%" align="center"><?echo $obj-> phone?></td>
				<td class="xlGeneral" style="width: 5%" align="center"><?echo $obj-> select_date?></td>
				<td class="xlGeneral" style="width: 5%" align="center"><?echo $paymentCard?></td>
				<td class="xlGeneral" style="width: 5%" align="center"><?echo $CardNumber?></td>
				<td class="xlGeneral" style="width: 5%" align="center"><?echo $obj-> expire_date?></td>
				<td class="xlGeneral" style="width: 5%" align="center"><?echo $obj-> installment?></td>
				<td class="xlGeneral" style="width: 5%" align="center"><?echo $obj-> birthday?></td>
				<td class="xlGeneral" style="width: 5%" align="center"><?echo $password?></td>
				<td class="xlGeneral" style="width: 5%" align="center"><?echo $obj-> create_date?></td>
				<td class="xlGeneral" style="width: 5%" align="center"><?echo $obj-> reservationNo?></td>
				
				
			</tr>
			<?php 
                }
			?>
		</table>
		
	</body>
</html>

<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>