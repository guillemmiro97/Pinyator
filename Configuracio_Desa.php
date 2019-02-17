<html>
<head>
  <title>Pinyator</title>
</head>
<body>
<?php
$resoluciopantalla = intval($_POST["resoluciopantalla"]);
$temporada = strval($_POST["temporada"]);


include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

$sql="UPDATE CONFIGURACIO SET TEMPORADA='".$temporada."',RESOLUCIOPANTALLA=".$resoluciopantalla;

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