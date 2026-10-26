<?php

require_once($root_path.'include/care_api_classes/class_person.php');
$objPerson=new person($pid);

$person=$objPerson->getAllInfoArray();
$smarty->assign('patientPID',$pid);
$smarty->assign('patientName',$person['name_last'].' '.$person['name_first']);

$patientImg=$root_path.'uploads/photos/registration/'.$person['photo_filename'];
if(!file_exists($patientImg)){
	$patientImg=$root_path.'gui/img/common/default/x-blank.gif';
}
$smarty->assign("patientImg",$patientImg);
?>