<?php
if (!empty($_GET['id']))
{
	$cookie_name = "marrec_inscripcio";
	$cookie_value = strval($_GET['id']);
	if((isset($_COOKIE[$cookie_name])) && ($_COOKIE[$cookie_name] != $cookie_value)) 
	{		
		unset($_COOKIE[$cookie_name]);	
		setcookie($cookie_name, $cookie_value, -1, "/"); // 86400 = 1 day
	}
	else
	{
		setcookie($cookie_name, $cookie_value, time() + (86400 * 320), "/"); // 86400 = 1 day	
	}
}
?>

<html>
<head>
  <title>Pinyator</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="apple-touch-icon" sizes="111x192" href="icons\logo192.png">
  <link rel="icon" sizes="111x192" href="icons\logo192.png">
  <script src="llibreria/inscripcio.js?v=1.1"></script>
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<br>
<body style='background-color:#cce6ff;'>
<div style='position: fixed; z-index: -1; width: 90%; height: 80%;background-image: url("icons/Logo_Marrecs.gif");background-repeat: no-repeat; 
background-attachment: fixed;  background-position: center; opacity:0.4'>
</div>
<div>

<a href="Apuntat.php?reset=1" class="boto" >No sóc jo</a>
<div style="position: absolute; right: 0px; top: 4px">
<iframe src="Counter.php" class="counterframe" id="counterCastellers"></iframe>
</div>
<?php
if ((!empty($_GET['id'])) && (isset($_COOKIE[$cookie_name])))
{
	$Casteller_uuid = strval($_GET['id']);
	$Casteller_id=0;
	$malnom="";
	$malnomPrincipal="";
	
	include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

	$sql="SELECT C.MALNOM, C.CASTELLER_ID 
	FROM CASTELLER AS C
	WHERE C.CODI='".$Casteller_uuid."'";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$malnom=$row["MALNOM"];
			$malnomPrincipal=$row["MALNOM"];
			echo "<h2>".$malnom."</h2>";
			$Casteller_id = $row["CASTELLER_ID"];
		}
	}
	echo "<h3>Llista esdeveniments disponibles:</h3>";
	
	include "$_SERVER[DOCUMENT_ROOT]/pinyator/Inscripcio_taula.php";
	
	$sql="SELECT DISTINCT C.CODI, C.MALNOM, C.CASTELLER_ID
	FROM CASTELLER AS CR
	INNER JOIN CASTELLER AS C ON C.FAMILIA_ID = CR.CASTELLER_ID OR C.FAMILIA2_ID = CR.CASTELLER_ID
	WHERE CR.CODI='".$Casteller_uuid."'
	ORDER BY C.MALNOM";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$malnom = $row["MALNOM"];
			echo "<h3>".$malnom."</h3>";
			$Casteller_id = $row["CASTELLER_ID"];
			include "$_SERVER[DOCUMENT_ROOT]/pinyator/Inscripcio_taula.php";
		}
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

