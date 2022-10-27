<?  
// ============= Input 변수 ================================================================= 
// strModule   : Module Name 
// strFunction : Function Name 
// strLogMsg   : Logging 할 정보 
// ========================================================================================== 
 
function TraceLog ($strLogMsg) 
{ 
//*************************************************************************** 
// Log File 지정 (해당업체에서 원하는 디렉토리를 적어준다.)
//*************************************************************************** 
  $LOG_PATH = "/data/www/docs/xpert/php/nkh_test/logs/"; 

  $ForReading = 1;
  $ForWriting = 2;
  $ForAppending = 8; 
 
 
//************************************************************************* 
// 현재 날짜를 생성 
//************************************************************************* 

 
  $iMonth = strftime("%m"); 
  $iDay = strftime("%d"); 
  $iYear = strftime("%Y"); 

  if($iMonth <10){
    $iMonth = "0".$iMonth;
  }

  if($iDay <10){
    $iDay = "0".$iDay;
  }
 

  $strLogFile = $LOG_PATH. $strModule . $iYear. $iMonth. $iDay. ".txt";

 
//************************************************************************* 
// Logging 할 Record 생성 
//************************************************************************* 
  $strRecord = ( "[" . $iYear. $iMonth. $iDay. "]"); 
  $strRecord = $strRecord. $strFunction ; 
  $strRecord = $strRecord . ": " . ($strLogMsg) . "\n"; 

 
//************************************************************************* 
// 화일에 Logging 
//************************************************************************* 
    $fp = fopen($strLogFile, "a"); 
	fwrite($fp, "$strRecord"); 
	fclose($fp); 
}
?> 

<?
	global $data;
	global $ret ;
	global $paraData;
	global $strLen ;
	global $writeData ;
	global $send ;
	global $strBool;
	
	$data = trim($data);
	
	$data = urldecode($data);

//*****************************************Header 전문*******************************************************
//0:길이,1:암호화구분,2:버전,3:Type,4:resend,5:요청일시,6:상점아이디,7:주문번호,8:주문자명,9:주민번호,10:이메일
//11:Product Type,12:상품명,13:KeyIn 여부,14:유무선구분,15:헤더뒤 전문개수,16:휴대폰번호,17:예비
//************************************************************************************************************
    $Version         		= $paraData[1];		// 0:암호안함, 1:암호화(openssl), 2:암호화(seed)
    $Type		           	= $paraData[2];		// 전문버전 : 311
    $msg_sele          	= $paraData[3];		// 전문구분
    $Resend			    	= $paraData[4];		// 재전송구분
    $RequestDate	     	= $paraData[5];		// 요청일시
    $StoreId           		= $paraData[6];		// 상점아이디
    $OrderNumber       	= $paraData[7];		// 주문번호
    $UserName			  	= $paraData[8];		// 주문자명
	$IdNum           		= $paraData[9];		// 주민번호
    $Email             		= $paraData[10];	// 이메일
	$GoodType          	= $paraData[11];	// 상품명
	$GoodName			= $paraData[12];	// 상품명
    $KeyInType		    = $paraData[13];	// keyin여부
    $LineType				= $paraData[14];	// 유무선구분
    $PhoneNo			    = $paraData[15];	// 휴대폰번호
    $ApprovalCount      = $paraData[16];		// 복합결제건수
    $HaedFiller     			= $paraData[17];	// 예비


	$paraData = explode("`", $data);
	$strLen = count($paraData);
	
	TraceLog ($data);

	
	$strBool = 0;
	$ret = "false";
//신용카드(ISP,Visa3D포함) paraData[18]=>승인구분	

//*****************************************신용카드 Data전문*************************************************
//18:승인구분,19:거래번호,20:오류구분,21:거래일자,22:거래시간,23:발급사코드,24:매입사코드,25:승인번호
//26:메시지1,27:메시지2,28:카드번호,29:유효기간,30:할부,31:금액,32:가맹점번호,33:인증여부,34:승인여부
//35:예비,36:인증구분,37:MPI인증장소,38:ISP또는 Visa3D인증정보
//************************************************************************************************************


