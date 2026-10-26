<?php
$db->Execute("SET NAMES UTF-8");

#CSS et JS
ob_start();

/*echo '<link rel="stylesheet" href="'.$root_path.'plugins/datatable/media/datatables.min.css" type="text/css">';
echo '<link rel="stylesheet" href="'.$root_path.'js/jquery/jquery-ui-1.10.4.min.css" type="text/css">';

echo '<script type="text/javascript" src="'.$root_path.'plugins/datatable/media/datatables.min.js"></script>';
echo '<script type="text/javascript" src="'.$root_path.'js/jquery/jquery-ui-1.10.4.min.js"></script>';*/
?>

<style type="text/css">
    body{
        font-size: 9px;
    }
</style>
<!-- <link rel="stylesheet" href="<?php echo $root_path.'plugins/datetimepicker/css/bootstrap-datetimepicker.min.css';?>">
<script type="text/javascript" src="<?php echo $root_path.'plugins/datetimepicker/js/bootstrap-datetimepicker.min.js';?>"></script> -->
<script type="text/javascript" src='<?php echo $root_path."js/tinymce/tinymce.min.js";?>'></script>
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

 		$("#ddr").datetimepicker({
            format:'dd/mm/yyyy',
            autoclose:true,
            minView:2
        });
        $("#date_examen").datetimepicker({
            format:'dd/mm/yyyy',
            autoclose:true,
            minView:2
        });

        $("#date_service_machine").datetimepicker({
            format:'dd/mm/yyyy',
            autoclose:true,
            minView:2
        });
        alert('cool')

    });
   
</script>
<?php
$js=ob_get_contents();
ob_end_clean();


#Breadcrumb
$breadcrumb=array("DOSSIER MEDICAL", "ECHOGRAPHIE","COMPTE RENDU D'ECHOGRAPHIE PELVIENNE");


$smarty->assign('urlAction', $baseLink.'&rapport=echo_pelvienne');
$smarty->assign('urlListe', $baseLink.'&rapport=echo_pelvienne&option=liste');

# Assignation des inputs



# Liste des echographie déja réalisées
if(isset($option) && !empty($option)){
    switch ($option) {
       case 'liste':
           # Liste de tous les echos réalisées      
            $smarty->assign('listeEchographie', true);
            $sql="SELECT * FROM care_medocs_echographie_pelvienne WHERE valide_status='O' AND pid=".$pid;
            $resultListe=$db->Execute($sql);
            if(is_object($resultListe)){
                ob_start();
                $i=0;
                while($ligne=$resultListe->FetchRow()){
                    $i++;
                    $actions='<a href="'.$baseLink.'&rapport=echo_pelvienne&option=edit&id='.$ligne['id'].'" class="btn-xs btn-success" style="color:white" title="Editer"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;<a href="'.$root_path.'modules/medocs/print_rapport_echographie_obstetricale.php'.URL_APPEND.'&id='.$ligne['id'].'" class="btn-xs btn-primary" style="color:white" title="Imprimer" target="_blank"><span class="glyphicon glyphicon-print"></span></a>&nbsp;<a href="'.$baseLink.'&rapport=echo_pelvienne&option=print&id='.$ligne['id'].'" class="btn-xs btn-danger" style="color:white" title="Supprimer"><span class="glyphicon glyphicon-trash"></span></a>';
                    echo '<tr>';
                        echo '<td class="text-right">'.$i.'</td>';
                        echo '<td class="text-center">'.parseDate($ligne['date_examen'],'Y-M-D','-','','D/M/Y','/').'</td>';
                        echo '<td>'.$ligne['prescripteur'].'</td>';
                        echo '<td>'.$objPerson->getValue('name_last', $ligne['create_id']).' '.$objPerson->getValue('name_first', $ligne['create_id']).'</td>';
                        echo '<td  class="text-center">'.$actions.'</td>';
                    echo '</tr>';
                }
                $listing=ob_get_contents();
                ob_clean();
                $smarty->assign('listing', $listing);
            }     

            break;
       case 'edit':
            # Mise à jour des données d'une echo
            $id=$_GET['id'];
            $sql="SELECT * FROM care_medocs_echographie_pelvienne WHERE id=".$id;
            if($result=$db->Execute($sql)){
                $rs=$result->getArray()[0];
                /*echo '<pre>';
                var_dump($rs);
                echo '</pre>';
                die();*/
                foreach ($rs as $k => $v) {
                    if(is_string($k)){
                        if($k=="date_service_machine"){

                            if($v=="0000-00-00"){
                            }else $smarty->assign($k, parseDate($v,'Y-M-D','-','','D/M/Y','/',''));
                            
                        }elseif($k=="ddr"){

                            if($v=="0000-00-00"){
                            }else $smarty->assign($k, parseDate($v,'Y-M-D','-','','D/M/Y','/',''));

                        }elseif($k=="ddg_theorique"){

                            if($v=="0000-00-00"){
                            }else $smarty->assign($k, parseDate($v,'Y-M-D','-','','D/M/Y','/',''));

                        }elseif($k=="ddg_theorique"){

                            if($v=="0000-00-00"){
                            }else $smarty->assign($k, parseDate($v,'Y-M-D','-','','D/M/Y','/',''));

                        }elseif($k=="ddg_echographique"){

                            if($v=="0000-00-00"){
                            }else $smarty->assign($k, parseDate($v,'Y-M-D','-','','D/M/Y','/',''));

                        }elseif($k=="date_examen"){

                            if($v=="0000-00-00"){
                            }else $smarty->assign($k, parseDate($v,'Y-M-D','-','','D/M/Y','/',''));

                        }else{

                            $smarty->assign($k, $v);
                        }
                       
                    }    

                }
                //die();
            }

            break;
       case 'delete':
            # Suppression d'un compte rendu

            break; 
       default:
           # code...
           break;
    }

}else{
    $smarty->assign('listeEchographie', false);
}

