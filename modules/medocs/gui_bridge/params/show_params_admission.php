<?php  
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

#Tableau des types d'input
$tabTypeInput=array(
		'input'=>'Input',
		'textarea'=>'Textarea',
		'select'=>'Select',
		'liste'=>'Liste',
		'date'=>'Date',
		'radio'=>'Radio'
	);

#Recuperation d'un parametre
if(isset($_GET['option']) && !empty($_GET['option'])){
	$option=$_GET['option'];
	$id=$_GET['id'];
	if(!empty($id)){
		switch ($option) {
			case 'edit':
				$parametre=$objMedocsParams->getParametre($id);
				$smarty->assign('id', $id);
				$smarty->assign('designation', $parametre['designation']);
				$smarty->assign('nameValue', $parametre['nameValue']);
				$smarty->assign('defaultValue', $parametre['defaultValue']);
				$smarty->assign('value', $parametre['value']);
				$smarty->assign('placeholder', $parametre['placeholder']);
				//Pour le select
				ob_start();
					foreach ($tabTypeInput as $k => $v) {
						$selected=($tabTypeInput[$parametre['typeInput']]==$v)?'selected="selected"':"";
						echo '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
					}
				$selectOptions=ob_get_contents();
				ob_end_clean();

				break;
			case 'delete':
				if($objMedocsParams->deleteParametre($id)){
					$msg="Le parametre a été supprimé avec succès";
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
	ob_start();
		foreach ($tabTypeInput as $k => $v) {
			echo '<option value="'.$k.'">'.$v.'</option>';
		}
	$selectOptions=ob_get_contents()	;
	ob_end_clean();
}

$smarty->assign('selectOptions', $selectOptions);
#Traitement des données du formulaire
if(isset($save)){
	if(isset($id) && !empty($id)){
		$tab=array(
			'designation' =>addslashes($designation),
			'typeInput'   =>addslashes($typeInput),
			'nameValue'   =>addslashes($nameValue),
			'defaultValue'=>addslashes($defaultValue),
			'value'       =>addslashes($value),
			'modify_id'   =>$userPID,
			'modify_time' =>$Time,
			'history'     =>"CONCAT(history,' ;','Modifier par $userName le $Time')"
		);
		$msg="Le parametre a bien été mis à jour";


	}else{
		
		$tab=array(
			'designation' =>addslashes($designation),
			'typeInput'   =>addslashes($typeInput),
			'nameValue'   =>addslashes($nameValue),
			'defaultValue'=>addslashes($defaultValue),
			'value'       =>addslashes($value),
			'create_id'   =>$userPID,
			'create_time' =>$Time,
			'history'     =>"Créer par ".$userName." le ".$Time
		);
		$msg="Le parametre a bien été enregistrer";
	}
	//var_dump($tab);die();
	if($objMedocsParams->insertFromArray('care_medocs_parametres',$tab, 'id',@$id)){
		$msgType="success";
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

#Listes des parametres actuelles
$parametres=$objMedocsParams->listeParametre();
if(is_object($parametres)){
	ob_start();
	$pos=0;
	while($ligne=$parametres->FetchRow()){
		$pos++;
		$linkEdit=$baseLink."&target=parametre&option=edit&id=".$ligne['id'];
		$linkDelete=$baseLink."&target=parametre&option=delete&id=".$ligne['id'];
		echo '<tr>';
			echo '<td class="text-right">'.$pos.'</td>';
			echo '<td>'.$ligne['designation'].'</td>';
			echo '<td>'.$ligne['typeInput'].'</td>';
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

$smarty->assign('sMainBlockIncludeFile','medocs/params_parametres.tpl');
?>

