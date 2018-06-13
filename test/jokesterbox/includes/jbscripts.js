var xmlhttp
function sendFollow(url, uid)
{
if (url=='follow.php?')
{
 var flink = "follow.php?uid=" + uid;
} 
else if(url=='unfollow.php?') 
{
var flink = "unfollow.php?uid=" + uid;
}


flink=flink+"&ssid="+Math.random();


xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
{
alert ("Your browser does not support XMLHTTP!");
return;
} 


xmlhttp.onreadystatechange=fstateChanged;
xmlhttp.open("GET",flink,true);
xmlhttp.send(null);
}

function sendComment(sid, sk, pid, value)
{
	if (value.length>3)
	{		
var val = encodeURI(value);

var url="send_comment.php?sid=" +sid + "&pid=" + pid;

url=url+ "&c=" + val + "&sk=" + sk;

url=url +"&ssid="+Math.random();


xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
{
alert ("Your browser does not support XMLHTTP!");
return;
} 


xmlhttp.onreadystatechange=function() 
{
if (xmlhttp.readyState==4)
{
document.getElementById('jbb'+sid).innerHTML=xmlhttp.responseText;
}
}
xmlhttp.open("GET",url,true);
xmlhttp.send(null);
	}
}


function sendLike(sid, sk, pid)
{
	

var url="send_like.php?sid=" +sid + "&pid=" + pid;

url=url + "&sk=" + sk +"&ssid="+Math.random();


xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
{
alert ("Your browser does not support XMLHTTP!");
return;
} 


xmlhttp.onreadystatechange=function() 
{
if (xmlhttp.readyState==4)
{
document.getElementById('jbb'+sid).innerHTML=xmlhttp.responseText;
}
}
xmlhttp.open("GET",url,true);
xmlhttp.send(null);
	
}

function sendPerson(a)
{
	

var url="send_people.php?pid=" + a;

url=url +"&ssid="+Math.random();


xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
{
alert ("Your browser does not support XMLHTTP!");
return;
} 


xmlhttp.onreadystatechange=function() 
{
if (xmlhttp.readyState==4)
{
document.getElementById(a).innerHTML=xmlhttp.responseText;
}
}
xmlhttp.open("GET",url,true);
xmlhttp.send(null);
}

function sendSearch(page, query) 
{
var q = encodeURI(query);

var url="check_search.php?p=" +page + "&q=" + q;

url=url +"&ssid="+Math.random();

xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
{
alert ("Your browser does not support XMLHTTP!");
return;
} 


xmlhttp.onreadystatechange=function() 
{
if (xmlhttp.readyState==4)
{
document.getElementById("describe").innerHTML=xmlhttp.responseText;
}
}
xmlhttp.open("GET",url,true);
xmlhttp.send(null);

}

function checkMail(value) 
{
var m = encodeURI(value);

apos=m.indexOf("@");
dotpos=m.lastIndexOf(".");
if ( (apos<1||dotpos-apos<2))
{
	document.getElementById("mcheck").innerHTML="<b class='error'>Invalid Email Address.</b>";
}
else {

var url="check_email.php?m=" + m;

url=url +"&ssid="+Math.random();

xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
{
alert ("Your browser does not support XMLHTTP!");
return;
} 


xmlhttp.onreadystatechange=function() 
{
if (xmlhttp.readyState==4)
{
document.getElementById("mcheck").innerHTML=xmlhttp.responseText;
}
}
xmlhttp.open("GET",url,true);
xmlhttp.send(null);

}
}

function checkUser(value) 
{
	
var u = encodeURI(value);

if(value.length<4) {
	
	document.getElementById("ucheck").innerHTML="<b class='error'>Username is too short.</b>";
	
} else if(value.length>30) {
	document.getElementById("ucheck").innerHTML="<b class='error'>Username is too long.</b>";
} else {

var url="check_user.php?u=" + u;

url=url +"&ssid="+Math.random();

xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
{
alert ("Your browser does not support XMLHTTP!");
return;
} 


xmlhttp.onreadystatechange=function() 
{
if (xmlhttp.readyState==4)
{
document.getElementById("ucheck").innerHTML=xmlhttp.responseText;
}
}
xmlhttp.open("GET",url,true);
xmlhttp.send(null);

}
}


