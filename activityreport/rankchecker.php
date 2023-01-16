<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
//error_reporting(0);
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', true);

// Takes raw data from the request
$json = file_get_contents('php://input');

// Converts it into a PHP object
$dataPost = json_decode($json,true);

$baid = $dataPost['baid'];

require_once ('SimpleXLSX.php');

function searchJson( $obj, $value ) {
	foreach( $obj as $key => $item ) {
		if( !is_nan( intval( $key ) ) && is_array( $item ) ){
			if( in_array( $value, $item ) ) return $item;
		} else {
			foreach( $item as $child ) {
				if(isset($child) && $child == $value) {
					return $child;
				}
			}
		}
	}
	return null;
}

$filename = "rankchecker-json/Rankchecker.json";

if(file_exists($filename)){
	$string = file_get_contents($filename);
	$data = json_decode($string,true);
	$results = searchJson( $data , $baid);
	if($results !== null){
		echo json_encode($results);
	}else{
		echo json_encode(array('errors_msg'=>'baid not found.'));
	}
}else{
	if ( $xlsx = SimpleXLSX::parse('rankchecker-excel/Rankchecker.xlsx') ) {
		try{
			$resultfile = fopen($filename, "a+") or die("Unable to open file!");
			$txt = json_encode($xlsx->rows());
			fwrite($resultfile, $txt);
			fclose($resultfile);
			chmod($filename, 0777);
	
			$data = $xlsx->rows();
			$results = searchJson( $data , $baid );
			if($results !== null){
				echo json_encode($results);
			}else{
				echo json_encode(array('error_msg'=>'baid not found.'));
			}
		}catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	} else {
		echo SimpleXLSX::parseError();
	}
}
