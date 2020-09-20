<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
  <title>Pinyator - Fites</title>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Head.php";?>
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body>
<?php $menu=10; include "$_SERVER[DOCUMENT_ROOT]/pinyator/Menu.php";?>
<?php


$id = 0;
if (!empty($_GET['id']))
{	
	$id = intval($_GET['id']);
}

$nom = "";
$link = "";
$estat = 0;
$grup="";
$data="";
$ordre=0;

$autofocus = "autofocus";

echo "<form method='post' action='Documentacio_Desa.php'>";

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

if ($id > 0)
{
	$sql="SELECT D.DOCUMENTACIO_ID, D.NOM,
	D.ESTAT, D.GRUP, D.LINK, D.ORDRE, D.DATA
	FROM DOCUMENTACIO AS D
	WHERE D.DOCUMENTACIO_ID = ".$id;

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result))
		{
			$nom = $row["NOM"];
			$link = $row["LINK"];
			$estat = $row["ESTAT"];
			$grup = $row["GRUP"];
			$ordre = $row["ORDRE"];
			$data = $row["DATA"];
		}
	}
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
}

?>
<div>
  <a href="Documentacio.php" class="boto" >Torna</a>
</div> 
<br>
<div style="position:absolute;width:500px">
  <div class="form_group">
  <table width=100%>
  <tr>
	<th>
		<label>ID</label>
	</th>
  </tr>
  <tr>
	<td>
		<input type="text" class="form_edit" name="id" value="<?php echo $id ?>" readonly>
	</td>
  </tr>
  </table>
<br><br>
	<label>Grup</label>
	<input type="text" class="form_edit" name="grup" value="<?php echo $grup ?>" <?php echo $autofocus ?>>
<br><br>
	<label>Ordre</label>
	<input type="text" class="form_edit" name="ordre" value="<?php echo $ordre ?>" required <?php echo $autofocus ?>>
<br><br>
	<label>Nom</label>
	<input type="text" class="form_edit" name="nom" value="<?php echo $nom ?>" required <?php echo $autofocus ?>>
<br><br>
	<label>Link</label>
	<input type="text" class="form_edit" name="link" value="<?php echo $link ?>" required <?php echo $autofocus ?>>
<br><br>
	<label>Estat</label><br>
	<label class="radio-inline"><input type="radio" name="estat" <?php if($estat==0) echo"checked"?> value=0>No actiu</label>
	<label class="radio-inline"><input type="radio" name="estat" <?php if($estat==1) echo"checked"?> value=1>Actiu</label>
<br><br>
  <button type="Submit" class="boto">Desa</button>
</div>   
</div> 
</form>
   </body>
</html>

