<?php
	
$id=-1;	
$accio=0;	
$usuari="";	
session_start();	

	
if(!empty($_GET["id"]))	
{	
	$id = intval($_GET["id"]);	
}	

if(!empty($_GET["a"]))	
{	
	$accio = intval($_GET["a"]);	
}
	
include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

	
if ($id > 0)	
{	
	$usuari = $_SESSION["usuari"];
	if ($accio==1)	
	{	
		$sql="INSERT IGNORE INTO CASTELL_REGISTRE (CASTELL_ID, USUARI) VALUES(".$id.",'".$usuari."')";
	
		mysqli_query($conn, $sql);
	
		if (mysqli_error($conn) != "")	
		{	
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	
		}
	}
	else if ($accio==2)	
	{	
		$usuaris = "";
		$enter = "";
		$sql="SELECT USUARI FROM CASTELL_REGISTRE WHERE CASTELL_ID = ".$id." AND USUARI != '".$usuari."'";
	
		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) 
		{
			// output data of each row
			while($row = mysqli_fetch_assoc($result)) 
			{
				$usuaris = $usuaris.$enter.$row["USUARI"];
				$enter = "<br>";
			}
		}
		else if (mysqli_error($conn) != "")
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	
		echo $usuaris;
	}	
	else if ($accio==-1)	
	{	
		$sql="DELETE FROM CASTELL_REGISTRE WHERE CASTELL_ID = ".$id." AND USUARI = '".$usuari."'";
	
		mysqli_query($conn, $sql);
	
		if (mysqli_error($conn) != "")	
		{	
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	
			$sql="";	
		}	
	}	
	else if ($accio==-10)	
	{	
		$sql="DELETE FROM CASTELL_REGISTRE WHERE CASTELL_ID = ".$id;
	
		mysqli_query($conn, $sql);
	
		if (mysqli_error($conn) != "")	
		{	
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	
			$sql="";	
		}	
	}	
	else	
	{
	
	}	
}
	
mysqli_close($conn);
	
?>