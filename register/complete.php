<?php 
session_start();
$_SESSION["S_CI"] = "";
$_SESSION["S_DI"] = "";
$_SESSION["S_BIRTH"] = "";
$_SESSION["S_GENDER"] = "";
$_SESSION["S_NM"] = "";
$_SESSION["S_MOBILE_NO"] = "";
session_destroy();;

include "./includes/top.php";?>

<div class="cont_wrap">

    <div class="completion_box">

		<?php if($_GET['isdup'] == "Y"){ ?>
			<p class="tit">유니시티코리아(유)</p>
			<p class="stit">회원가입 확인 중입니다.</p>
			<p class="txt">확인후 회원등록 결과를<br class="mo"/><br class="mo"/>이메일과 문자메세지로 발송해 드립니다.</p>
		<?php }else{ ?>
	        <p class="tit">유니시티코리아(유) 회원가입이 <br class="mo"/>정상적으로 완료되었습니다.</p>
		    <p class="stit">귀하의 회원번호는 <?=$_GET['no']?> 입니다.</p>
	        <p class="txt">귀하의 회원가입이 완료되어 <br class="mo"/>정상적인 회원활동을 시작할 수 있으며,<br />회원등록 결과를 신청시 등록하신 <br class="mo"/>이메일과 문자메세지로 발송해 드립니다.</p>
		<?php } ?>


        <div class="btn_box">
            <a class="btn" href="https://www.makelifebetter.co.kr/register/" class="gray_btn" id="nextbutton">새로운 회원가입</a>
            <a class="btn btn_color_2" href="https://www.makelifebetter.co.kr/" class="gray_btn" id="nextbutton">홈페이지</a>
        </div>
    </div>
</div>

</div>


<?php include "./includes/footer.php";?>

</body>
</html>
