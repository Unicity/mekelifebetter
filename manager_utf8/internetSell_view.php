<?php
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
	$numVal			= str_quote_smart(trim($numVal));
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
	
	echo $numVal; 
	
	
	$query = "select * from internet_sales_warning where no = '".$numVal."'";
	
	#	echo $query;
	
	$result = mysql_query($query);
	$list = mysql_fetch_array($result);
	
	
	$no = $list[no];
	$member_no = $list[member_no];
	$member_name = $list[member_name];
	$url = $list[url];
	$url1 = $list[url1];
	$url2 = $list[url2];
	$url3 = $list[url3];
	$url4 = $list[url4];
	$dsc_sel = $list[dsc_sel];
	$applyDate = $list[applyDate];
	$reg_status = $list[reg_status];
	
	
	if ($dsc_sel == "0") {
		$dsc_sel = "서울";
	} else if ($dsc_sel == "1") {
		$dsc_sel ="인천DSC";
	} else if ($dsc_sel == "2") {
		$dsc_sel ="안산DSC";
	} else if ($dsc_sel == "3") {
		$dsc_sel ="대전DSC";
	} else if ($dsc_sel == "4") {
		$dsc_sel ="광주DSC";
	} else if ($dsc_sel == "5") {
		$dsc_sel ="원주DSC";
	} else if ($dsc_sel == "6") {
		$dsc_sel ="대구DSC";
	} else if ($dsc_sel == "7") {
		$dsc_sel ="부산DSC";
	}
	
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<meta http-equiv="X-Frame-Options" content="deny" />
		<link rel="stylesheet" href="inc/admin.css" type="text/css">
		<title>인터넷 판매 제보</title>
		<script language="javascript">

			function goback() {
				document.frm_m.target = "frmain";
				document.frm_m.action="internetSell.php";
				                        
				document.frm_m.submit();
			}		

			function goMemo(numberVal) {
				
				var url = "interSellMemo.php?member_no="+numberVal;
				NewWindow(url, "memo_page", '600', '250', "no");
			}

			function NewWindow(mypage, myname, w, h, scroll) {
				var winl = (screen.width - w) / 2;
				var wint = (screen.height - h) / 2;
				winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
				win = window.open(mypage, myname, winprops)
				if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
			}
		</script>
		
	</head>
	<body  onLoad="init();">
	
<?php include "common_load.php" ?>

		<form name="frm_m" method="post">
			<table cellspacing="0" cellpadding="10" class="title">
				<tr>
					<td align="left"><b>인터넷 판매 제보</b></td>
					<td align="right" width="300" align="center" bgcolor=silver>
						<input type="button" onclick="goback();" value="목록" name="btn4">
				
						<input type="hidden" name="page" value="<?echo $page?>">
						
						
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
								<td><?echo $member_no?></td>
							</tr>
							<tr>
								<th>
									URL :
								</th>
								<td><?echo $url?><br/><?echo $url1?><br/><?echo $url2?><br/><?echo $url3?><br/><?echo $url4?><br/></td>
							</tr>
							<tr>
								<th>
									DSC :
								</th>
								<td><?echo $dsc_sel?></td>
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
											<td width=200><b>출력일</b> : <?echo $date_sp?></td>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td><b>완료일</b> : <?echo $date_sc?></td>
			
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td><b>보류일</b> : <?echo $date_sw?></td>
								
											<td><input type="button" value="보류 내용 입력" onClick="goMemo('<?echo $numVal?>');"></td>
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
										<option value = "5" <?if ($reg_status == "5") echo "selected";?>>처리중</option>
										<option value = "4" <?if ($reg_status == "4") echo "selected";?>>완료</option>
										<option value = "8" <?if ($reg_status == "8") echo "selected";?>>보류</option>
			
									</select>&nbsp; <!--최고 관리자로 접속하셨을 경우만 변경 가능 합니다.-->
								</td>
							</tr>
							<?	#} else { ?>
								<!--<input type="hidden" name="reg_status" value="<?echo $reg_status?>">-->
							<?	#} ?>
							<tr>
								<th bgcolor="#DDDDDD">
									보류 사유:
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