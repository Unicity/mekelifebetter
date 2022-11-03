function showMask()
{  
	$('body').prepend('<div id ="popup_mask_layer"></div>');
	$("#popup_mask_layer").css("display","block"); 
	$("#popupDiv").css("display","block");
	$("body").css("overflow","hidden"); //스크롤바 제거
}
function hideMask()
{	
	$("#popup_mask_layer").remove(); 
	$("#popupDiv").css("display","none");
	$("body").css("overflow","auto");//스크롤바 복원
}

function lengthValidator(value, length)
{
	var result = false;
	if (value == "" || value.length < length) {
		result = true;
	}
	return result;
}

//후원자조회
function searchFO() {
	var distNo = document.getElementById("sponsorNo").value;

	if (distNo == '' || distNo == null || isNaN(parseInt(distNo))) {
		alert('회원번호를 입력하세요.');
		document.getElementById("sponsorNo").focus();
		return ;
	}
	if (distNo < 100000) {
		alert('회원번호가 올바르지 않습니다.');
		document.getElementById("sponsorName").value ="";
		document.getElementById("sponsorNo").focus();
		return ;	
	}

	$('#popTxt').html('확인중입니다.<br>다소 시간이 걸릴 수 있으니<br>잠시만 기다려 주세요');
	showMask();

	$.ajax({
		type: 'post',
		url: 'getFOname.php',
		data: {'q': distNo},			
		success: function(msg){
			if(msg == 'none'){
				alert('후원자번호가 없습니다');
				document.getElementById("sponsorNo").focus();
			}else if(msg == '200' || msg == '404'){
				alert('후원자를 조회할 수 없습니다');
				document.getElementById("sponsorNo").focus();
			}else if(msg == 'none2'){
				alert('통신장애입니다. 잠시후 확인하여 주세요');
				document.getElementById("sponsorNo").focus();
			}else{
				$('#sponsorNo').attr('readonly', true);
				$('#btnSchFO').hide();
				$('#btnResetFO').show();

				document.getElementById("sponsorName").value = 	msg;
			}					
			console.log(msg);
			hideMask();
		},
		error: function( jqXHR, textStatus, errorThrown ) { 
			//alert( textStatus + ", " + errorThrown ); 
			alert('잠시후 다시 확인해 주십시오');   
			hideMask();
		} 
	});
}

function resetFO(){
	$('#sponsorNo').val('');	
	$('#sponsorNo').removeAttr('readonly');
	$('#sponsorName').val('');
	$('#btnSchFO').show();
	$('#btnResetFO').hide();
	$('#sponsorNo').focus();
}

