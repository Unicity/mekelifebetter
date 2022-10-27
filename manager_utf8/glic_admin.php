<?php session_start();?>
<?php
    include "./admin_session_check.inc";
    include "./inc/global_init.inc";
    include "../dbconn_utf8.inc";
    include "../AES.php";
    include "excel_modal.php";
    $r_status = str_quote_smart(trim($r_status));
    $idxfield = str_quote_smart(trim($idxfield));
    $qry_str = str_quote_smart(trim($qry_str));


    if ($con_order == "con_a") {
        $order = "asc";
    } else {
        $order = "desc";
        $con_order = "con_d";
    }
    
    if (empty($con_sort)) {
        $con_sort = "No";
    }
    
    if (empty($idxfield)) {
        $idxfield = "0";
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
    
    $query = "select count(*) from tb_glicTravel where 1 = 1 ".$que;
    $result = mysql_query($query,$connect);
    $row = mysql_fetch_array($result);
    $TotalArticle = $row[0];
    $query2 = "select * from tb_glicTravel where 1 = 1 ".$que." order by ".$con_sort." ".$order." limit ". $offset.", ".$nPageSize;

    $result2 = mysql_query($query2);


	$sumQuery0804 = "select sum(member) tot from tb_glicTravel where select_date=0804 and flagUD is null ";
	$sum0804 = mysql_query($sumQuery0804);
	$sum0804 = mysql_fetch_array($sum0804);
	$sum0804 = $sum0804[0];
	
	$sumQuery0805 = "select sum(member) tot from tb_glicTravel where select_date=0805 and flagUD is null ";
    $sum0805 = mysql_query($sumQuery0805);
	$sum0805 = mysql_fetch_array($sum0805);
	$sum0805 = $sum0805[0];
  


    //페이지 처리
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
		<title>GLIC 여행 확인</title>
		<link rel="stylesheet" href="./inc/admin.css" type="text/css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	</head>
	<body bgcolor="#FFFFFF">
		<form name="frmSearch" method="post" action="javascript:check_data();">
			
			<table cellspacing="0" cellpadding="10" class="title">
				<tr>
					<td align="left"><b>GLIC 여행 확인</b>/신청인원: 8월4일:<?php echo $sum0804?>명 / 8월5일:<?php echo $sum0805?>명</td>
			
					<td align="right" width="600" align="center" bgcolor=silver>
						<select name="idxfield">
							<option value="0" <?if($idxfield == "0") echo "selected";?>>회원번호</option>
							<option value="1" <?if($idxfield == "1") echo "selected";?>>회원이름</option>
						</select>
						<input type="text" name="qry_str" value="<?echo $qry_str?>">&nbsp;
						<input type="button" value="검색" onClick="onSearch();">
						<?php if($s_adm_id=='alsrnkmg'||$s_adm_id=='ailee'){ ?>
						<input type="button" value="엑셀 다운로드" onClick="onExcel();">
						<?php }?>

					</td>
				</tr>
			</table>
			<table cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
				<tr>
					<th width="5%" style="text-align: center;">회원번호</th>
					<th width="5%" style="text-align: center;">회원명</th>
					<th width="5%" style="text-align: center;">참여인원</th>
					<th width="5%" style="text-align: center;">핸드폰번호</th>
					<th width="5%" style="text-align: center;">신청일자</th>
					<th width="5%" style="text-align: center;">카드</th>
					<th width="5%" style="text-align: center;">카드번호</th>
					<th width="5%" style="text-align: center;">할부</th>
					<!--<th width="5%" style="text-align: center;">유효기간</th>-->
					<th width="5%" style="text-align: center;">생년월일</th>
					<th width="5%" style="text-align: center;">신청일자</th>
					<th width="5%" style="text-align: center;">예약번호</th>
				</tr>
				<?php
					$result2 = mysql_query($query2);
					if ($TotalArticle) {
						while($obj = mysql_fetch_object($result2)) {

							if($obj->payment_card == 'bc'){
								$paymentCard='BC카드';
							}else if($obj->payment_card == 'ss'){
								$paymentCard='삼성카드';
							}else if($obj->payment_card == 'sh'){
								$paymentCard='수협카드';
							}else if($obj->payment_card == 'jb'){
								$paymentCard='전북카드';
							}else if($obj->payment_card == 'kj'){
								$paymentCard='광주카드';
							}else if($obj->payment_card == 'hd'){
								$paymentCard='현대카드';
							}else if($obj->payment_card == 'lt'){
								$paymentCard='롯데카드';
							}else if($obj->payment_card == 'sinhan'){
								$paymentCard='신한카드';
							}else if($obj->payment_card == 'ct'){
								$paymentCard='시티카드';
							}else if($obj->payment_card == 'nh'){
								$paymentCard='농협카드';
							}else if($obj->payment_card == 'kb'){
								$paymentCard='국민카드';
							}else if($obj->payment_card == 'ha'){
								$paymentCard='하나카드';
							}else if($obj->payment_card == 'wo'){
								$paymentCard='우리카드';
							}

							if($obj->select_date=='0804'){
								$selectDate='8월4일';
							}else{
								$selectDate='8월5일';
							}

							$CardNumber = decrypt($key, $iv, $obj-> card_number);
							$CardNumber = substr($CardNumber,0,6)." **"." **** ".substr($CardNumber,12,4);
				?>
				<tr>
				
					<td style="width: 5%" align="center"><a href="javascript:onViewDetail('<?echo $obj->No?>')"><?echo $obj->member_no?></a></td>
					<td style="width: 5%" align="center"><?echo $obj->member_name?></td>
					<td style="width: 5%" align="center"><?echo $obj->member?></td>
					<td style="width: 5%" align="center"><?echo $obj->phone?></td>
					<td style="width: 5%" align="center"><?echo $selectDate?></td>
					<td style="width: 5%" align="center"><?echo $paymentCard?></td>
					<td style="width: 5%" align="center"><?echo $CardNumber?></td>
					<td style="width: 5%" align="center"><?echo $obj->installment?></td>
					<!--<td style="width: 5%" align="center"><?echo $obj->expire_date?></td>-->
					<td style="width: 5%" align="center"><?echo $obj->birthday?></td>
					<td style="width: 5%" align="center"><?echo $obj->create_date?></td>
					<td style="width: 5%" align="center"><?echo $obj->reservationNo?></td>
					
				</tr>
				<?php }
					}
				?>
			</table>
			<table cellspacing="1" cellpadding="5" class="list" border="0">
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
        	<input type="hidden" name="type" value="">
        	<input type="hidden" name="idVal" value="">
        	<input type="hidden" name="RefundNo" value="">
			<input type="hidden" name="r_status" value="">
			
       
		</form>
	</body>
	<script language="javascript">

			function check_data(){
				
				for(i=0; i < document.frmSearch.r_status.length; i++) {
					if (document.frmSearch.r_status[i].checked == true) {
						 
						document.frmSearch.status.value = document.frmSearch.r_status[i].value;
					}
				}
			
				document.frmSearch.action="glic_admin.php";
				document.frmSearch.submit();
			}
			function onSearch(){
				
				
				document.frmSearch.page.value="1";
				document.frmSearch.action="glic_admin.php";
				document.frmSearch.submit();
			}

			function goPage(i) {
			console.log(i);
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

			function toggleCheckbox(element){
				var chkboxes = document.getElementsByName("CheckItem");
		 	
		 		for(var i=0; i<chkboxes.length; i++){
		 			var obj = chkboxes[i];
		 			obj.checked = element.checked;
		 		}
		 	}

		 	function onViewDetail(idVal){
		
				document.frmSearch.idVal.value= idVal

				document.frmSearch.action= "./glic_view.php";
				document.frmSearch.submit();
			 }	

			function NewWindow(mypage, myname, w, h, scroll) {
				var winl = (screen.width - w) / 2;

				var wint = (screen.height - h) / 2;

				winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
				win = window.open(mypage, myname, winprops)
				if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
			}


			function onExcel(){
				var frm = document.frmSearch;
				frm.target = "";
				frm.action = "glic_excel.php";
				frm.submit();
			}

						
	</script>

	<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</html>
