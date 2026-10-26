<?php
# Load the encounter class
require_once($root_path.'language/fr/lang_fr_measurement.php');

require_once($root_path.'include/care_api_classes/class_encounter.php');
$objEncounter=new Encounter;

#Load class Person
require_once($root_path.'include/care_api_classes/class_person.php');
$objPerson=new Person;

require($root_path.'modules/biasa_pdf/utils.php');
require_once($root_path.'modules/caisse/utils.php');

$baseLink=$root_path."modules/medocs/medocs.php".URL_APPEND."&target=registre&pid=".$pid;
$Time=date("d/m/Y H:i:s");

$db->Execute("SET NAMES UTF-8");

#Fonction permettant de recuperer la valeur d'un champ paramétrer
function recupereValue($nr, $specialite=15, $field="diagnostic"){
    global $db;
    $sql="SELECT ep.value as name
            FROM care_medocs_encounter_parametres ep
            WHERE ep.spec_param_id=(SELECT p.id FROM care_medocs_specialites_parametres sp, care_medocs_parametres p WHERE sp.specialite_id=".$specialite." AND sp.parametre_id=p.id AND p.nameValue='".$field."') 
            AND ep.encounter_nr=".$nr." 
            AND ep.valide_status='O'";
    //var_dump($sql);die();
    if($result=$db->Execute($sql)){
        $rs=$result->FetchRow();
        return $rs['name']; 
    }else return "";
}


function recupereValueP($nr, $id=null){
    global $db;
    $sql="SELECT value as name
            FROM care_medocs_encounter_parametres 
            WHERE spec_param_id=".$id." AND encounter_nr=".$nr."
            AND valide_status='O'";
    //var_dump($sql);//die();
    if($result=$db->Execute($sql)){
        $rs=$result->FetchRow();
        return $rs['name']; 
    }else return "";
}
#Liste des docteurs
$docteurs=$objPerson->getListAtPost(17);
#CSS et JS
ob_start();
?>
<style type="text/css">
	
</style> 
<script type="text/javascript" src='<?php echo "https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js";?>'></script>
<script type="text/javascript" src='<?php echo "https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js";?>'></script>
<script type="text/javascript" src='<?php echo "https://cdn.datatables.net/buttons/1.3.1/js/buttons.flash.min.js";?>'></script>
<script type="text/javascript" src='<?php echo "https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js";?>'></script>
<script type="text/javascript" src='<?php echo "https://cdn.datatables.net/buttons/1.3.1/js/buttons.colVis.min.js";?>'></script>

<script type="text/javascript">
	
    $(function(){
        $("#exemple").DataTable({
            dom: 'Bfrtip',
            buttons: [
                 'excel','print'
            ],
            language: {
                processing:     "Traitement en cours...",
                search:         "Rechercher&nbsp;:",
                lengthMenu:     "Afficher _MENU_ éléments",
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

 		$("#date_letter").datetimepicker({
			format:'dd/mm/yyyy',
			autoclose:true,
			minView:2
		});

        $("#date_debut").datetimepicker({
			format:'dd/mm/yyyy',
			autoclose:true,
			minView:2
		});

		$("#date_fin").datetimepicker({
			format:'dd/mm/yyyy',
			autoclose:true,
			minView:2
		});

		 $("#date_debutd").datetimepicker({
            format:'dd/mm/yyyy',
            autoclose:true,
            minView:2
        });

        $("#date_find").datetimepicker({
            format:'dd/mm/yyyy',
            autoclose:true,
            minView:2
        });


    });
   
</script>
<?php
$js=ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript', $js);

#Ajout des spécialités

#Breadcrumb
$breadcrumb=array("DOSSIER MEDICAL","REGISTRE");
$specialites=$objMedocsParams->listeSpecialite();
$defaultSpecialite=15;//Cette specialité va etre remplacé par celui du medecin

//$breadcrumb=array("DOSSIER MEDICAL","REGISTRE","PEDIATRIE");//Ici aussi le nom de la specialité sera remplacé
$tabMenus=array();
if(is_object($specialites)){
    while($specialite=$specialites->FetchRow()){
        $idSpecialite=$specialite['id'];
        $nomSpecialite=$specialite['designation'];
       // $active=(mb_strtolower($idSpecialite)==$defaultSpecialite)?'active':'';
        if($specialite['registre']=='O'){
            $tabMenus[]=array('idSpecialite'=>$idSpecialite, 'nomSpecialite'=>$nomSpecialite, 'active'=>$active);
        }
        
    }
}

ob_start();
?>  

<ul class="nav nav-tabs">
    <?php
        if(!isset($registre) || $registre=='') $registre = 15;
        foreach ($tabMenus as $v) {

            if($registre==$v['idSpecialite']){
                $active = "active";
            }else{
                $active = " ";
            }
       
            echo '<li role="presentation" class="'.$active.'">';
            echo '<a class="item '.$active.'" href="' . $baseLink .'&registre=' . $v['idSpecialite'] . '" title="'.$v['nomSpecialite'].'">'.$v['nomSpecialite'].'</a>';
            echo '</li>';
        }
    ?>
</ul>

<?php

$sMenus=ob_get_contents();
ob_end_clean();

$smarty->assign('menus', $sMenus);

require_once('gui_'.$registre.'.php');

$smarty->assign("breadcrumbArray", $breadcrumb);

$smarty->assign('sMainBlockIncludeFile','medocs/medocs_registre.tpl');

?>