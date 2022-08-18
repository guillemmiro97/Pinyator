<html>
<head>
  <title>Pinyator</title>
</head>
<body>
<?php
$id = intval($_GET["id"]);

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

if ($id > 0)
{
	$sql="DELETE FROM USUARIS WHERE IDUSUARI = ".$id;

	if (mysqli_query($conn, $sql)) 
	{	
		echo "<meta http-equiv='refresh' content='0; url=Usuari.php'/>";
	} 
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
}
else
{
	echo "<meta http-equiv='refresh' content='0; url=Usuari.php'/>";	
}

mysqli_close($conn);

?>
<a href='Usuari.php'>Torna als usuaris.</a>
</body>
</html>