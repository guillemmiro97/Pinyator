<html>
<head>
  <title>Pinyator - Disseny castell</title>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Head.php";?>
  <script src="llibreria/plantilla.js"></script>
  <script src="llibreria/disseny.js"></script>
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body>
<?php $menu=3; include "$_SERVER[DOCUMENT_ROOT]/pinyator/Menu.php";

$id = 0;
$nom = "XdX";

if (!empty($_GET['id']))
{
	$id = intval($_GET['id']);
}

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

$nom="";
$H=700;
$W=1000;
$ps1="";
$ps2="";
$ps3="";
$ps4="";

$sql="SELECT NOM,H,W
,PESTANYA_1, PESTANYA_2, PESTANYA_3, PESTANYA_4
FROM PLANTILLA
WHERE PLANTILLA_ID = ".$id;

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) 
{
	while($row = mysqli_fetch_assoc($result)) 
	{
		$nom = $row["NOM"];
		$H = $row["H"];
		$W = $row["W"];
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

?>

<div class="sidenav" id="posicio" style="width:220px;" >
	<h2><?php echo $nom?></h2>
	Id(X:Y)/tot:
	<span id="txtInfo"></span>
	<br>
 <table class="simple">
  <tr>
    <td>	
		Angle:
		<input class="form_edit" type="number" id="posicioangle" onChange="Editing(this)" min=0 max=359 title="Edita l'angle de rotació">
	</td>
	<td>
	Figura:
		<select class="form_edit" id="forma"  onChange="Editing(this)" title="Determina quina figura es dibuixada">
			<option value=0>Rectangle</option>
			<option value=1>Oval</option>
			<option value=2>Rectangle2</option>
			<option value=3>Rectangle3</option>
			<option value=4>Triangle</option>
			<option value=5>Fletxa</option>
			<option value=6>Text</option>
			<option value=7>Trapezi1</option>
			<option value=8>Trapezi2</option>
		</select>
		</td>
  </tr>
  <tr>
  <td>
		Posició:
		<select class="form_edit" id="posicioC"  onChange="Editing(this)" title="Edita la posició per les estadístiques">
		<?php
		    $colors = "";
			
			$sql="SELECT NOM, POSICIO_ID, COLORFONS, COLORTEXT
			FROM POSICIO
			ORDER BY NOM";

			$result = mysqli_query($conn, $sql);

			if (mysqli_num_rows($result) > 0) 
			{				
				while($row = mysqli_fetch_assoc($result)) 
				{
					echo "<option value=".$row["POSICIO_ID"].">".$row["NOM"]."</option>";
					$colors = $colors." AddColorPosicio(".$row["POSICIO_ID"].",'".$row["COLORFONS"]."','".$row["COLORTEXT"]."');";
				}
			}
		?>
		</select>
		</td>
		<td>
		Cordo: 
		<input class="form_edit" type="number" id="posiciocordo" onChange="Editing(this)" min=0 title="Edita el cordo per les estadístiques">
		</td>
	</tr>
	<tr>
	<td>
		Altura: 
		<input class="form_edit" type="number" id="posicioH" onChange="Editing(this)" value=30 min=2 title="Edita l'altura del recuadre">
		</td>
		<td>
		<table>
		<tr>
		<td>
		Amplada:
		</td>
		</tr>
		<tr>
		<td>
		<input class="form_edit" type="number" id="posicioW" onChange="Editing(this)" value=80 min=2 title="Edita l'amplada del recuadre">
		</td>
		<td>
		<button class="boto" onclick="Alinia()" title="Alinia les caselles sel·leccionades">A</button>
		</td>
		</tr>
		</table>
	</tr>
	<tr>
	<td><table>
		<tr>
		<td>
		Següent:
		</td>
		</tr>
		<tr>
		<td>
		<input class="form_edit" type="number" id="seguent" onChange="Editing(this)" value=0 min=0 title="Edita quin recuadre es el següent per desplaçar els castellers">
		</td>
		<td>
		<button class="boto" onclick="SetSeguents()" title="Defineix la seqüència de les caselles, per moure els castellers automàticament.">S</button>
		</td>
		</tr>
		</table>
	</td>
    <td>
		Copia/Linkat: 
		<input class="form_edit" type="number" id="linkat" onChange="Editing(this)" value=0 min=0 title="Edita quin recuadre tindra el mateix casteller, copia del contingut">
	</td>
  </tr>
</table> 
<br>
	Mou el castell (pixels):
	<input class="form_edit" type="number" id="mourevalor" value=50 min=0 max=100>
	<br>
	<table class="butons">
		<tr>
			<td></td><td><button class="boto" onclick="MoureCastellAmunt()">Amunt</button></td><td></td>
		</tr>
		<tr>
			<td><button class="boto"  onclick="MoureCastellEsquerra()">Esq.</button></td>
			<td></td><td><button class="boto" onclick="MoureCastellDreta()">Drt.</button></td>
		</tr>
		<tr>
			<td></td><td><button class="boto" onclick="MoureCastellAvall()">Avall</button></td><td></td>
		</tr>
	</table>
	<br>
	Multiselect
	<label class="switch"> text
	  <input type="checkbox" onclick="MultiSelect()" checked>
	  <span class="slider round"></span>
	</label>
	<br>
	<br>
	<button class="boto" onclick="Esborra()">Esborra</button>
	<br>
	<br>
	<button class="boto" onclick="download()">Imatge</button>
</div>
<div class="popup" id="dibuix" style="position:absolute;left:220px;">
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
		<canvas id="canvas1" style="border:1px solid" height="<?php echo $H?>" width="<?php echo $W?>">
		This text is displayed if your browser does not support HTML5 Canvas.
		</canvas>
		<div class="popuptext" id = "myPopup">
			Text:
			<br>
			<input class="text" type="text" id="text" style="width:90%" onChange="EditingText()" >
			
		</div>
		</td>
		</tr>
	</table>
</div>
<script>initCanvas(<?php echo $id?>, 0);</script>
<span id="txtHint"></span>

</body>
<?php


$sql="SELECT CASELLA_ID,CORDO,POSICIO_ID,X,Y,H,W,ANGLE,FORMA,TEXT,PESTANYA,LINKAT,SEGUENT
FROM PLANTILLA_POSICIO 
WHERE PLANTILLA_ID = ".$id;

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) 
{
	echo "<script>";
	echo $colors;
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) 
	{
		echo " addRect(".$row["X"].",".$row["Y"].",".$row["W"].",".$row["H"].",".$row["CORDO"].",".$row["POSICIO_ID"].",".$row["ANGLE"].
		",".$id.",".$row["CASELLA_ID"].",".$row["PESTANYA"].
		",".$row["FORMA"].",'".$row["TEXT"]."',".$row["LINKAT"].",".$row["SEGUENT"].");
		";
    }
	echo "</script>";
}
else if (mysqli_error($conn) != "")
{
    echo "<br>Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
 
</html>

