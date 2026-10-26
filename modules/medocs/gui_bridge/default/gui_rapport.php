<?php
ini_set("display_errors",true);
global $db;
$db->Execute("SET NAMES UTF8");

require_once($root_path.'include/care_api_classes/class_medicalActs.php');
$med_obj = new MedicalActs();
$doctorInfo = $med_obj->getPatient($med_obj->getUserPid($_SESSION['sess_user_login_id']));
$doctor = $doctorInfo['title'].' '.$doctorInfo['name_last'].' '.$doctorInfo['name_first'];

# Load the letter class
require_once($root_path.'include/care_api_classes/class_letter.php');
$letter_obj = new Letter();

# Load the encounter class
require_once($root_path.'include/care_api_classes/class_encounter.php');
$objEncounter=new Encounter();

require_once($root_path.'include/care_api_classes/class_ecombill.php');
$ecombill = new eCombill();

require_once($root_path.'include/care_api_classes/class_person.php');
$objPerson = new Person();

require_once($root_path.'modules/caisse/utils.php');


/**************Recherche sur les autres info d'un patient admis******************/
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/care_api_classes/class_insurance.php');
require_once($root_path.'include/care_api_classes/class_ward.php');
require_once($root_path.'include/care_api_classes/class_personell.php');
require_once($root_path.'include/care_api_classes/class_measurement.php');

//Langage
require_once($root_path.'language/fr/lang_fr_measurement.php');

$objPerson=new Person();
$insurance_obj=new Insurance;
$personell_obj = new Personell;
$measurement_obj=new Measurement;
#CSS et JS
ob_start();
?>
<style type="text/css">
    .label{
        /*background-color: #00FFFF;
        padding: 2px;
        width: 180px;*/
        font-size: 10px;
        color:black;
    }
    .bleu{
        background-color: #02327E;
        border-color: #02327E;
    }
    .data{
        background-color: #EEEEEE;
       /*  padding: 2px; */
    }
</style>
<script type="text/javascript">
    var tabId=[];//Déclaration du tableau des textarea
    var tabVal=[];
    var idEncounterNr="";
    $(document).ready(function(){

        //$("#add_element").hide();
        //$("#add_form").hide();   

       /* var javMode='<?php echo $mode;?>';         
        if(javMode=='update'){
            $('#add_form').show();
        }
       */
        idEncounterNr=$('input[name="encounter_nr"]').val();
        //Recupération des labels
        tabId=[];
        $("form textarea").each(function(){
          tabId.push(this.id);
        });

    });

    //Fonction d'enregistrement automatique du rapport
    function autosave(id){
        //Recupération des valeurs des id
        tabVal=[]
        for(i=0;i<tabId.length; i++){
            idVal=$("#"+tabId[i]+"").val();
            tabVal.push(idVal);
         
        }
        //recupération de la date heure
        now=new Date();
        annee=now.getFullYear();
        mois=now.getMonth();
        jour=now.getDate();
        heure=now.getHours();
        minute=now.getMinutes();
        seconde=now.getSeconds();
       
        if(idEncounterNr!=''){
          if(tabVal[0]!='' || tabVal[1]!=''){//Si la modalité d'entrée ou le motif est saisi
             $.post("<?php echo $root_path.'modules/ajax/ajaxRegisterReport.php';?>",
                  {encounter_nr:idEncounterNr,tabId:tabId, tabVal:tabVal},
                  function(data){}
              );
          }
        }
     
        resultat = 'Seconde'+seconde+' '+idEncounterNr;
        document.getElementById(id).innerHTML = tabVal+resultat;
        setTimeout('autosave("'+id+'")', 60000);
        return true;
    }
   
    function chk_form() {
        if(document.getElementById('label_name').value==''){
            alert("Veuillez entrer un nom pour le champ à ajouter");
            document.getElementById('label_name').focus();
            return false;
        }
    }

    function showAddForm(id_div){
        //alert('salut les gard');
        $(document).ready(function(){
            var div_form = $("#"+id_div);
            if($(div_form).is(":visible")){
                $(div_form).hide();
            }else{
                $(div_form).show();    
            }
        });
    }

    function showPrintParametre(){

        var urlholder='<?php echo $root_path;?>modules/registration_admission/gui_bridge/default/print_parameter.php?lang=fr';
        window.showModalDialog(urlholder,null,"dialogWidth:380px;dialogHeight:550px;center:0;scroll:0;help:0;status:0");
           
    }
    //  Script End -->
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


        $("#datedentree").datetimepicker({
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
        $("#selectLabel").hide();
        
        $("input[name='position']").click(function(){
            if($(this).val()=="after"){
                $("#selectLabel").show(); 
            }else{
                $("#selectLabel").hide();
            }

        })



    });

    function chkAddElement(){
        if($("input[name='label_name']").val()==""){
            alert("Veuillez saisir le nom de l'élément");
            $("input[name='label_name']").focus();
            return false;
        }else{
            return true;
        }
    }
   
