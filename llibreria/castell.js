// $('html').keypress(function(e)
// {
	// if(mySel[0] != null)
    // {
		// if(e.keyCode == 46)
		// {
			// Esborra();
		// }		
	// }
// });


document.onkeydown = function(event) 
{
     if (!event)
	 {
          event = window.event;
     }
	 var code = event.keyCode;
     if (event.charCode && code == 0)
	 {    
		code = event.charCode;
     }
	 if(code == 46)
	{
		Esborra();
		event.preventDefault();
	}    
};

function boxJSON() 
{
  this.id = 0;
  this.txt = "";
  this.ca=0;
  this.cs=0;
  this.ps=0;
}


function CarregaAssaig() 
{
  this.id = 0;
  this.castell = [];
  this.carrega = 0;
}

// initialize our canvas, add a ghost canvas, set draw loop
// then add everything we want to intially exist on the canvas
function init() 
{

}

var elementActualId=0;


function ShowPopupCasella()
{
	/*if(document.getElementById("VeurePopup").checked)
	{
		var popup = document.getElementById("PopUpCasella");	
		popup.classList.toggle("show");
	}*/
}

function HidePopupCasella()
{
	//var popup = document.getElementById("PopUpCasella");
	//popup.classList.remove("show");
}


// Happens when the mouse is clicked in the canvas
function myDown(e)
{
	HidePopupCasella();
	HideEditPopup();
	ClearmySel();
	getMouse(e);
	clear(gctx);
	var l = boxes.length;
	for (var i = l-1; i >= 0; i--) 
	{
		if (EsPenstanyaActual(boxes[i].pestanya))
		{
			// draw shape onto ghost context
			drawshape(gctx, boxes[i]);
			
			// get image data at the mouse x,y pixel
			var imageData = gctx.getImageData(mx, my, 1, 1);
			var index = (mx + my * imageData.width) * 4;
			
			// if the mouse pixel exists, select and break
			if (imageData.data[3] > 0) 
			{
				mySel[0] = boxes[i];
				
				//Casteller seleccionat previament i clicar sobre casella plena i casella seguent
				if (EsCasteller(casteller) && (!EsCasellaBuida(mySel[0])) && (mySel[0].seguent > 0))
				{	
					if (!IsCastellerAfegit(casteller.id, mySel[0].castellId))
					{	//Si el casteller no estava a la pinya				
						Log1("Afegir a pinya");
						
						MouCastellers(mySel[0], casteller);
						Save();
					}			
					else if (IsCastellerAfegit(casteller.id, mySel[0].castellId))
					{	//Si el casteller estava a la pinya s´ha de moure de lloc		
						Log1("Moure casteller dins pinya");
						var indexes=GetIndexesBox(casteller.id, mySel[0].castellId);
						casellaDesti = mySel[0];
						if (indexes.length > 0)
						{
							ClearmySel();
							mySel[0] = boxes[indexes[0]];
							Log1("Treu de la pinya");
							for(var i=1;i<indexes.length;i++)
							{
								mySel.push(boxes[indexes[i]]);
							}
							ResetmySel();
							Save();
						}
						mySel[0] = casellaDesti;
						MouCastellers(casellaDesti, casteller);
						Save();
					}
					ResetCasteller();
					UpdataCarregaAssaig();
				}
				//Casteller seleccionat previament i clicar sobre casella buida
				else if (EsCasteller(casteller) && (EsCasellaBuida(mySel[0])))
				{	
					if (!IsCastellerAfegit(casteller.id, mySel[0].castellId))
					{	//Si el casteller no estava a la pinya				
						Log1("Afegir a pinya");
						CopiaCastellermySel(mySel[0], casteller);
						AfegeixCasellesLinkades(mySel[0].id, casteller, mySel[0].castellId);
						Save();
					}			
					else if (IsCastellerAfegit(casteller.id, mySel[0].castellId))
					{	//Si el casteller estava a la pinya s´ha de moure de lloc		
						Log1("Moure casteller dins pinya");
						var indexes=GetIndexesBox(casteller.id, mySel[0].castellId);
			
						CopiaCastellermySel(mySel[0], casteller);
						AfegeixCasellesLinkades(mySel[0].id, casteller, mySel[0].castellId);
						Save();				
						if (indexes.length > 0)
						{
							ClearmySel();
							mySel[0] = boxes[indexes[0]];								
							Log1("Treu de la pinya");
							for(var i=1;i<indexes.length;i++)
							{
								mySel.push(boxes[indexes[i]]);							
							}
							ResetmySel();
							Save();
						}					
					}
					ResetCasteller();
					UpdataCarregaAssaig();
				}
				//Casteller seleccionat previament i clicar sobre casella buida
				// else if ((casteller.id == -99) && (EsCasellaBuida(mySel[0])))
				// {
					// //Log1("Moure casteller dins pinya");
					// Log1(casteller.malnom);
					// var index=GetIndexBoxByIdText(casteller.id, casteller.malnom);	
				
					// if (index != -1)
					// {
						// mySel[0].castellerid = casteller.id;
						// mySel[0].text = casteller.malnom;

						// Save();	
						
						// //Log1("Treu de la pinya");
						// mySel[0] = boxes[index];
						// ResetmySel();
						// mySel[0].afegit=false;
						// Save();
					// }
					// ResetCasteller();
				// }
				else if (mySel[0].castellerid > 0)
				{//Seleccionar casteller de la pinya
					Log1("Seleccionar de la pinya");
					
					if (casteller.id > 0)
					{
						PintaCastellerLlista(casteller.id, casteller.inscrit);
					}
					
					casteller.id=mySel[0].castellerid;
					casteller.malnom=mySel[0].malnom;
					casteller.inscrit=mySel[0].inscrit;
					casteller.altura=mySel[0].altura;
					casteller.forca=mySel[0].forca;
					casteller.peu=mySel[0].peu;
					casteller.lesionat=mySel[0].lesionat;
					
					ShowPopupCasella();
				}
				else if (EsCastellerNoLLista(mySel[0].castellerid))
				{//Seleccionar casteller de la pinya
					Log1("Seleccionar de la pinya NO llista");
				
					casteller.id=mySel[0].castellerid;
					casteller.malnom=mySel[0].text;
					
					ShowPopupCasella();
				}
				else
				{
					ShowPopupCasella();
				}
				
				invalidate();
				clear(gctx);
				return;			
			}
		}
	}	
	ResetCasteller();
	// clear the ghost canvas for next time
	clear(gctx);
	// invalidate because we might need the selection border to disappear
	invalidate();
}

