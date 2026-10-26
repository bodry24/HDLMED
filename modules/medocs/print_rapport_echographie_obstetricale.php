<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('roots.php');
    require($root_path.'include/core/inc_environment_global.php');

    require_once($root_path.'include/care_api_classes/class_person.php');
    $objPerson=new Person();

    require($root_path.'modules/biasa_pdf/utils.php');
    require_once($root_path.'modules/caisse/utils.php');

    global $db;

#Les tableaux de valeurs    

//fonction qui teste si les mots sont des <br />
function NonVide($param){
	$array=str_word_count($param, 1);
	$ok=false;
	foreach ($array as $v) {
		if($v!='br'){
			$ok=true;
			continue;

		}
	}
	//var_dump($ok);die();
	return $ok;
}
//recuperation et calcul des valeurs

ob_start();

if(isset($_GET)){

	$id=$_GET['id'];
	$sql="SELECT * FROM care_medocs_echographie_obstetricale WHERE id=".$id;
	if($result=$db->Execute($sql)){
		$rs=$result->FetchRow();
		$date_naissance=$objPerson->getValue('date_birth', $rs['pid']);

		$titre=($objPerson->getValue('sex', $rs['pid'])=='m')?'Monsieur':'Madame';
		$e=($objPerson->getValue('sex', $rs['pid'])=='m')?'':'e';
		$patient=$objPerson->getValue('name_last', $rs['pid']).' '.$objPerson->getValue('name_first', $rs['pid']);
		$tabAge =calcuteAge($date_naissance,'','fr');
	
		$age = !empty($tabAge[0])?$tabAge[0] : (!empty($tabAge[1])?$tabAge[1]:(!empty($tabAge[2])?$tabAge[2]:''));
		$date_birth=parseDate($date_naissance,'Y-M-D','-','','D/M/Y','/');
		$adresse=$objPerson->getValue('addr_str', $rs['pid']);

		$ddr=($rs['ddr']=='0000-00-00')?'':parseDate($rs['ddr'],'Y-M-D','-','','D/M/Y','/');
		$ddg_theorique=($rs['ddg_theorique']=='0000-00-00')?'':parseDate($rs['ddg_theorique'],'Y-M-D','-','','D/M/Y','/');
		$ddg_echographique=($rs['ddg_echographique']=='0000-00-00')?'':parseDate($rs['ddg_echographique'],'Y-M-D','-','','D/M/Y','/');
	}
}
?>

<style type="text/css">

	table{
		width: 100%;
		font-size: 12px;
	}
	td{
		padding: 3.1px;
		font-size:12px;
	}

	.tdcenter{
		text-align: center;
	}

	
</style>

