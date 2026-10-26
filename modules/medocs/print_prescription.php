<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    require($root_path.'modules/biasa_pdf/utils.php');
    require($root_path.'modules/biasa_pdf/pdf/fpdf.php');
    require_once($root_path.'include/care_api_classes/class_prescription.php');
    require_once($root_path.'modules/caisse/utils.php');
    $obj=new Prescription;//Instanciation de prescription
    require_once($root_path.'include/care_api_classes/class_person.php');
    $person=new Person();//Instanciation de person
    $chaine=$obj->recup_chaine($prescription_nr);
    $create_login_id=$obj->getValue('create_id',$prescription_nr);
    //echo $create_login_id;
    //$create_id=$person->getUserPid($create_login_id);
    $create_id=$create_login_id;
    $title = 'ORDONNANCE MEDICALE';
    $user=ucfirst($person->getValue("title",$create_id))." ".$person->getValue("name_first",$create_id)." ".$person->getValue("name_last",$create_id);
    
    // var_dump($user);
    // die();
    //jBoanarges
    require_once($root_path.'include/care_api_classes/class_medicalActs.php');
    $act = new MedicalActs();
    //Retourner des informations sur le patient
    $en_nr=$obj->getValue('encounter_nr',$prescription_nr);
    $enc_row = $act->getPatient($act->getPatientPid($en_nr));
    $encounter_names = $enc_row['title'].' '.ucfirst($enc_row['name_first']).' '.strtoupper($enc_row['name_last']);

    if(empty($enc_row)){
        //$patient_id=$obj->getValue('encounter', $prescription_nr);
        $encounter_names=$person->getValue('title', $en_nr).' '.ucfirst($person->getValue('name_first', $en_nr)).' '.strtoupper($person->getValue('name_last', $en_nr));
    }
    // var_dump($encounter_names);
    // die();
    $explode1=explode('#',$chaine);
    $len1=count($explode1);
    ob_start();
    $d=array();
    for($i=1;$i<$len1;$i++){
       
        //$explode2=explode('%',$explode1[$i]);désactiver ce 03/07/2017 pour régler le problème de prince
        $explode2=explode('|||',$explode1[$i]);
       
        $d[$i]['num']=$i.' - ';
        $d[$i]['name']=$explode2[1];
        $d[$i]['qte']=$explode2[2];
        $d[$i]['posologie']='         '.$explode2[4];


    }
    //Décomposition de la date

    $date=parseDate($obj->getValue('prescribe_date', $prescription_nr),'Y-M-D','-','str','D M Y',' ',false);
    
    $mybody=ob_get_contents();
    ob_end_clean();


  
    $body = $mybody;
    $foot = "Consultation tous les jours ouvrables : de 8h à 12h et 15h à 20h, le Samedi de 8h à 13h et sur rendez-vous Urgences assurées 24h/24 ";
    require($root_path.'modules/biasa_pdf/print_prescription_pdf.php');
?>
