
	$(document).ready(function() {

		$("#distID").keypress(function( event ) {
			if ( event.which == 13 ) {
				js_search();
			}
		});

		$("#ssn2").keypress(function( event ) {
			if ( event.which == 13 ) {
				js_register();
			}
		});

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
	
	

	function js_search() {
		
		var distID = $("#distID").val().trim();

		if (distID == "") {

			$("#errortxt").removeClass("txt_blue");
			$("#errortxt").addClass("txt_red");
			$("#errortxt").html("<span>회원번호를 입력해 주세요.</span>");
			//alert("회원번호를 입력해 주세요.");
			$("#distID").focus();
			return;
		}

		var request = $.ajax({
			url:"api.php",
			type:"POST",
			data:{distID:distID},
			dataType:"json"
		});
		
		request.done(function(msg) {

			if (msg.items[0].humanName['fullName'].trim() == "false" || msg.items[0].humanName['fullName'] == "Korea Unicity" ) {
				
				$("#errortxt").removeClass("txt_blue");
				$("#errortxt").addClass("txt_red");
				$("#errortxt").html("<span>입력하신 회원정보를 찾을 수 없습니다. 회원번호를 다시 확인해 주십시오.</span>");
				$("#memberInfo").addClass("contents");

			} else {

				$("#errortxt").removeClass("txt_red");
				$("#errortxt").html("");
				$("#memberInfo").removeClass("contents");

				var dob = msg.items[0]['birthDate'] ;
				var dob1 = dob.substring(0,4)+"-**-**";
				 	
				$("#distName").html(msg.items[0].humanName['fullName'] );
				$("#distDob").html(dob1);
				$("input[name=id]").val(distID);
				$("input[name=fullname]").val(msg.items[0].humanName['fullName']);
				$("input[name=dob]").val(dob);
				$("input[name=myfile]").val("");
				$("input[name=myfile1]").val("");
				$("input[name=distMobilephone]").val("");
				//$("#distEmail").html( msg.items[0]['email'] );
				//$("#distMobilephone").html( msg.items[0]['mobilePhone'] );
				console.log(msg.items[0]);
			}

		});
	}

	function CheckJuminForm(ssn1, ssn2) {

		if (ssn1 === "" || ssn2 === "" || ssn1.length != 6 || ssn2.length != 7) {
			return false;
		} else {
			var ssn = ssn1.toString()+ssn2.toString();
			var multiplyer = [2,3,4,5,6,7,8,9,2,3,4,5];
			var arrSSN = [];
			
			for (i = 0 ; i < ssn.length ;i++) {
				arrSSN.push(ssn.charAt(i));
			}

			var sum = 0;
			for(var i=0; i<12; i++) {
				sum += multiplyer[i]*=arrSSN[i];
			}
			//console.log(sum);
			
			var value = (11-(sum%11))%10;
					
			if (arrSSN[6] == 5 || arrSSN[6] == 6 || arrSSN[6] == 7 || arrSSN[6] == 8) // for Foreigners, need extra calculation
			{
				if (value >= 10)
					value -= 10;
				
				value += 2;

				if (value >= 10)
					value -= 10;
			} 

			return (value == arrSSN[12]); 
		}
	}

	function isValidFormat(input,format) {
		if (input.value.search(format) != -1) {
			return true; //올바른 포맷 형식
		}
		return false;
	}

	function containsCharsOnly(input,chars) {
		for (var inx = 0; inx < input.value.length; inx++) {
			if (chars.indexOf(input.value.charAt(inx)) == -1)
				return false;
		}
		return true;	
	}

	function isNumber(input) {
		var chars = "0123456789";
		return containsCharsOnly(input,chars);
	}

	function js_register() {
		var frmValue = document.registerForm;
		var check_flag_1 = document.getElementById("chk1").checked;
		var check_flag_2 = document.getElementById("chk2").checked;

		var applyD= getTimeStamp();
		var distID = $("input[name=id]").val();
		var distName = $("input[name=fullname]").val();
		var distDob = $("input[name=dob]").val();
		var applyDate = $("input[name=applyDate]").val(applyD);
		var myfile = $("input[name=myfile]").val();
		var myfile1 = $("input[name=myfile1]").val();
		var distMobilephone = $("input[name=distMobilephone]").val();

		if (distID == "") {
			$("#errortxt").removeClass("txt_blue");
			$("#errortxt").addClass("txt_red");
			$("#errortxt").html("<span>회원번호를 입력해 주세요.</span>");
			//alert("회원번호를 입력해 주세요.");
			$("#distID").focus();
			return;
		}
		
		if (distName == "") {
			$("#errortxt").addClass("txt_red");
			$("#errortxt").html("<span>회원명을 찾지 못했습니다.</span>");
			 
			return;
		}

		$("#errortxt").removeClass("txt_red");
		$("#errortxt").html("<span></span>");
		
		if(distMobilephone == null || distMobilephone == "" ){
			alert("휴대폰 번호를 입력해주세요");
			return false;	
		}
		
		if(myfile == null || myfile == ""){
			alert("외국인 등록증 앞면을 업로드 해주세요.")
			return false;	
			
		}
		
		if(myfile1 == null || myfile1 == ""){
			alert("외국인 등록증 뒷면을 업로드 해주세요.")
			return false;	
			
		}
		
		
		if(check_flag_1 == false ||check_flag_1 == null ||check_flag_2 == true){
			alert("개인정보 동의에 동의 해야합니다.")
			return false;
		}
			

		$("#registerForm").submit();

		/*
		var request = $.ajax({
			url:"ssn.php",
			type:"POST",
			data:{distID:distID, ssn1:ssn1, ssn2:ssn2},
			dataType:"html"
		});

		request.done(function(msg) {

			if (msg.trim() == "T") {
				alert('주민번호 입력이 완료 되었습니다.');
				//document.location = "http://www.makelifebetter.co.kr";
				document.location = "https://www.makelifebetter.co.kr/ssnPage/ssnReceiver.php";
			} else {
				alert("잠시 후 다시 시도해 주시기 바랍니다.");
				document.location = "https://www.makelifebetter.co.kr/ssnPage/ssnReceiver.php";
			}

		});

		request.fail(function(jqXHR, textStatus) {
			alert("Request failed : " +textStatus);
			return false;
		});
		*/
	}

 	$(document).on("change", "#distID", function() {
		$("#chkID").val("false");
	});

	String.prototype.trim = function() {
		return this.replace(/^\s+|\s+$/g,"");   
	}