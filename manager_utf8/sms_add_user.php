<?
	//include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";

	$seq_no = trim($seq_no);
	$mode = trim($mode);

	$path = "../member/sms_file";

	$mode					= str_quote_smart(trim($mode));
	$qry_str			= str_quote_smart(trim($qry_str));
	$idxfield			= str_quote_smart(trim($idxfield));
	$page					= str_quote_smart(trim($page));
	$seq_no				= str_quote_smart(trim($seq_no));
	$new_file_no	= str_quote_smart(trim($new_file_no));


	if ($mode == "IS") {
		
		$query = "delete from tb_sms_mem where seq_no = '".$seq_no."' ";
		mysql_query($query) or die("Query Error".$query);

		$query = "insert into tb_sms_mem (seq_no, ba_number, mem_nm, htel) select '".$seq_no."', ba_number, mem_nm, htel from tb_sms_temp_file where file_nm = '".$new_file_no."' ";
		
		mysql_query($query) or die("Query Error".$query);

?>
<script>
	opener.re_load();
	self.close();
</script>
<?
		mysql_close($connect);
		exit;
	}


	if ($mode == "I") {
		if ($file_nm != "") {
			$file_ext = substr(strrchr($file_nm_name, "."), 1);
			
			//echo $file_ext;

			if ($file_ext != "txt" && $file_ext != "TXT") {
				echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
				<script>
					window.alert('확장자가 txt, TXT외에는 업로드할수가 없습니다.');
					history.go(-1);
					</script>";
					mysql_close($connect);
				exit;
			}
		

			$new_file = $seq_no.".".$file_ext;	
			$file_strtmp = $path."/".$new_file;	
			
	
			//if (!copy($file_nm, $file_strtmp)) {
			if(!move_uploaded_file($_FILES['file_nm']['tmp_name'], $file_strtmp)){				
				echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
				echo "<script>
					window.alert('$cfile_name 를 업로드할 수 없습니다.');
					history.go(-1);
					</script>";
					mysql_close($connect);
				exit;
			}
			exec("chmod 0777 ".$file_strtmp);


			$query = "delete from tb_sms_temp_file where file_nm = '$new_file' ";
			mysql_query($query) or die("Query Error".$query);
			
			$number_id = 0;
			$fo = fopen($path."/".$new_file, "r");
			while($str = fgets($fo, 3000)) {
				
				$str = iconv("EUC-KR","UTF-8",$str);
				$a_str = explode("	",$str);

				$name = $a_str[0]; 
				$number = $a_str[1]; 
				$hphone = $a_str[2]; 

				$name = trim($name);
				$number = trim($number);
				$hphone = trim($hphone);
				$hphone = str_replace("-","",$hphone);
				
				if ($number_id > 0) {
					$query = "insert into tb_sms_temp_file (file_nm, ba_number, htel, mem_nm) values 
							('".$new_file."', '".$number."', '".$hphone."', '".$name."')";
					mysql_query($query) or die("Query Error".$query);
				}
				$number_id++;
			}
		}

		$query = "select * from tb_sms_temp_file where file_nm = '$new_file'";
		
		//echo $query;
	}

	if ($mode != "I") {
		$query = "select * from tb_sms_mem where seq_no = '$seq_no'";
	}

	$result = mysql_query($query);
	$total  = mysql_affected_rows();

	//echo $total;


?>
<HTML>
<HEAD>
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>
<style type="text/css">
<!--
/*#pop_table {z-index: 1; left: 80; overflow: auto; width: 500; height: 220}*/
#pop_table_scroll { z-index: 1;  height: 220; background-color:#f7f7f7; overflow: auto; height: 325px; border:1px solid #d1d1d1;}
-->
</style>

<script language="javascript">
	function js_add() {

		var frm = document.frm;
		frm.action = "sms_add_user.php";
		frm.mode.value = "I";
		frm.submit();

	}

	function js_save() {
		var frm = document.frm;
		frm.action = "sms_add_user.php";
		frm.mode.value = "IS";
		frm.submit();
	}

</script>

</HEAD>
<BODY>
<table border=0 width=100%>
	<tr>
		<td align="center">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
	<TR>
		<TD align="left"><B>SMS 대상 등록 (txt 파일로 등록)</B></TD>
	</TR>
</TABLE>
<FORM name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="mode" value="" >
<input type="hidden" name="seq_no" value="<?echo $seq_no?>">
<input type="hidden" name="new_file_no" value="<?echo $new_file?>">

<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
		<TABLE border="0" cellspacing="1" cellpadding="2" class="IN">
			<tr>
				<th>파일선택 : </th>
				<td>
					<input type="file" name="file_nm" value="" size="40%">
					<br/>
					탭으로 분리된 txt 로 저장 후 등록 <br><a href="example.xls"><b>등록용 예제 파일 받기</b></a> 
				</td>
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
		<input type="button" name="btn" value=" 업로드 " onclick="js_add();" style="cursor:pointer">
		<?
			if (($new_file) && ($total > 0)) {
		?>
		<input type="button" VALUE="저 장" onClick="js_save();" style="cursor:pointer">
		<?
			}
		?>
		<input type="button" VALUE="닫 기" onClick="self.close();" style="cursor:pointer">
	</TD>
</TR>
</TABLE>
<br>
<table border=0 width=100%>
	<tr>
		<td align="center">

					<div id="pop_table_scroll">
						<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
								<tr>
									<th>NO.</th>
									<th scope="col">회원번호</th>
									<th scope="col">이름</th>
									<th class="end" scope="col">휴대폰번호</th>
								</tr>
							</thead>
							<tbody>
							<?
								if ($total > 0) {
									for($i=0 ; $i< $total ; $i++) {
										mysql_data_seek($result,$i);
										$row	= mysql_fetch_array($result);

										$ba_number		= Trim($row["ba_number"]);
										$mem_nm				= Trim($row["mem_nm"]);
										$htel					= Trim($row["htel"]);

							?>
								<tr>
									<td class="sort"><span><?=$total-$i?></span></td>
									<td><?=$ba_number?></td>
									<td><?=$mem_nm?></td>
									<td><?=$htel?></td>
								</tr>
							<?			
									}
								} else { 
							?> 
								<tr>
									<td height="50" align="center" colspan="7">데이터가 없습니다. </td>
								</tr>
							<? 
								}
							?>
							</tbody>
						</table>
					</div>
		</td>
	</tr>
</table>
</center>
</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
	mysql_close($connect);
?>