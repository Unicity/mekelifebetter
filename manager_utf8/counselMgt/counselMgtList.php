<?php session_start();?>

<?php
	
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../../AES.php";
	include "../inc/common_function.php";
 	//$s_flag = str_quote_smart_session($s_flag);

	function getCategory($code){
		$categoryCode = array(
			"01" =>"본인동의",
			"02" =>"수당/실적",
			"03" =>"주문/반품",
			"04" =>"가입/해지",
			"05" =>"제품문의(기타)",
			"06" =>"제품문의(건식)",
			"07" =>"제품문의(화장품)",
			"08" =>"1800",
			"09" =>"세무/증빙",
			"10" =>"웹사이트/모바일앱",
			"11" =>"군포물류",
			"12" =>"우체국",
			"13" =>"국제후원",
			"14" =>"오토쉽",
			"15" =>"정수기",
			"16" =>"기타",
		    "17" =>"프로모션"
		);
		return $categoryCode[$code];
	}

	function getCounselType($code){
		$typeCodes = array(
			"01" => "일반", 
			"02" => "클레임", 
			"03" => "건의"
		);
		return $typeCodes[$code];
	}

	function getDeptName($code){
		$DeptCodes = array(
			"SDSC" =>"서울DSC",
			"ADSC" =>"안산DSC",
			"TDSC" =>"대전DSC",
			"WDSC" =>"원주DSC",
			"BDSC" =>"부산DSC",
			"DDSC" =>"대구DSC",
			"KDSC" =>"광주DSC",
			"IDSC" =>"인천DSC",
			"PRD1" =>"건식",
			"PRD2" =>"화장품",
			"FA"   =>"Finance",
			"OP"   =>"Operation",
			"LA"   =>"Legal",
			"LG"   =>"Logistics",
			"WT"   =>"Water",
			"DES"  =>"Design",
			"EV"   =>"Event",
			"IT"   =>"IT",
			"HR"   =>"HR",
			"PR"   =>"PR"
		);
		return $DeptCodes[$code];
	}

	$idxfield				= str_quote_smart(trim($idxfield));
 	
 	$qry_str				= str_quote_smart(trim($qry_str));

	$page						= str_quote_smart(trim($page));
	$nPageSize			= str_quote_smart(trim($nPageSize));
 	
	if (!isset($idxfield)) {
		$idxfield = "1";
	} 
	if (isset($qry_str)) {
		
			if ($idxfield == "0") {
				$que = $que." and member_no = '$qry_str' ";
			} else if($idxfield == "1"){
				$searched_contact_no = $qry_str;
				$que = $que." and contact_no like '%$qry_str%' ";
			} else if($idxfield == "2"){
				$que = $que." and name like '%$qry_str%' ";
			}else {
				if ($qry_str == "완료") {
					$que = $que." and status = 1 ";	
				} else if($qry_str == "진행중") {
					$que = $que." and status = 0 ";	
				}
				
			} 
		
		logging($s_adm_id,'search counsel '.$que);
	}
	
	if ($page <> "") {
		$page = (int)($page);
	} else {
		$page = 1;
	}

	if ($nPageSize <> "") {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 20;
	}

	$nPageBlock	= 10;
	
	$offset = $nPageSize*($page-1);

	$query = "select count(*) from tb_counsel where 1 = 1 ".$que;
	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];
	logging($s_adm_id,'search counsel count'.$TotalArticle);
	
	 $query2 = "select * from tb_counsel where 1 = 1 ".$que." order by createdate desc limit ". $offset.", ".$nPageSize; ;

	$result2 = mysql_query($query2);
	/*
	if ($que != ""){
		$query3 = "select distinct member_no, name, contact_no from tb_counsel where 1 = 1 ".$que." limit 1 ";
		$result3 = mysql_query($query3);

		if (!$result3) {
	    //	echo 'Could not run query: ' . mysql_error();
		//	exit;
			$url = "";

		} else {
			$row = mysql_fetch_row($result3);

	    	$searched_member_no =  $row[0];  
	    	$searched_member_name =  $row[1];  
	    	$searched_contact_no =  $row[2];  
		}
	}*/
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<title><?echo $g_site_title?></title>
<link rel="stylesheet" href="../inc/admin.css" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqgrid/4.6.0/js/jquery.jqGrid.min.js"></script>

