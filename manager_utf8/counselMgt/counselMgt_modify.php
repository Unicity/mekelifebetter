<?php session_start();?>

<?php
	
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../../AES.php";
 	
 	$id = str_quote_smart(trim($id));

 	$query = "select member_no, name, contact_no, counsel_type1, counsel_type2, location, DSC, transferred_dept, transferred_staff, short_comment, description,status from tb_counsel where id = $id";

 	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	
	$member_no = $row['member_no'] ;
	$member_name = $row['name'] ;
	$contact_no = $row['contact_no'] ;
	$type1 = $row['counsel_type1'] ;
	$type2 = $row['counsel_type2'] ;
	$location = $row['location'] ;
	$DSC = $row['DSC'] ;
	$transferred_dept = $row['transferred_dept'] ;
	$transferred_staff = $row['transferred_staff'] ;
	$short_comment = $row['short_comment'] ;
	$description = $row['description'] ;
	$status = $row['status'] ;

 ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<title><?echo $g_site_title?></title>
<script language="javascript">
	function removeErrorClass(val){
		document.getElementById(val).classList.remove("error");
	}
	function checkEmpty(val){
		var attribute = document.getElementById(val) ;
		if(attribute.value == ""){
			attribute.className = "error";
			return 0;
		}
	}
	function save() {
 

		if (checkEmpty('received_no') == 0) {
			return ;
		}


		if (checkEmpty('counsel_Type1') == 0) {
			return ;
		}
 
		var type2 = document.modifyForm.counsel_Type2.value;
		 
		if (type2 == ""){
			document.modifyForm.counsel_Type2.focus();
			return ; 
		}
		

		if (checkEmpty('description') == 0) {
			return ;
		}
		 

		document.modifyForm.action = 'counselMgt_modify_save.php?';
		document.modifyForm.submit();
	}
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<style type='text/css'>
body {
	font-family: Sans-serif, Arial, Monospace; 
}
td {FONT-SIZE: 9pt}
.h {FONT-SIZE: 9pt; LINE-HEIGHT: 120%}
.h2 {FONT-SIZE: 9pt; LINE-HEIGHT: 180%}
.s {FONT-SIZE: 8pt}
.l {FONT-SIZE: 11pt}
.text {  line-height: 125%}

.infotable {    
    border: 1px solid #ddd;
    text-align: left;
    border-collapse: collapse;
    width: 85%;
    margin: 2px 1%;
}

.infotable, td {
    padding: 3px 3px;
    font-size: 12px;
}
.button {
    background-color: #555555; /* Green */
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
}


