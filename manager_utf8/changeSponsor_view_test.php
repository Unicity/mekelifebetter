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
$query = "select * from tb_change_sponsor where no = ".$idVal;
$result = mysql_query($query);
$list = mysql_fetch_array($result);

$memberNo = $list[member_no];
$memberName = $list[member_name];
$sponsorNo = $list[sponsor_no];
$sponsorName = $list[sponsor_name];
$sponsorAgreeYn = $list[sponsor_agree_yn];
$chSponsorNo = $list[ch_sponsor_no];
$chSponsorName = $list[ch_sponsor_name];
$applyDate = $list[apply_date];
$no = $list[no];
$memo= $list[memo];
$reject_ma = $list[reject_ma];
$reject_date = $list[reject_date];
$reg_status = $list[reg_status];
$update_date = $list[update_date];
$wait_date = $list[wait_date];
$wait_ma = $list[wait_ma]; 
$address = $list[address]; 
$phoneNum = $list[phoneNum]; 


$date_sr = date("Y-m-d [H:i]", strtotime($reject_date));
$date_sc = date("Y-m-d [H:i]", strtotime($update_date));
$date_wa = date("Y-m-d [H:i]", strtotime($wait_date));

//echo $s_adm_id;

$logQuery = "select * from tb_check_log where flag='S' and name='$sponsorName' and data3 = '$sponsorNo' order by chkdate desc limit 1";
$result1 = mysql_query($logQuery);
$list1 = mysql_fetch_array($result1);
$chkdate = $list1[chkdate]; 
$data3 = $list1[data3]; 

//echo "logQuery=>". $logQuery;

