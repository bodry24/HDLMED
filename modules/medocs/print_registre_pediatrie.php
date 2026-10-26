<?php
require('roots.php');
require($root_path.'include/core/inc_environment_global.php');
require_once($root_path.'language/fr/lang_fr_measurement.php');

$lang_tables[]='measurement.php';
$lang_tables[]='aufnahme.php';
define('LANG_FILE','stdpass.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');

global $db;
require_once($root_path.'global_conf/areas_allow.php');

require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'modules/caisse/utils.php');

require_once($root_path.'include/care_api_classes/class_person.php');
$patient_obj=new Person;


require_once($root_path.'include/care_api_classes/class_encounter.php');
$objEncounter=new Encounter;

require_once($root_path.'include/care_api_classes/class_medocs_params.php');
$objMedocsParams=new Medocs_params;

require_once($root_path	.'include/care_api_classes/class_measurement.php');
$objMeasurement=new Measurement;

require($root_path.'modules/biasa_pdf/utils.php');
require($root_path.'modules/biasa_pdf/pdf/fpdf.php');


class PDF extends FPDF{
	var $widths;
	var $aligns;
	var $date1;
	var $date2;
	function setDate1($d1){
		$this->date1=$d1;
	}
	function setDate2($d2){
		$this->date2=$d2;
	}
	function SetWidths($w)
	{
	    //Tableau des largeurs de colonnes
	    $this->widths=$w;
	}

	function SetAligns($a)
	{
	    //Tableau des alignements de colonnes
	    $this->aligns=$a;
	}

	function Row($data)
	{
	    //Calcule la hauteur de la ligne
	    $nb=0;
	    for($i=0;$i<count($data);$i++)
	        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	    $h=5*$nb;
	    //Effectue un saut de page si nécessaire
	    $this->CheckPageBreak($h);
	    //Dessine les cellules
	    for($i=0;$i<count($data);$i++)
	    {
	        $w=$this->widths[$i];
	        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
	        //Sauve la position courante
	        $x=$this->GetX();
	        $y=$this->GetY();
	        //Dessine le cadre
	        $this->Rect($x,$y,$w,$h);
	        //Imprime le texte
	        $this->MultiCell($w,5,$data[$i],0,$a);
	        //Repositionne Ã  droite
	        $this->SetXY($x+$w,$y);
	    }
	    //Va Ã  la ligne
	    $this->Ln($h);
	}

	function CheckPageBreak($h)
	{
	    //Si la hauteur h provoque un débordement, saut de page manuel
	    if($this->GetY()+$h>$this->PageBreakTrigger)
	        $this->AddPage($this->CurOrientation);
	}

	function NbLines($w,$txt)
	{
	    //Calcule le nombre de lignes qu'occupe un MultiCell de largeur w
	    $cw=&$this->CurrentFont['cw'];
	    if($w==0)
	        $w=$this->w-$this->rMargin-$this->x;
	    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	    $s=str_replace("\r",'',$txt);
	    $nb=strlen($s);
	    if($nb>0 and $s[$nb-1]=="\n")
	        $nb--;
	    $sep=-1;
	    $i=0;
	    $j=0;
	    $l=0;
	    $nl=1;
	    while($i<$nb)
	    {
	        $c=$s[$i];
	        if($c=="\n")
	        {
	            $i++;
	            $sep=-1;
	            $j=$i;
	            $l=0;
	            $nl++;
	            continue;
	        }
	        if($c==' ')
	            $sep=$i;
	        $l+=$cw[$c];
	        if($l>$wmax)
	        {
	            if($sep==-1)
	            {
	                if($i==$j)
	                    $i++;
	            }
	            else
	                $i=$sep+1;
	            $sep=-1;
	            $j=$i;
	            $l=0;
	            $nl++;
	        }
	        else
	            $i++;
	    }
	    return $nl;
	}
	//Header
	function header(){
		//Positionnement Ã  10 cm de haut
		$this->SetY(5);
		$this->SetFont('Arial','BI',10);
		$this->Cell(0,5,'CLINIQUE BIASA',0,0,'R');
		$this->SetLineWidth(0.4);
		$this->Line(10,10,286,10);
		$this->SetLineWidth(0.2);
		$this->Ln(10);
	    $this->SetFont('Arial', 'BU', 11);
		$this->Cell(0,5,"REGISTRE DE LA PEDIATRIE DU ".$this->date1." AU ".$this->date2,0,1,'C');
		$this->SetFont('Arial', '', 9);
		$position_entete=20;
		$this->entete_table($position_entete);
	}

	//Footer
	function footer(){
		//Positionnement Ã  15 cm du bas
		$this->SetY(-15);
		$this->SetLineWidth(0.4);
		$this->Line(10,195,286,195);
		$this->SetFont('Arial', '', 9);
		$this->Cell(0, 5, utf8_decode('30, Rue Pasteur BAETA - BP. 2160 - Tél. +228 22 21 11 37 -  +228 22 21 11 60 - Fax : +228 22 22 11 60 / LOME - TOGO'), 0, 1,'C');
		$this->cell(0,5,utf8_decode('Email:biasa@cliniquebiasa.org / clinicbiasa@yahoo.fr'), 0, 1, 'C');
		$this->cell(0,5,'www.cliniquebiasa.org',0,0,'C');
					
	}

