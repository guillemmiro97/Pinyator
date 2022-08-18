<?php
	$conn = mysqli_connect('localhost','root','password','pinyator');
	if (!$conn) 
	{
		die('Could not connect: ' . mysqli_error($conn));
	}
	
function GetStrDB($str)
{	
    return addslashes(NotInjection($str));
}

function NotInjection($str)
{	
	$str = str_replace("update", "", $str);
	$str = str_replace("delete", "", $str);
	$str = str_replace(" from ", "", $str);
	$str = str_replace("truncate", "", $str);
	$str = str_replace("drop ", "", $str);
	$str = str_replace("create ", "", $str);
	$str = str_replace("alter ", "", $str);
    return str_replace("select ", "", $str);
}

function GetFormatedDate($date, $format = 'Ymd')
{
	$combinedDT = date('Y-m-d', strtotime("$date"));
	$d = new DateTime($combinedDT);
	$data = $d->format($format);
	if ($data == "19700101")
		return "NULL";
	return "'".$data."'";
}

function GetFormatedDateTime($date, $time, $format = 'YmdHis')
{
	$combinedDT = date('Y-m-d H:i:s', strtotime("$date $time"));
    $d = new DateTime($combinedDT);
    return $d->format($format);
}

function GetLastId($conne)
{
	$sql = "SELECT LAST_INSERT_ID() as ID";
	$result = mysqli_query($conne, $sql);

	if (mysqli_num_rows($result) > 0) 
	{		
		// output data of each row
		while($row = mysqli_fetch_assoc($result)) 
		{	
			return $row["ID"];
		}
	}
	return -1;
}
?>
