<?php include "./includes/config/common_functions.php";?>
<?php
	  include "./includes/config/nc_config.php";
	  include "./includes/config/config.php";
	  cert_validation();
	  session_start();
	
	  $distID = $_POST['baId'];
	  $fName = $_POST['fName'];
	  $flag = $_POST['flag'];
	  

?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>인터넷 판매 제보</title>
		<meta name="description" content="" />
		<meta http-equiv="Content-Script-Type" content="text/javascript">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
		<link rel="stylesheet" type="text/css" href="./css/joo.css" />
		<script type="text/javascript" src="./js/jquery-1.8.0.min.js"></script>
		<script type="text/javascript" src='./js/common.js'></script>
		<script type="text/javascript" src="./js/selectordie.min.js"></script>
		<script>

			$(document).ready(function() {
				var flag = '<?php echo $flag ?>'
					
					if(flag != 'Y'){
						$('#distID').removeAttr("readonly");
						$('#distName').removeAttr("readonly");			
					}	
			});
	

			

			var date = new Date();
			function getTimeStamp() {
			  var s =
				leadingZeros(date.getFullYear(), 4) + '-' +
				leadingZeros(date.getMonth() + 1, 2) + '-' +
				leadingZeros(date.getDate(), 2) + ' ' +
	
				leadingZeros(date.getHours(), 2) + ':' +
				leadingZeros(date.getMinutes(), 2) + ':' +
				leadingZeros(date.getSeconds(), 2);
	
			  return s;
			}
	
			function leadingZeros(n, digits) {
			  var zero = '';
			  n = n.toString();
	
			  if (n.length < digits) {
				for (i = 0; i < digits - n.length; i++)
				  zero += '0';
			  }
			  return zero + n;
			}
		
			var idx ="";
			function agreementButton(){
				
				var formChk = document.form_chk
				var count = jQuery('tr td input').size();
				var dscSelect = $("#dscSelect").val();
				var urlVal = $("#url").val();
				var url1 = $("#url1").val();
				var url2 = $("#url2").val();
				var url3 = $("#url3").val();
				var url4 = $("#url4").val();

				var flag = "<?php echo $flag?>";
				
				var distId = $("#distID").val();
				var distName = $("#distName").val();

				if(distId == null || distId == ""){
					alert("회원번호를 입력해 주세요");
					return false;
				}else if(distName == null || distName == ""){
					alert("회원성명을 입력해 주세요");
					return false;
				}else if(urlVal == null || urlVal == ""){
					alert("url 한개는 필수입니다.");
					return false;
				}	
				var urlLength =urlVal.substring( 8, 11 );
				

				if(urlLength == 0){
					alert("url 한개는 필수입니다.");
					return false;
				}

				if(flag != 'Y'){
					if(dscSelect == null || dscSelect == ""){
						alert("DSC를 선택해 주세요");
						return false;
					}
				}	

				if(urlVal != undefined ||urlVal != null ){	
					formChk.urlInfo0.value = urlVal
				}else{
					formChk.urlInfo0.value = "";
				}

				if(url1 != undefined ){	
					formChk.urlInfo1.value = url1
				}else{
					formChk.urlInfo1.value = "";
				}

				if(url2 != undefined ){
					formChk.urlInfo2.value = url2
				}else {
					formChk.urlInfo2.value = "";
				}

				if(url3 != undefined ){
					formChk.urlInfo3.value = url3
				}else {
					formChk.urlInfo3.value = "";
				}

				if(url4 != undefined ){
					formChk.urlInfo4.value = url4
				}else {
					formChk.urlInfo4.value = "";
				}
			
				//formChk.dscSelect.value = dscSelect;
				//window.open('', 'popupChk', 'width=100%, height=100%, top=100, left=100, fullscreen=no, resizable=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
				formChk.applyDate.value = getTimeStamp();
				//formChk.target = "popupChk";
				formChk.action = "insertData.php";
				formChk.submit();	
				
			}	
			
			function add(){
				
				var idx = jQuery('tr td input').size();
				if(idx <= 4){ 			        
			        var addStaffText = '<tr name="trStaff">'+
			        	'    <td>'+
			        	'    <h2 style="margin-top: 9px;">URL 입력</h2>'	+
			        	'    </td>'+
			            '    <td>'+
			            '        <input type="text" class="form-control" name="url" id="url'+idx+'"+ placeholder="url" value="https://" style="width: 80%">'+
			            '        <button class="btn btn-default" name="delStaff" style="margin-top: 9px;" >삭제</button>'+
			            '    </td>'+
			            '</tr>';
			            
			        var trHtml = $( "tr[name=trStaff]:last" ); 
    
			        trHtml.after(addStaffText); 
		        
				    //삭제 버튼
				    $(document).on("click","button[name=delStaff]",function(){
				        var trHtml = $(this).parent().parent();
				        trHtml.remove(); //tr 테그 삭제
				    });
				}else{
					alert("URL추가 입력은 5개까지만 가능 합니다")
					return false;
				}
			}
		</script>
	</head>
	<body>
		<div class="wrapper" >
			<div class="main_wrapper">
				<div class="figure">
					<img src="./images/mainlogo.png" alt="유니시티 로고" />
				</div>
				<div class="main_box">
					<div class="main_inner_box">
						<div class="main_top">
							<h1>
								<span>인터넷 판매 제보</span>
							</h1>
						</div>
						<div class="wrap_input">
							<div id="applyBox">
								<form name="form_chk" method="post" >
									<input type="hidden" name="urlInfo0">
									<input type="hidden" name="urlInfo1">
				                    <input type="hidden" name="urlInfo2">
				                    <input type="hidden" name="urlInfo3">
				                    <input type="hidden" name="urlInfo4">
				                    <input type="hidden" name="applyDate">
				                    <input type="hidden" name="reg_status" value="2"/>
									<div class="member">
										<h2 style="float: left; margin-top: 9px; width: 73px;">제보자<br/> 회원번호&nbsp;</h2>
										<div class="wrap">
											<input type="text" placeholder="제보자 회원번호" name="distID" id="distID" value="<?php echo $distID ?>" readonly="readonly" style="width: 67%" />
										</div>
										<h2 style="float: left; margin-top: 9px; width: 73px;">제보자<br/> 성명&nbsp;</h2>
										<div class="wrap">
											<input type="text" placeholder="제보자 성명" name="distName" id="distName" value="<?php echo $fName ?>" readonly="readonly" style="width: 67%" />
										</div>
										<div class="wrap">
											<table border="0" style="width: 100%">
									            <tr name="trStaff">
									            	<td width="70px">
									            		<h2 style="margin-top: 9px;">URL 입력</h2>
									            	</td>
									                <td id="box">
									                    <input type="text" name="url" id="url" placeholder="url" value="https://" style="width: 80%">
									                    
									                </td>
									            </tr>     
									        </table>
								        </div>
								        <?php if($flag !='Y') { ?>
									        <div class="wrap">
									        	<h2 style="float: left; margin-top: 9px; width: 73px;">DSC<br/> 대리 접수</h2>
									        		<select name="dscSelect" id="dscSelect" title="DSC 선택" style="width:130px; height: 40px;">
									        			<option selected="selected" value="">선택</option>
									        			<option value="0">서울DSC</option>
									        			<option value="1">인천DSC</option>
									        			<option value="2">안산DSC</option>
									        			<option value="3">대전DSC</option>
									        			<option value="4">광주DSC</option>
									        			<option value="5">원주DSC</option>
									        			<option value="6">대구DSC</option>
									        			<option value="7">부산DSC</option>
			
									        		</select>	
									        </div>
								        <?php }?>
							       	</div> 
						        </form>
				    			<button name="addStaff" onclick="add()">URL 입력 추가</button>
				    		</div>
				    		<div align="center" id="agreementBut" style="background-color: #B2CCFF;width: 100px;margin-left: 40%; border-radius : 5px; text-shadow: 0px -1px 1px rgba(0,0,0,.3); border: 1px solid #4081AF;box-shadow : inset 0 1px 0 rgba(255,255,255,.3), inset 0 0 2px rgba(255,255,255,.3) 0 1px 2px rgba(0,0,0,.29); cursor: pointer;">
								<a href="javascript:agreementButton()"><b>제보하기</b></a>
							</div>
				    	</div>
				    </div>
			    </div>
		    </div>
		</div>
	</body>
</html>