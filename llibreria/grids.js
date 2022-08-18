

function filterTable(input, tableName) 
{
	var input, filter, table, tr, td, str, i, y;
	table = document.getElementById(tableName);
	filter = input.value.toUpperCase();
	
	tr = table.getElementsByTagName("tr");
	for (i = 1; i < tr.length; i++) 
	{
		str = "";
		td = tr[i].getElementsByTagName("td");
		if (td)
		{
			for (y = 0; y < td.length; y++) 
			{
				str = str + " " + getCleanedString(td[y].innerHTML).toUpperCase();
			}
		}
		if (str.search(filter) > -1) 
		{
			tr[i].style.display = "";
		} 
		else 
		{
			tr[i].style.display = "none";
		}
	}
}

function sortTable(column, tableName) 
{
	var table, rows, switching, i, x, y, shouldSwitch;

	descendent = false;
	
	table = document.getElementById(tableName);
	rows = table.getElementsByTagName("TR");

	if(rows.length > 0)
	{
		var thi = rows[0].getElementsByTagName("TH")[column].getElementsByTagName("i")[0];
		if (thi)
		{
			descendent = thi.classList.contains("triangle-up");	
		}
		var th = rows[0].getElementsByTagName("TH");
		for (i = 0; i < (th.length - 1); i++) 
		{
			var iObj = th[i].getElementsByTagName("i")[0];
			if (iObj)
			{
				iObj.classList.remove("triangle-up");
				iObj.classList.remove("triangle-down");
			}			
		}
		
		if(descendent)
		{
			thi.classList.add("triangle-down");
		}
		else
		{
			thi.classList.add("triangle-up");
		}
	}

	switching = true;
	/*Make a loop that will continue until
	no switching has been done:*/
	while (switching)
	{
		//start by saying: no switching is done:
		switching = false;

		/*Loop through all table rows (except the
		first, which contains table headers):*/
		for (i = 1; i < (rows.length - 1); i++) 
		{
			//start by saying there should be no switching:
			shouldSwitch = false;
			/*Get the two elements you want to compare,
			one from current row and one from the next:*/
			x = rows[i].getElementsByTagName("TD")[column];
			y = rows[i + 1].getElementsByTagName("TD")[column];

			if (x.getElementsByTagName("a").length > 0)
			{
				x = x.getElementsByTagName("a")[0];
			}
			if (y.getElementsByTagName("a").length > 0)
			{
				y = y.getElementsByTagName("a")[0];
			}

			//check if the two rows should switch place:
			if (((!descendent) && (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()))
			|| ((descendent) && (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase())))
			{
			//if so, mark as a switch and break the loop:
				shouldSwitch= true;
				break;
			}
		}
		
		if (shouldSwitch) 
		{
			/*If a switch has been marked, make the switch
			and mark that a switch has been done:*/
			rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
			switching = true;
		}
	}
}

function setTotal(idObject, value) 
{
	var object;
	object = document.getElementById(idObject);
	
	if (object)
	{
		object.innerHTML = "Total: " + value
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