<?php
	/**
	* CARE2X Integrated Hospital Information System beta 2.0.1 - 2004-07-04
	* GNU General Public License
	* Copyright 2002,2003,2004,2005,2006 Elpidio Latorilla
	* elpidio@care2x.org, 
	*
	* See the file "copy_notice.txt" for the licence notice
	**/
	global $db;	

	require('./include/init_show.php');
	
	

	#Appel des composants de la date
	require_once($root_path.'include/core/inc_date_format_functions.php');
	
	require_once($root_path.'js/jscalendar/calendar.php');
	$calendar=new DHTML_Calendar($root_path.'js/jscalendar/', $lang, 'calendar-system',true);
	$calendar->load_files();

	if(isset($current_encounter) && $current_encounter) { 
		$parent_admit=true; 
		$is_discharged=false;
		$_SESSION['sess_en'] = $current_encounter;
	}


	//VÃ©rifie si le patient a dÃ©jÃ  un calendrier de vaccination
	$sql='select care_vaccination_patient.vac_pid, care_vaccination_patient.pid, care_vaccination_patient.vid as vid, care_type_immunization.name as vac_name, care_type_immunization.periode as periode, duree, effectuee, date_exigee, date_estimee, date_effectuee 
	        from care_type_immunization, care_vaccination_patient  
	        where care_type_immunization.nr=care_vaccination_patient.vid and pid='.$pid.' and care_vaccination_patient.type="pediatrie"';

    
   	$result=$db->Execute($sql);
   	
	if($result->RecordCount()>0){
		//Liste des vaccinations ici
		$rows=1;
		
	}else{
		//permet de gÃ©nÃ©rer le clendrier de vaccination
		$rows=0;
	}

	//appel du fichier d'affichage



	ob_start();
		include('gui_show_vac_pediatrie.php');
		$sTemp = ob_get_contents();
	ob_end_clean();
	$smarty->assign('includeData',$sTemp);
	
	$subtitle="Calendrier des Vaccinations";
	
	
?>


