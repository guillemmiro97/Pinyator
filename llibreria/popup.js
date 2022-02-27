function ShowPopup(obj, text)
{
	if (obj != null)
	{
		document.getElementById("SI").name = obj.name;
	}
	var popup = document.getElementById("myPopup");
	var txt = document.getElementById("myPopupText");
	txt.innerHTML = text;
	popup.style.left = (window.innerWidth / 2) - (popup.offsetWidth / 2);
	popup.style.top = (window.innerHeight / 2) - (popup.offsetHeight / 2);
	popup.classList.toggle("show");
}

function ShowPopupCopia(obj)
{
	ShowPopup(obj, "Estàs segur que vols COPIAR-LO?");
}

function ShowPopupEsborra(obj)
{
	ShowPopup(obj, "Estàs segur que vols ESBORRAR-LO?");
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