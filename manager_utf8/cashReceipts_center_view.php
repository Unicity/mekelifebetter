<?php
session_start();
?>

<?
include "./admin_session_check.inc";
include "./inc/global_init.inc";
include "../dbconn_utf8.inc";
include "./inc/common_function.php";

include "../AES.php";

$s_flag = str_quote_smart_session($s_flag);
$s_adm_id = str_quote_smart_session($s_adm_id);
$s_adm_dept = str_quote_smart_session($s_adm_dept);

$type = str_quote_smart($type);
$idVal = str_quote_smart($idVal);
$orderNo = str_quote_smart($RefundNo);
$cashNum = str_quote_smart($cashNum);

if($type=='new'){
    $s_date="";         // 매출일시
    $member_no="";      // 회원번호
    $member_name="";    //회원명
    $order_no="";       //주문번호
    $back_no="";       //반품번호
    $amount="0";        //금액
    $check_text="";     //신분확인내용
    $check_num="";      //신분확인번호
    $approval_num="";   //승인번호
    $cancel_no="";   //승인번호
    $check_result="";   //발행상태
    $cancel_reason="";  //취소사유
    $center=$s_adm_dept;         //센터명
}else if ($type=='modify'){
    $query="select * from tb_cashReceipts where 1 = 1 and order_no =".$orderNo ."  and member_no=".$idVal." and cash_num=".$cashNum;

    $result = mysql_query($query);
    $list = mysql_fetch_array($result);
    $s_date = $list[s_date];
    $check_result = $list[check_result];
    $member_no = $list[member_no];
    $member_name = $list[member_name];
    $order_no = $list[order_no];
    $back_no = $list[back_no];
    $amount = $list[amount];
    $check_text = $list[check_text];
    $check_num = $list[check_num];
    $approval_num = $list[approval_num];
    $cancel_no = $list[cancel_no];
    $cancel_reason = $list[cancel_reason];
    $center = $list[center];
    $cash_num=$list[cash_num];

}

$today = date("Ymd");

?>
<HTML>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta http-equiv="X-Frame-Options" content="deny" />
<link rel="stylesheet" href="inc/admin.css" TYPE="text/css">
<title><?echo $g_site_title?></title>
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

