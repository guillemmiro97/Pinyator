<?php
$event_id = intval($_GET["e"]);
$casteller_id = strval($_GET["c"]);
$estat=0;

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

if (isset($_GET["s"])) 
{
	$estat = intval($_GET["s"]);	

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
}
else if (isset($_GET["a"])) 
{
	$acompanyants = intval($_GET["a"]);	

	$sql="UPDATE INSCRITS SET ACOMPANYANTS=".$acompanyants." WHERE CASTELLER_ID=".$casteller_id." AND EVENT_ID = ".$event_id;

	mysqli_query($conn, $sql);

	if (mysqli_affected_rows($conn) == 0)
	{
		$sql="INSERT IGNORE INTO INSCRITS(EVENT_ID,CASTELLER_ID,ESTAT,ACOMPANYANTS) VALUES (".$event_id.",".$casteller_id.",".$estat.",".$acompanyants.")";
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
}

mysqli_close($conn);

?>
