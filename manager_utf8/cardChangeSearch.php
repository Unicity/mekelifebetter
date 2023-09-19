<?
    include "./admin_session_check.inc";
    include "./inc/global_init.inc";
    include "../dbconn_utf8.inc";
    include "./inc/common_function.php";
    include "../AES.php";

    $nowDate = date("Y-m-d");
    $query = "select count(*) from card_change where create_date = '$nowDate'";
    $result = mysql_query($query,$connect);
    $row = mysql_fetch_array($result);
    $TotalArticle = $row[0];

    $selectQuery = "select * from card_change where create_date = '$nowDate'";

    $selectResult=mysql_query($selectQuery) or die('Query_error');
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <link rel="stylesheet" href="./inc/admin.css" type="text/css">
    </head>    
    <body>
        <table cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
            <tr>
                <th width="6%" style="text-align: center;">카드종류</th>
                <th width="6%" style="text-align: center;">카드번호</th>
                <th width="6%" style="text-align: center;">승인번호</th>
                <th width="6%" style="text-align: center;">금액</th>
                <th width="6%" style="text-align: center;">주문번호</th>
                <th width="6%" style="text-align: center;">비고</th>
                <th width="6%" style="text-align: center;">등록자</th>
     
            </tr>
            <tr>
                <?php
				
					if ($TotalArticle) {
						while($obj = mysql_fetch_object($selectResult)) {
                            $amount =number_format($obj-> amount);
  
				?>
				<tr> 
                    <td style="width: 5%" align="center"><?echo $obj->card?></td>
					<td style="width: 5%" align="center"><?echo $obj->card_num?></td>
					<td style="width: 5%" align="center"><?echo $obj->approve_num?></td>
                    <td style="width: 5%" align="center"><?echo $amount?></td>
					<td style="width: 5%" align="center"><?echo $obj->order_num?></td>
					<td style="width: 5%" align="center"><?echo $obj->note?></td>
                    <td style="width: 5%" align="center"><?echo $obj->ids?></td>
					
                </tr>
                    <?php }
                        }
                    ?>
			</tr>
        </table>                        
    </body> 
</html>    