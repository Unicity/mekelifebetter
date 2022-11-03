<?
ini_set('memory_limit',-1);
ini_set('max_execution_time', 60);

if (!include_once("./includes/dbconn.php")){
	echo "The config file could not be loaded";
}
include "./includes/AES.php";

//------------------ 계좌번호 디코드 Start
echo decrypt($key, $iv, "CiynZ5URGeD+8jTEgnut2A==");
echo "<br />";
exit;
//------------------ 계좌번호 디코드 End


include "../_classes/com/util/PHPExcel-1.8/Classes/PHPExcel.php";

$objPHPExcel = new PHPExcel();

// 엑셀 데이터를 담을 배열을 선언한다.
$allData = array();

$filename = "191217.xls";

// 파일의 저장형식이 utf-8일 경우 한글파일 이름은 깨지므로 euc-kr로 변환해준다.
//$filename = iconv("UTF-8", "EUC-KR", $_FILES['excelFile']['name']);
$filename = iconv("UTF-8", "EUC-KR", $filename);

try {

	// 업로드한 PHP 파일을 읽어온다.
	$objPHPExcel = PHPExcel_IOFactory::load($filename);
	$sheetsCount = $objPHPExcel -> getSheetCount();

	// 시트Sheet별로 읽기
	for($sheet = 0; $sheet < $sheetsCount; $sheet++) {

		  $objPHPExcel -> setActiveSheetIndex($sheet);
		  $activesheet = $objPHPExcel -> getActiveSheet();
		  $highestRow = $activesheet -> getHighestRow();             // 마지막 행
		  $highestColumn = $activesheet -> getHighestColumn();    // 마지막 컬럼

		  // 한줄읽기
		  for($row = 1; $row <= $highestRow; $row++) {

			// $rowData가 한줄의 데이터를 셀별로 배열처리 된다.
			$rowData = $activesheet -> rangeToArray("A" . $row . ":" . $highestColumn . $row, NULL, TRUE, FALSE);

			// $rowData에 들어가는 값은 계속 초기화 되기때문에 값을 담을 새로운 배열을 선안하고 담는다.
			$allData[$row] = $rowData[0];
		  }
	}
} catch(exception $exception) {
	echo $exception;
}

//echo "<pre>";
//print_r($allData);
//echo "</pre>";
//
//exit;

//for ($i = 1; $i < count($allData); $i++) {
for ($i = 250; $i < 307; $i++) {
//for ($i = 50; $i < 100; $i++) {
//for ($i = 100; $i < 150; $i++) {
//for ($i = 250; $i < 307; $i++) {
		
		$distID = $allData[$i][0];
		$distDATE = $allData[$i][1];
		$distNAME = $allData[$i][2];
		$BANK_NAME = $allData[$i][3];
		$JUMIN = $allData[$i][4];

//		$sqlstr = "SELECT * FROM tb_code where parent='bank3' and name = '".$BANK_NAME."'"; 
//
//		$result = mysql_query($sqlstr);
//		$list = @mysql_fetch_array($result);

//		echo $list['code'];
//		echo "<br />";


//----------------- Hydra에서 회원정보 가져오기
//		$url = 'https://hydra.unicity.net/v5/customers?unicity='.$distID.'&expand=customer';
//		$username = 'krWebEnrollment';
//		$password = 'qKZ95XCrpzth5MgCpzMGQwXHw7ZMHg';
//
//		$ch = curl_init();
//		curl_setopt($ch, CURLOPT_URL, $url);
//		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
//		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
//		
//		$response = curl_exec($ch);
//		
//		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//
//		curl_close($ch);
//
//		$response = json_decode($response, true);
//		
//		//echo "distID :: ".$distID;
//		//echo "<br />";
//		//echo "unicity :: ".$response['items'][0]['unicity'];
//		//echo "<br />";
//		//echo "fullName :: ".$response['items'][0]['humanName']['fullName@ko'];
//		//echo "<br />";
//		//echo "모바일번호 :: ".$response['items'][0]['mobilePhone'];
//		//echo "<br />";
//		//echo "--------------------------------------";
//		//echo "<br />";
//
//		
//		//echo $response['items'][0]['humanName']['fullName@ko'];
//		echo $response['items'][0]['mobilePhone'];
//		echo "<br />";


//		{"items":[{"unicity":218142082,"depositBankAccount":null,"mainAddress":{"address1":"�곸꽌濡� 2918","address2":"112��604��","city":"異섏쿇��","state":"媛뺤썝","zip":"24224","country":"KR"},"humanName":{"firstName":"","lastName":"Jungyuenhee","fullName":"Jungyuenhee","fullName@ko":"�뺤뿰��"},"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c","id":{"unicity":"218142082"},"sponsoredCustomers":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c\/sponsoredCustomers"},"metricsProfile":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c\/metricsProfile"},"metricsProfileHistory":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c\/metricsProfileHistory"},"profilePicture":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c\/profilePicture"},"achievementsHistory":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c\/achievementsHistory"},"cumulativeMetricsProfile":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c\/cumulativeMetricsProfile"},"orders":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c\/orders"},"statements":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c\/statements"},"draftBankAccounts":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c\/draftBankAccounts"},"customerSite":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c\/customerSite"},"loginAssociations":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/8b4f2508494aa45f4fa7414cae6db57d7f4dde206745a214f9a18b1741eb1a8c\/loginassociations"},"birthDate":"1965-05-25","joinDate":"2019-12-02T12:00:00-06:00","status":"Active","type":"Associate","email":"1127jyh@hanmail.net","enroller":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/98e9f74ab0664a47efbbbff8b5365fd67784abe7eb8b52050192d0c3cf9c6bdf","id":{"unicity":216608282}},"gender":"female","homePhone":"01059208834","mobilePhone":"01059208834","subscriptions":[{"type":"Franchise","startDate":"2019-12-02","endDate":"2020-12-02"}],"sponsor":{"href":"https:\/\/hydra.unicity.net\/v5\/customers\/98e9f74ab0664a47efbbbff8b5365fd67784abe7eb8b52050192d0c3cf9c6bdf","id":{"unicity":216608282}},"taxTerms":{"taxId":"650525�뺤뿰��"},"businessEntity":null,"rights":[]}]}



		
		$sqlstr = "SELECT * FROM tb_check_log where check_kind = 'B' and name='".$distNAME."' and data1 ='".$BANK_NAME."' and chkdate like '".$distDATE."%'"; 

		$result = mysql_query($sqlstr);
		$list = @mysql_fetch_array($result);

		echo "이름 :: ".$list['name'];
		echo "<br />";
		echo "생년월일 :: ".$list['jumin1'];
		echo "<br />";
		echo "은행명 :: ".$list['data1'];
		echo "<br />";
//		echo "계좌번호 :: ".decrypt($key, $iv, $list['data2']);
//		echo "<br />";
//		echo "-------------------------------------";
//		echo "<br />";

		echo decrypt($key, $iv, $list['data2']);
		echo "<br />";
		echo "-------------------------------------";
		echo "<br />";

}

//exit;
//		$b = 0;
//		
//		$sqlstr = "SELECT * FROM tb_check_log where check_no='".$parent."' and code = '".$code."'"; 
//
//		$result = mysql_query($sqlstr);
//		$list = @mysql_fetch_array($result);
//
//		$b = $list[name];
//
//		echo $i."번째 value 값 :".$_GET["gocoder"][$i]."<Br>";

//echo "<pre>";
//print_r($allData[1]);
//echo "</pre>";

?>
