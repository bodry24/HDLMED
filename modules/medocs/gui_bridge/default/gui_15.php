<?php
$db->Execute("SET NAMES UTF-8");
#Liste des docteurs
$docteurs=$objPerson->getListAtPost(17);
#CSS et JS
ob_start();
?>
<style type="text/css">
	
</style> 
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

 		$("#date_letter").datetimepicker({
			format:'dd/mm/yyyy',
			autoclose:true,
			minView:2
		});

        $("#date_debut").datetimepicker({
			format:'dd/mm/yyyy',
			autoclose:true,
			minView:2
		});

		$("#date_fin").datetimepicker({
			format:'dd/mm/yyyy',
			autoclose:true,
			minView:2
		});

		$("#date_sortie").datetimepicker({
			format:'dd/mm/yyyy',
			autoclose:true,
			minView:2
		});


    });
   
</script>
<?php
$js=ob_get_contents();
ob_end_clean();

#Breadcrumb
$breadcrumb=array("DOSSIER MEDICAL", "REGISTRE","PEDIATRIE");

#Les docteurs
ob_start();
if(is_object($docteurs)){
    echo '<option value="">Selectionnez le medécin</option>';
    while($ligne=$docteurs->FetchRow()){
        echo '<option value='.$ligne['pid'];
        if((isset($medecin)) && ($medecin==$ligne['pid'])) echo ' selected="selected"';
        echo '>'.$ligne['name_last'].' '.$ligne['name_first'].'</option>';
    }
}
$listingDocteurs=ob_get_contents();
ob_end_clean();
$smarty->assign('listingDocteurs', $listingDocteurs);

#les dates
if(!isset($date_debut)){
    $smarty->assign('date_debut', date('d/m/Y'));
}else{
     $smarty->assign('date_debut', $date_debut);
}
if(!isset($date_fin)){
    $smarty->assign('date_fin', date('d/m/Y'));
}else{
     $smarty->assign('date_fin', $date_fin);
}

