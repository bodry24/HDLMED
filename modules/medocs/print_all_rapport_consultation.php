<?php
	error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
	require_once('roots.php');
	require($root_path.'include/core/inc_environment_global.php');
	$lang_tables[]='person.php';
	$lang_tables[]='prompt.php';
	$lang_tables[]='measurement.php';
	define('LANG_FILE','aufnahme.php');
	define('NO_2LEVEL_CHK',1);
	require_once($root_path.'include/core/inc_front_chain_lang.php');
    require($root_path.'language/fr/lang_fr_measurement.php');
	require_once($root_path.'include/core/inc_date_format_functions.php');
	require_once($root_path.'include/core/inc_img_fx.php');
	require($root_path.'modules/biasa_pdf/utils.php');
	include($root_path.'modules/caisse/utils.php');

	require_once($root_path.'include/care_api_classes/class_medocs_params.php');
	$objMedocsParams=new Medocs_params;
	require_once($root_path.'include/care_api_classes/class_person.php');
	$objPerson = new Person();
	require_once($root_path.'include/care_api_classes/class_measurement.php');
	$objMeasurement=new Measurement;
	require_once($root_path.'include/care_api_classes/class_encounter.php');
	$objEncounter=new Encounter();
	global $db;
	
	//$pid=$_GET['pid'];
	//Constantes


ob_start();
?>

<style type="text/css">

	table{
		width: 100%;
		font-size: 10px;
	}

	tr{
		height: 0.1px;
	}

	td{
		padding: 2.1px;
		border-color: #0F4EC1;
	}

	.black{
		color:black;
	}

	.blue{
		color:#0F4EC1;
	}

	.line{
		height:1px; color:#000; background-color:#000;
		border:none;border-style: dashed;
	}

	.td_border{
		border:0.1px;
		padding:2px;
	}
	.text-center{
		text-align: center;
	}
	.text-right{
		text-align: right;
	}
	.text-left{
		text-align: left;
	}

</style>

<page backtop="12mm" backleft="0mm" backright="0mm" backbottom="20mm" footer="">
	<page_header>

	  	<table cellspacing="0px" cellpadding="0px" style="border-color:black;">
	 		<!--	<tr style="border-color: black">
	 				<td style="width:100%;text-align:right;border-color: black">
	 					<img src="<?php echo $root_path.'modules/biasa_pdf/images/biasa_logo.jpg';?>" style="width:178.6px;height:79.3px;">
	 				</td>
	 			</tr>-->
	 			<tr>
	 				<td style="width: 100%;text-align: center;">
	 					<b><u><h2>RAPPORT DE CONSULTATION</h2></u></b>
	 				</td>
	 			</tr>
	 		</table> 
 		
	</page_header>

	<page_footer>
		<hr style="height:0.5px;">
	
		<table>
			<tr>			
				<td align="center" style="width:100%;font-size:10px;line-height: 2px;" class="black" colspan="2">
					CLINIQUE BIASA
					
				</td>
			</tr>
			<tr>			
				<td align="center" style="width:100%;font-size:10px;line-height: 2px;" class="black" colspan="2">
					<!-- CLINIQUE BIASA<br> -->
					30, Rue Pasteur BAETA - BP. 2160  LOME-TOGO -  Tél. : +228 22 21 11 37 / 22 21 11 60 / Fax : +228 22 22 11 60 
				</td>
			</tr>
			<tr>
				<td class="black" style="width:50%;font-size:10px;text-align: right;">
					Email: biasa@cliniquebiasa.org /
				</td>
				<td class="blue" style="width:50%;font-size:10px;text-align: left">clinicbiasa@yahoo.fr - www.cliniquebiasa.org
				</td>
			</tr>
		</table>

	</page_footer>

<table style="width:100%;border:0px;margin-left:-5px">
    <?php
        $name_spouse=$objPerson->getValue('name_spouse', $pid);
    	if(!empty($name_spouse) && $name_spouse!=''){
    		$name=$objPerson->getValue('title',$pid).' '.$objPerson->getValue('name_last',$pid).' '.$objPerson->getValue('name_first',$pid).' Epouse '.$name_spouse;
        }else{
        	$name=$objPerson->getValue('title',$pid).' '.$objPerson->getValue('name_last',$pid).' '.$objPerson->getValue('name_first',$pid);
        }
        echo "<tr>
                <td style='width:25%; text-align:center;background-color:#eee;'>PID</td>
                <td style='width:25%;text-align:right;'>".$pid."</td>
                <td style='width:25%;background-color:#eee;text-align:center;'>NOM & PRENOMS</td>
                <td style='width:25%;text-align:right'>".$name."</td>
            </tr>";
    ?>
