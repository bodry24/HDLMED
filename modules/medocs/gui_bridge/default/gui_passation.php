<?php
require_once($root_path.'include/care_api_classes/class_person.php');
$objPerson=new Person;

require_once($root_path.'include/care_api_classes/class_encounter.php');
$objEncounter=new Encounter;

require_once($root_path.'include/care_api_classes/class_ward.php');
$objWard=new Ward();
    
require_once($root_path.'include/care_api_classes/class_medocs_params.php');
$objMedocsParams=new Medocs_params;

require_once($root_path.'include/core/inc_environment_global.php');

require_once($root_path.'modules/biasa_pdf/utils.php');
global $db;

#Information de l'utiilisateur connecté
$login=isset($_SESSION['sess_login_username'])?$_SESSION['sess_login_userid']:$_SESSION['sess_user_login_id'];

$userPID=$objPerson->getUserPid($login);
$userName=addslashes($objPerson->getValue('name_last',$userPID).' '.$objPerson->getValue('name_first',$userPID));
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
        width: 60px;
    }
    .important{color : #ff0000;}
    .link { cursor: pointer;}
    .right{
        text-align: right;
    }
    .left{
        text-align: left;
    }

    .list-group-item {
        position: relative;
        display: block;
        padding: 10px 15px;
        margin-bottom: -1px;
        background-color: #fff;
        border: 1px solid  #02327E;
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

        // Enregistrement de la passation
        $("#savePassation").click(function(){
            if($("#contenu").val==""){
                alert("Veuillez saisir le contenu de la passation !");
                $("#contenu").focus()
                return false;
            }else{
                var contenu=$("textarea#contenu").val()
                var encounter_nr=$("#modalEncounter").val()
                $.post('<?php echo $root_path."modules/ajax/ajaxMedocsMedocs.php".URL_APPEND;?>',
                    {target:"passation", action:"save", contenu:contenu, encounter_nr:encounter_nr},
                    function(data){
                        alert(data)
                    })
            }
        })
    });       

    // Ouverture du modal 
    function addPassation(id, titre){
        $("#modalEncounter").val(id)
        $("#modalTitre").html("NOUVELLE PASSATION :: " + titre)
        $.post('<?php echo $root_path."modules/ajax/ajaxMedocsMedocs.php".URL_APPEND;?>',
            {target:"passation", action:"liste", encounter_nr:id},
            function(data){
                $("#tablePassation").html(data)
            })
        $("#modalPassation").modal("show");
    }
   
</script>
<?php
$js=ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript', $js);

# Désactive les informations d'entete et le menu de droite
$smarty->assign("passationTemplate", true);
$smarty->assign("tabMenu", false);

# Assignation des titres
switch ($zone) {
    case 2:
        if($aile=="CHIRURGICAL"){
            $title="Hospitalisation Chirurgical";
            $activeMenuDroite="chirurgical";
        }else{
            $title="Hospitalisation Médical";
            $activeMenuDroite="medical";
        }
        
        break;
    case 3:
        $title="Pédiatrie";
        $activeMenuDroite="pediatrie";
        break;
    case 4:
        $title="Urgence";
        $activeMenuDroite="urgence";
        break;
    case 5:
        $title="Soins Intensifs";
        $activeMenuDroite="intensif";
        break;
    case 6:
        $title="Accouchement";
        $activeMenuDroite="accouchement";
        break;
    default:
        $title="Passation";
        break;
}
$breadcrumbArray=array(
    array("class"=>"", "url"=>"".$root_path."modules/medocs/medocs_dashboard.php".URL_APPEND, "titre"=>"Tableau de Bord"),
    array("class"=>"", "url"=>"", "titre"=>"Passation de Service"),
    array("class"=>"active", "url"=>"", "titre"=>$title),
    );
