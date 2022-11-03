<?php include "./includes/top.php";?>
<!-- <!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="format-detection" content="telephone=no" />
	<link rel="stylesheet" type="text/css" href="./css/reset.css?v=1"/>
	<link rel="stylesheet" type="text/css" href="./css/common.css?v=1"/>
	<link rel="stylesheet" type="text/css" href="./css/style.css"/>
	<script type="text/javascript" src="./js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="./js/ui.js"></script>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
	<title>본인인증</title>
</head>
<body>

<div id="pc_mo_bool"></div>
<div class="logo">
	<a href="javascript:history.back();" class="btn_back"></a>
</div>
 -->
<div class="cont_wrap">
    <dl class="conttit_wrap mo_none">
        <dt>회원가입</dt>
        <dd>외국인 회원가입 또는 국제후원가입은 <br class="mo"/>Call Center로 문의하시기 바랍니다(1577-8269)</dd>
    </dl>
	<form class="" action="">
		<div class="cont_from">
			<h2 class="certification_title">기본정보 입력</h2>
			<table>
				<colgroup>
					<col style="width:25%" />
					<col style="width:25%" />
				</colgroup>
				<tr>
					<td><div><label for="id1" class="point wid_4">한글성명</label> <input type="text" id="id1" name="" value=""> </div></td>
					<td><div><label for="id2" class="point wid_4">영문성명</label> <input type="text" id="id2" name="" value=""> </div></td>
				</tr>
				<tr>
					<td>
						<div>
							<label for="id3" class="point wid_5">휴대폰번호</label>
							<span>
								<span class="sel_box">
									<select class="" name="">
										<option value="010">010</option>
										<option value="010">011</option>
										<option value="010">016</option>
										<option value="010">017</option>
										<option value="010">019</option>
									</select>
								</span>
							</span>
							<span class="dash">-</span>
							<span><input type="text" id="id3" name="" value="" data-inp-type="number" maxlength="4"> </span>
							<span class="dash">-</span>
							<span><input type="text" id="id3" name="" value="" data-inp-type="number" maxlength="4"></span>
						</div>
					</td>
					<td>
						<div>
							<label for="id4" class="point wid_6">자택전화번호</label>
							<span>
								<span class="sel_box">
									<select class="" name="">
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
							<span><input type="text" id="id3" name="" value=""  data-inp-type="number" maxlength="4"> </span>
							<span class="dash">-</span>
							<span><input type="text" id="id3" name="" value=""  data-inp-type="number" maxlength="4"></span>
						</div>
					</td>
				</tr>
				<tr>
					<td class="all" colspan="4">
						<div class="fm_email">
							<label for="id5 " class="point wid_3">이메일</label>
							<span><input type="text" class="email1" id="id3" name="" value=""></span>
							<span class="dash">@</span>
							<span><input type="text" class="email2" id="id3" name="" value="" readonly></span>
							<span>
								<span class="sel_box">
									<select class="email3" name="" >
										<option value="" selected>직접입력</option>
										<option value="naver.com" >naver.com</option>
										<option value="hanmail.net">hanmail.net</option>
										<option value="hotmail.com">hotmail.com</option>
										<option value="nate.com">nate.com</option>
										<option value="yahoo.co.kr">yahoo.co.kr</option>
										<option value="empas.com">empas.com</option>
										<option value="dreamwiz.com">dreamwiz.com</option>
										<option value="freechal.com">freechal.com</option>
										<option value="lycos.co.kr">lycos.co.kr</option>
										<option value="korea.com">korea.com</option>
										<option value="gmail.com">gmail.com</option>
										<option value="hanmir.com">hanmir.com</option>
										<option value="paran.com">paran.com</option>
									</select>
								</span>
							</span>
							<span style="width:50%"></span>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div>
							<label for="id6" class="point wid_2">주소</label>
							<span>
								<span class="fm_btn">
									<input type="text" id="id6" name="" value="" data-inp-type="number" maxlength="6" readonly>
									<button type="button">주소검색</button>
								</span>
							</span>
						</div>
					</td>
					<td><div><input type="text" id="id7" name="" value="" readonly> </div></td>
				</tr>
				<tr>
					<td class="all"  colspan="4"><div><input type="text" id="id7" name="" value=""></div></td>
				</tr>
				<tr>
					<td>
						<div>
							<label for="id6" class="point wid_4">은행계좌</label>
							<span>
								<span class="sel_box">
									<select class="" name="" >
				                        <option value="">은행명을 선택하세요</option>
				                        <option value="35">경남은행</option>
				                        <option value="29">광주은행</option>
				                        <option value="7">국민은행</option>
				                        <option value="5">기업은행</option>
				                        <option value="15">농협중앙회</option>
				                        <option value="17">농협회원조합</option>
				                        <option value="25">대구은행</option>
				                        <option value="47">도이치은행</option>
				                        <option value="27">부산은행</option>
				                        <option value="3">산업은행</option>
				                        <option value="41">상호저축은행</option>
				                        <option value="37">새마을금고</option>
				                        <option value="11">수협중앙회</option>
				                        <option value="36">신한금융투자</option>
				                        <option value="60">신한은행</option>
				                        <option value="39">신협중앙회</option>
				                        <option value="9">외환은행</option>
				                        <option value="19">우리은행</option>
				                        <option value="56">우체국</option>
				                        <option value="33">전북은행</option>
				                        <option value="31">제주은행</option>
				                        <option value="68">카카오뱅크</option>
				                        <option value="67">케이뱅크</option>
				                        <option value="59">하나은행</option>
				                        <option value="23">한국씨티은행</option>
				                        <option value="45">HSBC은행</option>
				                        <option value="21">SC제일은행</option>
								</span>
							</span>
						</div>
					</td>
					<td><div><input type="text" id="id7" name="" value=""> </div></td>
				</tr>
				<tr>
					<td>
						<div>
							<label for="id6" class="point wid_2">후원</label>
							<span>
								<span class="fm_btn">
									<input type="text" id="id6" name="" value="" placeholder="후원자 회원번호 입력 ">
									<button type="button">FO번호 조회 </button>
								</span>
							</span>
						</div>
					</td>
					<td>
						<div>
							<label for="id6" class="wid_5">후원자 성명</label>
							<input type="text" id="id7" name="" value="" placeholder="성명">
						 </div>
					</td>
				</tr>
				<tr>
					<td class="all"  colspan="4"><div><label for="id6" class="point wid_7">온라인 비밀번호</label> <input type="password" id="id6" name="" value="" placeholder="6자이상 숫자/영문"> </div></td>
				</tr>
			</table>
		</div>


		<div class="certification_agree">
			<h2 class="certification_title agree_slide_btn_box">
				<a href="###" class="agree_slide_btn">약관 및 개인정보 동의</a>
				<label class="fm_ch">전체동의<input type="checkbox" name="chAll" value=""><span class="_icon"></span></label>
			</h2>
			<ul class="agree_box">
				<li>
					<div>
						<label class="fm_ch">[필수] 전자상거래에 관한 이용약관<input type="checkbox" name="ch" value=""><span class="_icon"></span></label>
						<a href="#term0"  onclick="$('#popup').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
				</li>
				<li>
					<div>
						<label class="fm_ch">[필수] 신청계약 동의<input type="checkbox" name="ch" value=""><span class="_icon"></span></label>
						<a href="#term0"  onclick="$('#popup1').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
				</li>
				<li>
					<div>
						<label class="fm_ch">[필수] 개인정보 수집 및 이용에 대한 동의<input type="checkbox" name="ch" value=""><span class="_icon"></span></label>
						<a href="#term0"  onclick="$('#popup2').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
				</li>
				<li>
					<div>
						<label class="fm_ch">[필수] 개인정보 제3자 제공 및 공유에 대한 동의<input type="checkbox" name="ch" value=""><span class="_icon"></span></label>
						<a href="#term0"  onclick="$('#popup3').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
				</li>
				<li>
					<div>
						<label class="fm_ch">[필수] 개인정보 국외 이전에 대한 동의<input type="checkbox" name="ch" value=""><span class="_icon"></span></label>
						<a href="#term0"  onclick="$('#popup4').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
				</li>
				<li>
					<div>
						<label class="fm_ch">[선택] 본인 외 주문에 대한 동의<input type="checkbox" name="ch" value=""><span class="_icon"></span></label>
						<a href="#term0"  onclick="$('#popup5').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
				</li>
				<li>
					<div>
						<label class="fm_ch">[선택] 다단계판매원 수첩과 등록증의 이메일 수령에 대한 동의<input type="checkbox" name="ch" value=""><span class="_icon"></span></label>
						<a href="#term0"  onclick="$('#popup6').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
				</li>
				<li>
					<div>
						<label>[선택] 마케팅 목적 이용에 관한 동의</label>
						<a href="#term0"  onclick="$('#popup7').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
					<div class="st_2">
						<label class="fm_ch">[선택] 마케팅 홍보 목적의 이메일 수신동의 <input type="checkbox" name="ch" value=""><span class="_icon"></span></label>
						<label class="fm_ch">[선택] 마케팅 홍보 목적의 SMS 수신동의 <input type="checkbox" name="ch" value=""><span class="_icon"></span></label>
						<label class="fm_ch">[선택] 마케팅 홍보 목적의 우편물 수신동의 <input type="checkbox" name="ch" value=""><span class="_icon"></span></label>
					</div>
				</li>
			</ul>
		</div>

		<div class="certification_agree2">
			<ul>
				<li>
					<div>본인은 ‘후원수당의 산정 및 지급기준 등의 변경’과 ‘방침 및 절차의 변경’에 대한 통지를 아래 방식으로 수신 받겠습니다. </div>
					<div>
						<label class="fm_ch"> <input type="checkbox" name="ch" value=""><span class="_icon"></span> 이메일 </label>
						<label class="fm_ch"> <input type="checkbox" name="ch" value=""><span class="_icon"></span> SNS </label>
						<label class="fm_ch"> <input type="checkbox" name="ch" value=""><span class="_icon"></span> 우편 </label>
					</div>
				</li>
				<li>
					<div>본인은 공무원 또는 교원이거나, 다단계판매업자의 지배주주이거나 임직원이 아니며, 방문판매 등에 관한 법률을 위반한 사실이 없으므로 다단계판매원으로 등록을 신청합니다.</div>
					<div>
						<label class="fm_ch"><input type="checkbox" name="ch" value=""><span class="_icon"></span> 확인함 </label>
					</div>
				</li>
				<li>
					<div>본인은 과거 유니시티의 방침 및 절차를 위반하거나 제재를 받은 사실이 없으며, 프레지덴셜 디렉터 1차 이상을 달성한 이력이 없음을 확인합니다.</div>
					<div>
						<label class="fm_ch"><input type="checkbox" name="ch" value=""><span class="_icon"></span> 확인함 </label>
					</div>
				</li>
				<li>
					<div>본인은 FO로 활동하여 지급받은 후원수당이 사업소득으로 국세청에 신고됨과 소득세 등 납세의무가 발생함을 알고 있습니다. 또한 타인의 신상 정보를 본인의 동의없이 사용하여 등록을 시킬 경우 회사로부터 엄중한 조치 및 관련 법규 위반으로 형사상의 불이익을 받을 수 있음을 알고 있고 모든 책임은 본인에 있음을 확인합니다.</div>
					<div>
						<label class="fm_ch"><input type="checkbox" name="ch" value=""><span class="_icon"></span> 확인함 </label>
					</div>
				</li>
			</ul>
		</div>
		<div class="btn_box">
			<button type="submit" name="button" class="btn btn_color_1">가입하기</button>
		</div>
	</form>
