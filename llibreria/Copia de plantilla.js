// $('html').keypress(function(e)
// {
	// if(mySel != null)
    // {
		// if(e.keyCode == 46)
		// {
			// Esborra();
		// }
		// else if ((e.keyCode >= 37) && (e.keyCode <= 40))
		// {
			// if(e.keyCode == 37)//esquerra
			// {
				// MoureCaselles(-1, 0);
			// }
			// else if(e.keyCode == 38)//amunt
			// {
				// MoureCaselles(0, -1);
			// }
			// else if(e.keyCode == 39)//dreta
			// {
				// MoureCaselles(1, 0);
			// }
			// else if(e.keyCode == 40)//avall
			// {
				// MoureCaselles(0, 1);		
			// }			
			// Save();
			// invalidate();
			// return false;
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
	else if ((code >= 37) && (code <= 40))
	{
		switch(code) 
		{
          case 37://esquerra
              MoureCaselles(-1, 0);
              break;
          case 38://amunt
              MoureCaselles(0, -1);
              break;
          case 39://dreta
              MoureCaselles(1, 0);
              break;
          case 40://avall
              MoureCaselles(0, 1);
              break;
		}
		Save();
		invalidate();
		event.preventDefault();	
	}     
};

document.ontouchstart = function (e) {
  if (e.target == canvas) 
  {
	touchDown(e);
    e.preventDefault();	
  }
};
document.ontouchend = function (e) {
  if (e.target == canvas) 
  {
	myUp(e);
    e.preventDefault();	
  };
} 
document.ontouchmove = function (e) {
  if (e.target == canvas) 
  {
	touchMove(e);
    e.preventDefault();	
  };
}

var shiftKey = true;
var currentSelected = null;


// Happens when the mouse is clicked in the canvas
function myDown(e)
{
	getMouse(e);
	OnDown();
}

function touchDown(e)
{
	getTouch(e);
	OnDown();
}

function OnDown()
{
	ReadOnlyTot();
	HideEditPopup();
	
	currentSelected = null;
	
	if (!shiftKey) 
	{
		mySel = [];
	}
	
	clear(gctx);
	var l = boxes.length;
	for (var i = l-1; i >= 0; i--) 
	{
		if (boxes[i].pestanya == pestanyaActual)
		{
			// draw shape onto ghost context
			drawshape(gctx, boxes[i]);
			
			// get image data at the mouse x,y pixel
			var imageData = gctx.getImageData(mx, my, 1, 1);
			//var index = (mx + my * imageData.width) * 4;

			// if the mouse pixel exists, select and break
			if (imageData.data[3] > 0)
			{
				var selected = boxes[i];
				//Si el primer seleccionat es un text no fem cap operacio.
				if ((mySel.length == 0) || (mySel[0].forma != 6) || (selected.id == mySel[0].id))
				{
					if (mySel.indexOf(selected) >= 0)
					{
						mySel.splice(mySel.indexOf(selected),1);
						isDrag = true;
						canvas.onmousemove = myMove;
						canvas.ontouchmove = touchMove;
						invalidate();
						clear(gctx);
					}
					else
					{
						Selecciona(selected);
						currentSelected = selected;
						offsetx = mx - selected.x;
						offsety = my - selected.y;
						selected.x = mx - offsetx;
						selected.y = my - offsety;
						document.getElementById("posicioangle").value = selected.angle;
						document.getElementById("posiciocordo").value = selected.cordo;
						document.getElementById("posicioC").value = selected.posicio;
						document.getElementById("posicioH").value = selected.h;
						document.getElementById("posicioW").value = selected.w;
						document.getElementById("forma").value = selected.forma;
						document.getElementById("text").value = selected.text;
						document.getElementById("linkat").value = selected.linkat;
						document.getElementById("seguent").value = selected.seguent;
						isDrag = true;
						canvas.onmousemove = myMove;
						canvas.ontouchmove = touchMove;
						invalidate();
						clear(gctx);
						textInfo(selected);
						
						ReadOnly("posicioH", EsOval(selected.forma));
						ReadOnly("posicioW", false);
						ReadOnly("posicioangle", false);
						ReadOnly("forma", false);
						if (!EsText(selected.forma))
						{
							ReadOnly("posicioC", false);
							ReadOnly("posiciocordo", false);
						}
						ReadOnly("linkat", false);
						ReadOnly("seguent", false);
					}
					return;
				}
			}
			clear(gctx);
		}
	}
	// havent returned means we have selected nothing
	mySel = [];
	// clear the ghost canvas for next time
	clear(gctx);	
	// invalidate because we might need the selection border to disappear
	invalidate();
}

function ExistsItem(item) 
{
    return item.id == this.id;
}

function Selecciona(item)
{
	if(!mySel.some(ExistsItem, item))
	{
		mySel.push(item);
	}
}

//Save to database
function SaveItem(item) 
{
	if (item != null)
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				item.id=this.responseText;
				if (this.responseText > 0)
				{
					item.id=this.responseText;
				}
				else
				{
					document.getElementById("txtHint").innerHTML = this.responseText;
				}
				invalidate();
			}
		};
		xmlhttp.open("GET", "Plantilla_Posicio_Desa.php?" + getPosicio(item), true);
		xmlhttp.send();
		textInfo(item);
	}
}

