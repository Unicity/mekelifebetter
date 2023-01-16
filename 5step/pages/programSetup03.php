<?php
    include_once("../inc/function.php");
    checkSessionValue();
?>
<?php include "./header_setup.inc"; ?>
<?php 

 //   $username = $_SESSION['username'];
 //   $realname = $_SESSION['realname'];
 //   $programID = isset($_SESSION['programID']) ? $_SESSION['programID'] : '';

    $oagree = $_POST['oagree'];
    $cagree = $_POST['cagree'];
    $type = $_POST['type'];
    $step3 = isset($_POST['step3']) ? $_POST['step3'] : ''; 
   
    $programname = $_POST['programname'];
    $programtype = $_POST['programtype'];
    $startdate = $_POST['startdate'];
    $period = $_POST['period'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];

    $height = '';
    $weight = '';

    if($programtype=='1'){
      $height = $_POST['height'];
      $weight = $_POST['weight'];
    }
/*
    echo 'oagree '.$oagree.'<br>';
    echo 'cagree '.$cagree.'<br>';
    echo 'programname '.$programname.'<br>';
    echo 'programtype '.$programtype.'<br>';
    echo 'startdate '.$startdate.'<br>';
    echo 'period '.$period.'<br>';
    echo 'name '.$name.'<br>';
    echo 'gender '.$gender.'<br>';
    echo 'age '.$age.'<br>';
    echo 'height '.$height .'<br>';
    echo 'weight '.$weight .'<br>';
*/

?>
  <script>
  $(document).ready(function() {
    $("input[name=step3]").click(function() {
        selectedBox = this.id;
        $("input[name=step3]").each(function() {
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
      document.myform.oagree.value = '<?php echo $oagree ;?>';
      document.myform.cagree.value = '<?php echo $cagree ;?>';
      document.myform.programname.value = '<?php echo $programname ;?>';
      document.myform.programtype.value = '<?php echo $programtype ;?>';
      document.myform.startdate.value = '<?php echo $startdate ;?>';
      document.myform.period.value = '<?php echo $period ;?>';
      document.myform.name.value = '<?php echo $name ;?>';
      document.myform.gender.value = '<?php echo $gender ;?>';
      document.myform.age.value = '<?php echo $age ;?>';
      document.myform.height.value = '<?php echo $height ;?>';
      document.myform.weight.value = '<?php echo $weight ;?>';
      document.myform.type.value = '<?php echo $type ;?>';
    
      if (document.myform.programtype.value == '1') {
        $("form").attr('action', './programSetup06.php');
      }

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
    font-size: 15px;
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
    top: 0px;
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
    background-color: #0068b7;
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
   <div style="display:block; width:100%; background-color:#0068b7; color:#FFF; line-height:70px;text-align:center; margin:0;">
        <div style="display:inline-block; vertical-align: middle;font-size: 150%"><b>STEP 3 클린즈</b></div> 
        
    </div>
 
    <div class="container">
    
        <div style="margin: 5% 0% 5% 0%;">
          <div style="font-weight:600;text-align:center;font-size:2em;color:#0068b7; margin-top:10%; margin-bottom:10%; "><img src="../images/wave.png" style="margin:0; vertical-align:middle; margin-right:3%" width="55"/>CLEANSE</div>
          
          <table id="pinfo" style="width:100%;  background-color: #FFF; font-size:17px; margin-bottom:5%;">
            <tr>
              <td style="padding-left:10%; height: 60px;">알로에 아보레센스</td>
            </tr>
            <tr>
              <td style="padding-left:10%; height: 60px;">패러웨이 플러스</td>
            </tr>
            <tr>
              <td style="padding-left:10%; height: 60px;">라이화이버</td>
            </tr>
          </table>
        </div> 
        <form name="myform" action="./programSetup04.php" method="post">
          <div style="margin:auto; width:80%; padding:5px;">
            <div class='item' style="margin-right:100px;vertical-align: middle;">
                <label style='margin:0px 0px;'> 섭취
                  <input type='checkbox' name='step3' id='step3_0' value='0'  <?php if($step3 == '1') {} else echo 'checked'; ?> >
                  <span class='checkmark'></span>
                </label>
              </div>
               <div class='item'>
                <label style='margin:0px 0px;'> 미섭취
                  <input type='checkbox' name='step3' id='step3_1' value='1' <?php if($step3 == '1') {echo 'checked'; } else {} ?> >
                  <span class='checkmark'></span>
                </label>
              </div>
          </div>
          <input type='hidden' name='oagree'> 
          <input type='hidden' name='cagree'>
          <input type='hidden' name='programname'>
          <input type='hidden' name='programtype'>
          <input type='hidden' name='startdate'>
          <input type='hidden' name='period'>
          <input type='hidden' name='name'>
          <input type='hidden' name='gender'>
          <input type='hidden' name='age'>
          <input type='hidden' name='height'>
          <input type='hidden' name='weight'>
          <input type='hidden' name='type'>
        </form>
    </div>
    <button class="button" style="border: 1px solid #0068b7;margin-top:3%"><u>다음</u></button>
  
</body>
</html>