	//Pour l'entete du tableau
	function entete_table($position_entete){
	    //global $pdf;
	    $this->SetDrawColor(183); 
	    $this->SetFillColor(221); 
	    $this->SetTextColor(0); 
	    $this->SetY($position_entete);
	    $this->SetX(10);
	    $this->Cell(10,5,utf8_decode('N°'),1,0,'L',1);
	    $this->SetX(20); 
	    $this->Cell(20,5,'Date',1,0,'C',1);
	    $this->SetX(40); 
	    $this->Cell(40,5,utf8_decode('Nom & Prénoms'),1,0,'C',1);
	    $this->SetX(80);
	    $this->Cell(15,5,utf8_decode('Age'),1,0,'C',1);
	    $this->SetX(95); 
	    $this->Cell(15,5,utf8_decode('Sexe'),1,0,'C',1);
	    $this->SetX(110); 
	    $this->Cell(35,5,utf8_decode('Diagnostic'),1,0,'C',1);
	    $this->SetX(145); 
	    $this->Cell(35,5,utf8_decode('Bilan'),1,0,'C',1);
	    $this->SetX(180); 
	    $this->Cell(35,5,utf8_decode('Vaccin'),1,0,'C',1);
	    $this->SetX(215); 
	    $this->Cell(35,5,utf8_decode('Mensuration'),1,0,'C',1);
	    $this->SetX(250); 
	    $this->Cell(35,5,utf8_decode('Traitement'),1,0,'C',1);
	    $this->Ln(); // Retour Ã  la ligne
	}

}
//Recupération des dates
if(isset($_GET)){

	$date_debut=$_GET['date_debut'];
	$date_fin=$_GET['date_fin'];
	$doctorPid=$_GET['doctorPid'];
	$idSpecialite=$_GET['idSpecialite'];
    $date1=parseDate($date_debut,'Y-M-D','-','','D/M/Y','/');
    $date2=parseDate($date_fin,'Y-M-D','-','','D/M/Y','/');

}


#Fonction permettant de recuperer la valeur d'un champ paramétrer
function recupereValue($nr, $specialite=15, $field="diagnostic"){
    global $db;
    $sql="SELECT ep.value as name
            FROM care_medocs_encounter_parametres ep
            WHERE ep.spec_param_id=(SELECT p.id FROM care_medocs_specialites_parametres sp, care_medocs_parametres p WHERE sp.specialite_id=".$specialite." AND sp.parametre_id=p.id AND p.nameValue='".$field."') 
            AND ep.encounter_nr=".$nr." 
            AND ep.valide_status='O'";
    //var_dump($sql);die();
    if($result=$db->Execute($sql)){
        $rs=$result->FetchRow();
        return $rs['name']; 
    }else return "";
}


function recupereValueP($nr, $id=null){
    global $db;
    $sql="SELECT value as name
            FROM care_medocs_encounter_parametres 
            WHERE spec_param_id=".$id." AND encounter_nr=".$nr."
            AND valide_status='O'";
    //var_dump($sql);//die();
    if($result=$db->Execute($sql)){
        $rs=$result->FetchRow();
        return $rs['name']; 
    }else return "";
}

$pdf=new PDF('L', 'mm', 'A4');
$pdf->SetTitle("REGISTRE DE LA PEDIATRIE");
$pdf->setDate1($date1);
$pdf->setDate2($date2);
$pdf->SetDrawColor(183); 
$pdf->AddPage();
$pdf->SetFont('Arial', '', 9);

$position_detail=$pdf->GetY();


# Début du traitement
$sql="SELECT e.encounter_date as encounter_date, e.para_test as bilan, p.date_birth as date_birth, e.encounter_nr as nr, p.sex as sexe,p.pid as pid, p.name_last as nom, p.name_first as prenoms, e.referrer_diagnosis as diagnostic, e.referrer_recom_therapy as traitement FROM care_encounter e, care_person p 
    WHERE p.pid=e.pid AND encounter_class_nr=2 AND date(encounter_date)>='".$date_debut."' AND date(encounter_date) <='".$date_fin."'  AND e.create_id LIKE '".$doctorPid."'";

