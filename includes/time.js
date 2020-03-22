var xmlHttp;function srvTime(){try{xmlHttp=new XMLHttpRequest()}
catch(err1){try{xmlHttp=new ActiveXObject('Msxml2.XMLHTTP')}
catch(err2){try{xmlHttp=new ActiveXObject('Microsoft.XMLHTTP')}
catch(eerr3){alert("AJAX not supported")}}}
xmlHttp.open('HEAD',window.location.href.toString(),!1);xmlHttp.setRequestHeader("Content-Type","text/html");xmlHttp.send('');return xmlHttp.getResponseHeader("Date")}
function nuketime(){var st=srvTime();var d=new Date(st);var t_hour=d.getHours();var t_min=d.getMinutes();var t_sec=d.getSeconds();if(t_hour<10){t_hour="0"+t_hour}
if(t_min<10){t_min="0"+t_min}
if(t_sec<10){t_sec="0"+t_sec}
document.getElementById("timeofnuke").innerHTML=t_hour+":"+t_min+":"+t_sec;setTimeout('nuketime()',1000)}
nuketime()