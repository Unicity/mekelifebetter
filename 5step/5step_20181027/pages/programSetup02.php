<?php
    include_once("../inc/function.php");
    checkSessionValue();
    
    if (!include_once("../inc/dbconn.php")){
    echo "The config file could not be loaded";
  }
?>

<? include "./header_setup.inc"; ?>
<?php 

  //  $username = $_SESSION['username'];
  //  $realname = $_SESSION['realname'];
    $programID = isset($_SESSION['programID']) ? $_SESSION['programID'] : '';
    
    $type = isset($_GET['type']) ? $_GET['type'] : 'new';

    $oagree = $_POST['oagree'];
    $cagree = $_POST['cagree'];
    if ($type !='edit' && ($oagree =="" || $cagree=="")){
      DisplayAlert('잘못된 접근입니다.');
      moveTo('../index.html');
    }

    $programName = '';
    $programType = '';
    $startDate = '';
    $duration = '';
    $name = '';
    $gender = '';
    $age = '';
    $height = '';
    $weight = '';


    if($type == 'edit'){
      $query = "SELECT programName, programType, startDate, duration, name, gender, age, height, weight, step3, consent FROM ProgramMaster WHERE ProgramMaster.programID =  $programID";

      $result = mysql_query($query) or die(mysql_error());

      $programInfo = mysql_fetch_array($result) or die(mysql_error());;

      $programName = $programInfo['programName'];
      $programType = $programInfo['programType'];
      $startDate = $programInfo['startDate'];
      $duration = $programInfo['duration'];
      $name = $programInfo['name'];
      $gender = $programInfo['gender'];
      $age = $programInfo['age'];
      $height = $programInfo['height'];
      $weight = $programInfo['weight'];
      $step3 = $programInfo['step3'];
      $consent = $programInfo['consent'];
      
    }

