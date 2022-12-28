
<?php

header('Content-Type:text/html;charset=UTF-8');
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Mon,26 Jul 1997 05:00:00 GMT");
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<meta http-equiv="X-Frame-Options" content="deny" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"
                integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ"
                crossorigin="anonymous">
        </script>

		<title>비밀번호 변경</title>
		
    </head>
    <body>
        <form name="frm_m" method="post" action ="ch_sendPw.php">
           <div>
                <h1>비밀번호 변경</h1>
           </div>
           <div>
                <label>아이디:<label>
                <input type="text" id="baId" name="baId" placeholder="Distributor No.">

                <label>infokor 휴대폰 번호:<label>
                <input type="text" id="phNum" name="phNum" placeholder="Phone No.">

                <label>문자 휴대폰 번호:<label>
                <input type="text" id="textNum" name="textNum" placeholder="Text Phone No.">
                <input type="submit" value="submit">
            <div>
        </form>    
    <body>
</html>    