$smarty->assign("crumbArray",$breadcrumbArray);
# Affichage du message d'information s'il y en a
if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){
    $msgArray=array('msg'=>$_SESSION['msg'], 'type'=>$_SESSION['msgType']);
    $smarty->assign("msgArray", $msgArray);
    unset($_SESSION['msg']);
    unset($_SESSION['msgType']);
}
# Activer le menu actif de droite
$smarty->assign("activeMenuDroite",$activeMenuDroite);

# Lien de la page actif
if(isset($aile) && !empty($aile)){
    $urlActif=$root_path."modules/medocs/medocs.php".URL_APPEND."&target=passation&zone=".$zone."&aile=".$aile;
}else{
    $urlActif=$root_path."modules/medocs/medocs.php".URL_APPEND."&target=passation&zone=".$zone;
}

# Le menu de droite
$smarty->assign("urlChirurgical", $root_path."modules/medocs/medocs.php".URL_APPEND."&target=passation&zone=2&aile=CHIRURGICAL");
$smarty->assign("urlMedical", $root_path."modules/medocs/medocs.php".URL_APPEND."&target=passation&zone=2&aile=MEDICAL");
$smarty->assign("urlUrgence", $root_path."modules/medocs/medocs.php".URL_APPEND."&target=passation&zone=4");
$smarty->assign("urlAccouchement", $root_path."modules/medocs/medocs.php".URL_APPEND."&target=passation&zone=6");
$smarty->assign("urlSoinsIntensif", $root_path."modules/medocs/medocs.php".URL_APPEND."&target=passation&zone=5");
$smarty->assign("urlPediatrie", $root_path."modules/medocs/medocs.php".URL_APPEND."&target=passation&zone=3");

