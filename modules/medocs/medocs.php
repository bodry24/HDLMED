<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
ini_set('display_errors',true);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/
$lang_tables[]='medocs.php';
$lang_tables[]='person.php';
$lang_tables[]='measurement.php';
$lang_tables[]='prompt.php';
define('LANG_FILE','aufnahme.php');
define('NO_2LEVEL_CHK',1);

require_once($root_path.'include/core/inc_front_chain_lang.php');

require_once($root_path.'include/care_api_classes/class_person.php');
$objPerson=new Person;

require_once($root_path.'include/care_api_classes/class_medocs_params.php');
	$objMedocsParams=new Medocs_params;

require_once($root_path	.'include/care_api_classes/class_encounter.php');
	$objEncounter=new Encounter;

require_once($root_path	.'include/care_api_classes/class_measurement.php');
	$objMeasurement=new Measurement;
//die();
//var_dump($_SESSION);
#Information de l'utiilisateur connecté
$login=isset($_SESSION['sess_login_username'])?$_SESSION['sess_login_userid']:$_SESSION['sess_user_login_id'];

$userPID=$objPerson->getUserPid($login);
$userName=$objPerson->getValue('name_last',$userPID).' '.$objPerson->getValue('name_first',$userPID);

if(!isset($userPID)){
	if(isset($create_id) && !empty($create_id)) $userPID=$create_id;
}
//var_dump($userPID);
if(empty($userPID)) header('location:'.$root_path.'modules/medocs/medocs_pass.php'.URL_APPEND);

$baseLink=$root_path."modules/medocs/medocs.php".URL_APPEND;

$Time=date("d-m-Y H:i:s");

if(isset($pid)) $baseLink.="&pid=".$pid;

global $db;
$db->Execute("SET NAMES utf8");
# Start Smarty templating here
/**
 * LOAD Smarty
 */
# Note: it is advisable to load this after the inc_front_chain_lang.php so
# that the smarty script can use the user configured template theme
$template_theme="medocs";
require_once($root_path.'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('common');
$smarty->assign('baseLink',$baseLink);
$smarty->assign('root_path',$root_path);

$smarty->assign("userName",$userName);
#JAVASCRIPT
ob_start();
?>
<script type="text/javascript">
	$(document).ready(function(){
		//alert('Bonjour');
		var len=parent.document.getElementsByTagName("frameset").length;
		//alert(len);
		if(len>0) parent.document.getElementsByTagName("frameset")[0].cols="0,*";
	})
</script>
<?php
$JavaScript=ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$JavaScript);
$smarty->assign('paramLink','medocs_params.php'.URL_APPEND.'&target=params');
$smarty->assign('deconLink','medocs_params.php'.URL_APPEND.'&target=deconnecter');

//Nombre de recu éffectué dans le compte du médecin
$sqlStatMed="SELECT ifnull(COUNT(r.code_recu),0) FROM care_caisse_recu r, care_caisse_recu_actes a WHERE a.code_recu=r.code_recu AND r.valide_status='O' AND a.personnel_pid='".$userPID."' AND date(r.create_time)='".date('Y-m-d')."'";
//echo $sqlStatMed;
if($resultStatMed=$db->Execute($sqlStatMed)) $statMed=$resultStatMed->FetchRow()[0];
$smarty->assign("nbreActe",$statMed);
//Nombre d'admission éffectué par le médecin dans la journée
$sqlStatAdmission="SELECT ifnull(COUNT(encounter_nr),0) FROM care_encounter WHERE create_id=".$userPID." AND date(encounter_date)='".date('Y-m-d')."'";
//echo $sqlStatAdmission;
if($resultStatAdmission=$db->Execute($sqlStatAdmission)) {
	$statAdmission=$resultStatAdmission->FetchRow();
	$statAdmission=$statAdmission[0];
}
else echo $sqlStatAdmission;
$smarty->assign("nbreConsultation",$statAdmission);


# Nombre de rapport en attente
$sqlRapportAttente="SELECT count(id) FROM care_mail_private WHERE recipient=".$userPID." AND valide_status='O' AND lu='N'";
if($resultRapportAttente=$db->Execute($sqlRapportAttente)){
	$rapportAttente=$resultRapportAttente->FetchRow();
	$nbreRapportAttente=$rapportAttente[0];
	if(intval($nbreRapportAttente)>0){
		$smarty->assign('nbreRapportAttente', $nbreRapportAttente);
	}
}
$smarty->assign('rapportAttenteLink', $root_path.'modules/medocs/medocs.php'.URL_APPEND.'&target=rapport_indexe&option=consulter&pid='.$pid."&medecin=".$userPID);

