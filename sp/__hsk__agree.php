<?php 	include "includes/config/common_functions.php";
		
?>
<?php
include "includes/config/config.php";
include "includes/config/nc_config.php";


cert_validation();

$distID = $_POST['distID'];
$distName = $_POST['fullName'];

$_GET['direct']=$_GET['direct']?$_GET['direct']:'n';
if(strpos($_SERVER['HTTP_REFERER'],'ushop-kr')>1) {
	$_GET['direct']='y';
}

//echo $distName;

$query = "select count(*) from tb_change_sponsor where 1 = 1 and sponsor_no =". $distID;

$result = mysql_query($query);
$row = mysql_fetch_array($result);
$TotalArticle = $row[0];
//echo "count".$TotalArticle;
$query2 = "select * from tb_change_sponsor where 1 = 1 and sponsor_no=".$distID;
$result2 = mysql_query($query2);


// 안심본인인증 서비스
$sitecode = $Cb_SiteID;				// Site Code for Unicity Korea
$sitepasswd = $Cb_SitePW;			// Site Password for Unicity Korea


$cb_encode_path = $Cb_encode_path;		// module path (absolute path+module name)
$authtype = "";

$popgubun 	= "N";		//Y : 취소버튼 있음 / N : 취소버튼 없음
$customize 	= "";			//없으면 기본 웹페이지 / Mobile : 모바일페이지

$reqseq = "REQ_0123456789";     // 요청 번호, 이는 성공/실패후에 같은 값으로 되돌려주게 되므로

// 업체에서 적절하게 변경하여 쓰거나, 아래와 같이 생성한다.
$reqseq  = `$Cb_encode_path SEQ $Cb_SiteID`;


// CheckPlus(본인인증) 처리 후, 결과 데이타를 리턴 받기위해 다음예제와 같이 http부터 입력합니다.

$returnurl = (isset($_SERVER['HTTPS']) ? "https" : "https")."://".$_SERVER['HTTP_HOST']."/sp/signup/cert_success.php";	// 성공시 이동될 URL - Success
$errorurl = (isset($_SERVER['HTTPS']) ? "https" : "https")."://".$_SERVER['HTTP_HOST']. "/sp/signup/cert_fail.php";		// 실패시 이동될 URL - Fail

// reqseq값은 성공페이지로 갈 경우 검증을 위하여 세션에 담아둔다.

$_SESSION["REQ_SEQ"] = $reqseq;

$defaultdata = "7:REQ_SEQ" . strlen($reqseq) . ":" . $reqseq .
"8:SITECODE" . strlen($sitecode) . ":" . $sitecode .
"7:RTN_URL" . strlen($returnurl) . ":" . $returnurl .
"7:ERR_URL" . strlen($errorurl) . ":" . $errorurl .
"11:POPUP_GUBUN" . strlen($popgubun) . ":" . $popgubun .
"9:CUSTOMIZE" . strlen($customize) . ":" . $customize ;
// plain data for enc_data.
$plaindata = $defaultdata."9:AUTH_TYPE" . strlen($authtype) . ":". $authtype ;

$authtype = "X";
$plaindataX = $defaultdata."9:AUTH_TYPE" . strlen($authtype) . ":". $authtype ;

$authtype = "M";
$plaindataM = $defaultdata."9:AUTH_TYPE" . strlen($authtype) . ":". $authtype ;

$authtype = "C";
$plaindataC = $defaultdata."9:AUTH_TYPE" . strlen($authtype) . ":". $authtype ;

$enc_data = `$cb_encode_path ENC $sitecode $sitepasswd $plaindata`;

$enc_dataM = `$cb_encode_path ENC $sitecode $sitepasswd $plaindataM`;
$enc_dataX = `$cb_encode_path ENC $sitecode $sitepasswd $plaindataX`;
$enc_dataC = `$cb_encode_path ENC $sitecode $sitepasswd $plaindataC`;


if( $enc_data == -1 )
{
    $returnMsg = "encryption error.";
    $enc_data = "";
}
else if( $enc_data== -2 )
{
    $returnMsg = "encryption process error.";
    $enc_data = "";
}
else if( $enc_data== -3 )
{
    $returnMsg = "encryption data error.";
    $enc_data = "";
}
else if( $enc_data== -9 )
{
    $returnMsg = "input value error.";
    $enc_data = "";
}


