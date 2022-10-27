<? 
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

	include "./admin_session_check.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";

	$number_id = 0;
	$path = "activity_file/";

	$cfile					= str_quote_smart(trim($cfile));
	$dfile					= str_quote_smart(trim($dfile));

	if ($cfile != "") {
		$cfile_ext = substr(strrchr($cfile_name, "."), 1);
//		$img_name = iconv("utf-8","euc-kr",$img_name);
		if ($cfile_ext != "txt" && $cfile_ext != "TXT")
		{
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			echo "<script>
				window.alert('확장자가 txt, TXT외에는 업로드할수가 없습니다.');
				history.go(-1);
				</script>";
				mysql_close($connect);
			exit;
		}

		$cfile_strtmp = $path."/c_activity002.".$cfile_ext;
		$new_cfile = "c_activity002.".$cfile_ext;

/*
		if (file_exists($image_zoom_strtmp)) {
			echo "<script>
       		window.alert('$image_zoom_name 이 같은 디렉토리에 존재합니다..');
				history.go(-1);
				</script>";
			exit;
		}
*/
		if (!copy($cfile, $cfile_strtmp))
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

	if ($dfile != "") {
		$dfile_ext = substr(strrchr($dfile_name, "."), 1);
	
		if ($dfile_ext != "txt" && $dfile_ext != "TXT")
		{
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			echo "<script>
				window.alert('확장자가 txt, TXT외에는 업로드할수가 없습니다.');
				history.go(-1);
				</script>";
				mysql_close($connect);
			exit;
		}

		$dfile_strtmp = $path."/d_activity002.".$dfile_ext;	
		$new_dfile = "d_activity002.".$dfile_ext;	

/*
		if (file_exists($image_zoom_strtmp)) {
			echo "<script>
       		window.alert('$image_zoom_name 이 같은 디렉토리에 존재합니다..');
				history.go(-1);
				</script>";
			exit;
		}
*/

		if (!copy($dfile, $dfile_strtmp))
		{
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			echo "<script>
				window.alert('$dfile_name 를 업로드할 수 없습니다.');
				history.go(-1);
				</script>";
				mysql_close($connect);
			exit;
		}
	} 


//	$query = "delete from tb_member where member_kind = 'C' ";
	//echo $query;
//	mysql_query($query) or die("Query Error".$query);

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
			if($row[0]>1){
				$query = "delete from tb_Activityreport where VolumePeriod = '$VolumePeriod' ";

				mysql_query($query) or die("Query Error".$query);
			}
		}

		$query = "insert into tb_Activityreport (ID, DistributorName, PaidRank, PV08, TV08, BD, PR, TD, OD, CarBonus, PCAB, MB, PGO, Adjustment, MonthNetEarning, IncomeTax, ResidentTax, soa, AutoshipAdj, Payment, address, sangho, companynum, companyaddr, VolumePeriod, regdate, JU_NO) values ('".$ID."', '".$DistributorName."', '".$PaidRank."', '".$PV08."', '".$TV08."', '".$BD."', '".$PR."','".$TD."','".$OD."','".$CarBonus."','".$PCAB."','".$MB."' ,'".$PGO."','".$Adjustment."','".$MonthNetEarning."', '".$IncomeTax."', '".$ResidentTax."', '".$soa."', '".$AutoshipAdj."', '".$ThisMonthNetPayment."','".$address."','".$sangho."','".$companynum."','".$companyaddr."','".$VolumePeriod."','".$regdate."','".$enc_jumin."')";
		mysql_query($query) or die("Query Error".$query);

		$query = "update tb_Activityreport set YYYYMM = CONCAT(
										 case substr(VolumePeriod,5,2) when '00' then '2000' when '10' then '2010' when '11' then '2011' when '12' then '2012' when '13' then '2013' when '14' then '2014' when '15' then '2015' when '16' then '2016' when '17' then '2017' when '18' then '2018' else '2009' end, "-",
										 case substr(VolumePeriod,1,3) when 'Jan' then '01' when 'Feb' then '02' when 'Mar' then '03' when 'Apr' then '04' when 'May' then '05' when 'Jun' then '06' when 'Jul' then '07' when 'Aug' then '08' when 'Sep' then '09' when 'Oct' then '10' when 'Nov' then '11' when 'Dec' then '12' else '13' end)
							 where YYYYMM = '' ";
		mysql_query($query) or die("Query Error".$query);

		$query = "update tb_Activityreport set YYYYMM = CONCAT(
										 case substr(VolumePeriod,5,2) when '00' then '2000' when '10' then '2010' when '11' then '2011' when '12' then '2012' when '13' then '2013' when '14' then '2014' when '15' then '2015' when '16' then '2016' when '17' then '2017' when '18' then '2018' else '2009' end, "-",
										 case substr(VolumePeriod,1,3) when 'Jan' then '01' when 'Feb' then '02' when 'Mar' then '03' when 'Apr' then '04' when 'May' then '05' when 'Jun' then '06' when 'Jul' then '07' when 'Aug' then '08' when 'Sep' then '09' when 'Oct' then '10' when 'Nov' then '11' when 'Dec' then '12' else '13' end)
							 where YYYYMM is NULL ";
		mysql_query($query) or die("Query Error".$query);

	}
	
	fclose($fo);

	$fo_d = fopen($path."/".$new_dfile, "r");
