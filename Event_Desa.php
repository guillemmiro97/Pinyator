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
$hashtag = strval($_POST["hashtag"]);
$temporada = strval($_POST["temporada"]);
$max_participants = strval($_POST["max_participants"]);
$max_acompanyants = strval($_POST["max_acompanyants"]);
$observacions = strval($_POST["observacions"]);
$esplantilla = 0;
$contador = 0;

if (!empty($_POST["esplantilla"]))
{
	$esplantilla = intval($_POST["esplantilla"]);
}
if (!empty($_POST["escontador"]))
{
	$contador = intval($_POST["escontador"]);
}

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

$nom = GetStrDB($nom);
$hashtag = GetStrDB($hashtag);
$temporada = GetStrDB($temporada);

if ($id > 0)
{
	$sql="UPDATE EVENT SET NOM='".$nom."',DATA='".GetFormatedDateTime($data,$hora)."',TIPUS=".$tipus.",ESTAT=".$estat.",EVENT_PARE_ID=".$pare."
	,ESPLANTILLA=".$esplantilla.",CONTADOR=".$contador.",HASHTAG='".$hashtag."',TEMPORADA='".$temporada."'
	,MAX_PARTICIPANTS=".$max_participants.",MAX_ACOMPANYANTS=".$max_acompanyants.",OBSERVACIONS='".$observacions."'  
	WHERE EVENT_ID = ".$id.";";
	$sql=$sql." UPDATE EVENT SET ESTAT=".$estat." WHERE EVENT_PARE_ID = ".$id.";";
}
else
{
	$sql="INSERT INTO EVENT(NOM,DATA,TIPUS,ESTAT,EVENT_PARE_ID,ESPLANTILLA,
		CONTADOR,HASHTAG,TEMPORADA,MAX_PARTICIPANTS,MAX_ACOMPANYANTS,OBSERVACIONS) 
		VALUES('".$nom."','".GetFormatedDateTime($data,$hora)."',".$tipus.",".$estat.",".$pare.",".$esplantilla.",
		".$contador.",'".$hashtag."','".$temporada."',".$max_participants.",".$max_acompanyants.",'".$observacions."');";
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


?>
<a href='Event.php'>Torna als Esdeveniments.</a>
</body>
</html>