?>
  <script>
    $(document).ready(function() {
      var today = new Date();
      var sevendays = new Array();
      var months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
      var type = '<?php echo $type ?>';
      var programtype = '<?php echo $programType?>';

      for(var i=0; i<7; i++) {
        var theday = new Date();
        theday.setDate(today.getDate()+i);
        
        var year = theday.getFullYear();
        var month = theday.getMonth();
        var date = theday.getDate() < 10 ? '0'+theday.getDate() : theday.getDate();

        sevendays.push(year+'-'+months[month]+'-'+date);
      }
      var startdate = '<?php echo date('Y-m-d', strtotime($startDate))?>';

      var startdateoption = "<option value=''>시작일선택</option>";
      for(var i=0; i < sevendays.length; i++){
        var selected = "";

        if (startdate == sevendays[i]) { 
          selected = 'selected';
        }
        if (type == 'edit' && startdate < sevendays[0] && i ==0){
          startdateoption = "<option value='"+startdate+"' selected>"+startdate+"</option>";
        }
        startdateoption += "<option value='"+sevendays[i]+"'"+selected+">"+sevendays[i]+"</option>";
      }
      $( 'select[name="startdate"]' ).append( startdateoption );
      if(type == 'edit' && programtype == '1') {
        $('#trheight' ).show();
        $('#trweight' ).show();  
      } else {
        $('#trheight' ).hide();
        $('#trweight' ).hide();  
      }

      
      $("input[name=gender]").click(function() {
        selectedBox = this.id;
        $("input[name=gender]").each(function() {
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
    $("input[name=programtype]").click(function() {
        selectedBox = this.id;
        $("input[name=programtype]").each(function() {
            if ( this.id == selectedBox )
            {
                this.checked = true;
            }
            else
            {
                this.checked = false;
            }
            if(selectedBox == 'programtype2') {
              $('#trheight' ).show();
              $('#trweight' ).show();
              $('#period')
                .find('option')
                .remove()
                .end()
                .append('<option value="181">6</option>')
                .val('181')
              ;
            } else {
              $('#trheight' ).hide();
              $('#trweight' ).hide();
              $('#period')
                .find('option')
                .remove()
                .end()
                .append('<option value="" selected>기간설정</option>')
                .append('<option value="31">1</option>')
                .append('<option value="61">2</option>')
                .append('<option value="91">3</option>')
                .append('<option value="121">4</option>')
                .append('<option value="151">5</option>')
                .append('<option value="181">6</option>')
                 
              ;
            }     
        });
    });
     $(".button").click(function(event) {
      var programname= $("#programname").val();

      var programtype= $("#programtype1").is(":checked") ? 0 : 1;
      var startdate= $("#startdate").val();
      var period= $("#period").val();

      var name= $("#name").val(); 
      var gender= $("#genderF").is(":checked") ? 'F' : 'M';
      var age= $("#age").val();
      var height =  weight = 0;

       if (startdate == "" || typeof startdate == 'undefined'){
        $("#startdate").css("border","solid red 2px");
         return null;
      } else {
        $("#startdate").css("border","solid #ddd 1px");
      }

      if (period == "" || typeof period == 'undefined'){
        $("#period").css("border","solid red 2px");
         return null;
      } else {
        $("#period").css("border","solid #ddd 1px");
      }

      if(name =="" || typeof name == 'undefined'){
        $("#name").css("border","solid red 2px");
         return null;
      } else {
        $("#name").css("border","solid #ddd 1px");
      }

      if(age =="" || typeof age == 'undefined'){
        $("#age").css("border","solid red 2px");
         return null;
      } else {
        $("#age").css("border","solid #ddd 1px");
      }

       if (programtype == 1) {
        height = $("#height").val();
        weight = $("#weight").val();

        if(height =="" || typeof height == 'undefined'){
          $("#height").css("border","solid red 2px");
           return null;
        } else {
          $("#height").css("border","solid #ddd 1px");
        }

        if(weight =="" || typeof weight == 'undefined'){
          $("#weight").css("border","solid red 2px");
          return null;
        } else {
          $("#weight").css("border","solid #ddd 1px");
        }

      }

      if (programname == "" || typeof programname == 'undefined') {
        programname ="프로그램_" + name;

        document.myForm.programname.value = programname;
      }

      document.myForm.programtype.value = programtype;
      document.myForm.gender.value = gender;
      
      
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
     <h5 style="margin:8% 0% 3% 3%; font-size:16px;">&#9724;프로그램 설정</h5>
     <form name="myForm" action="./programSetup03.php" method="POST">
      <input type="hidden" name="oagree" value="<?php echo $oagree;?>">
      
      <?php if($type =='edit') {  ?>
      <input type="hidden" name="type" value="<?php echo $type;?>">
      <input type="hidden" name="step3" value="<?php echo $step3;?>"> 
      <input type="hidden" name="oagree" value="<?php echo $consent;?>">
      <?php } else { ?>
      <input type="hidden" name="cagree" value="<?php echo $cagree;?>">  
      <?php } ?>
    <div class="row" style="margin:0% 3% 0% 1%; font-size:90%;">
      <table id="programinfo" class="pinfo" style="margin:0% 1%; text-align: center; background-color: #FFF;">
        <tr>
          <td style="width:30%; font-weight:bold">프로그램</td>
          <td style="text-align: left;vertical-align: middle;"> 
            <input type="text" name="programname" id="programname" placeholder="프로그램명을 입력하세요" maxlength="30" value="<?php echo $programName?>">
          </td>
        </tr>
        <tr>
          <td style="width:30%; font-weight:bold">타입</td>
          <td style="text-align: left;">
            <div class='item' style="margin-right:10px;vertical-align: middle;">
              <label style='margin:0px 0px;'> MY PROGRAM
                <input type='checkbox' name='programtype' id='programtype1' value='0' <?php if($programType == '1') {} else echo 'checked'; ?>>
                <span class='checkmark'></span>
              </label>
            </div>
             <div class='item'>
              <label style='margin:0px 0px;'> DYP
                <input type='checkbox' name='programtype' id='programtype2' value='1' <?php if($programType == '1') {echo 'checked'; } else {} ?>>
                <span class='checkmark'></span>
              </label>
            </div>
          </td>
        </tr>
        <tr>
          <td style="width:30%; font-weight:bold">시작일</td>
          <td style="text-align: left;">
            <select id="startdate" name="startdate" style="background-color: #FFF; border: 1px solid #ddd"></select>
          </td>
        </tr>
         <tr>
          <td style="width:30%; font-weight:bold">기간</td>
          <td style="text-align: left;">

            <select id="period" name="period" style="background-color: #FFF; border: 1px solid #ddd">
              <option value="">기간설정</option>
              <option value="31"  <?php if($duration == 31) {echo 'selected';}?>>1</option>
              <option value="61"  <?php if($duration == 61) {echo 'selected';}?>>2</option>
              <option value="91"  <?php if($duration == 91) {echo 'selected';}?>>3</option>
              <option value="121" <?php if($duration == 121) {echo 'selected';}?>>4</option>
              <option value="151" <?php if($duration == 151) {echo 'selected';}?>>5</option>
              <option value="181" <?php if($duration == 181) {echo 'selected';}?>>6</option>
            </select> 개월
          </td>
        </tr>
      </table> 
    </div>
    <br>
    <h5 style="margin:3% 3%; font-size:16px;">&#9724;이용자 정보 입력</h5>
     <div class="row" style="margin:0% 3% 0% 1%; font-size:90%;">
      <table id="personalinfo" class="pinfo" style="margin:0 1%; width:100%; text-align: center; background-color: #FFF">
        <tr>
          <td style="width:30%; font-weight:bold">이름</td>
          <td style="text-align: left;"> 
            <input type="text" name="name" id="name" placeholder="이름을 입력하세요" maxlength="30" required value="<?php echo $name?>">
          </td>
        </tr>
        <tr>
          <td style="width:30%; font-weight:bold">성별</td>
          <td style="text-align: left;">
            <div class='item' style="margin-right:10px;vertical-align: middle;">
              <label style='margin:0px 0px;'> 여자
                <input type='checkbox' name='gender' id='genderF' value='F' <?php if($gender == 'M') {} else echo 'checked'; ?>>
                <span class='checkmark'></span>
              </label>
            </div>
             <div class='item'>
              <label style='margin:0px 0px;'> 남자
                <input type='checkbox' name='gender' id='genderM' value='M' <?php if($gender == 'M') {echo 'checked'; } else {} ?> >
                <span class='checkmark'></span>
              </label>
            </div>
          </td>
        </tr>
        <tr>
          <td style="width:30%; font-weight:bold">연령</td>
          <td style="text-align: left;">
            <input type="number" name="age" id="age" min="20" max="90" value="<?php echo $age?>"> 세
          </td>
        </tr>

        <tr id="trheight">
          <td style="width:30%; font-weight:bold">키</td>
          <td style="text-align: left;">
            <input type="number" name="height" id="height" min="120" max="290" value="<?php echo $height?>"> cm
          </td>
        </tr>
        <tr id="trweight">
          <td style="width:30%; font-weight:bold">몸무게</td>
          <td style="text-align: left;">
            <input type="number" name="weight" id="weight" min="20" max="290" value="<?php echo $weight?>"> kg
          </td>
        </tr>
      </table>
    </div>
   </form>
    
  <br>
 <button class="button" style="border: 1px solid #4CAF50;"><u>다음</u></button>
</div>
</body>
</html>