if($result=$db->Execute($sql)){
	if($result->RecordCount()>0){	
		$compteurLigne=0;
		$pdf->SetWidths(array(10, 20, 40, 15, 15,35,35,35,35,35));
		$pdf->SetAligns(array('R','C','L','R','C','L','L','L','L','L'));

        while ($ligne=$result->FetchRow()) {	 
	        $compteurLigne++;

	        $date=date_create($ligne['encounter_date']);
	        $pid=$ligne['pid'];
	        $nr=$ligne['nr'];
	        $name=$ligne['nom']." ".$ligne['prenoms']."\r\nPID:".$ligne['pid'];
	        $diagnostic=recupereValue($nr);
	        #Calcule l'age de l'enfant 
	        $tabAge = calcuteAge($ligne['date_birth'],'','fr');
	        $age = !empty($tabAge[0])?$tabAge[0] : (!empty($tabAge[1])?$tabAge[1]:(!empty($tabAge[2])?$tabAge[2]:''));
	        
	        #Je recupère ici les demandes d'analyses
            $sqlTrait="SELECT s.test_type as analyse FROM care_test_request t, care_test_request_sub s WHERE t.nr=s.parent_nr AND t.status!='delected' AND t.encounter_nr=".$nr; 
            $donneesAnalyses="";
            if($resultTrait=$db->Execute($sqlTrait)){
                //$donneesAnalyses=$ligne['bilan'];
                //$k=1;
                while($ligneTrait=$resultTrait->FetchRow()){ 

                   //$donneesAnalyses.=$k++.")".$ligneTrait['analyse']."\n";                         
                    $donneesAnalyses.=$ligneTrait['analyse']."\n";                         
                }      
            }
            if(empty($donneesAnalyses)){
            	$sqlBilan="SELECT * FROM care_medocs_encounter_parametres WHERE valide_status='O' AND encounter_nr='".$ligne['nr']."' AND spec_param_id=28";
                //echo $sqlBilan;
                if($resultBilan=$db->Execute($sqlBilan)){
                    if($resultBilan->RecordCount()){
                        $rowBilan=$resultBilan->FetchRow();
                        $donneesAnalyses=$rowBilan['value'];
                    }
                    else $donneesAnalyses=$ligne['bilan'];
                }
                else $donneesAnalyses=$ligne['bilan'];
            }


            #Recupérer les vaccins effectués
            $sqlVac="SELECT i.name as vaccin FROM care_vaccination_patient v, care_type_immunization i WHERE i.nr=v.vid AND v.pid=".$pid." AND v.effectuee='O' AND v.date_effectuee>='".$date_debut."' AND v.date_effectuee<='".$date_fin."' AND v.type='pediatrie'";
            if($resultVac=$db->Execute($sqlVac)){
            	$donneesVaccins='';
            	//$k=1;
                while($ligneVac=$resultVac->FetchRow()){
                    //$donneesVaccins.=$k++.")".$ligneVac['vaccin']."\n";
                    $donneesVaccins.=$ligneVac['vaccin']."\n";
                }
        
            }     

            #Je recupère ici les constantes
            $donneesMens="";
            $listeConstante=$objMedocsParams->loadDetailSpecialiteConstantes($idSpecialite);
            if($encounterConstantes=$objMeasurement->getMeasurementByPidEncounterNr($nr,'O')){
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

                        for($i=0;$i<count($listeConstante);$i++){
                            
                            $varUnit=$listeConstante[$i]['LD_var']."_unit";
                            $varConstanteVal=$listeConstante[$i]['LD_var']."_val";
                            $donneesMens.=utf8_decode($$listeConstante[$i]['LD_var']).'='.$$varConstanteVal.' '.$$varUnit."\n";
                              
                        }
                    }
                  
                }
            }           
     
            #Je recupère ici les ordonnances
            //$sqlOrd="SELECT s.article as article, s.quantity as quantite,t.name as type, t.LD_var as LDVar FROM care_encounter_prescription p, care_encounter_prescription_sub s, care_type_application t WHERE p.nr=s.prescription_nr AND s.application_type_nr=t.nr AND p.encounter_nr=".$nr; 

            /*$sqlOrd="SELECT distinct(s.article) as article FROM care_encounter_prescription_sub s WHERE prescription_nr IN (SELECT nr FROM care_encounter_prescription WHERE encounter_nr=".$nr.") OR prescription_nr IN(SELECT nr FROM care_encounter_prescription p, care_encounter e WHERE p.encounter_nr=e.pid AND e.encounter_nr=".$nr.") ORDER BY article ";*/
            $sqlOrd="SELECT s.article as article 
                        FROM care_encounter_prescription_sub s, care_encounter_prescription p 
                        WHERE p.nr=s.prescription_nr 
                        AND p.encounter_nr=".$nr;
            /*la ligne qui suit est fait pour que lorsqu'il n'y a aucune ordonnance Ã§a affiche la térapie proposé*/
            //$donneesOrd=$ligne['traitement'];
            $donneesOrd=recupereValueP($nr, 27);
          
            if($resultOrd=$db->Execute($sqlOrd)){
            	$o=0;
                while($ligneOrd=$resultOrd->FetchRow()){
                	$o++;
                  $donneesOrd.=$o.") ".$ligneOrd['article']."\n";
                }
            }
            
            $pdf->Row(array($compteurLigne, date_format($date, 'd-m-Y'), $name, $age,strtoupper($ligne['sexe']),$diagnostic,$donneesAnalyses,$donneesVaccins,$donneesMens,utf8_decode($donneesOrd)));
	      

	    }
	}
}

$pdf->output('registre-pediatrie-du['.$date1.']AU['.$date2.']', 'I');


?>























?>