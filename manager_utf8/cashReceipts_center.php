<?php session_start();?>
<?php
    include "./admin_session_check.inc";
    include "./inc/global_init.inc";
    include "../dbconn_utf8.inc";
    include "../AES.php";
    include "./excel_modal.php";
    $r_status = str_quote_smart(trim($r_status));
    $idxfield = str_quote_smart(trim($idxfield));
    $qry_str = str_quote_smart(trim($qry_str));
    $s_adm_dept = str_quote_smart_session($s_adm_dept);



    if ($con_order == "con_a") {
        $order = "asc";
    } else {
        $order = "desc";
        $con_order = "con_d";
    }

    if (empty($con_sort)) {
        $con_sort = "entry_date";
    }

        
    switch ($idxfield) {
	    
        case '6':
            $que = $que." and back_no = $qry_str ";
            break;
        case '1':
            $que = $que." and order_no = $qry_str ";
            break;
        case '2':
            $que = $que." and member_no = $qry_str ";
            break;
        case '3':
            $que = $que." and member_name like '%$qry_str%' ";
            break;
        case '4':
            $que = $que." and s_date between '$qry_str' and '$qry_str1' ";
            break;
            
        default:
            $que = $que." ";
            break;
    }
    
    
    switch ($DSCSelect) {
        
        case '1':
            $que = $que." and center = 'Seoul DSC' ";
            break;
        case '2':
            $que = $que." and center = 'Incheon DSC' ";
            break;
        case '3':
            $que = $que." and center = 'Ansan DSC' ";
            break;
        case '4':
            $que = $que." and center = 'Daejeon DSC' ";
            break;
        case '5':
            $que = $que." and center = 'Gwangju DSC' ";
            break;
        case '6':
            $que = $que." and center = 'Wonju DSC' ";
            break;
        case '7':
            $que = $que." and center = 'Daegu DSC' ";
            break;
        case '8':
            $que = $que." and center = 'Busan DSC' ";
            break;
        case '9':
            $que = $que." and center = 'IT' ";
            break;
       case '10':
           $que = $que." and center = 'Jeju DSC' ";
           break;
            
        default:
            $que = $que." ";
            break;
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

    $query = "select count(*) from tb_cashReceipts where 1 = 1 ".$que;
    $result = mysql_query($query,$connect);
    $row = mysql_fetch_array($result);
    $TotalArticle = $row[0];

    $query2 = "select * from tb_cashReceipts where 1 = 1 ".$que." order by ".$con_sort." ".$order." limit ". $offset.", ".$nPageSize;
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
		<title>현금영수증발행_센터</title>
		<link rel="stylesheet" href="./inc/admin.css" type="text/css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <style>
            * {
            margin: 0;
            padding: 0;
            }

            .pop-layer .pop-container {
            padding: 20px 25px;
            }

            .pop-layer p.ctxt {
            color: #666;
            line-height: 25px;
            }

            .pop-layer .btn-r {
            width: 100%;
            margin: 10px 0 20px;
            padding-top: 10px;
            border-top: 1px solid #DDD;
            text-align: right;
            }

            .pop-layer {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            width: 410px;
            height: auto;
            background-color: #fff;
            border: 5px solid #000000;
            z-index: 10;
            }

            .dim-layer {
            display: none;
            position: fixed;
            _position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 100;
            }

            .dim-layer .dimBg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #000;
            opacity: .5;
            filter: alpha(opacity=50);
            }

            .dim-layer .pop-layer {
            display: block;
            }

            a.btn-layerClose {
            display: inline-block;
            height: 25px;
            padding: 0 14px 0;
            border: 1px solid #304a8a;
            background-color: #3f5a9d;
            font-size: 13px;
            color: #fff;
            line-height: 25px;
            }

            a.btn-layerClose:hover {
            border: 1px solid #091940;
            background-color: #1f326a;
            color: #fff;
            }
        </style> 
        
	</head>
    <body>
        <form name="frmSearch" method="post">
            <input type="hidden" name="page" value="<?echo $page?>">
            <input type="hidden" name="con_sort" value="<?echo $con_sort?>">
            <input type="hidden" name="con_order" value="<?echo $con_order?>">
            <input type="hidden" name="status" value="<?echo $status?>">
            <input type="hidden" name="type" value="">
            <input type="hidden" name="idVal" value="">
            <input type="hidden" name="RefundNo" value="">
            <input type="hidden" name="deleteVal" value="">
            <input type="hidden" name="cashNum" value="">
            <input type="hidden" name="que" value="<?echo $que?>">
            <table cellspacing="0" cellpadding="0 " class="title" border="0" width="100%">
				<tr>
					<td align="left"><b>현금영수증발행_센터</b></td>
					<td align="right">
                        <input type="button" value="입력" onclick="insert('new');" >
                        <input type="button" value="엑셀 업로드" onclick="layer_popup('#layer1');" >
						<input type="button" value="엑셀 다운로드" onclick="goExcelHistory('주문관리','현금영수증발행_센터','전체','cashReceipts_excel_list.php')" >
                        <input type="button" value="일괄삭제" onclick="goDelete()" >
					</td>
				</tr>
			</table>

            <table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
                <tr>
                    <td align='center'>
                        <table width='99%' bgcolor="#EEEEEE">
                            <tr align="center">
                                <td align="left">
                                    
                                    <span>※ DSC선택</span>
                                    <select name="DSCSelect">
                                        <option value="" >전체</option>
                                        <option value="9" <?if($DSCSelect == "9") echo "selected";?>>IT</option>
                                        <option value="1" <?if($DSCSelect == "1") echo "selected";?>>서울</option>
                                        <option value="2" <?if($DSCSelect == "2") echo "selected";?>>인천</option>
                                        <option value="3" <?if($DSCSelect == "3") echo "selected";?>>안산</option>
                                        <option value="4" <?if($DSCSelect == "4") echo "selected";?>>대전</option>
                                        <option value="5" <?if($DSCSelect == "5") echo "selected";?>>광주</option>
                                        <option value="6" <?if($DSCSelect == "6") echo "selected";?>>원주</option>
                                        <option value="7" <?if($DSCSelect == "7") echo "selected";?>>대구</option>
                                        <option value="8" <?if($DSCSelect == "8") echo "selected";?>>부산</option>
                                        <option value="10" <?if($DSCSelect == "10") echo "selected";?>>제주</option>
                                    </select>
                    
                                    <select name="idxfield" onchange="changeSelect(this)">
                                        <option value="">선택</option>			
                                        <option value="6" <?if($idxfield == "6") echo "selected";?>>반품번호</option>
                                        <option value="1" <?if($idxfield == "1") echo "selected";?>>주문번호</option>
                                        <option value="2" <?if($idxfield == "2") echo "selected";?>>회원번호</option>
                                        <option value="3" <?if($idxfield == "3") echo "selected";?>>회원이름</option>
                                        <option value="4" <?if($idxfield == "4") echo "selected";?>>매출일시</option>                    
                                    <!-- 
                                            <option value="5" <?if($idxfield == "5") echo "selected";?>>dsc</option>
                                -->
                                    </select>
                                    <input type="text" name="qry_str" value="<?echo $qry_str?>" onKeyPress="enterPressed(event,'search')" >&nbsp;
                                    <input type="text" name="qry_str1" value="<?echo $qry_str1?>" onKeyPress="enterPressed(event,'search')" >&nbsp;
                                    <input type="button" value="검색" onClick="onSearch1();">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
	        </table>

            <table cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
				<tr>
                    <th width="2%" style="text-align: center;">선택</th>
					<th width="6%" style="text-align: center;">매출일시</th>
					<th width="6%" style="text-align: center;">회원번호</th>
					<th width="6%" style="text-align: center;">회원명</th>
					<th width="6%" style="text-align: center;">주문번호</th>
                    <th width="6%" style="text-align: center;">반품번호</th>
					<th width="6%" style="text-align: center;">금액</th>
                    <th width="6%" style="text-align: center;">신분확인방법</th>
					<th width="6%" style="text-align: center;">신분확인번호</th>
					<th width="6%" style="text-align: center;">승인번호</th>
                    <th width="6%" style="text-align: center;">취소승인번호</th>
					<th width="6%" style="text-align: center;">발행상태</th>
                    <th width="6%" style="text-align: center;">취소사유</th>
                    <th width="6%" style="text-align: center;">센터명</th>
				</tr>
				<tr>
                <?php
					$result2 = mysql_query($query2);
					if ($TotalArticle) {
						while($obj = mysql_fetch_object($result2)) {
                            $amount =number_format($obj-> amount);
				?>
				<tr>
                <?php if($obj->center != $s_adm_dept){?>
                    <td align="center"><input type="checkbox" name="CheckItem"value="" disabled="disabled"></td>
				<?php }else if ($obj->center == $s_adm_dept){?>
                    <td align="center"><input type="checkbox" name="CheckItem1"value="<?echo $obj->cash_num?>"></td>
                <?php } ?>    
                    <td style="width: 5%" align="center"><?echo $obj->s_date?></td>
					<td style="width: 5%" align="center"><?echo $obj->member_no?></td>
					<td style="width: 5%" align="center"><?echo $obj->member_name?></td>
					<td style="width: 5%" align="center"><a href="javascript:onViewDetail('<?echo $obj->member_no?>','<?echo $obj->order_no?>','<?echo $obj->cash_num?>')"><?echo $obj->order_no?></a></td>
                    <td style="width: 5%" align="center"><?echo $obj->back_no?></td>
					<td style="width: 5%" align="center"><?echo $amount?></td>
					<td style="width: 5%" align="center"><?echo $obj->check_text?></td>
					<td style="width: 5%" align="center"><?echo $obj->check_num?></td>
					<td style="width: 5%" align="center"><?echo $obj->approval_num?></td>
                    <td style="width: 5%" align="center"><?echo $obj->cancel_no?></td>
					<td style="width: 5%" align="center"><?echo $obj->check_result?></td>
					<td style="width: 5%" align="center"><?echo $obj->cancel_reason?></td>
					<td style="width: 5%" align="center"><?echo $obj->center2?></td>
					
                </tr>
                    <?php }
                        }
                    ?>
				</tr>
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
                            }else 
                                echo "&nbsp;[1]&nbsp;";	
                        ?>
                    </td>
                </tr>
	        </table>
        </form> 
        <div id="layer1" class="pop-layer">
            <div class="pop-container">
                <div class="pop-conts">
                    
                    <form enctype="multipart/form-data" action="./excel_read.php" method="post">
                        <table border="1">	
                            <tr>		
                                <th style="background-color:#DCDCDC">파일</th>		
                                <td><input type="file" name="excelFile"/></td>	
                            </tr>	
                            <tr>		
                                <th style="background-color:#DCDCDC">등록</th>		
                                <td style="text-align:center;"><input type="submit" value="업로드"/></td>	
                            </tr>
                        </table>    
                    </form>
                    <div class="btn-r">
                        <a href="#" class="btn-layerClose">Close</a>
                    </div>
                    <!--// content-->
                </div>
            </div>
        </div>
    </body>
    <script language="javascript">

    var selVal = document.frmSearch.idxfield.value;
    $(document).ready(function() {
        
    	if(selVal == '4'){
			$('[name=qry_str1]').show();
		}else{
			$('[name=qry_str1]').hide();
		}		
    });

        function insert(type){
      
            document.frmSearch.type.value=type
            document.frmSearch.action= "./cashReceipts_center_view.php";
		    document.frmSearch.submit();
        }
    
