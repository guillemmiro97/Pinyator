<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pinyator - Busca't</title>
  <script src="llibreria/castell.js"></script>
  <script src="llibreria/disseny.js"></script>  
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body>
<?php

$id = intval($_GET['id']);
$malnom = strval($_GET['malnom']);

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";


echo "<input type='text' id='myInput' class='form_edit' onkeyup='Buscat()' placeholder='Cerca casteller..' value='".$malnom."'>";
		
		$nom="";
		$H=700;
		$W=1000;
		$ps1="";
		$ps2="";
		$ps3="";
		$ps4="";
				

		$sql="SELECT NOM, POSICIO_ID, COLORFONS, COLORTEXT, COLORCAMISA
		FROM POSICIO
		ORDER BY NOM";

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) 
		{
			$colors = "<script>";
			while($row = mysqli_fetch_assoc($result)) 
			{
				$colors = $colors." AddColorPosicio(".$row["POSICIO_ID"].",'".$row["COLORFONS"]."','".$row["COLORTEXT"]."','".$row["COLORCAMISA"]."');";
			}
			echo $colors."</script>";
		}
		
		

	
		$sql="SELECT NOM,H,W
		,PESTANYA_1, PESTANYA_2, PESTANYA_3, PESTANYA_4
		FROM CASTELL
		WHERE CASTELL_ID = ".$id;

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) 
		{
			while($row = mysqli_fetch_assoc($result)) 
			{
				$nom = $row["NOM"];
				$H = $row["H"];
				$W = $row["W"];
				$ps1 = $row["PESTANYA_1"];
				$ps2 = $row["PESTANYA_2"];
				$ps3 = $row["PESTANYA_3"];
				$ps4 = $row["PESTANYA_4"];
			}		
		}
		else if (mysqli_error($conn) != "")
		{
			echo "<br>Error: " . $sql . "<br>" . mysqli_error($conn);
		}

	?>	

	<div>
		<table>
			<tr>
			<td>
	<?php
		if($ps1 != "")
			echo "<button class='tabulador tabuladorActiu' name='1' onClick='CanviPestanya(this)'>".$ps1."</button>";
		if($ps2 != "")
			echo "<button class='tabulador' name='2' onClick='CanviPestanya(this)'>".$ps2."</button>";
		if($ps3 != "")
			echo "<button class='tabulador' name='3' onClick='CanviPestanya(this)'>".$ps3."</button>";
		if($ps4 != "")
			echo "<button class='tabulador' name='4' onClick='CanviPestanya(this)'>".$ps4."</button>";
	?>
			</td>
			</tr>
			<tr>
			<td>
				<canvas id="canvas1" style="border:1px solid" height="<?php echo $H ?>" width="<?php echo $W ?>">
					This text is displayed if your browser does not support HTML5 Canvas.
				</canvas>
			</td>
			</tr>
		</table>
	</div>
	<script>
		initCanvas(-1,1);
		init();
	</script>
	<span id="txtHint"></span>
<?php
	
	$sql="SELECT CASELLA_ID,CORDO,CP.POSICIO_ID,CP.X,CP.Y,CP.H,CP.W,ANGLE,
	FORMA,TEXT,SEGUENT,LINKAT,  P.COLORFONS, P.COLORTEXT,
	CP.CASTELLER_ID,IFNULL(C.MALNOM, 0) AS MALNOM, IF(I.ESTAT > 0, 2, 0) AS ESTAT,
	IFNULL(C.ALTURA, 0) AS ALTURA, IFNULL(C.ALTURA_TRONCS, 0) AS ALTURA_TRONCS, PESTANYA,
	IFNULL(C.PORTAR_PEU, 1) AS PORTAR_PEU, IFNULL(C.LESIONAT, 0) AS LESIONAT
	FROM CASTELL_POSICIO AS CP 
	INNER JOIN CASTELL AS CT ON CT.CASTELL_ID=CP.CASTELL_ID
	LEFT JOIN CASTELLER AS C ON C.CASTELLER_ID=CP.CASTELLER_ID 
	LEFT JOIN INSCRITS AS I ON CT.EVENT_ID=I.EVENT_ID AND I.CASTELLER_ID=C.CASTELLER_ID
	LEFT JOIN POSICIO AS P ON P.POSICIO_ID=CP.POSICIO_ID
	WHERE CP.CASTELL_ID = ".$id;

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		echo "<script>";
		// output data of each row
		while($row = mysqli_fetch_assoc($result)) 
		{
			echo "addRect(".$row["X"].",".$row["Y"].",".$row["W"].",".$row["H"].",".$row["CORDO"].",".$row["POSICIO_ID"].",".$row["ANGLE"].",".$id.",".$row["CASELLA_ID"]."
			,".$row["PESTANYA"].",".$row["FORMA"].",'".$row["TEXT"]."',".$row["LINKAT"].",".$row["SEGUENT"].",".$id.",".$row["CASTELLER_ID"].",'".$row["MALNOM"]."',".$row["ESTAT"].",".$row["ALTURA"]."
			,".$row["PORTAR_PEU"].",".$row["LESIONAT"].",0,0,0);\n";
		}
		echo " Buscat(); </script>";
	}
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
	
	mysqli_close($conn);
?>
</body>
</html>