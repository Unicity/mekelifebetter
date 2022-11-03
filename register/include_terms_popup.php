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
			<?php
			 if ($memberType == 'D') {
				 include "./terms/term01.php";
			 }else if ($memberType == 'C') {
				 include "./terms/term08.php";
			 }
		     ?>
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
			<?php
			 if ($memberType == 'D') {
				 include "./terms/term02.php";
			 }else if ($memberType == 'C') {
				 include "./terms/term09.php";
			 }
		     ?>
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
			<?php
			 if ($memberType == 'D') {
				 include "./terms/term03.php";
			 }else if ($memberType == 'C') {
				 include "./terms/term10.php";
			 }
		     ?>
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
