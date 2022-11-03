<?php
session_start();
if(!isset($_SERVER["HTTPS"])) {
	header('Location: https://'.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']);
	exit;
}
include $_SERVER['DOCUMENT_ROOT']."/register/includes/common_functions.php";
$user_device = mobile_check();  // return P or M

cert_validation();

include "./includes/top.php";
?>
<script type="text/javascript">
//전체선택
$(function(){
	$('#chAll').click(function(){
		if($("#chAll").is(":checked")){
			$(".check").prop("checked", true);
		}else{
			$(".check").prop("checked", false);
		}
	});

	$('.btn_submit').click(function(){
		var err = 0;
		$(".required").each(function(){

			if($(this).is(":checked") === false){

				if($(this).hasClass('marketing')){
					if(!$("#ch_10").is(":checked") && !$("#ch_11").is(":checked") && !$("#ch_12").is(":checked")){
						alert("통지 방식을 1개 이상 선택해 주세요");
						window.scrollTo({top: $(this).offset().top - 50, behavior: 'smooth'});
						err++;
						return false;
					}
				}else{
					var str = $(this).parent().text().trim();
					if(str == "확인함") alert("필수동의사항을 확인해 주세요");
					else alert("필수동의 : "+str);
					window.scrollTo({top: $(this).offset().top - 50, behavior: 'smooth'});
					err++;
					return false;
				}
			}
		});

		if(err === 0) $('#frm').submit();
	});
});
</script>
<div class="cont_wrap">
    <dl class="conttit_wrap mo_none">
        <dt>회원가입</dt>
        <dd>외국인 회원가입 또는 국제후원가입은 <br class="mo"/>Call Center로 문의하시기 바랍니다(1577-8269)</dd>
    </dl>
	<form name="frm" id="frm" method="post" action="registerForm.php">

		<input type="hidden" name="memberType" id="memberType" value="<?=$memberType?>">

		<div class="certification_agree">
			<h2 class="certification_title agree_slide_btn_box">
				<a href="###" class="agree_slide_btn">약관 및 개인정보 동의</a>
				<label class="fm_ch">전체동의<input type="checkbox" name="chAll" id="chAll" value="Y"><span class="_icon"></span></label>
			</h2>
			<ul class="agree_box">
				<li>
					<div>
						<label class="fm_ch">[필수] 전자상거래에 관한 이용약관<input type="checkbox" name="ch[0]" id="ch_0" value="Y" class="check required"><span class="_icon"></span></label>
						<a href="#term0"  onclick="$('#popup').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
				</li>
				<li>
					<div>
						<label class="fm_ch">[필수] 신청계약 동의<input type="checkbox" name="ch[1]" id="ch_1" value="Y" class="check required"><span class="_icon"></span></label>
						<a href="#term0"  onclick="$('#popup1').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
				</li>
				<li>
					<div>
						<label class="fm_ch">[필수] 개인정보 수집 및 이용에 대한 동의<input type="checkbox" name="ch[2]" id="ch_2" value="Y" class="check required"><span class="_icon"></span></label>
						<a href="#term0"  onclick="$('#popup2').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
				</li>
				<li>
					<div>
						<label class="fm_ch">[필수] 개인정보 제3자 제공 및 공유에 대한 동의<input type="checkbox" name="ch[3]" id="ch_3" value="Y" class="check required"><span class="_icon"></span></label>
						<a href="#term0"  onclick="$('#popup3').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
				</li>
				<li>
					<div>
						<label class="fm_ch">[필수] 개인정보 국외 이전에 대한 동의<input type="checkbox" name="ch[4]" id="ch_5" value="Y" class="check required"><span class="_icon"></span></label>
						<a href="#term0"  onclick="$('#popup4').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
				</li>
				<li>
					<div>
						<label class="fm_ch">[선택] 본인 외 주문에 대한 동의<input type="checkbox" name="ch[5]" id="ch_5" value="Y" class="check"><span class="_icon"></span></label>
						<a href="#term0"  onclick="$('#popup5').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
				</li>
				<li>
					<div>
						<label class="fm_ch">[선택] 다단계판매원 수첩과 등록증의 이메일 수령에 대한 동의<input type="checkbox" name="ch[6]" id="ch_6" value="Y" class="check"><span class="_icon"></span></label>
						<a href="#term0"  onclick="$('#popup6').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
				</li>
				<li>
					<div>
						<label>[선택] 마케팅 목적 이용에 관한 동의</label>
						<a href="#term0"  onclick="$('#popup7').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
					<div class="st_2">
						<label class="fm_ch"> <input type="checkbox" name="ch[7]" id="ch_7" value="Y" class="check"><span class="_icon"></span>[선택] 마케팅 홍보 목적의 이메일 수신동의</label>
						<label class="fm_ch"> <input type="checkbox" name="ch[8]" id="ch_8" value="Y" class="check"><span class="_icon"></span>[선택] 마케팅 홍보 목적의 SMS 수신동의</label>
						<label class="fm_ch"> <input type="checkbox" name="ch[9]" id="ch_9" value="Y" class="check"><span class="_icon"></span>[선택] 마케팅 홍보 목적의 우편물 수신동의</label>
					</div>
				</li>
			</ul>
		</div>
		<?php if ($memberType == 'D') {?>
		<div class="certification_agree2">
			<ul>
				<li>
					<div>[필수] 본인은 ‘후원수당의 산정 및 지급기준 등의 변경’과 ‘방침 및 절차의 변경’에 대한 통지를 아래 방식으로 수신 받겠습니다. </div>
					<div>
						<label class="fm_ch"> <input type="checkbox" name="ch[10]" id="ch_10" value="Y" class="check required marketing"><span class="_icon"></span> 이메일 </label>
						<label class="fm_ch"> <input type="checkbox" name="ch[11]" id="ch_11" value="Y" class="check"><span class="_icon"></span> SMS </label>
						<label class="fm_ch"> <input type="checkbox" name="ch[12]" id="ch_12" value="Y" class="check"><span class="_icon"></span> 우편 </label>
					</div>
				</li>
				<li>
					<div>[필수]  본인은 공무원 또는 교원이거나, 다단계판매업자의 지배주주이거나 임직원이 아니며, 방문판매 등에 관한 법률을 위반한 사실이 없으므로 다단계판매원으로 등록을 신청합니다.</div>
					<div>
						<label class="fm_ch"><input type="checkbox" name="ch[13]" id="ch_13" value="Y" class="check required"><span class="_icon"></span> 확인함 </label>
					</div>
				</li>
				<li>
					<div>[필수]  본인은 과거 유니시티의 방침 및 절차를 위반하거나 제재를 받은 사실이 없으며, 프레지덴셜 디렉터 1차 이상을 달성한 이력이 없음을 확인합니다.</div>
					<div>
						<label class="fm_ch"><input type="checkbox" name="ch[14]" id="ch_14" value="Y" class="check required"><span class="_icon"></span> 확인함 </label>
					</div>
				</li>
				<li>
					<div>[필수]  <b>본인은 <!-- FO -->회원으로 활동하여 지급받은 후원수당이 사업소득으로 국세청에 신고됨과 소득세 등 납세의무가 발생함을 알고 있습니다.</b> 또한 타인의 신상 정보를 본인의 동의없이 사용하여 등록을 시킬 경우 회사로부터 엄중한 조치 및 관련 법규 위반으로 형사상의 불이익을 받을 수 있음을 알고 있고 모든 책임은 본인에 있음을 확인합니다.</div>
					<div>
						<label class="fm_ch"><input type="checkbox" name="ch[15]" id="ch_15" value="Y" class="check required"><span class="_icon"></span> 확인함 </label>
					</div>
				</li>
			</ul>
		</div>
		<?php } ?>
		<div class="btn_box">
			<a href="javascript:;" class="btn btn_color_1 btn_submit">가입하기</a>
		</div>
	</form>
</div>


<!--팝업-->
<div class="popup_wrap" id="popup">
	<div class="popup_box">
		<div class="popup_top"><span>[필수] 전자상거래에 관한 이용약관</span></div>
		<div class="popup_con">
			<?php include "./terms/term0.php";?>
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
			<?php include "./terms/term01.php";?>
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
			<?php include "./terms/term02.php";?>
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
			<?php include "./terms/term03.php";?>
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
			<?php include "./terms/term04.php";?>
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
			<?php include "./terms/term05.php";?>
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
			<?php include "./terms/term06.php";?>
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
			<?php include "./terms/term07.php";?>
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
