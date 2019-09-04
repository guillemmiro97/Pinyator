<html lang="es-ES">
<head>
  <title>Pinyator - Planificació</title>
<meta charset="utf-8">
<?php $menu=9; include "$_SERVER[DOCUMENT_ROOT]/pinyator/Head.php";?>
<script src="llibreria/popup_esborra.js"></script>
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body class="popup">
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Menu.php";

if (!empty($_GET["d"]))
{	
	$dataInici=strval($_GET["d"]);
}
else
{
	$dataInici=date("Y-m-d", strtotime ( '-1 week'));
}

?>


&nbsp;<button class="boto" onClick="Filtrar()">Des de</button>
<input type="date" class="form_edit" style="width:140px" id="data" value="<?php echo $dataInici ?>">

&nbsp;&nbsp;&nbsp;&nbsp;<button class="boto" onClick="VesAvui()">VesAvui</button>

<br>
<div style="position: absolute; width: 100%; height :100%;">
 <table class="llistes2">
 <?php


$estat=1;
if (!empty($_GET["e"]))
{	
	$estat=intval($_GET["e"]);
}



include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

$sql="SELECT E.EVENT_ID, E.NOM, date_format(E.DATA, '%d-%m-%Y %H:%i') AS DATA, 
E.ESTAT,E.TIPUS,
COUNT(I.ESTAT) AS CONTESTES_QTA,
SUM(IF(I.ESTAT > 0, 1, 0)) + SUM(IFNULL(I.ACOMPANYANTS,0)) AS INSCRITS_QTA,
C.NOM AS CASTELL,
	(SELECT COUNT(*) 
			FROM CASTELL_POSICIO AS CPR 
			INNER JOIN POSICIO AS PR ON PR.POSICIO_ID=CPR.POSICIO_ID 
			WHERE CPR.CASTELL_ID=C.CASTELL_ID AND PR.ESNUCLI=1 AND PR.ESTRONC=0) AS BAIXOS,
	(SELECT COUNT(*) 
			FROM CASTELL_POSICIO AS CPR 
			INNER JOIN POSICIO AS PR ON PR.POSICIO_ID=CPR.POSICIO_ID 
			WHERE CPR.CASTELL_ID=C.CASTELL_ID AND PR.ESFOLRE=1) AS FOLRETIS,
(SELECT SUM(IF(IU.ESTAT > 0, 1, 0)) 
		FROM INSCRITS IU 
		JOIN CASTELLER C ON C.CASTELLER_ID=IU.CASTELLER_ID
		JOIN POSICIO P ON P.POSICIO_ID=C.POSICIO_PINYA_ID
		WHERE IU.EVENT_ID=E.EVENT_ID
		AND (P.ESNUCLI=1 OR P.ESTRONC=1 OR P.ESCORDO=1)) AS UTILS
