<?php
	$conn = mysqli_connect('localhost','pinyes','P1ny35','marrecs_pinyator');
	if (!$conn) 
	{
		die('Could not connect: ' . mysqli_error($conn));
	}
?>