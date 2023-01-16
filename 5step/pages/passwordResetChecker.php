<?php
	include_once("../inc/function.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex"> 

    <title>5 Step User Password Reset Page</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.2/css/bootstrapValidator.min.css"/>
  	<style>
		/* spinning loader */
		.loader {
		  border: 16px solid #f3f3f3;
		  border-radius: 50%;
		  border-top: 16px solid #3498db;
		  width: 120px;
		  height: 120px;
		  -webkit-animation: spin 2s linear infinite;
		  animation: spin 2s linear infinite;
		  
		  position: fixed;
		  top: 50%;
		  left: 50%;
		  margin-left: -50px;
		  margin-top: -50px;
		  z-index:2;
		  overflow: auto;
		 }

		@-webkit-keyframes spin {
		  0% { -webkit-transform: rotate(0deg); }
		  100% { -webkit-transform: rotate(360deg); }
		}

		@keyframes spin {
		  0% { transform: rotate(0deg); }
		  100% { transform: rotate(360deg); }
		}
		 
	</style>
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.2/js/bootstrapValidator.min.js"></script>
  	<script>
		 
  		$(document).ready(function() {
  		
    	$('#registerform').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                name: {
                    validators: {
                         notEmpty: {
                            message: '이름을 입력해 주세요.'
                        }, 
                        regexp: {
                        	regexp: /^[가-힣]{2,10}$/,
                        	message: '이름을 정확히 입력해 주세요.'
                        }
                    }
                },
                phonenumber: {
                    validators: {
                        notEmpty: {
                            message: '전화번호를 입력해 주세요.'
                        },
                         regexp: {
                            regexp: /^\d{2,3}\d{3,4}\d{4}$/,
                            message: '전화번호를 정확히 입력해 주세요.'
                        }
                        
                    }
                },
                username: {
                    validators: {
                        notEmpty: {
                            message: '아이디를 입력해 주세요.'
                        }, 
                        regexp: {
                        	regexp:  /^[A-za-z0-9]{5,12}$/,
                        	message: '아이디를 정확히 입력해 주세요.'
                        }
                    }
                },
               
            }
        })
        .on('success.form.bv', function(e) {
        	$("#loader").addClass("loader");
    		$("#content").css("opacity", "0.5");
        	
           $('#registerform').data('bootstrapValidator').resetForm();

            // Prevent form submission
            e.preventDefault();

            // Get the form instance
            var $form = $(e.target);

            // Get the BootstrapValidator instance
            var bv = $form.data('bootstrapValidator');

            // Use Ajax to submit form data
            $.post($form.attr('action'), $form.serialize(), function(result) {
                
                if (result == 'success') {
                	$("#loader").removeClass("loader");
    				$("#content").css("opacity", "1");
                	 
                	document.testForm.fullname.value=$('#name').val();
                    document.testForm.contactNo.value=$('#phonenumber').val();
                    document.testForm.userId.value=$('#username').val();
                    $('#testForm').submit();

                } else {
                    console.log(result);
                	$('#idCheckerResult').html("<font style='color:red;'>입력된 데이터와 일치하는 사용자가 없습니다.</font>");
                	$("#loader").removeClass("loader");
    				$("#content").css("opacity", "1");
                }
            });
        });
	});
  		
   

  	</script>
</head>
<body style="background-color: #efefef" onload='document.registerform.name.focus()'>
<div id="loader"></div>	
 <div style="display:block; width:100%; background-color:#2E2E2E; color:#FFF; line-height:70px;text-align:center; margin:0;">
        <div style="display:inline-block; vertical-align: middle; margin-right:5%; font-size: 120%">UNICITY SCIENCE</div> 
        <div style="display:inline-block; vertical-align: middle; font-size:220%"> <b><font color="#0096e0">5</font> <font color="#3eb134">S</font><font color="#0068b7">T</font><font color="#f6ab00">E</font><font color="#ed6d00">P</font></b> </div>
    </div>
<div class="container" id="content">
	<div style="text-align: center;margin: 20px 0; padding 30px; 0;">
		 <div style="padding: 15px 0; font-size: 25px; color:#aaaaaa; font-weight: bold;">
		 	5 STEP 비밀번호 재설정
		 </div>
		 <div style="padding: 15px 0; font-size: 15px; color:#aaaaaa;">비밀번호 재설정을 위한 본인 확인입니다.</div>
	</div>
	 <form name="registerform" id="registerform" class="form-horizontal" action="../inc/passwordResetCheckerProcess.php" novalidate>
		 	 
	    <div class="form-group">
		    <label class="control-label col-sm-3" for="name">이름:</label>
		    <div class="col-sm-9">          
			    <input type="text" class="form-control" id="name" placeholder="이름" name="name">
		    </div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-3" for="phonenumber">전화번호:</label>
		    <div class="col-sm-9">          
			    <input type="text" class="form-control" id="phonenumber" placeholder="전화번호(숫자만)"   name="phonenumber">
		    </div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-3" for="username">아이디:</label>
		    <div class="col-sm-9">          
		        <input type="text" class="form-control" id="username" placeholder="아이디(5~12자)" name="username">
		    </div>
		</div>

		 

		<div class="col-sm-12" style="text-align: center; color: #919191; margin-top: 10px;"> 
			<button type="submit" style="background-color: white; color: black; border: 2px solid #4CAF50;padding: 10px 80px; text-align: center; text-decoration: none; display: inline-block; border-radius: 10px; font-size: 16px; margin: 4px 2px; -webkit-transition-duration: 0.4s; transition-duration: 0.4s; cursor: pointer;">본인확인</button>
			<div id='idCheckerResult'></div>
		</div>
	</form>
    <form name="testForm" id="testForm" method="post" action="./passwordReset.php">
        <input type="hidden" name="fullname">
        <input type="hidden" name="contactNo">
        <input type="hidden" name="userId">
    </form>
</div>
		
	 
</div>
</body>
</html>

