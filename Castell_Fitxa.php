<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pinyator - Castell</title>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Head.php";?>
  <script src="llibreria/castell.js?1.1"></script>
  <script src="llibreria/disseny.js?1.1"></script>  
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body>
<?php $menu=4; include "$_SERVER[DOCUMENT_ROOT]/pinyator/Menu.php";

$id = intval($_GET['id']);
$eventid = -1;
$autogenerat = false;
$troncs = false;
$arxivats = 0;
$rating = 0;

if (!empty($_GET["a"]))
{	
	$autogenerat=intval($_GET["a"]);
}	

if (!empty($_GET["t"]))
{	
	$troncs=intval($_GET["t"]);
}	

$resoluciopantalla=1200;
$estronc=0;
$pinyaTronc="C.POSICIO_PINYA_ID";
$switchPinyaTronc="";
if ($_SESSION["carrec"]=="2")
{
	$estronc=1;
	$pinyaTronc="C.POSICIO_TRONC_ID";
	$switchPinyaTronc=" checked";
}


include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

$titol = "";
$estatEvent = 0;

$sql="SELECT C.NOM, E.NOM AS EVENT, E.EVENT_ID, E.ESTAT
FROM CASTELL C
JOIN EVENT E ON E.EVENT_ID=C.EVENT_ID
WHERE CASTELL_ID = ".$id;

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) 
{
	while($row = mysqli_fetch_assoc($result)) 
	{
		$eventid = $row["EVENT_ID"];
		$estatEvent = $row["ESTAT"];
		if ($autogenerat)
		{
			$titol = "Troncs i nuclis"." - ".$row["EVENT"];
		}
		else
		{
			$titol = $row["NOM"]." - ".$row["EVENT"];
		}
	}	
}
else if (mysqli_error($conn) != "")
{
	echo "<br>Error: " . $sql . "<br>" . mysqli_error($conn);
}


