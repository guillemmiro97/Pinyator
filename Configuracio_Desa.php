<html>
<head>
  <title>Pinyator</title>
</head>
<body>
<?php
$resoluciopantalla = intval($_POST["resoluciopantalla"]);
$temporada = strval($_POST["temporada"]);
$fites = 0;
if (!empty($_POST["fites"]))
{
	$fites = intval($_POST["fites"]);
}
$participants = 0;
if (!empty($_POST["participants"]))
{
	$participants = intval($_POST["participants"]);
}

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

$sql="UPDATE CONFIGURACIO SET TEMPORADA='".$temporada."',
	RESOLUCIOPANTALLA=".$resoluciopantalla.",FITES=".$fites.",PARTICIPANTS=".$participants;

if (mysqli_query($conn, $sql)) 
{	
	echo "<meta http-equiv='refresh' content='0; url=Configuracio.php'/>";
} 
else if (mysqli_error($conn) != "")
{
	echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);

?>
<a href='Configuracio.php'>Torna.</a>
</body>
</html>