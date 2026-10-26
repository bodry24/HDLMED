<?php
require_once($root_path.'include/care_api_classes/class_encounter.php');
$objEncounter=new Encounter;


#CSS et JS
ob_start();
?>

<link rel="stylesheet" href="<?php echo $root_path.'js/jquery/jquery-ui-1.9.1.custom.min.css';?>"/>
<script type="text/javascript" src="<?php echo $root_path.'js/jquery/jquery-ui-1.9.1.custom.min.js';?>"></script> 
<script type="text/javascript" src="<?php echo $root_path.'js/gadgets/squeezebox.js'; ?>"></script>
<style type="text/css">
    
</style> 
<script type="text/javascript">

    $(function(){
        $("#exemple").DataTable({
            order: [[3,"desc"]],
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
       
        $("#date_debut").datetimepicker({
            format:'dd-mm-yyyy hh:ii',
            autoclose:true,
            todayBtn: true,
            minuteStep:1
        });

        $("#date_fin").datetimepicker({
            format:'dd-mm-yyyy hh:ii',
            autoclose:true,
            todayBtn: true,
            minuteStep:1
        });

    }); 
</script>
<?php
$js=ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript', $js);


#Breadcrumb
$breadcrumb=array("DOSSIER MEDICAL","CONSULTATION PRE-NATALE", "COMPTE RENDUS OPERATOIRES");
$smarty->assign("breadcrumbArray", $breadcrumb);


$compte_rendu=getCompteRenduCpn($idCpn);
if(is_array($compte_rendu)){
    $smarty->assign('idCpn', $idCpn);
    $smarty->assign('idCompteRendu', $compte_rendu['id']);
    $smarty->assign('description', $compte_rendu['description']);
}

#Traitement de l'enregistrement
if(isset($save)){
    array_map('addslashes', $_POST);
    extract($_POST);

    if(isset($id) && !empty($id)){
        $modify_id=$userPID;
        $modify_time=date('Y-m-d H:i:s');
        $userName=addslashes($userName);
        $history="CONCAT('history','; ', 'Mise à jour par $userName le $Time')";
    }else{
        $create_id=$userPID;
        $create_time=date('Y-m-d H:i:s');
        $history="Créer par ".$userName." le ".$Time;
    }
    
    $tab=array(
        'id_cpn'=>$id_cpn,
        'description'=>addslashes($description),
        'valide_status'=>'O',
        'create_id'=>$create_id,
        'create_time'=>$create_time,
        'modify_id'=>$modify_id,
        'modify_time'=>$modify_time,
        'history'=>$history
    );

    if($objMedocsParams->insertFromArray('care_medocs_cpn_compte_rendu',$tab,"id",@$id)){
        $msg="La sauvegarde a été effectuée avec succès";
        $msgType="success";
        unset($tab);unset($_POST); unset($save);
        header("Location:".$baseLink."&cpn=compte_rendu_cpn");
    }else{
        $msg="Sauvegarde non effectuée";
        $msgType="danger";

    }

}


$smarty->assign('cpnTpl','medocs_compte_rendu_cpn.tpl');
?>