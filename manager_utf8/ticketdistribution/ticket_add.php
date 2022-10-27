<?php session_start();?>

<?php
	
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";

 	$eventQuery = "SELECT DISTINCT eventName FROM tb_ticket_master;";
  $eventQueryResult = mysql_query($eventQuery);
   
	if ($s_adm_id == '') {
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
  	var orderNo = document.myForm.orderNo;
  	var eventName = document.myForm.eventName;
    var fullName = document.myForm.fullName;
    
    var baid = document.myForm.baid;
    var leader = document.myForm.leader;
    var contactNo = document.myForm.contactNo;
    var orderedQty = document.myForm.orderedQty;
    var description = document.myForm.description;
    
    var ticketpanel = document.getElementById('ticketNoPanel').innerHTML;
     

    if(orderNo.value == '') {
      alert('주문번호를 입력해주세요');
      document.getElementById("orderNo").focus();
      return false;
    }

    if(eventName.value == '') {
      alert('이벤트명을 입력해주세요');
      document.getElementById("eventName").focus();
      return false;
    }
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
    if(leader.value == '') {
      alert('그룹정보를 입력해주세요');
      document.getElementById("leader").focus();
      return false;
    }
    if(contactNo.value == '') {
      alert('연락처를 입력해주세요');
      document.getElementById("contactNo").focus();
      return false;
    }
   	if(orderedQty.value == '' || orderedQty.value < 1) {
      alert('주문수량을 입력해주세요');
      document.getElementById("orderedQty").focus();
      return false;
    }

    if(ticketpanel.length > 5){
      var ticketPrefix = document.getElementById('ticketPrefix').value;
      if(ticketPrefix == ""){
        alert('티켓구분자를 선택해주세요');
        document.getElementById('ticketNo'+i).focus();
        return false;
      }

      for(var i=0; i<orderedQty.value; i++){
        if (document.getElementById('ticketNo'+i).value ==""){
          alert('티켓번호를 입력해주세요');
          document.getElementById('ticketNo'+i).focus();
          return false;
        }
      }
    }
    document.myForm.action="add_process.php";
    document.myForm.submit();

  }
  function showTicketPanel(){
    var qty = document.getElementById('orderedQty').value;
     
    var thePanel = document.getElementById('ticketNoPanel');
    var ticketPrefix = ['E','L','O','R', 'S'];
    html = "<td>";
    html +=  "<SELECT name='ticketPrefix' id='ticketPrefix'>"
            + "<option value=''>티켓구분자</option>";
    for(var j=0; j<ticketPrefix.length;j++){
      html += "<option value='"+ticketPrefix[j]+"'>"+ticketPrefix[j]+"</option>";
    }
    html +="</select><br>";
    for(var i=0; i<qty; i++){
      
      html +="<input type='text' name='ticketNo"+i+"' id='ticketNo"+i+"'><br>";
    }
    
    html = "<td>티켓번호</td>"+html + "</td>";
    thePanel.innerHTML = html;
  }
   
</script>
</head>
<body>
<form name="myForm" method="post">
  <p>티켓 주문 입력 </p>
  <table border="0" id="ticketTable" cellspacing="1" cellpadding="1" style="font-style: normal; font-size: 12px;font-weight: bold; width:100%; margin-bottom:20px;">
  	 <tr>
      <td style="width:20%">주문번호 </td>
      <td style="width:80%"><input type="text" name="orderNo" id="orderNo" maxlength="10"></td>
    </tr>
     <tr>
      <td style="width:20%">이벤트 </td>
      <td style="width:80%"><input type="text" name="eventName" id="eventName" maxlength="15">
      </td>
    </tr>
    <tr>
      <td style="width:20%">회원성명</td>
      <td style="width:80%"><input type="text" name="fullName" id="fullName" maxlength="10"></td>
    </tr>
    <tr>
        <td style="width:20%">회원번호</td>
       <td style="width:80%"><input type="text" name="baid" id="baid" maxlength="10"></td>
    </tr>
    <tr>
        <td style="width:20%">그룹</td>
       <td style="width:80%"><input type="text" name="leader" id="leader" maxlength="15"></td>
    </tr>
    <tr>
        <td style="width:20%">연락처</td>
       <td style="width:80%"><input type="text" name="contactNo" id="contactNo" maxlength="15"></td>
    </tr>
    <tr>
        <td style="width:20%">주문수량</td>
       <td style="width:80%"><input type="number" name="orderedQty" id="orderedQty" min="1"></td>
    </tr>
    <tr>
        <td style="width:20%">기타내용</td>
       <td style="width:80%"><input type="text" name="description" id="description" maxlength="40" size="50"></td>
    </tr>
    <tr>
        <td style="width:20%"></td>
       <td style="width:80%"><input type="button" name="addTicketNo" id="addTicketNo" value="티켓번호입력" onclick="showTicketPanel()"></td>
    </tr>
    <tr id="ticketNoPanel">
    </tr>
  </table>
  
  <div style="text-align: center;">
  <input type="button" value="저장하기" onClick="save();" class="btn">
  <input type="button" value="창닫기" onClick="self.close();" class="btn">
  </div>
</form>
</body>
</html>
	 