function EsCasteller(casteller)
{
	return (casteller.id > 0) || (EsCastellerNoLLista(casteller.id));
}

function EsCastellerNoLLista(id)
{
	return (id <= -99);
}

function CopiaCastellermySel(mySelect, objCasteller)
{
	mySelect.castellerid = objCasteller.id;
	mySelect.malnom = objCasteller.malnom;
	mySelect.text = objCasteller.malnom;
	mySelect.inscrit=objCasteller.inscrit;
	mySelect.altura=objCasteller.altura;
	mySelect.forca=objCasteller.forca;
	mySelect.peu=objCasteller.peu;
	mySelect.lesionat=objCasteller.lesionat;	
}

function AfegeixCasellesLinkades(id, objCasteller, castellId)
{
	var trobat = false;
	var i =0;
	while (!trobat && (i < boxes.length))
	{		
		if((id == boxes[i].linkat) 
			&& (objCasteller.id != boxes[i].castellerid)
			&& (castellId == boxes[i].castellId))
		{
			trobat = true;
			var mySelect = boxes[i];
			mySel.push(mySelect);
			CopiaCastellermySel(mySelect, objCasteller);
			AfegeixCasellesLinkades(mySelect.id, objCasteller, castellId);
		}
		i = i + 1;
	}
}

function TeCasteller(casella)
{
	return EsIdCasteller(casella.castellerid);
}

function EsIdCasteller(id)
{
	return (id> 0) || EsCastellerNoLLista(id);
}

