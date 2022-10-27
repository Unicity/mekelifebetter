<?php
session_start();
?>

<?
include "../admin_session_check.inc";
include "../inc/global_init.inc";
include "../../dbconn_utf8.inc";
include "../inc/common_function.php";

include "../../AES.php";

$s_flag = str_quote_smart_session($s_flag);
$s_adm_id = str_quote_smart_session($s_adm_id);
$s_adm_dept = str_quote_smart_session($s_adm_dept);
$type = str_quote_smart($type);
$idVal = str_quote_smart($idVal);
$RefundNo = str_quote_smart($RefundNo);


if ($type == 'new') {
    $dsc = getDSCName($s_adm_dept);

    $postDate = "";
    $baId = "";
    $baName = "";
    $comissionPeriod = "";
    $orderNo = "";
    $returnNo = "";
    $orderDate = "";
    $orderTotal = "";
    $rtnPvAdj = 0;
    $rtnPvVol = 0;
    $rtnAmtAdj = 0;
    $rtnAmtVol = 0;
    $deductCommision = 0;
    $deductCardFee = 0;
    $shopId = "";
    $cardBankType = "";
    $cardAccountNo = "";
    $expireDate = "";
    $installment = 0;
    $approvalAmount = "";
    $approvalNo = "";
    $cashAmount = "";
    $cardAmount = "";
    $etc = "";
    $reApprovalAmount = "";
    $reApprovalNo = "";
    $processDate = "";

    // logging($s_adm_id,'add new Refund Info ');
} else {

    $query = "select * from tb_refund_header rh join tb_refund_line rl on rh.refundID = rl.refundID where rh.memberID ='" . $idVal . "'  and rl.RefundNo ='" . $RefundNo . "'";
    //echo  $query;
    $result = mysql_query($query);
    $list = mysql_fetch_array($result);

    $postDate = $list[PostDate];
    $baId = $list[memberID];
    $baName = $list[memberName];
    $comissionPeriod = $list[CommissionPeriod];
    $orderNo = $list[OrderNo];
    $returnNo = $list[ReturnNo];
    $orderDate = $list[OrderDate];
    $orderTotal = $list[OrderTotal];
    $rtnPvAdj = $list[ReturnPVAdjustment];
    $rtnPvVol = $list[ReturnPV];
    $rtnAmtAdj = $list[ReturnAmountAdjustment];
    $rtnAmtVol = $list[ReturnAmount];
    $deductCommision = $list[Commission];
    $deductCardFee = $list[CardFee];
    $shopId = $list[StoreID];
    $cardBankType = $list[StoreName];
    $cardAccountNo = $list[CardNumber];
    $expireDate = $list[ExpireDate];
    $installment = $list[Installment];
    $approvalAmount = $list[ApprovalAmount];
    $approvalNo = $list[ApprovalNumber];
    $cashAmount = $list[cashAmount];
    $cardAmount = $list[cardAmount];
    $etc = $list[Comments];
    $reApprovalAmount = $list[ReApprovalAmount];
    $reApprovalNo = $list[ReApprovalNumber];
    $processDate = $list[LastModifiedDate];
    $dsc = $list[CreatedDSC];
    
    $postDate = date("Ymd", strtotime($postDate));
    $orderDate = date("Ymd", strtotime($orderDate));
    $comissionPeriod = preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $comissionPeriod);
    $expireDate = preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $expireDate);
    
    $cardAccountNo = decrypt($key, $iv, $cardAccountNo);
   
}

function getDSCName($dept)
{
    $deptName = array(
        "Ansan DSC" => "안산DSC",
        "Daejeon DSC" => "대전DSC",
        "Gwangju DSC" => "광주DSC",
        "Daegu DSC" => "대구DSC",
        "Seoul DSC" => "서울DSC",
        "Busan DSC" => "부산DSC",
        "Incheon DSC" => "인천DSC",
        "Wonju DSC" => "원주DSC",
		"Jeju DSC" => "제주 DSC",
        "IT" => "IT",
        "Legal" => "법무팀",
        "CS" => "콜센터",
        "Sales" => "영업부",
        "Finance" => "재경부",
        "PR" => "PR",
        "Operation" => "OP"
    );
    return $deptName[$dept];
}

$today = date("Ymd");