function submitForm()
{
	event.preventDefault();

	var myValue = document.applicationForm;
	var memberType = myValue.memberType.value;

	var n_RegExp = /^[가-힣]{2,15}$/;

	myValue.koreanName.value = myValue.koreanName.value.replace(/ /g,"");

	if (myValue.koreanName.value.length < 2) {
		alert("한글 성명을 입력해주세요(실명을 입력해 주세요)");
		myValue.koreanName.focus();
		return;
	}
	
	if(!n_RegExp.test(myValue.koreanName.value)){
        alert("한글만 입력가능합니다(특수문자,영어,숫자,공백 불가)");
		myValue.koreanName.focus();
	   	return;
    }	

	if (lengthValidator(myValue.englishName.value, 5)) {
		alert("영문성명을 정확하게 입력하세요.");
		myValue.englishName.focus();
		return;
	} 
	
	if (checkAlphabet(myValue.englishName.value)) {
		alert("영문성명을 정확하게 입력하세요.");
		myValue.englishName.focus();
		return;
	}
	
	if (lengthValidator(myValue.mobileNo2.value, 3)) {
		alert("휴대폰 번호를 정확하게 입력하세요.");
		myValue.mobileNo2.focus();
		return;
	}

	if (isNaN(myValue.mobileNo2.value)) {
		alert("휴대폰 번호를 정확하게 입력하세요.");
		myValue.mobileNo2.focus();
		return;
	}

	if (lengthValidator(myValue.mobileNo3.value, 3)) {
		alert("휴대폰 번호를 정확하게 입력하세요.");
		myValue.mobileNo3.focus();
		return;
	}

	if (isNaN(myValue.mobileNo3.value)) {
		alert("휴대폰 번호를 정확하게 입력하세요.");
		myValue.mobileNo3.focus();
		return;
	}

	
	if (lengthValidator(myValue.email1.value, 2)) {
		alert("메일주소를 정확히 입력하세요(1).");
		myValue.email1.focus();
		return;
	}

	if (lengthValidator(myValue.email2.value, 2)) {
		alert("메일주소를 정확히 입력하세요(2).");
		myValue.email12.focus();
		return;
	}

	//var email = myValue.email1.value+"@"+myValue.email2.value+myValue.email3.value;	
	var email = myValue.email1.value+"@"+myValue.email2.value;	
	if (checkEmailFormat(email)) {
		alert("메일주소를 정확히 입력하세요(3).");
		myValue.email1.focus();
		return;
	}
	
	if (lengthValidator(myValue.zip.value, 5)) {
		alert("주소를 입력하여 주세요.");
		myValue.zip.focus();
		return;
	}
	
	if (lengthValidator(myValue.fulladdress.value, 10)) {
		alert("주소를 정확하게 입력하세요.");
		myValue.fulladdress.focus();
		return;
	}

	if (lengthValidator(myValue.detailaddress.value, 2)) {
		alert("주소를 정확하게 입력하세요.");
		myValue.detailaddress.focus();
		return;
	}

	if(myValue.detailaddress.value.replace(/\s/gi,"") == ""){
		alert("상세주소를 입력해 주세요");
		myValue.detailaddress.value = myValue.detailaddress.value.replace(/\s/gi,"");
		myValue.detailaddress.focus();
		return;
	}

	re=/[^가-힣a-zA-Z0-9\-\s]/gi;
	myValue.detailaddress.value= myValue.detailaddress.value.replace(re,"");

	if(myValue.detailaddress.value == ""){
		alert("상세주소를 입력해 주세요\ㅜ(한글, 영문, 숫자, 하이픈(-)만 입력 가능합니다.");
		myValue.detailaddress.focus();
		return;
	}
	document.getElementById('address2').value = document.getElementById('detailaddress').value;

	if (memberType == 'D') {
		if (lengthValidator(myValue.bankcode.value, 3)) {
			alert("은행명을 정확히 선택해주세요.");
			myValue.bankcode.focus();
			return;
		}

		if (lengthValidator(myValue.accountNo.value, 8)) {
			alert("계좌번호를 정확하게 입력하세요.");
			myValue.accountNo.focus();
			return;
		}

		if (isNaN(myValue.accountNo.value)) {
			alert("계좌번호를 정확하게 입력하세요.");
			myValue.accountNo.focus();
			return;
		}
	}

	if (lengthValidator(myValue.sponsorNo.value, 4)) {
		alert("후원자 번호를 정확히 입력하세요.");
		myValue.sponsorNo.focus();
		return;
	}

	if (lengthValidator(myValue.sponsorName.value, 1)) {
		alert("후원자 조회를 하여 주세요.");
		myValue.sponsorNo.focus();
		return;
	}	
	
	if (lengthValidator(myValue.password.value, 6)) {
		alert("비밀번호를 정확하게 입력하세요.");
		myValue.password.focus();
		return;
	}

	if(checkSpecialCharacter(myValue.password.value)) {
		alert("비밀번호는 영문과 숫자만 가능합니다.");
		myValue.password.focus();
		return;
	}

	$(".required").each(function(){		

		if($(this).is(":checked") === false){	

			if($(this).hasClass('marketing')){
				if(!$("#ch_10").is(":checked") && !$("#ch_11").is(":checked") && !$("#ch_12").is(":checked")){
					alert("통지 방식을 1개 이상 선택해 주세요");
					window.scrollTo({top: $(this).offset().top - 50, behavior: 'smooth'});
					err++;
					return false;					
				}
			}else{
				var str = $(this).parent().text().trim();
				if(str == "확인함") alert("필수동의사항을 확인해 주세요");
				else alert("필수동의 : "+str);
				window.scrollTo({top: $(this).offset().top - 50, behavior: 'smooth'});
				err++;
				return false;
			}
		}
	});

	$('#popTxt').html('처리중입니다.<br>다소 시간이 걸릴 수 있으니<br>잠시만 기다려 주세요');
	//showMask();

	if(confirm('입력하신 정보로 가입하시겠습니까?')){
		$('.submitRegisterBtn').hide();
		myValue.target = 'ifrHidden';
		myValue.action = 'registerSave.php';
		myValue.submit();
	}
	/*
	$.ajax({
		type: 'post',
		url: 'registerSave.php',
		data: $('#frm').serialize(),
		async: false,
		success: function(msg){
			console.log(msg);
			hideMask();	
		},
		error: function( jqXHR, textStatus, errorThrown ) { 
			alert('통신장애입니다. 잠시후 다시 아용하여 주세요');   
			hideMask();
			//alert( textStatus + ", " + errorThrown ); 
		} 
	});
	*/
}




function getCheckBoxValue(boxname){
	var result = '';
	var thecheckbox = document.getElementsByName(boxname);
	for(var i=0; i< thecheckbox.length; i++) {
		if (thecheckbox[i].checked) {
			result = thecheckbox[i].value;
		}
	}
	return result;

}
function getCheckedBoxCounter(){

	var total =  $('input[type="checkbox"]:checked').length;
	 
	if(document.getElementById("allterm").checked == true) {
		total -= 1;
	}
	return total;
}
function getCheckedBoxCounter2(){

	var total =  $('input[type="checkbox"]:checked').length;
	 
	return total;
}
function getRadioCounter(){

	var total =  $('input[type="radio"]:checked').length;
	 
	return total;
}
function checkSpecialCharacter(string) { 
	//var stringRegx=/^[0-9a-zA-Z가-힝]*$/; 
	var stringRegx = /[~!@\#$%<>^&*\()\-=+_\’]/gi; 
	var isValid = false; 
	if(stringRegx.test(string)) { 
		isValid = true; 
	} 
	return isValid; 
}
function checkAlphabet(string) { 
	var stringRegx=/^[a-zA-Z ]*$/; 
	//var stringRegx = /[~!@\#$%<>^&*\()\-=+_\’]/gi; 
	var isValid = true; 
	if(stringRegx.test(string)) { 
		isValid = false; 
	} 
	return isValid; 
}
function checkEmailFormat(string) {
	 
	var stringRegx = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	var isValid = true; 
	if(stringRegx.test(string)) { 
		isValid = false; 
	} 
	return isValid;
}