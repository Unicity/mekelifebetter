<?php 
    header("Content-Type: text/html; charset=UTF-8");
	
	include_once("../inc/function.php");

	if (!include_once("./dbconn.php")){
		echo "The config file could not be loaded";
	}
	


	$max_file_size = 1024*1024*20; //20mb
	$valid_exts = array('jpeg', 'jpg', 'png', 'gif');

	$title = $_POST['title'];
	$description = $_POST['description'];
	$username = $_POST['username'];
	$myfile = $_FILES['myfile'];

	$type = $_POST['type'];
	$ext = "";
	if(isset($myfile) && is_uploaded_file($myfile['tmp_name'])) {
		if($myfile['size'] < $max_file_size) {
			$ext = pathinfo($myfile['name'], PATHINFO_EXTENSION);
			if(!in_array($ext, $valid_exts)){
				DisplayAlert("해당 파일 타입을 지원하지 않습니다.");
				goBack();
				exit();
			}
		} else {
			DisplayAlert("파일사이즈가 너무 큽니다.(20Mb이하)");
			goBack();
			exit();
		}
	}
	 
	if($type == "add") {

		$query ="INSERT INTO Board (`title`, `description`, `filename`, `writer`) value ('".$title."', '".$description."', '".$ext."', '".$username."')";
		$result = mysql_query($query);

		$newId = mysql_insert_id();
	 	
	 	if($ext !== "") {
 			resizeImage($myfile, $newId, $ext );
 		}

 		DisplayAlert("저장됐습니다.");
 		moveTo("../pages/community_list.php");

	} else if ($type == 'edit'){
		$id = $_POST['id'];
		echo $ext;
		if ($ext != ""){
			$filenameQuery = ", `filename`= '".$ext."'";
		}
		$query = "UPDATE Board set `title`='".$title."', `description`='".$description."' ".$filenameQuery.", lastModifyDate=now() where id=".$id ;
		$result = mysql_query($query);
		$affectedRow = mysql_affected_rows();
		echo "ext"+$ext;
		if($ext != "") {
			echo "file upload";
 			resizeImage($myfile, $id, $ext );
 		}
 		

		if ($affectedRow ==1) {
			DisplayAlert("저장됐습니다.");
 			moveTo("../pages/community_list.php");
		} else {
			DisplayAlert("잘못된 접속입니다.");
			goBack();
		}

	} else {
		DisplayAlert("잘못된 접속입니다.");
		goBack();

	}

	function resizeImage($file, $id, $ext) {
		$size = getimagesize($file['tmp_name']);

		$ratio = $size[0]/$size[1]; // width / height
		 
		if($ratio>1) {
			$width = 500;
			$height = 500/$ratio;
		} else {
			$width = 500*$ratio;
			$height = 500;
		}
		
		$path = "../uploads/".$id.".".$ext;
		
		$imgString = file_get_contents($file['tmp_name']);
	
    	$image = imagecreatefromstring($imgString);

		$image_p = imagecreatetruecolor($width, $height);

    	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
    
    	switch ($file['type']) {
		    case 'image/jpeg':
		      imagejpeg($image_p, $path, 100);
		      break;
		    case 'image/png':
		      imagepng($image_p, $path, 9);
		      break;
		    case 'image/gif':
		      imagegif($image_p, $path);
		      break;
		    default:
		      exit;
		      break;
		  }
		return $path;
		  /* cleanup memory */
		imagedestroy($image);
		imagedestroy($image_p);	
   	 
	// 	print_r(error_get_last());

   }

?>