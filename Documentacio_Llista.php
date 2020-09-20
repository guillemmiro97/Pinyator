<html>
<head>
  <title>Pinyator - Fites</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>
<body style='background-color:#cce6ff;'>
<div style='position: fixed; z-index: -1; width: 90%; height: 80%;background-image: url("icons/Logo_Colla.gif");background-repeat: no-repeat; 
background-attachment: fixed;  background-position: center; opacity:0.4'>
</div>
<div>

<button class="boto" onclick="goBack()" ><i class="fa fa-arrow-left" style="font-size:24px;color:white"></i></button>
	

<?php 
$grup="";
$cookie_name = "marrec_inscripcio";
if (isset($_COOKIE[$cookie_name]))
{
	include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

	$sql="SELECT D.NOM, D.LINK, D.GRUP
	FROM DOCUMENTACIO AS D
	WHERE D.ESTAT=1
	ORDER BY D.GRUP, D.ORDRE";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		$row = mysqli_fetch_assoc($result);		
		mysqli_data_seek($result, 0);
		
		// output data of each row
		while($row = mysqli_fetch_assoc($result)) 
		{		
			if ($grup != $row["GRUP"])
			{
				echo "<h3>".$row["GRUP"]."</h3>";
				$grup = $row["GRUP"];
			}
			echo "<a href='".$row["LINK"]."'>".$row["NOM"]."</a><br>";	
		}

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
   <script>
function goBack() {
  window.history.back();
}
</script>
</html>