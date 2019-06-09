<?php
$cs = intval($_GET['cs']);
$p = intval($_GET['p']);
$po = intval($_GET['po']);
$c = intval($_GET['c']);
$x = intval($_GET['x']);
$y = intval($_GET['y']);
$h = intval($_GET['h']);
$w = intval($_GET['w']);
$a = intval($_GET['a']);
$f = intval($_GET['f']);
$t = strval($_GET['t']);
$ps = intval($_GET['ps']);
$sg = intval($_GET['sg']);
$lk = intval($_GET['lk']);

include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";

$t = GetStrDB($t);

if ($cs > 0)
{
	$sql="UPDATE PLANTILLA_POSICIO SET POSICIO_ID=".$po.",CORDO=".$c.",X=".$x.",Y=".$y.",H=".$h.",W=".$w.
	",ANGLE=".$a.",FORMA=".$f.",TEXT='".$t."',SEGUENT=".$sg.",LINKAT=".$lk.
	" WHERE PLANTILLA_ID = ".$p." AND CASELLA_ID=".$cs." AND PESTANYA=".$ps;
}
else
{
	$cs = 1;
	$sql="SELECT MAX(CASELLA_ID)+1 AS newcasellaid 
	FROM PLANTILLA_POSICIO
	WHERE PLANTILLA_ID=".$p." GROUP BY PLANTILLA_ID";
	
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		// output data of each row
		while($row = mysqli_fetch_assoc($result)) 
		{
			$cs = $row["newcasellaid"];
		}
	}
		
	
	$sql="INSERT INTO PLANTILLA_POSICIO(PLANTILLA_ID,CASELLA_ID,PESTANYA,POSICIO_ID,CORDO,X,Y,H,W,ANGLE,FORMA,TEXT,LINKAT,SEGUENT) 
	VALUES (".$p.",".$cs.",".$ps.",".$po.",".$c.",".$x.",".$y.",".$h.",".$w.",".$a.",".$f.",'".$t."',0,0 )";

} 

if (mysqli_query($conn, $sql)) 
{
    //echo "New record created successfully";
	if ($cs > 0)
	{		
		echo $cs;
	}

} 
else if (mysqli_error($conn) != "")
{
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>