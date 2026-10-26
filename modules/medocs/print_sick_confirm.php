<?php
    #Don't touch the structure of $body
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    require($root_path.'modules/biasa_pdf/utils.php');
    require($root_path.'modules/biasa_pdf/pdf/fpdf.php');

    require_once($root_path.'include/care_api_classes/class_encounter.php');
    $enc_obj=new Encounter($_SESSION['sess_full_en']);
    # If $get_nr is non-empty, get the  single record 
    if(isset($get_nr) && $get_nr){
        if(!$single_obj=&$enc_obj->getSicknessConfirm($get_nr)) $get_nr=0;
    }else{
        $get_nr=0;
        
    }

    global $db;
    $db->execute("SET NAMES UTF8");

    if($get_nr){
        if($sickconfirm=$single_obj->FetchRow()){
            $med_name = $sickconfirm['med_name'];
            $med_title = $sickconfirm['med_title'];
            $rest_time_nr = $sickconfirm['rest_time_nr'];

    
            $encounter_nr = $sickconfirm['encounter_nr'];
            require_once($root_path.'include/care_api_classes/class_medicalActs.php');
            $act = new MedicalActs();
            
            //Retourne les informations sur le patient
            $enc_row = $act->getPatient($act->getPatientPid($encounter_nr));
            $encounter_names = $enc_row['title'].' '.strtoupper($enc_row['name_last']).' '.$enc_row['name_first'];
            // Retourne les informations de la maman
            // $mother_names=$enc_row['']

            $en_femal_mark =($enc_row['sex'] =='f')? 'e':'';
              

            //Retourner les infos du docteur afin de déterminer son sexe
            $enc_row = $act->getPatient($sickconfirm['pid']);
            $femal_mark =($enc_row['sex'] =='f')? 'e':'';
            /*
            * To test if rest_time exist 
            */
            if ($sickconfirm['rest_time']>0){
                $plural_mark = ($rest_time_nr>1)? 's':'';
                $rest_time = $sickconfirm['rest_time_nr'].' '; 
                switch($sickconfirm['rest_time']){
                    case 1 : 
                        $rest_time .= 'jour'.$plural_mark;
                        break;
                    case 2 : 
                        $rest_time .= 'semaine'.$plural_mark;
                        break;
                    case 3 : 
                        $rest_time .= 'mois';
                        break;
                    case 4 : 
                        $rest_time .= 'année'.$plural_mark;
                        break;
                } 
            }

            /*
            * To formatting text according to the typa of sickconfirm
            */

            switch($sickconfirm['certif_nr']){

                //Repos ordinaire
                case 1 :
                    include_once($root_path.'include/core/inc_date_format_functions.php');
                    
                    $confirm_date = formatDate2Local($sickconfirm['date_confirm'],$date_format);
                    $start_date = formatDate2Local($sickconfirm['date_start'],$date_format);

                    $body = utf8_decode("Je soussigné$femal_mark, ".utf8_encode($med_name).", ".utf8_encode($med_title).", en service à la Clinique BIASA, Certifie que l'état de santé de

                    <p style='text-align:center;'><u><b>".$encounter_names."</b></u></p>

nécessite un traitement avec repos de $rest_time à compter du $start_date.
<p>
En foi de quoi, je lui délivre le présent certificat pour faire valoir ce que de droit.
</p>  
<p align='right'>Fait à Lomé, le $confirm_date</p>");
                    break;

                //Repos après hospitalisation    
                case 2 :
                    include_once($root_path.'include/core/inc_date_format_functions.php');
                    
                    $confirm_date = formatDate2Local($sickconfirm['date_confirm'],$date_format);
                    $start_date = formatDate2Local($sickconfirm['date_start'],$date_format);
                    $end_date = formatDate2Local($sickconfirm['date_end'],$date_format);
                    $start_date_rest = formatDate2Local($sickconfirm['pg_rest_start'],$date_format);
                    
                    $body = utf8_decode("Je soussigné$femal_mark, ".utf8_encode($med_name).", ".$med_title." , en service à la Clinique BIASA, certifie que : 

                    <p style='text-align:center;'><u><b>".utf8_encode($encounter_names)."</b></u></p>

a été hospitalisé$en_femal_mark à la Clinique BIASA du $start_date au $end_date. Son état de santé nécessite un repos complémentaire de $rest_time à compter du $start_date_rest 
<p> 
En foi de quoi, je lui délivre le présent certificat pour faire valoir ce que de droit. 
</p>
<p align='right'>Fait à Lomé, le $confirm_date</p>");
                    break;

                //Repos prolongé après hospitalisation 
                case 3 :
                    include_once($root_path.'include/core/inc_date_format_functions.php');
                    
                    $confirm_date = formatDate2Local($sickconfirm['date_confirm'],$date_format);
                    $start_date = formatDate2Local($sickconfirm['date_start'],$date_format);

                    $body =utf8_decode("Je soussigné$femal_mark,  ".utf8_encode($med_name).", ".utf8_encode($med_title)." , en service à la Clinique BIASA, Certifie que l'état de santé de

                     <p style='text-align:center;'><u><b>".utf8_encode($encounter_names)."</b></u></p>

nécessite une prolongation de son arrêt de travail de $rest_time à compter du $start_date.
<p>
En foi de quoi, je lui délivre le présent certificat pour faire valoir ce que de droit. 
</p>
<p align='right'>Fait à Lomé, le $confirm_date</p>");
                    break;

                //Grossesse
                case 4 :
                    include_once($root_path.'include/core/inc_date_format_functions.php');
                    $exposant = ($nb_month==1)?'er': 'ème';
                    $confirm_date = formatDate2Local($sickconfirm['date_confirm'],$date_format);
                    $start_date = formatDate2Local($sickconfirm['date_start'],$date_format);
                    $end_date = formatDate2Local($sickconfirm['date_end'],$date_format);
                    $start_date_rest = formatDate2Local($sickconfirm['pg_rest_start'],$date_format);
                    $nb_month = $sickconfirm['nb_month'];                    
                    $body = utf8_decode("Je soussigné$femal_mark, ".utf8_encode($med_name).", ".utf8_encode($med_title).", en service à la Clinique BIASA, certifie avoir examiné ce jour :

                    <p style='text-align:center;'><u><b>".utf8_encode($encounter_names)."</b></u></p>

et affirme qu'elle présente un état de gestation à ce jour au $nb_month$exposant mois de grossesse.
<br>   
Le début de la grossesse peut être estimé au $start_date.
<br>
L'accouchement est prévu pour $end_date.
<br>    
Les examens biologiques ont été prescrits.
<br>   
Le congé prénatal doit débuter $start_date_rest.
<p>    
En foi de quoi, je lui délivre le présent certificat pour faire valoir ce que de droit. 
</p>
<p align='right'>Fait à Lomé, le $confirm_date</p>");
                                                                    
                    break;
                //Accouchement     
                case 5 :
                    include_once($root_path.'include/core/inc_date_format_functions.php');
                    
                    $confirm_date = formatDate2Local($sickconfirm['date_confirm'],$date_format);
                    $start_date = formatDate2Local($sickconfirm['date_start'],$date_format);
                    
                    if($sickconfirm['infant_sex']=='m')$sex ='masculin';
                    if($sickconfirm['infant_sex']=='f')$sex ='féminin';
                    
                    $body = utf8_decode("Je soussigné$femal_mark, ".utf8_encode($med_name).", ".utf8_encode($med_title).", en service à la Clinique BIASA, certifie que 

                     <p style='text-align:center;'><u><b>".utf8_encode($encounter_names)."</b></u></p>

a accouché à la clinique BIASA le $start_date, un enfant de sexe $sex.
<p>
En foi de quoi, je lui délivre le présent certificat pour faire valoir ce que de droit. 
</p>
<p align='right'>Fait à Lomé, le $confirm_date</p>");
                    break;

                //Hospitalisation 
                case 6 :
                    include_once($root_path.'include/core/inc_date_format_functions.php');
                    
                    $confirm_date = formatDate2Local($sickconfirm['date_confirm'],$date_format);
                    $start_date = formatDate2Local($sickconfirm['date_start'],$date_format);
                    
                    $body = utf8_decode("Je soussigné$femal_mark, ".utf8_encode($med_name).", ".utf8_encode($med_title).", en service à la Clinique BIASA, certifie que : 
                    <p style='text-align:center;'><u><b>".utf8_encode($encounter_names)."</b></u></p>

doit être hospitalisé à la clinique BIASA à partir du $start_date pour une durée encore indéterminée en vue d'une intervention chirurgicale.

<p>
    En foi de quoi, je lui délivre le présent certificat pour servir et faire valoir ce que de droit.
</p> 
<p align='right'>Fait à Lomé, le $confirm_date</p>");
                    break;
                
                //Aptitude à vivre en communauté
                case 7 :
                    include_once($root_path.'include/core/inc_date_format_functions.php');
                    $confirm_date = formatDate2Local($sickconfirm['date_confirm'],$date_format);
                    $body = utf8_decode("Je soussigné$femal_mark, ".utf8_encode($med_name).", ".utf8_encode($med_title).", en service à la Clinique BIASA, certifie avoir examiné en ce jour : 

                     <p style='text-align:center;'><u><b>".utf8_encode($encounter_names)."</b></u></p>

et affirme qu'il n'est atteint d'aucune forme de maladie contagieuse, et ne présente aucune anomalie décelable sur le plan physique et mental.
<p>
J'atteste en outre qu'il est physiquement apte à vivre en communauté.
</p>
<p>
En foi de quoi, je lui délivre le présent certificat pour faire valoir ce que de droit. 
</p>
<p align='right'>Fait à Lomé, le $confirm_date</p>");
                    break;
                    
                //Inaptitude à faire du sport    
                case 8 :
                    include_once($root_path.'include/core/inc_date_format_functions.php');
                    $confirm_date = formatDate2Local($sickconfirm['date_confirm'],$date_format);
                    $body = utf8_decode("Je soussigné$femal_mark, ".utf8_encode($med_name).", ".utf8_encode($med_title).", en service à la Clinique BIASA,  certifie avoir examiné l'état de santé de : 

                     <p style='text-align:center;'><u><b>".utf8_encode($encounter_names)."</b></u></p>

est incompatible avec la pratique des sports et de la gymnastique en général.
<p>
En foi de quoi, je lui délivre le présent certificat pour faire valoir ce que de droit. 
</p>
<p align='right'>Fait à Lomé, le $confirm_date</p>");
                    
                    break;
                    
                //Inaptitude à travailler    
                case 9 :
                   include_once($root_path.'include/core/inc_date_format_functions.php');
                    $confirm_date = formatDate2Local($sickconfirm['date_confirm'],$date_format);
                    $body = utf8_decode("Je soussigné$femal_mark, ".utf8_encode($med_name).", ".utf8_encode($med_title).", en service à la Clinique BIASA,  certifie avoir examiné l'état de santé de : 

                      <p style='text-align:center;'><u><b>".utf8_encode($encounter_names)."</b></u></p>
<p>
En foi de quoi, je lui délivre le présent certificat pour faire valoir ce que de droit. 
</p>
<p align='right'>Fait à Lomé, le $confirm_date</p>");
                    break;
                    
                //Voyage pour femme enceinte    
                case 10 :

                   include_once($root_path.'include/core/inc_date_format_functions.php');
                    
                    $confirm_date = formatDate2Local($sickconfirm['date_confirm'],$date_format);
                    $start_date = formatDate2Local($sickconfirm['date_start'],$date_format);
                    $end_date = formatDate2Local($sickconfirm['date_end'],$date_format);
                    $start_date_rest = formatDate2Local($sickconfirm['pg_rest_start'],$date_format);
                    $nb_month = $sickconfirm['nb_month'];
                                        
                    $body = utf8_decode("Je soussigné$femal_mark, ".utf8_encode($med_name).", ".utf8_encode($med_title).", en service à la Clinique BIASA, certifie avoir examiné ce jour :

                    <p style='text-align:center;'><u><b>".utf8_encode($encounter_names)."</b></u></p>

et affirme qu'elle présente un état de gestation à ce jour au $nb_month mois de grossesse.
<br>    
J'affirme en outre que son état ne présente aucun risque à voyager par avion, ce pour une période de $rest_time à compter de ce jour.
<p>    
En foi de quoi, je lui délivre le présent certificat pour faire valoir ce que de droit. 
</p>
<p align='right'>Fait à Lomé, le $confirm_date</p>");
                    break;

                 //Voyage pour enfant
                case 11 :
                    
                    include_once($root_path.'include/core/inc_date_format_functions.php');
                    $confirm_date = formatDate2Local($sickconfirm['date_confirm'],$date_format);
                    $body = utf8_decode("Je soussigné $femal_mark,  ".utf8_encode($med_name).", ".utf8_encode($med_title).", atteste que : 

L' état de santé de l'enfant : 
<p style='text-align:center;'><u><b>".utf8_encode($encounter_names)."</b></u></p> lui permet de voyager et que ses vaccinations sont à jour compte tenu de son âge.
<p>
Ce certificat lui est délivré pour servir et faire valoir ce que de droit. 
</p>
<p align='right'>Fait à Lomé, le $confirm_date</p>");
                    break;

                //Durée probable de séjour pour l'assurance   
                case 12 :
                    include_once($root_path.'include/core/inc_date_format_functions.php');
                    
                    $confirm_date = formatDate2Local($sickconfirm['date_confirm'],$date_format);
                    $start_date = formatDate2Local($sickconfirm['date_start'],$date_format);
                   
                    $start_date_rest = formatDate2Local($sickconfirm['pg_rest_start'],$date_format);
                    
                    $body =utf8_decode("Je soussigné$femal_mark,  ".utf8_encode($med_name).", ".utf8_encode($med_title).", en service à la Clinique BIASA, certifie que : 

                    <p style='text-align:center;'><u><b>".utf8_encode($encounter_names)."</b></u></p>

est hospitalisé$en_femal_mark à la Clinique BIASA depuis le $start_date pour une durée 
probable de $rest_time.
<p>
En foi de quoi, je lui délivre le présent certificat pour faire valoir ce que de droit. 
</p>
<p align='right'>Fait à Lomé, le $confirm_date</p>");
                    break;

                //Durée indéterminée pour l'assurance/certificat medical avant hospitalisation
                case 13 :
                    include_once($root_path.'include/core/inc_date_format_functions.php');
                    
                    $confirm_date = formatDate2Local($sickconfirm['date_confirm'],$date_format);
                    $start_date = formatDate2Local($sickconfirm['date_start'],$date_format);
                    
                     $body = utf8_decode("Je soussigné$femal_mark, ".utf8_encode($med_name).", ".utf8_encode($med_title).", en service à la Clinique BIASA, certifie que : 
                    <p style='text-align:center;'><u><b>".utf8_encode($encounter_names)."</b></u></p>

doit être hospitalisé à la clinique BIASA à partir du $start_date pour une durée encore indéterminée.

<p>
    En foi de quoi, je lui délivre le présent certificat pour servir et faire valoir ce que de droit.
</p> 
<p align='right'>Fait à Lomé, le $confirm_date</p>");
                    break;

                 //Durée indéterminée pour l'assurance
                case 14 :
                    include_once($root_path.'include/core/inc_date_format_functions.php');
                    
                    $confirm_date = formatDate2Local($sickconfirm['date_confirm'],$date_format);
                    $start_date = formatDate2Local($sickconfirm['date_start'],$date_format);
                    $end_date = formatDate2Local($sickconfirm['date_end'],$date_format);

                    $body = utf8_decode("Je soussigné$femal_mark,  ".utf8_encode($med_name).", ".utf8_encode($med_title).", certifie que : 

                    <p style='text-align:center;'><u><b>".utf8_encode($encounter_names)."</b></u></p>

a été hospitalisé$femal_mark à la clinique BIASA du $start_date au $end_date. 

<p>
En foi de quoi, je délivre le présent certificat pour servir et faire valoir ce que de droit. 
</p>
<p align='right'>Fait à Lomé, le $confirm_date</p>");
                    break;
                    
                //Vie
                case 15 :
                    include_once($root_path.'include/core/inc_date_format_functions.php');
                    
                    $confirm_date = formatDate2Local($sickconfirm['date_confirm'],$date_format);
                    $start_date = formatDate2Local($sickconfirm['admission_date_start'],$date_format);
                    $end_date = formatDate2Local($sickconfirm['admission_date_end'],$date_format);

                    $date_birth=formatDate2Local($enc_row['date_birth'],$date_format);

                    $body = utf8_decode("Je soussigné$femal_mark,  ".utf8_encode($med_name).", ".utf8_encode($med_title).", en service à la clinique BIASA, certifie que : 

                      <p style='text-align:center;'><u><b>".utf8_encode($encounter_names)."</b></u></p>

né le $date_birth, à la clinique BIASA est en vie. 

<p>
En foi de quoi, je délivre le présent certificat pour servir et faire valoir ce que de droit. 
</p>
<p align='right'>Fait à Lomé, le $confirm_date</p>");
                    break;

                //Repos pour la maman  
                case 16 :

                   include_once($root_path.'include/core/inc_date_format_functions.php');
                    
                    $confirm_date = formatDate2Local($sickconfirm['date_confirm'],$date_format);
                    $start_date = formatDate2Local($sickconfirm['date_start'],$date_format);
                    $end_date = formatDate2Local($sickconfirm['date_end'],$date_format);
                    
                    //$mother_name = $sickconfirm['mother_name'];
                    $mother_name=$_SESSION['mother_name'];
                    

                    $body = utf8_decode("Je soussigné$femal_mark,  ".utf8_encode($med_name).", ".utf8_encode($med_title).", en service à la Clinique BIASA, atteste que :

                    <p style='text-align:center;'><u><b>".utf8_encode($encounter_names)."</b></u></p>

et est hospitalisé$en_femal_mark à la Clinique BIASA depuis le $start_date et atteste que son état de santé nécéssite la présence de sa maman ".utf8_encode($mother_name)." à ses chevets pour une durée encore indéterminée.
<p>    
En foi de quoi, je lui délivre le présent certificat pour faire valoir ce que de droit. 
</p>
<p align='right'>Fait à Lomé, le $confirm_date</p>");
                    break;

                case 17 :
                    include_once($root_path.'include/core/inc_date_format_functions.php');
                    
                    $confirm_date = formatDate2Local($sickconfirm['date_confirm'],$date_format);
                    $start_date = formatDate2Local($sickconfirm['date_start'],$date_format);
                    
                    $body = utf8_decode("Je soussigné$femal_mark, ".utf8_encode($med_name).", ".$med_title.", en service à la Clinique BIASA, certifie que : 
                    <p style='text-align:center;'><u><b>".utf8_encode($encounter_names)."</b></u></p>

doit être hospitalisé à la clinique BIASA à partir du $start_date pour une durée encore indéterminée.
<p>
    En foi de quoi, je lui délivre le présent certificat pour servir et faire valoir ce que de droit.
</p> 
<p align='right'>Fait à Lomé, le $confirm_date</p>");
                    break;
                 case 18 :
                    include_once($root_path.'include/core/inc_date_format_functions.php');
                    
                    $confirm_date = formatDate2Local($sickconfirm['date_confirm'],$date_format);
                    $start_date = formatDate2Local($sickconfirm['date_start'],$date_format);
                    
                    $body = utf8_decode("Je soussigné$femal_mark, ".utf8_encode($med_name).", ".$med_title.", en service à la Clinique BIASA, certifie que : 
                    <p style='text-align:center;'><u><b>".utf8_encode($encounter_names)."</b></u></p>

est hospitalisé$en_femal_mark à la clinique BIASA à partir du $start_date pour une durée encore indéterminée.

<p>
    En foi de quoi, je lui délivre le présent certificat pour servir et faire valoir ce que de droit.
</p> 
<p align='right'>Fait à Lomé, le $confirm_date</p>");
                    break;

        
            }
            
            $date = formatDate2STD($confirm_date,$date_format);
            
            //Title of the document
            $title = 'CERTIFICAT MEDICAL';

            // Foot of the page
            $foot = utf8_decode("Consultation tous les jours ouvrables : de 8h à 12h et 15h à 20h, le Samedi de 8h à 13h et sur rendez-vous
            Urgences assurées 24h/24 ");

            require($root_path.'modules/biasa_pdf/print_certif_pdf.php');
        }
    }

?>
