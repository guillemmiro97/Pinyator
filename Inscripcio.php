<?php
if (!empty($_GET['id']))
{
	$cookie_name = "marrec_inscripcio";
	$cookie_value = strval($_GET['id']);
	if((isset($_COOKIE[$cookie_name])) && ($_COOKIE[$cookie_name] != $cookie_value)) 
	{		
		unset($_COOKIE[$cookie_name]);	
		setcookie($cookie_name, $cookie_value, -1, "/"); // 86400 = 1 day
	}
	else
	{
		setcookie($cookie_name, $cookie_value, time() + (86400 * 320), "/"); // 86400 = 1 day	
	}
}
?>

<html id="engrescatsApuntatHtml">
<head>
  <title>Pinyator</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="apple-touch-icon" sizes="111x192" href="icons\logo192.png">
  <link rel="icon" sizes="111x192" href="icons\logo192.png">
  <script src="llibreria/inscripcio.js?v=1.7"></script>
  <script src="llibreria/Cookies.js?v=1.1"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<?php include "$_SERVER[DOCUMENT_ROOT]/pinyator/Style.php";?>

<script>
$(document).unload = function(){window.location.reload();};
</script>

<body id="engrescatsApuntatBody">

<canvas id="strikePopUpFirework"></canvas>
<div id="strikePopUpConfeti">
</div>



<header id="engrescatsApuntatHeader">
    <article id="headerButtons">
        <section>
            <a href="Apuntat.php?reset=1">
                <div class="buttonLink">
                    <p>No sóc jo</p>
                </div>
            </a>
        </section>
        <section>
            <a href="Documentacio_Llista.php">
                <div class="buttonLink">
                    <p>Documentació</p>
                </div>
            </a>
        </section>
    </article>
    <article id="headerSocial">
        <section>
            <a href="https://www.youtube.com/channel/UClYwGl4Cz0G99akjFT0BKDw">
                <div id="youtubeButton" class="buttonSocial">
                </div>
            </a>
        </section>
        <section>
            <a href="https://www.instagram.com/engrescatsurl/?hl=es">
                <div id="instagramButton" class="buttonSocial">
                </div>
            </a>
        </section>
        <section>
            <a href="https://es-es.facebook.com/engrescats.delaurl?fref=mentions">
                <div id="facebookButton" class="buttonSocial">
                </div>
            </a>
        </section>
        <section>
            <a href="https://twitter.com/engrescatsurl?lang=es">
                <div id="twitterButton" class="buttonSocial">
                </div>
            </a>
        </section>
        <section>
            <a href="https://www.tiktok.com/@engrescats.url">
                <div id="ticktockButton" class="buttonSocial">
                </div>
            </a>
        </section>
    </article>
</header>


<div class = "missatge" id="missatgeM" style="display: table; height:100%;display: table-cell; vertical-align: middle;"onclick="HideMessage('missatgeM');" >
	<p>
        <b>Si et vols apuntar a un esdeveniment, només has de clicar al botó de color vermell.</b>
		<br>
        <a class="ok" onclick="PonerCookie('apuntatCookie', 'missatgeM');"><b>OK</b></a>
	</p>
</div>

<script>
	iniCookie('apuntatCookie', 'missatgeM');
</script>  

<div style='position: fixed; z-index: -1; width: 90%; height: 90%;background-image: url("icons/logoEngrescats.svg");background-repeat: no-repeat;
background-attachment: fixed;  background-position: center; opacity:0.4; background-size: 27%;'>
</div>




<?php
	$topLlista = 60;

	include "$_SERVER[DOCUMENT_ROOT]/pinyator/Connexio.php";
	
	$visualitzarFites = 0;
	$visualitzarPenya = 0;
				
	$sql="SELECT FITES, PARTICIPANTS, PERCENATGEASSISTENCIA
	FROM CONFIGURACIO";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result))
		{
			$visualitzarFites = $row["FITES"];
			$visualitzarPenya = $row["PARTICIPANTS"];
			$visualitzarPercentAssistecia = $row["PERCENATGEASSISTENCIA"];			
		}
	}
?>

