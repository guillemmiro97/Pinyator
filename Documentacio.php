<html lang="es-ES">
<head>
  <title>Pinyator - Documentació</title>
<meta charset="utf-8">
<?php $menu=10; include "$_SERVER[DOCUMENT_ROOT]/pinyator/Head.php";?>
<script src="llibreria/popup_esborra.js"></script>
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body class="popup">
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Menu.php";?>

<table class='butons'>
	<tr class='butons'>
		<th class='butons'><a href="Documentacio_Fitxa.php" class="boto" <?php EventLv2Not("hidden");?> >Nou</a></th>
		<th></th>
	</tr>
</table>
<br>
 <table class='llistes'>
  <tr class='llistes'>
 	<th class='llistes'>Grup</th>
	<th class='llistes'>Ordre</th>
    <th class='llistes'>Nom</th>
	<th class='llistes'>Link</th>
    <th class='llistes'>Data</th>
	<th class='llistes'>Estat</th>
	<th class='llistes'></th>	
  </tr>
<?php

$estat=1;
if (!empty($_GET["e"]))
{	
	$estat=intval($_GET["e"]);
}

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

$sql="SELECT D.DOCUMENTACIO_ID, D.NOM, date_format(D.DATA, '%d-%m-%Y') AS DATA, 
D.ESTAT, D.LINK, D.ORDRE, D.GRUP
FROM DOCUMENTACIO AS D
ORDER BY D.GRUP, D.ORDRE";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) 
{
	$PosicioId = 0;
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) 
	{
		$a  = $row["ESTAT"];
		
		if ($a == 0)
		{
			$estatNom = "INACTIU";
		}
		elseif ($a == 1)
		{
			$estatNom = "ACTIU";
		}
		else
		{
			$estatNom = "";
		}		
		
		$link = "id=".$row["DOCUMENTACIO_ID"];
		
		$linkNom = "<a href='Documentacio_Fitxa.php?".$link."'>".$row["NOM"]."</a>";		
		
		echo "<tr class='llistes'>
		<td class='llistes'>".$row["GRUP"]."</td>
		<td class='llistes'>".$row["ORDRE"]."</td>		
		<td class='llistes'>".$linkNom."</td>
		<td class='llistes'>".$row["LINK"]."</td>
		<td class='llistes'>".$row["DATA"]."</td>
		<td class='llistes'>".$estatNom."</td>";

		if ($a == 1)
			echo "<td class='llistes'></td>";
		else
			echo "<td class='llistes'><button class='boto boto_remove' name='Documentacio_Esborra.php?id=".$row["DOCUMENTACIO_ID"]."' onClick='ShowPopup(this)' >X</button></td>";

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
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Popup_Esborrar.php";?> 
   </body>
</html>

