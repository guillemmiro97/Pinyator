<html>
<head>
	<title>Pinyator - Posicions</title>
	<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Head.php";?>
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body>
<?php $menu=7; include "$_SERVER[DOCUMENT_ROOT]/pinyator/Menu.php";?>
	<table class="butons">
	<tr>
		<th><a href="Posicio_Fitxa.php" class="boto" >Nova posició</a></th>
	</tr>
	</table>
	<br>
	<table class="llistes" style="width:100%">
		<tr class="llistes">
			<th class="llistes">Nom</th>
			<th class="llistes">Es Nucli</th>
			<th class="llistes">Es Pinya</th>
			<th class="llistes">Es Tronc</th>
			<th class="llistes">Es Folre</th>
			<th class="llistes">Es Canalla</th>
		</tr>
<?php
	include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

	$sql="SELECT P.NOM, P.POSICIO_ID, 
	P.ESNUCLI, P.ESCORDO, P.ESTRONC, P.ESFOLRE, P.ESCANALLA
	FROM POSICIO AS P
	ORDER BY NOM";
		
	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) > 0) 
	{			
				// output data of each row
		while($row = mysqli_fetch_assoc($result)) 
		{
			echo "<tr class='llistes'>
			<td class='llistes'><a href='Posicio_Fitxa.php?id=".$row["POSICIO_ID"]."'>".$row["NOM"]."</a></td>
			<td class='llistes'>".retornaText($row["ESNUCLI"])."</td>
			<td class='llistes'>".retornaText($row["ESCORDO"])."</td>
			<td class='llistes'>".retornaText($row["ESTRONC"])."</td>
			<td class='llistes'>".retornaText($row["ESFOLRE"])."</td>
			<td class='llistes'>".retornaText($row["ESCANALLA"])."</td>
			</tr>";
		}	
	}
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}

	mysqli_close($conn);
	
	
function retornaText($valor) 
{
	if ($valor == 1)
	{	
		return "Si";
	}
	else
	{
		return "";
	}
}

	
?>	  

	</table> 	
</body>
</html>

