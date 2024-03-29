<?php 
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";
	include "./inc/common_function.php";

?>
<!DOCTYPE html>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html;charset=UTF-8">

<html>
<head>
	<title>KR API</title>

	<script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>

</head>

<body>

<?php

$mem_account = '159082182';
$url = 'https://hydra.unicity.net/v5a/customers?unicity='.$mem_account.'&expand=customer';
//$url = 'https://hydra.unicity.net/v5a/loginTokens';

$username = 'krWebEnrollment';
$password = 'qKZ95XCrpzth5MgCpzMGQwXHw7ZMHg';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
//curl_setopt($ch, CURLOPT_USERPWD, base64_encode("159082182:1234"));
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
$response = curl_exec($ch);
 
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$result = json_decode($response);

echo $result->items[0]->href;

echo "<pre>";		 
print_r($result);
echo "</pre>";


echo "<br><br>";

print_r($status);

echo "<br><br>";

echo "<pre>";		 
print_r($result);
echo "</pre>";

print_r($status);



exit;

?>


		<h1>Authenticate V5</h1>
		<?php 
		echo "=====".decrypt($key, $iv, 'reSxJeKfdTxiDWuyJTbRng==');
		
		?>
	usernameV5: <input type="text" id="usernameV5" value="159082182"><br/>
	passwordV5: <input type="text" id="passwordV5" value="1234"><br/>
	<input type="submit" id="authenticateV5_submit" value="OKay"><br/>
	<textarea id="authenticateV5_result" rows="12" cols="150"></textarea><br/>
	token: <input type="text" id="token" value=""><br/><br/>

<h1>Cal order </h1>
	<!--
	Dist ID :  <input type="text" id="dist_id" value="108357166">
	-->
	Item 1 :  <input type="text" id="item1" value="22250">	 <input type="text" id="qty1" value="1"><br>


	<input type="submit" id="calOrders_submit" value="Calculate Orders"><br/>
	<textarea id="calOrders_result" rows="12" cols="150"></textarea><br/><br/>
	<input type="text" id="freight"><br>
	
	<h1>Create Auto Order</h1>
	Paid With :
	<select  id="payment">
		<option value="ccCard">Credit Card</option>
		<option value="bank">Bank Wire</option>
	</select><br/>
	Ship With :
	<select  id="shipping">
<!--
		<option value="Economy">Yamoto</option>
		<option value="WillCall">WillCall</option>
-->
	<option value="택배">택배</option>

	</select><br/>
	<input type="submit" id="autoOrders_submit" value="Credit card Orders"><br/>
	<textarea id="autoOrders_result" rows="12" cols="150"></textarea><br/><br/>

	<h1>Auto Order List</h1>
	<input type="submit" id="ordersList_submit" value="Order List"><br/>
	<textarea id="ordersList_result" rows="12" cols="150"></textarea><br/><br/>
	
	1st Order : <input type="text" id="order" value="" size="100"><br/>
	<input type="submit" id="orderDetail_submit" value="Get Order Detail"><br/>
	<textarea id="orderDetail_result" rows="12" cols="150"></textarea><br/><br/>
	
	<h1>Edit Order</h1>
	Order Number : <input type="text" id="oldOrder" value="" size="100"><br/>
	<input type="submit" id="editOrder_submit" value="Edit Order"><br/>
	<textarea id="editOrder_result" rows="12" cols="150"></textarea><br/><br/>

	<h1>Delete Order</h1>
	<input type="submit" id="deleteOrder_submit" value="Delete Order"><br/>
	<textarea id="deleteOrder_result" rows="12" cols="150"></textarea><br/><br/>

	<script type="text/javascript">
		$(document).ready( function () {

		// AuthenticateV5 button click event
			$('#authenticateV5_submit').on('click', function() {
				var usernameV5 = $('#usernameV5').val();
				var passwordV5 = $('#passwordV5').val();
				// setup ajax call
				var data = {
					'type':'base64', 
					'value':btoa(usernameV5 + ":" + passwordV5), 
					'namespace':'https://hydra.unicity.net/v5a/me'
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
						console.log(result);
						$('#authenticateV5_result').val(JSON.stringify(result,undefined,4));
						$('#token').val(result.token);
						$('#customer_href').val(result.customer.href);

					},
					'error':function () {
						$('#authenticateV5_result').val('There was an error with your request.');
					}
				});
				// let user know that request is in progress
				$('#authenticateV5_result').val('Request in progress . . .');
			});


			$('#calOrders_submit').on('click', function() {
				//var usernameV5 = $('#usernameV5').val();
				//var passwordV5 = $('#passwordV5').val();
				// setup ajax call
				//var token = $('#token').val();
				//var dist_id = $('#dist_id').val();
				//var token = 'csEnrollmentSpoke03Spoke97:7rtZSiVtmoeU5hULldhQ';
				var item1 = $('#item1').val();
				var item2 = $('#item2').val();
				var item3 = $('#item3').val();
				var qty1 = $('#qty1').val();
				var qty2 = $('#qty2').val();
				var qty3 = $('#qty3').val();
				var shipping  = $('#shipping').val();
				//var shippingmethodV1 = $('#shippingmethod2').val();
				//var locationV1 = $('#location2').val();
				
				var data = {
					    "order": {
							"customer": {
								"href": "https://hydra.unicity.net/v5/customers?type=Associate"
							},
							"lines": {
								"items": [
								{
									"item": {
										"href": "https://hydra.unicity.net/v5/items?id.unicity="+item1
									},
									"quantity": qty1
								}
								]
							},
							 "shipToAddress": {
								"city": "춘천시",
								"country": "KR",
								"state": "강원도",
								"address1": "후석로",
								"address2": "111동 1001호",
								"zip": "24278"
							},
						"shippingMethod": {
							"href":"https://hydra.unicity.net/v5/shippingmethods?type="+shipping
						}
					}
				};

				//alert(data.order.shippingMethod.href);
				// convert data to json
				data = JSON.stringify(data);
				//alert(data.order.customer);
				// send ajax call
				$.ajax({
					'type':'POST',
					//'headers':{'Content-Type':'application/json','Authorization':'Bearer ' + token},
					'headers':{'Content-Type':'application/json'},
					'url':'https://hydra.unicity.net/v5/orderTerms?expand=item',
					'data':data,
					'success':function (result) {
						$('#calOrders_result').val(JSON.stringify(result,undefined,4));
						$('#freight').val(result.items[0].terms.total);
						//$('#token').val(result.token);
						//$('#customer_href').val(result.customer.href);

					},
					'error':function (result) {
						$('#calOrders_result').val(JSON.stringify(result,undefined,4));
					}
				});
				// let user know that request is in progress
				$('#calOrders_result').val('Request in progress . . .');
				$('#freight').val('');
			});


