
function uiInit(){
    uiRdCh();
    uiFile();
    uiFileBtn();
    uiInpClear();
    uiNumber();
    uiSelPlaceholder();
    uiEmail();
    uiNumberCount();
    uiPlaceholderLine();
    uiActMail();
}

/*
elBool                : element 있는지 여부 true false 반환
@param{$(element)} el : 선택된 element 있는지 여부
*/

function elBool(el){
    if(el.length < 1){ return true}else{ return false}
}


/*
uiNumber             : attr [data-inp-type=number] 인 경우 숫자만 입력 받을 수 있게
*/

function uiNumber(){
    var filter = "win16|win32|win64|mac|macintel";
    if ( navigator.platform ) {
         if ( filter.indexOf( navigator.platform.toLowerCase() ) < 0 ) {
            //mobile
            $("[data-inp-type=number]").not('[type=number]').each(function(){
                $(this).attr("type", "number");
            });
        }
    }
    $("[data-inp-type=number]").off();
    $(document).on("keyup", "[data-inp-type=number]", function(){
        var maxLen = $(this).attr("maxlength")|| -1;
        var val    = ($(this).val()).replace(/[^0-9]/g,'') ;
        if(maxLen < 0){
            $(this).val( val );
        }else{
            $(this).val( val.substr(0, maxLen) );
        }
    });

    $("[data-inp-type=phon]").off();
    $(document).on("keyup", "[data-inp-type=phon]", function(event){
        var maxLen = $(this).attr("maxlength")|| -1;
        var val    = ($(this).val()).replace(/[^0-9\-]/g,'') ;

        if(maxLen < 0){
            $(this).val( val );
        }else{
            $(this).val( val.substr(0, maxLen) );
        }
    });

}

/*
uiRdCh             : classNmae = .act_uiRdCh 인 경우 체크 or 라이오 ui 모양 변경
*/
function uiRdCh(){
    $(".act_uiRdCh").each(function(index, item){
        if( elBool($(item).find('_icon')) ){
            $(item).find("input").after("<span class='_icon'></span>")
        }
    })
}

/*
uiFile             : classNmae = .act_uiFile 인 file ui 모양 변경
*/
function uiFile() {
    $(".act_uiFile").each(function(index, item){
        var $file = $(item).find("[type=file]");
        var $box = $(item);
        var $btn = $box.find(".btn");

        if( $file.attr("disabled") ){
            $box.find(".inp").attr("disabled", "disabled")
        }
        if( $file.attr("readonly") ){
            $box.find(".inp").attr("readonly", "readonly")
        }
    });

    $(".act_uiFile").find(".btn").off();
    $(document).on("click", ".act_uiFile .btn", function(){
        var $file = $(this).closest(".act_uiFile").find("[type=file]");
        $file.trigger("click");
    });

    $(".act_uiFile").find(".inp").off();
    $(document).on("click", ".act_uiFile .inp", function(){
        var $file = $(this).closest(".act_uiFile").find("[type=file]");
        $file.trigger("click");
    });

    $(".act_uiFile").find("[type=file]").off();
    $(document).on("change", ".act_uiFile [type=file]", function(){
        var $box = $(this).closest(".act_uiFile");
        $box.find("> .inp").val( $(this).val());
    });
}
/*
uiFileBtn             : classNmae = .uiFileBtn 인 file ui 모양 변경
*/
function uiFileBtn() {
    $(".act_uiFileBtn").each(function(index, item){
        var $file = $(item).find("[type=file]");
        var $box = $(item);
        var $btn = $box.find(".btn");

        if( $file.attr("disabled") ){
            $box.find(".inp").attr("disabled", "disabled")
        }
        if( $file.attr("readonly") ){
            $box.find(".inp").attr("readonly", "readonly")
        }
    });

    $(".act_uiFileBtn").find(".btn").off();
    $(".act_uiFileBtn").find(".inp").off();
    $(document).on("click", ".act_uiFileBtn .btn, .act_uiFileBtn .inp", function(){
        var $file = $(this).closest(".act_uiFileBtn").find("[type=file]");
        $file.trigger("click");
    });

    $(".act_uiFileBtn").find("[type=file]").off();
    $(document).on("change", ".act_uiFileBtn [type=file]", function(){
        var $file =  $(this);
        var $box = $file.closest(".act_uiFileBtn");
        if($file.val() == null || $file.val() == ''){
            $box.find(".inp").removeClass('on');
        }else{
            $box.find(".inp").addClass('on');
        }
        $box.find(".inp").val( $file.val());
    });

}