?>
	<div  class="sidenav" id="navlateral">
		<h4><?php echo $titol ?></h4>
		<button class="boto" onclick="download()">Imatge</button>
		<button class="boto boto_remove" id="Neteja" onClick="Neteja()" <?php echo $autogenerat?"hidden":""; ?>>Neteja</button>
		<br><br>
		<button class="boto boto_remove" id="EsborraPaleta" onClick="EsborraMenu()">Esborra</button>
		<button class="boto" id="EditaPaleta" onClick="EditaMenu()">Edita</button>
		<br><br>
		<!--Popup 
		<label class="switch">text
		  <input type="checkbox" id="VeurePopup">
		  <span class="slider round"></span>
		</label-->
		Mides 
		<label class="switch">text
		  <input type="checkbox" onclick="Mides()">
		  <span class="slider round"></span>
		</label>
		<br>
		Tota la llista		
		<label class="switch">text
		  <input type="checkbox" id="MostraTots" onclick="RefreshTotals()">
		  <span class="slider round"></span>
		</label>
		<br>
		Pinyes		
		<label class="switch">text
		  <input type="checkbox" id="Pinyes" <?php echo $switchPinyaTronc; ?> onClick="SetPinyaTronc()">
		  <span class="slider slider2 round"></span>
		</label>
		Troncs
		<br>
		<input type="text" id="myInput" class="form_edit" onkeyup="Buscar()" placeholder="Cerca casteller.." title="Type in a name">
		<div id="panellLateral" style="overflow:scroll;padding-top:5px;height:400px">
	<?php
		//Mirem si el seguent event es actuacio
		$eventActuacioid = -1;
		
		$sql="SELECT E.EVENT_ID
		FROM EVENT AS E 
		WHERE E.ESTAT = 1
		AND E.TIPUS = 1
		ORDER BY E.DATA
		LIMIT 1";

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_assoc($result)) 
			{
				$eventActuacioid = $row["EVENT_ID"];
			}
		}		
		
		$sql="SELECT RESOLUCIOPANTALLA
		FROM CONFIGURACIO";

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_assoc($result)) 
			{
				$resoluciopantalla = $row["RESOLUCIOPANTALLA"];
			}
		}
		
		$sql="SELECT COUNT(*) AS ARX
			FROM EVENT AS ERR
			INNER JOIN EVENT AS E ON E.EVENT_ID=".$eventActuacioid." AND ERR.TEMPORADA=E.TEMPORADA
			WHERE ERR.TIPUS=0
			AND ERR.ESTAT=2";
			
		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_assoc($result)) 
			{
				$arxivats = $row["ARX"];
			}
		}
		
		$ordrealtura = "";
		if ($_SESSION["carrec"]=="2")
		{
			$ordrealtura="ALTURA, ";
		}
		
		$sql="SELECT C.CASTELLER_ID, MALNOM, P.NOM, P.POSICIO_ID, IFNULL(I.ESTAT,0) AS ESTAT,
		IFNULL(C.ALTURA, 0) AS ALTURA, IFNULL(C.FORCA, 0) AS FORCA, PORTAR_PEU, LESIONAT,
		IFNULL(IA.ESTAT,0) AS CAMISA,
		(SELECT SUM(IFNULL(IR.ESTAT,0)) AS RAT
			FROM EVENT AS ERR 
			LEFT JOIN INSCRITS AS IR ON IR.EVENT_ID=ERR.EVENT_ID
			WHERE ERR.TEMPORADA=E.TEMPORADA
			AND ERR.TIPUS=0
			AND ERR.ESTAT=2
			AND IR.CASTELLER_ID = C.CASTELLER_ID) AS RATING
		FROM CASTELLER AS C 
		INNER JOIN POSICIO AS P ON P.POSICIO_ID=".$pinyaTronc."
		LEFT JOIN CASTELL AS CT ON CT.CASTELL_ID=".$id."
		LEFT JOIN INSCRITS AS I ON CT.EVENT_ID=I.EVENT_ID AND I.CASTELLER_ID=C.CASTELLER_ID
		LEFT JOIN EVENT AS E ON E.EVENT_ID=I.EVENT_ID
		LEFT JOIN EVENT AS EA ON EA.EVENT_ID=".$eventActuacioid." AND EA.DATA >= E.DATA
		LEFT JOIN INSCRITS AS IA ON IA.EVENT_ID=EA.EVENT_ID AND IA.CASTELLER_ID=C.CASTELLER_ID
		WHERE (C.ESTAT = 1 OR I.ESTAT = 1)
		AND (P.ESTRONC = 1 OR P.ESCORDO = 1 OR P.ESNUCLI = 1)
		ORDER BY P.NOM, ".$ordrealtura." C.MALNOM ";

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) 
		{
			$PosicioId = 0;
			// output data of each row
			while($row = mysqli_fetch_assoc($result)) 
			{
				if($PosicioId!=$row["POSICIO_ID"])	
				{
					echo "<button id='".$row["NOM"]."' class='accordion boto' name='".$row["NOM"]."' onClick='CollapsaTitol(this.id)'>".$row["NOM"]."</button>";
				}
				
				$class="";
				$color="";
				$classFont="Aqui";
				if($row["ESTAT"]==0)
				{
					$class="castellerNoInscrit";
					$color=" style='background-color:#F5A9BC'";
				}
				else if($row["ESTAT"]==1)
				{
					$classFont="Vinc";
				}
				
				$lesionat = "";
				if($row["LESIONAT"]==1)
				{
					$lesionat="<img id='lesionat".$row["CASTELLER_ID"]."' src='icons/lesionat.png'>";
				}
				
				$portarpeu = "";
				if($row["PORTAR_PEU"]==0)
				{
					$portarpeu="<img id='peu".$row["CASTELLER_ID"]."' src='icons/peu_vermell.png'>";
				}
				
				$camisa = "";
				if($row["CAMISA"]==1)
				{
					$camisa="<img src='icons/camisa.png'>";
				}
				
				if($arxivats > 0)
				{
					$rating = (($row["RATING"]/$arxivats)*100);
				}				
				
				$cstl="<font class='".$classFont."' id='lbl".$row["CASTELLER_ID"]."' title='".$row["MALNOM"]." - ".$row["ALTURA"]."'>".$row["MALNOM"]." - ".$row["ALTURA"]."</font>";
				$info = "<a href='Casteller_Fitxa.php?id=".$row["CASTELLER_ID"]."' target='_blank'><img src='icons/info.png'></a>";
				$text = $info." ".$cstl." ".$portarpeu.$lesionat.$camisa;
				
				$text .= "<div><progress value='".$rating."' max='100' style='height:6px;' title='".round($rating)."'>
						</progress></div>";						
				
				$onClick = " onClick='SetCasteller(this,".$row["ALTURA"].",".$row["FORCA"].",".$row["PORTAR_PEU"].",".$row["LESIONAT"].",".$row["CAMISA"].")' ";
				
				
				echo "<div class='accordionItem ".$class."' id=".$row["CASTELLER_ID"]." title='".$row["MALNOM"]."' ".$onClick.$color.">".$text."</div>";
				$PosicioId=$row["POSICIO_ID"];
			}
		}
		else if (mysqli_error($conn) != "")
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		
		$nom="";
		$canvasH=700;
		$canvasW=$troncs?$resoluciopantalla:1000;
		$ps1="";
		$ps2="";
		$ps3="";
		$ps4="";		

		$sql="SELECT NOM, POSICIO_ID, COLORFONS, COLORTEXT, COLORCAMISA
		FROM POSICIO
		ORDER BY NOM";

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) 
		{
			$colors = "<script>";
			while($row = mysqli_fetch_assoc($result)) 
			{
				$colors = $colors." AddColorPosicio(".$row["POSICIO_ID"].",'".$row["COLORFONS"]."','".$row["COLORTEXT"]."','".$row["COLORCAMISA"]."');";
			}
			echo $colors."</script>";
		}
		
		

		if (! $autogenerat)
		{
			$sql="SELECT NOM,H,W
			,PESTANYA_1, PESTANYA_2, PESTANYA_3, PESTANYA_4
			FROM CASTELL
			WHERE CASTELL_ID = ".$id;

			$result = mysqli_query($conn, $sql);

			if (mysqli_num_rows($result) > 0) 
			{
				while($row = mysqli_fetch_assoc($result)) 
				{
					$nom = $row["NOM"];
					$canvasH = $row["H"];
					$canvasW = $row["W"];
					$ps1 = $row["PESTANYA_1"];
					$ps2 = $row["PESTANYA_2"];
					$ps3 = $row["PESTANYA_3"];
					$ps4 = $row["PESTANYA_4"];
				}		
			}
			else if (mysqli_error($conn) != "")
			{
				echo "<br>Error: " . $sql . "<br>" . mysqli_error($conn);
			}
		}
		else
		{
			$canvasH = 4000;		
		}
	?>	

		</div>
	</div>
	<div class="canvascontent" style="margin-left: 204px;">
		<div class="popup" id="dibuix">
			<table>
				<tr>
				<td>
		<?php
			if($ps1 != "")
				echo "<button class='tabulador tabuladorActiu' name='1' onClick='CanviPestanya(this)'>".$ps1."</button>";
			if($ps2 != "")
				echo "<button class='tabulador' name='2' onClick='CanviPestanya(this)'>".$ps2."</button>";
			if($ps3 != "")
				echo "<button class='tabulador' name='3' onClick='CanviPestanya(this)'>".$ps3."</button>";
			if($ps4 != "")
				echo "<button class='tabulador' name='4' onClick='CanviPestanya(this)'>".$ps4."</button>";
		?>
				</td>
				</tr>
				<tr>
				<td>
					<canvas id="canvas1" style="border:1px solid" height="<?php echo $canvasH ?>" width="<?php echo $canvasW ?>">
						This text is displayed if your browser does not support HTML5 Canvas.
					</canvas>
					<div class="popuptext" id = "myPopup">
						Nom:
						<br>
						<input class="text" type="text" id="text" onChange="CanviText(this.value)">
					</div>
				</td>
				</tr>
			</table>
		</div>
	</div>
	<script>
		initCanvas(-1, <?php if($estatEvent==2) echo "1"; else echo "0"; ?>);
		init();
	</script>
	<span id="txtHint"></span>
	<!--div class="popupcasella" id = "PopUpCasella">
		<button class="boto boto_remove" id="EsborraPopup" onClick="EsborraPopup()">Esborra</button>
		<button class="boto" id="EditaPopup" onClick="EditaPopup()">_Edita_</button>
	</div--> 
