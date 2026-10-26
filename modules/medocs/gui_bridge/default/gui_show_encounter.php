<?php
ob_start();
?>
<style type="text/css">
	.table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
	    padding: -15px;
	    line-height: 1.42857;
	    vertical-align: top;
	    border-top: 1px solid #DDD;
	}
</style>

<script type="text/javascript">
	$(document).ready(function(){
		$(".date").datetimepicker({
			format:'dd/mm/yyyy',
			autoclose:true,
			minView:2
		});

		$("#idCloturer").click(function(){
			if(confirm("Etes vous sur de cloturer cette admission?")){
				var nr=$("#idEncounterDischarge").val();
				
				$.post("<?php echo $root_path.'modules/ajax/ajaxMedocsMedocs.php';?>", {'target':'admission', 'action':'cloture', 'encounter_nr':nr}, function(data){
					alert(data);
					window.location.reload();
				})
			}
		});
	});

	function checkClassNr(nr){
		if(nr==""){
			var selected=$("input[name=encounter_class_nr]:checked").length;
			//alert(selected);
			if(selected==0){
				alert("Veuillez selectionner une issue de consultation");
				return false;
			}
			else return true;
		}
		//return false;
	}
</script>
<?php
$js=ob_get_contents();
ob_end_clean();

$smarty->append("JavaScript", $js);

/************************************
*****Pour la décharge du patient*****
*************************************/
if(isset($option)&&$option=='discharge'){
	if($objEncounter->setIsDischarged($encounter_nr,date('Y-m-d'),date('H:i:s'))){
		$msg='Le dossier a été cloturer avec succès';
		$msgType="success";
		header("Location:".$baseLink);
	}else{
		$msg="Le dossier n'a pas été cloturer! veuillez revoir ce qui va pas ou contacter le service informatique";
		$msgType="danger";
	}
	$msgArray=array('type'=>$msgType, 'msg'=>$msg);
    $smarty->assign('msgArray', $msgArray);
}

/***********************************
************BREADCRUMBB*************
***********************************/

if($encounter_nr=$objEncounter->isPIDCurrentlyAdmitted($pid)){ //Si patient actuellement admis
	$rowIdSpecialite=$objEncounter->getValue("admit_type",$encounter_nr)->FetchRow();
	$idSpecialite=$rowIdSpecialite[0];//Recuperation de l'id Specialite
	$nameSpecialite=$objMedocsParams->loadSpecialite($idSpecialite)['designation'];//Nom specialite
	$breadcrumb=array("DOSSIER MEDICAL","SPECIALITE :: <b>".mb_strtoupper($nameSpecialite)."</b>", mb_strtoupper("DONNEES D'ADMISSION"));//BreadCrumb spécialisé
	if(!isset($action)) $action="show"; //Affichage de données
}
else{
	$nameSpecialite=$objMedocsParams->loadSpecialite($idSpecialite)['designation'];
	$breadcrumb=array("DOSSIER MEDICAL","SPECIALITE :: <b>".mb_strtoupper($nameSpecialite)."</b>", mb_strtoupper("Nouvelle Consultation"));
}

$smarty->assign("breadcrumbArray", $breadcrumb); //Affichage dans le template

/*************************************************************************
*****RECUPERATION DES PARAMETRES/CONSTANTES RELATIVE A LA SPECIALITE******
*************************************************************************/
//Constantes
$listeConstante=$objMedocsParams->loadDetailSpecialiteConstantes($idSpecialite);
//Autres champs
$sqlParametresSpecialites="SELECT s.id as idSpecialite, sp.parametre_id, sp.position, p.designation, p.typeInput, p.nameValue, p.defaultValue, p.placeholder, p.value FROM care_medocs_specialites s, care_medocs_specialites_parametres sp, care_medocs_parametres p WHERE s.valide_status='O' AND sp.valide_status='O' AND p.valide='O' AND s.id=sp.specialite_id AND p.id=sp.parametre_id AND s.id=".$idSpecialite." ORDER BY sp.position ASC";
$parametresSpecialites=$db->Execute($sqlParametresSpecialites);


