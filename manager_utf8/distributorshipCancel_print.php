<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";

	$member_no	= str_quote_smart(trim($member_no));
	$mode				= str_quote_smart(trim($mode));

	$member_no = trim($member_no);
	//$member_no = str_replace("^", "'",$member_no);


		$query = "update distributorshipCancel set 
					reg_status = '3',
					print_date = now(),
					print_ma = '$s_adm_name'
			where no in ($member_no)";
		
		mysql_query($query);


	$query = "select * from distributorshipCancel where no in ($member_no)";
	$result = mysql_query($query);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<HEAD>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-Frame-Options" content="deny" />
		<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
		<TITLE><?echo $g_site_title?></TITLE>
		<script language="javascript">
			function init() {
				print();
			}
		
			function f_close() {
				opener.check_data();
				self.close();
			}
		
		</script>
		<style type="text/css">
			@page a4sheet {size:15.0cm 29.7cm}
			.a4 {page:a4sheet; page-break-after:always}
		</style>
	</HEAD>
	<BODY onload="init();">
	
<?php include "common_load.php" ?>

		<?	while($row = mysql_fetch_array($result)) { ?>
			<div class="a4">
				<!--[if gte IE 7]><br style='height:0; line-height:0'><![endif]-->
				<table border=0 width=100%>
					<tr>
						<td align="center">
							<TABLE cellspacing="0" cellpadding="10" class="TITLE">
								<TR>
									<TD align="left"><B>디스트리뷰터 해지 </B></TD>
								</TR>
							</TABLE>
							<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
								<tr>
									<td align='center'>
										<TABLE border="1" cellspacing="1" cellpadding="2" class="IN2">
											<?php 

												if($row[distributorshipCard] == 'Y'){
													$cardYN = '반납';
												}else if($row[distributorshipCard] == 'N'){
													$cardYN = '분실/훼손';
												}else if($row[distributorshipCard] == 'E'){
													$cardYN = '기타';
												}	
												
												if($row[distributorshipNote] == 'Y'){
													$noteYN = '반납';
												}else if($row[distributorshipNote] == 'N'){
													$noteYN = '분실/훼손';
												}else if($row[distributorshipNote] == 'E'){
													$noteYN = '기타';
												}
												
												if($row[mainsubchk] == '1'){
													$mainsubChk = '주';
												}else{
													$mainsubChk = '부';
												}		
												
												if($row[autoshipYn] == 'Y'){
													$autoYN = '동의';
												}else if($row[autoshipYn] == 'N'){
													$autoYN = '오토쉽 미신청';
												}
												
												if($row[dscChk] == "D0"){
													$dscChk = "서울";
												}else if ($row[dscChk] == "D1"){
													$dscChk = "인천";
												}else if ($row[dscChk] == "D2"){
													$dscChk = "안산";
												}else if ($row[dscChk] == "D3"){
													$dscChk = "대전";
												}else if ($row[dscChk] == "D4"){
													$dscChk = "원주";
												}else if ($row[dscChk] == "D5"){
													$dscChk = "대구";
												}else if ($row[dscChk] == "D6"){
													$dscChk = "광주";
												}else if ($row[dscChk] == "D7"){
													$dscChk = "부산";
												}
												
												if($row[autoshipYn]=="Y"){
													$autoshipYn ="신청 중";
												}else if($row[autoshipYn]=="N"){
													$autoshipYn ="신청 안함";
												}		
	
											?>
											<tr>
												<th>주/부사업자 : </th>
												<td><?echo $mainsubChk?></td>
											</tr>
											<tr>
												<th>회원번호 : </th>
												<td><?echo $row[id]?></td>
											</tr>
											<tr>
												<th>성명 : </th>
												<td><?echo $row[UserName]?></td>
											</tr>
											<tr>
												<th>생년월일 :</th>
												<td><?echo $row[birthDay]?></td>
											</tr>
											<tr>
												<th>휴대폰번호 :</th>
												<td><?echo $row[Phone]?></td>
											</tr>
											
											<tr>
												<th>회원등록증</th>
												<td><?echo $cardYN?></td>
											</tr>
											<?php if($row[cardetc] !=null){?>
											<tr>
												<th>회원등록증 사유</th>
												<td><?echo $row[cardetc]?></td>
											</tr>
											<?php }?>
											<tr>
												<th>회원수첩</th>
												<td><?echo $noteYN?></td>
											</tr>
											<?php if($row[noteetc] !=null){?>
											<tr>
												<th>회원수첩 사유</th>
												<td><?echo $row[noteetc]?></td>
											</tr>
											<?php }?>
											<tr>
												<th>해지사유</th>
												<td><?echo $row[cancelReason]?></td>
											</tr>
											<tr>
												<th>해지신청 일자</th>
												<td><?echo $row[cancelDate]?></td>
											</tr>
											<?php if($row[memberReg]=="Y"){?>
											<tr>
												<th>회원 등록서 <br/>제출처</th>
												<td>
													<? if($row[purposeSelect] == "P0"){
															echo "국민보험공단";
														}else if($row[purposeSelect] == "P1"){
															echo "고용보험";
														}else if($row[purposeSelect] == "P2"){
															echo "은행";
														}else if($row[purposeSelect] == "P3"){
															echo "국민연금공단";
														}else if($row[purposeSelect] == "P4"){
															echo "기타 (".$row[selectText].")";
														}
						
													?>
												</td>
											</tr>
											<tr>
												<th>
													회원 등록서 <br/>발급 목적 
												</th>
												<td><?echo $row[purpose]?></td>
											</tr>
											<tr>
												<th>
													수령방법 
												</th>
												<td><?
														if($row[faxORdsc] == "Y"){
															echo "팩스 (".$row[faxNum].")";
														}else if($row[faxORdsc] == "N"){
															echo "dsc (".$dscChk.")";
														}
													?>
												</td>
											</tr>
											<?php }?>
											<tr>
												<th>
													오토쉽 <br/>신청 여부 
												</th>
												<td><?echo $autoshipYn?></td>
											</tr>
										</TABLE>
									</td>
								</tr>
							</table>
							<br>
							<br>
						</td>
					</tr>
				</table>
				
				<TABLE cellspacing="0" cellpadding="10" class="TITLE">
					<TR>
						<TD align="left">&nbsp;</TD>
						<TD align="right" width="600" align="center" bgcolor=silver>
							<INPUT TYPE="button" VALUE="출력 하기" onClick="print();">	
							<INPUT TYPE="button" VALUE="닫 기" onClick="f_close();">	
						</TD>
					</TR>
				</TABLE>
			</center>
			</div>
		<?	} ?>
	</body>
</html>

<?
	mysql_close($connect);
?>