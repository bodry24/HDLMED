<?php
   
    //error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    require($root_path.'modules/biasa_pdf/utils.php');
    require($root_path.'modules/biasa_pdf/pdf/fpdf.php');

    
    require_once($root_path.'include/core/inc_date_format_functions.php');
    require_once($root_path.'modules/caisse/utils.php');

    require_once($root_path.'include/care_api_classes/class_letter.php');
    $letter_obj=new Letter();

    require_once($root_path.'include/care_api_classes/class_person.php');
    $person=new Person();

    require_once($root_path.'include/care_api_classes/class_medicalActs.php');
    $act = new MedicalActs();

    //if(isset($_GET['nr']) && isset($_GET['letter_type_nr']){
       
   // }
    //RecupÃ©ration de l'id de la lettre et du type de lettre
    ob_start();

    $nr=$_GET['nr'];
    $letter_type_nr=$_GET['letter_type_nr'];
    $single_obj=&$letter_obj->getPhysicianLetter($nr);
    /*var_dump($single_obj);
    die();*/
    if($nr && $letter_type_nr){
        if($letter=$single_obj->FetchRow()){

            
            $doctorInfo = $act->getPatient($letter['pid_create']);
            
            $doctor = $doctorInfo['title'].' '.$doctorInfo['name_last'].' '.$doctorInfo['name_first'];
            
            ////////include_once($root_path.'include/core/inc_date_format_functions.php');
            //letter creation date 
            $date = $letter['date_letter'];
            
            $LDObject = 'Objet :';
            //letter object
            $object = $letter['object_letter'];
            
            //Sender
            $sender = $letter['sender'];
            
            //The code of the letter

            $code = $letter['code_letter'];
            
            //Sender location
            $location = $letter['location'];
                
            //Receiver
            $receiver = "\n\n".$letter['receiver'];
            
            //The body of the letter
            $body = $letter['body_letter']; 
   
            //The signature of the sender : Title, name, firstname
            $signature = $doctor;

        }
    }

?>

<style type="text/css">

    table{
        width: 100%;
       
    }

    table td{
        font-size: 15px;
        font-style: 'Times';
    }

    td.right{
        text-align: right;

    }
    td.left{
        text-align: left;
        
    }
    td.center{
        text-align: center;

    }
    br{
        line-height: 5px;
    }
    ul{
       /* border: 1px solid black;*/
        margin-top: -15px;
    }
    p{
        margin-bottom:0px; 
    }

    br{
        line-height: -50px;
    }


</style>

<page backtop="25mm" backleft="0mm" backright="0mm" backbottom="0mm" footer="">
    <page_header>
        <table style="text-align:center;">
            <tr><td style="width:100%;text-align:right;">
            
            <img src="<?php echo $root_path.'modules/biasa_pdf/images/biasa_logo.jpg';?>" style="width:178.6px;height:79.3px;">
             
             
            </td></tr>
           <!--    <tr><td style="width:100%;" align="right">&nbsp;</td></tr>
          <tr>
                <td style="width:100%;font-weight:bold;font-color:blue;font-size:12px;" align="center">
                
            <hr style="margin-left:0px 30px 0px 30px;">
             GYNECOLOGIE-ACCOUCHEMENTS-CHIRURGIE-STERILITE-ASSISTANCE MEDICALE A LA PROCREATION 
             LABORATOIRE D'ANALYSES MEDICALES  
             <hr style="margin-left:0px 30px 0px 30px;">
            

                </td>
            </tr> -->

        </table>
    </page_header>

    <page_footer>
        
        <hr style="border:0.3px;">
        <table>
            <tr>
                <td align="center" style="width:100%;font-size:10px;">
                <?php  echo '30, Rue Pasteur BAETA - BP. 2160 - T&eacute;l. +228 22 21 11 37  +228 22 21 11 60 - Fax : +228 22 22 11 60 / LOME - TOGO<br>
                Email: biasa@cliniquebiasa.org / clinicbiasa@yahoo.fr<br>
                www.cliniquebiasa.org';?>
                </td>
            </tr>
            
        </table>
    </page_footer>

    <table style="width:100%;height:80%;font-family:Times;font-size:13px;"; border="0px" cellpadding="0px" cellspacing="0px" >
        
        <tr>
            <td style="width:100%;height:5%;font-color:blue;font-weight:bold;">
                <?php echo  nl2br($sender);?>
            </td>
        </tr>
        <tr>
            <td style="width:100%;">
                 <table>
                    <tr>
                        <td style="width:50%;height:5%;" class="right">
                            &nbsp;
                        </td>
                        <td style="width:50%;height:5%;">
                            <?php
                            $mois=mb_convert_encoding(conversionDateForPdf($date,'-',true), "ISO-8859-1", mb_detect_encoding(conversionDateForPdf($date,'-',true)));
                                //echo  $location.', le '.conversionDateForPdf($date,'-',true);
                                echo  $location.', le '.$mois;
                            ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width:100%;height:5%;" class="left">
                 <i><?php echo  $object;?></i>
            </td>
        </tr>
        <tr>
            <td style="width:100%;">

                <table>
                    <tr>
                        <td style="width:50%;height:5%;" class="right">
                            &nbsp;
                        </td>
                        <td style="width:50%;height:5%;">
                            <b><?php echo  nl2br($receiver);?></b>
                        </td>
                    </tr>
                </table>
               
            </td>
        </tr>
        <tr>
            <td style="width:100%;height:5%;" class="left">
                Cher Confr&egrave;re,
            </td>
        </tr>
        <tr>
            <td valign="top" style="width:100%;height:60%;text-align:justify;" class="left">
                <?php 
                $body=mb_convert_encoding($body, "ISO-8859-1", mb_detect_encoding($body));
                echo nl2br($body);?>
            </td>
        </tr>
        <tr>
            <td width="100%">
                <table>
                    <tr>
                        <td style="width:50%;height:5%;">
                            &nbsp;
                        </td>
                        <td style="width:50%;height:5%;" class="left">
                            <b><u><?php echo  $doctor;?></u></b>
                        </td>
                    </tr>
                </table>     
            </td>
        </tr>

    </table>

</page>


<?php

    $contents=ob_get_clean();
    require($root_path.'include/Classes/html2pdf/html2pdf.class.php');
    try{
        $pdf=new HTML2PDF('P','A4','fr', false, 'ISO-8859-1', array(20, 10, 20, 5));
        // $pdf=new HTML2PDF('P', 'A4', 'fr');
        $pdf->pdf->SetAuthor('Clinique BIASA');
        $pdf->pdf->SetTitle("LETTRE");
        $pdf->pdf->SetDisplayMode('fullpage');
        $pdf->WriteHTML($contents);
        $pdf->Output('lettre.pdf');

    }catch(HTML2PDF_exception $e){
        die($e);
    }
    
?>
