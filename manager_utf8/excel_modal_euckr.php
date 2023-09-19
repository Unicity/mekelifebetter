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
	<div class="popupBox">
		<div class="closeBtn" onclick="closeExPopup()">x</div>

		<div class="popup-title">������������ �ٿ�ε�</div>
		<div class="popup-cont"><!--popup-cont-->
			<ul>
				<li>���������� ������ ȸ����ġ ����[����������ȣ����ȸ��� ��2020-2ȣ ���� 2020. 8. 11.]�� 8��(���ӱ���� ���� �� ����) 2�׿� ���� ���������� �ٿ�ε� ������ ��ü������ �Է��Ͽ� �ֽñ� �ٶ��ϴ�.</li>
				<li>�������� ���� �ٿ�ε�� �αװ� ��ϵ˴ϴ�.</li>
				<li>�ʿ��� ������ �ּ������� ���</li>
				<li>�������� �Ϸ�� ������ �ݵ�� ����</li>
			</ul>
			
			<table>
				<tr>
					<th><label>��������������</label></th>
					<td>
						<select name="ex_type" id="ex_type" style="width:100%; padding:2px; height:25px;">
							<option value="������">������</option>
							<option value="�ڷẸ��">�ڷẸ��</option>
							<option value="�ڷ�����">�ڷ�����</option>
							<option value="��Ÿ">��Ÿ</option>
						</select>					
					</td>
				</tr>
				<tr>
					<th><label>�󼼳���</label></th>
					<td><textarea name="ex_task" id="ex_task" rows="3" maxlength="1000"></textarea></td>
				</tr>				
			</table>
			
			<div class="popup-btnBox">
				<button type="button" onclick="inExcelLog()" class="btn">�ٿ�ε�</button>&nbsp;&nbsp;
				<button type="button" onclick="closeExPopup()" class="btn-02">���</button>
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
		alert("��ΰ� �������� �ʾҽ��ϴ�. �����ڿ��� �����Ͽ� �ּ���");
	} else if($("#ex_task").val().replace(/ /gi, "") == ""){
		alert("�󼼳����� ������ �ּ���");
		$("#ex_task").focus();
	} else{
		
		//var edata = $("#frmExDown").serialize();
		//console.log(edata);
		var ex_cate = encodeURIComponent($("#ex_cate").val());
		var ex_page = encodeURIComponent($("#ex_page").val());
		var ex_detail = encodeURIComponent($("#ex_detail").val());
		var ex_type = encodeURIComponent($("#ex_type").val());
		var ex_task = encodeURIComponent($("#ex_task").val());
		

		$.ajax({
			type: "POST",
			url: "/manager_utf8/excel_log_insert.php",
			//data: edata,
			data: {ex_cate:ex_cate, ex_page:ex_page, ex_detail:ex_detail, ex_type:ex_type, ex_task:ex_task},
			success:function(data) {
				if(data == "OK"){					
					closeExPopup();					
					excelDown();
				}else{
					alert("ó���� ���� �ʾҽ��ϴ�.");
				}
				console.log(data);
			},
			error: function() {
				alert("������ �߻��Ͽ����ϴ�");
				closeExPopup();
			}
		});			
	}
}


function goExcelHistory(cate1, cate2, txt){	
	$("#ex_cate").val(cate1);
	$("#ex_page").val(cate2);
	$("#ex_detail").val(txt);	
	var retVal = confirm("EXCEL �ٿ�ε� �����ðڽ��ϱ�?\n�����Ͱ� ���� ��� �ټ� �ð��� �ҿ�� �� �ֽ��ϴ�.");
	if (retVal){
		openExPopup(); 
	}
}
</script>