<html>
<head>
  <title>Pinyator</title>
</head>
<body>
<?php
$id = intval($_POST["id"]);
$nom = strval($_POST["nom"]);

if(!empty($_POST["esnucli"]))
	$esnucli = intval($_POST["esnucli"]);
else
	$esnucli = 0;

if(!empty($_POST["escordo"]))
	$escordo = intval($_POST["escordo"]);
else
	$escordo = 0;

if(!empty($_POST["estronc"]))
	$estronc = intval($_POST["estronc"]);
else
	$estronc = 0;

if(!empty($_POST["esfolre"]))
	$esfolre = intval($_POST["esfolre"]);
else
	$esfolre = 0;

if(!empty($_POST["escanalla"]))
	$escanalla = intval($_POST["escanalla"]);
else
	$escanalla = 0;

if(!empty($_POST["colorfons"]))
	$colorfons = strval($_POST["colorfons"]);
else
	$colorfons = "#BDBDBD";

if(!empty($_POST["colortext"]))
	$colortext = strval($_POST["colortext"]);
else
	$colortext = "#FFFFFF";

if(!empty($_POST["colorcamisa"]))
	$colorcamisa = strval($_POST["colorcamisa"]);
else
	$colorcamisa = "#FFFFFF";

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

if ($id > 0)
{
	$sql="UPDATE POSICIO SET NOM='".$nom."',ESNUCLI=".$esnucli.",ESCORDO=".$escordo.
	",ESTRONC=".$estronc.",ESFOLRE=".$esfolre.",ESCANALLA=".$escanalla.
	",COLORFONS='".$colorfons."',COLORTEXT='".$colortext."',COLORCAMISA='".$colorcamisa."' 
	 WHERE POSICIO_ID = ".$id;
}
else
{
	$sql="INSERT INTO POSICIO(NOM, ESNUCLI, ESCORDO, ESTRONC, ESFOLRE, ESCANALLA, COLORFONS, COLORTEXT, COLORCAMISA) 
	VALUES('".$nom."',".$esnucli.",".$escordo.",".$estronc.",".$esfolre.",".$escanalla.",'".$colorfons."','".$colortext."','".$colorcamisa."')";
}

if (mysqli_query($conn, $sql)) 
{	
	echo "<meta http-equiv='refresh' content='0; url=Posicio.php'/>";
} 
else if (mysqli_error($conn) != "")
{
	echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);

?>
<a href='Posicio.php'>Torna a les posicions.</a>
</body>
</html>