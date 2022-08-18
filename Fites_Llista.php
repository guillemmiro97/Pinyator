<html>
<head>
  <title>Pinyator - Fites</title>
  <meta charset="utf-8">
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<style>
	table
	{
		border-collapse:separate; 
        border-spacing:0 15px;
	}
	td
	{
		padding:10px;
		height:80px;
		border-radius: 10px;
	}	
	td.fita
	{
		background-color:lemonchiffon;
		border: 2px solid #ff9933;
	}	
	td.done
	{
		background-color:palegreen;
		border: 2px solid 	mediumseagreen;
	}
	td.not
	{
		background-color:Silver;
		border: 2px solid darkgray;
		color: White;
	}
</style>
<body style='background-color:#cce6ff;'>
<div style='position: fixed; z-index: -1; width: 90%; height: 80%;background-image: url("icons/trofeo.png");background-repeat: no-repeat; 
background-attachment: fixed;  background-position: center; opacity:0.4'>
</div>
<div>

<h2>Fites</h2>

<?php 
$cookie_name = "marrec_inscripcio";
if (isset($_COOKIE[$cookie_name]))
{

	include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

	$temporada = "";
	$sql="SELECT C.TEMPORADA
	FROM CONFIGURACIO AS C";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		$row = mysqli_fetch_assoc($result);
		$temporada = $row["TEMPORADA"];
	}
	
	$sql="SELECT E.NOM, E.PERCENTATGE, E.ESTAT, E.RECOMPENSA
	FROM FITES AS E
	WHERE E.TEMPORADA=".$temporada."
	ORDER BY E.ORDRE";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		$row = mysqli_fetch_assoc($result);		
		mysqli_data_seek($result, 0);
		
		echo "<table>";
		
		// output data of each row
		while($row = mysqli_fetch_assoc($result)) 
		{			
			if ($row["ESTAT"] == 1)
			{
				$estat = "done";
			}
			else
			{
				$estat = "not";
			}
			
			echo "<tr>
			<td class='fita'><b>".$row["NOM"]."</b></td>
			<td>&nbsp</td>
			<td class='".$estat."' align='center'><b>".$row["RECOMPENSA"]."</b></td>
			</tr>";	
		}
		echo "</table>";
	}
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
	
	mysqli_close($conn);
}
else
{
	echo "<meta http-equiv='refresh' content='0; url=Apuntat.php'/>";	
}
?>	  
</div>
   </body> 
</html>