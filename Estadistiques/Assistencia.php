<html>
<head>
  <script src="../llibreria/graphics.js"></script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<body>
<?php include "../Connexio.php";?>
<h3>Assistència</h3>
	<div id="chart_div"  style="height: 500px"></div>
	<span id="txtHint"></span>
</body>
<?php	
$temporada=strval($_GET["t"]);

$i = 1;
$assaigs=array();
$assaigsData=array();
$assaigsNom=array();
$temporades=array();
$str="";

	$sql="SELECT DISTINCT E.TEMPORADA
			FROM EVENT AS E 
			LEFT JOIN INSCRITS AS I ON I.EVENT_ID=E.EVENT_ID
			WHERE E.TIPUS = 0
			AND E.ESTAT >= 0
			AND (E.TEMPORADA = '".$temporada."' OR '0' = '".$temporada."') 
			AND (EVENT_PARE_ID IS NULL OR EVENT_PARE_ID < 1)";

	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) > 0) 
	{
		array_push($temporades, array_fill(1, mysqli_num_rows($result),""));
		while($row = mysqli_fetch_assoc($result)) 
		{
			$temporades[$i] = $row["TEMPORADA"];
			$i++;
		}
	}


	$sql="SELECT *,
			@running:=if(@previous=E.DATA,@running,@running+1) as rownum,
			@previous:=E.DATA as prev
		FROM (
			SELECT CONCAT(DAY(DATA),'/',MONTH(DATA)) AS DATA, E.NOM, E.TEMPORADA, 
			SUM(IF(I.ESTAT > 0, 1, 0)) AS Y
			FROM EVENT AS E 
			LEFT JOIN INSCRITS AS I ON I.EVENT_ID=E.EVENT_ID
			LEFT JOIN CASTELLER AS C ON C.CASTELLER_ID=I.CASTELLER_ID
			LEFT JOIN POSICIO AS P ON P.POSICIO_ID=C.POSICIO_PINYA_ID
			JOIN    (SELECT @running := 0) r
			JOIN    (SELECT @previous := '') r2
			WHERE E.TIPUS = 0
			AND E.ESTAT >= 0
			AND (E.TEMPORADA = '".$temporada."' OR '0' = '".$temporada."') 
			AND (EVENT_PARE_ID IS NULL OR EVENT_PARE_ID < 1)
			AND (P.ESNUCLI=1 OR P.ESTRONC=1 OR P.ESCORDO=1)
			GROUP BY E.TEMPORADA, E.DATA, E.NOM
			ORDER BY MONTH(E.DATA), DAY(E.DATA)) AS E
			";

	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) > 0) 
	{
		array_push($assaigsData, array_fill(1, 300, ''));
		array_push($assaigs, array_fill(1, 300, array_fill(1, count($temporades), 0)));
		array_push($assaigsNom, array_fill(1, 300, array_fill(1, count($temporades), 0)));
		
		/*for($x = 1; $x < count($assaigs); $x++)
		{
			for($y = 1; $y < 11; $y++)
			{
				$assaigs[$x][$y]=0;
			}
		}*/
		
		
		echo "<script> google.charts.load('current', {packages: ['corechart']});
google.charts.setOnLoadCallback(drawBasic);

function drawBasic() {

		var data = new google.visualization.DataTable();
		data.addColumn('string', 'X');";
		for($x = 1; $x < count($temporades); $x++)
		{
			echo "data.addColumn('number', '".$temporades[$x]."');";
			echo "data.addColumn({type: 'number', role: 'annotation'});";
			echo "data.addColumn({type: 'string', role: 'tooltip'});";
		}


		
		echo "data.addRows([";
		// output data of each row
		while($row = mysqli_fetch_assoc($result)) 
		{
			$sy = array_search($row["TEMPORADA"], $temporades);
			$sx = intval($row["rownum"]);
			$assaigs[$sx][$sy] = $row["Y"];
			$assaigsNom[$sx][$sy] = $row["NOM"];
			$assaigsData[$sx] = $row["DATA"];
		}
		
		for($x = 1; $x < count($assaigs); $x++)
		{
			$str = "'".$assaigsData[$x]."'";
			for($y = 1; $y < count($temporades); $y++)
			{
				$str .= ",".(empty($assaigs[$x][$y])?"":$assaigs[$x][$y]);
				$str .= ",".(empty($assaigs[$x][$y])?"":$assaigs[$x][$y]);
				$str .= ",'".(empty($assaigsNom[$x][$y])?"":$assaigsNom[$x][$y])."'";
			}

			if ($x == 1)
			{
				echo "[".$str."]";
			}
			else
			{
				echo ",[".$str."]";
			}
		}
		echo "]);

      var options = {
		interpolateNulls: true,
		fontSize:13,
        hAxis: {
          title: 'Assaig',
		  showTextEvery: 0
        },
        vAxis: {
          title: 'Inscrits'
        }
      };

      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

      chart.draw(data, options);
    }";
		echo "</script>";
	}
	else if (mysqli_error($conn) != "")
	{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
	

	mysqli_close($conn);
?>
</html>