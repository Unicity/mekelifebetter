<?php
include "./inc/dbconn.php";

$newProgramQuery = "select date_format(createDate,'%Y-%m-%d') as date, count(*) as count from ProgramMaster where userID not in (15745082, 137240082, 99708282, 191759082, 188022582) AND createDate between DATE_ADD(now(), interVal -10 day) and now() group by date_format(createDate,'%Y-%m-%d') with rollup;";

$newProgramQueryResult = mysql_query($newProgramQuery);

$totalProgramQuery = "select count(*) as count from ProgramMaster where userID not in (15745082, 137240082, 99708282, 191759082, 188022582)";
$totalProgramQueryResult = mysql_query($totalProgramQuery);
$totalProgramQueryRow = mysql_fetch_array($totalProgramQueryResult);

 
$newNonMemberQuery = "select date_format(createDate, '%Y-%m-%d') as date, count(*) as count from User Join UserInfo on User.id = UserInfo.id where User.department = 'nonmember' 
AND createDate between DATE_ADD(now(), interVal -10 day) and now() group by date_format(createDate, '%Y-%m-%d') with rollup;";

$newNonMemberQuery = mysql_query($newNonMemberQuery);

$totalNewMemberQuery = "select department, count(*) as count   from User where User.id not in (15745082, 137240082, 99708282, 191759082, 188022582) group by department";
$totalnewMemberQueryResult = mysql_query($totalNewMemberQuery);
 
 ?>

 
 <html>
 <head>
 	<title> 5 step stats</title>
 	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<meta charset="UTF-8">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Allerta+Stencil">
 </head>
 <body>
 	<div class="w3-container w3-center w3-allerta">
  	 
  		<p class="w3-xxxlarge">5 Step Stats Page</p>

  		<p class="w3-xlarge">New Program Trend(새 프로그램)</p>
  		<table class="w3-table-all">
		    <thead>
		      <tr class="w3-red">
		        <th>Date</th>
		        <th>Count</th>
		      </tr>
		    </thead>
		    <?php 
		    	while($dailyProgram = mysql_fetch_assoc($newProgramQueryResult)) {
		    ?>
		    <tr>
		      <td><?=isset($dailyProgram['date']) ? $dailyProgram['date'] : 'Total' ?></td>
		      <td><?=$dailyProgram['count']?></td>
		    </tr>
		    <?php 
		    	}
		    ?>
		    <tr>
		    	<td>Grand Total</td>
		    	<td><?=$totalProgramQueryRow[0]?></td>
		    </tr>
		 </table>

		<p class="w3-xlarge">New Non-Member Trend(새 사용자)</p>
  		<table class="w3-table-all">
		    <thead>
		      <tr class="w3-blue">
		        <th>Date</th>
		        <th>Count</th>
		      </tr>
		    </thead>
		    <?php 
		    	while($dailyNewNonMember = mysql_fetch_assoc($newNonMemberQuery)) {
		    ?>
		    <tr>
		      <td><?= isset($dailyNewNonMember['date']) ? $dailyNewNonMember['date'] : 'Total' ?></td>
		      <td><?=$dailyNewNonMember['count']?></td>
		    </tr>
		    <?php 
		    	}
		    ?>
		 </table>

		 <p class="w3-xlarge">Member by Type (사용자 타입별)</p>
  		<table class="w3-table-all">
		    <thead>
		      <tr class="w3-green">
		        <th>Type</th>
		        <th>Count</th>
		      </tr>
		    </thead>
		    <?php 
		    	while($totalnewMember = mysql_fetch_assoc($totalnewMemberQueryResult)) {
		    ?>
		    <tr>
		      <td><?=$totalnewMember['department']?></td>
		      <td><?=$totalnewMember['count']?></td>
		    </tr>
		    <?php 
		    	}
		    ?>
		 </table>
		 <br>
	</div>

 </body>
 </html>
