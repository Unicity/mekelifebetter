<?
ini_set('memory_limit',-1);
ini_set('max_execution_time', 60);
?>
<?
	include "../admin_session_check.inc";
	//include "./inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../../AES.php";

	$str_title = "TicketList";

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
	$query = "select tm.orderNo, tm.eventName, tm.baid as ordererID, tm.fullName as orderedName,
					tm.leader, tm.orderedQty, coalesce(sum(td.qty),0) as pickupNo
				from tb_ticket_master tm
           		left outer join tb_ticket_detail td on tm.orderNo = td.orderNo
				group by tm.orderNo, tm.eventName, tm.baid, tm.fullName, tm.leader, tm.orderedQty" ;

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
		<td align='center' bgcolor='#F4F1EF'>주문번호</td>
		<td align='center' bgcolor='#F4F1EF'>이벤트명</td>
		<td align='center' bgcolor='#F4F1EF'>주문회원</td>
		<td align='center' bgcolor='#F4F1EF'>주문회원이름</td>
		<td align='center' bgcolor='#F4F1EF'>Leader</td>
		<td align='center' bgcolor='#F4F1EF'>주문수량</td>
	</tr>
<?
	while($obj = mysql_fetch_object($result)) {
?>
	<tr style='border-collapse:collapse;table-layout:fixed;'>
		<td class="xlGeneral"><?php echo $obj->orderNo ?></td>
		<td><?echo $obj->eventName ?></td>
		<td class="xlGeneral"><?echo $obj->ordererID ?></td>
		<td><?echo $obj->orderedName?></td>
		<td class="xlGeneral"><?echo $obj->leader?></td>
		<td><?echo $obj->orderedQty?></td>
		<td><?echo $obj->pickupNo?></td>
		 
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