?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<meta http-equiv="X-Frame-Options" content="deny" />
		<link rel="stylesheet" href="inc/admin.css" type="text/css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<title>후원자 변경 신청</title>
		
	</head>
	<body>
		<form name="frm_m" method="post">
			<table cellspacing="0" cellpadding="10" class="title">
    			<tr>
    				<td align="left"><b>후원자 변경 신청 관리 </b></td>
    				<td align="right" align="center" bgcolor=silver>
    					<?php if($s_adm_id=='alsrnkmg'||$s_adm_id=='eykang' ||$s_adm_id=='syjeong'|| $s_adm_id=='hjshin'){	?>
    					
    				
    					<input type="button" onclick="goIn()" value = '수정' name="btn2">&nbsp;
    					<input type="button" onclick="goDelete();" value="삭제" name="btn4">
    				<?php }?>
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
								<td><input type="text" name="baID" maxlength="15" value="<?php echo $memberNo?>" readonly="readonly"></td>
							</tr>
							<tr>
								<th>회원이름</th>
								<td><input type="text" name="baName" maxlength="15" value="<?php echo $memberName?>" readonly="readonly"></td>
							</tr>
							<tr>
								<th>현재 후원자 번호</th>
								<td><input type="text" name="spNo" maxlength="15" value="<?php echo $sponsorNo?>" readonly="readonly"></td>
							</tr>
							<tr>
								<th colspan="1">현재 후원자 이름</th>
								<td><input type="text" name="spName" maxlength="15" value="<?php echo $sponsorName?>" readonly="readonly"></td>
							</tr>
							<tr>
								<th colspan="1">변경 후원자 번호</th>
								<td><input type="text" name="chNo" maxlength="15" value="<?php echo $chSponsorNo?>" readonly="readonly"></td>
							</tr>
							<tr>
								<th colspan="1">변경 후원자 이름</th>
								<td><input type="text" name="chName" maxlength="15" value="<?php echo $chSponsorName?>" readonly="readonly"></td>
							</tr>
							<tr>
								<th colspan="1">신청회원의 주소</th>
								<td><input type="text" name="address" maxlength="15" value="<?php echo $address?>" readonly="readonly"></td>
							</tr>
							<tr>
								<th colspan="1">번호</th>
								<td><input type="text" name="phoneNum" maxlength="15" value="<?php echo $phoneNum ?>" readonly="readonly"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table border="0" width='100%' cellspacing="1" cellpadding="1">
			<tr>
				<th bgcolor="#DDDDDD" width="137">
					신청 처리 정보:
				</th>
				<td bgcolor="#EEEEEE">
					<table border=0>
						<?php if($reg_status=='3'){?>
						<tr>
							<td><b>완료일</b> : <?echo $date_sc?></td>
							<td><b>완료자</b> : <?echo $confirm_ma?></td>
							<td>&nbsp;</td>
						</tr>
						<?php }else{?>
						<tr>
							<td><b>거부일</b> : <?echo $date_sr?></td>
							<td><b>거부자</b> : <?echo $reject_ma?></td>
							<td><input type="button" value="거부 내용 입력" onClick="goMemo('r');"></td>
						</tr>
						<tr>
							<td><b>보류일</b> : <?echo $date_wa?></td>
							<td><b>보류자</b> : <?echo $wait_ma?></td>
							<td><input type="button" value="보류 내용 입력" onClick="goMemo('w');"></td>
						</tr>
						<tr>
							<td><b>동의여부</b> : <?echo$data3?></td>
							<td><b>동의시간</b> : <?echo $chkdate?></td>
							<td><input type="button" value="동의하기" onClick="goAgree()"></td>
						</tr>
						<?php }?>
					</table>
				</td>
			</tr>
			<?	#if ($s_flag == "1") {?>
			<tr>
				<th bgcolor="#DDDDDD">
					회원 처리 사항:
				</th>
				<td bgcolor="#EEEEEE">
					<select name="reg_status">
					
						<option value = "2" <?if ($reg_status == "2") echo "selected";?>>신청</option>
				
						<option value = "3" <?if ($reg_status == "3") echo "selected";?>>완료</option>
						<option value = "9" <?if ($reg_status == "9") echo "selected";?>>익월처리</option>
						<option value = "8" <?if ($reg_status == "8") echo "selected";?>>보류</option>
						
						<option value = "4" <?if ($reg_status == "4") echo "selected";?>>신청 거부</option>
					</select>&nbsp; <!--최고 관리자로 접속하셨을 경우만 변경 가능 합니다.-->
				</td>
			</tr>
			<?	#} else { ?>
				<!--<input type="hidden" name="reg_status" value="<?echo $reg_status?>">-->
			<?	#} ?>
			<tr>
				<th bgcolor="#DDDDDD">
					보류 또는 거부사유:
				</th>
				<td bgcolor="#EEEEEE">
					<?echo $memo?>
				</td>
			</tr>
			<tr>
				<th bgcolor="#DDDDDD">
					문자발송:
				</th>
				<td bgcolor="#EEEEEE">
					<select name="smsSend" id="smsSend">
						<option value="" selected>지역</option>
						<option value="se">서울DSC</option>
						<option value="an">안산DSC</option>
						<option value="de">대전DSC</option>
						<option value="wo">원주DSC</option>
						<option value="bu">부산DSC</option>
						<option value="dg">대구DSC</option>
						<option value="kw">광주DSC</option>
						<option value="in">인천DSC</option>
						<option value="je">제주DSC</option>
					</select>
					<a  href="javascript:sendText()">문자전송</a>
				</td>
			</tr>
		</table>
			<input type="hidden" name="delNo" value="<?php echo $no?>">
			<input type="hidden" name="agreeDate" value="<?php echo $chkdate?>">
			<input type="hidden" name="flag" value="delete">
		</form>
	</body>
	<script language="javascript">

	var sendNumber = '';	

	function goBack() {
		document.frm_m.target = "frmain";
		document.frm_m.action="changeSponsor_admin.php";
		                        
		document.frm_m.submit();
	}
	function goDelete(){

		if(confirm('<?php echo $memberName?>님 을 삭제 하시겠습니까?') == true) {
			 document.frm_m.action="change_process.php";
			 document.frm_m.submit();
		}	
	}	

	function goIn(){
		
		if(confirm("수정하시겠습니까?")){   
			document.frm_m.flag.value = 'update'
			document.frm_m.action = "change_process.php";
			document.frm_m.submit();
		}
	}	

	function goAgree(){
		if(confirm("동의 하시겠습니까?")){   
			document.frm_m.flag.value = 'forAgree'
			document.frm_m.action = "change_process.php";
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

	function sendText(){
		//alert(document.frm_m.smsSend.value);

		if(document.frm_m.smsSend.value == ''){
			alert('문자발송 지역을 선택 하세요');
			return false;
		}
		if(confirm("문자를 발송 하시겠습니까?")){   
			var phone = '<?php echo $phoneNum?>';

			var param = {smsSend:document.frm_m.smsSend.value,
						phone: phone};
			$.ajax({
						url: "sendMessage.php",
						async : false,
						dataType : "json",
						data:param,
						type:"POST",
						success	: function(result) {
			console.log(result.count);
			console.log(result.OK);

						if(result.OK=='OK'){
							alert("문자발송이 완료 됐습니다.");
							return false;
						}
						
						},
						'error':function () {
							alert("문자전송 실패");
						}
							
			});	
		}		
	}
	</script>
</html>