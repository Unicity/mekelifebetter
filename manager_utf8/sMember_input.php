<?php
    session_start();
?>

<?
    include "admin_session_check.inc";
    include "inc/global_init.inc";
    include "../dbconn_utf8.inc";
    include "inc/common_function.php";
    
    include "../AES.php";
    
    
?>


<html>
	<head>
    	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    	<meta http-equiv="X-Frame-Options" content="deny" />
    	<link rel="stylesheet" href="inc/admin.css" type="text/css">
    	<title>S회원 입력</title>
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>



    </head>
    <body>
    	<form name="frm_m" method="post">
    		<table cellspacing="0" cellpadding="10" class="title">
    			<tr>
    				<td align="left"><b>S회원 관리</b></td>
    				<td align="right" width="300" align="center" bgcolor=silver>
    					<input type="button" onclick="goBack();" value="목록" name="btn4">
    				</td>
    			</tr>
    		</table>
    		<table height='35' width='100%' cellpadding='0' cellspacing='0'border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF'bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
    			<tr>
					<td align='center'>
						<table border="0" cellspacing="1" cellpadding="2" class="IN3">
							<tr>
								<th>일시정지(S)일자</th>
								<td>
									<input type="text" id="startDate" name="startDate" >
								</td>
							</tr>
							<tr>
								<th>회원번호</th>
								<td>
									<input type="text" name="baId" maxlength="10"value="<?php echo $baId?>" onkeyup="enterkey();"> <a href="#none" onclick="search_member(); return false;">FO 아이디 검증</a>
								</td>
							</tr>
							
							<tr>
								<th>성명</th>
								<td>
									<input type="text" name="baName" maxlength="15"value="<?php echo $baName?>" readonly="readonly">
								</td>
							</tr>
							<tr>
								<th>회원쉽전환 알람일자</th>
								<td>
									<input type="text" id="endDate" name="endDate">
								</td>
							</tr>
							<tr>
								<th>일시정지(S) 사유</th>
								<td>
									<input type="text" id="note" name="note" style="width:500px">
								</td>
							</tr>
							<tr>
								<th>오토쉽 유무</th>
								<td>
									<select id="autoshipYn" name="autoshipYn">
										<option value="">선택</option>
										<option value="Y">Y</option>
										<option value="N">N</option>
									</select>
								</td>
							</tr>	
							<tr>
								<th>처리상태</th>
								<td>
									<select id="reg_status" name="reg_status">
										<option value="">선택</option>
										<option value="2">일시정지(S)</option>
										<option value="3">복귀(A)</option>
										<option value="4">해지(T)</option>
									</select>
								</td>
							</tr>	
						</table>
					</td>
				</tr>
				<tr>
					<td align=center>
						<input type="button" name="save" value="저장" onclick="insertInfo();">
						<input type="button" value="목록" onclick="goBack();"> 
					</td>
				</tr>
    		</table>
    		
		</form>
    </body>
    <script type="text/javascript">

    	function goBack() {
    		document.frm_m.target = "frmain";
    		document.frm_m.action="sMember.php";
    		document.frm_m.submit();
    	}

    	function enterkey() {
            if (window.event.keyCode == 13) {
        
                 // 엔터키가 눌렸을 때 실행할 내용
                 search_member();
            }
        }
    	
    	function search_member() {
    		var id = $('[name=baId]').val();
    		
    		$.ajax({
    			url: 'https://hydra.unicity.net/v5a/customers?unicity='+id+'&expand=customer',
    			headers:{
    				'Content-Type':'application/json'
    			},
    			type: 'GET',
    			success: function(result) {
    				console.log(result.items[0].href);
    				if(typeof(result) != 'undefined' && typeof(result.items) != 'undefined' && result.items.length > 0) {
    					var _oname = '';
    					if(typeof(result.items[0].humanName['fullName@ko']) != 'undefined') {
    						_oname = result.items[0].humanName['fullName@ko'];
    					}
    					if(_oname == '') {
    						_oname = result.items[0].humanName.fullName;
    					}
    					$('[name=baName]').val(_oname);
    				}else{
    				}		
    				
    			}, error: function() {
    				alert('검색된 회원이 없습니다.');
    			}
    		});
    	}

    	function insertInfo(){
    		if(frm_m.startDate.value == '' || frm_m.startDate.value == null ){
    			alert("일시정지(S)를 입력 하세요");
    			frm_m.startDate.focus();
    			return false;
    		}else if (frm_m.baId.value == '' || frm_m.baId.value == null){
    			alert("회원번호를 입력 하세요");
    			frm_m.baId.focus();
    			return false;
    		}else if (frm_m.endDate.value == '' || frm_m.endDate.value == null){
    			alert("회원쉽전환 알람일자 입력 하세요");
    			frm_m.endDate.focus();
    			return false;
    		}else if (frm_m.autoshipYn.value == '' || frm_m.autoshipYn.value == null){
    			alert("오토쉽유무를 입력 하세요");
    			frm_m.autoshipYn.focus();
    			return false;
    		}else if (frm_m.reg_status.value == '' || frm_m.reg_status.value == null){
    			alert("처리상태를 입력 하세요");
    			frm_m.reg_status.focus();
    			return false;
    		}else if (frm_m.note.value == '' || frm_m.note.value == null){
    			alert("일시정지(S) 사유를 입력 하세요");
    			frm_m.note.focus();
    			return false;
    		}

    		frm_m.target = "frmain";
			document.frm_m.action="sMember_update.php";
			document.frm_m.submit();
        }	


		$( function() {
    		$( "#startDate" ).datepicker({dateFormat:"yymmdd",
										  dayNamesMin : ['일','월','화','수','목','금','토']
										});
			$( "#endDate" ).datepicker({dateFormat:"yymmdd",
										dayNamesMin : ['일','월','화','수','목','금','토']
										});
			
  		} );

    	
    </script>

	<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>
</html>	
	