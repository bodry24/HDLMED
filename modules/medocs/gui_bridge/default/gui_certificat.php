<?php
require_once($root_path.'include/care_api_classes/class_medicalActs.php'); 
$objMedical = new MedicalActs();
$doctorInfo = $objMedical->getPatient($objMedical->getUserPid($_SESSION['sess_user_login_id']));
$doctor = $doctorInfo['title'].' '.$doctorInfo['name_last'].' '.$doctorInfo['name_first'];

# Load the encounter class
require_once($root_path.'include/care_api_classes/class_encounter.php');
//$objEncounter=new Encounter($_SESSION['sess_full_en']);

#Load class Person
require_once($root_path.'include/care_api_classes/class_person.php');
$objPerson=new Person();

$baseLink=$root_path."modules/medocs/medocs.php".URL_APPEND."&target=certificat&pid=".$pid;
$Time=date("d/m/Y H:i:s");

$db->Execute("SET NAMES UTF-8");

function getLastEncounterNrByPid($pid){
    global $db;
    $sql="SELECT max(encounter_nr) as encounter_nr FROM care_encounter WHERE pid=".$pid;
    if($result=$db->Execute($sql)){
        $rs=$result->FetchRow();
        return $rs['encounter_nr'];
    }else{
        echo $sql;
    }
}
#Recuperation du n° admission autrement
if(isset($_SESSION['sess_en']) && !empty($_SESSION['sess_en'])){
    $encounter_nr = $_SESSION['sess_en'];
}else if(isset($_SESSION['sess_full_en']) && !empty($_SESSION['sess_full_en'])){
    $encounter_nr= $_SESSION['sess_full_en'];
}

if(isset($_GET)){
    if(isset($_GET['encounter_nr']) && !empty($_GET['encounter_nr'])){
        $encounter_nr=$_GET['encounter_nr'];
    }else{
        #Je fais le forcing dans ce cas
        $pid=$_GET['pid'];
        $encounter_nr=getLastEncounterNrByPid($pid);
    }   
    
}else{
    $encounter_nr=getLastEncounterNrByPid($pid);
}

//var_dump($encounter_nr);die();
#Instanciation de l'objet encounter
$objEncounter=new Encounter($encounter_nr);

#Tableau des type de Certificats
$sexe=$objPerson->getValue('sex', $pid);

$tabCertificat = array(
    
    1 => 'Repos ordinaire(Arret de travail)',
    2 => 'Repos après hospitalisation',
    3 => 'Repos prolongé après hospitalisation',  
    4 => 'Grossesse',
    5 => 'Accouchement',
    6 => 'Hospitalisation pour Intervention Chirurgicale',
    7 => 'Aptitude à vivre en communauté',
    8 => 'Inaptitude à faire du sport',
    9 => 'Inaptitude à travailler',
    10 => 'Voyage pour femme enceinte',
    /**Pour l'assurance**/
    11 => 'Voyage pour enfant',
    12 => 'Durée probale de séjour pour l\'assurance',
    13 =>'Durée indéterminée pour l\'assurance',
    14 =>'Notification d\'hospitalisation',
    15 =>'Vie',
    16 =>'Repos pour la maman',
    /**Fin assurance**/
    17 =>'Avant hospitalisation',
    18 =>'En cours d\'Hospitalisation',

);

if($sexe!='f'){
    $tabCertificat = array(
        1 => 'Repos ordinaire(Arret de travail)',
        2 => 'Repos après hospitalisation',
        3 => 'Repos prolongé après hospitalisation',  
        6 => 'Hospitalisation pour Intervention Chirurgicale',
        7 => 'Aptitude à vivre en communauté',
        8 => 'Inaptitude à faire sport',
        9 => 'Inaptitude à travailler',
        11 => 'Voyage pour enfant',
         /**Pour l'assurance**/
        11 => 'Voyage pour enfant',
        12 => 'Durée probale de s&eacute;jour pour l\'assurance',
        13 =>'Durée indéterminée pour l\'assurance',
        14 =>'Notification d\'hospitalisation',
        15 =>'Vie',
        16 =>'Repos pour la maman',
        /**Fin assurance**/
        17 =>'Avant hospitalisation',
        18 =>'En cours d\'Hospitalisation',
    ); 
}

