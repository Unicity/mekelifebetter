<?php 	include "includes/config/common_functions.php";
		
?>
<?php 
	@session_start();
    include "includes/config/config.php";
    include "includes/config/nc_config.php";
    cert_validation();


	$parentInputName ='';
	$chNum ='';
	$chSponsorName ='';
	if($_POST['jsonValue']){
		$initData = json_decode(base64_decode($_POST['jsonValue']),1);
		
//		echo '<pre>';
//		print_R($initData['_POST']);
//echo '</pre>';
//		
		$parentInputName = $_POST['parentInputName'];
		$chNum = $initData['_POST']['chNum'];
		$chSponsorName = $initData['_POST']['chSponsorName'];
//		
		
//
		$_POST = json_decode(str_replace('\"','"',$initData['_POST']['_POST']),1);
		$_POST['action']='changeSponsor';
	}
	$distID = $_POST['fId'];
	$distName = $_POST['fName'];
	$rsponsorId = $_POST['sponsorId'];
	$rsponsorName = $_POST['sponsorName'];
	$fAddress = $_POST['fAddress'];
	$entryDate = $_POST['entryDate'];
	$mobilePhone = $_POST['mobilePhone'];
	$appCheck = $_POST['appYn'];
	$checkName = $_POST['checkName'];
	$baName = $_POST['baName'];
	$phoneCheck = $_POST['phoneCheck'];
	$appYn = $_POST['mYn'];
	$parentInputName = $_POST['parentInputName'];
	$jsonValue = $_POST['jsonValue'];
