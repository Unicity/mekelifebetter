<?php include_once $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/common_function.php"; ?>

<?php if(getRealClientIp() != "121.190.224.85" && $dragncopy != 'Y'){ ?>	

<style type="text/css" title="">
/* 영역선택 막기 */
body{
  -ms-user-select: none; 
  -moz-user-select: -moz-none;
  -khtml-user-select: none;
  -webkit-user-select: none;
  user-select: none;
}
input {
 -webkit-user-drag: none;
 -khtml-user-drag: none;
 -moz-user-drag: none;
 -o-user-drag: none;
 user-drag: none;
}
textarea {
    resize: none;
}
.unselectable {
   -moz-user-select: -moz-none;
   -khtml-user-select: none;
   -webkit-user-select: none;
   -ms-user-select: none;
   user-select: none;
}
</style>
<script type="text/javascript" src="inc/jquery.js"></script>
<script type="text/javascript">
$(function(){
    $("input, textarea, select").on("paste", function() {
		return false;
	});
	$("input, textarea, select").on("copy", function() {
		return false;
	});
	$("input, textarea, select").on("cut", function() {
		return false;
	});
	$("input, textarea, select").on("drag", function() {
		return false;
	});
	$("input, textarea, select").on("drop", function() {
		return false;
	});
	$("input, textarea, select").on("selectstart", function() {
		return false;
	});
});
</script>

<?php } ?>