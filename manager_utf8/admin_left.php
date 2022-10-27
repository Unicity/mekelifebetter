<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: admin_left.php
	// 	Description : 메뉴 화면
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	
	$query = "select group_item from tb_admin_group where group_id = '$s_flag'";

	$result = mysql_query($query);
	$list = mysql_fetch_array($result);
	$group_item = $list[group_item];
	
	$query = "select menu_id, big_menu, small_menu, menu_url from tb_admin_menu where menu_id in (".$group_item.") order by big_menu, menu_id";

	$result = mysql_query($query);

	while($row = mysql_fetch_array($result)) {

		$menu_id[] = $row[menu_id];
		$big_menu[] = $row[big_menu];
		$small_menu[] = $row[small_menu];
		$menu_url[] = $row[menu_url];
		
	}

	mysql_close($connect);
	
?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- <meta http-equiv="X-Frame-Options" content="deny" /> -->
<TITLE><?echo $g_site_title?></TITLE>
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<SCRIPT language="javascript">

	var old='';
	function menu(name)	{
		 
		submenu=eval("submenu_"+name+".style");
						
		if(submenu.display != 'block') {
				
			submenu.display='block';
						
		} else {	
			submenu.display='none';
		}
	}

	function goPage(strURL) {
		top.frmain.location=strURL;
	}




</SCRIPT>
</HEAD>
<BODY class="LFRAME">
<BASE target="frmain">
<!--
<table width=150 border=0>
<tr>
	<td width=80% align="center">
		<table width=135 height="25" border=0 bgcolor="gray">
			<tr><td align="center">
				<a href="http://www.be-kr.co.kr" target="_new"><font color="#FFFFFF"><b>홈페이지</b></font></a>
			</td></tr>
		</table>
	</td>