?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta http-equiv="X-Frame-Options" content="deny" />
<LINK rel="stylesheet" HREF="../inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<SCRIPT language="javascript">
var idx ="";
var num1 = 1;
 	function goBack() {
		document.frm_m.target = "frmain";
		document.frm_m.action="refund_list.php";
		document.frm_m.submit();
	}
	function updateInfo() {


		if($('#cardAccountNo'+idx).val()=='' ){
			alert("추가 카드내역을 입력 하세요 ");
			return false;
		}	

		
		if(frm_m.postDate.value == '' || frm_m.postDate.value == null ){
			alert("postDate를 입력 하세요");
			frm_m.postDate.focus();
			return false;
		}else if (frm_m.baId.value == '' || frm_m.baId.value == null){
			alert("회원번호를 입력 하세요");
			frm_m.baId.focus();
			return false;
		}else if (frm_m.comissionPeriod.value == '' || frm_m.comissionPeriod.value == null){
			alert("커미션 기간을 입력 하세요");
			frm_m.comissionPeriod.focus();
			return false;
		}else if (frm_m.orderNo.value == '' || frm_m.orderNo.value == null){
			alert("주문번호를  입력 하세요");
			frm_m.orderNo.focus();
			return false;
		}else if (frm_m.returnNo.value == '' || frm_m.returnNo.value == null){
			alert("반품 번호를 입력 하세요");
			frm_m.returnNo.focus();
			return false;
		}else if (frm_m.orderDate.value == '' || frm_m.orderDate.value == null){
			alert("주문일을 입력 하세요");
			frm_m.orderDate.focus();
			return false;
		}else if (frm_m.orderTotal.value == '' || frm_m.orderTotal.value == null){
			alert("주문 금액을 입력 하세요");
			frm_m.orderTotal.focus();
			return false;
		}else if (frm_m.rtnPvVol.value == '' || frm_m.rtnPvVol.value == null){
			alert("RTN Volume를 입력 하세요");
			frm_m.rtnPvVol.focus();
			return false;
		}else if (frm_m.rtnAmtVol.value == '' || frm_m.rtnAmtVol.value == null){
			alert("RTN Payment 입력 하세요");
			frm_m.rtnAmtVol.focus();
			return false;
		}
						


		if(frm_m.comissionPeriod.value.length != '6' || frm_m.comissionPeriod.value.length != '6'){
		 	alert("커미션기간을  바르게 입력 해 주세요.\n 예)190001");
		 	return false;
		}

		if(frm_m.orderDate.value.length != '8' || frm_m.orderDate.value.length != '8'){
		 	alert("주문일자를  바르게 입력 해 주세요.\n 예)19000101");
		 	return false;
		}

		if(frm_m.postDate.value < frm_m.orderDate.value){
			alert("주문일자를 바르게 입력하세요");
			return false;
		}	

		 

		// 음수 체크를 위하여 콤마 제거    
		frm_m.rtnAmtAdj.value=inputNumberRemoveComma(frm_m.rtnAmtAdj.value)
	    frm_m.rtnAmtVol.value=inputNumberRemoveComma(frm_m.rtnAmtVol.value)
	    frm_m.rtnPvVol.value=inputNumberRemoveComma(frm_m.rtnPvVol.value)
	    frm_m.rtnPvAdj.value=inputNumberRemoveComma(frm_m.rtnPvAdj.value)
	
		
		
		//음수 체크
		if(isPositive(frm_m.rtnPvAdj.value) == true){
			frm_m.rtnPvAdj.value = "-"+frm_m.rtnPvAdj.value
		}

		if(isPositive(frm_m.rtnPvVol.value) == true){
			frm_m.rtnPvVol.value = "-"+frm_m.rtnPvVol.value
		}

		if(isPositive(frm_m.rtnAmtAdj.value) == true){
			frm_m.rtnAmtAdj.value = "-"+frm_m.rtnAmtAdj.value
		}

		if(isPositive(frm_m.rtnAmtVol.value) == true){
			frm_m.rtnAmtVol.value = "-"+frm_m.rtnAmtVol.value
		}

		
			frm_m.target = "frmain";
			document.frm_m.action="refund_update.php";
			document.frm_m.submit();
	}
	
	function addACard() {
		idx = jQuery('tr td input').size();

		num1++; // 버튼 클릭시 1씩 증감

		var html = 
				 "<tbody name='additional'>"
				+"<tr> <th rowspan='9' >"
				+"			Deduction    "
				+"		</th>			 "
				+"  	<th colspan='2'>Commission</th> "
				+"  	<td><input type='number' name='deductCommision[]' value='0'></td></tr>"
				+" "
			 
				+"		<tr><th colspan='2'>Card Fee </th> "
				+"	    <td><input type='number' name='deductCardFee[]' value='0'></td>"
				+"</tr>"
				+"<tr>"
				+"	<th rowspan='7'>결제정보</th><th>상점ID</th> "

				+"	<td><select name='cardBankType[]' id='cardBankType1"+idx+"' onchange='cardBankTypeChg1("+idx+")'> <option value='new'>신상점</option> <option value='internet'>인터넷</option> <option value='allat'>올앳</option>	<option value='bankwire'>무통장</option>	</select></td>"
				+"</tr>"
				+"<tr>"
				+"	<th>Card/Bank</th>"
				+"	<td><input type='text' name='shopId[]' id='shopId"+idx+"' maxlength='10' value=''></td>"
				+"</tr> "
				+"<tr> "
				+"	<th>Card/Account#</th>"
				+"	<td><input type='text' name='cardAccountNo[]' id='cardAccountNo"+idx+"' maxlength='16' value=''></td>"
				+"</tr>"
				+"<tr>"
				+"	<th>유효기간</th>"
				+"	<td><input type='text' id='expireDateId"+idx+"' name='expireDate[]' maxlength='7' value=''></td>"
				+"</tr>"
				+"<tr>"
				+"	<th>할부기간</th>"
				+"	<td><input type='number' id='installment"+idx+"' name='installment[]' value='0'></td>"
				+"</tr>"
				+"<tr>"
				+"	<th>승인금액</th>"
				+"	<td><input type='text' name='approvalAmount[]' value='' onKeyup='inputNumberAutoComma(this)' style='text-align: right'></td>"
				+"</tr>"
				+"<tr>"
				+"	<th>승인번호</th>"
				+"	<td><input type='text' id='approvalNo"+idx+"' name='approvalNo[]' value=''></td>"
				+"</tr>"
				+"<tr>"
				+"	<th rowspan='2' colspan='2'>Payment</th>"
				+"	<th>Cash</th>"
				+"	<td><input type='text' id='cashAmount"+idx+"' name='cashAmount[]' value='' onKeyup='inputNumberAutoComma(this)' style='text-align: right'></td>"
				+"</tr>"
				+"<tr>"
				+"	<th>Card</th>"
				+"  <td><input type='text' id='cardAmount"+idx+"' name='cardAmount[]' value='' onKeyup='inputNumberAutoComma(this)' style='text-align: right'></td>";
			 	+"</tr>"
			 	+"<tr>"
				+"	<th colspan='3'>ETC</th>"
				+"	<td><input type='text' name='etc[]' maxlength='150' value=''></td>"
				+"</tr>";
				
				+"</tbody>";
		
		//var existingHTML	 = document.getElementById('additional').innerHTML
		//document.getElementById('additional').innerHTML= existingHTML+html;

		  var trHtml = $( "tbody[name=additional]:last" );   
		   trHtml.after(html); 
		    
			if(num1 >1){
				$('[name=deleteCard]').css("display","block");
			}	
	}

		

	function deleteACard() {
		num1--;
		   var trHtml1 =$( "tbody[name=additional]:last" );
	        trHtml1.remove(); //tr 테그 삭제
	        if(num1 <=1){
				$('[name=deleteCard]').css("display","none");
			}	 
	}	

    function enterkey() {
        if (window.event.keyCode == 13) {
    
             // 엔터키가 눌렸을 때 실행할 내용
             search_member();
        }
    }




	function search_member() {
		var id = $('[name=baId]').val();
		
		$.ajax({
			url: 'https://hydra.unicity.net/v5a/customers?unicity='+id+'&expand=customer',
			headers:{
				'Content-Type':'application/json'
			},
			type: 'GET',
			success: function(result) {
				console.log(result);
				console.log(result.items[0].href);
				if(typeof(result) != 'undefined' && typeof(result.items) != 'undefined' && result.items.length > 0) {
					var _oname = '';
					if(typeof(result.items[0].humanName['fullName@ko']) != 'undefined') {
						_oname = result.items[0].humanName['fullName@ko'];
					}
					if(_oname == '') {
						_oname = result.items[0].humanName.fullName;
					}
					$('[name=baName]').val(_oname);
				}else{
				}		
				
			}, error: function() {
				alert('검색된 회원이 없습니다.');
			}
		});
		
	}

	function cardBankTypeChg(){
		
		if(frm_m.cardBankType.value=='bankwire'){
			
			$('#expireDateId').css("display","none");
			$('#installmentId').css("display","none");
			$('#approvalNo').css("display","none");
			$('#cashAmount').css("display","block");
			$('#cardAmount').css("display","none");
		}else{
			$('#expireDateId').css("display","block");
			$('#installmentId').css("display","block");
			$('#approvalNo').css("display","block");
			$('#cashAmount').css("display","none");
			$('#cardAmount').css("display","block");
			
		}		
	}	

	function cardBankTypeChg1(idx){

		if($('#cardBankType1'+idx).val()=='bankwire'){
		
			$('#expireDateId'+idx).css("display","none");
			$('#installment'+idx).css("display","none");
			$('#approvalNo'+idx).css("display","none");
			$('#cashAmount'+idx).css("display","block");
			$('#cardAmount'+idx).css("display","none");
			
		}else{

			$('#expireDateId'+idx).css("display","block");
			$('#installment'+idx).css("display","block");
			$('#approvalNo'+idx).css("display","block");
			$('#cashAmount'+idx).css("display","none");
			$('#cardAmount'+idx).css("display","block");
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


    function input_comma(sfield) 
	{
		if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) 
			|| (event.keyCode == 188) || (event.keyCode == 190) || (event.keyCode == 110) || (event.keyCode == 8) || (event.keyCode == 46))
		{			
			sfield.value = remove_comma(sfield);
			money = sfield.value;
			var tmpH="";
			if(money.charAt(0)=="-")
			{
				tmpH=money.substring(0,1);
				money=money.substring(1,money.length);
			}
		
			for (; money.indexOf("-") != -1 ;) 
			{ 
				money = money.replace("-","")
			}
		
			belowzero = "";
			if (check_dot(money)==true)
			{
				arr = money.split(".");
				money = arr[0];		
				belowzero = "." + arr[1];    
			}
			
			len = money.length ;
			result ="";
			for (i=0; i < len;i++)
			{
				comma="";
				schar = money.charAt(i);
				where = len - 1 - i;
				if ( ( where % 3 == 0) && (len > 3) && ( where != 0 )) 
				{
					comma = ",";	
				}
				result = result +   schar + comma ;
			}
			if(tmpH)
			{
 				result = tmpH + result;
	 		}

			sfield.value = result + belowzero;			
			
	   	}	
		return true;
	}

	function remove_comma(sfield)
	{
		money = sfield.value;
		var arr;
		arr = money.split(",");
		len = arr.length;	
		result = "";
		for (k=0; k < len; k++) 
		{
			result = result + arr[k];
		}
		return result;
	}	

	function check_dot(v_value)
	{
		v_len= v_value.length;
		for (var i=0; i< v_len; i++) 
		{
			schar = v_value.charAt(i);
			if (schar == "." )
			{
				return true;
			}
		}
		return false;
	}
		
	function onlyNumber() //onKeyPress 이벤트 기준
	{ 
  		if ( ((event.keyCode < 48) || (57 < event.keyCode) && (188 != event.keyCode)) && (45 != event.keyCode) 
  			&& (190 != event.keyCode) && (110 != event.keyCode) && (109 != event.keyCode) && (46 != event.keyCode)) 
  		{
  			event.returnValue=false;
  		}
	}


	function isPositive (num) {
		  return num >= 0;
		};
	
