<html>
<head>
  <title>Pinyator</title>
</head>
<body>
<?php
	$id = intval($_GET["id"]);

	include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";
	
	if ($id > 0)
	{
		$sql="INSERT INTO EVENT(NOM,DATA,TIPUS,ESTAT,EVENT_PARE_ID,ESPLANTILLA,CONTADOR,HASHTAG,
		TEMPORADA,MAX_PARTICIPANTS,MAX_ACOMPANYANTS,OBSERVACIONS)
		SELECT CONCAT('Copia de ',NOM),DATA,TIPUS,ESTAT,EVENT_PARE_ID,ESPLANTILLA,CONTADOR,HASHTAG,
		TEMPORADA,MAX_PARTICIPANTS,MAX_ACOMPANYANTS,OBSERVACIONS
		FROM EVENT WHERE EVENT_ID=".$id;	

		if (mysqli_multi_query($conn, $sql)) 
		{	
			$eventid=GetLastId($conn);
			$sql="SELECT CASTELL_ID
				FROM CASTELL
				WHERE EVENT_ID=".$id;
			$result = mysqli_query($conn, $sql);

			if (mysqli_num_rows($result) > 0) 
			{		
				// output data of each row
				while($row = mysqli_fetch_assoc($result)) 
				{		
					$castellIdCopy=$row["CASTELL_ID"];
					$sql="INSERT INTO CASTELL(NOM,EVENT_ID,W,H,PESTANYA_1,PESTANYA_2,PESTANYA_3,PESTANYA_4, ORDRE, PUBLIC) 
						SELECT NOM,".$eventid.",W,H,PESTANYA_1,PESTANYA_2,PESTANYA_3,PESTANYA_4,ORDRE, 0
						FROM CASTELL
						WHERE CASTELL_ID=".$castellIdCopy;

					if (mysqli_multi_query($conn, $sql)) 
					{
						$castellId=GetLastId($conn);
						$sql="INSERT INTO CASTELL_POSICIO(CASTELL_ID,CASELLA_ID,PESTANYA,POSICIO_ID,CORDO,X,Y,H,W,ANGLE,FORMA,TEXT,LINKAT,SEGUENT,CASTELLER_ID) 
						SELECT ".$castellId.",CASELLA_ID,PESTANYA,POSICIO_ID,CORDO,X,Y,H,W,ANGLE,FORMA,TEXT,LINKAT,SEGUENT,CASTELLER_ID
						FROM CASTELL_POSICIO
						WHERE CASTELL_ID=".$castellIdCopy;	
						
						if (mysqli_multi_query($conn, $sql)) 
						{
							echo "<meta http-equiv='refresh' content='0; url=Event.php'/>";
						} 
						else if (mysqli_error($conn) != "")
						{
							echo "Error pas 3: " . $sql . "<br>" . mysqli_error($conn) . "<br>";
						}
					}
					else if (mysqli_error($conn) != "")
					{
						echo "Error pas 2: " . $sql . "<br>" . mysqli_error($conn) . "<br>";
					}
				}
			} 
		} 
		else if (mysqli_error($conn) != "")
		{
			echo "Error pas 1: " . $sql . "<br>" . mysqli_error($conn) . "<br>";
		}
	}

	mysqli_close($conn);

?>
<a href='Event.php'>Torna als Esdeveniments.</a>
</body>
</html>