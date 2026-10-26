<?php
/**Pour le fichier de langue
Je l'ai mis ici pour le moment puisque 
je ne coprends plus rien de tout ça**/
/*$LDITA='Anesthésie intratrachéale';
$LDLA='Anesthésie locale';
$LDAS='Sédation analgésique';
$LDOral='Orale';
$LDAnticoagulant='Anticoagulant';
$LDHemolytic='hémolytique';
$LDDiuretic='Diurétique';
$LDAntibiotic='Antibiotique';
$LDMask='Masque';
$LDIntravenous='Intraveineuse';
$LDIntraVaginal = 'Intra-vaginale';
$LDIntraRectal = 'Intra-rectale';
$LDIntraArterial= 'Intra-artérielle';
$LDSubcutaneous='Sous-cutanée';
$LDPreAdmission='Pré-admission';*/
/**fin **/
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
		$("#qte").val("");
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

    $(function(){

    	$("#medicament").autocomplete({
			source:"<?php echo $root_path.'modules/ajax/ajaxMedocsMedocs.php?target=ordonnance&action=autocomplete';?>",
			select:function(event,ui){
				//alert(ui.item.designation);
				//$("#medicament").val(ui.item.label);
				$("#article").val(ui.item.id);
				$("#qte").focus();
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

       


    
    }); 
</script>
<?php
$js=ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript', $js);

#Breadcrumb
$breadcrumb=array("DOSSIER MEDICAL","ORDONNANCES EXTERNES");
$smarty->assign("breadcrumbArray", $breadcrumb);

/*=======================Formulaire=======================*/
if(isset($_GET['prescription_nr'])){
	$prescription_nr=$_GET['prescription_nr'];
	//var_dump($prescription_nr);die();
}
#Assignation du mode
if(isset($prescription_nr)) {
	$mode="update"; 
	//creation de la chaine
	$chaine=$objPrescription->recup_chaine($prescription_nr);
}else $mode="create";

$encounter_nr=$objEncounter->isPIDCurrentlyAdmitted($pid);//Patient admis

$smarty->assign("mode", $mode);
$smarty->assign('encounterNr', $encounter_nr);
$smarty->assign('prescriber', $_SESSION['sess_user_name']);
$smarty->assign('prescriberId', $_SESSION['sess_user_login_id']);

ob_start();
	$app_types=$objPrescription->getAppTypes();
	$options='';
	while(list($x,$v)=each($app_types)){
		$options.='<option value="'.$v['nr'].'">';
		if(isset($$v['LD_var'])&&!empty($$v['LD_var'])) $options.= $$v['LD_var'];
		else $options.= $v['name'];
		$options.= '</option>';
	}
	echo $options;
	$selectOptions=ob_get_contents();
ob_end_clean();

$smarty->assign("selectOptions", $selectOptions);

ob_start();

if((isset($prescription_nr)) && ($prescription_nr!=0)){
	echo "<input type='hidden' name='prescription_nr' id='prescription_nr' value='".$prescription_nr."'>";
	//Recuperation des produits de l'ordonnance
	$presProduct=$objPrescription->getPrescription($prescription_nr);
	$app_types=$objPrescription->getAppTypes();

	if(is_object($presProduct)){
		$pos=1;
		while($ligne=$presProduct->FetchRow()){
			echo '<br>';
			$options='';
			while(list($x,$v)=each($app_types)){
				$options.='<option value="'.$v['nr'].'"';
				if($ligne['application_type_nr']==$v['nr']) 
					$options.='selected="selected"';
				$options.='>';
				if(isset($$v['LD_var'])&&!empty($$v['LD_var'])) $options.= $$v['LD_var'];
				else $options.= $v['name'];
				$options.= '</option>';
			}
			reset($app_types);
			?>
			<tr>
				<td>
                	<input type="hidden" name="nr[]" id="nr_<?php echo $pos; ?>" value="<?php echo $ligne['nr']; ?>" />
					<input type="text" name="nom[]" value="<?php echo $ligne['article']; ?>" id="nom_<?PHP echo $pos; ?>" class="form-control input-sm" readonly>
					<input type="hidden" name="article[]" id="article_<?PHP echo $pos; ?>" value="<?php echo $ligne['bestellnum'];?>" /> 
				</td>
				<td class="text-right">
					<input type="text" name="quantite[]" id="quantite_<?PHP echo $pos; ?>" size="10" value="<?php echo $ligne['quantity'];?>" class="form-control input-sm text-right">
				</td>
				<td>
					<select name="application_type_nr[]" id="application_type_nr_<?PHP echo $pos; ?>" value="<?php echo $ligne['application_type_nr'];?>" class="form-control input-sm">
						<option value=""></option>
						<?php
							echo $options;
						?>
					</select>
				</td>
				<td>
					<input type="text" name="posologie[]" id="posologie_<?PHP echo $pos; ?>"  value="<?php echo $ligne['posologie'];?>" class="form-control input-sm">
				</td>    
				<td>
					<button onclick='dropRow(this); return deletePrescriptionSub(<?php echo $ligne['nr'];?>);' class='btn btn-sm btn-danger'><i class='glyphicon glyphicon-trash'></i></button>
				</td>
			</tr>
			<?php
			$pos++;
		}
	}
}

$elementsPrescription=ob_get_contents();
ob_end_clean();

$smarty->assign("elementsPrescription", $elementsPrescription);
#Affichage des Ordonnances
$sql="SELECT s.* FROM ((SELECT pr.* 
		FROM care_encounter_prescription AS pr, 
		  	   care_encounter_prescription_sub prs,
		  	   care_encounter e
		WHERE pr.nr = prs.prescription_nr  
		AND e.encounter_nr=pr.encounter_nr
		AND e.pid=".$pid." AND pr.status='')
		UNION
        (SELECT pr.* FROM care_encounter_prescription AS pr, 
		  	   care_encounter_prescription_sub prs
		WHERE pr.nr = prs.prescription_nr AND pr.status='' 
		AND pr.encounter_nr=".$pid.")) as s ORDER BY s.prescribe_date DESC";

$listeOrdonnances=$db->Execute($sql);
$app_types=$objPrescription->getAppTypes();
$pres_types=$objPrescription->getPrescriptionTypes();

$compte=1;
if(is_object($listeOrdonnances)){
	ob_start();
	while($ligne=$listeOrdonnances->FetchRow()){
	?>
	  <tr>
	  	<td align="right"><?php echo $compte; ?></td>
	    <td align="center"><?php echo $ligne['prescribe_code']; ?></td>
	    <td align="center"><?php echo parseDate($ligne['prescribe_date'],"Y-M-D", "-", "", "D/M/Y","/"); ?></td>
	    <td>
			<?php 
				$prescriber=$objPerson->getValue('name_last',$ligne['create_id']).' '.$objPerson->getValue('name_first',$ligne['create_id']);
				echo $prescriber; 
			?>	
		</td>
	    <td align="center">
	    	<span class="btn btn-sm btn-default glyphicon glyphicon-th" title="Détail" data-toggle="modal" data-target="#modalForm" onclick="afficherDetail(<?php echo $ligne['nr'];?>)"></span>

	        <a href="<?php echo $baseLink.'&target=extPrescription&prescription_nr='.$ligne['nr'].'&mode=update';?>" class="btn btn-sm btn-success" style="color:#fff" title="Editer">
	        	<span class="glyphicon glyphicon-edit"></span>
	        </a>
		
	        <a href="javascript:squeezebox('print_prescription.php<?php echo URL_APPEND; ?>&prescription_nr=<?php echo $ligne['nr']; ?>')" class="btn btn-sm btn-primary" style="color:#fff" title="Imprimer">
	    		<span class="glyphicon glyphicon-print"></span>
	    	</a>

	    </td>
	   </tr>
	  <?php
	  	$compte++;
	}
	$listingOrdonnances=ob_get_contents();
	ob_end_clean();
	$smarty->assign("listingOrdonnances", $listingOrdonnances);
}


#Enregistrement des ordonnances dans la base
if(isset($save)){

	if(isset($champs))$chaine=$champs;
	switch($mode){	
		case 'create':

			//prescription 
			$id=$objPerson->getUserPid($_POST['prescriber_id']);
			$prescribe_code=$objPrescription->createPrescriptionCode($_POST['type']);
			//Ceci permet de remplir le champ encounter_nr par le PID du patient s'il est nul
			$num=($_POST['encounter_nr']==0?$pid:$_POST['encounter_nr']);
			
			$prescription = array(
				'encounter_nr' => $num,
				'prescribe_code'=>$prescribe_code,
				'prescribe_date'=>date("Y-m-d"),
				'prescribe_type'=>$_POST['type'],
				'status' => $_POST['status'],
				'prescriber' => $_POST['prescriber'],
				'history' =>$_SESSION['sess_user_name'],
				'modify_id' => $_POST['modify_id'],
				'modify_time' => $_POST['modify_time'],
				'create_id' => $id,
				'create_time' =>$Time
			);

			//var_dump($prescription);die();
			$objPrescription->insertDataFromArray($prescription);
			
			//prescription sub
			//Traitement des champs recupéres
			$objPrescription->usePrescription('prescription_sub');
			$pk = $db->Insert_ID();
			$prescription_nr= $objPrescription->LastInsertPK('nr',$pk);
			//$explode=explode('#',$chaine);
			$tabArray=array(count($nom),count($article),count($quantite),count($application_type_nr),count($posologie));
			$plafond=min($tabArray);
			for($i=0;$i<$plafond;$i++){
				if(empty($nom[$i]) || empty($quantite[$i])) continue;
				$prescription_sub = array(
					'prescription_nr' =>$prescription_nr,
					'article' => $nom[$i],
					'quantity' =>$quantite[$i],
					'application_type_nr' => $application_type_nr[$i],
					'posologie'=>$posologie[$i]
				);
				if(empty($article[$i])){
					$nomProduit=$nom[$i];
					$sql="INSERT INTO care_prescription_indexprod (LibelleMed) VALUES ('".addslashes($nomProduit)."')";
					echo $sql;
					if($db->Execute($sql)){
						$produiId=$db->Insert_ID();
						$prescription_sub['bestellnum']=$produiId;
					}
					else continue;
				}
				else{
					$prescription_sub['bestellnum']=$article[$i];
				}
				$objPrescription->insertDataFromArray($prescription_sub);					
			}
			
		
			header("location:".$baseLink."&target=extPrescription");
			exit;
		
			
			break;
		case 'update': 
			$objPrescription->usePrescription('prescription_sub');
			//echo '<br>';
			$tabArray=array(count($nom),count($article),count($quantite),count($application_type_nr),count($posologie));
			$plafond=min($tabArray);
			
			//echo 'prescription_nr'.$_POST['prescription_nr'];
			for($i=0;$i<$plafond;$i++){
				if(empty($article[$i]) || empty($article[$i]) || empty($quantite[$i]) || empty($posologie[$i]) || empty($quantite[$i])){echo 'error'; continue;}
				$prescription_sub = array(
					'prescription_nr' =>$_POST['prescription_nr'],
					'article' => $nom[$i],
					'quantity' =>$quantite[$i],
					'application_type_nr' => $application_type_nr[$i],
					'posologie'=>$posologie[$i]
				);
				if(empty($article[$i])){
					$nomProduit=$nom[$i];
					$sql="INSERT INTO care_prescription_indexprod (LibelleMed) VALUES ('".addslashes($nomProduit)."')";
					echo $sql;
					if($db->Execute($sql)){
						$produiId=$db->Insert_ID();
						$prescription_sub['bestellnum']=$produiId;
					}
					else continue;
				}
				else{
					$prescription_sub['bestellnum']=$article[$i];
				}
				if(empty($nr[$i])){
					//echo 'insertion';
					$objPrescription->insertDataFromArray($prescription_sub);					
				}
				else {
					//echo 'reussite';
					$objPrescription->where=' nr='.$nr[$i];
					$objPrescription->updatePrescriptionSub($prescription_sub,$nr[$i]);
				}
			}
		
			header("location:".$baseLink."&target=extPrescription");
			exit;

			break;
		}// end of switch
}

$smarty->assign('sMainBlockIncludeFile','medocs/medocs_ext_prescription.tpl');
?>