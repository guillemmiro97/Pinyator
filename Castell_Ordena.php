<?php

$sql="";

if(!empty($_GET["id"]))
{
	$id = intval($_GET["id"]);
}

if(!empty($_GET["e"]))
{
	$event = intval($_GET["e"]);
}

if(!empty($_GET["o"]))
{
	$ordre = intval($_GET["o"]);
}


include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

if ($id > 0)
{
	$sql="UPDATE CASTELL SET ORDRE=".$ordre." WHERE EVENT_ID=".$event." AND CASTELL_ID = ".$id;
	if (mysqli_query($conn, $sql)) 
	{
	}
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		$sql="";
	}
}

mysqli_close($conn);

?>
