<?php 	include "includes/config/common_functions.php";
		
?>
<?php 
    include "includes/config/config.php";
    include "includes/config/nc_config.php";
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
		<script type="text/javascript" src="./js/jquery-1.8.0.min.js"></script>
		<script type="text/javascript" src='./js/common.js'></script>
		<script type="text/javascript" src="./js/selectordie.min.js"></script>
		<script>
		
			function login(){
				var baId = $("#distID").val();
				var baPassword = $("#distPassword").val();
				$('#loadBankwire').css('display','block');
				var data = {
					'type':'base64', 
					'value':btoa(baId + ":" + baPassword), 
					'namespace':'https://hydra.unicity.net/v5a/customers'
				};
				data = JSON.stringify(data);

				$.ajax({
					'type':'POST',
					'headers':{'Content-Type':'application/json'},
					'url':'https://hydra.unicity.net/v5a/loginTokens',
					'data':data,
					'success':function (result) {
		
					
					loginSuccess(result.token,result.customer.href );

					},

					'error':function () {
						$('#loadBankwire').css('display','none');
						alert("회원번호 또는 비밀번호를 확인 해주세요");
					}

				});			
			}	

			function loginSuccess(token,href){

				 $.ajax({
			            'type' : 'GET',
			            'headers' : {
			                'Authorization' : 'Bearer '+token
			            },
			            'url' : href,
			            'success' : function(result) {
			            
			            	  var  data = result.humanName["fullName@ko"]
			
			                  console.log('rawData =>'+JSON.stringify(result,undefined,4));
			                  var fullName = result.humanName["fullName@ko"];
			                  var unicityId = result.unicity;
			            
			                  var spForm = document.spForm;
			                  var x = window.screen.Width;
							  var y = window.screen.Height;

							  var url = "agree.php";

							  window.open("","_self","menubar=yes, toolbar=yes, location=yes, status=yes, resizble=yes, scrollbars=yes,width=" + x + ", height=" + y);
							  spForm.method="post";
							  spForm.fullName.value = fullName
							  spForm.action =url; 
							  spForm.submit();
			               
			            },
			            'error' : function() {
			             	alert("오류입니다.");
			            }
			        });
				
			}	
		</script>
	</head>
	<body>
		<div class="wrapper" >
			<div class="main_wrapper">
				<div class="figure">
					<img src="./images/mainlogo.png" alt="유니시티 로고" />
				</div>
				<div class="main_box">
					<div class="main_inner_box" id="forLogin"  style="display: block">
						<div class="main_top">
							<h1>
								<span>로그인</span>
							</h1>
						</div>
						<form name="spForm">
							<input type="hidden" name="fullName">
							<input type="hidden" name="unicityId">
							<div class="wrap_input">
								<div class="member">
									<h2 style="float: left; margin-top: 9px;">회원번호&nbsp;&nbsp;&nbsp;&nbsp;</h2>
									<div class="wrap">
										<input type="text" placeholder="회원번호" name="distID" id="distID" value=""/>
									</div>
									<h2 style="float: left; margin-top: 9px;">비밀번호&nbsp;&nbsp;&nbsp;&nbsp;</h2>
									<div class="wrap">
										<input type="password" placeholder="비밀번호" name="distPassword" id="distPassword" style="margin-top: 7px;height: 35px;"/>
									</div>
								</div>
								<div id="applyBtn" align="center" style=" display:block; background-color: #B2CCFF;width: 100px;margin-left: 40%; border-radius : 5px; text-shadow: 0px -1px 1px rgba(0,0,0,.3); border: 1px solid #4081AF;box-shadow : inset 0 1px 0 rgba(255,255,255,.3), inset 0 0 2px rgba(255,255,255,.3) 0 1px 2px rgba(0,0,0,.29); cursor: pointer; ">
									<a href="javascript:login()"><b>로그인</b></a>
								</div>
								<div id="loadBankwire" style="margin:0 auto;width:100px;clear:both;display:none;"><img src="https://www.makelifebetter.co.kr/sp/images/loadding.gif" width="80" ></div>
							</div>
							
						</form>
					</div>
				</div>
			</div>		
		</div>
	</body>
	
</html>	