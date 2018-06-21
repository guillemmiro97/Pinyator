// Last updated August 2010 by Simon Sarris
// www.simonsarris.com
// sarris@acm.org
//
// Free to use and distribute at will
// So long as you are nice to people, etc

//Box object to hold data for all drawn rects
function Box() 
{
	this.x = 0;
	this.y = 0;
	this.w = 1; // default width and height?
	this.h = 1;
	this.cordo=0;
	this.angle=0;
	this.dibuixid=0;
	this.id=0;
	this.forma=0;
	this.text="pepepe";
	this.castellId=-1;
	this.castellerid=0;
	this.malnom="";
	this.inscrit=false; //0=No inscrit;1=Inscrit
	this.altura=0;
	this.forca=0;
	this.pestanya=1;
	this.peu=1;
	this.lesionat=0;
	this.seguent=0;
	this.linkat=0;
}

function Casteller() 
{
  this.id = 0;
  this.malnom = "";
  this.inscrit=false; //0=No inscrit;1=Inscrit
  this.altura=0;
  this.forca=0;
  this.peu=1;
  this.lesionat=0;
}

//Initialize a new Box, add it, and invalidate the canvas
function addRect(x, y, w, h, cordo, posicio, angle, dibuixId, id, pestanya, forma, text, linkat, seguent, castellid, castellerid, malnom, inscrit, altura, forca, peu, lesionat)
{
	var rect = new Box;
	rect.x = x;
	rect.y = y;
	rect.w = w
	rect.h = h;
	rect.cordo=cordo;
	rect.angle=angle;
	rect.posicio=posicio;
	rect.dibuixid=dibuixId;
	rect.id=id;
	rect.pestanya=pestanya;
	rect.forma=forma;
	rect.text=text;
	rect.linkat=linkat;
	rect.seguent=seguent;
	rect.castellId=castellid;
	rect.castellerid=castellerid;
	if (castellerid > 0)
	{		
		rect.malnom=malnom;
		rect.inscrit=inscrit;
		rect.altura=altura;
		rect.forca=forca;
		rect.peu=peu;
		rect.lesionat=lesionat;
		PintaCastellerLlista(castellerid, rect.inscrit);
	}
	boxes.push(rect);
	invalidate();
}

function PosicioColor() 
{
  this.id = 0;
  this.colorfons = "#BDBDBD";
  this.colortext = "#000000" 
}

function AddColorPosicio(id, colorfons, colottext)
{
	var color = new PosicioColor;
	color.id = id;
	color.colorfons = colorfons;
	color.colortext = colottext;
	
	colors.push(color);	
}

function GetColorFonsById(id)
{
	var index = GetColorById(id);
	
	if (index > -1)
	{
		return colors[index].colorfons;
	}
	else
	{
		return "#BDBDBD";
	}
}

function GetColorTextById(id)
{
	var index = GetColorById(id);
	
	if (index > -1)
	{
		return colors[index].colortext;
	}
	else
	{
		return "#000000";
	}
}

function GetColorById(id)
{
	var l = colors.length;
	var trobat = false;
	var i = 0;
    while (( i < l) && (!trobat)) 
	{
		if(colors[i] != null)
        {
			trobat = (colors[i].id == id);
		}
		if (!trobat)
		{
			i++;
		}
    }
	if (!trobat)
	{
		i = -1;
	}
	return i;	
}

var colors = [];

var casteller = new Casteller();

//var Plantilla_ID = '1';

// holds all our rectangles
var boxes = []; 

var editing = new Box;

var debug=1;

var plantilla_id=0;

var canvas;
var ctx;
var WIDTH;
var HEIGHT;
var INTERVAL = 20;  // how often, in milliseconds, we check to see if a redraw is needed
var angleTrapezi = 10;

var mostrar = false;

var isDownloading = false;

var _angle = 0 //Angle general

var isPopupShown = false;

