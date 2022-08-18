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
		$sql="DELETE FROM INSCRITS WHERE EVENT_ID=".$id.";DELETE FROM EVENT WHERE EVENT_ID=".$id.";";
	}

	if (mysqli_multi_query($conn, $sql)) 
	{	
		echo "<meta http-equiv='refresh' content='0; url=Event.php'/>";
	} 
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn) . "<br>";
	}

	mysqli_close($conn);

?>
<a href='Event.php'>Torna als Esdeveniments.</a>
</body>
</html>