/*
    $('.btn-example').click(function(){
        var $href = $(this).attr('href');
        layer_popup($href);
    });
  */
    function layer_popup(el){
        
        var $el = $(el);    //레이어의 id를 $el 변수에 저장
        var isDim = $el.prev().hasClass('dimBg'); //dimmed 레이어를 감지하기 위한 boolean 변수

        isDim ? $('.dim-layer').fadeIn() : $el.fadeIn();

        var $elWidth = ~~($el.outerWidth()),
            $elHeight = ~~($el.outerHeight()),
            docWidth = $(document).width(),
            docHeight = $(document).height();

        // 화면의 중앙에 레이어를 띄운다.
        if ($elHeight < docHeight || $elWidth < docWidth) {
            $el.css({
                marginTop: -$elHeight /2,
                marginLeft: -$elWidth/2
            })
        } else {
            $el.css({top: 0, left: 0});
        }

        $el.find('a.btn-layerClose').click(function(){
            isDim ? $('.dim-layer').fadeOut() : $el.fadeOut(); // 닫기 버튼을 클릭하면 레이어가 닫힌다.
            return false;
        });

        $('.layer .dimBg').click(function(){
            $('.dim-layer').fadeOut();
            return false;
        });

    }

    function onViewDetail(idVal,RefundNo,cashNum){
		document.frmSearch.type.value='modify'
		document.frmSearch.idVal.value=idVal
		document.frmSearch.RefundNo.value=RefundNo
        document.frmSearch.cashNum.value=cashNum
		document.frmSearch.action= "./cashReceipts_center_view.php";
		document.frmSearch.submit();

	}	
    function goPage(i) {
		document.frmSearch.page.value = i;
		document.frmSearch.submit();
	}
    function onSearch1(){
		if(selVal == '4'){
			$('[name=qry_str1]').show();
		}else{
			$('[name=qry_str1]').hide();
		}		
		
			document.frmSearch.page.value="1";
			document.frmSearch.action="./cashReceipts_center.php";
			document.frmSearch.submit();
		}

    function enterPressed(event, type){
		if (window.event.keyCode == 13){
    	    onSearch();	
        }
	}

    function onSearch(){
        document.frmSearch.page.value="1";
        document.frmSearch.action="./cashReceipts_center.php";
        document.frmSearch.submit();
    }

    function changeSelect(obj){
        if(obj.value == '4'){
            $('[name=qry_str1]').show();
        }else{
            $('[name=qry_str1]').hide();
        }		
    } 

    function toggleCheckbox(element){
		var chkboxes = document.getElementsByName("CheckItem");
 	
 		for(var i=0; i<chkboxes.length; i++){
 			var obj = chkboxes[i];
 			obj.checked = element.checked;
 		}
 	}

    function goDelete(){
        
        if(confirm("일괄 삭제 진행 하시겠습니까?")){
            var checkboxes = document.getElementsByName('CheckItem1');
        

            var vals = "";
            for (var i=0;i<checkboxes.length;i++) {	    	
                if (checkboxes[i].checked) {
                    vals += checkboxes[i].value+',';
                }
            }
            vals = vals.slice(0, -1); 
        
            document.frmSearch.deleteVal.value=vals
        
            document.frmSearch.action= "./cashReceipts_center_delete.php";
            document.frmSearch.submit();
        }
    }


    function excelDown(){

        var frm = document.frmSearch;
            frm.target = "";
            frm.action = "cashReceipts_centerExcel.php";
            frm.submit();
    }
    </script>
</html>    
