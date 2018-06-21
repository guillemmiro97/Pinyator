<?php
$event_id = intval($_GET["e"]);
$casteller_id = strval($_GET["c"]);
$estat = intval($_GET["s"]);

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

$sql="UPDATE INSCRITS SET ESTAT=".$estat." WHERE CASTELLER_ID=".$casteller_id." AND EVENT_ID = ".$event_id;

mysqli_query($conn, $sql);

if (mysqli_affected_rows($conn) == 0)
{
	$sql="INSERT IGNORE INTO INSCRITS(EVENT_ID,CASTELLER_ID,ESTAT) VALUES (".$event_id.",".$casteller_id.",".$estat.")";
	if (mysqli_query($conn, $sql)) 
	{
	} 
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
} 
else if (mysqli_error($conn) != "")
{
	echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}


mysqli_close($conn);

?>
