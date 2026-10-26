<?php  
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

        $("#vaccin").select2();


        $("#date_effectuee").datetimepicker({
            format:'dd-mm-yyyy',
            autoclose:true,
            minView:2
        });

    }); 
</script>
<?php
$js=ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript', $js);

#Breadcrumb
$breadcrumb=array("DOSSIER MEDICAL","VACCINATION ADULTE");
$smarty->assign("breadcrumbArray", $breadcrumb);

#Recuperation des infos d'une vaccination
if(isset($option)&&!empty($option)){
    $vac_pid=$_GET['vac_pid'];
    switch ($option) {
        case 'update':
            $sqlUpdate="SELECT * FROM care_vaccination_patient WHERE vac_pid=".$vac_pid;
            if($result=$db->Execute($sqlUpdate)){
                $rs=$result->FetchRow();
                $vid=$rs['vid'];//l'id du vaccin
                $date_effectuee=parseDate($rs['date_effectuee'],'Y-M-D','-','', 'D-M-Y','-');
            }
            break;
        case 'delete':
               
            $tab=array(
                'status'=>'N',
                'history'=>"CONCAT(history,';','Supprimer le ".date('Y-m-d H:i:s')." par ".addslashes($userName)."')",
            );
            if($objMedocsParams->insertFromArray('care_vaccination_patient', $tab, 'vac_pid', $vac_pid)){
                $msgType="success";
                $msg="La vaccination a été supprimée avec succès.";
                unset($option);
            }else{
                $msgType="danger";
                $msg="La vaccination n'a pas été supprimée";
            }
            break;
        default:
            # code...
            break;
    }
}

#Traitement des données
if(isset($enregistrer)){
    if(!isset($option) && empty($option)){
        $vaccin=$_POST['vaccin'];
        $date_effectuee=parseDate($_POST['date_effectuee'],'Y-M-D','-','', 'D-M-Y','-');
        $tab=array(
            'vid'=>$vaccin,
            'pid'=>$pid,
            'date_effectuee'=>$date_effectuee,
            'status'=>'O',
            'create_id'=>$userPID,
            'create_time'=>date('Y-m-d H:i:s'),
            'history'=>"Créer le ".date('Y-m-d H:i:s')." par ".addslashes($userName),
        );
       
        if($objMedocsParams->insertFromArray('care_vaccination_patient', $tab, 'vac_pid')){
            $msgType="success";
            $msg="La vaccination a été enregistrée avec succès.";
        }else{
            $msgType="danger";
            $msg="La vaccination n'a pas été enregistrée";
        }

    }else{
        if(!empty($option)){
            $vac_pid=$_GET['vac_pid'];
            switch ($option) {
                case 'update':
                    $vaccin=$_POST['vaccin'];
                    $date_effectuee=parseDate($_POST['date_effectuee'],'Y-M-D','-','', 'D-M-Y','-');
                    $tab=array(
                        'vid'=>$vaccin,
                        'pid'=>$pid,
                        'date_effectuee'=>$date_effectuee,
                        'modify_id'=>$userPID,
                        'modify_time'=>date('Y-m-d H:i:s'),
                        'history'=>"CONCAT(history,';','Modifier le ".date('Y-m-d H:i:s')." par ".addslashes($userName)."')",
                    );
                    $date_effectuee=$_POST['date_effectuee'];//ceci pour bien afficher dans le champ après insertion
                    if($objMedocsParams->insertFromArray('care_vaccination_patient', $tab, 'vac_pid', $vac_pid)){
                        $msgType="success";
                        $msg="La vaccination a été mise à jour avec succès.";
                    }else{
                        $msgType="danger";
                        $msg="La vaccination n'a pas été mise à jour";
                    }
                    break;
               
                default:
                    # code...
                    break;
            }
        }

    }
}

#Affichage de message de confirmation à l'utilisateur
if(isset($msg)&&!empty($msg)){
    $msgArray=array('type'=>$msgType, 'msg'=>$msg);
    $smarty->assign('msgArray', $msgArray);
}

#Remplissage par défaut de la date
if(!isset($date_effectuee)) $date_effectuee=date('d-m-Y');
$smarty->assign("date_effectuee", $date_effectuee);

#Je rempli la liste des vaccins
$sqlVaccins="SELECT i.item_code as code, i.item_description as libelle FROM care_billing_item i, care_billing_item_type t WHERE i.item_type=t.item_type_code AND i.item_type='V' ORDER BY i.item_description";
$resultVaccins=$db->execute($sqlVaccins);
$options="";
if(is_object($resultVaccins)){
    ob_start();
        echo '<option value="">Sélectionnez un vaccin</option>';
        while($ligne=$resultVaccins->FetchRow()){
            $libelle=explode(":", $ligne['libelle']);
            $nom=(is_array($libelle) && $libelle[1]!="")?$libelle[1]:$ligne['libelle'];
            $selected=(isset($vid)&&!empty($vid)&&$vid==$ligne['code'])?'selected=selected':'';
            echo '<option value="'.$ligne['code'].'" '.$selected.'>'.$nom.'</option>';
        }
        $options=ob_get_contents();
    ob_end_clean();

    $smarty->assign('options', $options);
}

#J'affiche la liste des vaccinations effectuées par ce patient
$sql="SELECT i.item_description as libelle, v.* FROM care_vaccination_patient v, care_billing_item i WHERE v.vid=i.item_code AND v.pid=".$pid." AND v.type='generale' AND v.status='O'";
$result=$db->Execute($sql);
if(is_object($result)){
    ob_start();
    $i=0;
    while($ligne=$result->FetchRow()){
        $i++;
        $libelle=explode(":", $ligne['libelle']);
        $nom=(is_array($libelle) && $libelle[1]!="")?$libelle[1]:$ligne['libelle'];
        $options='<a href="'.$baseLink.'&target=vaccination_adulte&option=update&vac_pid='.$ligne['vac_pid'].'" class="btn btn-xs btn-success" style="color:#fff" title="Editer"><span class="glyphicon glyphicon-edit"></span></a>
            <a href="'.$baseLink.'&target=vaccination_adulte&option=delete&vac_pid='.$ligne['vac_pid'].'" class="btn btn-xs btn-danger" style="color:#fff" title="Supprimer" onclick="return confirm(\'Etes vous sur ?\');"><span class="glyphicon glyphicon-trash"></span></a>';
        echo '<tr>';
            echo '<td class="text-right">'.$i.'</td>';
            echo '<td>'.$nom.'</td>';
            echo '<td class="text-center">'.parseDate($ligne['date_effectuee'],'Y-M-D','-','', 'D-M-Y','-').'</td>';
            echo '<td class="text-center">'.$options.'</td>';
        echo '</tr>';
    }
    $listing=ob_get_contents();
    ob_end_clean();
    $smarty->assign('listing', $listing);
}

$smarty->assign('sMainBlockIncludeFile','medocs/medocs_vaccination_adulte.tpl');
?>