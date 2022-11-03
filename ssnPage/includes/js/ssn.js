
	$(document).ready(function() {

		$("#distID").keypress(function( event ) {
			if ( event.which == 13 ) {
				js_search();
			}
		});

		$("#ssn2").keypress(function( event ) {
			if ( event.which == 13 ) {
				//js_register();
			}
		});

	});

	function js_search() {
		
		var distID = $("#distID").val().trim();

		if (distID == "") {

			$("#distName").removeClass("txt_blue");
			$("#distName").addClass("txt_red");
			$("#distName").html("<span>회원번호를 입력해 주세요.</span>");
			//alert("회원번호를 입력해 주세요.");
			$("#distID").focus();
			return;
		}


		$.ajax({
			type: 'GET',
			url:"https://hydra.unicity.net/v5a/customers?unicity="+distID+"&expand=customer",			
			headers:{
				'Content-Type':'application/json'
			},
			success: function(msg){
				console.log(msg);
				if (msg.items[0].humanName['fullName@ko'].trim() == "false") {
				
					$("#distName").removeClass("txt_blue");
					$("#distName").addClass("txt_red");
					$("#distName").html("<span>입력하신 회원정보를 찾을 수 없습니다. 회원번호를 다시 확인해 주십시오.</span>");

				} else {
					var humName = maskingName(msg.items[0].humanName['fullName@ko']);

					$("#distName").removeClass("txt_red");
					$("#distName").addClass("txt_blue");
					$("#distName").html("<span>"+humName+"</span>");
					$("#distName1").val(humName);
					$("#ssn1").focus();
					$("#chkID").val("true");

				}
		
			},
			error: function( msg,status,error ) { 
				
				//console.log(JSON.parse(msg.responseText).error.error_code);
				if(JSON.parse(msg.responseText).error.error_code=='2006'){
					alert('회원정보를 다시 확인 해주세요');
					return false;
				}else{
					alert('통신장애입니다. 잠시후 이용하여 주세요.'); 
					return false;
				}
				//alert( textStatus + ", " + errorThrown ); 
			} 
		});


		/*
		var request = $.ajax({
			url:"/ssnPage/api.php",
			type:"POST",
			data:{distID:distID},
			dataType:"json"
		});
		request.done(function(msg) {

			if (msg.items[0].humanName['fullName@ko'].trim() == "false") {
				
				$("#distName").removeClass("txt_blue");
				$("#distName").addClass("txt_red");
				$("#distName").html("<span>입력하신 회원정보를 찾을 수 없습니다. 회원번호를 다시 확인해 주십시오.</span>");

			} else {

				$("#distName").removeClass("txt_red");
				$("#distName").addClass("txt_blue");
				$("#distName").html("<span>"+msg.items[0].humanName['fullName@ko']+"</span>");
				$("#ssn1").focus();
				$("#chkID").val("true");

			}

		});
		*/
	}

	function maskingName(strName) {
		if (strName.length > 2) {
		  var originName = strName.split('');
		  originName.forEach(function(name, i) {
			if (i === 0 || i === originName.length - 1) return;
			originName[i] = '*';
		  });
		  var joinName = originName.join();
		  return joinName.replace(/,/g, '');
		} else {
		  var pattern = /.$/; // 정규식
		  return strName.replace(pattern, '*');
		}
	  };

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
		
		var ssn1 = $("#ssn1").val().trim();
		var ssn2 = $("#ssn2").val().trim();
		var distID = $("#distID").val().trim();

		if (distID == "") {
			$("#distName").removeClass("txt_blue");
			$("#distName").addClass("txt_red");
			$("#distName").html("<span>회원번호를 입력해 주세요.</span>");
			//alert("회원번호를 입력해 주세요.");
			$("#distID").focus();
			return;
		}
		
		if (ssn1 == "") {
			$("#SSNError").addClass("txt_red");
			$("#SSNError").html("<span>주민번호 앞자리를 입력해 주세요.</span>");
			$("#ssn1").focus();
			return;
		}

		if (ssn2 == "") {
			$("#SSNError").addClass("txt_red");
			$("#SSNError").html("<span>주민번호 뒷자리를 입력해 주세요.</span>");
			$("#ssn2").focus();
			return;
		}
		
		if (CheckJuminForm(ssn1, ssn2) == false) {
			$("#SSNError").addClass("txt_red");
			$("#SSNError").html("<span>주민등록번호를 다시 확인해 주십시오.</span>");
			return;
		}
		
		$("#SSNError").removeClass("txt_red");
		$("#SSNError").html("<span></span>");

		$("#chkJumin").val("true");

		//alert($("#chkID").val());

		if ($("#chkID").val() == "false") {

			$("#distName").removeClass("txt_blue");
			$("#distName").addClass("txt_red");
			$("#distName").html("<span>회원번호를 확인해 주세요.</span>");
			//alert("회원번호를 입력해 주세요.");
			$("#distID").focus();

			return;
		}

		$(".btn_register").hide();
		$(".btn_register_ing").show();
	
		var request = $.ajax({
			url:"ssn.php",
			type:"POST",
			data:{distID:distID, ssn1:ssn1, ssn2:ssn2},
			dataType:"html"
		});

		request.done(function(msg) {

			console.log(msg);
			
			if (msg.trim() == "T") {
				if(distID=='209415382'){
					if(confirm("주민번호 입력이 완료 되었습니다. 미지급 신청 화면으로 이동 하시겠습니까?")){
						var bdistId = btoa(distID);
						alert(bdistId);
					
						document.location = "/unclaimedCommission/unclaimedCommission.php?distID="+bdistId+"&flag=A";
					}else{
						document.location = "/ssnPage/ssnReceiver_test.php";
					}
				}else{

					alert('주민번호 입력이 완료 되었습니다.');
					document.location = "/ssnPage/ssnReceiver.php";

				}
				
			} else {
				alert("잠시 후 다시 시도해 주시기 바랍니다.");
				document.location = "/ssnPage/ssnReceiver.php";
			}

		});

		request.fail(function(jqXHR, textStatus) {
			alert("Request failed : " +textStatus);
			document.location = "/ssnPage/ssnReceiver.php";
			return false;
		});

	}

	$(document).on("keyup", "#ssn1", function() {
		if ($("#ssn1").val().trim().length >= 6) {
			$("#ssn1").blur();
			$("#ssn2").focus();
		}
	});

	$(document).on("change", "#distID", function() {
		$("#chkID").val("false");
	});

	$(document).on("change", "#ssn1, #ssn2", function() {
		$("#chkJumin").val("false");
	});

	String.prototype.trim = function() {
		return this.replace(/^\s+|\s+$/g,"");   
	}