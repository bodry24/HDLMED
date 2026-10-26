<?php
require_once($root_path.'include/care_api_classes/class_core.php');
$dbCore=new Core;

require_once($root_path.'include/care_api_classes/class_person.php');
$objPerson=new Person;

require_once($root_path.'include/care_api_classes/class_encounter.php');
$objEncounter=new Encounter;

require_once($root_path.'modules/caisse/utils.php');

$thisfile=basename(__FILE__);
$breakfile=$root_path.'main/startframe.php'.URL_APPEND;
$_SESSION['sess_path_referer']=$top_dir.$thisfile;

#Controle de sécurité
$login=$_SESSION['sess_user_login_id'];
if(empty($login))  header("location:".$breakfile);
$pid_user=$objPerson->getUserPid($login);

global $db;

#Traitement du formulaire
if(isset($saveBulletin) || isset($save2NewBulletin)){
	$date_prescription=parseDate($date_prescription,'D/M/Y','/','','Y-M-D','-');
	$status=true;
	$dateTime=date('Y-m-d H:i:s');
	//var_dump($_POST);
	if(!isset($dateRealisation)) $dateRealisation=date('Y-m-d');
	
	$tabBulletin=array(
		"pid"=>$pid,
		"code_recu"=>$code_recu,
		"encounter_nr"=>$encounter_nr,
		"date_demande"=>$date_prescription,
		"prescripteur"=>$medecin,
		"traitement"=>$traitement,
		"renseignement"=>$renseignement,
		"valide_status"=>'O',
		"modify_id"=>$pid_user,
		"modify_time"=>$dateTime,
		"create_id"=>$pid_user,
		"create_time"=>$dateTime
		);
	
	if($dbCore->insertFromArray('care_bulletin_rx',$tabBulletin)){
		$id=$db->Insert_ID();	
		$cptActe=count($selectedActe);
		
		for($i=0; $i<$cptActe; $i++){
			$tabBulletinActe=array(
				"bulletin_id"=>$id,
				"item_id"=>$selectedActe[$i],
				"price"=>intval($price[$i]),
				"valide_status"=>'O'

				);
			echo '<br>';

			if(!$dbCore->insertFromArray('care_bulletin_rx_element',$tabBulletinActe)) {
				$status=false;
				break;
			}
			
			echo '<br>';
		}
	}
	else $status=false;
	#Je redirige l'enregistrement vers les analyses effectuées à la personne
	if($status==true){
		unset($tabBulletin);
		/*	if(isset($saveBulletin)) header('location:'.$root_path.'modules/radiology/waiting_test_request.php'.URL_APPEND);
		else header('location:'.$root_path.'modules/radiology/test_request.php'.URL_APPEND);*/
	}
}

ob_start();

echo '<link rel="stylesheet" href="'.$root_path.'plugins/datatable/media/datatables.min.css" type="text/css">';
echo '<link rel="stylesheet" href="'.$root_path.'js/jquery/jquery-ui-1.10.4.min.css" type="text/css">';

echo '<script type="text/javascript" src="'.$root_path.'plugins/datatable/media/datatables.min.js"></script>';
echo '<script type="text/javascript" src="'.$root_path.'js/jquery/jquery-ui-1.10.4.min.js"></script>';
?>

<style type="text/css">
	body{
		font-size: 9px;
	}
</style>
<link rel="stylesheet" href="<?php echo $root_path.'plugins/datetimepicker/css/bootstrap-datetimepicker.min.css';?>">
<script type="text/javascript" src="<?php echo $root_path.'plugins/datetimepicker/js/bootstrap-datetimepicker.min.js';?>"></script>
<script type="text/javascript" src="<?php echo $root_path.'js/gadgets/squeezebox.js'; ?>"></script>
<script language="javascript">

function addActe(value, texte, prix){
	//alert(value+texte);
	var nbTR=$("#tbPanier tr").length;
	var prix=parseInt(prix);
	//alert(value);
	var htmlTR="<tr><td>"+nbTR+"</td><td>"+texte+"<input type='hidden' name='selectedActe[]' id='"+value+"' value='"+value+"'/><td class='' width='100px'><input type='text' name='price[]' class='input-sm form-control text-right' value='"+prix+"'></td></td><td>";
	htmlTR+='<button onclick="return deleteLine('+nbTR+')" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-trash"></i></button></td></tr>';
	$("#tbPanier").append(htmlTR);
}