</script>
<?php
$js=ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript', $js);

#BreadCrumbs
$breadcrumb=array('DOSSIER MEDICAL', "RAPPORT D'HOSPITALISATION");
$smarty->assign('breadcrumbArray', $breadcrumb);

ob_start();

#Recuperation du numero d'admission
if(isset($_SESSION['sess_en']) && !empty($_SESSION['sess_en'])){
    $encounter_nr = $_SESSION['sess_en'];
}else if(isset($_SESSION['sess_full_en']) && !empty($_SESSION['sess_full_en'])){
    $encounter_nr = $_SESSION['sess_full_en'];
}else if(isset($_GET['encounter_nr']) && !empty($_GET['encounter_nr'])){
    $encounter_nr = $_GET['encounter_nr'];
    //$_SESSION['sess_en']=$encounter_nr;
}
else $encounter_nr=$objEncounter->isPIDCurrentlyAdmitted($pid);

//var_dump($encounter_nr);
#Verification de la presence de rapport
$result_report = $ecombill->getAllReport($encounter_nr);

#Mode d'affichage
if(isset($_GET) && !empty($_GET['mode'])){
    $mode=$_GET['mode'];
    if(isset($_GET['encounter_nr']) && !empty($_GET['encounter_nr'])){
         $encounter_nr=$_GET['encounter_nr'];
    }
}
else{
    if(is_object($result_report) &&  $result_report->RowCount()!=0 ){
        $mode="show";
    }
    else $mode="form";
}

if(isset($encounter_nr) && !empty($encounter_nr)){
    $dbtable='care_encounter';
    $objEncounter->loadEncounterData($encounter_nr);
    $objEncounter=new Encounter($encounter_nr);

    /////////////Pour ajouter un élement au formulaire/////////////////
    ob_start();
    $result_list = $ecombill->getAllLabel();
    if(is_object($result_list)){
        while($line=$result_list->FetchRow()){
            echo'<option value="'.$line['position'].'">'.$line['libelle'].'</option>';

        }
    }
    $optionsLabel=ob_get_contents();
    ob_clean();
    $smarty->assign('optionsLabel', $optionsLabel);
    $smarty->assign('pid', $pid);
    $smarty->assign('encounterNr', $encounter_nr);
                     
}

#Recuperation des informations constantes
$dep=$objEncounter->getValue('department',$encounter_nr);
$allTypes=$measurement_obj->getAllMsrTypes($dep);
$measure=$measurement_obj->getMeasurementByPidEncounterNr($encounter_nr);

$measurement_nr=$measure[0]['nr'];

$measure=$measurement_obj->getMeasuresByNr($measurement_nr);

foreach($measure as $measure_line){
    $name=$measure_line['type'];
    $tab_constante[$name]=array("value"=>$measure_line['value'],"unite"=>$measure_line['unite'],"nr"=>$measure_line['nr']);
}


#Traitement et assignation du formulaire de configuration du rapport
$list_label=$ecombill->getAlllabel();
$nbreligne=$list_label->RowCount();
if($nbreligne%2){
    $pair=false;
}else $pair=true;

ob_start();
    if(is_object($list_label) && $list_label->RowCount()!=0){
       
        echo'<table border="0">';

        while($line=$list_label->FetchRow()){
              //on commence le formulaire ici
              if($line['print_option']=='O') {
                $checked=' checked';
              }else $checked=' ';
              echo'<tr>';
              echo'<td class="label"><label for="'.ucfirst($line['label_col_nam']).'">'.$line['libelle'].' : </label></td>';
              echo'<td><input type="checkbox" name="'.$line['label_col_nam'].'" id="'.$line['label_col_nam'].'" '.$checked.' /></td>';
              echo'<tr>';

        }
        echo'<tr><td colspan="2" class="text-center"><input type="submit" name="print_form_parameter" value="Enregistrer" class="btn btn-primary btn-sm">';
        echo'</td>';
        echo'</tr>';
        echo' </table>';
    }

    $formConfiguration=ob_get_contents();