</tr>
</table>
-->
<TABLE width=150 cellpadding="0" cellspacing="0">
	<TR valign="top">
		<td height="14">
		</td>
	</TR>
	<TR valign="top">
		<TD align=right width=150 bgcolor="#FFFFFF">
	<?
		$isExist = 0;
	
		for ($i = 0; $i < sizeof($menu_id); $i++) {
				
			if ($big_menu[$i] == "0") {
				
				if ($isExist == 0) {
	?>
		<p height="30" align="center"><b><a onclick="menu(0);" style="CURSOR:hand"><font color="#000000">회원관리</font></a></b><br>
	<span id=submenu_0 style="DISPLAY: block;"> 
		<table cellpadding=0 cellspacing=0 class=tblfnt border=0>
		<tr><td> 
	<?				
					$isExist = 1;
				}
	?>				
		<input type=button value="<?echo $small_menu[$i]?>" width=150 height=30 onclick="goPage('<?echo $menu_url[$i]?>')" style="cursor:pointer"></br>
	<?
			}
		}
		if ($isExist == 1) {
	?>
	
		</td>
		</tr>
		</table>
	</span>
	
	<?
		}
		
		$isExist = 0;
	
		for ($i = 0; $i < sizeof($menu_id); $i++) {
				
			if ($big_menu[$i] == "7") {
				
				if ($isExist == 0) {
	?>
		<p height="30" align="center"><b><a onclick="menu(7);" style="CURSOR:hand"><font color="#000000">수당관리</font></a></b><br>
	<span id=submenu_7 style="DISPLAY: block;"> 
		<table cellpadding=0 cellspacing=0 class=tblfnt border=0>
		<tr><td> 
	<?				
					$isExist = 1;
				}
	?>				
		<input type=button value="<?echo $small_menu[$i]?>" width=150 height=30 onclick="goPage('<?echo $menu_url[$i]?>')" style="cursor:pointer"></br>
	<?
			}
		}
	
		if ($isExist == 1) {
	?>
		</td>
		</tr>
		</table>
	</span>
	
	<?
		}
		
		$isExist = 0;
	
		for ($i = 0; $i < sizeof($menu_id); $i++) {
				
			if ($big_menu[$i] == "8") {
				
				if ($isExist == 0) {
	?>
		<p height="30" align="center"><b><a onclick="menu(8);" style="CURSOR:hand"><font color="#000000">상담관리</font></a></b><br>
	<span id=submenu_8 style="DISPLAY: block;"> 
		<table cellpadding=0 cellspacing=0 class=tblfnt border=0>
		<tr><td> 
	<?				
					$isExist = 1;
				}
	?>				
		<input type=button value="<?echo $small_menu[$i]?>" width=150 height=30 onclick="goPage('<?echo $menu_url[$i]?>')" style="cursor:pointer"></br>
	<?
			}
		}
	
		if ($isExist == 1) {
	?>
		</td>
		</tr>
		</table>
	</span>
	<?
		}
	 
		$isExist = 0;
	
		for ($i = 0; $i < sizeof($menu_id); $i++) {
				
			if ($big_menu[$i] == "2") {
				
				if ($isExist == 0) {
	?>
		<p height="30" align="center"><b><a onclick="menu(2);" style="CURSOR:hand"><font color="#000000">주문관리</font></a></b><br>
	<span id=submenu_2 style="DISPLAY: block;"> 
		<table cellpadding=0 cellspacing=0 class=tblfnt border=0>
		<tr><td> 
	<?				
					$isExist = 1;
				}
	?>				
		<input type=button value="<?echo $small_menu[$i]?>" width=150 height=30 onclick="goPage('<?echo $menu_url[$i]?>')" style="cursor:pointer"></br>
	<?
			}
		}  
	
		if ($isExist == 1) {
	?>
		</td>
		</tr>
		</table>
	</span>
	<?
		}
	
		$isExist = 0;
	
		for ($i = 0; $i < sizeof($menu_id); $i++) {
				
			if ($big_menu[$i] == "3") {
				
				if ($isExist == 0) {
	?>
		<p height="30" align="center"><b><a onclick="menu(3);" style="CURSOR:hand"><font color="#000000">반품관리</font></a></b><br>
	<span id=submenu_3 style="DISPLAY: block;"> 
		<table cellpadding=0 cellspacing=0 class=tblfnt border=0>
		<tr><td> 
	<?
					$isExist = 1;
				}		
	?>				
		<input type=button value="<?echo $small_menu[$i]?>" width=150 height=30 onclick="goPage('<?echo $menu_url[$i]?>')" style="cursor:pointer"></br>
	<?
			}
		}
	
		if ($isExist == 1) {
	?>
		</td>
		</tr>
		</table>
	</span>
	<?
		}
		 
		$isExist = 0;
	
		for ($i = 0; $i < sizeof($menu_id); $i++) {
				
			if ($big_menu[$i] == "4") {
				
				if ($isExist == 0) {
	?>
		<p height="30" align="center"><b><a onclick="menu(4);" style="CURSOR:hand"><font color="#000000">관리자권한관리</font></a></b><br>
	<span id=submenu_4 style="DISPLAY: block;"> 
		<table cellpadding=0 cellspacing=0 class=tblfnt border=0>
		<tr><td> 
	<?
					$isExist = 1;
				}		
	?>				
		<input type=button value="<?echo $small_menu[$i]?>" width=150 height=30 onclick="goPage('<?echo $menu_url[$i]?>')" style="cursor:pointer"></br>
	<?
			}
		}
	
		if ($isExist == 1) {
	?>
		</td>
		</tr>
		</table>
	</span>
	<?
		}
	
		$isExist = 0;
	
		for ($i = 0; $i < sizeof($menu_id); $i++) {
				
			if ($big_menu[$i] == "5") {
				
				if ($isExist == 0) {
	?>
		<p height="30" align="center"><b><a onclick="menu(5);" style="CURSOR:hand"><font color="#000000">시스템관리</font></a></b><br>
	<span id=submenu_5 style="DISPLAY: block;"> 
		<table cellpadding=0 cellspacing=0 class=tblfnt border=0>
		<tr><td> 
	<?
					$isExist = 1;
				}		
	?>				
		<input type=button value="<?echo $small_menu[$i]?>" width=150 height=30 onclick="goPage('<?echo $menu_url[$i]?>')" style="cursor:pointer"></br>
	<?
			}
		}


		if ($isExist == 1) {
	?>
		</td>
		</tr>
		</table>
	</span>
	<?
		}
	?>
	<p height="30" align="center"><b><a onclick="menu(9);" style="CURSOR:hand"><font color="#000000">회원카드온라인발급</font></a></b><br>
	<span id=submenu_9 style="DISPLAY: block;"> 
		<table cellpadding=0 cellspacing=0 class=tblfnt border=0>
			<tr>
				<td> 
					<input type=button value="회원등록" width=150 height=30 onclick="goPage('/manager_utf8/user_off_list.php')" style="cursor:pointer"></br>
					<input type=button value="메일링관리" width=150 height=30 onclick="goPage('/manager_utf8/user_off_mailing_list.php')" style="cursor:pointer">
				</td>
			</tr>
		</table>
	</span>
		</TD>
	</TR>
	<tr><td height="10px;"></td></tr>
	<tr>
		<td align="center">
			<p align="center" style="margin-bottom: 0px;"><b><font color="#000000">직원 이용 사이트</font></b></p>
	
			<select onChange="if(this.selectedIndex) { this.blur(); window.open(options[selectedIndex].value); }"  style="width:80%; height:10%; overflow:auto;text-align: center;">
				<option value="">선택</option>
				<option value="https://unicitykorea.co.kr/totalAdmin/">오토쉽 관리자</option>
				<option value="https://ushop-admin-dev-kr.unicity.com/#/login">마이비즈 관리자</option>
				<option value="https://www.makelifebetter.co.kr/account_chk_new/index.php">은행계좌 확인</option>
				<option value="https://www.makelifebetter.co.kr/kms/">KMS</option>
				<option value="https://www.makelifebetter.co.kr/etc/hy.html">효성 자동출금 확인</option>
				<option value="https://www.makelifebetter.co.kr/etc/receipt.html">효성 현금 영수증 신청</option>										
			</select>
			
		</td>
	</tr>
	<tr><td height="10px;"></td></tr>
	<!--
	<tr>
		<td align="center">
			<p align="center" style="margin-bottom: 0px;"><b><font color="#000000">회원 이용 사이트</font></b></p>
			
			<select onChange="if(this.selectedIndex) { this.blur(); window.open(options[selectedIndex].value); }" style="width:80%; height:10%; overflow:auto;text-align: center;">
				<option value="">선택</option>
				<option value="https://korea.unicity.com/">홈페이지</option>
				<option value="https://member-kr.unicity.com">FO 사이트</option>
				<option value="https://unicitykorea.co.kr">오토쉽</option>
				<option value="https://www.makelifebetter.co.kr/ssnPage/ssnReceiver.php">세금신고</option>
				<option value="https://www.makelifebetter.co.kr/distributorshipCancel/certification.php">회원해지</option>	
				<option value="https://www.makelifebetter.co.kr/pmbr/apply.php">포털 사이트</option>										
			</select>
			

		</td>
	</tr>
	-->
</TABLE>
<!--<a href="#d" onclick="goPage('r_n_d_news_list.php?BoardId=R1');">.</a><br>
<a href="#d" onclick="goPage('edu_cal_list.php');">.</a>

<a onclick="menu(1);" style="CURSOR:hand" >aaa</a>
<span id=submenu_1 style="DISPLAY: none;"> 
	<table cellpadding=0 cellspacing=0 class=tblfnt border=0>
	<tr> 
		<td height="1" colspan="3" ></td>
	</tr>
	<tr>
	<td height="1" bgcolor="#FFEAE8" class="cust_faq01" style="padding-left:10px;padding-right:10px;padding-top:10px;padding-bottom:10px;">
	</td>
	</tr>
	<tr> 
		<td height="1" colspan="3"></td>
	</tr>
	</table>
</span>

<a href="new_member_input_file.php">.</a>
<br>
<a href="new_member_list.php?member_kind=C">.</a>
<br>-->
<!-- 아래 점 없앰.. 왜 만든지 모르겠음 -->
<!--<a href="#d" onclick="goPage('new_member_activity_file.php')">.</a> -->

<br>
<br>
</BODY>
</HTML>