$_GET['direct']=$_GET['direct']?$_GET['direct']:'n';

	if($appCheck=='Y'){


		if(strpos($_SERVER['HTTP_REFERER'],'ushop-kr')>1) {

			$mobile_agent = "/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/";
			if(preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT'])){
				$_GET['mobile']='y';
			}else{
				
			}
			
		}

		$_SESSION['fAddress'] = $fAddress; 
		$_SESSION['sponsorId'] = $rsponsorId; 
		$_SESSION['sponsorName'] = $rsponsorName; 
		$_SESSION['entryDate'] = $entryDate; 
		$_SESSION['appYn'] = $appCheck; 
		$_SESSION['baName'] = $distName ; 
		

	}

	
	$sponsorId= substr($rsponsorId, 0, -3).'*';
	$sponsorName = substr($rsponsorName, 0, -3).'**';
	//echo $entryDate;
	
	
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
	

	$returnurl = (isset($_SERVER['HTTPS']) ? "https" : "https")."://".$_SERVER['HTTP_HOST']."/sp/signup/cert_success.php?flagId=$distID";
	
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
	//print_r($_POST);
	
	
	?>
	
	
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>후원자 변경 신청</title>
		<meta name="description" content="" />
		<meta http-equiv="Content-Script-Type" content="text/javascript">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
		<link rel="stylesheet" type="text/css" href="./css/joo.css" />
	</head>
	<body>
		<div class="wrapper" >
			<div class="main_wrapper">
				<div class="figure">
					<img src="./images/mainlogo.png" alt="유니시티 로고" />
				</div>
				<div class="main_box">
					<div class="main_inner_box">
						<div class="main_top">
							<h1>
								<span>후원자 변경 신청</span>
							</h1>
						</div>
						<!-- 본인인증 -->
						<form name="form_chk" method="post" >
							<input type="hidden" name="m" value="checkplusSerivce">						<!-- Mandatory data. -->
							<input type="hidden" name="EncodeData" value="<?=$enc_data?>">		<!-- encrypted data for unicity korea -->
						    <input type="hidden" name="MEncodeData" value="<?=$enc_dataM?>">
							<input type="hidden" name="CEncodeData" value="<?=$enc_dataC?>">
							<input type="hidden" name="XEncodeData" value="<?=$enc_dataX?>">
						    <!-- extra fields that we can request from the vendor. leave them blank for now -->
							<input type="hidden" name="param_r1" id="param_r1" value="">
							<input type="hidden" name="param_r2" value="<?=$_GET['mobile']?>">
							<input type="hidden" name="param_r3" id="param_r3" value="<?//=($_GET['mobile']=='y'?base64_encode(json_encode(array('mobile'=>$_GET['mobile'],'_POST'=>json_encode_han($_POST)))):'')?>">
							<input type="hidden" name="applyDate" value="">
							
							

						</form>
						<form name="agreeForm">
							<input type="hidden" name="rsponsorId" value="<?php echo $rsponsorId?>">
							<input type="hidden" name="rsponsorName" value="<?php echo $rsponsorName?>">
							<input type="hidden" name="reg_status" value="2"/>
							<input type="hidden" name="parentInputName" id="parentInputName" value="<?php echo $_SESSION["S_NM"]?$_SESSION["S_NM"]:$parentInputName;?>">
							<input type="hidden" name="entry_date" value="<?php echo $entryDate?>">
							<input type="hidden" name="fAddress" value="<?php echo $fAddress?>">
							<input type="hidden" name="mobilePhone" value="<?php echo $mobilePhone?>">
							<input type="hidden" name="app_yn" value="<?php echo $appCheck?>">
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
							<div class="wrap_input">
								<div class="member">
									<h2 style="float: left; margin-top: 9px;">회원 번호&nbsp;&nbsp;&nbsp;&nbsp;</h2>
									<div class="wrap">
										<input type="text"  name="distID" id="distID" value="<?php echo $distID ?>" readonly="readonly" style="width: 30%" />
									</div>
									<h2 style="float: left; margin-top: 9px;">회원 성명&nbsp;&nbsp;&nbsp;&nbsp;</h2>
									<div class="wrap">
										<input type="text"  name="distName" id="distName" readonly="readonly" value="<?php echo $distName?>" style="width: 30%" />
									</div>
									<div style="height: 40px;"></div>
									<h4>요청내용</h4>
									<div style="height: 10px;"></div>
									<h2 style="float: left; margin-top: 9px;">현재 후원자 번호&nbsp;</h2>
									<div class="wrap">
										<input type="text"  name="sponsorID" id="sponsorID" readonly="readonly" value="<?php echo $sponsorId?>" style="width: 30%" />
									</div>
									<h2 style="float: left; margin-top: 9px;">현재 후원자 성명&nbsp;</h2>
									<div class="wrap">
										<input type="text" name="sponsorName" id="sponsorName" readonly="readonly" value="<?php echo $sponsorName?>" style="width: 30%" />
									</div>
									<h2 style="float: left; margin-top: 9px;">변경 후원자 번호&nbsp;</h2>
									<div class="wrap">
										<input type="text" name="chSponsorID" id="chSponsorID"   style="width: 30%" value="<?php echo $chNum?>"/>

												<a href="javascript:js_search11()" style="height: 40px">확인</a>
									</div>
									<h2 style="float: left; margin-top: 9px;">변경 후원자 성명&nbsp;</h2>
									<div class="wrap">
										<input type="text"  name="chSponsorName" id="chSponsorName" readonly="readonly" value="<?php echo $chSponsorName?>" style="width: 30%" />
									</div>
									
									
								</div>
								<div align="center" style="background-color: #B2CCFF;width: 100px;margin-left: 40%; border-radius : 5px; text-shadow: 0px -1px 1px rgba(0,0,0,.3); border: 1px solid #4081AF;box-shadow : inset 0 1px 0 rgba(255,255,255,.3), inset 0 0 2px rgba(255,255,255,.3) 0 1px 2px rgba(0,0,0,.29); cursor: pointer; ">
									<a href="javascript:fnPopupCb('M')"><b>휴대폰 인증</b></a>
								</div>
								<div id="applyShow" style="display: none;">
    								<div>
    									<h4>동의 및 주의 사항</h4>
    									<table border="1" style="margin: 0px;padding: 0px">
    										<tr>
    											<td>1. 후원자 변경은 후원자 정보의 단순 기재 오류 등으로 이루어진 가입 건에 한하여 <font color="red"><b>회원가입 완료일부터 30일이내 신청인의 접수와 후원인의 동의까지 모두 완료된 건만이 처리 가능합니다.</b></font>&nbsp;타라인 이동에 해당하는 변경 신청은 처리 불가합니다. </td>
    										</tr>
    										<tr>
    											<td>2. 후원자 변경은 전월 매출에 영향을 끼치지 않습니다. 변경 대상 회원과 현재 후원인, 새로운 후원인의 전월 매출은 변경되지 않으며, 전월 마감 그대로 유지되고, 전월가입자의 변경 처리는 익월 15~20일 사이에 이루어 집니다.</td>
    										</tr>	
    										<tr>
    											<td>3. 후원자 변경의 취소 및 추가 변경은 불가합니다.  </td>
    										</tr>
    																
    									</table>
    								</div>
    								<div style="height: 5px;"></div>
    								<div>
    									<h4>본인은 동의 및 주의사항을 모두 읽고 이해하였으며, 이에 동의하며, 요청 내용과 같이 후원자 변경을 신청합니다 <input type="checkbox" id="chkVal" /></h4>
    								</div>
    								
    								<div id="applyBtn" align="center" style=" display:block; background-color: #B2CCFF;width: 100px;margin-left: 40%; border-radius : 5px; text-shadow: 0px -1px 1px rgba(0,0,0,.3); border: 1px solid #4081AF;box-shadow : inset 0 1px 0 rgba(255,255,255,.3), inset 0 0 2px rgba(255,255,255,.3) 0 1px 2px rgba(0,0,0,.29); cursor: pointer; ">
    									<a href="javascript:apply()"><b>신청하기</b></a>
    								</div>
								</div>
								<div id="mask"></div>   		
                           		<div id="layerbox" class="layerpop">
									<article class="layerpop_area">
										<div class="title" style="text-align: center">현재 후원인에게<br/> 후원자 변경 동의 문자 발송하기</div>
										<br>
										<div align="center">
    										<input  name="phoneBusinessNo" id="phoneBusinessNo" type="text" maxlength="11" placeholder="번호입력(-) 제외" style="margin-left: 18%; height: 3px;" />
    										<br/><br/>
    										<div id="applyBtn" align="center" style=" display:block; background-color: #B2CCFF;width: 100px; border-radius : 5px; text-shadow: 0px -1px 1px rgba(0,0,0,.3); border: 1px solid #4081AF;box-shadow : inset 0 1px 0 rgba(255,255,255,.3), inset 0 0 2px rgba(255,255,255,.3) 0 1px 2px rgba(0,0,0,.29); cursor: pointer; ">
    						
    											<a href="javascript:sendSmsVal()"><b>문자전송</b></a>
    										</div>
    										<br>
    										<a href="javascript:popupClose();"id="layerbox_close" style="background-color: #B2CCFF;width: 100px; border-radius : 5px; text-shadow: 0px -1px 1px rgba(0,0,0,.3); border: 1px solid #4081AF;box-shadow : inset 0 1px 0 rgba(255,255,255,.3), inset 0 0 2px rgba(255,255,255,.3) 0 1px 2px rgba(0,0,0,.29); cursor: pointer; "">닫기</a>
										</div>
										
									</article>
								</div>	
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>			
	</body>
	<script type="text/javascript" src="./js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src='./js/common.js'></script>
	<script type="text/javascript" src="./js/selectordie.min.js"></script>
	<script type="text/javascript">
    		window.name ="Parent_window";
    		var sponsorForm = document.agreeForm;

			$( document ).ready(function() {
    		
				var appYn = '<?php echo $appYn?>';
				var parentInputName = '<?php echo $parentInputName?>';
				var fAddress = '<?php echo $fAddress?>';
				var mobilePhone = '<?php echo $mobilePhone?>';

				if(appYn=='Y'){
					go_next_sign1(parentInputName)
				}



			});

    		function fnPopupCb(type){
	/*
					var data = {
							"chNum":sponsorForm.chSponsorID.value,
							"chSponsorName":sponsorForm.chSponsorName.value		
						}

					$.ajax({ 
					url :'./signup/cert_success.php',
					type : 'post',
					dataType : 'json', 
					data : { chNum : sponsorForm.chSponsorID.value, chSponsorName : sponsorForm.chSponsorName.value}, 
					success: function(data){ 
						console.log("성공"+JSON.stringify(data)); 
					}
				});
				
*/
    			<?
				/* mingu 모바일적용토록*/
				if($_GET['mobile'] !='y'){
					?>
					window.open('', 'popupChk', 'width=408, height=655, top=100, left=100, fullscreen=no, resizable=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
					<?
				}
					?>
    			
    			if (type == "M") {
    				document.form_chk.EncodeData.value = document.form_chk.MEncodeData.value;
    			} 			
    			document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
				
				<?
				/* mingu 모바일적용토록*/
				if($_GET['mobile'] !='y'){
					?>
					document.form_chk.target = "popupChk";
					<?
				} 
					?>
    			document.form_chk.submit();
    		}	

	

			function go_next_sign(){
				
				var distName = '<?php echo $distName?>'
				var s_nm = $("#parentInputName").val();

				if(distName ==s_nm ){
					//alert("이름이 동일 합니다.");
					$("#applyShow").css('display','block');
					
				}else{
					alert("신청인과 본인인증 성함이 다릅니다. 신청인 : "+distName +" 본인인증 : " + s_nm);
					$("#applyShow").css('display','none');
			
				}		
		 	}

			<?
			
			if($_POST['action']=='changeSponsor'){
				?>
				go_next_sign();
				<?
			}
			?>

			function go_next_sign1(val){

				var baName  = '<?php echo $baName ?>';
				var  aa = 	$("#distName").val(distName);
				if(baName  ==val ){
				
					$("#applyShow").css('display','block');
					$("#distName").val(baName);
				}else{
					alert("신청인과 본인인증 성함이 다릅니다");
					$("#applyShow").css('display','none');
					$("#distName").val(baName);
				}	

			}

		 	function apply(){
				

			
				var agreeChk=document.getElementById("chkVal").checked;

				if(sponsorForm.distID.value == ""){
					alert("회원번호를 입력 하세요");
					sponsorForm.distID.focus();
					return false;
				}else if(sponsorForm.distName.value == ""){
					alert("회원 성명을 입력 하세요");
					sponsorForm.distName.focus();
					return false;
				}else if(sponsorForm.sponsorID.value == ""){
					alert("현재 후원자 번호를 입력 하세요");
					sponsorForm.sponsorID.focus();
					return false;
				}else if (sponsorForm.sponsorName.value == ""){
					alert("현재 후원자 성명을 입력 하세요");
					sponsorForm.sponsorName.focus();
					return false;	
				}else if(sponsorForm.chSponsorID.value == ""){
					alert("변경 하실  후원자 번호를 입력 하세요");
					sponsorForm.chSponsorID.focus();
					return false;
				}else if(sponsorForm.chSponsorName.value == ""){
					alert("변경 하실  후원자 성명을 입력 하세요");
					sponsorForm.chSponsorName.focus();
					return false;
				}
						
				
				if(agreeChk==false){
					alert("동의 및 주의사항에 동의 하셔야 합니다.");
					return false
				}	



				popupOpen();

				

			} 	

			function sendSmsVal(){

				var senderName = '<?php echo $distName?>';
				var orderReal = '<?php echo $order_real;?>';
				var bankID = '<?=$bankwireDB->bankID?>';
				var accountID = '<?=$bankwireDB->accountID?>';
				var sendNum = $("#phoneBusinessNo").val();
				var total = '<?php echo$order_total;?>';

				if(senderName.length > 3){
					senderName = senderName.substring(0,3);
				}	

				var regExp = /^[0-9]+$/;
				if (!regExp.test(sendNum)) {
					alert("숫자만 입력 가능합니다.");
					return false;
				}

			

				 var data = {
							"senderName":senderName,
							"sendNum":sendNum
							
							
				    }

				 $.ajax({
					 'type':'POST',
					 data : { ref1: data },
					    'crossOrigin': true,
					    dataType : "jsonp",
					    jsonpCallback : "myCallback",
					    'url':'../manager_utf8/sms_to_sponsor.php',
					    'success':function (result) {
					    	alert(result.message);
					    	popupClose()
					    	$("#phoneNumSend").css("display","none");
					    } 
				 });


				

				 sponsorForm = document.agreeForm;
				 sponsorForm.method="post";
				 sponsorForm.action = "sponsor_insert.php";
				 sponsorForm.submit();
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
<?

			function han ($s) { return reset(json_decode('{"s":"'.$s.'"}')); }
			function to_han ($str) { return preg_replace('/(\\\u[a-f0-9]+)+/e','han("$0")',$str); }
			function json_encode_han ($arr) {
					if(getType($arr)=='array'){
						return str_replace("'",'',to_han(json_encode($arr)));
					} else {
						return str_replace("'",'',to_han(($arr)));
					}
			}

?>


			function utf8_to_b64( str ) {
				return window.btoa(unescape(encodeURIComponent( str )));
			}

			function b64_to_utf8( str ) {
				return decodeURIComponent(escape(window.atob( str )));
			}
			function js_search11(){
				const chNum = $("#chSponsorID").val();

				$.ajax({
					url: 'https://hydra.unicity.net/v5a/customers?unicity='+chNum+'&expand=customer',
					headers:{
						'Content-Type':'application/json'
					},
					type: 'GET',
					success: function(result) {
						//console.log(result.items[0].href);
						if(typeof(result) != 'undefined' && typeof(result.items) != 'undefined' && result.items.length > 0) {
							var _oname = '';
							if(typeof(result.items[0].humanName['fullName@ko']) != 'undefined') {
								_oname = result.items[0].humanName['fullName@ko'];
							}
							if(_oname == '') {
								_oname = result.items[0].humanName.fullName;
							}
							$('[name=chSponsorName]').val(_oname);

							$.post('json.ajax.php',{'mobile':'<?=$_GET['mobile']?>','chNum':chNum,'chSponsorName':_oname,'_POST':'<?=json_encode_han($_POST )?>'},function(r){
								$('#param_r3').val(r);
							});

							
//							$('#param_r3').val(b64_to_utf8(JSON.stringify({'mobile':'<?=$_GET['mobile']?>','chNum':chNum,'chSponsorName':_oname,'_POST':'<?=json_encode_han($_POST )?>'})));
//							$('[name=param_r3]').val(encodeURIComponent(JSON.stringify({'mobile':'<?=$_GET['mobile']?>','chNum':chNum,'chSponsorName':_oname,'_POST':'<?=json_encode_han($_POST )?>'})));
//							alert(atob(JSON.stringify({'mobile':'<?=$_GET['mobile']?>','chNum':chNum,'chSponsorName':_oname,'_POST':'<?=json_encode_han($_POST )?>'})));
						}else{
						}		
						
					}, error: function() {
						alert('검색된 회원이 없습니다.');
					}
				})
			}	

		</script>

</html>