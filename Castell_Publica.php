<?php
header("Content-Type: application/json; charset=UTF-8");

$id = intval($_GET["id"]);

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

if ($id > 0)
{
	$sql="UPDATE CASTELL SET PUBLIC = NOT PUBLIC
	WHERE CASTELL_ID = ".$id;

	if (mysqli_query($conn, $sql)) 
	{	
		
	} 
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
}
mysqli_close($conn);

?>