function MouCastellers(casella, objCasteller)
{
	var trobat = false;
	var i =0;
	while (!trobat && (i < boxes.length))
	{		
		if((casella.seguent == boxes[i].id) && TeCasteller(casella))
		{
			trobat = true;			
						
			if (casella.malnom == "")
				casella.malnom = casella.text;
						
			var casteller = new Casteller();
			casteller.id=casella.castellerid;
			casteller.malnom=casella.malnom;
			casteller.inscrit=casella.inscrit;
			casteller.altura=casella.altura;
			casteller.forca=casella.forca;
			casteller.peu=casella.peu;
			casteller.lesionat=casella.lesionat;
			
			var mySelect = boxes[i];
			mySel.push(mySelect);
			
			MouCastellers(mySelect, casteller);
			
			CopiaCastellermySel(casella, objCasteller);
		}
		i = i + 1;
	}
	if (!trobat)
	{
		CopiaCastellermySel(casella, objCasteller);
	}
}

function EsCasellaBuida(item)
{
	return mySel[0].castellerid == 0 || mySel[0].castellerid == -1;
}

function myDblClick(e) 
{
	getMouse(e);
	clear(gctx);
	var l = boxes.length;
	for (var i = l-1; i >= 0; i--) 
	{
		if (EsPenstanyaActual(boxes[i].pestanya == pestanyaActual))
		{
			// draw shape onto ghost context
			drawshape(gctx, boxes[i]);
			
			// get image data at the mouse x,y pixel
			var imageData = gctx.getImageData(mx, my, 1, 1);
			var index = (mx + my * imageData.width) * 4;
			
			// if the mouse pixel exists, select and break
			if (imageData.data[3] > 0) 
			{
				mySel[0] = boxes[i];

				if (mySel[0].castellerid > 0)
				{					
					Esborra();
				}
				else
				{				
					EditaPopup();			
				}
				
				clear(gctx);
				return;
			}
		}
	}	
}

function EditaMenu()
{
	document.getElementById("text").value = mySel[0].text;
	ShowEditPopup();	
}

function EditaPopup()
{
	HidePopupCasella();
	document.getElementById("text").value = mySel[0].text;
	ShowEditPopup();
}

function EsborraMenu()
{
	Esborra();
}

function EsborraPopup()
{
	Esborra();
	ShowPopupCasella();
}

function Esborra()
{
	if (mySel[0] != null) 
	{		
		EsborraCasellesLinkades(mySel[0].id, mySel[0].castellerid, mySel[0].castellId);
		ResetmySel();
		Save();	
		ResetCasteller();
		UpdataCarregaAssaig();
		invalidate();
	}	
}

function EsborraCasellesLinkades(id, idCasteller, castellId)
{
	var trobat = false;
	var i = 0;
	while (!trobat && (i < boxes.length))
	{		
		if((id == boxes[i].linkat) && (boxes[i].castellId == castellId))
		{
			var y = 0;
			while((!trobat) && (y < mySel.length))
			{
				trobat = ((mySel[y].id == boxes[i].id) && (boxes[i].castellId == castellId));
				y = y+1;
			}
			if(!trobat)
			{
				trobat = true;
				mySelect = boxes[i];
				mySel.push(mySelect);
				EsborraCasellesLinkades(mySelect.id, idCasteller, castellId);
			}
		}
		i = i + 1;
	}
}

function Neteja()
{	
	if(confirm("Segurx que vols esborrar tothom que no està apuntat?"))
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				Log(this.responseText);
				window.location.reload(true);
			}
		};
		xmlhttp.open("GET", "Castell_Posicio_Desa.php?n=" + boxes[0].castellId, true);
		xmlhttp.send();
	}
}


function checkLinkatAfegit(id) 
{
    return this.id == id;
}

function ResetmySel()
{
	var l = mySel.length;
	if(l > 0)
	{
		for (var i = l-1; i >= 0; i--)
		{
			mySel[i].castellerid = 0;
			mySel[i].malnom="";
			mySel[i].text="";
			mySel[i].altura=0;
			mySel[i].forca=0;
			mySel[i].peu=1;
			mySel[i].lesionat=0;
		}
	}
}

