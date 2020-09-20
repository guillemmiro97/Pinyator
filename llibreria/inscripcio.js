function Event()
{
	event_id=0;
	estat=0;
	casteller_id=0;
}

var events=[];

var total=0;
var actual=0;
 
 function DesaAssistenciaCasteller(t, a)
 {
	total=t;
	actual=a;
 }

function EventNou(event_id,estat,casteller_id)
{
  var event = new Event;
  event.event_id = event_id;
  event.estat = estat;
  event.casteller_id=casteller_id;
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
		ModificaAcompanyant(event_id, casteller_id, count);
	}
}

function DecrementaAcompanyant(event_id, casteller_id) 
{	
    if (event_id > 0)
	{			
		var count = parseInt(document.getElementById("AE"+event_id+"C"+casteller_id).innerHTML)-1;
		ModificaAcompanyant(event_id, casteller_id, count);
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
				var elementId = "AE"+event_id+"C"+casteller_id;
				var operador = count - parseInt(document.getElementById(elementId).innerHTML);
				document.getElementById(elementId).innerHTML = count;
				ModificaSom(event_id, operador);
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
		var frameList = document.getElementsByName("E"+event_id);
		for (var i= 0;i < frameList.length;i++)
		{
			if (parseInt(frameList[i].innerHTML) > 0)
				frameList[i].innerHTML=parseInt(frameList[i].innerHTML)+operador;
			else
				frameList[i].innerHTML=1;
		}
	}
}

function ModificaRanking(operador)
{
	if (operador != 0)
	{
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
			}
			else
			{
				star.style.width = "14px";
			}
		}
	}
}