// IPIN server  part
$sSiteCode					= $Ip_SiteID;			// IPIN service site code
$sSitePw					= $Ip_SitePW;			// IPIN service site password
$sModulePath				= $Ip_encode_path;			// Module Path
$sReturnURL					= (isset($_SERVER['HTTPS']) ? "https" : "https")."://".$_SERVER['HTTP_HOST']."/korea/signup/pc/includes/ipin_process.php";			// Return utl
$sCPRequest					= "";			// when result == success, we need to store sCPRequest value and verify from the success page

$sCPRequest = `$sModulePath SEQ $sSiteCode`;

$_SESSION['CPREQUEST'] = $sCPRequest;

$sEncData					= "";			// encrypted data
$sRtnMsg					= "";			// return result msg

// Encryption
$sEncData	= `$sModulePath REQ $sSiteCode $sSitePw $sCPRequest $sReturnURL`;

// result
if ($sEncData == -9)
{
    $sRtnMsg = "Input value error : one of the parameters are wrong. fail to encrypt";
} else {
    $sRtnMsg = "$sEncData has to be encrypted now. if now please contact admin.";
}



?>

<htm>
	<head>
    	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    	<title>후원자 변경 동의</title>
    	<meta name="description" content="" />
    	<meta http-equiv="Content-Script-Type" content="text/javascript">
    	<meta http-equiv="Content-Style-Type" content="text/css">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
    	<link rel="stylesheet" type="text/css" href="./css/joo.css" />
    	<style> 
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
                height: 25px;
                display: block;
                position: absolute;
                top: 10px;
                right: 10px;}

            .layerpop_area .layerpop_close:hover {
               
                cursor: pointer; }

            .layerpop_area .content {
                width: 96%;    
                margin: 2%;
                color: #828282; }                                
    </style>
 	</head>
 	<body>
 		<div class="wrapper" >
 			<div class="main_wrapper">
 				<div class="main_box">
						<div class="main_inner_box">
						<!-- 본인인증 -->
						<form name="form_chk" method="post" >
							<input type="hidden" name="m" value="checkplusSerivce">						<!-- Mandatory data. -->
							<input type="hidden" name="EncodeData" value="<?=$enc_data?>">		<!-- encrypted data for unicity korea -->
						    <input type="hidden" name="MEncodeData" value="<?=$enc_dataM?>">
							<input type="hidden" name="CEncodeData" value="<?=$enc_dataC?>">
							<input type="hidden" name="XEncodeData" value="<?=$enc_dataX?>">
						    <!-- extra fields that we can request from the vendor. leave them blank for now -->
							<input type="hidden" name="param_r1" value="">
							<input type="hidden" name="param_r2" value="<?=$_GET['direct']?>">
							<input type="hidden" name="param_r3" value="<?=$distID ?>:<?=$distName ?>">
							<input type="hidden" name="applyDate" value="">
							
						</form>
						<form name="agreeForm" method="post">
							<input type="hidden" name="agreeYn" value="Y">
							<input type="hidden" name="agreeUpdate" value="update">
							<input type="hidden" name="num" value="">
							<input type="hidden" name="distID" value="<?=$distID ?>">;
							<input type="hidden" name="distName" value="<?=$distName ?>">;
							<input type="hidden" name="parentInputName" id="parentInputName">

						</form>
						<table border="1" style="margin: 0px;padding: 0px;">
							<tr>
								<th width="5%" style="text-align: center;"><font color="#FFFFFF">후원인 변경 <br/>신청인 번호</font></th>
								<th width="5%" style="text-align: center;"><font color="#FFFFFF">신청인 이름</font></th>
								<th width="5%" style="text-align: center;"><font color="#FFFFFF">동의</font></th>
								<th width="5%" style="text-align: center;"><font color="#FFFFFF">동의여부</font></th>
								<th width="5%" style="text-align: center;"><font color="#FFFFFF">동의시간</font></th>
							</tr>	
							<?php 
							 $result2 = mysql_query($query2);
							 if ($TotalArticle) {
							 while($obj = mysql_fetch_object($result2)) {
							     
							     if($obj-> sponsor_agree_yn == null || $obj-> sponsor_agree_yn==''){
							         $obj-> sponsor_agree_yn = 'N';
							     }
							     
							     if($obj-> agree_date== '0000-00-00 00:00:00'){
							         $obj-> agree_date = '';
							     }
							?>
							<tr>
 								<td style="width: 5%" align="center"><font color="#FFFFFF"><?echo $obj-> member_no?></font></td>
 								<td style="width: 5%" align="center"><font color="#FFFFFF"><?echo $obj-> member_name?></font></td>
 								<td style="width: 5%" align="center">
 									
 									<input type="button" onclick="popupData(<?echo $obj-> no?>)" value="본인 인증 후 동의" style="background-color: #B2CCFF;width: 150px; border-radius : 5px; text-shadow: 0px -1px 1px rgba(0,0,0,.3); border: 1px solid #4081AF;box-shadow : inset 0 1px 0 rgba(255,255,255,.3), inset 0 0 2px rgba(255,255,255,.3) 0 1px 2px rgba(0,0,0,.29); cursor: pointer;">
 									
 								</td>
								<td style="width: 5%" align="center"><font color="#FFFFFF"><?echo $obj-> sponsor_agree_yn?></font></td>
								<td style="width: 5%" align="center"><font color="#FFFFFF"><?echo $obj-> agree_date?></font></td>
 							</tr>
 								<div id="mask"></div>   		
                   				<div id="layerbox" class="layerpop">
        							<article class="layerpop_area">
        								<div class="title">후원자 변경 동의</div>
        								<br>
        								<div align="center">
        									<div id="agreeText"> </div>
        								
        									<br/>
        									<a href="javascript:fnPopupCb('M');"id="agreeAndConfirm" style="background-color: #B2CCFF;width: 100px; border-radius : 5px; text-shadow: 0px -1px 1px rgba(0,0,0,.3); border: 1px solid #4081AF;box-shadow : inset 0 1px 0 rgba(255,255,255,.3), inset 0 0 2px rgba(255,255,255,.3) 0 1px 2px rgba(0,0,0,.29); cursor: pointer; "">동의 및 본인인증</a>
        									<a href="javascript:popupClose();"id="layerbox_close" style="background-color: #B2CCFF;width: 100px; border-radius : 5px; text-shadow: 0px -1px 1px rgba(0,0,0,.3); border: 1px solid #4081AF;box-shadow : inset 0 1px 0 rgba(255,255,255,.3), inset 0 0 2px rgba(255,255,255,.3) 0 1px 2px rgba(0,0,0,.29); cursor: pointer; "">닫기</a>
        								</div>
        								
        							</article>
								</div>	
							<?php }
							 } else{?>
							 	<tr>
							 		<td colspan="5" align="center"><font color="#FFFFFF">신청 내역이 존재 하지 않습니다.</font></td>
							 	</tr>
							 <?php } ?>					
						</table>
					
					</div>
				</div>	
 			</div>
 		</div>
	</body>
	<script type="text/javascript" src="./js/jquery-1.8.0.min.js"></script>
    	<script type="text/javascript" src='./js/common.js'></script>
    	<script type="text/javascript" src="./js/selectordie.min.js"></script>
    	<script type="text/javascript">
  
    	function fnPopupCb(type){
			
			<?
			if($_GET['direct']!='y'){
			?>
			window.open('', 'popupChk', 'width=408, height=655, top=100, left=100, fullscreen=no, resizable=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');

			<?}?>
		
			document.form_chk.EncodeData.value = document.form_chk.MEncodeData.value;
						
			document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
			<?
			if($_GET['direct']!='y'){
			?>
			document.form_chk.target = "popupChk";
			<?}?>
			document.form_chk.submit();
		}	


    	function go_next_sign(){
			//alert(2);
			var distName = '<?php echo $distName?>'
			var s_nm = $("#parentInputName").val();
			//var s_nm =$_SESSION["S_NM"]
			 //alert(distName);
			
			if(distName ==s_nm ){
				alert("동의가 완료 되었습니다..");
				
				goUpdate();
				
			}else{
				console.log(distName);
				console.log(s_nm);
				 //alert(s_nm);
				alert("본인 이름과 달라 동의 및 인증 하실 수 없습니다.");
				//goUpdate();
			}	
					
 		}



		function popupData(noVal){
		
			$.getJSON('sendVal.php', {'num1': noVal}, function(e) {
			 
			  var html ='<P>회원번호'+e.memberNo+'(이름:<b>'+e.memberName+'</b>) 의 후원자인 본인 <b>'+e.sponsorName+'</b>(회원번호 '+e.sponsorNo+') 은(는) 상기 회원의 후원자를 본인에서 <b>'+ e.chSponsorName+'</b>(회원번호' + e.chSponsorNo+') 로 변경' 
			            +' 하는 것에 동의 합니다.<br/><br/>' 	  
			            +'<font color="red">※주의사항:본 동의는 철회가 불가하고, 후원자 변경은 전월 매출에 영향을 주지 않습니다.</font>' 	  
				  		+'</P>'
				       	$('#agreeText').html(html);
				  		$('[name=num]').val(e.num);
			  wrapWindowByMask();

				$('.layerpop').css("position", "absolute");
		        //영역 가운에데 레이어를 뛰우기 위해 위치 계산 
		        $('.layerpop').css("top",(($(window).height() - $('.layerpop').outerHeight()) / 2) + $(window).scrollTop());
		        $('.layerpop').css("left",(($(window).width() - $('.layerpop').outerWidth()) / 2) + $(window).scrollLeft());
		        //$('.layerpop').draggable();
		        $('#layerbox').show();
		   
			});
			
		}	
 		
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
		    
	    	 wrapWindowByMask();

			$('.layerpop').css("position", "absolute");
	        //영역 가운에데 레이어를 뛰우기 위해 위치 계산 
	        $('.layerpop').css("top",(($(window).height() - $('.layerpop').outerHeight()) / 2) + $(window).scrollTop());
	        $('.layerpop').css("left",(($(window).width() - $('.layerpop').outerWidth()) / 2) + $(window).scrollLeft());
	        //$('.layerpop').draggable();
	        $('#layerbox').show();
	     
	    }

	    function popupClose() {
		
	        $('#layerbox').hide();
	        $('#mask').hide();
	    }


	    function agreeAndConfirm(type){


			<?
			if($_GET['direct']!='y'){
			?>
	    	window.open('', 'popupChk', 'width=408, height=655, top=100, left=100, fullscreen=no, resizable=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
			<?}?>
			if (type == "M") {
				document.form_chk.EncodeData.value = document.form_chk.MEncodeData.value;
			} else if (type == "C") {
				document.form_chk.EncodeData.value = document.form_chk.CEncodeData.value;
			} else if (type == "X") {
				document.form_chk.EncodeData.value = document.form_chk.XEncodeData.value;
			}				
			document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
			<?
			if($_GET['direct']!='y'){
			?>
			document.form_chk.target = "popupChk";
			<?}?>
			document.form_chk.submit();
		}    

		window.name ="Parent_window";
	
	function fnPopupCb(type){
		<?
			if($_GET['direct']!='y'){
			?>
		window.open('', 'popupChk', 'width=408, height=655, top=100, left=100, fullscreen=no, resizable=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
		<?}?>
		if (type == "M") {
			document.form_chk.EncodeData.value = document.form_chk.MEncodeData.value;
		} else if (type == "C") {
			document.form_chk.EncodeData.value = document.form_chk.CEncodeData.value;
		} else if (type == "X") {
			document.form_chk.EncodeData.value = document.form_chk.XEncodeData.value;
		}

		document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
		<?
			if($_GET['direct']!='y'){
			?>
		document.form_chk.target = "popupChk";
			<?}?>
		document.form_chk.submit();
		
	}

		function goUpdate(){
			//alert("1");

			var agreeForm = document.agreeForm;
			//alert(agreeForm.num.value);
			
			agreeForm.action = "sponsor_insert.php";
			agreeForm.submit();
			 $('#layerbox').hide();
		     $('#mask').hide();
			
		}	
    	</script>
    	   	
</htm>