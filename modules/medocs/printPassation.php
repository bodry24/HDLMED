<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require("roots.php");
    require($root_path.'include/core/inc_environment_global.php');

    require_once($root_path.'include/care_api_classes/class_person.php');
    $objPerson=new Person();

    require_once($root_path.'include/care_api_classes/class_encounter.php');
	$objEncounter=new Encounter;

	require_once($root_path.'include/care_api_classes/class_ward.php');
	$objWard=new Ward();
	    
	require_once($root_path.'include/care_api_classes/class_medocs_params.php');
	$objMedocsParams=new Medocs_params;


    require($root_path.'modules/biasa_pdf/utils.php');
    require_once($root_path.'modules/caisse/utils.php');

    global $db;

    $aile=$_GET['aile'];

   
    switch ($zone) {
    case 2:
        if($aile=="CHIRURGICAL"){
            $title="Hospitalisation Chirurgical";
           
        }else{
            $title="Hospitalisation Médical";
           
        }
        
        break;
    case 3:
        $title="Pédiatrie";
        break;
    case 4:
        $title="Urgence";
       
        break;
    case 5:
        $title="Soins Intensifs";
        
        break;
    case 6:
        $title="Accouchement";
       
        break;
    default:
        $title="Passation";
        break;
}
	$sqlPassation="SELECT * FROM care_passation WHERE id=".$id." AND valide_status='O'";
	if($resultPassation=$db->Execute($sqlPassation)){
		$rsPassation=$resultPassation->FetchRow();
		$medecin=(!empty($rsPassation['pid_medecin']))?"Dr ".$objPerson->getValue("name_last", $rsPassation['pid_medecin'])." ".$objPerson->getValue("name_first", $rsPassation['pid_medecin']):"";
		$date=(!empty($rsPassation['create_time']))?parseDate($rsPassation['create_time'],"Y-M-D","-","","D/M/Y","/",true):"";
	}


	$sql="SELECT p.*, d.*, d.id as id_detail, p.create_time as date_heure_passation, pe.pid, pe.name_last, pe.name_first, pe.name_spouse, pe.date_birth FROM care_passation p, care_passation_detail d, care_encounter e, care_person pe WHERE p.id=d.passation_id AND e.pid=pe.pid AND d.encounter_nr=e.encounter_nr AND p.valide_status='O' AND p.id=".$id;
    $result=$db->Execute($sql);


ob_start();
?>

<style type="text/css">
	table{
		width: 100%;
		font-size: 2px;
	}
	td{
		padding: 5px;
		font-size: 13px;
		vertical-align: middle;
	}
	.style1{
		font-size: 16px;
	}
	.bld{
		font-weight: bold;
	}
	.it{
		font-style: italic;
	}
</style>

<page backtop="45mm" backleft="0mm" backright="0mm" backbottom="0mm" footer="">
	<page_header>
		<table>
			<tr>
				<td style="width:100%;text-align:left;font-weight: bold;">
					<img src="<?php echo $root_path.'modules/biasa_pdf/images/hdl_logo.jpg';?>" style="width:60px;height:auto;">
				</td>
			</tr>
		</table>
		<hr style="border-width: 1px;">
		<h4 style="color:#337AB7;text-align: center;width: 100%; padding: 0px;margin: 0px;font-weight: bold; border:1px solid #337AB7; border-radius: 5px;text-transform: uppercase;"> LISTE DES PASSATIONS</h4>

		<table style="width:100%;" cellpadding='0px' cellspacing='0px' style="vertical-align: top;border: 0px;">		
			<tr>
				<td style="width:12%;text-align: left;"><u>Département:</u></td>
				<td style="width:20%;text-align: left;font-weight: bold;"><?php echo $title;?></td>
				<td style="width:10%;text-align: left;"><u>Medécin:</u></td>
				<td style="width:33%;text-align: left;font-weight: bold;"><?php echo $medecin;?></td>
				<td style="width:15%;text-align: left;"><u>Date & heure:</u></td>
				<td style="width:10%;text-align: left;font-weight: bold;"><?php echo $date;?></td>
			</tr>
		</table>
		<table style="width:100%;" cellpadding='0px' cellspacing='0px' style="vertical-align: top;border: 0.1px;">		
			<tr>
				<td style="width:5%;text-align: center;border: 0.1px;font-weight: bold;">N°</td>
				<td style="width:25%;text-align: center;border: 0.1px;font-weight: bold;">Patient</td>
				<td style="width:10%;text-align: center;border: 0.1px;font-weight: bold;">Age</td>
				<td style="width:20%;text-align: center;border: 0.1px;font-weight: bold;">Occupation</td>
				<td style="width:40%;text-align: center;border: 0.1px;font-weight: bold;">Passation</td>
			</tr>
		</table>
	</page_header>

	<page_footer>
		<hr style="border-width: 0.1px;">
	</page_footer>

	<table style="width:100%;" cellpadding='0px' cellspacing='0px' style="vertical-align: top;border: 0.1px;">		
		
		<?php if(is_object($result)): 
			$i=0;
			while($ligne=$result->FetchRow()):
				$encounter_nr=$ligne['encounter_nr'];
	            $encounterLocationInfo=$objWard->EncounterLocationsInfo($encounter_nr);
	            $ward_id=$encounterLocationInfo['ward_id'];
	            $ward_nr=$encounterLocationInfo['ward_nr'];
	            $i++;
	            $room_nr=$encounterLocationInfo['room_nr'];
	            $bed_nr=$encounterLocationInfo['bed_nr'];  
	            $pidPatient=$ligne['pid'];
	            $nomPatient=empty($ligne['name_spouse'])?$ligne['name_last'].' '.$ligne['name_first']:$ligne['name_last'].' '.$ligne['name_first'].' épouse '.$ligne['name_spouse'];
	            $chambre=$objWard->getRoomNameByNr($room_nr);
	          
	            $occupation=$chambre." Lit N° ".$bed_nr;
	            $age=implode('',calcuteAge($ligne['date_birth']));
	            
		?>
		<tr>
			<td style="width:5%;text-align: right;border: 0.1px;"><?php echo $i;?></td>
			<td style="width:25%;text-align: left;border: 0.1px;"><b><?php echo $nomPatient;?></b></td>
			<td style="width:10%;text-align: left;border: 0.1px;"><?php echo $age;?></td>
			<td style="width:20%;text-align: left;border: 0.1px;"><?php echo $occupation;?></td>
			<td style="width:40%;text-align: left;border: 0.1px;"><?php echo $ligne['contenu'];?></td>
		</tr>
		<?php endwhile;endif;?>
		
	</table>
		

	

</page>
	
 
<?php
	$contents=ob_get_clean();
	require($root_path.'include/Classes/html2pdf/html2pdf.class.php');
	try{
	
		$pdf=new HTML2PDF('P','A4','fr', false, 'ISO-8859-15', array(10,10,10,5));

		$pdf->pdf->SetDisplayMode('fullpage');
		$pdf->pdf->SetTitle('LISTE DE PASSATION');
		$pdf->WriteHTML($contents);
		$pdf->Output('passation.pdf');

	}catch(HTML2PDF_exception $e){
		die($e);
	}
	
?>