<page backtop="5mm" backleft="5mm" backright="5mm" backbottom="5mm" footer="">
	<page_header>
		
	</page_header>

	<page_footer>
		
			<hr style="border: 0.1px;">
				
	</page_footer>

	<table>
		<tr>
			<td style="width:30%;text-align:left;" >
				<img src="<?php echo $root_path.'modules/biasa_pdf/images/biasa_logo.jpg';?>" style="width:200px;height:80px;">
			</td>
			<td align="center" style="width:70%;font-size:12px;">
				30, Rue Pasteur BAETA - BP. 2160 - Tel. +228 22 21 11 37 -  +228 22 21 11 60 - Fax : +228 22 22 11 60 / LOME - TOGO<br>
				Email: biasa@cliniquebiasa.org / clinicbiasa@yahoo.fr<br>
				www.cliniquebiasa.org
			</td>

		</tr>
		<tr>
			<td colspan="2" style="width:100%;background-color:#eee;text-align:center;border-radius:5px;border:1px solid #000;">
				<h3>ECHOGRAPHIE OBSTETRICALE</h3>
			</td>
		</tr>
	</table>

    <table border='0px' style='width:100%;' cellpadding='0px' cellspacing='0px' align="">
		<tr>
			<td style="width:100%;" class="tdcenter">
				<?php echo 'Examen réalisé sur un échographe '.$rs['machine'].' mise en service le '.parseDate($rs['date_service_machine'], 'Y-M-D','-','','D/M/Y','/').'<br>Voie d\'examen : '.$rs['voie_examen'].'. Conditions de l\'examen : '.$rs['condition_examen'];?>
			</td>	
		</tr>
	</table>

	<table border='0px' style='width:100%;' cellpadding='0px' cellspacing='0px' align="">
		<tr>
			<td style="width:100%;">
				<?php echo '<b>'.$titre.' '.$patient.'</b> né'.$e.' le '.$date_birth.' ('.$age.')';?>
			</td>	
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo $adresse;?>
			</td>
		</tr>
		<tr>	
			<td style="width:100%;">
				<?php echo 'Groupe sanguin : '.$rs['groupe_sanguin'].'. Taille : '.$rs['taille'].' cm &nbsp;&nbsp;&nbsp;Poids : '.$rs['poids'].'  &nbsp;&nbsp;&nbsp; IMC : '.$rs['imc'];?>
			</td>	
		</tr>
	</table>
	<br>
	<table border='0px' style='width:100%;' cellpadding='0px' cellspacing='0px' align="">
		<tr>
			<td style="width:100%;">
				<?php echo 'Adressé(e) par '.$rs['prescripteur'];?>
			</td>	
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo 'Indication : '.$rs['indication'];?>
			</td>
		</tr>
	</table>
	<br>
	<table border='0px' style='width:100%;' cellpadding='0px' cellspacing='0px' align="">
		<tr>
			<td style="width:50%;">
				<?php echo 'DDR : '.$ddr.' &nbsp;&nbsp;&nbsp; DDG théorique : '.$ddg_theorique;?>
			</td>	
			<td style="width:50%;">
				<?php echo 'Soit un terme théorique de '.$rs['terme_theorique'];?>
			</td>	
		</tr>
		<tr>
			<td style="width:50%;">
				<?php echo '<b>DDG échographique : '.$ddg_echographique.'</b>';?>
			</td>
			<td style="width:50%;">
				<?php echo 'Soit un terme de <b>'.$rs['terme_echographique'].'</b>';?>
			</td>	
		</tr>
	</table>
	<br>
	<table border='0px' style='width:100%;' cellpadding='0px' cellspacing='0px' align="">
		<tr style="height: 10px;">
			<td style="width:100%;font-size: 14px;font-weight: bold;">
				Anamnèse
			</td>	
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo 'Gestité : '.$rs['gestite'].', &nbsp;&nbsp;&nbsp; Parité : '.$rs['parite'];?>
			</td>
		</tr>
	</table>
	<br>

	<table border='0px' style='width:100%;' cellpadding='0px' cellspacing='0px' align="">
		<tr style="height: 10px;">
			<td style="width:100%;font-size: 14px;font-weight: bold;">
				Grossesse en cours
			</td>	
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo 'Clarté nucale : '.$rs['clarte_nucale'].' mm. &nbsp;&nbsp;&nbsp; Longueur cranio-caudale : '.$rs['cranio_caudale'].' mm';?>
			</td>
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo 'Risque combiné du 1er trimestre : <b>'.$rs['risque_combine'].'</b>. &nbsp;&nbsp;&nbsp; PAPP-A '.$rs['pappa'].' MoM. &nbsp;&nbsp;&nbsp; FreeBeta : '.$rs['freebeta'];?>
			</td>
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo 'Amniocentèse : '.$rs['amniocentese'];?>
			</td>
		</tr>
	</table>
	<br>
	<table border='0px' style='width:100%;' cellpadding='0px' cellspacing='0px' align="">
		<tr style="height: 10px;">
			<td style="width:100%;font-size: 14px;font-weight: bold;">
				Foetus Unique
			</td>	
		</tr>
	</table>
	<br>
	<table border='0px' style='width:100%;' cellpadding='0px' cellspacing='0px' align="">
		<tr style="height: 10px;">
			<td style="width:100%;font-size: 14px;font-weight: bold;">
				Présentation
			</td>	
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo $rs['presentation'];?>
			</td>
		</tr>
	</table>
	<br>
	<table border='0px' style='width:100%;' cellpadding='0px' cellspacing='0px' align="">
		<tr style="height: 10px;">
			<td style="width:100%;font-size: 14px;font-weight: bold;">
				Vitalité
			</td>	
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo 'Activité cardiaque : '.$rs['activite_cardiaque'];?>
			</td>
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo 'Fréquance cardiaque : '.$rs['frequence_cardiaque'];?>
			</td>
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo 'Mobilité : '.$rs['mobilite'];?>
			</td>
		</tr>
	</table>
	<br>
	<table border='0px' style='width:100%;' cellpadding='0px' cellspacing='0px' align="">
		<tr style="height: 10px;">
			<td style="width:100%;font-size: 14px;font-weight: bold;" colspan="2">
				Biométries
			</td>	
		</tr>
		<tr>
			<td style="width:50%;">
				<?php echo 'Diamètre BIPariétal : ';?>
			</td>
			<td style="width:50%;">
				<?php echo $rs['diametre_bip'].' mm';?>
			</td>
		</tr>
		<tr>
			<td style="width:50%;">
				<?php echo 'Périmètre Cranien : ';?>
			</td>
				<td style="width:50%;">
				<?php echo $rs['perimetre_cranien'].' mm';?>
			</td>
		</tr>
		<tr>
			<td style="width:50%;">
				<?php echo 'Périmètre Abdominal : ';?>
			</td>
			<td style="width:50%;">
				<?php echo $rs['perimetre_abdominal'].' mm';?>
			</td>
		</tr>
		<tr>
			<td style="width:50%;">
				<?php echo 'Fémur : ';?>
			</td>
			<td style="width:50%;">
				<?php echo $rs['femur'].' mm';?>
			</td>
		</tr>
		<tr>
			<td style="width:50%;">
				<?php echo 'Poids estimé (Hadlock 5) +/-15% : ';?>
			</td>
			<td style="width:50%;">
				<?php echo $rs['poids_biometric'].' g';?>
			</td>
		</tr>
	</table>
	<br>
	<table border='0px' style='width:100%;' cellpadding='0px' cellspacing='0px' align="">
		<tr style="height: 10px;">
			<td style="width:100%;font-size: 14px;font-weight: bold;">
				Morphologie
			</td>	
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo '<b>Crâne</b> : '.$rs['crane'];?>
			</td>
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo '<b>Structures cérébrales</b> : '.$rs['structures_cerebrales'];?>
			</td>
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo '<b>Face</b> : '.$rs['face'];?>
			</td>
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo '<b>Thorax</b> : '.$rs['thorax'];?>
			</td>
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo '<b>Coeur</b> : '.$rs['coeur'];?>
			</td>
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo '<b>Paroi abdominale</b> : '.$rs['paroi_abdominale'];?>
			</td>
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo '<b>Appareil digestif</b> : '.$rs['appareil_digestif'];?>
			</td>
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo '<b>Appareil urinaire</b> : '.$rs['appareil_urinaire'];?>
			</td>
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo '<b>Rachis</b> : '.$rs['rachis'];?>
			</td>
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo '<b>Membres</b> : '.$rs['membres'];?>
			</td>
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo '<b>Organes génitaux externes</b> : '.$rs['organes_genitaux'];?>
			</td>
		</tr>
	</table>
	<br>
	<table border='0px' style='width:100%;' cellpadding='0px' cellspacing='0px' align="">
		<tr style="height: 10px;">
			<td style="width:30%;font-weight: bold;">
				Doppler ombilical
			</td>	
			<td style="width:70%;">
				<?php echo $rs['doppler_ombilical'];?>
			</td>
		</tr>
		<tr style="height: 10px;">
			<td style="width:30%;font-weight: bold;">
				Artère Cérébrale Moy.
			</td>	
			<td style="width:70%;">
				<?php echo $rs['artere_cerebrale'];?>
			</td>
		</tr>
			<tr style="height: 10px;">
			<td style="width:30%;font-weight: bold;">
				Ductus Venosus
			</td>	
			<td style="width:70%;">
				<?php echo $rs['ductus_venosus'];?>
			</td>
		</tr>
		
	</table>
	<br>
	<table border='0px' style='width:100%;' cellpadding='0px' cellspacing='0px' align="">
		<tr style="height: 10px;">
			<td style="width:100%;font-size: 14px;font-weight: bold;">
				Annexes
			</td>	
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo $rs['annexes'];?>
			</td>
		</tr>
	</table>
	<br>
	<table border='0px' style='width:100%;' cellpadding='0px' cellspacing='0px' align="">
		<tr style="height: 10px;">
			<td style="width:100%;font-size: 14px;font-weight: bold;">
				Dopplers utérins
			</td>	
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo $rs['dopplers_uterins'];?>
			</td>
		</tr>
	</table>
	<br>
	<table border='0px' style='width:100%;' cellpadding='0px' cellspacing='0px' align="">
		<tr style="height: 10px;">
			<td style="width:5%;font-size: 14px;font-weight: bold;">
				Col:
			</td>	
			<td style="width:95%;">
				<?php echo $rs['col'];?>
			</td>
		</tr>
	</table>
	<br>
	<table border='0px' style='width:100%;' cellpadding='0px' cellspacing='0px' align="">
		<tr style="height: 10px;">
			<td style="width:100%;font-size: 14px;font-weight: bold;">
				Commentaires
			</td>	
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo $rs['commentaires'];?>
			</td>
		</tr>
	</table>
	<br>
	<table border='0px' style='width:100%;' cellpadding='0px' cellspacing='0px' align="">
		<tr style="height: 10px;">
			<td style="width:100%;font-size: 14px;font-weight: bold;">
				Conclusion
			</td>	
		</tr>
		<tr>
			<td style="width:100%;">
				<?php echo $rs['conclusion'];?>
			</td>
		</tr>
	</table>
	<br>
	<table border='0px' style='width:100%;' cellpadding='0px' cellspacing='0px' align="center">		
		<tr>
			<td style="width:50%;text-align:center;font-size:13px;">
				<?php if(!empty($rs['image1'])):?>
					<img src="<?php echo $root_path.$rs['image1'];?>" style="width:100%;height: auto;">
				<?php endif;?>
			</td>
			<td style="width:50%;text-align:center;font-size:13px;">
				<?php if(!empty($rs['image2'])):?>
					<img src="<?php echo $root_path.$rs['image2'];?>" style="width:100%;height: auto;">
				<?php endif;?>
			</td>
		</tr>		
	</table>
	<br>
	<table border='0px' style='width:100%;' cellpadding='0px' cellspacing='0px' align="center">		
		<tr>
			<td style="width:50%;text-align:center;font-size:13px;">
				<?php if(!empty($rs['image3'])):?>
					<img src="<?php echo $root_path.$rs['image3'];?>" style="width:100%;height: auto;">
				<?php endif;?>
			</td>
			<td style="width:50%;text-align:center;font-size:13px;">
				<?php if(!empty($rs['image4'])):?>
					<img src="<?php echo $root_path.$rs['image4'];?>" style="width:100%;wiheightdheightth: auto;">
				<?php endif;?>
			</td>
		</tr>		
	</table>
	<table border='0px' style='width:100%;' cellpadding='0px' cellspacing='0px' align="center">		
		<tr>
			<td style="width:50%;text-align:center;font-size:13px;">
				<?php if(!empty($rs['image5'])):?>
					<img src="<?php echo $root_path.$rs['image5'];?>" style="width:100%;height: auto;">
				<?php endif;?>
			</td>
			<td style="width:50%;text-align:center;font-size:13px;">
				
			</td>
		</tr>		
	</table>



	

</page>


<?php

	$contents=ob_get_clean();
	require($root_path.'include/Classes/html2pdf/html2pdf.class.php');
	try{
		//$pdf=new HTML2PDF('P', 'A4', 'fr');
		$pdf=new HTML2PDF('P','A4','fr', false, 'ISO-8859-15');
		$pdf->pdf->SetDisplayMode('fullpage');
		$pdf->WriteHTML($contents);
		$pdf->Output('compte-rendu-hystero.pdf');

	}catch(HTML2PDF_exception $e){
		die($e);
	}
	
?>

