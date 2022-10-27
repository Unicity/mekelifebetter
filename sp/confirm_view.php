<?php 	include "includes/config/common_functions.php";

?>
<?php 
    include "includes/config/config.php";
    include "includes/config/nc_config.php";
    include "includes/AES.php";
    cert_validation();
    
    $noVal = trim($noVal);
    $title = trim($title);
    $title=decrypt($key, $iv,$title);
    $query = "select * from tb_change_sponsor where 1 = 1 and no=".$noVal;
    
    $result = mysql_query($query);
    $list = mysql_fetch_array($result);
    
    $member_no = $list[member_no];
    $member_name = $list[member_name];
    $sponsor_no = $list[sponsor_no];
    $sponsor_name = $list[sponsor_name];
    $ch_sponsor_name = $list[ch_sponsor_name];
    $ch_sponsor_no = $list[ch_sponsor_no];
    $apply_date = $list[apply_date];
     
    $apply_s = date("Y-m-d", strtotime($apply_date));
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>신청 확인</title>
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
						<table border="1" style="margin: 0px;padding: 0px;">
							<tr>
								<th width="5%" style="text-align: center;"><font color="#FFFFFF">신청일자</font></th>
								<th width="5%" style="text-align: center;"><font color="#FFFFFF">신청<br/>내용</font></th>
								<th width="5%" style="text-align: center;"><font color="#FFFFFF">현재 <br/>후원자 번호</font></th>
								<th width="5%" style="text-align: center;"><font color="#FFFFFF">현재 <br/>후원자 이름</font></th>
								<th width="5%" style="text-align: center;"><font color="#FFFFFF">변경 <br/>후원자 번호</font></th>
								<th width="5%" style="text-align: center;"><font color="#FFFFFF">변경 <br/>후원자 이름</font></th>
							</tr>
							<tr>
								<td style="width: 5%" align="center"><font color="#FFFFFF"><?echo $apply_s?></font></td>
								<td style="width: 5%" align="center"><font color="#FFFFFF"><?echo $title?></font></td>
								<td style="width: 5%" align="center"><font color="#FFFFFF"><?echo $sponsor_no?></font></td>
								<td style="width: 5%" align="center"><font color="#FFFFFF"><?echo $sponsor_name?></font></td>
								<td style="width: 5%" align="center"><font color="#FFFFFF"><?echo $ch_sponsor_no?></font></td>
								<td style="width: 5%" align="center"><font color="#FFFFFF"><?echo $ch_sponsor_name?></font></td>
								
							</tr>
						</table>	
					</div>
				</div>
			</div>
		</div>	
			
	</body>
</html>	