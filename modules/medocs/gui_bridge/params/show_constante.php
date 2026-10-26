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
		var designation=$("#designation").val();
		var typeInput=$("#typeInput").val();
		
		if(designation==""){
			alert("Veuillez saisir la désignation");
			$("#designation").focus();
			return false;
		}else if(typeInput==""){
			alert("Veuillez saisir le type de l'input");
			$("#designation").focus();
			return false;
		}else{
			return true;
		}
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
				if($constante=$objMedocsParams->getConstante($id)){
					$smarty->assign('name', $constante['name']);
					$smarty->assign('LD_var', $constante['LD_var']);
					$smarty->assign('id', $constante['nr']);

					//Remplir le type de constante
					$listeTypeConstante=$objMedocsParams->listeTypeConstante();
					if(is_object($listeTypeConstante)){
						ob_start();
							echo '<option value="">Selectionnez le type</option>';
							while($typeConstante=$listeTypeConstante->FetchRow()){
								$selected=($constante['measurement_type']==$typeConstante['nr'])?"selected=selected":"";
								echo '<option value="'.$typeConstante['nr'].'" '.$selected.'>'.$$typeConstante['LD_var'].'</option>';
							}
						$selectOptionsTypeConstante=ob_get_contents()	;
						ob_end_clean();
					}

					//Remplir l'unité de mesure et selectionner le défault
					$listeUnitDefault=$objMedocsParams->listeUnitDefaultByType($constante['measurement_type']);
					if(is_object($listeUnitDefault)){
						ob_start();
						while ($ligne=$listeUnitDefault->FetchRow()) {
							$selected=($constante['unit_default']==$ligne['nr'])?"selected=selected":"";
							echo "<option value='".$ligne['nr']."' ".$selected.">".$ligne['id']."</option>";
						}
						$selectOptionsUnitDefault=ob_get_contents()	;
						ob_end_clean();
					}
					$smarty->assign('selectOptionsUnitDefault', $selectOptionsUnitDefault);

					$defaultText="";
					$defaultNumber="";
					$defaultList="";
					switch($constante['val_type']){
						case 'text':
							$defaultText='selected="selected"';
						break;
						case 'number':
							$defaultNumber='selected="selected"';
						break;
						case 'select':
							$defaultList='selected="selected"';
						break;
						default:
							$defaultText='selected="selected"';
						break;
					}
					$smarty->assign("defaultText",$defaultText);
					$smarty->assign("defaultNumber",$defaultNumber);
					$smarty->assign("defaultList",$defaultList);
					$smarty->assign('val_possible', $constante['val_possible']);
				}			
				break;

			case 'delete':
				if($objMedocsParams->deleteConstante($id)){
					$msg="La constante a été supprimé avec succès";
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
	/*var_dump($_POST);die();
	extract($_POST);*/
	if(isset($id) && !empty($id)){
		$tab=array(
			'type' =>strtolower($name),
			'name' =>addslashes($name),
			'LD_var' =>$LD_var,
			'measurement_type'=>$measurement_type,
			'unit_default'    =>$unit_default,
			'val_type'		  =>$val_type,
			'val_possible'	  =>addslashes($val_possible),
			'status'          =>'O',
			'modify_id'       =>$userPID,
			'modify_time'     =>$Time,
			'history'         =>"CONCAT(history,' ;','Modifier par $userName le $Time')"
		);
		$msg="La constante a bien été mis à jour";

	}else{
		$tab=array(
			'type' =>strtolower($name),
			'name' =>addslashes($name),
			'LD_var' =>$LD_var,
			'measurement_type'=>$measurement_type,
			'unit_default'    =>$unit_default,
			'val_type'		  =>$val_type,
			'val_possible'	  =>addslashes($val_possible),
			'status'      =>'O',
			'create_id'   =>$userPID,
			'create_time' =>$Time,
			'history'     =>"Créer par ".$userName." le ".$Time
		);
		$msg="La Constante a bien été enregistrer";
	}
	
	if($objMedocsParams->insertFromArray('care_type_measurement',$tab, 'nr',@$id)){
		$msgType="success";
		#J'enregistre le LD_var dans lang_fr_measurement
		if(!isset($id) || $id=='' || intval($id)==0){
			//var_dump($_POST);die();
			$monfichier=fopen($root_path."language/fr/lang_fr_measurement.php", 'a+');
			fseek($monfichier, 0);
			fputs($monfichier, "\t$".$LD_var."='".$LD_description."';");
			fclose($monfichier);
		}
		

	}else{
		$msg="Le parametre n'a pas été enregistrer";
		$msgType="danger";
	}
}


#Affichage du message si il y en a 
if(isset($msg) && !empty($msg)){
	$smarty->assign("msg", $msg);
	$smarty->assign("msgType", $msgType);
}
$LDπ="π";
#Affichage des constantes
$allTypes=$objMeasurement->getAllMsrTypes();
if(!empty($allTypes)){
	ob_start();
	$pos=0;
	foreach ($allTypes as $v) {
		$pos++;
		$linkEdit=$baseLink."&target=constante&option=edit&id=".$v['nr'];
		$linkDelete=$baseLink."&target=constante&option=delete&id=".$v['nr'];
		$LDMeasurementType=$objMeasurement->getValue('LD_var','WHERE nr='.$v['measurement_type'].'','care_type_unit_measurement');
		
		$measurement_type=!empty($LDMeasurementType[0]['LD_var'])?$$LDMeasurementType[0]['LD_var']:"";
		
		echo '<tr>';
			echo '<td class="text-right">'.$pos.'</td>';
			echo '<td>'.$$v['LD_var'].'</td>';
			echo '<td>'.$measurement_type.'</td>';
			echo '<td>'.$objMedocsParams->getDefaultValueConstante($v['unit_default'])['id'].'</td>';
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


$smarty->assign('sMainBlockIncludeFile','medocs/params_constante.tpl');
?>

