<?
include "admin_session_check.inc";
include "./inc/global_init.inc";
include "../dbconn_utf8.inc";
include "../AES.php";

$uid = preg_replace('/[^0-9]/','',$_GET['uid']);

if(empty($uid)){
	echo "<script>
		alert('조회대상이 없습니다.');
		self.close();
		</script>";
	exit;
}

$result = mysql_query("select * from tb_log_v2 where uid = '$uid'") or die(mysql_error());	
$row = mysql_fetch_array($result);

if($row['uid'] == ""){
	echo "<script>
		alert('대상을 조회할 수 없습니다.');
		self.close();
		</script>";
	exit;
}
?>
<html>
<head>
<title><?echo $g_site_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<link rel="stylesheet" href="inc/admin.css" type="text/css">
</head>
<body bgcolor="#FFFFFF">
<div style="width:100%;height:40px;line-height:40px;background:#333;color:#fff;">
	<h2 style="margin-left:10px">LOG상세조회</h2>
</div>
<div style="width:100%:height:100%; padding:10px">
	<table cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver" style="word-break:break-all">
		<tr>
			<th style="width:20%">성명</th>
			<td style="width:80%">
				<strong><?=$row[name]?></strong>
			</td>
		</tr>
		<tr>
			<th>생년월일</th>
			<td>
				<?=$row[jumin1]?>
			</td>
		</tr>
		<tr>
			<th>회원번호</th>
			<td>
				<?=$row[memid]?>
			</td>
		</tr>
		<tr>
			<th>내용</th>
			<td>
				<strong><?=$row[gubun]?></strong>
			</td>
		</tr>
		
		<tr>
			<th>발송DATA</th>
			<td style="word-break:break-all">		
				<?=$row[sendData]?>
			</td>
		</tr>
		<tr>
			<th>수신DATA</th>
			<td style="word-break:break-all">
				<?php
				if($row[check_kind] == "api"){ 
					if($row[recieveData] != "") print_r(json_decode($row[recieveData]));			
				}else{
					echo $row[recieveData];
				}
				?>
			</td>
		</tr>
		<?php if(substr($row[recieveData], 0, 9) == "7:REQ_SEQ"){ ?>
		<tr>
			<th>내외국인</th>
			<td style="word-break:break-all">
				<?php 
				$npos = strpos($row[recieveData], 'NATIONALINFO1:');
				$nationalinfo = substr($row[recieveData], $npos+14, 1);
				if($nationalinfo == '0') echo "내국인";
				else echo "외국인";
				?>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<th>메세지</th>
			<td style="word-break:break-all">		
				<?=$row[msg]?>
			</td>
		</tr>
		<tr>
			<th>결과</th>
			<td style="word-break:break-all">		
				<?=$row[flag]?>
			</td>
		</tr>
		<tr>
			<th>디바이스</th>
			<td style="word-break:break-all">		
				<?=($obj->device == "P") ? "PC" : "Mobile";?>
			</td>
		</tr>
		<tr>
			<th>로그일시</th>
			<td style="word-break:break-all">		
				<?=$row[logdate]?>
			</td>
		</tr>
	</table>
</div>
<center><button onclick="self.close()">닫기</button></center>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>