</div>


<!--팝업-->
<div class="popup_wrap" id="popup">
	<div class="popup_box">
		<div class="popup_top"><span>[필수] 전자상거래에 관한 이용약관</span></div>
		<div class="popup_con">
			<?php include "./includes/term0.php";?>
		</div>
		<div class="popup_close">
            <a href="javascript:void()" onclick="$('#popup').fadeOut();" class="play_close" style="display: inline;"></a>
        </div>
	</div>
</div>

<div class="popup_wrap" id="popup1">
	<div class="popup_box">
		<div class="popup_top"><span>[필수] 신청계약 동의</span></div>
		<div class="popup_con">
			<?php include "./includes/term01.php";?>
		</div>
		<div class="popup_close">
            <a href="javascript:void()" onclick="$('#popup1').fadeOut();" class="play_close" style="display: inline;"></a>
        </div>
	</div>
</div>

<div class="popup_wrap" id="popup2">
	<div class="popup_box">
		<div class="popup_top"><span>[필수] 개인정보 수집 및 이용에 대한 동의</span></div>
		<div class="popup_con">
			<?php include "./includes/term02.php";?>
		</div>
		<div class="popup_close">
            <a href="javascript:void()" onclick="$('#popup2').fadeOut();" class="play_close" style="display: inline;"></a>
        </div>
	</div>
