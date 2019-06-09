<html>
<head>
  <title>Pinyator</title>
</head>
<?php


$id = intval($_POST["id"]);
$password = strval($_POST["password"]);
$nom = strval($_POST["nom"]);
$carrec = intval($_POST["carrec"]);

if (!empty($_POST["esadmin"]))
	$esadmin = intval($_POST["esadmin"]);
else
	$esadmin = 0;

$segevent = intval($_POST["segevent"]);
$segcasteller = intval($_POST["segcasteller"]);
$segcastell = intval($_POST["segcastell"]);
$segboss = intval($_POST["segboss"]);


include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

$password = GetStrDB($password);
$nom = GetStrDB($nom);

if ($id > 0)
{
	$sql="UPDATE USUARIS SET PASSWORD='".$password."',NOM='".$nom."'
	,SEGADMIN=".$esadmin.",CARREC=".$carrec.",SEGCASTELLER=".$segcasteller." 
	,SEGEVENT=".$segevent.",SEGCASTELL=".$segcastell.",SEGBOSS=".$segboss."
	WHERE IDUSUARI = ".$id;
}
else
{
	$sql="INSERT INTO USUARIS(PASSWORD, NOM, SEGADMIN, CARREC, SEGCASTELLER, SEGEVENT, SEGCASTELL, SEGBOSS) 
	VALUES('".$password."','".$nom."',".$esadmin.", ".$carrec."
	, ".$segcasteller.", ".$segevent.", ".$segcastell.", ".$segboss.")";
}

if (mysqli_query($conn, $sql)) 
{	
	echo "<meta http-equiv='refresh' content='0; url=Usuari.php'/>";
} 
else if (mysqli_error($conn) != "")
{
	echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);

?>
<a href='Usuari.php'>Torna als usuaris.</a>
</body>
</html>