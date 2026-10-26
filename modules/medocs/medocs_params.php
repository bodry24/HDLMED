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
define('NO_2LEVEL_CHK',1);
$lang_tables[]='medocs.php';
$lang_tables[]='person.php';
$lang_tables[]='measurement.php';
define('LANG_FILE','aufnahme.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');

require_once($root_path.'include/care_api_classes/class_person.php');
$objPerson=new Person;

require_once($root_path.'include/care_api_classes/class_medocs_params.php');
$objMedocsParams=new Medocs_params;

//var_dump($_SESSION);
#Information de l'utiilisateur connecté
$login=isset($_SESSION['sess_login_username'])?$_SESSION['sess_login_userid']:$_SESSION['sess_user_login_id'];

$userPID=$objPerson->getUserPid($login);
$userName=$objPerson->getValue('name_last',$userPID).' '.$objPerson->getValue('name_first',$userPID);
$Time=date("Y-m-d H:i:s");

$baseLink=$root_path."modules/medocs/medocs_params.php".URL_APPEND;
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

global $db;
$db->Execute("SET NAMES utf8");

$smarty->assign("userName",$userName);
#JAVASCRIPT
ob_start();
?>
<script type="text/javascript">
	$(document).ready(function(){
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
//Passage de la valeur du target au template
$smarty->assign('target',$target);

require_once('gui_bridge/params/gui_menu.php');

switch($target){
	case 'specialite':
		//require_once('show_search.php');
		$moduleName="Spécialités Admission";
		$includeFile="gui_bridge/params/show_specialite.php";
	break;
	
	case 'params_specialite':
		//echo 'admission';
		$moduleName="Parametres - Spécialités";
		$includeFile="gui_bridge/params/show_params_specialite.php";
	break;

	case 'constante';
		$moduleName="Constante";
		$includeFile="gui_bridge/params/show_constante.php";
	break;

	case 'constante_specialite';
		$moduleName="Constante - Spécialités";
		$includeFile="gui_bridge/params/show_constante_specialite.php";
	break;

	case 'constante_ward';
		$moduleName="Constante - Zone";
		$includeFile="gui_bridge/params/show_constante_ward.php";
	break;

	case 'unite_mesure';
		$moduleName="Unité de mésure";
		$includeFile="gui_bridge/params/show_unite_mesure.php";
	break;	

	case 'parametre';
		$moduleName="Parametres Admission";
		$includeFile="gui_bridge/params/show_params_admission.php";
	break;	

	case 'type_constante';
		$moduleName="Type Constante";
		$includeFile="gui_bridge/params/show_type_constante.php";
	break;

	case 'acte_specialite';
		$moduleName="Acte Spécialités";
		$includeFile="gui_bridge/params/show_acte_specialite.php";
	break;

	case 'medecin_specialite';
		$moduleName="Medecin Spécialités";
		$includeFile="gui_bridge/params/show_medecin_specialite.php";
	break;

	case 'deconnecter':
				
		# Empty session login values
		$_SESSION['sess_login_userid']='';		
		$_SESSION['sess_login_username']='';
		$_SESSION['department_nr']='';
		$_SESSION['sess_login_pw']='';
		$_SESSION['sess_user_name']='';
		#
		# Redirect to login page for eventual new login
		#
		header("location:../../main/login.php".URL_REDIRECT_APPEND."&is_logged_out=1");
	break;

	default:
		$includeFile='gui_bridge/params/show_dashboard.php';
	break;
}

require_once($includeFile);
/*$smarty->assign('Subtitle',$Subtitle);
$smarty->assign('moduleName',$moduleName);*/
if(isset($breadcrumb)) $smarty->assign('breadcrumbArray',$breadcrumb);
$smarty->display('common/mainframe.tpl');

?>