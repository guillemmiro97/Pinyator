	<form method="post" action="Event_Comentari_Desa.php">
	<div>
		<table class="butons">
			<tr>
				<th>
				<?php 
				echo "<a href='".$url.".php?id=".$id."&nom=".$nom."' class='boto'>Torna</a";
				?>
				</th>
				<th></th>
				<th></th>
			</tr>
		</table>
	</div> 
	<div class="form_group">
		<input type="hidden" name="id" value="<?php echo $id ?>" >
		<input type="hidden" name="url" value="<?php echo $url ?>" >
		<label>Nom</label>
		<input type="text" class="form_edit" name="nom" value="<?php echo $nom;?>" <?php if ($nom != "") echo "readonly"; ?>>
	<br><br>
		<label>Comentari</label>
		<textarea type="text" rows="7" class="form_edit" name="text" autofocus></textarea>
	<br><br>
		<button type="Submit" class="boto">Desa</button>
	</div>
	</form>


