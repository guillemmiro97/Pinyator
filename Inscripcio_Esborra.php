<?php

$event_id = intval($_GET["e"]);
$casteller_id = strval($_GET["c"]);


include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

if ($event_id > 0)
{
	$sql="DELETE FROM INSCRITS WHERE CASTELLER_ID=".$casteller_id." AND EVENT_ID = ".$event_id;

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