#Affichage du registre s'il en a
if(isset($afficher)){
   
    #Recupération du sess_user_name du docteur
    if(isset($medecin) && $medecin!=''){
        $sqlUser="SELECT u.name as login_id, p.pid as pid FROM care_users u, care_personell, care_personell pe, care_person p WHERE u.personell_nr=pe.nr AND p.pid=pe.pid AND p.pid=".$medecin;
        if($resultUser=$db->Execute($sqlUser)){
            if($rsUser=$resultUser->FetchRow()){
                $doctor_id=$rsUser['pid'];
            }
        }
    }

    $date1=parseDate($date_debut,'D/M/Y','/','','Y-M-D','-');
    $date2=parseDate($date_fin,'D/M/Y','/','','Y-M-D','-');

    $doctorPid=((isset($doctor_id) && $doctor_id!='')?$doctor_id:$_SESSION['sess_user_name']);

    # Début du traitement
    $sql="SELECT e.encounter_date as encounter_date, e.para_test as bilan, p.date_birth as date_birth, e.create_id as filtr, e.encounter_nr as nr, p.sex as sexe,p.pid as pid, p.name_last as nom, p.name_first as prenoms, e.referrer_diagnosis as diagnostic , e.referrer_recom_therapy as traitement FROM care_encounter e, care_person p 
        WHERE p.pid=e.pid AND encounter_class_nr=2 AND date(e.encounter_date)>='".$date1."' AND date(e.encounter_date) <='".$date2."' AND e.create_id LIKE '".$doctor_id."'";

    //var_dump($sql);die();
    if($result=$db->Execute($sql)){
        if($result->RecordCount()>0){ 
            #Pour l'impression du registre
            ob_start();
                echo "<center><h5>REGISTRE DE LA PEDIATRIE DU ".$date_debut." AU ".$date_fin."</h5></center>";
                        $linkPDF=$root_path.'modules/medocs/print_registre_pediatrie.php?lang=fr&date_debut='.$date1.'&date_fin='.$date2.'&idSpecialite='.$idSpecialite.'&doctorPid='.$doctorPid;
                ?>
                <center>
                    <a href="<?php echo $linkPDF;?>" target="_blank" class="btn btn-sm btn-primary" style="color:#fff;">
                       <span class="glyphicon glyphicon-print"></span> Exporter en PDF
                    </a>
                </center>
    <?php
                $linkPdf=ob_get_contents();
            ob_end_clean();
            $smarty->assign('linkPdf', $linkPdf);
        }
    } 
    ob_start();
    if($result=$db->Execute($sql)){
        if($result->RecordCount()>0){ 
        $I=0;
        while ($ligne=$result->FetchRow()) {
           
            $I++;
            $date=date_create($ligne['encounter_date']);
            $pid=$ligne['pid'];
            $nr=$ligne['nr'];
            $name=$ligne['nom'].' '.$ligne['prenoms'];
            #Calcule l'age de l'enfant 
            $tabAge = calcuteAge($ligne['date_birth'],'','fr');
            $age = !empty($tabAge[0])?$tabAge[0] : (!empty($tabAge[1])?$tabAge[1]:(!empty($tabAge[2])?$tabAge[2]:''));
            #Je recupere le diagnostic pour pouvoir le passer après
            echo '<tr>';
            echo '<td style="text-align:right;">'.$I.'</td>';
            echo '<td>'.date_format($date, 'd-m-Y').'</td>';
            echo '<td>'.$name.' (En: '.$ligne['nr'].')</td>';
            echo '<td style="text-align:right;">'.$age.'</td>';
            echo '<td style="text-align:center;">'.strtoupper($ligne['sexe']).'</td>';
            echo '<td>'.recupereValue($nr).'</td>';
            echo '<td>';
                #Je recupère ici les demandes d'analyses
                /*$sqlTrait="SELECT s.test_type as analyse FROM care_test_request t, care_test_request_sub s WHERE t.nr=s.parent_nr AND t.status!='delected' AND t.encounter_nr=".$nr; 
                if($resultTrait=$db->Execute($sqlTrait)){
                    if($resultTrait->RecordCount()){
                        ?>
                        <table>
                            <?php
                                while($ligneTrait=$resultTrait->FetchRow()){
                                    echo "<tr>";
                                    if($ligne['bilan']!=''){
                                        echo "<td>".$ligne['bilan']."</td>";
                                    }
                                    echo "<td>".$ligneTrait['analyse']."</td>
                                    </tr>";
                                }
                            ?>
                        </table>
                    <?php      
                    }
                    else echo $ligne['bilan'];
                             
                }else{
                    echo $ligne['bilan'];
                }*/  
                $sqlBilan="SELECT * FROM care_medocs_encounter_parametres WHERE valide_status='O' AND encounter_nr='".$ligne['nr']."' AND spec_param_id=28";
                //echo $sqlBilan;
                if($resultBilan=$db->Execute($sqlBilan)){
                    if($resultBilan->RecordCount()){
                        $rowBilan=$resultBilan->FetchRow();
                        echo nl2br($rowBilan['value']);
                    }
                    else echo $ligne['bilan'];
                }
                else echo $ligne['bilan'];
            echo '</td>';
            echo '<td>';
                #Recuperer les vaccins effectues
                $sqlVac="SELECT i.name as name FROM care_vaccination_patient v, care_type_immunization i WHERE i.nr=v.vid AND v.pid=".$pid." AND v.effectuee='O' AND v.date_effectuee>='".$date1."' AND v.date_effectuee<='".$date2."' AND v.type='pediatrie'";
                if($resultVac=$db->Execute($sqlVac)){
 ?>
                    <table>
                        <?php
                            while($ligneVac=$resultVac->FetchRow()){
                                echo "<tr><td>".$ligneVac['name']."</td></tr>";
                            }
                        ?>
                    </table>
 <?php                   
                }else{
                    echo 'vaccin caput';
                }           
            echo '</td>';
            echo '<td>';
                #Je recupere ici les constantes
                $listeConstante=$objMedocsParams->loadDetailSpecialiteConstantes($idSpecialite);
                if($encounterConstantes=$objMeasurement->getMeasurementByPidEncounterNr($nr,'O')){
                    $encounterConstantesNr=$encounterConstantes[0]['nr'];
                    if($encounterConstantesDetail=$objMeasurement->getMeasuresByNr($encounterConstantesNr)){
                        foreach($encounterConstantesDetail as $showConst){
                            //var_dump($showConst);echo '<br><br>';
                            $varConstanteVal=$showConst['LD_var']."_val";
                            $$varConstanteVal=$showConst['value'];
                            $varUnit=$showConst['LD_var']."_unit";
                            $$varUnit=$showConst['unite'];
                        }
                        if(is_object($listeConstante)){
                            $listeConstante=$listeConstante->getArray();
                            $nbConstante=count($listeConstante)/2;

                            echo '<table class="table table-bordered">';
                            for($i=0;$i<count($listeConstante);$i++){
                                echo '<tr>';
                                        $varUnit=$listeConstante[$i]['LD_var']."_unit";
                                        $varConstanteVal=$listeConstante[$i]['LD_var']."_val";
                                        echo '<td width="30%"><label>'.$$listeConstante[$i]['LD_var'].'</label></td>';
                                        echo '<td width="20%">'.$$varConstanteVal.' '.$$varUnit.'</td>';
                            
                                echo '</tr>';
                            }
                            echo '</table>';
                        }
                      
                    }
                }           

            echo '</td>';
            echo '<td>';
                #Je recupere ici les ordonnances
                //$sqlOrd="SELECT s.article as article, s.quantity as quantite,t.name as type, t.LD_var as LDVar FROM care_encounter_prescription p, care_encounter_prescription_sub s, care_type_application t WHERE p.nr=s.prescription_nr AND s.application_type_nr=t.nr AND p.encounter_nr=".$nr; 
                $sqlOrd="SELECT s.article as article 
                        FROM care_encounter_prescription_sub s, care_encounter_prescription p 
                        WHERE p.nr=s.prescription_nr 
                        AND p.encounter_nr=".$nr;
                //var_dump($sqlOrd);
                if($resultOrd=$db->Execute($sqlOrd)){
 ?>                 <!--la ligne qui suit est fait pour que lorsqu'il n'y a aucune ordonnance à affiche la térapie proposé-->
                    <table class="table table-bordered">
                        <?php
                           if(recupereValueP($nr, 27)!=''){
                                echo "<tr><td>".recupereValueP($nr, 27)."</td></tr>";
                           }

                            while($ligneOrd=$resultOrd->FetchRow()){
                                echo "<tr>
                                    <td>".$ligneOrd['article']."</td>
                                  
                                </tr>";
                            }
                        ?>
                    </table>
 <?php                   
                }else{
                    echo $sqlOrd;
                }           
            echo '</td>';
            echo '</tr>';
        }

        }else{
            echo '<tr><td colspan="10" align="center">Aucune donnée pour le moment !</td></tr>';
        }

        $listing=ob_get_contents();
        ob_end_clean();

        $smarty->assign('listing', $listing);


    }

}


$smarty->assign('registreTpl','medocs_'.$registre.'.tpl');
?>

