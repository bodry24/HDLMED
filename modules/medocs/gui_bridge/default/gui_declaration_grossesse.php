<?php

require_once($root_path.'include/care_api_classes/class_product.php');
$objProduct=new Product;

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
       
        /*$("#date_derniere_regle").datetimepicker({
            format:'dd/mm/yyyy',
            autoclose:true,
            minView:2
        }); 
        $("#date_fecondation").datetimepicker({
            format:'dd/mm/yyyy',
            autoclose:true,
            minView:2
        });  
        $("#datation_echo").datetimepicker({
            format:'dd/mm/yyyy',
            autoclose:true,
            minView:2
        }); 
        $("#date_echo").datetimepicker({
            format:'dd/mm/yyyy',
            autoclose:true,
            minView:2
        }); 
        $("#date_accouchement").datetimepicker({
            format:'dd/mm/yyyy',
            autoclose:true,
            minView:2
        });*/

        


    }); 
</script>
<?php
$js=ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript', $js);


#Breadcrumb
$breadcrumb=array("DOSSIER MEDICAL","CONSULTATION PRE-NATALE", "DECLARATION DE GROSSESSE");
$smarty->assign("breadcrumbArray", $breadcrumb);

#Préparation de la mise à jour des données
if(isset($option)&&!empty($option)){
    if(isset($id)&&!empty($id)){
        switch ($option) {
            case 'update':
                
                $sqlUpdate="SELECT * FROM care_medocs_cpn_declaration WHERE id=".$id;
                $resultUpdate=$db->Execute($sqlUpdate);
                $rsUpdate=$resultUpdate->FetchRow();
                //debug($rsUpdate);die();
                $smarty->assign('id', $rsUpdate['id']);
                $smarty->assign('pid', $rsUpdate['pid']);
                $smarty->assign('date_declaration',parseDate($rsUpdate['date_declaration'],'Y-M-D','-','', 'D/M/Y','/'));
                $smarty->assign('date_derniere_regle',parseDate($rsUpdate['date_derniere_regle'], 'Y-M-D','-','', 'D/M/Y','/'));
                $smarty->assign('date_datation_echo',parseDate($rsUpdate['date_datation_echo'], 'Y-M-D','-','', 'D/M/Y','/'));
                $smarty->assign('date_derniere_regle',parseDate($rsUpdate['date_derniere_regle'], 'Y-M-D','-','', 'D/M/Y','/'));
                $smarty->assign('date_accouchement',parseDate($rsUpdate['date_accouchement'], 'Y-M-D','-','', 'D/M/Y','/'));
                $smarty->assign('date_fecondation',parseDate($rsUpdate['date_fecondation'], 'Y-M-D','-','', 'D/M/Y','/'));
                $smarty->assign('date_echo',parseDate($rsUpdate['date_echo'], 'Y-M-D','-','', 'D/M/Y','/'));
                $smarty->assign('lieu_echo',$rsUpdate['lieu_echo']);
                $smarty->assign('parite',$rsUpdate['parite']);
                $smarty->assign('poids_avant_grossesse',$rsUpdate['poids_avant_grossesse']);
                $smarty->assign('taille',$rsUpdate['taille']);
                $smarty->assign('bassin',$rsUpdate['bassin']);
                $smarty->assign('contraception',$rsUpdate['contraception']);
                $smarty->assign('algie',$rsUpdate['algie']);
                $smarty->assign('infection',$rsUpdate['infection']);
                $smarty->assign( 'groupe_conjoint',$rsUpdate['groupe_conjoint']);
                $smarty->assign('rhesus_conjoint',$rsUpdate['rhesus_conjoint']);
                $smarty->assign('elctrophorese_conjoint',$rsUpdate['elctrophorese_conjoint']);
                break;
            case 'delete':
                $sqlDelete="UPDATE care_medocs_cpn_declaration SET valide_status='N' WHERE id=".$id;
                if($db->Execute($sqlDelete)){
                    $msgType="success";
                    $msg="La suppression a été effectué avec succès";
                    unset($id);
                }else{
                    $msgType="success";
                    $msg="La suppression n'a pas marché, veuillez revoir les données ou contacter le service informatique";
                }

                break;
            case 'view':
                $_SESSION['idCPN']=$id;
                break;
            default:
                break;
        }
    }
 
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
        'pid'=>$pid,
        'date_declaration'=>parseDate($date_declaration, 'D/M/Y','/','','Y-M-D','-'),
        'date_derniere_regle'=>parseDate($date_derniere_regle, 'D/M/Y','/','','Y-M-D','-'),
        'date_datation_echo'=>parseDate($date_datation_echo, 'D/M/Y','/','','Y-M-D','-'),
        'date_derniere_regle'=>parseDate($date_derniere_regle, 'D/M/Y','/','','Y-M-D','-'),
        'date_accouchement'=>parseDate($date_accouchement, 'D/M/Y','/','','Y-M-D','-'),
        'date_fecondation'=>parseDate($date_fecondation, 'D/M/Y','/','','Y-M-D','-'),
        'date_echo'=>parseDate($date_echo, 'D/M/Y','/','','Y-M-D','-'),
        'lieu_echo'=>$lieu_echo,
        'parite'=>$parite,
        'poids_avant_grossesse'=>$poids_avant_grossesse,
        'taille'=>$taille,
        'bassin'=>$bassin,
        'contraception'=>$contraception,
        'algie'=>$algie,
        'infection'=>$infection,
        'groupe_conjoint'=>$groupe_conjoint,
        'rhesus_conjoint'=>$rhesus_conjoint,
        'elctrophorese_conjoint'=>$elctrophorese_conjoint,
        'valide_status'=>'O',
        'status'=>'O',
        'create_id'=>$create_id,
        'create_time'=>$create_time,
        'modify_id'=>$modify_id,
        'modify_time'=>$modify_time,
        'history'=>$history
    );

   /* debug($id);
    debug($tab);die();*/

    if($objMedocsParams->insertFromArray('care_medocs_cpn_declaration',$tab,"id",@$id)){
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

#Affichage des CPN
$sqlCpn="SELECT * FROM care_medocs_cpn_declaration WHERE pid=".$pid." AND valide_status='O' ORDER BY date_declaration DESC";
$resulCpn=$db->Execute($sqlCpn);
if(is_object($resulCpn)){
    ob_start();
        $i=0;
        while ($ligne=$resulCpn->FetchRow()) {
            $i++;
            $options='<a href="'.$baseLink.'&target=cpn&option=update&id='.$ligne['id'].'" class="btn btn-xs btn-success" style="color:#fff" title="Editer"><span class="glyphicon glyphicon-edit"></span></a>
            <a href="'.$baseLink.'&target=cpn&option=view&id='.$ligne['id'].'" class="btn btn-xs btn-primary" style="color:#fff" title="Consulter" onclick="return confirm(\'Voulez vous consulter cette CPN?\');"><span class="glyphicon glyphicon-share-alt"></span></a>
            <a href="'.$baseLink.'&target=cpn&option=delete&id='.$ligne['id'].'" class="btn btn-xs btn-danger" style="color:#fff" title="Supprimer" onclick="return confirm(\'Etes vous sur ?\');"><span class="glyphicon glyphicon-trash"></span></a>';
            echo '<tr>';
                echo '<td class="text-right">'.$i.'</td>';
                echo '<td class="text-center">'.parseDate($ligne['date_declaration'],'Y-M-D','-','','D/M/Y','/').'</td>';
                echo '<td class="text-right">'.$ligne['parite'].'</td>';
                echo '<td class="text-center">'.parseDate($ligne['date_derniere_regle'],'Y-M-D','-','','D/M/Y','/').'</td>';
                echo '<td class="text-right">'.$ligne['poids_avant_grossesse'].'</td>';
                echo '<td class="text-center">'.$options.'</td>';
            echo '</tr>';
        }
    $listing=ob_get_contents();
    ob_end_clean();
    $smarty->assign('listing', $listing);
}

$smarty->assign('cpnTpl','medocs_declaration_grossesse.tpl');

?>