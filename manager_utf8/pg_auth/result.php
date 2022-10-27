<?
	echo "<script language=\"javascript\">\n
		document.location = '../pg/KSPAY_list.php';
		</script>";
	exit;


	$reAuthyn = $HTTP_POST_VARS["reAuthyn"];
	$reTrno   = $HTTP_POST_VARS["reTrno"  ];
	$reTrddt  = $HTTP_POST_VARS["reTrddt" ];
	$reTrdtm  = $HTTP_POST_VARS["reTrdtm" ];
	$reAuthno = $HTTP_POST_VARS["reAuthno"];
	$reOrdno  = $HTTP_POST_VARS["reOrdno" ];
	$reMsg1   = $HTTP_POST_VARS["reMsg1"  ];
	$reMsg2   = $HTTP_POST_VARS["reMsg2"  ];
	$reAmt    = $HTTP_POST_VARS["reAmt"   ];
	$reTemp_v = $HTTP_POST_VARS["reTemp_v"];
	$reIsscd  = $HTTP_POST_VARS["reIsscd" ];
	$reAqucd  = $HTTP_POST_VARS["reAqucd" ];
	$reRemark = $HTTP_POST_VARS["reRemark"];
	$reResult = $HTTP_POST_VARS["reResult"];

	$a        = $HTTP_POST_VARS["a"];
	$b        = $HTTP_POST_VARS["b"];
	$c        = $HTTP_POST_VARS["c"];
	$d        = $HTTP_POST_VARS["d"];
?>
<!--
<html>
<head> 
	<title>입력값</title>
	<link rel="stylesheet" href="form.css" type="text/css">
</head>
<body bgcolor=#ffffff onload="">

<CENTER><B><font size=4 color="blue">성공페이지 내역.</font></B></CENTER>
<br>
<TABLE  width="800" border="1" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center" colspan=4>
			<br>
			이페이지는 <font color = "red">승인성공시</font>에 업체측으로 리턴되는 결과 값들을 나타내고 있읍니다. 
			<br>
			아래와 같은 리턴 항목들중에서 귀사에서 필요하신 부분만 받으셔서 사용하시면 됩니다.
			<br>
			<br>
		</td>
	</tr>
<TR>
	<TD><B>항목명</B></TD>
	<TD><B>변수명</B></TD>
	<TD><B>결과값</B></TD>
	<TD><B>비고</B></TD>
</TR>
<TR>
	<TD>승인구분</TD>
	<TD>authyn</TD>
	<TD><?if($reAuthyn == "O") echo("승인"); else echo("거절");?></TD>
	<TD>승인요청에 대하여 승인이 허락되던지 <br>거절되던지 리턴값의 항목은 같읍니다.</TD>
</TR>
<TR>
	<TD>거래번호</TD>
	<TD>trno</TD>
	<TD><?echo($reTrno)?></TD>
	<TD>거래번호는 중요합니다. <br>결재정보중 유니크키로 사용하는값으로 사후 승인취소등의 처리시 꼭 필요합니다.</TD>
</TR>
<TR>
	<TD>거래일자</TD>
	<TD>trddt</TD>
	<TD><?echo($reTrddt)?></TD>
	<TD>&nbsp;</TD>
</TR>
<TR>
	<TD>거래시간</TD>
	<TD>trdtm</TD>
	<TD><?echo($reTrdtm)?></TD>
	<TD>&nbsp;</TD>
</TR>
<TR>
	<TD>승인번호</TD>
	<TD>authno</TD>
	<TD><?echo($reAuthno)?></TD>
	<TD>승인번호는 카드사에서 발급하는 것으로 유니크하지 않을수도 있음에 주의하십시요.</TD>
</TR>
<TR>
	<TD>주문번호</TD>
	<TD>ordno</TD>
	<TD><?echo($reOrdno)?></TD>
	<TD>주문번호는 업체측에서 넘겨주신 값을 그대로 리턴해드립니다.</TD>
</TR>
<TR>
	<TD>메세지1</TD>
	<TD>msg1</TD>
	<TD><?echo($reMsg1)?></TD>
	<TD>메세지는 카드사에서 보내는 것을 그대로 리턴해 드리는것으로<br> KSNET에서 생성된 내용은 아닙니다.</TD>
</TR>
<TR>
	<TD>메세지2</TD>
	<TD>msg2</TD>
	<TD><?echo($reMsg2)?></TD>
	<TD>승인성공시 이부분엔 OK와 승인번호가 표시됩니다.</TD>
</TR>
<TR>
	<TD>사용자추가 인자값</TD>
	<TD>&nbsp;</TD>
	<TD>
	a = <?echo($a)?><br>
	b = <?echo($b)?><br>
	c= <?echo($c)?><br>
	d = <?echo($d)?>
	</TD>
	<TD>&nbsp;</TD>
</TR>

	<tr>
		<td align="center" colspan=4>
			<br>
1
			<br>
			<br>
		</td>
	</tr>

</TABLE>
</body>
-->