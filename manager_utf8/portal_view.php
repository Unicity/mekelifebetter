<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";
	include "./inc/common_function.php";

	
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
	
	


	$query = "select * from tb_portal where id = '".$idValue."'";
	
#	echo $query;	

	$result = mysql_query($query);
	$list = mysql_fetch_array($result);
	
	$member_id = $list[member_id];
	$member_name = $list[member_name];
	$birthDay = $list[birthDay];
	$applyDate = $list[applyDate];
	$photoFile = $list[photo_file];
	$photoFileBack = $list[photo_file_back];
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
	$phone_number = $list[phone_number];
	
	$phonelength=strlen($phone_number);
	$str=$phone_number;
	if($phonelength == '11'){
		$phoneNum=substr($str,0,3)."-".substr($str,3,4)."-".substr($str,7,4);
	}else{
		$phoneNum=substr($str,0,2)."-".substr($str,2,4)."-".substr($str,6,4);
	}
	logging($s_adm_id,'view portal member '.$member_id);
?>		
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta http-equiv="X-Frame-Options" content="deny" />
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>

<SCRIPT language="javascript">

	function init() {
		document.frm_m.hpho1.value = "<?echo $hpho1?>";
		document.frm_m.pho1.value = "<?echo $pho1?>";
<? 	if ($member_kind == "D") {?>
		document.frm_m.account_bank.value = "<?echo $account_bank?>";
<?	}?>
	}

	

	function goMemo(kind) {

		var url = "portal_memo.php?flag_id="+document.frm_m.flag_id.value+"&member_no="+document.frm_m.member_id.value + "&memo_kind="+kind;
		NewWindow(url, "memo_page", '600', '250', "no");
	}


	function NewWindow(mypage, myname, w, h, scroll) {
		var winl = (screen.width - w) / 2;
		var wint = (screen.height - h) / 2;
		winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
		win = window.open(mypage, myname, winprops)
		if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
	}

	function goBack() {
		document.frm_m.target = "frmain";
		document.frm_m.action="portal_admin.php";
		                        
		document.frm_m.submit();
	}

	function goIn() {
		if(confirm("수정 하시겠습니까?")){   
			
			document.frm_m.action = "portal_confirm.php";
			document.frm_m.submit();
		}
	}


</SCRIPT>
</HEAD>
<body  onLoad="init();">
<form name="frm_m" method="post" action="dup_user_db.php">

<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>디스트리뷰터쉽 해지 관리</B></TD>
	<TD align="right" width="300" align="center" bgcolor=silver>
			<input type="button" onClick="goIn();" value="수정" name="btn3">
		<input type="button" onClick="goBack();" value="목록" name="btn4">

		<INPUT type="hidden" name="page" value="<?echo $page?>">
	</TD>
</TR>
</TABLE>
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
			<tr>
				<th>
					전화번호 :
				</th>
				<td><?echo $phoneNum?></td>
			</tr>
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
			<tr>
				<th>
					앞면 첨부파일
				</th>
				<td>
					<img src="../pmbr/uploads/<?echo $photoFile?>" width="50%">
				</td>				
			</tr>
			<tr>
				<th>
					뒷면 첨부파일
				</th>
				<td>
					<img src="../pmbr/uploads/<?echo $photoFileBack?>" width="50%">
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
<input type="hidden" name="mode" value="mod">
<input type="hidden" name="zip" value="">
<input type="hidden" name="del_zip" value="">
<input type="hidden" name="email" value="">
<input type="hidden" name="interest" value="">
<input type="hidden" name="member_id" value="<?echo $member_id?>">
<input type="hidden" name="member_kind" value="<?echo $member_kind?>">
<input type="hidden" name="idxfield" value="<?echo $idxfield?>">
<input type="hidden" name="idx" value="<?echo $idx?>">
<input type="hidden" name="qry_str" value="<?echo $qry_str?>">
<input type="hidden" name="con_sort" value="<?echo $con_sort?>">
<input type="hidden" name="con_order" value="<?echo $con_order?>">
<input type="hidden" name="r_status" value="<?echo $r_status?>">
<input type="hidden" name="r_memberkind" value="<?echo $r_memberkind?>">
<input type="hidden" name="r_join_kind" value="<?echo $r_join_kind?>">
<input type="hidden" name="r_active_kind" value="<?echo $r_active_kind?>">
<input type="hidden" name="r_couple" value="<?echo $r_couple?>">
<input type="hidden" name="from_date" value="<?echo $from_date?>">
<input type="hidden" name="to_date" value="<?echo $to_date?>">
<input type="hidden" name="flag_id" value="<?php echo $idValue?>">
<input type="hidden" name="member_no" value="<?php echo $id?>">
</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
mysql_close($connect);
?>