<!--Formulaire d'enregistrement de certificat -->
<?php
header('content-type: text/html; charset=utf-8' );
ob_start();
require_once ($root_path.'js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar($root_path.'js/jscalendar/', $lang, 'calendar-system', true);
$calendar->load_files();
?>
<script type="text/JavaScript">
	$(document).ready(function(){
		
	})
<!--Script Begin
    String.prototype.trim = function () {
        var str = this.replace(/^\s+/, '');
        for (var i = str.length - 1; i >= 0; i--) {
            if (/\S/.test(str.charAt(i))) {
                str = str.substring(0, i + 1);
                break;
            }
        }
        return str;
    };

    function isDateAnteriorThanAnother(str1, str2){
        var dt1   = parseInt(str1.substring(0,2),10); 
        var mon1  = parseInt(str1.substring(3,5),10);
        var yr1   = parseInt(str1.substring(6,10),10); 
        var dt2   = parseInt(str2.substring(0,2),10); 
        var mon2  = parseInt(str2.substring(3,5),10); 
        var yr2   = parseInt(str2.substring(6,10),10); 
        var date1 = new Date(yr1, mon1, dt1); 
        var date2 = new Date(yr2, mon2, dt2); 
        if(date2 < date1){
            return false; 
        } 
        return true;
    }

    function loadSickForm(value){
    	alert(value);
        if(isNaN(parseInt(value))||value==''){
            return false;
        }
        else{
        	//alert('quedal');
        	var nb_certif_type=$('#nb_certif_type').val();
        	alert(nb_certif_type);
            if(value<=parseInt(nb_certif_type)&& value>=1){
                document.newform.submit();
            }
        }
        return;
    }
    function chkSickForm(f) {
        var certif_type = document.newform.type_certif.value;
        //traitemet de chaque type de certificat
    
        if(f.sick_confirm_date.value.trim()==''){
            alert('Le champ "Date du certificat" ne doit pas Ãªtre vide ');
            f.sick_confirm_date.focus();
            return false;
        }
        switch(certif_type){
            case '1' :
            if(f.rest_time_nr.value.trim()==''){
                f.rest_time_nr.focus();
                alert('Le champ "DurÃ©e du repos" ne doit pas Ãªtre vide ');
                return false;

            }else if(isNaN(parseInt(f.nb_month.value))){
                alert('Le champ "DurÃ©e du repos" doit contenir une valeur numÃ©rique ');
                f.nb_month.focus();
                return false;
            }
            break;

            case '2' :
            if(f.admission_start_date.value.trim()==''){
                alert('Le champ "Date de dÃ©but d\'hospitalisation" ne doit pas Ãªtre vide ');
                f.admission_start_date.focus();
                return false;        
            }else if(f.admission_end_date.value.trim()==''){
                alert('Le champ "Date de fin d\'hospitalisation" ne doit pas Ãªtre vide ');
                f.admission_end_date.focus();
                return false;        
            }else if(!isDateAnteriorThanAnother(f.admission_start_date.value,f.admission_end_date.value)){
                alert('La date de dÃ©but d\'hospitalisation doit Ãªtre antÃ©rieure Ã  celle de fin d\'hospitalisation');
                f.admission_start_date.focus();
                return false;    
            }else if(f.rest_time_nr.value.trim()==''){
                f.rest_time_nr.focus();
                alert('Le champ "DurÃ©e du repos" ne doit pas Ãªtre vide ');
                return false;
            }else if(isNaN(parseInt(f.rest_time_nr.value))){
                alert('Le champ "DurÃ©e du repos" doit contenir une valeur numÃ©rique ');
                f.rest_time_nr.focus();
                return false;
                
            }else if(f.pg_rest_start.value.trim()==''){
                alert('Le champ "date de dÃ©but du repos" ne doit pas Ãªtre vide ');
                f.pg_rest_start.focus();
                return false;
                
            }else if(!isDateAnteriorThanAnother(f.admission_end_date.value, f.pg_rest_start.value)){
                alert('La date de dÃ©but du repos doit Ãªtre antÃ©rieure Ã  celle de fin d\'hospitalisation');
                f.pg_rest_start.focus();
                return false;
            }      
            break;
            case '3' :
            if(f.rest_time_nr.value.trim()==''){
                f.rest_time_nr.focus();
                alert('Le champ "DurÃ©e du repos" ne doit pas Ãªtre vide ');
                return false;

            }else if(isNaN(parseInt(f.nb_month.value))){
                alert('Le champ "DurÃ©e du repos" doit contenir une valeur numÃ©rique ');
                f.nb_month.focus();
                return false;
            }
            break;
            
            case '4' : 
            if(f.nb_month.value.trim()==''){
                f.nb_month.focus();
                alert('Le champ "Nombre de mois" ne doit pas Ãªtre vide ');
                return false;

            }else if(isNaN(parseInt(f.nb_month.value))){
                alert('Le champ "Nombre de mois" doit contenir une valeur numÃ©rique ');
                f.nb_month.focus();
                return false;

            }else if(parseInt(f.nb_month.value)>9){
                alert('Le champ "Nombre de mois" est erronnÃ© ');
                f.nb_month.focus();
                return false;
            }else if(f.pregnant_start_date.value.trim()==''){
                alert('Le champ "dÃ©but de la grossesse" ne doit pas Ãªtre vide ');
                f.pregnant_start_date.focus();
                return false;

            }else if(f.birth_start_date.value.trim()==''){
                alert('Le champ "date prÃ©vue pour l\'accouchement" ne doit pas Ãªtre vide ');
                f.birth_start_date.focus();
                return false;
            
            }else if(!isDateAnteriorThanAnother(f.pregnant_start_date.value,f.birth_start_date.value)){
                alert('La date de dÃ©but de la grossesse doit Ãªtre antÃ©rieure Ã  celle de l\'accouchement');
                f.pregnant_start_date.focus();
                return false;
            
            }else if(f.pg_rest_start.value.trim()==''){
                alert('Le champ "date du congÃ© prÃ©natal" ne doit pas Ãªtre vide ');
                f.pg_rest_start.focus();
                return false;
                
            }else if(!isDateAnteriorThanAnother(f.pg_rest_start.value,f.birth_start_date.value)){
                alert('La date de dÃ©but du congÃ© doit Ãªtre antÃ©rieure Ã  celle de l\'accouchement');
                f.birth_start_date.focus();
                return false;
            }      
            break;

            case '5' :
            if((f.infant_sex.value!=1)&&(f.infant_sex.value!=2)){
                alert('Veuillez sÃ©lectionner le sexe de l\'enfant');
                f.infant_sex.focus();
                return false;
            }else if(!isDateAnteriorThanAnother(f.sick_confirm_date.value,f.birth_start_date.value)){
                alert('La date de dÃ©but de la grossesse doit Ãªtre antÃ©rieure Ã  celle de l\'accouchement');
                f.pregnant_start_date.focus();
                return false;
            }     
            break;

            case '6' : 
            if(f.admission_start_date.value.trim()==''){
                alert('Le champ "Date prÃ©vue pour l\'hospitalisation" ne doit pas Ãªtre vide ');
                f.admission_start_date.focus();
                return false; 
            }
            break;
            
            case '10' :
            if(f.nb_month.value.trim()==''){
                f.nb_month.focus();
                alert('Le champ "Nombre de mois" ne doit pas Ãªtre vide ');
                return false;

            }else if(isNaN(parseInt(f.nb_month.value))){
                alert('Le champ "Nombre de mois" doit contenir une valeur numÃ©rique ');
                f.nb_month.focus();
                return false;

            }else if(parseInt(f.nb_month.value)>9){
                alert('Le champ "Nombre de mois" est erronnÃ© ');
                f.nb_month.focus();
                return false;
            }else if(f.rest_time_nr.value.trim()==''){
                f.rest_time_nr.focus();
                alert('Le champ "DurÃ©e de la pÃ©riode de voyage" ne doit pas Ãªtre vide ');
                return false;

            }else if(isNaN(parseInt(f.nb_month.value))){
                alert('Le champ "DurÃ©e de la pÃ©riode de voyage" doit contenir une valeur numÃ©rique ');
                f.nb_month.focus();
                return false;
            }
            break;
        }

        return true; 
    }
    function chkNewForm(v) {
        if(v==""||v=="<?php echo $dept_nr; ?>"){
            return false;
        }else{
            return true;
        }
    }
    //  Script End -->
</script>
<?php
	$jsComplete=ob_get_contents();
	ob_end_clean();
	$smarty->append('JavaScript',$jsComplete);

	$LDCreateNewForm = 'Choisir le type de certificat m&eacute;dical' ;
	$certif_type = array(
	1 => 'Repos ordinaire',
	2 => 'Repos apr&egrave;s hospitalisation',
	3 => 'Repos prolong&eacute; apr&egrave;s hospitalisation',  
	4 => 'Grossesse',
	5 => 'Accouchement',
	6 => 'Hospitalisation',
	7 => 'Aptitude Ã  vivre en communautÃ©',
	8 => 'Inaptitude Ã  faire du sport',
	9 => 'Inaptitude Ã  travailler',
	10 => 'Voyage pour femme enceinte');
	$nb_certif_type = count($certif_type);
	$smarty->assign('nb_certif_type',$nb_certif_type);
	if($sex!='f'){
	    $certif_type = array(
	    1 => 'Repos ordinaire',
	    2 => 'Repos apr&egrave;s hospitalisation',
	    3 => 'Repos prolong&eacute; apr&egrave;s hospitalisation',  
	    6 => 'Hospitalisation',
	    7 => 'Aptitude Ã  vivre en communautÃ©',
	    8 => 'Inaptitude Ã  faire sport',
	    9 => 'Inaptitude Ã  travailler'); 
	}

	$tab_legend = array(
    'Certificat mÃ©dical pour repos ordinaire',
    'Certificat mÃ©dical pour repos apr&egrave;s hospitaliasation',
    'Certificat mÃ©dical pour repos prolongÃ© apr&egrave;s hospitaliasation',
    'Certificat mÃ©dical pour grossesse',
    'Certificat mÃ©dical d\'accouchement',
    'Certificat mÃ©dical pour hospitalisation',    
    'Certificat mÃ©dical d\'Aptitude Ã  vivre en communautÃ©',
    'Certificat mÃ©dical d\'inaptitude &agrave; faire du sport',
    'Certificat mÃ©dical d\'inaptitude &agrave; travailler',
    'Certificat mÃ©dical de voyage pour femme enceinte'
    );

	//Selection du type de certificat
	$smarty->assign('newFormAction','medocs.php'.URL_APPEND.'&target='.$target.'&pid='.$pid);
	$smarty->assign("LDLegendNouveauCertificat",$LDLegendNouveauCertificat);
	$smarty->assign("LDTypeCertificat",$LDTypeCertificat);
    asort($certif_type); //Ordonner le tableau des type de certificat
    $selectTypeOptions=""; //Initialisation
    foreach($certif_type as $key=>$value){
	    $selectTypeOptions.='<option value="'.$key.'" ';
	    $selectTypeOptions.=($type_certif==$key)?'selected="selected"' : ''; //Selectionner la valeur par dÃ©faut
	    $selectTypeOptions.=">".$value."</option>";            
    }

    if(isset($type_certif)){
    	//var_dump($type_certif);
    	$smarty->assign('typeCertif',true);
    	$smarty->assign('certifLegend',$tab_legend[$type_certif-1]);
    	$smarty->assign('doctorId',$doctor);
    	$smarty->assign('certifDate',$calendar->show_calendar($calendar,$date_format,'sick_confirm_date'));
    }
    $smarty->assign('selectTypeOptions',$selectTypeOptions);
	$smarty->assign('sMainBlockIncludeFile','medocs/certificatFrm.tpl');
?>