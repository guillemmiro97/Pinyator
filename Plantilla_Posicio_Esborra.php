<?php
$id = intval($_GET['id']);
$cs = intval($_GET['cs']);

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

if ($cs > 0)
{
	$sql="DELETE FROM PLANTILLA_POSICIO WHERE PLANTILLA_ID = ".$id." AND CASELLA_ID=".$cs;

	if (mysqli_query($conn, $sql)) 
	{
		//echo "New record created successfully";
		if ($id > 0)
		{		
			echo $id;
		}
	} 
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
}
mysqli_close($conn);
?>