<?php
session_start();
?>
<?php
include "admin_session_check.inc";
include "./inc/global_init.inc";
include "../dbconn_utf8.inc";

//$member_no	= str_quote_smart(trim($member_no));
$memo_kind	= str_quote_smart(trim($memo_kind));
$mode				= str_quote_smart(trim($mode));
$memo				= str_quote_smart(trim($memo));
$no				= str_quote_smart(trim($no));

    if ($mode == "add") {
        if ($memo_kind == "r") {
        $query = "update tb_change_sponsor set
		          memo = '$memo',
		          reg_status = '4',
		          reject_date = now(),
		          reject_ma = '$s_adm_name'
		          where no = '$no'";
        }else{
            $query = "update tb_change_sponsor set
		          memo = '$memo',
		          reg_status = '8',
		      	wait_date = now(),
						wait_ma = '$s_adm_name'
		          where no = '$no'";
        }
        mysql_query($query) or die("Query Error");
        
  
    }
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-Frame-Options" content="deny" />
		<link rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
		<title>사유 입력</TITLE>
	</head>
	<?	if ($mode == "add") { ?>
	<body onload="init();">
	<?	} else {?>
	<body>
	<?	}?>

	
<?php include "common_load.php" ?>


		<table border=0 width=100%>
			<tr>
				<td align="center">
					<table cellspacing="0" cellpadding="10" class="TITLE">
                        <tr>
                        	<TD align="left"><B>신청 거부 사유 입력</B></TD>
                        </tr>
					</table>
					<form name="frmSearch" method="post" action="changeSponsor_memo.php">
                        <input type="hidden" name="no" value="<?echo $no?>">
                        <input type="hidden" name="memo_kind" value="<?echo $memo_kind?>">
                        <input type="hidden" name="mode" value="">
						<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
							<tr>
								<td align='center'>
									<table border="0" cellspacing="1" cellpadding="2" class="IN">
										<tr>
											<th>사유 : </th>
											<td>
												<textarea name="memo" cols="60" rows="6"><?echo $memo?></textarea>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
                        <br>
                        <br>
	
						<table cellspacing="0" cellpadding="10" class="title">
							<tr>
								<td align="left">&nbsp;</td>
								<td align="right" width="600" align="center" bgcolor=silver>
									<input type="button" value="자료 입력" onclick="goIn();">	
									<input type="button" value="닫 기" onclick="goClose();">	
								</td>
							</tr>
						</table>
					</form>
				</td>
			</tr>
		</table>		
	</body>
	 <script language="javascript">
            
            	function init() {
            		alert("입력 되었습니다.");
            	}
            
            	function goIn() {
    
            		if(document.frmSearch.memo.value == "") {
            			alert("사유를 입력하셔야 합니다.");
            			document.frmSearch.memo.focus();
            		    return;			
            	    }
            
            		document.frmSearch.mode.value = "add";
            		document.frmSearch.submit();
            	}
            
            	function goClose() {
            		//opener.reload_user();
            		self.close();
            	}
            
			</script>

	<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</html>
<?
	mysql_close($connect);
?>