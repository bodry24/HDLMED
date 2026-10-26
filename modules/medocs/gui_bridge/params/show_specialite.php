<?php
#Breadcrumb
$breadcrumb=array("Parametrage","Spécialités");
$formAction="medocs_params.php".URL_APPEND."&target=".$target;
$smarty->assign("formAction",$formAction);

/********JAVASCRIPT*******/
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
	function confirmation(){
		if(confirm("Voulez-vous supprimer cet enregistrement?")) return true;
		else return false;
	}

	$(document).ready(function(){

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

	})
	
</script>

<?php
$js=ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$js);

/*****EDITION*****/
if(isset($id) && !empty($id)){
	if(isset($action)){
		switch($action){
			case 'edit':
				$detailSpecialite=$objMedocsParams->loadSpecialite($id);
				$smarty->assign('id',$id);
				$smarty->assign('designation',$detailSpecialite['designation']);
			break;

			case 'drop':
				if($objMedocsParams->dropSpecialite($id,$userName)) $msgArray=array("msg"=>"Suppression Effectuée avec succès","type"=>"success");
				else $msgArray=array("msg"=>"Erreur suppression", "type"=>"warning");
				$smarty->assign("msgArray",$msgArray);
			break;
		}
	}
	
}

/****TRAITEMENT DU FORMULAIRE D'ENREGISTREMENT***/
if(isset($save)){
	$designation=addslashes($designation);
	$pkColumnName="";
	$pkValue="";
	$create_time=date('Y-m-d');
	$history="Init. par ".$userName." le ".$create_time;
	if(isset($id) && !empty($id)){
		$pkColumnName="id";
		$pkValue=$id;
		$history="concat(history,'Mise à jour par ".$userName." le ".$create_time."')";
	}

	$dataTab=array(
		"designation"=>$designation,
		"create_time"=>$create_time,
		"history"=>$history,
		"create_id"=>$userPID
	);
	if($objMedocsParams->insertFromArray('care_medocs_specialites',$dataTab,$pkColumnName,$pkValue)){
		$msgArray=array("msg"=>"Enregistrement Effectuer avec succès", "type"=>"success");
	}
	else $msgArray=array("msg"=>"Erreur Enregistrement",'type'=>"warning");

	#Affichage du message
	$smarty->assign("msgArray",$msgArray);
}

/***LISTE DES SPECIALITE***/
if($listeSpecialite=$objMedocsParams->listeSpecialite()){
	ob_start();
	$pos=1;
	while($specialite=$listeSpecialite->FetchRow()){
		echo '<tr>';
		echo '<td>'.$pos.'</td>';
		echo '<td>'.$specialite['designation'].'</td>';
		echo '<td>';
		echo '<a href="medocs_params.php'.URL_APPEND.'&target=specialite&action=edit&id='.$specialite['id'].'"><button class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-pencil"></i></button></a>';
		echo '<a href="medocs_params.php'.URL_APPEND.'&target=specialite&action=drop&id='.$specialite['id'].'" onclick="return confirmation();"><button class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-trash"></i></button></a>';
		echo '</td>';
		echo '</tr>';
		$pos++;
	}
	$listing=ob_get_contents();
	ob_end_clean();
	$smarty->assign('listing',$listing);
}


$smarty->assign('sMainBlockIncludeFile','medocs/specialite.tpl');
?>