function getCookie(c_name)
{
    var c_value = document.cookie;
    var c_start = c_value.indexOf(" " + c_name + "=");
    if (c_start == -1){
        c_start = c_value.indexOf(c_name + "=");
    }
    if (c_start == -1){
        c_value = null;
    }else{
        c_start = c_value.indexOf("=", c_start) + 1;
        var c_end = c_value.indexOf(";", c_start);
        if (c_end == -1){
            c_end = c_value.length;
        }
        c_value = unescape(c_value.substring(c_start,c_end));
    }
    return c_value;
}
 
function setCookie(c_name,value,exdays)
{
    var exdate=new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
    document.cookie=c_name + "=" + c_value;
}
 
function iniCookie(cookieName, idElement)
{
	if(getCookie(cookieName) != "1")
	{
		document.getElementById(idElement).style.display="block";
	}
	else
	{
		document.getElementById(idElement).style.display="none";
	}
}

function iniCookiePolitical(cookieName, idElement)
{
	iniCookie(cookieName, idElement);
	
	if (window.innerWidth < 450)
	{
		document.getElementById(idElement).style.height="30%";
	}
	else if (window.innerWidth < 600)
	{
		document.getElementById(idElement).style.height="20%";
	}
}

function PonerCookie(cookieName, idElement)
{
    setCookie(cookieName,'1',365);
    document.getElementById(idElement).style.display="none";
}
function HideMessage(idElement)
{
	document.getElementById(idElement).style.display="none";	
}