# Select du Groupe Sanguin
$tabGroupe=array('A+'=>'A+','A-'=>'A-',  'AB+'=>'AB+', 'AB-'=>'AB-', 'B+'=>'B+','B-'=>'B-','O+'=>'O+', 'O-'=>'O-',);
ob_start();
echo '<option value="">Veuillez selectionner le groupe sanguin</option>';
foreach ($tabGroupe as $v) {
     echo '<option value="'.$v.'">'.$v.'</option>';
}
$optionsGroupeSanguin=ob_get_contents();
ob_clean();
$smarty->assign('optionsGroupeSanguin', $optionsGroupeSanguin);

# Assignation des informations des patients
$smarty->assign('name_last', $nom);
$smarty->assign('name_first', $prenoms);
$smarty->assign('age', $age);


# Enregistrement du rapport 
if(isset($save)&&$save="Enregistrer"){
    
    $_POST = array_map('addslashes',$_POST);

    $tab=$_POST;
    $tab['date_service_machine']=parseDate($_POST['date_service_machine'],'D/M/Y','/','','Y-M-D','-');
    $tab['ddr']=parseDate($_POST['ddr'],'D/M/Y','/','','Y-M-D','-');
    $tab['ddg_theorique']=parseDate($_POST['ddg_theorique'],'D/M/Y','/','','Y-M-D','-');
    $tab['ddg_echographique']=parseDate($_POST['ddg_echographique'],'D/M/Y','/','','Y-M-D','-');
    $tab['date_examen']=parseDate($_POST['date_examen'],'D/M/Y','/','','Y-M-D','-');

    //respect des retour à la ligne et les espaces
    $tab['annexes']=nl2br($tab['annexes']);
    $tab['doppler_ombilical']=nl2br($tab['doppler_ombilical']);
    $tab['artere_cerebrale']=nl2br($tab['artere_cerebrale']);
    $tab['ductus_venosus']=nl2br($tab['ductus_venosus']);
    $tab['col']=nl2br($tab['col']);
    $tab['commentaires']=nl2br($tab['commentaires']);

    unset($tab['save']);

    /*echo '<pre>';
    var_dump($tab);
    echo '</pre>';
    die();*/
    if(isset($_POST['id'])&&!empty($_POST['id'])){
        $id=$_POST['id'];

        $tab['modify_id']=$userPID;
        $tab['modify_time']=$Time;
        $tab['history']="CONCAT(history, '; Modifier par ".$userName." le ".$Time."')";
    }else{
        $id="";
        $tab['create_id']=$userPID;
        $tab['create_time']=$Time;
        $tab['history']="Creer Par ".$userName." le ".$Time;
    }
    
    /*var_dump($dbCore->insertFromArray('care_medocs_echographie_obstetricale', $tab, 'id', @$id));
    var_dump($dbCore->sql);
    die();*/

    if($dbCore->insertFromArray('care_medocs_echographie_obstetricale', $tab, 'id', @$id)){
        $msgArray=array('type'=>'success', 'msg'=>'Rapport enregistré avec succès !');
    }
}



#Affichage du message de confirmation
if(isset($msg) && $msg!=""){
    $msgArray=array('type'=>$msgType, 'msg'=>$msg);
    $smarty->assign('msgArray',$msgArray);
}

$smarty->assign('registreTpl','medocs_'.$rapport.'.tpl');
?>