<script language="javascript">
 	function resetForm(){
 		 $("input[name=qry_str]").val('');
 		document.frmSearch.submit();
 	}
 	function onSearch() {
 		document.frmSearch.action="counselMgtList.php";
	 	document.frmSearch.submit();

 	}
 
	function goPage(i) {
		document.frmSearch.page.value = i;
		document.frmSearch.submit();
	}

	function enterPressed(event, type){
		if (window.event.keyCode == 13)
    	{
    		if (type == 'search')
    		{
    			onSearch();	
    		} else if (type =='name') {
    			getName();
    		} else {}
        	
    	}

	}
	function getName(){
		
		var name = $("#member_name").val();
		var member_no = $("#member_no").val();
		
	//	if (name == ''){
			var request = $.ajax({
				url:"https://hydra.unicity.net/v5a/customers?unicity="+member_no+"&expand=customer",
				type:"GET",
			});
			
			request.done(function(msg) {

				if (msg.items[0].humanName['fullName@ko'].trim() != "false") {
					$("#member_name").val(msg.items[0].humanName['fullName@ko']);
				} else {
					$("#member_name").val("");
				}

			});
		//}
	}
	function save() {
	 
	//	if (checkEmpty('member_no') == 0) {
	//		return ;
	//	}

	//	if (checkEmpty('member_name') == 0) {
	//		return ;
	//	}

		if (checkEmpty('received_no') == 0) {
			return ;
		}


		if (checkEmpty('counsel_Type1') == 0) {
			return ;
		}

		/*if (checkEmpty('location') == 0) {
			return ;
		}*/

		var type2 = document.frmSearch.counsel_Type2.value;
		 
		if (type2 == ""){
			document.frmSearch.counsel_Type2.focus();
			return ; 
		}
		

		if (checkEmpty('description') == 0) {
			return ;
		}
		 

		document.frmSearch.action = 'counselMgt_save.php';
		document.frmSearch.submit();
	}

	function checkEmpty(val){
		var attribute = document.getElementById(val) ;
		if(attribute.value == ""){
			attribute.className = "error";
			return 0;
		}
	}
	function removeErrorClass(val){
		document.getElementById(val).classList.remove("error");
	}

	
	function NewWindow(mypage, myname, w, h, scroll) {
		var winl = (screen.width - w) / 2;
		var wint = (screen.height - h) / 2;
		winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
		win = window.open(mypage, myname, winprops)
		if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
	}
	function goExcel() {
		var frm = document.frmSearch;
		frm.target = "";
		frm.action = "unclaimedCommission_excel_list.php";
		frm.submit();
	}

	 
	var date = new Date();
	function getTimeStamp() {
	  var s =
		leadingZeros(date.getFullYear(), 4) + '-' +
		leadingZeros(date.getMonth() + 1, 2) + '-' +
		leadingZeros(date.getDate(), 2) + ' ' +

		leadingZeros(date.getHours(), 2) + ':' +
		leadingZeros(date.getMinutes(), 2) + ':' +
		leadingZeros(date.getSeconds(), 2);

	  return s;
	}

	function leadingZeros(n, digits) {
	  var zero = '';
	  n = n.toString();

	  if (n.length < digits) {
		for (i = 0; i < digits - n.length; i++)
		  zero += '0';
	  }
	  return zero + n;
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
					sValues += "^"+frmSearch.CheckItem.value+"^";
				}
			}
		}
		sValues  +=")";
		return sValues;
	}
</script>
<style type='text/css'>
body {
	font-family: Sans-serif, Arial, Monospace; 
}
td {FONT-SIZE: 9pt}
.h {FONT-SIZE: 9pt; LINE-HEIGHT: 120%}
.h2 {FONT-SIZE: 9pt; LINE-HEIGHT: 180%}
.s {FONT-SIZE: 8pt}
.l {FONT-SIZE: 11pt}
.text {  line-height: 125%}

.infotable {    
    border: 1px solid #ddd;
    text-align: left;
    border-collapse: collapse;
    width: 80%;
    margin: 10px 3%;
}

.infotable, td {
    padding: 5px 5px;
    font-size: 13px;
}
.button {
    background-color: #555555; /* Green */
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
}

