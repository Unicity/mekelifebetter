<?php session_start();?>
<?php
    include "./admin_session_check.inc";
    include "./inc/global_init.inc";
    include "../dbconn_utf8.inc";
    include "../AES.php";
    
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
        $con_sort = "start_date";
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
    
    
    $query = "select count(*) from tb_smember where 1 = 1 ".$que;
    $result = mysql_query($query,$connect);
    $row = mysql_fetch_array($result);
    $TotalArticle = $row[0];
    $query2 = "select * from tb_smember where 1 = 1 ".$que." order by ".$con_sort." ".$order." limit ". $offset.", ".$nPageSize;
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
<htm>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-Frame-Options" content="deny" />
		<title>S회원 관리</title>
		<link rel="stylesheet" href="./inc/admin.css" type="text/css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    </head>
    <body bgcolor="#FFFFFF">
	
<?php include "common_load.php" ?>

        <form name="frmSearch" method="post" action="javascript:check_data();">
            <table cellspacing="0" cellpadding="10" class="title">
				<tr>
					<td align="left"><b>S회원 관리</b></td>
					<td align="right" width="600" align="center" bgcolor=silver>
						<select name="idxfield">
							<option value="0" <?if($idxfield == "0") echo "selected";?>>회원번호</option>
							<option value="1" <?if($idxfield == "1") echo "selected";?>>회원이름</option>
						</select>
						<input type="text" name="qry_str" value="<?echo $qry_str?>">&nbsp;
						<input type="button" value="검색" onClick="onSearch();">
                        <input type="button" value="입력" onClick="onInput();">
					</td>
				</tr>
			</table>
       
			<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
				<tr>
					<td align='center'>
						<table width='99%' bgcolor="#EEEEEE">
							<tr align="center">
								<td align="left">
									<input type="radio" name="r_status" value="A" <? if ($r_status == "A") echo "checked" ?>  onClick="check_data();"> 전체 &nbsp;&nbsp;
									<input type="radio" name="r_status" value="2" <? if ($r_status == "2") echo "checked" ?>  onClick="check_data();"> 일시정지(S) &nbsp;&nbsp;
									<input type="radio" name="r_status" value="3" <? if ($r_status == "3") echo "checked" ?>  onClick="check_data();"> 복귀(A) &nbsp;&nbsp;
									<input type="radio" name="r_status" value="4" <? if ($r_status == "4") echo "checked" ?>  onClick="check_data();"> 해지(T) &nbsp;&nbsp;
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
            <table cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
                <tr>
                    <th width="5%" style="text-align: center;">일지정지(S)일자</th>
					<th width="5%" style="text-align: center;">회원번호</th>
					<th width="5%" style="text-align: center;">성명</th>
					<th width="5%" style="text-align: center;">회원쉽전환 알람일자</th>
					<th width="5%" style="text-align: center;">처리상태</th>
					<th width="5%" style="text-align: center;">오토쉽 유무</th>
					<th width="5%" style="text-align: center;">일시정지(S)사유</th>
                </tr>
                <?php
					$result2 = mysql_query($query2);
					if ($TotalArticle) {
						while($obj = mysql_fetch_object($result2)) {
						    
						    if($obj->reg_status == '2'){
						        $stausVal = '일시정지(S)';
						    }else if($obj->reg_status == '3'){
						        $stausVal = '복귀(A)';
						    }else if($obj->reg_status == '4'){
						        $stausVal = '해지(T)';
						    }else{
						        $stausVal = ' ';
						    }
					
						    $sDate = date("Y-m-d", strtotime($obj->start_date));
						    $eDate = date("Y-m-d", strtotime($obj->end_date));
						    
				?>
				<tr>
					<td style="width: 5%" align="center"><?echo $sDate?></td>
					<td style="width: 5%" align="center"><a href="javascript:onView('<?echo $obj->s_no?>')"><?echo $obj->member_no?> </a></td>
					<td style="width: 5%" align="center"><?echo $obj->member_name?></td>
					<td style="width: 5%" align="center"><?echo $eDate?></td>
					<td style="width: 5%" align="center"><?echo $stausVal?>
					<td style="width: 5%" align="center"><?echo $obj->autoshipYn?></td>
					<td style="width: 5%" align="center"><?echo $obj->note?></td>
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
			<input type="hidden" name="type" value="">
			<input type="hidden" name="idVal" value="">
			<input type="hidden" name="s_no" value="">
			<input type="hidden" name="page" value="<?echo $page?>">
        	<input type="hidden" name="con_sort" value="<?echo $con_sort?>">
        	<input type="hidden" name="con_order" value="<?echo $con_order?>">
        	<input type="hidden" name="status" value="<?echo $status?>">
        </form>
    </body>
    <script language="javascript">
        function check_data(){
				
		    for(i=0; i < document.frmSearch.r_status.length; i++) {
			    if (document.frmSearch.r_status[i].checked == true) {	 
					document.frmSearch.status.value = document.frmSearch.r_status[i].value;
				}
			}
			
				document.frmSearch.action="sMember.php";
				document.frmSearch.submit();
		}
		function onSearch(){
			
			document.frmSearch.page.value="1";
			document.frmSearch.action="sMember.php";
			document.frmSearch.submit();
		}

		function goPage(i) {
			
			document.frmSearch.page.value = i;
			document.frmSearch.submit();
		}

		
        
        function onInput(){
            document.frmSearch.type.value='modify'
		    //document.frmSearch.idVal.value=idVal
		    //document.frmSearch.RefundNo.value=RefundNo	
		    document.frmSearch.action= "sMember_input.php";
		    document.frmSearch.submit();        
        }

		function onView(id){
		document.frmSearch.s_no.value = id; 
		document.frmSearch.action= "sMember_view.php";
		document.frmSearch.submit();
	}	

		




    </script>

	<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</htm>