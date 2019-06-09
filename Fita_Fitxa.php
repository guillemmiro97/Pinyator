<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
  <title>Pinyator - Fites</title>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Head.php";?>
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body>
<?php $menu=10; include "$_SERVER[DOCUMENT_ROOT]/pinyator/Menu.php";?>
<?php

$erd="";
if (!empty($_GET['erd']))
{	
	$erd ="Data invalida. Format DD-MM-YYYY";
}

$id = 0;
if (!empty($_GET['id']))
{	
	$id = intval($_GET['id']);
}

$nom = "";
$data = "";
$estat = 0;
$temporada="";
$recompensa="";

$autofocus="";

echo "<form method='post' action='Fita_Desa.php'>";

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

if ($id > 0)
{
	$sql="SELECT E.FITES_ID, E.NOM, E.ORDRE,
	date_format(E.DATA_COMPLETAT, '%Y-%m-%d') AS DATA,
	E.ESTAT, E.TEMPORADA, E.RECOMPENSA
	FROM FITES AS E
	WHERE E.FITES_ID = ".$id;

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result))
		{
			$nom = $row["NOM"];
			$data = $row["DATA"];
			$estat = $row["ESTAT"];
			$temporada = $row["TEMPORADA"];
			$recompensa = $row["RECOMPENSA"];
			$ordre = $row["ORDRE"];
		}
	}
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
}
else
{
	$autofocus = "autofocus";
	$sql="SELECT C.TEMPORADA
	FROM CONFIGURACIO AS C";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result))
		{
			$temporada = $row["TEMPORADA"];
		}
	}
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
}


?>
<div>
  <a href="Fites.php" class="boto" >Torna</a>
</div> 
<br>
<div style="position:absolute;width:500px">
  <div class="form_group">
  <table width=100%>
  <tr>
	<th>
		<label>ID</label>
	</th>
	<th>
		<label>Temporada</label>
	</th>
  </tr>
  <tr>
	<td>
		<input type="text" class="form_edit" name="id" value="<?php echo $id ?>" readonly>
	</td>
	<td>
		<input type="text" class="form_edit" name="temporada" value="<?php echo $temporada ?>" required>
	</td>
  </tr>
  </table>
<br><br>
	<label>Ordre</label>
	<input type="text" class="form_edit" name="ordre" value="<?php echo $ordre ?>" required <?php echo $autofocus ?>>
<br><br>
	<label>Nom</label>
	<input type="text" class="form_edit" name="nom" value="<?php echo $nom ?>" required <?php echo $autofocus ?>>
<br><br>
	<label>Recompensa</label>
	<input type="text" class="form_edit" name="recompensa" value="<?php echo $recompensa ?>" required <?php echo $autofocus ?>>
<br><br>
	<label>Data</label>
	
	<?php 
	if($erd != "")
	{
		echo "<label><font color='red'>Data amb format incorrecte</font></label>";
	}
	?>
	<input type="date" class="form_edit" name="data" value="<?php echo $data ?>">
<br><br>
	<label>Estat</label><br>
	<label class="radio-inline"><input type="radio" name="estat" <?php if($estat==0) echo"checked"?> value=0>No completada</label>
	<label class="radio-inline"><input type="radio" name="estat" <?php if($estat==1) echo"checked"?> value=1>Completada</label>
<br><br>
  <button type="Submit" class="boto">Desa</button>
</div>   
</div> 
</form>
   </body>
</html>