// Create order with Payment
			$('#autoOrders_submit').on('click', function() {

				var item1 = $('#item1').val();
				var item2 = $('#item2').val();
				var item3 = $('#item3').val();
				var qty1 = $('#qty1').val();
				var qty2 = $('#qty2').val();
				var qty3 = $('#qty3').val();
				var token = $('#token').val();
				//var shipping = $('#shipping').val();
				var payment  = $('#payment').val();
				var shipping  = $('#shipping').val();
				var data = {
					"lines":{
						"items":[
							{
								"item":{
									"href":"https://hydra.unicity.net/v5/items?id.unicity="+item1
								},
								"quantity":qty1
							}
						]
					},
					"shipToName":{
					"firstName":"First",
					"lastName":"Last"
					},
					"shipToPhone":"555-555-5555",
					"shipToEmail":"test@test.com",
					"shipToAddress": {
								"city": "춘천시",
								"country": "KR",
								"state": "강원도",
								"address1": "후석로",
								"address2": "111동 1001호",
								"zip": "24278"
							},
					 "shippingMethod":{
						"href":"https://hydra.unicity.net/v5/shippingmethods?type="+shipping
					},
					"notes":"please give me free shaker cup",
					"transactions":{
						"items":[
						{
							"amount":"this.terms.total",
							"type":"AuthorizeAndCapture",
							"method":"CreditCard",
							//"authorization": "12345",							
							"methodDetails":{
								"creditCardNumber":"4111111111111111",
								"payer":"First Last",
								"creditCardExpires":"2017-10",
								"creditCardSecurityCode":"123"
							}
							
						}
						]
					},
					"recurrence": {
						//"dateStarts": "2018-08-05",
						"schedule": {
							"month": "*",
							"dayOfMonth": 5,
							"skipRepetitions": "1"
						},
						"status": "Enabled"
					},
					/*"terms": {
						"period": "2018-09"
					}*/
				};

				if(payment =='bank'){
					var bankWire =  {
						"amount":"this.terms.total",
						"type":"IOU",
						"method":"BankWire",
						"methodDetails":{}
					};
					data.transactions.items[0] = bankWire;
				}
				// convert data to json
				data = JSON.stringify(data);
				// send ajax call
				$.ajax({
					'type':'POST',
					//'headers':{'Content-Type':'application/json','Authorization':'Bearer ' + token},
					'headers':{'Content-Type':'application/json','Authorization':'Bearer ' + token},
					'url':'https://hydraqa.unicity.net/v5a-test/customers/me/autoorders',
					'data':data,
					'success':function (result) {
						$('#autoOrders_result').val(JSON.stringify(result,undefined,4));
						//$('#token').val(result.token);
						//$('#customer_href').val(result.customer.href);

					},
					'error':function (result) {
						$('#autoOrders_result').val(JSON.stringify(result,undefined,4));
					}
				});
				// let user know that request is in progress
				$('#autoOrders_result').val('Request in progress . . .');
			});


			$('#ordersList_submit').on('click', function() {				
				var token = $('#token').val();
				// send ajax call
				$.ajax({
					'type':'GET',
					'headers':{'Content-Type':'application/json','Authorization':'Bearer ' + token},
					'url':'https://hydraqa.unicity.net/v5a-test/customers/me/autoorders',
					'success':function (result) {
						var json_data = JSON.stringify(result,undefined,4);
						$('#ordersList_result').val(json_data);
						$('#order').val(result.items[0].href);
					},
					'error':function (result) {
						$('#ordersList_result').val(JSON.stringify(result,undefined,4));
					}
				});
				// let user know that request is in progress
				$('#ordersList_result').val('Request in progress . . .');
			});


			$('#orderDetail_submit').on('click', function() {				
				var url = $('#order').val();
				var token = $('#token').val();
				// send ajax call
				url = url.replace("https://hydra.unicity.net/", "https://hydra.unicity.net/");
				$.ajax({
					'type':'GET',
					'headers':{'Content-Type':'application/json','Authorization':'Bearer ' + token},
					'url':url,
					'success':function (result) {
						var json_data = JSON.stringify(result,undefined,4);
						$('#orderDetail_result').val(json_data);
					},
					'error':function (result) {
						$('#orderDetail_result').val(JSON.stringify(result,undefined,4));
					}
				});
				// let user know that request is in progress
				$('#orderDetail_result').val('Request in progress . . .');
			});
			
			$('#editOrder_submit').on('click', function() {

				var item1 = $('#item1').val();
				var item2 = $('#item2').val();
				var item3 = $('#item3').val();
				var qty1 = $('#qty1').val();
				var qty2 = $('#qty2').val();
				var qty3 = $('#qty3').val();
				var token = $('#token').val();
				//var shipping = $('#shipping').val();
				var payment  = $('#payment').val();
				var oldOrder  = $('#oldOrder').val();
				var data = {
					"lines":{
					"items":[
						{
							"item":{
								"href":"https://hydraqa.unicity.net/v5a-test/items?id.unicity="+item1
							},
							"quantity":qty1
						},
						{
							"item":{
								"href":"https://hydraqa.unicity.net/v5a-test/items?id.unicity="+item2
							},
							"quantity":qty2
						},
												{
							"item":{
								"href":"https://hydraqa.unicity.net/v5a-test/items?id.unicity="+item3
							},
							"quantity":qty3
						}
						]
					},
					"shipToName":{
					"firstName":"First",
					"lastName":"Last"
					},
					"shipToPhone":"555-555-5555",
					"shipToEmail":"test@test.com",
					"shipToAddress": {
								"city": "춘천시",
								"country": "KR",
								"state": "강원도",
								"address1": "후석로",
								"address2": "111동 1001호",
								"zip": "24278"
							},
					 "shippingMethod":{
						"href":"https://hydraqa.unicity.net/v5a-test/shippingmethods?type=택배"
					},
					"recurrence": {
						"schedule": {
							"month": "*",
							"dayOfMonth": 10,
							"skipRepetitions": "2",
						},
						"status": "Enabled"
					},
					"notes":"please give me free shaker cup",
					"transactions":{
						"items":[
						{
							"amount":"this.terms.total",
							"type":"AuthorizeAndCapture",
							"method":"CreditCard",
							"authorization": "12345",							
							"methodDetails":{
								"creditCardNumber":"4111111111111111",
								"payer":"First Last",
								"creditCardExpires":"2017-10",
								"creditCardSecurityCode":"123"
							}
							
						}
						]
					}
				};

				if(payment =='bank'){
					var bankWire =  {
						"amount":"this.terms.total",
						"type":"IOU",
						"method":"BankWire",
						"methodDetails":{"bankName":"123123123"}
					};
					data.transactions.items[0] = bankWire;
				}
				// convert data to json
				data = JSON.stringify(data);
				// send ajax call
				$.ajax({
					'type':'POST',
					//'headers':{'Content-Type':'application/json','Authorization':'Bearer ' + token},
					'headers':{'Content-Type':'application/json','Authorization':'Bearer ' + token},
					'url':oldOrder,
					'data':data,
					'success':function (result) {
						$('#editOrder_result').val(JSON.stringify(result,undefined,4));
						//$('#token').val(result.token);
						//$('#customer_href').val(result.customer.href);

					},
					'error':function (result) {
						$('#editOrder_result').val(JSON.stringify(result,undefined,4));
					}
				});
				// let user know that request is in progress
				$('#editOrder_result').val('Request in progress . . .');
			});


			$('#deleteOrder_submit').on('click', function() {				
				var url = $('#order').val();
				var token = $('#token').val();
				// send ajax call
				url = url.replace("https://hydra.unicity.net/", "https://hydra.unicity.net/");
				$.ajax({
					'type':'DELETE',
					'headers':{'Content-Type':'application/json','Authorization':'Bearer ' + token},
					'url':url,
					'success':function (result) {
						var json_data = JSON.stringify(result,undefined,4);
						$('#deleteOrder_result').val(json_data);
					},
					'error':function (result) {
						$('#deleteOrder_result').val(JSON.stringify(result,undefined,4));
					}
				});
				// let user know that request is in progress
				$('#deleteOrder_result').val('Request in progress . . .');
			});

		});
		</script>
</body>
</html>