function deleteRow(bulletin_id){
	if(confirm("Voulez-vous supprimer cette demande d'analyse?")){
		$.post("<?php echo $root_path.'modules/ajax/ajax_radiology.php'; ?>",{rowId:bulletin_id},function(data){
			if(data=='o'){
				//Cacher la ligne
				$("tr[id="+bulletin_id+"]").hide('slow',function(){
					alert('Suppression effectue avec succes');
				});
			}
			else alert('Echec Suppression');
		});
		return false;
	}
	else return false;
}

function deleteLine(index){
	if(confirm("Voulez-vous supprimer cette ligne ?")){
		$("#tbPanier tr:eq("+index+")").remove();
	}
	else return false;
}

function formControl(){
	var encounter_nr=$("#encounter_nr").val();
	var nbSelected=$("input[name*=selectedActe]").length;
	var code_recu=$("#code_recu").val();
	var medecin=$("#medecin").val();
	var data_prescription=$("#date_prescription");
	$("[name=personnel]:checked").each(function(i){
		var radioPersonnel=$(this).val();
		//alert(radioPersonnel);
	})
	//return false;

	/*if(medecin==""){
		alert("Veuillez renseigner le nom du m\351d\351cin");
		$("#medecin").focus();
		return false;
	}*/
	if(date_prescription==""){
		alert("Veuillez renseigner la date de prescription");
		$("#date_prescription").focus();
		return false;
	}
	if((encounter_nr=="") && (code_recu=="") && (radioPersonnel=="N")){
		alert('Veuillez renseigner le num\351ro d\'admission ou le code recu');
		$("#encounter_nr").focus();
		return false;		
	}
	if(nbSelected==0){
		alert("Veuillez choisir un acte");
		return false;
	}

	return true;
}


$(document).ready(function(){
	  $("#datatable").DataTable({
            language: {
                processing:     "Traitement en cours...",
                search:         "Rechercher&nbsp;:",
                lengthMenu:    "Afficher _MENU_ éléments",
                info:           "Affichage de l'élement _START_ &agrave; _END_ sur _TOTAL_ éléments",
                infoEmpty:      "Affichage de l'élement 0 &agrave; 0 sur 0 éléments",
                infoFiltered:   "(filtré de _MAX_ éléments au total)",
                infoPostFix:    "",
                loadingRecords: "Chargement en cours...",
                zeroRecords:    "Aucun élément &agrave; afficher",
                emptyTable:     "Aucune donnée disponible dans le tableau",
                paginate: {
                    first:      "Premier",
                    previous:   "Précédent",
                    next:       "Suivant",
                    last:       "Dernier"
                },
                aria: {
                    sortAscending:  ": activer pour trier la colonne par ordre croissant",
                    sortDescending: ": activer pour trier la colonne par ordre décroissant"
                }
            }
           
        });

	$("#date_prescription").datetimepicker({
		format:'dd/mm/yyyy',
		autoclose:true,
		minView:2
	});

})


</script>

<?php

$js=ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$js);


#Breadcrumb
$breadcrumb=array("DOSSIER MEDICAL","DEMANDE D'EXAMEN");
$smarty->assign("breadcrumbArray", $breadcrumb);

#Medecin
$smarty->assign('medecin', $objPerson->getValue('name_last', $pid_user).' '.$objPerson->getValue('name_first', $pid_user));
#Formulaire d'admission
$objPerson->preloadPersonInfo($pid);

if($currentEncounter=$objPerson->currentEncounter($pid)){
	$smarty->assign('encounter_nr',$currentEncounter);
	#je recupère les ordonnances par défaut 
	$sqlOrd="SELECT s.article as article 
                        FROM care_encounter_prescription_sub s, care_encounter_prescription p 
                        WHERE p.nr=s.prescription_nr 
                        AND p.encounter_nr=".$currentEncounter;
    $resultOrd=$db->Execute($sqlOrd);
    $lesOrdonnances="";
    if(is_object($resultOrd)){
    	while ($ligne=$resultOrd->FetchRow()) {
    		if(!empty($lesOrdonnances)) $lesOrdonnances.=", ";
    		$lesOrdonnances.=$ligne["article"];
    	}
    	$smarty->assign("traitement", $lesOrdonnances);
    }

}

