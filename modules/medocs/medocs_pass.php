<?php

error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
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
define('LANG_FILE','stdpass.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');

require_once($root_path.'global_conf/areas_allow.php');

$allowedarea=&$allow_area['admit'];
$append=URL_REDIRECT_APPEND.'&from=pass'; 

if(!isset($_SESSION['sess_user_origin'])) $_SESSION['sess_user_origin'] = "";
if(!isset($target)) $target="dashboard";
switch($target){
	case 'params':
		$lognote="medocs_params_ok";
		$fileforward="medocs_params.php";
	break;

	case 'medocs':
		$lognote="medocs_ok";
		$fileforward="medocs.php";
	break;
	
	default:		
		$lognote="dashboard_ok";
		$fileforward="medocs_dashboard.php";
	break;
}

$fileforward=$fileforward.URL_APPEND;

$thisfile=basename(__FILE__);
//$breakfile='startframe.php'.URL_APPEND;
$breakfile=$root_path.'main/startframe.php'.URL_APPEND;

$userck='medocs_user';

setcookie($userck.$sid,'');
require($root_path.'include/core/inc_2level_reset.php'); 
setcookie(ck_2level_sid.$sid,'');

# reset the user origin
$_SESSION['sess_user_origin']='';

require($root_path.'include/core/inc_passcheck_internchk.php');
if ($pass=='check') 	
include($root_path.'include/core/inc_passcheck.php');

require($root_path.'include/core/inc_passcheck_head.php');
require($root_path.'include/core/inc_passcheck_mask.php');
?>
