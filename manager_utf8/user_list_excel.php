<?

	$str_title = iconv("UTF-8","EUC-KR","온라인회원");
	//$str_title ="참가자리스트";

	$file_name=$str_title."-".date("Ymd").".xls";
		header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
		header( "Content-Disposition: attachment; filename=$file_name" );
		header( "Content-Description: orion70kr@gmail.com" );

	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";


	function right($value, $count){
		$value = substr($value, (strlen($value) - $count), strlen($value));
		return $value;
	}

	function left($string, $count){
		return substr($string, 0, $count);
	}

	function getCodeName ($code, $parent)  { 

		$sqlstr = "SELECT name FROM tb_code where parent='".$parent."' and code='".$code."'"; 

		$result = mysql_query($sqlstr);
		$list = mysql_fetch_array($result);

		$name = $list[name];

		print($name);

	}

	$idxfield				= str_quote_smart(trim($idxfield));
	$con_sort				= str_quote_smart(trim($con_sort));
	$con_order			= str_quote_smart(trim($con_order));

	if ($con_order == "con_a") {
		$order = "asc";
		$con_order = "con_a";
	} else {
		$order = "desc";
		$con_order = "con_d";
	}

	$from_date			= str_quote_smart(trim($from_date));
	$to_date				= str_quote_smart(trim($to_date));
	$r_status				= str_quote_smart(trim($r_status));
	$r_memberkind		= str_quote_smart(trim($r_memberkind));
	$r_join_kind		= str_quote_smart(trim($r_join_kind));
	$r_active_kind	= str_quote_smart(trim($r_active_kind));
	$r_couple				= str_quote_smart(trim($r_couple));
	$qry_str				= str_quote_smart(trim($qry_str));
	$page						= str_quote_smart(trim($page));
	$nPageSize			= str_quote_smart(trim($nPageSize));
	$reg_status			= str_quote_smart(trim($reg_status));

	$toDay = date("Y-m-d");

	if (empty($idxfield)) {
		$idxfield = "0";
	} 

	if (empty($con_sort)) {
		$con_sort = "regdate";
	}

	if (empty($con_order)) {
		$order = "desc";
	}

	if (!empty($from_date)) {
		$que = " and regdate >= '$from_date' ";		
	}



	if ($page <> "") {
		$page = (int)($page);
	} else {
		$page = 1;
	}

	if ($nPageSize <> "") {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 20000000;
	}

	$nPageBlock	= 10;
	
	$offset = $nPageSize*($page-1);

	$que = $que." and regdate > '2016-05-31' ";

	$query = "select count(*) from tb_userinfo where 1 = 1 ".$que;
	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];


	$query2 = "select * from tb_userinfo where 1 = 1 ".$que." order by ".$con_sort." ".$order." limit ". $offset.", ".$nPageSize; ;
	$result2 = mysql_query($query2);



	$ListArticle = $nPageSize;
	$PageScale = $nPageSize;
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

<STYLE type='text/css'>
TD {FONT-SIZE: 9pt}
.h {FONT-SIZE: 9pt; LINE-HEIGHT: 120%}
.h2 {FONT-SIZE: 9pt; LINE-HEIGHT: 180%}
.s {FONT-SIZE: 8pt}
.l {FONT-SIZE: 11pt}
.text {  line-height: 125%}
</STYLE>
</head>

<TABLE cellspacing="1" cellpadding="5"  border="1" >
<TR>
	<TH width="6%">이름</TH>
	<TH width="7%">연락처</TH>
	<TH width="6%">신청종류</TH>
	<TH width="7%">FO NO</TH>
	<TH width="6%">신청일</TH>
	<TH width="6%">이메일통보</TH>
	<TH width="6%">SMS통보</TH>
	<TH width="6%">주요안내사항 통보 </TH>
	<TH width="6%">효성FMS㈜ 정보동의</TH>
	<TH width="6%">(주)GMCOM 정보동의</TH>
	<TH width="6%">㈜하나투어 / ㈜SM C&C BT&I 정보동의</TH>
	<TH width="6%">깊은인상 정보동의</TH>