var pestanyaActual = 1;
var pestanyaFixada = false;

var isDrag = false;
var mx, my; // mouse coordinates
var movedx = 0, movedy = 0; //mouse distance moved

 // when set to true, the canvas will redraw everything
 // invalidate() just sets this to false right now
 // we want to call invalidate() whenever we make a change
var canvasValid = false;

// The node (if any) being selected.
// If in the future we want to select multiple objects, this will get turned into an array
var mySel = []; 

// The selection color and width. Right now we have a red selection with a small width
var mySelColor = '#CC0000';
var mySelWidth = 4;

// we use a fake canvas to draw individual shapes for selection testing
var ghostcanvas;
var gctx; // fake canvas context

// since we can drag from anywhere in a node
// instead of just its x/y corner, we need to save
// the offset of the mouse when we start dragging.
var offsetx, offsety;

// Padding and border style widths for mouse offsets
var stylePaddingLeft, stylePaddingTop, styleBorderLeft, styleBorderTop;

// initialize our canvas, add a ghost canvas, set draw loop
// then add everything we want to intially exist on the canvas
function initCanvas(plantillaId) 
{
  plantilla_id = plantillaId;
  canvas = document.getElementById('canvas1');
  HEIGHT = canvas.height;
  WIDTH = canvas.width;
  ctx = canvas.getContext('2d');
  ghostcanvas = document.createElement('canvas');
  ghostcanvas.height = HEIGHT;
  ghostcanvas.width = WIDTH;
  gctx = ghostcanvas.getContext('2d');
  
  //fixes a problem where double clicking causes text to get selected on the canvas
  canvas.onselectstart = function () { return false; }
  
  // fixes mouse co-ordinate problems when there's a border or padding
  // see getMouse for more detail
  if (document.defaultView && document.defaultView.getComputedStyle) 
  {
    stylePaddingLeft = parseInt(document.defaultView.getComputedStyle(canvas, null)['paddingLeft'], 10)      || 0;
    stylePaddingTop  = parseInt(document.defaultView.getComputedStyle(canvas, null)['paddingTop'], 10)       || 0;
    styleBorderLeft  = parseInt(document.defaultView.getComputedStyle(canvas, null)['borderLeftWidth'], 10)  || 0;
    styleBorderTop   = parseInt(document.defaultView.getComputedStyle(canvas, null)['borderTopWidth'], 10)   || 0;
  }
  
  // make draw() fire every INTERVAL milliseconds
  setInterval(draw, INTERVAL);
  
  // set our events. Up and down are for dragging,
  // double click is for making new boxes
  canvas.onmousedown = myDown;
  canvas.onmouseup = myUp;
  canvas.ondblclick = myDblClick;
  canvas.ontouchstart = touchDown;
  canvas.ontouchend = myUp;

  if (!EsPlantilla)
  {
	  mySel.push(null);
  }
}

//wipes the canvas context
function clear(c) 
{
  c.clearRect(0, 0, WIDTH, HEIGHT);
}

function FixaPestanya()
{
	pestanyaFixada=true;
}

function EsPenstanyaActual(pestanya)
{
	return ((pestanya == pestanyaActual) || pestanyaFixada);
}

// While draw is called as often as the INTERVAL variable demands,
// It only ever does something if the canvas gets invalidated by our code
function draw() 
{
	if (canvasValid == false) 
	{
		clear(ctx);

		ctx.rotate(_angle * (Math.PI / 180));
		
		// draw all boxes
		var l = boxes.length;
		for (var i = 0; i < l; i++) 
		{
			if(EsPenstanyaActual(boxes[i].pestanya))
			{
				if ((!isDownloading) 
				|| ((isDownloading) && ((boxes[i].text != "") || (boxes[i].castellerid > 0)))
				|| (boxes[i].forma==5)
				|| (EsPlantilla()))
				{
					drawshape(ctx, boxes[i]);
				}
			}
		}
		
		// Add stuff you want drawn on top all the time here
		canvasValid = true;
	}
}

