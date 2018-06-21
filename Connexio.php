<?php

	//$conn = mysqli_connect('localhost','marrecs_de_salt','tripleta_de_salt_38_28_58','marrecs_Pinyator');
	$conn = mysqli_connect('localhost','root','','marrecs_pinyator');
	if (!$conn) 
	{
		die('Could not connect: ' . mysqli_error($conn));
	}
?>	  
