<?php

if ($public)
{
	$url = "Event_Comentari_Public.php";
}
else
{
	$url = "Event_Comentari_Privat.php";
}

$ordenacio="MALNOM";
if (!empty($_GET["o"]))
{	
	$ordenacio=($_GET["o"])." DESC,".$ordenacio;
}

if (!EsEventLv2()) $esEditable=0;

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

$sql="SELECT E.NOM, date_format(E.DATA, '%d/%m/%Y %H:%i') AS DATA,
E.EVENT_PARE_ID
FROM EVENT AS E
WHERE E.EVENT_ID = ".$id;

$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) 
{
	while($row = mysqli_fetch_assoc($result)) 
	{
		if ($row["EVENT_PARE_ID"] < 1)
		{
			echo "<a href='".$url."?id=".$id."&nom=".$nom."' class='boto'>Comentaris</a>";
		}
		
		echo "<h3>".$row["NOM"]." - ".$row["DATA"]."</h3>";
	}
}

?>
<label id="txtErrors"></label>
 <table class="llistes">
  <tr class="llistes">
    <th class="llistes">MALNOM</th>
    <th class="llistes">Estat</th>
	<?php if ($esEditable == 1) echo "<th class='llistes'>Vinc</th><th class='llistes'>NO Vinc</th><th class='llistes'>Esborra</th>";?>
  </tr>
<?php

$edicioInscripcio="";

$sql="SELECT C.MALNOM, IFNULL(I.ESTAT,-1) AS ESTAT, C.CODI,
IFNULL(E.EVENT_ID,".$id.") AS EVENT_ID, C.CASTELLER_ID
FROM CASTELLER AS C
LEFT JOIN EVENT AS E ON E.EVENT_ID=".$id."
LEFT JOIN INSCRITS AS I ON C.CASTELLER_ID=I.CASTELLER_ID AND I.EVENT_ID=E.EVENT_ID
WHERE C.ESTAT = 1
ORDER BY ".$ordenacio;

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) 
{
	$PosicioId = 0;
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) 
	{
		$color = "";
		$a  = $row["ESTAT"];
		if ($a == 0)
		{
			$Estat = "No vinc";
			$color = "style='background-color:#ff1a1a;'"; 			
		}
		elseif ($a == 1)
		{
			$Estat = "Vinc";
			$color = "style='background-color:#33cc33;'";
		}
		elseif ($a == -1)
		{
			$Estat = "????";
			$color = "style='background-color:#FFFF00;'";
		}
		echo "<tr class='llistes'>";
		if ($esEditable == 1)
		{
			echo "<td class='llistes'><a href='Inscripcio.php?id=".$row["CODI"]."'>".$row["MALNOM"]."</a></td>";
		}
		else
		{
			echo "<td class='llistes'>".$row["MALNOM"]."</td>";
		}
		echo "<td class='llistes' id=".$row["CASTELLER_ID"]." ".$color.">".$Estat."</td>";
		if ($esEditable == 1)
		{	
			echo "<td class='llistes'><button class='boto' onClick='Vinc(".$row["EVENT_ID"].", ".$row["CASTELLER_ID"].")'>Vinc</button></td>";
			echo "<td class='llistes'><button class='boto' onClick='NoVinc(".$row["EVENT_ID"].", ".$row["CASTELLER_ID"].")'>No vinc</button></td>";
			echo "<td class='llistes'><button class='boto' onClick='Esborra(".$row["EVENT_ID"].", ".$row["CASTELLER_ID"].")'>????</button></td>";
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