# Suppression d'une passation
if(isset($option) && !empty($option)){
    $id=$_GET['id'];
    switch ($option) {
        case 'delete':
            $sqlDelete="UPDATE care_passation SET valide_status='N', history=CONCAT(history, ';Supprimer par $userName le $Time'), modify_id=".$userPID.", modify_time='".$Time."' WHERE id=".$id;
            if($db->Execute($sqlDelete)){
                $_SESSION['msg']="Suppression exécutée avec succès !";
                $_SESSION['msgType']="success";
            }else{
                $_SESSION['msg']="Suppression non abouti !";
                $_SESSION['msgType']="danger";
            }
            header("Location:".$urlActif);
            break;
        case 'edit':
            $sqlUpdate="SELECT p.*, d.*, d.id as id_detail, pe.pid, pe.name_first, pe.name_last, pe.date_birth FROM care_passation p, care_passation_detail d, care_encounter e, care_person pe WHERE p.id=d.passation_id AND e.encounter_nr=d.encounter_nr AND e.pid=pe.pid AND p.valide_status='O' AND p.id=".$id;
           // var_dump($sqlUpdate);
            $resultUpdate=$db->Execute($sqlUpdate);
            if(is_object($resultUpdate)){
                ob_start();
                $i=0;
                while($ligne=$resultUpdate->FetchRow()){
                    $i++;
                    $encounter_nr=$ligne['encounter_nr'];
                    $encounterLocationInfo=$objWard->EncounterLocationsInfo($encounter_nr);
                    $room_nr=$encounterLocationInfo['room_nr'];
                    $bed_nr=$encounterLocationInfo['bed_nr'];  
                    $pidPatient=$ligne['pid'];
                    $nomPatient=empty($ligne['name_spouse'])?$ligne['name_last'].' '.$ligne['name_first']:$ligne['name_last'].' '.$ligne['name_first'].' épouse '.$ligne['name_spouse'];
                    $chambre=$objWard->getRoomNameByNr($room_nr);
                    $occupation=$chambre." Lit N° ".$bed_nr;
                    $age=implode('',calcuteAge($ligne['date_birth']));
                   
                    echo '<tr>';
                        echo '<td class="text-right">'.$i.'</td>';
                        echo '<td class="text-left">'.$occupation.'</td>';
                        echo '<td class="text-left">'.$nomPatient.'</td>';
                        echo '<td class="text-left">'.$age.'</td>';
                        echo '<td class="text-left">';
                        echo '<div class="form-group">
                                <textarea class="form-control input-sm" name="contenu[]" rows="2">'.$ligne['contenu'].'</textarea>
                               
                                <input type="hidden" name="detail_id[]" value="'.$ligne['id_detail'].'">
                            </div>';
                        echo '<input type="hidden" name="numero[]" value="'.$encounter_nr.'">';
                        echo '</td>';
                    echo '</tr>';
                }
                echo '<input type="hidden" name="passation_id" value="'.$id.'">';
                $form=ob_get_contents();
                ob_clean();
                $smarty->assign('form', $form);
            }
            
            break;
        default:
            # code...
            break;
    }

}else{

    # Formulaire par défaut
    $sql="SELECT e.encounter_nr, e.encounter_class_nr, p.pid, p.name_last, p.name_first, p.date_birth, p.addr_zip, p.sex,p.blood_group, r.room_name, e.encounter_date,e.discharge_date
            FROM care_encounter AS e LEFT JOIN care_person AS p ON e.pid=p.pid  LEFT JOIN care_encounter_location  AS l ON e.encounter_nr=l.encounter_nr LEFT JOIN care_room as r on l.location_nr=r.nr 
            WHERE e.is_discharged IN ('',0) AND l.status='' AND e.encounter_class_nr=1 AND r.ward_nr=".$zone;
    if($zone==2) $sql.=" AND r.aile='".$aile."'";

    $sql.=" AND r.room_name<> '' Group by e.encounter_nr ORDER BY r.room_name";

    $result=$db->Execute($sql);

    if(is_object($result)){
        ob_start();
            $i=0;
            while($ligne=$result->FetchRow()){
                $i++;
                $encounter_nr=$ligne['encounter_nr'];
                $encounterLocationInfo=$objWard->EncounterLocationsInfo($encounter_nr);
                $room_nr=$encounterLocationInfo['room_nr'];
                $bed_nr=$encounterLocationInfo['bed_nr'];  
                $pidPatient=$ligne['pid'];
                $nomPatient=empty($ligne['name_spouse'])?$ligne['name_last'].' '.$ligne['name_first']:$ligne['name_last'].' '.$ligne['name_first'].' épouse '.$ligne['name_spouse'];
                $chambre=$objWard->getRoomNameByNr($room_nr);
                $occupation=$chambre." Lit N° ".$bed_nr;
                $age=implode('',calcuteAge($ligne['date_birth']));
                echo '<tr>';
                    echo '<td class="text-right">'.$i.'</td>';
                    echo '<td class="text-left">'.$occupation.'</td>';
                    echo '<td class="text-left">'.$nomPatient.'</td>';
                    echo '<td class="text-left">'.$age.'</td>';
                    echo '<td class="text-left">';
                    echo '<div class="form-group">
                            <textarea class="form-control input-sm" name="contenu[]" rows="2"></textarea>
                        </div>';
                    echo '<input type="hidden" name="numero[]" value="'.$encounter_nr.'">';
                    echo '</td>';
                echo '</tr>';
            }
            echo '<input type="hidden" name="pid_medecin" value="'.$userPID.'">';
           
        $form=ob_get_contents();
        ob_clean();
        $smarty->assign("form", $form); 
    }

}
$smarty->assign("submit",  '<tr><td colspan="4" class="text-center"><input type="submit" class="btn btn-sm btn-primary" name="save" value="Enregistrer"></td></tr>');


