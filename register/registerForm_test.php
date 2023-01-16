<?php
set_time_limit(0); 
session_start();
header('Content-Type: text/html; charset=UTF-8');

if(!isset($_SERVER["HTTPS"])) {
	header('Location: https://'.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']);
	exit;
}
if (!include_once("./includes/dbconfig.php")){
	echo "The config file could not be loaded";
}
//세션아이디
if($_SESSION['ssid'] == "") $_SESSION['ssid'] = session_id().time();

include $_SERVER['DOCUMENT_ROOT']."/register/includes/common_functions.php";
$user_device = mobile_check();  // return P or M


$flag = $_POST['flag'];

$k_name = $_POST['k_name'];
$k_email = $_POST['k_email'];
$k_id = $_POST['k_id'];
$k_phone_number = $_POST['k_phone_number'];
$sponsor_id = $_POST['sponsor_id'];
$sponsor_name = $_POST['sponsor_name'];
$k_memberType = $_POST['memberType'];
$k_birthday = $_POST['k_birthday'];
$k_gender = $_POST['k_gender'];
$flagk = $_POST['flagk'];
$p_sponsorID = $_GET['pSponsorID'];
$pflag = $_GET['pflag'];
$direct = $_GET['direct'];

if($flag == '' || $flag != 'kakao'){
	//cert_validation();
}

$_SESSION["S_FLAG"] = $flag;


if($_SESSION["S_FLAG"] =='kakao'){

	$k_name = preg_replace("/[^가-힣]/u", "", $k_name);  //u가 없으면 4byte문자 처리시 에러

	$_email_arr = explode('@', $k_email);
	$_email_prefix = $_email_arr[0];
	$_email_suffix = $_email_arr[1];
	
	if($flagk == 'kakaok'){
		$memberType = $_SESSION["S_K_MEMBERTYPE"];
		$type = $_SESSION["S_K_MEMBERTYPE"];
	}else{	
		$type = $k_memberType;
	}


	if($k_gender == 'male'){
		$k_gender = 1;
	}else if ($k_gender=='female'){
		$k_gender = 0;
	}

	$_SESSION["S_BIRTH"] = $k_birthday;
	$_SESSION["S_GENDER"] = $k_gender;
	$_SESSION["S_NM"] = $k_name;
	$_SESSION["S_MOBILE_NO"] = $k_phone_number;
	//$_SESSION["k_id"] = $k_id;
	$_SESSION[S_AUTH_TYPE] = 'K';
	


	if(strlen($k_phone_number) == "10"){
		$tel1 = substr($_SESSION["S_MOBILE_NO"], 0, 3);
		$tel2 = substr($_SESSION["S_MOBILE_NO"], 3, 3);
		$tel3 = substr($_SESSION["S_MOBILE_NO"], 6, 4);
	}else{
		$tel1 = substr($_SESSION["S_MOBILE_NO"], 0, 3);
		$tel2 = substr($_SESSION["S_MOBILE_NO"], 3, 4);
		$tel3 = substr($_SESSION["S_MOBILE_NO"], 7, 4);
	}

	$dob = $_SESSION["S_BIRTH"];

	
}else{

	//print_R($_SESSION);


	//$sName = $_SESSION['S_NM'];
	if(strlen($_SESSION['S_MOBILE_NO']) == "10"){
		$tel1 = substr($_SESSION['S_MOBILE_NO'], 0, 3);
		$tel2 = substr($_SESSION['S_MOBILE_NO'], 3, 3);
		$tel3 = substr($_SESSION['S_MOBILE_NO'], 6, 4);
	}else{
		$tel1 = substr($_SESSION['S_MOBILE_NO'], 0, 3);
		$tel2 = substr($_SESSION['S_MOBILE_NO'], 3, 4);
		$tel3 = substr($_SESSION['S_MOBILE_NO'], 7, 4);
	}

	$type = isset($_GET["memberType"])? $_GET["memberType"] :  "D";
	$dob = $_SESSION['S_BIRTH'];

	

}

//$dob = $_SESSION['S_BIRTH'];
//$type = isset($_GET["memberType"])? $_GET["memberType"] :  "D";

$chk = addParamRule($name, "kr");

if ($chk != 1){
	echo "<script>
	alert('외국인 회원가입 또는 국제후원가입은 Call Center로 문의 하시기 바랍니다. (1577-8269)');
	window.location.href='https://www.makelifebetter.co.kr/register/certification.php';
	</script>";
	exit;
}

//영문이름 안내메세지 추가 - 20210616 이성수부장님 메일 요청
$str1 = strtoupper(mb_substr($name, 0, 1, 'utf-8'));
if($str1 >= 'A' && $str1 <= 'Z'){
	echo "<script>
	alert('외국인 회원가입 또는 국제후원가입은 Call Center로 문의 하시기 바랍니다. (1577-8269)');
	window.location.href='https://www.makelifebetter.co.kr/register/certification.php';
	</script>";
	exit;
}

$birthday = date("Ymd", strtotime($dob));
$today = date('Ymd');
$age = floor(($today - $birthday) /10000);
if ($age < 19) {
	echo "<script> alert('미성년자인 경우, 회원가입이 불가합니다.');
	window.location.href='https://www.makelifebetter.co.kr/register/certification.php';
	</script>";
	exit;
}

include "./includes/top.php";
/*
if(strlen($_SESSION['S_MOBILE_NO']) == "10"){
	$tel1 = substr($_SESSION['S_MOBILE_NO'], 0, 3);
	$tel2 = substr($_SESSION['S_MOBILE_NO'], 3, 3);
	$tel3 = substr($_SESSION['S_MOBILE_NO'], 6, 4);
}else{
	$tel1 = substr($_SESSION['S_MOBILE_NO'], 0, 3);
	$tel2 = substr($_SESSION['S_MOBILE_NO'], 3, 4);
	$tel3 = substr($_SESSION['S_MOBILE_NO'], 7, 4);
}
*/
?>
<script src="https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript">
	function closeDiv(id){
		document.getElementById(id).style.display = "none";
	}

	function closeDaumPostcode() {
		document.getElementById('Postlayer').style.display = 'none';
	}
	function sample2_execDaumPostcode() {
        new daum.Postcode({
            oncomplete: function(data) {

                var fullAddr = data.address; // 최종 주소 변수
                var extraAddr = ''; // 조합형 주소 변수

                if(data.userSelectedType === 'R'){
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
					if(extraAddr !== '') extraAddr = '('+ extraAddr +')';
                    fullAddr += (extraAddr !== '' ? ' '+ extraAddr : '');
                } else{
                	fullAddr = data.jibunAddress;
                }

                document.getElementById('zip').value = data.zonecode; //5자리 새우편번호 사용
                document.getElementById('fulladdress').value = fullAddr;

             	var streetNo = data.address.substr(data.address.indexOf(data.roadname)+data.roadname.length);

				var new_addr = fullAddr.substr(fullAddr.indexOf(data.roadname)+data.roadname.length);
				new_addr = new_addr.replace(',','').replace('(','').replace(')','');
				new_addr =  data.roadname+new_addr;

				document.getElementById('state').value = data.sido;
             	document.getElementById('city').value = data.sigungu;

				//상세주소 포함해서 전송하도록 요청 2021-01-05 이정윤대리 -> address2 +  (동,건물명) 형태로 Api전송요청 2021-01-06 이성수부장님
             	document.getElementById('address1').value = data.roadname + streetNo;
				//document.getElementById('address1').value = new_addr;
				document.getElementById('address1_sub').value = extraAddr;


                document.getElementById('detailaddress').focus();
                //document.getElementById('sample2_addressEnglish').value = data.addressEnglish;

                // iframe을 넣은 element를 안보이게 한다.
                // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
                document.getElementById('Postlayer').style.display = 'none';

				console.log(data);
            },
            width: '400px',
			height: '560px'
        }).embed(document.getElementById('Postlayer'));

        // iframe을 넣은 element를 보이게 한다.
         document.getElementById('Postlayer').style.display = 'block';

        // iframe을 넣은 element의 위치를 화면의 가운데로 이동시킨다.
        initLayerPosition();
    }

    // 브라우저의 크기 변경에 따라 레이어를 가운데로 이동시키고자 하실때에는
    // resize이벤트나, orientationchange이벤트를 이용하여 값이 변경될때마다 아래 함수를 실행 시켜 주시거나,
    // 직접 element_layer의 top,left값을 수정해 주시면 됩니다.
    function initLayerPosition(){
        var width = 400; //우편번호서비스가 들어갈 element의 width
        var height = 560; //우편번호서비스가 들어갈 element의 height
        var borderWidth = 1; //샘플에서 사용하는 border의 두께

        // 위에서 선언한 값들을 실제 element에 넣는다.
        document.getElementById('Postlayer').style.width = width + 'px';
        document.getElementById('Postlayer').style.height = height + 'px';
        document.getElementById('Postlayer').style.border = borderWidth + 'px solid';
        // 실행되는 순간의 화면 너비와 높이 값을 가져와서 중앙에 뜰 수 있도록 위치를 계산한다.
        document.getElementById('Postlayer').style.left = (((window.innerWidth || document.documentElement.clientWidth) - width)/2 - borderWidth) + 'px';
        document.getElementById('Postlayer').style.top = (((window.innerHeight || document.documentElement.clientHeight) - height)/2 - borderWidth) + 'px';

		//ie11
		$('#Postlayer').find('div').css('position','');
	
    }

	$(window).resize(function(e){
		initLayerPosition();
	});

	function registerLog(str){
		alert(str);
		
		//hideMask();
		$("#popup_mask_layer").remove(); 
		$("#popupDiv").css("display","none");
		$("body").css("overflow","auto");//스크롤바 복원
		
		return false;
	}

	function registerDone(mno,pw){
	

		var kakaoFlag = '<?php echo $_SESSION["S_FLAG"]?>';
		var kakaoId = '<?php echo $k_id?>';
	
		if(kakaoFlag=='kakao' && mno !='dup'){

			//kakoUpdate();

			
			var mnoVal = mno;
			var passVal = pw;
			var data =  btoa(JSON.stringify({ username: mnoVal, password: passVal }));
		

			$.ajax({
				'type':'GET',
				'crossOrigin': true,
				'headers' : {
               	 	'Content-Type' : 'application/json'
            	},
				'url':'https://member-calls2-kr.unicity.com/authorization-v2/createUserSocial?accessToken=""&refreshToken=""&socialId='+kakaoId+'&userId='+mnoVal+'&user='+data,
				'dataType' : 'json',
				'success':function (result) {
	
				
				location.replace("complete.php?isdup=N&no="+mno);
					

				},
				'error':function (result) {
				
				}
			});
		}else if(mno == "dup"){

			location.replace("complete.php?isdup=Y");
		}else{

			location.replace("complete.php?isdup=N&no="+mno);
		}
/*
		if(mno == "dup"){
			location.replace("complete.php?isdup=Y");
		}else{
			location.replace("complete.php?isdup=N&no="+mno);
		}
		*/
	}


	function nc(){
		//var jumin = '<?php echo $k_birthday?>';
		//var gender = '<?php echo $k_gender?>';
		//var name = $('#koreanName').val();

		var jumin = '19830725';
		var gender = '1';
		var name = '김민구';

alert(jumin);
alert(gender);
alert(name);

		var request = $.ajax({
			url:'./nc/nc_p.php',
			type:"POST",
			data: {
							"name":name,
							"jumin":jumin,
							"gender":gender
				    		},
			dataType:"html"
		});

		request.done(function(msg) {
		console.log(msg)
			if (msg.trim() != "인증성공") {
				alert(msg);
				
			}else{
				js_register();
			}

		});

		request.fail(function(jqXHR, textStatus) {
			alert("Request failed : " +textStatus);
			return false;
		});

		

	}

</script>
<script type="text/javascript" src="./js/register_test.js?<?=time()?>"></script>

<style>
#popup_mask_layer { position: fixed; width: 100%; height: 100%; top: 0px; left: 0px; background-color:#000; opacity: 0.6; z-index:10;}
#popupDiv { position: fixed; display:none; background: #fff; width: 200px; height: 200px; top: 50%; left: 50%; transform: translate(-50%, -50%); transition: opacity .3s; z-index:99; border:1px solid #333; border-radius:10px;}
</style>

<div id="popupDiv">
	<span id="popCloseBtn" style="position:absolute; right:-10px; top:-10px;cursor:pointer;display:none;"><img src="./images/close.png" title="닫기"/></span>
	<div style="width:100%; text-align:center; padding:10px;font-size:12px;">
		<img src="./images/logo.png" style="width:100px" /><br>
		<img src="./images/loading32.gif" style="margin:7px"/><br>
		<span id="popTxt">가입처리중입니다.<br>다소 시간이 걸릴 수 있으니<br>기다려 주세요</span>
	</div>
</div>

<div class="cont_wrap">
    <dl class="conttit_wrap mo_none">
        <!--<dt>회원가입</dt>
        <dd>외국인 회원가입 또는 국제후원가입은 <br class="mo"/>Call Center로 문의하시기 바랍니다(1577-8269)</dd>-->
    </dl>
	<form name="applicationForm" id="frm" method="post" action="#">

		<input type="hidden" name="mode" value="add">
		<input type="hidden" name="memberType" id="memberType" value='<?=$type?>'>
		<input type="hidden" name="k_id" id="k_id" value='<?=$k_id?>'>
		<?php
		for($i=0; $i<=15; $i++) { ?>
		<input type="hidden" name="agree_<?=$i?>" value='<?=($_POST['ch'][$i] == "")?"N":"Y";?>'>
		<?php } ?>

		<div class="cont_from">
			<h2 class="certification_title">기본정보 입력</h2>
			<table>
				<colgroup>
					<col style="width:25%" />
					<col style="width:25%" />
				</colgroup>
				<tr>
					<td>
						<div>
							<label for="koreanName" class="point wid_4">한글성명</label> 
							<span>
								<span class="fm_btn">
									<input id="koreanName" type="text" name="koreanName" value="<?=$_SESSION["S_NM"]?>" title="한글성명" maxlength="20"  <?=($_SESSION["S_FLAG"] !='kakao')?"readonly":"";?>  maxlength="6">
									<button type="button" id="" onclick="nc()">실명검색</button>
								</span>
							</span>
						</div>
					</td>
					<td><div><label for="englishName" class="point wid_4">영문성명</label> <input type="text" name="englishName" id="englishName" value="" maxlength="30" title="영문성명" placeholder="Hong gil dong" maxlength="30"> </div></td>
				</tr>
				<tr>
					<td>
						<div>
							<label for="id3" class="point wid_5">휴대폰번호</label>
							<?php if($_SESSION['S_MOBILE_NO'] == ""){ ?>
							<span>
								<span class="sel_box">
									<select id="mobileNo1" name="mobileNo1" title="휴대폰 통신사">
										<option value="010" <?=($tel1 == "010")?"selected":"";?>>010</option>
										<option value="011" <?=($tel1 == "011")?"selected":"";?>>011</option>
										<option value="016" <?=($tel1 == "016")?"selected":"";?>>016</option>
										<option value="017" <?=($tel1 == "017")?"selected":"";?>>017</option>
										<option value="018" <?=($tel1 == "018")?"selected":"";?>>019</option>
										<option value="019" <?=($tel1 == "019")?"selected":"";?>>019</option>
									</select>
								</span>
							</span>
							<?php }else{ ?>
							<span><input id="mobileNo1" type="text" name="mobileNo1" value="<?=$tel1?>" maxlength="3" title="휴대폰 통신사" <?=($_SESSION['S_MOBILE_NO'] != "")?"readonly data-inp-type='number'":"";?>> </span>
							<?php } ?>
							<span class="dash">-</span>
							<span><input id="mobileNo2" type="text" name="mobileNo2" value="<?=$tel2?>" maxlength="4" title="휴대폰 국번" <?=($_SESSION['S_MOBILE_NO'] != "")?"readonly data-inp-type='number'":"";?>> </span>
							<span class="dash">-</span>
							<span><input id="mobileNo3" type="text" name="mobileNo3" value="<?=$tel3?>" maxlength="4" title="휴대폰 번호" <?=($_SESSION['S_MOBILE_NO'] != "")?"readonly data-inp-type='number'":"";?>></span>
						</div>
					</td>
					<td>
						<div>
							<label for="id4" class="wid_4">전화번호</label>
							<span>
								<span class="sel_box">
									<select id="phoneNo1" name="phoneNo1" title="전화번호 앞자리">
										<option value="">선택</option>
										<option value="02">02</option>
										<option value="031">031</option>
										<option value="032">032</option>
										<option value="033">033</option>
										<option value="041">041</option>
										<option value="043">043</option>
										<option value="042">042</option>
										<option value="044">044</option>
										<option value="051">051</option>
										<option value="052">052</option>
										<option value="053">053</option>
										<option value="054">054</option>
										<option value="055">055</option>
										<option value="061">061</option>
										<option value="062">062</option>
										<option value="063">063</option>
										<option value="064">064</option>
										<option value="070">070</option>
									</select>
								</span>
							</span>
							<span class="dash">-</span>
							<span><input id="phoneNo2" type="text" name="phoneNo2" value="" title="전화번호 중간자리"  data-inp-type="number" maxlength="4"> </span>
							<span class="dash">-</span>
							<span><input id="phoneNo3" type="text" name="phoneNo3" class="txt" value="" title="전화번호 끝자리"  data-inp-type="number" maxlength="4"></span>
						</div>
					</td>
				</tr>
				<tr>
					<td class="all" colspan="4">
						<div class="fm_email">
							<label for="id5 " class="point wid_3">이메일</label>
							<span><input id="email1" type="text" name="email1" value="<?=$_email_prefix?>" title="이메일 주소입력" maxlength="30"></span>
							<span class="dash">@</span>
							<span><input type="text" id="email2" name="email2" value="<?=$_email_suffix?>" maxlength="30"></span>
							<span>
								<span class="sel_box">
									<select class="email3" id="email3" name="email3" title="이메일 도메일 선택">
										<option value="" selected>직접입력</option>
										<option value="naver.com" >naver.com</option>
										<option value="hanmail.net">hanmail.net</option>
										<option value="gmail.com">gmail.com</option>
										<option value="daum.net">daum.net</option>
										<option value="hotmail.com">hotmail.com</option>
										<option value="nate.com">nate.com</option>
										<option value="dreamwiz.com">dreamwiz.com</option>
										<option value="korea.com">korea.com</option>
										<option value="yahoo.co.kr">yahoo.co.kr</option>
									</select>
								</span>
							</span>
							<span style="width:50%"></span>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div style="position:relative">
							<label for="zip" class="point wid_2">주소</label>
							<span>
								<span class="fm_btn">
									<input type="text" id="zip" name="zip" value="" maxlength="6" readonly placeholder="우편번호">
									<button type="button" onclick="sample2_execDaumPostcode();">우편번호 검색</button>
								</span>

								<div id="Postlayer" style="display:none;position:fixed;overflow:hidden;z-index:1;-webkit-overflow-scrolling:touch;z-index:99999;background:#fff;" >
									<img src="./images/close.png" id="btnCloseLayer" style="width:20px; height:20px;cursor:pointer;position:absolute;right:-3px;top:-3px;z-index:1" onclick="javascript:closeDaumPostcode();" alt="닫기 버튼">
								</div>

								<input type="hidden" id="country" name="country" value="KR">
								<input type="hidden" id="state" name="state" value="">
								<input type="hidden" id="city" name="city" value="">
								<input type="hidden" id="address1" name="address1" value="">
								<input type="hidden" id="address2" name="address2" value="">
								<input type="hidden" id="address1_sub" name="address1_sub" value="">

							</span>
						</div>
					</td>
					<td><div><input type="text" id="fulladdress" name="fulladdress" value="" readonly placeholder="기본주소"> </div></td>
				</tr>
				<tr>
					<td class="all"  colspan="4"><div><input type="text" id="detailaddress" name="detailaddress"  value="" placeholder="상세주소 입력"></div></td>
				</tr>

				<?php if ($type == 'D') {?>
				<tr>
					<td>
						<div>
							<label for="id6" class="point wid_4">은행계좌</label>
							<span>
								<span class="sel_box">
									<?php 
									$result_bank = mysql_query("select * from tb_code where parent='bank3' order by name asc") or die(mysql_error());											
									?>
									
									<select id="bankcode" name="bankcode" title="거래은행 선택">
				                        <option value="">은행명을 선택하세요</option>
										<?php while($row_bank = mysql_fetch_array($result_bank)) { ?>
											<option value='<?=$row_bank['code']?>'><?=$row_bank['name']?></option>
										<?php } ?>
									</select>
								</span>
							</span>
						</div>
					</td>
					<td><div><input id="accountNo" type="text" name="accountNo" class="txt" value="" title="계좌번호" value=""  data-inp-type='number' placeholder="계좌번호 입력" maxlength="30"> </div></td>
				</tr>
				<?php } ?>

				<tr>
					<td>
						<div>
							<label for="id6" class="point wid_5">후원자 번호</label>
							<span>
								<span class="fm_btn">
									<input type="text" id="sponsorNo" name="sponsorNo"  title="후원자 확인" value="<?=($flagk== "kakaok" ||$pflag=="sponNum" )?$_SESSION["S_K_SPONSOR_ID"]:$sponsor_id;?>" data-inp-type='number' placeholder="후원자 회원번호 입력" maxlength="10">
									<button type="button" onclick="searchFO(); return false;"><!-- FO -->회원번호 조회 </button>
									<button type="button" id="btnResetFO" onclick="resetFO(); return false;" style="display:none">초기화</button>
								</span>
							</span>
						</div>
					</td>
					<td>
						<div>
							<label for="id6" class="wid_5">후원자 성명</label>
							<input type="text" id="sponsorName" name="sponsorName" value="<?=($flagk== "kakaok")?$_SESSION["S_SPONSOR_NAME"]:$sponsor_name;?>"  title="후원자 성명" placeholder="회원번호 조회로 조회해 주세요" autocomplete="new-password" readonly>
						 </div>
					</td>
				</tr>

				<tr>
					<td class="all" colspan="4"><div><label for="id6" class="point wid_7">온라인 비밀번호</label> <input id="password" type="password" name="password" value="" placeholder="6자이상 숫자/영문" autocomplete="new-password" maxlength="20"> </div></td>
				</tr>
			</table>
		</div>

		<?php include_once "include_terms.php"; ?>
		<?php include_once "include_terms_popup.php"; ?>

	</form>

	<div class="btn_box">
		<button type="button" name="button" class="btn btn_color_1" onclick="submitForm()">가입하기</button>
		
		&nbsp;<button type="button" name="button" class="btn btn_color_3" onclick="history.back()">뒤로가기</button>
		<?php if($user_device == "P"){ ?>
		<?php } ?>
	</div>

</div>

<?php if(getRealIp() == "121.190.224.191"){ ?>
<iframe name="ifrHidden" name="ifrHidden" src="about:blank" style="width:100%; height:500px; border:1px solid #000"></iframe>
<?php }else{ ?>
<iframe name="ifrHidden" name="ifrHidden" src="about:blank" style="width:0%; height:0px; border:none"></iframe>
<?php } ?>



<?php include "./includes/footer.php";?>

<script type="text/javascript">

$(function(){
	//uiInit();
    $(document).on("change", ".email3", function(){
		$('#email2').val($(this).val());
		if($(this).val() == "") $('#email2').focus();
    });
	$('.agree_slide_btn').click(function(e){
		//e.preventDefault();

		//$(this).closest('.agree_slide_btn_box').toggleClass('on');
		//$('.agree_box').slideToggle('on');
	});

	
	$( "#email1" ).blur(function() {
		var str = $(this).val().trim();
		$(this).val(str);
		if((str.match(/@/g)).length > 0){
			var stra = str.split('@');
			$('#email1').val(stra[0]);
			$('#email2').val(stra[1]);
		}
	});
	$( "#email2" ).blur(function() {
		var str = $(this).val().trim();
		$(this).val(str);		
	});

});

$(document).ready(function() {
	var flagK = '<?php echo $flagk ?>';
	var id = '<?php echo $_SESSION["S_K_SPONSOR_ID"] ?>';
	var pflag='<?php echo $pflag?>';

	if(flagK=='kakaok' || pflag=='sponNum'){

		searchFO();
	}
	});

</script>
</body>
</html>