if(substr($paraData[18],0,1) == "1" || substr($paraData[18],0,1) == "I"){

	$rApprovalType			= $paraData[18];  
    $rTransactionNo		= $paraData[19];	// 거래번호
    $rStatus					= $paraData[20];	// 상태 O : 승인, X : 거절
    $rTradeDate				= $paraData[21];	// 거래일자
    $rTradeTime				= $paraData[22];	// 거래시간
    $rIssCode					= $paraData[23];	// 발급사코드
    $rAquCode				= $paraData[24];	// 매입사코드
    $rAuthNo					= $paraData[25];	// 승인번호 or 거절시 오류코드
    $rMessage1				= $paraData[26];	// 메시지1
    $rMessage2				= $paraData[27];	// 메시지2
    $rCardNo					= $paraData[28];	// 카드번호
    $rExpDate				= $paraData[29];	// 유효기간
    $rInstallment				= $paraData[30];	// 할부
    $rAmount					= $paraData[31];	// 금액
    $rMerchantNo			= $paraData[32];	// 가맹점번호
    $rAuthSendType		= $paraData[33];	// 전송구분= new String(this.read(2));
    $rApprovalSendType  = $paraData[34];	// 전송구분(0 : 거절, 1 : 승인, 2: 원카드)
    $rPoint1					= $paraData[35];	// Point1
    $rPoint2					= $paraData[36];	// Point2
    $rPoint3					= $paraData[37];	// Point3
    $rPoint4					= $paraData[38];	// Point4
    $rVanTransactionNo  	= $paraData[39]; // Van거래번호
    $rFiller						= $paraData[40];	// 예비
    $rAuthType				= $paraData[41];	// ISP : ISP거래, MP1, MP2 : MPI거래, SPACE : 일반거래
    $rMPIPositionType		= $paraData[42];	// K : KSNET, R : Remote, C : 제3기관, SPACE : 일반거래
    $rMPIReUseType		= $paraData[43]; // Y : 재사용, N : 재사용아님
    $rEncData				= $paraData[44]; // MPI, ISP 데이터
	$ret = "true";
	}

//*****************************************가상계좌발급 Data전문*************************************************
//18:승인구분,19:거래번호,20:오류구분,21:거래일자,22:거래시간,23:은행,24:가상계좌,25:예금주
//26:메시지1,27:메시지2,28:예비
//************************************************************************************************************
	
	else if(substr($paraData[18],0,1) == "6"){		//가상계좌발급
    $rVAApprovalType		= $paraData[18];  //
    $rVATransactionNo		= $paraData[19];  // 거래번호
    $rVAStatus					= $paraData[20];  // 상태
    $rVATradeDate			= $paraData[21];  // 거래일자
    $rVATradeTime			= $paraData[22];  // 거래시간
    $rVABankCode			= $paraData[23];  // 은행코드
    $rVAVirAcctNo			= $paraData[24];  // 가상계좌번호
    $rVAName					= $paraData[25];  // 예금주
    $rVAMessage1			= $paraData[26];  // 메시지1
    $rVAMessage2			= $paraData[27];  // 메시지2
    $rVAFiller					= $paraData[28];  // 예비
	$ret = "true";
	}

//*****************************************월드패스카드 Data전문*************************************************
//18:승인구분,19:거래번호,20:오류구분,21:거래일자,22:거래시간,23:금액,24:잔액,25:한도액
//26:승인번호1,27:메시지1,28:메시지2,29:예비
//************************************************************************************************************

	else if(substr($paraData[18],0,1) == "7"){		//월드패스카드 paraData[18]=>승인구분
	$rWPApprovalType  = $paraData[18];   
	$rWPTransactionNo	 = $paraData[19];  		    
	$rWPStatus		 = $paraData[20];            
	$rWPTradeDate	 	 = $paraData[21];           
	$rWPTradeTime	 	 = $paraData[22];           
	$rWPAmount         = $paraData[23];           //  금액
	$rWPBalanceAmount  = $paraData[24];        //  잔액
	$rWPLimitAmount    = $paraData[25];          //  한도액
	$rWPAuthNo         = $paraData[26];          //  승인번호
	$rWPMessage1		 = $paraData[27];  	  //  메시지1
	$rWPMessage2		 = $paraData[28];  	  //  메시지2
	$rWPFiller		 = $paraData[29];
	$ret = "true";
	}

?> 
ret=<?echo($ret)?>
