<?php session_start();?>

<?php
	
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";

 	$data = isset($_GET['data']) ? $_GET['data'] : '';
   
	if ($data == '') {
    echo "<script type='text/javascript'> alert('Wrong Access');self.close(); </script>";
    exit();
  }
 ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 <style>
   .btn {
    border: none;
    display: inline-block;
    padding: 8px 16px;
    vertical-align: middle;
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    font-size: 1em;
    background-color: inherit;
    text-align: center;
    cursor: pointer;
    white-space: nowrap;color: #FFFFFF;
    background-color: #4CAF50;
   }
 </style>
<script language="javascript">
  function save() {
    var fullName = document.myForm.fullName;
    var baid = document.myForm.baid;
    var contactNo = document.myForm.contactNo;
    var description = document.myForm.description;
    var data = "<?php echo $data?>";

    if(fullName.value == '') {
      alert('회원성명을 입력해주세요');
      document.getElementById("fullName").focus();
      return false;
    }

    if(baid.value == '') {
      alert('회원번호를 입력해주세요');
      document.getElementById("baid").focus();
      return false;
    }
     if(contactNo.value == '') {
      alert('연락처를 입력해주세요');
      document.getElementById("contactNo").focus();
      return false;
    }
   
    document.myForm.orderNo.value = data;
    document.myForm.action="process.php";
    document.myForm.submit();

  }
</script>
</head>
<body>
<form name="myForm" method="post">
  <p>Kit 전달 정보 입력 </p>
  <table border="0" cellspacing="1" cellpadding="2" style="font-style: normal; font-size: 12px;font-weight: bold; width:100%; margin-bottom:20px;">
    <tr>
      <input type="hidden" name="orderNo">
      <td style="width:20%">회원성명</td>
      <td style="width:80%"><input type="text" name="fullName" id="fullName" maxlength="10"></td>
    </tr>
    <tr>
        <td style="width:20%">회원번호</td>
       <td style="width:80%"><input type="text" name="baid" id="baid" maxlength="10"></td>
    </tr>
    <tr>
        <td style="width:20%">연락처</td>
       <td style="width:80%"><input type="text" name="contactNo" id="contactNo" maxlength="15"></td>
    </tr>
    <tr>
        <td style="width:20%">기타내용</td>
       <td style="width:80%"><input type="text" name="description" id="description" maxlength="40" size="50"></td>
    </tr>
  </table>
  <div style="text-align: center;">
  <input type="button" value="저장하기" onClick="save();" class="btn">
  <input type="button" value="창닫기" onClick="self.close();" class="btn">
  </div>
</form>
</body>
</html>
	 