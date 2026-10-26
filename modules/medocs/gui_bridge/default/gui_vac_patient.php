<?php


# Load the letter class
require_once($root_path.'include/care_api_classes/class_letter.php');
$obj_vaccination=new Vaccination();

#Appel des composants de la date
require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'js/jscalendar/calendar.php');
$calendar=new DHTML_Calendar($root_path.'js/jscalendar/', $lang, 'calendar-system',true);
$calendar->load_files();


# Load the encounter class
require_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj=new Encounter($_SESSION['sess_full_en']);
#Load class person
require_once($root_path.'include/care_api_classes/class_person.php');
$person=new Person();


if($mode=='form'){
    
     ob_start();
     	include('gui_input_vac_patient.php');
     	$sTemp=ob_get_contents();   	
     ob_clean();
     $smarty->assign('includeData', $sTemp);

}elseif($mode=='create'){

	if(isset($save)){

		extract($_POST);

		if($idVaccin!=null){

			$date_effectuee=formatDate2STD($date_effectuee, $date_format);
			//vÃ©rifier s'il n y aurait pas dÃ©jÃ  fait ce vaccin sinon on fait la sauvegarde
			//mais la vÃ©rification est laissÃ© pour le moment parcequ'on faire deux vaccins
			$history="Create ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n";
	        $modify_id= $_SESSION['sess_user_name'];
	        $create_id=$_SESSION['sess_user_name'];
	        $create_time=date('YmdHis');

			$data=array(
				'pid'=>$pid,
				'vid'=>$idVaccin,
				'effectuee'=>'O',
				'date_effectuee'=>$date_effectuee,
				'history'=>$history,
				'modify_id'=>$modify_id,
				'create_id'=>$create_id,
				'create_time'=>$create_time
				);
			
			if($obj_vaccination->saveVaccination($data)){
				$msg='Sauvegarde effectuee avec succes!';
				$class='success';
				//$msg='<center><font color="green">Sauvegarde effectuee avec succes!</font></center>';

			}else{
				$msg='Sauvegarde non effectuÃ©e!';
				$class='negative';
				//$msg='<center><font color="red">Sauvegarde non effectuee, veuillez revoir vos saisies!</font></center>';
			}

		}

		$mode="form";
		ob_start();
	     	include('gui_input_vac_patient.php');
	     	$sTemp=ob_get_contents();
	     
	    ob_clean();
		$smarty->assign('includeData', $sTemp);
	}

}elseif($mode=='update'){

	if(isset($save)){
		extract($_POST);
		#Je fais l'update ici
		global $db;
		$sql="UPDATE care_vaccination_patient SET vid=".$_POST['idVaccin'].", date_effectuee='".formatDate2STD($_POST['date_effectuee'], $date_format)."' WHERE vac_pid=$vac_pid";

		$result=$db->Execute($sql);
		//$msg='<center><font color="green">Mise a jour effectuee avec succes!</font></center>';
		$msg='Mise &agrave; jour effectu&eacute;e avec succes!';
		$class='success';
		$mode="form";
	}else{
		#RecupÃ©ration des valeurs pour la mise Ã  jour
		$vac_pid=$_GET['vac_pid'];
		$vaccination=$obj_vaccination->getVaccination($vac_pid);
		$idVaccin=$vaccination['vid'];	
		$nameVaccin=$obj_vaccination->getNameVaccin($idVaccin);	
		$dateVaccin=$vaccination['date_effectuee'];
	}
	
	ob_start();
		include('gui_input_vac_patient.php');
		$sTemp=ob_get_contents();
	ob_end_clean();
	$smarty->assign('includeData', $sTemp);
	
	
   
}elseif($mode=='delete'){

	$vac_pid=$_GET['vac_pid'];
	if($obj_vaccination->deleteVaccination($vac_pid)){

		$class="success";
		$msg="Suppression effectuÃ©e avec succe&grave;s!";

	}else{
		$class="negative";
		$msg="Suppression non effectuÃ©e!";

	}

	header("location:".$baseLink."&target=$target");
	exit;
	
}else{


	ob_start();
		include('gui_input_vac_patient.php');
		$sTemp = ob_get_contents();
	ob_end_clean();

	$smarty->assign('includeData',$sTemp);
}


?>