/*
uiInpClear             : classNmae = .act_uiInpClear 인 input 내용 입력 시 제거 버튼 생성
*/
function uiInpClear(){

    $(".act_uiInpClear").each(function(index,item){

        if( elBool($(item).find('.inp_clear_btn')) ){
            $(item).find('input[data-clear="true"]').closest('.act_uiInpClear').append('<a href="#" class="inp_clear_btn">입력 값 제거</a>');
        }
        if($(item).find('input[data-clear="true"]').val() == '' || $(item).find('input[data-clear="true"]').val() == null){
            $(item).find('input[data-clear="true"]').next('.inp_clear_btn').removeClass('on');
        }else{
            $(item).find('input[data-clear="true"]').next('.inp_clear_btn').addClass('on');
        }
    });

   $('input[data-clear="true"]').off();
   $(document).on('keyup','input[data-clear="true"]', function(){
       if($(this).val() == '' || $(this).val() == null){
           $(this).next('.inp_clear_btn').removeClass('on');
        }else{
            $(this).next('.inp_clear_btn').addClass('on');
        }
    });

    $('.inp_clear_btn').off();
    $(document).on('click','.act_uiInpClear .inp_clear_btn', function(event){
        event.preventDefault();

        $(this).prev('input[data-clear="true"]').val('');
        $(this).removeClass('on');
    });
}

/*
uiSelPlaceholder     : classNmae = .sel_box select인 select 빈값일경우 색상 흐리기
*/
function uiSelPlaceholder(){
    $(".sel_box select").each(function(index,item){
        if($(this).val() == '' || $(this).val() == null){
            $(this).addClass('placeholder');
        }else{
            $(this).removeClass('placeholder');
        }
    });

    $(document).on('change','.sel_box select', function(){
        if($(this).val() == '' || $(this).val() == null){
            $(this).addClass('placeholder');
        }else{
            $(this).removeClass('placeholder');
        }
    });
}



/*
uiEmail             : classNmae = .uiFileBtn 인 file ui 모양 변경
*/
function uiEmail(){
    $(".act_uiEmail").find(".sel_box").off();
    $(document).on("change", ".act_uiEmail .sel_box select", function(){
        var $sel =  $(this);
        var $box = $sel.closest(".act_uiEmail");
        if($sel.val() == null || $sel.val() == ''){
            $box.find('.sel_box + .fm_inp .inp').removeAttr('readonly').val('').focus();
        }else{
            $box.find('.sel_box + .fm_inp .inp').attr('readonly', 'readonly').val($sel.val());
        }
    });
}


/*
uiNumberCount             : classNmae = .uiNumberCount  숫자 증가 증감
*/
function uiNumberCount() {
    $(".act_uiNumberCount").each(function(index,item){
        var $inp = $(item).find(".inp");
        if($inp.val() == '' || $inp.val() == null){
            $inp.val('0');
        }
    });
    $(".act_uiNumberCount").find(".minus_btn").off();
    $(document).on("click", ".minus_btn", function(){
        var $btn = $(this);
        var $box = $btn.closest(".act_uiNumberCount");
        var $inp = $box.find(".inp");
        var num = Number( $inp.val());
        var max = $inp.data('max');
        var min = $inp.data('min');

        if(num == 0 ){
            $inp.val(0);
        }else if (num > 0) {
            $inp.val( num - 1);
        }
    });
    $(".act_uiNumberCount").find(".plus_btn").off();
    $(document).on("click", ".plus_btn", function(){
        var $btn = $(this);
        var $box = $btn.closest(".act_uiNumberCount");
        var $inp = $box.find(".inp");
        var num = Number($inp.val());
        var max = $inp.data('max');
        var min = $inp.data('min');

        $inp.val( num + 1);
    });

}

/*
    attr [data-placeholder]          : placeholder 반응형
*/
function uiPlaceholderLine(){
    $('[data-placeholder]').each(function(index, item){
        var $el         = $(item);
        var datas       = $(item).data();
        
        if( elBool($el.find('._placeholder')) ){
            $el.find("input").after("<span class='_placeholder'>"+ datas.placeholder+"</span>")
        }
        if($el.find("input").val() != ''){
            $el.find("input").css('background', '#ffffff');
        }else{
            $el.find("input").removeAttr('style');
        }
    });
    $(document).off('[data-placeholder] input').on('blur', '[data-placeholder] input', function(){
        if($(this).val() != ''){
            $(this).css('background', '#ffffff');
        }else{
            $(this).removeAttr('style');
        }
    });
}
/*
.act_uiMail          : 이메일 1개의 input넣기
*/
function uiActMail(){
    $(document)
    .off('blur', '.act_uiMail ._mailText')
    .on('blur', '.act_uiMail ._mailText', function(){
        var $mailBox = $(this).closest('.act_uiMail'),
            text     = ($(this).val()).split('@');

        $mailBox.find('._mail1').val(text[0]);
        $mailBox.find('._mail2').val(text[1]);
        
    });

    $(document)
    .off('change', '.act_uiMail ._mailSel')
    .on('change', '.act_uiMail ._mailSel', function(){
        var $mailBox  = $(this).closest('.act_uiMail'),
            selText   = $(this).val(),
            text      = ($mailBox.find('._mailText').val()).split('@'),
            emailText = text[0] +'@'+ selText;
        
        $mailBox.find('._mail1').val(text[0]);
        $mailBox.find('._mail2').val(selText);
        $mailBox.find('._mailText').val(emailText);
    });
}