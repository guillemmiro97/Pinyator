function Event()
{
	event_id=0;
	estat=0;
	casteller_id=0;
	apuntats=0;
	maxParticipants=0;
	maxAcompanyants=0;
}

var events=[];

var total=0;
var actual=0;
 
 function DesaAssistenciaCasteller(t, a)
 {
	total=t;
	actual=a;
 }

function EventNou(event_id,estat,casteller_id,apuntats,maxParticipants,maxAcompanyants)
{
  var event = new Event;
  event.event_id = event_id;
  event.estat = estat;
  event.casteller_id=casteller_id;
  event.apuntats=apuntats;
  event.maxParticipants=maxParticipants;
  event.maxAcompanyants=maxAcompanyants;
  events.push(event);
}

function CastellerSet(uuid)
{
	casteller_id=uuid;
}

//Save to database
function Save(eventid, castellerid) 
{	
	var i=0;
	var index=0;
	var estat=0;
	var event_id=0;
	var casteller_id=0;
	
	for(i=0;i<events.length;i++)
	{
		if ((events[i].event_id==eventid) && (events[i].casteller_id==castellerid))
		{
			index=i;
			estat = events[i].estat;
		    event_id = events[i].event_id;
			casteller_id = events[i].casteller_id;

		    if (estat==0)
			{ 
				estat = 1;
				events[i].estat=estat;
			}
			else
			{
				estat = 0;
				events[i].estat=estat;
			}
		}		
	}

    if(event_id > 0)
	{			
		var elementNom="E"+event_id+"C"+castellerid;
		var eventNom="E"+event_id;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			var operador=0;
			if (this.readyState == 4 && this.status == 200) 
			{
				if (estat == 0)
				{
					document.getElementById(elementNom).style.backgroundColor = "#ff1a1a";
					document.getElementById(elementNom).innerHTML = "No vinc";
					operador=-1;
				}
				else
				{					
					document.getElementById(elementNom).style.backgroundColor = "#33cc33";
					document.getElementById(elementNom).innerHTML = "Vinc";
					operador=1;
				}
				
				var frame = document.getElementById("counterCastellers");
				if (frame != null)
				{
					frame.contentDocument.location.reload(true);
				}
				
				ModificaSom(event_id, operador);
				ModificaRanking(operador);
			}
		};	
		xmlhttp.open("GET", "Inscripcio_Desa.php?e=" + event_id + "&c=" + casteller_id + "&s=" + estat, true);
		xmlhttp.send();
	}
}

function PrimerSave(event_id, casteller_id) 
{	
    if(event_id > 0)
	{			
		var elementNom="E"+event_id+"C"+casteller_id;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{				
				document.getElementById(elementNom).innerHTML = "No vinc";
				document.getElementById(elementNom).style.backgroundColor = "#ff1a1a";
			}
		};	
		xmlhttp.open("GET", "Inscripcio_Desa.php?e=" + event_id + "&c=" + casteller_id + "&s=" + 0, true);
		xmlhttp.send();
	}
}

function PrimerSaveLike(event_id, casteller_id) 
{	
    if(event_id > 0)
	{			
		var elementNom="E"+event_id+"C"+casteller_id;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{				

			}
		};	
		xmlhttp.open("GET", "Inscripcio_Desa.php?e=" + event_id + "&c=" + casteller_id + "&s=" + 0, true);
		xmlhttp.send();
	}
}

function Vinc(event_id, casteller_id) 
{	
	ModificaEstatInscripcio(event_id, casteller_id, "Vinc", "#33cc33", 1);
}

function NoVinc(event_id, casteller_id) 
{	
	ModificaEstatInscripcio(event_id, casteller_id, "No vinc", "#ff1a1a", 0);
}

function Aqui(event_id, casteller_id) 
{	
    ModificaEstatInscripcio(event_id, casteller_id, "Aqui", "#B0E0E6", 2);
}

function Marxa(event_id, casteller_id) 
{	
    ModificaEstatInscripcio(event_id, casteller_id, "Marxa", "#E303FC", 3);
}

function ModificaEstatInscripcio(event_id, casteller_id, str, color, estat) 
{	
    if(event_id > 0)
	{			
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			document.getElementById("txtErrors").innerHTML = xmlhttp.responseText;
			if (this.readyState == 4 && this.status == 200) 
			{				
				document.getElementById(casteller_id).innerHTML = str;
				document.getElementById(casteller_id).style.backgroundColor = color;
			}
		};	
		xmlhttp.open("GET", "Inscripcio_Desa.php?e=" + event_id + "&c=" + casteller_id + "&s=" + estat, true);
		xmlhttp.send();
	}
}

function Esborra(event_id, casteller_id) 
{	
    if(event_id > 0)
	{			
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			document.getElementById("txtErrors").innerHTML = xmlhttp.responseText;
			if (this.readyState == 4 && this.status == 200) 
			{				
				document.getElementById(casteller_id).innerHTML = "????";
				document.getElementById(casteller_id).style.backgroundColor = "#FFFF00";
			}
		};	
		xmlhttp.open("GET", "Inscripcio_Esborra.php?e=" + event_id + "&c=" + casteller_id, true);
		xmlhttp.send();
	}
}

