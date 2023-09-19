<?
    include "./admin_session_check.inc";
    include "./inc/global_init.inc";
    include "../dbconn_utf8.inc";
    include "./inc/common_function.php";
    include "../AES.php";

    $approveNum = $_POST['approveNum'];
    $orderNum = $_POST['orderNum'];
    $ids = $_POST['ids'];

    $deleteQuery = "delete from card_change where approve_num = $approveNum  and order_num = $orderNum and ids='$ids'";

    mysql_query($deleteQuery) or die("deleteQuery error");

    $okVal="deleteOK";
	echo(json_encode(array("okVal"=>$okVal)));
?>