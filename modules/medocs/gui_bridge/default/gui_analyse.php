<?php
$lang_tables[] = 'departments.php';
$lang_tables[] = 'nursing.php';
define('LANG_FILE','konsil.php');

require_once($root_path.'include/care_api_classes/class_person.php');
$objPerson=new Person();

require_once($root_path.'include/care_api_classes/class_ecombill.php');
$objEcombill=new eCombill();

require_once($root_path.'include/care_api_classes/class_encounter.php');
$objEncounter=new Encounter();

require_once($root_path.'include/care_api_classes/class_ward.php');
$objWard=new Ward();

require_once($root_path.'include/care_api_classes/class_core.php');
$objCore=new Core();

require_once($root_path.'modules/caisse/utils.php');

$user_login=$_SESSION['sess_login_userid'];
$user_pid=$objPerson->getUserPid($user_login);

require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'global_conf/inc_global_address.php');
					
/* Here begins the real work */
require_once($root_path.'include/core/inc_date_format_functions.php');
   
# Create a core object
require_once($root_path.'include/core/inc_front_chain_lang.php');
$core = & new Core;

global $db;

#Breadcrumb
$breadcrumb=array("DOSSIER MEDICAL","DEMANDE D'ANALYSE");
$smarty->assign("breadcrumbArray", $breadcrumb);

#Medecin
$med_pid=$user_pid;
/**
**
**Voir si la personne connectée est un docteur
**Si oui recupere le med_pid
**
**/

#Recuperation du numéro d'admission
$encounter_nr=$objEncounter->isPIDCurrentlyAdmitted($pid);
$encounterClassNr=intval($objEncounter->getValue('encounter_class_nr', $encounter_nr));
if(!isset($encounter_nr)){
	$encounter_nr=$_GET['encounter_nr'];
}

if(isset($encounter_nr) && $encounter_nr){		
	include_once($root_path.'include/care_api_classes/class_encounter.php');
	$enc_obj=new Encounter;
	if($enc_obj->loadEncounterData($encounter_nr)) {
		$full_en=$encounter_nr;
		$result=&$enc_obj->encounter;
	}
	else{
		$edit=0;
		$mode="";
		$encounter_nr="";
	}
}

$Time=date('Y-m-d H:i:s');

if(!$encounter_nr){
	$msg="Cette personne n'est pas actuellement admise. Veuillez l'admettre d'abord!";
	$msgType="danger";
}
//Recupere si la personne est assuré
//$assure=$objPerson->isInsuredMultiple($encounter_nr);

if(!isset($mode)) $mode="";
	