</table>
<hr>
<?php
if($listAdmission=$objEncounter->getEncounterList($pid)){
	$compteur=0;
	while($encounter=$listAdmission->FetchRow()){	

	$compteur++;
	echo $compteur.")";
	$encounter_date=$encounter['encounter_date'];
	$encounter_nr=$encounter['encounter_nr'];
	$idSpecialite=$encounter['admit_type'];
	//var_dump($idSpecialite);die();
	$listeConstante=$objMedocsParams->loadDetailSpecialiteConstantes($idSpecialite);
?>
    <table style="width:100%;border:0px;margin-left:-5px">
        <?php
            echo "<tr>
                    <td style='width:25%; text-align:center;background-color:#fff'>N° ADMISSION</td>
                    <td style='width:25%'>".$encounter['encounter_nr']."</td>
                    <td style='width:25%;background-color:#eee;text-align:center;'>DATE ADMISSION</td>
                    <td style='width:25%;text-align:right'>".parseDate($encounter_date, "Y-M-D","-","","D/M/Y","/")."</td>
                </tr>";
        ?>
    </table>
	<h4><u><b><i>CONSTANTE</i></b></u></h4>
	
	<?php
		//Constantes
		if($encounterConstantes=$objMeasurement->getMeasurementByPidEncounterNr($encounter_nr,'O')){
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
					echo '<table style="width:100%;border:1px; " cellpadding="0px" cellspacing="0px">';
					for($i=0;$i<$nbConstante;$i++){
						$j=$i+1;
						if($j % 2==0){
							$color='white';
						} 
						else $color='#ddd';
						echo '<tr style="background-color:'.$color.'">';
								$varUnit=$listeConstante[$i]['LD_var']."_unit";
								$varConstanteVal=$listeConstante[$i]['LD_var']."_val";
							    echo '<td style="width:30%;border:0.5px;">'.$$listeConstante[$i]['LD_var'].'</td>';
								echo '<td style="width:20%;border:0.5px;" align="right">'.$$varConstanteVal.' '.$$varUnit.'</td>';

								if($listeConstante[$nbConstante+$i]['LD_var']."_unit"!=''){
									$varUnit=$listeConstante[$nbConstante+$i]['LD_var']."_unit";
									$varConstanteVal=$listeConstante[$nbConstante+$i]['LD_var']."_val";
								    echo '<td style="width:30%;border:0.5px;">'.$$listeConstante[$nbConstante+$i]['LD_var'].'</td>';
									echo '<td style="width:20%;border:0.5px;" align="right">'.$$varConstanteVal.' '.$$varUnit.'</td>';
									
								}

						echo '</tr>';
					}
					echo '</table>';
					
				}
			}
		}

		echo '<h4><u><b><i>DONNEES DE CONSULTATION</i></b></u></h4>';
		//Autres champs
		$sqlParametresSpecialites="SELECT s.id as idSpecialite, p.value, sp.parametre_id, sp.position, p.designation, p.typeInput, p.nameValue, p.defaultValue, p.placeholder FROM care_medocs_specialites s, care_medocs_specialites_parametres sp, care_medocs_parametres p WHERE s.valide_status='O' AND sp.valide_status='O' AND p.valide='O' AND s.id=sp.specialite_id AND p.id=sp.parametre_id AND s.id=".$idSpecialite." ORDER BY sp.position ASC";
		$parametresSpecialites=$db->Execute($sqlParametresSpecialites);

		//Parametres
		if($encounterParameters=$objEncounter->getEncounterParameters($encounter_nr)){
			while($encParam=$encounterParameters->FetchRow()){
				$$encParam['nameValue']=$encParam['value'];
			}
		}

		echo '<table style="width:100%;border:0px; " cellpadding="0px" cellspacing="0px">';
		$i=0;
		while($showParam=$parametresSpecialites->FetchRow()){
			$i++;
			if($i % 2==0){
				$color='white';
			} 
			else $color='#ddd';

			$paramNameValue="";
			if($showParam['typeInput']=="date"){
				$paramNameValue=parseDate($$showParam['nameValue'],'Y-M-D','-','','D/M/Y','/');
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

			echo '<tr style="background-color:'.$color.'">';
				echo '<td style="width:30%; vertical-align: text-top;"><u>'.$showParam['designation'].'</u></td>';
				echo '<td style="width:70%;text-align:justify;">: '.$paramNameValue.'</td>';
			echo '</tr>';
		
		}
		echo '</table>';
		
		echo "<hr>";

	}
}


	?>     
	
</page>

<?php

	$contents=ob_get_clean();

	require($root_path.'include/Classes/html2pdf/html2pdf.class.php');
	try{

		$pdf = new HTML2PDF('P','A4','fr', true, 'UTF-8', array(20, 5, 20, 0)); 
		$pdf->pdf->SetAuthor('Clinique BIASA');
		$pdf->pdf->SetTitle("RAPPORT DE CONSULTATION");
		$pdf->pdf->SetDisplayMode('fullpage');
		$pdf->WriteHTML($contents);
		$pdf->Output('rapport-consultation.pdf');

	}catch(HTML2PDF_exception $e){
		die($e);
	}
						
?>