<?php session_start();?>
<?php
    include "./admin_session_check.inc";
    include "../dbconn_utf8.inc";
?>    

<html>
    <head>
    <meta charset="utf-8">
    <title>카드변경</title>
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <style>
        * {
        margin: 0;
        padding: 5;
        }

        body {
        margin: 2%;
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

        .pop-layer .btn-s {
        width: 100%;
        margin: 10px 0 20px;
        padding-top: 10px;
        text-align: right;
        float: left;
        }

        .pop-layer {
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        width: 70%;
        height: auto;
        background-color: #fff;
        border: 5px solid #3571B5;
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

        a.btn-layerSave {
        display: inline-block;
        height: 25px;
        padding: 0 14px 0;
        border: 1px solid #304a8a;
        background-color: #3f5a9d;
        font-size: 13px;
        color: #fff;
        line-height: 25px;
        }

        a.btn-layerSave:hover {
        border: 1px solid #091940;
        background-color: #1f326a;
        color: #fff;
        }
        a.btn-layerAdd {
        display: inline-block;
        height: 25px;
        padding: 0 14px 0;
        border: 1px solid #304a8a;
        background-color: #3f5a9d;
        font-size: 13px;
        color: #fff;
        line-height: 25px;
        }

        a.btn-layerAdd:hover {
        border: 1px solid #091940;
        background-color: #1f326a;
        color: #fff;
        }

        a.btn-layerDelete {
        display: inline-block;
        height: 25px;
        padding: 0 14px 0;
        border: 1px solid #304a8a;
        background-color: #3f5a9d;
        font-size: 13px;
        color: #fff;
        line-height: 25px;
        }

        a.btn-layerDelete:hover {
        border: 1px solid #091940;
        background-color: #1f326a;
        color: #fff;
        }
    </style>    
    </head>

    <body>
        <div style="text-align:center">
            <button name="addRow"><a href="#layer1" class="btn-input">입력</a></button>
            <button name="addRow"><a href="#" class="btn-search">검색</a></button>
        </div>   


        <div id="layer1" class="pop-layer">
            <div class="pop-container">
                <div class="pop-conts">
                    <form name="frm_m" method="post">
                        <input type='hidden' name="newValue" value="new">
                        <table width="100%" class="LIST" border="1">

                            <tr>
                                <th width="50%" style="text-align: center; background-color:#A6A6A6;">카드종류</th>
                                <td align="center"><input width="100%" type="text" class="form-control" id="card" name="card[]" placeholder="카드종류"></td>
                            </tr>
                            <tr>
                                <th width="2%" style="text-align: center; background-color:#A6A6A6;">카드번호</th>
                                <td align="center"><input type="number" class="form-control" id="cardNum" name="cardNum[]" placeholder="카드번호"></td>
                            </tr>    
                            <tr>
                                <th width="2%" style="text-align: center; background-color:#A6A6A6;">승인번호(*)</th>
                                <td align="center"><input type="number" class="form-control" id="approveNum" name="approveNum[]" placeholder="승인번호"></td>
                            </tr>    
                            <tr> 
                                <th width="2%" style="text-align: center; background-color:#A6A6A6;">금액(*)</th>
                                <td align="center"><input type="number" class="form-control" id="amount" name="amount[]" placeholder="금액"></td>
                            </tr>    
                            <tr> 
                                <th width="2%" style="text-align: center; background-color:#A6A6A6;">주문번호(*)</th>
                                <td align="center"><input type="number" class="form-control" id="orderNum" name="orderNum[]" placeholder="주문번호"></td>
                            </tr> 
                            <tr> 
                                <th width="2%" style="text-align: center; background-color:#A6A6A6;">비고</th>
                                <td align="center"><input type="text" class="form-control" id="note" name="note[]" placeholder="비고"></td>
                            </tr>  

                            <tr style='height:10px;'></tr>
                            <tbody name="additional"></tbody>      
                    
                            
                        </table>        
                    </form>

                    <div class="btn-r">
                        <a href="#" class="btn-layerAdd">추가</a>
                        <a href="#" class="btn-layerDelete">삭제</a>
                        <a href="#" class="btn-layerSave">저장</a>
                        <a href="#" class="btn-layerClose">닫기</a>
                    </div>
        
                </div>
            </div>
        </div>

        <script>
            var num1=1;
            $('.btn-search').click(function(){
                alert("오늘 날짜로 검색이 됩니다.");
                frm_m.target = "frmain";
                document.frm_m.action="cardChangeSearch.php";
                document.frm_m.submit();
                

            });
            $('.btn-input').click(function(){
                var $href = $(this).attr('href');
                layer_popup($href);
            });


            function layer_popup(el){

                var $el = $(el);    
                var isDim = $el.prev().hasClass('dimBg'); 

                isDim ? $('.dim-layer').fadeIn() : $el.fadeIn();

                var $elWidth = ~~($el.outerWidth()),
                    $elHeight = ~~($el.outerHeight()),
                    docWidth = $(document).width(),
                    docHeight = $(document).height();

      
                if ($elHeight < docHeight || $elWidth < docWidth) {
                    $el.css({
                        marginTop: -$elHeight /2,
                        marginLeft: -$elWidth/2
                    })
                } else {
                    $el.css({top: 0, left: 0});
                }

                $el.find('a.btn-layerClose').click(function(){
                    isDim ? $('.dim-layer').fadeOut() : $el.fadeOut(); 
                    return false;
                });

                $el.find('a.btn-layerSave').click(function(){
                    if(frm_m.approveNum.value == '' || frm_m.approveNum.value == null ){
			            alert("승인 번호를 입력 하세요");
			            frm_m.approveNum.focus();
			            return false;
		            }else if(frm_m.amount.value == '' || frm_m.amount.value == null){
                        alert("금액을 입력 하세요");
			            frm_m.amount.focus();
			            return false;   
                    }else if(frm_m.orderNum.value == '' || frm_m.orderNum.value == null){
                        alert("주문번호를 입력 하세요");
			            frm_m.orderNum.focus();
			            return false;   
                    }
                    if (confirm("저장 하시겠습니까?")) {
                        frm_m.target = "frmain";
                        document.frm_m.action="cardChangeSave.php";
                        document.frm_m.submit();

                    }

                });

                $('.layer .dimBg').click(function(){
                    $('.dim-layer').fadeOut();
                    return false;
                });

                $el.find('a.btn-layerAdd').click(function(){
                    var idx = jQuery('tr td input').size();
                    num1++; 
                    var html = 
                        "<tbody name='additional'>"
                            +"<tr>"
                            +"	<th style='text-align: center; background-color:#A6A6A6;'>카드종류</th>"
                            +"	<td align='center'><input type='text' id='card"+idx+"' name='card[]'></td>"
                            +"</tr>"
                            +"<tr>"
                            +"	<th style='text-align: center; background-color:#A6A6A6;'>카드번호</th>"
                            +"	<td align='center'><input type='text' id='cardNum"+idx+"' name='cardNum[]'></td>"
                            +"</tr>"
                            +"<tr>"
                            +"	<th style='text-align: center; background-color:#A6A6A6;'>승인번호(*)</th>"
                            +"	<td align='center'><input type='number' id='approveNum"+idx+"' name='approveNum[]'></td>"
                            +"</tr>"
                            +"<tr>"
                            +"	<th style='text-align: center; background-color:#A6A6A6;'>금액(*)</th>"
                            +"	<td align='center'><input type='number' id='amount"+idx+"' name='amount[]'></td>"
                            +"</tr>"
                            +"<tr>"
                            +"	<th style='text-align: center; background-color:#A6A6A6;'>주문번호(*)</th>"
                            +"	<td align='center'><input type='number' id='orderNum"+idx+"' name='orderNum[]'></td>"
                            +"</tr>"
                            +"<tr>"
                            +"	<th style='text-align: center; background-color:#A6A6A6;'>비고</th>"
                            +"	<td align='center'><input type='text' id='note"+idx+"' name='note[]'></td>"
                            +"</tr>"
                            +"<tr style='height:10px;'></tr>"
                        +"</tbody>";
                
                    var trHtml = $( "tbody[name=additional]:last" );   
                    trHtml.after(html); 
                    });


                    $el.find('a.btn-layerDelete').click(function(){
                        num1--;
                        var trHtml1 =$( "tbody[name=additional]:last" );
	                    trHtml1.remove(); //tr 테그 삭제
                    });
                    


            }

        </script>    
        <!--<button name="save">저장</button>
        <form name="frmSearch" method="post">
            <table cellspacing="1" cellpadding="5" class="LIST" border="1">
                <tr name="trStaff" bgcolor='#088A68'>
                    <th width="2%" style="text-align: center;">카드종류</th>
                    <th width="2%" style="text-align: center;">카드번호</th>
                    <th width="2%" style="text-align: center;">승인번호</th>
                    <th width="2%" style="text-align: center;">금액</th>
                    <th width="2%" style="text-align: center;">주문번호</th>
                    <th width="2%" style="text-align: center;">비고</th>
                    <th width="2%" style="text-align: center;">삭제</th>
                </tr>
                <script>
                    $(document).on("click","button[name=addRow]",function(){

                        var addStaffText =  
                            '<tr name="trStaff">'+
                            '    <td class="col-md-11"><input type="text" class="form-control" id="card" name="card" placeholder="카드종류"></td>'+
                            '    <td class="col-md-11"><input type="number" class="form-control" id="cardNum" name="cardNum" placeholder="카드번호"></td>'+
                            '    <td class="col-md-11"><input type="number" class="form-control" id="approveNum" name="approveNum" placeholder="승인번호"></td>'+
                            '    <td class="col-md-11"><input type="number" class="form-control" id="amount" name="amount" placeholder="금액"></td>'+
                            '    <td class="col-md-11"><input type="number" class="form-control" id="orderNum" name="orderNum" placeholder="주문번호"></td>'+
                            '    <td class="col-md-11"><input type="text" class="form-control" id="note" name="note" placeholder="비고"></td>'+
                            '    <td align="center"><button name="delRow">삭제</button></td>'+
                            '</tr>';
                            
                        var trHtml = $( "tr[name=trStaff]:last" ); 

                        trHtml.after(addStaffText);

                        });

            
                        $(document).on("click","button[name=delRow]",function(){
                            var trHtml = $(this).parent().parent();
                            trHtml.remove(); //tr 테그 삭제
                        });
                        $(document).on("click","button[name=save]",function(){ 
                            var rows = document.getElementById("trValue").getElementsByTagName("tr");
                            var frm = document.frmSearch;
                            var aa = [];
                            console.log(rows.length);//

                            for( var i=1; i<rows.length; i++ ){
                                
                                var cells = rows[i].getElementsByTagName("td");
                                console.log(cells[i].value);
                            }
                        });    

                </script>
            </table>        
        </form>
        -->
    </body> 
</html>      