/**************************************************************************
*************TRAITEMENT DU FORMULAIRE D'ENREGISTREMENT*********************
**************************************************************************/
if(isset($register)){
	//Debut de transaction
	$db->Execute("SET AUTOCOMMIT=false");
	$state=true; //Statue de la transaction
	$admitted=false;  //Patient admis ou pas
	$class_nr=$encounter_class_nr;
	if(!isset($encounter_nr) || empty($encounter_nr)){
		$encounter_nr=$objEncounter->getNewEncounterNr(0,$class_nr);
		$admitted=true;
	}
	//Detail de l'admission
	//Traitement des droits
	if(isset($idSpecialite) && $idSpecialite==13){
		$access="prive";
		$autorisation=$create_id;
	}
	else {
		$access="publique";
		$autorisation="";
	}
	$admissionData=array(
		"pid"=>$pid,
		"encounter_date"=>date('Y-m-d H:i:s'),
		"encounter_class_nr"=>$class_nr,
		"encounter_nr"=>$encounter_nr,
		"patient_type"=>"PATIENT",
		"admit_type"=>$idSpecialite,
		"history"=>"Create ".$useName." ".date('Y-m-d H:i:s'),
		"access"=>$access,
		"autorisations"=>$autorisation
	);
	$pk="";
	$pkValue=$encounter_nr;
	if($admitted){
		$admissionData['create_id']=$create_id;
		$admissionData['create_time']=date('Y-m-d H:i:s');
		$admissionData['modify_id']=$create_id;
		$admissionData['modify_time']=date('Y-m-d H:i:s');
	}
	else{ //Si patient admis on fait la MAJ en se basant sur la colonne $pk et avec valeur $pkValue
		$pk="encounter_nr";
		$pkValue=$encounter_nr;
		$admissionData['modify_id']=$create_id;
		$admissionData['modify_time']=date('Y-m-d H:i:s');
	}
	if($objEncounter->insertFromArray("care_encounter",$admissionData,$pk,$pkValue)){
		//Enregistrement des Constantes
		//Recuperer le measure nr
		if($ongoingMeasurement=$objMeasurement->getMeasurementByPidEncounterNr($encounter_nr,'O')){
			$currentMeasurement=$ongoingMeasurement[0];
			$measure_nr=$currentMeasurement['nr'];
			$objMeasurement->attributeEncounter($measure_nr,$encounter_nr,'O');
		}
		elseif($waitingMeasurement=$objMeasurement->getMeasurementByPidEncounterNr($pid,'N')){
			$currentMeasurement=$ongoingMeasurement[0];
			$measure_nr=$currentMeasurement['nr'];
			$objMeasurement->attributeEncounter($measure_nr,$encounter_nr,'O');
		}
		else{
			//Enregistrement de la constante
			$constData=array("admit_measurement"=>'O',
				"pid"=>$pid,
				"encounter_nr"=>$encounter_nr,
				"status"=>'O',
				"history"=>"Reg. ".$userName." ".date('Y-m-d H:i:s'),
				"modify_id"=>$create_id,
				"modify_time"=>date('Y-m-d H:i:s'),
				"create_id"=>$create_id,
				"create_time"=>date('Y-m-d H:i:s')
			);
			if($objMeasurement->insertFromArray("care_encounter_measurement",$constData)){
				$measure_nr=$db->Insert_ID();
			}
			else{
				echo $objMeasurement->sql;
				$state=false;
			}
		}

		if($state){
			while($registerConst=$listeConstante->FetchRow()){
				$varValue=$registerConst['LD_var'];
				//var_dump($varValue);
				if(isset($_POST[$varValue])){
					//var_dump($varUnit);
					if($registerConst['LD_var_type']=="LDDate") {
						$measurement_value=parseDate($_POST[$varValue],'D/M/Y','/','','Y-M-D','-');
						$varUnit=0;
						$varUnitType=0;
					}
					else {
						$measurement_value=$_POST[$varValue];
						$varUnit=$registerConst['LD_var']."_unit";
						$varUnitType=$registerConst['LD_var']."_unit_type";
					}
					$const=array(
						"measurement_nr"=>$measure_nr,
						"msr_type_nr"=>$registerConst['nr'],
						"value"=>$measurement_value,
						"unit_nr"=>@$_POST[$varUnit],
						"unit_type_nr"=>@$_POST[$varUnitType],
						"status"=>'O',
						"history"=>"Reg. ".$userName." ".date('Y-m-d H:i:s'),
						"modify_id"=>$create_id,
						"modify_time"=>date('Y-m-d H:i:s'),
						"create_id"=>$create_id,
						"create_time"=>date('Y-m-d H:i:s')
					);
					
					$measurement_sub_pk="";
					$measurement_sub_pkValue="";
					if($measurement_sub=$objMeasurement->getMeasureMeasurementType($measure_nr,$registerConst['nr'])){
						$measurement_sub_pk="nr";
						$measurement_sub_pkValue=$measurement_sub['nr'];
						$cons["modify_id"]=$create_id;
						$cons["modify_time"]=date('Y-m-d H:i:s');
					}
					else{
						$cons["modify_id"]=$create_id;
						$cons["modify_time"]=date('Y-m-d H:i:s');
						$cons["create_id"]=$create_id;
						$cons["create_time"]=date('Y-m-d H:i:s');
					}
					if(!$objMeasurement->insertFromArray("care_measurement_sub",$const,$measurement_sub_pk,$measurement_sub_pkValue)){
						//echo $objMeasurement->sql;
						$state=false;
						break;
					}
					//else echo $objMeasurement->sql;
				}
			}
		}
		
		//var_dump($state);
		$listeConstante->MoveFirst();
		//Parametres
		if($state){
			while($registerParam=$parametresSpecialites->FetchRow()){
				if($registerParam['typeInput']=="liste"){
					$paramNameValue=implode("#",$$registerParam['nameValue']);
				} 
				elseif($registerParam['typeInput']=="date"){
					$paramNameValue=parseDate($$registerParam['nameValue'],'D/M/Y','/','','Y-M-D','-');
				} 
				else $paramNameValue=$$registerParam['nameValue'];
				$champs=array(
					'encounter_nr'=>$encounter_nr,
					'spec_param_id'=>$registerParam['parametre_id'],
					'value'=>addslashes($paramNameValue),
					"history"=>"Reg. ".$userName." ".date('Y-m-d H:i:s'),
					"modify_time"=>date('Y-m-d H:i:s'),
					"create_id"=>$create_id,
					"create_time"=>date('Y-m-d H:i:s')
				);
				
				$encounter_parameter_pk="";
				$encounter_parameter_pkValue="";
				if($encounter_parameters=$objEncounter->getEncounterSavedParameter($encounter_nr,$registerParam['parametre_id'])){
					$encounter_parameter_pk="id";
					$encounter_parameter_pkValue=$encounter_parameters['id'];
				}
				if(!$objEncounter->insertFromArray("care_medocs_encounter_parametres",$champs,$encounter_parameter_pk,$encounter_parameter_pkValue)){
					echo $objEncounter->sql;
					$state=false;
					break;
				}
				//echo $objEncounter->sql;

			}
			$parametresSpecialites->MoveFirst(); //Réinitialiser le pointeur du tableau
		}
	}
	else {
		$state=false;
	}
	if($state) {
		$db->Execute("COMMIT");

		//***************Mettre à jour le numéro d'admission dans la table appointment
		if($encounter_class_nr==2){
			$sqlSelectAppointment="SELECT pid FROM care_encounter_appointment WHERE to_personell_nr=".$userPID." AND pid=".$pid." AND date='".date('Y-m-d')."' AND status='O'";
			if($resultSelect=$db->Execute($sqlSelectAppointment)){
				$rsSelect=$resultSelect->FetchRow();
				if($rsSelect['pid']>0){
					$updateAppointment="UPDATE care_encounter_appointment SET encounter_nr=".$encounter_nr.", encounter_class_nr=2 WHERE to_personell_nr=".$userPID." AND pid=".$pid." AND date='".date('Y-m-d')."' AND status='O'";
					if($db->Execute($updateAppointment)){
						//Je sais plus quoi faire pour le moment Merci
					}
				}
			}
		}
		//***************Fin de la mise à jour
		
		$action="show";
	}
	else $db->Execute("ROLLBACK");
	$db->Execute("SET AUTOCOMMIT=true");
}

