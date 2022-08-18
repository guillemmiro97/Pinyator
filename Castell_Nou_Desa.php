<html>
<head>
  <title>Pinyator</title>
</head>
<body>

<?php
$plantillaid = intval($_POST['plantillaid']);
$eventid = intval($_POST['eventid']);
$castellCopyId = intval($_POST['castellid']);

$event="";
if (!empty($_POST["id"]))
{	
	$event="?id=".strval($_POST["id"]);
}

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

if ($plantillaid > 0)
{
	$sql="INSERT INTO CASTELL(NOM,EVENT_ID,W,H,PESTANYA_1,PESTANYA_2,PESTANYA_3,PESTANYA_4, ORDRE, PUBLIC) 
	SELECT NOM,".$eventid.",W,H,PESTANYA_1,PESTANYA_2,PESTANYA_3,PESTANYA_4,
	(SELECT COUNT(*)+1 FROM CASTELL WHERE EVENT_ID=".$eventid.") AS ORDRE, 0
	FROM PLANTILLA
	WHERE PLANTILLA_ID=".$plantillaid;
}
else if ($castellCopyId > 0)
{
	$sql="INSERT INTO CASTELL(NOM,EVENT_ID,W,H,PESTANYA_1,PESTANYA_2,PESTANYA_3,PESTANYA_4, ORDRE, PUBLIC) 
	SELECT NOM,".$eventid.",W,H,PESTANYA_1,PESTANYA_2,PESTANYA_3,PESTANYA_4,
	(SELECT COUNT(*)+1 FROM CASTELL WHERE EVENT_ID=".$eventid.") AS ORDRE, 0
	FROM CASTELL
	WHERE CASTELL_ID=".$castellCopyId;
}

if (($plantillaid > 0) && ($castellCopyId > 0))
{
	echo "Has seleccionat una plantilla i un casetell d'un esdeveniment per copiar, i com es normal el sistema no sap quin es el bo, encara no sap llegir la ment :P <br>";
}
else if (mysqli_query($conn, $sql)) 
{
	$castellId = mysqli_insert_id($conn);

	if ($plantillaid > 0)
	{
		$sql="INSERT INTO CASTELL_POSICIO(CASTELL_ID,CASELLA_ID,PESTANYA,CASTELLER_ID,POSICIO_ID,CORDO,X,Y,H,W,ANGLE,FORMA,TEXT,LINKAT,SEGUENT) 
		SELECT ".$castellId.",CASELLA_ID,PESTANYA,0,POSICIO_ID,CORDO,X,Y,H,W,ANGLE,FORMA,TEXT,LINKAT,SEGUENT
		FROM PLANTILLA_POSICIO
		WHERE PLANTILLA_ID=".$plantillaid;
	}
	else if ($castellCopyId > 0)
	{
		$sql="INSERT INTO CASTELL_POSICIO(CASTELL_ID,CASELLA_ID,PESTANYA,POSICIO_ID,CORDO,X,Y,H,W,ANGLE,FORMA,TEXT,LINKAT,SEGUENT,CASTELLER_ID) 
		SELECT ".$castellId.",CASELLA_ID,PESTANYA,POSICIO_ID,CORDO,X,Y,H,W,ANGLE,FORMA,TEXT,LINKAT,SEGUENT,CASTELLER_ID
		FROM CASTELL_POSICIO
		WHERE CASTELL_ID=".$castellCopyId;	
	}
	
	if (mysqli_query($conn, $sql)) 
	{
		echo "<meta http-equiv='refresh' content='0; url=Castell.php".$event."'/>";
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
<a href='Castell.php'>Torna als Castells.</a>
</body>
</html>