ob_clean();
$smarty->assign('formConfiguration', $formConfiguration);

if($print_form_parameter){
    $tab_label = array();
    $tab_data = array();
    $result_list_label = $ecombill->getAllLabel();
    while($item=$result_list_label->FetchRow()){
         $tab_label[]=$item['label_col_nam'];
         if(isset($_POST[$item['label_col_nam']])){
            $tab_data[]='O';
         }else $tab_data[]='N';
    }
    $sql_ok=true;
    for ($i=0;$i<=count($tab_label);$i++){
    if ($ecombill->registerReportPrintParameter($tab_label[$i],$tab_data[$i])) {
      $sql_ok=true;
    }else{
      $sql_ok=false;
      break;
    } 
    }
    if($sql_ok){
        $msg="Modification effectuée avec succès";
        $msgType="success";
    } 
    else {
        $msg=" Echec Modification";
        $msgType="danger";
    }

}

#Traitement du formulaire d'ajout d'une ligne dans la base de données
if($add_colomn){
    $label=strtolower(($_POST['label_name']));
    $position = $_POST['position'];
    $position_itermediaire = $_POST['list_label'];
    $pid = $_POST['pid'];
    if($ecombill->addcolum($label,$position,$position_itermediaire,$pid)){
        $msg="Enregistrement effectué avec succès";
        $msgType="success";
    }else{
        $msg=" Echec Enregistrement";
        $msgType="danger";
    }
}

#Traitement du formulaire
if($register_form){
    
    $tab_label = array();
    $tab_data = array();
    $mode= $_POST['mode'];
    $pid=$_POST['pid']; //pid du patient
    $encounter_nr=$_POST['encounter_nr'];
    $userLogin=$_SESSION['sess_login_userid'];
    $userPid=$objPerson->getUserPid($userLogin);
    $result_list_label = $ecombill->getAllLabel();

    //var_dump($mode);
    
    while($item=$result_list_label->FetchRow()){
        if(substr_count($item['label_col_nam'], 'date')){
            $tab_label[]=$item['label_col_nam'];
            $tab_data[]=parseDate($_POST[$item['label_col_nam']],'D/M/Y','/','','Y-M-D','-');
        }else {
            $tab_label[]=$item['label_col_nam'];
            $tab_data[]=$_POST[$item['label_col_nam']];
        }
    }
    $rapportExist=$ecombill->getAllReport($encounter_nr);
    $existeReelle=$rapportExist->FetchRow();
    if(!$existeReelle) $mode="insert";
  
    //if(!$rapportExist) $mode="insert";

    if($mode=="insert" || $mode==""){
        $mode="insert";
        //var_dump($ecombill->registerReport($mode,$encounter_nr,$userPid,$tab_label,$tab_data));
        if ($ecombill->registerReport($mode,$encounter_nr,$userPid,$tab_label,$tab_data)) {
            
            $message='<span style="font-size:11px;font-weight:bold;color:#063">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rapport enregistr&eacute; </span>';
            $afficher=1;
        }else $message="Echec enregistrement";

    }else {
        //var_dump($tab_data);
        //var_dump($ecombill->registerReport($mode,$encounter_nr,$userPid,$tab_label,$tab_data));die();
        if ($ecombill->registerReport($mode,$encounter_nr,$userPid,$tab_label,$tab_data)) {
            //unset($mode);
            $mode="show";
            $message='<span style="font-size:11px;font-weight:bold;color:#063">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Modifications enregistrées; </span>';
        }else $message="Echec Modification";

    }

    /**********************Insertion dans la table admission************************/
    $tab_encounter_admission = array( 
            'raison'=> addslashes($_POST['motifdhospitalisation']),
            'collateral_exam' => addslashes($_POST['examenscliniques']),
            'referrer_diagnosis' => addslashes($_POST['diagnostics']),
            'para_test' => addslashes($_POST['examensparacliniques']),
            'referrer_recom_therapy' => addslashes($_POST['traitements']),
            'modify_id'=> $userPid,
            'history' => "CONCAT(history,\"\n update: ".date('Y-m-d H:i:s')." par ".$userPid." \")"
        );

    $objEncounter->setDataArray($tab_encounter_admission);
  
    //var_dump($tab_encounter_admission);
    if($objEncounter->updateEncounterFromInternalArray($encounter_nr)) {
        if(isset($measurement_nr)) $measurement_obj->attributeEncounter($measurement_nr,$encounter_nr);
        else {

            $create_time=date('Y-m-d H:i:s');
            $main['encounter_nr']=$encounter_nr;
            $main['admit_measurement']='O';
            $main['pid']=$pid;
            $main['status']='O';
            $main['history']='Reg :'.$_SESSION['sess_login_username'].' '.$create_time;
            $main['modify_id']=$userPid;
            $main['modify_time']=date('Y-m-d H:i:s');
            $main['create_id']=$userPid;
            $main['create_time']=date('Y-m-d H:i:s');
            
            if($measurement_obj->insertDataFromArray($main))
                $measurement_nr=$db->Insert_ID();
        }
        $measurement_obj->setTable('care_measurement_sub');

        foreach($allTypes as $element){
            $LD=$element['LD_var'];
            $msr_type_nr=$element['nr'];
            $type=$element['type'];
            $unit_type_name='unit_'.$type;
            $unit_type_nr=$element['measurement_type'];
            $nr=$LD.'_nr';
            $value=trim($$type);
            if((isset($value)) && (!empty($value))) {
                $Array['value']=$value;
                $Array['msr_type_nr']=$msr_type_nr;
                if((isset($$unit_type_name)) && (!empty($$unit_type_name))){
                    $Array['unit_nr']=$$unit_type_name;
                    $Array['unit_type_nr']=$unit_type_nr;
                }
                $Array['create_time']=$create_time;
                $Array['create_id']=$userPid;
                $Array['modify_id']=$userPid;

                if((isset($$nr)) && (!empty($$nr))){
                    $Array['history']='concat(history,"Modification :'.$_SESSION['sess_login_username'].' '.$create_time.'")';
                    $measurement_obj->updateDataFromArray($Array,$$nr);

                }else{
                    
                    $Array['measurement_nr']=$measurement_nr;
                    $Array['history']='Reg :'.$_SESSION['sess_login_username'].' '.$create_time;
                    $measurement_obj->insertDataFromArray($Array);
                }
            }
        }
    }
    /*echo '<br><br>';
    var_dump($objEncounter->sql);*/
}

