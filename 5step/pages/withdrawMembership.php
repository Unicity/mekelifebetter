<?php
    include_once("../inc/function.php");
    checkSessionValue();
    include "./header_setup.inc"; 
    
    $username = $_SESSION['username'];

?>
  <script>
  $(document).ready(function() {
    
    $("#withdraw").click(function(event) {
      var consent = $("input[name=agree]:checked").val();
       
      if(consent !== '1'){
         $("#warningmsg").text("안내사항에 동의하셔야 탈퇴가 진행됩니다.");
      } else {
        $("form").submit();
        event.preventDefault();
      }
      
    });  

    $("#cancel").click(function(event) {
      document.location.href = "./mainpage.php";      
    });   

  });
 


  </script>
  <style>
   .item {
    display: inline-block;
    position: relative;
    padding-left: 35px;
    margin-top: 0px;
    cursor: pointer;
    font-size: 13px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
  }

  /* Hide the browser's default checkbox */
  .item input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
  }

  /* Create a custom checkbox */
  .checkmark {
    position: absolute;
    top: -2px;
    left: 0;
    height: 23px;
    width: 23px;
    background-color: #fff;
    border-radius: 50%;
  }

  /* On mouse-over, add a grey background color */
  .item:hover input ~ .checkmark {
    background-color: #fff;
  }

  /* When the checkbox is checked, add a blue background */
  .item input:checked ~ .checkmark {
    background-color: #4CAF50;
  }

  /* Create the checkmark/indicator (hidden when not checked) */
  .checkmark:after {
    content: "";
    position: absolute;
    display: none;
  }

    /* Show the checkmark when checked */
  .item input:checked ~ .checkmark:after {
    display: block;
  }

  /* Style the checkmark/indicator */
  .item .checkmark:after {
    left: 9px;
    top: 6px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
  }
  </style>
  <div style="display:block; width:100%; background-color:#2E2E2E; color:#FFF; line-height:70px;text-align:center; margin:0;">
    <div style="display:inline-block; vertical-align: middle; margin-right:5%; font-size: 120%">UNICITY SCIENCE</div> 
    <div style="display:inline-block; vertical-align: middle; font-size:220%"> <b><font color="#0096e0">5</font> <font color="#3eb134">S</font><font color="#0068b7">T</font><font color="#f6ab00">E</font><font color="#ed6d00">P</font></b> </div>
  </div>
  <div style="padding:0px; margin:0px; text-align: center; width:100%;">
    <div class="program">
      <form name="myForm" action="../inc/withdrawProcess.php" method="POST">
        <div class="consent">
          <span>비회원 탈퇴 안내</span>
          <div class="terms">
            <p><b><font size="2">탈퇴할 경우 입력한 데이터의 재사용 및 복구가 불가능합니다.</font></b><br>
              &#9724; 탈퇴 후 입력한 데이터의 복구가 불가하오니 신중히 선택하시기 바랍니다. </p>
            <span style="font-size: 12px; color:red;">&#8251; 유니시티 회원의 경우, 회원십 해지 시 자동으로 탈퇴됩니다. </span></br>
          </div>
          <div class="col-sm-12" style="text-align: center; margin: 3% 0% 0% 0%; font-size: smaller;">
            <p>안내 사항을 모두 확인하였으며,</p>
            <div style="margin-right:10px;vertical-align: middle;">
              <label  class='item' style='margin:0px 0px;'>이에 동의합니다.
                <input type='checkbox' name='agree' value="1" checked="checked" >
                <span class='checkmark'></span>
              </label>
            </div>
          </div>
        </div>
        <input type='hidden' name="username" value="<?php echo $username?>">
      </form>
      <div id="warningmsg" style="font-size:80%; color:red;margin-left:1%;"></div>
      <button class="halfbutton" style="border: 1px solid #4CAF50;" id="withdraw" ><u>확인</u></button>
      <button class="halfbutton" style="border: 1px solid #7f7f7f;" id="cancel"><u>취소</u></button>
    </div>
  </div>
</body>
</html>