/*************************************************************
********************** FORMULAIRE D'ENREGISTREMENT************
*************************************************************/
/** Gestion des buttons a afficher et  **/
$smarty->assign("create_id",$userPID);
if(isset($encounter_nr)){
	if(!isset($action) || ($action=="edit")) $action="form";
	if($action=="show") {
		$frmAction=false;
		$modifyLink=$root_path."modules/medocs/medocs.php".URL_APPEND."&target=".$target."&pid=".$pid."&encounter_nr=".$encounter_nr."&idSpecialite=".$idSpecialite."&action=edit";
		$smarty->assign('modify',true);
		$smarty->assign('modifyLink',$modifyLink);

		$dischargeHref=$baseLink."&target=".$target."&encounter_nr=".$encounter_nr."&idSpecialite=".$idSpecialite."&option=discharge";
		$smarty->assign('dischargeHref',$dischargeHref);
		#Pour griser le boutton cloturer si c'est une admission d'hospitalisation
		if(intval($objEncounter->getValue('encounter_class_nr', $encounter_nr)->FetchRow()[0])==1){
			$smarty->assign('readonly', true);
		}
		#Fin du grisement (ne rie pas ceci fait parti de l'accademie 2018!!!!)
		$printLink=$root_path."modules/medocs/print_rapport_consultation.php".URL_APPEND."&pid=".$pid."&encounter_nr=".$encounter_nr."&idSpecialite=".$idSpecialite;
		$smarty->assign('printLink',$printLink);

		$smarty->assign('idEncounterDischarge', $encounter_nr);
	}
	else {
		$frmAction=$root_path."modules/medocs/medocs.php".URL_APPEND."&target=".$target."&pid=".$pid."&idSpecialite=".$idSpecialite;
		$smarty->assign('register',true);
		$smarty->assign('encounter_nr',$encounter_nr);
	}
}
else{
	$frmAction=$root_path."medocs/medocs.php".URL_APPEND."&target=".$target."&pid=".$pid."&idSpecialite=".$idSpecialite;
	$smarty->assign('register',true);
}
$smarty->assign("frmAction",$frmAction);

