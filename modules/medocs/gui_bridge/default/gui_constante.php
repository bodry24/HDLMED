<?php
require_once($root_path.'include/care_api_classes/class_medicalActs.php');
$objMedical = new MedicalActs();
$doctorInfo = $objMedical->getPatient($objMedical->getUserPid($_SESSION['sess_user_login_id']));
$doctor = $doctorInfo['title'].' '.$doctorInfo['name_last'].' '.$doctorInfo['name_first'];

$lang_tables[]='departments.php';
$lang_tables[]='measurement.php';

# Load the encounter class
require_once($root_path.'include/care_api_classes/class_encounter.php');
//$objEncounter=new Encounter($_SESSION['sess_full_en']);

require_once($root_path.'include/care_api_classes/class_measurement.php');
$objMeasurement=new Measurement;

//Tous les type de mésure valide
$allTypes=$objMeasurement->getAllMsrTypes();
$count=count($allTypes);
$nb_boucle=ceil($count/2);
$unit_types=$objMeasurement->getUnits();

# Prepare unit ids in array
$unit_ids=array();
while(list($x,$v)=each($unit_types)){
    $unit_ids[$v['nr']]=$v['id'];
}
reset($unit_types);

#Load class Person
require_once($root_path.'include/care_api_classes/class_person.php');
$objPerson=new Person();

$baseLink=$root_path."modules/medocs/medocs.php".URL_APPEND."&target=constante&pid=".$pid;
$Time=date("d/m/Y H:i:s");



$db->Execute("SET NAMES UTF-8");


#CSS et JS
ob_start();
?>
<style type="text/css">
	
</style> 
<script type="text/javascript" src='<?php echo $root_path."js/tinymce/tinymce.min.js";?>'></script>
<script type="text/javascript">
	
    //Affiche le détail d'une constante prise
    function afficherDetail(element){
        $.post("<?php echo $root_path.'modules/ajax/ajaxMedocsMedocs.php?target=constante&action=detail';?>",
            {nr:element},
            function(data){
                $('#detailContent > tr').remove();//Je supprime tous les tr d'abord
                $("#detailContent").append(data);
        });
    }
    //Validation du formulaire
    function checkForm(){
    	if($("#type_certificat").val()==0){
    		alert("Veuillez sélectionner le Type de certificat");
    		$("#type_certificat").focus();
    		return false;
    	}
    	return true;
    }

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

            },
            order:[[1, 'desc']]
           
        });

      
        $("#date_start").datetimepicker({
            format:'dd/mm/yyyy',
            autoclose:true,
            minView:2
        });



    });
   
</script>
<?php
$js=ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript', $js);


#Breadcrumb
$breadcrumb=array("DOSSIER MEDICAL","CONSTANTE");
$smarty->assign("breadcrumbArray", $breadcrumb);

#Préparation des données pour la mise à jour
if(isset($option)){
    switch ($option) {
        case 'edit':
            
            break;
        case 'delete':
            if($objMeasurement->delete($nr)){
               // echo "Suppression effectué";
            }else{
                //echo "Suppression Non effectué";
            }
            break;
        default:
            break;
    }
}

