<html>
<head>
	<title>Pinyator</title>
	<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Head.php";?>
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body>
<?php $menu=1; include "$_SERVER[DOCUMENT_ROOT]/pinyator/Menu.php";

	include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

	
	$temporada = "";
	$resolucioPantalla = "";

				
	echo "<form method='post' action='Configuracio_Desa.php'>";

	$sql="SELECT TEMPORADA, RESOLUCIOPANTALLA
	FROM CONFIGURACIO";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result))
		{
			$temporada = $row["TEMPORADA"];
			$resolucioPantalla = $row["RESOLUCIOPANTALLA"];				
		}
	}
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}

?>
	
	<div style="position:absolute;left:20px;width:500px">
		<div class="form_group">
			<label>TEMPORADA</label>
			<input type="text" class="form_edit" name="temporada" value="<?php echo $temporada ?>" autofocus required>
			<br><br>
			<label>Resolució pantalla(amplada)</label>
			<input type="number" class="form_edit" name="resoluciopantalla" value="<?php echo $resolucioPantalla ?>"required>
			<br><br>
			<button type="Submit" name= "Desa" value="desar" class="boto">Desa</button>
		</div>
	</div>
	</form>
</body>
</html>

