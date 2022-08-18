<html lang="es-ES">
<head>
  <title>Pinyator - Esdeveniment</title>
<meta charset="utf-8">
<?php $menu=2; include "$_SERVER[DOCUMENT_ROOT]/pinyator/Head.php";?>
<script src="llibreria/popup.js"></script>
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body class="popup">
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Menu.php";?>

<table class='butons'>
	<tr class='butons'>
		<th class='butons'><a href="Event_Fitxa.php" class="boto" <?php EventLv2Not("hidden");?> >Nou</a></th>
		<th></th>
		<th class='butons'>
			<a href="Event.php?e=1" class="boto" >Actius</a>
			<a href="Event.php?e=-1" class="boto" >Inactius</a>
			<a href="Event.php?e=2" class="boto" >Arxivats</a>
		</th>
	</tr>
</table>
<br>
 <table class='llistes'>
  <tr class='llistes'>
    <th class='llistes'>Esdeveniment</th>
    <th class='llistes'>Dia</th>
	<?php if (EsEventLv2())echo "<th class='llistes'>Castells</th>"; ?>	
	<th class='llistes'>Inscrits</th>
	<th class='llistes'>Comentaris</th>
	<th class='llistes'>Màx. Part.</th>
	<th class='llistes'>Plantilla</th>
	<th class='llistes'>Estat</th>
	<?php if (EsEventLv2()) echo "<th class='llistes'>Acció</th>" ?>	
  </tr>
<?php

$estat=1;
if (!empty($_GET["e"]))
{	
	$estat=intval($_GET["e"]);
}

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

$sql="SELECT E.EVENT_ID, E.NOM, date_format(E.DATA, '%d-%m-%Y %H:%i') AS DATA, 
E.ESTAT, E.EVENT_PARE_ID, E.ESPLANTILLA,
COUNT(I.ESTAT) AS CONTESTES_QTA,
(SUM(IF(I.ESTAT > 0, 1, 0)) + SUM(IFNULL(I.ACOMPANYANTS,0))) AS INSCRITS_QTA,
(SELECT IFNULL(COUNT(*), 0) FROM CASTELL C WHERE C.EVENT_ID=E.EVENT_ID) AS CASTELLS_QTA,
IFNULL(EP.DATA, E.DATA) AS ORDENACIO, E.MAX_PARTICIPANTS,
(SELECT IFNULL(COUNT(*), 0) FROM EVENT_COMENTARIS EC WHERE EC.EVENT_ID=E.EVENT_ID) AS COMENTARIS,
(SELECT SUM(IF(IU.ESTAT > 0, 1, 0)) 
		FROM INSCRITS IU 
		JOIN CASTELLER C ON C.CASTELLER_ID=IU.CASTELLER_ID
		JOIN POSICIO P ON P.POSICIO_ID=C.POSICIO_PINYA_ID
		WHERE IU.EVENT_ID=E.EVENT_ID
		AND (P.ESNUCLI=1 OR P.ESTRONC=1 OR P.ESCORDO=1)) AS UTILS
FROM EVENT AS E
LEFT JOIN EVENT AS EP ON EP.EVENT_ID = E.EVENT_PARE_ID
LEFT JOIN INSCRITS AS I ON I.EVENT_ID = E.EVENT_ID
WHERE (E.ESTAT = ".$estat." OR E.ESTAT = 0)
GROUP BY E.EVENT_ID, E.NOM, E.DATA, E.ESTAT, E.EVENT_PARE_ID
ORDER BY ORDENACIO, E.EVENT_PARE_ID, E.DATA";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) 
{
	$PosicioId = 0;
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) 
	{
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
		$esPlantilla = $row["ESPLANTILLA"]==1?"SI":"NO";
		
		$tInici = "";
		$tFinal = "";
		if ($row["EVENT_PARE_ID"] > 0)
		{
			$tInici = "<li>";
			$tFinal = "</li>";
		}
		
		$link = "e=".$estat."&id=".$row["EVENT_ID"];
		
		$linkCastells = "";
		if ($row["CASTELLS_QTA"] > 0)
		{
			$linkCastells = "<a href='Castell.php?id=".$row["EVENT_ID"]."'>".$row["CASTELLS_QTA"]."</a>";
		}
		else
		{
			$linkCastells = "<a href='Castell_Nou.php?id=".$row["EVENT_ID"]."' class='boto' >+</a>";
		}

		if(EsEventLv2())
		{
			$linkNom = "<a href='Event_Fitxa.php?".$link."'>".$row["NOM"]."</a>";
		}
		else
		{
			$linkNom = $row["NOM"];
		}		
		
		echo "<tr class='llistes'>
		<td class='llistes'>".$tInici.$linkNom.$tFinal."</td>
		<td class='llistes'>".$row["DATA"]."</td>";

		if(EsEventLv2())
		{
			echo "<td class='llistes'>".$linkCastells."</td>";
		}
		echo "<td class='llistes'><a href='Event_LlistaPrivat.php?".$link."'>(".$row["UTILS"].")".$row["INSCRITS_QTA"]."/".$row["CONTESTES_QTA"]."</a></td>
		<td class='llistes'><a href='Event_Comentari_Privat.php?id=".$row["EVENT_ID"]."'>".$row["COMENTARIS"]."</a></td>
		<td class='llistes'>".$row["MAX_PARTICIPANTS"]."</td>
		<td class='llistes'>".$esPlantilla."</td>
		<td class='llistes'>".$estatNom."</td>";
		if(EsEventLv2())
		{
			echo "<td class='llistes'>";
			echo "<button class='boto' name='Event_Copia.php?id=".$row["EVENT_ID"]."' onClick='ShowPopupCopia(this)'><img class='img_boto' src='icons/copy.png'></button>";

			echo "<button class='boto boto_remove' name='Event_Esborra.php?id=".$row["EVENT_ID"]."' onClick='ShowPopupEsborra(this)'><img class='img_boto' src='icons/trash.png'></button>";

			echo "</td>";
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
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Popup.php";?> 
   </body>
</html>