</div>

<div class="popup_wrap" id="popup3">
	<div class="popup_box">
		<div class="popup_top"><span>[필수] 개인정보 제3자 제공 및 공유에 대한 동의</span></div>
		<div class="popup_con">
			<?php include "./includes/term03.php";?>
		</div>
		<div class="popup_close">
            <a href="javascript:void()" onclick="$('#popup3').fadeOut();" class="play_close" style="display: inline;"></a>
        </div>
	</div>
</div>

<div class="popup_wrap" id="popup4">
	<div class="popup_box">
		<div class="popup_top"><span>[필수] 개인정보 국외 이전에 대한 동의</span></div>
		<div class="popup_con">
			<?php include "./includes/term04.php";?>
		</div>
		<div class="popup_close">
            <a href="javascript:void()" onclick="$('#popup4').fadeOut();" class="play_close" style="display: inline;"></a>
        </div>
	</div>
</div>

<div class="popup_wrap" id="popup5">
	<div class="popup_box">
		<div class="popup_top"><span>[선택] 본인 외 주문에 대한 동의</span></div>
		<div class="popup_con">
			<?php include "./includes/term05.php";?>
		</div>
		<div class="popup_close">
            <a href="javascript:void()" onclick="$('#popup5').fadeOut();" class="play_close" style="display: inline;"></a>
        </div>
	</div>
