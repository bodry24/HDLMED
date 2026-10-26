<?php  

$menuTab=array(
	array("text"=>"Spécialités","link"=>"medocs_params.php".URL_APPEND."&target=specialite","active"=>$target=="specialite"?'O':'N'),
	array("text"=>"Parametres Admission","link"=>"medocs_params.php".URL_APPEND."&target=parametre","active"=>$target=="parametre"?'O':'N'),
	array("text"=>"Parametres - Spécialités","link"=>"medocs_params.php".URL_APPEND."&target=params_specialite","active"=>$target=="params_specialite"?'O':'N'),
	array("text"=>"Constante","link"=>"medocs_params.php".URL_APPEND."&target=constante","active"=>$target=="constante"?'O':'N'),
	array("text"=>"Type Constante","link"=>"medocs_params.php".URL_APPEND."&target=type_constante","active"=>$target=="type_constante"?'O':'N'),
	array("text"=>"Constante - Spécialités","link"=>"medocs_params.php".URL_APPEND."&target=constante_specialite","active"=>$target=="constante_specialite"?'O':'N'),
	array("text"=>"Constante - Zone","link"=>"medocs_params.php".URL_APPEND."&target=constante_ward","active"=>$target=="constante_ward"?'O':'N'),
	array("text"=>"Unité de mésure","link"=>"medocs_params.php".URL_APPEND."&target=unite_mesure","active"=>$target=="unite_mesure"?'O':'N'),
	array("text"=>"Actes - Spécialites","link"=>"medocs_params.php".URL_APPEND."&target=acte_specialite","active"=>$target==""?'O':'N'),
	array("text"=>"Medécin - Spécialités","link"=>"medocs_params.php".URL_APPEND."&target=medecin_specialite","active"=>$target==""?'O':'N'),
	array("text"=>"Dashboard","link"=>"medocs_dashboard.php".URL_APPEND,"active"=>$target==""?'O':'N')


	);

$smarty->assign('tabMenu',$menuTab);

?>