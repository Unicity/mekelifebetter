<style type="text/css">
		.ov-bg{display: none; position: fixed; z-index:999999; width: 100%; height: 100%; background: rgba(0,0,0,0.2); top: 0; left: 0;}

		#forMaskModalContent{
			display:none;
			position: absolute; top: 50%;left: 50%; margin-left: -300px; margin-top: -200px;
			display:block; z-index:999999; width:600px; text-align:center}
		#forMaskModalContent .popupBox{position:relative;width:600px; background-color:#fff; border: 2px solid #333; box-shadow: 3px 3px 5px rgba(0,0,0,0.2)}
		#forMaskModalContent .popupBox .closeBtn{position:absolute;width:16px; height:16px;top:-7px; right:-7px; background-color:#000; border:1px solid #fff; cursor:pointer; color: #fff;; line-height: 16px}
		#forMaskModalContent .popupBox .popup-title{background: #035fa7; font-size: 16px; color:#fff; height:35px; padding-top:7px;}
		#forMaskModalContent .popupBox .popup-cont{padding:10px; font-size:16px; font-size:16px; line-height:30px}

		#forMaskModalContent .popupBox .popup-cont li{text-align: left; font-size:14px; padding-left: 1em; position: relative; line-height: 1.3; margin-bottom: 0.5em;}
		#forMaskModalContent .popupBox .popup-cont li:before{content: '*'; width: 1em; height: 1em; top: :0; left: 0; position: absolute;}
		#forMaskModalContent .popupBox .popup-cont table{width:100%; margin-bottom:0.5em;}
		#forMaskModalContent .popupBox .popup-cont table th, 
		#forMaskModalContent .popupBox .popup-cont table td{border: 1px solid #ddd; padding: 5px;}
		#forMaskModalContent .popupBox .popup-cont table th{background: #f7f7f7;}

		#forMaskModalContent .popupBox .popup-cont table td input:not([type="radio"]),
		#forMaskModalContent .popupBox .popup-cont table td input:not([type="checkbox"]),
		#forMaskModalContent .popupBox .popup-cont table td textarea,
		#forMaskModalContent .popupBox .popup-cont table td select{padding: 5px; width:95%; border: 1px solid #ccc}
		
		#forMaskModalContent .popupBox .popup-cont .popup-btnBox{text-align: center;}
		#forMaskModalContent .popupBox .popup-cont .popup-btnBox .btn{display: inline-block; font-size:16px; color:#fff; width: 140px; padding:5px 10px; background: #035fa7; text-align: center;}
		#forMaskModalContent .popupBox .popup-cont .popup-btnBox .btn-02{display: inline-block;  font-size:16px; color:#fff; width: 140px; padding:5px 10px; background: #4e4e4e; text-align: center;}
</style>
<div class="ov-bg"></div>
<div id="forMaskModalContent" style="display:none"> <!--forMaskModalContent-->
	<form name="frmExDown" id="frmExDown" method="POST">
	<input type="hidden" name="ex_cate" id="ex_cate" value="">
	<input type="hidden" name="ex_page" id="ex_page" value="">
	<input type="hidden" name="ex_detail" id="ex_detail" value="">
	<input type="hidden" name="ex_kind" id="ex_kind" value="">
	<div class="popupBox">
		<div class="closeBtn" onclick="closeExPopup()">x</div>

		<div class="popup-title">개인정보파일 다운로드</div>
		<div class="popup-cont"><!--popup-cont-->
			<ul>
				<li>개인정보의 안정성 회보조치 기준[개인정보보호위원회고시 제2020-2호 시행 2020. 8. 11.]제 8조(접속기록의 보관 및 점검) 2항에 따라 개인정보의 다운로드 사유를 구체적으로 입력하여 주시기 바랍니다.</li>
				<li>개인정보 파일 다운로드시 로그가 기록됩니다.</li>
				<li>필요한 정보는 최소한으로 사용</li>
				<li>사용목적이 완료된 파일은 반드시 삭제</li>
			</ul>
			
			<table>
				<tr>
					<th><label>개인정보사용목적</label></th>
					<td>
						<select name="ex_type" id="ex_type" style="width:100%; padding:2px; height:25px;">
							<option value="업무용">업무용</option>
							<option value="자료보관">자료보관</option>
							<option value="자료제출">자료제출</option>
							<option value="기타">기타</option>
						</select>					
					</td>
				</tr>
				<tr>
					<th><label>상세내용</label></th>
					<td><textarea name="ex_task" id="ex_task" rows="3" maxlength="1000"></textarea></td>
				</tr>				
			</table>
			
			<div class="popup-btnBox">
				<button type="button" onclick="inExcelLog()" class="btn">다운로드</button>&nbsp;&nbsp;
				<button type="button" onclick="closeExPopup()" class="btn-02">취소</button>
			</div>
		</div><!--//popup-cont-->
	</div>
	</form>
</div><!--//forMaskModalContent-->

<script>

function openExPopup()
{
    var bg = document.querySelector('.ov-bg');
    var popup = document.querySelector('#forMaskModalContent');
    
    bg.style.display = 'block';
    popup.style.display = 'block';
	$("#ex_task").val('');
	$("#ex_task").focus();
}
    
function closeExPopup()
{
    var bg = document.querySelector('.ov-bg');
    var popup = document.querySelector('#forMaskModalContent');
    
    bg.style.display = 'none';
    popup.style.display = 'none';
}

function inExcelLog(){
	if($("#ex_page").val() == ""){
		alert("경로가 지정되지 않았습니다. 관리자에게 문의하여 주세요");
	} else if($("#ex_task").val().replace(/ /gi, "") == ""){
		alert("상세내용을 기입해 주세요");
		$("#ex_task").focus();
	} else{
		
		var kind = $("#ex_kind").val();
		var edata = $("#frmExDown").serialize();
		//console.log(edata);

		$.ajax({
			type: "POST",
			url: "/manager_utf8/excel_log_insert.php",
			data: edata,
			success:function(data) {
				if(data == "OK"){					
					closeExPopup();					
					excelDown(kind);
				}else{
					alert("처리가 되지 않았습니다.");
				}
				console.log(data);
			},
			error: function() {
				alert("에러가 발생하였습니다");
				closeExPopup();
			}
		});			
	}
}


function goExcelHistory(cate1, cate2, txt,  kind=''){	
	console.log(cate1 +', '+ cate2 +', '+ txt +', '+ kind);
	$("#ex_cate").val(cate1);
	$("#ex_page").val(cate2);
	$("#ex_detail").val(txt);
	$("#ex_kind").val(kind);

	if(cate1 == '회원수당조회'){

		openExPopup(); 

	}else{
		var retVal = confirm("EXCEL 다운로드 받으시겠습니까?\n데이터가 많은 경우 다소 시간이 소요될 수 있습니다.");
		if (retVal){
			openExPopup(); 
		}
	}
}
</script>