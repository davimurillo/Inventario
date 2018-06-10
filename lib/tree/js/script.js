window.onload = function(){
    tmspans=document.getElementsByTagName("span");
	//if(tmspans.length==0)
	//   tmspans=getElementsByName_iefix("span", "tmtext");
    var i=0;
   
    while(i<tmspans.length)
    {
       if(tmspans[i].className == "tmNode")
       {
            tmspans[i].onmouseover=function(i){__tmHighlight(this);};
            tmspans[i].onmouseout=function(i){__tmLowlight(this);};
       }
       i++;
    }
    tmimages=document.getElementsByName("tmImage");
	if(tmimages.length==0)
	   tmimages=getElementsByName_iefix("img", "tmImage");
    i=0;
    while(i<tmimages.length)
    {
       tmimages[i].onmouseover=function(){__tmHighlight(this);};
       tmimages[i].onmouseout=function(){__tmLowlight(this);};
       i++;
    }
}

function getElementsByName_iefix(tag, name)
{
   var elem = document.getElementsByTagName(tag);
   var arr = new Array();
   for(i = 0,iarr = 0; i < elem.length; i++) {
      att = elem[i].getAttribute("name");
      if(att == name) {
         arr[iarr] = elem[i];
         iarr++;
      }
   }
   return arr;
}
function __tmHighlight(span) {
    var id = span.id.substring(4);
    var imgid = "imge" + id;
    var textid = "text" + id;
    if (jTMenu(textid).className == "tmRegular")
        jTMenu(textid).className = "tmHighlighted";
    if(jTMenu(imgid)!= null)
    {   
        if(jTMenu(imgid).src.indexOf("plus-sel") == -1)
            jTMenu(imgid).src = jTMenu(imgid).src.replace("plus","plus-sel");
        if(jTMenu(imgid).src.indexOf("minus-sel") == -1)
            jTMenu(imgid).src = jTMenu(imgid).src.replace("minus","minus-sel");
     }
}
function __tmLowlight(span) {

    var id = span.id.substring(4);
    var imgid = "imge" + id;
    var textid = "text" + id;
    if (jTMenu(textid).className == "tmHighlighted")
        jTMenu(textid).className = "tmRegular";
        if(jTMenu(imgid)!= null)
        {
            jTMenu(imgid).src=jTMenu(imgid).src.replace("-sel","");
        } 
}
function str_replace(search, replace, subject) {
    return subject.split(search).join(replace);
} 

 function __tmPostBack(nodeid,parentid)
 {
    if(jTMenu("text" + nodeid).className == "tmSelectedPass") return;
    var parentid = nodeid.substr(0, nodeid.indexOf("_"));
    if(jTMenu("node" + nodeid) != null)
    {
        jTMenu("node" + nodeid).value="e";
        var oldid = jTMenu('nodeid' + parentid).value;
        if(jTMenu('node' + oldid) != null && jTMenu('node' + oldid).value == "e"
           && nodeid.substring(0, nodeid.lastIndexOf("_")) == oldid.substring(0, oldid.lastIndexOf("_")))
            jTMenu('node' + oldid).value="c";
    }
    jTMenu('nodeid' + parentid).value = nodeid;
    jTMenu('frmnodes' + parentid).submit();
}
function __tmSwitch(nodeid, directory) {
    var parentid = nodeid.substr(0, nodeid.indexOf("_"));
    icon = jTMenu("pic" + nodeid);
    classNaming = jTMenu("tm" + nodeid).className;
    if (classNaming == "tmExpanded") {
        jTMenu("imge" + nodeid).src = str_replace("minus", "plus", jTMenu("imge" + nodeid).src);
        jTMenu("tm" + nodeid).className = "tmCollapsed";
        if (icon != null)
            icon.src = str_replace("folderopened", "folder", icon.src);
        jTMenu("node" + nodeid).value = "c";
    }
    else if (classNaming == "tmCollapsed") {
        if (directory != "") {
        
            jQuery.get(jTMenu("path" + parentid).value + "inc/getfiles.php",
                {
                    directory: directory,
                    style: jTMenu("style" + parentid).value,
                    folderIcons: jTMenu("folderIcons" + parentid).value,
                    fileIcons: jTMenu("fileIcons" + parentid).value,
                    parentId: nodeid,
                    path: jTMenu("path" + parentid).value,
                    password: "apphpmenu",
                    direction: jTMenu("direction" + parentid).value
                },
                function (data) {
                    //jTMenu("innercontainer" + parentid).innerHTML = data;
                    numNodes = data.substring(0, data.indexOf("<") - 1);
                    if (numNodes == 0) {
                    jTMenu("imge" + nodeid).style.visibility = "hidden";
                    }
                    else {
                    data = data.substring(data.indexOf("<"));
                    if (jTMenu("showNumFiles" + parentid).value == 1) {
                    jTMenu("text" + nodeid).innerHTML += " (" + numNodes + ")";
                    }
                    jTMenu("tm" + nodeid).innerHTML += data;
                    jTMenu("span" + nodeid).onmouseover = function () {
                    __tmHighlight(this);
                    };
                    jTMenu("span" + nodeid).onmouseout = function () {
                    __tmLowlight(this);
                    };
                    jTMenu("imge" + nodeid).onmouseover = function () {
                    __tmHighlight(this);
                    };
                    jTMenu("imge" + nodeid).onmouseout = function () {
                    __tmLowlight(this);
                    };
                    jTMenu("imge" + nodeid).onclick = function () {
                    __tmSwitch(nodeid, "");
                    };
                    }
                    jTMenu("span" + nodeid).onclick = function () {
                    __tmPostBackAjax(nodeid, "/", "file");
                    };

                });
        }
        jTMenu("imge" + nodeid).src = str_replace("plus", "minus", jTMenu("imge" + nodeid).src);
        jTMenu("tm" + nodeid).className = "tmExpanded";
        if (icon != null)
            icon.src = str_replace("folder", "folderopened", icon.src);
        jTMenu("node" + nodeid).value = "e";
    }

}

