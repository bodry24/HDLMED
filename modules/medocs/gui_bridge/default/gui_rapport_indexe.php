<?php
require_once($root_path.'include/care_api_classes/class_person.php');
$objPerson=new Person;

require_once($root_path.'include/care_api_classes/class_medocs_params.php');
$objMedocsParams=new Medocs_params;

require_once($root_path.'include/core/inc_environment_global.php');
global $db;

#Information de l'utiilisateur connecté
$login=isset($_SESSION['sess_login_username'])?$_SESSION['sess_login_userid']:$_SESSION['sess_user_login_id'];

$userPID=$objPerson->getUserPid($login);
$userName=$objPerson->getValue('name_last',$userPID).' '.$objPerson->getValue('name_first',$userPID);
$Time=date('Y-m-d H:i:s');

ob_start();
?>
 <!--  Appel du select2 -->
<link rel="stylesheet" type="text/css" href="<?php echo $root_path.'plugins/select2/select2.css';?>">
<script type="text/javascript" src="<?php echo $root_path.'plugins/select2/select2.js';?>"></script>

<script type="text/javascript" src='<?php echo $root_path."js/tinymce/tinymce.min.js";?>'></script>
<style type="text/css">
    div.dataTables_wrapper div.dataTables_filter input {
        margin-left: 0.5em;
        display: inline-block;
        width: 130px;
    }
    .important{color : #ff0000;}
    .link { cursor: pointer;}
    .right{
        text-align: right;
    }
    .left{
        text-align: left;
    }
</style>
<script language="javascript">

    tinymce.init({
        selector: "textarea#body",
        theme: "modern",
        width: 450,
        height: 300,
        plugins: [
             "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
             "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
             "save table contextmenu directionality emoticons template paste textcolor"
       ],
       //content_css: "css/content.css",
       toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons", 
       style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ]
     });  

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

        $("#recipient").select2();

        $("#send_dt").datetimepicker({
            format:'dd/mm/yyyy hh:ii',
            autoclose: true,
            todayBtn: true,
            minuteStep:1
        });
    });       

    //verification du formulaire
    function verification() {
        
        
    }
    //Permet de consulter 
    function lireRapport(id){
        //alert(id)
        $.post('<?php echo $root_path."modules/ajax/ajaxMedocsMedocs.php".URL_APPEND;?>', 
            {target:'rapport', action:'lire', id:id}, 
            function(data){
               // alert(data);
                $("#contenu").empty();
                $("#contenu").append(data);
                $("#modalRapport").modal('show');
            })
       
    }
</script>
<?php
$js=ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript', $js);

#Breadcrumb
$breadcrumb=array("DOSSIER MEDICAL","RAPPORT INDEXE");
$smarty->assign("breadcrumbArray", $breadcrumb);


#Modification et suppression
if(isset($option)){
    switch ($option) {
        case 'modifier':
            $id=$_GET['id'];
            $sqlModifier="SELECT * FROM care_mail_private WHERE valide_status='O' AND id=".$id;
            $resultRapport=$db->Execute($sqlModifier);
            $rapport=$resultRapport->FetchRow();

            $smarty->assign('body', $rapport['body']);

            if($rapport['send_dt']!="0000-00-00 00:00:00" && !is_null($rapport['send_dt']) && $rapport['send_dt']!=""){
                $smarty->assign('send_dt', parseDate($rapport['send_dt'],'Y-M-D','-','','D/M/Y','/', true));
            }
            $smarty->assign('value', $rapport['id']);

            $medecin=$medecin['recipient'];
            break;
        case 'supprimer':
            $id=$_GET['id'];
            $tab= array(
                'valide_status'=>'N',
                'modify_id'=>$userPID,
                'modify_time'=>$Time,
                'history'=>"CONCAT(history, 'Modifier par ".$userName." le ".$Time."')",
            );
            
            if($objMedocsParams->insertFromArray('care_mail_private', $tab, 'id', $id)){
                //Message de confirmation
            }
            break;
        case 'consulter':
            $smarty->assign('consulter', true);
            $sqlListe="SELECT * FROM care_mail_private WHERE sender=".$userPID." OR recipient=".$userPID." AND valide_status='O' ORDER BY send_dt DESC";
            
            if($listeRapport=$db->Execute($sqlListe)){
                ob_start();
                    
                    while($ligne=$listeRapport->FetchRow()){
                        $pos=1;
                        if($ligne['send_dt']!="0000-00-00 00:00:00" && !is_null($ligne['send_dt']) && $ligne['send_dt']!=""){
                            $send_dt=parseDate($ligne['send_dt'],'Y-M-D','-','','D/M/Y','/', true);
                        }else $send_dt="";
                        $docteur=$objPerson->getValue('name_last',$ligne['sender']).' '.$objPerson->getValue('name_first', $ligne['sender']);
                        $patient=$objPerson->getValue('name_last',$ligne['pid']).' '.$objPerson->getValue('name_first', $ligne['pid']);
                        $statut=($ligne['lu']=='O')?'<span class="btn btn-xs btn-primary glyphicon glyphicon-ok"></span>':'';
                        $actions='<span class="glyphicon glyphicon-share-alt btn btn-xs btn-success" onclick="lireRapport('.$ligne['id'].')"></span></a>';
                        echo '<tr>';
                        echo '<td class="text-right">'.$pos.'</td>';
                        echo '<td class="text-left">'.$docteur.'</td>';
                        echo '<td class="text-left">'.$patient.'</td>';
                        echo '<td class="text-center">'.parseDate($ligne['send_dt'],'Y-M-D','-','','D/M/Y','/', true).' </td>';
                        echo '<td class="text-center">'.$statut.'</td>';
                        echo '<td class="text-center">'.$actions.'</td>';
                        echo '</tr>';
                        $pos++;
                    }
                $listing=ob_get_contents();
                ob_end_clean();
                $smarty->assign("listingRapport",$listing);
            }

            break;
        default:    
            
            break;
    }
}


