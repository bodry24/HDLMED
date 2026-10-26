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
       
        $("input[name='datenouveau_ne']").datetimepicker({
            format:'dd/mm/yyyy hh:ii',
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
$breadcrumb=array("DOSSIER MEDICAL","CONSULTATION PRE-NATALE", "NOUVEAU NE");
$smarty->assign("breadcrumbArray", $breadcrumb);


#Si il y a une donnée on fait l'update
$nouveau=getArrayCpn("care_medocs_cpn_nouveau_ne",$idCpn);
if(is_array($nouveau)){
 
    $smarty->assign('idNouveauNe', $nouveau['id']);  
    $smarty->assign('date_nouveau_ne', parseDate($nouveau['date_nouveau_ne'], 'Y-M-D','-','','D/M/Y','/', true));
    //var_dump(parseDate($nouveau['date_nouveau_ne'], 'Y-M-D','-','','D/M/Y','/', true));die();
    $smarty->assign('sexe', $nouveau['sexe']);
    $smarty->assign('age', $nouveau['age']);
    $smarty->assign('score_maturite',$nouveau['score_maturite']);
    $smarty->assign('situation_naissance',$nouveau['situation_naissance']);
    $smarty->assign('poids',$nouveau['poids']);
    $smarty->assign('taille',$nouveau['taille']);
    $smarty->assign('perimetre_cranien',$nouveau['perimetre_cranien']);
    $smarty->assign('perimetre_thoracique',$nouveau['perimetre_thoracique']);
    $smarty->assign('score_apgar',$nouveau['score_apgar']);
    $smarty->assign('reanimation',$nouveau['reanimation']);
    $smarty->assign('modalite',$nouveau['modalite']);
    $smarty->assign('malformation',$nouveau['malformation']);
    $smarty->assign('infection',$nouveau['infection']);
    $smarty->assign('autre',$nouveau['autre']);
    $smarty->assign('date_examen', parseDate($nouveau['date_examen'], 'Y-M-D','-','','D/M/Y','/'));
    $smarty->assign('poids_examen', $nouveau['poids_examen']);
    $smarty->assign('etat_examen', $nouveau['etat_examen']);
    $smarty->assign('alimentation_examen', $nouveau['alimentation_examen']);
    $smarty->assign('consigne_examen', $nouveau['consigne_examen']);
    $smarty->assign('groupe', $nouveau['groupe']);
    $smarty->assign('rhesus', $nouveau['rhesus']);

  

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
        'id_cpn'=>$idCpn,
        'date_nouveau_ne'=>parseDate($datenouveau_ne, 'D/M/Y','/','','Y-M-D','-',true),
        'sexe'=>$sexe,
        'age'=>$age,
        'score_maturite'=>$score_maturite,
        'situation_naissance'=>$situation_naissance,
        'poids'=>$poids,
        'taille'=>$taille,
        'perimetre_cranien'=>$perimetre_cranien,
        'perimetre_thoracique'=>$perimetre_thoracique,
        'score_apgar'=>$score_apgar,
        'reanimation'=>$reanimation,
        'modalite'=>$modalite,
        'malformation'=>$malformation,
        'infection'=>$infection,
        'autre'=>$autre,
        'date_examen'=>parseDate($date_examen, 'D/M/Y','/','','Y-M-D','-'),
        'poids_examen'=>$poids_examen,
        'etat_examen'=>$etat_examen,
        'alimentation_examen'=>$alimentation_examen,
        'consigne_examen'=>$consigne_examen,
        'groupe'=>$groupe,
        'rhesus'=>$rhesus,
        'valide_status'=>'O',
        'create_id'=>$create_id,
        'create_time'=>$create_time,
        'modify_id'=>$modify_id,
        'modify_time'=>$modify_time,
        'history'=>$history
    );

   /* debug($id);
    debug($tab);die();*/

    if($objMedocsParams->insertFromArray('care_medocs_cpn_nouveau_ne',$tab,"id",@$id)){
        $msg="La sauvegarde a été effectuée avec succès";
        $msgType="success";
        unset($tab);unset($_POST);
        
        header("Location:".$baseLink."&cpn=nouveau_ne_cpn");
        
    }else{
        $msg="Sauvegarde non effectuée";
        $msgType="danger";
    }

}
$smarty->assign('cpnTpl','medocs_nouveau_ne_cpn.tpl');
?>