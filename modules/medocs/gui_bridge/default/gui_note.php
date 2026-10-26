<?php  
ob_start();
    require_once($root_path.'language/fr/lang_fr_aufnahme.php');
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

        $("#type_nr").select2();


        $("#date1").datetimepicker({
            format:'dd-mm-yyyy',
            autoclose:true,
            minView:2
        });


        $("#time").datetimepicker({
            format:'hh:ii',
            autoclose:true,
            minView:2
        });

        //pour la date et heure du bon de sortie
        $("#date").datetimepicker({
            format:'dd-mm-yyyy hh:ii',
            autoclose: true,
            todayBtn: true,
            minuteStep:1
        });

    }); 

    function validationForm(){
        if($("#type_nr").val==''){
            alert('Veuillez selectionner un type');
            $("#type_nr").focus();
            return false
        }else if($("#notes").val()==''){
            alert('Veuillez saisir une note');
            $("#notes").focus();
            return false
        }else{
            return true
        }
    }
</script>
<?php
$js=ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript', $js);

#Breadcrumb
$breadcrumb=array("DOSSIER MEDICAL","NOTE D'HOSPITALISATION");
$smarty->assign("breadcrumbArray", $breadcrumb);

$encounter_nr=$objEncounter->isPIDCurrentlyAdmitted($pid);

#Recuperation des infos d'une notes
if(isset($option)&&!empty($option)){
    $nr=$_GET['nr'];
    switch ($option) {
        case 'update':
            $sqlUpdate="SELECT * FROM care_encounter_notes WHERE nr=".$nr;
            if($result=$db->Execute($sqlUpdate)){
                $rs=$result->FetchRow();
                $type_nr=$rs['type_nr'];//l'id du type
                $date=parseDate($rs['date'],'Y-M-D','-','', 'D-M-Y','-',true);
                $time=explode(':',$rs['time']);
                $date=$date.' '.$time[0].':'.$time[1];
                $notes=$rs['notes'];
                $smarty->assign('notes', $notes);
            }
            break;
        case 'delete':
               
            $tab=array(
                'status'=>'N',
                'history'=>"CONCAT(history,';','Supprimer le ".date('Y-m-d H:i:s')." par ".addslashes($userName)."')",
            );
           // var_dump($nr);die();
            if($objMedocsParams->insertFromArray('care_encounter_notes', $tab, 'nr', $nr)){
                $msgType="success";
                $msg="La note a été supprimée avec succès.";
                unset($option);
            }else{
                $msgType="danger";
                $msg="La note n'a pas été supprimée";
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
        $type_nr=$_POST['type_nr'];
        $date=parseDate($_POST['date'],'Y-M-D','-','', 'D-M-Y','-',true);
        $tab=array(
            'type_nr'=>$type_nr,
            'encounter_nr'=>$encounter_nr,
            'notes'=>addslashes($_POST['notes']),
            'date'=>explode(' ',$date)[0],
            'time'=>explode(' ',$date)[1],
            'personell_nr'=>$userPID,
            'status'=>'O',
            'create_id'=>$userPID,
            'create_time'=>date('Y-m-d H:i:s'),
            'history'=>"Créer le ".date('Y-m-d H:i:s')." par ".addslashes($userName),
        );
       
        if($objMedocsParams->insertFromArray('care_encounter_notes', $tab, 'nr')){
            $msgType="success";
            $msg="La note a été enregistrée avec succès.";
            unset($nr);
        }else{
            $msgType="danger";
            $msg="La note n'a pas été enregistrée";
        }

    }else{
        if(!empty($option)){
            $nr=$_GET['nr'];
            switch ($option) {
                case 'update':
                    $type_nr=$_POST['type_nr'];
                    $date=parseDate($_POST['date'],'Y-M-D','-','', 'D-M-Y','-',true);
                    $tab=array(
                        'type_nr'=>$type_nr,
                        'encounter_nr'=>$encounter_nr,
                        'notes'=>addslashes($_POST['notes']),
                        'date'=>explode(' ',$date)[0],
                        'time'=>explode(' ',$date)[1],
                        'personell_nr'=>$userPID,
                        'modify_id'=>$userPID,
                        'modify_time'=>date('Y-m-d H:i:s'),
                        'history'=>"CONCAT(history,';','Modifier le ".date('Y-m-d H:i:s')." par ".addslashes($userName)."')",
                    );
                    $date_effectuee=$_POST['date'];//ceci pour bien afficher dans le champ après insertion
                    if($objMedocsParams->insertFromArray('care_encounter_notes', $tab, 'nr', $nr)){
                        $msgType="success";
                        $msg="La note a été mise à jour avec succès.";
                    }else{
                        $msgType="danger";
                        $msg="La note n'a pas été mise à jour";
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
if(!isset($date)) $date=date('d-m-Y H:i');
$smarty->assign("date", $date);

#Je rempli la liste des types
$sqlType="SELECT * FROM care_type_notes WHERE status IN('','O') ORDER BY sort_nr ";
$resultType=$db->execute($sqlType);
$options="";
if(is_object($resultType)){
    ob_start();
        echo '<option value="">Sélectionnez un type</option>';
        while($ligne=$resultType->FetchRow()){
            $type=!empty($$ligne['LD_var'])?$$ligne['LD_var']:$ligne['LD_var'];
            $selected=(isset($type_nr)&&!empty($type_nr)&&$type_nr==$ligne['nr'])?'selected=selected':'';
            echo '<option value="'.$ligne['nr'].'" '.$selected.'>'.$type.'</option>';
        }
        $options=ob_get_contents();
    ob_end_clean();

    $smarty->assign('options', $options);
}

#J'affiche la liste des vaccinations effectuées par ce patient
$sql="SELECT * FROM care_encounter_notes n, care_type_notes t 
        WHERE t.nr=n.type_nr AND n.status IN('','O') AND n.encounter_nr=".$encounter_nr." ORDER BY date DESC, time DESC";
$result=$db->Execute($sql);
if(is_object($result)){
    ob_start();
    $i=0;
    while($ligne=$result->FetchRow()){
        $i++;
       // var_dump($ligne);die();
        $type=$$ligne['LD_var'];
        $options='<a href="'.$baseLink.'&target=note&option=update&nr='.$ligne['0'].'" class="btn btn-xs btn-success" style="color:#fff" title="Editer"><span class="glyphicon glyphicon-edit"></span></a>
            <a href="'.$baseLink.'&target=note&option=delete&nr='.$ligne['0'].'" class="btn btn-xs btn-danger" style="color:#fff" title="Supprimer" onclick="return confirm(\'Etes vous sur ?\');"><span class="glyphicon glyphicon-trash"></span></a>';
        echo '<tr>';
            echo '<td class="text-right">'.$i.'</td>';
            echo '<td>'.$type.'</td>';
            echo '<td class="">'.$ligne['notes'].'</td>';
            echo '<td class="text-center">'.parseDate($ligne['date'],'Y-M-D','-','', 'D-M-Y','-').' à '.$ligne['time'].'</td>';
            echo '<td class="text-center">'.$options.'</td>';
        echo '</tr>';
    }
    $listing=ob_get_contents();
    ob_end_clean();
    $smarty->assign('listing', $listing);
    //var_dump('lolo');
}/*else{

    $smarty->assign('listing', '<tr><td colspan="5" class="text-center">Aucune note pour le moment !</td></tr>');
}*/

$smarty->assign('sMainBlockIncludeFile','medocs/medocs_note.tpl');
?>