<?php 
	ob_start();
	
	
?>
<script type="text/javascript">

	$(document).ready(function(){
		//Autocompletion		
		$('#LDVaccin').keypress(function(){
			$("#LDVaccin").autocomplete({				
				source:function(request,response){
					var val=$("#LDVaccin").val();
					//alert(val);
					$.get('../ajax/autoComplete_vaccin.php',{value:val},function(data){
						response($.map(data, function(item) {
							return {
								label: item.name,
								nr: item.nr
							}
						}))
					},'json');
				
				},
				focus:function(event,ui){
					alert(data);
					return false;
				},
				select:function(event,ui){
					$("#LDVaccin").val(ui.item.label);
					$("#idVaccin").val(ui.item.nr);
				}
			});
		});

		$('.message .close').on('click', function() {

		    $(this).closest('.message').transition('fade');

		});

	});


		
</script>
<?php

$tmpJs=ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript',$tmpJs);
?>	
	<?php if(isset($msg)): ?>

		<div class="ui success message" style="text-align:center;">
			<i class="close icon"></i>
			<div class="header">
				<?php echo $msg; ?>
			</div>
		</div>

	<?php endif;?>


<form class="ui form" action="" method="POST">

	<div class="wide column" style="text-align:left;">

		<div class="three fields">
		
			<div class="field">
			    <label>Vaccin</label>
			    <input id="LDVaccin" type="text" name="vaccin" placeholder="Nom du vaccin" value="<?php echo isset($nameVaccin)?$nameVaccin:'';?>">
			    <input id="idVaccin" type="hidden" name="idVaccin" value="<?php echo isset($idVaccin)?$idVaccin:'';?>">
		    </div>

			<div class="field">
			    <label>Date </label>

				<?php echo $calendar->show_calendar($calendar, $date_format, 'date_effectuee', isset($dateVaccin)?$dateVaccin:date('Y-m-d'));?>
			  
			</div>

			<div class="field">
			    <label>&nbsp;</label>
			    <input type="hidden" name="mode" value="<?php echo $mode=='form'?'create':'update';?>">
				<button class="ui blue button" type="submit" name="save"><i class="save icon"></i>Enregistrer</button>
			</div>							    
		
		</div>	

	</div>	
		
</form>

<br>
<!-- Affichage des vaccinations effectuÃ©es par ce patient -->
<?php

	$result=$obj_vaccination->getVaccinationPatient($pid);
	//var_dump($result);
    if(!empty($result)){

		//echo "<center><u>Affichage des Vaccinations effectu&eacute;es</u></center>";
		echo "<table class='ui definition table blue'>";
		echo "<tr><td>N&deg;</td><td>Vaccin</td><td>Date</td><td>Actions</td></tr>";
		$i=0;
		while($ligne=$result->FetchRow()){
			$i++;
			$linkEdit=$baseLink."&target=$target&mode=update&vac_pid=".$ligne['vac_pid'];
			$linkDelete=$baseLink."&target=$target&mode=delete&vac_pid=".$ligne['vac_pid'];

			echo "<tr>";
			echo "<td>".$i."</td>";
			echo "<td>".$ligne['name']."</td>";
			echo "<td>".date('d-m-Y', strtotime($ligne['date_effectuee']))."</td>";
			echo "<td>";
			echo '<a href="'.$linkEdit.'" class="ui green compact icon button"><i class="write edit icon"></i></a> ';
		    echo '<a href="'.$linkDelete.'" onclick="confirm(\'Voulez vous supprimer cet raticle?\');" class="ui red compact icon button"><i class="write delete icon"></i></a></td>';
			//echo "<button class='ui green compact icon button' id='update' title='Editer'><i class='write edit icon'></i></button>";
		    //echo "<button class='ui red compact icon button del' title='Supprimer' value='".$linkDelete."'><i class='delete icon'></i></button>";

			echo "</td></tr>";
		}

		echo "</table>";

	}else{

		echo "<center>Pas de vaccination &agrave; afficher pour le moment !</center>";
	}

?>















































