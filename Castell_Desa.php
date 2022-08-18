<html>
<head>
  <title>Pinyator</title>
</head>
<body>
<?php
$id=-1;
$accio=0;
$sql="";

if(!empty($_GET["id"]))
{
	$id = intval($_GET["id"]);
}

if(!empty($_GET["id"]))
{
	$accio = intval($_GET["a"]);
}

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

if ($id > 0)
{
	if ($accio=1)
	{
		$sql="DELETE FROM CASTELL_POSICIO WHERE CASTELL_ID = ".$id;
		if (mysqli_query($conn, $sql)) 
		{
			$sql="DELETE FROM CASTELL WHERE CASTELL_ID = ".$id;
		}
		else if (mysqli_error($conn) != "")
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			$sql="";
		}
	}
	else
	{
	}
}

if (mysqli_query($conn, $sql)) 
{	
	echo "<meta http-equiv='refresh' content='0; url=".$_SERVER['HTTP_REFERER']."'/>";
} 
else if (mysqli_error($conn) != "")
{
	echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);

?>
<a href='Castell.php'>Torna als Castells.</a>
</body>
</html>