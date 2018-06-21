<html>
<head>
  <title>Pinyator</title>
</head>
<body>
<?php
$id = intval($_POST["id"]);
$nom = strval($_POST["nom"]);
$data = $_POST["data"];
$hora = $_POST["hora"];
$tipus = intval($_POST["tipus"]);
$estat = intval($_POST["estat"]);
$pare = intval($_POST["eventpareid"]);
$esplantilla = 0;
if (!empty($_POST["esplantilla"]))
	$esplantilla = intval($_POST["esplantilla"]);


include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

if ($id > 0)
{
	$sql="UPDATE EVENT SET NOM='".$nom."',DATA='".GetFormatedDate($data,$hora)."',TIPUS=".$tipus.",ESTAT=".$estat.",EVENT_PARE_ID=".$pare.",ESPLANTILLA=".$esplantilla." 
	WHERE EVENT_ID = ".$id.";";
	$sql=$sql." UPDATE EVENT SET ESTAT=".$estat." WHERE EVENT_PARE_ID = ".$id.";";
}
else
{
	$sql="INSERT INTO EVENT(NOM, DATA, TIPUS, ESTAT, EVENT_PARE_ID, ESPLANTILLA) VALUES('".$nom."','".GetFormatedDate($data,$hora)."',".$tipus.",".$estat.",".$pare.",".$esplantilla.");";
}

if (mysqli_multi_query($conn, $sql)) 
{	
	echo "<meta http-equiv='refresh' content='0; url=Event.php'/>";
} 
else if (mysqli_error($conn) != "")
{
	echo $id.";".$nom.";".$data;
	echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);


function GetFormatedDate($date, $time, $format = 'YmdHis')
{
	$combinedDT = date('Y-m-d H:i:s', strtotime("$date $time"));
    $d = new DateTime($combinedDT);
    return $d->format($format);
}

?>
<a href='Event.php'>Torna als Esdeveniments.</a>
</body>
</html>