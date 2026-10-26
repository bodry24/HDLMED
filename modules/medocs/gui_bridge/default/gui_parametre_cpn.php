<?php
require_once($root_path.'include/care_api_classes/class_encounter.php');
$objEncounter=new Encounter;


#Assignation de l' id du CPN
/*if(isset($idCpn)&& !empty($idCpn)){
    $smarty->assign('idCpn', $idCpn);
}*/
#Si on a une fois saisi de parametres au CPN
//debug($parametre=getParametresCpn($idCpn));
$show=0;
if($parametre=getParametresCpn($idCpn)){
    $smarty->assign('idParametre', $parametre['id']);
    $show=1;
    $smarty->assign("show",$show);
}

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
    .panel-heading{
        padding: 0px;
    }
    .panel-body{
        padding: 2px;
        margin-bottom: 0px;
    }
    .table{
        margin-bottom: 0px;
    }
    table
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
$breadcrumb=array("DOSSIER MEDICAL","CONSULTATION PRE-NATALE", "PARAMETRES");
$smarty->assign("breadcrumbArray", $breadcrumb);

if($parametre=getParametresCpn($idCpn)){
    $smarty->assign('groupe', $parametre['groupe']);
    $smarty->assign('rhesus', $parametre['rhesus']);
    $smarty->assign('electrophorese', $parametre['electrophorese']);
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
      
    /*function maSerialize($champs, $valeur=""){
        $champs=unserialize($champs);
        if(is_string($champs)){
            $champs=array($champs);
        }
        array_push($champs, addslashes(strval($valeur)));
        $champs=addslashes(serialize($champs));
        return $champs;
    }

    function maSerializeSimple($valeur=""){
        $valeur=serialize($valeur);
        return addslashes($valeur);
    }*/

    $tab=array(
        'id_cpn'=>$id_cpn,
        'groupe'=>$groupe,
        'rhesus'=>$rhesus,
        'date_agglutinine'=>(isset($id)&&!empty($id))?maSerialize($parametre['date_agglutinine'],$date_agglutinine):maSerializeSimple($date_agglutinine),
        'titre_agglutinine'=>(isset($id)&&!empty($id))?maSerialize($parametre['titre_agglutinine'],$titre_agglutinine):maSerializeSimple($titre_agglutinine),
        'electrophorese'=>addslashes($electrophorese),
        'date_immunisation'=>(isset($id)&&!empty($id))?maSerialize($parametre['date_immunisation'],$date_immunisation):maSerializeSimple($date_immunisation),
        'toxoplasme'=>(isset($id)&&!empty($id))?maSerialize($parametre['toxoplasme'],$toxoplasme):maSerializeSimple($toxoplasme),
        'rubeole'=>(isset($id)&&!empty($id))?maSerialize($parametre['rubeole'],$rubeole):maSerializeSimple($rubeole),
        'syphilis'=>(isset($id)&&!empty($id))?maSerialize($parametre['syphilis'],$syphilis):maSerializeSimple($syphilis),
        'vih'=>(isset($id)&&!empty($id))?maSerialize($parametre['vih'],$vih):maSerializeSimple($vih),
        'autre'=>(isset($id)&&!empty($id))?maSerialize($parametre['autre'],$autre):maSerializeSimple($autre),
        'date_vaccination'=>(isset($id)&&!empty($id))?maSerialize($parametre['date_vaccination'],$date_vaccination):maSerializeSimple($date_vaccination),
        'antitetanique'=>(isset($id)&&!empty($id))?maSerialize($parametre['antitetanique'],$antitetanique):maSerializeSimple($antitetanique),
        'bcg'=>(isset($id)&&!empty($id))?maSerialize($parametre['bcg'],$bcg):maSerializeSimple($bcg),
        'hepatite'=>(isset($id)&&!empty($id))?maSerialize($parametre['hepatite'],$hepatite):maSerializeSimple($hepatite),
        'valide_status'=>'O',
        'create_id'=>$create_id,
        'create_time'=>$create_time,
        'modify_id'=>$modify_id,
        'modify_time'=>$modify_time,
        'history'=>$history
    );
  
    //var_dump($objMedocsParams->insertFromArray('care_medocs_cpn_parametres',$tab,"id",@$id));die();

    if($objMedocsParams->insertFromArray('care_medocs_cpn_parametres',$tab,"id",@$id)){
        $msg="La sauvegarde a été effectuée avec succès";
        $msgType="success";
        //header("Location:".$baseLink.URL_APPEND."&cpn=declaration_grossesse");
        unset($tab);unset($_POST);
        unset($save);
    }else{
        $msg="Sauvegarde non effectuée";
        $msgType="danger";

    }

}

