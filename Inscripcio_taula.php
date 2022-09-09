<script src="https://cdn.jsdelivr.net/mojs/latest/mo.min.js"></script>
<article>
    <section style="padding-bottom: 10px;">
        <div id="taula-Titols" class="taula" style="font-size: 25px;">
            <div class="cell columna_Nom">
                <strong style="grid-column:1/100;">Nom</strong>
            </div>
            <div class="cell columna_Data">
                <strong>Data</strong>
            </div>
            <div class="cell columna_Hora">
                <strong>Hora</strong>
            </div>
            <div class="cell columna_Lloc">
                <strong>Lloc</strong>
            </div>
            <div class="cell columna_Assitencia">
                <strong>Assistència</strong>
            </div>
            <div class="cell columna_Engrescats">
                <strong>Engrescats</strong>
            </div>
        </div>
        <?php
        if (!empty($Casteller_id_taula))
        {
            $script = "";

            $sql="SELECT E.EVENT_ID, E.NOM AS EVENT_NOM, E.EVENT_PARE_ID,
	date_format(E.DATA, '%d-%m-%Y %H:%i') AS DATA, ".$Casteller_id_taula." AS CASTELLER_ID, IFNULL(I.ESTAT,-1) AS ESTAT,
	IFNULL(EP.DATA, E.DATA) AS ORDENACIO, E.MAX_PARTICIPANTS,
	IFNULL(I.ACOMPANYANTS, 0) AS ACOMPANYANTS, E.TIPUS,
	E.MAX_ACOMPANYANTS,
	(SELECT SUM(C.PUBLIC) FROM CASTELL AS C WHERE C.EVENT_ID=E.EVENT_ID) AS PUBLIC,
	(SELECT SUM(IF(IU.ESTAT > 0, 1,0))
		FROM INSCRITS IU
		JOIN CASTELLER C ON C.CASTELLER_ID=IU.CASTELLER_ID
		JOIN POSICIO P ON P.POSICIO_ID=C.POSICIO_PINYA_ID
		WHERE IU.EVENT_ID=E.EVENT_ID
		AND IU.ESTAT > 0
		AND (P.ESNUCLI=1 OR P.ESTRONC=1 OR P.ESCORDO=1)) AS APUNTATS,
	(SELECT SUM(IF(IU.ESTAT>0,1,0)) + SUM(IFNULL(IU.ACOMPANYANTS, 0))
		FROM INSCRITS IU
		JOIN CASTELLER C ON C.CASTELLER_ID=IU.CASTELLER_ID
		WHERE IU.EVENT_ID=E.EVENT_ID
		) AS APUNTATS_ALTRES,
	REPLACE(E.OBSERVACIONS, CHAR(13), '<br>') AS OBSERVACIONS
	FROM EVENT AS E
	LEFT JOIN EVENT AS EP ON EP.EVENT_ID = E.EVENT_PARE_ID
	LEFT JOIN CASTELLER_INSCRITS AS I ON I.EVENT_ID = E.EVENT_ID AND I.CASTELLER_ID=".$Casteller_id_taula."
	WHERE E.ESTAT = 1	
	ORDER BY ORDENACIO, E.EVENT_PARE_ID, E.DATA";

            $result2 = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result2) > 0)
            {
                $PosicioId = 0;
                $Separador="";
                // output data of each row
                while($row2 = mysqli_fetch_assoc($result2))
                {
                    $idElement="E".$row2["EVENT_ID"]."C".$row2["CASTELLER_ID"];

                    $comment = "<a class='commentLink' href='Event_Comentari_Public.php?id=".$row2["EVENT_ID"]."&nom=".$malnomPrincipal."'><img src='icons/comment.png'></img></a>";

                    $max_participants = $row2["MAX_PARTICIPANTS"];
                    $max_acompanyants = $row2["MAX_ACOMPANYANTS"];
                    $observacions = $row2["OBSERVACIONS"];

                    $apuntats=0;
                    if (($row2["APUNTATS"] > 0) && ($row2["TIPUS"] != -1))
                    {
                        $apuntats=$row2["APUNTATS"];
                    }
                    else if (($row2["APUNTATS_ALTRES"] > 0) && ($row2["TIPUS"] == -1))
                    {
                        $apuntats=$row2["APUNTATS_ALTRES"];
                    }

                    if ($row2["PUBLIC"]>0)
                    {
                        $eventNom = "<a class='nomEvent' href='Actuacio.php?id=".$row2["EVENT_ID"]."'><span style='text-decoration:underline;'>".$row2["EVENT_NOM"]."</span></a>";
                    }
                    else
                    {
                        $eventNom = "<span class='nomEvent'>".$row2["EVENT_NOM"]."</span>";
                    }
                    $stat  = $row2["ESTAT"];
                    if ($stat == -1)
                    {
                        $stat = 0;
                        $script .= "PrimerSaveLike(".$row2["EVENT_ID"].",".$row2["CASTELLER_ID"].");";
                    }

                    $checked="";
                    $imgLike="semaforVermell.png";

                    if ($stat== 0)
                    {
                        $color = "style='background-color:#ff1a1a;'";
                        $estat="No vinc";
                        $checked="";
                        $imgLike="semaforVermell.png";
                    }
                    elseif ($stat > 0)
                    {
                        $color = "style='background-color:#33cc33;'";//green
                        $estat="Vinc";
                        $checked="checked";
                        $imgLike="semaforVerd.png";
                    }

                    $tInici = "";
                    $tFinal = "";
                    $Juntador = "";
                    if ($row2["EVENT_PARE_ID"] > 0)
                    {
                        $Separador="";
                        //PONER EL TAB Y EL ESTILO DE JUNTAR
                        $tInici = "";
                        $tFinal = "";
                        $Juntador = "style= margin-left:35px;margin-top:0px;border-top:0px;";
                    }

                    $data = date_create($row2["DATA"]);

                    $script .= "EventNou(".$row2["EVENT_ID"].",".$stat.",".$row2["CASTELLER_ID"].",".$apuntats.",".$max_participants.",".$max_acompanyants.");";

                    $tipusCelda = "";
                    $tipusTaula = "";
                    $siluetas = "";
                    $gradiente = "";
                    $nomColla = "";

                    if($row2["TIPUS"] == 1){
                        $tipusCelda = "Actuacio";
                        $tipusTaula = "Actuacio";
                        $siluetas = "<div class='fondoResponsiveActuacion'></div>";

                        if(str_contains($row2["EVENT_NOM"], "DIADA")){
                            $nomColla = explode(" ",$row2["EVENT_NOM"]);
                            $nomColla = $nomColla[1];
                        } else {
                            $nomColla = "ENGRESCATS";
                        }

                        $sqlGradient = "SELECT Color_1, Color_2 FROM COLLES WHERE Nom_Colla like '".$nomColla."';";
                        $resultGradient = mysqli_query($conn, $sqlGradient);
                        $colores = mysqli_fetch_assoc($resultGradient);

                        $gradiente = "style='background: linear-gradient(0deg, #000000 0%, ".$colores['Color_1']." 45%, ".$colores['Color_2']." 100%);'";

                    } else if($row2["TIPUS"] == -1){
                        $tipusCelda = "Altres";
                    }

                    echo "
                    <div class='taula".$tipusTaula."' ".$Juntador." ".$gradiente.">
                        ".$siluetas."
                        <div class='cell".$tipusCelda." columna_Nom'>
                            ".$comment.$eventNom."
                        </div>
                        <div class='cell".$tipusCelda." columna_Data'>
                            ".date_format($data, "d/m")."
                        </div>
                        <div class='cell".$tipusCelda." columna_Hora'>
                            ".date_format($data, "H:i")."
                        </div>
                        <div class='cell".$tipusCelda." columna_Lloc'>
                            ".$row2["OBSERVACIONS"]."
                        </div>
            
            ";

                    echo "<div class='cell".$tipusCelda." columna_Assitencia'>
                        <div>
                          <div class='like-cnt ".$checked."' id='".$idElement."' onClick='OnClickLike(".$idElement.", ".$row2["EVENT_ID"].", ".$row2["CASTELLER_ID"].", ".$Casteller_id.", ".$row2["TIPUS"].")'>
                                  <img id='IMG".$idElement."' src='icons/".$imgLike."' width=48 height=48>
                            </div>
                        </div>
                  </div>";

                    //<div>
                    //  <div class='like-cnt ".$checked."' id='".$idElement."' onClick='OnClickLike(".$idElement.", ".$row2["EVENT_ID"].", ".$row2["CASTELLER_ID"].", ".$Casteller_id.", ".$row2["TIPUS"].")'>
                    //      <img id='IMG".$idElement."' src='icons/".$imgLike."' width=48 height=48>
                    //  </div>
                    //</div>

                    if ($visualitzarPenya)
                    {
                        echo "<div class='cell".$tipusCelda." columna_Engrescats' name='E".$row2["EVENT_ID"]."'>
                        ".$apuntats."
                      </div></div>";
                    }
                    else
                    {
                        echo "</div>";
                    }
                }

                echo "<script>".$script."</script>";
            }
            else if (mysqli_error($conn) != "")
            {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
        ?>
