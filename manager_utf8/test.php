<?php 
set_time_limit(0);
session_start();
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";
	include "./inc/common_function.php";
	
	echo '<meta charset="utf-8">';


echo urldecode('mainAddress%5Bcity%5D=%EC%84%B1%EB%B6%81%EA%B5%AC&mainAddress%5Bcountry%5D=KR&mainAddress%5Bstate%5D=%EC%84%9C%EC%9A%B8&mainAddress%5Bzip%5D=02879&mainAddress%5Baddress1%5D=%EC%84%B1%EB%B6%81%EB%A1%9C23%EA%B0%80%EA%B8%B8+16&mainAddress%5Baddress2%5D=101+%28%EC%84%B1%EB%B6%81%EB%8F%99%29&humanName%5BfirstName%5D=Jy+lee&humanName%5BlastName%5D=+&humanName%5BfirstName%40ko%5D=%EC%9D%B4%EC%A0%95%EC%9C%A4&humanName%5BlastName%40ko%5D=+&type=WholesaleCustomer&status=Active&gender=female&password%5Bvalue%5D=123456&enroller%5Bhref%5D=https%3A%2F%2Fhydra.unicity.net%2Fv5a%2Fcustomers%3Fid.unicity%3D&birthDate=1989-01-26&email=ljyoon0303%40gmail.com&mobilePhone=01099957624&homePhone=&taxTerms%5BtaxId%5D=890126%EC%9D%B4%EC%A0%95%EC%9C%A4');

echo "<br><br>";

echo urlDecode('mainAddress%5Bcity%5D=%EC%84%9C%EB%8C%80%EB%AC%B8%EA%B5%AC&mainAddress%5Bcountry%5D=KR&mainAddress%5Bstate%5D=%EC%84%9C%EC%9A%B8&mainAddress%5Bzip%5D=03617&mainAddress%5Baddress1%5D=%EA%B0%84%ED%98%B8%EB%8C%80%EB%A1%9C4%EA%B8%B8+14-17&mainAddress%5Baddress2%5D=1%EC%B8%B5+%28%ED%99%8D%EC%A0%9C%EB%8F%99%29&humanName%5BfirstName%5D=Yoo+Jin&humanName%5BlastName%5D=+&humanName%5BfirstName%40ko%5D=%EC%9C%A0%EC%A7%84&humanName%5BlastName%40ko%5D=+&type=WholesaleCustomer&status=Active&gender=female&password%5Bvalue%5D=123456z&enroller%5Bhref%5D=https%3A%2F%2Fhydra.unicity.net%2Fv5a%2Fcustomers%3Fid.unicity%3D214617682&birthDate=1997-08-05&email=wlslrkwkd%40naver.com&mobilePhone=01030250805&homePhone=&taxTerms%5BtaxId%5D=970805%EC%9C%A0%EC%A7%84');

echo "<br><br>";

echo urldecode('mainAddress%5Bcity%5D=%EC%84%B1%EB%B6%81%EA%B5%AC&mainAddress%5Bcountry%5D=KR&mainAddress%5Bstate%5D=%EC%84%9C%EC%9A%B8&mainAddress%5Bzip%5D=02879&mainAddress%5Baddress1%5D=%EC%84%B1%EB%B6%81%EB%A1%9C23%EA%B0%80%EA%B8%B8+16&mainAddress%5Baddress2%5D=101+%28%EC%84%B1%EB%B6%81%EB%8F%99%29&humanName%5BfirstName%5D=Jylee&humanName%5BlastName%5D=+&humanName%5BfirstName%40ko%5D=%EC%9D%B4%EC%A0%95%EC%9C%A4&humanName%5BlastName%40ko%5D=+&type=Associate&status=Active&gender=female&password%5Bvalue%5D=123456&enroller%5Bhref%5D=https%3A%2F%2Fhydra.unicity.net%2Fv5a%2Fcustomers%3Fid.unicity%3D15745082&birthDate=1989-01-26&email=ljyoon0303%40gmail.com&mobilePhone=01099957624&homePhone=&taxTerms%5BtaxId%5D=890126%EC%9D%B4%EC%A0%95%EC%9C%A4');

exit;


$sql = "select * from tb_userinfo where number != '' and  REF !='M' and member_kind='D' and substring(regdate,1,10) = '2021-01-05'
	union all
	select * from tb_userinfo_dup where number != '' and REF !='M' and member_kind='D' and substring(regdate,1,10) = '2021-01-05'";

