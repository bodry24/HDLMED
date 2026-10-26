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
	$objEncounter=new Encounter($encounter_nr);
	
	global $db;

	//$db->Execute('SET NAMES UTF8');
	if(!isset($encounter_nr)){
		$encounter_nr=$_GET['encounter_nr'];
	}
	if(!isset($idSpecialite)){
		$idSpecialite=$_GET['idSpecialite'];
	}
	//Constantes
	$listeConstante=$objMedocsParams->loadDetailSpecialiteConstantes($idSpecialite);

	//$objEncounter->loadEncounterData($encounter_nr);
	//var_dump($objEncounter->EncounterDate($encounter_nr));die();
	$rsDate=$objEncounter->EncounterDate($encounter_nr)->FetchRow();
	$encounter_date=$rsDate['encounter_date'];

	# Medecin qui a fait la consultation
	$sqlMedecin="SELECT create_id, encounter_class_nr FROM care_encounter WHERE encounter_nr=".$encounter_nr;
	if($resultMedecin=$db->Execute($sqlMedecin)){
		$rsMedecin=$resultMedecin->FetchRow();
		$idMedecin=$rsMedecin['create_id'];
		if(!empty($idMedecin) && $idMedecin!=""){
			$medecin=$objPerson->getValue('name_last', $idMedecin).' '.$objPerson->getValue('name_first', $idMedecin);
		}else $medecin="";	
	}

	# Recuperation du format de l'impression
	$sqlSpecialite="SELECT format, champ_inutile FROM care_medocs_specialites WHERE id=".$idSpecialite." AND valide_status='O'";
	if($resultSpecialite=$db->Execute($sqlSpecialite)){
		$rsSpecialite=$resultSpecialite->FetchRow();
		$formatPdf=$rsSpecialite['format'];
		$champInutile=$rsSpecialite['champ_inutile'];
		$size="9px";

	}else {
		$formatPdf="A4";
		$size="12px";
	}

		# ce qui suit pour l'impression des Gynéco en A4
	if(isset($_GET['format_impression']) && !empty($_GET['format_impression'])){
		$formatPdf="A4";
	}
	/*$tabPdf=array(
			'A4' =>array('head'=>'<h2>','head-f'=>'</h2>', 'title'=>'10px','size'=>'13px', 'footsize'=>'10px',
				'margin'=>'20', 'marginTop'=>'5','title-moyen'=>'<h3>', 'title-moyen-f'=>'</h3>','backtop'=>'12mm',),
			'A6' =>array('head'=>'<h6>', 'head-f'=>'</h6>', 'title'=>'8px;','size'=>'8px', 'footsize'=>'5px', 
				'margin'=>'5', 'marginTop'=>'0','title-moyen'=>'<h6>', 'title-moyen-f'=>'</h6>','backtop'=>'7mm',),
		);*/
	$tabPdf=array(
			'A4' =>array('head'=>'<h2>','head-f'=>'</h2>', 'title'=>'10px','size'=>'16px', 'footsize'=>'10px',
				'margin'=>'20', 'marginTop'=>'5','title-moyen'=>'<h3>', 'title-moyen-f'=>'</h3>','backtop'=>'12mm',),
			'A6' =>array('head'=>'<h6>', 'head-f'=>'</h6>', 'title'=>'8px;','size'=>'8px', 'footsize'=>'5px', 
				'margin'=>'5', 'marginTop'=>'0','title-moyen'=>'<h6>', 'title-moyen-f'=>'</h6>','backtop'=>'7mm',),
		);
	$hmoyen=$tabPdf[$formatPdf]['title-moyen'];
/*
echo '<pre>';
var_dump($tabPdf);
echo '</pre>';

var_dump($formatPdf);
var_dump($tabPdf[A4]['head']);
die();*/
ob_start();
?>

