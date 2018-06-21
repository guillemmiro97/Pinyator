<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
  <title>Pinyator - Esdeveniment</title>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Head.php";?>
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body>
<?php $menu=2; include "$_SERVER[DOCUMENT_ROOT]/pinyator/Menu.php";?>
<?php

$erd="";
if (!empty($_GET['erd']))
{	
	$erd ="Data invalida. Format DD-MM-YYYY hh:mm";
}

$id = 0;
if (!empty($_GET['id']))
{	
	$id = intval($_GET['id']);
}

$nom = "";
$data = "";
$estat = -1;
$tipus=0;
$eventPareId = 0;
$esplantilla=0;

echo "<form method='post' action='Event_Desa.php'>";

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

if ($id > 0)
{
	$sql="SELECT E.EVENT_ID, E.NOM, 
	date_format(E.DATA, '%Y-%m-%d') AS DATA,
	date_format(E.DATA, '%H:%i') AS HORA,
	E.TIPUS, E.ESTAT, E.EVENT_PARE_ID, E.ESPLANTILLA
	FROM EVENT AS E
	WHERE E.EVENT_ID = ".$id."
	ORDER BY E.DATA, E.NOM ";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result))
		{
			$nom = $row["NOM"];
			$data = $row["DATA"];
			$hora = $row["HORA"];
			$estat = $row["ESTAT"];
			$tipus = $row["TIPUS"];
			$eventPareId = $row["EVENT_PARE_ID"];
			$esplantilla = $row["ESPLANTILLA"];
		}
	}
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
}

if (!empty($_GET["e"]))
{	
	$estat=intval($_GET["e"]);			
}

?>
<div>
  <a href="Event.php?e=<?php echo $estat?>" class="boto" >Torna als esdeveniments</a>
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
	<label>Data</label>
	
	<?php 
	if($erd != "")
	{
		echo "<label><font color='red'>Data amb format incorrecte</font></label>";
	}
	?>
	<input type="date" class="form_edit" name="data" value="<?php echo $data ?>" required>
	<input type="time" class="form_edit" name="hora" value="<?php echo $hora ?>" required>
<br><br>
	<label>Tipus</label><br>
	<label class="radio-inline"><input type="radio" name="tipus" <?php if($tipus==-1) echo"checked"?> value=-1>Altres</label>
	<label class="radio-inline"><input type="radio" name="tipus" <?php if($tipus==0) echo"checked"?> value=0>Assaig</label>
	<label class="radio-inline"><input type="radio" name="tipus" <?php if($tipus==1) echo"checked"?> value=1>Actuació</label>
<br><br>
	<label>Estat</label><br>
	<label class="radio-inline"><input type="radio" name="estat" <?php if($estat==-1) echo"checked"?> value=-1>Inactiu</label>
	<label class="radio-inline"><input type="radio" name="estat" <?php if($estat==1) echo"checked"?> value=1>Actiu</label>
	<label class="radio-inline"><input type="radio" name="estat" <?php if($estat==2) echo"checked"?> value=2>Arxivat</label>
<br><br>
	<table>
		<tr>
			<th>És plantilla</th>
		</tr>	
		<tr>
			<td width=100px>
				<label class="switch">texte
					<input type="checkbox" name="esplantilla" value=1 <?php if ($esplantilla == 1) echo " checked";?>>
					<span class="slider round"></span>
				</label>
			</td>
		</tr>
	</table>
  <br><br>
  <label for="sel1">Selecciona esdeveniment pare:</label>
  <select class="form_edit" name="eventpareid">
	<option value=0>Sense pare</oprion>
<?php
    $and = "";
	if($id > 0)
	{
		$and = "AND E.EVENT_ID <> ".$id;
	}
	
	$sql="SELECT E.EVENT_ID, E.NOM, E.DATA
	FROM EVENT AS E
	WHERE E.ESTAT IN (-1, 1) AND E.EVENT_PARE_ID = 0 
	".$and."
	ORDER BY E.NOM ";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result))
		{			
			if($row["EVENT_ID"]==$eventPareId)
				$selected="selected";
			else
				$selected="";
			echo "<option value=".$row["EVENT_ID"]." ".$selected.">".$row["NOM"]." - ".$row["DATA"]."</option>";
		}
	}

   	mysqli_close($conn);

?>
  </select>
<br><br>
  <button type="Submit" class="boto">Desa</button>
</div>   
</div> 
</form>
   </body>
</html>

