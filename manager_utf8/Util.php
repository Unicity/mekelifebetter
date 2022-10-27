<?
function upload($filearray, $targetdir, $max_size = 2 /* MByte */, $allowext) {
	$max_size = $max_size * 1024 * 1024;    // 바이트로 계산한다. 1MB = 1024KB = 1048576Byte   

	if (!file_exists($targetdir)) { 
		//echo "targetdir --> ".$targetdir."<br>";
		mkdir($targetdir, 0777);
		//exec("mkdir -p ".$targetdir);                # 디렉토리 만들기
	}
	
	if($filearray['size'] > $max_size){
?>
				<SCRIPT LANGUAGE="JavaScript">
				<!--
					alert('등록가능 용량초과 입니다.');
					history.back();
				//-->
				</SCRIPT>
				<?
					die;
	}else {
		//try {   // 특별한 경우를 위해 예외처리    
			/**  
			 * 이부분은 파일명의 마지막 부분을 리턴시킵니다.   
			 *   
			 * 예를 들어 test.jpeg 이면 jpeg를 가져옵니다.  
			 */  
			$file_ext = end(explode('.', $filearray['name']));   

			$file_real_name = str_replace(".".$file_ext,"",$filearray['name']);
			
			

			if (in_array(strtolower($file_ext), $allowext)) { // 확장자를 검사한다.   
			
				//공백 _ 로 대치 
				$temp_file_name = str_replace(" ","_",$filearray['name']);
				
				

				//한글 파일명 처리를 위해 임시 파일명을 날짜로 만듦
				$fn_rand = mt_rand (0, (strlen ($temp_file_name)));
				$writeday = date("YmdHis",strtotime("0 day"));
				$temp_file_name = $writeday."_".$fn_rand.".".$file_ext;

				$file_name = get_filename_check($targetdir, $temp_file_name);

				//$file_name = $file_real_name."-".mktime() . '.' . $file_ext;    
				// 중복된 파일이 업로드 될수 있으므로 time함수를 이용해 unixtime으로 파일이름을 만들어주고   
				// 그 후 파일 확장자를 붙여줍니다. 정확히는 이 방식으로는 파일업로드를 정확히 중복을 체크했다고 할수 없습니다.   
				
				$path = $targetdir . '/' . $file_name;   

				// 파일 저장 경로를 만들어 줍니다. 함수 실행시에 입력받은 경로를 이용해서 만들어 줍니다.    

				if(move_uploaded_file($filearray['tmp_name'], $path))    
				{   
					// 정상적으로 업로드 했다면 업로드된 파일명을 내보냅니다   
					// 이부분에 DB에 저장 구문을 넣어주시거나 파일명을 저장하는 부분을 넣어주시면 됩니다.    
					// 또는 리턴된 파일명으로 처리 하시면 됩니다.    
					return $file_name;
				}
				else return false;   
				// 실패 했을 경우에는 false를 출력합니다.   
			
			}else{ //return false;
				if($file_ext!=""){
				?>
				<SCRIPT LANGUAGE="JavaScript">
				<!--
					alert('등록할수 없는 확장자 입니다.');
					history.back();
				//-->
				</SCRIPT>
				<?
					die;
				}
			}
	}
}

//파일 중복 체크
function get_filename_check($filepath, $filename) { 

	if (!preg_match("'/$'", $filepath)) $filepath .= '/'; 
	if (is_file($filepath . $filename)) { 

		preg_match("'^([^.]+)(\[([0-9]+)\])(\.[^.]+)$'", $filename, $match); 

		if (empty($match)) { 

			$filename = preg_replace("`^([^.]+)(\.[^.]+)$`", "\\1[1]\\2", $filename); 
		} 
		else{ 

			$filename = $match[1] . '[' . ($match[3] + 1) . ']' . $match[4]; 
		} 

		return get_filename_check($filepath, $filename); 
	} 
	else { 

		return $filename; 
	} 
} 

// DB에 입력 하기
function SetStringToDB($str) {
	
	$temp_str = "";
	
	$temp_str = trim($str);
	$temp_str = addslashes($temp_str);

	return $temp_str; 
}

// DB에서 빼오기
function SetStringFromDB($str) {
	
	$temp_str = "";
	
	$temp_str = trim($str);
	$temp_str = stripslashes($temp_str);
	$temp_str = str_replace("\"","&quot;", $temp_str);
//	$temp_str = str_replace("\"","'", $temp_str);
	return $temp_str; 
}

?>