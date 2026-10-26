<?php 
require_once($root_path.'include/care_api_classes/class_antecedent.php');
$objAntecedent=new Antecedent;

ob_start();
?>
<style type="text/css">
	.panel-heading{
		margin: 0px;
	}
	.panel-title{
		font-size: 12px;
		font-weight: bold;
		margin: -5px;
	} 

	.menuPanelAccueil a{
		font-size: 11px;
		margin:-5px;
	}

	.menuPanelAccueil a:hover{
		color:#337AB7;
	}
</style>
<?php
//Enregistrement des antecedents
if(isset($majAntecedent)){
	$datatab=array(
		"pere"=>$pere,
		"pere"=>$mere,
		"fraterie"=>$fraterie,
		"medicaux"=>$medicaux,
		"chirurgicaux"=>$chirurgicaux,
		"gyneco"=>$gyneco,
		"obstetrique"=>$obstetrique
	);
}

$selectAntecedentPersonnel=array(
	'rougeole'=>"Rougéole", 
	'rubeole'=>"Rubéole",
	'varicelle'=>"Varicelles",
	'hepathiteA'=>"Hépathite A",
	'hepathiteB'=>"Hépathite B",
	'hepathiteC'=>"Hépathite C",
	'taxoplasm'=>"Taxoplasm"
);

$js=ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript', $js);
//Antecedent
#Recuperation des antecedents
$antecedents=$objAntecedent->get_ante_gen($pid);
/*while($rowAntecedent=$antecedents->FetchRow()){
	$smarty->assign("pere",$rowAntecedent['pere']);
	$smarty->assign("mere",$rowAntecedent['mere']);
	$smarty->assign("fraterie",$rowAntecedent['fraterie']);
}*/

#Affichage des antecedent checkbox 
ob_start();
	foreach($selectAntecedentPersonnel as $key=>$element){
		echo '<label for="'.$key.'">'.$element.'&nbsp;<input type="checkbox" name="'.$key.'" id="'.$key.'"></label>';
		echo '&nbsp;';
	}

$autresAntecedentPersonnel=ob_get_clean();
$smarty->assign('autresAntecedentPersonnel',$autresAntecedentPersonnel);

