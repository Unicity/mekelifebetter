<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: menu_view.php
	// 	Description : 메뉴 보기 화면
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";

	$group_id = str_quote_smart(trim($group_id));

	//========================================================
	// 그룹 아이디로 메뉴 아이템 구하기
	//========================================================
	
	$query = "select group_item, group_name from tb_admin_group where group_id = '$group_id' ";

	$result = mysql_query($query);
	$list = mysql_fetch_array($result);

	$group_item = $list[group_item];
	$group_name = $list[group_name];
	
	//========================================================
	// 메뉴 아이템이 있으면 권한 있는 메뉴 가져오기
	//========================================================
	
	if (trim($group_item) <> "" ) {
	
		$query = "select menu_id from tb_admin_menu where menu_id in ($group_item) order by big_menu";
		$result = mysql_query($query);

		while($row = mysql_fetch_array($result)) {

			$menu_id1[] = $row[menu_id];
		
		}		
	}
			
	$query = "select m.menu_id, b.big_menu_name, m.small_menu from tb_admin_menu m, tb_big_menu b where b.big_menu = m.big_menu order by b.big_menu";
	$result = mysql_query($query);
	
   	//========================================================
   	// 메뉴 배열
   	//========================================================
	
	while($row = mysql_fetch_array($result)) {

		$menu_id2[] = $row[menu_id];
		$big_menu_name[] = $row[big_menu_name];
		$small_menu[] = $row[small_menu];
				
	}		
	
?>
<HTML>
<HEAD>
<TITLE><?echo $g_site_title?></TITLE>
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<script src=js/script.js language=javascript></script>
<script language=javascript>

	function LeftToRight()
	{
	   var objSel, objSel

		objLeft = document.form1.sel_left
		objRight = document.form1.sel_right
		
		for(var i = 0; i < objLeft.length; i++)
		  if(objLeft.options[i].selected)
			  objRight.options[objRight.length] = new Option(objLeft.options[i].text,  objLeft.options[i].value);
	   
		for(var i = 0; i < objRight.length; i++)
      {
			for(var j = 0; j < objLeft.length; j++)
			{
			   if(objLeft.options[j].selected)
				{
				   objLeft.options[j] = null;
				   break;
				}	
			}
		} 
	}
	
	function RightToLeft()
	{
	   var objSel, objSel
		objLeft = document.form1.sel_left
		objRight = document.form1.sel_right
		
		for(var i = 0; i < objRight.length; i++)
		  if(objRight.options[i].selected)
			  objLeft.options[objLeft.length] = new Option(objRight.options[i].text,  objRight.options[i].value);

		for(var i = 0; i < objLeft.length; i++)
      {
			for(var j = 0; j < objRight.length; j++)
			{
			   if(objRight.options[j].selected)
				{
					objRight.options[j] = null;
				   break;
				}	
			}
		} 
	}
	
	function goBack() {
		document.form1.target = "frmain";
		document.form1.action="admin_group_list.php";
		document.form1.submit();
	}

	function goIn() {
	
		var menu_items = ""; 
		
	    for(var i = 0; i < document.form1.sel_right.length; i++) {
		   	document.form1.sel_right.options[i].selected = true; 
			if (menu_items == "") {
				menu_items = document.form1.sel_right.options[i].value;	
			} else {
				menu_items = menu_items+","+document.form1.sel_right.options[i].value;	
			}
		}	
		document.form1.menu_item.value = menu_items;					
		document.form1.target = "frhidden";
		document.form1.action = "menu_group_db.php";
		document.form1.submit();
		
	}

</SCRIPT>
</HEAD>
<BODY>

<?php include "common_load.php" ?>

<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>메뉴 권한 설정</B></TD>
	<TD align="right" width="300" align="center" silver>
		<input type="button" onClick="goIn();" value="설정" name="btn3">
		<input type="button" onClick="goBack();" value="목록" name="btn4">
	</TD>
</TR>
</TABLE>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' '#FFFFFF' bordercolor='#FFFFFF' bgcolor="#EEEEEE">
<tr>
	<td align='center'>
<TABLE border="0" cellspacing="1" cellpadding="2" width='70%'>
<tr>
	<td align=center>
	<form name=form1 method=post>

		<input type="hidden" name="test" value="<?=$_GET[test]?>">
		<input type="hidden" name="old_item" value="<?=$group_item?>">

		<table  border=0 cellpadding=0 cellspacing=0 width="90%">
			<tr>
		   		<td colspan=3  align=center><br>관리자 그룹 이름 : <font color="red"><b><?echo $group_name?></b></font><br><br></td>
			</tr>
			<tr>
		   		<td width=40% align=center><font color=>권한없음</font></td>
		   		<td width=20% align=center>&nbsp;</td>
				<td width=40% align=center><font color=>권한있음</font></td>
			</tr>
			<tr>
         		<td width=45%  align=right>
					<select name="sel_left" size="17" multiple>
<?
	
	
   	//========================================================
   	// 초기 설정이 아닌 경우와 초기 설정인 경우 다름
   	//========================================================
	
	if (trim($group_item) <> "") {
	
		$isExist = 0;
	
		for ($i = 0; $i < sizeof($menu_id2); $i++) {
			for ($j = 0; $j < sizeof($menu_id1); $j++) {
				if (($menu_id2[$i]) == ($menu_id1[$j])) {
				 	$isExist = $isExist+1;
				}
			}
		
			if ($isExist == 0) {
?>
			<option value="<?echo $menu_id2[$i]?>"><?echo $big_menu_name[$i]?> : <?echo $small_menu[$i]?></option>
<?
			}		  

			$isExist = 0;
		}

	} else {
	
		for ($i = 0; $i < sizeof($menu_id2); $i++) {
?>
			<option value="<?echo $menu_id2[$i]?>"><?echo $big_menu_name[$i]?> : <?echo $small_menu[$i]?></option>
<?
		}

	}
?>
					</select>
				</td>
				<td width=10%  align=center>
			 		<input type=button name=LR value=' -&gt; ' onClick="javascript:LeftToRight()"><p>
         	 		<input type=button name=RL value=' &lt;- '  onClick="javascript:RightToLeft()">
				</td>
         		<td width=45% >
		   			<select name="sel_right" size="17" multiple>
<?
   	//========================================================
   	// 초기 설정이 아닌 경우와 초기 설정인 경우 다름
   	//========================================================
	
	if (trim($group_item) <> "") {

		for ($i = 0; $i < sizeof($menu_id2); $i++) {
			for ($j = 0; $j < sizeof($menu_id1); $j++) {
				if (($menu_id2[$i]) == ($menu_id1[$j])) {

?>
			<option value="<?echo $menu_id2[$i]?>"><?echo $big_menu_name[$i]?> : <?echo $small_menu[$i]?></option>
<?
				}
			}
		}
	}
?>
					</select>  
				</td>
			</tr>
		</table> <br><br>
		</td>	   
	</tr>
<input type="hidden" name="group_id" value="<?echo $group_id?>">
<input type="hidden" name="old_menu_item" value="<?=implode(',', $menu_id1)?>">
<input type="hidden" name="menu_item" value="">
	</form>
</table>
	</td>
</tr>
</table>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
	mysql_close($connect);
?>