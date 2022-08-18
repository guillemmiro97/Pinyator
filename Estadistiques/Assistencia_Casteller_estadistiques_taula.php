<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="../Llibreria/table2CSV.js"></script>
<style>
table, td
{
    border: 1px solid #4069B2;
    text-align: left;
    padding: 1px;
}
tr:nth-child(even) 
{
    /* background-color: #CED8F6; */
	background-color: #eaeae1;
}
th
{
    border: 1px solid #4069B2;
    text-align: left;
    padding: 6px;
	background-color: #4069B2;
	color: #FFF;
}

.castellerInactiu
{
	color:red;
}

.castellerInactiuHidden
{
	display:none;
}
</style>
</head>
<link rel="stylesheet" href="../Style_Custom.css">
<body>
<button class="boto" onClick="filterTable(true)">Tots</button>
&nbsp&nbsp<button class="boto" onClick="filterTable(false)">Actius</button>
&nbsp&nbsp<a class="boto" onClick="ExportCSV(false)">Exporta CSV</a>

<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";?>

<?php
$temporada=strval($_GET["t"]);

class Casteller 
{ 
	public $Dies=array();
	public $DiesActius=array();
	public $Nom = ""; 
	public $Posicio = "";
	public $Total = 0;
	public $Estat = 1;

	public function __construct()
	{
		$this->Dies = array();
		$this->DiesActius = array();
		$this->Nom = "";
		$this->Posicio = "";
		$this->Total = 0;
		$this->Estat = 1;
	}		
}

$dies=array();
$diesPlant=array();
$diesNom=array();

//Primer carreguem tots els possibles castellers
$sql="SELECT E.EVENT_ID, date_format(E.DATA, '%d-%m') AS DATA, E.NOM
FROM EVENT E
WHERE (E.EVENT_PARE_ID IS NULL OR E.EVENT_PARE_ID=0)
AND E.TIPUS = 0
AND (E.TEMPORADA = '".$temporada."' OR '0' = '".$temporada."') 
ORDER BY E.DATA";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) 
{
	while($row = mysqli_fetch_assoc($result)) 
	{
		$dies[$row["EVENT_ID"]] = $row["DATA"];
		$diesPlant[$row["EVENT_ID"]] = 0;
		$diesNom[$row["EVENT_ID"]] = $row["NOM"];
	}
}	
else if (mysqli_error($conn) != "")
{
	echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

$castellers=array();

$objTotal = new Casteller();
$objTotal->Dies = new ArrayObject($diesPlant);
$objTotal->DiesActius = new ArrayObject($diesPlant);
$objTotal->Nom = "TOTAL";			

array_push($castellers, $objTotal);	

$casteller = "";

$sql="SELECT E.EVENT_ID,
CT.MALNOM AS CASTELLER,
CASE WHEN (P.ESNUCLI=1 OR P.ESTRONC=1 OR P.ESCORDO=1) THEN 1 ELSE 0 END AS ESTAT 
FROM EVENT E
JOIN INSCRITS AS I ON I.EVENT_ID = E.EVENT_ID
JOIN CASTELLER CT ON CT.CASTELLER_ID = I.CASTELLER_ID
LEFT JOIN POSICIO AS P ON P.POSICIO_ID=CT.POSICIO_PINYA_ID
WHERE (E.EVENT_PARE_ID IS NULL OR E.EVENT_PARE_ID=0)
AND E.TIPUS = 0
AND I.ESTAT > 0
AND (E.TEMPORADA = '".$temporada."' OR '0' = '".$temporada."') 
ORDER BY CT.MALNOM, E.EVENT_ID";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) 
{
	while($row = mysqli_fetch_assoc($result)) 
	{
		if ($casteller != $row["CASTELLER"])
		{
			$casteller = $row["CASTELLER"];

			$objCasteller = new Casteller();
			$objCasteller->Dies = new ArrayObject($diesPlant);
			$objCasteller->Nom = $casteller;
			$objCasteller->Estat = $row["ESTAT"];			
			
			array_push($castellers, $objCasteller);				
		}
		$objCasteller->Dies[$row["EVENT_ID"]] = 1;
		$objCasteller->Total = $objCasteller->Total + 1;
		if($objCasteller->Estat==1)
		{
			$objTotal->DiesActius[$row["EVENT_ID"]] = $objTotal->DiesActius[$row["EVENT_ID"]] + 1;
		}
		$objTotal->Dies[$row["EVENT_ID"]] = $objTotal->Dies[$row["EVENT_ID"]] + 1;
	}	
}
else if (mysqli_error($conn) != "")
{
	echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
else
{
	echo "<br>Sense dades";
}

//Printem la taula
$head = "<tr>
		<th>CASTELLERS</th>
		<th>TOTAL</th>";

foreach($dies as $event=>$dia) 
{
	$nom=$diesNom[$event];
	$head = $head." <th title='".$nom."'>".$dia."</th>";
}

$head = $head." </tr>";

echo "<table id='graella' name='graella'>";
//echo "<table class='thead-fixed' name='graella'>";
//echo "<thead class='sticky'>";
//echo $head;
//echo "</thead>";

echo "<tbody>";

echo $head;

foreach($castellers as $cas) 
{
	$visible="";
	if($cas->Estat==2)
	{
		$visible="class='castellerInactiu castellerInactiuHidden'";
	}
	
	echo "<tr ".$visible.">
			<td><b>".$cas->Nom."</b></td>
			<td><b>".$cas->Total."</b></td>";

	$esTotal = ($cas->Nom == "TOTAL");
		
	foreach($cas->Dies as $event=>$dia)
	{	
		$style="";
		if($dia > 0)
		{
			$style = "style='background-color:green;color:green'";
		}
		else
		{
			$style = "style='background-color:red;color:red'";
		}
		
		if($esTotal)
		{
			$diaActiu=$cas->DiesActius[$event];
			if($dia != $diaActiu)
			{
				echo "<td title='".$diaActiu." actius actuals de ".$dia." en total'>(".$diaActiu.")".$dia."</td>";
			}
			else
			{
				echo "<td>".$dia."</td>";
			}
		}
		else
		{
			echo "<td ".$style.">".$dia."</td>";
		}
	}
	
	echo "</tr>";
}
echo "</tbody></table>";

?>
</body>
<script>

function ExportCSV() 
{
	$('#graella').table2CSV();
}

function filterTable(Tots) 
{
	var tr = document.getElementsByClassName("castellerInactiu");
	for (var i = 0; i < tr.length; i++) 
	{
		if (Tots) 
		{
			tr[i].classList.remove("castellerInactiuHidden");
		} 
		else 
		{
			tr[i].classList.add("castellerInactiuHidden");
		}
	}
}

</script>
</html>