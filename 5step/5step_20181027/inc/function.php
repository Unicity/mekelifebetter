<?php
   
	function isEmpty($value)
	{	// php < 5.4.0
		if ($value == '') {
		// php >= 5.4.0
		// if (session_status() == PHP_SESSION_NONE) {
 	   		return true;
 	   	}
 	   	return false;
	}
	function DisplayAlert($msg) {
		echo "<script language=\"javascript\">\n
					alert('$msg');
					</script>";
	}
	function moveTo($location) {
		echo "<script language=\"javascript\">\n
					location.href = '$location'  ;
					</script>";
	}
	function loginAPI(){

	}
	function postAPI($url, $data)
	{	 
		$result = "";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$response = curl_exec($ch);
		
		$result = json_decode($response, true);
	 	
	 	if (isset($result["error"]["code"])) {
	 		$result = "";
	 	}

	 	curl_close($ch);
	
		return $result;
		
	}
	function getAPI($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			 
		$response = curl_exec($ch);
		
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if (($response != false) && ($status == 200)) {
	 	
	 		$result = json_decode($response, true);
	 	}

	 	curl_close($ch);
	
		return $result;
	
	}

	function checkSessionValue() {
		if (is_session_started() === FALSE) {
        // php >= 5.4.0
       // 	if (session_status() == PHP_SESSION_NONE) {
        		session_start();
    	//	}
    	}
    	 
        if (!isset($_SESSION["username"]) || !isset($_SESSION["realname"]) || $_SESSION["username"] == '' || $_SESSION["realname"]=='') {
        	DisplayAlert('로그인 후 사용해 주십시오.');
        	moveTo('../index.html');
        }
    }
    function is_session_started()
	{
	    if ( php_sapi_name() !== 'cli' ) {
	        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
	            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
	        } else {
	            return session_id() === '' ? FALSE : TRUE;
	        }
	    }
	    return FALSE;
	}

   function check_useragent()
	{
		$isMobile = false;
		$useragent=$_SERVER['HTTP_USER_AGENT'];

		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
		{
			$isMobile=true;
		}

		if ($isMobile == false) {
			DisplayAlert('모바일폰에서 접속하세요.');
			moveTo('http://korea.unicity.com');
		}
	}
	function logging($id, $type) {
		$query = "insert into AccessLog (id, type, logdate) values ('".$id."','".$type."', now())";
			mysql_query($query) or die("Query Error");
	}

	function loggingQuery($id, $text) {
		$query = "insert into AccessLog (`id`, `type`, `logdate`, `text`) values ('".$id."','query', now(), '".$text."')";
			mysql_query($query) or die("Query Error!!".$query);
	}

	function logout()
	{	 
		session_unset();
	}

	function updateExpiredProgram($userid) {
		$query = "SELECT programID FROM ProgramMaster WHERE userID = '$userid' AND delFlag = 'N' AND now() > endDate";
		$result = mysql_query($query);
		$rows = mysql_fetch_array($result);

		if (is_array($rows) || is_object($rows)) {
			$programIDs = "(";
			foreach($rows as $row) {
	      		$programIDs .= $row.", ";
	    	}
	    	if(strlen($programIDs) > 2 ){
	    		$programIDs = substr($programIDs, 0, -2).")";

	    		$query2 = "UPDATE ProgramMaster set delFlag = 'Y' WHERE programID IN ".$programIDs;

	    		mysql_query($query2);
	    	}
    	}
    	 


	}
?>