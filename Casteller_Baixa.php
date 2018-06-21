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
	$sql="UPDATE CASTELLER SET ESTAT=2 WHERE CASTELLER_ID = ".$id;

	if (mysqli_query($conn, $sql)) 
	{	
		echo "<meta http-equiv='refresh' content='0; url=Casteller.php'/>";
	} 
	else if (mysqli_error($conn) != "")
	{
		echo $id.";".$nom.";".$data;
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
}
mysqli_close($conn);

?>
<a href='Casteller.php'>Torna als Esdeveniments.</a>
</body>
</html>