function SaveUp()
{
}

function Save() 
{
	if (mySel != null)
	{		
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				Log(this.responseText);
			}
		};
		xmlhttp.open("GET", "Castell_Posicio_Desa.php?obj=" + getPosicio(mySel), true);
		xmlhttp.send();
	}
}

function getPosicio(obj)
{
	var arry = [];
	for(var i=0;i<obj.length;i++)
	{
		var b = new boxJSON();
		b.id = obj[i].dibuixid;
		b.ca = obj[i].castellerid;
		b.cs = obj[i].id;
		b.ps = obj[i].pestanya;
		b.txt = obj[i].text;
	
		arry.push(b);	
	}
	
	return JSON.stringify(arry);
}

function MarcaCastellerPinya()
{
	var trobat=false;
	var i=0;
	while((!trobat) && (i<boxes.length))
	{		
		trobat = (casteller.id==boxes[i].castellerid);
		if(trobat)
		{
			mySel[0] = boxes[i];
			mySel[0].castellerid = casteller.id;
			mySel[0].malnom = casteller.malnom;
		}
		i=i+1;
	}
	
	if(pestanyaFixada)
	{
		for(y=i;y<boxes.length;y++)
		{
			if(casteller.id==boxes[y].castellerid)
			{
				var mySelect = boxes[y];
				mySelect.castellerid = casteller.id;
				mySelect.malnom = casteller.malnom;
				mySel.push(mySelect);
			}
		}
	}
}

function ClearmySel()
{
	mySel[0] = null;
	while(mySel.length > 1)
	{
		mySel.pop();
	}
}

function ResetCasteller()
{
	if(casteller.id > 0)
	{	
		PintaCastellerLlista(casteller.id, casteller.inscrit);
		
		var item = document.getElementById(casteller.id);
		if (item != null)
		{
			item.style.display = MostrarCastellerLlista(true, item.id);
		}
		
		RefreshTotals();
	}
	ClearmySel();
	
	casteller.id = 0;
	casteller.malnom = "";
	casteller.inscrit = false;
	casteller.altura = 0;
	casteller.forca = 0;
	casteller.lesionat = 0;
	casteller.peu = 1;
}

function SetCasteller(elem, altura, forca, peu, lesionat)
{
	ResetCasteller();
	casteller.id = elem.id;	
	if(casteller.id > 0)
	{
		casteller.malnom = elem.title;
		casteller.inscrit = IsCastellerInscrit(casteller.id);
		casteller.altura = altura;
		casteller.forca = forca;
		casteller.peu = peu;
		casteller.lesionat = lesionat;
		elem.classList.add("castellerSeleccionat");
		elem.style.backgroundColor = "#81F781";
		MarcaCastellerPinya();
		invalidate();
	}	
}


//Utilitzat en el disseny.js
function PintaCastellerLlista(id, inscrit)
{
	if(id > 0)
	{		
		var item = document.getElementById(id);
		if (item != null)
		{		
			item.classList.remove("castellerSeleccionat");
			item.style.backgroundColor = "#FFF";

			if (inscrit)
				item.classList.remove("castellerNoInscrit");
			else
			{
				item.classList.add("castellerNoInscrit");
				item.style.backgroundColor = "#F5A9BC";
			}
			
			if (IsCastellerAfegit(id, 0))
				item.classList.remove("active");
			else
				item.classList.add("active");
			}
	}
}

function IsCastellerInscrit(id)
{
	var inscrit=0;
	if(id > 0)
	{
		var item = document.getElementById(id);

		inscrit = !item.classList.contains("castellerNoInscrit");
	}	
	return inscrit;
}

function GetIndexBox(id, castellId)
{
	var index = -1;
	if(EsIdCasteller(id))
	{
		var trobat=false;
		var i = 0;
		while((!trobat) && (i<boxes.length))
		{
			trobat = EsBox(i, id, castellId);
			i++;
		}
		if (trobat)
			index = i-1;
	}	
	return index;
}

