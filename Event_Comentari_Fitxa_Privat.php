<html>
<head>
  <title>Pinyator - Inscrits esdeveniment</title>
  <meta charset="utf-8">
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Head.php";?>  
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body>
<?php $menu=2; include "$_SERVER[DOCUMENT_ROOT]/pinyator/Menu.php"; 

$id = intval($_GET["id"]);

?>
	<div>
	</div> 
	<table class='butons'>
		<tr class='butons'>
			<th class='butons'><a href="Event.php" class="boto" >Torna als esdeveniment</a></th>
		</tr>
	</table>

	<br>
<?php
	$nom = "";
	$url="Event_Comentari_Privat";
	include "$_SERVER[DOCUMENT_ROOT]/pinyator/Event_Comentari_Fitxa.php";
?>
   </body>
</html>

