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
  <script src="llibreria/inscripcio.js?v=1.6"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<br>
<script>
$(document).unload = function(){window.location.reload();};

</script>
<body style='background-color:#cce6ff;'>
<div style='position: fixed; z-index: -1; width: 90%; height: 80%;background-image: url("icons/Logo_Marrecs.gif");background-repeat: no-repeat; 
background-attachment: fixed;  background-position: center; opacity:0.4'>
</div>

<?php
	$topLlista = 60;

	include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";
	
	$visualitzarFites = 0;
	$visualitzarPenya = 0;
				
	$sql="SELECT FITES, PARTICIPANTS, PERCENATGEASSISTENCIA
	FROM CONFIGURACIO";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result))
		{
			$visualitzarFites = $row["FITES"];
			$visualitzarPenya = $row["PARTICIPANTS"];
			$visualitzarPercentAssistecia = $row["PERCENATGEASSISTENCIA"];			
		}
	}
?>

<div style='position:absolute;'><a href="Apuntat.php?reset=1" class="boto" >No sóc jo</a>
<?php
/***+++++++++++ DOCUMENTACIÓ +++++++++++***/
	$topLlista = 100;
	echo "<div class='dot' style='position:absolute; left:0px; top:38px; width:52; height:52; box-shadow: 0px 3px #888888;'>
			<a href='Documentacio_Llista.php'>
				<img src='icons/Logo_Colla.gif' width=48 height=48>
			</a>
		</div>";
	if ($visualitzarFites)
	{
		/***+++++++++++ TROFEU FITES +++++++++++***/
		echo "<div style='position: absolute; left: 60px; top: 40px;'><a href='Fites_Llista.php'>
				<img src='icons/trofeo.png' width=48 height=48>
			</a></div>";
	}
?>
</div>
<div style="position:absolute; right:0px; top:4px;">
<?php
    $eventId=0;
	$hashtag="";
	$hasHash=0;

	$sql="SELECT EVENT_ID, HASHTAG 
	FROM EVENT
	WHERE CONTADOR=1
	AND ESTAT=1
	ORDER BY DATA LIMIT 1";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		$row = mysqli_fetch_assoc($result);
		$eventId = $row["EVENT_ID"];
		$hashtag = $row["HASHTAG"];
		if(strpos($hashtag, '#') !== false)
		{
			$hashtag=str_replace("#", "", $hashtag);
			$hasHash=1;
		}
	}

	echo "<iframe src='Counter.php?id=".$eventId."&h=".$hashtag."&hh=".$hasHash."' class='counterframe' id='counterCastellers'></iframe>";
?>
</div>

<?php
if ((!empty($_GET['id'])) && (isset($_COOKIE[$cookie_name])))
{
	$Casteller_uuid = strval($_GET['id']);
	$Casteller_id=0;
	$malnom="";
	$malnomPrincipal="";
	$percentatgeAssistencia=0;

	
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
			$Casteller_id = $row["CASTELLER_ID"];
		}
	}
	
	echo "<div style='position: absolute; right: 6px; left: 6px; top:".$topLlista."px;'>";
	echo "<h2>".$malnom."</h2>";

	/***+++++++++++ ESTRELLES +++++++++++***/
	
	if ($visualitzarPercentAssistecia)
	{			
		echo "<a href='inscripcio_LlistaPercentatge.php?id=".$Casteller_uuid."'>";
		
		$sql="SELECT COUNT(E.ESTAT) AS NUM, COUNT(I.CASTELLER_ID) AS CAST
		FROM EVENT E
		JOIN CONFIGURACIO C ON C.TEMPORADA=E.TEMPORADA
		LEFT JOIN INSCRITS I ON E.EVENT_ID=I.EVENT_ID 
			AND I.CASTELLER_ID = ".$Casteller_id." AND I.ESTAT>0
		WHERE E.TIPUS=0
		AND E.ESTAT IN (1,2)";

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) 
		{
			while($row = mysqli_fetch_assoc($result))
			{
				if ($row["NUM"]>0)
				{
					$percentatgeAssistencia = intval(($row["CAST"]/$row["NUM"])*100);
					echo "<script>DesaAssistenciaCasteller(".$row["NUM"].", ".$row["CAST"].")</script>";
				}
			}
		}

		echo "<div style='width:200px; height:20px' title='".$percentatgeAssistencia."'>";
		$left = 0;
		
		for ($x = 0; $x < 10; $x++)
		{		
			StarOff($left);
			$starvisible = "display:none;";
			if(($x*10) < $percentatgeAssistencia)
			{
				$starvisible = "";
			}
						
			if (($percentatgeAssistencia-($x*10))>5)
			{
				$mig = "";
			}
			else
			{
				$mig = "width:14px;";
			}
			echo "<div id='star".$x."' style='position:absolute; left:".$left.";".$mig."overflow:hidden;".$starvisible."'><span class='fa fa-star starOn' style='font-size:30px'></span></div>";				
		

			$left += 30;			
		}
		echo "</div>";
		echo "</a>";
	}
	
	/***+++++++++++ LLISTAT +++++++++++***/
	
	echo "<h3>Llista esdeveniments disponibles:</h3>";
	
	$Casteller_id_taula = $Casteller_id;
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
			$Casteller_id_taula = $row["CASTELLER_ID"];
			include "$_SERVER[DOCUMENT_ROOT]/pinyator/Inscripcio_taula.php";
		}
	}
	echo "</div>";
	mysqli_close($conn);
}
else
{
	echo "<meta http-equiv='refresh' content='0; url=Apuntat.php'/>";	
}

function StarOff($left)
{
	echo "<div style='position:absolute; left:".$left."'><span class='fa fa-star starOff' style='font-size:30px'></span></div>";
}
	
?>
   </body>
</html>