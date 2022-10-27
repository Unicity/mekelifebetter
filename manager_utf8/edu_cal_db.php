<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<?
	include "admin_session_check.inc";
	include "../dbconn_utf8.inc";
	include("../VBN_WEAS/ServerInclude/VBN_files.php"); //[by 벤처브레인]   

	$v_fileSaveDir = "/home/httpd/unicity/com_pds/files";
	$v_fileSaveBaseURL = "http://www.makelifebetter.co.kr/com_pds/files";
	$v_fileLimitSize = "30";

	$path = "../com_pds/files";

	function trimSpace ($array)
	{
		$temp = Array();
		for ($i = 0; $i < count($array); $i++)
				$temp[$i] = trim($array[$i]);

		return $temp;		
	}

	function getMaxCode($scode)  { 
	
		$iNewid = 0;
		$sqlstr = "SELECT MAX(seq) CNT FROM tb_education_calendar where NewsId = '$scode'"; 
	
		$result = mysql_query($sqlstr);
		$row = mysql_fetch_array($result);
	
		$iNewid = $row["CNT"] + 1;

		return $iNewid;
	
	}


	$mode						= str_quote_smart(trim($mode));
	$qry_str				= str_quote_smart(trim($qry_str));
	$idxfield				= str_quote_smart(trim($idxfield));
	$page						= str_quote_smart(trim($page));
	$NewsId					= str_quote_smart(trim($NewsId));

	if ($mode == "add") {

		//$sMax = getMaxCode($NewsId);

		$Title			= str_quote_smart(trim($Title));
		$Content		= html_quote_smart(trim($Content));
		$Source			= html_quote_smart(trim($sel_goods));
		$bshow			= str_quote_smart(trim($bshow));
		$isBa				= str_quote_smart(trim($isBa));

		$Year1			= str_quote_smart(trim($Year1));
		$Month1			= str_quote_smart(trim($Month1));
		$Day1				= str_quote_smart(trim($Day1));
		$Hour1			= str_quote_smart(trim($Hour1));
		$Minute1		= str_quote_smart(trim($Minute1));

		$s_Date = $Year1.$Month1.$Day1;

		if ($Image != "") {
			$Image_ext = substr(strrchr($Image_name, "."), 1);

			$Image_strtmp = $path."/".$Image_name;	
        	
			if (file_exists($Image_strtmp)) {
				echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
					<script>
        		window.alert('$Image_name 이 같은 디렉토리에 존재합니다..');
					history.go(-1);
					</script>";
				mysql_close($connect);
				exit;
			}
        	
			if (!copy($Image, $Image_strtmp))
			{
				echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
					<script>
					window.alert('$Image_name 를 업로드할 수 없습니다.');
					history.go(-1);
					</script>";
				mysql_close($connect);
				exit;
			}
        } else {
			$Image_name = "";
		}

	
	   	$Content= VBN_uploadMultiFiles($Content,$v_fileSaveDir,$v_fileSaveBaseURL,$v_fileLimitSize);  //[by 벤처브레인]    
		
		$query = "insert into tb_education_calendar ( y,mo,d,h,mi,subject, content, wdate, bshow) values 
					  ('$Year1', '$Month1', '$Day1', '$Hour1', '$Minute1', '$Title', '$Content', now(),'$bshow')";

		#echo $query; 					 

		mysql_query($query) or die("Query Error");
		mysql_close($connect);

		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
					<script language=\"javascript\">\n
			alert('등록 되었습니다.');
			parent.frames[3].location = 'edu_cal_list.php';
			</script>";
		exit;

	} else if ($mode == "mod") {

/*		$query1 = "select * from tb_education_calendar where seq = $id";
		$result1 = mysql_query($query1);
		$list = mysql_fetch_array($result1);

		$old_Image = $list[Image];
		$old_Image_size = $list[Image_size];
				
		if ($Image != "") {
						
			$old_file = $path."/".$old_Image;						
    		$exist = file_exists($old_file);
    	    
    		if($exist){
        		$delrst=unlink($old_file);			
            	if(!$delrst) {
            		echo "삭제실패";
				} 			
			} 

			$Image_ext = substr(strrchr($Image_name, "."), 1);

			$Image_strtmp = $path."/".$Image_name;	
        	
			if (file_exists($Image_strtmp)) {
				echo "<script>
        		window.alert('$Image_name 이 같은 디렉토리에 존재합니다..');
					history.go(-1);
					</script>";
				mysql_close($connect);
				exit;
			}
        	
			if (!copy($Image, $Image_strtmp))
			{
				echo "<script>
					window.alert('$Image_name 를 업로드할 수 없습니다.');
					history.go(-1);
					</script>";
				mysql_close($connect);
				exit;
			}
 
			$new_name_Image = $Image_name;
			$new_name_Image_size = $Image_size;

		} else {
			$new_name_Image = $old_Image;
			$new_name_Image_size = $old_Image_size;
		}
*/
		$id					= str_quote_smart(trim($id));

		$Title			= str_quote_smart(trim($Title));
		$Content		= html_quote_smart(trim($Content));
		$Source			= html_quote_smart(trim($sel_goods));
		$bshow			= str_quote_smart(trim($bshow));
		$isBa				= str_quote_smart(trim($isBa));

		$Year1			= str_quote_smart(trim($Year1));
		$Month1			= str_quote_smart(trim($Month1));
		$Day1				= str_quote_smart(trim($Day1));
		$Hour1			= str_quote_smart(trim($Hour1));
		$Minute1		= str_quote_smart(trim($Minute1));

		$s_Date = $Year1.$Month1.$Day1;

		$Content= VBN_uploadMultiFiles($Content,$v_fileSaveDir,$v_fileSaveBaseURL,$v_fileLimitSize);  //[by 벤처브레인]
		$query = "update tb_education_calendar set 
					y = '$Year1',
					mo = '$Month1',
					d = '$Day1',
					h = '$Hour1',
					mi = '$Minute1',
					subject = '$Title',
					content = '$Content',
					bshow = '$bshow'
					where seq = $id ";
					 
		echo $query; 					 

		mysql_query($query) or die("Query Error");
		mysql_close($connect);

		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
					<script language=\"javascript\">\n
			alert('수정 되었습니다.');
			parent.frames[3].location = 'edu_cal_list.php?page=$page&idxfield=$idxfield&qry_str=$qry_str';
			</script>";
		exit;

	} else if ($mode == "del") {

		$id					= str_quote_smart(trim($id));
		$SeqNo			= str_quote_smart(trim($SeqNo));

		$id = str_replace("^", "'",$id);

		$query = "select * from tb_education_calendar 
				    where seq in $id ";

		$result = mysql_query($query);

		while($row = mysql_fetch_array($result)) {

			$SeqNo = $row[seq];
			$NewsId = $row[NewsId];
			$Image = $row[Image];
			$Content = $row[Content];
			VBN_deleteFiles($Content,$v_fileSaveDir,$v_fileSaveBaseURL);

			if ($Image != "") {
				$old_file = $path."/".$Image;		
    			$exist = file_exists($old_file);    	    
    			if($exist){
            	
        			$delrst=unlink($old_file);
                				
            		if(!$delrst) {
            			echo "삭제실패";
				    } 			
				} 
			}

			
			$query_del = "delete from tb_education_calendar 
				    where seq = '$SeqNo' ";
			
			mysql_query($query_del) or die("Query Error");

		}	
		
		#echo $query; 					 
		#mysql_query($query) or die("Query Error");
		mysql_close($connect);

		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
					<script language=\"javascript\">\n
			alert('삭제 되었습니다.');
			parent.frames[3].location = 'edu_cal_list.php?NewsId=$NewsId&page=$page&idxfield=$idxfield&qry_str=$qry_str';
			</script>";
		exit;

	} else if ($mode == "bshow") {

		$query = "update tb_education_calendar set bshow = '$bshow' where seq = '$id' ";

		mysql_query($query) or die("Query Error");
		mysql_close($connect);

		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
					<script language=\"javascript\">\n
			alert('전시 여부가 수정 되었습니다.');
			parent.frames[3].location = 'edu_cal_list.php?NewsId=$NewsId&page=$page&idxfield=$idxfield&qry_str=$qry_str';
			</script>";
		exit;
		
	}
?>