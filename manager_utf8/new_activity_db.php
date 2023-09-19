<?php 
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
	ini_set('memory_limit',-1);
	ini_set('max_execution_time', 60);

	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/3/7
	// 	Last Update : 2003/3/7
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.3.7 by Park ChanHo 
	// 	File Name 	: new_member_file_db.php
	// 	Description : the member information Insert and Update as file
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////


	// 2020. 6. 22 
	// register_globals = On 되어 있으므로 $cfile[name] 을 $cfile_name 로 표현 할 수 있다.
	//

	include "./admin_session_check.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";

	$cfile_name = $_FILES['cfile']['name'];
	$efile_name = $_FILES['efile']['name'];

	function isDash($value){
		$value = trim($value);
		$value = str_replace(",","",$value);
		$value = str_replace("(","-",$value);
		$value = str_replace(")","",$value);
		if($value == '-'){
			$value = 0;
		} 
		return $value;
	}
	$number_id = 0;
	$path = "activity_file/";

	$cfile_name					= str_quote_smart(trim($cfile_name));
	$dfile_name					= str_quote_smart(trim($dfile_name));
	$efile_name					= str_quote_smart(trim($efile_name));


	if ($cfile_name != "") {
		$cfile_ext = substr(strrchr($cfile_name, "."), 1);
//		$img_name = iconv("utf-8","euc-kr",$img_name);
		if (strtoupper($cfile_ext) != "TXT"){
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			echo "<script>
				window.alert('확장자가 txt, TXT외에는 업로드할수가 없습니다.');
				history.go(-1);
				</script>";
				mysql_close($connect);
			exit;
		}

		$cfile_strtmp = $path."/c_activity002_1.".$cfile_ext;
		$new_cfile = "c_activity002_1.".$cfile_ext;


//		if (file_exists($image_zoom_strtmp)) {
//			echo "<script>
//       		window.alert('$image_zoom_name 이 같은 디렉토리에 존재합니다..');
//				history.go(-1);
//				</script>";
//			exit;
//		}

		//if (!copy($cfile, $cfile_strtmp))
		if(!move_uploaded_file($_FILES['cfile']['tmp_name'], $cfile_strtmp))
		{
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			echo "<script>
				window.alert('$cfile_name 를 업로드할 수 없습니다.');
				history.go(-1);
				</script>";
				mysql_close($connect);
			exit;
		}
	}

	if ($efile_name != "") {

		$efile_ext = substr(strrchr($efile_name, "."), 1);
		if (strtoupper($efile_ext) != "TXT"){
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			echo "<script>
				window.alert('확장자가 txt, TXT외에는 업로드할수가 없습니다.');
				history.go(-1);
				</script>";
				mysql_close($connect);
			exit;
		}

//		if (strtoupper($efile_ext) != "TXT" && strtoupper($efile_ext) != "CSV" && strtoupper($efile_ext) != "XLS"  && strtoupper($efile_ext) != "XLSX"  )
//		{
//			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
//			echo "<script>
//				window.alert('지원되지 않는 확장자를 가진 파일입니다.');
//				history.go(-1);
//				</script>";
//				mysql_close($connect);
//			exit;
//		}

		$efile_strtmp = $path."/d_activity002_1.".$efile_ext;	
		$new_efile = "d_activity002_1.".$efile_ext;	

 
		//if (!copy($efile, $efile_strtmp))
		if(!move_uploaded_file($_FILES['efile']['tmp_name'], $efile_strtmp))
		{
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			echo "<script>
				window.alert('$efile_name 를 업로드할 수 없습니다.');
				history.go(-1);
				</script>";
				mysql_close($connect);
			exit;
		}
	} 

//	$query = "delete from tb_member where member_kind = 'C' ";
	//echo $query;
//	mysql_query($query) or die("Query Error".$query);


