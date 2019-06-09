<html>
<head>
  <title>Pinyator</title>
</head>
<body>
<?php
$id = intval($_POST["id"]);
$nom = strval($_POST["nom"]);
$text = strval($_POST["text"]);
$url = strval($_POST["url"]);

if (trim($text) != "")
{
	include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";
	
	$nom = GetStrDB($nom);
	$text = GetStrDB($text);
	$url = GetStrDB($url);

	$sql="INSERT INTO EVENT_COMENTARIS(EVENT_ID, USUARI, TEXT, DATA) VALUES(".$id.",'".$nom."','".$text."', NOW())";

	if (mysqli_query($conn, $sql)) 
	{	
		//echo $sql;
		echo "<meta http-equiv='refresh' content='0; url=".$url.".php?id=".$id."&nom=".$nom."'/>";
	} 
	else if (mysqli_error($conn) != "")
	{
		echo $id.";".$nom.";".$data;
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}

	mysqli_close($conn);
}
else
{
	echo "<meta http-equiv='refresh' content='0; url=".$url.".php?id=".$id."&nom=".$nom."'/>";
}
?>
<a href='Event.php'>Torna als Esdeveniments.</a>
</body>
</html>