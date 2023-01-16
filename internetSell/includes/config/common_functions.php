<?php 
	function Redirect($url)
	{
    	header('Location: ' . $url);
    	exit();
	}

	function cert_validation()
	{	// php < 5.4.0
		if (session_id() == '') {
		// php >= 5.4.0
		// if (session_status() == PHP_SESSION_NONE) {
 	   		session_start();
		}
		 
		if (!(isset($_SESSION["S_BIRTH"])) || !(isset($_SESSION["S_GENDER"]))  || !(isset($_SESSION["S_NM"])) || ($_SESSION["S_BIRTH"] == "") || ($_SESSION["S_GENDER"]== "")  ||  ($_SESSION["S_NM"]== "") )  {
			
		}
	}


?>