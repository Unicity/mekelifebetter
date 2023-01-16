<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$dir = "activity-excel/";

$listFile = array();
// Open a directory, and read its contents
if (is_dir($dir)){
  if ($dh = opendir($dir)){
    while (($file = readdir($dh)) !== false){
      //echo "filename:" . $file . "<br>";
      $list = explode('Activity-Report-',$file);
      if(count($list) >= 2){
         // echo str_replace("-"," ",substr($list[1],0,-5)). "<br>";
         //$listVal = str_replace("-"," ",substr($list[1],0,-5));
         $listVal = substr($list[1],0,-5);
         array_push($listFile, $listVal);
      }
    }
    closedir($dh);
  }
}

echo json_encode(array_reverse($listFile));
?>