ob_start();

if($encounter_nr){//Pour savoir si la personne est admise 

    if($mode=="show"){
        $url='?pid='.$pid.'&nr_admission='.$encounter_nr;
        echo '<div class="row">';
        echo '<div class="col-sm-3">';
        echo '<a target="_blank" href="'.$root_path.'modules/medocs/print_rapport_hospitalisation.php'.$url.'" class="btn btn-sm btn-primary" style="color:#fff;">
             Imprimer le rapport <span class="glyphicon glyphicon-arrow-right"></span></a>';
        echo '</div>';
        echo '<div class="col-sm-3 text-center">';
        echo '<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#configurationRapport">Paramètre de l\'impression <span class="glyphicon glyphicon-arrow-right"></span></boutton>';
        echo '</div>';
        echo '<div class="col-sm-3 text-center">';
        echo '<button class="btn btn-sm btn-primary" onclick="return showAddForm(\'add_form\');">Voir plus de détails <span class="glyphicon glyphicon-arrow-right"></span></boutton>';
        echo '</div>';
        echo '<div class="col-sm-3 text-right">';
        #Ceci est temporaire
        echo '<button  data-toggle="modal" data-target="#addElement" class="btn btn-sm btn-primary">Ajouter un élément <span class="glyphicon glyphicon-arrow-right"></span></boutton>';
        #fin du temporaire
        echo '</div>';
        echo '</div>';
        echo '<br><br>';
        if(!empty($measure)){
            $nbTypes=count($allTypes);
            $nb_ligne=ceil($nbTypes/3);
            $nb_column=3;
            $constante = '<table width="100%" border="1" cellspacing="0" class="table table-bordered">';
            for($x=0;$x<$nb_ligne;$x++){
                $position1=($x*$nb_column);
                if($position1>=$nbTypes) break;
                for($y=0;$y<$nb_column;$y++){
                    if($y==0) $constante.='<tr>';
                    $position=($x*$nb_column)+$y;
                    $element=$allTypes[$position];
                    if(isset($element)){
                        $LD=$element['LD_var'];
                        $var=$$LD;
                        $type=$element['type'];
                        $measurement_type=$element['measurement_type'];
                        $unit_default=$element['unit_default'];
                        $unit_nr=$tab_constante[$LD]['unite'];
                        if(!empty($tab_constante[$type]['value'])){
                            $val=$tab_constante[$type]['value'].' '.$tab_constante[$type]['unite']; 
                            //echo explode(" ",$val)[0].";";

                            if(intval(explode(" ",$val)[0])>0){
                                $constante.='<td>'.$var.':</td><td>'.$val.'</td>'; 
                            }
                        
                        } 
                        else{
                            $val='-';
                            //$constante.='<td>'.$var.':</td><td>'.$val.'</td>';
                        } 
                       
                    }
                    else{
                        $constante.='<td></td><td></td>';
                    }
                    if($y==2) $constante.='</tr>';
                }
            }
            $constante.='</table>';
        }
        echo"<div id='add_form'>";
            echo'<table border="0" cellspacing="0" class="table table-responsive table-bordered">';
     
            $list_label=$ecombill->getAlllabel();
            if(is_object($list_label) && $list_label->RowCount()!=0){
                while($line=$list_label->FetchRow()){
                  //on commence le formulaire ici
                  echo'<tr>';
                  echo'<td style="width:25%;"><label>'.$line['libelle'].' </label></td>';
                  if(substr_count($line['label_col_nam'], 'date')){
                     $date=$ecombill->getValue($encounter_nr,$line['label_col_nam']);
                     if($date!='' && $date!='--'){
                        $date=parseDate($date,'Y-M-D','-','','D/M/Y','/');
                     }else $date="";
                     
                     echo'<td class="data">'.$date.'</td>';
                  }else if($line['label_col_nam']=='constantes'){
                      echo'<td class="data">'.$constante.'</td>';
                  }else if($line['label_col_nam']=='docteur'){
                    $docteur_pid=$ecombill->getValue($encounter_nr, $line['label_col_nam']);
                    echo '<td class="data">'.$objPerson->getValue('name_last', $docteur_pid).' '.$objPerson->getValue('name_first', $docteur_pid).'</td>';
                  }else echo'<td class="data">'.$ecombill->getValue($encounter_nr,$line['label_col_nam']).'</td>';
                  echo'<tr>';
                }
            }
            echo ' </table>';
            echo '</br>';
            echo '<center>
                    <a href="'.$baseLink.'&target=rapport&mode=update&encounter_nr='.$encounter_nr.'" id="update" class="btn btn-sm btn-success" style="color:#fff">
                    Modifier
                    </a>
                  </center>';
            echo'</br>';
            echo'</br>';

        echo"</div>";
    }
    elseif($mode=="form"){

        $nbTypes=count($allTypes);
        $nb_ligne=ceil($nbTypes/3);
        $nb_column=3;
        $constante = '<table width="100%" border="1" cellspacing="0">';
        for($x=0;$x<$nb_ligne;$x++){
            $position1=($x*$nb_column);
            if($position1>=$nbTypes) break;
            for($y=0;$y<$nb_column;$y++){
                if($y==0) $constante.='<tr>';
                $position=($x*$nb_column)+$y;
                $element=$allTypes[$position];
                if(isset($element)){
                    $LD=$element['LD_var'];
                    $var=$$LD;
                    $type=$element['type'];
                    $measurement_type=$element['measurement_type'];
                    $unit_default=$element['unit_default'];
                    $unit_nr=$tab_constante[$LD]['unite'];
                    $constante.='
                    <td>'.$var.':</td>
                    <td ';
                    if(empty($measurement_type)) $constante.='colspan="2"';
                    $constante.='>
                    <input type="text" ';
                    if(!empty($measurement_type)) $constante.= 'size="4"';
                    $constante.=' name="'.$type.'" id="'.$type.'" value="';
                    if(isset($$type)) $constante.= trim($$type);
                    else $constante.= trim($tab_constante[$type]['value'].' '.$tab_constante[$LD]['unite']);
                    $constante.= '">';
                    $constante.= '</td>';
                    if(!empty($measurement_type)){
                    $constante.= '<td>';
                    $units=$measurement_obj->getUnitByType($measurement_type);
                    $unit_type_name='unit_'.$type;
                    $constante.= '<select name="'.$unit_type_name.'">';     
                        while(list($a,$v)=each($units)){
                            $constante.= '<option value="'.$v['nr'].'" ';
                            if(isset($$unit_type_name)){
                                if($v['nr']==$$unit_type_nr)
                                    $constante.= 'selected';
                            }
                            elseif(!empty($unit_nr)){
                                if($v['nr']==$unit_nr) $constante.='selected';
                            }
                            elseif($v['nr']==$unit_default) $constante.= 'selected';
                            $constante.= '>';
                            $constante.= $v['id'];
                            $constante.= '</option>';
                        }
                    $constante.= '</select>';
                    reset($unit_types);
                    $constante.= '</td>';
                    }
                    $constante.= '
                    <input type="hidden" name="'.$LD.'_nr" id="'.$LD.'_nr" value="'.$tab_constante[$LD]['nr'].'">
                    </td>';
                   
                }
                else{
                     $constante.='<td></td><td></td>';
                }
                if($y==2) $constante.='<tr>';
            }
        }
        $constante.='</table>';

        echo"<div id='add_form'>";
            echo'<table border="0" cellspacing="0" class="table table-responsive table-bordered">';
     
            $list_label=$ecombill->getAlllabel();
            if(is_object($list_label) && $list_label->RowCount()!=0){
                while($line=$list_label->FetchRow()){
                  //on commence le formulaire ici
                  echo'<tr>';
                  echo'<td style="width:25%;"><label>'.$line['libelle'].' </label></td>';
                  if(substr_count($line['label_col_nam'], 'date')){
                     $date=$ecombill->getValue($encounter_nr,$line['label_col_nam']);
                     if($date!='' && $date!='--'){
                        $date=parseDate($date,'Y-M-D','-','','D/M/Y','/');
                     }else $date="";
                     
                     echo'<td class="data">'.$date.'</td>';
                  }else if($line['label_col_nam']=='constantes'){
                      echo'<td class="data">'.$constante.'</td>';
                  }else if($line['label_col_nam']=='docteur'){
                    $docteur_pid=$ecombill->getValue($encounter_nr, $line['label_col_nam']);
                    echo '<td class="data">'.$objPerson->getValue('name_last', $docteur_pid).' '.$objPerson->getValue('name_first', $docteur_pid).'</td>';
                  }else echo'<td class="data">'.$ecombill->getValue($encounter_nr,$line['label_col_nam']).'</td>';
                  echo'<tr>';
                }
            }
            echo ' </table>';
            echo '</br>';
            echo '<center>
                    <a href="'.$baseLink.'&target=rapport&mode=update&encounter_nr='.$encounter_nr.'" id="update" class="btn btn-sm btn-success" style="color:#fff">
                    Modifier
                    </a>
                  </center>';
            echo'</br>';
            echo'</br>';

        echo "</div>";
    }
    else{

        $list_label=$ecombill->getAlllabel();
        $nbTypes=count($allTypes);
        $nb_ligne=ceil($nbTypes/3);
        $nb_column=3;
        $constante = '<table width="100%" border="1" cellspacing="0">';
        for($x=0;$x<$nb_ligne;$x++){
            $position1=($x*$nb_column);
            if($position1>=$nbTypes) break;
            for($y=0;$y<$nb_column;$y++){
                if($y==0) $constante.='<tr>';
                $position=($x*$nb_column)+$y;
                $element=$allTypes[$position];
                if(isset($element)){
                    $LD=$element['LD_var'];
                        $var=$$LD;
                        $type=$element['type'];
                        $measurement_type=$element['measurement_type'];
                        $unit_default=$element['unit_default'];
                        $unit_nr=$tab_constante[$LD]['unite'];
                        $constante.='
                            <td>'.$var.':</td>
                            <td ';
                            if(empty($measurement_type)) $constante.='colspan="2"';
                            $constante.='>
                            <input type="text" ';
                                if(!empty($measurement_type)) $constante.= 'size="4"';
                            $constante.=' name="'.$type.'" id="'.$type.'" value="';
                            if(isset($$type)) $constante.= trim($$type);
                            else $constante.= trim($tab_constante[$type]['value'].' '.$tab_constante[$LD]['unite']);
                            $constante.= '">';
                            $constante.= '</td>';
                            if(!empty($measurement_type)){
                                $constante.= '<td>';
                                $units=$measurement_obj->getUnitByType($measurement_type);
                                $unit_type_name='unit_'.$type;
                                $constante.= '<select name="'.$unit_type_name.'">';     
                                    while(list($a,$v)=each($units)){
                                        $constante.= '<option value="'.$v['nr'].'" ';
                                        if(isset($$unit_type_name)){
                                            if($v['nr']==$$unit_type_nr)
                                                $constante.= 'selected';
                                        }
                                        elseif(!empty($unit_nr)){
                                            if($v['nr']==$unit_nr) $constante.='selected';
                                        }
                                        elseif($v['nr']==$unit_default) $constante.= 'selected';
                                        $constante.= '>';
                                        $constante.= $v['id'];
                                        $constante.= '</option>';
                                    }
                                $constante.= '</select>';
                                reset($unit_types);
                                $constante.= '</td>';
                            }
                            $constante.= '
                            <input type="hidden" name="'.$LD.'_nr" id="'.$LD.'_nr" value="'.$tab_constante[$LD]['nr'].'">
                            </td>';
                   
                }
                else{
                     $constante.='<td>const'.$position.'</td><td></td>';
                }
                if($y==2) $constante.='<tr>';
            }
        }
        $constante.='</table>';

        if(is_object($list_label) && $list_label->RowCount()!=0){

            echo"<div class='row'>";
            echo "<div class='form-group'>";
            echo'<form method="post" name="report_form" action="">';
            echo'<table border="0" style="width:100%;">';

            while($line=$list_label->FetchRow()){
                  //on commence le formulaire ici
                  echo'<tr>';
                  if($line['label_col_nam']=="constantes"){
                    echo'<td colspan="2" align="center" bgcolor="#02327E" style="color:#fff;line-height:30px;">RESUME CLINIQUE</td></tr><tr>';
                  }else if($line['label_col_nam']=="biologie"){
                    echo'<td colspan="2" align="center" bgcolor="#02327E" style="color:#fff;line-height:30px;">PARACLINIQUE</td></tr><tr>';                
                  }

                  echo'<td width="25%"><label>'.$line['libelle'].' : </label></td>';
              
                  if(substr_count($line['label_col_nam'], 'datedentree')){
                    $date_admission = $objEncounter->EncounterDate($encounter_nr);

                    if(is_object($date_admission) && $date=$date_admission->FetchRow()){
                        $date=parseDate($date['encounter_date'],'Y-M-D','-','','D/M/Y','/');
                    }else{
                        if(isset($date_admission)&& !empty($date_admission)){
                            $date=parseDate($date_admission,'Y-M-D','-','','D/M/Y','/');
                        }
                    }
                    echo'<td><input type="text" name="'.$line['label_col_nam'].'" value="'.$date.'" id="'.$line['label_col_nam'].'" size="10" readonly class="form-control"></td>';
                  }else if((substr_count($line['label_col_nam'], 'date'))  &&  ($line['label_col_nam']!='datedentree')){
                    if($mode=="update"){
                        $date_sortie=$ecombill->getValue($encounter_nr,$line['label_col_nam']);
                        if($date_sortie!='' && $date_sortie!="--"){
                            $date_sortie=parseDate($date_sortie,'Y-M-D','-','','D/M/Y','/');
                        }else $date_sortie=date("d/m/Y");                 
                        //echo'<td>'.$date_sortie.'</td>';
                        echo'<td><input type="text" name="'.$line['label_col_nam'].'" class="form-control input-sm" id="date_sortie" value="'.$date_sortie.'"></td>';
                    }else echo'<td><input type="text" name="date_sortie" class="form-control input-sm" id="date_sortie" value="'.date('d/m/Y').'"></td>';
                  }else if($line['label_col_nam']=="motifdhospitalisation" && $mode!="update"){
                  echo'<td><textarea name="'.$line['label_col_nam'].'" id="'.$line['label_col_nam'].'" cols="60" class="form-control">'.$raison.'</textarea></td>';  
                  }else if($line['label_col_nam']=="examenscliniques" && $mode!="update"){
                  echo'<td><textarea name="'.$line['label_col_nam'].'" id="'.$line['label_col_nam'].'" cols="60" class="form-control">'.$collateral_exam.'</textarea></td>';  
                  }else if($line['label_col_nam']=="diagnostics" && $mode!="update"){
                  echo'<td><textarea name="'.$line['label_col_nam'].'" id="'.$line['label_col_nam'].'" cols="60" class="form-control">'.$referrer_diagnosis.'</textarea></td>';  
                  }else if($line['label_col_nam']=="traitements" && $mode!="update"){
                  echo'<td><textarea name="'.$line['label_col_nam'].'" id="'.$line['label_col_nam'].'" cols="60" class="form-control">'.$referrer_recom_therapy.'</textarea></td>';  
                  }else if($line['label_col_nam']=="examensparacliniques" && $mode!="update"){
                  echo'<td><textarea name="'.$line['label_col_nam'].'" id="'.$line['label_col_nam'].'" cols="60" class="form-control">'.$para_test.'</textarea></td>';  
                  }else if($line['label_col_nam']=='constantes'){
                  echo '<td>'. $constante.'</td>';
                  }else if($line['label_col_nam']=='docteur'){
                    $list_doctor=$objPerson->getListAtPost(17);
                    if($mode=="update"){
                      $docteur=$ecombill->getValue($encounter_nr, $line['label_col_nam']);
                    }else{
                      $login=$_SESSION['sess_user_login_id'];
                      $docteur=$objPerson->getUserPid($login);
                    }              
                    echo '<td>
                            <select name="docteur" id="docteur" style="" class="form-control">
                              <option value="">S&eacute;lectionner m&eacute;decin</option>';
                              if(is_object($list_doctor)){
                                  while($ligne=$list_doctor->FetchRow()){
                                      echo '<option value='.$ligne['pid'];
                                      if((isset($docteur)) && ($docteur==$ligne['pid'])) echo ' selected="selected"';
                                      echo '>'.$ligne['name_last'].' '.$ligne['name_first'].'</option>';
                                  }
                              }
                    echo'</select>
                    </td>';
                  }
                  else echo'<td><textarea name="'.$line['label_col_nam'].'" id="'.$line['label_col_nam'].'" cols="60" class="form-control">'.$ecombill->getValue($encounter_nr,$line['label_col_nam']).'</textarea></td>';
                  echo'<tr>';

            }
            //les bouton hidden
            echo'<tr>';
            echo'<td colspan="2" align="center">';
            echo'<input type="hidden" name="encounter_nr" value="'.$encounter_nr.'">';
            echo'<input type="hidden" name="pid" value="'.$pid.'">';
            echo'<input type="hidden" name="mode" value="'.$mode.'">';
            echo'<input type="submit" name="register_form" value="Enregistrer" class="btn btn-sm btn-primary"/>';
            echo'</td>';
            echo'</tr>';
            echo' </table>';
            echo'</fieldset>';
            echo'</form>';
            echo '</div>';
            echo"</div>";

        }
    }

}else{

    /*if($listAdmission=$objEncounter->getEncounterList($pid)){
        $compteur=0;
        echo '<table class="table table-bordered table-striped-hover" id="exemple">';
        echo '<tr>';
        echo '<th class="text-center">N°</th><th class="text-center">Date d\'admission</th><th class="text-center">N° d\'admission</th><th class="text-center">-</th>';
        echo '</tr>';
        while($encounter=$listAdmission->FetchRow()){   
            $compteur++;
            echo '<tr>';
                $encounter_date=$encounter['encounter_date'];
                $encounter_nr=$encounter['encounter_nr'];
                echo '<td class="text-right">'.$compteur.'</td>';
                echo '<td class="text-center">'.parseDate($encounter_date,'Y-M-D','-','','D/M/Y','/').'</td>';
                echo '<td class="text-center">'.$encounter_nr.'</td>';
                echo '<td class="text-center"></td>';
            echo  '</tr>';
        }
        echo '</table>';
    }*/

    echo "<center><h3>Cette personne n'est pas encore admise</h3></center>";

}

if(isset($msg)&&!empty($msg)){
    $msgArray=array('type'=>$msgType, 'msg'=>$msg);
    $smarty->assign('msgArray', $msgArray);
}

$content=ob_get_contents();
ob_get_clean();
$smarty->assign('content', $content);

$smarty->assign('sMainBlockIncludeFile', 'medocs/medocs_rapport.tpl');

?>