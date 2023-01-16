<?php
    include_once("../inc/function.php");
    $name = isset($_POST["fullname"]) ? $_POST["fullname"] : '';
    $phonenumber = isset($_POST["contactNo"]) ? $_POST["contactNo"] : '';
    $username = isset($_POST["userId"]) ? $_POST["userId"] : '';

    if($name == '' || $phonenumber == '' || $username == ''){
        DisplayAlert('잘못된 접근입니다.');
        moveTo('../index.html');
    }

    
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
        var idcheck = 1;
        $(document).ready(function() {
        
        $('#passwordForm').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            pwd: {
                validators: {
                    notEmpty: {
                        message: '비밀번호를 입력해 주세요.'
                    },
                    regexp: {
                        regexp:  /^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/,
                        message: '비밀번호를 정확히 입력해 주세요.'
                    }
                }
            },
            confirmpwd: {
                validators: {
                     notEmpty: {
                        message: '비밀번호를 확인해 주세요.'
                    },
                    identical: {
                        field: 'pwd',
                        message: '비밀번호와 일치하는 값을 입력해 주세요.'
                    }
                }
            }
            }
        })
        .on('success.form.bv', function(e) {
            $("#loader").addClass("loader");
            $("#content").css("opacity", "0.5");
            
            $('#passwordForm').data('bootstrapValidator').resetForm();

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
                    alert('비밀번호가 변경되었습니다.');
                    window.location = '../index.html';
                } else {
                    $('#idCheckerResult').html("<font style='color:red;'>비밀번호 등록 중 문제가 발생했습니다. 잠시 후 다시 시도해주세요.</font>");
                    $("#loader").removeClass("loader");
                    $("#content").css("opacity", "1");
                }
            });
        });
    });
        
    function validatePassword() {
        if (document.getElementById('pwd').value != document.getElementById('confirmpwd').value){
            document.getElementById('confirmpwd').setCustomValidity("비밀번호가 일치하지 않습니다.");
            console.log( document.getElementById('confirmpwd').value);
            document.getElementById('confirmpwd').value='';
            document.getElementById('confirmpwd').focus();
            
        } else {
            document.getElementById('confirmpwd').setCustomValidity('');
        }
    }

    </script>
</head>
<body style="background-color: #efefef" onload='document.passwordForm.pwd.focus()'>
<div id="loader"></div> 
 <div style="display:block; width:100%; background-color:#2E2E2E; color:#FFF; line-height:70px;text-align:center; margin:0;">
        <div style="display:inline-block; vertical-align: middle; margin-right:5%; font-size: 120%">UNICITY SCIENCE</div> 
        <div style="display:inline-block; vertical-align: middle; font-size:220%"> <b><font color="#0096e0">5</font> <font color="#3eb134">S</font><font color="#0068b7">T</font><font color="#f6ab00">E</font><font color="#ed6d00">P</font></b> </div>
    </div>
<div class="container" id="content">
    <div style="text-align: center;margin: 20px 0; padding 30px; 0;">
         <div style="padding: 15px 0; font-size: 25px; color:#aaaaaa; font-weight: bold;">
            5 STEP 사용자 비밀번호 재설정   
         </div>
         <div style="padding: 15px 0; font-size: 15px; color:#aaaaaa;">5 STEP 사용자 비밀번호를 재설정 합니다.</div>
    </div>
     <form name="passwordForm" id="passwordForm" class="form-horizontal" action="../inc/passwordResetProcess.php" novalidate >
        <input type="hidden" name="name" value="<?php echo $name?>">
        <input type="hidden" name="phonenumber" value="<?php echo $phonenumber?>">
        <input type="hidden" name="username" value="<?php echo $username?>">
        <div class="form-group">
            <label class="control-label col-sm-3" for="pwd">비밀번호:</label>
            <div class="col-sm-9">          
                <input type="password" class="form-control" id="pwd" placeholder="영문자, 숫자, 특수문자 조합 8~15자" name="pwd">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3" for="confirmpwd">비밀번호 확인:</label>
            <div class="col-sm-9">          
                <input type="password" class="form-control" id="confirmpwd" placeholder="비밀번호확인" name="confirmpwd">
            </div>
        </div>

        <div class="col-sm-12" style="text-align: center; color: #919191; margin-top: 10px;"> 
            <button type="submit" style="background-color: white; color: black; border: 2px solid #4CAF50;padding: 10px 80px; text-align: center; text-decoration: none; display: inline-block; border-radius: 10px; font-size: 16px; margin: 4px 2px; -webkit-transition-duration: 0.4s; transition-duration: 0.4s; cursor: pointer;">비밀번호변경</button>
            <div id='idCheckerResult'></div>
        </div>
    </form>
</div>
        
     
</div>
</body>
</html>

