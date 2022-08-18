<html>
<head>
	<title>Pinyator</title>
	<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Head.php";?>
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body>
<?php $menu=7; include "$_SERVER[DOCUMENT_ROOT]/pinyator/Menu.php";

	include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

	$id = 0;
	if (!empty($_GET['id']))
	{	
		$id = intval($_GET['id']);
	}

	$nom = "";
	$esnucli = 0;
	$escordo = 0;
	$estronc = 0;
	$esfolre = 0;
	$escanalla = 0;
	
	echo "<form method='post' action='Posicio_Desa.php'>";

	if ($id > 0)
	{
		$sql="SELECT NOM, ESNUCLI, ESCORDO, ESTRONC, ESFOLRE, COLORFONS, COLORTEXT, ESCANALLA, COLORCAMISA
		FROM POSICIO
		WHERE POSICIO_ID = ".$id;

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) 
		{
			while($row = mysqli_fetch_assoc($result))
			{
				$nom = $row["NOM"];
				$esnucli = $row["ESNUCLI"];
				$escordo = $row["ESCORDO"];
				$estronc = $row["ESTRONC"];
				$esfolre = $row["ESFOLRE"];
				$escanalla = $row["ESCANALLA"];
				$colorfons = $row["COLORFONS"];
				$colortext = $row["COLORTEXT"];
				$colorcamisa = $row["COLORCAMISA"];
			}
		}
		else if (mysqli_error($conn) != "")
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	
	}
?>
	<div>
		<table class="butons">
			<tr>
				<th><a href="Posicio.php" class="boto" >Torna</a></th>
				<th></th>
				<th></th>
			</tr>
		</table>
	</div> 
	<br>
	<div class="form_group">
		<label>ID</label>
		<input type="text" class="form_edit" name="id" value="<?php echo $id ?>" readonly>
		<br><br>
		<label>Nom</label>
		<input type="text" class="form_edit" name="nom" value="<?php echo $nom ?>" autofocus required>
		<br><br>
		<label>Color base</label>
		<input name="colorfons" class="form_edit" value="<?php echo $colorfons ?>" style="width:5%;" type="color">
		<br><br>
		<label>Color text</label>
		<input name="colortext" class="form_edit" value="<?php echo $colortext ?>" style="width:5%;" type="color">
		<br><br>
		<label>Color text camisa</label>
		<input name="colorcamisa" class="form_edit" value="<?php echo $colorcamisa ?>" style="width:5%;" type="color">
		<br><br>
		<label>Es nucli</label>
		<label class="switch">texte
			<input type="checkbox" name="esnucli" value=1 <?php if ($esnucli == 1) echo " checked";?>>
			<span class="slider round"></span>
		</label>
		<br><br>
		<label>Es cordo</label>
		<label class="switch">texte
			<input type="checkbox" name="escordo" value=1 <?php if ($escordo == 1) echo " checked";?>>
			<span class="slider round"></span>
		</label>
		<br><br>
		<label>Es tronc</label>
		<label class="switch">texte
			<input type="checkbox" name="estronc" value=1 <?php if ($estronc == 1) echo " checked";?>>
			<span class="slider round"></span>
		</label>
		<br><br>
		<label>Es folre</label>
		<label class="switch">texte
			<input type="checkbox" name="esfolre" value=1 <?php if ($esfolre == 1) echo " checked";?>>
			<span class="slider round"></span>
		</label>
		<br><br>
		<label>Es canalla</label>
		<label class="switch">texte
			<input type="checkbox" name="escanalla" value=1 <?php if ($escanalla == 1) echo " checked";?>>
			<span class="slider round"></span>
		</label>
		<br><br>
		<button type="Submit" class="boto">Desa</button>
	</div>
	</form>
</body>
</html>