$cptCertificat = count($tabCertificat);
$certificatsArrayObject=new ArrayObject($tabCertificat);
$certificatsArrayObject->asort();

ob_start();
	echo '<option value="">Choisir le type de certificat</option>';
	foreach ($certificatsArrayObject as $k => $v) {
		$selected=(isset($typeCertificat) && $typeCertificat==$k)?'selected=selected':"";
		echo '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
	}
$selectCertificats=ob_get_contents();
ob_end_clean();
$smarty->assign("selectOptionsCertificats", $selectCertificats);

#CSS et JS
ob_start();
?>
<style type="text/css">
	/* div.dataTables_wrapper div.dataTables_filter input {
	    margin-left: 0.5em;
	    display: inline-block;
	    width: 120px;
	}*/
</style> 
<script type="text/javascript" src='<?php echo $root_path."js/tinymce/tinymce.min.js";?>'></script>
<script type="text/javascript">
	
    function popPrintSickConfirm(nr, type) {
        urlholder='print_sick_confirm.php?&get_nr=' + nr + '&certif_type=' + type;
        window.open(urlholder,"","width=600,height=650,menubar=no,resizable=yes,scrollbars=yes");
    }

    function loadTypeCertificat(value, nr, certif_nr){
        if(nr==undefined||nr==''){
            nr = 0;
        }else{
            document.newform.sick_confirm_nr.value = nr;    
        }
        if(certif_nr==undefined||certif_nr==''){
            nr = 0;
        }else{
            document.newform.certif_nr.value = nr;    
        }

        if(isNaN(parseInt(value))||value==''){
           alert('Veuillez selectionner un type');
            return false;
        }
        else{
            if(value>=1){
                document.newform.submit();
            }
        }
        return;
    }

     function checkForm(f) {
        var certif_type = document.newform.type_certificat.value;
        //traitemet de chaque type de certificat
        if(f.sick_confirm_date.value.trim()==''){
            alert('Le champ "Date du certificat" ne doit pas être vide ');
            f.sick_confirm_date.focus();
            return false;
        }
        switch(certif_type){
            case '1' :
            if(f.rest_time_nr.value.trim()==''){
                f.rest_time_nr.focus();
                alert('Le champ "Durée du repos" ne doit pas être vide ');
                return false;

            }else if(isNaN(parseInt(f.nb_month.value))){
                alert('Le champ "Durée du repos" doit contenir une valeur numérique ');
                f.nb_month.focus();
                return false;
            }
            break;

            case '2' :
            if(f.admission_start_date.value.trim()==''){
                alert('Le champ "Date de début d\'hospitalisation" ne doit pas être vide ');
                f.admission_start_date.focus();
                return false;        
            }else if(f.admission_end_date.value.trim()==''){
                alert('Le champ "Date de fin d\'hospitalisation" ne doit pas être vide ');
                f.admission_end_date.focus();
                return false;        
            }else if(!isDateAnteriorThanAnother(f.admission_start_date.value,f.admission_end_date.value)){
                alert('La date de début d\'hospitalisation doit être antérieure à celle de fin d\'hospitalisation');
                f.admission_start_date.focus();
                return false;    
            }else if(f.rest_time_nr.value.trim()==''){
                f.rest_time_nr.focus();
                alert('Le champ "Durée du repos" ne doit pas être vide ');
                return false;
            }else if(isNaN(parseInt(f.rest_time_nr.value))){
                alert('Le champ "Durée du repos" doit contenir une valeur numérique ');
                f.rest_time_nr.focus();
                return false;
                
            }else if(f.pg_rest_start.value.trim()==''){
                alert('Le champ "date de début du repos" ne doit pas être vide ');
                f.pg_rest_start.focus();
                return false;
                
            }else if(!isDateAnteriorThanAnother(f.admission_end_date.value, f.pg_rest_start.value)){
                alert('La date de début du repos doit être antérieure à celle de fin d\'hospitalisation');
                f.pg_rest_start.focus();
                return false;
            }      
            break;
            case '3' :
            if(f.rest_time_nr.value.trim()==''){
                f.rest_time_nr.focus();
                alert('Le champ "Durée du repos" ne doit pas être vide ');
                return false;

            }else if(isNaN(parseInt(f.nb_month.value))){
                alert('Le champ "Durée du repos" doit contenir une valeur numérique ');
                f.nb_month.focus();
                return false;
            }
            break;
            
            case '4' : 
            if(f.nb_month.value.trim()==''){
                f.nb_month.focus();
                alert('Le champ "Nombre de mois" ne doit pas être vide ');
                return false;

            }else if(isNaN(parseInt(f.nb_month.value))){
                alert('Le champ "Nombre de mois" doit contenir une valeur numérique ');
                f.nb_month.focus();
                return false;

            }else if(parseInt(f.nb_month.value)>9){
                alert('Le champ "Nombre de mois" est erronné ');
                f.nb_month.focus();
                return false;
            }else if(f.pregnant_start_date.value.trim()==''){
                alert('Le champ "début de la grossesse" ne doit pas être vide ');
                f.pregnant_start_date.focus();
                return false;

            }else if(f.birth_start_date.value.trim()==''){
                alert('Le champ "date prévue pour l\'accouchement" ne doit pas être vide ');
                f.birth_start_date.focus();
                return false;
            
            }else if(!isDateAnteriorThanAnother(f.pregnant_start_date.value,f.birth_start_date.value)){
                alert('La date de début de la grossesse doit être antérieure à celle de l\'accouchement');
                f.pregnant_start_date.focus();
                return false;
            
            }else if(f.pg_rest_start.value.trim()==''){
                alert('Le champ "date du congé prénatal" ne doit pas être vide ');
                f.pg_rest_start.focus();
                return false;
                
            }else if(!isDateAnteriorThanAnother(f.pg_rest_start.value,f.birth_start_date.value)){
                alert('La date de début du congé doit être antérieure à celle de l\'accouchement');
                f.birth_start_date.focus();
                return false;
            }      
            break;

            case '5' :
            if((f.infant_sex.value!=1)&&(f.infant_sex.value!=2)){
                alert('Veuillez sélectionner le sexe de l\'enfant');
                f.infant_sex.focus();
                return false;
            }else if(!isDateAnteriorThanAnother(f.sick_confirm_date.value,f.birth_start_date.value)){
                alert('La date de début de la grossesse doit être antérieure à celle de l\'accouchement');
                f.pregnant_start_date.focus();
                return false;
            }     
            break;

            case '6' : 
            if(f.admission_start_date.value.trim()==''){
                alert('Le champ "Date prévue pour l\'hospitalisation" ne doit pas être vide ');
                f.admission_start_date.focus();
                return false; 
            }
            break;
            
            case '10' :
            if(f.nb_month.value.trim()==''){
                f.nb_month.focus();
                alert('Le champ "Nombre de mois" ne doit pas être vide ');
                return false;

            }else if(isNaN(parseInt(f.nb_month.value))){
                alert('Le champ "Nombre de mois" doit contenir une valeur numérique ');
                f.nb_month.focus();
                return false;

            }else if(parseInt(f.nb_month.value)>9){
                alert('Le champ "Nombre de mois" est erronné ');
                f.nb_month.focus();
                return false;
            }else if(f.rest_time_nr.value.trim()==''){
                f.rest_time_nr.focus();
                alert('Le champ "Durée de la période de voyage" ne doit pas être vide ');
                return false;

            }else if(isNaN(parseInt(f.nb_month.value))){
                alert('Le champ "Durée de la période de voyage" doit contenir une valeur numérique ');
                f.nb_month.focus();
                return false;
            }
            break;
            //Mes codes ici
            case '12' :
            if(f.admission_start_date.value.trim()==''){
                alert('Le champ "Date de début d\'hospitalisation" ne doit pas être vide ');
                f.admission_start_date.focus();
                return false;                
            }else if(f.rest_time_nr.value.trim()==''){
                f.rest_time_nr.focus();
                alert('Le champ "Durée probable d\'hospitalisation" ne doit pas être vide ');
                return false;
            }else if(isNaN(parseInt(f.rest_time_nr.value))){
                alert('Le champ "Durée probable de l\'hospitalisation" doit contenir une valeur numérique ');
                f.rest_time_nr.focus();
                return false;
                
            }
            break;
            case '13' : 
            if(f.admission_start_date.value.trim()==''){
                alert('Le champ "Date prévue pour l\'hospitalisation" ne doit pas être vide ');
                f.admission_start_date.focus();
                return false; 
            }
            break;
              case '14' :
            if(f.admission_start_date.value.trim()==''){
                alert('Le champ "Date de début d\'hospitalisation" ne doit pas être vide ');
                f.admission_start_date.focus();
                return false;        
            }else if(f.admission_end_date.value.trim()==''){
                alert('Le champ "Date de fin d\'hospitalisation" ne doit pas être vide ');
                f.admission_end_date.focus();
                return false;        
            }else if(!isDateAnteriorThanAnother(f.admission_start_date.value,f.admission_end_date.value)){
                alert('La date de début d\'hospitalisation doit être antérieure à celle de fin d\'hospitalisation');
                f.admission_start_date.focus();
                return false;    
            }
            break;
             case '16' : 
            if(f.mother_name.value.trim()==''){
                alert('Le champ "Nom et prénoms de la maman" ne doit pas être vide ');
                f.mother_name.focus();
                return false; 
            }
            break;


        }

        return true; 
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
            }
           
        });

        $("#birth_start_date").datetimepicker({
            format:'dd/mm/yyyy',
            autoclose:true,
            minView:2
        }); 

        $("#date_start").datetimepicker({
            format:'dd/mm/yyyy',
            autoclose:true,
            minView:2
        }); 

        $("#pregnant_start_date").datetimepicker({
            format:'dd/mm/yyyy',
            autoclose:true,
            minView:2
        }); 

        $("#pg_rest_start").datetimepicker({
            format:'dd/mm/yyyy',
            autoclose:true,
            minView:2
        });

 		$("#date_certificat").datetimepicker({
			format:'dd/mm/yyyy',
			autoclose:true,
			minView:2
		});

        $("#admission_start_date").datetimepicker({
			format:'dd/mm/yyyy',
			autoclose:true,
			minView:2
		});

		$("#admission_end_date").datetimepicker({
			format:'dd/mm/yyyy',
			autoclose:true,
			minView:2
		});

		$("#sick_confirm_date").datetimepicker({
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
if(isset($typeCertificat) && $typeCertificat>=1){
    $breadcrumb=array("DOSSIER MEDICAL","CERTIFICATS", mb_strtoupper($tabCertificat[$typeCertificat]));
}else{
    $breadcrumb=array("DOSSIER MEDICAL","CERTIFICATS");
}
 $smarty->assign("breadcrumbArray", $breadcrumb);


#Type des champs à afficher
if(isset($_GET['typeCertificat']) && !empty($_GET['typeCertificat'])){
	$typeCertificat=$_GET['typeCertificat'];
}

if(isset($typeCertificat) && intval($typeCertificat)>=1){
   
	$smarty->assign("typeCertificat", $typeCertificat);
	$smarty->assign("type_certificat", $typeCertificat);
}

#Recuperation d'un certificat
if(isset($_GET['option']) && !empty($_GET['option'])){
	$option=$_GET['option'];
	$nr=$_GET['nr'];

	if(!empty($nr)){
		switch ($option) {
			case 'edit':
				$result=$objEncounter->getSicknessConfirm($nr);
			    $certificat=$result->FetchRow();

			    $date_create=$certificat['date_create'];
			    $type_certificat=$certificat['certif_nr'];
			    	
                
                $smarty->assign('nr', $nr);
				$smarty->assign('type_certificat', $type_certificat);
			
				$smarty->assign("mode", "edit");
				
			
				break;
			case 'delete':
                
				if($objEncounter->jb_deleteSicknessConfirm($encounter_nr,$nr)){
					$msg="Le certificat a été supprimée avec succès";
					$msgType="success";
				}else{
					$msg="La suppression n'a pas réussi";
					$msgType="danger";
				}

                header("Location:".$baseLink);
				break;
			default:
				# code...
				break;
		}
	}
	
}else{
    $smarty->assign('doctor', $doctor);
	$smarty->assign('encounter_nr', $encounter_nr);
    $smarty->assign('sick_confirm_date', date('d/m/Y'));
    $smarty->assign('admission_start_date', date('d/m/Y'));
    $smarty->assign('admission_end_date', date('d/m/Y'));
    $smarty->assign('date_start', date('d/m/Y'));
    $smarty->assign('birth_start_date', date('d/m/Y'));

	$smarty->assign('mode', 'create');

}

#Traitement des données du formulaire
if(isset($save)){
	if(isset($nr) && !empty($nr)){

		$history='CONCAT(history," ;","Modifier par $userName le $Time")';
		$msg="La lettre a bien été mise à jour";

	}else{

		$history="Créer par ".$userName." le ".$Time;
		$msg="La lettre a bien été enregistrer";

	}


	////////////////////////////
    # Convert date to standard format

    $_POST['date_end']=parseDate($_POST['date_end'],'D/M/Y','/','','Y-M-D','-');
    $_POST['date_start']=parseDate($_POST['date_start'],'D/M/Y','/','','Y-M-D','-');

    $_POST['date_confirm']=parseDate($_POST['sick_confirm_date'],'D/M/Y','/','','Y-M-D','-');

    if($_POST['infant_sex']==1){
        $_POST['infant_sex']='m'; 
    }
    if($_POST['infant_sex']==2){
        $_POST['infant_sex']='f'; 
    }
  
    switch($type_certificat){
        case 2 :
            $_POST['date_end']=parseDate($_POST['admission_end_date'],'D/M/Y','/','','Y-M-D','-');
            $_POST['date_start']=parseDate($_POST['admission_start_date'],'D/M/Y','/','','Y-M-D','-');
            //$_POST['pg_rest_start']=parseDate($_POST['pg_rest_start'],'D/M/Y','/','','Y-M-D','-');
            $_POST['rest_time_nr']=$_POST['rest_time_nr'];
            $_POST['rest_time']=$_POST['rest_time'];
            break;
        case 3:
            if($_POST['admission_start_date']!=""){
                $_POST['date_start']=parseDate($_POST['admission_start_date'],'D/M/Y','/','','Y-M-D','-');
            }  
            $_POST['rest_time_nr']=$_POST['rest_time_nr'];
            $_POST['rest_time']=$_POST['rest_time'];
            break;
        case 4 :
       // var_dump($_POST);die();
            $_POST['nb_month']=$_POST['nb_month'];
            $_POST['date_end']=parseDate($_POST['birth_start_date'],'D/M/Y','/','','Y-M-D','-');
            $_POST['date_start']=parseDate($_POST['pregnant_start_date'],'D/M/Y','/','','Y-M-D','-');
            $_POST['pregnant_start_date']=parseDate($_POST['pregnant_start_date'],'D/M/Y','/','','Y-M-D','-');
            $_POST['pg_rest_start']=parseDate($_POST['pg_rest_start'],'D/M/Y','/','','Y-M-D','-');
            $_POST['birth_start_date']=parseDate($_POST['birth_start_date'],'D/M/Y','/','','Y-M-D','-');
            break;

        case 5 :
            $_POST['date_start']=parseDate($_POST['birth_start_date'],'D/M/Y','/','','Y-M-D','-');
            $_POST['infant_sex']=$_POST['infant_sex'];
            break;

        case 6 :
            $_POST['date_start']=parseDate($_POST['admission_start_date'],'D/M/Y','/','','Y-M-D','-');
            break;

        //Les codes de Felix
        case 10 :
            $_POST['nb_month']=$_POST['nb_month'];
             $_POST['rest_time_nr']=$_POST['rest_time_nr'];
            $_POST['rest_time']=$_POST['rest_time'];
            break;
        case 12 :
            $_POST['date_start']=parseDate($_POST['date_start'],'D/M/Y','/','','Y-M-D','-');
            $_POST['rest_time_nr']=$_POST['rest_time_nr'];
            $_POST['rest_time']=$_POST['rest_time'];
            break;
        case 13:
            $_POST['date_start']=parseDate($_POST['date_start'],'D/M/Y','/','','Y-M-D','-');
          
            break;
        case 14 :
             $_POST['date_start']=parseDate($_POST['admission_start_date'],'D/M/Y','/','','Y-M-D','-');
            $_POST['date_end']=parseDate($_POST['admission_end_date'],'D/M/Y','/','','Y-M-D','-');
           
            break;
        case 16:
            $_POST['date_start']=parseDate($_POST['admission_start_date'],'D/M/Y','/','','Y-M-D','-');
            //pour recupérer le nom de la maman
            $mother_name=addslashes($_POST['mother_name']);
            $_SESSION['mother_name']=$mother_name;
            break;
        case 17:
             $_POST['date_start']=parseDate($_POST['admission_start_date'],'D/M/Y','/','','Y-M-D','-');
        break;
        case 18:
             $_POST['date_start']=parseDate($_POST['admission_start_date'],'D/M/Y','/','','Y-M-D','-');
        break;

    }
    $_POST['certif_nr']=$type_certificat;
    $_POST['encounter_nr']=$encounter_nr;
    $_POST['date_create']=date("Y-m-d");
    $_POST['modify_id']=$_SESSION['sess_user_name'];
    $_POST['create_id']=$_SESSION['sess_user_name'];
    $_POST['create_time']=date('YmdHis');
    $_POST['rest_time']= addslashes($_POST['rest_time']);
    $_POST['med_name']= addslashes($_POST['med_name']);
    $_POST['med_title']= addslashes($_POST['med_title']);
    $_POST['pid'] = $doctorInfo['pid'];
    $tab=array();
    $data=array('type_certificat','admission_start_date','sick_confirm_date','admission_end_date','mode','save');
    foreach ($_POST as $k => $v) {
        if(!in_array($k, $data)){
             $tab[$k]=$v;
        } 
    }
    ///////////////////////////
	//var_dump($tab);die();
	//var_dump($objEncounter->insertFromArray('care_encounter_sickconfirm',$tab, 'nr',@$nr));die();
	if($objEncounter->insertFromArray('care_encounter_sickconfirm',$tab, 'nr',@$nr)){
		$msgType="success";
	}else{
		$msg="La lettre n'a pas été enregistrer";
		$msgType="danger";
	}
  /*  echo '<pre>';
    var_dump($objEncounter->sql);
    echo '</pre>';
    die();*/

	header("Location:".$baseLink);
}

#Affichage du message si il y en a 
if(isset($msg) && !empty($msg)){
	$smarty->assign("msg", $msg);
	$smarty->assign("msgType", $msgType);
}

#Affichage des lettres et nouvelle lettre
$urlAfficheCertificat=$baseLink."&option=affichage";
$smarty->assign('urlAfficheCertificat', $urlAfficheCertificat);

$urlNouvelleCertificat=$baseLink;
$smarty->assign('urlNouvelleCertificat', $urlNouvelleCertificat);

//if($option=="affichage"){
	ob_start();
    $listeCertificats=$objEncounter->jb_allSicknessConfirm();

	if(is_object($listeCertificats)){

		$i=0;
		while ($ligne=$listeCertificats->FetchRow()) {
			
		    $i++;

            $imgPrint = '<img '.createComIcon($root_path,'printer.png','0','absmiddle',true,'Imprimer cette lettre','Imprimer cette lettre').' >'; 
            $imgEdit = '<img '.createComIcon($root_path,'edit.png','0','absmiddle',true,'Modifier cette lettre','Modifier cette lettre').' >'; 
            $imgDelete = '<img '.createComIcon($root_path,'delete.gif','0','absmiddle',true,'Supprimer cette lettre','Supprimer cette lettre').' >'; 
            $url = $thisfile.URL_REDIRECT_APPEND."&type_nr=$type_nr&pid=".$_SESSION['sess_pid']."&sick_confirm_nr=".$other_row['nr'];

           
            $TP_edit_link ='<a href="update/sickConfirm" onclick="loadSickForm('.$other_row['certif_nr'].','.$other_row['nr'].'); return false;">'.$imgEdit.'</a>';
            $TP_delete_link ='<a href="'.$url.'&action=delete" onclick="return confirm(\'Etes vous sûr de vouloir effectuer cette suppression\');">'.$imgDelete.'</a>';


            $date_create=parseDate($ligne['date_create'], "Y-M-D", "-","","D/M/Y","/");
            $date_confirm=parseDate($ligne['date_confirm'], "Y-M-D", "-","","D/M/Y","/");
            $certificat_type = $tabCertificat[$ligne['certif_nr']];
            $save_by=$ligne['create_id'];

           
            $linkPrint=$root_path."modules/medocs/print_lettre_pdf.php?nr=".$nr."&letter_type_nr=".$ligne['letter_type_nr'];
            $linkEdit=$baseLink."&option=edit&nr=$nr&typeCertificat=".$ligne['nr'];
            $linkDelete=$baseLink."&option=delete&nr=".$ligne['nr'];

            $print_link ='<a href="#" onclick="return popPrintSickConfirm('.$ligne['nr'].','.$ligne['certif_nr'].');" class="btn btn-xs btn-primary" style="color:#fff"><i class="glyphicon glyphicon-print"></i></a>';
            $edit_link='<a href='.$linkEdit.' class="btn btn-xs btn-success" title="Editer"><i class="glyphicon glyphicon-edit" style="color:white"></i></a>';
            $delete_link='<a href='.$linkDelete.' class="btn btn-xs btn-danger" onclick="return confirm(\'Etes vous sur?\')" title="Supprimer"><i class="glyphicon glyphicon-trash" style="color:white"></i></a>';
      		
            echo '<tr>
                    <td class="text-right">'.$i.'</td>
                    <td class="text-center">'.$date_create.'</td>
                    <td>'.$certificat_type.'</td>
                    <td>'.$date_confirm.'</td>
                    <td>'.$save_by.'</td>
                    <td>
                        
                        '.$print_link.'
                        '.$delete_link.'                       
                    </td>
                  
                </tr> ';
		}

	}
	
	$listingCertificats=ob_get_contents();	
	ob_end_clean();
	
	$smarty->assign("listingCertificats", $listingCertificats);
//}

$smarty->assign('sMainBlockIncludeFile','medocs/medocs_certificats.tpl');
?>

