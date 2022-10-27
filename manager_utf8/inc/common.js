//팝업창

function topWindow(url){
popup = window.open(url,"print","height=450,width=600,scrollbars=yes");
}

function leanWindow1(url){
popup = window.open(url,"lean_des","height=400,width=560,scrollbars=yes");
}
function movWindow(url){
popup = window.open(url,"movie","height=310,width=490,scrollbars=no");
}

//팝업
function NewWindow(mypage, myname, w, h, scroll) {
var winl = (screen.width - w) / 2;
var wint = (screen.height - h) / 2;
winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
win = window.open(mypage, myname, winprops)
if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

//팝업
function NewWindow_with_param(mypage, myname, w, h, scroll, param) {
var winl = (screen.width - w) / 2;
var wint = (screen.height - h) / 2;
winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
win = window.open(mypage+"?id="+param, myname, winprops)
if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

function isValidEmail(input) {
//    var format = /^(\S+)@(\S+)\.([A-Za-z]+)$/;
    var format = /^((\w|[\-\.])+)@((\w|[\-\.])+)\.([A-Za-z]+)$/;
    return isValidFormat(input,format);
}
	
function isValidFormat(input,format) {
    if (input.value.search(format) != -1) {
        return true; //올바른 포맷 형식
    }
    return false;
}

function containsCharsOnly(input,chars) {
    for (var inx = 0; inx < input.value.length; inx++) {
       	if (chars.indexOf(input.value.charAt(inx)) == -1)
          	return false;
    }
    return true;	
}

function isNumber(input) {
    var chars = "0123456789";
    return containsCharsOnly(input,chars);
}

function isPhoneNumber(input) {
    var chars = "0123456789-";
    return containsCharsOnly(input,chars);
}

function CheckJuminForm(J1, J2) {

	var SUM=0;

	for(i=0;i<J1.length;i++)  {

		if (J1.charAt(i) >= 0 || J1.charAt(i) <= 9) { 
			if(i == 0) {
				SUM = (i+2) * J1.charAt(i);
			} else { 
				SUM = SUM + (i+2) * J1.charAt(i);
			}
		} else {
			return false;
		}
	}

	for(i=0;i<2;i++) {
		
		if (J2.charAt(i) >= 0 || J2.charAt(i) <= 9) {
			SUM = SUM + (i+8) * J2.charAt(i);
		} else { 
			return false;
		}
	}

	for(i=2;i<6;i++) {

		if (J2.charAt(i) >= 0 || J2.charAt(i) <= 9) {
			SUM = SUM + (i) * J2.charAt(i);
		} else {
			return false;
		}
	}

	var checkSUM = SUM % 11;

	if(checkSUM == 0) {
		var checkCODE = 10;
	} else if (checkSUM ==1) {
		var checkCODE = 11;
	} else {
		var checkCODE = checkSUM;
	}

	var check1 = 11 - checkCODE; 

	if (J2.charAt(6) >= 0 || J2.charAt(6) <= 9) {
		var check2 = parseInt(J2.charAt(6))
	} else {
		return false;
	}

	if(check1 != check2) {
		return false;
	} else {
		return true; 
	}
} 

function fitImagePop(what) { 
		
	var imgwin = window.open("",'WIN','scrollbars=no,status=no,toolbar=no,resizable=1,location=no,menu=no,width=10,height=10'); 
	imgwin.focus(); 
	imgwin.document.open(); 
	imgwin.document.write("<html>\n"); 
	imgwin.document.write("<head>\n"); 
	imgwin.document.write("<title>:::문학과지성사 홈페이지에 오신걸 환영합니다:::</title>\n"); 
	
	imgwin.document.write("<sc"+"ript>\n"); 
	imgwin.document.write("function resize() {\n"); 
	imgwin.document.write("pic = document.il;\n"); 
	imgwin.document.write("if (eval(pic).height) { var name = navigator.appName\n"); 
	imgwin.document.write("  if (name == 'Microsoft Internet Explorer') { myHeight = eval(pic).height + 40; myWidth = eval(pic).width + 12;\n"); 
	imgwin.document.write("  } else { myHeight = eval(pic).height + 9; myWidth = eval(pic).width; }\n"); 
	imgwin.document.write("  clearTimeout();\n"); 
	imgwin.document.write("  var height = screen.height;\n"); 
	imgwin.document.write("  var width = screen.width;\n"); 
	imgwin.document.write("  var leftpos = width / 2 - myWidth / 2;\n"); 
	imgwin.document.write("  var toppos = height / 2 - myHeight / 2; \n"); 
	imgwin.document.write("  self.moveTo(leftpos, toppos);\n"); 
	imgwin.document.write("  self.resizeTo(myWidth, myHeight-10);\n"); 
	imgwin.document.write("}else setTimeOut(resize(), 100);}\n"); 
	imgwin.document.write("</sc"+"ript>\n"); 
	
	imgwin.document.write("</head>\n"); 
	imgwin.document.write('<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" bgcolor="#FFFFFF">\n'); 
	
	imgwin.document.write("<img border=0 src="+what+" xwidth=100 xheight=9 name=il onload='resize();'>\n"); 
	imgwin.document.write("</body>\n"); 
	imgwin.document.close(); 
	
} 

function fitImagePop2(what,title) { 
		
	var imgwin = window.open("",'WIN','scrollbars=no,status=no,toolbar=no,resizable=1,location=no,menu=no,width=10,height=10'); 
	imgwin.focus(); 
	imgwin.document.open(); 
	imgwin.document.write("<html>\n"); 
	imgwin.document.write("<head>\n"); 
	imgwin.document.write("<link rel='stylesheet' href='../css.css' type='text/css'>\n");
	imgwin.document.write("<title>:::문학과지성사 홈페이지에 오신걸 환영합니다:::</title>\n"); 
	
	imgwin.document.write("<sc"+"ript>\n"); 
	imgwin.document.write("function resize() {\n"); 
	imgwin.document.write("pic = document.il;\n"); 
	imgwin.document.write("if (eval(pic).height) { var name = navigator.appName\n"); 
	imgwin.document.write("  if (name == 'Microsoft Internet Explorer') { myHeight = eval(pic).height + 90; myWidth = eval(pic).width + 12;\n"); 
	imgwin.document.write("  } else { myHeight = eval(pic).height + 9; myWidth = eval(pic).width; }\n"); 
	imgwin.document.write("  clearTimeout();\n"); 
	imgwin.document.write("  var height = screen.height;\n"); 
	imgwin.document.write("  var width = screen.width;\n"); 
	imgwin.document.write("  var leftpos = width / 2 - myWidth / 2;\n"); 
	imgwin.document.write("  var toppos = height / 2 - myHeight / 2; \n"); 
	imgwin.document.write("  self.moveTo(leftpos, toppos);\n"); 
	imgwin.document.write("  self.resizeTo(myWidth, myHeight-10);\n"); 
	imgwin.document.write("}else setTimeOut(resize(), 100);}\n"); 
	imgwin.document.write("</sc"+"ript>\n"); 
	
	imgwin.document.write("</head>\n"); 
	imgwin.document.write('<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" bgcolor=EFEFEF>\n'); 
	imgwin.document.write('<table border=0 cellspacing=0 cellpadding=0><tr><td>\n');
	imgwin.document.write("<img border=0 src="+what+" xwidth=100 xheight=9 name=il onload='resize();'>\n"); 	 
	imgwin.document.write("</td></tr><tr height='50'><td valign=middle align=center><b>"+title+"</b></td></tr></table>\n"); 
	
	imgwin.document.write("</body>\n"); 
	imgwin.document.close(); 
	
} 
