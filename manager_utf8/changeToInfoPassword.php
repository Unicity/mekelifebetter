<?php
session_start();
?>

<?
	header('Content-Type:text/html;charset=UTF-8');
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
	header("Expires: Mon,26 Jul 1997 05:00:00 GMT");
	header('Access-Control-Allow-Origin: *');

	include "./admin_session_check.inc";

	include "../dbconn_utf8.inc";



?>
<HTML>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta http-equiv="X-Frame-Options" content="deny" />
<!--<link rel="stylesheet" href="inc/admin.css" TYPE="text/css">-->
<title><?echo $g_site_title?></title>
<script
<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>

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
                            <td><input style="margin-left:10px;" type="button" value="시작" onkeyup="enterkey();" onclick="search();"></td>
                        </tr>
                    </table>    
                </td>
            </tr>
         
        </table>
	</form>
</body>
<script language="javascript">


	$( document ).ready(function() {
			//search();
		});

    function search(val){

	console.log(val);
	if(val=='reload'){
		location.reload();
		return false;
	}
		<?php 
			$query = "select * from reset_password where 1 = 1 and flag = 'Y' and info_yn='N'";
			$result2 = mysql_query($query,$connect);
			$list = mysql_fetch_array($result2);
				$memberHref = $list[member_href];

				$memberNo = $list[member_no];

				function passwordGenerator( $length=8 ){

					$counter = ceil($length/4);
					// 0보다 작으면 안된다.
					$counter = $counter > 0 ? $counter : 1;            
			
					$charList = array( 
									array("0", "1", "2", "3", "4", "5","6", "7", "8", "9", "0"),
									array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z"),
									array("!") 
								);
					$password = "";
					for($i = 0; $i < $counter; $i++)
					{
						$strArr = array();
						for($j = 0; $j < count($charList); $j++)
						{
							$list = $charList[$j];
			
							$char = $list[array_rand($list)];
							$pattern = '/^[a-z]$/';
							// a-z 일 경우에는 새로운 문자를 하나 선택 후 배열에 넣는다.
							if( preg_match($pattern, $char) ) array_push($strArr, strtoupper($list[array_rand($list)]));
							array_push($strArr, $char);
						} 
						// 배열의 순서를 바꿔준다.
						shuffle( $strArr );
			
						// password에 붙인다.
						for($j = 0; $j < count($strArr); $j++) $password .= $strArr[$j];
					}
					// 길이 조정
					return substr($password, 0, $length);
				}

			?>
        
          var memberHref = '<?php echo $memberHref ?>'; 

		  var passWord = '<?php echo passwordGenerator()?>';

		  var param = {
      					"value": passWord				
					};

		param = JSON.stringify(param);		
		$.ajax({
			url: memberHref+'/password',
			type: 'POST',
			data:param,
			'headers':{'Content-Type':'application/json','Authorization':'Bearer 545961c8-64a0-4484-b695-107bb87322b7'},
			success: function(result) {
				
				let val = result.value;
				goInfo(val);
				//let href = result.items[0].href;
				//let unicityId = result.items[0].unicity;
				//forUpdate(href,unicityId);


				
			}, error: function(err) {
				alert('검색된 회원이 없습니다.' + JSON.stringify(err));
			}
		});
	
    }

	function goInfo(val){
		if(val != null ||  val != ' '){
			
			<?php
				

				$update = "update reset_password set infoDate=now(),
													  info_yn = 'Y'
													 where member_no ='$memberNo'";
							mysql_query($update) or die("Query Error");									  
				$countChekc  = "select count(*) as cnt from reset_password where info_yn = 'N' and flag = 'Y'";
				$resultCount = mysql_query($countChekc);
				$rowCk = mysql_fetch_array($resultCount);
				$cnt = $rowCk[cnt];													  

				?>
				let cnt = '<?echo $cnt?>';
	console.log(cnt);
			if(cnt > 0){
					search("reload");
				}else if(cnt == 0){
					alert("주문완료");
					return false;
				}

			}
	

	}



    function enterkey() {
        if (window.event.keyCode == 13) {
    
             // 엔터키가 눌렸을 때 실행할 내용
             search();
        }
    }

	
</script>