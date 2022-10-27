<?

	include "admin_session_check.inc";
	include "../dbconn_utf8.inc";

	$path = "../goods_img";

	function trimSpace ($array)
	{
		$temp = Array();
		for ($i = 0; $i < count($array); $i++)
				$temp[$i] = trim($array[$i]);

		return $temp;
		
	}

	function getMaxCode()  { 
	
		$iNewid = 0;
		$sqlstr = "SELECT MAX(goods_id) CNT FROM tb_goods "; 
	
		$result = mysql_query($sqlstr);
		$row = mysql_fetch_array($result);
	
		$iNewid = $row["CNT"] + 1;

		return $iNewid;
	
	}

	$page					=	str_quote_smart(trim($page)); 
	$sort					=	str_quote_smart(trim($sort)); 
	$order				=	str_quote_smart(trim($order)); 
	$idxfield			=	str_quote_smart(trim($idxfield)); 
	$qry_str			=	str_quote_smart(trim($qry_str)); 
	$sel_goods		=	str_quote_smart(trim($sel_goods)); 

	$goods_id			= str_quote_smart(trim($goods_id));

	$b_cate				= str_quote_smart(trim($b_cate));
	$sub_title		= str_quote_smart(trim($sub_title));
	$goods_name		= str_quote_smart(trim($goods_name));
	$sub_cate			= str_quote_smart(trim($sub_cate));
	$goods_no			= str_quote_smart(trim($goods_no));
	$child_goods	= str_quote_smart(trim($child_goods));
	$food_cate		= str_quote_smart(trim($food_cate));
	$maker				= str_quote_smart(trim($maker));
	$size					= str_quote_smart(trim($size));
	$components		= str_quote_smart(trim($components));
	$howto				= str_quote_smart(trim($howto));
	$ba_price			= str_quote_smart(trim($ba_price));
	$c_price			= str_quote_smart(trim($c_price));
	$cv						= str_quote_smart(trim($cv));
	$goods_memo		= str_quote_smart(trim($goods_memo));
	$other_goods	= str_quote_smart(trim($other_goods));
	$SImage				= str_quote_smart(trim($SImage));
	$MImage				= str_quote_smart(trim($MImage));
	$LImage				= str_quote_smart(trim($LImage));
	$BImage				= str_quote_smart(trim($BImage));
	$bshow				= str_quote_smart(trim($bshow));
	$brand				= str_quote_smart(trim($brand));
	$goods_menual	= str_quote_smart(trim($goods_menual));

	$autoship			= str_quote_smart(trim($autoship));
	$auto_price		= str_quote_smart(trim($auto_price));
	$auto_cv			= str_quote_smart(trim($auto_cv));

	$sub_title = str_replace("'", "''",$sub_title);	
	$goods_name = str_replace("'", "''",$goods_name);	
	$sub_cate = str_replace("'", "''",$sub_cate);	
	$goods_no = str_replace("'", "''",$goods_no);	
	$child_goods = str_replace("'", "''",$child_goods);	
	$food_cate = str_replace("'", "''",$food_cate);	
	$maker = str_replace("'", "''",$maker);	
	$size = str_replace("'", "''",$size);	
	$components = str_replace("'", "''",$components);	
	$howto = str_replace("'", "''",$howto);	
	$ba_price = str_replace("'", "''",$ba_price);	
	$c_price = str_replace("'", "''",$c_price);	
	$cv = str_replace("'", "''",$cv);	
	$goods_memo = str_replace("'", "''",$goods_memo);	
	$contents= str_replace("'", "''",$Content);	
	$sub_title = stripslashes($sub_title);	
	$goods_name = stripslashes($goods_name);	
	$sub_cate = stripslashes($sub_cate);	
	$goods_no = stripslashes($goods_no);	
	$child_goods = stripslashes($child_goods);	
	$food_cate = stripslashes($food_cate);	
	$maker = stripslashes($maker);	
	$size = stripslashes($size);	
	$components = stripslashes($components);	
	$howto = stripslashes($howto);	
	$ba_price = stripslashes($ba_price);	
	$c_price = stripslashes($c_price);	
	$cv = stripslashes($cv);	
	$goods_memo = stripslashes($goods_memo);	
	
	if ($mode == "odr") {

		$goods_id = str_quote_smart(trim($goods_id));
		$odrs			= str_quote_smart(trim($odrs));
	
		$a_goods_id = explode("|",$goods_id);
		$a_odrs = explode("|",$odrs);
	
		for ($i=0 ; count($a_goods_id) > $i ; $i++) {
			$query = "update tb_goods set auto_order = '$a_odrs[$i]' where goods_id = '$a_goods_id[$i]'";
		//	echo $query;
		//	die;
			mysql_query($query) or die("Query Error");
			#echo $query; 
		}

		mysql_close($connect);

		
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
		echo "<script language=\"javascript\">\n
			alert('변경 되었습니다.');
			parent.frames[3].location = 'autoship_goods_list.php';
			</script>";
		exit;


	} else if ($mode == "autoship") {

		$query = "update tb_goods set autoship = '$autoship' where goods_id = '$goods_id'";

		mysql_query($query) or die("Query Error");
		mysql_close($connect);

		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
		echo "<script language=\"javascript\">\n
			alert('Autoship 여부가 수정 되었습니다.');
			parent.frames[3].location = 'autoship_goods_list.php?page=$page&sort=$sort&order=$order&idxfield=$idxfield&qry_str=$qry_str&sel_goods=$sel_goods';
			</script>";
		exit;

	}
?>