</head>
<body>
	<form name="frm_m" method="post">
        <input type='hidden' name='flag'>
        <input type='hidden' name='idVal' value='<?php echo $idVal?>'/>
        <input type='hidden' name='orderNo'value='<?php echo $orderNo?>'>
        <input type='hidden' name='cash_num'value='<?php echo $cash_num?>'>
        <table cellspacing="0" cellpadding="10" class="title">
			<tr>
				<td align="left"><b>현금영수증 센터</b></td>
                <td align="right"bgcolor=silver>
                    <input type="button" onclick="goBack();" value="목록" name="btnBack">&nbsp;
    				<input type="button" onclick="goDelete();" value="삭제" name="btnDelete">	
    			</td>
			</tr>
		</table>
        <table height='35' width='100%' cellpadding='0' cellspacing='0'border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
            <tr>
                <td align='center'>
					<table border="0" cellspacing="1" cellpadding="2" class="IN3">
                        <tr>
                            <th>매출일시</th>
                            <td><input type="text" name="s_date" maxlength="8"value="<?php echo $s_date?>" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');"></td>
                        </tr>
                        <tr>
                            <th>발행상태</th>
                            <td>
                                <select name="check_result" >
                                    <option value="" selected>선택</option>
                                    <option value="발행완료" <?if($check_result=='발행완료'){?>selected<?}?>>발행완료</option>
                                    <option value="발행취소" <?if($check_result=='발행취소'){?>selected<?}?>>발행취소</option>
                                    <option value="재발행완료" <?if($check_result=='재발행완료'){?>selected<?}?>>재발행완료</option>
                                </select>
                               
                            </td>
                        </tr>

                        <tr>
                            <th>회원번호</th>
                            <td><input type="text" name="member_no" maxlength="10"value="<?php echo $member_no?>"><input style="margin-left:10px;" type="button" value="회원검색" onkeyup="enterkey();" onclick="search()"></td>
                        </tr>
                        <tr>
                            <th>회원이름</th>
                            <td><input type="text" name="member_name" value="<?php echo $member_name?>" readonly="readonly"></td>
                        </tr>
                        <tr>
                            <th>주문번호</th>
                            <td><input type="text" name="order_no" value="<?php echo $order_no?>" maxlength="9" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');"></td>
                        </tr>
                        <tr>
                            <th><font color = "red"><b>반품번호</b></font></th>
                            <td><input type="text" name="back_no" value="<?php echo $back_no?>" maxlength="9" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');"></td>
                        </tr>
                        <tr>
                            <th>금액</th>
                            <td><input type="text" name="amount" value="<?php echo $amount?>" style="text-align:right;" onKeyup="inputNumberAutoComma(this)"></td>
                        </tr>
                        <tr>
                            <th>신분확인 방법</th>
                            <td>
                                <select name="check_text" onchange="resultChange()">
                                    <option value="" selected>선택</option>
                                    <option value="자진발급" <?if($check_text=='자진발급'){?>selected<?}?>>자진발급</option>
                                    <option value="휴대전화"<?if($check_text=='휴대전화'){?>selected<?}?>>휴대전화</option>
                                    <option value="사업자번호"<?if($check_text=='사업자번호'){?>selected<?}?>>사업자번호</option>
                                    <option value="주민등록번호"<?if($check_text=='주민등록번호'){?>selected<?}?>>주민등록번호</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>신분확인 번호</th>
                            <td><input type="text" name="check_num" value="<?php echo $check_num?>" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');"></td>
                        </tr>
                        <tr>
                            <th>승인번호</th>
                            <td><input type="text" name="approval_num" value="<?php echo $approval_num?>" maxlength="9" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');"></td>
                        </tr>
                        <tr>
                            <th><font color = "red"><b>취소승인번호</b></font></th>
                            <td><input type="text" name="cancel_no" value="<?php echo $cancel_no?>" maxlength="9" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');"></td>
                        </tr>
                        <tr>
                            <th>취소/재발행 사유</th>
                            <td><input type="text" name="cancel_reason" value="<?php echo $cancel_reason?>" style="width:300px;"></td>
                        </tr>
                        <tr>
                            <th>센터명</th>
                            <td><input type="text" name="center"  value="<?php echo $center?>" readonly="readonly"></td>
                        </tr>
                    </table>    
                </td>
            </tr>
            <tr>
                <td align="center">
                    <input type="button" name="save" value="저장" onclick="saveInfo();" style='display:none;'>
                    <input type="button" name="update" value="수정" onclick="updateInfo();" style='display:none;'>
                </td>
            </tr>
        </table>
	</form>
