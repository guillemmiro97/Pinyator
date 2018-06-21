<html>
<head>
  <title>Pinyator</title>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Head.php";?>
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body>
<form method='post' action='Plantilla_Desa.php'>
<?php 

$menu=3; 

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Menu.php";


$id = 0;
if (!empty($_GET['id']))
{	
	$id = intval($_GET['id']);
}

$nom = "";
$estat = 0;
$deshabilitada="";
$habilitada="";
$H=700;
$W=1000;
$ps1="";
$ps2="";
$ps3="";
$ps4="";

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

if ($id > 0)
{

	$sql="SELECT NOM, ESTAT, W, H,
	PESTANYA_1, PESTANYA_2, PESTANYA_3, PESTANYA_4
	FROM PLANTILLA
	WHERE PLANTILLA_ID = ".$id;

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result))
		{
			$nom = $row["NOM"];
			$estat = $row["ESTAT"];
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
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}

	if($estat==2)
	{
		$deshabilitada="selected";
		$habilitada="";
	}
	else
	{
		$deshabilitada="";
		$habilitada="selected";
	}
	
}?>
<div>
  <a href="Plantilla.php" class="boto" >Torna a les plantilles</a>
</div> 
<br>
<div style="position:absolute;width:500px">
<div class="form_group">
	<label>ID</label>
	<input type="text" class="form_edit" name="id" value="<?php echo $id ?>" readonly>
<br><br>
	<label>Nom</label>
	<input type="text" class="form_edit" name="nom" value="<?php echo $nom ?>" required>
<br><br>
	<label>Altura</label>
	<input type="number" class="form_edit" name="H" value="<?php echo $H ?>" required>
<br><br>
	<label>Amplada</label>
	<input type="number" class="form_edit" name="W" value="<?php echo $W ?>" required>
<br><br>
  <label for="sel1">Estat:</label>
  <select class="form_edit" name="estat">
	<option value=2 <?php echo $deshabilitada?>>Deshabilitada</option>
	<option value=1 <?php echo $habilitada?>>Habilitada</option>
  </select>
<br><br>
	<label>Pestanya 1</label>
	<input type="text" class="form_edit" name="pestanya_1" value="<?php echo $ps1 ?>">
<br><br>
	<label>Pestanya 2</label>
	<input type="text" class="form_edit" name="pestanya_2" value="<?php echo $ps2 ?>">
<br><br>
	<label>Pestanya 3</label>
	<input type="text" class="form_edit" name="pestanya_3" value="<?php echo $ps3 ?>">
<br><br>
	<label>Pestanya 4</label>
	<input type="text" class="form_edit" name="pestanya_4" value="<?php echo $ps4 ?>">
<br><br>
<?php
if($id == 0)
{
	echo "<div class='form-group'>";
	echo "<br>";
	echo "<label for='sel1'>Copia d'una Plantilla:</label>";
	echo "<select class='form-control' name='plantillaid'>";
	echo "<option value='-1' selected>Selecciona plantilla</option>";


	$sql="SELECT PLANTILLA_ID, NOM
	FROM PLANTILLA
	WHERE 1=1
	AND ESTAT=1
	ORDER BY NOM ";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result))
		{			
			echo "<option value=".$row["PLANTILLA_ID"].">".$row["NOM"]."</option>";
		}
	}
	echo "</select>";
	echo "</div>";
}
?>

	<button type="Submit" class="boto">Desa</button>
</div>
</div>
</form>
</body>
</html>

