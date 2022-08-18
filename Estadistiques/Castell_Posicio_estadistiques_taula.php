<html>
<head>
</head>
<link rel="stylesheet" href="../Style_Custom.css">
<body>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";?>

<?php
$temporada=strval($_GET["t"]);
$Castell_nom=strval($_GET["id"]);
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
	
	$posicions=array();
	$posicio="";
	$cordo=-1;	
	
	$sql="SELECT *, COUNT(*) AS CNT
	FROM (SELECT P.NOM AS POSICIO, 
	CASE WHEN P.ESCORDO=1 THEN CP.CORDO ELSE 0 END AS CORDO,
	CT.MALNOM AS CASTELLER
	FROM CASTELL C
	JOIN CASTELL_POSICIO AS CP ON CP.CASTELL_ID = C.CASTELL_ID
	JOIN POSICIO P ON P.POSICIO_ID = CP.POSICIO_ID
	JOIN CASTELLER CT ON CT.CASTELLER_ID = CP.CASTELLER_ID
	JOIN EVENT E ON E.EVENT_ID = C.EVENT_ID
	WHERE C.NOM = '".$Castell_nom."'
	AND (E.TEMPORADA = '".$temporada."' OR '0' = '".$temporada."') 
	AND (P.ESTRONC = 1 OR P.ESNUCLI = 1 OR P.ESCORDO = 1 OR P.ESFOLRE = 1)	
	ORDER BY P.NOM, CORDO, CT.MALNOM) T1
	GROUP BY POSICIO, CORDO, CASTELLER";
	
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
				$objPosicio->Castellers = array();
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
			<th class='llistes'>POSICIÓ</th>
			<th class='llistes'>CORDO</th>
			<th class='llistes'>CASTELLERS</th>";

	echo "</tr>";

	foreach($posicions as $pos) 
	{
		echo "<tr class='llistes'>
				<td class='llistes'>".$pos->Nom."</td>
				<td class='llistes'>".$pos->Cordo."</td>";

		$separado_por_comas="";
		foreach($pos->Castellers as $key => $value)
		{
			$separado_por_comas = $separado_por_comas.$key."(".$value."), ";
		}
		echo "<td class='llistes'>".$separado_por_comas."</td>";
		
		echo "</tr>";
	}
	echo "</table>";
}

?>	
</body>
</html>