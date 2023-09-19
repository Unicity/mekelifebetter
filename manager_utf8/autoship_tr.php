<?php session_start();?>
<?php
    include "./admin_session_check.inc";
    include "./inc/global_init.inc";
    include "../dbconn_utf8.inc";
    include "../AES.php";
    include "excel_modal.php";
    $r_status = str_quote_smart(trim($r_status));
    $idxfield = str_quote_smart(trim($idxfield));
    $qry_str = str_quote_smart(trim($qry_str));

    /**autoship DB연결 */
    $db_host = '54.180.152.178';
    $db_user = 'autoship';
    $db_passwd = 'inxide1!!';
    $db_name = 'autoship';
  
    $conn = mysql_connect($db_host,$db_user,$db_passwd) or die ("데이터베이스 연결에 실패!"); 
    mysql_select_db($db_name, $conn); // DB 선택 
    
    $query = "select count(*) from autoship_update where 1 = 1 ";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    $TotalArticle = $row[0];

    $query2 = "select * from autoship_update where 1 = 1 ";
    $result2 = mysql_query($query2);

?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-Frame-Options" content="deny" />
		<title>오토쉽 업데이트</title>
		<link rel="stylesheet" href="./inc/admin.css" type="text/css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <style>
            * {
            margin: 0;
            padding: 0;
            }

            .pop-layer .pop-container {
            padding: 20px 25px;
            }

            .pop-layer p.ctxt {
            color: #666;
            line-height: 25px;
            }

            .pop-layer .btn-r {
            width: 100%;
            margin: 10px 0 20px;
            padding-top: 10px;
            border-top: 1px solid #DDD;
            text-align: right;
            }

            .pop-layer {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            width: 410px;
            height: auto;
            background-color: #fff;
            border: 5px solid #000000;
            z-index: 10;
            }

            .dim-layer {
            display: none;
            position: fixed;
            _position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 100;
            }

            .dim-layer .dimBg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #000;
            opacity: .5;
            filter: alpha(opacity=50);
            }

            .dim-layer .pop-layer {
            display: block;
            }

            a.btn-layerClose {
            display: inline-block;
            height: 25px;
            padding: 0 14px 0;
            border: 1px solid #304a8a;
            background-color: #3f5a9d;
            font-size: 13px;
            color: #fff;
            line-height: 25px;
            }

            a.btn-layerClose:hover {
            border: 1px solid #091940;
            background-color: #1f326a;
            color: #fff;
            }
        </style> 
        
	</head>
    <body>
	
