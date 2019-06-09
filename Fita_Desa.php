<html>
<head>
  <title>Pinyator</title>
</head>
<body>
<?php
$id = intval($_POST["id"]);
$nom = strval($_POST["nom"]);
$data = $_POST["data"];
$estat = intval($_POST["estat"]);
$temporada = strval($_POST["temporada"]);
$recompensa = strval($_POST["recompensa"]);
$ordre = intval($_POST["ordre"]);


include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

$nom = GetStrDB($nom);
$temporada = GetStrDB($temporada);
$recompensa = GetStrDB($recompensa);

if ($id > 0)
{
	$sql="UPDATE FITES SET NOM='".$nom."'
	,DATA_COMPLETAT=".GetFormatedDate($data)."
	,ESTAT=".$estat."
	,TEMPORADA='".$temporada."'
	,RECOMPENSA='".$recompensa."'
	,ORDRE='".$ordre."'
	WHERE FITES_ID = ".$id.";";
}
else
{
	$sql="INSERT INTO FITES(NOM,DATA_COMPLETAT,ESTAT,TEMPORADA,RECOMPENSA,ORDRE) 
	VALUES('".$nom."',".GetFormatedDate($data).",".$estat.",'".$temporada."','".$recompensa."',".$ordre.");";
}

if (mysqli_multi_query($conn, $sql)) 
{	
	echo "<meta http-equiv='refresh' content='0; url=Fites.php'/>";
} 
else if (mysqli_error($conn) != "")
{
	echo $id.";".$nom.";".$data;
	echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);

?>
<a href='Fites.php'>Torna a Fites.</a>
</body>
</html>