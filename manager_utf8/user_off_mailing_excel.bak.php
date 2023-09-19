<?
include "admin_session_check.inc";
include "../dbconn_utf8.inc";

//logging($s_adm_id,'open new member list (user_list.php)');

$query = "select * from tb_useroff where email != '' and  email_send_yn = 'N' and ifnull(del_tf,'N') = 'N' order by mno desc ";
$result = mysql_query($query)  or die(mysql_error());
$num = mysql_num_rows($result);

$fname = "mail_".date("YmdHis").".xls";
header("Content-type: application/vnd.ms-excel;"); 
header("Content-Disposition: attachment; filename=".$fname); 
header("Content-Description: PHP5 Generated Data"); 
?>
<table>
<tr>
	<td>성명</td>
	<td>이메일</td>
	<td>핸드폰</td>
	<td>팩스</td>
	<td>고객정보1</td>
	<td>고객정보2</td>
	<td>고객정보3</td>
	<td>고객정보4</td>
	<td>고객정보5</td>
</tr>
<?
$snum = "";
$enum = "";
$cnt =0;

for($i=0; $i<mysql_num_rows($result); $i++) {
	$row = mysql_fetch_array($result);
	if($row['email'] != ""){
		if($snum == "") $snum = $row['mno'];
		$enum = $row['mno'];
		$cnt++;
		$fixNum = sprintf("%010d",$row['mno']);
		$cardNum = $row['reg_num'].$fixNum;
		$enNum = base64_encode($cardNum);
		?>
		<tr>
			<td><?=$row['name']?></td>
			<td><?=$row['email']?></td>
			<td></td>
			<td></td>
			<td><?=$row['mno']?></td>
			<td><?=$row['reg_num']?></td>
			<td><?=$enNum?></td>
			<td></td>
			<td></td>
		</tr>
	<?
	}
}
?>
</table>

<?
mysql_query("insert into tb_useroff_mail_list (fname, snum, enum, cnt, cdate) values ('$fname', '$snum', '$enum', '$cnt', now())") or die(mysql_error());	
mysql_close();
?>
	