<?php 	include "includes/config/common_functions.php";
		
?>
<? 

    include "includes/config/config.php";
    include "includes/config/nc_config.php";
	include "./includes/AES.php";


	?>
	
	
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>GLIC Travel pack 예약</title>
		<meta name="description" content="" />
		<meta http-equiv="Content-Script-Type" content="text/javascript">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
		<link rel="stylesheet" type="text/css" href="./css/joo.css" />
	
	</head>
	<body>
		<form name="sendForm" method="post">
							<input type="hidden" name="custHref" value="">
							<input type="hidden" name="unicityId" value="">
		
		</form>
	</body>
	<script type="text/javascript" src="./js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src='./js/common.js'></script>
	<script type="text/javascript" src="./js/selectordie.min.js"></script>

	<script type="text/javascript">
   var sendForm = document.sendForm;
   var unicityId = new Array(); 
   var custHref = new Array(); 

			$( document ).ready(function() {

				var arr=[];
					for(var i=0;i<arr.length;i++){

						$.ajax({
							url: 'https://hydra.unicity.net/v5a/customers?unicity='+arr[i]+'&expand=customer',
							headers:{
								'Content-Type':'application/json'
							},
							type: 'GET',
							async: false,
							success: function(result) {
								


								unicityId.push(result.items[0].unicity);
								custHref.push(result.items[0].href);
								
								
								
								
							}, error: function() {
								alert('검색된 회원이 없습니다.');
							}
						})
					}

					saveData(unicityId,custHref);
			});

			function saveData (unicityId,custHref){
				console.log(unicityId);
					console.log(custHref);

					json_data = JSON.stringify(custHref);
					json_data1= JSON.stringify(unicityId);

				$.ajax({
						url: "savehref.php",
						type: "post",
						data: { 
							unicityId  : unicityId, 
							custHref : custHref
						},

						success: function(val) {
							console.log(">>>>>"+val);
						}
				});
			
			}

	</script>
		
</html>