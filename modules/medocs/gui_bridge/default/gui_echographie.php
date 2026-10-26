<?php
require_once($root_path.'include/care_api_classes/class_core.php');
$dbCore=new Core;

require_once($root_path.'include/care_api_classes/class_person.php');
$objPerson=new Person;

require_once($root_path.'include/care_api_classes/class_encounter.php');
$objEncounter=new Encounter;

require($root_path.'modules/biasa_pdf/utils.php');
require_once($root_path.'modules/caisse/utils.php');

$thisfile=basename(__FILE__);
$breakfile=$root_path.'main/startframe.php'.URL_APPEND;
$_SESSION['sess_path_referer']=$top_dir.$thisfile;

#Information de l'utiilisateur connecté et Controle de sécurité
$login=isset($_SESSION['sess_login_username'])?$_SESSION['sess_login_userid']:$_SESSION['sess_user_login_id'];
if(empty($login))  header("location:".$breakfile);

$userPID=$objPerson->getUserPid($login);
$userName=addslashes($objPerson->getValue('name_last',$userPID).' '.$objPerson->getValue('name_first',$userPID));
$Time=date('Y-m-d H:i:s');


$baseLink=$root_path."modules/medocs/medocs.php".URL_APPEND."&target=echographie&pid=".$pid;

global $db;

ob_start();

echo '<link rel="stylesheet" href="'.$root_path.'plugins/datatable/media/datatables.min.css" type="text/css">';
echo '<link rel="stylesheet" href="'.$root_path.'js/jquery/jquery-ui-1.10.4.min.css" type="text/css">';

echo '<script type="text/javascript" src="'.$root_path.'plugins/datatable/media/datatables.min.js"></script>';
echo '<script type="text/javascript" src="'.$root_path.'js/jquery/jquery-ui-1.10.4.min.js"></script>';
?>

<style type="text/css">
	body{
		font-size: 9px;
	}
</style>
<link rel="stylesheet" href="<?php echo $root_path.'plugins/datetimepicker/css/bootstrap-datetimepicker.min.css';?>">
<script type="text/javascript" src="<?php echo $root_path.'plugins/datetimepicker/js/bootstrap-datetimepicker.min.js';?>"></script>
<script type="text/javascript" src="<?php echo $root_path.'js/gadgets/squeezebox.js'; ?>"></script>
<script language="javascript">

function deleteLine(index){
	if(confirm("Voulez-vous supprimer cette ligne ?")){
		$("#tbPanier tr:eq("+index+")").remove();
	}
	else return false;
}

function formControl(){
	
	return true;
}


$(document).ready(function(){
	  $("#datatable").DataTable({
            language: {
                processing:     "Traitement en cours...",
                search:         "Rechercher&nbsp;:",
                lengthMenu:    "Afficher _MENU_ éléments",
                info:           "Affichage de l'élement _START_ &agrave; _END_ sur _TOTAL_ éléments",
                infoEmpty:      "Affichage de l'élement 0 &agrave; 0 sur 0 éléments",
                infoFiltered:   "(filtré de _MAX_ éléments au total)",
                infoPostFix:    "",
                loadingRecords: "Chargement en cours...",
                zeroRecords:    "Aucun élément &agrave; afficher",
                emptyTable:     "Aucune donnée disponible dans le tableau",
                paginate: {
                    first:      "Premier",
                    previous:   "Précédent",
                    next:       "Suivant",
                    last:       "Dernier"
                },
                aria: {
                    sortAscending:  ": activer pour trier la colonne par ordre croissant",
                    sortDescending: ": activer pour trier la colonne par ordre décroissant"
                }
            }
           
        });
		 //Autocomplete pour le patient
	    $("#name_last").autocomplete({
	        source:function(request,response){
	            var val=$("#name_last").val();
	            $.post("../ajax/autoComplete_patient.php<?php echo URL_APPEND; ?>",{value:val},function(data){     
	                response($.map(data, function(item) {
	                    return {
	                        label: item.nom,
	                        value: item.pid,
	                        age:item.age,
	                        prenoms:item.name_first,
	                        nom:item.name_last

	                    }
	                }))
	            }, "json");
	        },
	        select:function(event,ui){
	            $("#name_last").val(ui.item.nom);
	            $("#name_first").val(ui.item.prenoms);
	            $("#pid").val(ui.item.value);
	            $("#age").val(ui.item.age);
	            return false;
	        }
	    });

	

})


</script>

<?php

$js=ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$js);

$tabMenus=array('echo_obstetricale'=>'ECHOGRAPHIE OBSTETRICALE', 'echo_pelvienne'=>'ECHOGRAPHIE PELVIENNE');

# Informations global du patient
$nom=$objPerson->getValue('name_last', $pid);
$prenoms=$objPerson->getValue('name_first', $pid);
$tabAge = calcuteAge($ligne['date_birth'],'','fr');
$age = !empty($tabAge[0])?$tabAge[0] : (!empty($tabAge[1])?$tabAge[1]:(!empty($tabAge[2])?$tabAge[2]:''));

ob_start();
?>  
<ul class="nav nav-tabs">
    <?php
        if(!isset($rapport) || $rapport=='') $rapport = 'echo_obstetricale';
        foreach ($tabMenus as $k=>$v) {

            if($rapport==$k){
                $active = "active";
            }else{
                $active = " ";
            }
       
            echo '<li role="presentation" class="'.$active.'">';

            echo '<a class="item '.$active.'" href="'.$baseLink.'&rapport='.$k.'" title="'.$v.'">'.$v.'</a>';
            echo '</li>';
        }
    ?>
</ul>

<?php

$sMenus=ob_get_contents();
ob_end_clean();

$smarty->assign('menus', $sMenus);

require_once('gui_'.$rapport.'.php');

#Breadcrumb
$smarty->assign("breadcrumbArray", $breadcrumb);

$smarty->assign('sMainBlockIncludeFile','medocs/medocs_registre.tpl');

?>