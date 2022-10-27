<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";

	function right($value, $count){
		$value = substr($value, (strlen($value) - $count), strlen($value));
		return $value;
	}

	function left($string, $count){
		return substr($string, 0, $count);
	}

	function str_cut($str,$len){
		$slen = strlen($str);
		if (!$str || $slen <= $len) $tmp = $str;
		else	$tmp = preg_replace("/(([\x80-\xff].)*)[\x80-\xff]?$/", "\\1", substr($str,0,$len))."...";
		return $tmp;
	}


	function makeCode ($parent)  { 

		$sqlstr = "SELECT * FROM tb_code where parent='".$parent."' order by code"; 

		$result = mysql_query($sqlstr);
		$total 	= mysql_affected_rows();
			
		for($i=0 ; $i< $total ; $i++)	{  	//  start 에서 scale 까지 만

			if($i< $total )	{ 								// 전체 자료 개수까지만 출력

				mysql_data_seek($result,$i);
				$row = mysql_fetch_array($result);		
				print("<option value='$row[code]' style='color:352000'>$row[name]</option>\n");

			}
		}
	}

	function makeCodeAsName ($parent)  { 

		$sqlstr = "SELECT * FROM tb_code where parent='".$parent."' order by code"; 

		$result = mysql_query($sqlstr);
		$total 	= mysql_affected_rows();
			
		for($i=0 ; $i< $total ; $i++)	{  	//  start 에서 scale 까지 만

			if($i< $total )	{ 								// 전체 자료 개수까지만 출력

				mysql_data_seek($result,$i);
				$row = mysql_fetch_array($result);		
				print("<option value='$row[name]' style='color:352000'>$row[name]</option>\n");

			}
		}
	}

	$mode						= str_quote_smart(trim($mode));
	$member_no			= str_quote_smart(trim($member_no));
	$member_kind		= str_quote_smart(trim($member_kind));
	$idxfield				= str_quote_smart(trim($idxfield));
	$idx						= str_quote_smart(trim($idx));
	$qry_str				= str_quote_smart(trim($qry_str));
	$con_sort				= str_quote_smart(trim($con_sort));
	$con_order			= str_quote_smart(trim($con_order));
	$r_status				= str_quote_smart(trim($r_status));
	$r_memberkind		= str_quote_smart(trim($r_memberkind));
	$r_join_kind		= str_quote_smart(trim($r_join_kind));
	$r_active_kind	= str_quote_smart(trim($r_active_kind));
	$r_couple				= str_quote_smart(trim($r_couple));
	$from_date			= str_quote_smart(trim($from_date));
	$to_date				= str_quote_smart(trim($to_date));
	$idValue				= str_quote_smart(trim($id));
	



	$query = "select * from mainSubChg where id = '".$idValue."'";
	
	//echo $query;	

	$result = mysql_query($query);
	$list = mysql_fetch_array($result);
	
	$member_id = $list[FO_ID];
	$member_name = $list[FO_NAME];
	$birthDay = $list[FO_BIRTHDAY];
	$phone_number = $list[FO_PHONE];
	$applyDate = $list[applyDate];
	$applyflag = $list[applyflag];
	$photoFile = $list[TOGETHER_FILE];
	$mainSubFile = $list[CHG_FILE];
	
	$together_name = $list[TOGETHER_NAME];
	$together_birthday = $list[TOGETHER_BIRTHDAY];
	$together_relation = $list[TOGETHER_RELATION];
	$togerher_phone = $list[TOGETHER_PHONE];
	
	$chg_main_name = $list[CHG_MAIN_NAME];
	$chg_main_birthday = $list[CHG_MAIN_BIRTHDAY];
	$chg_bank = $list[CHG_BANK];
	$chg_account = $list[CHG_ACCOUNT];
	$chg_sub_name = $list[CHG_SUB_NAME];
	$chg_sub_birthday = $list[CHG_SUB_BIRTHDAY];
	
	$wait_ma = $list[wait_ma];
	$print_sw = $list[print_date];
	$confirm_sw = $list[confirm_date];
	$confirm_ma = $list[confirm_ma];
	$date_sw = $list[wait_date];
	$date_sr = $list[reject_date];
	$reject_ma = $list[reject_ma];
	$print_ma = $list[print_ma];
	$memo = $list[memo];
	$reg_status = $list[reg_status];
	$id = $list[id];
	$mTime = $list[mtime];
	$gender = $list[gender];

	
	$phonelength=strlen($phone_number);
	$str=$phone_number;
	if($phonelength == '11'){
		$phoneNum=substr($str,0,3)."-".substr($str,3,4)."-".substr($str,7,4);
	}else{
		$phoneNum=substr($str,0,2)."-".substr($str,2,4)."-".substr($str,6,4);
	}
