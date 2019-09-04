<?php
header("Content-Type: application/json; charset=UTF-8");

// $id = intval($_GET['id']);
// $cs = intval($_GET['cs']);
// $ca = intval($_GET['ca']);
// $ps = intval($_GET['ps']);
// $lk = intval($_GET['lk']);
// $txt = strval($_GET['txt']);

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

$arry= [];
if (!empty($_GET["obj"]))
{
	$arry=json_decode($_GET["obj"], false);

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
			$sql=$sql."UPDATE CASTELL_POSICIO SET CASTELLER_ID=".$obj->ca.", TEXT='".GetStrDB($obj->txt)."' WHERE CASTELL_ID = ".$obj->id." AND CASELLA_ID=".$obj->cs." AND PESTANYA=".$obj->ps.";";
		}
		else
		{
			$sql=$sql."UPDATE CASTELL_POSICIO SET CASTELLER_ID=0, TEXT='".GetStrDB($obj->txt)."' WHERE CASTELL_ID = ".$obj->id." AND CASELLA_ID=".$obj->cs." AND PESTANYA=".$obj->ps.";";
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

}

if (!empty($_GET["n"]))
{
	$CastellId = intval($_GET["n"]);
	$sql="UPDATE CASTELL_POSICIO AS CP
		INNER JOIN CASTELL C ON C.CASTELL_ID = CP.CASTELL_ID
		SET CP.CASTELLER_ID=0, CP.TEXT=''
		WHERE C.CASTELL_ID=".$CastellId."
		AND CP.CASTELLER_ID > 0
		AND NOT EXISTS(SELECT * FROM INSCRITS I WHERE I.EVENT_ID=C.EVENT_ID AND I.CASTELLER_ID=CP.CASTELLER_ID AND I.ESTAT>0)";
			
	if (mysqli_multi_query($conn, $sql)) 
	{
		//echo "OK - ".$sql;
		echo "OK";
	} 
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
}

mysqli_close($conn);
?>