</TR>     
<?
	if ($TotalArticle) {
		while($obj = mysql_fetch_object($result2)) {
		
		//	if ($i >= $First) {
				if(!empty($obj->regdate)){
					$date_s1 = date("Y-m-d", strtotime($obj->regdate));
				}else{
					$date_s1 = "";
				}
				

				if ($r_status == "A") { 

					if ($obj->ldate != null) {
						$date_s2 = date("Y-m-d [H:i]", strtotime($obj->ldate));
					} else {
						$date_s2 = "";
					}
				
					$temp = number_format($obj->visit_count);

	 			} else if ($r_status == "1") { 

					if ($obj->ldate != null) {
						$date_s2 = date("Y-m-d [H:i]", strtotime($obj->ldate));
					} else {
						$date_s2 = "";
					}
				
					$temp = number_format($obj->visit_count);

	 			} else if ($r_status == "2") { 

					if ($obj->confirm_person_date != null) {
						$date_s2 = date("Y-m-d [H:i]", strtotime($obj->confirm_person_date));
					} else {
						$date_s2 = "";
					}
				
					$temp = $obj->confirm_person_ma;

				} else if ($r_status == "3") { 

					if ($obj->print_date != null) {
						$date_s2 = date("Y-m-d [H:i]", strtotime($obj->print_date));
					} else {
						$date_s2 = "";
					}
				
					$temp = $obj->print_ma;

				} else if ($r_status == "4") { 

					if ($obj->confirm_date != null) {
						$date_s2 = date("Y-m-d [H:i]", strtotime($obj->confirm_date));
					} else {
						$date_s2 = "";
					}
				
					$temp = $obj->confirm_ma;

				} else if ($r_status == "8") { 

					if ($obj->wait_date != null) {
						$date_s2 = date("Y-m-d [H:i]", strtotime($obj->wait_date));
					} else {
						$date_s2 = "";
					}
				
					$temp = $obj->wait_ma;

				} else if ($r_status == "9") { 

					if ($obj->reject_date != null) {
						$date_s2 = date("Y-m-d [H:i]", strtotime($obj->reject_date));
					} else {
						$date_s2 = "";
					}
				
					$temp = $obj->reject_ma;

				} 

				if ($obj->co_number != null) {
					$email_mod_date = $obj->co_number;
				} else {
					$email_mod_date = "";
				}

				if ($obj->co_name != null) {
					$co_name = $obj->co_name;
				} else {
					$co_name = "";
				}
				
				if (trim($obj->REF) == "M") $str_mobile = "(M)";
				if (trim($obj->REF) == "W") $str_mobile = "";


?>
<TR align="center">
	<TD height="25"><?echo $str_mobile?><?echo $obj->name?></TD>
	<TD><?echo $obj->hpho1?>-<?echo $obj->hpho2?>-<?echo $obj->hpho3?></TD>
	<TD>
<?	

	if (trim($obj->member_kind) == "D") {
		echo "FO 회원";
	} else if (trim($obj->member_kind) == "C") {
		echo "소비자회원";
	} 

?>	
	</TD>
	<TD>
	<?
	if ($r_status == "3"){
		if($obj->number){?>
		<?echo $obj->number?>
	<?}else{?>
		<input type="text" name="banumber">
		<input type="hidden" name="no" value="<?=$obj->member_no?>">
	<?}
	}else{
		echo $obj->number;
	}
	?>

	</TD>
	<TD><?echo $date_s1?></TD>
	<TD><?echo $obj->agree_01?></TD>
	<TD><?echo $obj->agree_02?></TD>
	<TD><?echo $obj->agree_03?></TD>
	<TD><?echo $obj->sel_agree01?></TD>
	<TD><?echo $obj->sel_agree02?></TD>
	<TD><?echo $obj->sel_agree03?></TD>
	<TD><?echo $obj->sel_agree04?></TD>
</TR>
<?
		//	}
		}
	}
?>
</TABLE>

</body>
</html>
<?
mysql_close($connect);
?>