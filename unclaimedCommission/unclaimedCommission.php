<?

    include "./includes/dbconn.php";
    include "../AES.php";
    include "./includes/dbconn_utf8.inc";

    header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
    header('Pragma: no-cache');
    header('Expires: 0');

    if(!isset($_SERVER["HTTPS"])) {
	    header('Location: https://'.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']);
	    exit;
    }

    //강제로 www붙이기
    if(!stristr($_SERVER['HTTP_HOST'],"www.")) {
	    header("location: https://www.".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	    exit;
    }

    $page						= str_quote_smart(trim($page));
	$nPageSize			= str_quote_smart(trim($nPageSize));

    $mAgent = array("iPhone","iPod","Android","Blackberry","Opera Mini", "Windows ce", "Nokia", "sony" );
    $chkMobile = false;
    for($i=0; $i<sizeof($mAgent); $i++){
        if(stripos( $_SERVER['HTTP_USER_AGENT'], $mAgent[$i] )){
            $chkMobile = true;
        break;
        }
    }

    //주민번호 등록 사이트로 부터 
    $flag = $_GET['flag'];
  
    if($flag=='A'){
    
        
        $memID = $_GET['distID'];
        $memID= base64_decode(urldecode($memID ));
    }else{
        $memID = $_POST['sid'];
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


    $memID ='226081382';

    $query = "select count(*) from unclaimedCommission where 1 = 1 and id=".$memID;

    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    $TotalArticle = $row[0];

    $query2 = "select * from unclaimedCommission where 1 = 1 and id=".$memID." limit ". $offset.", ".$nPageSize;;
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

    function getBankCode($code){
        $bankCodes = array(
            "060" => "BOA은행", 
            "263" => "HMC투자증권", 
            "054" => "HSBC은행", 
            "292" => "LIG투자증권",
            "289" => "NH투자증권", 
            "023" => "SC제일은행", 
            "266" => "SK증권", 
            "039" => "경남은행", 
            "034" => "광주은행", 
            "261" => "교보증권", 
            "004" => "국민은행", 
            "003" => "기업은행", 
            "011" => "농협중앙회", 
            "012" => "농협회원조합", 
            "031" => "대구은행", 
            "267" => "대신증권", 
            "238" => "대우증권", 
            "279" => "동부증권",
            "209" => "유안타증권", 
            "287" => "메리츠종합금융증권", 
            "230" => "미래에셋증권", 
            "059" => "미쓰비시도쿄UFJ은행", 
            "058" => "미즈호코퍼레이트은행", 
            "290" => "부국증권", 
            "032" => "부산은행", 
            "002" => "산업은행", 
            "240" => "삼성증권", 
            "050" => "상호저축은행", 
            "045" => "새마을금고연합회", 
            "007" => "수협중앙회", 
            "291" => "신영증권", 
            "076" => "신용보증기금", 
            "278" => "신한금융투자", 
            "088" => "신한은행", 
            "048" => "신협중앙회", 
            "005" => "외환은행", 
            "020" => "우리은행", 
            "247" => "우리투자증권", 
            "071" => "우체국", 
            "280" => "유진투자증권", 
            "265" => "이트레이드증권", 
            "037" => "전북은행", 
            "035" => "제주은행", 
            "264" => "키움증권", 
            "270" => "하나대투증권", 
            "081" => "하나은행", 
            "262" => "하이투자증권", 
            "027" => "한국씨티은행", 
            "243" => "한국투자증권", 
            "269" => "한화증권", 
            "218" => "KB증권", 
            "089" => "케이뱅크",
            "090" => "카카오뱅크" 
        );
        return $bankCodes[$code];
    }
 
?>

<html lang="ko">
    <head>
        <meta charset="utf-8">
        <title>미지급 신청</title>
        <meta name="description" content="" />
        <meta http-equiv="Content-Script-Type" content="text/javascript">
        <meta http-equiv="Content-Style-Type" content="text/css">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
        <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
        <script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script> 
        <link rel="stylesheet" type="text/css" href="./css/joo.css" />
            <style>

                body {
                    font-family: "맑은 고딕"
                }

                /*-- POPUP common style S ======================================================================================================================== --*/
                #mask {
                    position: absolute;
                    left: 0;
                    top: 0;
                    z-index: 999;
                    background-color: #000000;
                    display: none; }

                .layerpop {
                    display: none;
                    z-index: 1000;
                    border: 2px solid #ccc;
                    background: #fff;
                    cursor: move; }

                .layerpop_area .title {
                    padding: 10px 10px 10px 10px;
                    border: 0px solid #aaaaaa;
                    background: #f1f1f1;
                    color: #3eb0ce;
                    font-size: 1.3em;
                    font-weight: bold;
                    line-height: 24px; }

                .layerpop_area .layerpop_close {
                    width: 40px;
                    height: 30px;
                    display: block;
                    position: absolute;
                    top: 10px;
                    right: 0px;
                    background: transparent url('btn_exit_off.png') no-repeat; }

                .layerpop_area .layerpop_close:hover {
                    background: transparent url('btn_exit_on.png') no-repeat;
                    cursor: pointer; }

                .layerpop_area .content {
                    width: 96%;    
                    margin: 2%;
                    color: #828282; }
                /*-- POPUP common style E --*/

        </style>
    </head>
    <body>
        <div>
            <form name="frmSearch" method="post">
                <input type="hidden" name="deleteVal" value="">
                <input type="hidden" name="memId" value="">
                <input type="hidden" name="page" value="<?echo $page?>">
                <div class="wrapper">
                    <!-- container start {-->
                    <div>
                        <div class="figure">
                                <img src="./images/mainlogo.png" alt="유니시티 로고" />
                        </div>
                        <div class="main_inner_box">
                            <div class="main_top">
                                <h1>
                                    <span>미지급 신청</span>
                                </h1>
                            </div>    
                        </div>
                        
                        <table border="1" style="margin: 0px;padding: 0px;">
                            <tr>
                                <th width="2%" style="text-align: center;"><input type="checkbox" id="chckHead" onchange="toggleCheckbox(this);" /></th>
                                <th width="5%" style="text-align: center;"><font color="#FFFFFF">커미션지급일</font></th>
                            <!--   <th width="5%" style="text-align: center;"><font color="#FFFFFF">회원번호</font></th> -->
                                <th width="5%" style="text-align: center;"><font color="#FFFFFF">회원이름</font></th>
                                <th width="5%" style="text-align: center;"><font color="#FFFFFF">금액(원)</font></th>
                                <th width="5%" style="text-align: center;"><font color="#FFFFFF">에러코드</font></th>
                                <th width="5%" style="text-align: center;"><font color="#FFFFFF">처리상태</font></th>
                            <!--   <th width="68%" style="text-align: center;"><font color="#FFFFFF">비고</font></th> -->
                            <!-- 
                                <th width="5%" style="text-align: center;"><font color="#FFFFFF">은행</font></th>
                                <th width="5%" style="text-align: center;"><font color="#FFFFFF">계좌번호</font></th>
                                <th width="5%" style="text-align: center;"><font color="#FFFFFF">계좌주명</font></th>
                            -->  
                            </tr>	
                            <?php 
                    
                                if ($TotalArticle) {
                                    while($obj = mysql_fetch_object($result2)) { 

                                        $newAccountNo = decrypt($key, $iv, $obj->newAccountNo);
                                        $commDate = date("Y-m-d", strtotime($obj->CommissionDate));

                            ?>
                            <tr>
                                <?php if($obj->status != "30"){?>
                                    <td align="center"><input type="checkbox" name="CheckItem" value="<?echo $obj->No?>"></td>
                                <?php }else{?>
                                    <td align="center"></td>
                                <?php }?>    
                                <td style="width: 5%" align="center"><font color="#FFFFFF"><? echo $commDate?></font></td>
                                <!--<td style="width: 5%" align="center"><font color="#FFFFFF"><? echo $obj-> id?></font></td>-->
                                <td style="width: 5%" align="center"><font color="#FFFFFF"><? echo $obj-> memberName?></font></td>
                                <td style="width: 5%" align="center"><font color="#FFFFFF"><? echo number_format($obj-> Amount)?></font></td>
                                <td style="width: 5%" align="center"><font color="#FFFFFF"><?
                                                                                            if($obj-> errorCode=="DEP00043"||$obj-> errorCode=="ETA00310"||$obj-> errorCode=="VAS52903"){
                                                                                                echo "계좌오류";
                                                                                            }else if($obj-> errorCode=="EEF90409"){
                                                                                                echo "법적제한 계좌";       
                                                                                            }else if($obj-> errorCode=="EEF90723"){
                                                                                                echo "사고계좌";       
                                                                                            }else if($obj-> errorCode=="EEF90413"||$obj-> errorCode=="EEF90308"){
                                                                                                echo "수취불가 계좌";      
                                                                                            }else if($obj-> errorCode=="EEF90438"){
                                                                                                echo "압류계좌";       
                                                                                            }else if($obj-> errorCode=="ETA00315"||$obj-> errorCode=="CIB10101"||$obj-> errorCode=="CIB10601"||$obj-> errorCode=="ELB00016"||$obj-> errorCode=="ETA00305"||$obj-> errorCode=="ETA00325"){
                                                                                                echo "압류계좌";       
                                                                                            }else if($obj-> errorCode=="EEF90412"){
                                                                                                echo "잡좌계좌";       
                                                                                            }else if($obj-> errorCode=="EEF90401"||$obj-> errorCode=="EEF90415"){
                                                                                                echo "조회오류";       
                                                                                            }else if($obj-> errorCode=="EEF90411"){
                                                                                                echo "해약계좌";       
                                                                                            }else{
                                                                                                echo $obj-> errorCode;
                                                                                            }
                                                                                            ?>

                                    </font>
                                </td>


                                <td style="width: 5%" align="center"><font color="#FFFFFF"><?	
                                                                                                if ($obj->status == "10") {
                                                                                                    echo "신규등록";
                                                                                                } else if ($obj->status == "20") {
                                                                                                    echo "수정완료";
                                                                                                } else if ($obj->status == "30") {
                                                                                                    echo "지급완료";
                                                                                                } else if ($obj->status == "40") {
                                                                                                    echo "반려";
                                                                                                }  
                                                                                            ?>	</font></td>
                             <!--<td style="width: 68%" align="center"><font color="#FFFFFF"><? echo $obj-> comment?></font></td>-->

                            <!--<td style="width: 5%" align="center"><font color="#FFFFFF"><? echo getBankCode($obj->newBankCode)?></font></td>
                                <td style="width: 5%" align="center"><font color="#FFFFFF"><? echo $newAccountNo ?></font></td>
                                <td style="width: 5%" align="center"><font color="#FFFFFF"><? echo $obj-> newAccountHolder?></font></td> -->
                            
                                                                                                


                            </tr>
                            <?php }
                                    } else{?>
                                    <tr>
                                        <td colspan="10" align="center"><font color="#FFFFFF">신청 내역이 존재 하지 않습니다.</font></td>
                                    </tr>
                            <?php } ?>	
                            
                        
                        </table>
                        <table cellspacing="1" cellpadding="5" border="0" width="100%">
                            <tr>
                                <td align="left">
                                    <font color='#5CD1E5'>
                                        전체 미지급  : <?echo $TotalArticle?> 개
                                    </font>    
                                </td>
                                <td align="right">
                                    <font color='#F6F6F6'>
                                    <?
                                        $Scale = floor(($page - 1) / $PageScale);
                                        if ($TotalArticle > $ListArticle){
                                            if ($page != 1)
                                                echo "[<a href=javascript:goPage('1');><font color='#5CD1E5'>맨앞</font></a>]";
                                                // 이전페이지
                                                    if (($TotalArticle + 1) > ($ListArticle * $PageScale)){
                                                        $PrevPage = ($Scale - 1) * $PageScale;
                                                        if ($PrevPage >= 0)
                                                            echo "&nbsp;[<a href=javascript:goPage('".($PrevPage + 1)."');><font color='#5CD1E5'>이전".$PageScale."개</font></a>]";
                                                    }

                                                echo "&nbsp;";

                                                // 페이지 번호
                                                for ($vj = 0; $vj < $PageScale; $vj++){
                                                    $vk = $Scale * $PageScale + $vj + 1;
                                                    if ($vk < $TotalPage + 1){
                                                        if ($vk != $page)
                                                            echo "&nbsp;[<a href=javascript:goPage('".$vk."');><font color='#5CD1E5'>".$vk."</font></a>]&nbsp;";
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
                                                    echo "&nbsp;[<a href=javascript:goPage('".$TotalPage."');><font color='#5CD1E5'>맨뒤</font></a>]&nbsp;&nbsp;";
                                        }
                                        
                                        else 
                                            echo "&nbsp;[1]&nbsp;";	
                                    ?>
                                    </font>
                                </td>
                            </tr>
	                    </table>
                    </div>
                        <div style='display:block; text-align:center; font-size:20px; font-weight:bold; line-height:60px; background:#11c2c9; color:#fff; width:40%;border-radius:30px; margin-top: 5%;  margin-left: 30%;'>
                                <a href="javascript:goDetail()">미지급 신청</a>
                        </div>
                </div>
                <div id="mask"></div>

                <!--Popup Start -->
                
                <?if($chkMobile) { ?>
                    <div id="layerbox" class="layerpop" style="width: 70%; height: 50%;">
                <?}else{?>
                    <div id="layerbox" class="layerpop" style="width: 30%; height: 60%;">
                <?}?>

                    <article class="layerpop_area">
                    <div class="title">미지급 신청</div>
                        <a href="javascript:popupClose();" class="layerpop_close"id="layerbox_close">닫기</a> <br>
                        <div class="content">
                            <table border="0" cellspacing="1" cellpadding="2">
                                <tr>
                                    <th>
                                        <h3>은행코드 </h3>
                                    </th>
                                </tr>
                                <tr style="height='3%'"></tr>
                                <tr>    
                                    <td><select name="bankCode" id="bankCode">
                                            <option value="">거래은행 선택</option>
                                                        <option value='060'>BOA은행</option>
                                                        <option value='263'>HMC투자증권</option>
                                                        <option value='054'>HSBC은행</option>
                                                        <option value='292'>LIG투자증권</option>
                                                        <option value='289'>NH투자증권</option>
                                                        <option value='023'>SC제일은행</option>
                                                        <option value='266'>SK증권</option>
                                                        <option value='039'>경남은행</option>
                                                        <option value='095'>경찰청</option>
                                                        <option value='034'>광주은행</option>
                                                        <option value='261'>교보증권</option>
                                                        <option value='004'>국민은행</option>
                                                        <option value='003'>기업은행</option>
                                                        <option value='011'>농협중앙회</option>
                                                        <option value='012'>농협회원조합</option>
                                                        <option value='031'>대구은행</option>
                                                        <option value='267'>대신증권</option>
                                                        <option value='238'>대우증권</option>
                                                        <option value='279'>동부증권</option>
                                                        <option value='287'>메리츠종합금융증권</option>
                                                        <option value='230'>미래에셋증권</option>
                                                        <option value='032'>부산은행</option>
                                                        <option value='064'>산림조합중앙회</option>
                                                        <option value='002'>산업은행</option>
                                                        <option value='240'>삼성증권</option>
                                                        <option value='050'>상호저축은행</option>
                                                        <option value='045'>새마을금고연합회</option>
                                                        <option value='007'>수협중앙회</option>
                                                        <option value='291'>신영증권</option>
                                                        <option value='278'>신한금융투자</option>
                                                        <option value='088'>신한은행</option>
                                                        <option value='048'>신협중앙회</option>
                                                        <option value='056'>알비에스은행</option>
                                                        <option value='005'>외환은행</option>
                                                        <option value='020'>우리은행</option>
                                                        <option value='247'>우리투자증권</option>
                                                        <option value='071'>우체국</option>
                                                        <option value='209'>유안타증권</option>
                                                        <option value='280'>유진투자증권</option>
                                                        <option value='265'>이트레이드증권</option>
                                                        <option value='037'>전북은행</option>
                                                        <option value='035'>제주은행</option>
                                                        <option value='090'>카카오뱅크</option>
                                                        <option value='089'>케이뱅크</option>
                                                        <option value='264'>키움증권</option>
                                                        <option value='270'>하나대투증권</option>
                                                        <option value='081'>하나은행</option>
                                                        <option value='262'>하이투자증권</option>
                                                        <option value='027'>한국씨티은행</option>
                                                        <option value='243'>한국투자증권</option>
                                                        <option value='269'>한화증권</option>
                                                        <option value='218'>KB증권</option>
                                        </select>
                                    </td>
                                </tr>    
                                </tr>
                                <tr style="height='3%'"></tr>
                                <tr>
                                    <th>
                                        <h3>계좌번호 </h3>
                                    </th>
                                </tr>
                                <tr style="height='3%'"></tr>
                                <tr>
                                    <td><input type="number" id="newAccountNo" name="newAccountNo" style='width:100%;'  maxlength="30" value="" ></td>
                                </tr>
                                <tr style="height='3%'"></tr>
                                <tr>
                                    <th>
                                        <h3>계좌주명</h3>
                                    </th>
                                </tr>
                                <tr style="height='3%'"></tr>
                                <tr>
                                    <td><input type="text" id="newAccountHolder" name="newAccountHolder" maxlength="30" value=""></td>
                                </tr>
                               
                            </table>
                            <br/>
                            <div>
                                <p>지급일 : 수요일 신청 건 까지 목요일 지급 </p>
                                <P>해당 계좌 정보는 미지급 수당에만 적용 </P>
                                <p><font size="2px;">(후원수당 계좌 변경 : 마이페이지->개인정보수정)</font></p>
                            </div>
                            <div style='display:block; text-align:center; font-size:20px; font-weight:bold; line-height:60px; background:#11c2c9; color:#fff; width:40%;border-radius:30px; margin-top: 20%;  margin-left: 30%;'>
                                <a href="javascript:accountValue()">신청</a>
                            </div>  
                        </div>
                    </article>
                </div>
            </form>
        </div>
    </body>
    
    <script>
        var memId = '<?php echo $memID?>';
        
    
            $( document ).ready(function() {
               if(memId=='' || memId==null){
                    alert("잘못된 접근 입니다. 마이비즈에서 접속 해주세요");
                    document.location = "https://ushop-kr.unicity.com/login";
               }
      
            });


          

        function wrapWindowByMask() {
            //화면의 높이와 너비를 구한다.
            var maskHeight = $(document).height(); 
            var maskWidth = $(window).width();

            //문서영역의 크기 
            console.log( "document 사이즈:"+ $(document).width() + "*" + $(document).height()); 
            //브라우저에서 문서가 보여지는 영역의 크기
            console.log( "window 사이즈:"+ $(window).width() + "*" + $(window).height());        

            //마스크의 높이와 너비를 화면 것으로 만들어 전체 화면을 채운다.
            $('#mask').css({
                'width' : maskWidth,
                'height' : maskHeight
            });

            //애니메이션 효과
            //$('#mask').fadeIn(1000);      
            $('#mask').fadeTo("slow", 0.5);
        }

        function popupOpen() {
            $('.layerpop').css("position", "absolute");
            //영역 가운에데 레이어를 뛰우기 위해 위치 계산 
            $('.layerpop').css("top",(($(window).height() - $('.layerpop').outerHeight()) / 2) + $(window).scrollTop());
            $('.layerpop').css("left",(($(window).width() - $('.layerpop').outerWidth()) / 2) + $(window).scrollLeft());
            $('.layerpop').draggable();
            $('#layerbox').show();
        }

        function popupClose() {
            $('#layerbox').hide();
            $('#mask').hide();
        }

        function goDetail() {

            /*팝업 오픈전 별도의 작업이 있을경우 구현*/ 

            popupOpen(); //레이어 팝업창 오픈 
            wrapWindowByMask(); //화면 마스크 효과 
        }

        function toggleCheckbox(element){
		    var chkboxes = document.getElementsByName("CheckItem");
 	
 		        for(var i=0; i<chkboxes.length; i++){
 			        var obj = chkboxes[i];
 			        obj.checked = element.checked;
 		        }
 	    }

        function accountValue(){
            var newBankCodeVal = $('#bankCode').val();
            var newAccountNo = $("#newAccountNo").val();
            var newAccountHolder = $("#newAccountHolder").val();

            var data = {    
                            "baId": memId,
                            "newBankCodeVal":newBankCodeVal,
							"newAccountNo":newAccountNo,
							"newAccountHolder":newAccountHolder	
				    }

				 $.ajax({
					 'type':'POST',
					 data : { ref1: data },
					    'crossOrigin': true,
					    dataType : "jsonp",
					    jsonpCallback : "myCallback",
					    'url':'../manager_utf8/accountCheck.php',
					    'success':function (result) {
                            var resultVal= result.replyCode;

                            
                            if(resultVal=='0000'){
                                js_register()               
                            }else{
                                alert("계좌 정보를 확인 해주세요.")
                            }
					    
					    } 
				 });

        }

        function js_register(){
            var checkboxes = document.getElementsByName('CheckItem');
            var bankCodeVal = $('#bankCode').val();
            var newAccountNoVal=$('#newAccountNo').val();
            var newAccountHolderVal=$('#newAccountHolder').val();

            var vals = "";
                for (var i=0;i<checkboxes.length;i++) {	    	
                    if (checkboxes[i].checked) {
                        vals += checkboxes[i].value+',';
                    }
                }
		    vals = vals.slice(0, -1); 

            if (vals=='' ||vals== null ){
                alert("신청 체크 하셔야 합니다.");
                return false;
            }
            
            
        
            document.frmSearch.bankCode.value=bankCodeVal;
            document.frmSearch.newAccountNo.value=newAccountNoVal;
            document.frmSearch.newAccountHolder.value=newAccountHolderVal;
            document.frmSearch.deleteVal.value = vals;
            document.frmSearch.memId.value = memId;
            document.frmSearch.action="./unclaimedCommission_update.php";
		    document.frmSearch.submit();

        
        }


        
	function goPage(i) {

		document.frmSearch.page.value = i;
		document.frmSearch.submit();
	}
        	
    </script>

</html>

<?php include_once("./includes/google.php");?>
