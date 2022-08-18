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
	$sql="UPDATE FROM CASTELLERS SET POSICIO_ID=0 WHERE POSICIO_ID = ".$id;

	if (mysqli_query($conn, $sql)) 
	{
		$sql="DELETE FROM POSICIONS WHERE POSICIO_ID = ".$id;

		if (mysqli_query($conn, $sql)) 
		{	
			echo "<meta http-equiv='refresh' content='0; url=Usuari.php'/>";
		} 
		else if (mysqli_error($conn) != "")
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
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
<a href='Posicio.php'>Torna a les posicions.</a>
</body>
</html>