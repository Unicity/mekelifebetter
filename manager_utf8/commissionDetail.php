<?php


?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-Frame-Options" content="deny" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <title>수당상세조회</title>
    </head>
    <body>
	
<?php include "common_load.php" ?>

        <table cellspacing="0" cellpadding="10" class="title">
            <tr>
                <td align="center"><b>수당상세</b></td>
            </tr>
            <tr>
                <td align="left" width="600" >
                    회원번호: <input type="text" name="baId" id="baId" value=''>&nbsp;
                    <input type="button" value="검색" onClick="onSearch();">
                </td>
            </tr>
            <tr>
                <td id="report-commission"> 

                </td>
            </tr>
           
		</table>
    </body>
    <script>
      
        function onSearch(){
            var baId = $("#baId").val();

            $.ajax({
    			url: 'https://hydra.unicity.net/v5a/customers?unicity='+baId+'&expand=customer',
    			headers:{
    				'Content-Type':'application/json'
    			},
    			type: 'GET',
    			success: function(result) {
    				console.log("herf-->"+result);
                    var urlHref = result.items[0].href;    
                    $.ajax({
                            'type':'GET',
                            'headers':{'Authorization':'Bearer krWebEnrollment:qKZ95XCrpzth5MgCpzMGQwXHw7ZMHg'},
                            'url':urlHref+'/commissionstatements',
                            'success':function (results) {
                                console.log("결과==>" + JSON.stringify(results,undefined,4))				
			 	                var message = results;
                                 var opCom = '';
                                if(message.items.length != 0){
				                    if(typeof message.items[0].period != 'undefined'){
						                for(var i = message.items.length-1;i>=0;i--){
							                opCom +='<option value="'+message.items[i].href+'">'+message.items[i].period+'</option>';
						                }
				                    }
                                    
                                    var msgCom = '<select id="monthCom"></select><br/><br/>'
			                    }
                                $("#report-commission").html(msgCom);
                                alert($("#report-commission").html(msgCom));
			    	            $("#monthCom").html(opCom);
                  
                                console.log(">>>>::::" + opCom);
			                },
			        'error':function (results) {
			 	
			                                    }
		            });
    				
    			}, error: function() {
    				alert('검색된 회원이 없습니다.');
    			}
    		});
        }

    </script>
</html>