function DrawCustomShape(context, shape, filled)
{	
	if (shape.forma==0)//Rectangle
	{		
		context.strokeRect(0,0,shape.w,shape.h);		
		if (filled)
		{
			context.fillRect(0,0,shape.w,shape.h);
		}
	}
	else if ((shape.forma==6) && EsPlantilla())//Text
	{		
		context.strokeRect(0,0,shape.w,shape.h);		
		context.fillRect(0,0,shape.w,shape.h);
	}	
	else if(shape.forma==1)//Oval
	{					
		var lineW = context.lineWidth;
		context.lineWidth = context.lineWidth*2;
		context.beginPath();
		context.scale(1,0.5);
		context.arc(shape.w/2, shape.h, shape.w/2, 0, 2 * Math.PI);
		context.closePath();
		if (filled) context.fill();				
		context.stroke();
		context.lineWidth = lineW;
		context.scale(1,2);//Tornem a l'escala original
	}
	else if (shape.forma==2)//Rectangle2
	{
		context.beginPath();
		context.moveTo(0,0);
		context.lineTo(shape.w,0);
		context.lineTo(shape.w+angleTrapezi,shape.h);
		context.lineTo(angleTrapezi,shape.h);
		context.lineTo(0,0);
		context.closePath();
		if (filled) context.fill();
		context.stroke();		
	}
	else if (shape.forma==3)//Rectangle3
	{
		context.beginPath();
		context.moveTo(0,0);
		context.lineTo(shape.w,0);
		context.lineTo(shape.w-angleTrapezi,shape.h);
		context.lineTo(-angleTrapezi,shape.h);
		context.lineTo(0,0);
		context.closePath();
		if (filled) context.fill();		
		context.stroke();
	}
	else if (shape.forma==4)//Triangle
	{
		context.beginPath();
		context.moveTo(shape.w/2,0);
		context.lineTo(shape.w,shape.h);
		context.lineTo(0,shape.h);
		context.lineTo(shape.w/2,0);
		context.closePath();
		if (filled) context.fill();		
		context.stroke();
	}	
	else if (shape.forma==5)//Fletxa
	{			
		var lineW = context.lineWidth;
		context.lineWidth = 2;
		var headlen = 5;   // length of head in pixels
		var yh = shape.h/2;
		context.beginPath();
		context.moveTo(0, yh);
		context.lineTo(shape.w, yh);
		context.lineTo(shape.w-headlen,yh+headlen);
		context.lineTo(shape.w, yh);
		context.lineTo(shape.w-headlen,yh-headlen);
		context.lineTo(shape.w, yh);
		context.closePath();
		context.stroke();
		context.lineWidth = lineW;
	}
	else if (shape.forma==7)//Trapezi1
	{
		context.beginPath();
		context.moveTo(angleTrapezi,0);
		context.lineTo(shape.w-angleTrapezi,0);
		context.lineTo(shape.w,shape.h);		
		context.lineTo(0,shape.h);
		context.lineTo(angleTrapezi,0);
		context.closePath();
		if (filled) context.fill();		
		context.stroke();
	}
	else if (shape.forma==8)//Trapezi2
	{
		context.beginPath();
		context.moveTo(0,0);
		context.lineTo(shape.w,0);
		context.lineTo(shape.w-angleTrapezi,shape.h);
		context.lineTo(angleTrapezi,shape.h);
		context.lineTo(0,0);
		context.closePath();
		if (filled) context.fill();		
		context.stroke();
	}	
	
	if((shape.peu==0) && (!isDownloading))
	{
		var img=document.getElementById("peu");
		if (img != null)		
			context.drawImage(img,0,0);
	}
	
	if((shape.lesionat==1) && (!isDownloading))
	{
		var img=document.getElementById("lesionat");
		if (img != null)
			context.drawImage(img,shape.w-20,0);
	}
}