.error {
	border: 2px solid red;
}
</style>
</head>
<body>
	<form name="modifyForm" method="post" action="">
		<input type="hidden" name="id" value='<?php echo $id;?>'>
		<h2>상담내용 수정</h2>
		<table class="infotable" >
		<tr>
			<td width="10%">회원번호</td>
			<td width="20%"><input type="text" name="member_no" id="member_no" value="<?php echo $member_no;?>"></td>
			<td width="10%">회원명</td>
			<td width="20%"><input type="text" name="member_name" id="member_name" value="<?php echo $member_name;?>"></td>
			<td width="10%">인입전화번호*(번호만)</td>
			<td width="20%"><input type="text" name="received_no" id="received_no" onfocus="removeErrorClass('received_no')" maxlength="11" value="<?php echo $contact_no;?>"></td>
		</tr>
		<tr>
			<td>상담구분*</td>
			<td><input type="radio" name="counsel_Type2" id="counselType1" value="01" <?php if($type2=='01') echo 'checked';?>>
				<label for="counselType1">일반상담</label>
				<input type="radio" name="counsel_Type2" id="counselType2" value="02" <?php if($type2=='02') echo 'checked';?>>
				<label for="counselType2">클레임</label>
				<input type="radio" name="counsel_Type2" id="counselType3" value="03" <?php if($type2=='03') echo 'checked';?>>
				<label for="counselType3">건의</label>
			</td>
			<td>상담유형*</td>
			<td>
				<select name="counsel_Type1" id="counsel_Type1">
					<option value="01" <?php if($type1=='01') echo 'selected';?>>본인동의</option>
					<option value="02" <?php if($type1=='02') echo 'selected';?>>수당/실적</option>
					<option value="03" <?php if($type1=='03') echo 'selected';?>>주문/반품</option>
					<option value="04" <?php if($type1=='04') echo 'selected';?>>가입/해지</option>
					<option value="05" <?php if($type1=='05') echo 'selected';?>>제품문의(기타)</option>
					<option value="06" <?php if($type1=='06') echo 'selected';?>>제품문의(건식)</option>
					<option value="07" <?php if($type1=='07') echo 'selected';?>>제품문의(화장품)</option>
					<option value="08" <?php if($type1=='08') echo 'selected';?>>1800</option>
					<option value="09" <?php if($type1=='09') echo 'selected';?>>세무/증빙</option>
					<option value="10" <?php if($type1=='10') echo 'selected';?>>웹사이트/모바일앱</option>
					<option value="11" <?php if($type1=='11') echo 'selected';?>>군포물류</option>
					<option value="12" <?php if($type1=='12') echo 'selected';?>>우체국</option>
					<option value="13" <?php if($type1=='13') echo 'selected';?>>국제후원</option>
					<option value="14" <?php if($type1=='14') echo 'selected';?>>오토쉽</option>
					<option value="15" <?php if($type1=='15') echo 'selected';?>>정수기</option>
					<option value="16" <?php if($type1=='16') echo 'selected';?>>기타</option>
					 
				</select>
			</td>
			<td>지역</td>
			<td>
				<select name="location" id="location">
					<option value="">지역선택</option>
					<option value="서울" <?php if($location=='서울') echo 'selected';?>>서울(서울,그외 경기,제주)</option>
					<option value="안산" <?php if($location=='안산') echo 'selected';?>>안산(안산,수원,화성,군포,의왕,평택,오산)</option>
					<option value="인천" <?php if($location=='인천') echo 'selected';?>>인천(인천,부천,김포,일산,파주,광명,강화,시흥)</option>
					<option value="원주" <?php if($location=='원주') echo 'selected';?>>원주(강원,제천)</option>
					<option value="대전" <?php if($location=='대전') echo 'selected';?>>대전(대전,충청,세종)</option>
					<option value="부산" <?php if($location=='부산') echo 'selected';?>>부산(부산,울산,경남)</option>
					<option value="대구" <?php if($location=='대구') echo 'selected';?>>대구(대구,경북)</option>
					<option value="광주" <?php if($location=='광주') echo 'selected';?>>광주(광주,전라)</option>
				</select>
			</td>
		
		</tr>
		<tr>
			<td> 이관(메일)부서 </td>
			<td><select name="transferred_dept">
					<option value="" <?php if($transferred_dept=='') echo 'selected';?>>부서선택</option>
					<option value="SDSC" <?php if($transferred_dept=='SDSC') echo 'selected';?>>서울DSC</option>
					<option value="ADSC" <?php if($transferred_dept=='ADSC') echo 'selected';?>>안산DSC</option>
					<option value="TDSC" <?php if($transferred_dept=='TDSC') echo 'selected';?>>대전DSC</option>
					<option value="WDSC" <?php if($transferred_dept=='WDSC') echo 'selected';?>>원주DSC</option>
					<option value="BDSC" <?php if($transferred_dept=='BDSC') echo 'selected';?>>부산DSC</option>
					<option value="DDSC" <?php if($transferred_dept=='DDSC') echo 'selected';?>>대구DSC</option>
					<option value="KDSC" <?php if($transferred_dept=='KDSC') echo 'selected';?>>광주DSC</option>
					<option value="IDSC" <?php if($transferred_dept=='IDSC') echo 'selected';?>>인천DSC</option>
					<option disabled>-------------</option>
					<option value="PRD1" <?php if($transferred_dept=='PRD1') echo 'selected';?>>건식</option>
					<option value="PRD2" <?php if($transferred_dept=='PRD2') echo 'selected';?>>화장품</option>
					<option value="FA" <?php if($transferred_dept=='FA') echo 'selected';?>>Finance</option>
					<option value="OP" <?php if($transferred_dept=='OP') echo 'selected';?>>Operation</option>
					<option value="LA" <?php if($transferred_dept=='LA') echo 'selected';?>>Legal</option>
					<option value="LG" <?php if($transferred_dept=='LG') echo 'selected';?>>Logistics</option>
					<option value="WT" <?php if($transferred_dept=='WT') echo 'selected';?>>Water</option>
					<option value="DES" <?php if($transferred_dept=='DES') echo 'selected';?>>Design</option>
					<option value="EV" <?php if($transferred_dept=='EV') echo 'selected';?>>Event</option>
					<option value="IT" <?php if($transferred_dept=='IT') echo 'selected';?>>IT</option>
					<option value="HR" <?php if($transferred_dept=='HR') echo 'selected';?>>HR</option>
					<option value="PR" <?php if($transferred_dept=='PR') echo 'selected';?>>PR</option>
				</select>
			</td>
			<td>이관받은사람</td>
			<td><input type="text" name="transferred_staff" value="<?php echo $transferred_staff;?>"></td>
			<td>상담상태</td>
			<td>
				<select name="status">
					<option value="0" <?php if($status=='0') echo 'selected';?>>진행중</option>
					<option value="1" <?php if($status=='1') echo 'selected';?>>완료</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>기타(건의)(Max:100)</td>
			<td colspan="5"><input type="text" name="short_comment" maxlength="100" size="116" value="<?php echo $short_comment;?>"></td>
		</tr>
		<tr>
			<td colspan="6">상담내역* (Max:240)</td>
		</tr>
		<tr>
			<td colspan="6">
				<textarea cols="130" rows="5" name="description" id="description" style="resize:none;padding:10px;" maxlength="240"> <?php echo $description;?> </textarea>
			</td>
		</tr>
		
		
		<tr>
			<td colspan="6" align="center">
				<input type="button" class="button" value="저장" onClick="save();">
				<input type="button" class="button" value="닫기" onClick="self.close();">
			</td>
		</tr>
	</table>
	</form>

	<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>
</body>
</html>
 