$number_id=0;
	while($str =fgets($fo_d, 3000)) {

		$number_id++;
		
		$a_str = str_replace("\"","",$str);
		$a_str = str_replace(",","",$str);
		$a_str = explode("	",$str);

		$ID								= $a_str[0]; 
		$DistributorName			= $a_str[1]; 
		$MLB							= $a_str[2]; 
		$Adjustment					= $a_str[3]; 
		$MonthNetEarning		= $a_str[4]; 
		$IncomeTax					= $a_str[5]; 
		$ResidentTax				= $a_str[6]; 
		$AutoshipAdj				= $a_str[7]; 
		$ThisMonthNetPayment = $a_str[8]; 
		$jumin							= $a_str[9]; 
		$address						= $a_str[10]; 
		$sangho							= $a_str[11]; 
		$companynum					= $a_str[12]; 
		$companyaddr				= $a_str[13]; 
		$VolumePeriod				= $a_str[14]; 
		$regdate						= $a_str[15]; 
	
		$ID = trim($ID);
		$DistributorName = trim($DistributorName);
		$MLB = trim($MLB);
		$Adjustment = trim($Adjustment); 
		$MonthNetEarning = trim($MonthNetEarning); 
		$IncomeTax = trim($IncomeTax);
		$ResidentTax = trim($ResidentTax);
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

		if($number_id==1){
			$sql="select count(id) from tb_Activityreport_MBL where VolumePeriod='$VolumePeriod'";

			$result=mysql_query($sql);
			$row = mysql_fetch_array($result);
			if($row[0]>0){
				$query = "delete from tb_Activityreport_MBL where VolumePeriod = '$VolumePeriod' ";
				mysql_query($query) or die("Query Error".$query);
			}
		}
		
		$query = "insert into tb_Activityreport_MBL (ID, DistributorName, MLB, Adjustment, MonthNetEarning, IncomeTax, ResidentTax, AutoshipAdj, ThisMonthNetPayment, address, sangho, companynum, companyaddr, VolumePeriod, regdate, JU_NO) values ('".$ID."', '".$DistributorName."', '".$MLB."','".$Adjustment."','".$MonthNetEarning."', '".$IncomeTax."', '".$ResidentTax."', '".$AutoshipAdj."', '".$ThisMonthNetPayment."','".$address."','".$sangho."','".$companynum."','".$companyaddr."','".$VolumePeriod."','".$regdate."','".$enc_jumin."')";
		mysql_query($query) or die("Query Error".$query);

		$query = "update tb_Activityreport_MBL set YYYYMM = CONCAT(
										 case substr(VolumePeriod,5,2) when '00' then '2000' when '10' then '2010' when '11' then '2011' when '12' then '2012' when '13' then '2013' when '14' then '2014' when '15' then '2015' when '16' then '2016' when '17' then '2017' when '18' then '2018' else '2009' end, "-",
										 case substr(VolumePeriod,1,3) when 'Jan' then '01' when 'Feb' then '02' when 'Mar' then '03' when 'Apr' then '04' when 'May' then '05' when 'Jun' then '06' when 'Jul' then '07' when 'Aug' then '08' when 'Sep' then '09' when 'Oct' then '10' when 'Nov' then '11' when 'Dec' then '12' else '13' end)
							 where YYYYMM = '' ";
		mysql_query($query) or die("Query Error".$query);

		$query = "update tb_Activityreport_MBL set YYYYMM = CONCAT(
										 case substr(VolumePeriod,5,2) when '00' then '2000' when '10' then '2010' when '11' then '2011' when '12' then '2012' when '13' then '2013' when '14' then '2014' when '15' then '2015' when '16' then '2016' when '17' then '2017' when '18' then '2018' else '2009' end, "-",
										 case substr(VolumePeriod,1,3) when 'Jan' then '01' when 'Feb' then '02' when 'Mar' then '03' when 'Apr' then '04' when 'May' then '05' when 'Jun' then '06' when 'Jul' then '07' when 'Aug' then '08' when 'Sep' then '09' when 'Oct' then '10' when 'Nov' then '11' when 'Dec' then '12' else '13' end)
							 where YYYYMM is NULL ";
		mysql_query($query) or die("Query Error".$query);
	
	}
	
	fclose($fo_d);


	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	echo "<script language=\"javascript\">\n
		alert('정보가 갱신 되었습니다.1');
		parent.frames[3].location = 'new_activity_list.php';
		</script>";
	exit;

?> 