<?
    include "./includes/dbconn.php";
    include "../AES.php";
   
    header('Content-Type:text/html;charset=UTF-8');
    header('Cache-Control: no-cache, no-store, must-revalidate'); 
    header('Pragma: no-cache');
    header('Expires: 0');

    if(!isset($_SERVER["HTTPS"])) {
        header('Location: https://'.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']);
        exit;
    }

    $bankCode = $_POST['bankCode'];
    $newAccountNo = $_POST['newAccountNo'];
    $newAccountHolder = $_POST['newAccountHolder'];
    $deleteVal = $_POST['deleteVal'];
    $memId = $_POST['memId'];

    $newAccountNo = encrypt($key, $iv, $newAccountNo); 
    $updateQuery="update unclaimedCommission set 
                    newBankCode = '$bankCode', 
                    newAccountNo= '$newAccountNo', 
                    newAccountHolder='$newAccountHolder',
                    status=20
                    where No in ($deleteVal)
                    and id=$memId";
echo  $updateQuery;

mysql_query($updateQuery) or die("Query Error");

$alert = '신청이 완료 되었습니다.';

echo "<script>alert('$alert');history.back();</script>";


?>