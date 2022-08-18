<html>
<head>
  <title>Pinyator</title>
</head>
<body>
<?php
$id = intval($_POST["id"]);
$malnom = strval($_POST["malnom"]);
$nom = strval($_POST["nom"]);
$cognom1 = strval($_POST["cognom1"]);
$cognom2 = strval($_POST["cognom2"]);
$posicioPinya = intval($_POST["posiciopinyaid"]);
$posicioTronc = intval($_POST["posiciotroncid"]);
$responsable1 = intval($_POST["responsableid1"]);
$responsable2 = intval($_POST["responsableid2"]);
$altura = intval($_POST["altura"]);
$alturaTroncs = intval($_POST["alturaTroncs"]);
$lesionat = 0;
$portarpeu = 0;
$novell = 0;
$vacunaCOVID = 0;
$estat = 1;
$accio = strval($_POST["Desa"]);

if (!empty($_POST["estat"]))
	$estat=intval($_POST["estat"]);

if (!empty($_POST["lesionat"]))
	$lesionat=intval($_POST["lesionat"]);

if (!empty($_POST["portarpeu"]))
	$portarpeu=intval($_POST["portarpeu"]);

if (!empty($_POST["novell"]))
	$novell=intval($_POST["novell"]);

if (!empty($_POST["covid"]))
	$vacunaCOVID=intval($_POST["covid"]);

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

$malnom = GetStrDB($malnom);
$nom = GetStrDB($nom);
$cognom1 = GetStrDB($cognom1);
$cognom2 = GetStrDB($cognom2);

if ($id > 0)
{
	$sql="UPDATE CASTELLER SET MALNOM='".$malnom."',NOM='".$nom."',COGNOM_1='".$cognom1."',COGNOM_2='".$cognom2."'
	,POSICIO_PINYA_ID=".$posicioPinya.",POSICIO_TRONC_ID=".$posicioTronc."
	,FAMILIA_ID=".$responsable1.",FAMILIA2_ID=".$responsable2.",ALTURA=".$altura.",ALTURA_TRONCS=".$alturaTroncs."
	,ESTAT=".$estat.",LESIONAT=".$lesionat.",PORTAR_PEU=".$portarpeu.",NOVELL=".$novell.",VACUNA_COVID=".$vacunaCOVID."
	WHERE CASTELLER_ID = ".$id;
	//$sql="UPDATE CASTELLER SET CODI= UUID() WHERE (CODI IS NULL OR CODI='')AND CASTELLER_ID = ".$id;
}
else
{
	$sql="INSERT INTO CASTELLER(MALNOM, NOM, COGNOM_1, COGNOM_2, POSICIO_PINYA_ID, POSICIO_TRONC_ID, CODI, FAMILIA_ID, FAMILIA2_ID, ESTAT, ALTURA, ALTURA_TRONCS, LESIONAT, PORTAR_PEU, NOVELL, VACUNA_COVID) 
	VALUES('".$malnom."','".$nom."','".$cognom1."','".$cognom2."',".$posicioPinya.",".$posicioTronc.", UUID(),".$responsable1.",".$responsable2.",1,".$altura.",".$alturaTroncs.",".$lesionat.",".$portarpeu.",".$novell.",".$vacunaCOVID.")";
}

if (mysqli_query($conn, $sql)) 
{	
	if ($accio=="desarievents")	
	{		
		$sql="SELECT CASTELLER_ID
			FROM CASTELLER
			WHERE MALNOM = '".$malnom."'";

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) 
		{
			while($row = mysqli_fetch_assoc($result))
			{
				$id = $row["CASTELLER_ID"];
			}
			echo "<meta http-equiv='refresh' content='0; url=Casteller_Fitxa.php?id=".$id."'/>";
		}
		else if (mysqli_error($conn) != "")
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	}
	else
	{
		echo "<meta http-equiv='refresh' content='0; url=Casteller.php'/>";
	}
} 
else if (mysqli_error($conn) != "")
{
	echo $id.";".$nom.";".$data;
	echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);

?>
<a href='Casteller.php'>Torna als castellers.</a>
</body>
</html>