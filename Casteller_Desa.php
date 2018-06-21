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
$forca = intval($_POST["forca"]);
$lesionat = 0;
$portarpeu = 0;
$estat = 1;

if (!empty($_POST["estat"]))
	$estat=intval($_POST["estat"]);

if (!empty($_POST["lesionat"]))
	$lesionat=intval($_POST["lesionat"]);

if (!empty($_POST["portarpeu"]))
	$portarpeu=intval($_POST["portarpeu"]);

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

if ($id > 0)
{
	$sql="UPDATE CASTELLER SET MALNOM='".$malnom."',NOM='".$nom."',COGNOM_1='".$cognom1."',COGNOM_2='".$cognom2."'
	,POSICIO_PINYA_ID=".$posicioPinya.",POSICIO_TRONC_ID=".$posicioTronc."
	,FAMILIA_ID=".$responsable1.",FAMILIA2_ID=".$responsable2.",ALTURA=".$altura.",FORCA=".$forca."
	,ESTAT=".$estat.",LESIONAT=".$lesionat.",PORTAR_PEU=".$portarpeu."
	WHERE CASTELLER_ID = ".$id;
	//$sql="UPDATE CASTELLER SET CODI= UUID() WHERE (CODI IS NULL OR CODI='')AND CASTELLER_ID = ".$id;
}
else
{
	$sql="INSERT INTO CASTELLER(MALNOM, NOM, COGNOM_1, COGNOM_2, POSICIO_PINYA_ID, POSICIO_TRONC_ID, CODI, FAMILIA_ID, FAMILIA2_ID, ESTAT, ALTURA, FORCA, LESIONAT, PORTAR_PEU) 
	VALUES('".$malnom."','".$nom."','".$cognom1."','".$cognom2."',".$posicioPinya.",".$posicioTronc.", UUID(),".$responsable1.",".$responsable2.",1,".$altura.",".$forca.",".$lesionat.",".$portarpeu.")";
}

if (mysqli_query($conn, $sql)) 
{	
	echo "<meta http-equiv='refresh' content='0; url=Casteller.php'/>";
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