<?php include "common_load.php" ?>

        <form name="frmSearch" method="post">
            <table cellspacing="0" cellpadding="0 " class="title" border="0" width="100%">
				<tr>
					<td align="left"><b>오토쉽 업데이트</b></td>
					<td align="right">
                        <input type="button" value="엑셀 업로드" onclick="layer_popup('#layer1');" >
					</td>
				</tr>
			</table>
            <table cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
				<tr>		
					<th width="6%" style="text-align: center;">일시</th>
					<th width="6%" style="text-align: center;">오토쉽번호</th>
					<th width="6%" style="text-align: center;">회원번호</th>
					<th width="6%" style="text-align: center;">회원이름</th>
                    <th width="6%" style="text-align: center;">금액</th>
					<th width="6%" style="text-align: center;">결과</th>
                    <th width="6%" style="text-align: center;">재전송</th>
				</tr>
				
                <?php
					if ($TotalArticle) {
						while($obj = mysql_fetch_object($result2)) {
				?>
				<tr>
					<td style="width: 5%" align="center"><?echo $obj->u_date?></td>
					<td style="width: 5%" align="center"><?echo $obj->autoship_num?></td>
					<td style="width: 5%" align="center"><?echo $obj->member_no?></td>
                    <td style="width: 5%" align="center"><?echo $obj->member_name?></td>
					<td style="width: 5%" align="center"><?echo $obj->amount?></td>
					<td style="width: 5%" align="center"><?echo $obj->result?></td>
					<td style="width: 5%" align="center"><input type="button" value="재전송" onclick="reSend('<?echo $obj->member_name?>',
                                                                                                            '<?echo $obj->autoship_num?>',
                                                                                                            '<?echo $obj->authorization?>',
                                                                                                            '<?echo $obj->paymethod?>',
                                                                                                            '<?echo $obj->credit_num?>',
                                                                                                            '<?echo $obj->payer?>',
                                                                                                            '<?echo $obj->expires_date?>',
                                                                                                            '<?echo $obj->token?>',
                                                                                                            '<?echo $obj->order_href?>',
                                                                                                            '<?echo $obj->amount?>')"></td>

            </tr>
                    <?php }
                        }
                    ?>
			</table>
        </form> 
        <div id="layer1" class="pop-layer">
            <div class="pop-container">
                <div class="pop-conts">
                    
                    <form enctype="multipart/form-data" action="./excel_read_autoship.php" method="post">
                        <table border="1">	
                            <tr>		
                                <th style="background-color:#DCDCDC">파일</th>		
                                <td><input type="file" name="excelFile"/></td>	
                            </tr>	
                            <tr>		
                                <th style="background-color:#DCDCDC">등록</th>		
                                <td style="text-align:center;"><input type="submit" value="업로드"/></td>	
                            </tr>
                        </table>    
                    </form>
                    <div class="btn-r">
                        <a href="#" class="btn-layerClose">Close</a>
                    </div>
                    <!--// content-->
                </div>
            </div>
        </div>
    </body>
    <script language="javascript">
        function reSend(name,autoNum,authoNum,paymethod,creditNum,payer,expiresDate,token,orderHref,amount){
            
            var data={};

            if(paymethod=="BankWire"){
                    data = {
                        "amount": amount,
                        "type":"record",
                        "method":paymethod,
                        "authorization":authoNum,
                        "methodDetails":{
                        "bankName":paymethod,
                        "creditCardNumber":creditNum
                        }  
                    };
            }else{
                    data={  
                        "amount": amount,
                        "type":"record",
                        "method":paymethod,
                        "authorization":authoNum,
                        "methodDetails":{
                        "bankName":paymethod,
                        "creditCardNumber":creditNum,
                        "payer":payer,
                        "creditCardExpires":expiresDate,
                        "creditCardSecurityCode":"**"  
                        }
                    };
            }
            alert(JSON.stringify(data));
            $.ajax({
					'type':'POST',
					'headers':{'Content-Type':'application/json','Authorization':'Bearer ' + token},
					'url':orderHref+'/transactions',
					'data':data,
					'success':function (result) {
                        console.log(result);
					},
					'error':function (result) {
						console.log('fail'+JSON.stringify(result));
					}
				});
        }

        function layer_popup(el){
        
            var $el = $(el);    //레이어의 id를 $el 변수에 저장
            var isDim = $el.prev().hasClass('dimBg'); //dimmed 레이어를 감지하기 위한 boolean 변수

            isDim ? $('.dim-layer').fadeIn() : $el.fadeIn();

            var $elWidth = ~~($el.outerWidth()),
                $elHeight = ~~($el.outerHeight()),
                docWidth = $(document).width(),
                docHeight = $(document).height();

            // 화면의 중앙에 레이어를 띄운다.
            if ($elHeight < docHeight || $elWidth < docWidth) {
                $el.css({
                    marginTop: -$elHeight /2,
                    marginLeft: -$elWidth/2
                })
            } else {
                $el.css({top: 0, left: 0});
            }

            $el.find('a.btn-layerClose').click(function(){
                isDim ? $('.dim-layer').fadeOut() : $el.fadeOut(); // 닫기 버튼을 클릭하면 레이어가 닫힌다.
                return false;
            });

            $('.layer .dimBg').click(function(){
                $('.dim-layer').fadeOut();
                return false;
            });

        }

    
    </script>
</html>    
