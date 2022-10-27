<?php 	include "includes/config/common_functions.php";
		
?>
<?php 
	@session_start();
    include "includes/config/config.php";
    include "includes/config/nc_config.php";
    cert_validation();
	$sid = $_POST['sid'];
	//$sid = '15745082';
	$fname = $_POST['fname'];
	$Phone = $_POST['phone'];
	$address = $_POST['address'];
	$eccodeURL = $_POST['eccodeURL'];
	$token = $_POST['token'];
	$name = isset($_SESSION["S_NM"])? $_SESSION["S_NM"] : "";
	$sMobileNo = isset($_SESSION["S_MOBILE_NO"]) ? $_SESSION["S_MOBILE_NO"] : "";



?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>회원쉽 해지</title>
		<meta name="description" content="" />
		<meta http-equiv="Content-Script-Type" content="text/javascript">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
		<link rel="stylesheet" type="text/css" href="./css/joo.css" />
		<style>
			.login { 
			border: none;  
			border-bottom: 3px solid; 
			background: none; 
			font: 14px; 
			font-weight: bold;
			width: 200px; 
			}
			.line { 
			border: none;  
			border-bottom: 3px solid; 
			background: none; 
			font: 14px; 
			font-weight: bold;
			width: 100px; 
			}
		</style>
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
									<span>유니시티 회원쉽 해지</span>
								</h1>
							</div>
							<form name="distributorshipForm" method="post">
								<input type="hidden" name="year" value=""/>
								<input type="hidden" name="distributorshipCard" value=""/>
								<input type="hidden" name="distributorshipNote" value=""/>
								<input type="hidden" name="autoshipYn" value=""/>
								<input type="hidden" name="memberReg" value=""/>
								<input type="hidden" name="faxORdsc" value=""/>
								<input type="hidden" name="cancelDate" value=""/>
								<input type="hidden" name="reg_status" value="2"/>
								<input type="hidden" name="flag" value=""/>
							<?php if($sid != null){ ?>
								<input type="hidden" name="certification" value="Y"/>
							<?php }else{?>
								<input type="hidden" name="certification" value="N"/>
							<?php }?>
								<input type="hidden" name="address" value="<?php echo $address?>"/>



								<div id="mainSubSelect" style="display: block;">
								
								<?php if($sid != null ){?>
									<div align="center">
										<div style="height: 30px;"></div>
										<font color="#FFFFFF" size="5px">회원쉽 전체 해지 접수</font><input type="checkbox" size="30px" id="mainCheck" name="selectChk" onclick="selectMenu()" value="Yes" style="margin-left: 15px;">	
									</div>
									<div style="height: 10px;"></div>
									<div align="center">
										<font color="#FFFFFF" size="4px">※회원쉽 전체 해지 란,<br/>주사업자와 부사업자 모두 해지를 뜻하며,<br/>해지 접수는 주사업자 기준으로 이루어집니다.</font>
									</div>

									<div style="height: 100px;"></div>
									<div align="center">
										<font color="#FFFFFF" size="5px">부사업자 해지 접수</font><input type="checkbox" size="30px" id="subCheck" name="selectChk" onclick="selectMenu()"value="Yes" style="margin-left: 15px;">
									</div>
									<div align="center">
										<font color="#FFFFFF" size="4px">※부사업자 해지 시,<br/>부사업자만 탈퇴되며 해당 회원쉽은 유지 됩니다.</font>
									</div>
								<?php }else{?>
									<div align="center">
										<div style="height: 30px;"></div>
										<font color="#FFFFFF" size="5px">회원쉽 전체 해지 접수</font><input type="checkbox" size="30px" id="mainCheck" name="selectChk" onclick="selectMenu1()" value="Yes" style="margin-left: 15px;">	
									</div>
									<div style="height: 10px;"></div>
									<div align="center">
										<font color="#FFFFFF" size="4px">※회원쉽 전체 해지 란,<br/>주사업자와 부사업자 모두 해지를 뜻하며,<br/>해지 접수는 주사업자 기준으로 이루어집니다.</font>
									</div>
									<div style="height: 40px;"></div>
									<div align="center">
										<font color="#FFFFFF" size="4px"><p>부사업자 해지는 마이비즈 사이트를 이용 부탁 드립니다.</p></font>
									</div>
								<?php }?>
								</div>	
								
								<div class="container" id="mainDistributor" style="display: none;" >
									<div class="jumbotron" align="center">
										<font color="red">※ 회원번호 오류 기재 시,해지 처리가 되지 않습니다.<br/>회원번호를 모르실 경우,당사로 연락하여 주시기 바랍니다</font>	
									</div>
									
									<div class="row marketing" align="center">
										<div class="col-lg-6" style="margin-left: 35px;">
											<table style="margin: 0px;padding: 0px;width: 100%">
												<tr>
													<td style="width: 10%;"><font size="3px;" color="#FFFFFF" style="margin-left: 30px;">회원번호</font></td>
													<td style="color:#FFFFFF;width: 10%">
														<font id="mainName" style="display: none;margin-left: 30px;" size="3px;" color="#FFFFFF">회원성명</font>
														<font id="subName" style="display: none;margin-left: 10px;" size="3px;" color="#FFFFFF"><b>부사업자</b> 성명</font>
													</td>
												</tr>
												<tr>	
													<td style="width: 30%">
													<?php if($sid != null ){?>
														<input id="id" type="text" name="id" class="txt" value="<?php echo $sid ?>" title="회원번호" readonly style="width:120px;text-align: center; height:30px;"/>
													<?php }else{?>
														<input id="id" type="text" name="id" class="txt" value="<?php echo $sid ?>" title="회원번호" style="width:120px;text-align: center;height:30px;"/>
													<?php }?>	
													</td>
													
													<td style="width: 30%">
													<?php if($sid!=null ){?>
														<input id="UserName" type="text" name="UserName" class="txt" value="<?php echo $fname ?>" readonly  title="성명" style="width:120px;text-align: center;height:30px;">
													<?php }else{?>
														<input id="UserName" type="text" name="UserName" class="txt" value="<?php echo $name ?>" readonly  title="성명" style="width:120px;text-align: center;height:30px;">	
													<?php }?>
													</td>
												</tr>
												<tr style="height:10px;"></tr>
												<tr>
													<td style="width: 10%">
														<font id="mainBirth" style="display: none;margin-left: 10px;" size="3px;" color="#FFFFFF" maxlength="8" oninput="maxLengthCheck(this)">회원 생년월일<b><br/>(예:19000101)</b></font>
														<font id="subBirth" style="display: none;margin-left: 10px;" size="3px;" color="#FFFFFF" maxlength="8" oninput="maxLengthCheck(this)"><b>부사업자</b> 생년월일<b><br/>(예:19000101)</b></font>
													</td>
													<td style="width: 10%">
														<font id="mainPhone" style="display: none;margin-left: 10px;" size="3px;" color="#FFFFFF">휴대폰 번호<b><br/>&nbsp;(번호만 입력)</b></font>
														<font id="subPhone" style="display: none;margin-left: 0px;" size="3px;" color="#FFFFFF"><b>부사업자</b> 휴대폰 번호<b><br/>&nbsp;(번호만 입력)</b></font>
													</td>
												</tr>
												<tr>
													<td style="width: 40%">
														<input id="birthDay" type="number" name="birthDay" maxlength="8" oninput="maxLengthCheck(this)" class="txt" value="" title="생년월일" style="width:120px;text-align: center;height:30px;">
													</td>
													<td style="width: 40%">
														<?php if($sid != null ){?>
														<input id="Phone" type="number" name="Phone" class="txt" maxlength="11" oninput="maxLengthCheck(this)" value="<?php echo $Phone?>" title="휴대폰 번호" style="width:120px;text-align: center;height:30px;">
														<?php }else{?>
														<input id="Phone" type="number" name="Phone" class="txt" maxlength="11" oninput="maxLengthCheck(this)" value="<?php echo $sMobileNo?>" title="휴대폰 번호" style="width:120px;text-align: center;height:30px;">
														<?php }?>
													</td>
												</tr>
											</table>
										<?php if($sid == null){?>
											<h4	h4><font size="3px" color="#FFFFFF">주소 입력</font></h4>			
											<div style="height:5px;"></div>
											<select name="addressSelect" id="addressSelect" title="시도 선택" style="width:130px; height: 40px;">
												<option selected="selected">선택</option>
												<option value="서울특별시">서울특별시</option>
												<option value="인천광역시">인천광역시</option>
												<option value="대전광역시">대전광역시</option>
												<option value="광주광역시">광주광역시</option>
												<option value="대구광역시">대구광역시</option>
												<option value="울산광역시">울산광역시</option>
												<option value="부산광역시">부산광역시</option>
												<option value="경기도">경기도</option>
												<option value="충청북도">충청북도</option>
												<option value="충청남도">충청남도</option>
												<option value="전라북도">전라북도</option>
												<option value="전라남도">전라남도</option>
												<option value="강원도">강원도</option>
												<option value="경상북도">경상북도</option>
												<option value="경상남도">경상남도</option>
												<option value="세종특별자치시">세종특별자치시</option>
												<option value="제주특별자치도">제주특별자치도</option>
											</select>
											<input id="addressDetail" type="text" name="addressDetail" class="txt" value="" style="width:50%;height:4%;margin-left:3%">
											<div style="height:15px;"></div>
										<?php }?>
											<div id="mainYn">	
												<div style="height:10px;"></div>
												<div style="float: left;margin-right: 10px; margin-top: 3px;">
													<font size="3px" color="#FFFFFF">회원 등록증 : </font>
												</div>
		
												<div align="left">	
													<input type="radio" id="chk1" name="checkYN1" value="Yes"/><font color="#FFFFFF">반납</font>
													<input type="radio" id="chk2" name="checkYN1" value="No"/><font color="#FFFFFF">분실/훼손</font>
													<input type="radio" id="chk8" name="checkYN1" value="Etc" /><font color="#FFFFFF">기타:</font>
													<input type="text" id="etcText" name="etcText" class="line" maxlength="50" style="color:#FFFFFF; width: 20%"/>
												</div>					
												<div style="height:10px;"></div>
												<div style="float: left;margin-right: 10px; margin-top: 3px;">
													<font size="3px" color="#FFFFFF">회원 수첩&nbsp;&nbsp; : </font>
												</div>
						
												<div align="left">
													<input type="radio" id="chk3" name="checkYN2" value="Yes"/><font color="#FFFFFF">반납</font>
													<input type="radio" id="chk4" name="checkYN2" value="No"/><font color="#FFFFFF">분실/훼손</font>
													<input type="radio" id="chk9" name="checkYN2" value="" /><font color="#FFFFFF">기타:</font>
													<input type="text" id="etcText1" name="etcText1" class="line" maxlength="50" style="color:#FFFFFF; width: 20%;"/>
												</div>
												<div id="autoship" style="display: none;">
													<div style="height:10px;"></div>
													<div style="float: left;margin-right: 10px; margin-top: 3px;">
														<font size="3px" color="#FFFFFF">오토쉽 사용 여부 &nbsp;&nbsp; : </font>
													</div>
													
													<div align="left">
														<input type="radio" id="chk10" name="checkYN5" value="Yes"/><font color="#FFFFFF">신청중</font>
														<input type="radio" id="chk11" name="checkYN5" value="No"/><font color="#FFFFFF">미신청</font>				
													</div>
												</div>
												<div style="height:10px;"></div>
												<div style="float: left;margin-right: 10px; margin-top: 3px;">
													<font size="3px" color="#FFFFFF">회원 확인 등록서&nbsp;&nbsp; : </font>
												</div>
						
												<div align="left">
													<input type="radio" id="chk13" name="checkYN6" value="Yes" onclick="changeEt()" /><font color="#FFFFFF">발급</font>
													<input type="radio" id="chk14" name="checkYN6" value="No" onclick="changeEt()"/><font color="#FFFFFF">미발급</font>				
												</div>
												<div id="purposeId" style="display: none;">
													<div style="height:10px;"></div>
													<div style="float: left;margin-right: 10px; margin-top: 3px;">
														<font size="3px" color="#FFFFFF">제출처&nbsp;&nbsp; : </font>
													</div>
													<div align="left">
														<select name="purposeSelect" id="purposeSelect" title="제출처" style="width:130px; height: 30px;" onchange="purposeChg()" >
															<option value="">선택</option>
															<option value="P0">건강보험공단</option>
															<option value="P1">고용보험</option>
															<option value="P2">은행</option>
															<option value="P3">국민연금공단</option>
															<option value="P4">기타</option>
														</select>
														
													</div>
							
													<div id="etcReason" style="display: none;">
														<div style="float: left;margin-right: 10px; margin-top: 3px;">
															<font size="3px" color="#FFFFFF">제출처(기타) 입력&nbsp;&nbsp; : </font>
														</div>
														<div align="left">
															<input type="text" id="selectText" name="selectText" class="login" maxlength="20" oninput="maxLengthCheck(this)" style="color:#FFFFFF; width:20%;"/>
														</div>
													</div>
													<div style="height:10px;"></div>
													<div style="float: left;margin-right: 10px; margin-top: 3px;">
														<font size="3px" color="#FFFFFF">발급목적&nbsp;&nbsp; : </font>
													</div>
													
													<div align="left">
														<input type="text" id="purpose" name="purpose" class="login" maxlength="20" oninput="maxLengthCheck(this)" style="color:#FFFFFF;"  />
													</div>
													
													<div style="height:10px;"></div>
													<div style="float: left;margin-right: 10px; margin-top: 3px;">
														<font size="3px" color="#FFFFFF">수령방법 선택&nbsp;&nbsp; : </font>
													</div>
													<div align="left">
														<input type="radio" id="chk15" name="checkYN7" value="Yes" onclick="faxDscSel()" /><font color="#FFFFFF">팩스</font>
														<input type="radio" id="chk16" name="checkYN7" value="No" onclick="faxDscSel()"/><font color="#FFFFFF">DSC방문</font>		
													</div>
													<div align="left">
														<input type="number" id="faxNum" name="faxNum" placeholder="팩스번호(숫자만) 입력" maxlength="12" oninput="maxLengthCheck(this)" style="width: 45%; display: none;"/>
														<select name="dscChk" id="dscChk" title="DSC선택" style="width:130px; height: 30px;display: none;">
															<option value="">선택</option>
															<option value="D0">서울</option>
															<option value="D1">인천</option>
															<option value="D2">안산</option>
															<option value="D3">대전</option>
															<option value="D4">원주</option>
															<option value="D5">대구</option>
															<option value="D6">광주</option>
															<option value="D7">부산</option>
														</select>		
													</div>
													<div style="height:10px;"></div>
													<div style="margin-right: 10px; margin-top: 3px; text-align: left;">
														<font color="#FFFFFF">
															<P>수령방법: FAX 발송(FAX번호 기재) / DSC 본인 방문수령(방문 예정 DSC 선택)<br/>
															*DSC 방문 수령은 신청일로부터 1주일 이내까지 가능하며, 기간 경과 후에는 재 신청하셔야 합니다. 이 점 착오 없으시기 바랍니다.</P>
														</font>
													</div>
												</div>

												<div style="height:10px;"></div>
												<div style="float: left;margin-right: 10px; margin-top: 3px;">
													<font size="3px" color="#FFFFFF">해지 신청사유 : </font>
												</div>
												<div align="left">
													<input type="text" id="reason" name="reason" class="login" maxlength="50" oninput="maxLengthCheck(this)" style="color:#FFFFFF;"  />
												</div>
											</div>
										</div>			
									</div>
									<div class="row">
										<div>
											<div style="height:15px"></div>			
											<div>
												<font size="4px"color="#7cf1f6"><b>개인정보 수집 및 이용에 대한 동의</b></font><br/>
												<table class="table table-striped" border="2">
													<tr>
														<td colspan="2">
															<font size="4px"  color="#D5D5D5">
																<b>필수 수집 및 이용목적:</b> 해지 신청자의 식별 및 원활한 의사소통<br/>
																<b>필수 수집 항목 :</b> 회원번호, 성명, 생년월일<br/>
																<b>보유 및 이용기간:</b> 해지 처리 완료 시까지.다만, 다른 법령에 따라 보존하여야 하는 경우에는 해당 규정에 따라 보관합니다.<br/>
																<b>수집 동의 거부 권리 및 불이익 사항 :</b> 이용자는 개인정보 수집 및 이용에 거부할 권리가 있습니다.단, 개인정보의 필수적 수입 및 이용에 동의하지 않을 경우 해지 신청이 제한됩니다.<br/>
																<b>본인은 회사의 필수 개인정보 수집 및 이용에 관한 설명을 모두 이해 하였고,</b>
															</font>
														</td>
													</tr>
													<tr>
														<td width="50%">
															<font color="#7cf1f6">이에 동의 합니다.</font><input type="radio" id="chk5" name="checkYN3" value="Yes"/>
														</td>
														<td width="50%">
															<font color="#7cf1f6">동의하지 않습니다.</font><input type="radio" id="chk6" name="checkYN3" value="No"/>
														</td>
													</tr>
													<tr>
														<td colspan="2">
															<font size="4px;" color="#D5D5D5">본 해지는 오토쉽 계약 등의 해지를 포함하며,해지 요청 접수 후에 그 취소는 불가함을 알고 있습니다. 본인은 이와 같이 인지하고,자발적 의사에 따라 유니시티코리아(유) 디스트리뷰터쉽 해지를 요청합니다.</font> 
												
														</td>
													</tr>
													<tr>
														<td width="50%">
															<font color="#7cf1f6">이에 동의 합니다.</font><input type="radio" id="chk7" name="checkYN4" value="Yes"/>
														</td>
														<td width="50%">
															<font color="#7cf1f6">동의하지 않습니다.</font><input type="radio" id="chk12" name="checkYN4" value="No"/>
														</td>
													</tr>
													<tr height="5px"></tr>
												</table>
											</div>
										</div>
									</div><!-- /.row -->
									<div id="applyBtn" align="center" style=" display:block; background-color: #B2CCFF;width: 100px;margin-left: 40%; border-radius : 5px; text-shadow: 0px -1px 1px rgba(0,0,0,.3); border: 1px solid #4081AF;box-shadow : inset 0 1px 0 rgba(255,255,255,.3), inset 0 0 2px rgba(255,255,255,.3) 0 1px 2px rgba(0,0,0,.29); cursor: pointer; ">
    									<a href="javascript:submitForm()"><b>신청하기</b></a>
    								</div>			
								</div> <!-- /container -->    
							</form>	
						</div>	
					</div>		
				</div>	
			</div>	
		</body>
		<script type="text/javascript" src="./js/jquery-1.8.0.min.js"></script>
		<script type="text/javascript" src='./js/common.js'></script>
		<script type="text/javascript" src="./js/selectordie.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script type="text/javascript">
			var flag = "";
			var disValue ="";
			var check_flag_1 = "";
			var check_flag_2 = "";
			var check_flag_3 = "";
			var check_flag_4 = "";
			var check_flag_5 = "";
			var check_flag_6 = "";
			var check_flag_7 = "";
			var check_flag_8 = "";
			var check_flag_9 = "";
			var check_flag_10 = "";
			var check_flag_11 = "";
			var check_flag_12 = "";
			var check_flag_13 = "";
			var check_flag_14 = "";
			var check_flag_15 = "";
			var check_flag_16 = "";
			var sidVal='<?php echo $sid ?>';		
			
			function selectMenu(){
	
				var mainSelect = document.getElementById("mainCheck").checked;
				var subCheck = document.getElementById("subCheck").checked;

				if(mainSelect ==  true){	
					$("#mainSubSelect").css("display","none");	
					$("#mainDistributor").css("display","block");
					$("#autoship").css("display","block");
					$("#mainTxt").show();
					$("#mainName").show();
					$("#mainBirth").show();
					$("#mainPhone").show();
					flag = 1;
					//$('#UserName').attr('readonly', true); 
				}else if(subCheck == true){
					$("#mainSubSelect").css("display","none");	
					$("#mainDistributor").css("display","block");
					$("#subTxt").show();
					$("#subName").show();
					$("#subBirth").show();
					$("#subPhone").show();
					$("#reason").show();
					$("#mainYn").hide();
					$("#UserName").val('');
					flag =2;
					$('#UserName').attr('readonly', false); 
			
				}		
			}	

			function selectMenu1(){
				var mainSelect = document.getElementById("mainCheck").checked;
				
				if(mainSelect ==  true){	
					$("#mainSubSelect").css("display","none");	
					$("#mainDistributor").css("display","block");
					$("#autoship").css("display","block");
					$("#mainTxt").show();
					$("#mainName").show();
					$("#mainBirth").show();
					$("#mainPhone").show();
					flag = 1;
					//$('#UserName').attr('readonly', true); 
				}else if(subCheck == true){
					$("#mainSubSelect").css("display","none");	
					$("#mainDistributor").css("display","block");
					$("#subTxt").show();
					$("#subName").show();
					$("#subBirth").show();
					$("#subPhone").show();
					$("#reason").show();
					$("#mainYn").hide();
					$("#UserName").val('');
					flag =2;
					$('#UserName').attr('readonly', false); 
			
				}		
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

			function changeEt(){
				disValue = document.distributorshipForm;
					check_flag_13 = document.getElementById("chk13").checked;
					check_flag_14 = document.getElementById("chk14").checked;
				
				if(check_flag_13 == true){
					$("#purposeId").css("display","block");
				}else{
					$("#purposeId").css("display","none");
				}		
			}	

			function faxDscSel(){
				check_flag_15 = document.getElementById("chk15").checked;
				check_flag_16 = document.getElementById("chk16").checked;

				if(check_flag_15 == true){
					$("#faxNum").css("display","block");
					$("#dscChk").css("display","none");
				}else if(check_flag_16 == true){
					$("#faxNum").css("display","none");
					$("#dscChk").css("display","block");
				}		
			}
			function purposeChg(){
				if(disValue.purposeSelect.value == "P4"){
					$("#etcReason").css("display","block");
				}else{
					$("#etcReason").css("display","none");
				}		
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

			function maxLengthCheck(object){
				if(object.value.length>object.maxLength){
					object.value = object.value.slice(0, object.maxLength);
				}	
			}
			
			function submitForm (){

				var token = '<?php echo $token ?>';
				var eccodeURL = '<?php echo $eccodeURL ?>'

				disValue = document.distributorshipForm;

				var year = date.getFullYear();
					check_flag_1 = document.getElementById("chk1").checked;
					check_flag_2 = document.getElementById("chk2").checked;
					check_flag_3 = document.getElementById("chk3").checked;
					check_flag_4 = document.getElementById("chk4").checked;
					check_flag_5 = document.getElementById("chk5").checked;
					check_flag_6 = document.getElementById("chk6").checked;
					check_flag_7 = document.getElementById("chk7").checked;
					check_flag_8 = document.getElementById("chk8").checked;
					check_flag_9 = document.getElementById("chk9").checked;
					check_flag_10 = document.getElementById("chk10").checked;
					check_flag_11 = document.getElementById("chk11").checked;
					check_flag_12 = document.getElementById("chk12").checked;
					check_flag_13 = document.getElementById("chk13").checked;
					check_flag_14 = document.getElementById("chk14").checked;
					check_flag_15 = document.getElementById("chk15").checked;
					check_flag_16 = document.getElementById("chk16").checked;

				var chkYN1 = "";
				var chkYN2 = "";
				var chkYN5 = "";
				var chkYN6 = "";
				var chkYN7 = "";
		
					if(check_flag_1 == true){
						chkYN1 = 'Y'
					}else if(check_flag_2 == true){
						chkYN1 = 'N'
					}else if(check_flag_8 == true){
						chkYN1 = 'E'
					}			

					if(check_flag_3 == true){
						chkYN2 = 'Y'
					}else if(check_flag_4 == true){
						chkYN2 = 'N'
					}else if(check_flag_9 == true){
						chkYN2 = 'E'
					}	

					if(check_flag_10 == true){
						chkYN5 = 'Y'
					}else if(check_flag_11 == true){
						chkYN5 = 'N'
					}

					if(check_flag_13 == true){
						chkYN6 = 'Y'
					}else if(check_flag_14 == true){
						chkYN6 = 'N'
					}

					if(check_flag_15 == true){
						chkYN7 = 'Y'
					}else if(check_flag_16 == true){
						chkYN7 = 'N'
					}
		
		
			 	
					if(flag==1){
						if(disValue.id.value == "" || disValue.id.value==null){
							alert("회원번호를 입력 해주세요.");
							disValue.id.focus();
							return false;
						}else if(disValue.UserName.value == "" || disValue.UserName.value==null){
							alert("회원성명을 입력 해주세요.");
							disValue.UserName.focus();
							return false;
						}else if(disValue.birthDay.value == "" || disValue.birthDay.value==null){
							alert("생년월일을 입력 해주세요.");
							disValue.birthDay.focus();
							return false;
						}else if(disValue.Phone.value == "" || disValue.Phone.value==null){
							alert("휴대폰 번호를 입력 해주세요.");
							disValue.Phone.focus();
							return false;
						}else if(chkYN1 == null || chkYN1 == ""){
							alert("회원등록증 여부 체크 해주세요");
							return false;
						}else if(chkYN2 == null || chkYN2 == ""){
							alert("회원수첩 여부 체크 해주세요");
							return false;
						}else if(chkYN5 == null || chkYN5 == ""){
							alert("오토쉽 여부를 확인 해주세요");
							return false;
						}else if(chkYN6 == null || chkYN6 == ""){
							alert("회원 확인 등록서 여부를 확인 해주세요");
							return false;
						}

						if(check_flag_8 ==true && disValue.etcText.value ==""){
							alert("회원 등록증 기타 사유를 입력해주세요");
							return false;
						}else if(check_flag_9 ==true && disValue.etcText1.value==""){
							alert("회원수첩 기타 사유를 입력해주세요");
							return false;
						}
							
					
					}else if(flag ==2){
						if(disValue.id.value == "" || disValue.id.value==null){
							alert("회원번호를 입력 해주세요.");
							disValue.id.focus();
							return false;
						}else if(disValue.UserName.value == "" || disValue.UserName.value==null){
							alert("회원성명을 입력 해주세요.");
							disValue.UserName.focus();
							return false;
						}else if(disValue.birthDay.value == "" || disValue.birthDay.value==null){
							alert("생년월일을 입력 해주세요.");
							disValue.birthDay.focus();
							return false;
						}else if(disValue.Phone.value == "" || disValue.Phone.value==null){
							alert("휴대폰 번호를 입력 해주세요.");
							disValue.Phone.focus();
							return false;
						}
							
					}

					if(disValue.birthDay.value.length != '8'){
						alert("생년월일을 바르게 입력 해 주세요.\n 예)19000101");
						return false;
					}


					if(chkYN6 =="Y"){
						if(disValue.purposeSelect.value == "" ||disValue.purposeSelect.value == null ){
							alert("제출처를 선택 하셔야 합니다.");
							return false;		
						}

						if(disValue.purposeSelect.value == "P4" && (disValue.selectText.value == "" || disValue.selectText.value == null)){
							alert("제출처(기타) 입력 해 주세요");
							return false;
							
						}	

						if(disValue.purpose.value == "" || disValue.purpose.value == null){
							alert("발급 목적을 입력 하세요");
							return false;		
						}	 

						if(chkYN7 == "" || chkYN7 == null){
							alert("수령 방법을 선택 하세요");
							return false;		
						}	

						if(chkYN7 == "Y" && (disValue.faxNum.value =="" || disValue.faxNum.value == null)){
							alert("fax 번호를 입력 하세요");
							return false;	
						}else if (chkYN7 == "N" && (disValue.dscChk.value =="" || disValue.dscChk.value == null)){
							alert("방문하실 DSC를 선택 해 주세요");
							return false;	
						}		
						
					}	
		
					if(flag == 1){
						if(disValue.reason.value == null ||disValue.reason.value == ""){
							alert("해지 신청사유를 입력 하세요");
							return false;
						}	
					}	
					if(check_flag_5 != true || check_flag_6 == true){
						alert("개인정보 수집 및 이용에 대한 동의에 동의해 주세요 ");
						$("#chk5").focus();
						return false;
					}

					if(check_flag_7 != true ||check_flag_12 == true ){
						alert("오토쉽 계약등 해지등 최종 해지요청에 동의해 주세요");
						$("#chk7").focus();
						return false;
					}		 

					if(disValue.address.value == "" ||disValue.address.value == null ){
						disValue.address.value = disValue.addressSelect.value;
					}	

					if(check_flag_13 == true && (disValue.purpose.value =="" || disValue.purposeSelect.value =="" )){
						alert("제출처 및 발급 목적을 작성 하셔야 합니다.");
						return false;
					}	

					var confrimId = disValue.id.value;
		
					if(flag == 1){
						if(confirm(confrimId +' '+"회원쉽 전체 해지 신청하시겠습니까?")){
							$.ajax({
								'type':'DELETE',
								'headers':{'Content-Type':'application/json','Authorization':'Bearer ' + token},
								'url': eccodeURL+'/rights?type=Order&holder=Upline',
								success:function (result) {
									//console.log("resutl:::"+result);
								},
								error:function (result){
									//console.log("error::"+result);
								}
							});
							
							disValue.year.value = year;
					
							disValue.cancelDate.value = getTimeStamp();
							disValue.distributorshipCard.value = chkYN1;
							disValue.distributorshipNote.value = chkYN2; 
							disValue.autoshipYn.value = chkYN5;   
							disValue.memberReg.value = chkYN6; 
							disValue.faxORdsc.value = chkYN7;
							disValue.flag.value = flag;
							disValue.action = "insertApply.php";
							disValue.submit();
						}	
					}else if (flag ==2){
						if(confirm(confrimId +' '+"회원쉽의 부사업자 해지 신청하시겠습니까?")){
							disValue.year.value = year;
							disValue.cancelDate.value = getTimeStamp();
							disValue.distributorshipCard.value = chkYN1;
							disValue.distributorshipNote.value = chkYN2;
							disValue.autoshipYn.value = chkYN5;
							disValue.memberReg.value = chkYN6;
							disValue.faxORdsc.value = chkYN7;
							disValue.flag.value = flag;
							disValue.action = "insertApply.php";
							disValue.submit();	
						}				
					}		
					/*
					if(confirm("신청하시겠습니까?")){   
						disValue.year.value = year;
						disValue.cancelDate.value = getTimeStamp();
						disValue.distributorshipCard.value = chkYN1;
						disValue.distributorshipNote.value = chkYN2;
						disValue.autoshipYn.value = chkYN3;
						disValue.flag.value = flag;
						disValue.action = "insertApply.php";
						disValue.submit();
					}
					*/				
	}	

	
		</script>
	</head>
</html>	