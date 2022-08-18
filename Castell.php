<html>
<head>
  <title>Pinyator - Castells</title>
  <meta charset="utf-8">
<?php $menu=4; include "$_SERVER[DOCUMENT_ROOT]/pinyator/Head.php";?>
<script src="llibreria/popup.js"></script>
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body class="popup">
	<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Menu.php";?>

<?php 
	$eventId=0;
	if (!empty($_GET["id"]))
	{	
		$eventId=intval($_GET["id"]);
	}
	$eventLink="";
	if ($eventId>0)
	{	
		$eventLink="?id=".$eventId;
	}
?> 
 
<table class='butons'>
	<tr class='butons'>
<?php 
	echo "<th class='butons'><a href='Castell_Nou.php".$eventLink."' class='boto' >Nou</a></th>";
?>		
		<th></th>
		<th class='butons'>
			<a href="Castell.php?e=1" class="boto" >Actius</a>
			<a href="Castell.php?e=-1" class="boto" >Inactius</a>
		</th>
	</tr>
</table> 
 <br>

<?php

	include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";
	
	$estat=1;
	if (!empty($_GET["e"]))
	{	
		$estat=intval($_GET["e"]);
	}
	
	$where="";
	if ($eventId>0)
	{	
		$where=" C.EVENT_ID=".$eventId;
	}
	else
	{
		$where=" E.ESTAT=".$estat;
	}

	$sql="SELECT CASTELL_ID, C.NOM, E.NOM AS EVENT, date_format(E.DATA, '%d-%m-%Y %H:%i') AS DATA, 
	date_format(C.DATA_CREACIO, '%d-%m-%Y %H:%i') AS DATA_CREACIO, 
	C.EVENT_ID AS EVENT_ID, C.ORDRE, C.PUBLIC,
	(SELECT COUNT(*) 
			FROM CASTELL_POSICIO AS CPR 
			INNER JOIN POSICIO AS PR ON PR.POSICIO_ID=CPR.POSICIO_ID 
			WHERE CPR.CASTELL_ID=C.CASTELL_ID AND PR.ESNUCLI=1 AND PR.ESTRONC=0) AS BAIXOS,
	(SELECT COUNT(*) 
			FROM CASTELL_POSICIO AS CPR 
			INNER JOIN POSICIO AS PR ON PR.POSICIO_ID=CPR.POSICIO_ID 
			WHERE CPR.CASTELL_ID=C.CASTELL_ID AND PR.ESFOLRE=1) AS FOLRETIS
	FROM CASTELL AS C
	INNER JOIN EVENT AS E ON E.EVENT_ID=C.EVENT_ID
	WHERE ".$where."
	ORDER BY E.DATA, C.ORDRE, NOM";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		$anterior = "";
		$minuts=0;
		// output data of each row
		while($row = mysqli_fetch_assoc($result)) 
		{
			$event = "<a href='Castell.php?id=".$row["EVENT_ID"]."'>".$row["EVENT"]."  ".$row["DATA"]."</a>";
			
			if (($event != $anterior) || ($anterior == ""))
			{
				$timeInici=new DateTime($row["DATA"]);
				$time=new DateTime($row["DATA"]);
			
				if ($anterior != "")
					echo "</table>";
				
				
				echo "<h3>".$event."</h3>
				<table class='llistes fixed' id=".$row["EVENT_ID"].">
					<col width='74px' />
					<tr class='llistes'>
						<th class='llistes'>Ordre</th>
						<th class='llistes'>Castell</th>
						<th class='llistes'>Hora</th>
						<th class='llistes'>Creat</th>
						<th class='llistes'>Acció</th>
					</tr>";
				echo "<tr class='llistes'>
					<td class='llistes'><button class='boto' onClick='Ordena(".$row["EVENT_ID"].")'>Ordena</button></td>
					<td class='llistes'><a href='Castell_Fitxa.php?id=".$row["CASTELL_ID"]."&a=1'>Troncs i nuclis</a></td>
					<td class='llistes'><a href='Castell_Fitxa.php?id=".$row["CASTELL_ID"]."&a=1&t=1'>Troncs</a></td>
					<td class='llistes'></td>
					<td class='llistes'></td>
				</tr>";
			}
			
			if(($row["FOLRETIS"] > 0) && ($row["BAIXOS"] > 0)) //PINYA+FOLRE
				$minuts=15;
			elseif ($row["BAIXOS"] > 0)//PINYA
				$minuts=10;
			elseif ($row["FOLRETIS"] > 0)//FOLRE TERRA
				$minuts=10;
			else
				$minuts=5;
			
			$time->add(new DateInterval('PT' . $minuts . 'M'));

			$stamp1 = $timeInici->format('H:i');
			$stamp2 = $time->format('H:i');	
			
			$public = "";
			if ($row["PUBLIC"] == 1)
			{
				$public = "boto_add";
			}
			
			echo"
				<tr class='llistes'>
					<td class='llistes'><div contenteditable id=".$row["CASTELL_ID"]." title=".$row["ORDRE"].">".$row["ORDRE"]."</div>
					<td class='llistes'><a href='Castell_Fitxa.php?id=".$row["CASTELL_ID"]."'>".$row["NOM"]."</a></td>
					<td class='llistes'>".$stamp1."-".$stamp2."</td>
					<td class='llistes'>".$row["DATA_CREACIO"]."</td>
					<td class='llistes'>
						<button class='boto ".$public."' name='Castell_Publica.php?id=".$row["CASTELL_ID"]."' onClick='Publicar(this)'>P</button>
						<button class='boto boto_remove' name='Castell_Desa.php?id=".$row["CASTELL_ID"]."&a=1' onClick='ShowPopupEsborra(this)'><img class='img_boto' src='icons/trash.png'></button>
					</td>
				</tr>";
				
			$timeInici->add(new DateInterval('PT' . $minuts . 'M'));
			
			$anterior = $event;
		}
		if ($anterior != "")
			echo "</table>";
	}
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}

	mysqli_close($conn);
?>	  
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Popup.php";?> 
	 
   </body>
<script>
function Ordena(eventId)
{
    var taula = document.getElementById(eventId);

	var items = taula.getElementsByTagName("div");
	for (var i = 0; i < items.length; i++) 
	{
		if(items[i].innerHTML != items[i].title)
		{
			var castellId = items[i].id;
			var ordre = items[i].innerHTML;
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() 
			{
				if (this.readyState == 4 && this.status == 200) 
				{
					location.reload();
				}
			};
			xmlhttp.open("GET", "Castell_Ordena.php?e="+eventId+"&id="+castellId+"&o="+ordre, true);
			xmlhttp.send();
		}
	}	
}

function Publicar(item)
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() 
	{
		if (this.readyState == 4 && this.status == 200) 
		{
			location.reload();
		}
	};
	xmlhttp.open("GET", item.name, true);
	xmlhttp.send();

}
</script>   
   
</html>

