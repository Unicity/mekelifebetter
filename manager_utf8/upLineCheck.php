<?
    include "./admin_session_check.inc";
    include "./inc/global_init.inc";
    include "../dbconn_utf8.inc";
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
        <title>제 3자 주문동의 확인</title>
    </head>    
    <body>

        <div style="text-align:center";><h1><u>제3자 주문 동의하기</u></h1></div>    
        <div style="float:left;margin-right:10px;"> <span>회원번호</span></div>    
        <div>
            <input type="text" name="memberNo" style="float:left;margin-right:10px;">
            <button onclick="goToUpline()">확인</button>
        </div>     
        <br/>    
        <div class="agree"></div>    

    </body>
    <script>
        function goToUpline(){ 
            var ids = $("input[name=memberNo]").val();
            if(confirm(ids+"님 조회 하시겠습니까?")){  
            var param = {memberNo : ids};

                $.ajax({
                        url: "upLineDetail.php",
                        async : false,
                        dataType : "json",
                        data:param,
                        type:"POST",
                        success	: function(result) {                
                            var href = result.href;
                            if(result.val==1){
                                var makeHtml ="<span>동의가 된 상태 입니다.</span>";
                                $(".agree").html(makeHtml);
                                return false;
                            }else{
                                var makeHtml ='<span>동의가 안된 상태 입니다.</span>&nbsp;<button onclick = "goToAgree('+"'"+href+"'"+')">마이비즈 제3자 주문 동의</button>';
                                $(".agree").html(makeHtml);
                                return false;
                            }
                        }
                });	
            }
        }

        function goToAgree(val){

            if(confirm("동의처리 하시겠습니까?")){  
                var forHref = {href : val};

                $.ajax({
                        url: "upLineDetail.php",
                        async : false,
                        dataType : "json",
                        data:forHref,
                        type:"POST",
                        success	: function(result) { 
                            if(result.hrefValue=='Upline'){
                                alert('동의처리 완료');
                            }else{
                                alert('정상 처리가 되지 않았습니다. IT 부서로 문의 해주세요');
                            }
                        }
                });	
            }        
  
        }

        function doNotReload(){
            if((event.ctrlKey == true && (event.keyCode == 78 || event.keyCode == 82))){
                event.keyCode = 0;
                event.cancelBubble = true;
                event.returnValue = false;
            }
        }
        document.onkeydown = doNotReload;
    </script>
</html>