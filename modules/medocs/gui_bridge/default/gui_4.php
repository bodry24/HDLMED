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
       /* $("#exemple").DataTable({
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
           
        });*/

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
$breadcrumb=array("DOSSIER MEDICAL", "REGISTRE","DIABETOLOGIE");

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
    
    $date1=parseDate($date_debut,'D/M/Y','/','','Y-M-D','-');
    $date2=parseDate($date_fin,'D/M/Y','/','','Y-M-D','-');

    //$doctorPid=((isset($doctor_id) && $doctor_id!='')?$doctor_id:$_SESSION['sess_user_name']);
    # Début du traitement
    $sql="SELECT e.encounter_date as encounter_date, p.date_birth as date_birth, e.encounter_nr as nr, p.sex as sexe,p.pid as pid, p.name_last as nom, p.name_first as prenoms, p.phone_1_nr as telephone FROM care_encounter e, care_person p WHERE p.pid=e.pid AND e.admit_type=".$registre." AND e.status IN ('O', '') AND date(e.encounter_date)>='".$date1."' AND date(e.encounter_date) <='".$date2."' ORDER BY e.encounter_date";
    //var_dump($sql);die();
    if($result=$db->Execute($sql)){
        if($result->RecordCount()>0){ 
            #Pour l'impression du registre
            ob_start();
                echo "<center><h5>REGISTRE DU ".$date_debut." AU ".$date_fin."</h5></center>";
                        $linkPDF=$root_path.'modules/medocs/print_registre_diabetologie.php?lang=fr&date_debut='.$date1.'&date_fin='.$date2.'&admit_type='.$registre;
                ?>
                <center>
                    <a href="<?php echo $linkPDF;?>" target="_blank" class="btn btn-sm btn-primary" style="color:#fff;">
                       <span class="glyphicon glyphicon-print"></span> Exporter en Excel
                    </a>
                </center>
    <?php
                $linkPdf=ob_get_contents();
            ob_end_clean();
            $smarty->assign('linkPdf',$linkPdf);

            /*****************************************
            **********Debut des operations************
            ******************************************/
             ob_start();
            $compteurLigne=0;
            while ($ligne=$result->FetchRow()) {
                $compteurLigne++;
                $nr=$ligne['nr'];
                $pid=$ligne['pid'];
                $name=$ligne['nom'].' '.$ligne['prenoms'];
                $encounter_date=$ligne['encounter_date'];
                #Calcule l'age de l'enfant 
                $tabAge = calcuteAge($ligne['date_birth'],'','fr');
                $age = !empty($tabAge[0])?$tabAge[0] : (!empty($tabAge[1])?$tabAge[1]:(!empty($tabAge[2])?$tabAge[2]:''));
                echo '<tr>';
                    echo '<td class="text-right">'.$compteurLigne.'</td>';
                    echo '<td>'.$name.' / '.$ligne['telephone'].'</td>';
                    echo '<td>';
                    #Je recupere ici les constantes
                    $listeConstante=$objMedocsParams->loadDetailSpecialiteConstantes($registre);
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

                               //echo '<table class="table table-bordered">';
                                $constante="";
                                for($i=0;$i<count($listeConstante);$i++){
                                   // echo '<tr>';
                                            $varUnit=$listeConstante[$i]['LD_var']."_unit";
                                            $varConstanteVal=$listeConstante[$i]['LD_var']."_val";
                                           /* echo '<td width="30%"><label>'.$$listeConstante[$i]['LD_var'].'</label></td>';
                                            echo '<td width="20%">'.$$varConstanteVal.' '.$$varUnit.'</td>';
                                            $$listeConstante[$i]['LD_var']*/
                                
                                   // echo '</tr>';
                                    if($i==0){
                                        $constante=$$listeConstante[$i]['LD_var']."=".$$varConstanteVal.' '.$$varUnit;
                                    }else{
                                        $constante.="; ".$$listeConstante[$i]['LD_var']."=".$$varConstanteVal.' '.$$varUnit;
                                    }
                                   
                                }
                                //echo '</table>';
                                echo $constante;
                                }
                          
                        }
                    }           
                    echo '</td>';
                    //Autres champs
                    $sqlParametresSpecialites="SELECT s.id as idSpecialite, p.value, sp.parametre_id, sp.position, p.designation, p.typeInput, p.nameValue, p.defaultValue, p.placeholder FROM care_medocs_specialites s, care_medocs_specialites_parametres sp, care_medocs_parametres p WHERE s.valide_status='O' AND sp.valide_status='O' AND p.valide='O' AND s.id=sp.specialite_id AND p.id=sp.parametre_id AND s.id=".$registre." ORDER BY sp.position ASC";
                    $parametresSpecialites=$db->Execute($sqlParametresSpecialites);
                    if($encounterParameters=$objEncounter->getEncounterParameters($nr)){
                        while($encParam=$encounterParameters->FetchRow()){
                            $$encParam['nameValue']=$encParam['value'];
                        }
                    }
                    $tabLibelle=$tabDonnees='';
                    while($showParam=$parametresSpecialites->FetchRow()){

                        $i++;
                        if($i % 2==0){
                            $color='white';
                        } 
                        else $color='#ddd';

                        $paramNameValue="";
                       if($showParam['typeInput']=="date"){
                            if($$showParam['nameValue']!="" && $$showParam['nameValue']!="0000-00-00" && !is_null($$showParam['nameValue'])){
                                $paramNameValue=parseDate($$showParam['nameValue'],'Y-M-D','-','','D/M/Y','/'); 
                            }
                            if($paramNameValue=="//"){

                                $paramNameValue=parseDate($encounter_date,'Y-M-D','-','','D/M/Y','/'); ;
                            }

                            
                        }
                        elseif($showParam['typeInput']=="liste"){
                            $keys=explode("#",$$showParam['nameValue']);
                            $tuple=explode(';',$showParam['value']);
                           // var_dump($showParam['value']);die();
                            foreach($tuple as $listItem){
                                $itemTab=explode(":",$listItem);
                                $tuples[$itemTab[0]]=$itemTab[1];
                            }
                            foreach($keys as $key=>$val){
                                if(empty($paramNameValue)) $paramNameValue.=$tuples[$val];
                                else $paramNameValue.=" ;".$tuples[$val];
                            }
                        }
                        else $paramNameValue=$$showParam['nameValue'];

                        $tabLibelle.='<th class="text-center">'.$showParam['designation'].'</th>';
                        $tabDonnees.='<td>'.$paramNameValue.'</td>';

                    }
                    $smarty->assign('tabLibelle', $tabLibelle);
                    
                    echo $tabDonnees;
                echo '</tr>';    
            }
            $listing=ob_get_contents();
            ob_end_clean();
            $smarty->assign('listing', $listing);

            /*****************************************
            ****fin de tous les patients consultés****
            ******************************************/
        }

        
    } 
 

}else{

    $sql="SELECT e.encounter_date as encounter_date, p.date_birth as date_birth, e.encounter_nr as nr, p.sex as sexe,p.pid as pid, p.name_last as nom, p.name_first as prenoms, p.phone_1_nr as telephone FROM care_encounter e, care_person p WHERE e.pid=p.pid AND e.admit_type=".$registre." AND e.pid=".$pid." AND e.status IN ('O', '')";
    //var_dump($sql);//die();
    $result=$db->Execute($sql);
    if(is_object($result)){
        ob_start();
        $compteurLigne=0;
        while ($ligne=$result->FetchRow()) {
            $compteurLigne++;
            $nr=$ligne['nr'];
            $pid=$ligne['pid'];
            $encounter_date=$ligne['encounter_date'];
            $name=$ligne['nom'].' '.$ligne['prenoms'];
            #Calcule l'age de l'enfant 
            $tabAge = calcuteAge($ligne['date_birth'],'','fr');
            $age = !empty($tabAge[0])?$tabAge[0] : (!empty($tabAge[1])?$tabAge[1]:(!empty($tabAge[2])?$tabAge[2]:''));
            echo '<tr>';
                echo '<td class="text-right">'.$compteurLigne.'</td>';
                echo '<td>'.$name.'</td>';
               /* echo '<td>'.parseDate($ligne['encounter_date'],'Y-M-D','-','','D/M/Y','/').'</td>';*/
                 #Constantes
                echo '<td>';
                #Je recupere ici les constantes
                $listeConstante=$objMedocsParams->loadDetailSpecialiteConstantes($registre);
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

                            //echo '<table class="table table-bordered">';
                            $constante="";
                            for($i=0;$i<count($listeConstante);$i++){
                               // echo '<tr>';
                                        $varUnit=$listeConstante[$i]['LD_var']."_unit";
                                        $varConstanteVal=$listeConstante[$i]['LD_var']."_val";
                                       /* echo '<td width="30%"><label>'.$$listeConstante[$i]['LD_var'].'</label></td>';
                                        echo '<td width="20%">'.$$varConstanteVal.' '.$$varUnit.'</td>';
                                        $$listeConstante[$i]['LD_var']*/
                            
                               // echo '</tr>';
                                if($i==0){
                                    $constante=$$listeConstante[$i]['LD_var']."=".$$varConstanteVal.' '.$$varUnit;
                                }else{
                                    $constante.="; ".$$listeConstante[$i]['LD_var']."=".$$varConstanteVal.' '.$$varUnit;
                                }
                            }
                            //echo '</table>';
                            echo $constante;
                        }
                      
                    }
                }           
                echo '</td>';
                //Autres champs
                $sqlParametresSpecialites="SELECT s.id as idSpecialite, p.value, sp.parametre_id, sp.position, p.designation, p.typeInput, p.nameValue, p.defaultValue, p.placeholder FROM care_medocs_specialites s, care_medocs_specialites_parametres sp, care_medocs_parametres p WHERE s.valide_status='O' AND sp.valide_status='O' AND p.valide='O' AND s.id=sp.specialite_id AND p.id=sp.parametre_id AND s.id=".$registre." ORDER BY sp.position ASC";
                $parametresSpecialites=$db->Execute($sqlParametresSpecialites);
                if($encounterParameters=$objEncounter->getEncounterParameters($nr)){
                    while($encParam=$encounterParameters->FetchRow()){
                        $$encParam['nameValue']=$encParam['value'];
                    }
                }
                $tabLibelle=$tabDonnees='';
                while($showParam=$parametresSpecialites->FetchRow()){

                    $i++;
                    if($i % 2==0){
                        $color='white';
                    } 
                    else $color='#ddd';

                    $paramNameValue="";
                    if($showParam['typeInput']=="date"){
                        if($$showParam['nameValue']!="" && $$showParam['nameValue']!="0000-00-00" && !is_null($$showParam['nameValue'])){
                            $paramNameValue=parseDate($$showParam['nameValue'],'Y-M-D','-','','D/M/Y','/'); 
                        }
                        if($paramNameValue=="//"){

                            $paramNameValue=parseDate($encounter_date,'Y-M-D','-','','D/M/Y','/'); ;
                        }
                        
                    }
                    elseif($showParam['typeInput']=="liste"){
                        $keys=explode("#",$$showParam['nameValue']);
                        $tuple=explode(';',$showParam['value']);
                        //var_dump($showParam['value']);die();
                        foreach($tuple as $listItem){
                            $itemTab=explode(":",$listItem);
                            $tuples[$itemTab[0]]=$itemTab[1];
                        }
                        foreach($keys as $key=>$val){
                            if(empty($paramNameValue)) $paramNameValue.=$tuples[$val];
                            else $paramNameValue.=" ;".$tuples[$val];
                        }
                    }
                    else $paramNameValue=$$showParam['nameValue'];

                    $tabLibelle.='<th class="text-center">'.$showParam['designation'].'</th>';
                    $tabDonnees.='<td>'.$paramNameValue.'</td>';

                }
                $smarty->assign('tabLibelle', $tabLibelle);
                
                echo $tabDonnees;
            echo '</tr>';    
        }
        $listing=ob_get_contents();
        ob_end_clean();
        $smarty->assign('listing', $listing);
    }

}

$smarty->assign('registreTpl','medocs_'.$registre.'.tpl');
?>

