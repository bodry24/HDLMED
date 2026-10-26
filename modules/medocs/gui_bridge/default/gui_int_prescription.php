<?php
include_once($root_path.'include/care_api_classes/class_prescription.php');
$objPrescription=new Prescription;

require_once($root_path.'include/care_api_classes/class_product.php');
$objProduct=new Product;

require_once($root_path.'include/care_api_classes/class_encounter.php');
$objEncounter=new Encounter;

$app_types=$objPrescription->getAppTypes();
$pres_types=$objPrescription->getPrescriptionTypes();

#CSS et JS
ob_start();
?>

<link rel="stylesheet" href="<?php echo $root_path.'js/jquery/jquery-ui-1.9.1.custom.min.css';?>"/>
<script type="text/javascript" src="<?php echo $root_path.'js/jquery/jquery-ui-1.9.1.custom.min.js';?>"></script> 
<script type="text/javascript" src="<?php echo $root_path.'js/gadgets/squeezebox.js'; ?>"></script>
<style type="text/css">
	.input-xs{
		height: 10px;
		font-size: 10px;
	}
</style> 
<script type="text/javascript">


	function dropRow(element){
		if(confirm("Etes vous sur?")){
			$(element).closest("tr").remove();
		}
	}

	function initialise(){
		$("#medicament").val("");
		$("#article").val("");
		$("#quantite").val("");
		$("#application_type_nr").val("");
		$("#posologie").val("");

		$("#medicament").focus();
	}

	function addRow(){
		var medicament=$("#medicament").val();
		var article=$("#article").val();
		var application_type_nr=$("#application_type_nr").val();
		var application_type_name=$("#application_type_nr").find("option:selected").text();
		var posologie=$("#posologie").val();
		var qte=$("#qte").val();

		if(medicament==''){
			alert("Veuillez selectionner le medicament");
			$("#medicament").focus();
			return false;
		}else if(qte==''){
			alert("Veuillez saisir la quantité");
			$("#qte").focus();
			return false;
		}else if(application_type_nr==''){
			alert("Veuillez selectionner le type d'application");
			$("#application_type_nr").focus();
			return false;
		}else if(posologie==''){
			alert("Veuillez saisir la posologie");
			$("#posologie").focus();
			return false;
		}else{

			var tr="<tr><td><input type='hidden' name='article[]' id='article_"+article+"' value='"+article+"'><input type='hidden' name='nom[]' value='"+medicament+"'>"+medicament+"</td><td class='text-right'><input type='hidden' name='quantite[]' value='"+qte+"'>"+qte+"</td><td><input type='hidden' name='application_type_nr[]' value='"+application_type_nr+"' size='7'>"+application_type_name+"</td><td><input type='hidden' name='posologie[]' value='"+posologie+"'>"+posologie+"</td><td><button onclick='return dropRow(this);' class='btn btn-sm btn-danger'><i class='glyphicon glyphicon-trash'></i></button></td></tr>";

			$("#tableContent").append(tr);

			initialise();

		}
		
	}

	function deletePrescriptionSub(element){
		$.post("<?php echo $root_path.'modules/ajax/ajaxMedocsMedocs.php?target=ordonnance&action=deletePrescriptionSub';?>",
			{nr:element},
			function(data){

			});
	}

	function afficherDetail(element){
		$.post("<?php echo $root_path.'modules/ajax/ajaxMedocsMedocs.php?target=ordonnance&action=detail';?>",
			{nr:element},
			function(data){
				$('#detailContent > tr').remove();//Je supprime tous les tr d'abord
				$("#detailContent").append(data);
		});
	}

	function chechSubmit(){
		//alert($("#encounter_nr").val());
		if($("#encounter_nr").val()==0 || $("#encounter_nr").val()==''){
			alert('Veuillez admettre la personne d\'abord');
			return false;
		}else if($("#pid").val()==0 || $("#pid").val()==''){
			alert('Veuillez revoir le pid du patient ou revoir si vous etes connecter');
			return false;
		}else{
			return true;
		}
		
	}

	/**Permet de changer de type journalier ou horaire**/
	function loadRepere(){
    	var rp=$("#repere option:selected").text();
        if(rp=='JOURNALIER'){
        	$(".cachePosologie").show();
        	$(".cacheUniteDuree").hide();
        }else{
        	$(".cachePosologie").hide();
        	$(".cacheUniteDuree").show();
        }
    }
		

    $(function(){
    	loadRepere();

    	$("#medicament").autocomplete({
			source:"<?php echo $root_path.'modules/ajax/ajaxMedocsMedocs.php?target=ordonnance&action=autocompletePharmacie';?>",
			select:function(event,ui){
				//alert(ui.item.designation);
				//$("#medicament").val(ui.item.label);
				$("#bestellnum").val(ui.item.id);
				//$("#qte").focus();
			}
		});

	
		$("#exemple").DataTable({
			order: [[3,"desc"]],
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

        /* $("#date").datetimepicker({
            format:'dd-mm-yyyy hh:ii',
            autoclose: true,
            todayBtn: true,
            minuteStep:1
        });
        $("#date_fin").datetimepicker({
			format:'dd-mm-yyyy',
			autoclose:true,
			minView:2
		});
		*/
       
        $("#date_debut").datetimepicker({
			format:'dd-mm-yyyy hh:ii',
			autoclose:true,
			todayBtn: true,
            minuteStep:1
		});

        $("#date_fin").datetimepicker({
			format:'dd-mm-yyyy hh:ii',
			autoclose:true,
			todayBtn: true,
            minuteStep:1
		});

        $("#date_effective").datetimepicker({
			format:'dd-mm-yyyy hh:ii',
			autoclose:true,
			todayBtn: true,
            minuteStep:1
		});

    }); 
</script>
<?php
$js=ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript', $js);

#Breadcrumb
$breadcrumb=array("DOSSIER MEDICAL","PROTOCOLE DE TRAITEMENT");
$smarty->assign("breadcrumbArray", $breadcrumb);

$encounter_nr=$objEncounter->isPIDCurrentlyAdmitted($pid);//Patient admis
$smarty->assign('encounterNr', $encounter_nr);
#Les posologies
$tabPosologies=array(
	"X-X-X",
	"X-X-0",
	"X-0-0",
	"0-X-X",
	"0-X-0",
	"0-0-X",
	"X-0-X"
	);
ob_start();
	foreach ($tabPosologies as $v) {
		$selected=($posologie==$v)?"selected=selected":"";
		echo '<option value="'.$v.'" '.$selected.'>'.$v.'</option>';
	}
	$optionsPosologies=ob_get_contents();
ob_end_clean();
$smarty->assign('optionsPosologies', $optionsPosologies);

#Les reperes
$tabReperes=array('journalier', 'horaire');
ob_start();
	foreach ($tabReperes as $v) {
		$selected=($repere==$v)?"selected=selected":"";
		echo '<option value="'.$v.'" '.$selected.'>'.mb_strtoupper($v).'</option>';
	}
	$optionsReperes=ob_get_contents();
ob_end_clean();
$smarty->assign('optionsReperes', $optionsReperes);

#Les Unités de durrée
$tabUnites=array('jrs', 'h');
ob_start();
	foreach ($tabUnites as $v) {
		$selected=($repere==$v)?"selected=selected":"";
		echo '<option value="'.$v.'" '.$selected.'>'.mb_strtoupper($v).'</option>';
	}
	$optionsUniteDurees=ob_get_contents();
ob_end_clean();
$smarty->assign('optionsUniteDurees', $optionsUniteDurees);
/************Modifiaction des données ****************************/

if(isset($mode)&&!empty($mode)){
	switch ($mode) {
		case 'update':
			$id_prescription=$_GET['id_prescription'];
			$sqlUpdate="SELECT p.artikelname as designation,  i.* FROM care_encounter_internal_prescription i,  care_pharma_products_main p WHERE p.bestellnum=i.bestellnum AND i.valide_status='O' AND i.id_prescription=".$id_prescription;
			if($resultUpdate=$db->execute($sqlUpdate)){
				$prescription=$resultUpdate->FetchRow();
				$date1=new DateTime($prescription['debut_traitement']);
				$date2=new DateTime($prescription['fin_traitement']);
				$dif=$date2->diff($date1);
				$nbre_fois=$dif->days;
				//var_dump($nbre_fois);
				//var_dump($prescription['fin_traitement']);
				//die();
				//$date_fin="";
				$smarty->assign('nbre_fois', $nbre_fois);
				$smarty->assign('readonly', "readonly=readonly");

				$smarty->assign('mode', "update");
				$smarty->assign('encounterNr', $prescription['encounter_nr']);
				$smarty->assign('bestellnum', $prescription['bestellnum']);
				$smarty->assign('quantite', $prescription['quantite']);
				$smarty->assign('duree', $prescription['duree']);
				$smarty->assign('unite_duree', $prescription['unite_duree']);
				$smarty->assign("commentaire", $prescription['commentaire']);
				$smarty->assign("idPrescription", $prescription['id_prescription']);
				$smarty->assign("medicament", $prescription['designation']);

				if(!is_null($prescription['debut_traitement']) && $prescription['debut_traitement']!="0000-00-00 00:00:00"){
					//$debut_prescription=parseDate($prescription['debut_traitement'], 'Y-M-D','-','','D-M-Y','-',true);
					$debut_prescription=$prescription['debut_traitement'];
				}else $debut_prescription="";

				if(!is_null($prescription['fin_traitement']) && $prescription['fin_traitement']!="0000-00-00 00:00:00"){
					//$fin_prescription=parseDate($prescription['fin_traitement'], 'Y-M-D','-','','D-M-Y','-',true);
					$fin_prescription=$prescription['fin_traitement'];
				}else $fin_prescription="";

				if(!is_null($prescription['fin_traitement_effectif']) && $prescription['fin_traitement_effectif']!="0000-00-00 00:00:00"){
					//$fin_prescription_effectif=parseDate($prescription['fin_traitement_effectif'], 'Y-M-D','-','','D-M-Y','-',true);
					$fin_prescription_effectif=$prescription['fin_traitement_effectif'];
				}else $fin_prescription_effectif="";

				$smarty->assign("date_debut", $debut_prescription);
				$smarty->assign("date_fin", $fin_prescription);
				$smarty->assign("date_effective", $fin_prescription_effectif);
			
				#Les reperes
				$repere=$prescription['repere'];
				ob_start();
					foreach ($tabReperes as $v) {
						$selected=($repere==$v)?"selected=selected":"";
						echo '<option value="'.$v.'" '.$selected.'>'.mb_strtoupper($v).'</option>';
					}
					$optionsReperes=ob_get_contents();
				ob_end_clean();
				$smarty->assign('optionsReperes', $optionsReperes);

				#Posologie
				$posologie=$prescription['posologie'];
				ob_start();
					foreach ($tabPosologies as $v) {
						$selected=($posologie==$v)?"selected=selected":"";
						echo '<option value="'.$v.'" '.$selected.'>'.$v.'</option>';
					}
					$optionsPosologies=ob_get_contents();
				ob_end_clean();
				$smarty->assign('optionsPosologies', $optionsPosologies);

			}
			break;
		case 'delete':
			$sqlDelete="UPDATE care_encounter_internal_prescription SET valide_status='N' WHERE id_prescription=".$id_prescription;
			if($db->Execute($sqlDelete)){
				$msgType="success";
				$msg="Suppression effectuée avec succès";
			}else{
				$msgType="danger";
				$msg="Suppression non effectuée";
			}
			break;
		case 'arreter':
			$sqlDelete="UPDATE care_encounter_internal_prescription SET fin_traitement_effectif='".date('Y-m-d H:i:s')."' WHERE id_prescription=".$id_prescription;
			if($db->Execute($sqlDelete)){
				$msgType="success";
				$msg="Traitement Terminé avec succès";
			}else{
				$msgType="danger";
				$msg="Traitement non terminé, il y a une erreur";
			}
			break;


		default:
			# code...
			break;
	}
}
/*=======================Traitement du formulaire=======================*/
if(isset($save)){
	$Time1=date('Y-m-d H:i:s');
	$heure=date("H:i:s");
	//array_map("addslashes", $_POST);
	extract($_POST);


	if(!isset($id_prescription) || empty($id_prescription)){
		$date_debut=$Time1;
		$date = new DateTime(date('Y-m-d'));
		$date->add(new DateInterval('P'.intval($nbre_fois).'D'));
		$date1=$date->format('Y-m-d');
		$date_fin=$date1." ".$heure;

	}else{
		//Revoir cette partie pour ajouter certaines choses
	}

	$tab=array(
		'encounter_nr'=>$encounter_nr,
		'bestellnum'=>$bestellnum,
		'repere'=>$repere,
		'quantite'=>$quantite,
		'posologie'=>$posologie,
		'commentaire'=>addslashes($commentaire),
		'duree'=>$duree,
		'unite_duree'=>$unite_duree,
		'debut_traitement'=>$date_debut,
		'fin_traitement'=>$date_fin,
		//'fin_traitement_effectif'=>$date_fin,
		);
	//var_dump($tab);//die();
	if(isset($id_prescription) && !empty($id_prescription)){
		$tab['modify_id']=$userPID;
		$tab['modify_time']=$Time1;
		$tab['history']='concat(history,";"," Modifier par '.$userPID.' le '.$Time.'")';

		//var_dump("update");
	}else{
		$tab['create_id']=$userPID;
		$tab['create_time']=$Time1;
		$tab['history']="Créer par ".$userPID." le ".$Time;
		//var_dump("creation");
	}
/*	echo '<pre>';
	var_dump($tab);
	var_dump($objMedocsParams->insertFromArray('care_encounter_internal_prescription',$tab, 'id_prescription',@$id_prescription));
	echo '</pre>';
	die();*/
	if($objMedocsParams->insertFromArray('care_encounter_internal_prescription',$tab, 'id_prescription',@$id_prescription)){
		$msgType="success";
		$msg="Sauvegarde effectuée avec succès";
	}else{
		$msgType="danger";
		$msg="Sauvegarde non effectuée";
	}

	#redirect to the page
	/*unset($_POST);
	header("Location:".$baseLink."&target=intPrescription");*/

}

/***** Affichage des valeurs de l'admissiion *****/
$sql="SELECT p.artikelname as designation,  i.* FROM care_encounter_internal_prescription i,  care_pharma_products_main p WHERE p.bestellnum=i.bestellnum AND i.valide_status='O' AND i.encounter_nr=".$encounter_nr." ORDER BY i.create_time DESC";

if($result=$db->execute($sql)){
	if(is_object($result)){
		ob_start();
		$i=0;
		while ($ligne=$result->FetchRow()) {
			$i++;
			$actions="";
			/*$actions='<a href="'.$baseLink.'&target=intPrescription&id_prescription='.$ligne['id_prescription'].'&mode=update" class="btn btn-sm btn-success" style="color:#fff" title="Editer">
	        	<span class="glyphicon glyphicon-edit"></span>
	        </a>';*/
	       // if(date('Y-m-d H:i:s')>=date($ligne['debut_traitement']) && $ligne['valide_status']=='O' && $ligne['fin_traitement_effectif']!='0000-00-00 00:00:00'){ A revoir
	        if(date('Y-m-d H:i:s')>=date($ligne['debut_traitement']) && $ligne['valide_status']=='O'){
	        	$actions.='&nbsp;
		        <a href="'.$baseLink.'&target=intPrescription&id_prescription='.$ligne['id_prescription'].'&mode=arreter" class="btn btn-sm btn-primary" style="color:#fff" title="Arreter le traitement" onclick="return confirm(\'Le traitement est-il terminé?\')">
		        	<span class="glyphicon glyphicon-ok"></span>
		        </a>';
	        }
	        if($ligne['fin_traitement_effectif']=='0000-00-00 00:00:00'){
	        	$actions.='&nbsp;
			        <a href="'.$baseLink.'&target=intPrescription&id_prescription='.$ligne['id_prescription'].'&mode=delete" class="btn btn-sm btn-danger" style="color:#fff" title="Supprimer" onclick="return confirm(\'Etes vous sur?\')">
			        	<span class="glyphicon glyphicon-trash"></span>
			        </a>';
	        }
			echo '<tr>';
				echo '<td class="text-right">'.$i.'</td>';
				echo '<td>'.$ligne['designation'].'</td>';
				echo '<td class="text-right">'.$ligne['quantite'].'</td>';
				echo '<td>'.$ligne['posologie'].'</td>';
				echo '<td>'.$ligne['unite_duree'].'</td>';
				echo '<td class="text-center">'.$actions.'</td>';
			echo '</tr>';
		}
		$listing=ob_get_contents();
		ob_end_clean();
		$smarty->assign('listingOrdonnances', $listing);
	}
}

/******* Message de confirmation ********/
if(isset($msg) && !empty($msg)){
	$msgArray=array('type'=>$msgType, 'msg'=>$msg);
	$smarty->assign('msgArray', $msgArray);
}


$smarty->assign('sMainBlockIncludeFile','medocs/medocs_int_prescription.tpl');
?>