//Historique d'admission
if($listAdmission=$objEncounter->getEncounterList($pid)){

	#Impression de tous les consultations
	ob_start();
		echo '<center>';
			$printLinkAll=$root_path."modules/medocs/print_all_rapport_consultation.php".URL_APPEND."&pid=".$pid;
			echo '<span class="btn btn-default glyphicon glyphicon-plus btn-xs" style="margin-bottom:3px;font-style:arial;" data-toggle="modal" data-target="#myModal"> Aperçu de tout</span>';
			echo "&nbsp;";
			echo '<a href="'.$printLinkAll.'" class="btn btn-xs btn-primary" style="color:#fff;" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimer Toutes les Consultations</a>';
		echo '</center>';
	$printGlobal=ob_get_contents();
	ob_end_clean();
	$smarty->assign('printGlobal', $printGlobal);
	#fin de l'impression global

	ob_start();
	$pos=1;
	while($encounter=$listAdmission->FetchRow()){
		//Entete de l'accordéon
		$encounterDate=parseDate($encounter['encounter_date'],'Y-M-D','-','str','D M Y',' ');
		echo '<a href="#panel'.$encounter['encounter_nr'].'" class="list-group-item" style="background-color: #02327E; color:white; font-weight: bold" data-toggle="collapse" data-parent="#historique">Admission N°: '.$encounter['encounter_nr'].' du '.$encounterDate.' ('.$encounter['specialite'].')</a>';
		//Gestion de la confidentialité
		if($encounter['access']=="prive" && trim($encounter['autorisations'])!=$userPID){

		}
		else{
			//Constantes
			//Parametres d'admission
			if($encounterData=$objEncounter->getEncounterParameters($encounter['encounter_nr'])){
				echo '<div class="collapse';
				if($pos==1) echo ' in ';
				echo '" id="panel'.$encounter['encounter_nr'].'">';
				while($encounterDataRow=$encounterData->FetchRow()){
					echo '<p><b>'.$encounterDataRow['name'].'</b> : '.$encounterDataRow['value'].'</p>';
				}
				if(isset($encounter['create_id']) && !empty($encounter['create_id'])){
					echo '<p><b>Admis Par : </b>';
					if(is_numeric($encounter['create_id'])){
						$nomOp=$objPerson->getValue('name_last',$encounter['create_id']);
						$prenomOp=$objPerson->getValue('name_first',$encounter['create_id']);
						echo $nomOp.' '.$prenomOp;
					}
					else echo $encounter['create_id'];
					echo '</p>';
				}
				#Impression des données d'admission
				echo '<center>';
					$printLink=$root_path."modules/medocs/print_rapport_consultation.php".URL_APPEND."&pid=".$pid."&encounter_nr=".$encounter['encounter_nr']."&idSpecialite=".$encounter['admit_type'];
					echo '<a href="'.$printLink.'" class="btn btn-xs btn-success" style="color:#fff;" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimer</a>';

					# Afficher boutton impression A4 pour Gynécologue
					if($encounter['admit_type']==1){
						$printLink2=$root_path."modules/medocs/print_rapport_consultation.php".URL_APPEND."&pid=".$pid."&encounter_nr=".$encounter['encounter_nr']."&idSpecialite=".$encounter['admit_type']."&format_impression=A4";
						echo '&nbsp;<a href="'.$printLink2.'" class="btn btn-xs btn-primary" style="color:#fff;" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimer A4</a>';
					}

					#Lien de modification
					if($encounter['is_discharged']==0)
					echo '&nbsp;<a href="'.$root_path.'modules/medocs/medocs.php'.URL_APPEND.'&target=newdata&pid='.$encounter['pid'].'&encounter_nr='.$encounter['encounter_nr'].'&idSpecialite='.$encounter['admit_type'].'&action=edit" class="btn btn-xs btn-success" style="color:#fff;"><span class="glyphicon glyphicon-print"></span> Modifier</a>';
				echo '</center><br>';
					
				echo '</div>';
			}
			//Demande d'examens
			//Demande d'analyses
			//Pour les admissions de type hospitalisation
			if($encounter['encounter_class_nr']==1){

				echo '<div class="collapse';
				if($pos==1) echo ' in ';
				echo '" id="panel'.$encounter['encounter_nr'].'">';
				#Saisie du rapport d'hospitalisation
				echo '<br>';
				echo '<center>';
					$linkRapport=$root_path."modules/medocs/medocs.php".URL_APPEND."&target=rapport&pid=".$pid."&encounter_nr=".$encounter['encounter_nr'];
					echo '<a href="'.$linkRapport.'" class="btn btn-xs btn-success" style="color:#fff;" title="Saisie du rapport d\'hospitalisation"><span class="glyphicon glyphicon-edit"></span> Rapport d\'Hospitalisation</a>';
				echo '</center><br>';
					

				echo '</div>';
			}
		}
		
		
		$pos++;
	}

	$encounterDataTpl=ob_get_clean();
	$smarty->assign("historiques",$encounterDataTpl);

	/**************************************************************
	Ce qui suit pour afficher le détail de toutes ses consultations
	**************************************************************/
	ob_start();
	echo '<div style="height:600px; width:100%;overflow:scroll;">';
	    echo '<table style="width:100%;border:0px;margin-left:-5px">';
		        $name_spouse=$objPerson->getValue('name_spouse', $pid);
		    	if(!empty($name_spouse) && $name_spouse!=''){
		    		$name=$objPerson->getValue('title',$pid).' '.$objPerson->getValue('name_last',$pid).' '.$objPerson->getValue('name_first',$pid).' Epouse '.$name_spouse;
		        }else{
		        	$name=$objPerson->getValue('title',$pid).' '.$objPerson->getValue('name_last',$pid).' '.$objPerson->getValue('name_first',$pid);
		        }
		        echo "<tr>
		                <td style='width:25%; text-align:center;background-color:#eee;'>PID</td>
		                <td style='width:25%;text-align:right;'>".$pid."</td>
		                <td style='width:25%;background-color:#eee;text-align:center;'>NOM & PRENOMS</td>
		                <td style='width:25%;text-align:right'>".$name."</td>
		            </tr>";
		echo '</table>

			<hr>';

		if($listAdmission=$objEncounter->getEncounterList($pid)){
			$compteur=0;
			while($encounter=$listAdmission->FetchRow()){	

				$compteur++;
				echo $compteur.")";
				$encounter_date=$encounter['encounter_date'];
				$encounter_nr=$encounter['encounter_nr'];
				$idSpecialite=$encounter['admit_type'];
				
				$listeConstante=$objMedocsParams->loadDetailSpecialiteConstantes($idSpecialite);
				echo '<table style="width:100%;border:0px;margin-left:-5px">';
			            echo "<tr>
			                    <td style='width:25%; text-align:center;background-color:#fff'>N° ADMISSION</td>
			                    <td style='width:25%'>".$encounter['encounter_nr']."</td>
			                    <td style='width:25%;background-color:#eee;text-align:center;'>DATE ADMISSION</td>
			                    <td style='width:25%;text-align:right'>".parseDate($encounter_date, "Y-M-D","-","","D/M/Y","/")."</td>
			                </tr>";
				echo '</table>
				<h4><u><b><i>CONSTANTE</i></b></u></h4>';
				//Constantes
				if($encounterConstantes=$objMeasurement->getMeasurementByPidEncounterNr($encounter_nr,'O')){
					$encounterConstantesNr=$encounterConstantes[0]['nr'];
					if($encounterConstantesDetail=$objMeasurement->getMeasuresByNr($encounterConstantesNr)){
						foreach($encounterConstantesDetail as $showConst){
							//var_dump($showConst);echo '<br><br>';
							$varConstanteVal=$showConst['LD_var']."_val";
							$$varConstanteVal=$showConst['value'];
							$varUnit=$showConst['LD_var']."_unit";
							$$varUnit=$showConst['unite'];
						}

						if(is_object($listeConstante)){

							$listeConstante=$listeConstante->getArray();
							$nbConstante=count($listeConstante)/2;
							echo '<table style="width:100%;border:1px; " cellpadding="0px" cellspacing="0px">';
							for($i=0;$i<$nbConstante;$i++){
								$j=$i+1;
								if($j % 2==0){
									$color='white';
								} 
								else $color='#ddd';
								echo '<tr style="background-color:'.$color.'">';
										$varUnit=$listeConstante[$i]['LD_var']."_unit";
										$varConstanteVal=$listeConstante[$i]['LD_var']."_val";
									    echo '<td style="width:30%;border:0.5px;">'.$$listeConstante[$i]['LD_var'].'</td>';
										echo '<td style="width:20%;border:0.5px;" align="right">'.$$varConstanteVal.' '.$$varUnit.'</td>';

										if($listeConstante[$nbConstante+$i]['LD_var']."_unit"!=''){
											$varUnit=$listeConstante[$nbConstante+$i]['LD_var']."_unit";
											$varConstanteVal=$listeConstante[$nbConstante+$i]['LD_var']."_val";
										    echo '<td style="width:30%;border:0.5px;">'.$$listeConstante[$nbConstante+$i]['LD_var'].'</td>';
											echo '<td style="width:20%;border:0.5px;" align="right">'.$$varConstanteVal.' '.$$varUnit.'</td>';
											
										}

								echo '</tr>';
							}
							echo '</table>';
							
						}
					}
				}

				echo '<h4><u><b><i>DONNEES DE CONSULTATION</i></b></u></h4>';
				//Autres champs
				$sqlParametresSpecialites="SELECT s.id as idSpecialite, p.value, sp.parametre_id, sp.position, p.designation, p.typeInput, p.nameValue, p.defaultValue, p.placeholder FROM care_medocs_specialites s, care_medocs_specialites_parametres sp, care_medocs_parametres p WHERE s.valide_status='O' AND sp.valide_status='O' AND p.valide='O' AND s.id=sp.specialite_id AND p.id=sp.parametre_id AND s.id=".$idSpecialite." ORDER BY sp.position ASC";
				$parametresSpecialites=$db->Execute($sqlParametresSpecialites);

				//Parametres
				if($encounterParameters=$objEncounter->getEncounterParameters($encounter_nr)){
					while($encParam=$encounterParameters->FetchRow()){
						$$encParam['nameValue']=$encParam['value'];
					}
				}

				echo '<table style="width:100%;border:0px; " cellpadding="0px" cellspacing="0px">';
				$i=0;
				while($showParam=$parametresSpecialites->FetchRow()){
					$i++;
					if($i % 2==0){
						$color='white';
					} 
					else $color='#ddd';

					$paramNameValue="";
					if($showParam['typeInput']=="date"){
						$paramNameValue=parseDate($$showParam['nameValue'],'Y-M-D','-','','D/M/Y','/');
					}
					elseif($showParam['typeInput']=="liste"){
						$keys=explode("#",$$showParam['nameValue']);
						$tuple=explode(';',$showParam['value']);
						//var_dump($showParam['value']);die();
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

					echo '<tr style="background-color:'.$color.'">';
						echo '<td style="width:30%; vertical-align: text-top;"><u>'.$showParam['designation'].'</u></td>';
						echo '<td style="width:70%;text-align:justify;">: '.$paramNameValue.'</td>';
					echo '</tr>';
				
				}
				echo '</table>';
				
				echo "<hr>";

			}
		}
	echo '</div>';
	$modalContent=ob_get_contents();
	ob_end_clean();
	$smarty->assign('modalContent', $modalContent);
	/**************************Fin de ceci************************/
}

$smarty->assign('sMainBlockIncludeFile','medocs/medocs_dashboard.tpl');
?>