function DrawText(context, shape)
{
	context.fillStyle = GetColorTextById(shape.posicio);
	context.textAlign = "center";
	context.textBaseline="middle"; 
	var x = (shape.w / 2);
	var y = (shape.h / 2);
	
	if (shape.forma==4)
	{
		y = shape.h - (shape.h / 3);
	}
	
	var str = "";
	if ((shape.forma==6) || (!EsPlantilla() && (shape.text != "") && (shape.castellerid < 0)))
	{
		str = shape.text;
		if((str.indexOf("SUM(")>-1) && (str.indexOf(")")>4)  && (str.indexOf(";")>4))
		{
			str = str.replace("SUM(","");
			str = str.replace(")","");
			var suma=0;
			var ar = str.split(";");
			for (var i = 0; i < ar.length; i++)
			{
				var ind = GetIndexById(parseInt(ar[i]), shape.castellId);
				if (ind > -1)
				{
					suma=suma+ boxes[ind].altura;
				}
			}
			str = suma;
		}
	}
	else 
	{	
		if (EsPlantilla())
		{
			//No se que passa pro faig un trim
			str = ""+shape.id;
			str = "Id:"+str.trim()+"-Crd:"+shape.cordo;
		}		
		else if (shape.malnom != "")
		{
			var mides = "";
			if ((mostrar) && (!isDownloading))
			{
				mides = shape.altura;// + "  (" + shape.forca + ")";
			}
			str = shape.malnom.toUpperCase() + " " + mides;
			
			if(!shape.inscrit)
			{
				context.fillStyle = "red";
			}
		}
	}
	DrawDownText(context, shape, str, x, y);
	
	if((shape.forma != 5) && (EsPlantilla())
	&& (IsInmySelById(shape.id, shape.castellId)))
	{
		context.fillStyle = "red";
		str = GetSelPosById(shape.id, shape.castellId)
		DrawDownText2(context, shape, str, 8, 6, 12);
	}
}

function DrawDownText(context, shape, str, x, y)
{
	var fontSize = 14;
	if (shape.forma==6)
	{
		fontSize=shape.h;
	}
	DrawDownText2(context, shape, str, x, y, fontSize);
}

function DrawDownText2(context, shape, str, x, y, fontSize)
{	
	context.font = "bold "+fontSize+"px Arial";
	
	var shapeW=shape.w - angleTrapezi;
	
	if (context.measureText(str).width > shapeW)
	{
		
		while((fontSize > 7) && (context.measureText(str).width > shapeW))
		{
			context.font = "bold "+fontSize+"px Arial";
			
			fontSize=fontSize-1;
		}
		
		/*
		 * @param ctx   : The 2d context 
		 * @param mw    : The max width of the text accepted
		 * @param text  : The text to be splitted   into 
		 */
		var split_lines = function(ctx, mw, text) 
		{
			// We give a little "padding"
			// This should probably be an input param
			// but for the sake of simplicity we will keep it
			// this way
			mw = mw - 10;
			// We split the text by words 
			var words = text.split(' ');
			var new_line = words[0];
			var lines = [];			
			for(var i = 1; i < words.length; ++i) 
			{			   
			   if (ctx.measureText(new_line + " " + words[i]).width < mw) 
			   {
				   new_line += " " + words[i];
			   } 
			   else 
			   {	
				   lines.push(new_line);		   
				   new_line = words[i];				   
			   }
			}
			lines.push(new_line);
			return lines;
		}

		var lines = split_lines(context, shape.w, str);
		var ly=shape.h/(lines.length+1);
		if (shape.forma==4)
		{
			ly=(shape.h/4)+(shape.h /(lines.length+1));
		}
		
		for (var j = 0; j < (lines.length); ++j) 
		{
            // We continue to centralize the lines
            y = (ly * (j+1));
			context.fillText(lines[j], x, y);
        }
	}
	else
	{
		context.fillText(str,x,y);
	}	
}

