<?php
	if (!include_once("./includes/dbconn.php")){
		echo "The config file could not be loaded";
	}
	include "./includes/AES.php";

 ?>

 <!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>ssn</title>
 
</head>
<body>
	<article class="boardArticle">
		<h3>SSN</h3>
		<table>
			<thead>
				<tr>
					<th scope="col" class="no">번호</th>
					<th scope="col" class="title">회원번호</th>
					<th scope="col" class="author">주민번호</th>
					<th scope="col" class="date">작성일</th>
				</tr>
			</thead>
			<tbody>
					<?php
						// 개인정보 누출될 위험이 있어 강제 오류 발생
						$sql = 'XXXXXXselect * from tb_distSSN order by 1 desc';
						$result = mysql_query($sql);
						//$row = mysql_fetch_array($result);
					 	while($row =mysql_fetch_array($result))
						{
							$datetime = explode(' ', $row['create_date']);
							$date = $datetime[0];
						 	
						 	$decSSN = decrypt($key, $iv, $row['government_id']);
					?>
				<tr>
					<td class="no"><?php echo $row['id']?></td>
				 	<td class="author"><?php echo $row['dist_id']?></td>
					<td class="date"><?php echo $decSSN;?></td>
					<td class="hit"><?php echo $date;?></td>
				</tr>
					<?php
						}
					?>
			</tbody>
		</table>
	</article>
</body>
</html>