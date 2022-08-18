<html>
<head>
	<title>Pinyator - Usuaris</title>
	<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Head.php";?>
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body>
<?php $menu=6; include "$_SERVER[DOCUMENT_ROOT]/pinyator/Menu.php";?>
	<table class="butons">
	<tr>
		<th><a href="Usuari_Fitxa.php" class="boto" >Nou Usuari</a></th>
	</tr>
	</table>
	<br>
	<table class="llistes">
		<tr class="llistes">
			<th class="llistes">Nom</th>
		</tr>
<?php
	include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

	$sql="SELECT A.NOM, A.IDUSUARI
	FROM USUARIS AS A";
		
	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) > 0) 
	{			
				// output data of each row
		while($row = mysqli_fetch_assoc($result)) 
		{
			echo "<tr class='llistes'>
			<td class='llistes'><a href='Usuari_Fitxa.php?id=".$row["IDUSUARI"]."'>".$row["NOM"]."</a></td>
			</tr>";
		}	
	}
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}

	mysqli_close($conn);
?>	  

	</table> 	
</body>
</html>

