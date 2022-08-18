<html>
<head>
  <title>Pinyator</title>
<?php $menu=4; include "$_SERVER[DOCUMENT_ROOT]/pinyator/Head.php";?>
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Menu.php";?>
<?php

echo "<form method='post' action='Castell_Nou_Desa.php'>";

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

?>
<div>
  <a href="Castell.php" class="boto" >Torna als castells</a>
</div> 
<div> 
<br>
</div> 
 <div class="form_group">
 
 <?php
	$event=0;
	if (!empty($_GET["id"]))
	{	
		$event=intval($_GET["id"]);
		echo "<input type='text' class='form_edit' name='id' value='".$event."' hidden>";
	}
 
 ?>
 
  <label for="sel1">Esdeveniment:</label>
  <select class="form_edit" name="eventid">
<?php

	$where=" AND ESTAT=1 ";
	if($event > 0)
	{
		$where=" AND EVENT_ID=".$event." ";
	}

	$sql="SELECT EVENT_ID, NOM
	FROM EVENT
	WHERE 1=1
	".$where."
	AND EVENT_PARE_ID=0
	ORDER BY DATA";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result))
		{			
			echo "<option value=".$row["EVENT_ID"].">".$row["NOM"]."</option>";
		}
	}
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
	
?>
  </select>
<br><br><br>   
<h3>Copiar des de:</h3>

  <br>
  <label for="sel1">Plantilla:</label>
  <select class="form_edit" name="plantillaid">
	<option value="-1" selected>Selecciona plantilla</option>
<?php

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

?>
  </select>
<br><br>
  <br>
  <label for="sel1">Esdeveniment:</label>
  <select class="form_edit" name="castellid">
	<option value="-1" selected>Selecciona castell</option>
<?php

	$sql="SELECT CASTELL_ID, C.NOM, E.NOM AS EVENT
	FROM CASTELL AS C
	INNER JOIN EVENT AS E ON E.EVENT_ID=C.EVENT_ID
	WHERE ESPLANTILLA=1
	ORDER BY EVENT, NOM";	
	
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result))
		{			
			echo "<option value=".$row["CASTELL_ID"].">".$row["NOM"]." - ".$row["EVENT"]."</option>";
		}
	}

	mysqli_close($conn);
?>
  </select>
<br><br>
  <button type="Submit" class="boto">GAAAASS</button>
</div>
</form>
   </body>
</html>

