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
	class Event
	{ 
		public $Castells=array();
		public $Nom = ""; 
		public $Id = 0;

		public function __construct()
		{
			$this->Castells = array();
			$this->Nom = "";
			$this->Id = 0;
		}		
	}
	
	$Events=array();

	$sql="SELECT CASTELL_ID, C.NOM, E.NOM AS EVENT,	C.EVENT_ID AS EVENT_ID
	FROM CASTELL C
	INNER JOIN EVENT AS E ON E.EVENT_ID=C.EVENT_ID
	WHERE C.NOM='".$Castell_nom."'
	AND (E.TEMPORADA = '".$temporada."' OR '0' = '".$temporada."')
	ORDER BY E.DATA";
	
	
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		$anterior = 0;
		while($row = mysqli_fetch_assoc($result)) 
		{
			$event = $row["EVENT_ID"];
			if ($event != $anterior)
			{
				$objEvent = new Event();
				$objEvent->Castells = array();
				$objEvent->Nom = $row["EVENT"];
				$objEvent->Id = $row["EVENT_ID"];				
				
				array_push($Events, $objEvent);				
			}
			$objEvent->Castells[$row["CASTELL_ID"]] = $row["NOM"];
			
			$anterior = $event;
		}	
	}
	
	//Printem la taula
	echo "<table class='llistes'>";
	echo "<tr class='llistes'>
			<th class='llistes'>Event</th>
			<th class='llistes'>Castell</th>
		</tr>";

	foreach($Events as $evnt) 
	{
		echo "<tr class='llistes'>
				<td class='llistes' rowspan=".count($evnt->Castells)."><a href='..\Castell.php?id=".$evnt->Id."' target='_blank'>".$evnt->Nom."</a></td>";
			
		foreach($evnt->Castells as $key => $value)
		{
			echo "<td class='llistes'><a href='..\Castell_Fitxa.php?id=".$key."' target='_blank'>".$value."</a></td>";
			if (count($evnt->Castells) > 1)
			{
				echo "</tr>";
			}
		}
		
		echo "</tr>";
	}
	echo "</table>";
}

?>	
</body>
</html>