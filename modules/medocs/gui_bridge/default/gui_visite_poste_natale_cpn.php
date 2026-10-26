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
    .input-xs{
        height: 22px;
        font-size: 10px;
    }
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
$breadcrumb=array("DOSSIER MEDICAL","CONSULTATION PRE-NATALE", "VISITE POSTE NATALE");
$smarty->assign("breadcrumbArray", $breadcrumb);

#Si il y a une donnée on fait l'update
$visite=getVisiteCpn($idCpn);
if(is_array($visite)){
    
    $smarty->assign('idVisite', $visite['id']);  
    $smarty->assign('temps_accouchement', $visite['temps_accouchement']);
    $smarty->assign('date_couche', parseDate($visite['date_couche'], 'Y-M-D','-','','D/M/Y','/'));
    $smarty->assign('retour_couche', $visite['retour_couche']);
    $smarty->assign('modalite_couche', $visite['modalite_couche']);
    $smarty->assign('date_visite', parseDate($visite['date_visite'], 'Y-M-D','-','','D/M/Y','/'));
    $smarty->assign('allaitement', $visite['allaitement']);
    $smarty->assign('examen', $visite['examen']);
    $smarty->assign('particularite', $visite['particularite']);
    $smarty->assign('contraception', $visite['contraception']);

}
#Traitement de l'enregistrement et de la mise à jour
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
        'id_cpn'=>$idCpn,
        'temps_accouchement'=>$temps_accouchement,
        'date_visite'=>parseDate($date_visite, 'D/M/Y','/','','Y-M-D','-'),
        'temps_accouchement'=>$temps_accouchement,
        'retour_couche'=>$retour_couche,
        'date_couche'=>parseDate($date_couche, 'D/M/Y','/','','Y-M-D','-'),
        'modalite_couche'=>$modalite_couche,
        'allaitement'=>$allaitement,
        'examen'=>$examen,
        'particularite'=>$particularite,
        'contraception'=>$contraception,
        'valide_status'=>'O',
        'create_id'=>$create_id,
        'create_time'=>$create_time,
        'modify_id'=>$modify_id,
        'modify_time'=>$modify_time,
        'history'=>$history
    );

   /* debug($id);
    debug($tab);die();*/

    if($objMedocsParams->insertFromArray('care_medocs_cpn_visite_poste_natale',$tab,"id",@$id)){
        $msg="La sauvegarde a été effectuée avec succès";
        $msgType="success";
        //header("Location:".$baseLink.URL_APPEND."&cpn=declaration_grossesse");
        unset($tab);unset($_POST);
    }else{
        $msg="Sauvegarde non effectuée";
        $msgType="danger";
    }

}

#Affichage de message de confirmation à l'utilisateur
if(isset($msg)&&!empty($msg)){
    $msgArray=array('type'=>$msgType, 'msg'=>$msg);
    $smarty->assign('msgArray', $msgArray);
}

$smarty->assign('cpnTpl','medocs_visite_poste_natale_cpn.tpl');
?>