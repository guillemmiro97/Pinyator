<html>
<head>
  <script src="../llibreria/graphics.js"></script>
<body>
<?php include "../Connexio.php";?>
<h3>Assistència últims 10 assaigs</h3>
	<div style="position:static" height="100%" width="100%">
		<canvas id="canvas1" style="border:1px solid" height="300" width="1200">
			This text is displayed if your browser does not support HTML5 Canvas.
		</canvas>
	</div>
	<span id="txtHint"></span>
</body>
<?php
	
	$sql="SELECT * 
	FROM (
		SELECT E.DATA, E.NOM, SUM(IFNULL(I.ESTAT, 0)) AS Y
		FROM EVENT AS E 
		LEFT JOIN INSCRITS AS I ON I.EVENT_ID=E.EVENT_ID
		WHERE E.TIPUS = 0
		AND E.ESTAT >= 0
		AND (EVENT_PARE_ID IS NULL OR EVENT_PARE_ID < 1)
		GROUP BY E.DATA, E.NOM
		ORDER BY E.DATA DESC
		LIMIT 10
	)AS T1 ORDER BY DATA";

	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) > 0) 
	{
		$i = 1;
		echo "<script>";
		// output data of each row
		while($row = mysqli_fetch_assoc($result)) 
		{
			echo "addPoint(".$i.",".$row["Y"].",'".$row["NOM"]."');";
			$i=$i+1;
		}
		echo "initCanvas('canvas1');";
		echo "</script>";
	}
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}

	mysqli_close($conn);
?>
</html>

