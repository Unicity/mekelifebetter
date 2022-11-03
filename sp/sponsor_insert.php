<?php

header('Content-Type:text/html;charset=UTF-8');
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Mon,26 Jul 1997 05:00:00 GMT");
?>

<?php

include "includes/config/config.php";

$distID = $_POST['distID'];
$distName = $_POST['distName'];
$sponsorID = $_POST['rsponsorId'];
$sponsorName = $_POST['rsponsorName'];
$chSponsorID = $_POST['chSponsorID'];
$chSponsorName = $_POST['chSponsorName'];
$reg_status = $_POST['reg_status'];
$agreeYn = $_POST['agreeYn'];
$agreeUpdate = $_POST['agreeUpdate'];
$num = $_POST['num'];
$entry_date = $_POST['entry_date'];
$address = $_POST['fAddress'];
$mobilePhone= $_POST['mobilePhone'];
$appYn= $_POST['app_yn'];



$already = '이미 신청이 완료 되어 재접수 불가 합니다.';
$now_date = date("Y-m-d H:i:s");


$query3 = "SELECT COUNT(*) CNT FROM tb_change_sponsor  where member_no='".$distID."'and reg_status not in('8','4');";
$result1 = mysql_query($query3);

if($agreeUpdate !='update' || $agreeUpdate ==''){
    if(mysql_result($result1,0) >0){
    
        echo "<script>alert('$already');history.back();</script>";
        echo "<script>parent.close();</script>";;
        exit();
    }
}

if($agreeUpdate =='update'){
    $updateInsert='update';
}else{
    $updateInsert='insert';
}

$logQuery= "insert into ChangeSponsorLog (name, logDate, spName, updateInsert,Id) value('".$distName."',now(),'".$sponsorName."','".$updateInsert."','".$distID."')";

mysql_query($logQuery) or die("ChangeSponsorlogQuery-".mysql_error());




    if($agreeUpdate){
        $updateQuery = "update tb_change_sponsor set sponsor_agree_yn ='$agreeYn', agree_date = '$now_date'
                        where no ='$num'";

        mysql_query($updateQuery) or die("Query Error");
        echo "<script> if ( window.history.replaceState ) {
                            window.history.replaceState( null, null, window.location.href );
                        }
                        history.back();
               </script>";

    }else{
        $alert = '신청이 완료 되었습니다.';
       
    
        $qurey ="insert into tb_change_sponsor (member_no, member_name, sponsor_no, sponsor_name, address, sponsor_agree_yn, reg_status,ch_sponsor_no, ch_sponsor_name,apply_date,agree_date, entry_date,phoneNum,app_yn ) value('".$distID."','".$distName."','".$sponsorID."','".$sponsorName."','".$address."','','".$reg_status."','".$chSponsorID."','".$chSponsorName."','".$now_date."','','".$entry_date."','".$mobilePhone."','".$appYn."')";
        
        mysql_query($qurey) or die("Query Error");
    
        echo "<script>alert('$alert');history.back();</script>";
        echo "<script>parent.close();</script>";
    }
    
    
    function goPage(){
        
    }
?>