#Affichage des saisies effectuées
if($show==1){
  
    ob_start(); 
    $d=unserialize($parametre['date_agglutinine']);
    echo '<form method="POST" action="">';
    echo '<div class="row">';
    echo '<div class="col-sm-12">';
    echo '<table class="table table-bordered">';
    echo '<tr>';
        echo '<td><h4>1)</h4></td>';
        echo '<td><label>Groupe</label></td>';
        echo '<td><input type="text" class="form-control input-xs" value="'.$parametre['groupe'].'" name="groupe"></td>';
        echo '<td><label">Rhesus</label></td>';
        echo '<td><input type="text" class="form-control input-xs" value="'.$parametre['rhesus'].'" name="rhesus"></td>';
    echo '</tr>';
    echo '</table>';
    echo '</div></div>';

    echo '<div class="row">';
        echo '<div class="col-sm-12">';
        echo '<hr>';
        echo '<h4>2) AGGLUTININES IRREGULIERES ANT. D.</h4>';
        echo '<table class="table table-bordered">';
        echo '<tr><td>';
            echo '<label>Dates</label>';
        echo '</td>';
            if(is_array($d)){
                for ($i=0; $i <count($d) ; $i++) { 
                    echo '<td><input type="text" name="date_agglutinine[]" id="date_agglutinine_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                }
            }else{
                echo '<td><input type="text" name="date_agglutinine" id="date_agglutinine" class="form-control input-xs" value="'.$d.'"></td>';
            }   
        echo '</tr>';
        
        $d=unserialize($parametre['titre_agglutinine']);
        echo '<tr><td>';
            echo '<label>Titres</label>';
        echo '</td>';
        if(is_array($d)){
            for ($i=0; $i <count($d) ; $i++) { 
                echo '<td><input type="text" name="titre_agglutinine[]" id="titre_agglutinine'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
            }
        }else{
            echo '<td><input type="text" name="titre_agglutinine" id="titre_agglutinine" class="form-control input-xs" value="'.$d.'"></td>';
        }
        echo '</tr>';
        echo '</table>';
    echo '</div></div>';
    echo '<hr>';

    echo '<div class="row">';
    echo '<div class="col-sm-12">';
    echo '<table class="table table-bordered">';
    echo '<tr>';
        echo '<td><h4>3) ELECTROPHORESE DE L\'HEMOGBLOBINE</h4></td>';
        echo '<td><input type="text" class="form-control input-xs" value="'.$parametre['electrophorese'].'" name="electrophorese"></td>';
    echo '</tr>';
    echo '</table>';
    echo '</div></div>';

    echo '<div class="row">';
        echo '<div class="col-sm-12">';
        echo '<hr>';
        echo '<h4>4) IMMUNISATIONS ACQUISES</h4>';
        echo '<table class="table table-bordered">';
        echo '<tr><td>';
            echo '<label>Dates</label>';
        echo '</td>';
        $d=unserialize($parametre['date_immunisation']);
        if(is_array($d)){
            for ($i=0; $i <count($d) ; $i++) { 
                echo '<td><input type="text" name="date_immunisation[]" id="date_immunisation'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
            }
        }else{
            echo '<td><input type="text" name="date_immunisation" id="date_immunisation" class="form-control input-xs" value="'.$d.'"></td>';
        }
        echo '</tr>';
        echo '<tr><td>';
            echo '<label>toxoplasme</label>';
        echo '</td>';
        $d=unserialize($parametre['toxoplasme']);
        if(is_array($d)){
            for ($i=0; $i <count($d) ; $i++) { 
                echo '<td><input type="text" name="toxoplasme[]" id="toxoplasme'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
            }
        }else{
             echo '<td><input type="text" name="toxoplasme" id="toxoplasme" class="form-control input-xs" value="'.$d.'"></td>';
        }
        echo '</tr>';
        
        echo '<tr><td>';
            echo '<label>Rubeole</label>';
        echo '</td>';
        $d=unserialize($parametre['rubeole']);
        if(is_array($d)){
            for ($i=0; $i <count($d) ; $i++) { 
                echo '<td><input type="text" name="rubeole[]" id="rubeole'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
            }
        }else{
           echo '<td><input type="text" name="rubeole" id="rubeole" class="form-control input-xs" value="'.$d.'"></td>'; 
        }
        echo '</tr>';

        echo '<tr><td>';
            echo '<label>Syphilis</label>';
        echo '</td>';
        $d=unserialize($parametre['syphilis']);
        if(is_array($d)){
            for ($i=0; $i <count($d) ; $i++) { 
                echo '<td><input type="text" name="syphilis[]" id="syphilis'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
            }
        }else{
            echo '<td><input type="text" name="syphilis" id="syphilis" class="form-control input-xs" value="'.$d.'"></td>';
        }
        echo '</tr>';

        echo '<tr><td>';
            echo '<label>VIH</label>';
        echo '</td>';
        $d=unserialize($parametre['vih']);
        if(is_array($d)){
            for ($i=0; $i <count($d) ; $i++) { 
                echo '<td><input type="text" name="vih[]" id="vih'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
            }
        }else{
            echo '<td><input type="text" name="vih" id="vih" class="form-control input-xs" value="'.$d.'"></td>';
        }
        echo '</tr>';

        echo '<tr><td>';
            echo '<label>Autre</label>';
        echo '</td>';
        $d=unserialize($parametre['autre']);
        if(is_array($d)){
            for ($i=0; $i <count($d) ; $i++) { 
                echo '<td><input type="text" name="autre[]" id="autre'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
            }
        }else{
            echo '<td><input type="text" name="autre" id="autre" class="form-control input-xs" value="'.$d.'"></td>';
        }
        echo '</tr>';
        
        
        echo '</table>';
    echo '</div></div>';

    echo '<div class="row">';
        echo '<div class="col-sm-12">';
        echo '<h4>VACCINATIONS</h4>';
        echo '<table class="table table-bordered">';
        echo '<tr><td>';
            echo '<label>Dates</label>';
        echo '</td>';
         $d=unserialize($parametre['date_vaccination']);
        if(is_array($d)){
            for ($i=0; $i <count($d) ; $i++) { 
                echo '<td><input type="text" name="date_vaccination[]" id="date_vaccination'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
            }
        }else{
             echo '<td><input type="text" name="date_vaccination" id="date_vaccination" class="form-control input-xs" value="'.$d.'"></td>';
        }
        echo '</tr>';
        
        $d=unserialize($parametre['antitetanique']);
        echo '<tr><td>';
            echo '<label>Antitétanique</label>';
        echo '</td>';
        if(is_array($d)){
            for ($i=0; $i <count($d) ; $i++) { 
                echo '<td><input type="text" name="antitetanique[]" id="antitetanique'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
            }
        }else{
             echo '<td><input type="text" name="antitetanique" id="antitetanique" class="form-control input-xs" value="'.$d.'"></td>';
        }
        echo '</tr>';
        $d=unserialize($parametre['bcg']);
        echo '<tr><td>';
            echo '<label>BCG</label>';
        echo '</td>';
        if(is_array($d)){
            for ($i=0; $i <count($d) ; $i++) { 
                echo '<td><input type="text" name="bcg[]" id="bcg'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
            }
        }else{
            echo '<td><input type="text" name="bcg" id="bcg" class="form-control input-xs" value="'.$d.'"></td>';
        }
        echo '</tr>';
        $d=unserialize($parametre['hepatite']);
        echo '<tr><td>';
            echo '<label>Hépatite</label>';
        echo '</td>';
        if(is_array($d)){
            for ($i=0; $i <count($d) ; $i++) {
                echo '<td><input type="text" name="hepatite[]" id="hepatite'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
            }
        }else{
             echo '<td><input type="text" name="hepatite" id="hepatite" class="form-control input-xs" value="'.$d.'"></td>';
        }
        echo '</tr>';
        echo '</table>';
    echo '</div></div>';
    echo '<div class="row">';
        echo '<div class="col-sm-12">';
        echo '<br><br>';
        echo '<center>
                <input type="hidden" name="id_cpn" value="'.$parametre['id_cpn'].'" id="id_cpn">
                <input type="hidden" name="id" value="'.$parametre['id'].'" id="id">
                <input type="submit" name="modifier" value="Modifier" id="modifier" class="btn btn-sm btn-primary">
            </center>';
        echo '</div>';
    echo '</div>';
    echo '<br><br><br>';

    $affichage=ob_get_contents();
    ob_end_clean();
    $smarty->assign('affichage', $affichage);
}

#Modification des données
if(isset($modifier)){
   
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
   // debug($date_immunisation);die();
    $tab=array(
        'id_cpn'=>$id_cpn,
        'groupe'=>$groupe,
        'rhesus'=>$rhesus,
        'date_agglutinine'=>addslashes(serialize($date_agglutinine)),
        'titre_agglutinine'=>addslashes(serialize($titre_agglutinine)),
        'electrophorese'=>addslashes($electrophorese),
        'date_immunisation'=>addslashes(serialize($date_immunisation)),
        'toxoplasme'=>addslashes(serialize($toxoplasme)),
        'rubeole'=>addslashes(serialize($rubeole)),
        'syphilis'=>addslashes(serialize($syphilis)),
        'vih'=>addslashes(serialize($vih)),
        'autre'=>addslashes(serialize($autre)),
        'date_vaccination'=>addslashes(serialize($date_vaccination)),
        'antitetanique'=>addslashes(serialize($antitetanique)),
        'bcg'=>addslashes(serialize($bcg)),
        'hepatite'=>addslashes(serialize($hepatite)),
        'valide_status'=>'O',
        'create_id'=>$create_id,
        'create_time'=>$create_time,
        'modify_id'=>$modify_id,
        'modify_time'=>$modify_time,
        'history'=>$history
    );
  
   
    if($objMedocsParams->insertFromArray('care_medocs_cpn_parametres',$tab,"id",@$id)){
        $msg="La sauvegarde a été effectuée avec succès";
        $msgType="success";
        //header("Location:".$baseLink.URL_APPEND."&cpn=declaration_grossesse");
        unset($tab);unset($_POST);
    }else{
        $msg="Sauvegarde non effectuée";
        $msgType="danger";

    }

}

$smarty->assign('cpnTpl','medocs_parametre_cpn.tpl');
?>