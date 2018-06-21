<html>
<head>
  <title>Pinyator - Plantilles</title>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Head.php";?>
<script src="llibreria/popup_esborra.js"></script>
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body class="popup">
	<?php $menu=3; include "$_SERVER[DOCUMENT_ROOT]/pinyator/Menu.php";?>
 <a href="Plantilla_Fitxa.php" class="boto">Nova</a> <br><br>

	<table class="llistes">
	  <tr class="llistes">
		<th class="llistes">Nom</th>	
		<th class="llistes">Estat</th>
		<th class="llistes"></th>
		<th class="llistes"></th>
		<th class="llistes"></th>
	  </tr>
	<?php

		include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";
		
		$sql="SELECT PLANTILLA_ID, NOM, ESTAT
		FROM PLANTILLA
		ORDER BY NOM";

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) 
		{
			// output data of each row
			while($row = mysqli_fetch_assoc($result)) 
			{
				$estat="Deshabilitada";
				if($row["ESTAT"] == 1)
				{
					$estat="Habilitada";
				}
				
				echo "<tr class='llistes'>
				<td class='llistes'>".$row["NOM"]."</td>
				<td class='llistes'>".$estat."</td>
				<td class='llistes'><a href='Plantilla_Fitxa.php?id=".$row["PLANTILLA_ID"]."&nom=".$row["NOM"]."'>Edita la fitxa</a></td>
				<td class='llistes'><a href='Plantilla_Disseny.php?id=".$row["PLANTILLA_ID"]."&nom=".$row["NOM"]."'>Dissenya</a></td>
				<td class='llistes'><button class='boto boto_remove' name='Plantilla_Desa.php?id=".$row["PLANTILLA_ID"]."&a=1' onClick='ShowPopup(this)'>Esborra</button></td>
				</tr>";
			}	
		}
		else if (mysqli_error($conn) != "")
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}

		mysqli_close($conn);
	?>	  
		  
	<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Popup_Esborrar.php";?>

   </body>
</html>

