
function Point() 
{
	this.x = 0;
	this.y = 0;
	this.text = "";
	this.html = "";
}

//Initialize a new Box, add it, and invalidate the canvas
function addPoint(x, y, text, html)
{
	var point = new Point;
	point.x = x;
	point.y = y;
	point.html = html;

	point.text=text;

	points.push(point);
	invalidate();
}

var points = []; 


var debug=0;

var canvas;
var ctx;
var WIDTH;
var HEIGHT;
var INTERVAL = 20;  // how often, in milliseconds, we check to see if a redraw is needed

var MARGEINF = 60;
var MARGESUP = 30;
var MARGEESQUERRA = 60;
var MARGEDRET = 30;

var mx, my; // mouse coordinatesar movedx = 0, movedy = 0; //mouse distance moved

 // when set to true, the canvas will redraw everything
 // invalidate() just sets this to false right now
 // we want to call invalidate() whenever we make a change
var canvasValid = false;

// The node (if any) being selected.
// If in the future we want to select multiple objects, this will get turned into an array
var mySel = null; 

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
function initCanvas(canvasName) 
{
  canvas = document.getElementById(canvasName);
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
  
}

//wipes the canvas context
function clear(c) 
{
  c.clearRect(0, 0, WIDTH, HEIGHT);
}

// While draw is called as often as the INTERVAL variable demands,
// It only ever does something if the canvas gets invalidated by our code
function draw() 
{
	if (canvasValid == false) 
	{
		clear(ctx);
		
		DrawEixos(ctx);		
		
		prevPoint = null;
		var EsImpar = true;
		// draw all points
		var l = points.length;
		for (var i = 0; i < l; i++) 
		{
			DrawPoint(ctx, prevPoint, points[i], EsImpar);
			prevPoint = points[i];
			EsImpar = !EsImpar;
		}
		
		// Add stuff you want drawn on top all the time here
		canvasValid = true;
	}
}

function DrawText(context, point, EsImpar)
{
	context.fillStyle = "black";
	context.textAlign = "center";
	context.textBaseline="middle"; 
	var x = point.x * GetX();
	var y = HEIGHT - MARGEINF + 10;
	
	if (!EsImpar)
	{
		y = y + 15;
	}
	
	var str = point.text;
		
	context.font = "bold 12px Arial";
	
	var shapeW=100;
	
	if (context.measureText(str).width == shapeW)
	{
		var fontSize=12;
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

		var lines = split_lines(context, MARGEINF, str);
		var ly=MARGEINF/(lines.length+1);
		
		for (var j = 0; j < (lines.length); ++j) 
		{
            // We continue to centralize the lines
            yy = (ly * (j+1));
			context.fillText(lines[j], x, y+yy);
        }
	}
	else
	{
		context.fillText(str,x,y);
	}
	context.stroke();
}

function DrawEixos(context)
{
	context.save();
	//Y
	context.beginPath();
	context.moveTo(MARGEESQUERRA, MARGESUP);
	context.lineTo(MARGEESQUERRA, HEIGHT - MARGEINF);
	context.closePath();
	context.stroke();
	//X
	context.beginPath();
	context.moveTo(MARGEESQUERRA, HEIGHT - MARGEINF);
	context.lineTo(WIDTH - MARGEDRET, HEIGHT - MARGEINF);
	context.closePath();
	context.stroke();		

}

function DrawPoint(context, prevPoint, point, EsImpar) 
{
	if (prevPoint != null)
	{
		context.save();

		var x = GetX();
		var y = 1;
		
		while ((GetMaxY() * y) > (HEIGHT - MARGEINF - MARGESUP))
		{
			y = y/2;
		}
		
		DrawLinaEix(context, prevPoint, x, !EsImpar);
		DrawLinaEix(context, point, x, EsImpar);
		
		
		context.fillStyle = "red";
		context.strokeStyle = "red";
		
		//Draw point
		DrawPunt(context, prevPoint, x, y);
		DrawPunt(context, point, x, y);
		
		//Draw lines
		context.beginPath();
		context.moveTo((prevPoint.x * x), HEIGHT - MARGEINF - (prevPoint.y * y));		
		context.lineTo((point.x * x), HEIGHT - MARGEINF - (point.y * y));
		context.closePath();
		context.stroke();	

		context.restore();		
	}
}

function DrawPunt(context, point, x, y)
{
	context.beginPath();
	context.arc((point.x * x), HEIGHT - MARGEINF - (point.y * y),4,0,2*Math.PI);
	context.fill();
	
	context.fillText(point.y, (point.x * x), HEIGHT - MARGEINF - (point.y * y)-15);
	
	context.stroke();
}

function DrawLinaEix(context, point, x, EsImpar)
{
	context.beginPath();
	context.moveTo((point.x * x), HEIGHT - MARGEINF);		
	context.lineTo((point.x * x), HEIGHT - MARGEINF + 5);
	context.closePath();	
	context.stroke();
	
	DrawText(context, point, EsImpar);	
}

function GetX()
{
	return (WIDTH-MARGEESQUERRA)/(points.length+1);
}

function GetMaxY()
{
	var y = 0;
	var l = points.length;
	for (var i = 0; i < l; i++) 
	{
		if (y < points[i].y)
			y = points[i].y;
	}
	return y;
}

// Happens when the mouse is moving inside the canvas
function myMove(e)
{
  if ( 1==0 )
  {
    getMouse(e);
    
    // something is changing position so we better invalidate the canvas!
    invalidate();
  }
}

function myUp()
{
	canvas.onmousemove = null;
	movedx = 0;
	movedy = 0;
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