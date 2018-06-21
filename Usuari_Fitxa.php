<html>
<head>
	<title>Pinyator</title>
	<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Head.php";?>
	<script src="llibreria/popup_esborra.js"></script>
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body class="popup">
<?php $menu=6; include "$_SERVER[DOCUMENT_ROOT]/pinyator/Menu.php";

	include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

	$id = 0;
	if (!empty($_GET['id']))
	{	
		$id = intval($_GET['id']);
	}

	$password = "";
	$nom = "";
	$carrec = "";
	$esadmin = 0;
	$segcasteller = 0;
	$segcastell = 0;
	$segboss = 0;
	$segevent = 0;
	$count = 0;
	$checked="";
	$disabled="";

	if ($id > 0)
	{
		$sql="SELECT NOM, PASSWORD, CARREC,
		SEGADMIN, SEGCASTELLER, SEGEVENT, SEGCASTELL, SEGBOSS
		FROM USUARIS
		WHERE IDUSUARI = ".$id;

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) 
		{
			while($row = mysqli_fetch_assoc($result))
			{
				$nom = $row["NOM"];
				$password = $row["PASSWORD"];				
				$carrec = $row["CARREC"];
				$esadmin = $row["SEGADMIN"];
				$segcasteller = $row["SEGCASTELLER"];
				$segevent = $row["SEGEVENT"];
				$segcastell = $row["SEGCASTELL"];
				$segboss = $row["SEGBOSS"];
			}
		}
		else if (mysqli_error($conn) != "")
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		
		
		$sql="SELECT COUNT(*) AS NUM
		FROM USUARIS
		WHERE SEGADMIN > 0";

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) 
		{
			while($row = mysqli_fetch_assoc($result))
			{
				$count = $row["NUM"];
			}
		}
		else if (mysqli_error($conn) != "")
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		
		if ($esadmin > 0)
		{ 
			$checked=" checked "; 
			if ($count<2) 
				$disabled=" disabled";
		}		
	}
?>
	
<table class="butons">
	<tr>
		<th><a href="Usuari.php" class="boto" >Torna als usuaris</a></th>
		<th></th>
		<?php 
			if ($disabled=="") 
				echo "<th><button name='Usuari_Esborra.php?id=".$id."' class='boto boto_remove' onClick='ShowPopup(this)'>Esborra</a></th>";
		?>
		<th></th>
	</tr>
</table>
<br>
<form method='post' action='Usuari_Desa.php'>
	<div class="form_group">
		<label>ID</label>
		<input type="text" class="form_edit" name="id" value="<?php echo $id ?>" readonly>
		<br><br>
		<label>Nom</label>
		<input type="text" class="form_edit" name="nom" value="<?php echo $nom ?>" autofocus required>
		<br><br>
		<label>Password</label>
		<input type="password" class="form_edit" name="password" value="<?php echo $password ?>" required>
		<br><br>
		<label>Càrrec</label>
		<label for="sel1">Posicio tronc:</label>
		<select class="form_edit" name="carrec">
		<option value=0>Sense càrrec</option>
		<option value=1 <?php if($carrec==1) echo "selected";?>>Pinyes</option>
		<option value=2 <?php if($carrec==2) echo "selected";?>>Troncs</option>
		</select>
		<br><br>
		<h3>Seguretats</h3>
		<label>Administrador</label>
		<input type="checkbox" name="esadmin" value="1" <?php echo $checked; echo $disabled;?>>
		<br><br>
		<label for="sel1">Esdeveniments</label>
		<select class="form_edit" name="segevent">
		<option value=0>Sense accés</option>
		<option value=1 <?php if($segevent==1) echo "selected";?>>Lectura</option>
		<option value=2 <?php if($segevent==2) echo "selected";?>>Escrtura</option>
		</select>
		<br>
		<label for="sel1">Castellers</label>
		<select class="form_edit" name="segcasteller">
		<option value=0>Sense accés</option>
		<option value=1 <?php if($segcasteller==1) echo "selected";?>>Lectura</option>
		<option value=2 <?php if($segcasteller==2) echo "selected";?>>Escrtura</option>
		</select>
		<br>
		<label for="sel1">Castells</label>
		<select class="form_edit" name="segcastell">
		<option value=0>Sense accés</option>
		<option value=1 <?php if($segcastell==1) echo "selected";?>>Lectura</option>
		<option value=2 <?php if($segcastell==2) echo "selected";?>>Escrtura</option>
		</select>
		<br>
		<label for="sel1">Cap de colla</label>
		<select class="form_edit" name="segboss">
		<option value=0>Sense accés</option>
		<option value=1 <?php if($segboss==1) echo "selected";?>>Lectura</option>
		<option value=2 <?php if($segboss==2) echo "selected";?>>Escrtura</option>
		</select>
		<br><br>
		<button type="Submit" class="boto">Desa</button>
	</div>
	</form>
	<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Popup_Esborrar.php"?>
</body>
</html>