$result = mysql_query($sql) or die(mysql_error());	
for($i=0; $i<mysql_num_rows($result); $i++) {
	$row = mysql_fetch_array($result);

	$address = explode(" ",$row[addr]); 
	$sido = $address[0];
	$gugun = $address[1];
	if($gugun == "성남시" || $gugun == "천안시" || $gugun == "안양시" || $gugun == "안산시" || $gugun == "고양시" || $gugun == "전주시" || $gugun == "수원시" || $gugun == "용인시" || $gugun == "포항시" || $gugun == "청주시"){
		$gugun = $address[1]." ".$address[2];
	}
	if(strpos($row[addr]) > 0){
		$addr = substr($row[addr], 0, strpos($row[addr], ")"));		
	}else{
		$addr = $row[addr];
	}
	$addr = str_replace(array($sido." ", $gugun." ", "(", ","), "", $addr);


	echo $i.":".$row[number]." ".$row[name]." ".$row[zip]." ".$row[addr]." ".$row[addr_detail]."-<b>".$sido."-".$gugun."-".$addr."-".$row[addr_detail]."</b><br>";

	
	$url = 'https://hydra.unicity.net/v5a/customers?unicity='.$row[number].'&expand=customer';
	$username = 'krWebEnrollment';
	$password = 'qKZ95XCrpzth5MgCpzMGQwXHw7ZMHg';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	$response = curl_exec($ch);
		 
	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		 
	if (($response != false) && ($status == 200)) {
		
		//$response = json_decode($response, true);
		$data = json_decode($response);
		 
		if($data->items[0]->href != "") {
			
			$url2 = $data->items[0]->href;	
		
			//주소업데이트
			$postdata = http_build_query(
					array(
						'mainAddress' => array(
							'address1' => $addr,
							'address2' => $row[addr_detail],
							'city' => $gugun,
							'state' => $sido,
							'zip' => $row[zip],
							'country' => 'KR'
						)
					));	

			curl_setopt($ch, CURLOPT_URL, $url2);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
			$response3 = curl_exec($ch);
			$status3 = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			
			$response3 = str_replace("fullName@ko","fullNameko",$response3); //@ 있을시 PHP syntax error
			$data = json_decode($response3);
			print_r($data);
			echo "<br>";
			print_r($status3);
			echo "<br><br>";
			flush();
			ob_flush();
		}

	}
	curl_close($ch);
	
}

exit;
?>


<?

exit;
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
		
	$query = "SELECT count(AA.ID) FROM (
SELECT jumin, ID, DistributorName, VolumePeriod,
case substr(VolumePeriod,1,3) when 
'Jan' then '01' when 
'Feb' then '02' when 
'Mar' then '03' when 
'Apr' then '04' when 
'May' then '05' when 
'Jun' then '06' when 
'Jul' then '07' when 
'Aug' then '08' when 
'Sep' then '09' when 
'Oct' then '10' when 
'Nov' then '11' when 
'Dec' then '12' 
else '13' end as tt,

case substr(VolumePeriod,5,2) when 
'09' then '2009' when 
'10' then '2010' when 
'11' then '2011' when 
'12' then '2012' when 
'13' then '2013'when 
'14' then '2014' 
else '2009' end as tty 
FROM tb_Activityreport 

UNION

SELECT '' as jumin, ID, DistributorName,VolumePeriod,
case substr(VolumePeriod,1,3) when 
'Jan' then '01' when 
'Feb' then '02' when 
'Mar' then '03' when 
'Apr' then '04' when 
'May' then '05' when 
'Jun' then '06' when 
'Jul' then '07' when 
'Aug' then '08' when 
'Sep' then '09' when 
'Oct' then '10' when 
'Nov' then '11' when 
'Dec' then '12' 
else '13' end as tt,

case substr(VolumePeriod,5,2) when 
'09' then '2009' when 
'10' then '2010' when 
'11' then '2011' when 
'12' then '2012' when 
'13' then '2013'when 
'14' then '2014' 
else '2009' end as tty 
FROM 
tb_Activityreport_MBL

) AA
";

	$query2 = "SELECT AA.jumin, AA.ID, AA.DistributorName, AA.tty, AA.tt FROM (
