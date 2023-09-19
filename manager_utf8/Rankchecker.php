<?session_start();
	ini_set("display_errors", 0);

	include_once($_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/str_check.php"); 


	
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>rankchecker</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/starter-template/">

    

    <!-- Bootstrap core CSS -->
<link href="./css/bootstrap.min.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">
  </head>
    <?
        include "../dbconn_utf8.inc";

        $id						= str_quote_smart(trim($id));
        $VolumePeriod = str_quote_smart(trim($VolumePeriod));

        $query = "SELECT * FROM Rankchecker_v1 where ID='$id'";

        $result = mysql_query($query);
        $row = mysql_fetch_array($result);

        $member_name= $row[1];
        $base_rank= $row[2];
        $d_month= $row[3];
        $p_1= $row[4];
        $p_2= $row[5];
        $m_1= $row[6];
        $m_2= $row[7];
        $m_condiction= $row[8];
      

        if($row == "" || $row == null){
            $query1 = "SELECT * FROM Rankchecker_v1 where ID=0";
     
            $result1 = mysql_query($query1);
            $row1 = mysql_fetch_array($result1);

            $member_name= $row1[1];
            $base_rank= $row1[2];
            $d_month= $row1[3];
            $p_1= $row1[4];
            $p_2= $row1[5];
            $m_1= $row1[6];
            $m_2= $row1[7];
            $m_condiction= $row1[8];
        }

        $query3 = "SELECT * FROM Rankchecker_text where NO=1";
        $result3 = mysql_query($query3);
        $row3 = mysql_fetch_array($result3);

        $text1=$row3[1]; 
        $text2=$row3[2];
    ?>
  <body>
  
<?php include "common_load.php" ?>

    <div id="rank-tab">
        <div class="">
            <div id="firstdiv"style="border: 0;margin-top: 10px; margin-bottom: 10px;padding-top: 0px;padding-bottom: 0px;">
                <h3 style="font-weight: bold;"><?php echo $text1?></h3><p style="font-size: 20px;display: inline;"><?php echo $text2?></p><div class="checker hide"></div>
            </div>
            <table id="successtable" style="min-width: 100%; max-width: 100%;margin-right: auto;margin-left: auto; text-align : center;" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="width:32%; background-color:#6799FF;color: white;padding: 5px 10px; ">
                        <span style="font-size: 22px;display: inline;font-weight: bold;">성함</span>                                  
                    </td>
                    <td style="width:32%; background-color:#6799FF;color: white;padding: 5px 10px;">
                        <span style="font-size: 22px;display: inline;font-weight: bold;">기준직급</span>                                 
                    </td>
                    <td style="width:32%; background-color:#6799FF;color: white;padding: 5px 10px;">
                        <span style="font-size: 22px;display: inline;font-weight: bold;">GD달성월</span>                                 
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="font-size: 20px;padding: 5px 10px;background-color: rgba(208, 208, 208, 0.29);white-space: nowrap; border: 1px solid #000000; box-shadow: 0 1px 0 white;margin: 0;">
                            <?php echo $member_name?>
                        </p>
                    </td>
                    <td>
                        <p style="font-size: 20px;padding: 5px 10px;background-color: rgba(208, 208, 208, 0.29);white-space: nowrap; border: 1px solid #000000; box-shadow: 0 1px 0 white;margin: 0;">
                            <?php echo $base_rank?>
                        </p>
                    </td>
                    <td>
                        <p style="font-size: 20px;padding: 5px 10px;background-color: rgba(208, 208, 208, 0.29);white-space: nowrap;border: 1px solid #000000; box-shadow: 0 1px 0 white;margin: 0;">
                            <?php echo $d_month?>
                        </p>
                    </td>
                </tr>
                
            </table>
            <br/>
            <table id="successtable" style="min-width: 100%; max-width: 100%;margin-right: auto;margin-left: auto; text-align : center;" border="0" cellspacing="0" cellpadding="0">
       
                <tr>
                    <td style="background-color:#6799FF;color: white;padding: 5px 10px;">
                        <span style="font-size: 22px;display: inline;font-weight: bold;">달성인원</span>                                  
                    </td>
                    <td style="background-color:#6799FF;color: white;padding: 5px 10px;">
                        <span style="font-size: 22px;display: inline;font-weight: bold;">승급조건</span>                                 
                    </td>
                    <td colspan='2' style="background-color:#6799FF;color: white;padding: 5px 10px;  border-top: 2px solid #FF0000;  border-left: 2px solid #FF0000;border-right: 2px solid #FF0000;">
                        <span style="font-size: 22px;display: inline;font-weight: bold;">유지조건</span>                                 
                    </td>
                  
                </tr>
                <tr>
                    <td>
                        <p style="font-size: 20px;padding: 5px 10px;background-color: rgba(208, 208, 208, 0.29);white-space: nowrap; border-top: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000; box-shadow: 0 1px 0 white;margin: 0;">
                            1인
                        </p>
                    </td>
                    <td>
                        <p style="font-size: 20px;padding: 5px 10px;background-color: rgba(208, 208, 208, 0.29);white-space: nowrap; border-top: 1px solid #000000;  box-shadow: 0 1px 0 white;margin: 0;">
                            <?php echo $p_1?>
                        </p>
                    </td>
                    <td>
                        <p style="font-size: 20px;padding: 5px 10px;background-color: rgba(208, 208, 208, 0.29);white-space: nowrap;  border-top: 1px solid #000000; border-left: 2px solid #FF0000; border-right: 1px solid #000000; box-shadow: 0 1px 0 white;margin: 0;">
                            <?php echo $m_1?>
                        </p>
                    </td>
                    <td rowspan='2' style="font-size: 20px;padding: 5px 10px;background-color: rgba(208, 208, 208, 0.29);white-space: nowrap; box-shadow: 0 1px 0 white;margin: 0; border-top: 1px solid #000000; border-right: 2px solid #FF0000; border-bottom: 2px solid #FF0000;">
                        <?php echo $m_condiction ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="font-size: 20px;padding: 5px 10px;background-color: rgba(208, 208, 208, 0.29);white-space: nowrap; border: 1px solid #000000; box-shadow: 0 1px 0 white;margin: 0;">
                            2인
                        </p>
                    </td>
                    <td>
                        <p style="font-size: 20px;padding: 5px 10px;background-color: rgba(208, 208, 208, 0.29);white-space: nowrap; border-bottom: 1px solid #000000; border-top: 1px solid #000000;  box-shadow: 0 1px 0 white;margin: 0;">
                            <?php echo $p_2?>
                        </p>
                    </td>
                    <td>
                        <p style="font-size: 20px;padding: 5px 10px;background-color: rgba(208, 208, 208, 0.29);white-space: nowrap; border-bottom: 2px solid #FF0000; border-left: 2px solid #FF0000; border-right: 1px solid #000000; border-top: 1px solid #000000; box-shadow: 0 1px 0 white;margin: 0;">
                            <?php echo $m_2?>
                        </p>
                    </td>
                </tr>
          
   
            </table>
            <br/>
            <table id="successtable" style="min-width: 100%; max-width: 100%;margin-right: auto;margin-left: auto; text-align : center;" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td colspan="3" style="background: linear-gradient(rgb(4, 165, 234) 0%, rgb(4, 100, 157) 100%);">
                    <p style="color: white;font-weight: bold; text-align: center;padding: 10px 10px;margin: 0;">
                        기준 직급에 오류가 있을 수 있으니, 자세한 사항은 각 지역 고객지원센터(DSC)로 문의 주시기 바랍니다. <br>
                    </p>
                        <ul style="color: white;font-weight: bold; text-align: left;padding: 10px 10px;margin-left:25px;">
                        <li> 서울/그 외 경기(안산/인천 고객지원센터 담당지역 외): 서울센터 02-564-1880 Fax : 02-2179-8962</li>
                        <li> 인천/부천/김포/일산/파주/광명/강화/시흥 : 인천센터 032-504-1880 Fax : 032-714-3914</li>
                        <li> 안산/수원/화성/군포/의왕/오산/평택(경기일부) 지역 : 안산센터 031-484-1880 Fax : 031-601-8593</li>
                        <li> 대전/충청 지역 : 대전센터 042-485-1860 Fax : 042-367-0553</li>
                        <li> 광주/전라 지역 : 광주센터 062-376-1880 Fax : 062-443-0624</li>
                        <li> 원주/강원 지역 : 원주센터 033-766-8269 Fax : 0303-3440-8269</li>
                        <li> 대구/경북 지역 : 대구센터 053-656-9636 Fax : 053-289-0313</li>
                        <li> 부산/울산/경남 지역 : 부산센터 051-865-6669 Fax: 051-980-0667</li>
                        <li> 제주지역 : 제주센터 064-726-1882 Fax: 0303-3440-1882</li>
                        </ul>                        
                    </td>
                </tr>
            </table>
        </div>
    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
    </div>  
  </body>
</html>
