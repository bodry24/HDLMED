<?php
$menuTab=array(
	array("target"=>"dashboard","label"=>"R&eacute;sum&eacute;","active"=>1),
	array("target"=>"antecedent","label"=>"Ant&eacute;c&eacute;dent","active"=>1),
	array("target"=>"admission","label"=>"Nouvelle Donn&eacute;e","active"=>1),
	array("target"=>"showAdmission","label"=>"Donn&eacute;es d'admission","active"=>1),
	array("target"=>"prescription","label"=>"Ordonnances","active"=>1),
	array("target"=>"certificat","label"=>"Certificats","active"=>1),
	array("target"=>"vac_pediatrie","label"=>"Vaccination P&eacute;diatrie","active"=>1),
	array("target"=>"vac_patient","label"=>"Vaccination G&eacute;n&eacute;rale","active"=>1),
	array("target"=>"rapport","label"=>"Rapport d'hospitalisation","active"=>1),
	array("target"=>"lettre","label"=>"Lettres","active"=>1),
	array("target"=>"labo","label"=>"Laboratoire","active"=>1),
	array("target"=>"imagerie","label"=>"Imagerie","active"=>1),
	);
$smarty->assign("menuTab",$menuTab);
?>