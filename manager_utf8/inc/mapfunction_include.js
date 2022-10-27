function clickHandler() {

	var targetId, srcElement, targetElement, folderId, folderElement;
	
	srcElement = window.event.srcElement;
	
	targetId = srcElement.id + "d";

	targetElement = document.all(targetId);
	
	folderId = srcElement.id + "f";
	folderElement = document.all(folderId);
	
	if( srcElement.className == "Outline" ) {
		if (targetElement.style.display == "none") {
			targetElement.style.display = "";
			srcElement.src = "../manager/images/mid_minus.gif";
			folderElement.src = "../manager/images/open.gif";

//			add_Node(targetId);
			
		} else {
			targetElement.style.display = "none";
			srcElement.src = "../manager/images/mid_plus.gif";
			folderElement.src = "../manager/images/close.gif";

//			remove_Node(targetId);

		}
		
	} else if (srcElement.className == "LastOutline") {
		if (targetElement.style.display == "none") {
			targetElement.style.display = "";
			srcElement.src = "../manager/images/last_minus.gif";
			folderElement.src = "../manager/images/open.gif";

//			add_Node(targetId);

		} else {
			targetElement.style.display = "none";
			srcElement.src = "../manager/images/last_plus.gif";
			folderElement.src = "../manager/images/close.gif";

//			remove_Node(targetId);

		}
	}
}

document.onclick = clickHandler;

function treeHandler(id , flag) {
	var targetId, srcElement, targetElement, folderId, folderElement;
    
	targetId = id + "d";
	targetElement = document.all(targetId);
    srcElement    = document.all(id);
    
 	folderId = srcElement.id + "f";
	folderElement = document.all(folderId);
	
    if( flag == "last") {
    	if (targetElement.style.display == "none") {
			targetElement.style.display = "";
			srcElement.src = "../manager/images/last_minus.gif";
			folderElement.src = "../manager/images/open.gif";
		} else {
			targetElement.style.display = "none";
			folderElement.src = "../manager/images/close.gif";
			srcElement.src = "../manager/images/last_plus.gif";
		}
	} else {
		if (targetElement.style.display == "none") {
			targetElement.style.display = "";
			srcElement.src = "../manager/images/mid_minus.gif";
			folderElement.src = "../manager/images/open.gif";
	
		} else {
			targetElement.style.display = "none";
			srcElement.src = "../manager/images/mid_plus.gif";
			folderElement.src = "../manager/images/close.gif";
		}
	}
}


function changeColor() {
	var srcElement, targetElement, o_node;
	o_node = document.all.old_node.value;
	srcElement = window.event.srcElement;
	if ( o_node != null && o_node != "" ) {
		targetElement = document.all(o_node);
		targetElement.style.backgroundColor='white';
	}
	
	document.theForm.old_node.value = srcElement.name;
	
	srcElement.style.backgroundColor='#CBE4C9';
}

function changeColor2(node) {
	var srcElement, targetElement, o_node;
	o_node = document.all.old_node.value;
    srcElement    = document.all('s' + node);
	if ( o_node != null && o_node != "" ) {
		targetElement = document.all(o_node);
		targetElement.style.backgroundColor='white';
	}
	
	document.theForm.old_node.value = srcElement.name;
	
	srcElement.style.backgroundColor='#CBE4C9';
}
	