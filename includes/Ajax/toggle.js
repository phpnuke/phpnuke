function Toggle(item,align)
{obj=document.getElementById(item);visible=(obj.style.display!="none")
key=document.getElementById("x"+item);if(visible)
{obj.style.display="none";key.innerHTML="<img src='images/CZModules/"+align+"/closed.gif' width=\"34\" height=\"18\" alt=\"closed\" title=\"closed\" align='absmiddle' border='0'>"}
else{obj.style.display="block";key.innerHTML="<img src='images/CZModules/"+align+"/open.gif' width=\"34\" height=\"16\" alt=\"open\" title=\"open\" align='absmiddle' border='0'>"}}