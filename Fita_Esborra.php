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
		$sql="DELETE FROM FITES WHERE FITES_ID=".$id.";";
	}

	if (mysqli_multi_query($conn, $sql)) 
	{	
		echo "<meta http-equiv='refresh' content='0; url=Fites.php'/>";
	} 
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn) . "<br>";
	}

	mysqli_close($conn);

?>
<a href='Fites.php'>Torna a Fites.</a>
</body>
</html>