<?
include "admin_session_check.inc";
include "./inc/global_init.inc";
include "../dbconn_utf8.inc";
include "../AES.php";
include "./inc/common_function.php";
?>
<html>
<head>
<title><?echo $g_site_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- <meta http-equiv="X-Frame-Options" content="deny" /> -->
<link rel="stylesheet" href="inc/admin.css" type="text/css">
<script type="text/javascript" src="inc/jquery.js"></script>
<script language="javascript">
function js_send(n) {

	if(n == ""){
		alert("처리할 데이터가 없습니다.");
	}else{
		if(confirm("API일괄발송 처리를 하시겠습니까?")){
			location.href =  "batch_account_process.php?grp="+n;
		}
	}
}

</script>
<STYLE type='text/css'>
.btn { padding:10px 20px; background:#000; color:#fff !important; }
</STYLE>
</head>
<BODY bgcolor="#FFFFFF">

<?php include "common_load.php" ?>

<div style="padding:20px">
<?php 
//그룹 count
$result = mysql_query("select max(grp) from tb_batch_account") or die(mysql_error());	
$row = mysql_fetch_array($result);
$group = $row[0] + 1;

$arr = array();

if($_FILES){

	if(strtolower(end(explode(".", $_FILES['upfile']['name']))) != "txt"){
		echo "<script>
		alert('txt 파일만 등록하실 수 있습니다.');
		history.back();
		</script>";
		exit;
	
	}else{
		$i = 0;
		$success = 0;		

		$fp = fopen($_FILES['upfile']['tmp_name'],"r"); 

		while (($line = fgets($fp)) !== false) {

			$data = explode("\t", $line);
		
			$data[0] = preg_replace('/[^0-9]/','',$data[0]);

			if($data[0] != ""){

				//회원테이블과 중복테이블을 병합해서 나중에 등록된 자료를 기준으로 처리합니다.
				$query = "select * from (
						select 'user' as tbl, number, name, account, account_bank, regdate  from tb_userinfo where number = '".$data[0]."'
						union all
						select 'dup' as tbl, number, name, account, account_bank, regdate  from tb_userinfo_dup where number = '".$data[0]."'
						) A order by regdate desc limit 1";
				$result = mysql_query($query) or die(mysql_error());
				$row = mysql_fetch_array($result);
		
				if($row['number'] == ""){
					
					echo $data[0].":조회 결과 없슴<br>";
				
				}else{

					//은행코드 확인
					$sql2 = "SELECT * FROM tb_code where parent='bank3' and name='".$row['account_bank']."' limit 1"; 
					$result2 = mysql_query($sql2);
					$row2 = mysql_fetch_array($result2);
	
					
					$insql = "insert into tb_batch_account set
								grp = '".$group."',						
								number = '".$row['number']."',
								tbl = '".$row['tbl']."',
								name = '".$row['name']."',
								account_bank = '".$row['account_bank']."',
								bank_code = '".$row2['code']."',
								account = '".$row['account']."',
								regDate = '".$row['regdate']."', 
								createdDate = now()";	
					mysql_query($insql) or die(mysql_error());
					

					echo ($i+1).": ".$data[0].", ".$row['name'].", ".$row['regdate'].", ".$row['tbl'].", ".$row['account_bank'].":".$row2['code'].", ".decrypt($key, $iv, $row['account'])."<br>"; 
					$success++;
				}
			}
			$i++;
		}
		fclose($fp); 

		echo "<br>총<strong style='color:blue'>".$i."</strong>건중 <strong style='color:red'>".$success."</strong>건의 데이터가 조회 되었습니다.<br>
			  조회결과가 없는 자료는 발송하지 않습니다.<br><br>user:회원테이블조회, dup:중복회원테이블조회<br><br>";

		//$json_data = json_encode($arr, JSON_UNESCAPED_UNICODE);
		?>
		<!-- <form name="frm" id="frm" method="post" action="batch_process.php" style="padding:20px 0px;">
			<textarea name="data" id="data" style="display:none"><?=json_encode($arr)?></textarea> -->
			<div  style="padding:20px 0px;">
				<a href="javascript:js_send('<?=$group?>');" class="btn blue" style="font-size:16px;">API일괄발송 시작</a>
			</div>
		<!-- </form> -->
		<?
	}


}else{ 

	echo "<script>
		alert('파일이 전송되지 않았습니다');
		history.back();
		</script>";
	exit;

}
?>
</div>
</body>
</html>
<?
mysql_close($connect);
?>