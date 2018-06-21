<?php
header("Content-Type: application/json; charset=UTF-8");

// $id = intval($_GET['id']);
// $cs = intval($_GET['cs']);
// $ca = intval($_GET['ca']);
// $ps = intval($_GET['ps']);
// $lk = intval($_GET['lk']);
// $txt = strval($_GET['txt']);

$arry=json_decode($_GET["obj"], false);

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

$sql="";
for ($x = 0; $x < count($arry); $x++)
{
	$obj = $arry[$x];
	if ($obj->ca > 0)
	{
		$sql1="";
		$sql2="";
		if($x==0)
		{
			$sql1="UPDATE CASTELL_POSICIO SET CASTELLER_ID=0 WHERE CASTELL_ID = ".$obj->id." AND CASTELLER_ID=".$obj->ca.";";
		}
		$sql2="UPDATE CASTELL_POSICIO SET CASTELLER_ID=".$obj->ca." WHERE CASTELL_ID = ".$obj->id." AND CASELLA_ID=".$obj->cs." AND PESTANYA=".$obj->ps.";";
		$sql=$sql.$sql1.$sql2;
	}
	elseif ($obj->ca <= -99)
	{
		$sql=$sql."UPDATE CASTELL_POSICIO SET CASTELLER_ID=".$obj->ca.", TEXT='".$obj->txt."' WHERE CASTELL_ID = ".$obj->id." AND CASELLA_ID=".$obj->cs." AND PESTANYA=".$obj->ps.";";
	}
	else
	{
		$sql=$sql."UPDATE CASTELL_POSICIO SET CASTELLER_ID=0, TEXT='".$obj->txt."' WHERE CASTELL_ID = ".$obj->id." AND CASELLA_ID=".$obj->cs." AND PESTANYA=".$obj->ps.";";
	}
}

if (mysqli_multi_query($conn, $sql)) 
{
	//echo "OK - ".$sql;
	echo "OK";
} 
else if (mysqli_error($conn) != "")
{
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>