</SCRIPT>
</HEAD>
<body>
	<form name="frm_m" method="post">

		<table cellspacing="0" cellpadding="10" class="title">
			<tr>
				<td align="left"><b>반품 데이터 관리</b></td>
				<td align="right" width="300" align="center" bgcolor=silver><input
					type="button" onclick="goback();" value="목록" name="btn4"></td>
			</tr>
		</table>
		<table height='35' width='100%' cellpadding='0' cellspacing='0'
			border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF'
			bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
			<tr>
				<td align='center'>
					<table border="0" cellspacing="1" cellpadding="2" class="IN3">
						<tr>
							<th colspan="3">Postdate</th>
						<?php if($type != 'modify'){?>
							<td><input type="text" name="postDate" maxlength="10" readonly="readonly" value="<?php echo $today?> "></td>
						<?php }else{?>	
							<td><input type="text" name="postDate" maxlength="10"value="<?php echo $postDate?>"></td>
						<?php }?>
						</tr>
						<tr>
							<th colspan="3">BA#</th>
							<td>
						<?php if($type != 'modify'){?>
						<input type="text" name="baId" maxlength="10"
								value="<?php echo $baId?>" onkeyup="enterkey();"> <a
								href="#none" onclick="search_member(); return false;">FO 아이디 검증</a>
						<?php }else{?>
							<input type="text" name="baId" maxlength="10"
								value="<?php echo $baId?>" readonly="readonly">
						<?php }?>
					</td>

						</tr>
						<tr>
							<th colspan="3">Name</th>
							<td><input type="text" name="baName" maxlength="15"
								value="<?php echo $baName?>" readonly="readonly"></td>
						</tr>
						<tr>
							<th colspan="3">Commission Period(커미션기간)</th>
							<td><input type="text" name="comissionPeriod" maxlength="7"
								value="<?php echo $comissionPeriod?>"></td>
						</tr>
						<tr>
							<th colspan="3">주문번호(ORD#)</th>
							<td><input type="number" name="orderNo" maxlength="12"
								value="<?php echo $orderNo?>"></td>
						</tr>
						<tr>
							<th colspan="3">반품번호(RTN#)</th>
							<td><input type="number" name="returnNo" maxlength="12"
								value="<?php echo $returnNo?>"></td>
						</tr>
						<tr>
							<th colspan="3">주문일</th>
							<td><input type="text" name="orderDate" maxlength="10"
								value="<?php echo $orderDate?>"></td>
						</tr>
						<tr>
							<th colspan="3">주문금액</th>
							<td><input type="text" name="orderTotal"  onKeyup="inputNumberAutoComma(this)" style="text-align: right"
								value="<?php echo $orderTotal?>"></td>
						</tr>
						<tr>
							<th rowspan="2" colspan="2">RTN Total PV</th>
							<th>Adjustment</th>
							<td><input type="text" name="rtnPvAdj" onKeyPress="onlyNumber();" onKeyUp="input_comma(this);" style="text-align: right"
								value="<?php echo $rtnPvAdj?>"></td>
						</tr>
						<tr>
							<th>Volume</th>
							<td><input type="text" name="rtnPvVol" onKeyPress="onlyNumber();" onKeyUp="input_comma(this);" style="text-align: right"
								value="<?php echo $rtnPvVol?>"></td>
						</tr>
						<tr>
							<th rowspan="2" colspan="2">RTN Total Amount</th>
							<th>Adjustment</th>

							<td><input type="text" name="rtnAmtAdj" onKeyPress="onlyNumber();" onKeyUp="input_comma(this);" style="text-align: right"
								value="<?php echo $rtnAmtAdj?>"></td>
						</tr>
						<tr>
							<th>payment</th>

							<td><input type="text" name="rtnAmtVol" onKeyPress="onlyNumber();" onKeyUp="input_comma(this);" style="text-align: right"
								value="<?php echo $rtnAmtVol?>"></td>
						</tr>
						<tr>
							<th rowspan="9">Deduction</th>
							<th colspan="2">Commission</th>

							<td><input type="number" name="deductCommision[]"
								value="<?php echo $deductCommision?>"></td>
						</tr>
						<tr>
							<th colspan="2">Card Fee</th>
							<td><input type="number" name="deductCardFee[]"
								value="<?php echo $deductCardFee?>"></td>
						</tr>
						<tr>
							<th rowspan="7">결제정보</th>
							<th>상점ID</th>
							<td>
								<select name="cardBankType[]" id="cardBankType" onchange="cardBankTypeChg()">
									<option value='new' <?if($cardBankType=='internet'){?>selected<?}?>>신상점</option>
									<option value='internet' <?if($cardBankType=='internet'){?>selected<?}?>>인터넷</option>
									<option value='allat' <?if($cardBankType=='allat'){?>selected<?}?>>올앳</option>
									<option value='bankwire'<?if($cardBankType=='bankwire'){?>selected<?}?>>무통장</option>		
								</select>
							</td>
						</tr>
						<tr>
							<th>Card/Bank</th>
							<td><input type="text" name="shopId[]" maxlength="10"
								value="<?php echo $shopId?>"></td>
						</tr>
						<tr>
							<th>Card/Account#</th>
							<td><input type="text" name="cardAccountNo[]" maxlength="16"
								value="<?php echo $cardAccountNo?>"></td>
						</tr>
						<tr>
							<th>유효기간</th>
							<td><input type="text" id="expireDateId" name="expireDate[]" maxlength="7"
								value="<?php echo $expireDate?>"></td>
						</tr>
						<tr>
							<th>할부기간</th>
							<td><input type="number" id="installmentId" name="installment[]"
								value="<?php echo $installment?>"></td>
						</tr>
						<tr>
							<th>승인금액</th>
							<td><input type="text" name="approvalAmount[]" onKeyup="inputNumberAutoComma(this)" style="text-align: right"
								value="<?php echo $approvalAmount?>"></td>
						</tr>
						<tr>
							<th>승인번호</th>
							<td><input type="text" id="approvalNo" name="approvalNo[]"
								value="<?php echo $approvalNo?>"></td>
						</tr>
						<tr>
							<th rowspan="2" colspan="2">Payment</th>
							<th>Cash</th>

							<td><input type="text" id="cashAmount" name="cashAmount[]" onKeyup="inputNumberAutoComma(this)" style="text-align: right"
								value="<?php echo $cashAmount?>"></td>
						</tr>
						<tr>
							<th>Card</th>

							<td><input type="text" id="cardAmount" name="cardAmount[]" onKeyup="inputNumberAutoComma(this)" style="text-align: right"
								value="<?php echo $cardAmount?>"></td>
						</tr>
						<tr>
							<th colspan="3">ETC</th>
							<td><input type="text" name="etc[]" maxlength="150"
								value="<?php echo $etc?>"></td>
						</tr>

						<tbody name="additional"></tbody>
					<?php if($type == 'new'){?>
						<tr>
							<th colspan="3">카드추가</th>
							<td><input type="button" name="addCard" onclick="addACard();"value="Add" />
							<input type="button" name="deleteCard" onclick="deleteACard();"value="delete" style="display: none;" /></td>
							
						</tr>
					<?php }?>
		
				<tr>
							<th colspan="3">DSC</th>
							<td><input type="text" name="dsc" maxlength="7" readonly="readonly"
								value="<?php echo $dsc?>"></td>
						</tr>
						 
						<tr style="display: none;">
							<th rowspan="2" colspan="2">재승인</th>
							<th>금액</th>

							<td><input type="number" name="reApprovalAmount"
								value="<?php echo $reApprovalAmount?>"></td>
						</tr>
						<tr style="display: none;">
							<th>승인번호</th>

							<td><input type="number" name="reApprovalNo"
								value="<?php echo $reApprovalNo?>"></td>
						</tr>
						
						<tr style="display: none;">
							<th colspan="3">처리일(yyyy/mm/dd)</th>
							<td><input type="text" name="processDate" maxlength="10"
								value="<?php echo $processDate?>"></td>
						</tr>
					</table>

				</td>
			</tr>
			<tr>
				<td colspan=2><input type="hidden" name="processType"
					value="<?php echo $type?>"> <input type="hidden" name="adminID"
					value="<?php echo $s_adm_id?>"> <input type="hidden"
					name="RefundNo" value="<?php echo $RefundNo?>"></td>
			</tr>

			<tr>
				<td align=center>
			<?php
// if( $status != "30") {
// if ($s_flag == "3" || $s_flag == "1" || $s_flag == "5") { ?>
					<input type="button" name="save" value="저장" onclick="updateInfo();">
					<input type="button" value="목록" onclick="goBack();"> 
			<?php //} ?>
			<?php 
				//if ($s_flag == "8" || $s_flag =="1") { ?>
					
					
			<?php //} } ?>
			 
		</td>

			</tr>
		</table>
	</form>
</body>
</html>
<?
mysql_close($connect);
?>