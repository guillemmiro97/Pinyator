<html>
<head>
	<title>Pinyator</title>
	<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Head.php";?>
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<style>
td {padding:15px}
</style>
<body>
<?php $menu=10; include "$_SERVER[DOCUMENT_ROOT]/pinyator/Menu.php";

	include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";
	
	$temporada = "";
	$resolucioPantalla = "";
	$visualitzarFites = 0;
	$visualitzarPenya = 0;
	$visualitzarDiferencies = 0;
	$visualitzarPercentatgeAssistencia = 0;
				
	echo "<form method='post' action='Configuracio_Desa.php'>";

	$sql="SELECT TEMPORADA, RESOLUCIOPANTALLA, FITES, PARTICIPANTS, DIFERENCIES,
	PERCENATGEASSISTENCIA
	FROM CONFIGURACIO";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result))
		{
			$temporada = $row["TEMPORADA"];
			$resolucioPantalla = $row["RESOLUCIOPANTALLA"];
			$visualitzarFites = $row["FITES"];
			$visualitzarPenya = $row["PARTICIPANTS"];
			$visualitzarDiferencies = $row["DIFERENCIES"];
			$visualitzarPercentatgeAssistencia = $row["PERCENATGEASSISTENCIA"];				
		}
	}
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
?>
	
	<div style="position:absolute;left:20px;width:500px">
		<div class="form_group">
		<table width=300>
			<tr>
				<td>
					<a href="Fites.php" class="boto">Fites</a> &nbsp &nbsp Activar
					<label class="switch">texte
						<input type="checkbox" name="fites" value=1 <?php if ($visualitzarFites == 1) echo " checked";?>>
						<span class="slider round"></span>
					</label>
				</td>
			</tr><tr>
				<td>
					<a href="Documentacio.php" class="boto">Documentació</a>
				</td>
			</tr><tr>
				<td>
					<label>TEMPORADA</label>
					<input type="text" class="form_edit" name="temporada" value="<?php echo $temporada ?>" autofocus required>
				</td>
			</tr><tr>
				<td>
					<label>Resolució pantalla(amplada)</label>
					<input type="number" class="form_edit" name="resoluciopantalla" value="<?php echo $resolucioPantalla ?>"required>
				</td>
			</tr><tr>
				<td>
					<label>Visualitzar participants</label>
					<label class="switch">texte
						<input type="checkbox" name="participants" value=1 <?php if ($visualitzarPenya == 1) echo " checked";?>>
						<span class="slider round"></span>
					</label>
				</td>
			</tr><tr>
				<td>
					<label>Visualitzar diferències</label>
					<label class="switch">texte
						<input type="checkbox" name="diferencies" value=1 <?php if ($visualitzarDiferencies == 1) echo " checked";?>>
						<span class="slider round"></span>
					</label>
				</td>
			</tr><tr>
				<td>
					<label>Visualitzar % assistència</label>
					<label class="switch">texte
						<input type="checkbox" name="percentAssistencia" value=1 <?php if ($visualitzarPercentatgeAssistencia == 1) echo " checked";?>>
						<span class="slider round"></span>
					</label>
				</td>
			</tr><tr>
				<td>
					<button type="Submit" name= "Desa" value="desar" class="boto">Desa</button>
				</td>
			</tr>
		</table>
		</div>
	</div>
	</form>	
</body>
</html>