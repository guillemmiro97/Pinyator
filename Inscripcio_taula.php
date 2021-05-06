<script src="https://cdn.jsdelivr.net/mojs/latest/mo.min.js"></script>

<?php
if (!empty($Casteller_id_taula))
{	
	$script = "";
	
	$sql="SELECT E.EVENT_ID, E.NOM AS EVENT_NOM, E.EVENT_PARE_ID,
	date_format(E.DATA, '%d-%m-%Y %H:%i') AS DATA, ".$Casteller_id_taula." AS CASTELLER_ID, IFNULL(I.ESTAT,-1) AS ESTAT,
	IFNULL(EP.DATA, E.DATA) AS ORDENACIO, E.MAX_PARTICIPANTS,
	IFNULL(I.ACOMPANYANTS, 0) AS ACOMPANYANTS, E.TIPUS,
	E.MAX_ACOMPANYANTS,
	(SELECT SUM(C.PUBLIC) FROM CASTELL AS C WHERE C.EVENT_ID=E.EVENT_ID) AS PUBLIC,
	(SELECT SUM(IF(IU.ESTAT > 0, 1,0))
		FROM INSCRITS IU
		JOIN CASTELLER C ON C.CASTELLER_ID=IU.CASTELLER_ID
		JOIN POSICIO P ON P.POSICIO_ID=C.POSICIO_PINYA_ID
		WHERE IU.EVENT_ID=E.EVENT_ID
		AND IU.ESTAT > 0
		AND (P.ESNUCLI=1 OR P.ESTRONC=1 OR P.ESCORDO=1)) AS APUNTATS,
	(SELECT SUM(IF(IU.ESTAT>0,1,0)) + SUM(IFNULL(IU.ACOMPANYANTS, 0))
		FROM INSCRITS IU
		JOIN CASTELLER C ON C.CASTELLER_ID=IU.CASTELLER_ID
		WHERE IU.EVENT_ID=E.EVENT_ID
		) AS APUNTATS_ALTRES
	FROM EVENT AS E
	LEFT JOIN EVENT AS EP ON EP.EVENT_ID = E.EVENT_PARE_ID
	LEFT JOIN CASTELLER_INSCRITS AS I ON I.EVENT_ID = E.EVENT_ID AND I.CASTELLER_ID=".$Casteller_id_taula."
	WHERE E.ESTAT = 1	
	ORDER BY ORDENACIO, E.EVENT_PARE_ID, E.DATA";
	
	$result2 = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result2) > 0) 
	{
		$PosicioId = 0;		
		echo "<table width='100%' >
			<tr>
				<th  width='60%'>Esdeveniments</th>";
		if ($visualitzarPenya)
		{
			echo "	<th width='20%'>Som</th>";
		}
		else
		{
			echo "	<th width='1%'></th>";
		}
	
		echo "	<th width='20%'></th>
			</tr>";
		$Separador="";
		// output data of each row
		while($row2 = mysqli_fetch_assoc($result2)) 
		{
			$idElement="E".$row2["EVENT_ID"]."C".$row2["CASTELLER_ID"];
			
			$comment = "<a href='Event_Comentari_Public.php?id=".$row2["EVENT_ID"]."&nom=".$malnomPrincipal."'><img src='icons/comment.png'></img></a>";
			
			$max_participants = $row2["MAX_PARTICIPANTS"];
			$max_acompanyants = $row2["MAX_ACOMPANYANTS"];
			
			$apuntats=0;
			if (($row2["APUNTATS"] > 0) && ($row2["TIPUS"] != -1))
			{
				$apuntats=$row2["APUNTATS"];				
			}
			else if (($row2["APUNTATS_ALTRES"] > 0) && ($row2["TIPUS"] == -1))
			{
				$apuntats=$row2["APUNTATS_ALTRES"];
			}			
			
			$str_max = "";
			if ($max_participants > 0)
			{
				$str_max = "(màx ".$max_participants.")";
			}
			
			if ($row2["PUBLIC"]>0)
			{	
				$eventNom = "<a href='Actuacio.php?id=".$row2["EVENT_ID"]."'><b>".$row2["EVENT_NOM"]." ".$str_max."</b></a>";
			}
			else
			{
				$eventNom = "<b>".$row2["EVENT_NOM"]." ".$str_max."</b>";
			}
			$stat  = $row2["ESTAT"];
			if ($stat == -1)
			{
				$stat = 0;
				$script .= "PrimerSaveLike(".$row2["EVENT_ID"].",".$row2["CASTELLER_ID"].");";
			}
			
			$checked="";
			$imgLike="Logo_Colla_null.gif";
			
			if ($stat== 0)
			{
				$color = "style='background-color:#ff1a1a;'";
				$estat="No vinc";
				$checked="";
				$imgLike="Logo_Colla_null.gif";
			}
			elseif ($stat > 0)
			{
				$color = "style='background-color:#33cc33;'";//green
				$estat="Vinc";
				$checked="checked";
				$imgLike="Logo_Colla.gif";
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
			
			$acompanyants = "";
			if ($row2["TIPUS"] == -1)
			{
				$acompanyants = "<button class='boto' onClick='IncrementaAcompanyant(".$row2["EVENT_ID"].", ".$row2["CASTELLER_ID"].")'>&nbsp+&nbsp</button>";
				$acompanyants .= "&nbsp&nbsp&nbsp<font size='2px'><b>Acompanyants:</b></font> <label id='AE".$row2["EVENT_ID"]."C".$row2["CASTELLER_ID"]."'>".$row2["ACOMPANYANTS"]."</label>&nbsp&nbsp&nbsp";
				$acompanyants .= "<button class='boto' onClick='DecrementaAcompanyant(".$row2["EVENT_ID"].", ".$row2["CASTELLER_ID"].")'>&nbsp-&nbsp</button>";
			}
			
			$script .= "EventNou(".$row2["EVENT_ID"].",".$stat.",".$row2["CASTELLER_ID"].",".$apuntats.",".$max_participants.",".$max_acompanyants.");";
			echo "<tr>			
			<td width='85%'>".$tInici.$comment.$eventNom."<br>".$row2["DATA"]."<br>".$acompanyants.$tFinal."</td>";
			if ($visualitzarPenya)
			{
				echo "<td><b name='E".$row2["EVENT_ID"]."'>".$apuntats."</b></td>";
			}
			else
			{
				echo "<td></td>";
			}
			//echo "<td><button class='boto' onClick='Save(".$row2["EVENT_ID"].", ".$row2["CASTELLER_ID"].")' id=".$idElement." ".$color.">".$estat."</button></td>
			//</tr>";
			//<img id='IMG".$idElement."' src='icons/Logo_Colla_null.gif' width=48 height=48>
			//<img id='IMG".$idElement."' src='icons/Logo_Colla_null.gif' width=48 height=48>
			echo "<td>
					<div>
						<div class='like-cnt ".$checked."' id='".$idElement."' onClick='OnClickLike(".$idElement.", ".$row2["EVENT_ID"].", ".$row2["CASTELLER_ID"].", ".$Casteller_id.", ".$row2["TIPUS"].")'>
							<img id='IMG".$idElement."' src='icons/".$imgLike."' width=48 height=48>
						</div>
					</div>
				  </td>
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