# Sauvegarde de la Passation Globale
if(isset($save) && !empty($save)){
    // Enregistrement de la passation
    $tabPassation=array(
        'pid_medecin'=>$userPID,
        'zone'=>$zone,
        'aile'=>$aile,
    );
    if(isset($id) && !empty($id)){
        
        $tabPassation['modify_id']=$userPID;
        $tabPassation['modify_time']=$Time;
        $tabPassation['history']='CONCAT(history, ";Modifier par '.$userName.' le '.$Time.'")';
        
    }else{
        $id="";
        $tabPassation['create_id']=$userPID;
        $tabPassation['create_time']=$Time;
        $tabPassation['history']="Ajouter le ".$Time." par ".$userName;
        
    }


    if($objMedocsParams->insertFromArray("care_passation", $tabPassation, "id", @$id)){

        if(isset($id) && !empty($id)){
            $passation_id=$id;
        }else{
            $passation_id=$db->insert_id();
        }
        // Enregistrement du detail de la passation
        $element=count($contenu);


        for($e=0;$e<$element;$e++){

            $tabDetail=array(
                'passation_id'=>$passation_id,
                'encounter_nr'=>$numero[$e],
                'contenu'=>addslashes($contenu[$e]),
            );

            if(isset($id) && !empty($id)){
                $idDetail=$detail_id[$e];
                $tabDetail['modify_id']=$userPID;
                $tabDetail['modify_time']=$Time;
                $tabDetail['history']='CONCAT(history, ";Modifier par '.$userName.' le '.$Time.'")';
              
            }else{
                $idDetail="";
                $tabDetail['create_id']=$userPID;
                $tabDetail['create_time']=$Time;
                $tabDetail['history']="Ajouter le ".$Time." par ".$userName;
                
            }

            if($objMedocsParams->insertFromArray("care_passation_detail", $tabDetail, "id", @$idDetail)){
                $msg="Passation mise à jour avec succès !";
                $msgType="success";
            }else{
                $msg="Passation échouée !";
                $msgType="danger";
            }

            $_SESSION['msg']=$msg;
            $_SESSION['msgType']=$msgType;
            
            header("Location:".$urlActif);
        }
    }else{
        echo "Passation échouée !";
    }
   
    /*echo '<pre>';
    var_dump($element);
    echo '</pre>';*/
}

# Liste des passations
$sqlListe="SELECT * FROM care_passation WHERE zone=".$zone." AND valide_status='O'";
if($zone==2) $sqlListe.=" AND aile='".$aile."'";
$sqlListe.=" ORDER BY create_time DESC LIMIT 60";
//var_dump($sqlListe);
if($resultListe=$db->Execute($sqlListe)){
    if(is_object($resultListe)){
        ob_start();
        $compte=0;
        while ($passation=$resultListe->FetchRow()) {
            $compte++;
            $medecin=(!empty($passation['pid_medecin']))?"Dr ".$objPerson->getValue("name_last", $passation['pid_medecin'])." ".$objPerson->getValue("name_first", $passation['pid_medecin']):"";
            $date=(!empty($passation['create_time']))?parseDate($passation['create_time'],"Y-M-D","-","","D/M/Y","/",true):"";
            $printPDF=$root_path."modules/medocs/printPassation.php".URL_APPEND."&id=".$passation['id']."&zone=".$zone;
            if($zone==2) $printPDF.="&aile=".$aile;
            
            $actions='<a href="'.$urlActif.'&option=edit&id='.$passation['id'].'" class="btn btn-xs btn-success" style="color:white;" title="Editer"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;<a href="'.$printPDF.'" class="btn btn-xs btn-default" style="color:#00009C;border-color:#00009C;" title="Imprimer la liste" target="_blank"><span class="glyphicon glyphicon-print"></span></a>&nbsp;<a href="'.$urlActif.'&option=delete&id='.$passation['id'].'" class="btn btn-xs btn-danger" style="color:white;" title="Annuler cette Passation" onclick="return confirm(\'Etes vous sur ?\')"><span class="glyphicon glyphicon-trash"></span></a>';
            echo '<tr>';
                echo '<td class="text-right">'.$compte.'</td>';
                echo '<td>'.$date.'</td>';
                echo '<td>'.$medecin.'</td>';
                echo '<td class="text-center">'.$actions.'</td>';
            echo '</tr>';
        }

        $listing=ob_get_contents();
        ob_clean();
        $smarty->assign('listing', $listing);
    }
}
$smarty->assign('sMainBlockIncludeFile','medocs/medocs_passation.tpl');

?>
