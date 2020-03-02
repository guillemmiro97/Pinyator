<html>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body>
<?php

if (!empty($_GET['id']))
{	
	$Casteller_id = intval($_GET['id']);
}
$temporada = "";
if (!empty($_GET['t']))
{	
	$temporada = "AND E.TEMPORADA = '".strval($_GET['t'])."'";
}

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

if (!empty($Casteller_id))
{
	
	$sql="SELECT COUNT(E.EVENT_ID) AS EVENTS, SUM(IF(I.ESTAT > 0, 1, 0)) AS INSCRITS,
	E.TIPUS	
	FROM EVENT AS E
	LEFT JOIN CASTELLER_INSCRITS AS I ON I.EVENT_ID = E.EVENT_ID AND I.CASTELLER_ID=".$Casteller_id."
	WHERE 1=1
	AND E.TIPUS >= 0
	AND E.ESTAT > 0
	".$temporada."
	GROUP BY E.TIPUS";
	
	$result2 = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result2) > 0) 
	{
		$PosicioId = 0;		
		echo "<table><tr><th>Tipus</th><th>Esdev.</th><th>Inscrit</th></tr>";		
		// output data of each row
		while($row2 = mysqli_fetch_assoc($result2)) 
		{
			if($row2["TIPUS"]==1)
				$tipus="Actuació";
			else
				$tipus="Assaig";
			echo "<tr class='llistes'>
			<td width=100px>".$tipus."</td>
			<td width=100px>".$row2["EVENTS"]."</td>
			<td width=100px>".$row2["INSCRITS"]."</td>
			</tr>";
		}
		echo "</table>";		
	}
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
	else
	{
		echo "<br>Sense dades";
	}
	
	echo "<br><br><b>Assaig</b>";
	
	$sql="SELECT P.NOM, C.NOM AS CASTELL, CP.CORDO, COUNT(*) AS CNT
	FROM CASTELL_POSICIO AS CP
	JOIN CASTELL C ON C.CASTELL_ID = CP.CASTELL_ID
	JOIN EVENT E ON E.EVENT_ID = C.EVENT_ID
	LEFT JOIN POSICIO P ON P.POSICIO_ID = CP.POSICIO_ID
	WHERE CP.CASTELLER_ID=".$Casteller_id."
	AND E.TIPUS = 0
	AND CP.CORDO <= 4
	".$temporada."
	GROUP BY P.NOM, C.NOM, CP.CORDO
	ORDER BY C.NOM, P.NOM, CP.CORDO";
	
	$result2 = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result2) > 0) 
	{
		$PosicioId = 0;		
		echo "<table><tr><th>Castell</th><th>Posició</th><th>Cordo</th><th>TOTAL</th></tr>";		
		// output data of each row
		while($row2 = mysqli_fetch_assoc($result2)) 
		{
			echo "<tr class='llistes'>
			<td width=200px>".$row2["CASTELL"]."</td>
			<td width=200px>".$row2["NOM"]."</td>
			<td width=150px>".$row2["CORDO"]."</td>
			<td width=50px>".$row2["CNT"]."</td>
			
			</tr>";
		}
		echo "</table>";		
	}
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
	else
	{
		echo "<br>Sense dades";
	}
	
	echo "<br><br><b>Actuació</b>";
	
	$sql="SELECT P.NOM, C.NOM AS CASTELL, CP.CORDO, COUNT(*) AS CNT
	FROM CASTELL_POSICIO AS CP
	JOIN CASTELL C ON C.CASTELL_ID = CP.CASTELL_ID
	JOIN EVENT E ON E.EVENT_ID = C.EVENT_ID
	LEFT JOIN POSICIO P ON P.POSICIO_ID = CP.POSICIO_ID
	WHERE CP.CASTELLER_ID=".$Casteller_id."
	AND E.TIPUS = 1
	".$temporada."
	GROUP BY P.NOM, C.NOM, CP.CORDO
	ORDER BY C.NOM, P.NOM, CP.CORDO";
	
	$result2 = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result2) > 0) 
	{
		$PosicioId = 0;		
		echo "<table><tr><th>Castell</th><th>Posició</th><th>Cordo</th><th>TOTAL</th></tr>";		
		// output data of each row
		while($row2 = mysqli_fetch_assoc($result2)) 
		{
			echo "<tr class='llistes'>
			<td width=200px>".$row2["CASTELL"]."</td>
			<td width=200px>".$row2["NOM"]."</td>
			<td width=150px>".$row2["CORDO"]."</td>
			<td width=50px>".$row2["CNT"]."</td>
			
			</tr>";
		}
		echo "</table>";		
	}
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
	else
	{
		echo "<br>Sense dades<br>";
	}
}
?>	
</body>
</html>
