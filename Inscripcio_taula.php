
<?php
if (!empty($Casteller_id))
{
	$script = "";
	
	$sql="SELECT E.EVENT_ID, E.NOM AS EVENT_NOM, E.EVENT_PARE_ID,
	date_format(E.DATA, '%d-%m-%Y %H:%i') AS DATA, ".$Casteller_id." AS CASTELLER_ID, IFNULL(I.ESTAT,-1) AS ESTAT,
	IFNULL(EP.DATA, E.DATA) AS ORDENACIO, 
	(SELECT SUM(C.PUBLIC) FROM CASTELL AS C WHERE C.EVENT_ID=E.EVENT_ID) AS PUBLIC
	FROM EVENT AS E
	LEFT JOIN EVENT AS EP ON EP.EVENT_ID = E.EVENT_PARE_ID
	LEFT JOIN CASTELLER_INSCRITS AS I ON I.EVENT_ID = E.EVENT_ID AND I.CASTELLER_ID=".$Casteller_id."
	WHERE E.ESTAT = 1	
	ORDER BY ORDENACIO, E.EVENT_PARE_ID, E.DATA";
	
	$result2 = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result2) > 0) 
	{
		$PosicioId = 0;		
		echo "<table width='100%' >
			<tr><th>Esdeveniments</th><th>Estat</th></tr>";
		$Separador="";
		// output data of each row
		while($row2 = mysqli_fetch_assoc($result2)) 
		{
			$comment = "<a href='Event_Comentari_Public.php?id=".$row2["EVENT_ID"]."&nom=".$malnomPrincipal."'><img src='icons/comment.png'></img></a>";
			if ($row2["PUBLIC"]>0)
			{	
				$eventNom = "<a href='Actuacio.php?id=".$row2["EVENT_ID"]."'><b>".$row2["EVENT_NOM"]."</b></a>";
			}
			else
			{
				$eventNom = "<b>".$row2["EVENT_NOM"]."</b>";
			}
			$stat  = $row2["ESTAT"];
			if ($stat == -1)
			{
				$stat = 0;
				$script .= "PrimerSave(".$row2["EVENT_ID"].",".$row2["CASTELLER_ID"].");";
			}
			
			if ($stat== 0)
			{
				$color = "style='background-color:#ff1a1a;'";
				$estat="No vinc";
			}
			elseif ($stat == 1)
			{
				$color = "style='background-color:#33cc33;'";//green
				$estat="Vinc";
			}

			$tInici = "";
			$tFinal = "";
			if ($row2["EVENT_PARE_ID"] > 0)
			{
				$Separador="";
				$tInici = "<li>";
				$tFinal = "</li>";
			}

			echo $Separador;
			
			$script .= "EventNou(".$row2["EVENT_ID"].",".$stat.",".$row2["CASTELLER_ID"].");";
			echo "<tr>			
			<td width='85%'>".$tInici.$comment.$eventNom."<br>".$row2["DATA"].$tFinal."</td>
			<td><button class='boto' onClick='Save(".$row2["EVENT_ID"].", ".$row2["CASTELLER_ID"].")' id=E".$row2["EVENT_ID"]."C".$row2["CASTELLER_ID"]." ".$color.">".$estat."</button></td>
			</tr>";
			
			$Separador="<tr><td><br></td></tr>";
		}
		echo "</table>";		
		
		echo "<script>".$script."</script>";
	}
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}	
}
?>	
