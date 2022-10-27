<?

function han ($s) { return reset(json_decode('{"s":"'.$s.'"}')); }
function to_han ($str) { return preg_replace('/(\\\u[a-f0-9]+)+/e','han("$0")',$str); }
function json_encode_han ($arr) {
	    if(getType($arr)=='array'){
	        return str_replace("'",'',to_han(json_encode($arr)));
        } else {
	        return str_replace("'",'',to_han(($arr)));
        }
}
echo base64_encode(json_encode_han(array('_POST'=>$_POST)));
?>