// Draws a single shape to a single context
// draw() will call this with the normal canvas
// myDown will call this with the ghost canvas
function drawshape(context, shape) 
{
	if (EsPlantilla())
	{
		if(shape.forma != 6)
		{
			context.fillStyle = GetColorFonsById(shape.posicio);
		}
		else
		{
			context.fillStyle = '#FFEE00';
		}
	}
	else
	{
		context.fillStyle = GetColorFonsById(shape.posicio);
	}

	// We can skip the drawing of elements that have moved off the screen:
	if (shape.x > WIDTH || shape.y > HEIGHT) return; 
	if (shape.x + shape.w < 0 || shape.y + shape.h < 0) return;

	context.save();
	context.translate(shape.x,shape.y);

	context.rotate(shape.angle * (Math.PI / 180));
	
	if(IsInmySelById(shape.id, shape.castellId))
	{
		context.strokeStyle = mySelColor;
		if(shape.forma != 5)
		{
			context.lineWidth = mySelWidth;
		}
	}
	else
	{	
		context.strokeStyle = 'black';
		context.lineWidth = 1;
	}

	DrawCustomShape(context, shape, true);
	if(shape.forma != 5)
	{
		DrawText(context, shape);
	}
	
	context.restore();
}

function IsInmySel(box)
{
	return IsInmySelById(box.id, box.castellId);
}

function IsInmySelById(id, castellId)
{
	return GetSelPosById(id, castellId) != -1;
}

function GetSelPosById(id, castellId)
{
	var l = mySel.length;
	var trobat = false;
	var i = 0;
    while (( i < l) && (!trobat)) 
	{
		if(mySel[i] != null)
        {
			trobat = ((mySel[i].id == id) && (mySel[i].castellId == castellId));
		}
		if (!trobat)
		{
			i++;
		}
    }
	if (!trobat)
	{
		i = -1;
	}
	return i;
}

//Save to database
function Save() 
{
	if (mySel != null)
	{
		mySel.forEach(SaveItem);
	}
}

function GiraCastellDreta()
{	
	GiraCastell(90);
	if (_angle == 360) _angle = 0;
}

function GiraCastellEsquerra()
{
	if (_angle == 0) _angle = 360;
	GiraCastell(-90);
}

function GiraCastell(angle)
{
	_angle = _angle + angle;
	invalidate();
}

function GetIndex(item)
{
	var trobat=false;
	var i=0;
	while((!trobat) && (i<boxes.length))
	{
		trobat = (item.id==boxes[i].id);
		if(!trobat)
			i=i+1;
	}
	if(trobat)
		return i;
	else
		return -1;	
}

function GetIndexById(id, castellid)
{
	var trobat=false;
	var i=0;
	while((!trobat) && (i<boxes.length))
	{
		trobat = ((id==boxes[i].id) && (castellid==boxes[i].castellId));
		if(!trobat)
			i=i+1;
	}
	if(trobat)
		return i;
	else
		return -1;	
}

function MoureCaselles(dX, dY)
{
	var l = mySel.length;
	for (var i = 0; i < l; i++) 
	{
		mySel[i].x += dX;
		mySel[i].y += dY;
    }
}

// Happens when the mouse is moving inside the canvas
function myMove(e)
{
  if (isDrag)
  {
    getMouse(e);
    
	MoureCaselles(movedx, movedy);
    
    // something is changing position so we better invalidate the canvas!
    invalidate();
  }
}

// Happens when the mouse is moving inside the canvas
function touchMove(e)
{
  if (isDrag)
  {
    getTouch(e);
    
	MoureCaselles(movedx, movedy);
    
    // something is changing position so we better invalidate the canvas!
    invalidate();
  }
}

function myUp()
{
	isDrag = false;
	canvas.onmousemove = null;
	canvas.ontouchmove = null;
	movedx = 0;
	movedy = 0;

	if(mySel != null) 
	{
		SaveUp(); 
	}  
}

