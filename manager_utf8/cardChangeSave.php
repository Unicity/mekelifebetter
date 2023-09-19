<?	include "./admin_session_check.inc";
    include "./inc/global_init.inc";
    include "../dbconn_utf8.inc";
    include "./inc/common_function.php";
    include "../AES.php";

    $s_adm_id = str_quote_smart_session($s_adm_id);



    $card = isset($_POST['card']) ? $_POST['card'] : "";	 
    $cardNum = isset($_POST['cardNum']) ? $_POST['cardNum'] : "";	 
    $approveNum = isset($_POST['approveNum']) ? $_POST['approveNum'] : "";	 
    $amount = isset($_POST['amount']) ? $_POST['amount'] : "";	 
    $orderNum = isset($_POST['orderNum']) ? $_POST['orderNum'] : "";	 
    $note = isset($_POST['note']) ? $_POST['note'] : "";	
    $newVal = $_POST['newValue'];

    $count = count($approveNum);

    $nowDate = date("Y-m-d");

    if($newVal=='new'){

    
        $insertQuery = "INSERT INTO card_change (card, card_num, approve_num, amount, order_num, note,create_date,ids) VALUES ";

		for($i=0; $i<$count; $i++){
   
			$card[$i] 			= isset($card[$i]) ? $card[$i] : '';
			$cardNum[$i]  	= isset($cardNum[$i]) ? $cardNum[$i] : '';
			$approveNum[$i] 		= isset($approveNum[$i]) ? $approveNum[$i] : 0;
			$amount[$i] 		= isset($amount[$i]) ? $amount[$i] : '0';
			$orderNum[$i]			= isset($orderNum[$i]) ? $orderNum[$i] : 0;
			$note[$i]			= isset($note[$i]) ? $note[$i] : '';
      
		
            $cardChangeValues[] ="('$card[$i]', '$cardNum[$i]', $approveNum[$i], $amount[$i], $orderNum[$i], '$note[$i]' ,'$nowDate','$s_adm_id')";
        
		}
        $insertRefundLineQuery = $insertQuery.implode(',',$cardChangeValues);
        $res = mysql_query($insertRefundLineQuery) or die(__FILE__." : Line ".__LINE__."<p>".mysql_error());

        $alert = '저장이 완료 됐습니다.';
		echo "<script>alert('$alert')</script>";
    }  
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
                <th width="6%" style="text-align: center;">삭제</th>
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
                    <td style="width: 5%" align="center"><button><a href="javascript:goDelete('<?echo $obj->approve_num?>','<?echo $obj->order_num?>','<?echo $obj->ids?>')">삭제</a></button></td>
                </tr>
                    <?php }
                        }
                    ?>
			</tr>

        </table>        
            
    </body>      
    <script>
        function goDelete(approve, order, ids){
            var s_adm_id = '<?php echo $s_adm_id ?>';
            //등록한 본인만 삭제 가능
            if(ids==s_adm_id){                 
                var param = {approveNum:approve,
								orderNum:order,
								ids:ids		
					    };
        
                    $.ajax({
					url: "cardChangeDelete.php",
					async : false,
					dataType : "json",
					data:param,
					type:"POST",
					success	: function(result) {                
                        if(result.okVal=="deleteOK"){
                           alert("삭제완료");
                           history.go(-2)
                        }
					}
				});	 
            }else{
                alert('삭제는 본인이 등록한 데이터만 가능 합니다.');
                return false;        
            } 
        }   
    </script>      
</html>    