//Passage de la valeur du target au template
$smarty->assign('target',$target);
//var_dump($target);
if(!isset($mode)) $mode="form";
require_once('gui_bridge/default/patientHeaderDetail.php');
require_once("gui_bridge/default/gui_menu.php");
//var_dump($target);
$smarty->assign("searchAction","medocs_dashboard.php".URL_APPEND."&chercher=chercher");

switch($target){
	case 'search':
		//require_once('show_search.php');
		$moduleName="Nouvelles donne&eacute;es";
		$includeFile="show_search.php";
		$formFile="gui_input_encounter.php";
	break;

	case 'admission':
		//echo 'admission';
		$moduleName="Nouvelles donne&eacute;es";
		$includeFile="gui_bridge/default/gui_encounter.php";
		$formFile="gui_input_encounter.php";
	break;
	case 'newdata':
		$moduleName="Nouvelles donne&eacute;es";
		$includeFile="gui_bridge/default/gui_show_encounter.php";
	break;
	case 'constante':
		$moduleName="Constante";
		$includeFile="gui_bridge/default/gui_constante.php";
	break;
	case 'prescription';
		$moduleName="Ordonnance M&eacute;dicales";
		$includeFile="gui_bridge/default/gui_prescription.php";
		$formFile="gui_input_prescription.php";
	break;

	case 'extPrescription';
		$moduleName="Ordonnance M&eacute;dicales";
		$includeFile="gui_bridge/default/gui_ext_prescription.php";
	break;

	case 'intPrescription';
		$moduleName="Ordonnance de Traitement";
		$includeFile="gui_bridge/default/gui_int_prescription.php";
	break;

	case 'analyse';
		$moduleName="Demande d'analyse";
		$includeFile="gui_bridge/default/gui_analyse.php";
	break;

	case 'examen';
		$moduleName="Demande d'examen";
		$includeFile="gui_bridge/default/gui_examen.php";
	break;


	case 'certificat';
		$moduleName="Certificat";
		$includeFile="gui_bridge/default/gui_certificat.php";
		$formFile="gui_input_certificat.php";
	break;	

	case 'dashboard':
		$moduleName="R&eacute;sum&eacute;";
		$includeFile="gui_bridge/default/gui_medocs.php";
		$formFile="gui_input_encounter.php";
	break;
	case 'lettre':
		$moduleName="Lettre";
		$includeFile="gui_bridge/default/gui_lettres.php";
		$formFile="gui_input_lettres.php";
	break;
	case 'antecedent':
		$includeFile="gui_bridge/default/gui_antecedent.php";
		$formFile="gui_antecedent.php";
	break;
	case 'vaccination_pediatrie':
		$includeFile="gui_bridge/default/gui_vaccination_pediatrie.php";
		$formFile="gui_vaccination_pediatrie.php";

	break;
	case 'vaccination_adulte':
		$includeFile="gui_bridge/default/gui_vaccination_adulte.php";
		$formFile="gui_vaccination_adulte.php";

	break;
	case 'registre':
		
		$includeFile="gui_bridge/default/gui_registre.php";
		$formFile="gui_registre.php";
	break;

	case 'note':
		$includeFile="gui_bridge/default/gui_note.php";
		$formFile="gui_note.php";
	break;
	case 'rapport':
		$includeFile="gui_bridge/default/gui_rapport.php";
		$formFile="gui_rapport.php";
	break;
	case 'passation':
		$includeFile="gui_bridge/default/gui_passation.php";
		$formFile="gui_passation.php";
	break;
	case 'rapport_indexe':
		$includeFile="gui_bridge/default/gui_rapport_indexe.php";
		$formFile="gui_rapport_indexe.php";
	break;
	case 'resume':
		$moduleName="Dossier medical :: Resumé";
		$includeFile='gui_bridge/default/gui_show_dashboard.php';
	break;
	/***************************** CPN *************************/
	case 'cpn':
		$moduleName="CONSULTATION PRE-NATALE";
		$includeFile='gui_bridge/default/gui_declaration_cpn.php';
	break;
	case 'declaration_grossesse':
		$moduleName="Déclaration de Grossesse";
		$includeFile='gui_bridge/default/gui_declaration_grossesse.php';
	break;
	/*****************************Fin CPN***********************/
	case 'echographie':
		$moduleName="RAPPORT D'ECHOGRAPHIE";
		$includeFile='gui_bridge/default/gui_echographie.php';
	break;
	case 'scoreigs':
		$moduleName="SCORE IGS II";
		$includeFile='gui_bridge/default/gui_scoreigs.php';
	break;

	default:
		$moduleName="Dossier medical :: Resumé";
		$includeFile='gui_bridge/default/gui_show_dashboard.php';
	break;
}

require_once($includeFile);
/*$smarty->assign('Subtitle',$Subtitle);
$smarty->assign('moduleName',$moduleName);*/

$smarty->display('common/mainframe.tpl');

?>