SELECT jumin, ID, DistributorName, VolumePeriod,
case substr(VolumePeriod,1,3) when 
'Jan' then '01' when 
'Feb' then '02' when 
'Mar' then '03' when 
'Apr' then '04' when 
'May' then '05' when 
'Jun' then '06' when 
'Jul' then '07' when 
'Aug' then '08' when 
'Sep' then '09' when 
'Oct' then '10' when 
'Nov' then '11' when 
'Dec' then '12' 
else '13' end as tt,

case substr(VolumePeriod,5,2) when 
'09' then '2009' when 
'10' then '2010' when 
'11' then '2011' when 
'12' then '2012' when 
'13' then '2013'when 
'14' then '2014' 
else '2009' end as tty 
FROM tb_Activityreport 

UNION

SELECT '' as jumin, ID, DistributorName,VolumePeriod,
case substr(VolumePeriod,1,3) when 
'Jan' then '01' when 
'Feb' then '02' when 
'Mar' then '03' when 
'Apr' then '04' when 
'May' then '05' when 
'Jun' then '06' when 
'Jul' then '07' when 
'Aug' then '08' when 
'Sep' then '09' when 
'Oct' then '10' when 
'Nov' then '11' when 
'Dec' then '12' 
else '13' end as tt,

case substr(VolumePeriod,5,2) when 
'09' then '2009' when 
'10' then '2010' when 
'11' then '2011' when 
'12' then '2012' when 
'13' then '2013'when 
'14' then '2014' 
else '2009' end as tty 
FROM 
tb_Activityreport_MBL
) AA order AA.tty asc, AA.tt asc
";

	
	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];

	$ListArticle = 10;
	$PageScale = 10;
	$TotalPage = ceil($TotalArticle / $ListArticle);		// 총 페이지수

	if (!$TotalPage)
		$TotalPage = 0;

	if (empty($page))
		$page = 1;


	# 이전 페이지
	$Prev = $page - 1;
	if ($Prev < 0)
		$Prev = 0;

	# 다음 페이지
	$Next = $page + 1;
	if ($Next > $TotalPage)
		$Next = $TotalPage;

	# 현재 보여줄 글의 개수 계산
	$First = $ListArticle * $Prev;
	$Last = $First + $ListArticle;
	if ($Last > $TotalArticle)
		$Last = $TotalArticle;

	$Scale = floor($page / ($ListArticle * $PageScale));

	# 게시물 번호
	$NumberArticle = $TotalArticle - $First;
	
?>
<html>
<head>
<title><?echo $g_site_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<link rel="stylesheet" href="inc/admin.css" type="text/css">
<script language="javascript">

function form_check(){

	if (frmSearch.query.value=="") {
		alert("검색어를 넣으세요");
		return false;
	} else {
		frmSearch.submit();
	}

}

function init(){
<?	if (!empty($qry_str)) {  ?>
		document.frmSearch.qry_str.value="<?echo $qry_str ?>";
		document.frmSearch.idxfield.options[<?echo $idxfield ?>].selected = true;
<?	} ?>

}

function onView(id) {
	document.frmSearch.id.value = id; 
	document.frmSearch.action= "admin_view.php";
	document.frmSearch.submit();
}

function goIn() {
	document.frmSearch.action= "admin_input.php";
	document.frmSearch.submit();
}

function goDel() {

	var check_count = 0;
	var total = document.frmSearch.length;
						 
	for(var i=0; i<total; i++) {
		if(document.frmSearch.elements[i].checked == true) {
	    	check_count++;
	    }
	}
	
	if(check_count == 0) {
		alert("삭제하실 관리자를 선택해 주십시오.");
	    return;
	}
	
	bDelOK = confirm("정말 삭제 하시겠습니까?");
		
	if ( bDelOK ==true ) {
		document.frmSearch.id.value = getIds();
		document.frmSearch.mode.value = "del";
		document.frmSearch.action = "admin_db.php";
		document.frmSearch.submit();
	}
	else {
		return;
	}

}

function getIds(){
	var sValues = "(";
	if(frmSearch.CheckItem != null){
		if(frmSearch.CheckItem.length != null){
			for(i=0; i<frmSearch.CheckItem.length; i++){
				if(frmSearch.CheckItem[i].checked == true){
					if(sValues != "("){
						sValues += ",";
					}
					sValues +="^"+frmSearch.CheckItem[i].value+"^";
				}
			}
		}else{
			if(frmSearch.CheckItem.checked == true){
				sValues += frmSearch.CheckItem.value;
			}
		}
	}
	sValues  +=")";
	return sValues;
}