#Traitement du formulaire
if(isset($save)){
	//var_dump($encounter_nr);die();	
	$state=true;
	$nbMontant=count($montant);
	$nbItem=count($acte);
	$nbDate=count($itemDate);

	if(($nbMontant!=$nbItem) || ($nbItem!=$nbDate) || ($nbMontant!=$nbDate)){
		echo 'Erreur, Veuillez remplir tous les champs';
		$state=false;
	}else{
		if(isset($user_pid) && !empty($user_pid)){
			//Enregistrement dans la table demande d'examen
			$dataArray1 = array(
				'patient_pid'   => $pid,//A revoir avoir Bogui
				'id_prescriber' => $med_pid,
				'bill_id_bill'  => $pid,
				'status'        => 'O',
				'date_request'  => date('Y-m-d'),
				'close_status'  => 1,
				'valid_status'  => 0,
				'create_id'     => $user_pid,
				'create_time'   => $item_date,
				'modify_id'     => $user_pid,
				'history'       => 'Enrégistrer par '.$user_pid. ' le '.date('Y-m-d H:i:s'),
				'encounter_nr'  => $encounter_nr
			);

			if(isset($laboratory_id) && !empty($laboratory_id)) $primaryKey="id_lab_request";
			else $primaryKey="";

			if($objCore->insertFromArray('care_labo_lab_request',$dataArray1,$primaryKey,@$laboratory_id)){				
				if(!isset($laboratory_id) || empty($laboratory_id)) {
					$id_lab_request=$db->Insert_ID();
				}
				else $id_lab_request=$laboratory_id;
				
				for($i=0; $i<$nbItem;$i++){
					$item_date=parseDate($itemDate[$i],'D/M/Y','/','','Y-M-D','-',true);
					$amount=str_replace(' ','', $montant[$i]);
					$amount=$amount*1;
					$itemId=$acte[$i];

					$dataArray2=array(
						'id_lab_request'  => $id_lab_request,
						'amount'          => $amount,
						'status'          => 'O',
						'create_id'       => $user_pid,
						'create_time'     => $item_date,
						'modify_id'       => $user_pid,
						'modify_time'     => $item_date,
						'id_billing_item' => $itemId
					);

					$id_analysis="";
					$analysis_primary_key="";

					if(isset($analysis)){
						if(isset($analysis[$i]) && !empty($analysis[$i])){
							$id_analysis=$analysis[$i];
							$analysis_primary_key="id_analysis";
						}
					}

					if($objCore->insertFromArray('care_labo_analysis_lab_request',$dataArray2,$analysis_primary_key,@$id_analysis)){
						
						$dataArrayCopy=array(
							'bill_item_status'       => '0',
							'type'  		         => "SO",
							'create_id'              => $user_pid,
							'billitem_valid'          => "O",
							'bill_item_encounter_nr' => $encounter_nr,
							'bill_item_code'         => $itemId,
							'bill_item_unit_cost'    => $amount,
							'bill_item_units'        => 1,
							'bill_item_amount'       => $amount,
							'bill_item_date'         => $item_date,
							'pid' 					 => $med_pid
						);

						if(empty($id_analysis)) {
							$id_prescription='lab_'.$db->Insert_ID();
							$bill_item_primary_key="";
							$dataArrayCopy['id_prescription']=$id_prescription;
						}
						else{
							$id_prescription='lab_'.$id_analysis;
							$bill_item_primary_key="";
						}
						#Si c'est le cas d'un hospitalisé
						if($encounterClassNr==1){
							if(!$objCore->insertFromArray('care_billing_bill_item',$dataArrayCopy,$bill_item_primary_key,@$id_analysis)) $state=false;
						}
						
					}
					else $state=false;
				}
			}

		}else{
			$msgType="success";
			$msg="Veuillez vous reconnecter.";
		}

	}

	if($state=true){
		$msgType="success";
		$msg="Copie effectuée avec succès";
	}
	else{
		$msgType="warning";
		$msg="Echec de la copie!! Veuillez contacter le service informatique";
	}
}

