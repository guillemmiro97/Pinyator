<html>
<head>
  <title>Pinyator - Inscrits esdeveniment</title>
  <meta charset="utf-8">
    <?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
    <script src="llibreria/popup.js"></script>
</head>

<body class="popup">
<?php  
	$id = intval($_GET["id"]);
	$nom = strval($_GET["nom"]); ?>
	
	<table class='butons'>
		<tr class='butons'>
			<th class='butons'><a href="Apuntat.php" class="boto" >Torna</a>
			<a href="Event_Comentari_Fitxa_Public.php?id=<?php echo $id?>&nom=<?php echo $nom?>" class="boto" >Nou</a></th>
		</tr>
	</table>

	<?php 	
include "Event_Comentari_Llista.php";
?>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Popup.php";?>
</body>
</html>

