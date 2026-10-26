<?php
require_once($root_path.'include/care_api_classes/class_encounter.php');
$objEncounter=new Encounter;


#Assignation de l' id du CPN
if(isset($idCpn)&& !empty($idCpn)){
    $smarty->assign('idCpn', $idCpn);
}

#Si on a une fois saisi d'examen au CPN
$show=0;
if($examen=getExamenCpn($idCpn)){
    $smarty->assign('idExamen', $examen['id']);
    $show=1;
    $smarty->assign("show",$show);
   // var_dump($examen);die();
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
$breadcrumb=array("DOSSIER MEDICAL","CONSULTATION PRE-NATALE", "EXAMENS COMPLEMANTAIRES");
$smarty->assign("breadcrumbArray", $breadcrumb);

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
        'date_examen'=>(isset($id)&&!empty($id))?maSerialize($examen['date_examen'],$date_examen):maSerializeSimple($date_examen),
        'age'=>(isset($id)&&!empty($id))?maSerialize($examen['age'],$age):maSerializeSimple($age),
        'echo'=>(isset($id)&&!empty($id))?maSerialize($examen['echo'],$echo):maSerializeSimple($echo),
        'bip'=>(isset($id)&&!empty($id))?maSerialize($examen['bip'],$bip):maSerializeSimple($bip),
        'dat'=>(isset($id)&&!empty($id))?maSerialize($examen['dat'],$dat):maSerializeSimple($dat),
        'femur'=>(isset($id)&&!empty($id))?maSerialize($examen['femur'],$femur):maSerializeSimple($femur),
        'placen'=>(isset($id)&&!empty($id))?maSerialize($examen['placen'],$placen):maSerializeSimple($placen),
        'liq'=>(isset($id)&&!empty($id))?maSerialize($examen['liq'],$liq):maSerializeSimple($liq),
        'nfs'=>(isset($id)&&!empty($id))?maSerialize($examen['nfs'],$nfs):maSerializeSimple($nfs),
        'hb'=>(isset($id)&&!empty($id))?maSerialize($examen['hb'],$hb):maSerializeSimple($hb),
        'hte'=>(isset($id)&&!empty($id))?maSerialize($examen['hte'],$hte):maSerializeSimple($hte),
        'uree'=>(isset($id)&&!empty($id))?maSerialize($examen['uree'],$uree):maSerializeSimple($uree),
        'uricemie'=>(isset($id)&&!empty($id))?maSerialize($examen['uricemie'],$uricemie):maSerializeSimple($uricemie),
        'glycemie'=>(isset($id)&&!empty($id))?maSerialize($examen['glycemie'],$glycemie):maSerializeSimple($glycemie),
        'creatinine'=>(isset($id)&&!empty($id))?maSerialize($examen['creatinine'],$creatinine):maSerializeSimple($creatinine),
        'tp'=>(isset($id)&&!empty($id))?maSerialize($examen['tp'],$tp):maSerializeSimple($tp),
        'tck'=>(isset($id)&&!empty($id))?maSerialize($examen['tck'],$tck):maSerializeSimple($tck),
        'plaquette'=>(isset($id)&&!empty($id))?maSerialize($examen['plaquette'],$plaquette):maSerializeSimple($plaquette),
        'fibrinogene'=>(isset($id)&&!empty($id))?maSerialize($examen['fibrinogene'],$fibrinogene):maSerializeSimple($fibrinogene),
        'ecbu'=>(isset($id)&&!empty($id))?maSerialize($examen['ecbu'],$ecbu):maSerializeSimple($ecbu),
        'kop'=>(isset($id)&&!empty($id))?maSerialize($examen['kop'],$kop):maSerializeSimple($kop),
        'monitorage'=>(isset($id)&&!empty($id))?maSerialize($examen['monitorage'],$monitorage):maSerializeSimple($monitorage),
        'ammioscopie'=>(isset($id)&&!empty($id))?maSerialize($examen['ammioscopie'],$ammioscopie):maSerializeSimple($ammioscopie),
        'valide_status'=>'O',
        'create_id'=>$create_id,
        'create_time'=>$create_time,
        'modify_id'=>$modify_id,
        'modify_time'=>$modify_time,
        'history'=>$history
    );
  
    //var_dump($objMedocsParams->insertFromArray('care_medocs_cpn_parametres',$tab,"id",@$id));die();

    if($objMedocsParams->insertFromArray('care_medocs_cpn_examens',$tab,"id",@$id)){
        $msg="La sauvegarde a été effectuée avec succès";
        $msgType="success";
        //header("Location:".$baseLink.URL_APPEND."&cpn=examens_cpn");
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
    $d=unserialize($examen['date_examen']);
    $nombreTd=count($d)+1;
    // debug($d);die();
    echo '<form name="frmExamenCpn" method="POST" action="">';
    echo    '<div class="panel panel-primary">
            <div class="panel-heading text-center">
                EXAMENS COMPLEMENTAIRES
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        
                        <table class="table table-bordered">';
                            echo '<tr>
                                <td><label><sub>Titres </sub>\ <sup>Dates</sup></label></td>';
                                if(is_array($d)){
                                    for ($i=0; $i <count($d) ; $i++) { 
                                        echo '<td><input type="text" name="date_examen[]" id="date_examen_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                                    }
                                }else{
                                    echo '<td><input type="text" name="date_examen" id="date_examen" class="form-control input-xs" value="'.$d.'"></td>';
                                }   
                            echo '
                            </tr>
                            <tr style="background-color: #0d5da3;"><td colspan="'.$nombreTd.'"></td></tr>
                            <tr>
                                <td><label>Age de la grossesse</label></td>';
                                $d=unserialize($examen['age']);
                                if(is_array($d)){
                                    for ($i=0; $i <count($d) ; $i++) { 
                                        echo '<td><input type="text" name="age[]" id="age'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                                    }
                                }else{
                                    echo '<td><input type="text" name="age" id="age" class="form-control input-xs" value="'.$d.'"></td>';
                                }                              
                            echo '</tr>
                            <tr style="background-color: #0d5da3;"><td colspan="7"></td></tr>
                            <tr>
                                <td><label>ECHO</label></td>';
                                $d=unserialize($examen['echo']);
                                if(is_array($d)){
                                    for ($i=0; $i <count($d) ; $i++) { 
                                        echo '<td><input type="text" name="echo[]" id="echo_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                                    }
                                }else{
                                    echo '<td><input type="text" name="echo" id="echo" class="form-control input-xs" value="'.$d.'"></td>';
                                }   
                                
                            echo '</tr>
                            <tr>
                                <td><label>Bip</label></td>';
                                $d=unserialize($examen['bip']);
                                if(is_array($d)){
                                    for ($i=0; $i <count($d) ; $i++) { 
                                        echo '<td><input type="text" name="bip[]" id="bip_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                                    }
                                }else{
                                    echo '<td><input type="text" name="bip" id="bip" class="form-control input-xs" value="'.$d.'"></td>';
                                }   
   
                            echo '</tr>
                            <tr>
                                <td><label>Dat</label></td>';
                                $d=unserialize($examen['dat']);
                                if(is_array($d)){
                                    for ($i=0; $i <count($d) ; $i++) { 
                                        echo '<td><input type="text" name="dat[]" id="dat_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                                    }
                                }else{
                                    echo '<td><input type="text" name="dat" id="dat" class="form-control input-xs" value="'.$d.'"></td>';
                                }   
                            
                            echo '</tr>
                            <tr>
                                <td><label>Fémur</label></td>';
                                $d=unserialize($examen['femur']);
                                if(is_array($d)){
                                    for ($i=0; $i <count($d) ; $i++) { 
                                        echo '<td><input type="text" name="femur[]" id="femur_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                                    }
                                }else{
                                    echo '<td><input type="text" name="femur" id="femur" class="form-control input-xs" value="'.$d.'"></td>';
                                }   
                                
                            echo '</tr>
                            <tr>
                                <td><label>Placen.</label></td>';
                                $d=unserialize($examen['placen']);
                                if(is_array($d)){
                                    for ($i=0; $i <count($d) ; $i++) { 
                                        echo '<td><input type="text" name="placen[]" id="placen_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                                    }
                                }else{
                                    echo '<td><input type="text" name="placen" id="placen" class="form-control input-xs" value="'.$d.'"></td>';
                                }   
                                
                            echo '</tr>
                            <tr>
                                <td><label>Liq. Am</label></td>';
                                $d=unserialize($examen['liq']);
                                if(is_array($d)){
                                    for ($i=0; $i <count($d) ; $i++) { 
                                        echo '<td><input type="text" name="liq[]" id="liq_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                                    }
                                }else{
                                    echo '<td><input type="text" name="liq" id="liq" class="form-control input-xs" value="'.$d.'"></td>';
                                }    
                            echo '</tr>
                            <tr style="background-color: #0d5da3;"><td colspan="'.$nombreTd.'"></td></tr>
                            <tr>
                                <td><label>NFS GE</label></td>';
                                $d=unserialize($examen['nfs']);
                                if(is_array($d)){
                                    for ($i=0; $i <count($d) ; $i++) { 
                                        echo '<td><input type="text" name="nfs[]" id="nfs_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                                    }
                                }else{
                                    echo '<td><input type="text" name="nfs" id="nfs" class="form-control input-xs" value="'.$d.'"></td>';
                                }    
                                
                            echo '</tr>
                            <tr>
                                <td><label>HB</label></td>';
                                $d=unserialize($examen['hb']);
                                if(is_array($d)){
                                    for ($i=0; $i <count($d) ; $i++) { 
                                        echo '<td><input type="text" name="hb[]" id="hb_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                                    }
                                }else{
                                    echo '<td><input type="text" name="hb" id="hb" class="form-control input-xs" value="'.$d.'"></td>';
                                }    
                                
                            echo '</tr>
                            <tr>
                                <td><label>HTE</label></td>';
                                $d=unserialize($examen['hte']);
                                if(is_array($d)){
                                    for ($i=0; $i <count($d) ; $i++) { 
                                        echo '<td><input type="text" name="hte[]" id="hte_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                                    }
                                }else{
                                    echo '<td><input type="text" name="hte" id="hte" class="form-control input-xs" value="'.$d.'"></td>';
                                }    
                                
                            echo '</tr>
                            <tr style="background-color: #0d5da3;"><td colspan="7"></td></tr>
                            <tr>
                                <td><label>UREE</label></td>';
                                $d=unserialize($examen['uree']);
                                if(is_array($d)){
                                    for ($i=0; $i <count($d) ; $i++) { 
                                        echo '<td><input type="text" name="uree[]" id="uree_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                                    }
                                }else{
                                    echo '<td><input type="text" name="uree" id="uree" class="form-control input-xs" value="'.$d.'"></td>';
                                }    
                                
                            echo '</tr>
                            <tr style="background-color: #0d5da3;"><td colspan="7"></td></tr>
                            <tr>
                                <td><label>URICEMIE</label></td>';
                                $d=unserialize($examen['uricemie']);
                                if(is_array($d)){
                                    for ($i=0; $i <count($d) ; $i++) { 
                                        echo '<td><input type="text" name="uricemie[]" id="uricemie_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                                    }
                                }else{
                                    echo '<td><input type="text" name="uricemie" id="uricemie" class="form-control input-xs" value="'.$d.'"></td>';
                                }    
                                
                            echo '</tr>
                            <tr style="background-color: #0d5da3;"><td colspan="7"></td></tr>
                            <tr>
                                <td><label>Glycémie</label></td>';
                                $d=unserialize($examen['glycemie']);
                                if(is_array($d)){
                                    for ($i=0; $i <count($d) ; $i++) { 
                                        echo '<td><input type="text" name="glycemie[]" id="glycemie_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                                    }
                                }else{
                                    echo '<td><input type="text" name="glycemie" id="glycemie" class="form-control input-xs" value="'.$d.'"></td>';
                                }    
                                
                            echo '</tr>
                            <tr style="background-color: #0d5da3;"><td colspan="7"></td></tr>
                            <tr>
                                <td><label>Créatinine</label></td>';
                                $d=unserialize($examen['creatinine']);
                                if(is_array($d)){
                                    for ($i=0; $i <count($d) ; $i++) { 
                                        echo '<td><input type="text" name="creatinine[]" id="creatinine_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                                    }
                                }else{
                                    echo '<td><input type="text" name="creatinine" id="creatinine" class="form-control input-xs" value="'.$d.'"></td>';
                                }    
                                
                            echo '</tr>
                            <tr style="background-color: #0d5da3;"><td colspan="7"></td></tr>
                            <tr>
                                <td><label>TP</label></td>';
                                $d=unserialize($examen['tp']);
                                if(is_array($d)){
                                    for ($i=0; $i <count($d) ; $i++) { 
                                        echo '<td><input type="text" name="tp[]" id="tp_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                                    }
                                }else{
                                    echo '<td><input type="text" name="tp" id="tp" class="form-control input-xs" value="'.$d.'"></td>';
                                }    
                            echo '</tr>
                            <tr>
                                <td><label>TCK</label></td>';
                                $d=unserialize($examen['tck']);
                                if(is_array($d)){
                                    for ($i=0; $i <count($d) ; $i++) { 
                                        echo '<td><input type="text" name="tck[]" id="tck_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                                    }
                                }else{
                                    echo '<td><input type="text" name="tck" id="tck" class="form-control input-xs" value="'.$d.'"></td>';
                                }    
                            echo '</tr>
                            <tr>
                                <td><label>Plaquettes</label></td>';
                                $d=unserialize($examen['plaquette']);
                                if(is_array($d)){
                                    for ($i=0; $i <count($d) ; $i++) { 
                                        echo '<td><input type="text" name="plaquette[]" id="plaquette_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                                    }
                                }else{
                                    echo '<td><input type="text" name="plaquette" id="plaquette" class="form-control input-xs" value="'.$d.'"></td>';
                                }    
                                
                            echo '</tr>
                            <tr>
                                <td><label>Fibrinogène</label></td>';
                                $d=unserialize($examen['fibrinogene']);
                                if(is_array($d)){
                                    for ($i=0; $i <count($d) ; $i++) { 
                                        echo '<td><input type="text" name="fibrinogene[]" id="fibrinogene_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                                    }
                                }else{
                                    echo '<td><input type="text" name="fibrinogene" id="fibrinogene" class="form-control input-xs" value="'.$d.'"></td>';
                                }                                 
                            echo '</tr>
                            <tr style="background-color: #0d5da3;"><td colspan="'.$nombreTd.'"></td></tr>
                            <tr>
                                <td><label>ECBU</label></td>';
                                $d=unserialize($examen['ecbu']);
                                if(is_array($d)){
                                    for ($i=0; $i <count($d) ; $i++) { 
                                        echo '<td><input type="text" name="ecbu[]" id="ecbu_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                                    }
                                }else{
                                    echo '<td><input type="text" name="ecbu" id="ecbu" class="form-control input-xs" value="'.$d.'"></td>';
                                }              
                                
                            echo '</tr>
                            <tr>
                                <td><label>KOP</label></td>';
                                $d=unserialize($examen['kop']);
                                if(is_array($d)){
                                    for ($i=0; $i <count($d) ; $i++) { 
                                        echo '<td><input type="text" name="kop[]" id="kop_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                                    }
                                }else{
                                    echo '<td><input type="text" name="kop" id="kop" class="form-control input-xs" value="'.$d.'"></td>';
                                }            
                                
                            echo '</tr>
                            <tr>
                                <td><label>Monitorage Score de fisher</label></td>';
                                $d=unserialize($examen['monitorage']);
                                if(is_array($d)){
                                    for ($i=0; $i <count($d) ; $i++) { 
                                        echo '<td><input type="text" name="monitorage[]" id="monitorage_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                                    }
                                }else{
                                    echo '<td><input type="text" name="monitorage" id="monitorage" class="form-control input-xs" value="'.$d.'"></td>';
                                }            
                            echo '</tr>
                            <tr>
                                <td><label>Ammioscope</label></td>';
                                $d=unserialize($examen['ammioscopie']);
                                if(is_array($d)){
                                    for ($i=0; $i <count($d) ; $i++) { 
                                        echo '<td><input type="text" name="ammioscopie[]" id="ammioscopie_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td>';
                                    }
                                }else{
                                    echo '<td><input type="text" name="ammioscopie" id="ammioscopie" class="form-control input-xs" value="'.$d.'"></td>';
                                }         
                            echo '</tr>

                        </table>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-center">
                <input type="submit" name="modifier" value="Modifier" id="modifier" class="btn btn-sm btn-primary">
            </div>';
    echo '    </div>
        <br><br><br>
    </form>

    ';

    echo '<br><br><br>';

    $affichage=ob_get_contents();
    ob_end_clean();
    $smarty->assign('affichage', $affichage);
}


$smarty->assign('cpnTpl','medocs_examens_cpn.tpl');
?>