switch($mode){
	case 'save':
		$state=true;
		$nbMontant=count($montant);
		$nbItem=count($acte);
		$nbDate=count($itemDate);

		if(($nbMontant!=$nbItem) || ($nbItem!=$nbDate) || ($nbMontant!=$nbDate)){
			echo 'Erreur, Veuillez remplir tous les champs';
			$state=false;
		}else{
			if(isset($user_pid) && !empty($user_pid)){
				//Enregistrement dans la table demande d'examen
				$dataArray1 = array(
					'patient_pid'   => $pid,//A revoir avoir Bogui
					'id_prescriber' => $med_pid,
					'bill_id_bill'  => $pid,
					'status'        => 'O',
					'date_request'  => date('Y-m-d'),
					'close_status'  => 1,
					'valid_status'  => 0,
					'create_id'     => $user_pid,
					'create_time'   => $item_date,
					'modify_id'     => $user_pid,
					'history'       => 'Enrégistrer par '.$user_pid. 'le '.date('Y-m-d H:i:s'),
					'encounter_nr'  => $encounter_nr
				);
			
				if(isset($laboratory_id) && !empty($laboratory_id)) $primaryKey="id_lab_request";
				else $primaryKey="";

				if($objCore->insertFromArray('care_labo_lab_request',$dataArray1,$primaryKey,@$laboratory_id)){				
					if(!isset($laboratory_id) || empty($laboratory_id)) {
						$id_lab_request=$db->Insert_ID();
					}
					else $id_lab_request=$laboratory_id;
					
					for($i=0; $i<$nbItem;$i++){
						$item_date=parseDate($itemDate[$i],'D/M/Y','/','','Y-M-D','-',true);
						$amount=str_replace(' ','', $montant[$i]);
						$amount=$amount*1;
						$itemId=$acte[$i];

						$dataArray2=array(
							'id_lab_request'  => $id_lab_request,
							'amount'          => $amount,
							'status'          => 'O',
							'create_id'       => $user_pid,
							'create_time'     => $item_date,
							'modify_id'       => $user_pid,
							'modify_time'     => $item_date,
							'id_billing_item' => $itemId
						);

						$id_analysis="";
						$analysis_primary_key="";

						if(isset($analysis)){
							if(isset($analysis[$i]) && !empty($analysis[$i])){
								$id_analysis=$analysis[$i];
								$analysis_primary_key="id_analysis";
							}
						}

						if($objCore->insertFromArray('care_labo_analysis_lab_request',$dataArray2,$analysis_primary_key,@$id_analysis)){
							
							$dataArrayCopy=array(
								'bill_item_status'       => '0',
								'type'  		         => "SO",
								'create_id'              => $user_pid,
								'billitem_valid'          => "O",
								'bill_item_encounter_nr' => $encounter_nr,
								'bill_item_code'         => $itemId,
								'bill_item_unit_cost'    => $amount,
								'bill_item_units'        => 1,
								'bill_item_amount'       => $amount,
								'bill_item_date'         => $item_date,
								'pid' 					 => $med_pid
							);

							if(empty($id_analysis)) {
								$id_prescription='lab_'.$db->Insert_ID();
								$bill_item_primary_key="";
								$dataArrayCopy['id_prescription']=$id_prescription;
							}
							else{
								$id_prescription='lab_'.$id_analysis;
								$bill_item_primary_key="";
							}
							/**
							A revoir
							if(!$objCore->insertFromArray('care_billing_bill_item',$dataArrayCopy,$bill_item_primary_key,@$id_analysis)) $state=false;
							**/
						}
						else $state=false;
					}
				}

			}else{
				$msgType="success";
				$msg="Veuillez vous reconnecter.";
			}

		}

		if($state=true){
			$msgType="success";
			$msg="Copie effectuée avec succès";
		}
		else{
			$msgType="warning";
			$msg="Echec de la copie!! Veuillez contacter le service informatique";
		}
		break; // end of case 'save'

	case 'delete':
			if(isset($id_analysis) && !empty($id_analysis)){
				$sqlDelete1="UPDATE care_labo_analysis_lab_request SET status='N' WHERE lab_request_item_id =".$id_analysis;
				$sqlDelete2="UPDATE care_billing_bill_item SET billitem_valid='N' WHERE id_prescription='lab_".$id_analysis."'";
				if($db->Execute($sqlDelete1) && $db->Execute($sqlDelete2)){
					$msg="Suppression effectuée avec succès";
					$msgType="success";
				}
				else{
					$msg="Echec Suppression!! Veuillez contacter le service Informatique";
					$msgType="warning";
				}
			}
		break; // end of case 'save'
				
	default: $mode="";
						   
}
ob_start();
?>
<!-- DATETIME-PICKER -->
<link rel="stylesheet" href="<?php echo $root_path.'plugins/datetimepicker/css/bootstrap-datetimepicker.css' ?>">
<script type="text/javascript" src="<?php echo $root_path.'plugins/datetimepicker/js/bootstrap-datetimepicker.js' ?>"></script>

<!-- SELECT 2 -->
<link rel="stylesheet" href="<?php echo $root_path.'plugins/select2/select2.css' ?>">
<link rel="stylesheet" href="<?php echo $root_path.'plugins/select2/select2-bootstrap.css' ?>">
<script type="text/javascript" src="<?php echo $root_path.'plugins/select2/select2.js' ?>"></script>

<!-- MULTISELECT -->
<link rel="stylesheet" href="<?php echo $root_path.'plugins/multiselect/css/bootstrap-multiselect.css' ?>">
<script type="text/javascript" src="<?php echo $root_path.'plugins/multiselect/js/bootstrap-multiselect.js' ?>"></script>

