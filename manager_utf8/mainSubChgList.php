<?php session_start();?>
<?php
	include "./admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";

	if ($con_order == "con_a") {
		$order = "asc";
	} else {
		$order = "desc";
		$con_order = "con_d";
	}
	
	if ($flag == "together") {
		$selval = "and applyflag = 1";
	} else if($flag == "chgMs") {
		$selval = "and applyflag = 2";
	} else if($flag == "sumData"){ 
		$selval = "and applyflag = 3";
	}else {
		$selval = "and applyflag in ('1','2','3')";
	}
	
	if (empty($idxfield)) {
		$idxfield = "0";
	}
	
	if (empty($con_sort)) {
		$con_sort = "applyDate";
	}
	
	if (empty($con_order)) {
		$order = "desc";
	}
	
	if (empty($flag)) {
		$flag = "allData";
	}
	
	if ((empty($r_status)) || ($r_status == "A")) {
		$r_status = "A";
	} else {
		$que = $que." and reg_status = '$r_status' ";
	}
	
	if (!empty($qry_str)) {
	
		if ($idxfield == "0") {
			$que = $que." and member_id like '%$qry_str%' ";
		} else if($idxfield == "1"){
			$que = $que." and member_name like '%$qry_str%' ";
		}
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
	
	$query = "select count(*) from mainSubChg where 1 = 1 ".$que." ".$selval;
	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];
	echo $query."<br/>";
	$query2 = "select * from mainSubChg where 1 = 1 ".$que." ".$selval." order by ".$con_sort." ".$order." limit ". $offset.", ".$nPageSize; 
	echo $query2;
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
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-Frame-Options" content="deny" />
		<title>공동 등록 및 주부 사업자 변경</title>
		<link rel="stylesheet" href="./inc/admin.css" type="text/css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script language="javascript">

			function check_data(){
				
				for(i=0; i < document.frmSearch.rsort.length ; i++) {
					if (document.frmSearch.rsort[i].checked == true) {
						document.frmSearch.con_sort.value = document.frmSearch.rsort[i].value;
					}
				}
	
				for(i=0; i < document.frmSearch.rorder.length ; i++) {
					if (document.frmSearch.rorder[i].checked == true) {
						document.frmSearch.con_order.value = document.frmSearch.rorder[i].value;
					}
				}

				for(i=0; i < document.frmSearch.flagVal.length ; i++) {
					if (document.frmSearch.flagVal[i].checked == true) {
						document.frmSearch.flag.value = document.frmSearch.flagVal[i].value;
					}
				}
				
				
	
				document.frmSearch.action="mainSubChgList.php";
				document.frmSearch.submit();
			}
			function onSearch(){
				
				for(i=0; i < document.frmSearch.rsort.length ; i++) {
					if (document.frmSearch.rsort[i].checked == true) {
						document.frmSearch.con_sort.value = document.frmSearch.rsort[i].value;
					}
				}
	
				for(i=0; i < document.frmSearch.rorder.length ; i++) {
					if (document.frmSearch.rorder[i].checked == true) {
						document.frmSearch.con_order.value = document.frmSearch.rorder[i].value;
					}
				}
	
				document.frmSearch.page.value="1";
				document.frmSearch.action="mainSubChgList.php";
				document.frmSearch.submit();
			}

			function goPage(i) {
				alert(i);
				document.frmSearch.page.value = i;
				document.frmSearch.submit();
			}

			function optionValue(){
				var valChk = document.frmSearch.optionChk.value;
				alert(valChk);
				if(valChk =='002'){
					$(".chgMs").css("display","none");
					$("#togerher1").css("display","block");
					$("#togerher2").css("display","block");
					$("#togerher3").css("display","block");
					$("#togerher4").css("display","block");
				}else if (valChk =='003'){
					$(".togerher").css("display","none");
				}	

				document.frmSearch.action="mainSubChgList.php";
				document.frmSearch.submit();	

			}	

			function onView(id){
				document.frmSearch.id.value = id; 
				document.frmSearch.action= "mainSub_view.php";
				document.frmSearch.submit();
			}
						
		</script>
	</head>
	<body bgcolor="#FFFFFF">
		<form name="frmSearch" method="post" action="javascript:check_data();">
			<table cellspacing="0" cellpadding="10" class="title">
				<tr>
					<td align="left"><b>공동등록 및 부부사업자 변경</b></td>
					<td align="right" width="600" align="center" bgcolor=silver>
						<select name="idxfield">
							<option value="0" <?if($idxfield == "0") echo "selected";?>>회원번호</OPTION>
						</select>
						<input type="text" name="qry_str" value="<?echo $qry_str?>">&nbsp;
						<input type="button" value="검색" onClick="onSearch();">
						<input type="button" value="엑셀 다운로드" onClick="goExecl();">
					</td>
				</tr>
			</table>
			<b>* 처리 하실 단계를 선택하세요.</b>
			<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
				<tr>
					<td align='center'>
						<table width='99%' bgcolor="#EEEEEE">
							<tr align="center">
								<td align="left">
									<input type="radio" name="r_status" value="A" <? if ($r_status == "A") echo "checked" ?>  onClick="check_data();"> 전체 &nbsp;&nbsp;
									<!--input type="radio" name="r_status" value="1" <? if ($r_status == "1") echo "checked" ?>  onClick="check_data();"> 신청 (본인여부확인) &nbsp;&nbsp;-->
									<input type="radio" name="r_status" value="2" <? if ($r_status == "2") echo "checked" ?>  onClick="check_data();"> 신청 &nbsp;&nbsp;
									<input type="radio" name="r_status" value="3" <? if ($r_status == "3") echo "checked" ?>  onClick="check_data();"> 출력 처리 (프린트 출력) &nbsp;&nbsp;
									<input type="radio" name="r_status" value="4" <? if ($r_status == "4") echo "checked" ?>  onClick="check_data();"> 완료 (서버 입력 완료)&nbsp;&nbsp;
									<input type="radio" name="r_status" value="8" <? if ($r_status == "8") echo "checked" ?>  onClick="check_data();"> 보류&nbsp;&nbsp;
									<input type="radio" name="r_status" value="9" <? if ($r_status == "9") echo "checked" ?>  onClick="check_data();"> 신청 거부&nbsp;&nbsp;
								</td>
								
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
				<tr>
					<td align='center'>
						<table width='99%' bgcolor="#EEEEEE">
							<tr align="center">
								<td align="left">
									<b><input type="radio" name="rsort" value="applyDate" <?if($con_sort == "applyDate") echo "checked";?> onClick="check_data();"> 등록일 </b>
									<b><input type="radio" name="rsort" value="id" <?if($con_sort == "id") echo "checked";?> onClick="check_data();"> 회원번호 </b>
								</td>
								<td align="right">
									<b><input type="radio" name="rorder" value="con_d" <?if($con_order == "con_d") echo "checked";?> onClick="check_data();">오름차순 </b>
									<b><input type="radio" name="rorder" value="con_a" <?if($con_order == "con_a") echo "checked";?> onClick="check_data();">내림차순 </b>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
				<tr>
					<td align='center'>
						<table width='99%' bgcolor="#EEEEEE">
							<tr align="center">
								<td align="left">
									<b><input type="radio" name="flagVal" value="allData" <?if($flag == "allData") echo "checked";?> onClick="check_data();"> 전체 </b>
									<b><input type="radio" name="flagVal" value="together" <?if($flag == "together") echo "checked";?> onClick="check_data();"> 공동 </b>
									<b><input type="radio" name="flagVal" value="chgMs" <?if($flag == "chgMs") echo "checked";?> onClick="check_data();"> 부부 </b>
									<b><input type="radio" name="flagVal" value="sumData" <?if($flag == "sumData") echo "checked";?> onClick="check_data();">공동 + 부부 </b>
								</td>
							</tr>
						</table>			
					</td>
				</tr>
			</table>
			<table cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
				<tr align="center">
					<th width="5%" style="text-align: center;">신청일자</th>
					<th width="5%" style="text-align: center;">신청사항</th>
						
					<th width="5%" style="text-align: center;">지역</th>
					<th width="5%" style="text-align: center;">회원번호</th>
					<th width="5%" style="text-align: center;">성명</th>
					<th width="5%" style="text-align: center;">처리상태</th>
				<?php	if (($r_status == 1) || ($r_status == 2) ) {?>
					<th width="5%" style="text-align: center;">선택</th>
				<?php }else if ($r_status == "3"){ ?>
					<th width="5%" style="text-align: center;">출력일</th>
					<th width="5%" style="text-align: center;">출력자</th>
	
					<th width="5%" style="text-align: center;">완료처리</th>
				<?php }else if($r_status == "4"){?>
					<th width="5%" style="text-align: center;">완료일</th>
					<th width="5%" style="text-align: center;">완료자</th>
				<?php } else if($r_status == "8"){?>
					<th width="5%" style="text-align: center;">보류일</th>
					<th width="5%" style="text-align: center;">보류자</th>
				<?php } else if($r_status == "9"){?>
					<th width="5%" style="text-align: center;">신청거부일</th>
					<th width="5%" style="text-align: center;">신청거부자</th>
				<?php }?>
					<th width="5%" style="text-align: center;">본인외 주문</th>
					<th width="5%" style="text-align: center;">인증방식</th>
					<!--  	
					<th width="5%" style="text-align: center;">생년월일</th>
					<th width="5%" style="text-align: center;">전화번호</th> 
		
					<th id="togerher1" class="togerher" width="5%" style="text-align: center;FONT-SIZE: 12px;FONT-WEIGHT: normal;TEXT-ALIGN: left;PADDING: 5px;COLOR: white;BACKGROUND-COLOR: 555555;">공동이름</th>
					<th id="togerher2" class="togerher" width="5%" style="text-align: center;">공동생일</th>
					<th id="togerher3" class="togerher" width="5%" style="text-align: center;">공동관계</th>
					<th id="togerher4" class="togerher" width="5%" style="text-align: center;">공동번호</th>
					
					<th class="chgMs" width="5%" style="text-align: center;">부주이름</th>
					<th class="chgMs" width="5%" style="text-align: center;">부주생일</th>
					<th class="chgMs" width="5%" style="text-align: center;">부부이름</th>
					<th class="chgMs" width="5%" style="text-align: center;">부부생일</th>
					
					<th class="chgMs" width="5%" style="text-align: center;">은행명</th>
					<th class="chgMs" width="5%" style="text-align: center;">계좌번호</th>
					-->
				</tr>   
				<?php
					$result2 = mysql_query($query2);
					if ($TotalArticle) {
						while($obj = mysql_fetch_object($result2)) {
							$date_s = date("Y-m-d", strtotime($obj->applyDate));
							$print_s = date("Y-m-d", strtotime($obj->print_date));
							$wait_s = date("Y-m-d", strtotime($obj->wait_date));
							$reject_s = date("Y-m-d", strtotime($obj->reject_date));
							$confirm_s = date("Y-m-d", strtotime($obj->confirm_date));	
				?>
			<tr>
				<td style="width: 5%" align="center"><?echo $obj->applyDate?></td>
				<td style="width: 5%" align="center"><?	
					if ($obj->applyflag == "1") {
						echo "공동등록";
					} else if ($obj->applyflag == "2") {
						echo "주부변경";
					} else if ($obj->applyflag == "3") {
						echo "공동등록"."<br/>"."주부변경";
					} 
				?></td>
				
				<td style="width: 5%" align="center"><?echo $obj->state?></td>
				<td style="width: 5%" align="center"><a href="javascript:onView('<?echo $obj->id?>')"><?echo $obj->FO_ID?></a></td>
				<td style="width: 5%" align="center"><?echo $obj->FO_NAME?></td>
				<td align="center">
				<?	
					if ($obj->reg_status == "1") {
						echo "신청(본인확인)";
					} else if ($obj->reg_status == "2") {
						echo "신청";
					} else if ($obj->reg_status == "3") {
						echo "출력처리";
					} else if ($obj->reg_status == "4") {
						echo "완료";
					} else if ($obj->reg_status == "8") {
						echo "보류";
					} else if ($obj->reg_status == "9") {
						echo "거부";
					} 
				?>	
				</td>
				<td style="width: 5%" align="center">Y</td>
				<td style="width: 5%" align="center"><?php echo "[모]".$obj->mtime?></td>
				<!-- 
				<td style="width: 5%" align="center"><?echo $obj->FO_BIRTHDAY?></td>
				<td style="width: 5%" align="center"><?echo $obj->FO_PHONE?></td>
				
				<td class="togerher" style="width: 5%" align="center" ><?echo $obj->TOGETHER_NAME?></td>
				<td class="togerher" style="width: 5%" align="center"><?echo $obj->TOGETHER_BIRTHDAY?></td>
				<td class="togerher" style="width: 5%" align="center"><?echo $obj->TOGETHER_RELATION?></td>
				<td class="togerher" style="width: 5%" align="center"><?echo $obj->TOGETHER_PHONE?></td>
				
				<td class="chgMs" style="width: 5%" align="center"><?echo $obj->CHG_MAIN_NAME?></td>
				<td class="chgMs" style="width: 5%" align="center"><?echo $obj->CHG_MAIN_BIRTHDAY?></td>
				<td class="chgMs" style="width: 5%" align="center"><?echo $obj->CHG_SUB_NAME?></td>
				<td class="chgMs" style="width: 5%" align="center"><?echo $obj->CHG_SUB_BIRTHDAY?></td>
				
				<td class="chgMs" align="center">
				<?	
					if ($obj->CHG_BANK == "060") {
						echo "BOA은행";
					} else if ($obj->CHG_BANK == "263") {
						echo "HMC 투자증권";
					} else if ($obj->CHG_BANK == "054") {
						echo "HSBC은행";
					} else if ($obj->CHG_BANK == "292") {
						echo "LIG투자증권";
					} else if ($obj->CHG_BANK == "289") {
						echo "NH투자증권";
					} else if ($obj->CHG_BANK == "023") {
						echo "SC제일은행";
					} else if ($obj->CHG_BANK == "266") {
						echo "SK증권";
					} else if ($obj->CHG_BANK == "039") {
						echo "경남은행";
					}  else if ($obj->CHG_BANK == "034") {
						echo "광주은행";
					} else if ($obj->CHG_BANK == "261") {
						echo "교보증권";
					} else if ($obj->CHG_BANK == "004") {
						echo "국민은행";
					} else if ($obj->CHG_BANK == "003") {
						echo "기업은행";
					} else if ($obj->CHG_BANK == "011") {
						echo "농협중앙회";
					} else if ($obj->CHG_BANK == "012") {
						echo "농협회원조합";
					} else if ($obj->CHG_BANK == "031") {
						echo "대구은행";
					} else if ($obj->CHG_BANK == "267") {
						echo "대신증권";
					} else if ($obj->CHG_BANK == "238") {
						echo "대우증권";
					} else if ($obj->CHG_BANK == "055") {
						echo "도이치은행";
					} else if ($obj->CHG_BANK == "279") {
						echo "동부증권";
					} else if ($obj->CHG_BANK == "209") {
						echo "유안타증권";
					} else if ($obj->CHG_BANK == "287") {
						echo "메리츠종합금융증권";
					} else if ($obj->CHG_BANK == "052") {
						echo "모건스탠리은행";
					} else if ($obj->CHG_BANK == "230") {
						echo "미래에셋증권";
					} else if ($obj->CHG_BANK == "059") {
						echo "미쓰비시도쿄UFJ은행";
					} else if ($obj->CHG_BANK == "058") {
						echo "미즈호코퍼레이트은행";
					} else if ($obj->CHG_BANK == "290") {
						echo "부국증권";
					} else if ($obj->CHG_BANK == "032") {
						echo "부산은행";
					} else if ($obj->CHG_BANK == "002") {
						echo "산업은행";
					} else if ($obj->CHG_BANK == "240") {
						echo "삼성증권";
					} else if ($obj->CHG_BANK == "050") {
						echo "상호저축은행";
					} else if ($obj->CHG_BANK == "045") {
						echo "새마을금고연합회";
					} else if ($obj->CHG_BANK == "268") {
						echo "솔로몬투자증권";
					} else if ($obj->CHG_BANK == "008") {
						echo "수출입은행";
					} else if ($obj->CHG_BANK == "007") {
						echo "수협중앙회";
					} else if ($obj->CHG_BANK == "291") {
						echo "신영증권";
					}  else if ($obj->CHG_BANK == "278") {
						echo "신한금융투자";
					} else if ($obj->CHG_BANK == "088") {
						echo "신한은행";
					} else if ($obj->CHG_BANK == "048") {
						echo "신협중앙회";
					} else if ($obj->CHG_BANK == "056") {
						echo "알비에스은행";
					} else if ($obj->CHG_BANK == "005") {
						echo "외환은행";
					} else if ($obj->CHG_BANK == "020") {
						echo "우리은행";
					} else if ($obj->CHG_BANK == "247") {
						echo "우리투자증권";
					} else if ($obj->CHG_BANK == "071") {
						echo "우체국";
					} else if ($obj->CHG_BANK == "280") {
						echo "유진투자증권";
					} else if ($obj->CHG_BANK == "265") {
						echo "이트레이드증권";
					} else if ($obj->CHG_BANK == "037") {
						echo "전북은행";
					} else if ($obj->CHG_BANK == "057") {
						echo "제이피모간체이스은행";
					} else if ($obj->CHG_BANK == "035") {
						echo "제주은행";
					} else if ($obj->CHG_BANK == "090") {
						echo "카카오뱅크";
					} else if ($obj->CHG_BANK == "264") {
						echo "키움증권";
					} else if ($obj->CHG_BANK == "270") {
						echo "하나대투증권";
					} else if ($obj->CHG_BANK == "081") {
						echo "하나은행";
					} else if ($obj->CHG_BANK == "262") {
						echo "하이투자증권";
					} else if ($obj->CHG_BANK == "027") {
						echo "한국씨티은행";
					}  else if ($obj->CHG_BANK == "243") {
						echo "한국투자증권";
					} else if ($obj->CHG_BANK == "269") {
						echo "한화증권";
					} else if ($obj->CHG_BANK == "218") {
						echo "현대증권";
					}  
					
				?>	
				</td>
				<td class="chgMs" style="width: 5%" align="center"><?echo $obj->CHG_ACCOUNT?></td>
			 -->
			</tr>
			<?php
						}
					}
			?>
			</table>
			<table cellspacing="1" cellpadding="5" class="LIST" border="0">
				<tr>
					<td align="left">
					    등록된 회원 수 : <?echo $TotalArticle?> 개
					</td>
					<td align="right">
						<?
						$Scale = floor(($page - 1) / $PageScale);
						
						if ($TotalArticle > $ListArticle)
						{
						
							if ($page != 1)
									echo "[<a href=javascript:goPage('1');>맨앞</a>]";
							// 이전페이지
							if (($TotalArticle + 1) > ($ListArticle * $PageScale))
							{
								$PrevPage = ($Scale - 1) * $PageScale;
						
								if ($PrevPage >= 0)
										echo "&nbsp;[<a href=javascript:goPage('".($PrevPage + 1)."');>이전".$PageScale."개</a>]";
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
							}else 
									echo "&nbsp;[1]&nbsp;";	
						?>
					</td>
				</tr>
			</table>
			<input type="hidden" name="page" value="<?echo $page?>">
			<input type="hidden" name="con_sort" value="<?echo $con_sort?>">
			<input type="hidden" name="con_order" value="<?echo $con_order?>">
			<input type="hidden" name="flag" value="<?echo $flag?>">
			<input type="hidden" name="member_no" value="">
			<input type="hidden" name="reg_status" value="">
			<input type="hidden" name="flag_id" value="<?php echo $idValue?>">
			<input type="hidden" name="id">
			<input type="hidden" name="member_id" value="">
			<input type="hidden" name="confirmDate">
		</form>

		<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

	</body>	
</html>	