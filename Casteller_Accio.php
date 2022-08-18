<html>
<head>
  <title>Pinyator</title>
</head>
<body>
<?php
$id = intval($_GET["id"]);
$accio = intval($_GET["a"]);

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

if ($id > 0)
{
	$sql="UPDATE CASTELLER SET ESTAT=".$accio." WHERE CASTELLER_ID = ".$id;

	if (mysqli_query($conn, $sql)) 
	{	
		echo "<meta http-equiv='refresh' content='0; url=".$_SERVER['HTTP_REFERER']."'/>";
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