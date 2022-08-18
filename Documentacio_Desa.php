<html>
<head>
  <title>Pinyator</title>
</head>
<body>
<?php
$id = intval($_POST["id"]);
$nom = strval($_POST["nom"]);
$estat = intval($_POST["estat"]);
$link= strval($_POST["link"]);
$grup= strval($_POST["grup"]);
$ordre = intval($_POST["ordre"]);


include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

$nom = GetStrDB($nom);
$link = GetStrDB($link);
$grup = GetStrDB($grup);

if ($id > 0)
{
	$sql="UPDATE DOCUMENTACIO SET NOM='".$nom."'
	,DATA=CURRENT_DATE()
	,GRUP='".$grup."'
	,ESTAT=".$estat."
	,LINK='".$link."'
	,ORDRE='".$ordre."'
	WHERE DOCUMENTACIO_ID = ".$id.";";
}
else
{
	$sql="INSERT INTO DOCUMENTACIO(NOM,DATA,GRUP,ESTAT,LINK,ORDRE) 
	VALUES('".$nom."',CURRENT_DATE(),'".$grup."',".$estat.",'".$link."',".$ordre.");";
}

if (mysqli_multi_query($conn, $sql)) 
{	
	echo "<meta http-equiv='refresh' content='0; url=Documentacio.php'/>";
} 
else if (mysqli_error($conn) != "")
{
	echo $id.";".$nom.";".$link;
	echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);

?>
<a href='Documentacio.php'>Torna a Documentació.</a>
</body>
</html>