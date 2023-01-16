<?php
    include_once("../inc/function.php");
    include "./header_setup.inc"; 
 
?>
  <script>
  $(document).ready(function() {
    
    $("input[name=cagree]").click(function() {
        selectedBox = this.id;
        $("input[name=cagree]").each(function() {
            if ( this.id == selectedBox )
            {
                this.checked = true;
            }
            else
            {
                this.checked = false;
            };        
        });
    });    
      
    $(".button").click(function(event) {
      var consent1 = $("input[name=cagree]:checked").val();
      
      if(consent1 == '1'){
         $("#warningmsg").text("개인정보수집 및 이용에 동의하셔야 사용이 가능합니다.");
      } else {
        $("form").submit();
        event.preventDefault();
      }
      
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
    <form name="myForm" action="./register.php" method="POST">
      <div class="consent">
        <span>개인정보 수집 및 이용에 대한 동의</span>
        <div class="terms">
          <p><b>필수 수집 항목, 이용목적과 기간 및 거부 권리에 대한 안내</b></p>
            &#9724; 필수 수집 항목 <br>
          <div style="margin-bottom:-10px" >성명, 휴대폰 번호, 아이디, 비밀번호 </div>

          <br>&#9724; 수집 및 이용 목적 <br>
          <div style="margin-bottom:-10px" >유니시티코리아(유) (이하 ‘회사’라 함)의 회원관리 및 서비스 제공을 위하여 이용자의 개인정보를 수집합니다.</div>
             
          <br>&#9724; 보유 및 이용 기간<br> 
          <div style="margin-bottom:-10px" >회원탈퇴 시 또는 서비스 해지 시 이용자의 개인정보를 파기합니다. 단, 관계법령에 의해 보관해야 하는 정보는 해당 법령에서 정한 기간 동안 보관합니다.</div>
             
          <br>&#9724; 수집 동의 거부 권리 및 불이익 사항 </br>
          <div style="margin-bottom:-10px" >이용자는 개인정보 수집 및 이용을 거부하실 수 있습니다. 단, 개인정보의 필수적 수집 및 이용에 동의하지 않을 경우 서비스가 제한됩니다.</div>
        </div>
        
          <div class="col-sm-12" style="text-align: center; margin: 3% 0% 0% 0%; font-size: smaller;">
            <p>본인은 회사의 필수 개인정보 수집 및 이용에 관한 설명을 모두 읽고 이해하였으며, </p>
            <div class='item' style="margin-right:10px;vertical-align: middle;">
              <label style='margin:0px 0px;'>이에 동의합니다.
                <input type='checkbox' name='cagree' id='cagree0' value='0' checked>
                <span class='checkmark'></span>
              </label>
            </div>
             <div class='item'>
              <label style='margin:0px 0px;'> 동의하지 않습니다.
                <input type='checkbox' name='cagree' id='cagree1' value='1'>
                <span class='checkmark'></span>
              </label>
            </div>
          </div>
      
      </div>
       
    </div>
    </form>
    <div id="warningmsg" style="font-size:80%; color:red;"></div>
    <button class="button" style="border: 1px solid #4CAF50;" ><u>동의하기</u></button>
       
    </div>
 
  </div>
</body>
</html>