<?php
if ((!empty($_GET['id'])) && (isset($_COOKIE[$cookie_name])))
{
	$Casteller_uuid = strval($_GET['id']);
	$Casteller_id=0;
	$malnom="";
	$malnomPrincipal="";
	$percentatgeAssistencia=0;

	
	$sql="SELECT C.MALNOM, C.CASTELLER_ID, C.Nom, C.Cognom_1, C.Cognom_2 
	FROM CASTELLER AS C
	WHERE C.CODI='".$Casteller_uuid."'";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$malnom=$row["MALNOM"];
			$malnomPrincipal=$row["MALNOM"];
			$Casteller_id = $row["CASTELLER_ID"];
            $nom=$row["Nom"]." ".$row["Cognom_1"]." ".$row["Cognom_2"];
		}
	}
}
else
{
	echo "<meta http-equiv='refresh' content='0; url=Apuntat.php'/>";	
}

function StarOff($left)
{
	echo "<div style='position:absolute; left:".$left."'><span class='fa fa-star starOff' style='font-size:30px'></span></div>";
}

$sqlEventsTotals = "SELECT COUNT(Event_ID) AS num FROM EVENT WHERE estat = 1;";
$resultEventsTotals = mysqli_query($conn, $sqlEventsTotals);
$rowEventsTotals = mysqli_fetch_assoc($resultEventsTotals);

$EventsTotals = $rowEventsTotals['num'];

$sqlAssitenciaTotal = "SELECT sum(inscrits.Estat) as num FROM inscrits INNER JOIN Event ON inscrits.Event_ID=Event.Event_ID WHERE inscrits.Casteller_ID = '".$Casteller_id."' AND event.Estat = 1;";
$resultAssitenciaTotal = mysqli_query($conn, $sqlAssitenciaTotal);
$rowAssitenciaTotal = mysqli_fetch_assoc($resultAssitenciaTotal);

$AssitenciaTotal = $rowAssitenciaTotal['num'];

$strike = "";
$imgName = "noPleno.png";

if($AssitenciaTotal == $EventsTotals){
    $strike = "checked";
    $imgName = "pleno.png";
}

?>

<div id="assitenciaNum" style="display:none"><?php echo $AssitenciaTotal; ?></div>
<div id="eventsNum" style="display:none"><?php echo $EventsTotals; ?></div>

        <main id="engrescatsApuntatMain">
            <!-- PART DEL USER -->
            <article>
                <section>
                    <div id="userIMGBack">
                        <div id="userIMG"></div>
                    </div>
                </section>
                <section>
                    <div id="userName">
                        <h1><?php echo $nom; ?></h1>
                        <p>Àlies: <?php echo $malnom; ?></p>
                    </div>
                </section>
                <section id="strikeSection" >
                    <div id='strikeButtonID' class='strikeButtonClass <?php echo $strike?>' onClick='OnClickStrike(strikeButtonID, <?php echo $Casteller_id?>)'>
                        <img class='boton' id="IMGstrikeButton" src='icons/<?php echo $imgName?>' width=100 height=100>
                    </div>
                </section>
            </article>

            <?php

            /***+++++++++++ LLISTAT +++++++++++***/

            $Casteller_id_taula = $Casteller_id;
            include "$_SERVER[DOCUMENT_ROOT]/pinyator/Inscripcio_taula.php";

            $sql="SELECT DISTINCT C.CODI, C.MALNOM, C.CASTELLER_ID
            FROM CASTELLER AS CR
            INNER JOIN CASTELLER AS C ON C.FAMILIA_ID = CR.CASTELLER_ID OR C.FAMILIA2_ID = CR.CASTELLER_ID
            WHERE CR.CODI='".$Casteller_uuid."'
            ORDER BY C.MALNOM";

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0)
            {
                while($row = mysqli_fetch_assoc($result))
                {
                    $malnom = $row["MALNOM"];
                    $Casteller_id_taula = $row["CASTELLER_ID"];
                    include "$_SERVER[DOCUMENT_ROOT]/pinyator/Inscripcio_taula.php";
                }
            }
            mysqli_close($conn);

            ?>
        </main>
   </body>
</html>

