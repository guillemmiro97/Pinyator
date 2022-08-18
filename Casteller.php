<html>
<head>
	<title>Pinyator - Castellers</title>
	<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Head.php";?>
	<script src="llibreria/grids.js"></script>
	
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<script src="llibreria/table2CSV.js"></script>
<body>
<?php $menu=1; include "$_SERVER[DOCUMENT_ROOT]/pinyator/Menu.php";?>
	<table class="butons">
		<tr>
			<th>
				<a href="Casteller_Fitxa.php" class="boto" <?php CastellerLv2Not("hidden")?>>Nou</a>
			</th>
			<th></th>
			<th>
				<input type="text" id="edtCerca" style="width:200" class="form_edit" onkeyup="CercaEnter(event)" placeholder="Cerca.." title="Cerca...">			
				<button class="boto" onClick="Cerca()">Cerca</button>
			</th>
			<!--th>
				<input type="text" id="edtFiltra" style="width:200" class="form_edit" onkeyup="filterTable(this,'castellers')" placeholder="Filtra.." title="Filtr...">
			</th-->
			<th><a class="boto" onClick="ExportCSV()">Exporta CSV</a></th>
		</tr>
	</table>
	<label id="Total"></label>
	<br>
	<table class="llistes" id="castellers">
		<tr class="llistes">
			<th class="llistes" onClick="sortTable(0,'castellers')">Malnom<i></i></th>
			<th class="llistes" onClick="sortTable(1,'castellers')">Nom<i></i></th>
			<th class="llistes" onClick="sortTable(2,'castellers')">Cognoms<i></i></th>
			<th class="llistes" onClick="sortTable(3,'castellers')">Posició pinya<i></i></th>
			<th class="llistes" onClick="sortTable(3,'castellers')">Posició tronc<i></i></th>
			<th class="llistes" onClick="sortTable(4,'castellers')">Altura<i></i></th>
			<th class="llistes" onClick="sortTable(4,'castellers')">Altura tronc<i></i></th>
			<th class="llistes" <?php CastellerLv2Not("hidden")?>>UUID</th>
			<th class="llistes" onClick="sortTable(6,'castellers')">Responsables<i></i></th>
			<th class="llistes" onClick="sortTable(7,'castellers')">Estat<i></i></th>
			<th class="llistes" <?php CastellerLv2Not("hidden")?>></th>
		</tr>
<?php
    $count = 0;
	if (empty($_GET["b"]))
	{

	}
	else
	{
		include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";
		
		$count=0;
		
		$baixa = "";
		if (!empty($_GET["e"]))
		{	
			$baixa=" OR C.ESTAT = 2";
		}
		
		$value = strval($_GET["b"]);
		
		//$veureCanalla = EsCastellerLv2() ? "" : " AND (IFNULL(P1.ESCANALLA, 0) = 0)";

		$where = "";
		if($value != "($)($)")
		{
			$where = " AND ((C.MALNOM LIKE '%".$value."%')
				OR (C.NOM LIKE '%".$value."%')
				OR (C.COGNOM_1 LIKE '%".$value."%')
				OR (C.COGNOM_2 LIKE '%".$value."%')
				OR (P1.NOM LIKE '%".$value."%')
				OR (P2.NOM LIKE '%".$value."%')) ";
		}

		$sql="SELECT C.CASTELLER_ID, IFNULL(C.MALNOM, '---') AS MALNOM, C.NOM, C.COGNOM_1, C.COGNOM_2,
		P1.NOM AS POSICIO_PINYA, P2.NOM AS POSICIO_TROC,C.CODI,
		CR1.MALNOM AS RESPONSABLE1, CR2.MALNOM AS RESPONSABLE2, C.ALTURA,
		C.ALTURA_TRONCS, CASE WHEN C.ESTAT = 1 THEN 'ACTIU' ELSE 'BAIXA' END AS ESTAT
		FROM CASTELLER AS C
		LEFT JOIN POSICIO AS P1 ON P1.POSICIO_ID=C.POSICIO_PINYA_ID
		LEFT JOIN POSICIO AS P2 ON P2.POSICIO_ID=C.POSICIO_TRONC_ID
		LEFT JOIN CASTELLER AS CR1 ON CR1.CASTELLER_ID=C.FAMILIA_ID
		LEFT JOIN CASTELLER AS CR2 ON CR2.CASTELLER_ID=C.FAMILIA2_ID
		WHERE 1=1
		".$where."
		ORDER BY C.MALNOM";

		$result = mysqli_query($conn, $sql);
		
		if (mysqli_num_rows($result) > 0) 
		{			
			$PosicioId = 0;
			// output data of each row
			while($row = mysqli_fetch_assoc($result)) 
			{
				$malnom = $row["MALNOM"];
				if ($malnom== "")
				{
					$malnom = "----";
				}
				if ($row["ESTAT"] == "ACTIU")
				{
					$a=2;
					$aTxt="BAIXA"; 
				}
				else
				{
					$a=1;
					$aTxt="ACTIU";
				}
				
				$malnom = SiCastellerLv2("<a href='Casteller_Fitxa.php?id=".$row["CASTELLER_ID"]."'>".$malnom."</a>", $malnom);
				$codi = SiCastellerLv2("<td class='llistes'><a href='Inscripcio.php?id=".$row["CODI"]."'>".$row["CODI"]."</a></td>", "");
				$accio = SiCastellerLv2("<td class='llistes'><a href='Casteller_Accio.php?id=".$row["CASTELLER_ID"]."&a=".$a."'>".$aTxt."</a></td>", "");
				
				echo "<tr class='llistes'>
				<td class='llistes'>".$malnom."</td>
				<td class='llistes'>".$row["NOM"]."</td>
				<td class='llistes'>".$row["COGNOM_1"]." ".$row["COGNOM_2"]."</td>
				<td class='llistes'>".$row["POSICIO_PINYA"]."</td>
				<td class='llistes'>".$row["POSICIO_TROC"]."</td>
				<td class='llistes'>".$row["ALTURA"]."</td>
				<td class='llistes'>".$row["ALTURA_TRONCS"]."</td>
				".$codi."
				<td class='llistes'>".implode(", ", array_filter([$row["RESPONSABLE1"],$row["RESPONSABLE2"]]))."</td>
				<td class='llistes'>".$row["ESTAT"]."</td>
				".$accio."
				</tr>";
				$count++;
			}	
		}
		else if (mysqli_error($conn) != "")
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}

		mysqli_close($conn);
	}
?>	  

	</table> 	
</body>
<script>
	function ExportCSV() 
	{
		$("#castellers").table2CSV();
	}

	sortTable(0,'castellers');
	<?php echo "setTotal('Total', ".$count.");"; ?>	
	

	
	function Cerca()
	{
		input = document.getElementById("edtCerca").value;
		if (input == "")
			input = "($)($)";
		window.open("Casteller.php?b=" + input, "_self");
	}
	
	function CercaEnter(event)
	{
		var code = event.keyCode;
		 if (event.charCode && code == 0)
		 {    
			code = event.charCode;
		 }
		 if(code == 13)
		{
			Cerca();
		} 
	}

	
</script>
</html>