# Pour remplir le select des medecins
ob_start();
    $list_med=$objPerson->getListAtPost(17);
    echo '<option value="">Sélectionnez le medécin</option>';
    if(is_object($list_med)){
        while($ligne=$list_med->FetchRow()){
            if((isset($medecin)) && ($medecin==$ligne['pid'])) $selected='selected="selected"';
            else $selected='';
            echo '<option value='.$ligne['pid'].' '.$selected.'>'.$ligne['name_last'].' '.$ligne['name_first'].'</option>';
        }
    }
    $list_doctor=ob_get_contents();
ob_end_clean();
$smarty->assign('recipientOptions', $list_doctor);

#Traitement du formulaire
if(isset($save) && $save=="Enrégistrer" && !empty($_POST)){
    /* echo '<pre>';
    var_dump($_POST);
    echo '</pre>';
    die();
    */
    $tab= array(
        'recipient'=>$recipient,
        'send_dt'=>parseDate($send_dt, 'D/M/Y','/','','Y-M-D','-', true),
        'body'=>nl2br(addslashes($body)),
        'sender'=>$userPID,  
    );
    

    if(isset($_POST['id']) && !empty($_POST['id'])){
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
    /*echo '<pre>';
    var_dump($tab);
    echo '</pre>';die();*/
    if($objMedocsParams->insertFromArray('care_mail_private', $tab, 'id', $id)){
        unset($_POST);

        header('Location:'.$root_path.'modules/medocs/medocs.php'.URL_APPEND.'&target=rapport_indexe');
       //Message de confirmation      
    }

}

#Liste des rapports de la clinique

$sqlListe="SELECT * FROM care_mail_private WHERE sender=".$userPID." AND valide_status='O' ORDER By send_dt DESC";
if($listeRapport=$db->Execute($sqlListe)){
    ob_start();
        $pos=1;
        if($ligne['send_dt']!="0000-00-00 00:00:00" && !is_null($ligne['send_dt']) && $ligne['send_dt']!=""){
            $send_dt=parseDate($ligne['send_dt'],'Y-M-D','-','','D/M/Y','/', true);
        }else $send_dt="";
        $docteur=$objPerson->getValue('name_last',$ligne['recipient']).' '.$objPerson->getValue('name_first', $ligne['recipient']);
        while($ligne=$listeRapport->FetchRow()){
            $actions='<a href="'.$root_path.'modules/medocs/medocs.php'.URL_APPEND.'&target=rapport_indexe&option=modifier&id='.$ligne['id'].'" class="btn btn-xs btn-success" title="Modifier" style="color:white;"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;<a href="'.$root_path.'modules/medocs/medocs.php'.URL_APPEND.'&target=rapport_indexe&option=supprimer&id='.$ligne['id'].'" class="btn btn-xs btn-danger" title="Supprimer" style="color:white;" onclick="return confirm(\'Etes vous sur?\')"><span class="glyphicon glyphicon-trash"></span></a>';
            echo '<tr>';
            echo '<td class="text-right">'.$pos.'</td>';
            echo '<td class="text-left">'.$docteur.'</td>';
            echo '<td class="text-center">'.parseDate($ligne['send_dt'],'Y-M-D','-','','D/M/Y','/', true).' </td>';
            echo '<td class="text-center">'.$actions.'</td>';
            echo '</tr>';
            $pos++;
        }
    $listing=ob_get_contents();
    ob_end_clean();
    $smarty->assign("listing",$listing);
}

$smarty->assign('sMainBlockIncludeFile','medocs/medocs_rapport_indexe.tpl');

?>
