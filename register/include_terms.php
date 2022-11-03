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

	$('.check').on('click', function(){
		if($(this).is(":checked")){
			if($("#ch_0").is(":checked") && $("#ch_1").is(":checked") &&  $("#ch_2").is(":checked") &&  $("#ch_3").is(":checked") && $("#ch_4").is(":checked") && $("#ch_5").is(":checked") && $("#ch_7").is(":checked") && $("#ch_8").is(":checked") && $("#ch_9").is(":checked")) $("#chAll").prop("checked", true);	
		}else{
			$("#chAll").prop("checked", false);
		}
	});

});
</script>

		<div class="certification_agree">
			<h2 class="certification_title agree_slide_btn_box">
				<span class="agree_slide_btn">약관 및 개인정보 동의</span>
				<label class="fm_ch"><input type="checkbox" name="chAll" id="chAll" value="Y"><span class="_icon"></span>전체동의</label>
			</h2>
			<ul class="agree_box">
				<li>
					<div>
						<label class="fm_ch">[필수] 전자상거래에 관한 이용약관<input type="checkbox" name="agree_0" id="ch_0" value="Y" class="check required"><span class="_icon"></span></label>
						<a href="#term0"  onclick="$('#popup').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
				</li>
				<li>
					<div>
						<label class="fm_ch">[필수] 신청계약 동의<input type="checkbox" name="agree_1" id="ch_1" value="Y" class="check required"><span class="_icon"></span></label>
						<a href="#term0"  onclick="$('#popup1').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
				</li>
				<li>
					<div>
						<label class="fm_ch">[필수] 개인정보 수집 및 이용에 대한 동의<input type="checkbox" name="agree_2" id="ch_2" value="Y" class="check required"><span class="_icon"></span></label>
						<a href="#term0"  onclick="$('#popup2').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
				</li>
				<li>
					<div>
						<label class="fm_ch">[필수] 개인정보 제3자 제공 및 공유에 대한 동의<input type="checkbox" name="agree_3" id="ch_3" value="Y" class="check required"><span class="_icon"></span></label>
						<a href="#term0"  onclick="$('#popup3').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
				</li>
				<li>
					<div>
						<label class="fm_ch">[필수] 개인정보 국외 이전에 대한 동의<input type="checkbox" name="agree_4" id="ch_4" value="Y" class="check required"><span class="_icon"></span></label>
						<a href="#term0"  onclick="$('#popup4').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
				</li>
				<li>
					<div>
						<label class="fm_ch">[선택] 본인 외 주문에 대한 동의<input type="checkbox" name="agree_5" id="ch_5" value="Y" class="check"><span class="_icon"></span></label>
						<a href="#term0"  onclick="$('#popup5').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
				</li>
				<?php if ($memberType == 'D') {?>
				<li>
					<div>
						<label class="fm_ch">[선택] 다단계판매원 수첩과 등록증의 이메일 수령에 대한 동의<input type="checkbox" name="agree_6" id="ch_6" value="Y" class="check"><span class="_icon"></span></label>
						<a href="#term0"  onclick="$('#popup6').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
				</li>
				<?php } ?>
				<li>
					<div>
						<label>[선택] 마케팅 목적 이용에 관한 동의</label>
						<a href="#term0"  onclick="$('#popup7').fadeIn(); return false;" class="popup_btn">전체보기</a>
					</div>
					<div class="st_2">
						<label class="fm_ch"> <input type="checkbox" name="agree_7" id="ch_7" value="Y" class="check"><span class="_icon"></span> [선택] 마케팅 홍보 목적의 이메일 수신동의</label>
						<label class="fm_ch"> <input type="checkbox" name="agree_8" id="ch_8" value="Y" class="check"><span class="_icon"></span> [선택] 마케팅 홍보 목적의 SMS 수신동의</label>
						<label class="fm_ch"> <input type="checkbox" name="agree_9" id="ch_9" value="Y" class="check"><span class="_icon"></span> [선택] 마케팅 홍보 목적의 우편물 수신동의</label>
					</div>
				</li>
			</ul>
		</div>
	
		<?php if ($memberType == 'D') {?>
		<div class="certification_agree2">
			<ul>
				<li>
					<div>본인은 ‘후원수당의 산정 및 지급기준 등의 변경’과 ‘방침 및 절차의 변경’에 대한 통지를 아래 방식으로 수신 받겠습니다. </div>
					<div>
						<label class="fm_ch"> <input type="checkbox" name="agree_10" id="ch_10" value="Y" class="check required marketing"><span class="_icon"></span> 이메일 </label>
						<label class="fm_ch"> <input type="checkbox" name="agree_11" id="ch_11" value="Y" class="check"><span class="_icon"></span> SMS </label>
						<label class="fm_ch"> <input type="checkbox" name="agree_12" id="ch_12" value="Y" class="check"><span class="_icon"></span> 우편 </label>
					</div>
				</li>
				<li>
					<div>본인은 공무원 또는 교원이거나, 다단계판매업자의 지배주주이거나 임직원이 아니며, 방문판매 등에 관한 법률을 위반한 사실이 없으므로 다단계판매원으로 등록을 신청합니다.</div>
					<div>
						<label class="fm_ch"><input type="checkbox" name="agree_13" id="ch_13" value="Y" class="check required"><span class="_icon"></span> 확인함 </label>
					</div>
				</li>
				<li>
					<div>본인은 과거 유니시티의 방침 및 절차를 위반하거나 제재를 받은 사실이 없으며, 프레지덴셜 디렉터 1차 이상을 달성한 이력이 없음을 확인합니다.</div>
					<div>
						<label class="fm_ch"><input type="checkbox" name="agree_14" id="ch_14" value="Y" class="check required"><span class="_icon"></span> 확인함 </label>
					</div>
				</li>
				<li>
					<div><strong>본인은 회원으로 활동하여 발생한 후원수당은 세금신고용 주민등록번호를 회사에 제공해야 지급된다는 것과 이렇게 지급받은 후원수당이 사업소득으로 국세청에 신고됨과 소득세 등 납세의무가 발생함을 알고 있습니다. </strong> 또한 타인의 신상 정보를 본인의 동의없이 사용하여 등록을 시킬 경우 회사로부터 엄중한 조치 및 관련 법규 위반으로 형사상의 불이익을 받을 수 있음을 알고 있고 모든 책임은 본인에 있음을 확인합니다. </div>
					<div>
						<label class="fm_ch"><input type="checkbox" name="agree_15" id="ch_15" value="Y" class="check required"><span class="_icon"></span> 확인함 </label>
					</div>
				</li>
			</ul>
		</div>
		<?php } ?>
