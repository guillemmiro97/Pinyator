function ShowPopup(obj)
{
	if (obj != null)
	{
		document.getElementById("SI").name = obj.name;
	}
	var popup = document.getElementById("myPopup");
	popup.classList.toggle("show");
}

function Si()
{
	ShowPopup();
	Esborra(document.getElementById("SI").name);
}

function No()
{
	ShowPopup();
}

function Esborra(url)
{
	window.location.replace(url);
}
function ShowAvis(texte)
{
	document.getElementById("myAvis").context = texte;

	var popup = document.getElementById("myAvis");
	popup.classList.toggle("show");
}