</script>
</head>
<BODY bgcolor="#FFFFFF" onLoad="init();">
<FORM name="frmSearch" method="post" action="admin_list.php">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>관리자 관리</B></TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
	<SELECT NAME="idxfield">
		<OPTION VALUE="0">관리자 ID</OPTION>
		<OPTION VALUE="1">관리자 성명</OPTION>
	</SELECT>
	<INPUT TYPE="text" NAME="qry_str" VALUE="">&nbsp;
	<INPUT TYPE="submit" VALUE="검색">
	<INPUT TYPE="button" VALUE="등록" onClick="goIn();">
	<INPUT TYPE="button" VALUE="삭제" onClick="goDel();">	
	</TD>
</TR>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
<TR>
	<TH width="3%">&nbsp;</TH> 
	<TH width="17%">관리자 ID</TH>
	<TH width="15%">관리자 성명</TH>
	<TH width="20%">관리자 그룹</TH>
	<TH width="15%">연락처</TH>
	<TH width="15%">E-Mail</TH>
	<TH width="15%">등록일</TH>
</TR>     
<?
	$result2 = mysql_query($query2);

	echo $TotalArticle;

	if ($TotalArticle) {

		for ($i = 0; $i < $Last; ++$i) {
			mysql_data_seek($result2,$i);
			$obj = mysql_fetch_object($result2);

			if ($i >= $First) {
				
?>					
<TR align="center">                    
	<TD><INPUT TYPE="checkbox" name="CheckItem" value="<?echo $obj->ID?>"></TD>
	<TD><A HREF="javascript:onView('<?echo $obj->ID?>')"><?echo $obj->ID?></A></TD>
	<td><?echo $obj->jumin?></td>
	<td><?echo $obj->jumin?></td>
</TR>
<?
			}
		}
	}
?>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0">
<TR>
	<TD align="center" colspan=7>
<?
$Scale = floor(($page - 1) / $PageScale);

if ($TotalArticle > $ListArticle)
{

	if ($page != 1)
			echo "[<a href='".$PHP_SELF."?page=1&idxfield=$idxfield&qry_str=$qry_str'>맨앞</a>]";
	// 이전페이지
	if (($TotalArticle + 1) > ($ListArticle * $PageScale))
	{
		$PrevPage = ($Scale - 1) * $PageScale;

		if ($PrevPage >= 0)
				echo "&nbsp;[<a href='".$PHP_SELF."?page=".($PrevPage + 1)."&idxfield=$idxfield&qry_str=$qry_str'>이전".$PageScale."개</a>]";
	}

	echo "&nbsp;";

	// 페이지 번호
	for ($vj = 0; $vj < $PageScale; $vj++)
	{
//		$ln = ($Scale * $PageScale + $vj) * $ListArticle + 1;
		$vk = $Scale * $PageScale + $vj + 1;
		if ($vk < $TotalPage + 1)
		{
			if ($vk != $page)
					echo "&nbsp;[<a href='".$PHP_SELF."?page=".$vk."&idxfield=$idxfield&qry_str=$qry_str'>".$vk."</a>]&nbsp;";
			else
				echo "&nbsp;<b>[".$vk."]</b>&nbsp;";
		}

		
	}

	echo "&nbsp;";
	// 다음 페이지
	if ($TotalArticle > (($Scale + 1) * $ListArticle * $PageScale))
	{
		$NextPage = ($Scale + 1) * $PageScale + 1;
			echo "[<a href='".$PHP_SELF."?page=".$NextPage."&idxfield=$idxfield&qry_str=$qry_str'>이후".$PageScale."개</a>]";
	}

	if ($page != $TotalPage)
			echo "&nbsp;[<a href='".$PHP_SELF."?page=".$TotalPage."&idxfield=$idxfield&qry_str=$qry_str'>맨뒤</a>]&nbsp;&nbsp;";
}
else 
			echo "&nbsp;[1]&nbsp;";	
?>
	</TD>
</TR>
</TABLE>
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="id" value="">
<input type="hidden" name="mode" value="del">
</form>
</body>
</html>
<?
	mysql_close($connect);
?>