.error {
	border: 2px solid red;
}
</style>
</head>
<body bgcolor="#FFFFFF">
<form name="frmSearch" id="frmSearch" method="post" action="">
	<table cellspacing="0" cellpadding="10" class="title" border="0">
		<tr>
			<td align="left"><b>상담이력관리</b></td>
			<td align="right" width="600" align="center" bgcolor=silver>
			<select name="idxfield">
				<OPTION VALUE="1" <?if($idxfield == "1") echo "selected";?>>연락처</OPTION>
				<option value="0" <?if($idxfield == "0") echo "selected";?>>회원번호</OPTION>
				<option value="2" <?if($idxfield == "2") echo "selected";?>>회원명</OPTION>
				<option value="3" <?if($idxfield == "3") echo "selected";?>>상태</OPTION>
			</select>
			<input type="text" name="qry_str" value="<?echo $qry_str?>" onKeyPress="enterPressed(event,'search')">&nbsp;
			<input type="button" value="검색" onClick="onSearch();">
			<input type="button" value="엑셀 다운로드" onClick="NewWindow('counselMgt_excel_month.php', '기간선택', 650, 500, '0');">
			<input type="button" value="Reset" onClick="resetForm();">
		 	</td>
		</tr>
	</table> 
		<br>
	<table class="infotable" >
		<tr>
			<td width="10%">회원번호</td>
			<td width="20%"><input type="text" name="member_no" id="member_no" onKeyPress="enterPressed(event,'name')" onfocusout="getName();"></td>
			<td width="10%">회원명</td>
			<td width="20%"><input type="text" name="member_name" id="member_name"></td>
			<td width="10%">인입전화번호*(번호만)</td>
			<td width="20%"><input type="text" name="received_no" id="received_no" onfocus="removeErrorClass('received_no')" maxlength="11" value="<?php echo $searched_contact_no;?>"></td>
		</tr>
		<tr>
			<td>상담구분*</td>
			<td><input type="radio" name="counsel_Type2" id="counselType1" value="01" checked>
				<label for="counselType1">일반상담</label>
				<input type="radio" name="counsel_Type2" id="counselType2" value="02">
				<label for="counselType2">클레임</label>
				<input type="radio" name="counsel_Type2" id="counselType3" value="03">
				<label for="counselType3">건의</label>
			</td>
			<td>상담유형*</td>
			<td>
				<select name="counsel_Type1" id="counsel_Type1">
					<option value="">상담유형 선택</option>
					<!-- <option value="01">본인동의</option> -->
					<option value="02">수당/실적</option>
					<option value="03">주문/반품</option>
					<option value="04">가입/해지</option>
					<option value="05">제품문의(기타)</option>
					<option value="06">제품문의(건식)</option>
					<option value="07">제품문의(화장품)</option>
					<option value="08">1800</option>
					<option value="09">세무/증빙</option>
					<option value="10">웹사이트/모바일앱</option>
					<option value="11">군포물류</option>
					<option value="12">우체국</option>
					<option value="13">국제후원</option>
					<option value="14">오토쉽</option>
					<option value="15">정수기</option>
					<option value="17">프로모션</option>
					<option value="16">기타</option>
					 
				</select>
			</td>
			<td>지역</td>
			<td>
				<select name="location" id="location">
					<option value="">지역선택</option>
					<option value="서울">서울(서울,그외 경기,제주)</option>
					<option value="안산">안산(안산,수원,화성,군포,의왕,평택,오산)</option>
					<option value="인천">인천(인천,부천,김포,일산,파주,광명,강화,시흥)</option>
					<option value="원주">원주(강원,제천)</option>
					<option value="대전">대전(대전,충청,세종)</option>
					<option value="부산">부산(부산,울산,경남)</option>
					<option value="대구">대구(대구,경북)</option>
					<option value="광주">광주(광주,전라)</option>
				</select>
			</td>
		
		</tr>
		<tr>
			<td> 이관(메일)부서 </td>
			<td><select name="transferred_dept">
					<option value="">부서선택</option>
					<option value="SDSC">서울DSC</option>
					<option value="ADSC">안산DSC</option>
					<option value="TDSC">대전DSC</option>
					<option value="WDSC">원주DSC</option>
					<option value="BDSC">부산DSC</option>
					<option value="DDSC">대구DSC</option>
					<option value="KDSC">광주DSC</option>
					<option value="IDSC">인천DSC</option>
					<option disabled>-------------</option>
					<option value="PRD1">건식</option>
					<option value="PRD2">화장품</option>
					<option value="FA">Finance</option>
					<option value="OP">Operation</option>
					<option value="LA">Legal</option>
					<option value="LG">Logistics</option>
					<option value="WT">Water</option>
					<option value="DES">Design</option>
					<option value="EV">Event</option>
					<option value="IT">IT</option>
					<option value="HR">HR</option>
					<option value="PR">PR</option>
				</select>
			</td>
			<td>이관받은사람</td>
			<td><input type="text" name="transferred_staff"></td>
			<td>상담상태</td>
			<td>
				<select name="status">
					<option value="0">진행중</option>
					<option value="1" selected="selected">완료</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>기타(건의)(Max:100)</td>
			<td colspan="5"><input type="text" name="short_comment" maxlength="100" size="116"></td>
		</tr>
		<tr>
			<td colspan="6">상담내역* (Max:240)</td>
		</tr>
		<tr>
			<td colspan="6">
				<textarea cols="130" rows="5" name="description" id="description" style="resize:none;padding:10px;" maxlength="240"></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="6" align="center">
				<input type="button" class="button" value="저장하기" onClick="save();">
			</td>
		</tr>
	</table>
	<table cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
		<tr align="center">
			<th width="2%" style="text-align: center;">#</th>
			<th width="5%" style="text-align: center;">상담구분</th>
			<th width="5%" style="text-align: center;">상담유형</th>
			<th width="5%" style="text-align: center;">지역</th>
			<th width="8%" style="text-align: center;">인입번호</th>
			<th width="5%" style="text-align: center;">회원번호</th>
			<th width="5%" style="text-align: center;">회원명</th>
			<th width="8%" style="text-align: center;">상담시간</th>
			<th width="5%" style="text-align: center;">상담자</th>
			<th width="5%" style="text-align: center;">이관부서</th>
			<th width="5%" style="text-align: center;">이관받은사람</th>
			<th width="14%" style="text-align: center;">기타(건의)</th>
		</tr>     
	<?php
		$result2 = mysql_query($query2);
		if ($TotalArticle) {
	
			while($obj = mysql_fetch_object($result2)) {
				$createddate_s = date("Y-m-d H:i", strtotime($obj->createdate));
				$status = $obj->status;

	?>
		<tr>
			<td align="center"><a href="javascript:NewWindow('counselMgt_modify.php?id=<?echo $obj->id?>', '자료수정', 870, 430, '0');"><?echo $obj->id?></a></td>
			<td align="center"><?echo getCounselType($obj->counsel_type2).' ('.($status==0?'진행중':'완료').')' ?></td>
			<td align="center"><?echo getCategory($obj->counsel_type1) ?></td>
			<td align="center"><?echo $obj->location?></td>
			<td align="center"><?echo $obj->contact_no?></a></td>
			<td align="center"><?echo $obj->member_no?></td>
			<td align="center"><?echo $obj->name?></td>
			<td align="center"><?echo $createddate_s?></td>
			<td align="center"><?echo $obj->updator?></td>
			<td align="center"><?echo getDeptName($obj->transferred_dept)?></td>
			<td align="center"><?echo $obj->transferred_staff?></td>
			<td align="center"><?echo $obj->short_comment?></td>
		</tr>
		<tr>
			<td align="center">&rarr;</td>
			<td colspan="11"><?echo $obj->description?></td>
		</tr>
	<?php
	
			}
		}
	?>
	</table>
	<table cellspacing="1" cellpadding="5" class="LIST" border="0">
		<tr>
			<td align="left">
	    		등록된 데이터 수 : <?echo $TotalArticle?> 개
			</td>
			<td align="right">
				<?
					$Scale = floor(($page - 1) / $PageScale);
					if ($TotalArticle > $ListArticle){
						if ($page != 1)
							echo "[<a href=javascript:goPage('1');>맨앞</a>]";
							// 이전페이지
								if (($TotalArticle + 1) > ($ListArticle * $PageScale)){
									$PrevPage = ($Scale - 1) * $PageScale;
									if ($PrevPage >= 0)
										echo "&nbsp;[<a href=javascript:goPage('".($PrevPage + 1)."');>이전".$PageScale."개</a>]";
								}

							echo "&nbsp;";

							// 페이지 번호
							for ($vj = 0; $vj < $PageScale; $vj++){
								$vk = $Scale * $PageScale + $vj + 1;
								if ($vk < $TotalPage + 1){
									if ($vk != $page)
										echo "&nbsp;[<a href=javascript:goPage('".$vk."');>".$vk."</a>]&nbsp;";
									else
										echo "&nbsp;<b>[".$vk."]</b>&nbsp;";
								}
							}

							echo "&nbsp;";
							// 다음 페이지
							if ($TotalArticle > (($Scale + 1) * $ListArticle * $PageScale)){
								$NextPage = ($Scale + 1) * $PageScale + 1;
								echo "[<a href=javascript:goPage('".$NextPage."');>이후".$PageScale."개</a>]";
							}

							if ($page != $TotalPage)
								echo "&nbsp;[<a href=javascript:goPage('".$TotalPage."');>맨뒤</a>]&nbsp;&nbsp;";
					}
					
					else 
						echo "&nbsp;[1]&nbsp;";	
				?>
			</td>
		</tr>
	</table>
 
	<input type="hidden" name="page" value="<?echo $page?>">
 
</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
mysql_close($connect);
?>