#Enregistrement des constantes
if(isset($save)){
    $state=true;
    if(isset($option)){
        $nr=$nr;

        $modify_id=$userPID;
        $modify_time=date('Y-m-d H:i:s');
        $history='concat(history,";","Modifier par '.$userName.' le '.$time.'")';

    }else{
        $create_time=date('Y-m-d H:i:s');
        $create_id=$userPID;
        $modify_id="";
        $modify_time="";
        $history='Creer par '.$userName.' le '.$Time;
    }

    if($current_encounter) {
        $admit_measurement='O';
        $encounter_nr=$encounter_nr;
    }
    else {
        $admit_measurement='N';
        $encounter_nr=0;
        $pid=$pid;
    }
    $constData=array(
        "admit_measurement"=>$admit_measurement,
        "pid"=>$pid,
        "encounter_nr"=>$encounter_nr,
        "status"=>'O',
        "history"=>$history,
        "modify_id"=>$userPID,
        "modify_time"=>$modify_time,
        "create_id"=>$create_id,
        "create_time"=>$create_time
    );

    if(!isset($nr)){//Ceci pour qu'on enregistre plus la prise de constante mais ses élements
        if($objMeasurement->insertFromArray("care_encounter_measurement",$constData)){
            $measurement_nr=$db->Insert_ID();
        }
        else{
            echo $objMeasurement->sql;
            $state=false;
        }
    }else{
        $measurement_nr=$nr;
    }
       
    if($state){

        foreach($allTypes as $measure){
            $name=$measure['type'];         
            $value=$$name;
            
            $unit='unit_'.$measure['name'];
            
            if((isset($value)) && (!empty($value))){
                $const['measurement_nr']=$measurement_nr;
                $const['value']=$value;
                $const['msr_type_nr']=$measure['nr']; 
                $const['unit_nr']=$$unit;
                $const['unit_type_nr']=$measure['measurement_type'];
                $const['status']='O';
                $const['history']=$history;
                $const['create_time']=$create_time;
                $const['create_id']=$create_id;
               
                $measurement_sub_pk="";
                $measurement_sub_pkValue="";
              
                if($measurement_sub=$objMeasurement->getMeasureMeasurementType($measurement_nr,$measure['nr'])){
                    $measurement_sub_pk="nr";
                    $measurement_sub_pkValue=$measurement_sub['nr'];
                }
                if(!$objMeasurement->insertFromArray("care_measurement_sub",$const,$measurement_sub_pk,$measurement_sub_pkValue)){
                    $state=false;
                    break;
                }
            }
        }
    
    }else $state=false;

    if($state) {
        $db->Execute("COMMIT");
        $form="show";
    }
    else $db->Execute("ROLLBACK");
    $db->Execute("SET AUTOCOMMIT=true");

}

#J'affiche le formulaire de saisie des constantes
ob_start();
if(!empty($allTypes)){
    for($i=0;$i<$nb_boucle;$i++){
        $pos1=$i;
        $elt=$allTypes[1];
        $pos2=$nb_boucle+$i;
        $ligne1=$allTypes[$pos1];
        $measurement_type1=$ligne1['measurement_type'];
        $unit_default1=$ligne1['unit_default'];
        $units1=$objMeasurement->getUnitByType($measurement_type1);
       
        $nr=$_GET['nr'];//si il y a un enregistrement

        if($inputValue=$objMeasurement->getMeasureMeasurementType($nr, $ligne1['nr'])){
            $inputValue=$objMeasurement->getMeasureMeasurementType($nr, $ligne1['nr'])['value'];
        }else $inputValue="";
      
        echo '<tr>';
        echo '<td><label class="control-label">'.ucfirst($$ligne1['LD_var']).'</label></td>';
        echo '<td><input type="text" name="'.$ligne1['type'].'" size=10 maxlength=10 value="'.$inputValue.'" class="form-control input-sm"></td>';
        echo '<td>';
        if(empty($measurement_type1)) echo '<center>-</center>';
        else{
            echo '<select name="unit_'.$ligne1['name'].'" class="form-control input-sm">';        
            while(list($x,$v)=each($units1)){
                echo '<option value="'.$v['nr'].'"';
                if($v['nr']==$unit_default1) echo 'selected';
                echo '>';
                if(isset($$v['LD_var'])&&!empty($$v['LD_var'])) echo $$v['LD_var'];
                    else echo $v['name'];
                echo '</option>';
            }
            reset($unit_types);
            echo '</select>';
        }
        echo '</td>';
    
        $ligne2=$allTypes[$pos2];
        if(isset($ligne2)){
            //var_dump($ligne2);
            $measurement_type2=$ligne2['measurement_type'];
            $unit_default2=$ligne2['unit_default'];
            $units2=$objMeasurement->getUnitByType($measurement_type2);
             $nr=$_GET['nr'];//si il y a un enregistrement

            if($inputValue=$objMeasurement->getMeasureMeasurementType($nr, $ligne2['nr'])){
                $inputValue=$objMeasurement->getMeasureMeasurementType($nr, $ligne2['nr'])['value'];
            }else $inputValue="";

            echo '<td><label class="control-label">'.ucfirst($$ligne2['LD_var']).'</label></td>';
            //echo '<td><input type="text" name="'.$ligne2['type'].'" size=10 maxlength=10 value="'.$$ligne2['name'].'" class="form-control input-sm"></td>';
            echo '<td><input type="text" name="'.$ligne2['type'].'" size=10 maxlength=10 value="'.$inputValue.'" class="form-control input-sm"></td>';
            echo '<td>';
            if(empty($measurement_type2)) echo '<center>-</center>';
            else{
                echo '<select name="unit_'.$ligne2['name'].'" class="form-control input-sm">';        
                while(list($x,$v)=each($units2)){
                    echo '<option value="'.$v['nr'].'"';
                    if($v['nr']==$unit_default2) echo 'selected';
                    echo '>';
                    if(isset($$v['LD_var'])&&!empty($$v['LD_var'])) echo $$v['LD_var'];
                        else echo $v['name'];
                    echo '</option>';
                }
                reset($unit_types);
            }
            echo '</select>';
            echo '</td>';
        }
        else{
            echo '<td align="center">-</td>';
            echo '<td align="center">-</td>';
            echo '<td align="center">-</td>';
        }
        echo '</tr>';
    }
}