</body>
<script language="javascript">
    var dept = '<?php echo $s_adm_dept?>';
    var s_dept = '<?php echo $center?>';
    
    $(document).ready(function() {
        var type = '<?php echo $type?>';

        if(type == 'modify'){
            $('input[name=update]').css('display','block');
        }else if(type == 'new'){
            $('input[name=save]').css('display','block');
        }
    	
    });

    function goBack() {
		document.frm_m.target = "frmain";
		document.frm_m.action="cashReceipts_center.php";
		document.frm_m.submit();
	}

    function search(){
        var baId = $('input[name=member_no]').val();
        
		$.ajax({
			url: 'https://hydra.unicity.net/v5a/customers?unicity='+baId+'&expand=customer',
			/*
            headers:{
				'Content-Type':'application/json'
			},
            */
			type: 'GET',
			success: function(result) {
				console.log(result.items[0].href);
				if(typeof(result) != 'undefined' && typeof(result.items) != 'undefined' && result.items.length > 0) {
					var _oname = '';
					if(typeof(result.items[0].humanName['fullName@ko']) != 'undefined') {
						_oname = result.items[0].humanName['fullName@ko'];
					}
					if(_oname == '') {
						_oname = result.items[0].humanName.fullName;
					}
					$('[name=member_name]').val(_oname);
				}else{
				}		
				
			}, error: function() {
				alert('검색된 회원이 없습니다.');
			}
		});
    }

    function enterkey() {
        if (window.event.keyCode == 13) {
    
             // 엔터키가 눌렸을 때 실행할 내용
             search();
        }
    }

    function inputNumberAutoComma(obj) {
	       
           // 콤마( , )의 경우도 문자로 인식되기때문에 콤마를 따로 제거한다.
           var deleteComma = obj.value.replace(/\,/g, "");
   
           // 콤마( , )를 제외하고 문자가 입력되었는지를 확인한다.
           if(isFinite(deleteComma) == false) {
               alert("문자는 입력하실 수 없습니다.");
               obj.value = "";
               return false;
           }
          
           // 기존에 들어가있던 콤마( , )를 제거한 이 후의 입력값에 다시 콤마( , )를 삽입한다.
           obj.value = inputNumberWithComma(inputNumberRemoveComma(obj.value));
    }
       
       // 천단위 이상의 숫자에 콤마( , )를 삽입하는 함수
    function inputNumberWithComma(str) {

        str = String(str);
        return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, "$1,");
    }

    // 콤마( , )가 들어간 값에 콤마를 제거하는 함수
    function inputNumberRemoveComma(str) {

        str = String(str);
        return str.replace(/[^\d]+/g, "");
    }

    function saveInfo(){

        if(document.frm_m.s_date.value == ''){
            alert('매출날짜를 입력 하세요');
            return false;
        }else if (document.frm_m.member_no.value == ''){
            alert('회원번호를 입력 하세요');
            return false;
        }else if (document.frm_m.order_no.value == ''){
            alert('주문번호를 입력 하세요');
            return false;
        }else if (document.frm_m.amount.value == ''){
            alert('금액을 입력 하세요');
            return false;
        }else if (document.frm_m.check_text.value == ''){
            alert('신분 확인 방법을 선택 하세요');
            return false;
        }else if (document.frm_m.check_num.value == ''){
            alert('신분 확인 번호를 입력 하세요');
            return false;
        }else if (document.frm_m.approval_num.value == ''){
            alert('승인번호를 입력 하세요');
            return false;
        }else if (document.frm_m.check_result.value == ''){
            alert('발행상태를 선택 하세요');
            return false;
        }

        if(document.frm_m.order_no.value.length < 9){
            alert('주문번호를 다시 확인 해주세요');
            return false;
        }else if (document.frm_m.approval_num.value.length < 9){
            alert('승인번호를 다시 확인 해주세요');
            return false;
        }

        if(document.frm_m.check_result.value=='발행취소'){
           if(document.frm_m.back_no.value==''){
             alert('반품 번호는 필수 입니다.')
             return false;
           }else if (document.frm_m.cancel_reason.value==''){
            alert('취소 사유 필수 입니다.')
             return false;
           }else if (document.frm_m.cancel_no.value==''){
            alert('취소 승인 번호 필수 입니다.')
             return false;
           }  
        }

        document.frm_m.action= "./cashReceipts_center_save.php";
        document.frm_m.flag.value = 'save'
		document.frm_m.submit();
    }

    function updateInfo(){
        if(dept!=s_dept){
            alert('본인 DSC 데이터만 수정 할 수 있습니다.');
            return false;
        }

        if(document.frm_m.check_result.value=='발행취소'){
            if(document.frm_m.back_no.value==''){
             alert('반품 번호는 필수 입니다.')
             return false;
           }else if (document.frm_m.cancel_reason.value==''){
            alert('취소 사유 필수 입니다.')
             return false;
           }else if (document.frm_m.cancel_no.value==''){
            alert('취소 승인 번호 필수 입니다.')
             return false;
           }  
        }

        document.frm_m.action= "./cashReceipts_center_save.php";
        document.frm_m.flag.value = 'update'
		document.frm_m.submit();
    }

    function goDelete(){
        if(dept!=s_dept){
            alert('본인 DSC 데이터만 수정 할 수 있습니다.');
            return false;
        }

        if(confirm('<?php echo $member_name?>님 을 삭제 하시겠습니까?') == true) {
            document.frm_m.action= "./cashReceipts_center_save.php";
            document.frm_m.flag.value = 'delete'
		    document.frm_m.submit();
        }
    }

    function resultChange(){

        if(document.frm_m.check_text.value == '자진발급'){
            $('input[name=check_num]').val('0100001234');
        }
    }
   

</script>