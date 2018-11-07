<html>
<head>
  <title>Pinyator - Actuació</title>
  <meta charset="utf-8">
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body style='background-color:#cce6ff;'>
<div style='position: fixed; z-index: -1; width: 90%; height: 80%;background-image: url("icons/Logo_Marrecs.gif");background-repeat: no-repeat; 
background-attachment: fixed;  background-position: center; opacity:0.4'>
</div>
<div>

<?php 
$cookie_name = "marrec_inscripcio";
if ((!empty($_GET['id'])) && (isset($_COOKIE[$cookie_name])))
{

	include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

	$malnom = "";
	$sql="SELECT C.MALNOM
	FROM CASTELLER AS C
	WHERE C.CODI='".$_COOKIE[$cookie_name]."'";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		$row = mysqli_fetch_assoc($result);
		$malnom = $row["MALNOM"];
	}
	
	$eventId=0;
	if (!empty($_GET["id"]))
	{	
		$eventId=intval($_GET["id"]);
	}
	
	
	$sql="SELECT CASTELL_ID, C.NOM, E.NOM AS EVENT_NOM
	FROM CASTELL AS C
	INNER JOIN EVENT AS E ON E.EVENT_ID=C.EVENT_ID
	WHERE C.EVENT_ID=".$eventId."
	AND C.PUBLIC = 1
	ORDER BY C.ORDRE";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		$row = mysqli_fetch_assoc($result);
		echo "<h3>".$row["EVENT_NOM"]."</h3><br>";
		mysqli_data_seek($result, 0);
		
		// output data of each row
		while($row = mysqli_fetch_assoc($result)) 
		{
			echo "<li><a href='Castell_Buscat.php?id=".$row["CASTELL_ID"]."&malnom=".$malnom."'><b>".$row["NOM"]."</b></a></li><br>";
		}
	}
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}

	mysqli_close($conn);
}
else
{
	echo "<meta http-equiv='refresh' content='0; url=Apuntat.php'/>";	
}
?>	  
</div>
   </body> 
</html>