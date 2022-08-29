<?php

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";
$nom="";

$url = $_SERVER["REQUEST_URI"];
$url_components = parse_url($url);
parse_str($url_components['query'], $params);
$nomCasteller = $params['nom'];

$sql="SELECT E.NOM
FROM EVENT AS E
WHERE E.EVENT_ID = ".$id;

$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) 
{
	while($row = mysqli_fetch_assoc($result)) 
	{
		echo "<h3>".$row["NOM"]."</h3>";
	}
}

$sql="SELECT EC.ID, EC.USUARI, EC.TEXT, date_format(EC.DATA, '%d/%m/%Y %H:%i') AS DATA
FROM EVENT_COMENTARIS AS EC
LEFT JOIN EVENT AS E ON E.EVENT_ID = EC.EVENT_ID
WHERE E.EVENT_ID = ".$id.
" ORDER BY EC.DATA";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) 
{
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) 
	{
        if(!strcmp($nomCasteller, $row["USUARI"])){
            echo "<label>".$row["USUARI"]." - ".$row["DATA"]."</label>";
            echo "<button class='boto boto_remove' name='Event_Comentari_Esborra.php?id=".$row["ID"]."&eventID=".$id."&nom=".$row["USUARI"]."' onClick='ShowPopupEsborra(this)'><img class='img_boto' src='icons/trash.svg'></button>";
            echo "<pre style='background:lightyellow;white-space: pre-wrap;'>".$row["TEXT"]."</pre>";
            echo "<br>";
        } else {
            echo "<label>".$row["USUARI"]." - ".$row["DATA"]."</label>";
            echo "<pre style='background:lightgreen;white-space: pre-wrap;'>".$row["TEXT"]."</pre>";
            echo "<br>";
        }

    }
}

mysqli_close($conn);
?>

