<?php
session_start();
if ($_SESSION["carrec"] == "1")
{
	$_SESSION["carrec"] = 2;
}
else
{
	$_SESSION["carrec"] = 1;
}
$id= strval($_GET['id']);
$a="";
if (!empty($_GET['a']))
	$a="&a=".strval($_GET['a']);

echo "<meta http-equiv='refresh' content='0; url=Castell_Fitxa.php?id=".$id.$a."'/>";
?>
