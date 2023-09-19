<?php session_start();?>
<?php
	include "./admin_session_check.inc";
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
	$yyyy= date('Y');
	
	$mode = str_quote_smart(trim($mode));
	$qry_str = str_quote_smart(trim($qry_str));
	$idxfield = str_quote_smart(trim($idxfield));
	$page = str_quote_smart(trim($page));
	
	$con_sort = str_quote_smart(trim($con_sort));
	$con_order = str_quote_smart(trim($con_order));
	$r_status = str_quote_smart(trim($r_status));

	if ($con_order == "con_a") {
		$order = "asc";
	} else {
		$order = "desc";
		$con_order = "con_d";
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
	
	if ((empty($r_status)) || ($r_status == "A")) {
		$r_status = "A";
	} else {
		$que = $que." and reg_status = '$r_status' ";		
	}

	if (!empty($qry_str)) {
	
		if ($idxfield == "0") {
			$que = $que." and member_no like '%$qry_str%' ";
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

	$query = "select count(*) from internet_sales_warning where 1 = 1 ".$que;
	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];
echo $query."<br/>";

	$query2 = "select * from internet_sales_warning where 1 = 1 ".$que." order by ".$con_sort." ".$order." limit ". $offset.", ".$nPageSize; ;
	$result2 = mysql_query($query2);
echo $query2."<br/>";
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
		<title>인터넷 판매 제보 확인</title>
		<link rel="stylesheet" href="./inc/admin.css" type="text/css">
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
	
				document.frmSearch.action="internetSell.php";
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
				document.frmSearch.action="internetSell.php";
				document.frmSearch.submit();
			}

			function goPage(i) {
				document.frmSearch.page.value = i;
				document.frmSearch.submit();
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


			function onView(no){

				document.frmSearch.numVal.value = no; 
				document.frmSearch.action= "internetSell_view.php";
				document.frmSearch.submit();
			}	

			function goPrint() {

				var check_count = 0;
				
				if (document.frmSearch.CheckItem == null ) {
					alert("접수확인 할 회원이 없습니다.");
				    return;		
				}

				var total = document.frmSearch.CheckItem.length;
			
				if (document.frmSearch.CheckItem.length == null) {
					if(document.frmSearch.CheckItem.checked == true) {
				    	check_count++;
				    }
				}
									 
				for(var i=0; i<total; i++) {
					if(document.frmSearch.CheckItem[i].checked == true) {
				    	check_count++;
				    }
				}
				
				if(check_count == 0) {
					alert("접수확인 할 회원이 없습니다.");
				    return;
				}
				
				document.frmSearch.member_no.value = getIds();
				var url = "interSellPrint.php?member_no="+document.frmSearch.member_no.value;

				NewWindow(url, "print_page", '700', '500', "yes");
				
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

			function NewWindow(mypage, myname, w, h, scroll) {
				var winl = (screen.width - w) / 2;
				var wint = (screen.height - h) / 2;
				winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
				win = window.open(mypage, myname, winprops)
				if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
			}

			function goConfirm(id){

				var url = "interSellConfirm.php?member_no="+id;
				NewWindow(url, "memo_page", '600', '250', "no");
				
				
				/*
				if(confirm("완료하시겠습니까?")){   
					document.frmSearch.confirmDate.value = getTimeStamp();
					document.frmSearch.member_no.value = id;
					document.frmSearch.reg_status.value = "4";
					document.frmSearch.action = "interSellConfirm.php";
					document.frmSearch.submit();
				}
				*/	
			}	

			function goWorking(id){
				if(confirm("처리중으로 변경 하시겠습니까?")){
					document.frmSearch.workingDate.value = getTimeStamp();
					document.frmSearch.member_no.value = id;
					document.frmSearch.reg_status.value = "5";
					document.frmSearch.action = "interSellWork.php";
					document.frmSearch.submit();				}	
			}

			function textDetail(id){
				var url = "internetTextDetail.php?member_no="+id;
				NewWindow(url, "memo_page", '600', '250', "no");
			}	
								
		</script>
		<style type='text/css'>
			td {font-size: 9pt}
			.h {font-size: 9pt; line-height: 120%}
			.h2 {font-size: 9pt; line-height: 180%}
			.s {font-size: 8pt}
			.l {font-size: 11pt}
			.text {  line-height: 125%}
		</style>

	</head>
	<body>
	
<?php include "common_load.php" ?>

		<form name="frmSearch" method="post" action="javascript:check_data();">
			<input type ="hidden" name="numVal" value="">
			<table cellspacing="0" cellpadding="10" class="title">
				<tr>
					<td align="left"><b>인터넷 판매 제보</b></td>
					<td align="right" width="600" align="center" bgcolor=silver>
					<select name="idxfield">
						<option value="0" <?if($idxfield == "0") echo "selected";?>>회원번호</option>
						<option value="1" <?if($idxfield == "1") ?>>회원성명</option>
					</select>
					<input type="text" name="qry_str" value="<?echo $qry_str?>">&nbsp;
					<input type="button" value="검색" onClick="onSearch();">
					<!--
					<INPUT TYPE="button" VALUE="삭제" onClick="goDel();">	
					-->
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
									<input type="radio" name="r_status" value="2" <? if ($r_status == "2") echo "checked" ?>  onClick="check_data();"> 신청 &nbsp;&nbsp;
									<input type="radio" name="r_status" value="3" <? if ($r_status == "3") echo "checked" ?>  onClick="check_data();"> 접수확인 (프린트 출력) &nbsp;&nbsp;
									<input type="radio" name="r_status" value="5" <? if ($r_status == "5") echo "checked" ?>  onClick="check_data();"> 처리중 &nbsp;&nbsp;
									<input type="radio" name="r_status" value="4" <? if ($r_status == "4") echo "checked" ?>  onClick="check_data();"> 완료 &nbsp;&nbsp;
									<input type="radio" name="r_status" value="8" <? if ($r_status == "8") echo "checked" ?>  onClick="check_data();"> 오류(보류)&nbsp;&nbsp;
									
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
									<b><input type="radio" name="rsort" value="member_no" <?if($con_sort == "member_no") echo "checked";?> onClick="check_data();"> 회원번호 </b>
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
			 
			<table cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
				<tr align="center">
					<th width="5%" style="text-align: center;">회원번호</th>
					<th width="5%" style="text-align: center;">성명</th>
					<th width="5%" style="text-align: center;">url</th>
					<th width="5%" style="text-align: center;">신청일자</th>
					<th width="5%" style="text-align: center;">접수 DSC</th>
					<th width="5%" style="text-align: center;">처리상태</th>
					<?php	if (($r_status == 1) || ($r_status == 2) ) {?>
						<th width="5%" style="text-align: center;">선택</th>
					<?php }else if ($r_status == "3"){ ?>
						<th width="5%" style="text-align: center;">접수일자</th>
						<th width="5%" style="text-align: center;">처리중 변경</th>
					<?php }else if ($r_status == "5"){ ?>
						<th width="5%" style="text-align: center;">처리일자</th>
						<th width="5%" style="text-align: center;">완료처리</th>
					<?php }else if($r_status == "4"){?>
						<th width="5%" style="text-align: center;">완료일</th>
						<th width="5%" style="text-align: center;">처리결과</th>
					<?php } else if($r_status == "8"){?>
						<th width="5%" style="text-align: center;">보류일</th>
						<th width="5%" style="text-align: center;">보류사유</th>
					<?php } ?>
	
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
							$working_s = date("Y-m-d", strtotime($obj->working_date));
						
				?>
				<tr>
					<?php if($obj->reg_status != "4"){?>                    
						<td style="width: 5%" align="center"><a href="javascript:onView('<?echo $obj->No?>')"><?echo $obj->member_no?></a></td>
					<?php }else if ($obj->reg_status == "4"){?>
						<td style="width: 5%" align="center"><?echo $obj->member_no?></td>
					<?php }?>
					<td style="width: 5%" align="center"><?echo $obj->member_name?></td>
					<td style="width: 5%" align="center"><a href="<?echo $obj->url?>" target="_blank"><?echo $obj->url?></a><br/>
					                                     <a href="<?echo $obj->url1?>" target="_blank"><?echo $obj->url1?></a><br/>
					                                     <a href="<?echo $obj->url2?>" target="_blank"><?echo $obj->url2?></a><br/>
					                                     <a href="<?echo $obj->url3?>" target="_blank"><?echo $obj->url3?></a><br/>
					                                     <a href="<?echo $obj->url4?>" target="_blank"><?echo $obj->url4?></a></td>
					<td align="center"><?echo $date_s?></td>
					
					<td style="width: 5%" align="center">
						
						<?php 
							if ($obj->dsc_sel == "0") {
								echo "서울DSC";
							} else if ($obj->dsc_sel == "1") {
								echo "인천DSC";
							} else if ($obj->dsc_sel == "2") {
								echo "안산DSC";
							} else if ($obj->dsc_sel == "3") {
								echo "대전DSC";
							} else if ($obj->dsc_sel == "4") {
								echo "광주DSC";
							} else if ($obj->dsc_sel == "5") {
								echo "원주DSC";
							} else if ($obj->dsc_sel == "6") {
								echo "대구DSC";
							} else if ($obj->dsc_sel == "7") {
								echo "부산DSC";
							}
						
						?>
			
					</td>
					
					<td align="center">
						<?	
							if ($obj->reg_status == "1") {
								echo "신청(본인확인)";
							} else if ($obj->reg_status == "2") {
								echo "신청";
							} else if ($obj->reg_status == "3") {
								echo "접수완료";
							} else if ($obj->reg_status == "5") {
								echo "처리중";
							} else if ($obj->reg_status == "4") {
								echo "완료";
							} else if ($obj->reg_status == "8") {
								echo "보류";
							}  
						?>	
					</td>
					
					<?	if (($r_status == 1) || ($r_status == 2)) {?>
						<td align="center"><input type="checkbox" name="CheckItem" value="<?echo $obj->No?>"></td>
					<?	} else if($r_status == 3){?>
						<td align="center"><?php echo $print_s?></td>
			
						<td align="center"><input width="5%" type="button" value="처리중 변경" onClick="goWorking('<?=$obj->No?>');"></td>
					<? } else if($r_status == 4){?>
						<td align="center"><?php echo $confirm_s?></td>
						<td align="center">
							<?php if($obj->endmemo != null){ ?>
							<a href="javascript:textDetail('<?echo $obj->No?>')">
								<?php 
								
									echo iconv_substr($obj->endmemo,0,20,"utf-8")."..."
								?>
							</a>
							<?php }?>
						</td>
					<? }  else if($r_status == 5){?>
						<td align="center"><?php echo $working_s?></td>
						<td align="center"><input width="5%" type="button" value="완료처리" onClick="goConfirm('<?=$obj->No?>');"></td>
					<? } else if($r_status == 8){?>
						<td align="center"><?php echo $wait_s?></td>
						<td align="center"><?php echo $obj->memo?></td>
					<? }  ?>
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
			<table cellspacing="0" cellpadding="10" class="title">
				<tr>
					<td align="left">&nbsp;</td>
					<td align="right" width="600" align="center" bgcolor=silver>
				
				<?  if ($r_status == "2") { ?>
						<input type="button" value="접수확인" onClick="goPrint();">	
				<?	} ?>
				
					</td>
				</tr>
			</table>
			<input type="hidden" name="page" value="<?echo $page?>">
			<input type="hidden" name="con_sort" value="<?echo $con_sort?>">
			<input type="hidden" name="con_order" value="<?echo $con_order?>">
			<input type="hidden" name="reg_status" value="">
			<input type="hidden" name="member_no" value="">
			<input type="hidden" name="confirmDate" value="">
			<input type="hidden" name="workingDate" value="">
		</form>	
	</body>	

	<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</html>		