<script language="javascript">
	<!-- 
	//Annulation d'une demande
	function deleteDemande(nr,element){
		if(confirm("Voulez-vous annuler cette demande?")){
			$.post('<?php echo $root_path;?>modules/nursing/rep_ajax.php'+'<?php echo URL_APPEND; ?>',{action:'deleteDemande', valeur:nr}, function(data){
				alert(data['msg']);
				if(data['msgType']=="ok") $(element).parents('tr').remove();
			},'json');
		}else{
			return false;
		}
	}
	
	function dropLine(element){
		if(confirm('Voulez-vous supprimer cette ligne')){
			$(element).parents('tr').remove();
			//$(element).closest('tr').remove();
			return false;	
		} 
		else return false;
	}

	$(document).ready(function(){
		var encounter_nr='<?php echo $encounter_nr;?>';
		$("#item_date").datetimepicker({
			format:'dd/mm/yyyy hh:ii:ss',
			autoclose:true,
			minView:0
		});	

		$("#med_pid").select2();

		$("#acte").multiselect({
			enableFiltering:true,
			enableCaseInsensitiveFiltering:true,
			numberDisplayed: 3,	
			maxHeight:350,
			onChange:function(option,checked,select){
				var optionValue=option.val();
				//alert(optionValue);
				//var personInsurance=$("#personInsurance").val();
				personInsurance="";
				if(checked==true){
					var position=$("#acte option:selected").length;
					//alert(position);
					$.post('<?php echo $root_path; ?>modules/ajax/ajaxFacturationAutomatique.php',{itemId:optionValue,personInsurance:personInsurance,position:position, encounter_nr:encounter_nr},function(data){
						$("#tableBody").append(data);
						//alert(data);
					});
				}
				else{
					/*var code=optionValue.split('_');
					var item=code[1];*/
					$("#tableBody").find("tr#"+optionValue).remove();				
				}
			}
		});

		$("#test").multiselect({
			enableFiltering:true
		});
	});

	function confirmSuppression(){
		if(confirm('Voulez-vous supprimer cet examen')) return true;
		else return false;
	}

	//-->
</script>

<style>
.form-group{
	padding: 5px;
	margin: 5px;
}
</style>
<?php

$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp);

//Select des actes d'analyses
ob_start();
$sqlTypeacte="SELECT i.*
			FROM care_billing_item i, care_billing_item_type m, care_billing_item_type_classe a
			WHERE m.classe=a.classe
				AND m.item_type_code= i.item_type
				AND a.classe='laboratoire' ORDER BY i.item_description ASC";
if($resultTypeActe=$db->Execute($sqlTypeacte)){
	while($typeActe=$resultTypeActe->FetchRow()){
		echo '<option value="'.$typeActe['item_code'].'"';
		if(isset($code) && $code==$typeActe['item_code']) echo 'selected="selected"';
		echo'>'.strtoupper($typeActe['item_description']).'</option>';
	}
}

$selectanalysis = ob_get_contents();
ob_end_clean();

//Liste des medécins
ob_start();
$sqlMed="SELECT DISTINCT p.pid, name_last, name_first, name_2, role
	FROM care_person, care_personell p, care_role_person r, care_personell_assignment a
	WHERE p.pid = care_person.pid
	AND p.nr = a.personell_nr
	AND a.role_nr = r.nr
	AND role_nr=17
	AND a.location_type_nr=1
	ORDER BY name_last,name_first ASC";

if($resultMed=$db->Execute($sqlMed)){
	while($med=$resultMed->FetchRow()){
		echo '<option value="'.$med['pid'].'"';
		if(isset($med_pid) && ($med_pid==$med['pid'])) echo 'selected="selected"';
		echo '>'.$med['name_last'].' '.$med['name_first'].'</option>';
	}
}
$selectMedecin=ob_get_contents();
ob_end_clean();

//$baseUrl=$root_path.'modules/medocs/medocs.php'.URL_APPEND.'&target=analyse&encounter_nr='.$encounter_nr;
$smarty->assign('baseUrl',$baseLink."&target=analyse");

//Info patient
$objEncounter->loadEncounterData($encounter_nr);
$encounter_name=$objEncounter->LastName();
$encounter_name.=" ".$objEncounter->FirstName();
$nameSpouse=$objEncounter->SpouseName();
if(!empty($nameSpouse)) $encounter_name.='(Epse '.$nameSpouse.')';
$smarty->assign('patientName',$encounter_name);

$encounterLocation=$objWard->EncounterLocationsInfo($encounter_nr);
//Informations sur la chambre
/*
Commenter pour le moment
$ward_id=$encounterLocation['ward_id'];
$ward_nr=$encounterLocation['ward_nr'];
$room_nr=$encounterLocation['room_nr'];
$bed_nr=$encounterLocation['bed_nr'];  
$chambre=$objWard->getRoomNameByNr($room_nr);
$EncounterLocationsInfo=$chambre.' LIT N° '.$bed_nr;

$smarty->assign('locationInformation',$EncounterLocationsInfo);
*/
//Recupération des demandes d'analyses s'il y en a
$sqlDemande="SELECT * FROM care_labo_lab_request WHERE valid_status=0 AND encounter_nr=".$encounter_nr." ORDER BY id_lab_request DESC";

