<?php 
ob_start();
?>
<style type="text/css">
	.table {
	    height: 0px;
	}
	
	.tdheight{
		height: 5px;
	}
	/* .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
	    padding: 2px;
	    padding-left: 5px;
	    padding-top: 5px; 
	    line-height: 0.42857;
	    vertical-align: top;
	    border-top: 1px solid #DDD;
	    margin-bottom: 0px;
	    height: 5px;
	} */
	table{
		border:0.1px;
		border-color: #ccc;
	}
	table tr {
		height: 5px;
		border-color: #ccc;
	}
	table tr td{
		padding-left:5px;
		border-color: #ccc;
	}

	
</style>
<?php
$js=ob_get_contents();
ob_end_clean();
$smarty->append("JavaScript", $js);

require_once($root_path."modules/caisse/utils.php");

$smarty->assign('pid', $pid);
$smarty->assign('PatientName', $objPerson->getValue('name_last', $pid)." ".$objPerson->getValue('name_first', $pid));
$smarty->assign('sexe', $objPerson->getValue('sex', $pid)=="m"?"Masculin":"Féminin");
if($objPerson->getValue('name_spouse', $pid)!=""){
	$smarty->assign('nameSpouse', $objPerson->getValue('name_spouse', $pid));
}
$smarty->assign('dateNaissance', parseDate($objPerson->getValue('date_birth', $pid), "Y-M-D","-","","D/M/Y","/"));
//$srcImage=$root_path."uploads/photos/registration/".$pid.".jpg";
$filename=$objPerson->getValue('photo_filename', $pid);
$srcImage=$root_path."uploads/photos/registration/".$filename;
if(file_exists($srcImage)){
	$smarty->assign("srcImage",$srcImage);
}

//Recuperation de encounter_nr
if(isset($_SESSION['sess_en']) && !empty($_SESSION['sess_en'])){
    $encounter_nr = $_SESSION['sess_en'];
}else if(isset($_SESSION['sess_full_en']) && !empty($_SESSION['sess_full_en'])){
    $encounter_nr = $_SESSION['sess_full_en'];
}else if(isset($_GET['encounter_nr']) && !empty($_GET['encounter_nr'])){
    $encounter_nr = $_GET['encounter_nr'];
    //$_SESSION['sess_en']=$encounter_nr;
}
else $encounter_nr=$objEncounter->isPIDCurrentlyAdmitted($pid);

$smarty->assign('AdmissionNr', $encounter_nr);

$smarty->assign('patientHeaderInfoTpl','medocs/patientHeaderInfo.tpl');

?>