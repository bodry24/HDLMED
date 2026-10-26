<?php
require_once($root_path.'include/care_api_classes/class_encounter.php');
$objEncounter=new Encounter;


#Assignation de l' id du CPN
if(isset($idCpn)&& !empty($idCpn)){
    $smarty->assign('idCpn', $idCpn);
}

#Si on a une fois saisi d'examen au CPN
$show=0;
if($surveillance=getSurveillanceCpn($idCpn)){
    $smarty->assign('idSurveillance', $surveillance['id']);
    $show=1;
    $smarty->assign("show",$show);
   // var_dump($surveillance);die();
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


        $('[data-toggle="tooltip"]').tooltip(); 



    }); 
</script>
<?php
$js=ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript', $js);


#Breadcrumb
$breadcrumb=array("DOSSIER MEDICAL","CONSULTATION PRE-NATALE", "SURVEILLANCE DE LA GROSSESSE");
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
        'date_surveillance'=>(isset($id)&&!empty($id))?maSerialize($surveillance['date_surveillance'],$date_surveillance):maSerializeSimple($date_surveillance),
        'age'=>(isset($id)&&!empty($id))?maSerialize($surveillance['age'],$age):maSerializeSimple($age),
        'ta'=>(isset($id)&&!empty($id))?maSerialize($surveillance['ta'],$ta):maSerializeSimple($ta),
        'poids'=>(isset($id)&&!empty($id))?maSerialize($surveillance['poids'],$poids):maSerializeSimple($poids),
        'urine'=>(isset($id)&&!empty($id))?maSerialize($surveillance['urine'],$urine):maSerializeSimple($urine),
        'hu'=>(isset($id)&&!empty($id))?maSerialize($surveillance['hu'],$hu):maSerializeSimple($hu),
        'examen'=>(isset($id)&&!empty($id))?maSerialize($surveillance['examen'],$examen):maSerializeSimple($examen),
        'traitement'=>(isset($id)&&!empty($id))?maSerialize($surveillance['traitement'],$traitement):maSerializeSimple($traitement),
        'observation'=>(isset($id)&&!empty($id))?maSerialize($surveillance['observation'],$observation):maSerializeSimple($observation),
        'bilan'=>(isset($id)&&!empty($id))?maSerialize($surveillance['bilan'],$bilan):maSerializeSimple($bilan),
        'valide_status'=>'O',
        'create_id'=>$create_id,
        'create_time'=>$create_time,
        'modify_id'=>$modify_id,
        'modify_time'=>$modify_time,
        'history'=>$history
    );

  /*  debug($objMedocsParams->insertFromArray('care_medocs_cpn_surveillance',$tab,"id",@$id));*/

    if($objMedocsParams->insertFromArray('care_medocs_cpn_surveillance',$tab,"id",@$id)){
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
        'date_surveillance'=>addslashes(serialize($date_surveillance)),
        'age'=>addslashes(serialize($age)),
        'ta'=>addslashes(serialize($ta)),
        'poids'=>addslashes(serialize($poids)),
        'urine'=>addslashes(serialize($urine)),
        'hu'=>addslashes(serialize($hu)),
        'examen'=>addslashes(serialize($examen)),
        'traitement'=>addslashes(serialize($traitement)),
        'observation'=>addslashes(serialize($observation)),
        'bilan'=>addslashes(serialize($bilan)),
        'valide_status'=>'O',
        'create_id'=>$create_id,
        'create_time'=>$create_time,
        'modify_id'=>$modify_id,
        'modify_time'=>$modify_time,
        'history'=>$history
    );
  
   
    if($objMedocsParams->insertFromArray('care_medocs_cpn_surveillance',$tab,"id",@$id)){
        $msg="La sauvegarde a été effectuée avec succès";
        $msgType="success";
        //header("Location:".$baseLink.URL_APPEND."&cpn=declaration_grossesse");
        unset($tab);unset($_POST);
    }else{
        $msg="Sauvegarde non effectuée";
        $msgType="danger";

    }

}

