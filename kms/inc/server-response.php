<?php
// Database details
  include_once "./dbconn.php";

 // Get job (and id)
$job = '';
$id  = '';
if (isset($_REQUEST['job'])){
  $job =  $_REQUEST['job'];
  if ($job == 'get_qnas' ||
      $job == 'get_qna'   ||
      $job == 'add_qna'   ||
      $job == 'edit_qna'  ||
      $job == 'delete_qna'){
    if (isset($_REQUEST['id'])){
      $id = $_REQUEST['id'];
      if (!is_numeric($id)){
        $id = '';
      }
    }
  } else {
    $job = '';
  }
}
$typeArray = array(
  "1" => "회원십",
//  "2" => "제품",
//  "3" => "수당",
//  "4" => "국제후원",
  "5" => "주문",
 // "6" => "반품/교환",
  "9" => "기타", 

  "101" => "회원가입",
  "102" => "정보변경",
  "103" => "해지",
  "104" => "갱신",
  "105" => "공동등록",
  "106" => "주부사업자 전환",
  "107" => "정보변경",
//  "107" => "후원자변경",   //107로 통합
//  "108" => "국적변경",    //107로 통합
  "109" => "상속",
//  "110" => "진정서",
//  "111" => "회원간 결혼",  //107로 통합
//  "112" => "사업자 연결",
//  "113" => "HUB 불러오기",
//  "114" => "위임장",
  "115" => "기타",
  "501" => "택배/배송",
  "502" => "주문관련",
//  "502" => "주문자 변경",  //502로 통합
//  "503" => "주문취소",    //502로 통합
  "504" => "카드관련",
  "505" => "오토십",
  "506" => "마감관련",
  "507" => "반품/교환",
//  "506" => "일일마감", //506로 통합
//  "507" => "총마감",  //506로 통합
  "508" => "기타",
  "901" => "국제후원",
//  "902" => "PD이하 핀수령",
  "903" => "정수기",
  "904" => "온라인",
  "905" => "쇼핑몰",
  "906" => "온라인판매",
  "907" => "수당관련",
  "908" => "전산",
//  "909" => "상품권 접수",
  "910" => "세무관련",
  "911" => "기타"
  );
// Prepare array
$mysql_data = array();
// Valid job found
if ($job != ''){
  // Execute job
  if ($job == 'get_qnas'){
   
    // Get companies
    $query = "SELECT * FROM tb_kms where status='1' ORDER BY id desc";
    $query = mysql_query($query);
    if (!$query){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
    
      while ($qna = mysql_fetch_array($query)){
        $functions  = '<div class="function_buttons"><ul>';
        $functions .= '<li class="function_edit"><a data-id="'   . $qna['id'] . '" data-name="' . $qna['title'] . '"><span>Edit</span></a></li>';
        $functions .= '<li class="function_delete"><a data-id="' . $qna['id'] . '" data-name="' . $qna['title'] . '"><span>Delete</span></a></li>';
        $functions .= '</ul></div>';
        $createdDate = explode(" ",$qna['timestamp']);
        $mysql_data[] = array(
          "id"          => $qna['id'],
          "type1"       => $typeArray[$qna['type1']],
          "type2"       => $typeArray[$qna['type2']],
          "title"       => $qna['title'],
          "description" => $qna['description'],
          "writer"      => $qna['writer'],
          "filename"    => $qna['filename'],
          "timestamp"   => $createdDate[0],
          "functions"   => $functions
        );
      }
    }
    
  } elseif ($job == 'get_qna'){
    
    // Get QnA
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "SELECT * FROM tb_kms WHERE id = '" . mysql_real_escape_string($id) . "'";
      $query = mysql_query($query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
        while ($qna = mysql_fetch_array($query)){
          $mysql_data[] = array(
            "id"         => $qna['id'],
            "type1"      => $qna['type1'],
            "type2"      => $qna['type2'],
            "title"      => $qna['title'],
            "description"=> $qna['description'],
            "writer"     => $qna['writer'],
            "filename"   => $qna['filename'],
            "timestamp"  => $qna['timestamp']
          );
        }
      }
    }
  
  } elseif ($job == 'add_qna'){
    
    // Add company
    $query = "INSERT INTO tb_kms SET ";
    if (isset($_POST['type1'])) { $query .= "type1 = '" . mysql_real_escape_string($_POST['type1']) . "' "; }
    if (isset($_POST['type2']))   { $query .= ", type2   = '" . mysql_real_escape_string($_POST['type2'])   . "'  "; }
    if (isset($_POST['title']))      { $query .= ", title      = '" . mysql_real_escape_string($_POST['title'])      . "' "; }
    if (isset($_POST['description']))  { $query .= ", description  = '" . mysql_real_escape_string($_POST['description'])  . "'"; }
    if (isset($_POST['writer']))    { $query .= ", writer    = '" . mysql_real_escape_string($_POST['writer']). "' "    ; }
    if (isset($_POST['files']))   { $query .= ", filename   = '" . mysql_real_escape_string($_POST['files'])   . "' "; }
    //if (isset($_GET['headquarters'])) { $query .= "headquarters = '" . mysqli_real_escape_string($db_connection, $_GET['headquarters']) . "'";   }
    
    $qrystatement= $query ;
    $query = mysql_query($query);
    if (!$query){
      $result  = 'error';
      $message = 'query error '. $qrystatement;
    } else {
      $result  = 'success';
      $message = 'query success';
    }
   
  } elseif ($job == 'edit_qna'){
    
    // Edit company
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "UPDATE tb_kms SET ";
    //  if (isset($_GET['id']))         { $query .= "id         = '" . mysql_real_escape_string($_GET['id'])         . "', "; }
      if (isset($_POST['type1'])) { $query .= "type1 = '" . mysql_real_escape_string($_POST['type1']) . "', "; }
      if (isset($_POST['type2']))   { $query .= "type2   = '" . mysql_real_escape_string($_POST['type2'])   . "', "; }
      if (isset($_POST['title']))      { $query .= "title      = '" . mysql_real_escape_string($_POST['title'])      . "', "; }
      if (isset($_POST['description']))  { $query .= "description  = '" . mysql_real_escape_string($_POST['description'])  . "', "; }
      if (isset($_POST['writer']))    { $query .= "writer    = '" . mysql_real_escape_string($_POST['writer'])    . "', "; }
      if (isset($_POST['files']))   { $query .= "filename   = '" . mysql_real_escape_string($_POST['files'])   . "', "; }
      //if (isset($_GET['headquarters'])) { $query .= "headquarters = '" . mysqli_real_escape_string($db_connection, $_GET['headquarters']) . "'";   }
      $query .= "timestamp = now() ";
      $query .= "WHERE id = '" . mysql_real_escape_string($id) . "'";
      $query  = mysql_query($query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
      }
    }
    
  } elseif ($job == 'delete_qna'){
  
    // Delete company
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "UPDATE tb_kms SET status='2' WHERE id = '" . mysql_real_escape_string($id) . "'";
      $query = mysql_query($query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
      }
    }
  
  }
  
  // Close database connection
  mysql_close($connect);
} else {
  $data = array(
    "result" => "500",
    "message" => "no job values",
    "data"    => "no job values"
    );   
}
if(is_array($mysql_data)){
  // Prepare data
  $data = array(
    "result"  => $result,
    "message" => $message,
    "data"    => $mysql_data
  );
  // Convert PHP array to JSON array
}else {
   $data = array(
    "result" => "123",
    "message" => "no values",
    "data"    => "no values"
    );   
}
$json_data = json_encode($data);
print $json_data;
?>