<style type="text/css">

	table{
		width: 100%;
		font-size: <?php echo $size;?>
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
		height:1px; 
		color:#000; 
		background-color:#000;
		border:none;
		border-style: dashed;
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

<page backtop="<?php echo $tabPdf[$formatPdf]['backtop'];?>" backleft="0mm" backright="0mm" backbottom="0mm" footer="">
	<page_header>
		<table cellspacing="0px" cellpadding="0px" style="border-color:black;">

 			<tr>
 				<td style="width: 100%;text-align: center;">
 					<b><u><?php echo $tabPdf[$formatPdf]['head'];?>RAPPORT DE CONSULTATION<?php echo $tabPdf[$formatPdf]['head-f'];?></u></b>
 				</td>
 			</tr>
 		</table> 
	  	
 		
	</page_header>

	<page_footer>
	<?php if(!empty($medecin)):
		if($rsMedecin['encounter_class_nr']!=1){
			?>
				<p style="text-align:right;margin-bottom:0px;font-size:<?php echo $tabPdf[$formatPdf]['size'];?>;"><b><?php echo "Dr ".$medecin;?></b></p>
			<?php
		}
		
	endif;?>
		<hr style="height:0.5px;">
		<table>
			<tr>			
				<td align="center" style="width:100%;font-size:<?php echo $tabPdf[$formatPdf]['footsize'];?>;line-height: 2px;" class="black" colspan="2">
					CONTACTER LE SERVICE CLIENTELE EN CAS DE RECLAMATION
					
				</td>
			</tr> 
			<tr>			
				<td align="center" style="width:100%;font-size:<?php echo $tabPdf[$formatPdf]['footsize'];?>;line-height: 2px;" class="black" colspan="2">
					Adresse : Aogè-Nyive, Route nationale N°1, Lomé-Togo; 19 BP 319 Lomé - Tel: +228 22 53 50 50 - Courriel: contact@hdl.tg
				</td>
			</tr>
			<tr>			
				<td align="center" style="width:100%;font-size:<?php echo $tabPdf[$formatPdf]['footsize'];?>;line-height: 2px;" class="black" colspan="2">
					Site Web : https://hopitaldogtalafie.tg
				</td>
			</tr>
		</table>
	</page_footer>

	<table style="width:100%;border:0px;" cellpadding="0px" cellspacing="0px">
	
	    <tr>
	    	<td style="width:100%;border:0px;">
	            <table style="width:100%;border:0px;margin-left:-5px">
	                <?php
	                    $rsp=$objEncounter->getValue('name_spouse', $encounter_nr, true);
	                	$name_spouse=$rsp->FetchRow();
	                	$name_spouse=$name_spouse['name_spouse'];
	                	if(!empty($name_spouse) && $name_spouse!=''){
	                		$name=$objPerson->getValue('title',$pid).' '.$objPerson->getValue('name_last',$pid).' '.$objPerson->getValue('name_first',$pid).' Epouse '.$name_spouse;
	                    }else{
	                    	$name=$objPerson->getValue('title',$pid).' '.$objPerson->getValue('name_last',$pid).' '.$objPerson->getValue('name_first',$pid);
	                    }
		                echo "<tr>
			                    <td style='width:100%;text-align:center;'><b>".$name."</b></td>    
			                </tr>";
	                ?>
	            </table>
			</td>
		</tr> 
		<!-- PID et encounter nr -->
	    <tr>
	    	<td style="width:100%;border:0px;">
	            <table style="width:100%;border:0px;margin-left:-5px">
	                <?php
		                echo "<tr>
			                    <td style='width:25%; text-align:center;background-color:#eee;'>PID</td>
			                    <td style='width:25%;text-align:right;'>".$pid."</td>
			                    <td style='width:25%;background-color:#eee;text-align:center;'>N° ADMISSION</td>
			                    <td style='width:25%;text-align:right'>".$encounter_nr."</td>
			                </tr>";
	                ?>
	                 <?php
		                echo "<tr>
			                    <td style='width:25%; text-align:center;background-color:#fff'></td>
			                    <td style='width:25%'></td>
			                    <td style='width:25%;background-color:#eee;text-align:center;'>DATE ADMISSION</td>
			                    <td style='width:25%;text-align:right'>".parseDate($encounter_date, "Y-M-D","-","","D/M/Y","/")."</td>
			                </tr>";
	                ?>
	            </table>
            </td>
        </tr>
 	</table>


	<?php echo $tabPdf[$formatPdf]['title-moyen'];?><u><b><i style="">CONSTANTE</i></b></u><?php echo $tabPdf[$formatPdf]['title-moyen-f'];?>
	
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
?>
		<?php echo $tabPdf[$formatPdf]['title-moyen'];?><u><b><i>DONNEES DE CONSULTATION</i></b></u><?php echo $tabPdf[$formatPdf]['title-moyen-f'];?>
		<?php
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

			if($paramNameValue!=""){
				echo '<tr style="background-color:'.$color.'">';
						echo '<td style="width:30%; vertical-align: text-top;"><u>'.utf8_encode($showParam['designation']).'</u></td>';
						echo '<td style="width:70%;text-align:justify;">: '.utf8_encode($paramNameValue).'</td>';
						//echo '<td style="width:70%;text-align:justify;">: '.htmlentities(htmlspecialchars_decode($paramNameValue)).'</td>';
					echo '</tr>';
			}else{
				if($champInutile=="O"){
					echo '<tr style="background-color:'.$color.'">';
						echo '<td style="width:30%; vertical-align: text-top;"><u>'.utf8_encode($showParam['designation']).'</u></td>';
						echo '<td style="width:70%;text-align:justify;">: '.utf8_encode($paramNameValue).'</td>';
						//echo '<td style="width:70%;text-align:justify;">: '.htmlentities(htmlspecialchars_decode($paramNameValue)).'</td>';
					echo '</tr>';
				}
			}
			
		
		}
		echo '</table>';
		

		


	?>     
	
</page>

<?php

	$contents=ob_get_clean();

	require($root_path.'include/Classes/html2pdf/html2pdf.class.php');
	try{
		$margin=$tabPdf[$formatPdf]['margin'];
		$marginTop=$tabPdf[$formatPdf]['marginTop'];

		$pdf = new HTML2PDF('P',$formatPdf,'fr', true, 'UTF-8', array($margin, $marginTop, $margin, 0)); 
		$pdf->pdf->SetAuthor('Clinique BIASA');
		$pdf->pdf->SetTitle("RAPPORT DE CONSULTATION");
		$pdf->pdf->SetDisplayMode('fullpage');
		$pdf->WriteHTML($contents);
		$pdf->Output('rapport-consultation.pdf');

	}catch(HTML2PDF_exception $e){
		die($e);
	}
						
?>