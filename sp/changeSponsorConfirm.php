<?php 	include "includes/config/common_functions.php";

?>
<?php 
    include "includes/config/config.php";
    include "includes/config/nc_config.php";
    include "includes/AES.php";
    cert_validation();
    
    $distID = $_POST['fId'];
    $title = $_POST['titleVal'];
    $title = encrypt($key, $iv, $title);

      
    $query = "select count(*) from tb_change_sponsor where 1 = 1 and member_no =". $distID;
    
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    $TotalArticle = $row[0];

    $query2 = "select * from tb_change_sponsor where 1 = 1 and member_no=".$distID;
    $result2 = mysql_query($query2);
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>후원자 변경 신청</title>
		<meta name="description" content="" />
		<meta http-equiv="Content-Script-Type" content="text/javascript">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
		<link rel="stylesheet" type="text/css" href="./css/joo.css" />
	</head>
	<body>
		<div class="wrapper" >
			<div class="main_wrapper">
				<div class="figure">
					<img src="./images/mainlogo.png" alt="유니시티 로고" />
				</div>
				<div class="main_box">
					<div class="main_inner_box">
						<form name="frmSearch">
							<input type="hidden" name="noVal" value="">
							<input type="hidden" name="title" value="<?php echo $title?>">
    						<table border="1" style="margin: 0px;padding: 0px;">
    							<tr>
    								<th width="5%" style="text-align: center;"><font color="#FFFFFF">신청일자</font></th>
    								<th width="5%" style="text-align: center;"><font color="#FFFFFF">신청내용</font></th>
    								<th width="5%" style="text-align: center;"><font color="#FFFFFF">처리결과</font></th>
    							</tr>
    							<?php 
    							 $result2 = mysql_query($query2);
    							 if ($TotalArticle) {
        							 while($obj = mysql_fetch_object($result2)) {
        							     $apply_s = date("Y-m-d", strtotime($obj->apply_date));
        							     
        							     if($obj->reg_status=='3'){
        							         $resultVal = '완료';
        							     }else if ($obj->reg_status=='4'){
        							         $resultVal = '반려';
        							     }else if ($obj->reg_status=='2'){
        							         $resultVal = '처리중';
        							     }
    							 
    							?>
    							<tr>
    								<td style="width: 5%" align="center"><font color="#FFFFFF"><?echo $apply_s?></font></td>
     								<td style="width: 5%" align="center"><a HREF="javascript:onView('<?echo $obj->no?>')"><font color="#FFFFFF"><?echo decrypt($key, $iv,$title)?></font></a></td>
     								<td style="width: 5%" align="center"><font color="#FFFFFF"><?echo $resultVal?></font></td>
    							</tr>
    							<?php }
    							 } else{?>
    							 	<tr>
    							 		<td colspan="5" align="center"><font color="#FFFFFF">신청 내역이 존재 하지 않습니다.</font></td>
    							 	</tr>
    							 <?php } ?>		
    						</table>
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>	
	<script type="text/javascript">
		function onView(noVal){
			
			document.frmSearch.noVal.value = noVal; 
			
		
			document.frmSearch.action= "confirm_view.php";
			document.frmSearch.submit();
		}	
	</script>
</html>