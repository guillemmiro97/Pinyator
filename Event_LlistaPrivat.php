<html>
<head>
  <title>Pinyator - Inscrits esdeveniment</title>
  <meta charset="utf-8">
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Head.php";?>  
</head>
<script src="llibreria/inscripcio.js"></script>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body>
<?php $menu=2; include "$_SERVER[DOCUMENT_ROOT]/pinyator/Menu.php"; 

$estat = intval($_GET["e"]);
$id = intval($_GET["id"]);
$nom = "";
$public = false;

$link = "id=".$id."&e=".$estat;
$esEditable = 1;
?>
	<div>
	</div> 
	<table class='butons'>
		<tr class='butons'>
			<th class='butons'><a href="Event.php?e=<?php echo $estat?>" class="boto" >Torna</a></th>
			<th></th>
			
			<th class='butons'>
				Ordena per:
				<a href="Event_LlistaPrivat.php?<?php echo $link?>"  class="boto" >Malnom</a>
				<a href="Event_LlistaPrivat.php?<?php echo $link?>&o=ESTAT" class="boto" >Estat</a>
			</th>
		</tr>
	</table>

	<br>
<?php
	include "$_SERVER[DOCUMENT_ROOT]/pinyator/Event_Llista.php";
?>
   </body>
</html>

