function fitImagePop(what) { 
		
	var imgwin = window.open("",'WIN','scrollbars=no,status=no,toolbar=no,resizable=1,location=no,menu=no,width=10,height=10'); 
	imgwin.focus(); 
	imgwin.document.open(); 
	imgwin.document.write("<html>\n"); 
	imgwin.document.write("<head>\n"); 
	imgwin.document.write("<title>:::이미지보기:::</title>\n"); 
	
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
	imgwin.document.write("<title>:::이미지보기:::</title>\n"); 
	
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
