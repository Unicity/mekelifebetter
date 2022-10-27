<?
ini_set('memory_limit',-1);
ini_set('max_execution_time', 60);
?>
<?
	include "../admin_session_check.inc";
	//include "./inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../../AES.php";

	$str_title = "KitList";

	$file_name=$str_title."-".date("Ymd").".xls";
	header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
	header( "Content-Disposition: attachment; filename=$file_name" );
	//header( "Content-Description: orion70kr@gmail.com" );
	
	function right($value, $count){
		$value = substr($value, (strlen($value) - $count), strlen($value));
		return $value;
	}

	function left($string, $count){
		return substr($string, 0, $count);
	}

	  
 

//	$query2 = "select * from unclaimedCommission where 1 = 1  ".$que."order by ".$con_sort." ".$order ;
	$query = "select ttd.*, ttm.leader, tkd.baid as kitBA, tkd.fullname as kitFullname, tkd.contactNo as kitContactNo, tkd.creator as kitcreator, tkd.createdDate as kitCreatedDate, tkd.description as kitDescription from tb_ticket_detail as ttd "
			. "LEFT OUTER JOIN tb_ticket_master ttm on ttm.orderNo = ttd.orderNo " 
			. "LEFT OUTER JOIN tb_kit_detail tkd on ttd.ticketNo = tkd.ticketNo "  ;

	$result = mysql_query($query);
?>
<!--<font size=3><b>미지급 커미션 리스트</b></font> <br>-->
<style>
.xlGeneral {
	padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:10.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	mso-number-format:\@;
	text-align:Left;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;
}
</style>
<br>
출력 일자 : [<?=date("Y년 m월 d일")?> ]
<br>
<br>
<table border="1">
	<tr>
		<th width="7%" style="text-align: center;">티켓번호</th> 
		<th width="7%" style="text-align: center;">주문번호</th>
		<th width="7%" style="text-align: center;">그룹</th>
		<th width="7%" style="text-align: center;">티켓수령회원번호</th>
		<th width="7%" style="text-align: center;">티켓수령회원이름</th>
		<th width="10%" style="text-align: center;">티켓수령회원연락처</th>
		<th width="10%" style="text-align: center;">티켓전달직원</th>
		<th width="10%" style="text-align: center;">티켓전달일시</th>
		<th width="6%" style="text-align: center;">키트수령회원번호</th>
		<th width="6%" style="text-align: center;">키트수령회원이름</th>
		<th width="10%" style="text-align: center;">키트수령회원연락처</th>
		<th width="10%" style="text-align: center;">키트전달직원</th>
		<th width="10%" style="text-align: center;">키트전달일시</th>
	</tr>
<?
	while($obj = mysql_fetch_object($result)) {
?>
	<tr style='border-collapse:collapse;table-layout:fixed;'>
		<td align="left" style="padding-left: 10px"><?echo $obj->ticketPrefix.'-'.$obj->ticketNo?> </td>
		<td align="center" class="xlGeneral"><?echo $obj->orderNo?></td>
		<td align="center"><?echo $obj->leader?></td>
		<td align="center" class="xlGeneral"><?echo $obj->baid?></td>
		<td align="center"><?echo $obj->fullName?></td>
		<td align="center" class="xlGeneral"><?echo $obj->contactNo?></td>
		<td align="center"><?echo $obj->creator?></td>
		<td align="center"><?echo $obj->createdDate?></td>
		<td align="center" class="xlGeneral"><?echo $obj->kitBA?></td>
		<td align="center"><?echo $obj->kitFullname?></td>
		<td align="center" class="xlGeneral"><?echo $obj->kitContactNo?></td>
		<td align="center"><?echo $obj->kitcreator?></td>
		<td align="center"><?echo $obj->kitCreatedDate?></td>
	</tr>
<?
	}
?>

</table>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