/*	echo $member_id."<br/>";
	echo $member_name."<br/>";
	echo $birthDay."<br/>";
	echo $phone_number."<br/>";
	echo $applyflag."<br/>";
	echo $together_name."<br/>";
	echo $together_birthday."<br/>";
	echo $together_relation."<br/>";
	echo $togerher_phone."<br/>";
	echo $chg_main_name."<br/>";
	echo $chg_main_birthday."<br/>";
	echo $chg_bank."<br/>";
	echo $chg_account."<br/>";
	echo $chg_sub_name."<br/>";
	echo $chg_sub_birthday."<br/>";
*/

	

?>	

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<meta http-equiv="X-Frame-Options" content="deny" />
		<link rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<title><?echo $g_site_title?></title>	
		<script language="javascript">
		function init() {
			var flag = '<?php echo $applyflag?>'
			if(flag =='2'){
				$(".togetherChk").css("display","none");
			}else if(flag =='1'){
				$(".mainSubChk").css("display","none");
			}	
		}
		
			
		function goBack() {
			document.frm_m.target = "frmain";
			document.frm_m.action="mianSubChgList.php";
			                        
			document.frm_m.submit();
		}
		</script>
	</head>
	<body  onLoad="init();">
		<form name="frm_m" method="post" action="">.
			<table cellspacing="0" cellpadding="10" class="TITLE">
				<tr>
					<td align="left"><B>공동 등록 및 주부 사업자 변경</b></td>
					<td align="right" width="300" align="center" bgcolor=silver>
						<input type="button" onClick="goIn();" value="수정" name="btn3">
						<input type="button" onClick="goBack();" value="목록" name="btn4">
				
						<INPUT type="hidden" name="page" value="<?echo $page?>">
					</td>
				</tr>
			</table>
			<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
				<tr>
					<td align='center'>
						<table border="0" cellspacing="1" cellpadding="2" class="IN">
							<tr>
								<th>
									회원성명 :
								</th>
								<td><?echo $member_name?></td>
							</tr>
							<tr>
								<th>
									회원번호 :
								</th>
								<td><?echo $member_id?></td>
							</tr>
							<!-- 
							<tr>
								<th>
									전화번호 :
								</th>
								<td><?echo $phoneNum?></td>
							</tr>
							 -->
							<tr>
								<th>
									생년월일 :
								</th>
								<td><?echo $birthDay?></td>
							</tr>
							<tr>
								<th>
									신청일 :
								</th>
								<td><?echo $applyDate?></td>
							</tr>