#Chargement des parametres

if($action=="form" || $action=="edit"){
	if(isset($encounter_nr) && !empty($encounter_nr)){
		$encounter_class_nr=$objEncounter->getValue("encounter_class_nr",$encounter_nr)->FetchRow()[0];
		//var_dump($encounter_class_nr);
		//Recuperation et traitement des données Parametres
		if($encounterParameters=$objEncounter->getEncounterParameters($encounter_nr)){
			while($encParam=$encounterParameters->FetchRow()){
				$$encParam['nameValue']=$encParam['value'];
			}
		}
		//Recuperation et traitement des données constantes
		if($encounterConstantes=$objMeasurement->getMeasurementByPidEncounterNr($encounter_nr,'O')){
			$encounterConstantesNr=$encounterConstantes[0]['nr'];
			if($encounterConstantesDetail=$objMeasurement->getMeasuresByNr($encounterConstantesNr)){
				foreach($encounterConstantesDetail as $showConst){
					//var_dump($showConst);echo '<br><br>';
					$varConstanteVal=$showConst['LD_var']."_val";
					$$varConstanteVal=$showConst['value'];
					if($showConst['LD_var_type']=="LDDate") $$varConstanteVal=parseDate($$varConstanteVal,'Y-M-D','-','','D/M/Y','/');
					$varUnit=$showConst['LD_var']."_unit";
					$$varUnit=$showConst['unite'];
				}
			}
		}
	}
	else{
		//Recuperation et traitement des données Parametres
		//var_dump($pid);
		if($waitingConstantes=$objMeasurement->getMeasurementByPidEncounterNr($pid,'N')){
			$waitingConstante=$waitingConstantes[0]['nr'];
			if($encounterConstantes=$objMeasurement->getMeasuresByNr($waitingConstante)){
				foreach($encounterConstantes as $showConst){
					$varConstanteVal=$showConst['LD_var']."_val";
					$$varConstanteVal=$showConst['value'];
					$varUnit=$showConst['LD_var']."_unit";
					$$varUnit=$showConst['unite'];
				}
			}
		}
	}
	//Affichage de la partie parametres
	ob_start();

	if($parametresSpecialites){
		echo "<div class='row'>";
		while($sp=$parametresSpecialites->FetchRow()){
			if($sp['typeInput']=="input" ||$sp['typeInput']=="date") echo "<div class='col-sm-6'>";
			elseif($sp['typeInput']=="select") echo "<div class='col-sm-6'>";
			else echo "<div class='col-sm-12'>";

			echo '<div class="form-group">';

			if($sp['typeInput']=="textarea"){ 
				echo '<label class="control-label">'.$sp['designation'].'</label>';
				echo '<textarea class="form-control input-sm" id="'.$sp['nameValue'].'" name="'.$sp['nameValue'].'" rows="2">'.@$$sp['nameValue'].'</textarea>';
			}
			else{
				echo '<label class="control-label col-sm-3" style="text-align:left;">'.$sp['designation'].'</label>';
				echo '<div class="col-sm-9">';
				if($sp['typeInput']=="select"){
					$listeValue=$sp['value'];
					//var_dump($listeValue);
					$elements=explode(';',$listeValue);
					echo '<select name="'.$sp['nameValue'].'" id="'.$sp['nameValue'].'" class="form-control input-sm">';
						echo '<option value="">'.$sp['placeholder'].'</option>';
					foreach($elements as $element){
						$tuple=explode(':',$element);
						echo '<option value="'.$tuple[1].'">'.$tuple[0].'</option>';
					}
					echo '</select>';
				}
				elseif($sp['typeInput']=="liste"){
					$listeValue=$sp['value'];
					$formParamValue=explode("#","#".$$sp['nameValue']);
					//var_dump($formParamValue);
					//var_dump($listeValue);
					$elements=explode(';',$listeValue);
					foreach($elements as $element){
						$tuple=explode(':',$element);
						echo '<label class="">'.$tuple[1].'</label> <input type="checkbox" name="'.$sp['nameValue'].'[]" id="'.$tuple[0].'" value="'.$tuple[0].'"';
							if(array_search($tuple[0],$formParamValue)) echo 'checked="checked"';
						echo '> &nbsp;';
					}
				}
				elseif($sp['typeInput']=="date"){
					//var_dump($$sp['nameValue']);
					if(isset($$sp['nameValue'])) $formParamValue=parseDate($$sp['nameValue'],'Y-M-D','-','','D/M/Y','/');
						else $formParamValue="";
					echo '<div class="input-group date"><input type="text" class="form-control input-sm" name="'.$sp['nameValue'].'" id="'.$sp['nameValue'].'" value="'.$formParamValue.'"><div class="input-group-addon"><i class="glyphicon glyphicon-th"></i></div></div>';
				}
				else{
					echo '<input class="form-control input-sm" type="text" name="'.$sp['nameValue'].'" placeholder="'.$sp['placeholder'].'" value="'.@$$sp['nameValue'].'">';
				}
				echo '</div>';
				
			}
			echo '</div>';
		echo "</div>";
		
		}
		echo '<div class="col-sm-12"><label class="control-label">Issue de consultation</label><div>';
		//var_dump();
		if($encounter_nr){
			if($encounter_class_nr=="1") echo 'Patient Hospitalisé';
			else echo 'Patient Externe';
			echo '<input type="hidden" name="encounter_class_nr" id="encounter_class_nr" value="'.$encounter_class_nr.'">';
		}
		else echo '<label>Patient externe</label>&nbsp;<input type="radio" name="encounter_class_nr" id="interne" value="2"><label>&nbsp;Patient interne(hospitalisé)</label>&nbsp;<input type="radio" name="encounter_class_nr" id="externe" value="1">';
		echo '</div></div>';
		echo "</div>";
	}

	$obParametresSpecialites=ob_get_contents();
	ob_end_clean();
	$smarty->assign('parametresSpecialites',$obParametresSpecialites);

	#chargement des Constantes
	ob_start();

	if(is_object($listeConstante)){
		echo '<br>';
		echo '<table class="table table-bordered">';
		$constante=$listeConstante->getArray();
		//var_dump($$constante[0]["LD_var"]);die();
		$cpteConstante=count($constante);
		$nbConstante=ceil($cpteConstante/2);
		//var_dump($nbConstante);die();
		for($i=0;$i<$nbConstante;$i++){
			$constVal=$constante[$i]['LD_var']."_val";
			$unite_mesure=$objMedocsParams->listeUnitDefaultByType($constante[$i]['measurement_type']);
			
			echo '<tr>';
				echo '<td width="20%"><label class="control-label">'.$$constante[$i]['LD_var'].'</label></td>';
			if($constante[$i]['LD_var_type']=="LDDate"){
				?>
				<td colspan="2">
					<div class="input-group date">
						<input type="text" class="form-control" name="<?php echo $constante[$i]['LD_var']; ?>" id="<?php echo $constante[$i]['LD_var']; ?>">
						<div class="input-group-addon">
						<i class="glyphicon glyphicon-th"></i>
						</div>
					</div>
				</td>
				<?php
			}
			else{
				echo '<td width="15%"><input type="text" name="'.$constante[$i]['LD_var'].'" class="form-control input-sm" value="'.@$$constVal.'"></td>';
				echo '<td width="">';
				echo '<input type="hidden" name="'.$constante[$i]['LD_var'].'_unit_type" id="'.$constante[$i]['LD_var'].'_unit_type" value="'.$constante[$i]['measurement_type'].'">';

				$cptUnite=count($unite_mesure);//Pour eviter d'ecrire selected=selected
				//var_dump($constante[$i]['LD_var']."_unit");
				echo '<select class="form-control input-sm" name="'.$constante[$i]['LD_var'].'_unit">';
					
				if(is_object($unite_mesure)){
					while($unite=$unite_mesure->FetchRow()){
						$selectedUnitVar=$constante[$i]['LD_var']."_unit";
						
						if(isset($$selectedUnitVar) && !empty($$selectedUnitVar)){
							if($selectedUnitVar==$unite['nr']) $selected='selected="selected"';
						}
						else{
							if($cptUnite>=1){
								$selected=($unite['nr']==$constante[$i]['unit_default'])?'selected="selected"':"";
							}else $selected="";
						}
						$selected=($unite['nr']==$constante[$i]['unit_default'])?'selected="selected"':"";
						echo '<option value="'.$unite['nr'].'" '.$selected.'>'.$unite['id'].'</option>';
					}
				}
				echo '</select>';
				echo '</td>';
			}
			
			if(isset($constante[$nbConstante+$i]['LD_var'])){

				$constVal=$constante[$nbConstante+$i]['LD_var']."_val";
				$unite_mesure=$objMedocsParams->listeUnitDefaultByType($constante[$nbConstante+$i]['measurement_type']);
				
					echo '<td width="20%"><label class="control-label">'.$$constante[$nbConstante+$i]['LD_var'].'</label></td>';
					if($constante[$nbConstante+$i]['LD_var_type']=="LDDate"){
					?>
						<td colspan="2">
							<div class="input-group date">
								<input type="text" class="form-control" name="<?php echo $constante[$nbConstante+$i]['LD_var']; ?>" id="<?php echo $constante[$nbConstante+$i]['LD_var']; ?>">
								<div class="input-group-addon">
								<i class="glyphicon glyphicon-th"></i>
								</div>
							</div>
						</td>
						<?php
					}
					else{
					echo '<td width="15%"><input type="text" name="'.$constante[$nbConstante+$i]['LD_var'].'" class="form-control input-sm" value="'.@$$constVal.'"></td>';
					echo '<td width="15%">';
					echo '<input type="hidden" name="'.$constante[$nbConstante+$i]['LD_var'].'_unit_type" id="'.$constante[$nbConstante+$i]['LD_var'].'_unit_type" value="'.$constante[$nbConstante+$i]['measurement_type'].'">';
					echo '<select class="form-control input-sm" name="'.$constante[$nbConstante+$i]['LD_var'].'_unit">';
						$cptUnite=count($unite_mesure);//Pour eviter d'ecrire selected=selected
						if(is_object($unite_mesure)){
							while($unite=$unite_mesure->FetchRow()){
								/*$selectedUnitVar=$constante[$nbConstante+$i]['LD_var']."_unit";
								if(isset($$selectedUnitVar) && !empty($$selectedUnitVar)){
									if($$selectedUnitVar==$unite['nr']) $selected='selected="selected"';
								}
								else{
									if($cptUnite>1){
										$selected=($unite['nr']==$constante[$nbConstante+$i]['unit_default'])?'selected="selected"':"";
									}else $selected="";
								}*/

								$selected=($unite['nr']==$constante[$nbConstante+$i]['unit_default'])?'selected="selected"':"";
								echo '<option value="'.$unite['nr'].'" '.$selected.'>'.$unite['id'].'</option>';
							}
						}
					echo '</select>';
					echo '</td>';
				}
			}else{

	            echo '<td align="center">-</td>';
	            echo '<td align="center">-</td>';
	            echo '<td align="center">-</td>';
    
			}

			echo '</tr>';
		}
		echo '</table>';	
	}
	$listingConstante=ob_get_contents();
	ob_end_clean();
	$smarty->assign("listingConstante", $listingConstante);
}

