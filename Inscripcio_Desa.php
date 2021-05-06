<?php
$event_id = intval($_GET["e"]);
$casteller_id = intval($_GET["c"]);
$estat=0;
$allowed = true;

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

if (isset($_GET["s"])) 
{
	$estat = intval($_GET["s"]);	
	
	if ($estat == 1)
	{
		$sql="SELECT E.MAX_PARTICIPANTS,
			(SELECT SUM(IF(IU.ESTAT>0,1,0)) + SUM(IFNULL(IU.ACOMPANYANTS, 0))
			FROM INSCRITS IU
			JOIN CASTELLER C ON C.CASTELLER_ID=IU.CASTELLER_ID
			WHERE IU.EVENT_ID=E.EVENT_ID
			) AS APUNTATS_ALTRES
		FROM EVENT AS E
		WHERE E.EVENT_ID = ".$event_id;

		$result = mysqli_query($conn, $sql);
		
		if (mysqli_num_rows($result) > 0) 
		{			
					// output data of each row
			while($row = mysqli_fetch_assoc($result)) 
			{
				$allowed = ($row["APUNTATS_ALTRES"] < $row["MAX_PARTICIPANTS"]) || ($row["MAX_PARTICIPANTS"] == 0);
			}	
		}
		else if (mysqli_error($conn) != "")
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	}	
	
	if ($allowed)
	{	
		$sql="UPDATE INSCRITS SET ESTAT=".$estat." WHERE CASTELLER_ID=".$casteller_id." AND EVENT_ID = ".$event_id;

		mysqli_query($conn, $sql);

		if (mysqli_affected_rows($conn) == 0)
		{
			$sql="INSERT IGNORE INTO INSCRITS(EVENT_ID,CASTELLER_ID,ESTAT) VALUES (".$event_id.",".$casteller_id.",".$estat.")";
			if (mysqli_query($conn, $sql)) 
			{
			} 
			else if (mysqli_error($conn) != "")
			{
				echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
		} 
		else if (mysqli_error($conn) != "")
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	}
	else
	{
		echo "NotAllowed";
	}
}
else if (isset($_GET["a"])) 
{
	$acompanyants = intval($_GET["a"]);
	
	$sql="SELECT E.MAX_PARTICIPANTS,
		(SELECT SUM(IF(IU.ESTAT>0,1,0)) + SUM(IFNULL(IU.ACOMPANYANTS, 0))
		FROM INSCRITS IU
		JOIN CASTELLER C ON C.CASTELLER_ID=IU.CASTELLER_ID
		WHERE IU.EVENT_ID=E.EVENT_ID
		) AS APUNTATS_ALTRES
	FROM EVENT AS E
	WHERE E.EVENT_ID = ".$event_id;

	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) > 0) 
	{			
				// output data of each row
		while($row = mysqli_fetch_assoc($result)) 
		{
			$allowed = ($row["APUNTATS_ALTRES"] < $row["MAX_PARTICIPANTS"]) 
			|| ($row["MAX_PARTICIPANTS"] == 0);
		}	
	}
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
	
	if (!$allowed)
	{
		$sql="SELECT ACOMPANYANTS
		FROM INSCRITS AS I
		WHERE CASTELLER_ID=".$casteller_id." AND EVENT_ID = ".$event_id;

		$result = mysqli_query($conn, $sql);
		
		if (mysqli_num_rows($result) > 0) 
		{			
					// output data of each row
			while($row = mysqli_fetch_assoc($result)) 
			{
				$allowed = ($acompanyants < $row["ACOMPANYANTS"]);
			}	
		}
		else if (mysqli_error($conn) != "")
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	}
	
	if ($allowed)
	{
		$sql="UPDATE INSCRITS SET ACOMPANYANTS=".$acompanyants." WHERE CASTELLER_ID=".$casteller_id." AND EVENT_ID = ".$event_id;

		mysqli_query($conn, $sql);

		if (mysqli_affected_rows($conn) == 0)
		{
			$sql="INSERT IGNORE INTO INSCRITS(EVENT_ID,CASTELLER_ID,ESTAT,ACOMPANYANTS) VALUES (".$event_id.",".$casteller_id.",".$estat.",".$acompanyants.")";
			if (mysqli_query($conn, $sql)) 
			{
			} 
			else if (mysqli_error($conn) != "")
			{
				echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
		} 
		else if (mysqli_error($conn) != "")
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	}
	else
	{
		echo "NotAllowed";
	}
}

mysqli_close($conn);

?>
