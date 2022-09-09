<?php
$casteller_id = intval($_GET["c"]);
$estat  = 0;

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

$sql="UPDATE INSCRITS SET ESTAT=".$estat." WHERE CASTELLER_ID=".$casteller_id.";";
mysqli_query($conn, $sql);

mysqli_close($conn);

?>
