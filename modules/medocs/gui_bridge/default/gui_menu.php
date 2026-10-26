<?php  
	ob_start();

	require_once($root_path.'include/care_api_classes/class_appointment.php');
	$objAppointment=new Appointment;
	?>
	<script type="text/javascript">
		$(function(){
			$("#selectSpecialite").change(function(){
				var selectSpecialite=$(this).val();
				if(selectSpecialite!=""){
					$(location).attr("href", "<?php echo $root_path.'modules/medocs/medocs.php'.URL_APPEND.'&pid='.$pid.'&target=newdata&idSpecialite=';?>"+selectSpecialite);
				}
			});
		});
	</script>
	<style type="text/css">
		.unIdentified{
		background-color: #eeeeee;
	}

	.rdvM{
		border: 1px blue solid;
		background-color: #eeeeee;
	}

	.rdvF{
		border: 1px #ffc0cb solid;	
		background-color: #eeeeee;
	}


	.rdvArrive{
		background-color: green;
		color:white;
	}

	.rdvTraite{
		background-color: #02327E;
		color:white;
		font-weight: bold;
	}

	.rdvAttente1{
		background-color: orange;
		color:white;
	}

	.rdvAttente2{
		background-color: orange;
		color:white;
	}

	/*.rdvAttente2{
		background-color: red;
		color:white;
	}
	*/
	.rdvNon{
		background-color: #c71585;
		color:white;
	}
	</style>
	<?php
	$js=ob_get_contents();
	ob_end_clean();
	$smarty->append('JavaScript', $js);

	//var_dump(URL_APPEND);die();
	$thisfile="medocs.php".URL_APPEND."&pid=".$pid;
	var_dump($pid);
	
	$tabMenu=array(
		array("text"=>"Resume","link"=>$thisfile."&target=resume","active"=>$target=="resume"?"active":""),
		array("text"=>"Consultation","link"=>"&target=newdata","active"=>$target=="newdata"?"active":"", "selectOptions"=>$objMedocsParams->specialiteOptions()),
		
		array("text"=>"Constante","link"=>$thisfile."&target=constante","active"=>$target=="constante"?"active":""),
		array("text"=>"Ordonnance Externe","link"=>$thisfile."&target=extPrescription","active"=>$target=="extPrescription"?"active":""),
		array("text"=>"Protocole de Traitement","link"=>$thisfile."&target=intPrescription","active"=>$target=="intPrescription"?"active":""),
		array("text"=>"Demande d'analyse","link"=>$thisfile."&target=analyse","active"=>$target=="analyse"?"active":""),
		array("text"=>"Demande d'examen","link"=>$thisfile."&target=examen","active"=>$target=="examen"?"active":""),
		array("text"=>"Certificats","link"=>$thisfile."&target=certificat","active"=>$target=="certificat"?"active":""),
		array("text"=>"Lettres","link"=>$thisfile."&target=lettre","active"=>$target=="lettre"?"active":""),
		array("text"=>"Vaccination Pédiatrie","link"=>$thisfile."&target=vaccination_pediatrie","active"=>$target=="vaccination_pediatrie"?"active":""),
		array("text"=>"Vaccination Adulte","link"=>$thisfile."&target=vaccination_adulte","active"=>$target=="vaccination_adulte"?"active":""),
		array("text"=>"Régistre","link"=>$thisfile."&target=registre&idSpecialite=15","active"=>$target=="registre"?"active":""),
		array("text"=>"Antécédent","link"=>$thisfile."&target=antecedent","active"=>$target=="antecedent"?"active":""),
		array("text"=>"Notes d'hospitalisation","link"=>$thisfile."&target=note","active"=>$target=="note"?"active":""),
		array("text"=>"Echographie","link"=>$thisfile."&target=echographie","active"=>$target=="echographie"?"active":""),
		array("text"=>"Rapport d'hospitalisation","link"=>$thisfile."&target=rapport","active"=>$target=="rapport"?"active":""),
		array("text"=>"Passation de service","link"=>$thisfile."&target=passation","active"=>$target=="passation"?"active":""),
		array("text"=>"SCORE IGS II","link"=>$thisfile."&target=scoreigs","active"=>$target=="scoreigs"?"active":""),
		array("text"=>"Rapport Indexé","link"=>$thisfile."&target=rapport_indexe","active"=>$target=="rapport_indexe"?"active":""),
		//array("text"=>"Dashboard","link"=>"medocs_dashboard.php".URL_APPEND,"active"=>$target==""?'O':'N'),
		
	);

	/**********CPN***********/
	if($objPerson->getValue("sex", $pid)=="f"){
		$tabMenu[]=array("text"=>"CONSULTATION PRE-NATALE (CPN)","link"=>$thisfile."&target=cpn","active"=>$target=="cpn"?"active":"");
	}
	/*********Fin CPN********/
	$tabMenu[]=array("text"=>"Dashboard","link"=>"medocs_dashboard.php".URL_APPEND,"active"=>$target==""?'O':'N');
	

	$smarty->assign('tabMenu',$tabMenu);

	/********RENDEZ-VOUS*******/
	$dateOp=date('Y-m-d');
	if($appointmentList=$objAppointment->getDoctorAppointmentList($dateOp,$userPID,' ORDER BY position, start_time ')){
		if(isset($appointmentList)){
			ob_start();
			$pos=1;
			$tampon="";
			while($appointment=$appointmentList->FetchRow()){
				$hEntree=$appointment['h_entree'];
				$createDate=$appointment['create_date'];
				$apptDate=$appointment['date'];
				$sexe=$appointment['sexe'];
				$patient_pid=$appointment['pid'];
				$position=$appointment['position'];
				$remind_phone=$appointment['remind_phone'];
				$appointmentId=$appointment['nr'];
				$history=$appointment['history'];
				$hArrive=$appointment['h_arrive'];
				$position=$appointment['position'];
				$NRdv="";
				$icon="glyphicon-folder-close";
				$is_discharged="";
				//Determination de classe
				$classe="unIdentified";
				//var_dump($sexe);
				/*switch($sexe){
					case 'f':
						$classe="rdvF";
					break;
					case 'm':
						$classe="rdvM";
					break;
				}*/
				
				if($createDate==$apptDate){
					$classe="rdvNon";
					$NRdv="nrdv";
				}
				
				if($hArrive=="00:00:00"){
					//$classe="rdvNon";
				}
				else{

					/*if($Entree==""){
						//Determiner le temps d'attente
						$heure=date(His);
						$apptHeure=str_replace(':','',$hArrive);
						$tempsAttente=$heure-$apptHeure;
						if($tempsAttente>=0 && $tempsAttente<10000) $classe="rdvArrive";
						if($tempsAttente>=10000 && $tempsAttente<20000) $classe="rdvAttente1";	//Attente de 1H																										
						if($tempsAttente>=20000) $classe="rdvAttente2";
					}
					else{
						$classe="rdvTraite";
						if($createDate==$apptDate){
							$NRdv="nrdv";
						}
					}*/
					//Vérifier la présence d'un admission
					if($appointment['encounter_nr']!=0){
						$classe="rdvTraite";
						//Vérifier si le dossier est fermé
						$check_discharged=$objEncounter->Is_Discharged($appointment['encounter_nr']);
						//var_dump($is_discharged);
						$row_discharged=$check_discharged->FetchRow();
						$is_discharged=$row_discharged['is_discharged'];
						if($is_discharged=="1") $icon="glyphicon-folder-close";
						else $icon="glyphicon-folder-open";
					}
					else{
						$heure=date(His);
						$apptHeure=str_replace(':','',$hArrive);
						$tempsAttente=$heure-$apptHeure;
						if($tempsAttente>=0 && $tempsAttente<10000) $classe="rdvArrive";
						if($tempsAttente>=10000 && $tempsAttente<20000) $classe="rdvAttente1";	//Attente de 1H																										
						if($tempsAttente>=20000) $classe="rdvAttente2";
					}
				}
				$btnClasse='btn-default';
				switch($classe){
					case 'rdvTraite':
						$btnClasse='btn-success';
						if($is_discharged!="1") $btnClasse="btn-warning";
					break;

					case 'rdvAttente1':
					break;

					case 'rdvAttente2':
					break;

					case 'rdvArrive':
					break;
				}
				//var_dump($appointment);die();
				if($position==0){
					$tampon.='<tr><td class="'.$classe.'"><a href="medocs.php'.URL_APPEND.'&pid='.$patient_pid.'"><button class="btn btn-sm '.$btnClasse.'"><i class="glyphicon '.$icon.'"></i></button></a></td><td class="'.$classe.'">'.mb_convert_encoding(strtoupper($appointment['name_last']).' '.$appointment['name_first'], "UTF-8").'  ['.mb_convert_encoding($appointment['item_description'], "UTF-8").']</td></tr>';
				}
				else{
					echo '<tr>';
					echo '<td class="'.$classe.'"><a href="medocs.php'.URL_APPEND.'&pid='.$patient_pid.'"><button class="btn btn-sm '.$btnClasse.'">'.str_pad($position,2,0,STR_PAD_LEFT).' <i class="glyphicon '.$icon.'"></i></button></a></td>';
					echo '<td class="'.$classe.'">'.utf8_decode(strtoupper($appointment['name_last']).' '.$appointment['name_first']).'  ['.mb_convert_encoding($appointment['item_description'], "UTF-8").']</td></tr>';
				}
				$pos++;
			}
			echo $tampon;
			$listing=ob_get_contents();
			ob_end_clean();
			$smarty->assign("listingAppointment",$listing);
		}
	}


?>