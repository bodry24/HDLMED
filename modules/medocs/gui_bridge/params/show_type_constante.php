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
	$id=$_GET['id'];

	if(!empty($id)){
		switch ($option) {
			case 'edit':
				if($typeConstante=$objMedocsParams->getTypeConstante($id)){
					$smarty->assign('name', $typeConstante['name']);
					$smarty->assign('LD_var', $typeConstante['LD_var']);
					$smarty->assign('LD_description', $$typeConstante['LD_var']);
					$smarty->assign('description', $typeConstante['description']);
					$smarty->assign('id', $typeConstante['nr']);
				}			
				break;

			case 'delete':
				if($objMedocsParams->deleteTypeConstante($id)){
					$msg="Le type de constante a été supprimé avec succès";
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
	
}


#Traitement
if(isset($save) && $save=="Enregistrer"){
	extract($_POST);
	if(isset($id) && !empty($id)){
		
		$tab=array(
			'type'       =>strtolower(addslashes($name)),
			'name'       =>addslashes($name),
			'LD_var'     =>$LD_var,
			'description'=>$description,
			'status'     =>'O',
			'modify_id'  =>$userPID,
			'modify_time'=>$Time,
			'history'    =>"CONCAT(history,' ;','Modifier par $userName le $Time')"
		);
		$msg="Le type de constante a bien été mis à jour";

	}else{
		$tab=array(
			'type'       =>strtolower(addslashes($name)),
			'name'       =>addslashes($name),
			'LD_var'     =>$LD_var,
			'description'=>$description,
			'status'     =>'O',
			'create_id'   =>$userPID,
			'create_time' =>$Time,
			'history'     =>"Créer par ".$userName." le ".$Time
		);
		$msg="Le type de constante a bien été enregistrer";
	}
	//var_dump($id);die();
	//var_dump($objMedocsParams->insertFromArray('care_unit_type_measurement',$tab, 'nr',@$id));die();
	if($objMedocsParams->insertFromArray('care_type_unit_measurement',$tab, 'nr',@$id)){
		$msgType="success";
		#J'enregistre le LD_var dans lang_fr_measurement
		if(!isset($id) || empty($id) || $id==""){
			
			$monfichier=fopen($root_path."language/fr/lang_fr_measurement.php", 'a+');
			fseek($monfichier, 0);
			fputs($monfichier, "$".$LD_var."='".$LD_description."';");
			fclose($monfichier);
		}
		
	}else{
		$msg="Le type constante n'a pas été enregistrer";
		$msgType="danger";
	}

}


#Affichage du message si il y en a 
if(isset($msg) && !empty($msg)){
	$smarty->assign("msg", $msg);
	$smarty->assign("msgType", $msgType);
}

#Affichage des type de constantes
$typeConstantes=$objMedocsParams->listeTypeConstante();
if(is_object($typeConstantes)){
	ob_start();
	$pos=0;
	foreach ($typeConstantes as $v) {
		$pos++;
		$linkEdit=$baseLink."&target=type_constante&option=edit&id=".$v['nr'];
		$linkDelete=$baseLink."&target=type_constante&option=delete&id=".$v['nr'];
	
		echo '<tr>';
			echo '<td class="text-right">'.$pos.'</td>';
			echo '<td>'.$$v['LD_var'].'</td>';
			echo '<td>'.$v['description'].'</td>';
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


$smarty->assign('sMainBlockIncludeFile','medocs/params_type_constante.tpl');

?>