function getPosicio(item)
{
	return "cs="+item.id+"&p="+plantilla_id+"&ps="+item.pestanya+"&po="+item.posicio+"&c="+item.cordo+"&x="+item.x+"&y="+item.y+
	"&h="+item.h+"&w="+item.w+
	"&a="+item.angle+"&f="+item.forma+"&t="+item.text+
	"&sg="+item.seguent+"&lk="+item.linkat;
}

function GetValorMoure()
{
	var i=0;
	i=document.getElementById("mourevalor").value;
	return parseInt(i);
}

function SeleccionaTot()
{
	mySel = [];
	var l = boxes.length;
    for (var i = 0; i < l; i++) 
	{
        Selecciona(boxes[i]);
    }
}

function MoureCastellAvall()
{
	MoureCastellVertical(GetValorMoure());
}

function MoureCastellAmunt()
{
	MoureCastellVertical(GetValorMoure() * -1);
}

function MoureCastellVertical(distancia)
{
	SeleccionaTot();
	MoureCaselles(0, distancia);
	Save();
	mySel=[];
	invalidate();
}

function MoureCastellDreta()
{
	MoureCastellHoritzontal(GetValorMoure());
}

function MoureCastellEsquerra()
{
	MoureCastellHoritzontal(GetValorMoure() * -1);
}

function MoureCastellHoritzontal(distancia)
{
	SeleccionaTot();
	MoureCaselles(distancia, 0);
	Save();
	mySel=[];
	invalidate();
}


function Esborra()
{
  if(mySel != null)
  {
	mySel.forEach(RemoveItem);
	
	mySel=[];
	invalidate();
  }	
}

function SaveUp()
{
	Save();
}

function RemoveItem(item)
{
	var index = GetIndex(item);
	if (index > -1)
	{		
		Remove(item.id);
		boxes.splice(index,1);
	}
}

//Remove to database
function Remove(id) 
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open("GET", "Plantilla_Posicio_Esborra.php?cs="+id+"&id="+plantilla_id, true);
	xmlhttp.send();
	textInfo();
}

// adds a new node
function myDblClick(e) 
{
	getMouse(e);
	
	if ((currentSelected != null) && (currentSelected.forma == 6)) //Text
	{
		//Mostrem popup per introduir text
		ShowEditPopup();		
	}
	else
	{
		// for this method width and height determine the starting X and Y, too.
		// so I left them as vars in case someone wanted to make them args for something and copy this code
		var width = document.getElementById("posicioW").value;
		var height = document.getElementById("posicioH").value;
		var angle = document.getElementById("posicioangle").value;
		var forma = document.getElementById("forma").value;
		var cordo = document.getElementById("posiciocordo").value;
		var posicio = document.getElementById("posicioC").value;
		addRect(mx - (width / 2), my - (height / 2), width, height, cordo, posicio, angle, plantilla_id, 0, pestanyaActual, forma, "", 0, 0);
		Save();
	}
}

function ReadOnlyTot()
{
	ReadOnly("posicioH", true);
	ReadOnly("posicioW", true);
	ReadOnly("posicioC", true);
	ReadOnly("posiciocordo", true);
	ReadOnly("posicioangle", true);
	ReadOnly("forma", true);
	ReadOnly("seguent", true);
	ReadOnly("linkat", true);
}

function ReadOnly(control, valor)
{
	var control = document.getElementById(control);
	control.readOnly=valor;
	if (valor)
	{
		control.style.backgroundColor="LightGray";
	}
	else
	{
		control.style.backgroundColor="White";
	}	
}

function EsOval(forma)
{
	return forma == 1;
}

function EsText(forma)
{
	return forma == 6;
}

function MultiSelect()
{
	shiftKey = !shiftKey;
}

function SetSeguents()
{
	var l = mySel.length;
    for (var i = 1; i < l; i++) 
	{
        mySel[i-1].seguent = parseInt(mySel[i].id);
    }
	Save();
}

function Alinia()
{
	var l = mySel.length;
	if(l > 1)
	{
		var xIni = mySel[0].x;
		var yIni = mySel[0].y;
		var xFi = mySel[l-1].x;
		var yFi = mySel[l-1].y;
		var xDif = xFi - xIni;
		var yDif = yFi -yIni;
		
		for (var i = 1; i < l; i++) 
		{
			mySel[i].x = mySel[0].x + (i * parseInt(xDif/(l-1)));
			mySel[i].y = mySel[0].y + (i * parseInt(yDif/(l-1)));
		}
		
		Save();
	}
}

function textInfo(box)
{
	if (box != null)
		document.getElementById("txtInfo").innerHTML = box.id+" ("+box.x+":"+box.y+")/"+boxes.length;
	else
		document.getElementById("txtInfo").innerHTML = "0 (0:0)/"+boxes.length;
}


function Log(str)
{
	if((debug == 1) && (str != ""))
	{	
		document.getElementById("txtHint").innerHTML=document.getElementById("txtHint").innerHTML+str+"<br>";
	}
}