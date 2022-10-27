<?php

	header('Content-Type:text/html;charset=UTF-8');
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
	header("Expires: Mon,26 Jul 1997 05:00:00 GMT");
?>

<?php

	include "includes/config/config.php";
	include "./includes/AES.php";
	


	$queryCheck ="select * from tb_glicTravel where (paid !='Y' OR paid is null)and(flagUD !='D' OR flagUD is null) order by No desc ";


    $result = mysql_query($queryCheck,$connect);
    $row = mysql_fetch_array($result);

	$memberNo = $row[member_no];
	$memberName = $row[member_name];
	$member = $row[member];
	$phone = $row[phone];
	$selectDate = $row[select_date];
	$paymentCard = $row[payment_card];
	$cardNumber = $row[card_number];
	$expireDate = $row[expire_date];
	$birthday = $row[birthday];
	$installment = $row[installment];
	$password = $row[password];
	$customersHref = $row[customers_href];
	$No = $row[No];
    $TotalArticle = $row[0];


	if($paymentCard  == 'bc'){
		$paymentCard='BC카드';
	}else if($paymentCard  == 'ss'){
		$paymentCard='삼성카드';
	}else if($paymentCard  == 'sh'){
		$paymentCard='수협카드';
	}else if($paymentCard  == 'jb'){
		$paymentCard='전북카드';
	}else if($paymentCard  == 'kj'){
		$paymentCard='광주카드';
	}else if($paymentCard  == 'hd'){
		$paymentCard='현대카드';
	}else if($paymentCard  == 'lt'){
		$paymentCard='롯데카드';
	}else if($paymentCard  == 'sinhan'){
		$paymentCard='신한카드';
	}else if($paymentCard  == 'ct'){
		$paymentCard='시티카드';
	}else if($paymentCard  == 'nh'){
		$paymentCard='농협카드';
	}else if($paymentCard  == 'kb'){
		$paymentCard='국민카드';
	}else if($paymentCard  == 'ha'){
		$paymentCard='하나카드';
	}else if($paymentCard  == 'wo'){
		$paymentCard='우리카드';
	}


	echo $selectDate;

	if($selectDate=='0804'){
		$dateVal = '34801';
	}else if ($selectDate=='0805'){
		$dateVal = '34807';
	}
	
?>


<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-Frame-Options" content="deny" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	</head>
	<body>
			<input type="button" onclick="goToken()" value="확인">
			<input type="hidden" name="custHref" value ="">
			<input type="hidden" name="unicityId" value ="">
	</body>
	<script>
		var custHref="";
		var orderNum="";
		var totAmount="";//총액
		var taxAmount="";//세금
		var taxtAxableTotal="";//세금 미포함
		var payform = document.payForm;

		var token="";
	
		$( document ).ready(function() {
		//	goToken();
		});

		function goToken(val){

			if(val=='reload'){
				location.reload();
			}
			var usernameV5 = 'minguk'
			var passwordV5 = 'Mg555555';

				// setup ajax call
			var data = {
				'type':'base64', 
				'value':btoa(usernameV5 + ":" + passwordV5), 
				'namespace':'https://hydra.unicity.net/v5a/employees'
			};
			// convert data to json
			data = JSON.stringify(data);
			// send ajax call
			$.ajax({
				'type':'POST',
				'headers':{'Content-Type':'application/json'},
				'url':'https://hydra.unicity.net/v5a/loginTokens',
				'data':data,
				'success':function (result) {
					token=result.token;

					forOrder(token);

				},
				'error':function () {
					
				}
			});
		}
			

			function forOrder(token){

				var data ={
							"source":{
								"medium":"Template"
							},
							"lines":{
									"items":[
												{
												"item":{
														"href":"https://hydra.unicity.net/v5a/items?id.unicity=<?php echo $dateVal?>"
														},
												"quantity":'<?php echo $member?>'
												}
											]
									},
							"items":{
									"city":"강남구",
									"country":"KR",
									"state":"서울",
									"address1":"테헤란로 328",
									"address2":"동우빌딩 13층",
									"zip":"08851"
							},
						
							
							"shipToName":{
										"firstName":'<?php echo $memberName?>',
										"lastName":" "
							},
							
							"shipToPhone":'<?php echo $phone?>',
							
							"shipToEmail":"test@test.com",
							
							"shippingMethod":{
											"href":"https://hydra.unicity.net/v5a/shippingmethods?type=택배"
											},
							"shipToAddress":{
											"city":"강남구",
											"country":"KR",
											"state":"서울",
											"address1":"테헤란로 328",
											"address2":"동우빌딩 13층",
											"zip":"08851"
							},
							"notes":"",
							
							"transactions":{
										"items":[
												]
							},
							"terms":{
									"discount":{
						
									}
							}
						}
				
						data = JSON.stringify(data);
					
						var url = '<?php echo $customersHref?>'+'/orders';
			

				$.ajax({
					'type':'POST',
					'headers':{'Content-Type':'application/json','Authorization':'Bearer ' + token},
					'url':url,
					'data':data,
					'success':function (result) {

						custHref = result.href;
						orderNum = result.id.unicity
						orderNum = orderNum.replace('82-', '');
						taxAmount = result.terms.tax.amount;
						taxtAxableTotal = result.terms.taxableTotal;
						totAmount = result.terms.total;
						shipToName = result.customer.humanName['fullName@ko'];
				
						ksnetPay(custHref,orderNum,taxAmount,taxtAxableTotal,totAmount,shipToName, token);
					},
					'error':function (result) {
						
					}
				});

			}

			function leadingZeros(date, num) {
					 var zero = '';
					date = date.toString();
					
					if (date.length < num) {
					for (i = 0; i < num - date.length; i++)
					zero += '0';
					}
					return zero + date;
			}

			function ksnetPay(custHref,orderNum,taxAmount,taxtAxableTotal,totAmount,shipToName, token){


				var cardNumber = '<?php echo $cardNumber ?>';
				var expireDate = '<?php echo $expireDate ?>';
				var birthday = '<?php echo $birthday ?>';
				var installment = '<?php echo $installment ?>';
				var password = '<?php echo $password ?>';
				var paymentCard = '<?php echo $paymentCard?>';
				var member_no = '<?php echo $memberNo?>';
				var valNo = '<?php echo $No?>';

				installment = leadingZeros(installment,2);

				var param = {cardNumber:cardNumber,
								expireDate:expireDate,
								birthday:birthday,
								installment:installment,
								password:password,
								orderNum:orderNum,
								taxAmount:taxAmount,
								taxtAxableTotal:taxtAxableTotal,
								totAmount:totAmount,
								custHref:custHref,
								shipToName:shipToName,
								token:token,
								paymentCard:paymentCard,
								member_no:member_no,
								valNo:valNo
								
					};

					//param = JSON.stringify(param);
					console.log(param);
			

				$.ajax({
					url: "savePay.php",
					async : false,
					dataType : "json",
					data:param,
					type:"POST",
					success	: function(result) {
						var resultVal = result.count;
						var okVal = result.okVal;
					console.log("count==>"+resultVal);
						if(resultVal > 0){
							goToken("reload");
						}else if(resultVal == 0){
							alert("주문완료");
							return false;
						}
					
					
					}
						
				});	
			}


	</script>
</html>		