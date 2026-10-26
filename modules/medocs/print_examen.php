<?php 
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require($root_path.'include/core/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_core.php');
$dbCore=new Core;
require_once($root_path.'include/care_api_classes/class_person.php');
$objPerson=new Person;

require_once($root_path.'include/care_api_classes/class_encounter.php');
$objEncounter=new Encounter;

require_once($root_path.'modules/biasa_pdf/utils.php');
require_once($root_path.'modules/caisse/utils.php');

global $db;

$id=$_GET['bulletin_id'];

/***Recuperation des informations du patient***/
$sqlBull="SELECT * FROM care_bulletin_rx WHERE valide_status='O' AND id=".$id;
$resultBull=$db->Execute($sqlBull);
$rs=$resultBull->FetchRow();
$prescripteur=$rs['prescripteur'];
$patient=$objPerson->getValue('name_last', $rs['pid']).' '.$objPerson->getValue('name_first', $rs['pid']);
$sexe=$patient=$objPerson->getValue('sex', $rs['pid']);
$age=implode(" ", calcuteAge($objPerson->getValue('date_birth', $rs['pid'])));
$dateNaissance=parseDate($objPerson->getValue('date_birth', $rs['pid']), 'Y-M-D','-','','D-M-Y','-');

/***Recuperation des examens demandés***/
$sql="SELECT i.item_description FROM care_billing_item i, care_bulletin_rx_element e WHERE e.item_id=i.item_code AND e.bulletin_id=".$id;
$resultExam=$db->Execute($sql);
$lesExamens="";
//while($ligneExam=$resultExam->FetchRow()){
	//if(!empty($lesExamens)) $lesExamens.=", ";
	//$lesExamens.=$ligneExam['item_description']."<br style='line-height:1px;'>";
	//$lesExamens.=$ligneExam['item_description']."<br>";
//}

ob_start();
?>

<style type="text/css">

	table{
		width: 100%;
		font-size: 14px;
		font-family: Helvetica;
		
	}

	tr{
		height: 30px;
	}

	td{
		padding: 5px;
		border-color: #0F4EC1;
		line-height: 50px;
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
	br{
		line-height: 5px;
	}

</style>

<page backtop="34mm" backleft="0mm" backright="0mm" backbottom="0mm" footer="">
	<page_header>
	 	<!-- <table cellspacing="0px" cellpadding="0px" style="border-color:black;">
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
 		-->
	</page_header>

	<page_footer>
		
	</page_footer>

	<table style="width: 100%;">
		<tr>
			<td style="text-align:right;width: 75%">&nbsp;</td>
			<td style="text-align:left;font-weight:bold;width: 25%"><?php echo date('d/m/Y');?></td>
		</tr>
	</table>
	<br><br><br>
	<h3 align="center" style="text-decoration: underline;font-weight: bold;margin-top: 0px;">
		BULLETIN D'EXAMENS
	</h3>
    <br><br>
	<table style="width:100%;border:0px;" cellpadding="0px" cellspacing="0px">
		<tr>
			<td style="width: 10%;">Nom :</td>
			<td style="width: 40%;font-weight: bold;">
				<?php echo $objPerson->getValue('name_last', $rs['pid']);?>
			</td>
			<td style="width: 15%;">Prénoms :</td>
			<td style="width: 35%;font-weight: bold;">
				<?php echo $objPerson->getValue('name_first', $rs['pid']);?>
			</td>
		</tr>
	</table>
	<?php
		if($sexe=='m'){
    		$image_m='checkbox_checked1';
    	}else $image_m='checkbox_unchecked1';
    	if($sexe=='f'){
    		$image_f='checkbox_checked1';
    	}else $image_f='checkbox_unchecked1';
    	
    ?>
	<table style="width:100%;border:0px;" cellpadding="0px" cellspacing="0px">
		<tr>
			<td style="width: 25%;">Age / date de naissance :</td>
			<td style="width: 35%;font-weight: bold;">
				<?php echo $age." / ".$dateNaissance;?>
			</td>
			<td style="width: 10%;">Sexe :</td>
			<td style="width: 10%;font-weight: bold;">
				M <img src="<?php echo $root_path.'modules/laboratory/images/'.$image_m.'.png';?>"> 
			</td>
			<td style="width: 10%;font-weight: bold;">
				F <img src="<?php echo $root_path.'modules/laboratory/images/'.$image_f.'.png';?>"> 
			</td>
		</tr>
	</table>
	<table style="width:100%;border:0px;" cellpadding="0px" cellspacing="0px">
		<tr>
			<td style="width: 30%;">Traitement en cours :</td>
			<td style="width: 70%;font-weight: bold;">
				<?php echo $rs['traitement'];?>
			</td>
		</tr>
	</table>
	<table style="width:100%;border:0px;" cellpadding="0px" cellspacing="0px">
		<tr>
			<td style="width: 30%;">Renseignements Cliniques :</td>
			<td style="width: 70%;font-weight: bold;">
				<?php echo $rs['renseignement'];?>
			</td>
		</tr>
	</table>
	<table style="width:100%;border:0px;" cellpadding="0px" cellspacing="0px">
		<tr>
			<td style="width: 30%;text-align: top;vertical-align: top" align="top">Examens demandés :</td>
			<td style="width: 70%;font-weight: bold;">
				<table style="width: 100%;border: 0px;margin-left: -5px;" cellpadding="0px" cellspacing="0px">
				<?php 
					while($ligneExam=$resultExam->FetchRow()){
						echo "<tr><td>".$ligneExam['item_description']."</td></tr>";
					}
				?>
				</table>
			</td>
		</tr>
	</table>
	<div style="top: 475px; right:40px; position:absolute;font-weight: bold; font-size: 15px;margin-right: 20px;" align="right">
		<?php echo "Dr ".$prescripteur;?>
	</div>
</page>

<?php

	$contents=ob_get_contents();
	ob_end_clean();

	require($root_path.'include/Classes/html2pdf/html2pdf.class.php');

	try{

		//$pdf = new HTML2PDF('L','A5','fr', false, 'ISO-8859-15', array(10, 10, 10, 5)); 
		$pdf = new HTML2PDF('P','A4','fr', true, 'UTF-8', array(10, 10, 10, 5)); 
		$pdf->pdf->SetAuthor('Clinique BIASA');
		$pdf->pdf->SetTitle("DEMANDE D'ANALYSE");
		$pdf->pdf->AddFont('Helvetica');
		$pdf->pdf->SetDisplayMode('fullpage');
		$pdf->WriteHTML($contents);
		$pdf->Output('demande-analyse.pdf');

	}catch(HTML2PDF_exception $e){
		die($e);
	}
						
?>