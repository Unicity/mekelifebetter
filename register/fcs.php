<?php 
/*
테스트 : https://cmsarstest.ksnet.co.kr/ksnet/auth/account
운영 : https://cmsars.ksnet.co.kr/ksnet/auth/account

method : POST

변수명		길이		필수여부		설명	비고
fcs_cd   	8		필수			계좌인증 업체코드	
bank_cd  	3		필수			계좌 은행	
acct_no  	16					계좌번호	
acct_nm  	22					예금주명	계좌번호없이 실명인증(실명+생년월일) 방식을 사용할 경우에만 세팅
id_no		13					신원확인번호	생년월일 6자리(개인:YYMMDD), 사업자번호 10자리(사업자/법인)
amount   	13					금액	가상계좌 조회시 사용 (외환, 농협, 우리, 씨티)
seq_no   	6		필수			일련번호	

******** 계좌인증시는 acct_nm, amount 하기 예시의 auth_type 은 전송안해도 됨 2021-02-24 *****************************************

요청예시 :
JSONData={"auth_key":"LG8eOBVHeEQkO01DSvTb","reqdata":[
        {"amount":"0","bank_cd":"011","fcs_cd":"FCS90000","seq_no":"000001","auth_type":"99","acct_no":"36702017327","acct_nm":"홍길동","id_no":"661107"}
    ]
}

응답 :
변수명		타입		길이		설명
NAME		문자열	22		조회된 계좌주
REPLY		문자열	4		응답코드
REPLY_MSG	문자열	20		응답코드 내용

응답코드	내용							비 고
0000	정상처리						정상조회
0001	전문형식불일치				전문레이아웃 오류
0007	은행코드오류					출금or입금은행코드 오류
0009	미처리*						결제원 타임아웃                                
0111	이체일자오류					날짜오류
0115	CMS계좌						CMS계좌 처리불가
0116	주민번호 또는 사업자번호 상이	당행 조회일경우 발생
0122	입금계좌오류					조회대상계좌오류
0123	입금계좌입금한도초과			거래금액오류(예금계좌 외 계좌일경우 - 적금계좌등..)
0124	입금계좌거래제한				해당은행오류응답(타행환거래불가,CMS계좌,등...)
0126	계좌상태 오류					조회대상계좌오류
0131	출금계좌 미등록				미등록된업체모계좌
0132	출금계좌 오류					업체모계좌오류
0145	상대은행 장애*				조회대상은행장애
0146	결제원 장애*					금결원장애
0197	사업자인증 불가은행			KSNET 계약은행이 아닌 은행의 사업자인증 요청시
0198	요청성명과 상이				요청전문 성명비교 사용시 불일치일경우
0199	기타오류						기타오류
A002	인증실패						성명,주민번호 불일치
A003	자료없음               		한신평자료 없음
A004	조회실패						성명조회 실패
A005	주민번호오류					주민번호 오류
A009	비정상데이타					주민번호 non setting시
A032	한신평통신장애*				한신평쪽 HTTP 통신장애
A034	통신중장애발생*				KSNET <-> 한신평간 통신장애 발생
A050	정보도용차단중				사용자가 한신평 정보도용차단 요청상태
A098	응답시간 TIMEOUT*				응답 타임아웃 초과
A099	기타오류						기타오류


테스트인증키  FCS90000 LG8eOBVHeEQkO01DSvT
테스트계좌
111333555/카카오뱅크(090)/541218/테스트이름
3021086804181/농협(011)/870722/에효사
*/
header('Content-Type: text/html; charset=UTF-8');

/*
업체코드 : FCS04979
인증키 ; Te11Cd229djKejdmqqdA

*/

//$api_url = "https://cmsarstest.ksnet.co.kr/ksnet/auth/account"; //테스트
$api_url = "https://cmsars.ksnet.co.kr/ksnet/auth/account";  //운영

$auth_key = "Te11Cd229djKejdmqqdA";
$fcs_cd = "FCS04979";

$bank_cd = "004";
$acct_no = "28220104154421";
$id_no = "690113";
$seq_no = rand(111111,999999);   //6자리;

$JSONData = 'JSONData={"auth_key":"'.$auth_key.'","reqdata":[{"fcs_cd":"'.$fcs_cd.'","bank_cd":"'.$bank_cd.'","acct_no":"'.$acct_no.'","id_no":"'.$id_no.'","seq_no":"'.$seq_no.'"}]}';

echo "Send Data : ".$JSONData."<br>";

list($mtime,$time) = explode(" ",microtime());
$start_time = $time + $mtime;

$ch = curl_init($api_url); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $JSONData); 
curl_setopt($ch, CURLOPT_POST, true); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
$reponse = curl_exec($ch); 
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch); 
$result = json_decode($reponse);


list($mtime,$time) = explode(" ",microtime());
$end_time = $time + $mtime;

$duration = $end_time - $start_time;

echo "<br>";
echo "Return Data : <br>";
echo "name:".$result->name."<br>";
echo "reply:".$result->reply."<br>";
echo "reply_msg:".$result->reply_msg."<br>";
echo "<br>";
echo "HTTP CODE:".$status."<br>";
echo "Response Data:".$reponse;
echo "<br>";
echo "Process Time:".$duration."<br>";

?>