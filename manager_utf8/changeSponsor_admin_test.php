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
        $con_sort = "apply_date";
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
    
    if ((empty($r_status)) || ($r_status == "A")) {
        $r_status = "A";
    } else {
        $que = $que." and reg_status = '$r_status' ";
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
    
    $query = "select count(*) from tb_change_sponsor where 1 = 1 ".$que;
    $result = mysql_query($query,$connect);
    $row = mysql_fetch_array($result);
    $TotalArticle = $row[0];
    $query2 = "select * from tb_change_sponsor where 1 = 1 ".$que." order by ".$con_sort." ".$order." limit ". $offset.", ".$nPageSize;
    $result2 = mysql_query($query2);
    
  
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
		<title>후원자 변경 신청</title>
		<link rel="stylesheet" href="./inc/admin.css" type="text/css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	</head>
	<body bgcolor="#FFFFFF">
	
<?php include "common_load.php" ?>

		<form name="frmSearch" method="post" action="javascript:check_data();">
			
			<table cellspacing="0" cellpadding="10" class="title">
				<tr>
					<td align="left"><b>후원자 변경 신청</b></td>
					<td align="right" width="600" align="center" bgcolor=silver>
						<select name="idxfield">
							<option value="0" <?if($idxfield == "0") echo "selected";?>>회원번호</option>
							<option value="1" <?if($idxfield == "1") echo "selected";?>>회원이름</option>
						</select>
						<input type="text" name="qry_str" value="<?echo $qry_str?>">&nbsp;
						<input type="button" value="검색" onClick="onSearch();">
						<input type="button" value="완료" onclick="getCheckedValues();" >
						<input type="button" value="엑셀다운로드" onclick="excelDown();" >
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
									<input type="radio" name="r_status" value="9" <? if ($r_status == "9") echo "checked" ?>  onClick="check_data();"> 익월처리 &nbsp;&nbsp; 
									<input type="radio" name="r_status" value="3" <? if ($r_status == "3") echo "checked" ?>  onClick="check_data();"> 완료 &nbsp;&nbsp;
									<input type="radio" name="r_status" value="8" <? if ($r_status == "8") echo "checked" ?>  onClick="check_data();"> 보류&nbsp;&nbsp; 
									<input type="radio" name="r_status" value="4" <? if ($r_status == "4") echo "checked" ?>  onClick="check_data();"> 신청 거부&nbsp;&nbsp;
								</td>
								
							</tr>
						</table>
					</td>
				</tr>
			</table>
		
			<table cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
				<tr>
					<th width="2%" style="text-align: center;"><input type="checkbox" id="chckHead" onchange="toggleCheckbox(this);" /></th>
					<th width="5%" style="text-align: center;">신청일자</th>
					<th width="5%" style="text-align: center;">신청인 번호</th>
					<th width="5%" style="text-align: center;">신청인</th>
					<th width="5%" style="text-align: center;">신청인 주소</th>
					<th width="5%" style="text-align: center;">가입일자</th>
					<th width="5%" style="text-align: center;">현재 후원자 번호</th>
					<th width="5%" style="text-align: center;">현재 후원인</th>
					<th width="5%" style="text-align: center;">변경 후원자 번호</th>
					<th width="5%" style="text-align: center;">변경 후원인</th>
					<th width="5%" style="text-align: center;">동의 여부</th>
					<th width="5%" style="text-align: center;">동의 날짜</th>
					<th width="5%" style="text-align: center;">처리상태</th>
				</tr>
				<?php
					$result2 = mysql_query($query2);
					if ($TotalArticle) {
						while($obj = mysql_fetch_object($result2)) {
						    
						    if($obj->reg_status == '2'){
						        $stausVal = '신청';
						    }else if($obj->reg_status == '3'){
						        $stausVal = '완료';
						    }else if($obj->reg_status == '9'){
						        $stausVal = '익월처리';
						    }else if($obj->reg_status == '8'){
						        $stausVal = '보류';
						    }else if($obj->reg_status == '4'){
						        $stausVal = '신청거부';
						    }else{
						        $stausVal = ' ';
						    }
						    
						    $date_entry = date("Y-m-d", strtotime($obj->entry_date));
							
						    if($date_entry=='1970-01-01'){
						        $date_entry = '';
						    }
				?>
				<tr>
					<td align="center"><input type="checkbox" name="CheckItem" value="<?echo $obj->no?>"></td>
					<td style="width: 5%" align="center"><?echo $obj->apply_date?></td>
					<td style="width: 5%" align="center"><a href="javascript:onViewDetail('<?echo $obj->no?>')"><?echo $obj->member_no?></a></td>
					<td style="width: 5%" align="center"><?echo $obj->member_name?></td>
					<td style="width: 5%" align="center"><?echo $obj->address?></td>
					<td style="width: 5%" align="center"><?echo $obj->entry_date?></td>
					<td style="width: 5%" align="center"><?echo $obj->sponsor_no?></td>
					<td style="width: 5%" align="center"><?echo $obj->sponsor_name?></td>
					<td style="width: 5%" align="center"><?echo $obj->ch_sponsor_no?></td>
					<td style="width: 5%" align="center"><?echo $obj->ch_sponsor_name?></td>
					<td style="width: 5%" align="center"><?echo $obj->sponsor_agree_yn?></td>
					<td style="width: 5%" align="center"><?echo $obj->agree_date?></td>
					<td style="width: 5%" align="center"><?echo $stausVal?></td>
					
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
        	<input type="hidden" name="status" value="<?echo $status?>">
        	<input type="hidden" name="type" value="">
        	<input type="hidden" name="idVal" value="">
        	<input type="hidden" name="RefundNo" value="">
       
		</form>
	</body>
	<script language="javascript">

			function check_data(){
				
				for(i=0; i < document.frmSearch.r_status.length; i++) {
					if (document.frmSearch.r_status[i].checked == true) {
						 
						document.frmSearch.status.value = document.frmSearch.r_status[i].value;
					}
				}
			
				document.frmSearch.action="changeSponsor_admin_test.php";
				document.frmSearch.submit();
			}
			function onSearch(){
				
				
				document.frmSearch.page.value="1";
				document.frmSearch.action="changeSponsor_admin_test.php";
				document.frmSearch.submit();
			}

			function goPage(i) {

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

				document.frmSearch.action= "./changeSponsor_view_test.php";
				document.frmSearch.submit();
			 }	

			function getCheckedValues(){
				var checkboxes = document.getElementsByName('CheckItem');

				var vals = "";
				for (var i=0;i<checkboxes.length;i++) 
				{
			    	if (checkboxes[i].checked) 
		    		{
		        	vals += checkboxes[i].value+',';
		    		}
				}
				vals = vals.slice(0, -1); 
				alert(vals);	
				url = 'changeSponsor_task.php?data='+btoa(vals);
			 	NewWindow(url, '일괄처리', 350, 250, 'no');
			 
			}

			function NewWindow(mypage, myname, w, h, scroll) {
				var winl = (screen.width - w) / 2;

				var wint = (screen.height - h) / 2;

				winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
				win = window.open(mypage, myname, winprops)
				if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
			}


			

			function excelDown(){
				var frm = document.frmSearch;
				frm.target = "";
				frm.action = "changeSponsor_excel.php";
				frm.submit();
			}

			function goExecl(){
				var frm = document.frmSearch;
				frm.target = "";
				frm.action = "changeSponsor_excel.php";
				frm.submit();
			}
			
						
	</script>

	<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</html>