function IncrementaAcompanyant(event_id, casteller_id) 
{	
    if (event_id > 0)
	{			
		var count = parseInt(document.getElementById("AE"+event_id+"C"+casteller_id).innerHTML)+1;
		var allowed = true;
		var i=0;
		var trobat = false;
		while((i<events.length) && (!trobat))
		{
			trobat = ((events[i].event_id==event_id) && (events[i].casteller_id==casteller_id));
			if (trobat)
			{			
				allowed = (events[i].apuntats < events[i].maxParticipants) || (events[i].maxParticipants == 0);
				allowed = allowed && (count <= events[i].maxAcompanyants || events[i].maxAcompanyants == 0);
			}
			i++;			
		}

		if (allowed)
		{			
			ModificaAcompanyant(event_id, casteller_id, count);
		}
	}
}

function DecrementaAcompanyant(event_id, casteller_id) 
{	
    if (event_id > 0)
	{		
		var count = parseInt(document.getElementById("AE"+event_id+"C"+casteller_id).innerHTML);
		if (count > 0)
		{
			ModificaAcompanyant(event_id, casteller_id, count-1);
		}
	}
}


function ModificaAcompanyant(event_id, casteller_id, count) 
{	
    if ((event_id > 0) && (count>=0))
	{			
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				var str = this.response.trim();
				if (str == "")
				{
					var elementId = "AE"+event_id+"C"+casteller_id;
					var operador = count - parseInt(document.getElementById(elementId).innerHTML);
					document.getElementById(elementId).innerHTML = count;
					ModificaSom(event_id, operador);
				}
				else if (str == "NotAllowed")
				{
					location.reload();
				} 
			}
		};	
		xmlhttp.open("GET", "Inscripcio_Desa.php?e=" + event_id + "&c=" + casteller_id + "&a=" + count, true);
		xmlhttp.send();
	}
}

function ModificaSom(event_id, operador)
{
	if ((event_id > 0) && (operador != 0))
	{
		var valor = 0;
		var frameList = document.getElementsByName("E"+event_id);
		for (var i= 0;i < frameList.length;i++)
		{
			if (parseInt(frameList[i].innerHTML) > 0)
			{
				valor=parseInt(frameList[i].innerHTML)+operador;
			}
			else
			{
				valor = 1;
			}
			frameList[i].innerHTML=valor;
		}
		
		for (var i= 0;i < events.length;i++)
		{
			if(events[i].event_id==event_id)
			{	
				events[i].apuntats = valor;
			}
			i++;			
		}
	}
}

function ModificaRanking(operador)
{
	var star = document.getElementById("star1");
	if ((operador != 0) && (star))
	{
		var lastStar;
		increment = 0;
		actual = actual + operador;
		percentatgeAssistencia = ((actual/total)*100);
		
		for (var i= 0;i < 10;i++)
		{
			var star = document.getElementById("star"+i);

			if((i*10) < percentatgeAssistencia)
			{
				star.style.display = "";
			}
			else
			{
				star.style.display = "none";
			}
						
			if ((percentatgeAssistencia-(i*10))>5)
			{
				star.style.width = "";
				increment = 0;
			}
			else
			{
				star.style.width = "14px";
				increment = 7;
			}
			
			if (star.style.display == "")
			{
				lastStar = star;
				leftPosition = getOffset(lastStar).leftCenter+increment;
			}
		}
		
		if (operador == 1)
		{
			var burst = new mojs.Burst({
				left: leftPosition, 
				top: getOffset(lastStar).topCenter,
				radius:   { 4: 32 },
				angle:    45,
				count:    14,
				children: {
				radius:       2.5,
				fill:         'gold',
				scale:        { 1: 0, easing: 'quad.in' },
				pathScale:    [ .8, null ],
				degreeShift:  [ 13, null ],
				duration:     [ 500, 700 ],
				easing:       'quint.out'}});

			burst.replay();
		}
	}
}

