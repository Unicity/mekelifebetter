<?php
    include_once("../inc/function.php");
    checkSessionValue();

    if (!include_once("../inc/dbconn.php")){
    echo "The config file could not be loaded";
  }
?>
<?php include "./header_setup.inc"; ?>
<?php 

  //  $username = $_SESSION['username'];
  //  $name = $_SESSION['name'];
  //  $programID = isset($_SESSION['programID']) ? $_SESSION['programID'] : '';

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
    $step4items  = $_POST['step4items'];

    $type = $_POST['type'];
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
    echo 'step3'.$step3.'<br>';
    echo 'step4items'.$step4items[0];
    */

    $query ="SELECT ProductID, ProductName FROM Product where step = 5";

    $result = mysql_query($query);


?>
  <script>
    $(document).ready(function () {
      var limit = 5;
      $('input[type=checkbox]').on('change', function(evt) {
         if($('input[type=checkbox]:checked').length > limit) {
             this.checked = false;
         }
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
        document.myform.step3.value = '<?php echo $step3; ?>';
        document.myform.type.value = '<?php echo $type; ?>';
         
        
        var chkArray = [];
        $(".chk:checked").each(function() {
          chkArray.push($(this).val());
        });

        var selected = chkArray.join(',');
      
       if(selected == ''){
           $("#warningmsg").text("타겟영양 제품을 선택해 주세요.(최대 5개)");
        } else {
          $("form").submit();
          event.preventDefault();
        }
      });  
    });
  </script>
  <style>
    .item {
    display: block;
    position: relative;
    padding-left: 35px;
    margin-bottom: 0px;
    cursor: pointer;
    font-size: 17px;
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
        top: 2px;
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
        background-color: #ed6d00;
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
   <div style="display:block; width:100%; background-color:#ed6d00; color:#FFF; line-height:70px;text-align:center; margin:0;">
        <div style="display:inline-block; vertical-align: middle;font-size: 150%"><b>STEP 5 타겟영양</b></div> 
        
    </div>
 
    <div class="container">
    
        <div style="margin: 5% 0% 5% 0%;">
         <div style="font-weight:600;text-align:center;font-size:2em;color:#ed6d00; margin-top:10%; margin-bottom:10%; "><img src="../images/squared_tablet.png" style="margin:0; vertical-align:middle; margin-right:3%" width="55"/>TARGET</div>
           <form name="myform" action="./programSetup06.php" method="post">
          <div style="vertical-align:middle; margin:0 0% 2% 10%;"><img src="../images/checked_red.png" width="25px" style="margin: 0 1% 1% 0"/> 최대5개까지 선택 가능합니다.</div>
          <table id="pinfo" style="width:100%;  background-color: #FFF; font-size:15px; margin-bottom:5%;">
            <?php 
              while ($row = mysql_fetch_array($result)) {
                echo "
                  <tr>
                    <td style='padding-left:10%; height: 60px;'>
                      <div class='item'>
                        <label style='margin:0px 0px;'>".$row['ProductName']."
                          <input type='checkbox' class='chk' name='step5items[]' value='".$row['ProductID']."'> 
                          <span class='checkmark'></span>
                        </label>
                      </div>
                    </td>
                  </tr>
                ";

              }
            ?>
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
            <input type='hidden' name='step3'>
            <input type='hidden' name='type'>
            <?php
             if (is_array($step4items) || is_object($step4items)) {

                foreach ($step4items as $value) {
                  echo '<input type="hidden" name="step4items[]" value="'. $value. '">';  
                
                }
              }
            ?>
          </table>
        </form>
        </div> 
           
        
    </div>
    <div id="warningmsg" style="font-size:80%; color:red;margin-left:3%;"></div>
    <button class="button" style="border: 1px solid #ed6d00;margin-top:3%"><u>다음</u></button>
  
</body>
</html>