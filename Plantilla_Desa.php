<html>
<head>
  <title>Pinyator</title>
</head>
<body>
<?php
$id=-1;
$nom="";
$estat=1;
$sql="";
$accio=0;
$H=700;
$W=1000;
$plantillaid=-1;
$ps1="";
$ps2="";
$ps3="";
$ps4="";

if (!empty($_POST['a']))
{
	$accio = intval($_POST['a']);
}
if (!empty($_POST['id']))
{
	$id = intval($_POST['id']);
}
if (!empty($_POST['nom']))
{
	$nom = strval($_POST['nom']);
}
if (!empty($_POST['estat']))
{
	$estat = intval($_POST['estat']);
}
if (!empty($_POST['W']))
{
	$W = intval($_POST['W']);
}
if (!empty($_POST['H']))
{
	$H = intval($_POST['H']);
}
if (!empty($_POST['plantillaid']))
{
	$plantillaid = intval($_POST['plantillaid']);
}
if (!empty($_POST['pestanya_1']))
{
	$ps1 = strval($_POST['pestanya_1']);
}
if (!empty($_POST['pestanya_2']))
{
	$ps2 = strval($_POST['pestanya_2']);
}
if (!empty($_POST['pestanya_3']))
{
	$ps3 = strval($_POST['pestanya_3']);
}
if (!empty($_POST['pestanya_4']))
{
	$ps4 = strval($_POST['pestanya_4']);
}


if (!empty($_GET['a']))
{
	$accio = intval($_GET['a']);
}
if (!empty($_GET['id']))
{
	$id = intval($_GET['id']);
}
if (!empty($_GET['nom']))
{
	$nom = strval($_GET['nom']);
}
if (!empty($_GET['estat']))
{
	$estat = intval($_GET['estat']);
}
if (!empty($_GET['W']))
{
	$W = intval($_GET['W']);
}
if (!empty($_GET['H']))
{
	$H = intval($_GET['H']);
}
if (!empty($_GET['pestanya_1']))
{
	$ps1 = strval($_GET['pestanya_1']);
}
if (!empty($_GET['pestanya_2']))
{
	$ps2 = strval($_GET['pestanya_2']);
}
if (!empty($_GET['pestanya_3']))
{
	$ps3 = strval($_GET['pestanya_3']);
}
if (!empty($_GET['pestanya_4']))
{
	$ps4 = strval($_GET['pestanya_4']);
}


include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

$nom = GetStrDB($nom);
$ps1 = GetStrDB($ps1);
$ps2 = GetStrDB($ps2);
$ps3 = GetStrDB($ps3);
$ps4 = GetStrDB($ps4);

if ($id > 0)
{
	if ($accio == 1)
	{
		$sql="DELETE FROM PLANTILLA_POSICIO WHERE PLANTILLA_ID = ".$id;
		if (mysqli_query($conn, $sql)) 
		{
			$sql="DELETE FROM PLANTILLA WHERE PLANTILLA_ID = ".$id;
		}
		else if (mysqli_error($conn) != "")
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			$sql="";
		}
	}
	else
	{
		$sql="UPDATE PLANTILLA SET NOM='".$nom."',ESTAT=".$estat.",H=".$H.",W=".$W."
		,PESTANYA_1='".$ps1."',PESTANYA_2='".$ps2."',PESTANYA_3='".$ps3."',PESTANYA_4='".$ps4."'
		WHERE PLANTILLA_ID = ".$id;
	}
}
else if ($nom <> "")
{
	$sql="INSERT INTO PLANTILLA(NOM, ESTAT, PESTANYA_1, PESTANYA_2, PESTANYA_3, PESTANYA_4) 
	VALUES ('".$nom."',".$estat.",'".$ps1."','".$ps2."','".$ps3."','".$ps4."')";
} 

if (($sql <> "") && mysqli_query($conn, $sql))
{
	if (($id > 0) && ($plantillaid == -1))
	{		
		echo $id;
		echo "<meta http-equiv='refresh' content='0; url=Plantilla.php'/>";
	}
	else
	{
		$id = mysqli_insert_id($conn);
		
		if ($plantillaid != -1)
		{
			$sql="INSERT INTO PLANTILLA_POSICIO(PLANTILLA_ID,CASELLA_ID,PESTANYA,POSICIO_ID,CORDO,X,Y,H,W,ANGLE,FORMA,TEXT,LINKAT,SEGUENT)
			SELECT ".$id.",CASELLA_ID,PESTANYA,POSICIO_ID,CORDO,X,Y,H,W,ANGLE,FORMA,TEXT,LINKAT,SEGUENT
			FROM PLANTILLA_POSICIO
			WHERE PLANTILLA_ID=".$plantillaid;
		
			if (mysqli_query($conn, $sql))
			{
				echo "<meta http-equiv='refresh' content='0; url=Plantilla.php'/>";
			}
			else if (mysqli_error($conn) != "")
			{
				echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
		}
		else
		{
			echo "<meta http-equiv='refresh' content='0; url=Plantilla.php'/>";
		}		
	}
} 
else if (mysqli_error($conn) != "")
{
	echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
<br>
<a href='Plantilla.php'>Torna a les plantilles.</a>
</body>
</html>