<script>
    function ejecutaStrike() {
        animate();
        setTimeout(function() {
            var strike = document.getElementById("strikePopUpFirework");
            strike.style.display = "block";
            strike.style.opacity = "100%";
        }, 0)

       setTimeout(function() {
            var strike = document.getElementById("strikePopUpFirework");
            strike.style.display = "none";
            strike.style.opacity = "0%%";
        }, 5000)
    }

    const canvas = document.getElementById("strikePopUpFirework");
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    var ctx = canvas.getContext("2d");
    function Firework(x,y,height,yVol,R,G,B){
        this.x = x;
        this.y = y;
        this.yVol = yVol;
        this.height = height;
        this.R = R;
        this.G = G;
        this.B = B;
        this.radius = 2;
        this.boom = false;
        var boomHeight = Math.floor(Math.random() * 200) + 50;
        this.draw = function(){

            ctx.fillStyle = "rgba(" + R + "," + G + "," + B + ")";
            ctx.strokeStyle = "rgba(" + R + "," + G + "," + B + ")";
            ctx.beginPath();
            //   ctx.arc(this.x,boomHeight,this.radius,Math.PI * 2,0,false);
            ctx.stroke();
            ctx.beginPath();
            ctx.arc(this.x,this.y,3,Math.PI * 2,0,false);
            ctx.fill();
        }
        this.update = function(){
            this.y -= this.yVol;
            if(this.radius < 20){
                this.radius += 0.35;
            }
            if(this.y < boomHeight){
                this.boom = true;

                for(var i = 0; i < 120; i++){
                    particleArray.push(new Particle(
                        this.x,
                        this.y,
                        // (Math.random() * 2) + 0.5//
                        (Math.random() * 2) + 1,
                        this.R,
                        this.G,
                        this.B,
                        1,
                    ))

                }
            }
            this.draw();
        }
        this.update()
    }

    function Particle(x,y,radius,R,G,B,A){
        this.x = x;
        this.y = y;
        this.radius = radius;
        this.R = R;
        this.G = G;
        this.B = B;
        this.A = A;
        this.timer = 0;
        this.fade = false;

        // Change random spread
        this.xVol = (Math.random() * 10) - 4
        this.yVol = (Math.random() * 10) - 4


        //console.log(this.xVol,this.yVol)
        this.draw = function(){
            //   ctx.globalCompositeOperation = "lighter"
            ctx.fillStyle = "rgba(" + R + "," + G + "," + B + "," + this.A + ")";
            ctx.save();
            ctx.beginPath();
            // ctx.fillStyle = "white"
            ctx.globalCompositeOperation = "screen"
            ctx.arc(this.x,this.y,this.radius,Math.PI * 2,0,false);
            ctx.fill();

            ctx.restore();
        }
        this.update = function(){
            this.x += this.xVol;
            this.y += this.yVol;

            // Comment out to stop gravity.
            if(this.timer < 200){
                this.yVol += 0.12;
            }
            this.A -= 0.02;
            if(this.A < 0){
                this.fade = true;
            }
            this.draw();
        }
        this.update();
    }

    var fireworkArray = [];
    var particleArray = [];
    for(var i = 0; i < 50; i++){
        var x = Math.random() * canvas.width;
        var y = canvas.height;
        var R = Math.floor(Math.random() * 255)
        var G = Math.floor(Math.random() * 255)
        var B = Math.floor(Math.random() * 255)
        var height = (Math.floor(Math.random() * 20)) + 10;
        fireworkArray.push(new Firework(x,y,height,5,R,G,B))
    }


    function animate(){
        requestAnimationFrame(animate);
        // ctx.clearRect(0,0,canvas.width,canvas.height)
        ctx.fillStyle = "rgba(0,0,0,0.1)"
        ctx.fillRect(0,0,canvas.width,canvas.height);
        for(var i = 0; i < fireworkArray.length; i++){
            fireworkArray[i].update();
        }
        for(var j = 0; j < particleArray.length; j++){
            particleArray[j].update();
        }
        if(fireworkArray.length < 0){
            var x = Math.random() * canvas.width;
            var y = canvas.height;
            var height = Math.floor(Math.random() * 20);
            var yVol = 5;
            var R = Math.floor(Math.random() * 255);
            var G = Math.floor(Math.random() * 255);
            var B = Math.floor(Math.random() * 255);
            fireworkArray.push(new Firework(x,y,height,yVol,R,G,B));
        }


        fireworkArray = fireworkArray.filter(obj => !obj.boom);
        particleArray = particleArray.filter(obj => !obj.fade);
    }

    window.addEventListener("resize", (e) => {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    })

</script>