function jTMenu(id)
{
	return document.getElementById(id);
}

function __tmPostBackAjax(newid, filename, mode) {
    if(jTMenu("text" + newid).className == "tmSelectedPass") return;
    var parentid=newid.substr(0,newid.indexOf("_"));
    if (mode == "file")
        jTMenu("innercontainer" + parentid).innerHTML = "<img src='" + jTMenu("path" + parentid).value + "styles/ajax_loading.gif' alt='' />";
    oldid = jTMenu("nodeid" + parentid).value;
    if (oldid.length != 0) {
        jTMenu("text" + oldid).className = "tmRegular";
    }
    jTMenu("nodeid" + parentid).value = newid;
    if (oldid != "" && jTMenu("tm" + oldid).className == "tmExpanded" && newid.substring(0, newid.lastIndexOf("_")) == oldid.substring(0, oldid.lastIndexOf("_")))
        __tmSwitch(oldid);
    if (jTMenu("tm" + newid).className == "tmCollapsed") {
        if (mode == "filesystem") {
            __tmSwitch(newid, filename);
        }
        else __tmSwitch(newid, "");
    }
    if (jTMenu("refresh"+parentid).value=="1")
        jTMenu("text" + newid).className = "tmSelected";
	else jTMenu("text" + newid).className = "tmSelectedPass";
    if (oldid != null && jTMenu("code" + oldid) != null) {
        jTMenu("code" + oldid).style.display = 'none';
    }
    if (mode == "file") {
        jQuery.get(jTMenu("path" + parentid).value + "inc/getcontent.php", { filename: filename, password: "apphpmenu",
        debug: jTMenu("debug" + parentid).value },
           function (data) {
               if(data.lastIndexOf("|||") != -1)
               {
                    if(jTMenu("tmTime" + parentid)!=null)
                        jTMenu("tmTime" + parentid).innerHTML = data.substring(data.lastIndexOf("|||")+3);
                    jTMenu("innercontainer" + parentid).innerHTML = data.substring(0, data.lastIndexOf("|||"));
               }
               else
               jTMenu("innercontainer" + parentid).innerHTML = data;
           });
    }
    else if (mode == "code") {
        jTMenu("code" + newid).style.display = '';
        jTMenu("innercontainer" + parentid).innerHTML = '';
    }
}

function __tmPostBackNewWindow(nodeid,parentid,filename)
{
    if(jTMenu("text" + nodeid).className == "tmSelectedPass") return;
    var previousAction = jTMenu("frmnodes"+parentid).action;
    jTMenu("frmnodes"+parentid).action = filename;
    if(previousAction.indexOf('?') != -1)
      {
         var previousparameters = previousAction.substring(previousAction.indexOf('?')+1);
         if(filename.indexOf('?') != -1)
             jTMenu("frmnodes"+parentid).action += "&" + previousparameters;
         else jTMenu("frmnodes"+parentid).action += "?" + previousparameters;
      }
    __tmPostBack(nodeid,parentid)
}