if(!isset($date_prescription)) $date_prescription=date('d/m/Y');
$datePrescription='<input type="text" class="form-control input-sm" name="date_prescription" id="date_prescription" value="'.$date_prescription.'">';
$smarty->assign('datePrescription',$datePrescription);

ob_start();
$sql="SELECT i.*,t.item_type_description 
FROM care_billing_item i, care_billing_item_type t 
WHERE t.classe IN('radio') AND t.item_type_code=i.item_type ORDER BY i.item_type,i.item_description ASC";
$options="";
if($result=$db->Execute($sql)){
	$optiongroup="";
	$groups=0;
	while($ligneActe=$result->FetchRow()){
		if(empty($optiongroup) || $optiongroup!=$ligneActe['item_type_description']){
			if($groups!=0) $options.="</optgroup>";
			$groups++;
			$optiongroup=$ligneActe['item_type_description'];
			$options.="<optgroup label=".$ligneActe['item_type_description'].">";
		}
		$options.='<option value="'.$ligneActe['item_code'].'" prix="'.$ligneActe['item_unit_cost'].'">'.$ligneActe['item_description'].'</option>';
	}
}
echo '<select name="selectActe" class="form-control " id="selectActe" size="15" ondblclick="addActe(this.value,this.options[this.selectedIndex].text, this.options[this.selectedIndex].getAttribute(\'prix\'))">';
	echo $options;
echo '</select>';

$listeExamens=ob_get_contents();
ob_end_clean();
$smarty->assign('listeExamens',$listeExamens);

#Affichage des examens
ob_start();
	$sql="SELECT p.pid,p.name_last,p.name_first,r.* FROM care_bulletin_rx r, care_person p WHERE valide_status='O' AND p.pid=r.pid AND r.pid=".$pid." ORDER BY r.date_demande DESC";
	
	if($result=$db->Execute($sql)){
		$rows=$result->RecordCount();
	}

	if(!empty($rows)){
		$compte=1;
		while($ligne=$result->FetchRow()){
			if($ligne['done']=='O') $class="success";
			else $class="";
			echo "<tr class='".$class."' id='".$ligne['id']."'><td class='text-right'>";
			echo $compte."</td><td class='text-center'>";
			$dateEnreg=parseDate($ligne['date_demande'],'Y-M-D','-','','D/M/Y','/');
			echo $dateEnreg."</td><td>";
			echo $ligne['prescripteur']."</td><td>";
			//Recuperation des actes
			$sql="SELECT i.item_description FROM care_billing_item i, care_bulletin_rx_element e WHERE e.item_id=i.item_code AND e.bulletin_id=".$ligne['id'];
			//echo $sql;
			$examens="";
			if($resultExam=$db->Execute($sql)){
				while($ligneExam=$resultExam->FetchRow()){
					if(!empty($examens)) $examens.=", ";
					$examens.=$ligneExam['item_description'];
				}
			}
			echo $examens."</td>
			<td>".$ligne['traitement']."</td>
			<td>".$ligne['renseignement']."</td>
			<td class='text-center'>";
			$jprint='javascript:squeezebox("print_examen.php'.URL_APPEND.'&bulletin_id='.$ligne['id'].'")';
			echo "<button class='btn btn-sm btn-primary pull-left' onclick='".$jprint."' title='Imprimer la demande'><i class='glyphicon glyphicon-print'></i></button>";
			echo '&nbsp;';
			if($ligne['done']=='N'){
				echo "<button class='btn btn-sm btn-danger pull-right' onclick='return deleteRow(".$ligne['id'].")' title='Supprimer la demande'><i class='glyphicon glyphicon-trash'></i></button>";
			}else{
				echo '&nbsp;';
			}
				
			echo "</td></tr>";		

			$compte++;
		}
	}

	$listing=ob_get_contents();
ob_end_clean();
$smarty->assign('listing', $listing);

$smarty->assign('sMainBlockIncludeFile','medocs/medocs_examen.tpl');
?>