$form=ob_get_contents();
ob_end_clean();
$smarty->assign("formConstante", $form);

#J'affiche les constantes en bas
$listeMeasurement=$objMeasurement->getAllMeasurementByPid($pid);

if(is_object($listeMeasurement)){
    ob_start();
        $i=0;
        while ($ligne=$listeMeasurement->FetchRow()) {
            $i++;
             
            $linkEdit=$baseLink."&option=edit&nr=".$ligne['nr'];
            $linkDelete=$baseLink."&option=delete&nr=".$ligne['nr'];
            $linkConsulte='';

            $edit_link='<a href='.$linkEdit.' class="btn btn-xs btn-success" title="Editer"><i class="glyphicon glyphicon-edit" style="color:white"></i></a>';
            $delete_link='<a href='.$linkDelete.' class="btn btn-xs btn-danger" onclick="return confirm(\'Etes vous sur?\')" title="Supprimer"><i class="glyphicon glyphicon-trash" style="color:white"></i></a>';
           
            echo '<tr>';
                echo '<td align="right">'.$i.'</td>';
                echo '<td align="center">'.parseDate($ligne['create_time'],"Y-M-D","-","","D/M/Y","/", true).'</td>';
                echo '<td align="center">'.($ligne['encounter_nr']!=0?$ligne['encounter_nr']:'&nbsp;').'</td>';
                echo '<td align="left">'.$objPerson->getValue('name_last', $ligne['create_id']).' '.$objPerson->getValue('name_first', $ligne['create_id']).'</td>';
                echo '<td align="center"> 
                        '.$edit_link;
            ?>
            <span class="btn btn-xs btn-default glyphicon glyphicon-th" title="Détail" data-toggle="modal" data-target="#modalForm" onclick="afficherDetail(<?php echo $ligne['nr'];?>)"></span>
            <?php
            echo   $delete_link.' 
                     </td>';
            echo '</tr>';
        }
    $listing=ob_get_contents();
    ob_end_clean();

    $smarty->assign('listingMeasurement', $listing);
}


$smarty->assign('sMainBlockIncludeFile','medocs/medocs_constante.tpl');
?>

