<?php
require_once($root_path.'include/care_api_classes/class_appointment.php');
$objAppointment=new Appointment;

require_once($root_path.'include/care_api_classes/class_encounter.php');
$objEncounter=new Encounter;

require_once($root_path.'include/care_api_classes/class_ward.php');
$objWard= new Ward;

require_once($root_path.'include/care_api_classes/class_person.php');
$objPerson= new Person;

require_once($root_path.'include/care_api_classes/class_medocs_params.php');
$objMedocsParams= new Medocs_params;

require_once($root_path.'modules/caisse/utils.php');
/********RENDEZ-VOUS*******/
$dateOp=date('Y-m-d');

?>

<script type="text/javascript">
	$(function(){
		$(".selectSpecialite").change(function(){
			//var pid="<?php echo $pid;?>";
			var mavar=$(this).val();
			var array=mavar.split('-');
			selectSpecialite=array[0];
			var pid=array[1];
			
			if(pid!="" && pid!=0){
				if(selectSpecialite!=""){
					$(location).attr("href", "<?php echo $root_path.'modules/medocs/medocs.php'.URL_APPEND.'&pid=';?>"+pid+"&target=newdata&idSpecialite="+selectSpecialite);
				}
			}else{
				alert("Veuillez selectionnez le patient ou soit veuillez l'enregistrer");
				$("input[name='search']").focus();
			}
			
		});

		$("#idRendezVous").DataTable({
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
	        lengthMenu: [[10, 30, 100, 200, -1], [10, 30, 100, 200, "Tout"]],
		});

		$("#idOccupation").DataTable({
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
	        lengthMenu: [[8, 30, 100, 200, -1], [8, 30, 100, 200, "Tout"]],
		});

	});
</script>

<?php
$js=ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript', $js);


if($appointmentList=$objAppointment->getDoctorAppointmentList($dateOp,$userPID)){
	if(isset($appointmentList)){
		ob_start();
		$pos=1;
		while($appointment=$appointmentList->FetchRow()){
			$options="";
			$liste=$objMedocsParams->listeSpecialite();
			$pid=$appointment['pid'];
			while ($row=$liste->FetchRow()) {
				$options.='<option value="'.$row['id'].'-'.$pid.'">'.$row['designation'].'</option>';
			}
			echo '<tr>';
			echo '<td>'.$pos.'</td>';
			echo '<td>'.mb_convert_encoding($appointment['name_last'].' '.$appointment['name_first'], "UTF-8").'</td>';
			echo '<td>'.$appointment['start_time'].' '.$appointment['end_time'].'</td>';
			echo '<td>';
			if($appointment['date']==date('Y-m-d')) echo 'NRDV';
			else echo 'RDV';
			echo '</td>';
			//echo '<td><select name="options" id="" class="selectSpecialite"><option>Selectionner</option>'.utf8_decode($objMedocsParams->specialiteOptions()).'</select></td>';
			echo '<td><select name="options" id="" class="selectSpecialite"><option>Selectionner</option>'.$options.'</select></td>';

			echo '</tr>';
			$pos++;
		}
		$listing=ob_get_contents();
		ob_end_clean();
		$smarty->assign("listingAppointment",$listing);
	}
}

if(isset($chercher) && !empty($search)){
	//var_dump($search);
	$searchResult=$objPerson->SearchSelect($search);
	if(is_object($searchResult)){
		if($searchResult->RecordCount()==1) {
			$rowSearch=$searchResult->FetchRow();
			//var_dump($rowSearch);
			header("location:medocs.php".URL_APPEND."&pid=".$rowSearch['pid']);
		}
		ob_start();
		$pos=1;

		while($patient=$searchResult->FetchRow()){
			echo '<tr>';
			echo '<td>'.$pos.'</td>';
			echo '<td>'.$patient['name_last'].'</td>';
			echo '<td>'.$patient['name_first'].'</td>';
			echo '<td>'.$patient['name_spouse'].'</td>';
			echo '<td>'.strtoupper($patient['sex']).'</td>';
			echo '<td><a href="medocs.php'.URL_APPEND.'&pid='.$patient['pid'].'"><button class="btn btn-primary btn-xs">'.$patient['pid'].'</button></a></td>';
			echo '</tr>';
			$pos++;
		}
		$listingResearch=ob_get_contents();
		ob_end_clean();
		$smarty->assign('listingResearch',$listingResearch);
	}
}
else{
	/********OCCUPATIONS DES CHAMBRES*******/
	$listEncounter=$objEncounter->listpatadmi('*');

	if(is_object($listEncounter)){
		ob_start();
			$pos=1;
			while ($occupation=$listEncounter->FetchRow()) {

				$encounter_nr=$occupation['encounter_nr'];               
	            $encounterLocationInfo=$objWard->EncounterLocationsInfo($encounter_nr);
	            $ward_id=$encounterLocationInfo['ward_id'];
	            $ward_nr=$encounterLocationInfo['ward_nr'];
	            $room_nr=$encounterLocationInfo['room_nr'];
	            $bed_nr=$encounterLocationInfo['bed_nr'];  
	            $pid=$occupation['pid'];
	            $patientName=empty($occupation['name_spouse'])?$occupation['name_last'].' '.$occupation['name_first']:$occupation['name_last'].' '.$occupation['name_first'].' épouse '.$occupation['name_spouse'];
	            $roomName=($objWard->getRoomNameByNr($room_nr)!=''?$objWard->getRoomNameByNr($room_nr):'');
	            $rsDate=$objEncounter->getValue('encounter_date',$encounter_nr);
	            $rs=$rsDate->FetchRow();
	            $dateAdmission=parseDate($rs[0],'Y-M-D','-','','D/M/Y','/');
	            $chambre=$roomName.' Lit N°'.$bed_nr;
	            echo '<tr>';
					echo '<td class="text-right">'.$pos.'</td>';
					echo '<td>'.$patientName.'</td>';
					echo '<td>'.$encounter_nr.'</td>';
				    echo '<td>'.$chambre.'</td>';
					echo '<td class="text-center">
								<a href="'.$root_path.'modules/medocs/medocs.php'.URL_APPEND.'&pid='.$pid.'" class="btn btn-xs btn-primary" style="color:#fff">
									DOSSIER <span class="glyphicon glyphicon-arrow-right"></span>
								</a>
							</td>';
				echo '</tr>';
				
				$pos++;
			}
		$listing=ob_get_contents();
		ob_end_clean();
		$smarty->assign('listingOccupation', $listing);
	}
}

# Pour la passation
$smarty->assign("urlChirurgical", $root_path."modules/medocs/medocs.php".URL_APPEND."&target=passation&zone=2&aile=CHIRURGICAL");
$smarty->assign("urlMedical", $root_path."modules/medocs/medocs.php".URL_APPEND."&target=passation&zone=2&aile=MEDICAL");
$smarty->assign("urlUrgence", $root_path."modules/medocs/medocs.php".URL_APPEND."&target=passation&zone=4");
$smarty->assign("urlAccouchement", $root_path."modules/medocs/medocs.php".URL_APPEND."&target=passation&zone=6");
$smarty->assign("urlSoinsIntensif", $root_path."modules/medocs/medocs.php".URL_APPEND."&target=passation&zone=5");
$smarty->assign("urlPediatrie", $root_path."modules/medocs/medocs.php".URL_APPEND."&target=passation&zone=3");

$smarty->assign('sMainBlockIncludeFile','medocs/dashboard.tpl');
?>