/***************************************
*********AFFICHAGE DES DONNEES**********
****************************************/

if($action=="show"){
	$encounter_class_nr=$objEncounter->getValue("encounter_class_nr",$encounter_nr)->FetchRow()[0];
	var_dump($encounter_class_nr);
	//Parametres
	if($encounterParameters=$objEncounter->getEncounterParameters($encounter_nr)){
		while($encParam=$encounterParameters->FetchRow()){
			$$encParam['nameValue']=$encParam['value'];
		}
	}
	ob_start();
	echo '<table class="table table-responsive table-bordered">';
	while($showParam=$parametresSpecialites->FetchRow()){
		$paramNameValue="";
		if($showParam['typeInput']=="date"){
			$paramNameValue=parseDate($$showParam['nameValue'],'Y-M-D','-','','D/M/Y','/');
		}
		elseif($showParam['typeInput']=="liste"){
			$keys=explode("#",$$showParam['nameValue']);
			$tuple=explode(';',$showParam['value']);
			foreach($tuple as $listItem){
				$itemTab=explode(":",$listItem);
				$tuples[$itemTab[0]]=$itemTab[1];
			}
			foreach($keys as $key=>$val){
				if(empty($paramNameValue)) $paramNameValue.=$tuples[$val];
				else $paramNameValue.=" ;".$tuples[$val];
			}
		}
		else $paramNameValue=$$showParam['nameValue'];
		echo '<tr>';
			echo '<td width="25%"><label class="">'.$showParam['designation'].'</label></td>';
			echo '<td style="width:70%;vertical-align: text-justify;">'.$paramNameValue.'</td>';
		echo '</tr>';
	}
	echo '<tr><td>Issue de consultation</td><td>';
		if($encounter_class_nr=="1") echo 'Patient Hospitalisé';
		else echo 'Patient Externe';
	echo '</td></tr>';
	echo '</table>';
	$obParametresSpecialites=ob_get_contents();
	ob_end_clean();
	$smarty->assign('parametresSpecialites',$obParametresSpecialites);

	//Constantes
	if($encounterConstantes=$objMeasurement->getMeasurementByPidEncounterNr($encounter_nr,'O')){
		$encounterConstantesNr=$encounterConstantes[0]['nr'];
		if($encounterConstantesDetail=$objMeasurement->getMeasuresByNr($encounterConstantesNr)){
			foreach($encounterConstantesDetail as $showConst){
				//var_dump($showConst);echo '<br><br>';
				$varConstanteVal=$showConst['LD_var']."_val";
				$$varConstanteVal=$showConst['value'];
				if($showConst['LD_var_type']=="LDDate") $$varConstanteVal=parseDate($$varConstanteVal,'Y-M-D','-','','D/M/Y','/');
				$varUnit=$showConst['LD_var']."_unit";
				$$varUnit=$showConst['unite'];
			}
			ob_start();
			if(is_object($listeConstante)){
				$listeConstante=$listeConstante->getArray();
				$nbConstante=count($listeConstante)/2;
				echo '<table class="table table-bordered">';
				for($i=0;$i<$nbConstante;$i++){
					echo '<tr>';
							$varUnit=$listeConstante[$i]['LD_var']."_unit";
							$varConstanteVal=$listeConstante[$i]['LD_var']."_val";
						    echo '<td width="30%"><label>'.$$listeConstante[$i]['LD_var'].'</label></td>';
							echo '<td width="10%">'.$$varConstanteVal.'</td>';
							echo '<td width="10%">'.$$varUnit.'</td>';

							if($listeConstante[$nbConstante+$i]['LD_var']."_unit"!=''){
								$varUnit=$listeConstante[$nbConstante+$i]['LD_var']."_unit";
								$varConstanteVal=$listeConstante[$nbConstante+$i]['LD_var']."_val";
							    echo '<td width="30%"><label>'.$$listeConstante[$nbConstante+$i]['LD_var'].'</label></td>';
								echo '<td width="10%">'.$$varConstanteVal.'</td>';
								echo '<td width="10%">'.$$varUnit.'</td>';
							}

					echo '</tr>';
				}
				echo '</table>';
			}
			$listingConstante=ob_get_contents();
			ob_end_clean();
			$smarty->assign("listingConstante", $listingConstante);
		}
	}
}


$smarty->assign('sMainBlockIncludeFile','medocs/medocs_encounter.tpl');

?>