function sendLol(sid, sk, pid, a)
{
	

var url="send_lol.php?sid=" +sid + "&pid=" + pid;

url=url +"&sk=" +sk +"&a=" +a;

url=url +"&ssid="+Math.random();

xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
{
alert ("Your browser does not support XMLHTTP!");
return;
} 


xmlhttp.onreadystatechange=function() 
{
if (xmlhttp.readyState==4)
{
document.getElementById('jbb'+sid).innerHTML=xmlhttp.responseText;
}
}
xmlhttp.open("GET",url,true);
xmlhttp.send(null);
	
}

function sendBlock(sid, sk, pid, value)
{

if (value=='confirmed') {
var change_button = sid;
} else {
var change_button = 'jbb'+sid;
}	

var url="send_block.php?sid=" +sid + "&pid=" + pid;

url=url +"&b=" +value +"&sk=" +sk;

url=url +"&ssid="+Math.random();

xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
{
alert ("Your browser does not support XMLHTTP!");
return;
} 


xmlhttp.onreadystatechange=function() 
{
if (xmlhttp.readyState==4)
{

document.getElementById(change_button).innerHTML=xmlhttp.responseText;
}
}
xmlhttp.open("GET",url,true);
xmlhttp.send(null);
	
}

function postDel(sid, sk, pid, value)
{

if (value=='confirmed') {
var change_button = sid;
} else {
var change_button = 'jbb'+sid;
}		

var url="delete_post.php?sid=" +sid + "&pid=" + pid;

url=url +"&d=" +value +"&sk=" +sk;

url=url +"&ssid="+Math.random();

xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
{
alert ("Your browser does not support XMLHTTP!");
return;
} 


xmlhttp.onreadystatechange=function() 
{
if (xmlhttp.readyState==4)
{
document.getElementById(change_button).innerHTML=xmlhttp.responseText;
}
}
xmlhttp.open("GET",url,true);
xmlhttp.send(null);
	
}


function delDel(cid, sk, val)
{
var url="delete_comment.php?cid=" + cid;

url=url +"&confirm=" +val +"&sk=" +sk;

url=url +"&ssid="+Math.random();

xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
{
alert ("Your browser does not support XMLHTTP!");
return;
} 


xmlhttp.onreadystatechange=function() 
{
if (xmlhttp.readyState==4)
{
document.getElementById(cid).innerHTML=xmlhttp.responseText;
}
}
xmlhttp.open("GET",url,true);
xmlhttp.send(null);
	
}

function sendEndorse(sid, sk, pid)
{
	

var url="send_endorse.php?sid=" +sid + "&pid=" + pid;

url=url + "&sk=" + sk +"&ssid="+Math.random();

xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
{
alert ("Your browser does not support XMLHTTP!");
return;
} 


xmlhttp.onreadystatechange=function() 
{
if (xmlhttp.readyState==4)
{
document.getElementById('jbb'+sid).innerHTML=xmlhttp.responseText;
}
}
xmlhttp.open("GET",url,true);
xmlhttp.send(null);
	
}

function fstateChanged()
{
if (xmlhttp.readyState==4)
{
	
	
document.getElementById("follower").innerHTML=xmlhttp.responseText;
}
}

function GetXmlHttpObject()
{
if (window.XMLHttpRequest)
{
	return new XMLHttpRequest();
}
if (window.ActiveXObject)
{
// code for IE6, IE5
return new ActiveXObject("Microsoft.XMLHTTP");
}
return null;
}
// code for IE7+, Firefox, Chrome, Opera, Safari