<!-- 공동등록  -->					
							<tr>
								<th>
									신청사항 :
								</th>
								<td>
									<?php 
										if($applyflag=='1'){
											echo "공동등록";
										}else if($applyflag=='2'){
											echo "주부변경";
										}else if($applyflag=='3'){
											echo "공동등록"."<br/>"."주부변경";
										}
									?>
								</td>
							</tr>
							<tr>
								<th>
									인증방법 :
								</th>
								<td>[모]<?php echo $mTime?></td>
							</tr>			
							<tr class="togetherChk">
								<th>
									공동등록 신청자 이름 :
								</th>
								<td><?php echo $together_name?></td>
							</tr>
							<tr class="togetherChk">
								<th>
									공동등록 신청자 생년월일 :
								</th>
								<td><?echo $together_birthday?></td>
							</tr>
							<tr class="togetherChk">
								<th>
									성별 :
								</th>
								<td>
									<?php 
										if($gender == 'm'){
											echo "남자";
										}else if($gender == 'w'){
											echo "여자";
										}
									?>
								</td>
							</tr>
							<tr class="togetherChk">
								<th>
									공동등록 신청자 전화번호 :
								</th>
								<td><?echo $togerher_phone?></td>
							</tr>
							<tr class="togetherChk">
								<th>
									신청자와 주사업자와 관계 :
								</th>
								<td><?echo $together_relation?></td>
							</tr>
							
							
							<tr class="togetherChk">
								<th>
									공동등록 첨부파일
								</th>
								<td>
									<img src="../mainsubchg/uploads/<?echo $photoFile?>" width="50%">
								</td>				
							</tr>
