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
  <script src="llibreria/inscripcio.js?v=1.4"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
.tblRanking 
{
  font-family: arial, sans-serif;
  border-collapse: separate;
  width: 100%;
  border-spacing: 0 4px;
}

td
{
  font-size:18px;
}


</style>
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<br>
<body style='background-color:#cce6ff;'>
<div style='position: fixed; z-index: -1; width: 90%; height: 80%;background-image: url("icons/Logo_Marrecs.gif");background-repeat: no-repeat; 
background-attachment: fixed;  background-position: center; opacity:0.4'>
</div>

<h2 style='text-align:center'>
	RANKING
</h2>
<p style='text-align:center'>
	<span class='fa fa-star starTitleBlue'></span>
	<span class='fa fa-star starTitleOrange'></span>
	<span class='fa fa-star starTitleBlue'></span>
	<span class='fa fa-star starTitleOrange'></span>
	<span class='fa fa-star starTitleBlue'></span>	
</p>

<?php

if ((!empty($_GET['id'])) && (isset($_COOKIE[$cookie_name])))
{
	class Ranking
	{
		public $malnom = '';
		public $rati = '';
		public $codi = '';
		public $posicio = '';
	}
	
	
	
	$Casteller_uuid = strval($_GET['id']);
	
	include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";
	
	$sql="SELECT PERCENATGEASSISTENCIA
	FROM CONFIGURACIO";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result))
		{
			$visualitzarPercentAssistecia = $row["PERCENATGEASSISTENCIA"];			
		}
	}
	if ($visualitzarPercentAssistecia)
	{
		$assajos = 0;
		
		$sql="SELECT COUNT(*) AS NUM
		FROM EVENT E
		JOIN CONFIGURACIO C ON C.TEMPORADA=E.TEMPORADA
		WHERE E.TIPUS=0
		AND E.ESTAT IN (1,2)";

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) 
		{
			while($row = mysqli_fetch_assoc($result))
			{
				$assajos = $row["NUM"];
			}
		}
		
		if ($assajos>0)
		{
			$ranking=array();			
			
			$sql="SELECT COUNT(I.CASTELLER_ID) AS CAST,
			CE.MALNOM, CE.CODI
			FROM EVENT E
			JOIN CONFIGURACIO C ON C.TEMPORADA=E.TEMPORADA
			JOIN INSCRITS I ON E.EVENT_ID=I.EVENT_ID AND I.ESTAT>0
			JOIN CASTELLER CE ON CE.CASTELLER_ID=I.CASTELLER_ID
			WHERE E.TIPUS=0
			AND E.ESTAT IN (1,2)
			AND CE.ESTAT=1
			GROUP BY CE.MALNOM
			ORDER BY 1 DESC, CE.MALNOM";

			$result = mysqli_query($conn, $sql);

			if (mysqli_num_rows($result) > 0) 
			{
				echo "<table class=tblRanking>";
				$posicio = 0;
				$percAnt = 0;
				while($row = mysqli_fetch_assoc($result))
				{
					$percentatgeAssistencia = intval(($row["CAST"]/$assajos)*100);
					if($percentatgeAssistencia != $percAnt)
					{
						if ($posicio > 0)
							array_push($ranking, $casteller);
						$percAnt = $percentatgeAssistencia;
						$posicio++;
						$casteller=array();						
					}
					
					$r = new Ranking();
					$r->malnom =$row["MALNOM"];
					$r->rati = $percentatgeAssistencia;
					$r->codi = $row["CODI"];
					$r->posicio = $posicio;
					array_push($casteller, $r);
				}
				
				for($x = 0; $x < count($ranking); $x++)
				{
					$posicio = $x+1;
					$casteller = $ranking[$x];
					
					$rowspan = "rowspan='".count($casteller)."'";
					$posColor = "style='background-color:#F2F2F2;text-align:center;'";
					$star="";
					if ($posicio==1)
					{
						$star = "<span class='fa fa-star' style='color:gold'></span>";
					}
					if ($posicio==2)
					{
						$star = "<span class='fa fa-star' style='color:silver'></span>";
					}
					if ($posicio==3)
					{
						$star = "<span class='fa fa-star' style='color:#B87333'></span>";
					}
					
					for($y = 0; $y < count($casteller); $y++)
					{
						$r = $casteller[$y];
						$malnom = $r->malnom;
						$percentatgeAssistencia = $r->rati;
						$codi = $r->codi;
						$posicio = $r->posicio;
					
						$malnomStyle = "style='padding-left:8px;'";
					
						if ($Casteller_uuid == $codi)
						{
							$malnomStyle = "style='padding-left:8px;background-color:red;color:white;'";
						}
					
						echo "<tr>";

						if ($rowspan != "")
						{
							echo "<td ".$rowspan." ".$posColor.">".$posicio.$star."</td>";
						}							
						echo "<td ".$malnomStyle .">".$malnom."</td>";
						if ($rowspan != "")
						{
							echo "<td ".$rowspan." ".$posColor.">".$percentatgeAssistencia."</td>";
						}
						echo "</tr>";
						$rowspan="";
						$posColor="";
					}
				}
				echo "</table>";
			}
		}
		echo  "";
	}
}
?>

   </body>
</html>