function OnClickLike(like_cnt, eventid, castellerid, casteller_ranking, tipus)
{
	var check_status = like_cnt.classList.contains("checked");
	var imgId = "IMG" + like_cnt.id;
	var img = document.getElementById(imgId);
	var operador=0;
	
	var allowed = true;
	
	var estat=0;
	var event_id=eventid;
	var casteller_id=castellerid;

	var assitencia = document.getElementById("assitenciaNum");
	var eventsTotals = document.getElementById("eventsNum");
	
	for(i=0;i<events.length;i++)
	{
		if ((events[i].event_id==eventid) && (events[i].casteller_id==castellerid))
		{			
		    if (!check_status)
			{ 
				allowed = (events[i].apuntats < events[i].maxParticipants) || (events[i].maxParticipants == 0);
				if (allowed)
				{
					estat = 1;
					events[i].estat=estat;
				}
			}
			else
			{
				estat = 0;
				events[i].estat=estat;
			}
		}		
	}	
	
	if(event_id > 0)
	{			
		var eventNom="E"+event_id;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			
			if (this.readyState == 4 && this.status == 200) 
			{
				var str = this.response.trim();
				if (str == "")
				{					
					if(!check_status)
					{
						if (allowed)
						{
							var burst = new mojs.Burst({
								left: getOffset(like_cnt).leftCenter, 
								top: getOffset(like_cnt).topCenter,
								radius:   { 4: 32 },
								angle:    45,
								count:    14,
								children: {
								radius:       2.5,
								fill:         '#03AC47',
								scale:        { 1: 0, easing: 'quad.in' },
								pathScale:    [ .8, null ],
								degreeShift:  [ 13, null ],
								duration:     [ 500, 700 ],
								easing:       'quint.out'}});


							like_cnt.classList.add("checked");
							img.src='icons/semaforVerd.png';
							burst.replay();
							operador=1;
							assitencia.textContent = parseInt(assitencia.textContent) + 1;
						}
					}
					else
					{
						img.src='icons/semaforVermell.png';
						like_cnt.classList.remove("checked");
						operador=-1;
						assitencia.textContent = parseInt(assitencia.textContent) - 1;
					}				
					
					var frame = document.getElementById("counterCastellers");
					if (frame != null)
					{
						frame.contentDocument.location.reload(true);
					}

					ModificaSom(event_id, operador);
					if ((castellerid == casteller_ranking) && (tipus==0/*assaig*/))
					{
						ModificaRanking(operador);
					}


					var elementIMG = "IMGstrikeButton";
					if(assitencia.textContent == eventsTotals.textContent){
						strikeButtonID.classList.add("checked");
						document.getElementById(elementIMG).src = "icons/pleno.png"
					} else {
						strikeButtonID.classList.remove("checked");
						document.getElementById(elementIMG).src = "icons/noPleno.png"
					}

				}
				else if (str == "NotAllowed")
				{
					location.reload();
				}
			}
		};	
		if(allowed)
		{
			xmlhttp.open("GET", "Inscripcio_Desa.php?e=" + event_id + "&c=" + casteller_id + "&s=" + estat, true);
			xmlhttp.send();
		}
	}
}

function getOffset(el) 
{
  const rect = el.getBoundingClientRect();
  return {
    left: rect.left + window.scrollX,
    top: rect.top + window.scrollY,
	leftCenter: rect.left + window.scrollX + (rect.width/2),
    topCenter: rect.top + window.scrollY + (rect.height/2)
  };
}

function OnClickStrike(strikeButtonID, castellerID)
{
	var assitencia = document.getElementById("assitenciaNum");

	var checkedStatus = strikeButtonID.classList.contains("checked");
	var elementIMG = "IMGstrikeButton";

	var elementStrike = "strikePopUp";

	if(!checkedStatus){
		strikeButtonID.classList.add("checked");
		document.getElementById(elementIMG).src = "icons/pleno.png"
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.open("GET", "Inscripcio_Desa_Tot.php?c=" + castellerID, true);
		xmlhttp.send();
		var numButons = document.querySelectorAll('[id^="IMGE"]').length;

		var numText = document.querySelectorAll('[name^="E"]').length;
		for(var i = 0; i < numText; i++){
			if(! document.querySelectorAll('[id^="IMGE"]')[i].src.includes( "icons/semaforVerd.png")){
				document.querySelectorAll('[name^="E"]')[i].textContent = parseInt(document.querySelectorAll('[name^="E"]')[i].textContent) + 1;
				document.querySelectorAll('[class^="like-cnt"]')[i].classList.add("checked");
			}
		}

		for(var i = 0; i < numButons; i++){
			document.querySelectorAll('[id^="IMGE"]')[i].src = 'icons/semaforVerd.png';
		}
		assitencia.textContent = numButons;

	} else {
		strikeButtonID.classList.remove("checked");
		document.getElementById(elementIMG).src = "icons/noPleno.png"
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.open("GET", "Inscripcio_Esborra_Tot.php?c=" + castellerID, true);
		xmlhttp.send();

		var numText = document.querySelectorAll('[name^="E"]').length;
		for(var i = 0; i < numText; i++){
			document.querySelectorAll('[name^="E"]')[i].textContent = parseInt(document.querySelectorAll('[name^="E"]')[i].textContent) - 1;
			document.querySelectorAll('[class^="like-cnt"]')[i].classList.remove("checked");
		}

		var numButons = document.querySelectorAll('[id^="IMGE"]').length;
		for(var i = 0; i < numButons; i++){
			document.querySelectorAll('[id^="IMGE"]')[i].src = 'icons/semaforVermell.png';
		}
		assitencia.textContent = 0;

	}

}