<!--                      주부 사업자 변경                                           -->				
							<tr class="mainSubChk">
								<th>
									주사업자 이름 :
								</th>
								<td><?echo $chg_main_name?></td>
							</tr>
							<tr class="mainSubChk">
								<th>
									주사업자 생년월일 :
								</th>
								<td><?echo $chg_main_birthday?></td>
							</tr>
							<tr class="mainSubChk">
								<th>
									부사업자 이름 :
								</th>
								<td><?echo $chg_sub_name?></td>
							</tr>
							<tr class="mainSubChk">
								<th>
									부사업자 생년월일 :
								</th>
								<td><?echo $chg_sub_birthday?></td>
							</tr>	
							<tr class="mainSubChk">
								<th>
									은행명 :
								</th>
								<td>
									<?	
										if ($chg_bank == "060") {
											echo "BOA은행";
										} else if ($chg_bank == "263") {
											echo "HMC 투자증권";
										} else if ($chg_bank == "054") {
											echo "HSBC은행";
										} else if ($chg_bank == "292") {
											echo "LIG투자증권";
										} else if ($chg_bank == "289") {
											echo "NH투자증권";
										} else if ($chg_bank == "023") {
											echo "SC제일은행";
										} else if ($chg_bank == "266") {
											echo "SK증권";
										} else if ($chg_bank == "039") {
											echo "경남은행";
										}  else if ($chg_bank == "034") {
											echo "광주은행";
										} else if ($chg_bank == "261") {
											echo "교보증권";
										} else if ($chg_bank == "004") {
											echo "국민은행";
										} else if ($chg_bank == "003") {
											echo "기업은행";
										} else if ($chg_bank == "011") {
											echo "농협중앙회";
										} else if ($chg_bank == "012") {
											echo "농협회원조합";
										} else if ($chg_bank == "031") {
											echo "대구은행";
										} else if ($chg_bank == "267") {
											echo "대신증권";
										} else if ($chg_bank == "238") {
											echo "대우증권";
										} else if ($chg_bank == "055") {
											echo "도이치은행";
										} else if ($chg_bank == "279") {
											echo "동부증권";
										} else if ($chg_bank == "209") {
											echo "유안타증권";
										} else if ($chg_bank == "287") {
											echo "메리츠종합금융증권";
										} else if ($chg_bank == "052") {
											echo "모건스탠리은행";
										} else if ($chg_bank == "230") {
											echo "미래에셋증권";
										} else if ($chg_bank == "059") {
											echo "미쓰비시도쿄UFJ은행";
										} else if ($chg_bank == "058") {
											echo "미즈호코퍼레이트은행";
										} else if ($chg_bank == "290") {
											echo "부국증권";
										} else if ($chg_bank == "032") {
											echo "부산은행";
										} else if ($chg_bank == "002") {
											echo "산업은행";
										} else if ($chg_bank == "240") {
											echo "삼성증권";
										} else if ($chg_bank == "050") {
											echo "상호저축은행";
										} else if ($chg_bank == "045") {
											echo "새마을금고연합회";
										} else if ($chg_bank == "268") {
											echo "솔로몬투자증권";
										} else if ($chg_bank == "008") {
											echo "수출입은행";
										} else if ($chg_bank == "007") {
											echo "수협중앙회";
										} else if ($chg_bank == "291") {
											echo "신영증권";
										}  else if ($chg_bank == "278") {
											echo "신한금융투자";
										} else if ($chg_bank == "088") {
											echo "신한은행";
										} else if ($chg_bank == "048") {
											echo "신협중앙회";
										} else if ($chg_bank == "056") {
											echo "알비에스은행";
										} else if ($chg_bank == "005") {
											echo "외환은행";
										} else if ($chg_bank == "020") {
											echo "우리은행";
										} else if ($chg_bank == "247") {
											echo "우리투자증권";
										} else if ($chg_bank == "071") {
											echo "우체국";
										} else if ($chg_bank == "280") {
											echo "유진투자증권";
										} else if ($chg_bank == "265") {
											echo "이트레이드증권";
										} else if ($chg_bank == "037") {
											echo "전북은행";
										} else if ($chg_bank == "057") {
											echo "제이피모간체이스은행";
										} else if ($chg_bank == "035") {
											echo "제주은행";
										} else if ($chg_bank == "090") {
											echo "카카오뱅크";
										} else if ($chg_bank == "264") {
											echo "키움증권";
										} else if ($chg_bank == "270") {
											echo "하나대투증권";
										} else if ($chg_bank == "081") {
											echo "하나은행";
										} else if ($chg_bank == "262") {
											echo "하이투자증권";
										} else if ($chg_bank == "027") {
											echo "한국씨티은행";
										}  else if ($chg_bank == "243") {
											echo "한국투자증권";
										} else if ($chg_bank == "269") {
											echo "한화증권";
										} else if ($chg_bank == "218") {
											echo "현대증권";
										}  
										
									?>	
								</td>
							</tr>
							<tr class="mainSubChk">
								<th>
									계좌번호 :
								</th>
								<td><?echo $chg_account?></td>
							</tr>
							<tr class="mainSubChk">
								<th>
									주부사업자 변경 동의 여부  :
								</th>
								<td>Y</td>
							</tr>
							<!--  					
							<tr class="mainSubChk">
								<th>
									부부 사업자 변경 첨부파일
								</th>
								<td>
									<img src="../mainsubchg/uploads/<?echo $mainSubFile?>" width="50%">
								</td>				
							</tr>
							-->
						</table>
						<table border="0" width='100%' cellspacing="1" cellpadding="1">
							<tr>
								<th bgcolor="#DDDDDD" width="137">
									신청 처리 정보:
								</th>
								<td bgcolor="#EEEEEE">
									<table border=0>
										<tr>
											<td width=200><b>출력일</b> : <?echo $print_sw?></td>
											<td width=150><b>출력자</b> : <?echo $print_ma?></td>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td><b>완료일</b> : <?echo $confirm_sw?></td>
											<td><b>완료자</b> : <?echo $confirm_ma?></td>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td><b>보류일</b> : <?echo $date_sw?></td>
											<td><b>보류자</b> : <?echo $wait_ma?></td>
											<td><input type="button" value="보류 내용 입력" onClick="goMemo('w');"></td>
										</tr>
										<tr>
											<td><b>거부일</b> : <?echo $date_sr?></td>
											<td><b>거부자</b> : <?echo $reject_ma?></td>
											<td><input type="button" value="거부 내용 입력" onClick="goMemo('r');"></td>
										</tr>
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
										<option value = "1" <?if ($reg_status == "1") echo "selected";?>>신청 (본인확인)</option>
										<option value = "2" <?if ($reg_status == "2") echo "selected";?>>신청</option>
										<option value = "3" <?if ($reg_status == "3") echo "selected";?>>출력</option>
										<option value = "4" <?if ($reg_status == "4") echo "selected";?>>완료</option>
										<option value = "8" <?if ($reg_status == "8") echo "selected";?>>보류</option>
										<option value = "9" <?if ($reg_status == "9") echo "selected";?>>거부</option>
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
									<?echo nl2br($memo)?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>

		<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

	</body>
	
</html>		