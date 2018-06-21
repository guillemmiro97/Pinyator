
<!--//BLOQUE COOKIES-->
<div id="barraaceptacion" style="display: block; height:15%" >
    <div class="inner">
        Solicitamos su permiso para obtener datos de su navegaci&oacute;n en esta web y hacer la navegaci&oacute;n m&aacute;s f&aacute;cil, en cumplimiento del Real 
        Decreto-ley 13/2012. Si contin&uacute;a navegando consideramos que acepta el uso de cookies.
        <a href="javascript:void(0);" class="ok" onclick="PonerCookie();"><b>OK</b></a> | 
        <a href="http://politicadecookies.com" target="_blank" class="info">M&aacute;s informaci&oacute;n</a>
    </div>
</div>

<script>
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
 
if(getCookie('avisoCookie') != "1")
{
    document.getElementById("barraaceptacion").style.display="block";
}
else
{
	document.getElementById("barraaceptacion").style.display="none";
}

if (window.innerWidth < 450)
{
	document.getElementById("barraaceptacion").style.height="30%";
}
else if (window.innerWidth < 600)
{
	document.getElementById("barraaceptacion").style.height="20%";
}

function PonerCookie()
{
    setCookie('avisoCookie','1',365);
    document.getElementById("barraaceptacion").style.display="none";
}
</script>
<!--//FIN BLOQUE COOKIES-->