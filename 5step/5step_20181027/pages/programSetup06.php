<?php
    include_once("../inc/function.php");
    checkSessionValue();
?>
<? include "./header_setup.inc"; ?>
<?php 

    $username = $_SESSION['username'];
    $name = $_SESSION['name'];
    $programID = isset($_SESSION['programID']) ? $_SESSION['programID'] : '';
   
    $oagree = $_POST['oagree'];
    $cagree = $_POST['cagree'];

    $programname = $_POST['programname'];
    $programtype = $_POST['programtype'];
    $startdate = $_POST['startdate'];
    $period = $_POST['period'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $step3  = $_POST['step3'];
    $type  = $_POST['type'];
    if ($programtype =='1')
    {
      $step4items = array("29276", "29200");
      $step5items = array("24991", "28911", "24927", "26189");                
    } else {
      $step4items  = $_POST['step4items'];
      $step5items  = $_POST['step5items'];
    }
    
    $height = '';
    $weight = '';

    if($programtype=='1'){
      $height = $_POST['height'];
      $weight = $_POST['weight'];
    }

?>
  <script>
    $(document).ready(function() {
    
      $(".modifyButton").click(function(event) {
        window.location.replace('http://www.makelifebetter.co.kr/5step/pages/programSetup01.php');
      });
      
      $(".saveButton").click(function(event) {
        
        $("form").submit();
        event.preventDefault();
        
        
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
    background-color: #eee;
    border-radius: 50%;
  }

  /* On mouse-over, add a grey background color */
  .item:hover input ~ .checkmark {
    background-color: #eee;
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
 
  <div style="margin: 0%; font-size:90%;">
     <h5 style="margin:8% 0% 3% 3%; font-size:16px;">&#9724;프로그램 정보</h5>
     <form name="myForm" action="../inc/programSetupProcess.php" method="POST">
      <input type="hidden" name="oagree" value="<?php echo $oagree;?>">
      <input type="hidden" name="cagree" value="<?php echo $cagree;?>">
      
      <?php if ($type == 'edit') { ?>
        <input type="hidden" name="type" value="<?php echo $type;?>">
        <input type="hidden" name="programid" value="<?php echo $programID;?>">

      <?php }    ?>

    <div class="row" style="margin:0% 3% 0% 1%; font-size:90%;">
       
      <table id="programinfo" class="pinfo" style="margin:0% 1%; text-align: center; background-color: #FFF;">
        <tr>
          <td style="width:30%; font-weight:bold">프로그램</td>
          <td style="text-align: left;vertical-align: middle;"> 
            <input type="text" name="programname" id="programname"  value="<?php echo $programname;?>" readonly>
             <input type="hidden" name="programtype" id="programtype"  value="<?php echo $programtype;?>">
              <input type="hidden" name="name" id="name"  value="<?php echo $name;?>">
          </td>
        </tr>
        <tr>
          <td style="width:30%; font-weight:bold">성별</td>
          <td style="text-align: left;vertical-align: middle;"> 
            <input type="text" name="genderText" id="genderText"  value="<?php echo $gender=='F' ? '여성' : '남성';?>" readonly>
             <input type="hidden" name="gender" id="gender"  value="<?php echo $gender;?>">
          </td>
        </tr>
        <tr>
          <td style="width:30%; font-weight:bold">나이</td>
          <td style="text-align: left;vertical-align: middle;"> 
            <input type="text" name="age" id="age"  value="<?php echo $age;?>" readonly>
          </td>
        </tr>
        <?php if($programtype == '1') { ?>
          <tr>
          <td style="width:30%; font-weight:bold">키</td>
          <td style="text-align: left;vertical-align: middle;"> 
            <input type="text" name="height" id="height"  value="<?php echo $height;?>" readonly> cm
          </td>
        </tr>
        <tr>
          <td style="width:30%; font-weight:bold">몸무게</td>
          <td style="text-align: left;vertical-align: middle;"> 
            <input type="text" name="weight" id="weight"  value="<?php echo $weight;?>" readonly> kg
          </td>
        </tr>

         <?php }?>
        <tr>
          <td style="width:30%; font-weight:bold">시작일</td>
          <td style="text-align: left;">
            <input type="text" id="startdate" name="startdate"  value="<?php echo $startdate;?>" readonly> 
          </td>
        </tr>
         <tr>
          <td style="width:30%; font-weight:bold">기간</td>
          <td style="text-align: left;">
             <input type="text" id="periodInMonth" name="periodInMonth"  value="<?php echo number_format($period/30);?>" readonly> 개월
             <input type="hidden" id="period" name="period"  value="<?php echo $period;?>"> 
          </td>
        </tr>
      </table> 
    </div>
    <br>
    <h5 style="margin:3% 3%; font-size:16px;">&#9724;섭취제품</h5>
     <div class="row" style="margin:0% 3% 0% 1%; font-size:90%;">
      <table id="personalinfo" class="pinfo" style="margin:0 1%; width:100%; text-align: center; background-color: #FFF">
        <tr>
          <td style="width:35%; font-weight:bold">STEP 3 클린즈</td>
          <td style="text-align: left;"> 
            <div>
              <?php 
                if($step3 == '0') {
                  echo '라이화이버'.'<br>';
                  echo '알로에 아보레센스'.'<br>';
                  echo '패러웨이 플러스'.'<br>';
                } else {
                  echo '미섭취';
                }
              ?>
              <input type="hidden" name="step3" value="<?php echo $step3;?>">
            </div>
            
          </td>
        </tr>
        <tr>
          <td style="width:35%; font-weight:bold">STEP 4 기초영양</td>
          <td style="text-align: left;">
            <div>
              <?php 
              $step4allitems = array('29276'=>'코어 헬스 팩'
                                      , '31021'=>'우먼스포뮬라1' 
                                      , '31022'=>'우먼스포뮬라2' 
                                      , '31020'=>'멘스포뮬라' 
                                      , '28582'=>'칠드런스 포뮬라' 
                                      , '29125'=>'칠드런스 칼슘' 
                                      , '28826'=>'데일리포스' 
                                      , '30821'=>'클로로파워' 
                                      , '26206'=>'엔지겐 B 플러스' 
                                      , '29200'=>'프로바이오닉 플러스' 
                                    ) ;

             // echo $step4items[0];
              if (is_array($step4items) || is_object($step4items)) {

                foreach ($step4items as $v) {
                  echo $step4allitems[$v].'<br>';
                  echo '<input type="hidden" name="step4items[]" value="'.$v.'">';  
                }
              }
              ?>
            </div>
          </td>
        </tr>
        <tr>
          <td style="width:35%; font-weight:bold">STEP 5 타켓영양</td>
          <td style="text-align: left;">
             <?php 
              $step5allitems = array('28974'=>'프로스테이트 티엘씨'
                                      , '28463'=>'리버 에센셜' 
                                      , '28584'=>'하와이안 노니' 
                                      , '25141'=>'오메가라이프-3' 
                                      , '25155'=>'비전 에센셜' 
                                      , '18739'=>'클리어소트' 
                                      , '17284'=>'피토파스' 
                                      , '28824'=>'바이오-씨' 
                                      , '28830'=>'셀룰라베이직' 
                                      , '24724'=>'코엔자임 큐텐' 
                                      , '19281'=>'키토리치' 
                                      , '30904'=>'본메이트 칼슘' 
                                      , '30823'=>'조인트 모빌리티' 
                                      , '27267'=>'이뮤니젠' 
                                      , '26189'=>'린 컴플리트' 
                                      , '23818'=>'소이프로틴' 
                                      , '22370'=>'프로바이오닉 플러스' 
                                      , '29843'=>'유니마테' 
                                      , '28586'=>'바이오스 라이프 C 플러스' 
                                      , '24991'=>'바이오스 라이프 S' 
                                      , '28911'=>'바이오스 라이프 S 플러스' 
                                      , '24927'=>'바이오스 라이프 이 에너지' 
                                      , '31215'=>'바이오스 라이프 7' 
                                      , '26022'=>'라이화이버' 
                                      , '24723'=>'알로에 아보레센스' 
                                      , '15744'=>'패러웨이 플러스' 
                                    ) ;

              if (is_array($step5items) || is_object($step5items)) {
                foreach($step5items as $value) {
                  echo $step5allitems[$value].'<br>';
                  echo '<input type="hidden" name="step5items[]" value="'.$value.'">'; 
                }
              }
              
              ?>
          </td>
        </tr>

       
      </table>
    </div>
  </form>
    
  <br>
  <div class="w3-center">
    <?php if($type =='edit') {
      echo "<button class='saveButton w3-button w3-white w3-border w3-border-green' style='width:90%'><u>저장</u></button>";
    } else { ?>
      <button class="modifyButton w3-button w3-white w3-border w3-border-green" style="width:40%"><u>수정</u></button>
      <button class="saveButton w3-button w3-white w3-border w3-border-green" style="width:40%"><u>저장</u></button>
     <?php }?>
    
  </div>
</div>
</body>
</html>