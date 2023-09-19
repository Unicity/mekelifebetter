<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";
    $s_no			= str_quote_smart(trim($s_no));

    $query = "select * from tb_smember where s_no = '".$s_no."'";
	
    $result = mysql_query($query);
    $list = mysql_fetch_array($result);

    $start_date = $list[start_date];
	$member_no = $list[member_no];
	$member_name = $list[member_name];
	$end_date = $list[end_date];
	$autoshipYn = $list[autoshipYn];
    $reg_status = $list[reg_status];
    $note = $list[note];

    $sDate = date("Y-m-d", strtotime($start_date));
	$eDate = date("Y-m-d", strtotime($end_date));


    if($reg_status == '2'){
        $stausVal = '일시정지(S)';
    }else if($reg_status == '3'){
        $stausVal = '복귀(A)';
    }else if($reg_status == '4'){
        $stausVal = '해지(T)';
    }else{
        $stausVal = ' ';
    }
?> 

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <meta http-equiv="X-Frame-Options" content="deny" />
        <link rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    </head>
    
    <body  onLoad="init();">
	
<?php include "common_load.php" ?>

        <form name="frm_m" method="post">
            <table cellspacing="0" cellpadding="10" class="TITLE">
                <tr>
                    <td align="left"><b>S회원관리</b></td>
                    <td align="right" width="300" align="center" bgcolor=silver>
                            <input type="button" onClick="goIn();" value="수정" name="btn3">
                        <input type="button" onClick="goBack();" value="목록" name="btn4">

                        <input type="hidden" name="page" value="<?echo $page?>">
                    </td>
                </tr>
            </table>
            <table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
                <tr>
	                <td align='center'>
		                <table border="0" cellspacing="1" cellpadding="2" class="IN">
                            <tr>
                                <th>
                                    일시정지(S)일자
                                </th>
				                <td> <input type="text" id="startDate" name="startDate" value="<?echo $sDate?>"> </td>
			                </tr>
                            <tr>
                                <th>
                                    회원번호
                                </th>
				                <td><?echo $member_no?></td>
			                </tr>
                            <tr>
                                <th>
                                    회원성명
                                </th>
				                <td><?echo $member_name?></td>
			                </tr>
                            <tr>
                                <th>
                                    회원쉽전환 알람일자
                                </th>
				                <td><input type="text" id="endDate" name="endDate" value="<?echo $eDate?>"></td>
			                </tr>
                            <tr>
                                <th>
                                    처리상태
                                </th>
				                <td>
                                    <select id="reg_status" name="reg_status">
                                        <option value='2'<?if($reg_status=="2"){?>selected<?}?>>일시정지(S)</option>
			                            <option value='3'<?if($reg_status=="3"){?>selected<?}?>>복귀(A)</option>
                                        <option value='4'<?if($reg_status=="4"){?>selected<?}?>>해지(T)</option>
                                    </select>
                                </td>
			                </tr>
                            <tr>
                                <th>
                                    오토쉽유무
                                </th>
				                <td><?echo $autoshipYn?></td>
			                </tr>
                            <tr>
                                <th>
                                    일시정지(S) 사유
                                </th>
				                <td><?echo $note?></td>
			                </tr>
                        </table>
                    </td> 
                </td>
            </table>  
            <input type="hidden" name="typeCheck" value="edit"> 
            <input type="hidden" name="s_no" value="<?echo $s_no?>">
            <input type="hidden" name="baId" value="">     
        </form>
    </body>
    <script>
        $( function() {
    		$( "#startDate" ).datepicker({dateFormat:"yymmdd",
										  dayNamesMin : ['일','월','화','수','목','금','토']
										});
			$( "#endDate" ).datepicker({dateFormat:"yymmdd",
										dayNamesMin : ['일','월','화','수','목','금','토']
										});
			
  		} );

        function goIn() {
		    if(confirm("수정 하시겠습니까?")){   
                document.frm_m.baId.value = "<?echo $member_no?>"
			    document.frm_m.action = "sMember_update.php";
			    document.frm_m.submit();
		    }
	}      

    function goBack() {
    		document.frm_m.target = "frmain";
    		document.frm_m.action="sMember.php";
    		document.frm_m.submit();
    	}
    </script>

	<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</html>