function Editing(element)
{
	if (mySel != null)
	{
		mySel.forEach(EditingItem, element);
		Save();
		invalidate();
	}
}

function EditingItem(item)
{
	if ((item != null) && (this != null))
	{	
		var value = document.getElementById(this.id).value;
		if (this.id == "posiciocordo")
			item.cordo = value;
		else if (this.id == "posicioangle")
			item.angle = value;
		else if (this.id == "posicioC")
			item.posicio = value;
		else if (this.id == "posicioH")
			item.h = value;
		else if (this.id == "posicioW")
			item.w = value;
		else if (this.id == "forma")
			item.forma = value;
		else if (this.id == "text")
			item.text = value;
		else if (this.id == "linkat")
			item.linkat = value;
		else if (this.id == "seguent")
			item.seguent = value;
	}
}

function EditingText()
{
	HideEditPopup();
	Editing(document.getElementById("text"));
}

function ShowPopup()
{
	isPopupShown = !isPopupShown;
	var popup = document.getElementById("myPopup");
	popup.classList.toggle("show");
}

function ShowEditPopup()
{
	if (!isPopupShown)
	{
		ShowPopup();		
	
		var edit = document.getElementById("text");
		edit.focus();
	}
}

function HideEditPopup()
{
	if (isPopupShown)
	{
		ShowPopup();		
	}	
}

function EsPlantilla()
{
	return !(plantilla_id == -1);
}

function invalidate() 
{
  canvasValid = false;
}

// Sets mx,my to the mouse position relative to the canvas
// unfortunately this can be tricky, we have to worry about padding and borders
function getMouse(e) 
{
	var element = canvas, offsetX = 0, offsetY = 0;

	if (element.offsetParent) 
	{
		do {
			offsetX += element.offsetLeft;
			offsetY += element.offsetTop;
		} while ((element = element.offsetParent));
	}

	// Add padding and border style widths to offset
	offsetX += stylePaddingLeft;
	offsetY += stylePaddingTop;

	offsetX += styleBorderLeft;
	offsetY += styleBorderTop;

	if (mx != 0)
	{
		movedx = (e.pageX- offsetX) - mx;
	}
	
	if (my != 0)
	{
		movedy = (e.pageY- offsetY) - my;
	}

	mx = e.pageX- offsetX;
	my = e.pageY - offsetY;
}

function getTouch(e) 
{

	var element = canvas, offsetX = 0, offsetY = 0;

	if (element.offsetParent) 
	{
		do {
			offsetX += element.offsetLeft;
			offsetY += element.offsetTop;
		} while ((element = element.offsetParent));
	}

	// Add padding and border style widths to offset
	offsetX += stylePaddingLeft;
	offsetY += stylePaddingTop;

	offsetX += styleBorderLeft;
	offsetY += styleBorderTop;
	Log("2");
	if (mx != 0)
	{
		movedx = (e.touches[0].screenX- offsetX) - mx;
	}
	
	if (my != 0)
	{
		movedy = (e.touches[0].screenY- offsetY) - my;
	}

	mx = e.touches[0].screenX- offsetX;
	my = e.touches[0].screenY - offsetY;
	Log(mx);
}

function download()
{
	isDownloading = true;
	invalidate();
	draw();
	var canvas = document.getElementById("canvas1");
	var w=window.open('about:blank','image from canvas');
	w.document.write("<img src='"+canvas.toDataURL("image/png")+"' alt='from canvas'/>");
	isDownloading = false;
	invalidate();
}

function CanviPestanya(obj)
{
	var list = document.getElementsByClassName("tabuladorActiu");
	
	for (var i = 0; i < list.length; i++)
	{
		list[i].classList.remove("tabuladorActiu");
	}
	
	obj.classList.add("tabuladorActiu");
	pestanyaActual = obj.name;
	invalidate();
}
