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
require_once($root_path.'include/core/inc_front_chain_lang.php');

require_once($root_path.'include/care_api_classes/class_person.php');
$objPerson=new Person;

require_once($root_path."modules/caisse/utils.php");

//var_dump($_SESSION);
#Information de l'utiilisateur connecté
$login=isset($_SESSION['sess_login_username'])?$_SESSION['sess_login_userid']:$_SESSION['sess_user_login_id'];

$userPID=$objPerson->getUserPid($login);
$userName=$objPerson->getValue('name_last',$userPID).' '.$objPerson->getValue('name_first',$userPID);

$baseLink=$root_path."modules/medocs/medocs.php".URL_APPEND;
if(isset($pid)) $baseLink.="&pid=".$pid;
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
		$("#datatable2").DataTable();
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

$includeFile='show_dashboard.php';
	
require_once($includeFile);

$smarty->display('common/mainframe.tpl');

?>