<?php
	
	if ($autogenerat)
	{		
		$nucli = $troncs?"1=0":"P.ESNUCLI = 1";
		
		$sql="SELECT CP.CASELLA_ID,CTT.CASTELL_ID,CTT.NOM AS CASTELL_NOM,CORDO,CP.POSICIO_ID,
		CP.CASTELLER_ID, CP.TEXT, IFNULL(C.MALNOM, 0) AS MALNOM, IFNULL(I.ESTAT,0) AS ESTAT,
		IFNULL(C.ALTURA, 0) AS ALTURA, IFNULL(C.FORCA, 0) AS FORCA,
		IFNULL(C.PORTAR_PEU, 1) AS PORTAR_PEU, IFNULL(C.LESIONAT, 0) AS LESIONAT,
		ESTRONC, ESNUCLI, PESTANYA, IFNULL(LINKAT, 0) AS LINKAT, IFNULL(IA.ESTAT,0) AS CAMISA,
		(SELECT MAX(CORDO) 
			FROM CASTELL_POSICIO AS CPR 
			INNER JOIN POSICIO AS PR ON PR.POSICIO_ID=CPR.POSICIO_ID 
			WHERE CPR.CASTELL_ID=CTT.CASTELL_ID AND PR.ESTRONC=1) AS RENGLES
		FROM CASTELL AS CTT
		INNER JOIN CASTELL_POSICIO AS CP ON CP.CASTELL_ID=CTT.CASTELL_ID
		INNER JOIN POSICIO AS P ON P.POSICIO_ID=CP.POSICIO_ID
		LEFT JOIN CASTELLER AS C ON C.CASTELLER_ID=CP.CASTELLER_ID 
		LEFT JOIN INSCRITS AS I ON CTT.EVENT_ID=I.EVENT_ID AND I.CASTELLER_ID=C.CASTELLER_ID
		LEFT JOIN EVENT AS E ON E.EVENT_ID=I.EVENT_ID
		LEFT JOIN EVENT AS EA ON EA.EVENT_ID=".$eventActuacioid." AND EA.DATA >= E.DATA
		LEFT JOIN INSCRITS AS IA ON IA.EVENT_ID=EA.EVENT_ID AND IA.CASTELLER_ID=C.CASTELLER_ID		
		WHERE CTT.EVENT_ID = ".$eventid."
		AND (".$nucli." OR P.ESTRONC = 1)
		ORDER BY CTT.ORDRE, CTT.NOM, CORDO, ESTRONC DESC, ESNUCLI, 
		CASE WHEN ESTRONC=1 THEN CP.Y ELSE CP.POSICIO_ID END";

		$result = mysqli_query($conn, $sql);
		
		if (mysqli_num_rows($result) > 0) 
		{
			echo "<script>";
			
			$castellId = -1;
			$x=0;
			$xIni=10;
			$y=0;
			$yIni=30;
			$h=0;
			$w=0;
			$forma=2;
			$cordo=-1;
			$posicio=0;
			$rengles=0;
			$yMax=0;
			$suma="";
			$linkat=0;
			$esTronc=array();
			
			$row = mysqli_fetch_assoc($result);

			while ($row) 
			{
				$nextRow = mysqli_fetch_assoc($result);
			
				if($y>$yMax)
				{	
					$yMax=$y;
				}
				
				if ($castellId != $row["CASTELL_ID"])
				{//Inici, nom del castell
					
					$renglesMax=$canvasW/100;
					//TODO: Calcular inici cada castell
					if(($rengles + $row["RENGLES"]) > $renglesMax)
					{
						$rengles = $row["RENGLES"];
						$xIni=10;
						$x=$xIni;
						$yIni=30+$h+$yMax;
						$y=$yIni;
					}
					else
					{
						$rengles = $rengles + $row["RENGLES"];
						$xIni=20+$x+$w;
						$x=$xIni;
						$y=$yIni;					
					}					
					
					$castellId = $row["CASTELL_ID"];
					$forma=6;
					$cordo = $row["CORDO"];
					$h=30;
					$w=80;
					
					//Nom castell
					echo "addRect(".$x.",".$y.",".$w.",".$h.",0,0,0,
					".$row["CASTELL_ID"].",".(-1*$row["CASELLA_ID"]).",1,".$forma.",'".$row["CASTELL_NOM"]."',0,0,".$castellId.");\n";
				}
				
				$forma=0;				

				if ($linkat != $row["CASELLA_ID"])
				{
					$y=$y + $h + 4;
				}
				
				if($row["ESTRONC"] == 1)
				{	
					$h=30;
					if($linkat != $row["CASELLA_ID"])
					{
						$suma=$suma.$row["CASELLA_ID"].";";
					}
					
					if (!in_array($row["POSICIO_ID"],$esTronc))
					{
						array_push($esTronc, $row["POSICIO_ID"]);
					}
				}
				else
				{
					$h=20;
					if($posicio != $row["POSICIO_ID"])
					{
						$y = $y +5;
						$posicio = $row["POSICIO_ID"];
					}
				}
				
				$w=80;
				if(($cordo != $row["CORDO"]))
				{									
					$x=$x + $w + 4;
					$y=$yIni+30+4;
					$cordo = $row["CORDO"];
				}
				
				echo "addRect(".$x.",".$y.",".$w.",".$h.",".$row["CORDO"].",".$row["POSICIO_ID"].",0,
				".$row["CASTELL_ID"].",".$row["CASELLA_ID"].",".$row["PESTANYA"].",".$forma.",'".$row["TEXT"]."',".$row["LINKAT"].",-1,".$castellId.",
				".$row["CASTELLER_ID"].",'".$row["MALNOM"]."',".$row["ESTAT"].",".$row["ALTURA"].",".$row["FORCA"].",".$row["PORTAR_PEU"].",".$row["LESIONAT"].",".$row["CAMISA"].");\n";
				
				$linkat = $row["LINKAT"];
				
				if(!$troncs)
				{
					if (($suma != "") && (
					(($nextRow != null) && ($cordo != $nextRow["CORDO"]) || ($nextRow["ESTRONC"] == 0))
					|| (($nextRow == null) && ($row["ESTRONC"] == 0))
					|| (($nextRow != null) && ($castellId != $nextRow["CASTELL_ID"]))
					))
					{//Insertem text per sumar altures
						$forma=6;
						$y=$y + $h + 4;
						$suma=substr($suma,0,-1);

						echo "addRect(".$x.",".$y.",".$w.",".$h.",0,0,0,
						".$row["CASTELL_ID"].",".$row["CASELLA_ID"].",1,".$forma.",'SUM(".$suma.")',0,0,".$castellId.");\n";
						
						$suma="";
					}
				}
				$row = $nextRow;
			}
			echo " CollapsaTot();\n";
			echo " FixaPestanya();\n";
			foreach($esTronc as $x_value) 
			{
				echo " AddPosicioTronc(".$x_value.");\n";
			}
			echo " UpdataCarregaAssaig();\n";
			echo "</script>";
		}
		else if (mysqli_error($conn) != "")
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	}
	else
	{
		$sql="SELECT CASELLA_ID,CORDO,CP.POSICIO_ID,CP.X,CP.Y,CP.H,CP.W,ANGLE,
		FORMA,TEXT,SEGUENT,LINKAT,
		CP.CASTELLER_ID,IFNULL(C.MALNOM, 0) AS MALNOM, IFNULL(I.ESTAT,0) AS ESTAT,
		IFNULL(C.ALTURA, 0) AS ALTURA, IFNULL(C.FORCA, 0) AS FORCA, PESTANYA,
		IFNULL(C.PORTAR_PEU, 1) AS PORTAR_PEU, IFNULL(C.LESIONAT, 0) AS LESIONAT,
		IFNULL(IA.ESTAT,0) AS CAMISA
		FROM CASTELL_POSICIO AS CP 
		INNER JOIN CASTELL AS CT ON CT.CASTELL_ID=CP.CASTELL_ID
		LEFT JOIN CASTELLER AS C ON C.CASTELLER_ID=CP.CASTELLER_ID 
		LEFT JOIN INSCRITS AS I ON CT.EVENT_ID=I.EVENT_ID AND I.CASTELLER_ID=C.CASTELLER_ID
		LEFT JOIN POSICIO AS P ON P.POSICIO_ID=CP.POSICIO_ID
		LEFT JOIN EVENT AS E ON E.EVENT_ID=I.EVENT_ID
		LEFT JOIN EVENT AS EA ON EA.EVENT_ID=".$eventActuacioid." AND EA.DATA >= E.DATA
		LEFT JOIN INSCRITS AS IA ON IA.EVENT_ID=EA.EVENT_ID AND IA.CASTELLER_ID=C.CASTELLER_ID	
		WHERE CP.CASTELL_ID = ".$id;

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) 
		{
			echo "<script>";
			// output data of each row
			while($row = mysqli_fetch_assoc($result)) 
			{
				echo "addRect(".$row["X"].",".$row["Y"].",".$row["W"].",".$row["H"].",".$row["CORDO"].",".$row["POSICIO_ID"].",".$row["ANGLE"].",".$id.",".$row["CASELLA_ID"]."
				,".$row["PESTANYA"].",".$row["FORMA"].",'".$row["TEXT"]."',".$row["LINKAT"].",".$row["SEGUENT"].",".$id.",".$row["CASTELLER_ID"].",'".$row["MALNOM"]."',".$row["ESTAT"].",".$row["ALTURA"].",".$row["FORCA"]."
				,".$row["PORTAR_PEU"].",".$row["LESIONAT"].",".$row["CAMISA"].");\n";
			}
			echo " CollapsaTot();\n";
			echo "</script>";
		}
		else if (mysqli_error($conn) != "")
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	}
	mysqli_close($conn);
?>
</body>
</html>