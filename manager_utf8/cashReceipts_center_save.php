<?php
session_start();
?>

<?
include "./admin_session_check.inc";
include "./inc/global_init.inc";
include "../dbconn_utf8.inc";
include "./inc/common_function.php";

include "../AES.php";

$s_flag = str_quote_smart_session($s_flag);
$s_adm_id = str_quote_smart_session($s_adm_id);
$s_adm_dept = str_quote_smart_session($s_adm_dept);

$s_adm_dept_name = $s_adm_dept."(".$s_adm_id.")";

$flag = $_POST['flag'];
$idVal = $_POST['idVal'];
$orderNo = $_POST['orderNo'];


    $s_date = $_POST['s_date'];
    $check_result = $_POST['check_result'];
    $member_no = $_POST['member_no'];
    $member_name = $_POST['member_name'];
    $order_no = $_POST['order_no'];
    $back_no = $_POST['back_no'];
    $amount = $_POST['amount'];
    $check_text = $_POST['check_text'];
    $check_num = $_POST['check_num'];
    $approval_num = $_POST['approval_num'];
    $cancel_no = $_POST['cancel_no'];
    $cancel_reason = $_POST['cancel_reason'];
    $cash_num = $_POST['cash_num'];
    $now_date = date("Y-m-d H:i:s");
    $u_date = date("Y-m-d H:i:s");



//콤마제거
$amount  = preg_replace("/[^\d]/","",$amount);

$alert = '저장이 완료 됐습니다.';
$updateAlert = '수정이 완료 됐습니다.';
$deleteAlert = '삭제가 완료 됐습니다.';

if($flag=='save'){
    $saveQuery = "insert into tb_cashReceipts (s_date, member_no, member_name, order_no,back_no,amount,check_text,check_num,approval_num,cancel_no, check_result, cancel_reason, center, entry_date, center2) 
                    value('".$s_date."','".$member_no."','".$member_name."','".$order_no."','".$back_no."','".$amount."','".$check_text."','".$check_num."','".$approval_num."','".$cancel_no."','".$check_result."','".$cancel_reason."','".$s_adm_dept."','".$now_date."','".$s_adm_dept_name."')";
                    echo $saveQuery;
                mysql_query($saveQuery) or die("saveError".mysql_error());
                echo "<script>alert('$alert');
                history.go(-2);</script>";

}else if($flag=='update'){
    $updateQuery = "update tb_cashReceipts set s_date ='$s_date',
                                                check_result='$check_result',
                                                member_no='$member_no',
                                                member_name='$member_name',
                                                order_no='$order_no',
                                                back_no='$back_no',
                                                amount='$amount',
                                                check_text='$check_text',
                                                check_num='$check_num',
                                                approval_num='$approval_num',
                                                cancel_no='$cancel_no',
                                                cancel_reason='$cancel_reason',
                                                u_date = '$u_date'
                                            where member_no='$member_no'
                                            and order_no='$order_no'
                                            and cash_num = '$cash_num'";
echo $updateQuery;
                                           mysql_query($updateQuery) or die("updateError".mysql_error());
                                            echo "<script>alert('$updateAlert');
                                            history.go(-2);</script>";
                            
}else if($flag=='delete'){
    $deleteQuery="delete from tb_cashReceipts where member_no='$member_no' and order_no='$order_no' and cash_num = '$cash_num'";

echo $deleteQuery;

    mysql_query($deleteQuery) or die("deleteError".mysql_error());
    echo "<script>alert('$deleteAlert');
    history.go(-2);</script>";
}

?>
<?
mysql_close($connect);
?>