if($resultDemande=$db->Execute($sqlDemande)){
	$i=0;
	//ob_start();
	while($ligne=$resultDemande->FetchRow()){
       // var_dump($ligne);die();
		$i++;
		$date=parseDate($ligne['date_request'], 'Y-M-D','-','','D/M/Y','/');
		$operateur=$objPerson->getValue('name_last', $ligne['create_id']).' '.$objPerson->getValue('name_first', $ligne['create_id']);
		
		$urlPrint=$root_path.'modules/biasa_pdf/print_test_request_pdf.php'.URL_APPEND.'&nr='.$ligne['id_lab_request'].'&encounter_nr='.$encounter_nr;
		$urlPrint2=$root_path.'modules/biasa_pdf/print_prescription_html2pdf.php'.URL_APPEND.'&nr='.$ligne['id_lab_request'].'&encounter_nr='.$encounter_nr;

		$option='<a href="'.$baseLink.'&target=analyse&laboratory_id='.$ligne['id_lab_request'].'&mode=edit"><button class="btn btn-xs btn-success" title=""><i class="glyphicon glyphicon-edit"></i></button></a>
			<a href="'.$urlPrint.'" class="btn btn-xs btn-primary" style="color:white" target="_blank()" title="Imprimer Fiche Médicale">
				<span class="glyphicon glyphicon-print"></span>
			</a>
			<a href="'.$urlPrint2.'" class="btn btn-xs btn-warning" style="color:white"  target="_blank()" title="Imprimer Ordonnance Interne">
				<span class="glyphicon glyphicon-print"></span>
			</a>
			<a href="#" onclick="return deleteDemande('.$ligne['id_lab_request'].',this)" class="btn btn-xs btn-danger" style="color:white" onclick="" title="Supprimer Demande">
				<span class="glyphicon glyphicon-trash"></span>
			</a>';
		
		$demande="<tr><td>".$i."</td>
				 	<td>".$date."</td>
				 	<td>".$operateur."</td>
					<td>".$option."</td>
				</tr>";
		$smarty->append('demandes', $demande);
	}
}

//FORMULAIRE D'ENREGISTREMENT

//$smarty->assign('formAction',$root_path.'modules/medocs/medocs.php'.URL_APPEND.'&target=analyse&encounter_nr='.$encounter_nr.'&mode=save');

$smarty->assign('selectActe',$selectanalysis);

$smarty->assign('selectMedecin',$selectMedecin);

$smarty->assign('montant',@$montant);

if(empty($item_date)) $item_date=date('d/m/Y H:i:s');
$smarty->assign('defaultDate',$item_date);

if(isset($mode) && ($mode=="edit")) $smarty->assign('laboratory_id',$laboratory_id);
//Listes des analyses de la demande
if(isset($laboratory_id) && !empty($laboratory_id)){
	//Recuperation des examens
	$sqlExamens="SELECT a.lab_request_item_id, i.item_description, i.item_code, a.id_billing_item, b.bill_item_unit_cost, b.bill_item_date FROM care_labo_analysis_lab_request a, care_billing_item i,care_billing_bill_item b  WHERE id_lab_request=".$laboratory_id." AND a.id_billing_item=i.item_code AND b.id_prescription=concat('lab_',a.lab_request_item_id) AND status='O'";
	//echo $sqlExamens;
	if($resultExamens=$db->Execute($sqlExamens)){
		$smarty->assign('laboratory_id',$laboratory_id);
		$pos=1;
		while($rowExamens=$resultExamens->FetchRow()){
			$dateExamens=parseDate($rowExamens['bill_item_date'],'Y-M-D','-','','D/M/Y','/');
			$tableRow=array(
				'rowId'=>$pos,
				'analysis'=>$rowExamens['item_description'],
				'pu'=>$rowExamens['bill_item_unit_cost'],
				'date'=>$dateExamens,
				'id_analysis'=>$rowExamens['lab_request_item_id']);
			$smarty->append('items',$tableRow);
			$pos++;
		}
	}

}

#Affichage du message d'informations
if(isset($msg) && !empty($msg)){
	$msgArray=array('type'=>$msgType, 'msg'=>$msg);
    $smarty->assign('msgArray', $msgArray);
}


$smarty->assign('sMainBlockIncludeFile','medocs/medocs_analyse.tpl');
?>