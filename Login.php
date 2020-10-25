<?php

session_start();

$error = "";
$loged = false;

if($_SERVER["REQUEST_METHOD"] == "POST") 
{
	session_unset();
	session_destroy();
	session_start();
	// username and password sent from form       
	$myusername = $_POST['uname'];
	$mypassword = $_POST['psw'];

	include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";	  

	$sql = "SELECT NOM, CARREC, SEGADMIN, SEGCASTELLER, SEGEVENT, SEGCASTELL, SEGBOSS 
	FROM USUARIS 
	WHERE nom = '".$myusername."' and password = '".$mypassword."'";
	$result = mysqli_query($conn, $sql);

	// If result matched $myusername and $mypassword, table row must be 1 row

	if(mysqli_num_rows($result) == 1) 
	{			
		while($row = mysqli_fetch_assoc($result)) 
		{
			$_SESSION["usuari"] = $row["NOM"];
			$_SESSION["carrec"] = $row["CARREC"];
			
			$_SESSION["casteller"] = $row["SEGCASTELLER"] ;
			$_SESSION["event"] = $row["SEGEVENT"];
			$_SESSION["castell"] = $row["SEGCASTELL"];
			$_SESSION["boss"] =$row["SEGBOSS"];
			
			if ($row["SEGADMIN"] == "1")
			{
				$_SESSION["admin"] = 2;
				$_SESSION["casteller"] = 2;
				$_SESSION["event"] = 2;
				$_SESSION["castell"] = 2;
				$_SESSION["boss"] = 2;
			}			
		}			
		//header("location: Pinyator.php");
		$loged = true;
	}
	else 
	{
		$_SESSION["usuari"] = "";
		$_SESSION["admin"] = "";
		$error = "Your Login Name or Password is invalid";
	}
}
?>

<html>
<head>
<title>Pinyator - Login</title>
<meta charset="utf-8">
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/HeadLogin.php";
include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";
?>   
</head>
<style>

	/* Bordered form */
	form {
		border: 3px solid #f1f1f1;
	}

	/* Full-width inputs */
	input[type=text], input[type=password] {
		width: 100%;
		padding: 12px 20px;
		margin: 8px 0;
		display: inline-block;
		border: 1px solid #ccc;
		box-sizing: border-box;
	}

	/* Set a style for all buttons */
	button {
		background-color: #4CAF50;
		color: white;
		padding: 14px 20px;
		margin: 8px 0;
		border: none;
		cursor: pointer;
		width: 100%;
	}

	/* Add a hover effect for buttons */
	button:hover {
		opacity: 0.8;
	}

	/* Extra style for the cancel button (red) */
	.cancelbtn {
		width: auto;
		padding: 10px 18px;
		background-color: #f44336;
	}

	/* Center the avatar image inside this container */
	.imgcontainer {
		text-align: center;
		margin: 24px 0 12px 0;
	}

	/* Avatar image */
	img.avatar {
		width: 40%;
		border-radius: 50%;
	}

	/* Add padding to containers */
	.container {
		padding: 16px;
	}

	/* The "Forgot password" text */
	span.psw {
		float: right;
		padding-top: 16px;
	}

	/* Change styles for span and cancel button on extra small screens */
	@media screen and (max-width: 300px) {
		span.psw {
			display: block;
			float: none;
		}
		.cancelbtn {
			width: 100%;
		}
	}
</style>

<body>
<?php $menu=-1; include "$_SERVER[DOCUMENT_ROOT]/pinyator/Menu.php";
if ($loged == true)
{
	echo "<meta http-equiv='refresh' content='0; url=Pinyator.php'/>";
}
?>
	<form method="post" action="Login.php">
		<div class="container">
			<label><b><?php echo $error;?></b></label>
			<br>
			<label><b>Username</b></label>
			<input type="text" placeholder="Enter Username" name="uname" required autofocus>
			<label><b>Password</b></label>
			<input type="password" placeholder="Enter Password" name="psw" required>
			<button type="submit">Login</button>
		</div>
	</form>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Politica_Cookies.php";?>
</body>
</html>