</div>
<div class="popup_wrap" id="popup6">
	<div class="popup_box">
		<div class="popup_top"><span>[선택] 다단계판매원 수첩과 등록증의 이메일 수령에 대한 동의</span></div>
		<div class="popup_con">
			<?php include "./includes/term06.php";?>
		</div>
		<div class="popup_close">
            <a href="javascript:void()" onclick="$('#popup6').fadeOut();" class="play_close" style="display: inline;"></a>
        </div>
	</div>
</div>
<div class="popup_wrap" id="popup7">
	<div class="popup_box">
		<div class="popup_top"><span>[선택] 마케팅 목적 이용에 관한 동의</span></div>
		<div class="popup_con">
			<?php include "./includes/term07.php";?>
		</div>
		<div class="popup_close">
            <a href="javascript:void()" onclick="$('#popup7').fadeOut();" class="play_close" style="display: inline;"></a>
        </div>
	</div>
</div>



<?php include "./includes/footer.php";?>


<script type="text/javascript">

$(function(){
	uiInit();
    $(document).on("change", ".fm_email .email3", function(){
        var $sel =  $(this);
        var $box = $sel.closest(".fm_email");
        if($sel.val() == null || $sel.val() == ''){
            $box.find('.email2').removeAttr('readonly').val('').focus();
        }else{
            $box.find('.email2').attr('readonly', 'readonly').val($sel.val());
        }
    });
	$('.agree_slide_btn').click(function(e){
		e.preventDefault();

		$(this).closest('.agree_slide_btn_box').toggleClass('on');
		$('.agree_box').slideToggle('on');
	});
});

</script>
</body>
</html>