if($new_cfile != ""){	

	$fo = fopen($path."/".$new_cfile, "r");


	while($str = fgets($fo, 3000)) {
		$number_id++;
		$a_str = str_replace("\"","",$str);
		$a_str = str_replace(",","",$str);
		$a_str = explode("	",$str);

		$ID								= $a_str[0]; 
		$DistributorName			= $a_str[1]; 
		$PaidRank						= $a_str[2]; 
		$PV08							= $a_str[3]; 
		$TV08							= $a_str[4]; 
		$BD								= $a_str[5];  
		$PR								= $a_str[6]; 
		$TD								= $a_str[7]; 
		$OD								= $a_str[8]; 
		$CarBonus						= $a_str[9]; 
		$PCAB							= $a_str[10]; 
		$MB								= $a_str[11]; 
		$PGO							= $a_str[12]; 
		$Adjustment					= $a_str[13]; 
		$MonthNetEarning		= $a_str[14]; 
		$IncomeTax					= $a_str[15]; 
		$ResidentTax				= $a_str[16]; 
		$soa								= $a_str[17]; 
		$AutoshipAdj				= $a_str[18]; 
		$ThisMonthNetPayment = $a_str[19]; 
		$jumin							= $a_str[20]; 
		$address						= $a_str[21]; 
		$sangho							= $a_str[22]; 
		$companynum					= $a_str[23]; 
		$companyaddr				= $a_str[24]; 
		$VolumePeriod				= $a_str[25]; 
		$regdate						= $a_str[26]; 

		$ID = trim($ID);
		$DistributorName = trim($DistributorName);
		$PaidRank = trim($PaidRank);
		$PV08 = trim($PV08); 
		$TV08 = trim($TV08);
		$BD = trim($BD);
		$PR = trim($PR);
		$TD = trim($TD);
		$OD = trim($OD);
		$CarBonus = trim($CarBonus);
		$PCAB = trim($PCAB);
		$MB = trim($MB);
		$PGO = trim($PGO);
		$Adjustment = trim($Adjustment); 
		$MonthNetEarning = trim($MonthNetEarning); 
		$IncomeTax = trim($IncomeTax);
		$ResidentTax = trim($ResidentTax);
		$soa = trim($soa);
		$AutoshipAdj = trim($AutoshipAdj);
		$ThisMonthNetPayment = trim($ThisMonthNetPayment);
		$jumin = trim($jumin); 
		$address = trim($address); 
		$sangho = trim($sangho);
		$companynum = trim($companynum);
		$companyaddr = trim($companyaddr);
		$VolumePeriod = trim($VolumePeriod);
		$regdate = trim($regdate);

		$enc_jumin = encrypt($key, $iv, $jumin); 

	//	$phone = eregi_replace("\"", "''", $phone);

		if($number_id==1){
			$sql="select count(id) from tb_Activityreport where VolumePeriod='$VolumePeriod'";
			$result=mysql_query($sql);
			$row = mysql_fetch_array($result);
			if($row[0]>0){
				$query = "delete from tb_Activityreport where VolumePeriod = '$VolumePeriod' ";

				mysql_query($query) or die("Query Error".$query);
			}
		}

		$query = "insert into tb_Activityreport (ID, DistributorName, PaidRank, PV08, TV08, BD, PR, TD, OD, CarBonus, PCAB, MB, PGO, Adjustment, MonthNetEarning, IncomeTax, ResidentTax, soa, AutoshipAdj, Payment, address, sangho, companynum, companyaddr, VolumePeriod, regdate, JU_NO) values ('".$ID."', '".$DistributorName."', '".$PaidRank."', '".$PV08."', '".$TV08."', '".$BD."', '".$PR."','".$TD."','".$OD."','".$CarBonus."','".$PCAB."','".$MB."' ,'".$PGO."','".$Adjustment."','".$MonthNetEarning."', '".$IncomeTax."', '".$ResidentTax."', '".$soa."', '".$AutoshipAdj."', '".$ThisMonthNetPayment."','".$address."','".$sangho."','".$companynum."','".$companyaddr."','".$VolumePeriod."','".$regdate."','".$enc_jumin."')";
		mysql_query($query) or die("Query Error".$query);
	}
	
	$query = "update tb_Activityreport set YYYYMM = CONCAT(
									 case substr(VolumePeriod,5,2) when '00' then '2000' when '10' then '2010' when '11' then '2011' when '12' then '2012' when '13' then '2013' when '14' then '2014' when '15' then '2015' when '16' then '2016' when '17' then '2017' when '18' then '2018' when '19' then '2019' when '20' then '2020' when '21' then '2021' when '22' then '2022' else '2009' end, '-',
									 case substr(VolumePeriod,1,3) when 'Jan' then '01' when 'Feb' then '02' when 'Mar' then '03' when 'Apr' then '04' when 'May' then '05' when 'Jun' then '06' when 'Jul' then '07' when 'Aug' then '08' when 'Sep' then '09' when 'Oct' then '10' when 'Nov' then '11' when 'Dec' then '12' else '13' end)
						 where YYYYMM = '' ";

	mysql_query($query) or die("Query Error".$query);

	$query = "update tb_Activityreport set YYYYMM = CONCAT(
									 case substr(VolumePeriod,5,2) when '00' then '2000' when '10' then '2010' when '11' then '2011' when '12' then '2012' when '13' then '2013' when '14' then '2014' when '15' then '2015' when '16' then '2016' when '17' then '2017' when '18' then '2018' when '19' then '2019' when '20' then '2020' when '21' then '2021' when '22' then '2022' else '2009' end, '-',
									 case substr(VolumePeriod,1,3) when 'Jan' then '01' when 'Feb' then '02' when 'Mar' then '03' when 'Apr' then '04' when 'May' then '05' when 'Jun' then '06' when 'Jul' then '07' when 'Aug' then '08' when 'Sep' then '09' when 'Oct' then '10' when 'Nov' then '11' when 'Dec' then '12' else '13' end)
						 where YYYYMM is NULL ";
	mysql_query($query) or die("Query Error".$query);


	fclose($fo);

}


