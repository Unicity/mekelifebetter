<?php
session_start();
?>

<?
	header('Content-Type:text/html;charset=UTF-8');
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
	header("Expires: Mon,26 Jul 1997 05:00:00 GMT");
	include "./admin_session_check.inc";

	include "../dbconn_utf8.inc";



?>
<HTML>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta http-equiv="X-Frame-Options" content="deny" />
<link rel="stylesheet" href="inc/admin.css" TYPE="text/css">
<title><?echo $g_site_title?></title>
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

</head>
<body>
	<form name="frm_m" method="post">  
		<input type="hidden" name="href" value="">
		<input type="hidden" name="unicityId" value="">
        <table height='35' width='100%' cellpadding='0' cellspacing='0'border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
            <tr>
                <td align='center'>
					<table border="0" cellspacing="1" cellpadding="2" class="IN3">
                        <tr>
                            <td><input style="margin-left:10px;" type="button" value="시작" onkeyup="enterkey();" onclick="search()"></td>
                        </tr>
                    </table>    
                </td>
            </tr>
         
        </table>
	</form>
</body>
<script language="javascript">

$( document ).ready(function() {
			search();
		});

    function search(value){
console.log(value);
		if(value=='reload'){
				location.reload();
				return false;
			}
		<?php 
			$query = "select * from reset_password where 1 = 1 and flag = 'N' and new_member_no=0 ";
			$result2 = mysql_query($query,$connect);
			$list = mysql_fetch_array($result2);
				$memberNo = $list[member_no];

			?>
        
          var memberNo = '<?php echo $memberNo ?>'; 

		$.ajax({
			url: 'https://hydra.unicity.net/v5a/customers?unicity='+memberNo+'&expand=customer',
			type: 'GET',
			success: function(result) {
				let href = result.items[0].href;
				let unicityId = result.items[0].unicity;
				forUpdate(href,unicityId);

				
			}, error: function(err) {
				alert('검색된 회원이 없습니다.' + err);
			}
		});
	
    }

    function enterkey() {
        if (window.event.keyCode == 13) {
    
             // 엔터키가 눌렸을 때 실행할 내용
             search();
        }
    }

	function forUpdate(val,val1){

		var param = {href:val,
					unicityId:val1
					
		};

		//param = JSON.stringify(param);
		//console.log(param);
			

			$.ajax({
				url: "./deletePassword_b_update.php",
				async : false,
				dataType : "json",
				data:param,
				type:"POST",
				success	: function(result) {
					//alert(result.count);
					var resultVal = result.count;
					var okVal = result.okVal;
				
					if(resultVal > 0){
						search("reload");
					}else if(resultVal == 0){
						alert("주문완료");
						return false;
					}
					
				
				}
					
			});	
				
	}


   

</script>