<?php
	//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
	ini_set('display_errors',false);
	//error_reporting(E_ALL);
	//header('content-type:text/html; charset=ISO-8859-1');
	require('roots.php');
	require($root_path.'include/core/inc_environment_global.php');

	/*$lang_tables=array('measurement.php');
    //$lang_tables[]='measurement.php';
    $lang_tables[]='prompt.php';
    $lang_tables[]='help.php';
	$lang_tables[]='departments.php';
    $lang_tables[]='person.php';
    define('LANG_FILE','aufnahme.php');*/
    $local_user='aufnahme_user';
    define('NO_2LEVEL_CHK',1);
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
	$encounter_obj=new Encounter($nr_admission);
	require($root_path.'modules/biasa_pdf/pdf/fpdf.php'); 
	  //creation de la nouvelle classe
	class PDF extends FPDF{
		var $B;
		var $I;
		var $U;
		var $HREF;
		
		function PDF($orientation='P', $unit='mm', $size='A4'){
			$this->FPDF($orientation,$unit,$size);
			$this->B = 0;
			$this->I = 0;
			$this->U = 0;
			$this->HREF = '';
		}
		function Header(){
			$this->Image('../../modules/biasa_pdf/images/hdl_logo.jpg',25,10,30,30,'JPG');
			$this->Ln(25);
		}
		
		
		function Footer () {
			// Positionnement à 1,5 cm du bas
			 $this->SetY(-20);
			 //$y=$this->GetY();
			//$this->Cell(0,5,$y+50,0,1,'R');
    		// Police Arial italique 8
    		$this->SetFont('Times','',10);
    		// Numéro de page
   			// $this->Cell(0,5,'Page '.$this->PageNo().'/{nb}',1,1,'C');
    		$this->Cell(0,5,'CONTACTER LE SERVICE CLIENTELE EN CAS DE RECLAMATION',0,1,'C');
			$this->Cell(0,5,'Adresse : Aogè-Nyive, Route nationale N°1, Lomé-Togo; 19 BP 319 Lomé - Tel: +228 22 53 50 50 - Courriel: contact@hdl.tg',0,1,'C'); 
			$this->SetFont('Times','BU',12);
			$this->Cell(0,8,'Site Web : https://hopitaldogtalafie.tg',0,0,'C');
			$this->SetFont('Times','',12); 
			//$this->Cell(0,8,'Page '.$this->PageNo().'/{nb}',0,0,'R'); 
		}
		
		function write($style,$position,$largeur,$hauteur,$texte,$border=1,$ligne=0,$alignement='L'){
			$this->SetFont('Times',$style,10);
			if($ligne==1){
				$this->SetX($this->GetX()+1);
				$this->Cell($largeur,$hauteur,$texte,$border,1,$alignement);
			}else if($ligne==0){
				$this->SetX($position);
				$this->Cell($largeur,$hauteur,$texte,$border,0,$alignement);
				
			}else{//ici ligne est different de 0 et 1
				$this->SetX($position);
				$this->Cell($largeur,$hauteur,$texte,$border,1,$alignement);
			}
	  	}

	}
	//fin classe 
	if(isset($_GET) && !empty($_GET) && $_GET['nr_admission']){
			$pid = $_GET['pid'];
		    $nr_admission = $_GET['nr_admission'];
			$result=$ecombill->getAllReport($nr_admission);
			$list_label=$ecombill->getAlllabel();
        	if(is_object($result)){
				$item=$result->FetchRow();
				$name_last = $person->getValue('name_last',$pid);
				$name_first = $person->getValue('name_first',$pid);
				$bith_date = $person->getValue('date_birth',$pid);
			} 
			/******************************************Récuperer les constantes************************************/
			$dep=$encounter_obj->getValue('department',$nr_admission);
			$allTypes=$measurement_obj->getAllMsrTypes($dep);
    		$measure=$measurement_obj->getMeasurementByPidEncounterNr($nr_admission);
    		$measurement_nr=$measure[0]['nr'];
    		$measure=$measurement_obj->getMeasuresByNr($measurement_nr);


    		foreach($measure as $measure_line){
       			$name=$measure_line['type'];
        		$tab_constante[$name]=array("value"=>$measure_line['value'],"unite"=>$measure_line['unite'],"nr"=>$measure_line['nr']);
   			}
   			//var_dump($tab_constante);
   			if(!empty($measure)){
        		$nbTypes=count($allTypes);
        		$nb_ligne=ceil($nbTypes/3);
        		$nb_column=3;
        		//$constante = '<table width="100%" border="1" cellspacing="0">';
        		$constante='';
        		for($x=0;$x<$nb_ligne;$x++){
            		$position1=($x*$nb_column);
            		if($position1>=$nbTypes) break;
            		for($y=0;$y<$nb_column;$y++){
                		if($y==0) $constante.='';//$constante.='<tr>';
                		$position=($x*$nb_column)+$y;
                		$element=$allTypes[$position];
                		if(isset($element)){
                    		$LD=$element['LD_var'];
                    		//var_dump($LD);
                    		$var=$$LD;
                    		$type=$element['type'];
                    		$measurement_type=$element['measurement_type'];
                    		$unit_default=$element['unit_default'];
                    		$unit_nr=$tab_constante[$LD]['unite'];
                    		if(!empty($tab_constante[$type]['value'])){
                    			$val=$tab_constante[$type]['value'].' '.$tab_constante[$type]['unite'];
                    			//$constante.='<td>'.$var.':</td><td>'.$val.'</td>';
                    			$constante.=$var.': '.$val.' ; ';
                    		} 
                    		else $val='-';
                		}
                		else{
                     		//$constante.='<td></td><td></td>';
                     		$constante.='';
                		}
                		if($y==2) $constante.='';//$constante.='<tr>';
            		}
        		}
        		$constante.='';//$constante.='</table>';
    		}
			/*********************************************fin***************************************************/

			$file_name="rapport_hospi.pdf";
			$pdf = new PDF('P','mm','A4');
	  		$pdf->AddPage();
	  		$pdf->SetAuthor('AGT BROYLE');
	  		//$pdf->SetTitle($file_name);
	  		$pdf->SetTitle('COMPTE RENDU D\'HOSPITALISATION', true);
	  		$pdf->SetFont('Times','B',12);
	  		$pdf->Cell(60);
			$pdf->Cell(80,5,'COMPTE RENDU D\'HOSPITALISATION','B',1,'C');
			$pdf->Ln(5);
			$pdf->SetFont('Times','',9);

			$pdf->write('BU',15,35,7,'NOM ET PRENOM :',0,0,'L');
		/*	$pdf->write('B',50,65,7,$name_last.' '.$name_first,0,0,'L');
			$pdf->write('BU',115,40,7,"DATE DE NAISSANCE :",0,0,'L');*/
			$pdf->write('B',50,65,7,$name_last.' '.$name_first,0,0,'L');
			$pdf->write('BU',140,40,7,"DATE DE NAISSANCE :",0,0,'L');
			$pdf->write('',180,20,7, $date=parseDate($bith_date,'Y-M-D','-','','D/M/Y','/'),0,0,'L');
			$pdf->Ln(10);
			//Affichage des informations du formulaire
			$resume_clinique=0;
			$paraclinique=0;

			if(is_object($list_label) && $list_label->RowCount()!=0){
            	while($line=$list_label->FetchRow()){
          		
            	  if($line['print_option']=='O'){
            	  		
            	  		#La condition qui suit est mise pour ne pas afficher les champs qui 
            	  		if($ecombill->getValue($nr_admission, $line['label_col_nam'])){
            	  			//var_dump($ecombill->getValue($nr_admission, $line['label_col_nam']));
				            if($line['label_col_nam']=="constantes"){
		        	       		$pdf->write('BUI',15,30,7,"RESUME CLINIQUE",0,0,'L');
		            	   		$resume_clinique+=1;
		               			$pdf->Ln();
		               		}else if($line['label_col_nam']=="signescliniques" && $resume_clinique==0){
		               			$pdf->write('BUI',15,30,7,"RESUME CLINIQUE",0,0,'L');
		               			$resume_clinique+=1;
		               			$pdf->Ln();
		               		}

		               		if($line['label_col_nam']=="biologie"){
		               			$pdf->write('BUI',15,30,7,"PARACLINIQUE",0,0,'L');
		               			$paraclinique+=1;
		               			$pdf->Ln();
		               		}else if($line['label_col_nam']=="morphologie" && $paraclinique==0){
		               			$pdf->write('BUI',15,30,7,"PARACLINIQUE",0,0,'L');
		               			$paraclinique+=1;
		               			$pdf->Ln();
		               		}

				           	//$nbre_label=strlen(mb_strtoupper(html_entity_decode($line['libelle'])).':');
				           	$nbre_label=strlen(mb_strtoupper(html_entity_decode($line['libelle'])).':');
				           	$date=$ecombill->getValue($nr_admission,$line['label_col_nam']);//ceci pour cacher le libelle date de sortie
				           	if($line['label_col_nam']!="autres" && $line['label_col_nam']!="docteur" && $date!="--"){
				           		//$pdf->write('BU',15,$nbre_label*2+6,5,mb_strtoupper(html_entity_decode($line['libelle'])).':',0,0,'L');
				           		$pdf->write('BU',15,$nbre_label*2+6,5,mb_strtoupper(html_entity_decode($line['libelle'])).':',0,0,'L');
		             			$pdf->SetFont('Times','',11);

				           	}else $pdf->SetX(15);

		        	      	if(substr_count($line['label_col_nam'], 'date')){
		                 		$date=$ecombill->getValue($nr_admission,$line['label_col_nam']);
		                 		
		                 		if($date=='--'){//cette condition pour cacher la valeur de la date de sortie
		                 			$date="";
		                 		}else{
		                 			$date=parseDate($date,'Y-M-D','-','','D/M/Y','/');
		                 		}

		                 		$pdf->MultiCell(0,5,$date,0,'J');

		               		}else if($line['label_col_nam']=="constantes"){
		               			$pdf->MultiCell(0,5,$constante,0,'J');
		               		}else {
		               			if($line['label_col_nam']!='docteur'){

		               				$pdf->MultiCell(0,5,ucfirst($ecombill->getValue($nr_admission,$line['label_col_nam'])),0,'J');
		               			}
		               			
		               		}
		              		$pdf->Ln(3);
		              	}
	                }
               	}
        	}
        	//die();
        
			//fin avec signature du médecin
			//$pdf->Ln(10);
			$y=$pdf->GetY();
			if(($y+20)>262){
				$pdf->AddPage();
			}
			//$pdf->Cell(50,7,$y+50,0,1,'R');
			$pdf->SetFont('Times','',12);
			$pdf->Cell(140);
			$pdf->Cell(50,7,'Bien confraternellement',0,1,'R');
			$pdf->Cell(140);
			setlocale(LC_TIME,'fr-FR');
	  		$pdf->Cell(50,7,'Lomé, le '.date('j/m/Y'),0,1,'R');
	  		$pdf->Cell(140);
			$pdf->Cell(50,7,'',0,1,'R');
			//$pdf->Ln(10);
			$pdf->Cell(140);
			$docteur_pid=$ecombill->getValue($nr_admission,'docteur');
			$docteur='Dr '.$person->getValue('name_last', $docteur_pid).' '.$person->getValue('name_first', $docteur_pid);
			$pdf->Cell(50,7,$docteur,0,1,'R');
			//$pdf->Cell(50,5,'Lomé,',1,1,'C');
			$pdf->Output($file_name,'I');
					
	}// fin de la requete
	

?>