if($new_efile != ""){	

 	$fo_e = fopen($path."/".$new_efile, "r");
	
	while(($str = fgetcsv($fo_e)) !== false) {
		$ID						= $str[0]; 
		$DistributorName		= $str[1]; 
		$PaidRank				= $str[2]; 
		$PV08					= $str[3]; 
		$TV08					= $str[4]; 
		$BD						= $str[5];
		$PR						= $str[6];
		$TD						= $str[7]; 
		$OD						= $str[8]; 
		$CarBonus				= $str[9]; 
		$PCAB					= $str[10]; 
		$MB						= $str[11]; 
		$PGO					= $str[12]; 
		$Adjustment				= $str[13]; 
		$MonthNetEarning		= $str[14]; 
		$IncomeTax				= $str[15]; 
		$ResidentTax			= $str[16]; 
		$soa					= $str[17]; 
		$AutoshipAdj			= $str[18]; 
		$ThisMonthNetPayment 	= $str[19]; 
		$jumin					= $str[20]; 
		$address				= $str[21]; 
		$sangho					= $str[22]; 
		$companynum				= $str[23]; 
		$companyaddr			= $str[24]; 
		$VolumePeriod			= $str[25]; 
		$regdate				= $str[26]; 
		 

		$ID 				 = trim($ID);
		$DistributorName 	 = trim($DistributorName);
		$PaidRank			 = trim($PaidRank);
		$PV08				 = isDash($PV08); 
		$TV08				 = isDash($TV08);
		$BD					 = isDash($BD);
		$PR 				 = isDash($PR);
		$TD 				 = isDash($TD);
		$OD					 = isDash($OD);
		$CarBonus			 = isDash($CarBonus);
		$PCAB				 = isDash($PCAB);
		$MLB 				 = isDash($MB);
		$PGO 				 = isDash($PGO);
		$Adjustment 		 = isDash($Adjustment); 
		$MonthNetEarning	 = isDash($MonthNetEarning); 
		$IncomeTax 			 = isDash($IncomeTax);
		$ResidentTax 		 = isDash($ResidentTax);
		$soa 				 = isDash($soa);
		$AutoshipAdj 		 = isDash($AutoshipAdj);
		$ThisMonthNetPayment = isDash($ThisMonthNetPayment);
		$jumin 			 	 = trim($jumin); 
		$address		 	 = trim($address); 
		$sangho				 = trim($sangho);
		$companynum			 = trim($companynum);
		$companyaddr		 = trim($companyaddr);
		$VolumePeriod		 = trim($VolumePeriod);

		$enc_jumin			 = encrypt($key, $iv, $jumin); 
		$regdate			 = date("Y-m-d", strtotime($regdate)) ; 
		//$YYYYMM 			 = date("y-m", strtotime($VolumePeriod)); 
		$volPeriod =explode('-',$VolumePeriod);
		$YYYYMM = date("Y-m", strtotime('20'.$volPeriod[1].'-'.$volPeriod[0]));			
	    
		$values = "`ID`              = '$ID', "
		 		 ."`DistributorName` = '$DistributorName', "
		 		 ."`PaidRank`        = '$PaidRank', "
		 		 ."`PV08`            = '$PV08', "
		 		 ."`TV08` 			 = '$TV08', "
		 		 ."`BD` 			 = '$BD', "
		 		 ."`PR` 			 = '$PR', "
		 		 ."`TD` 			 = '$TD', "
		 		 ."`OD` 			 = '$OD', "
		 		 ."`CarBonus` 		 = '$CarBonus', "
		 		 ."`PCAB` 			 = '$PCAB', "
		 		 ."`MB` 			 = '$MLB', "
		 		 ."`PGO` 			 = '$PGO', "
		 		 ."`Adjustment`		 = '$Adjustment', "
		 		 ."`MonthNetEarning` = '$MonthNetEarning', " 
		 		 ."`IncomeTax`		 = '$IncomeTax', "
		 		 ."`ResidentTax`	 = '$ResidentTax', " 
		 		 ."`soa`			 = '$soa', "
		 		 ."`AutoshipAdj`	 = '$AutoshipAdj', " 
		 		 ."`Payment`		 = '$ThisMonthNetPayment', "
		 		 ."`address`		 = '$address', "
		 		 ."`sangho`			 = '$sangho', "
		 		 ."`companynum`		 = '$companynum', " 
		 		 ."`companyaddr`	 = '$companyaddr', " 
		 		 ."`VolumePeriod`	 = '$VolumePeriod', " 
		 		 ."`regdate`		 = '$regdate', "
		 		 ."`JU_NO`			 = '$enc_jumin', "
		 		 ."`YYYYMM`			 = '$YYYYMM' " ;
		$where = "`ID`              = '$ID' AND `VolumePeriod`	 = '$VolumePeriod' ";
		
		$checkQuery = "SELECT COUNT(*) FROM tb_Activityreport WHERE ".$where;
		$checkQueryResult = mysql_query($checkQuery);
		$row = mysql_fetch_array($checkQueryResult);
		
		$query = "";
		
		if ($row[0] > 0){
			$query = "UPDATE tb_Activityreport SET ".$values." WHERE ".$where ;
		} else {
			$query = "INSERT INTO tb_Activityreport SET ".$values ;
		}

		//echo $query."<br>";
		 
		mysql_query($query) or die("Query Error".$query);
	}

	fclose($fo_e);
}

	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	echo "<script language=\"javascript\">\n
		alert('정보가 갱신 되었습니다.');
		parent.frames[3].location = 'new_activity_list.php';		
		</script>";
	exit;
 
?> 