function GetIndexesBox(id, castellId)
{
	var indexes = [];
	if(EsIdCasteller(id))
	{
		for(var i=0;i<boxes.length;i++)
		{
			if(EsBox(i, id, castellId))
			{
				indexes.push(i);
			}
		}
	}	
	return indexes;
}

function EsBox(index, id, castellId)
{
	return ((boxes[index].castellerid==id) && ((boxes[index].castellId==castellId) || (castellId==0)));
}

function GetIndexBoxByIdText(id, nom)
{
	var index=-1;

	var trobat=false;
	var i=0;
	while((!trobat) && (i<boxes.length))
	{
		trobat=((boxes[i].castellerid==id) && (boxes[i].text==nom));
		i++;
	}
	if (trobat)
		index=i-1;

	return index;
}

function IsCastellerAfegit(id, castellId)
{	
	return (GetIndexBox(id, castellId) != -1);
}


//PARROT: S´ha de fer al final, quan estan totes les dades carregades.
function CollapsaTot() 
{
	var items = document.getElementsByClassName("accordionItem");
	for (var i = 0; i < items.length; i++) 
	{
		var item = items[i];
		item.style.display = "none";
	}
	
	RefreshTotals();
	ResizeLateralBar();
}

function RefreshTotals()
{
	var mostratTots = document.getElementById("MostraTots").checked;
	var	items = document.getElementsByClassName("accordion");
	for (var i = 0; i < items.length; i++) 
	{
		var afegits = 0, total = 0;
		var panel = items[i].nextElementSibling;
		while (panel != null)
		{
			if (IsCastellerAfegit(panel.id, 0))
			{
				afegits++;			
				total++;
			}
			else if (IsCastellerInscrit(panel.id))
			{
				total++;
			}
			else if (mostratTots)
			{
				total++;
			}
			
			panel = panel.nextElementSibling;

			if ((panel != null) && (panel.classList.contains("accordion")))
				panel = null;
		}
		items[i].innerHTML = items[i].name +" ("+ afegits+ "/" + total + ")";
	}
}

function Buscar() 
{
    var input, text, i;
    input = document.getElementById("myInput");
    text = getCleanedString(input.value).toUpperCase();
	
	items = document.getElementsByClassName("accordionItem");
	for (i = 0; i < items.length; i++) 
	{
		var item = items[i];
		var mostrar = (text == "") || (getCleanedString(item.innerHTML).toUpperCase().indexOf(text) > -1);

		item.style.display = MostrarCastellerLlista(mostrar, item.id);
	}
}

function getCleanedString(cadena)
{

   // Lo queremos devolver limpio en minusculas
   cadena = cadena.toLowerCase();

   // Quitamos acentos y "ñ". Fijate en que va sin comillas el primer parametro
   cadena = cadena.replace(/á/gi,"a");
   cadena = cadena.replace(/à/gi,"a");
   cadena = cadena.replace(/é/gi,"e");
   cadena = cadena.replace(/è/gi,"e");
   cadena = cadena.replace(/í/gi,"i");
   cadena = cadena.replace(/ó/gi,"o");
   cadena = cadena.replace(/ò/gi,"o");
   cadena = cadena.replace(/ú/gi,"u");
   cadena = cadena.replace(/ñ/gi,"n");
   return cadena;
}

function CollapsaTitol(id)
{
	elementActualId = id;
	Collapsa(id);
	var panell = document.getElementById("panellLateral");
}

function Collapsa(id)
{	
	var input, filter, afegits, total;
    input = document.getElementById("myInput");
    text = input.value.toUpperCase();

	var obj = document.getElementById(id);
	
	/* Toggle between adding and removing the "active" class,
	to highlight the button that controls the panel */
	obj.classList.toggle("active");
	
	/* Toggle between hiding and showing the active panel */
	var panel = obj.nextElementSibling;
	while (panel != null)
	{
		var mostrar = obj.classList.contains("active") && ((text == "") || (panel.innerHTML.toUpperCase().indexOf(text) > -1));
		panel.style.display = MostrarCastellerLlista(mostrar, panel.id);

		panel = panel.nextElementSibling;

		if ((panel != null) && (panel.classList.contains("accordion")))
			panel = null;
	}
}

