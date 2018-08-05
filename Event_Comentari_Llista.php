<?php

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";
$nom="";

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

$sql="SELECT EC.USUARI, EC.TEXT, date_format(EC.DATA, '%d/%m/%Y %H:%i') AS DATA
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
		echo "<label>".$row["USUARI"]." - ".$row["DATA"]."</label>";
		echo "<pre style='background:lightblue;white-space: pre-wrap;'>".$row["TEXT"]."</pre>";
		echo "<br>";
    }	
}


mysqli_close($conn);
?>