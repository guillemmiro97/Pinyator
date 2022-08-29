<html>
<head>
    <title>Pinyator</title>
</head>
<body>
<?php
$id = intval($_GET["id"]);

$url = $_SERVER["REQUEST_URI"];
$url_components = parse_url($url);
parse_str($url_components['query'], $params);
$nomCasteller = $params['nom'];
$eventID = $params['eventID'];

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

if ($id > 0)
{
    $sql="DELETE FROM EVENT_COMENTARIS WHERE ID=".$id.";";
}

if (mysqli_multi_query($conn, $sql))
{
    echo "<meta http-equiv='refresh' content='0; url=Event_Comentari_Public.php?id=".$eventID."&nom=".$nomCasteller."'/>";
}
else if (mysqli_error($conn) != "")
{
    echo "Error: " . $sql . "<br>" . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);

?>
<a href='Event.php'>Torna als Esdeveniments.</a>
</body>
</html>