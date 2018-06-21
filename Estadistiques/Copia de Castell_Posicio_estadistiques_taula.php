<html>
<head>
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body>
<?php include "Connexio.php";?>

<?php
$Castell_nom="2d8f";
if (!empty($Castell_nom))
{
	
	class Posicio 
	{ 
		public $Castellers=array();
		public $Nom = ""; 
		public $Cordo = 0;

		public function __construct()
		{
			$this->Castellers = array();
			$this->Nom = "";
			$this->Cordo = 0;
		}		
	}
	
	$castellers=array();
	
	//Primer carreguem tots els possibles castellers
	$sql="SELECT DISTINCT CT.MALNOM
	FROM CASTELL C
	JOIN CASTELL_POSICIO AS CP ON CP.CASTELL_ID = C.CASTELL_ID
	JOIN CASTELLER CT ON CT.CASTELLER_ID = CP.CASTELLER_ID
	WHERE C.NOM='".$Castell_nom."'
	ORDER BY CT.NOM";
	
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$castellers[$row["MALNOM"]] = 0;
		}
	}	
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
	
	$posicions=array();
	$posicio="";
	$cordo=-1;	
	
	$sql="SELECT P.NOM AS POSICIO, CP.CORDO,
	CT.NOM AS CASTELLER, COUNT(*) AS CNT
	FROM CASTELL C
	JOIN CASTELL_POSICIO AS CP ON CP.CASTELL_ID = C.CASTELL_ID
	JOIN POSICIO P ON P.POSICIO_ID = CP.POSICIO_ID
	JOIN CASTELLER CT ON CT.CASTELLER_ID = CP.CASTELLER_ID
	WHERE C.NOM='".$Castell_nom."'
	GROUP BY P.NOM, CP.CORDO, CT.NOM
	ORDER BY P.NOM, CP.CORDO, CT.NOM";
	
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result)) 
		{
			if (($posicio != $row["POSICIO"]) || ($cordo != $row["CORDO"]))
			{
				$posicio=$row["POSICIO"];
				$cordo=$row["CORDO"];

				$objPosicio = new Posicio();
				$objPosicio->Castellers = new ArrayObject($castellers);
				$objPosicio->Nom = $posicio;
				$objPosicio->Cordo = $cordo;				
				
				array_push($posicions, $objPosicio);				
			}
			$objPosicio->Castellers[$row["CASTELLER"]] = $row["CNT"];
		}	
	}
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
	else
	{
		echo "<br>Sense dades";
	}
	
	//Printem la taula
	echo "<table>";
	echo "<tr class='llistes'>
			<th>POSICIÓ</th>
			<th>CORDO</th>
			<th>CASTELLERS</th>";

	echo "</tr>";

	foreach($posicions as $pos) 
	{
		echo "<tr class='llistes'>
				<td>".$pos->Nom."</td>
				<td>".$pos->Cordo."</td>";


		$cast = new ArrayObject($pos->Castellers);
		$separado_por_comas = implode(",", (array)$cast);

		echo "<td>".$separado_por_comas."</td>";
		
		echo "</tr>";
	}
	echo "</table>";
}

?>	
</body>
</html>