function CanviText(t)
{
	if (mySel[0] != null)
	{
		HideEditPopup();
		mySel[0].castellerid = NouId();
		mySel[0].text = t;		
		Save();
		invalidate();
		document.getElementById("text").value="";
		ResetCasteller();
	}
}

function NouId()
{
	var id = -99;
	
	for (var i = 0; i < boxes.length; i++) 
	{
		if (id >= boxes[i].castellerid)
		{
			id = boxes[i].castellerid-1;
		}
	}
	
	return id;
}

function Log1(str)
{
	if(str != "")
	{	
		//console.log(str);
	}
}

function Log(str)
{
	Log1(str);
}

function Mides()
{
	mostrar = !mostrar;
	invalidate();
}

function Malnom()
{
	mostrar = false;
	invalidate();
}

function MostrarCastellerLlista(filtre, id)
{
	var mostratTots = document.getElementById("MostraTots").checked;

	if (filtre && (mostratTots || (!(!pestanyaFixada && IsCastellerAfegit(id, 0)) && IsCastellerInscrit(id))))
	{
		return "";
	}
	else
	{
		return "none";
	}
}

function CasellaCalculaCarregaAssaig(i)
{
	return (!document.getElementById("Pinyes").checked || (posicionsTronc.indexOf(boxes[i].posicio)>-1));
}

function UpdataCarregaAssaig()
{
	if(pestanyaFixada)
	{
		var carrega = [];

		var lblNom = document.getElementsByClassName("lblNom");
		for (var i = 0; i < lblNom.length; i++)
		{
			lblNom[i].innerHTML=lblNom[i].title;
		}
		
		for (var i = 0; i < boxes.length; i++)
		{
			if ((boxes[i].castellerid > 0) && CasellaCalculaCarregaAssaig(i))
			{
				var j=0;
				var trobat = false;
				while(!trobat && (j<carrega.length))
				{
					trobat = (carrega[j].id==boxes[i].castellerid);
					
					if(!trobat)
						j++;
				}
				if(!trobat)
				{
					var crg = new CarregaAssaig();
					crg.id=boxes[i].castellerid;
					crg.castell.push(boxes[i].castellId);
					crg.carrega=1;
					carrega.push(crg);
				}
				else if (carrega[j].castell.indexOf(boxes[i].castellId)==-1)
				{
					carrega[j].carrega++;
					carrega[j].castell.push(boxes[i].castellId);
				}
			}
		}
		
		for (var i = 0; i < carrega.length; i++)
		{
			var item = document.getElementById("lbl"+carrega[i].id);
			if (item != null)
			{
				item.innerHTML=carrega[i].carrega + " " + item.title;
			}
		}
	}
}

function SetPinyaTronc()
{
	var a = "";
	if(pestanyaFixada)
	{
		a = "&a=1";
	}
	location.replace("PinyaTronc.php?id="+boxes[0].castellId+a+"'");
}

function Buscat()
{
	mySelWidth = 10;
	var input, text, tab=0;
    input = document.getElementById("myInput");
    text = getCleanedString(input.value).toUpperCase();
	ClearmySel();
	
	if (text != "")
	{
		for (var i=0;i<boxes.length;i++)
		{		
			if(getCleanedString(boxes[i].malnom).toUpperCase().indexOf(text) == 0)
			{
				if ((mySel.length == 1) && (mySel[0] == null))
				{
					mySel[0] = boxes[i];
				}
				else
				{
					mySel.push(boxes[i]);
				}
				
				tab = boxes[i].pestanya;
			}
		}
	}
	
	var obj = document.getElementsByName(tab);
	if(obj.length > 0)
	{
		CanviPestanya(obj[0]);	
	}

	invalidate();
}