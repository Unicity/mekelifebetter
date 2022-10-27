<?php session_start();?>
<?php
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "./inc/common_function.php";

	$applyId = $_REQUEST['applyId'];
	$page = $_REQUEST['page'];
	$con_sort = $_REQUEST['sort'];
	$order = $_REQUEST['order'];

	$query = "select * from education_apply where id = '".$applyId."'";
	$result = mysql_query($query);
	$list = mysql_fetch_array($result);

	$applyId = $list[id];
	$userName = $list[UserName];
	$email = $list[email];
	$phone = $list[Phone];
	$birthDay = $list[birthDay];
	$licenseNum = $list[licenseNum];
	$representativeName = $list[representativeName];
	$representativeBirth = $list[representativeBirth];
	$deputyEduYn = $list[deputyEduYn];
	$deputyReason = $list[deputyReason];
	$cancelYn = $list[cancelYn];
	$cancelReason = $list[cancelReason];
	$cancelDate = $list[cancelDate];
	
	$ymd= date('Y-m-d');
	
	$companyMember = $_SESSION[reg];
	

	logging($s_adm_id,'view training applicant detail '.$applyId);
?>		
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<script type="text/javascript" src="../SE2.1.1.8141/js/HuskyEZCreator.js" charset="utf-8"></script>
<link rel="stylesheet" href="inc/admin.css" type="text/css">
<style type='text/css'>
td {FONT-SIZE: 9pt}
.h {FONT-SIZE: 9pt; LINE-HEIGHT: 120%}
.h2 {FONT-SIZE: 9pt; LINE-HEIGHT: 180%}
.s {FONT-SIZE: 8pt}
.l {FONT-SIZE: 11pt}
.text {  line-height: 125%}
.DEK {POSITION:absolute;VISIBILITY:hidden;Z-INDEX:200;}
</STYLE>
<title>건식 보수교육 수정</title>
<script language="javascript">

	var ns4=document.layers;
	var date = new Date();
	
	function getTimeStamp() {
		  var s =
			leadingZeros(date.getFullYear(), 4) + '-' +
			leadingZeros(date.getMonth() + 1, 2) + '-' +
			leadingZeros(date.getDate(), 2) + ' ' +

			leadingZeros(date.getHours(), 2) + ':' +
			leadingZeros(date.getMinutes(), 2) + ':' +
			leadingZeros(date.getSeconds(), 2);

		  return s;
		}

	function leadingZeros(n, digits) {
		  var zero = '';
		  n = n.toString();

		  if (n.length < digits) {
			for (i = 0; i < digits - n.length; i++)
			  zero += '0';
		  }
		  return zero + n;
		}
		

	function cancelChange(){
		var frm = document.frm;
		var cancelYn = frm.cancelYn.value;

		if(cancelYn=='Y'){			
			frm.cancelDate.value =getTimeStamp();
		}else if(cancelYn=='N'){
			frm.cancelDate.disabled = true;
			frm.cancelDate.value ='';
			frm.cancelReason.value ='';
		}	

	}	

	function goBack() {
		document.location ="eduApply_admin.php?page=<?echo $page?>&sort=<?echo $con_sort?>&order=<?echo $order?>";
	}

	function goIn() {

		var upDatefrm = document.frm;
		var birthDay = upDatefrm.birthDay.value;
		var email = upDatefrm.email.value;
		var phone = upDatefrm.phone.value;

		var licenseNum = upDatefrm.licenseNum.value;
		var representativeName = upDatefrm.representativeName.value;
		var representativeBirth = upDatefrm.representativeBirth.value;
		var deputyEduYn = upDatefrm.deputyEduYn.value;
		var deputyReason = upDatefrm.deputyReason.value;
		var cancelYn = upDatefrm.cancelYn.value;
		var cancelReason = upDatefrm.cancelReason.value;
		var cancelDate = upDatefrm.cancelDate.value;

		if(cancelYn == 'Y'){
			if(cancelReason==''||cancelReason==null){
				alert("취소사유를 입력하세요");
				return false;
			}	
		}
		

		if(confirm("수정하시겠습니까?")){    
			
			upDatefrm.action = "eduApply_db.php";
			upDatefrm.submit();
		}	
		
	}

	function goBack() {
		document.location ="eduApply_admin.php?page=<?echo $page?>&sort=<?echo $sort?>&order=<?echo $order?>&idxfield=<?echo $idxfield?>&qry_str=<?echo $qry_str?>&sel_goods=<?echo $sel_goods?>";
	}
