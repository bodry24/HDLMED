<?php  
//define('LANG_FILE','measurement.php');
require_once($root_path.'include/care_api_classes/class_measurement.php');
$objMeasurement=new Measurement;
//Tous les type de mésure valide
ob_start();
?>
<style type="text/css">
	div.dataTables_wrapper div.dataTables_filter input {
	    margin-left: 0.5em;
	    display: inline-block;
	    width: 130px;
	}
</style>
<script type="text/javascript">

	//Controle sur le formulaire
	function checkForm(){
		
	}

	//Remplir la liste des unités par type 
	function loadUnitDefault(element){
	    $("#unit_default").empty();
		$.post('<?php echo $root_path."modules/ajax/ajaxMedocsParams.php";?>',{'target':'remplirUnitDefault','unit_type_nr':element}, 
			function(data){
				$("#unit_default").append(data);
			}
		);
	}

	$(function(){
		$("#datatable").DataTable({
			//'dom':'tp',
			language: {
	            processing:     "Traitement en cours...",
	            search:         "Rechercher&nbsp;:",
	            lengthMenu:    "Afficher _MENU_ &eacute;l&eacute;ments",
	            info:           "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
	            infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
	            infoFiltered:   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
	            infoPostFix:    "",
	            loadingRecords: "Chargement en cours...",
	            zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher",
	            emptyTable:     "Aucune donn&eacute;e disponible dans le tableau",
	            paginate: {
	                first:      "Premier",
	                previous:   "Pr&eacute;c&eacute;dent",
	                next:       "Suivant",
	                last:       "Dernier"
	            },
	            aria: {
	                sortAscending:  ": activer pour trier la colonne par ordre croissant",
	                sortDescending: ": activer pour trier la colonne par ordre d?roissant"
	            }
	        },
	        lengthMenu: [[15, 30, 100, 200, -1], [15, 30, 100, 200, "Tout"]],
		});
	});

</script>
<?php
$js=ob_get_contents();
ob_end_clean();
$smarty->append("JavaScript", $js);

#Recuperation d'une constante

if(isset($_GET['option']) && !empty($_GET['option'])){

	$option=$_GET['option'];
	$nr=$_GET['nr'];

	if(!empty($nr)){
		switch ($option) {
			case 'edit':
	
				if($unitMesure=$objMedocsParams->getUnitMesure($nr)){
					$smarty->assign('name', $unitMesure['name']);
					$smarty->assign('id', $unitMesure['id']);
					$smarty->assign('LD_var', $unitMesure['LD_var']);
					$smarty->assign('LD_description', $$unitMesure['LD_var']);
					$smarty->assign('nr', $unitMesure['nr']);

						//Remplir le type de constante
					$listeTypeConstante=$objMedocsParams->listeTypeConstante();
					if(is_object($listeTypeConstante)){
						ob_start();
							echo '<option value="">Selectionnez le type</option>';
							while($typeConstante=$listeTypeConstante->FetchRow()){
								$selected=($unitMesure['unit_type_nr']==$typeConstante['nr'])?"selected=selected":"";
								echo '<option value="'.$typeConstante['nr'].'" '.$selected.'>'.$$typeConstante['LD_var'].'</option>';
							}
						$selectOptionsTypeConstante=ob_get_contents()	;
						ob_end_clean();
					}

				}			
				break;

			case 'delete':

				if($objMedocsParams->deleteUnitMesure($nr)){
					$msg="L'unité de mesure a été supprimé avec succès";
					$msgType="success";
				}else{
					$msg="La suppression n'a pas réussi";
					$msgType="danger";
				}
				break;

			default:
				# code...
				break;
		}
	}
	
}else{

	$listeTypeConstante=$objMedocsParams->listeTypeConstante();
	if(is_object($listeTypeConstante)){
		ob_start();
			echo '<option value="">Selectionnez le type</option>';
			while($typeConstante=$listeTypeConstante->FetchRow()){
				echo '<option value="'.$typeConstante['nr'].'">'.$$typeConstante['LD_var'].'</option>';
			}
		$selectOptionsTypeConstante=ob_get_contents()	;
		ob_end_clean();
	}
}

$smarty->assign('selectOptionsTypeConstante', $selectOptionsTypeConstante);

#Traitement
if(isset($save) && $save=="Enregistrer"){
	extract($_POST);
	if(isset($nr) && !empty($nr)){
		$tab=array(
			'name'       =>addslashes($name),
			'unit_type_nr'=>$unit_type_nr,
			'id'       =>addslashes($id),
			'LD_var'     =>$LD_var,
			'status'     =>'O',
			'modify_id'  =>$userPID,
			'modify_time'=>$Time,
			'history'    =>"CONCAT(history,' ;','Modifier par $userName le $Time')"
		);
		$msg="L'unité de mesure a bien été mis à jour";

	}else{
		$tab=array(
			'name'       =>addslashes($name),
			'unit_type_nr'=>$unit_type_nr,
			'id'       =>addslashes($id),
			'LD_var'     =>$LD_var,
			'status'     =>'O',
			'create_id'   =>$userPID,
			'create_time' =>$Time,
			'history'     =>"Créer par ".$userName." le ".$Time
		);
		$msg="L'unité de mesure a bien été enregistrer";

	}
	
	if($objMedocsParams->insertFromArray('care_unit_measurement',$tab, 'nr',@$nr)){
		$msgType="success";
		#J'enregistre le LD_var dans lang_fr_measurement
		if(!isset($nr) || empty($nr) || $nr==""){
			
			$monfichier=fopen($root_path."language/fr/lang_fr_measurement.php", 'a+');
			fseek($monfichier, 0);
			fputs($monfichier, "$".$LD_var."='".$LD_description."';");
			fclose($monfichier);
		}
		
	}else{

		$msg="L'unité de mesure n'a pas été enregistrer";
		$msgType="danger";
	}

}


#Affichage du message si il y en a 
if(isset($msg) && !empty($msg)){
	$smarty->assign("msg", $msg);
	$smarty->assign("msgType", $msgType);
}

#Affichage des type de constantes
$uniteMesures=$objMedocsParams->listeUniteMesure();
if(is_object($uniteMesures)){
	ob_start();
	$pos=0;
	while ($v=$uniteMesures->FetchRow()) {
		$pos++;
		$linkEdit=$baseLink."&target=unite_mesure&option=edit&nr=".$v['nr'];
		$linkDelete=$baseLink."&target=unite_mesure&option=delete&nr=".$v['nr'];
		$typeConstante=$objMedocsParams->getTypeConstante($v['unit_type_nr']);//recuperation du type
		echo '<tr>';
			echo '<td class="text-right">'.$pos.'</td>';
			echo '<td>'.$$v['LD_var'].' :: '.$v['id'].'</td>';
			echo '<td>'.$$typeConstante['LD_var'].'</td>';
			echo '<td><a href="'.$linkEdit.'" style="text-decoration:none" title="Editer">
							<span class="glyphicon glyphicon-edit btn btn-xs btn-success"></span>
						</a>
						<a href="'.$linkDelete.'" title="Supprimer" onclick="return confirm(\'Etes vous sur?\')">
							<span class="glyphicon glyphicon-trash btn btn-xs btn-danger"></span>
						</a>
					</td>';
		echo '</tr>';

	}

	$listing=ob_get_contents();
	ob_end_clean();
	$smarty->assign('listing', $listing);

}

$smarty->assign('sMainBlockIncludeFile','medocs/params_unite_mesure.tpl');

?>

