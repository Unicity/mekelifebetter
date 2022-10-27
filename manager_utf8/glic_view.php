<?php
session_start();
?>

<?
include "./admin_session_check.inc";
include "./inc/global_init.inc";
include "../dbconn_utf8.inc";
include "./inc/common_function.php";
include "../AES.php";

$idVal = str_quote_smart($idVal);
$query = "select * from tb_glicTravel where No = ".$idVal;

$result = mysql_query($query);
$list = mysql_fetch_array($result);

$memberNo = $list[member_no];
$memberName = $list[member_name];
$member = $list[member];
$phone = $list[phone];
$selectDate = $list[select_date];
$paymentCard = $list[payment_card];
$cardNumber = $list[card_number];
$expireDate = $list[expire_date];
$birthday = $list[birthday];
$installment = $list[installment];
$password = $list[password];
$No = $list[No];

$update_date = $list[update_date];

$CardNumber = decrypt($key, $iv, $cardNumber);
$password = decrypt($key, $iv, $password);

$date_sc = date("Y-m-d [H:i]", strtotime($update_date));



//echo $s_adm_id;
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<meta http-equiv="X-Frame-Options" content="deny" />
		<link rel="stylesheet" href="inc/admin.css" type="text/css">
		
		<title>GLIC 여행</title>
		
	</head>
	<body>
		<form name="frm_m" method="post">
			<table cellspacing="0" cellpadding="10" class="title">
    			<tr>
    				<td align="left"><b>GLIC 여행 </b></td>
    				<td align="right" align="center" bgcolor=silver>
    				
    					
    				
    					<input type="button" onclick="goIn()" value = '수정' name="btn2">&nbsp;
    					<input type="button" onclick="goDelete();" value="삭제" name="btn4">
    		
    					<input type="button" onclick="goBack();" value="목록" name="btn4">&nbsp;
    				</td>
    				
    			</tr>
			</table>
			<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
				<tr>
					<td width="100%">
						<table border="0" cellspacing="1" cellpadding="2" class="IN3">
							<tr>
								<th>회원번호</th>
								<td><input type="text" name="member_no" value="<?php echo $memberNo?>" readonly="readonly"></td>
							</tr>
							<tr>
								<th>회원이름</th>
								<td><input type="text" name="member_name" value="<?php echo $memberName?>" readonly="readonly"></td>
							</tr>
							<tr>
								<th>참여인원</th>
								<td>
										<select name="attendP" id="attendP" title="참석인원" style="width:70px; height: 20px;">
								
											<option value="1" <?if($member=='1'){?>selected<?}?>>1명</option>
											<option value="2" <?if($member=='2'){?>selected<?}?>>2명</option>
										</select>
								</td>
							</tr>
							<tr>
								<th>전화번호</th>
								<td><input type="text" name="phone" maxlength="11" value="<?php echo $phone?>"></td>
							</tr>
							<tr>
								<th>신청날짜</th>
								<td>
									<select name="selectDate" id="selectDate" title="신청날짜" style="width:140px; height: 20px;">
								
										<option value="0804" <?if($selectDate=='0804'){?>selected<?}?>>선택1) 8월4일 출발</option>
										<option value="0805" <?if($selectDate=='0805'){?>selected<?}?>>선택2) 8월5일 출발</option>
									</select>
								</td>
							</tr>
							<tr>
								<th>카드</th>
								<td>
									<select name="selectCard" id="selectCard" title="카드선택" style="width:130px; height: 20px;">
							
										<option value="bc" <?if($paymentCard=='bc'){?>selected<?}?>>BC카드</option></option>
										<option value="kb" <?if($paymentCard=='kb'){?>selected<?}?>>국민카드</option>
										<option value="ss" <?if($paymentCard=='ss'){?>selected<?}?>>삼성카드</option>
										<option value="sh" <?if($paymentCard=='sh'){?>selected<?}?>>수협카드</option>
										<option value="jb" <?if($paymentCard=='jb'){?>selected<?}?>>전북카드</option>
										<option value="kj" <?if($paymentCard=='kj'){?>selected<?}?>>광주카드</option>
										<option value="hd" <?if($paymentCard=='hd'){?>selected<?}?>>현대카드</option>
										<option value="lt" <?if($paymentCard=='lt'){?>selected<?}?>>롯데카드</option>
										<option value="sinhan" <?if($paymentCard=='sinhan'){?>selected<?}?>>신한카드</option>
										<option value="ct" <?if($paymentCard=='ct'){?>selected<?}?>>시티카드</option>
										<option value="nh" <?if($paymentCard=='nh'){?>selected<?}?>>농협카드</option>
										<option value="nh" <?if($paymentCard=='ha'){?>selected<?}?>>하나카드</option>
										<option value="nh" <?if($paymentCard=='wo'){?>selected<?}?>>우리카드</option>

									</select>					
								</td>
							</tr>
							<tr>
								<th>카드 번호</th>
								<td><input type="text" name="card_number" maxlength="16" value="<?php echo $CardNumber?>"></td>
							</tr>
							<tr>
								<th>유효기간(/제외 입력)</th>
								<td><input type="text" name="expire_date" maxlength="4" value="<?php echo $expireDate?>" placeholder="YYMM(년월)"></td>
							</tr>
							<tr>
								<th>생년월일</th>
								<td><input type="text" name="birthday" maxlength="6" value="<?php echo $birthday?>"></td>
							</tr>
							<tr>
								<th>비밀번호(앞두자리)</th>
								<td><input type="password" name="pword" id="pword" maxlength="2" value="<?php echo $password ?>"></td>
							</tr>
							<tr>
								<th>카드할부</th>
								<td>
								<select name="installment" id="installment" title="카드할부" style="width:130px; height: 20px;">
											<option value="" selected>선택</option>
											<option value="0"<?if($installment==0){?>selected<?}?>>일시불</option></option>
											<option value="1"<?if($installment==1){?>selected<?}?>>1개월</option>
											<option value="2"<?if($installment==2){?>selected<?}?>>2개월</option>
											<option value="3"<?if($installment==3){?>selected<?}?>>3개월</option>
											<option value="4"<?if($installment==4){?>selected<?}?>>4개월</option>
											<option value="5"<?if($installment==5){?>selected<?}?>>5개월</option>
											<option value="6"<?if($installment==6){?>selected<?}?>>6개월</option>
											<option value="7"<?if($installment==7){?>selected<?}?>>7개월</option>
											<option value="8"<?if($installment==8){?>selected<?}?>>8개월</option>
											<option value="9"<?if($installment==9){?>selected<?}?>>9개월</option>
											<option value="10"<?if($installment==10){?>selected<?}?>>10개월</option>
											<option value="11"<?if($installment==11){?>selected<?}?>>11개월</option>
											<option value="12"<?if($installment==12){?>selected<?}?>>12개월</option>
										</select>
								</td>
							</tr>	
						</table>
					</td>
				</tr>
			</table>
			<input type="hidden" name="noFlag" value="<?php echo $No?>">
			<input type="hidden" name="flagUD" value="">

		</form>
	</body>
	<script language="javascript">
		function goBack() {
			document.frm_m.target = "frmain";
			document.frm_m.action="glic_admin.php";
									
			document.frm_m.submit();
		}
		function goDelete(){

			if(confirm('<?php echo $memberName?>님 을 삭제 하시겠습니까?') == true) {
				document.frm_m.flagUD.value='D'
				document.frm_m.action="../glicTravel/updateData.php";
				document.frm_m.submit();
			}	
		}	

		function goIn(){
			
			if(confirm("수정하시겠습니까?")){   
				document.frm_m.flagUD.value='U'
				document.frm_m.action = "../glicTravel/updateData.php";
				document.frm_m.submit();
			}
		}	

		function goMemo(kind) {
			
			var url = "changeSponsor_memo.php?memo_kind="+kind+"&no="+'<?php echo $no ?>';
			
			NewWindow(url, "memo_page", '600', '250', "no");
		}


		function NewWindow(mypage, myname, w, h, scroll) {
			var winl = (screen.width - w) / 2;
			var wint = (screen.height - h) / 2;
			winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
			win = window.open(mypage, myname, winprops)
			if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
		}

		function inputNumberFormat(obj) {
			obj.value = comma(uncomma(obj.value));
		}

		function comma(str) {
			str = String(str);
			return str.replace(/(\d)(?=(?:\d{2})+(?!\d))/g, '$1/');
		}

		function uncomma(str) {
			str = String(str);
			return str.replace(/[^\d]+/g, '');
		}
	</script>
</html>