</script>
</head>
<body>
	<form name='frm' method='post'  enctype='multipart/form-data'>
		<table cellspacing="0" cellpadding="10" class="title">
			<tr>
				<td align="left"><b>건식 보수교육 신청 (수정)</b></td>
				<td align="right" width="300" align="center" bgcolor=silver>
					<input type="button" onClick="goIn();" value="수정" name="btn3">
					<input type="button" onClick="goBack();" value="목록" name="btn4">
					<input type="hidden" name="page" value="<?echo $page?>">
				</td>
			</tr>
		</table>
	
		<br>
		<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
			<tr>
				<td align='center'>
					<table border="0" cellspacing="1" cellpadding="2" width="100%">
						<tr>
							<th bgcolor="#666666" colspan="2" align="center">
								<b><font color="#FFFFFF">신청정보</font></b>
							</th>
						</tr>
						</table>
						<table border="0" cellspacing="1" cellpadding="2" width="100%">
						
						<tr>
							<td width="15%" bgcolor="#DDDDDD" align="right">
								회원번호 :
							</td>
							<td width="85%" bgcolor="#EEEEEE" colspan="3">
								<input type="text" name="applyId" id="applyId" size="20" value="<?echo $applyId?>" readonly>
							</td>
						</tr>
						<tr>
							<td width="15%" bgcolor="#DDDDDD" align="right">
								회원성명 :
							</td>
							<td width="85%" bgcolor="#EEEEEE" colspan="3">
								<input type="text" name="userName" id="userName" size="40" value="<?echo $userName?>" readonly>
							</td>
						</tr>
						<tr>
							<td width="15%" bgcolor="#DDDDDD" align="right">
								생년월일 :
							</td>
							<td width="85%" bgcolor="#EEEEEE" colspan="3">
								<input type="text" name="birthDay" id="birthDay" size="40" value="<?echo $birthDay?>">
							</td>
						</tr>
						<tr>
							<td width="15%" bgcolor="#DDDDDD" align="right">
								이메일 :
							</td>
							<td width="85%" bgcolor="#EEEEEE" colspan="3">
								<input type="text" name="email" id="email" size="40" value="<?echo $email?>">
							</td>
						</tr>
						<tr>
							<td width="15%" bgcolor="#DDDDDD" align="right">
								휴대폰번호 :
							</td>
							<td width="85%" bgcolor="#EEEEEE" colspan="3">
								<input type="text" name="phone" id="phone" size="40" value="<?echo $phone?>">
							</td>
						</tr>
						<tr>
							<td width="15%" bgcolor="#DDDDDD" align="right">
								인허가번호 :
							</td>
							<td width="85%" bgcolor="#EEEEEE" colspan="3"  >
								<input type="text" name="licenseNum" id="licenseNum" size="40" value="<?echo $licenseNum?>">
							</td>
						</tr>
						<tr>	
							<td width="15%" bgcolor="#DDDDDD" align="right">
								대표자 성명 :
							</td>
							<td width="85%" bgcolor="#EEEEEE" colspan="3">
								<input type="text" name="representativeName" id="representativeName" size="40" value="<?echo $representativeName?>">
							</td>
						</tr>
						<tr>	
							<td width="15%" bgcolor="#DDDDDD" align="right">
								대표자 생년월일 :
							</td>
							<td width="85%" bgcolor="#EEEEEE" colspan="3">
								<input type="text" name="representativeBirth" id="representativeBirth" size="40" value="<?echo $representativeBirth?>">
							</td>
						</tr>
						<tr>
							<td width="15%" bgcolor="#DDDDDD" align="right">
								대리여부 :
							</td>
							<td width="85%" bgcolor="#EEEEEE" colspan="3">
								<select id="deputyEduYn" name="deputyEduYn">	
									<option value='Y'<?if($deputyEduYn=="Y"){?>selected<?}?>>Y</option>
									<option value='N'<?if($deputyEduYn=="N"){?>selected<?}?>>N</option>
								</select>
							</td>
						</tr>
						<tr>
							<td width="15%" bgcolor="#DDDDDD" align="right">
								대리사유 :
							</td>
							<td width="85%" bgcolor="#EEEEEE" colspan="3">
								<select id="deputyReason" name="deputyReason" style="width:100%;text-align:center;">	
									<option value='0'<?if($deputyReason==""){?>selected<?}?>></option>
									<option value='1'<?if($deputyReason=="1"){?>selected<?}?>>천재지변, 질병사고, 업무상 국외출장 등의 사유로 교육을 받은 수 없는 경우</option>
									<option value='2'<?if($deputyReason=="2"){?>selected<?}?>>영업자가 영업에 직접 종사하지 아니한 경우</option>
									<option value='3'<?if($deputyReason=="3"){?>selected<?}?>>2곳 이상의 장소에서 같은 영업자가 영업을 하려는 경우</option>	
								</select>
							</td>
						</tr>
						
						<tr>
							<td width="15%" bgcolor="#DDDDDD" align="right">
								취소여부 :
							</td>
							<td width="85%" bgcolor="#EEEEEE" colspan="3">
								<select id="cancelYn" name="cancelYn" onChange="cancelChange()">	
									<option value='Y'<?if($cancelYn=="Y"){?>selected<?}?>>Y</option>
									<option value='N'<?if($cancelYn=="N"){?>selected<?}?>>N</option>
								</select>
							</td>
						</tr>
						
						<tr>
							<td width="15%" bgcolor="#DDDDDD" align="right">
								취소사유 :
							</td>
							<td width="85%" bgcolor="#EEEEEE" colspan="3">
								<input type="text" name="cancelReason" id="cancelReason" size="40" value="<?echo $cancelReason?>">
							</td>
						</tr>
						
						<tr>
							<td width="15%" bgcolor="#DDDDDD" align="right">
								취소날짜 :
							</td>
							<td width="85%" bgcolor="#EEEEEE" colspan="3">
								<input type="text" name="cancelDate" id="cancelDate" size="40" value="<?echo $cancelDate?>" readonly;disabled="true"; >
							</td>
						</tr>
					</table>
			
					<table cellspacing="0" cellpadding="10" class="title">
					<tr>
						<td align="left">&nbsp;</td>
						<td align="right" width="300" align="center" bgcolor=silver>
							<input type="button" onClick="goIn();" value="수정" name="btn3">
							<input type="button" onClick="goBack();" value="목록" name="btn4">
							<input type="hidden" name="page" value="<?echo $page?>">
						</td>
					</tr>
					</table>
					
				</td>
			</tr>
		</table>
	
	
		<input type="hidden" name="mode" value="mod">
		<input type="hidden" name="page" value="<?echo $page?>">
		<input type="hidden" name="sort" value="<?echo $con_sort?>">
		<input type="hidden" name="companyMember" value="<?echo $companyMember?>">
		<input type="hidden" name="order" value="<?echo $order?>">
	
	<!--이전 페이지 정보 끝-->
	</form>

	<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?php
	mysql_close($connect);
?>