FROM EVENT AS E
LEFT JOIN INSCRITS AS I ON I.EVENT_ID = E.EVENT_ID
LEFT JOIN CASTELL AS C ON C.EVENT_ID = E.EVENT_ID
WHERE (E.EVENT_PARE_ID IS NULL OR E.EVENT_PARE_ID=0)
AND E.DATA >= '".$dataInici."'
GROUP BY E.EVENT_ID, E.NOM, E.DATA, E.ESTAT, E.TIPUS, C.NOM, C.ORDRE
ORDER BY E.DATA, E.NOM, C.ORDRE";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) 
{
	$event=-1;
	$th="";
	$tdData="";
	$tdEstat="";
	$tdInscrits="";
	$tdContestes="";
	$tdCastell="";
	$tSeparador="";
	$seguentActuacio=0;
	$castells=array();
	$linia=0;
	$eventMax=0;
	$e=-1;
	$minuts=0;
	$proxim=0;
	
	array_push($castells, array_fill(0, 150, ""));
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) 
	{	
		if ($event != $row["EVENT_ID"])
		{			
			$timeInici=new DateTime($row["DATA"]);
			$time=new DateTime($row["DATA"]);
			$minuts=0;
			$e=$e+1;
			$linia=0;
			$tdCastells="";
			$a  = $row["ESTAT"];
			
			if ($a == -1)
			{
				$estatNom = "INACTIU";
			}
			elseif ($a == 1)
			{
				$estatNom = "ACTIU";
			}
			elseif ($a == 2)
			{
				$estatNom = "ARXIVAT";
			}
			else
			{
				$estatNom = "";
			}
			
			if (($proxim == 0) && (strtotime($row["DATA"]) > strtotime(date("d-m-Y H:i"))))
			{				
				$proxim = 1;				
			}
			else if ($proxim == 1)
			{
				$proxim = 2;
			}

			$link = "e=".$estat."&id=".$row["EVENT_ID"];
			
			if ($row["TIPUS"]==1)
			{
				$style="style='background:#01DF01;'";
				if (($seguentActuacio==0) && (strtotime($row["DATA"]) < strtotime(date("d-m-Y H:i"))))
				{
					$seguentActuacio=1;
					$style="style='background:#04B404;'";
				}
				
				if($proxim == 1)
				{
					$style="id='proxim' ".$style;
				}
			}
			else if ($proxim == 1)
			{
				$style="id='proxim' style='background:red;'";
			}
			else
			{
				$style="";
			}
			
			$th .= "<th  class='llistes2' ".$style."><a style='color:#FFF;' href='Castell.php?".$link."'>".$row["NOM"]."</a></th>";		
			$tdData .= "<td class='llistes2'>".$row["DATA"]."</td>";
			$tdEstat .= "<td class='llistes2'>".$estatNom."</td>";
			$tdContestes .= "<td class='llistes2'>".$row["CONTESTES_QTA"]."</td>";
			$tdInscrits .= "<td class='llistes2'>(".$row["UTILS"].")".$row["INSCRITS_QTA"]."</td>";
			
			$event = $row["EVENT_ID"];
			if ($tdCastells <> "")
			{
				$tdCastells .= "</tr>";
			}
			$tdCastells .= "<tr class='llistes'>";
			$tSeparador .= "<th class='llistes2'></th>";
		}
		
		if($row["CASTELL"] != "")
		{
			if(($row["FOLRETIS"] > 0) && ($row["BAIXOS"] > 0)) //PINYA+FOLRE
				$minuts=15;
			elseif ($row["BAIXOS"] > 0)//PINYA
				$minuts=10;
			elseif ($row["FOLRETIS"] > 0)//FOLRE TERRA
				$minuts=10;
			else
				$minuts=5;
			

			$time->add(new DateInterval('PT' . $minuts . 'M'));

			$stamp1 = $timeInici->format('H:i');
			$stamp2 = $time->format('H:i');		
			
			$colorCasella = "";
			if (strpos($row["CASTELL"], "3d") !== false)
			{
				$colorCasella = "style='background:lightpink'";
			}
			elseif (strpos($row["CASTELL"], "4d") !== false)
			{
				$colorCasella = "style='background:lightgreen'";
			}
			elseif (strpos($row["CASTELL"], "2d") !== false)
			{
				$colorCasella = "style='background:lightblue'";
			}
			elseif (strpos($row["CASTELL"], "5d") !== false)
			{
				$colorCasella = "style='background:#ffffb3'";
			}
			elseif (strpos($row["CASTELL"], "7d") !== false)
			{
				$colorCasella = "style='background:lightsalmon'";
			}
			
			$tdCastell="<td class='llistes2' ".$colorCasella.">".$row["CASTELL"]."  - ".$stamp1."-".$stamp2."</td>";
			
			$timeInici->add(new DateInterval('PT' . $minuts . 'M'));
		}
		else
		{
			$tdCastell="<td class='llistes2'></td>";
		}
		
		if (count($castells) <= $linia)
			array_push($castells, array_fill(0, 150, ""));	
		
		$castells[$linia][$e] .= $tdCastell;
		
		if ($eventMax < count($castells[$linia]))
			$eventMax = count($castells[$linia]);
		
		$linia=$linia+1;
    }
	echo "<tr class='llistes'>".$th."</tr>";
	echo "<tr class='llistes'>".$tdData."</tr>";
	echo "<tr class='llistes'>".$tdEstat."</tr>";
	//echo "<tr class='llistes'>".$tdContestes."</tr>";
	echo "<tr class='llistes'>".$tdInscrits."</tr>";
	echo "<tr class='llistes'>".$tSeparador."</tr>";

	for($x = 0; $x < count($castells); $x++) 
	{
		echo "<tr class='llistes'>";

		for($c = 0; $c < count($castells[$x]); $c++) 
		{
			if ($castells[$x][$c] <> "")
				echo $castells[$x][$c];
			else
				echo "<td class='llistes2'></td>";
		}
		
		echo "</tr>";
	}
	
}
else if (mysqli_error($conn) != "")
{
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>	  
	  
	</table> 
	</div>
<script>

VesAvui();

function VesAvui()
{
	var obj = document.getElementById("proxim");
	if(obj!= null)
	{
		window.scrollBy(obj.offsetLeft-200, 0);
	}
}

function Filtrar()
{
	window.open("Planificacio.php?d="+document.getElementById("data").value, "_self");
}
</script>
   </body>
</html>

