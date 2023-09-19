<!DOCTYPE html>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html;charset=UTF-8">

<html>
<head>
	<title>KR API</title>

	<script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>

</head>

<body>
<h1>Authenticate v5a</h1>
usernameV5: <input type="text" id="usernameV5" value="226336882"><br/>
passwordV5: <input type="text" id="passwordV5" value="123456"><br/>
<input type="submit" id="authenticateV5_submit" value="OKay"><br/>
<textarea id="authenticateV5_result" rows="5" cols="150"></textarea><br/>
token: <input type="text" id="token" value="" style="width:600px"><br/>
url: <input type="text" id="customer_href" value="" style="width:600px"><br/>
<br/>


<h1>Rights Update</h1>
<input type="submit" id="rights_submit" value="OKay"><br/>
<textarea id="rights_result" rows="5" cols="150"></textarea><br/>


<h1>Get info</h1>
<input type="submit" id="info_submit" value="OKay"><br/>
<textarea id="info_result" rows="12" cols="150"></textarea><br/>


<script type="text/javascript">
$(document).ready( function () {

	$('#authenticateV5_submit').on('click', function() {
		var usernameV5 = $('#usernameV5').val();
		var passwordV5 = $('#passwordV5').val();
		// setup ajax call
		var data = {
			'type':'base64', 
			'value':btoa(usernameV5 + ":" + passwordV5), 
			'namespace':'https://hydra.unicity.net/v5a/customers'
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


	$('#rights_submit').on('click', function() {
		var token = $('#token').val();
		var url = 'https://hydra.unicity.net/v5a/customers/me/rights?type=SendNoticeEmail&holder=Unicity';

		$.ajax({
			'type':'DELETE',
			'headers':{'Authorization':'Bearer ' + token},
			'url':url,
			//'data':data,
			'success':function (result) {
				$('#rights_result').val(JSON.stringify(result,undefined,4));
			},
			'error': function( jqXHR, textStatus, errorThrown ) { 
				console.log(jqXHR);
				$('#rights_result').val( jqXHR + "," + textStatus + ", " + errorThrown ); 
			} 
		});
		// let user know that request is in progress
		$('#rights_result').val('Request in progress . . .');
	});

	$('#info_submit').on('click', function() {
		var token = $('#token').val();
		var url = $('#customer_href').val();

		$.ajax({
			'type':'GET',
			'headers':{'Authorization':'Bearer ' + token},
			'url':url,
			//'data':data,
			'success':function (result) {
				$('#info_result').val(JSON.stringify(result,undefined,4));
			},
			'error':function () {
				$('#info_result').val('There was an error with your request.');
			}
		});
		// let user know that request is in progress
		$('#info_result').val('Request in progress . . .');
	});
});
</script>
</body>
</html>