#Affichage des saisies effectuées
if($show==1){
    ob_start(); 
    /*$d=unserialize($surveillance['date_surveillance']);
    $nombreTd=count($d)+1;
    debug($d);die();*/
    echo '<form name="frmSurveillance" method="POST" action="">';
    echo    '<div class="panel panel-primary">
            <div class="panel-heading text-center">
                SURVEILLANCE DE GROSSESSE
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        
                        <table class="table table-bordered">';
                            echo '<tr>
                                    <td><label>Date</label></td>
                                    <td><label>Age de la grossesse</label></td>
                                    <td><label>TA</label></td>
                                    <td><label>Poids prise</label></td>
                                    <td><label>Urine AS</label></td>
                                    <td><label>HU</label></td>
                                    <td><label>Examen</label></td>
                                    <td><label>Traitement</label></td>
                                    <td><label>Observations</label></td>
                                    <td><label>Bilan demandé</label></td>
                                </tr>';
                            echo '<tr>
                                    <td>
                                        <table>';
                                        $d=unserialize($surveillance['date_surveillance']);   
                                        if(is_array($d)){
                                            for ($i=0; $i <count($d) ; $i++) { 
                                                echo '<tr><td><input type="text" name="date_surveillance[]" id="date_surveillance_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td></tr>';
                                            }
                                        }else{
                                            echo '<td><input type="text" name="date_surveillance" id="date_surveillance" class="form-control input-xs" value="'.$d.'"></td>';
                                        }   
                                        echo '
                                        </table>
                                    </td>
                                    <td>
                                        <table>';
                                        $d=unserialize($surveillance['age']);   
                                        if(is_array($d)){
                                            for ($i=0; $i <count($d) ; $i++) { 
                                                echo '<tr><td><input type="text" name="age[]" id="age_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td></tr>';
                                            }
                                        }else{
                                            echo '<td><input type="text" name="age" id="age" class="form-control input-xs" value="'.$d.'"></td>';
                                        }   
                                        echo '
                                        </table>
                                    </td>
                                    <td>
                                        <table>';
                                        $d=unserialize($surveillance['ta']);   
                                        if(is_array($d)){
                                            for ($i=0; $i <count($d) ; $i++) { 
                                                echo '<tr><td><input type="text" name="ta[]" id="ta_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td></tr>';
                                            }
                                        }else{
                                            echo '<td><input type="text" name="ta" id="ta" class="form-control input-xs" value="'.$d.'"></td>';
                                        }   
                                        echo '
                                        </table>
                                    </td>
                                    <td>
                                        <table>';
                                        $d=unserialize($surveillance['poids']);   
                                        if(is_array($d)){
                                            for ($i=0; $i <count($d) ; $i++) { 
                                                echo '<tr><td><input type="text" name="poids[]" id="poids_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td></tr>';
                                            }
                                        }else{
                                            echo '<td><input type="text" name="poids" id="poids" class="form-control input-xs" value="'.$d.'"></td>';
                                        }   
                                        echo '
                                        </table>
                                    </td>
                                    <td>
                                        <table>';
                                        $d=unserialize($surveillance['urine']);
                                        if(is_array($d)){
                                            for ($i=0; $i <count($d) ; $i++) { 
                                                echo '<tr><td><input type="text" name="urine[]" id="urine_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td></tr>';
                                            }
                                        }else{
                                            echo '<td><input type="text" name="urine" id="urine" class="form-control input-xs" value="'.$d.'"></td>';
                                        }   
                                        echo '
                                        </table>
                                    </td>
                                    <td>
                                        <table>';
                                        $d=unserialize($surveillance['hu']);
                                        if(is_array($d)){
                                            for ($i=0; $i <count($d) ; $i++) { 
                                                echo '<tr><td><input type="text" name="hu[]" id="hu_'.$i.'" class="form-control input-xs" value="'.$d[$i].'"></td></tr>';
                                            }
                                        }else{
                                            echo '<td><input type="text" name="hu" id="hu" class="form-control input-xs" value="'.$d.'"></td>';
                                        }   
                                        echo '
                                        </table>
                                    </td>
                                     <td>
                                        <table>';
                                        $d=unserialize($surveillance['examen']);
                                        if(is_array($d)){
                                            for ($i=0; $i <count($d) ; $i++) { 
                                                echo '<tr><td>
                                                    <textarea name="examen[]" class="form-control input-xs" id="examen_'.$i.'" rows="10" data-toggle="tooltip" title="'.$d[$i].'" >'.$d[$i].'
                                                    </textarea></td></tr>';
                                            }
                                        }else{
                                            echo '<td>
                                                <textarea name="examen" class="form-control input-xs" id="examen" rows="5">'.$d.'
                                                    </textarea></td>';
                                        }   
                                        echo '
                                        </table>
                                    </td>
                                     <td>
                                        <table>';
                                        $d=unserialize($surveillance['traitement']);
                                        if(is_array($d)){
                                            for ($i=0; $i <count($d) ; $i++) { 
                                                echo '<tr><td>
                                                    <textarea name="traitement[]" class="form-control input-xs" id="traitement_'.$i.'" rows="10" data-toggle="tooltip" title="'.$d[$i].'">'.$d[$i].'
                                                    </textarea></td></tr>';
                                            }
                                        }else{
                                            echo '<td>
                                                <textarea name="traitement" class="form-control input-xs" id="traitement" rows="5" data-toggle="tooltip" title="'.$d[$i].'">'.$d.'
                                                    </textarea></td>';
                                        }   
                                        echo '
                                        </table>
                                    </td>
                                     <td>
                                        <table>';
                                       $d=unserialize($surveillance['observation']);
                                        if(is_array($d)){
                                            for ($i=0; $i <count($d) ; $i++) { 
                                                echo '<tr><td>
                                                    <textarea name="observation[]" class="form-control input-xs" id="observation_'.$i.'" rows="10" data-toggle="tooltip" title="'.$d[$i].'">'.$d[$i].'
                                                    </textarea></td></tr>';
                                            }
                                        }else{
                                            echo '<td>
                                                <textarea name="observation" class="form-control input-xs" id="observation" rows="5">'.$d.'
                                                    </textarea></td>';
                                        }   
                                        echo '
                                        </table>
                                    </td>
                                    <td>
                                        <table>';
                                        $d=unserialize($surveillance['bilan']);
                                        if(is_array($d)){
                                            for ($i=0; $i <count($d) ; $i++) { 
                                                echo '<tr><td>
                                                    <textarea name="bilan[]" class="form-control input-xs" id="bilan_'.$i.'" rows="10" data-toggle="tooltip" title="'.$d[$i].'">'.$d[$i].'
                                                    </textarea></td></tr>';
                                            }
                                        }else{
                                            echo '<td>
                                                <textarea name="bilan" class="form-control input-xs" id="bilan" rows="5">'.$d.'
                                                    </textarea></td>';
                                        }   
                                        echo '
                                        </table>
                                    </td>
                                </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-center">
                <input type="hidden" name="id_cpn" value="'.$surveillance['id_cpn'].'" id="id_cpn">
                <input type="hidden" name="id" value="'.$surveillance['id'].'" id="id">
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


$smarty->assign('cpnTpl','medocs_surveillance_cpn.tpl');
?>