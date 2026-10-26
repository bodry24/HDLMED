<?php
	//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
	ini_set('display_errors',false);
	require('./roots.php');
	require($root_path.'include/core/inc_environment_global.php');
    $local_user='aufnahme_user';
    require($root_path.'language/fr/lang_fr_measurement.php');
    require($root_path.'include/core/inc_front_chain_lang.php');
	require_once($root_path.'include/core/inc_date_format_functions.php');
	require_once($root_path.'include/core/inc_img_fx.php');
	require($root_path.'modules/biasa_pdf/utils.php');
	include($root_path.'modules/caisse/utils.php');
	require_once($root_path.'modules/caisse/gui_bridge/recette/ChiffresEnLettres.php');
	$lettre= new ChiffreEnLettre();
	require_once($root_path.'include/care_api_classes/class_person.php');
	$person = new Person();
	require_once($root_path.'include/care_api_classes/class_department.php');
	$objDepartment = new Department;
    require_once($root_path.'include/care_api_classes/class_ecombill.php');
	$ecombill = new eComBill;
	require_once($root_path.'include/care_api_classes/class_measurement.php');
	$measurement_obj=new Measurement;
	require_once($root_path.'include/care_api_classes/class_encounter.php');
	$encounter_obj=new Encounter($encounter_nr);
	
	global $db;
	//$db->Execute('SET NAMES UTF8');
	$encounter_nr=$_GET['encounter_nr'];
	$idSpecialite=$_GET['idSpecialite'];

	$sqlParametresSpecialites="SELECT s.id as idSpecialite, sp.parametre_id, sp.position, p.designation, p.typeInput, p.nameValue, p.defaultValue, p.placeholder FROM care_medocs_specialites s, care_medocs_specialites_parametres sp, care_medocs_parametres p WHERE s.valide_status='O' AND sp.valide_status='O' AND p.valide='O' AND s.id=sp.specialite_id AND p.id=sp.parametre_id AND s.id=".$idSpecialite." ORDER BY sp.position ASC";
	$parametresSpecialites=$db->Execute($sqlParametresSpecialites);

ob_start();
?>

<style type="text/css">

	table{
		width: 100%;
		font-size: 13px;
		
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

</style>

<page backtop="0mm" backleft="0mm" backright="0mm" backbottom="0mm" footer="">
	<page_header>

	 	<table cellspacing="0px" cellpadding="0px" style="border-color:black;">
			<tr style="border-color: black">
				<td style="width:100%;text-align:right;border-color: black">
					<img src="<?php echo $root_path.'modules/biasa_pdf/images/biasa_logo.jpg';?>" style="width:178.6px;height:79.3px;">
				</td>
			</tr>
			<tr>
				<td style="width: 100%;text-align: center;">
					<b><u><h3>RAPPORT D'HOSPITALISATION</h3></u></b>
				</td>
			</tr>
		</table>
 		
	</page_header>

	<page_footer>

		<table style="width:100%;border:0px;margin-left:-5px">
            <?php
                echo "<tr>
	                    <td style='width:50%; text-align:center;background-color:#eee;'>
	                    	Date Admission
	                    </td>
	                    <td style='width:50%'>
	                    ".parseDate($encounter_date, "Y-M-D", "-","str","D/M/Y","/")."</td>
	                </tr>";
            ?>
        </table>

	</page_footer>

	<table style="width:100%;border:0px; " cellpadding="0px" cellspacing="0px">
	
	<?php
		//Parametres
		if($encounterParameters=$objEncounter->getEncounterParameters($encounter_nr)){
			while($encParam=$encounterParameters->FetchRow()){
				$$encParam['nameValue']=$encParam['value'];
			}
		}
		ob_start();
		while($showParam=$parametresSpecialites->FetchRow()){
			echo '<div class="form-group">';
			echo '<label class="control-label">'.$showParam['designation'].'</label>';
			echo '<div>'.$$showParam['nameValue'].'</div>';

			echo '</div>';
		}
		$obParametresSpecialites=ob_get_contents();
		ob_end_clean();
		$smarty->assign('parametresSpecialites',$obParametresSpecialites);

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
				ob_start();
				if(is_object($listeConstante)){
					echo '<table class="table table-bordered">';
					echo '<thead>
							<tr>
								<th class="text-center">Libellé</th>
								<th class="text-center">Valeur</th>
								<th class="text-center">Unité de mesure</th>
							</tr>
						</thead>';
					while ($constante=$listeConstante->FetchRow()) {
						$varUnit=$constante['LD_var']."_unit";
						$varConstanteVal=$constante['LD_var']."_val";
						echo '<tr>';
						echo '<td width="40%">'.$$constante['LD_var'].'</td>';
						echo '<td width="30%">'.$$varConstanteVal.'</td>';
						echo '<td width="30%">'.$$varUnit.'</td>';
						echo '</tr>';
					}
					echo '</table>';	
				}
				$listingConstante=ob_get_contents();
				ob_end_clean();
				$smarty->assign("listingConstante", $listingConstante);
			}
		}

	?>     
	
	</table>

</page>

<?php

	$contents=ob_get_clean();

	require($root_path.'include/Classes/html2pdf/html2pdf.class.php');
	try{

		$pdf = new HTML2PDF('P','A4','fr', false, 'ISO-8859-15', array(10, 10, 10, 5)); 
		$pdf->pdf->SetAuthor('Clinique BIASA');
		$pdf->pdf->SetTitle("RAPPORT CONSULTATION");
		$pdf->pdf->SetDisplayMode('fullpage');
		$pdf->WriteHTML($contents);
		$pdf->Output('rapport-consultation.pdf');

	}catch(HTML2PDF_exception $e){
		die($e);
	}
						
?>