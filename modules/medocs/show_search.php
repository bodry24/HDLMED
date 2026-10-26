<?php
/*
Recherche de patient pour le dossier medical
*/

#Importation de la classe de recherche
require_once($root_path."include/care_api_classes/class_gui_search_person.php");
$psearch = & new GuiSearchPerson('dashboard'); //Instantiation de la classe de recherche
#
# Create the search object
#
//Mettre les \ aux mot-clÃ© de recherche 
if(isset($searchkey)&&trim($searchkey)!=''){
	$searchkey = addslashes($searchkey);
}

$psearch->setTargetFile('medocs.php');
//$psearch->setOrigin($origine);
 
$psearch->setCancelFile($breakfile);

$psearch->setPrompt($LDEnterPersonSearchKey);

# Set to TRUE if you want to auto display a single result
//$psearch->auto_show_byalphanumeric =TRUE;
# Set to TRUE if you want to auto display a single result based on a numeric keyword
# usually in the case of barcode scanner data
$psearch->auto_show_bynumeric = TRUE;

# Start buffering the appending post search text
ob_start();

# If the origin is admission link, show a button for creating an empty form
if(isset($origin) && $origin=='pass')
{
?>
<form action="patient_register.php" method=post>
<input type=submit value="<?php echo $LDNewForm ?>">
<input type=hidden name="sid" value=<?php echo $sid; ?>>
<input type=hidden name="lang" value="<?php echo $lang; ?>">
</form>
<?php
}

#  End buffering and assign buffer contents to template

$sTemp = ob_get_contents();
ob_end_clean();

# Assign the appending post search text
$psearch->posttext = $sTemp;

$smarty->assign('sMainDataBlock',$psearch->create());

$smarty->assign('includeTemplate','registration_admission/reg_plain.tpl');
?>