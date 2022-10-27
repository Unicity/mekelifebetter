<?
/*******************************************************************
 * Postman - 이메일 외부 DB 회원연동 Gateway file (Ver 1.0)
 *
 * 최종수정일 : 2005.05.23
 *
 * by postman (postman@amail.co.kr)
 *
 * 초기설정변수, 데이터베이스 접속 설정 및 
 * 실행 쿼리와 쿼리에서 값을 불러오는 부분을 해당 사이트에 맞게
 * 수정해 주세요.
 *******************************************************************/

	/*******************************************************************
	 * 초기설정변수 (반드시 먼저 설정해 주세요.)
	 *******************************************************************/
	$USER_ID = "moma";			// 포스트맨 사용자 아이디
	$CONNECTION_KEY = "S7DEHY-CT2SK7-X9MBPX-5577DV";	// 포스트맨 연동키, 키 발급방법은 포스트맨 사이트(http://www.postman.co.kr)를 참조하세요.

	// 데이터베이스 설정변수
	$DB_SERVER = "localhost";	// 데이터베이스 서버 호스트명
	$USERNAME = "unicity_db_user";		// 데이터베이스 사용자명
	$PASSWORD = "unicity!2011";		// 데이터베이스 패스워드
	$DB_NAME = "makelifebetter";		// 데이터베이스명

	
	$user_id = $_REQUEST['user_id'];
	$connection_key = $_REQUEST['connection_key'];
	$exe_code = $_REQUEST['exe_code'];
	
	/*******************************************************************
	 * 연동 처리 (수정하지 마세요)
	 *******************************************************************/

	// 초기설정 체크
	if (!$USER_ID || $USER_ID == "" || !$CONNECTION_KEY || $CONNECTION_KEY == "") {
		echo("[Error] Configuration variables are not initialized.");
		exit;
	}

	// 파라미터 체크
	if ($USER_ID != $user_id) {
		echo("[Error] Incorrect access!");
		exit;
	}
	
	if ($CONNECTION_KEY != $connection_key) {
		echo("[Error] Incorrect access!");
		exit;
	}

	if ($exe_code == "group") {
		getGroupList();
	} else if ($exe_code == "member") {
		getMemberList();
	} else if ($exe_code == "count") {
		getTargetCount();
	} else {
		echo("[Error] Incorrect access!");
	}

	/*******************************************************************
	 * functions 
	 * (MySql 용으로 제작되었습니다. 해당 DB에 맞게 변경해주세요.)
	 *******************************************************************/

	function dbconn() {
		global $DB_SERVER, $USERNAME, $PASSWORD, $DB_NAME;

		if (!($dbconn = @mysql_connect($DB_SERVER, $USERNAME, $PASSWORD))) {
			echo("Database connect Failed.");
			exit;
		}

		mysql_select_db($DB_NAME, $dbconn);
		return $dbconn;
	}

	function getTargetCount() {
		$dbconn = dbconn();
		
		//테이블 정의에 맞게 쿼리를 수정해주세요.
		$query = "select count(*) from tb_useroff where ifnull(email_send_yn,'N')='N' and ifnull(del_tf,'N')='N'";

		$result = mysql_query($query, $dbconn);
		echo(mysql_result($result, 0, 0));

		if ($dbconn) {
			mysql_close($dbconn);
		}
	}

	function getMemberList() {
		$dbconn = dbconn();

		//테이블 정의에 맞게 쿼리를 수정해주세요.
		$query = " SELECT mno, name, email, reg_num FROM tb_useroff WHERE ifnull(email_send_yn,'N')='N' and ifnull(del_tf,'N')='N'";

		$result = mysql_query($query, $dbconn);
		echo("<TABLE BORDER=1>\n");
		echo("<TR><TH>아이디</TH><TH>이름</TH><TH>이메일</TH><TH>고객정보1</TH><TH>고객정보2</TH><TH>고객정보3</TH><TH>고객정보4</TH><TH>고객정보5</TH></TR>\n");
		
		//위 쿼리에서 불러온 정보들을 출력해 주세요.
		while ($row = mysql_fetch_array($result)) {

			$fixNum = sprintf("%010d",$row['mno']);
			$cardNum = $row[reg_num].$fixNum;
			$enNum = base64_encode($cardNum);

			echo("<TR>");
			echo("<TD>".$row["mno"]."</TD>");	//아이디
			echo("<TD>".$row["name"]."</TD>");		//이름
			echo("<TD>".$row["email"]."</TD>");		//이메일
			echo("<TD>".$row["reg_num"]."</TD>");	//고객정보1
			echo("<TD>".$cardNum."</TD>");			//고객정보2
			echo("<TD>".$enNum."</TD>");			//고객정보3
			echo("<TD></TD>");						//고객정보4
			echo("<TD></TD>");						//고객정보5
			echo("</TR>\n");
		}
		echo("</TABLE>");

		if ($dbconn) {
			mysql_close($dbconn);
		}
	}

?>
