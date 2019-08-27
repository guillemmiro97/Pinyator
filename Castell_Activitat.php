<?php
$id=-1;
$accio=0;
$usuari="";
session_start();

if(!empty($_GET["id"]))
{
	$id = intval($_GET["id"]);
}

if(!empty($_GET["u"]))
{
	$usuari = strval($_GET["u"]);
}

if(!empty($_GET["a"]))
{
	$accio = intval($_GET["a"]);
}

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

if ($id > 0)
{
	PurgaRegistres($conn);
	
	$usuari = GetStrDB($usuari);
	if ($accio==1)
	{
		InsertaCastellRegistre($conn, $id, $usuari);
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